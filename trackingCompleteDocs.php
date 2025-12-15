<?php

namespace App\Http\Controllers\SST;

use App\Http\Controllers\Controller;
use Illuminate\Database\Connection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Exception;

/**
 * Controlador para el seguimiento y gestión de documentos firmados.
 *
 * Flujo:
 *  - getTrackingDocs($companyId, $module, $subModule) => carga config y modelos
 *  - tracingDocs(...) => arma el tracking por módulo y subitem
 *
 * Características:
 *  - Descubre modelos por submódulo (no se pasan por parámetro).
 *  - Evita "Unknown column" validando tablas/columnas dinámicamente.
 *  - Una consulta por modelo (whereIn) y agrupación por item.
 *  - Toma el ÚLTIMO registro por subitem (entre TODOS los modelos).
 *  - Reglas de firmas:
 *      * Obligatorias solo firmas numeradas: firma1, firma2, ...
 *      * Excepción: si existen 'firma' y 'firma1', y 'firma1' está vacía pero 'firma' tiene valor,
 *        NO se exige 'firma1'. Si 'firma' está vacía, sí se exige 'firma1'.
 *      * Documentos iguales (documento* con mismo valor): basta UNA firma numerada (o la excepción anterior).
 *      * Documentos diferentes: TODAS las firmas numeradas deben estar llenas (respetando la excepción de firma1).
 *  - Si un subitem no tiene registros: false y se agrega detalle.
 *  - Resultado por id_modulo, ordenado alfanuméricamente (SORT_NATURAL).
 */
class trackingCompleteDocs extends Controller
{
    /**
     * Configuración del módulo de tracking (inyectable para pruebas).
     *
     * @var array
     */
    private $config = [];

    /**
     * Varible para almacenar el último log de depuración.
     *
     * @var array
     */
    private $lastDebugLog = array();

    /**
     * Cache simple por petición de columnas de tablas.
     *
     * @var array
     */
    private $columnCache = [];

    /**
     * Constructor.
     *
     * @param array $config
     */
    public function __construct(array $config = array(), array $secondaryDBSignature = array())
    {
        // Carga la config real si no se inyecta una
        $this->config = empty($config) ? config('module.tracking') : $config;
    }

    /**
     * Entrada pública: coordina el proceso para una compañía y módulo dados.
     *
     * @param int    $companyId
     * @param string $module
     * @param string $subModule
     * @return array|false
     */
    public function getTrackingDocs($companyId, $module, $subModule, $subItemsToSkip = array(), $subItemsToDefaultTrue = array())
    {
        $config = $this->getSpecificConfig($module);
        if (empty($config)) {
            Log::warning("Configuración no encontrada para el módulo '$module'");
            return false;
        }

        $models = $this->processToGetModels($config, $subModule);
        if (!$models) {
            return false;
        }
        // dd($this->tracingDocs($models, $config, $companyId, $subModule));

        return $this->tracingDocs($models, $config, $companyId, $subModule, $subItemsToSkip, $subItemsToDefaultTrue);
    }

    /**
     * Construye el tracking por módulo y subitem.
     * - $subItemsToSkip: IDs de subitems que NO deben exigirse (usan modelos con firmas pero no aplican firmas).
     *
     * @param array  $models
     * @param array  $config
     * @param int    $companyId
     * @param string $subModule
     * @param array  $subItemsToSkip  // <-- NUEVO (opcional)
     * @return array
     */
    private function tracingDocs($models, $config, $companyId, $subModule, $subItemsToSkip = array(), $subItemsToDefaultTrue = array())
    {
        $tracing = array();  // [moduloId][subItemId] = bool
        $subitemSnapshots = array();  // [moduloId][subItemId] = detalle SubItem
        $modelList = $models[0][$subModule];

        // --- mini log para depuración (NO va en el retorno) ---
        $debugLog = array();  // [moduloId][subItemId] => info depuración
        $debugLog['__meta'][] = array('nota' => 'current_year_filter', 'year' => date('Y'));

        // 1) Subitems desde la tabla configurada
        $subItems = $this->getSubItems($config['dbItems'][$subModule]);
        if ($subItems->isEmpty()) {
            $this->lastDebugLog = $debugLog;
            return array(
                'totales' => array('subitems_total' => 0, 'faltan_total' => 0),
                'modulos' => array(),
            );
        }

        // 2) Diccionario subitem_id => id_modulo (lookup O(1))
        $subitemsTable = $config['dbItems'][$subModule];
        $mapSubItemToModule = $this->buildSubitemToModuleMap($subitemsTable);

        // 3) Último registro por subitem (entre TODOS los modelos; del año actual)
        $latestBySubItem = $this->collectLatestRecordsBySubItem($modelList, $companyId, $subItems);

        // 4) Evaluar cada subitem (skip, reglas, firmas)
        foreach ($subItems as $subItem) {
            $subItemId = $subItem->id;
            $moduloId = isset($mapSubItemToModule[$subItemId]) ? $mapSubItemToModule[$subItemId] : null;
            $nombreSubItem = isset($subItem->nombre) ? $subItem->nombre : ('SubItem ' . $subItemId);
            $numeralSubItem = isset($subItem->item) ? $subItem->item : null;
            $sec = $this->secondaryEvidenceCheck($companyId, $subItem, $subitemsTable, $moduloId, $subModule);

            if (!$moduloId) {
                // Subitem no mapeado a módulo
                $debugLog['__unmapped'][$subItemId] = array(
                    'mensaje' => 'Subitem sin mapeo a id_modulo',
                    'subitem_id' => $subItemId,
                    'nombre' => $nombreSubItem,
                );
                continue;
            }
            $registro = isset($latestBySubItem[$subItemId]) ? $latestBySubItem[$subItemId] : null;
            // Validacion de firma en "ARRAY"
            $isArraySigned = $this->isRegistroFullySigned($subItemId, $registro->id ?? null, $subModule, $config);

            // === (A) SUBITEMS TO SKIP: ignorar firmas y decidir por existencia (primario o secundaria) con prioridad por recencia
            if (in_array($subItemId, $subItemsToSkip)) {
                // if($subItemId == 63) dd($registro);
                $regTs = isset($registro->created_at) && $registro->created_at ? (string) $registro->created_at : null;
                // A1) Hay primario y secundaria más reciente → TRUE por secundaria
                if ($registro && $sec['ok'] && !empty($sec['ts']) && $regTs && strtotime($sec['ts']) > strtotime($regTs)) {
                    $tracing[$moduloId][$subItemId] = true;

                    $subitemSnapshots[$moduloId][$subItemId] = array(
                        'subitem_id' => $subItemId,
                        'estado' => true,
                        'nombre' => $numeralSubItem . ' ' . $nombreSubItem,
                        'faltantes' => array(),
                        'total_faltantes' => 0,
                        'id_registro' => isset($registro->id) ? $registro->id : null,
                        'id_company' => $companyId,
                        'nota' => 'SKIPPED_secondary_newer_TRUE: ' . $sec['note'],
                        'step' => 'A1',
                    );

                    if (!isset($debugLog[$moduloId]))
                        $debugLog[$moduloId] = array();
                    $debugLog[$moduloId][$subItemId] = array(
                        'modelo' => get_class($registro),
                        'tabla' => method_exists($registro, 'getTable') ? $registro->getTable() : null,
                        'id_registro' => isset($registro->id) ? $registro->id : null,
                        'estado' => true,
                        'faltantes' => array(),
                        'nota' => 'SKIPPED_secondary_newer_TRUE: ' . $sec['note'],
                        'record_ts' => $regTs,
                        'secondary_ts' => $sec['ts'],
                        'step' => 'A1',
                    );
                    continue;  // decisión tomada
                }

                // A2) Con registro primario (año actual) → TRUE (sin validar firmas)
                if ($registro) {
                    if ($isArraySigned) {
                        if (isset($isArraySigned['ok']) && !$isArraySigned['ok'])
                            $tracing[$moduloId][$subItemId] = false;
                    } else {
                        $tracing[$moduloId][$subItemId] = true;
                    }

                    $subitemSnapshots[$moduloId][$subItemId] = array(
                        'subitem_id' => $subItemId,
                        'estado' => true,
                        'nombre' => $numeralSubItem . ' ' . $nombreSubItem,
                        'faltantes' => array(),
                        'total_faltantes' => 0,
                        'id_registro' => isset($registro->id) ? $registro->id : null,
                        'id_company' => $companyId,
                        'nota' => 'SKIPPED_only_record_presence',
                        'step' => 'A2',
                    );

                    if (!isset($debugLog[$moduloId]))
                        $debugLog[$moduloId] = array();
                    $debugLog[$moduloId][$subItemId] = array(
                        'modelo' => get_class($registro),
                        'tabla' => method_exists($registro, 'getTable') ? $registro->getTable() : null,
                        'id_registro' => isset($registro->id) ? $registro->id : null,
                        'estado' => true,
                        'faltantes' => array(),
                        'nota' => 'SKIPPED_only_record_presence',
                        'step' => 'A2',
                    );
                    continue;  // decisión tomada
                }

                // A3) Sin registro primario pero con secundaria → TRUE
                if (!$registro && $sec['ok']) {
                    $tracing[$moduloId][$subItemId] = true;

                    $subitemSnapshots[$moduloId][$subItemId] = array(
                        'subitem_id' => $subItemId,
                        'estado' => true,
                        'nombre' => $numeralSubItem . ' ' . $nombreSubItem,
                        'faltantes' => array(),
                        'total_faltantes' => 0,
                        'id_registro' => null,
                        'id_company' => $companyId,
                        'nota' => 'SKIPPED_secondary_only_TRUE: ' . $sec['note'],
                        'step' => 'A3',
                    );

                    if (!isset($debugLog[$moduloId]))
                        $debugLog[$moduloId] = array();
                    $debugLog[$moduloId][$subItemId] = array(
                        'modelo' => null,
                        'tabla' => null,
                        'id_registro' => null,
                        'estado' => true,
                        'faltantes' => array(),
                        'nota' => 'SKIPPED_secondary_only_TRUE: ' . $sec['note'],
                        'secondary_ts' => $sec['ts'] ?? null,
                        'step' => 'A3',
                    );
                    continue;  // decisión tomada
                }

                // A4) No hay registro ni secundaria → FALSE
                $tracing[$moduloId][$subItemId] = false;

                $subitemSnapshots[$moduloId][$subItemId] = array(
                    'subitem_id' => $subItemId,
                    'estado' => false,
                    'nombre' => $numeralSubItem . ' ' . $nombreSubItem,
                    'faltantes' => array('registro no encontrado (año actual)'),
                    'total_faltantes' => 1,
                    'id_registro' => null,
                    'id_company' => $companyId,
                    'nota' => 'SKIPPED_no_record_current_year',
                    'step' => 'A4',
                );

                if (!isset($debugLog[$moduloId]))
                    $debugLog[$moduloId] = array();
                $debugLog[$moduloId][$subItemId] = array(
                    'modelo' => null,
                    'tabla' => null,
                    'id_registro' => null,
                    'estado' => false,
                    'faltantes' => array('registro no encontrado (año actual)'),
                    'nota' => 'SKIPPED_no_record_current_year',
                    'step' => 'A4',
                );
                continue;  // no validar nada más para SKIP
            }

            // === (B) Sin registro principal y con secundaria → marcar TRUE por secundaria
            if (!$registro && $sec['ok']) {
                $tracing[$moduloId][$subItemId] = true;

                $subitemSnapshots[$moduloId][$subItemId] = array(
                    'subitem_id' => $subItemId,
                    'estado' => true,
                    'nombre' => $numeralSubItem . ' ' . $nombreSubItem,
                    'faltantes' => array(),
                    'total_faltantes' => 0,
                    'id_registro' => null,
                    'id_company' => $companyId,
                    'nota' => 'SECONDARY_ONLY_TRUE: ' . $sec['note'],
                    'step' => 'B',
                );

                if (!isset($debugLog[$moduloId]))
                    $debugLog[$moduloId] = array();
                $debugLog[$moduloId][$subItemId] = array(
                    'modelo' => null,
                    'tabla' => null,
                    'id_registro' => null,
                    'estado' => true,
                    'faltantes' => array(),
                    'nota' => 'SECONDARY_ONLY_TRUE: ' . $sec['note'],
                    'secondary_ts' => $sec['ts'] ?? null,
                    'step' => 'B',
                );

                continue;  // no validar nada más
            }

            // === (C) Hay registro y secundaria MÁS nueva → priorizar secundaria
            if ($registro && $sec['ok'] && !empty($sec['ts'])) {
                $regTs = null;
                if (isset($registro->created_at) && $registro->created_at) {
                    $regTs = (string) $registro->created_at;
                }
                if ($regTs && strtotime($sec['ts']) > strtotime($regTs)) {
                    $tracing[$moduloId][$subItemId] = true;

                    $subitemSnapshots[$moduloId][$subItemId] = array(
                        'subitem_id' => $subItemId,
                        'estado' => true,
                        'nombre' => $numeralSubItem . ' ' . $nombreSubItem,
                        'faltantes' => array(),
                        'total_faltantes' => 0,
                        'id_registro' => isset($registro->id) ? $registro->id : null,
                        'id_company' => $companyId,
                        'nota' => 'SECONDARY_NEWER_TRUE: ' . $sec['note'],
                        'step' => 'C',
                    );
                    if (!isset($debugLog[$moduloId]))
                        $debugLog[$moduloId] = array();
                    $debugLog[$moduloId][$subItemId] = array(
                        'modelo' => get_class($registro),
                        'tabla' => method_exists($registro, 'getTable') ? $registro->getTable() : null,
                        'id_registro' => isset($registro->id) ? $registro->id : null,
                        'estado' => true,
                        'faltantes' => array(),
                        'nota' => 'SECONDARY_NEWER_TRUE: ' . $sec['note'],
                        'record_ts' => $regTs,
                        'secondary_ts' => $sec['ts'],
                        'step' => 'C',
                    );
                    continue;  // ya decidido
                }
            }

            // === (D) DEFAULT TRUE por configuración
            if (in_array($subItemId, $subItemsToDefaultTrue)) {
                $tracing[$moduloId][$subItemId] = true;

                $subitemSnapshots[$moduloId][$subItemId] = array(
                    'subitem_id' => $subItemId,
                    'estado' => true,
                    'nombre' => $numeralSubItem . ' ' . $nombreSubItem,
                    'faltantes' => array(),
                    'total_faltantes' => 0,
                    'id_registro' => null,
                    'id_company' => $companyId,
                    'nota' => 'FORCED_TRUE_by_config',
                    'step' => 'D',
                );

                if (!isset($debugLog[$moduloId]))
                    $debugLog[$moduloId] = array();
                $debugLog[$moduloId][$subItemId] = array(
                    'modelo' => null,
                    'tabla' => null,
                    'id_registro' => null,
                    'estado' => true,
                    'faltantes' => array(),
                    'nota' => 'FORCED_TRUE_by_config',
                    'step' => 'D',
                );
                continue;  // no validar nada más
            }

            // --- (E) NO SKIP ---

            // Sin registro del año actual → FALSE
            if (!$registro) {
                $tracing[$moduloId][$subItemId] = false;

                $subitemSnapshots[$moduloId][$subItemId] = array(
                    'subitem_id' => $subItemId,
                    'estado' => false,
                    'nombre' => $numeralSubItem . ' ' . $nombreSubItem,
                    'faltantes' => array('registro no encontrado (año actual)'),
                    'total_faltantes' => 1,
                    'id_registro' => null,
                    'id_company' => $companyId,
                    'nota' => 'NO_RECORD_current_year',
                    'step' => 'E',
                );

                if (!isset($debugLog[$moduloId]))
                    $debugLog[$moduloId] = array();
                $debugLog[$moduloId][$subItemId] = array(
                    'modelo' => null,
                    'tabla' => null,
                    'id_registro' => null,
                    'estado' => false,
                    'faltantes' => array('registro no encontrado (año actual)'),
                    'nota' => 'NO_RECORD_current_year',
                    'step' => 'E',
                );
                continue;
            }

            // Con registro: si NO tiene campos de firma → TRUE directo (no exige)
            if (!$this->recordHasSignatureFields($registro)) {
                // Si el registro está firmado en el array, no se considera como no firmado
                if ($isArraySigned) {
                    if (isset($isArraySigned['ok']) && !$isArraySigned['ok'])
                        $tracing[$moduloId][$subItemId] = false;
                } else {
                    $tracing[$moduloId][$subItemId] = true;
                }

                $subitemSnapshots[$moduloId][$subItemId] = array(
                    'subitem_id' => $subItemId,
                    'estado' => isset($isArraySigned['missing']) && $isArraySigned['missing'] == 'firma vacía' ? false : true,
                    'nombre' => $numeralSubItem . ' ' . $nombreSubItem,
                    'faltantes' => array(),
                    'total_faltantes' => 0,
                    'id_registro' => isset($registro->id) ? $registro->id : null,
                    'id_company' => $companyId,
                    'nota' => 'RECORD_without_signature_fields_AUTO_TRUE',
                    'step' => 'E DIRECT',
                );

                if (!isset($debugLog[$moduloId]))
                    $debugLog[$moduloId] = array();
                $debugLog[$moduloId][$subItemId] = array(
                    'modelo' => get_class($registro),
                    'tabla' => method_exists($registro, 'getTable') ? $registro->getTable() : null,
                    'id_registro' => isset($registro->id) ? $registro->id : null,
                    'estado' => true,
                    'faltantes' => array(),
                    'nota' => 'RECORD_without_signature_fields_AUTO_TRUE',
                    'step' => 'E DIRECT',
                );
                continue;
            }

            // Con registro y SÍ tiene campos de firma → aplicar reglas de firmas (docs iguales/distintos + excepción 'firma'/'firma1')
            $eval = $this->evaluateSignaturesWithRules($registro);

            if ($isArraySigned) {
                if (isset($isArraySigned['ok']) && !$isArraySigned['ok'])
                    $tracing[$moduloId][$subItemId] = false;
            } else {
                $tracing[$moduloId][$subItemId] = $eval['ok'];
            }

            $subitemSnapshots[$moduloId][$subItemId] = array(
                'subitem_id' => $subItemId,
                'estado' => $isArraySigned['ok']  ?? $eval['ok'],
                'nombre' => $numeralSubItem . ' ' . $nombreSubItem,
                'faltantes' => $eval['missing'],
                'total_faltantes' => count($eval['missing']),
                'id_registro' => isset($registro->id) ? $registro->id : null,
                'id_company' => $companyId,
                'step' => 'E WITH RULES',
            );

            if (!isset($debugLog[$moduloId]))
                $debugLog[$moduloId] = array();
            $debugLog[$moduloId][$subItemId] = array(
                'modelo' => get_class($registro),
                'tabla' => method_exists($registro, 'getTable') ? $registro->getTable() : null,
                'id_registro' => isset($registro->id) ? $registro->id : null,
                'estado' => $isArraySigned['ok']  ?? $eval['ok'],
                'faltantes' => $eval['missing'],
                'step' => 'E WITH RULES',
            );
        }

        // 5) Consolidar estado por módulo + totales globales
        $consolidated = array();
        $totalSubitems = 0;  // evaluados (mapeados; incluye skips)
        $totalPendientes = 0;  // estado=false

        foreach ($subitemSnapshots as $moduloId => $subitemsDetail) {
            $faltantesIds = array();
            $subitemsFaltantes = array();
            $allOk = true;

            foreach ($subitemsDetail as $sid => $snap) {
                $totalSubitems++;
                if (!$snap['estado']) {
                    $allOk = false;
                    $faltantesIds[] = $sid;
                    $subitemsFaltantes[$sid] = $snap;
                    $totalPendientes++;
                }
            }

            $consolidated[$moduloId] = array(
                'estado' => $allOk,
                'faltantes_subitems' => $faltantesIds,
                'subitems_faltantes' => $subitemsFaltantes,
                'subitems' => $subitemsDetail,
            );
        }

        // 6) Orden alfanumérico por id_modulo
        ksort($consolidated, SORT_NATURAL);

        // 7) Guardar mini log para depurar
        $this->lastDebugLog = $debugLog;

        // 8) Retorno con totales globales + módulos
        return array(
            'totales' => array(
                'subitems_total' => $totalSubitems,
                'faltan_total' => $totalPendientes,
            ),
            'modulos' => $consolidated,
        );
    }

    /**
     * Determina si un REGISTRO (no la tabla) tiene campos de firma:
     * - 'firma' (base) o
     * - alguna 'firmaN' (firma1, firma2, ...).
     *
     * @param \Illuminate\Database\Eloquent\Model $record
     * @return bool
     */
    private function recordHasSignatureFields($record)
    {
        if (!$record)
            return false;

        $attrs = $record->getAttributes();
        foreach ($attrs as $k => $v) {
            if (strcasecmp($k, 'firma') === 0) {
                return true;
            }
            if (preg_match('/^firma\d+$/i', $k)) {
                return true;
            }
        }
        return false;
    }

    /**
     * Intenta obtener el ciclo (1=planear, 2=hacer, 3=verificar, 4=actuar)
     * desde el subitem o su tabla.
     */
    private function guessCicloForSubitem($subItem, $subitemsTable)
    {
        // 1) Si el subItem ya trae 'ciclo'
        if (is_object($subItem) && isset($subItem->ciclo) && $subItem->ciclo !== null && $subItem->ciclo !== '') {
            return (int) $subItem->ciclo;
        }

        // 2) Intentar leerlo de la tabla, si existe una columna 'ciclo'
        if (Schema::hasTable($subitemsTable)) {
            $cols = $this->getCachedColumns($subitemsTable);
            if (in_array('ciclo', $cols)) {
                $row = DB::table($subitemsTable)->select('ciclo')->where('id', $subItem->id)->first();
                if ($row && isset($row->ciclo)) {
                    return (int) $row->ciclo;
                }
            }
        }

        // 3) Sin información
        return null;
    }

    /**
     * Segundo análisis: busca evidencia externa para marcar el subitem como TRUE.
     * 1) file_upsst + lineabasal + subitemsTable (por numeral y $subModule → accion)
     * 2) Si no encuentra:
     *    - $subModule = 'hacer'     → updoc_hacer (por id_diagnostico/id_item)
     *    - $subModule = 'verificar' → updoc_verificar
     *    - $subModule = 'actuar'    → updoc_actuar
     *    - $subModule = 'planear'   → no hay updoc_planear (solo file_upsst)
     *
     * @param int    $companyId
     * @param object $subItem        (de la tabla de subitems)
     * @param string $subitemsTable  (tabla config['dbItems'][$subModule])
     * @param mixed  $moduloId
     * @param string $subModule      ('planear'|'hacer'|'verificar'|'actuar'...)
     * @return array ['ok' => bool, 'note' => string]
     */
    private function secondaryEvidenceCheck($companyId, $subItem, $subitemsTable, $moduloId, $subModule)
    {
        $currentYear = date('Y');

        // Mapear $subModule → número de acción para file_upsst (1 planear, 2 hacer, 3 verificar, 4 actuar)
        $sm = strtolower(trim((string) $subModule));
        $cicloNum = null;
        if ($sm === 'planear')
            $cicloNum = 1;
        elseif ($sm === 'hacer')
            $cicloNum = 2;
        elseif ($sm === 'verificar')
            $cicloNum = 3;
        elseif ($sm === 'actuar')
            $cicloNum = 4;

        // -------------------- Leer numeral real del subItem desde su tabla --------------------
        $siCols = Schema::hasTable($subitemsTable) ? $this->getCachedColumns($subitemsTable) : array();
        $siIdCol = 'id';
        $siItemCol = in_array('item', $siCols, true) ? 'item' : (in_array('numeral', $siCols, true) ? 'numeral' : null);

        $numeralValue = null;
        if (Schema::hasTable($subitemsTable) && $siItemCol) {
            $rowSi = DB::table($subitemsTable)
                ->select($siItemCol)
                ->where($siIdCol, $subItem->id)
                ->first();
            if ($rowSi && isset($rowSi->{$siItemCol})) {
                $numeralValue = $rowSi->{$siItemCol};
            }
        }

        // -------------------- file_upsst + lineabasal (por numeral + company + (acción) + año) --------------------
        $fuTable = 'file_upsst';
        $lbTable = 'lineabasal';

        $lbCols = Schema::hasTable($lbTable) ? $this->getCachedColumns($lbTable) : array();
        $lbIdCol = in_array('id', $lbCols, true) ? 'id' : null;
        $lbNumeralCol = in_array('id_numeral', $lbCols, true) ? 'id_numeral' : (in_array('numeral', $lbCols, true) ? 'numeral' : null);

        $fuCols = Schema::hasTable($fuTable) ? $this->getCachedColumns($fuTable) : array();
        $fuCompanyCol = in_array('id_company', $fuCols, true) ? 'id_company' : (in_array('company_id', $fuCols, true) ? 'company_id' : null);
        $fuAccionCol = in_array('accion', $fuCols, true) ? 'accion' : null;
        $fuLineaBCol = in_array('id_lineaB', $fuCols, true) ? 'id_lineaB' : (in_array('linea_b_id', $fuCols, true) ? 'linea_b_id' : null);
        $fuHasCreated = in_array('created_at', $fuCols, true);

        if (
            $numeralValue !== null &&
            Schema::hasTable($fuTable) &&
            Schema::hasTable($lbTable) &&
            $lbIdCol &&
            $lbNumeralCol &&
            $fuCompanyCol &&
            $fuLineaBCol
        ) {
            $q = DB::table($fuTable . ' as fu')
                ->join($subitemsTable . ' as si', 'si.' . $siIdCol, '=', 'fu.' . $fuLineaBCol)
                ->join($lbTable . ' as lb', 'lb.' . $lbIdCol, '=', 'si.' . 'id_modulo')
                ->where('fu.' . $fuCompanyCol, $companyId)
                ->where('fu.' . $fuLineaBCol, $subItem->id);

            // Filtrar por acción SOLO si tenemos el mapeo por $subModule
            if ($fuAccionCol && $cicloNum !== null) {
                $q->where('fu.' . $fuAccionCol, (int) $cicloNum);
            }

            // Año actual (si la tabla tiene created_at)
            if ($fuHasCreated) {
                $q->whereYear('fu.created_at', $currentYear);
            }

            // Fecha mas reciente
            $ts = null;
            if ($fuHasCreated) {
                $ts = (clone $q)->max('fu.created_at');
            }

            if ($q->exists()) {
                return array('ok' => true, 'note' => 'SECONDARY_FOUND_in_file_upsst', 'ts' => $ts);
            }
        }

        // -------------------- updoc_* por $subModule (hacer/verificar/actuar) --------------------
        $docTable = null;
        if ($sm === 'hacer')
            $docTable = 'updoc_hacer';
        elseif ($sm === 'verificar')
            $docTable = 'updoc_verificar';
        elseif ($sm === 'actuar')
            $docTable = 'updoc_actuar';
        // planear u otros: no hay updoc_planear → no se consulta docTable

        if ($docTable && Schema::hasTable($docTable)) {
            $docCols = $this->getCachedColumns($docTable);
            $docDiagCol = in_array('id_diagnostico', $docCols, true) ? 'id_diagnostico' : (in_array('id_item', $docCols, true) ? 'id_item' : null);
            $docCompanyCol = in_array('id_company', $docCols, true) ? 'id_company' : (in_array('company_id', $docCols, true) ? 'company_id' : null);
            $docHasCreated = in_array('created_at', $docCols, true);

            if ($docDiagCol && $docCompanyCol) {
                $dq = DB::table($docTable)
                    ->where($docCompanyCol, $companyId)
                    ->where($docDiagCol, $subItem->id);

                // Año actual (si existe created_at)
                if ($docHasCreated) {
                    $dq->whereYear('created_at', $currentYear);
                }

                // Fecha mas reciente
                $ts = null;
                if ($docHasCreated) {
                    $ts = (clone $dq)->max('created_at');
                };

                if ($dq->exists()) {
                    return array('ok' => true, 'note' => 'SECONDARY_FOUND_in_' . $docTable, 'ts' => $ts);
                }
            }
        }

        // Nada encontrado con criterios estrictos
        return array('ok' => false, 'note' => 'SECONDARY_NOT_FOUND', 'ts' => null);
    }

    /**
     * Construye un mapa subitem_id => id_modulo leyendo la tabla de subitems.
     * (Evita consultas por subitem y acelera el mapeo.)
     *
     * @param string $table
     * @return array
     */
    private function buildSubitemToModuleMap($table)
    {
        $map = array();

        if (!Schema::hasTable($table)) {
            return $map;
        }

        $rows = DB::table($table)->select('id', 'id_modulo')->get();
        foreach ($rows as $row) {
            $map[$row->id] = $row->id_modulo;
        }

        return $map;
    }

    /**
     * Devuelve el ÚLTIMO registro (del AÑO ACTUAL) por subItem entre TODOS los modelos.
     * Reglas:
     *  - Omite modelos con tablas en blacklist: v3PlanearArray, v3_rutpdf (case-insensitive).
     *  - Omite modelos cuya tabla tenga la columna 'id_principal' (tablas secundarias).
     *  - Requiere 'created_at' para filtrar por año actual.
     *  - Requiere columna de empresa: id_company | company_id.
     *  - Requiere columna de subItem: id_item | id_diagnostico.
     *  - Devuelve el más reciente por subitem dentro del año actual (created_at DESC, fallback id DESC).
     *
     * @param array $modelList        Lista de clases de modelos Eloquent
     * @param int   $companyId
     * @param \Illuminate\Support\Collection $subItems  (debe tener ->pluck('id'))
     * @return array  [subItemId => \Illuminate\Database\Eloquent\Model]
     */
    private function collectLatestRecordsBySubItem(array $modelList, $companyId, $subItems)
    {
        $currentYear = date('Y');
        $latest = array();
        $subItemIds = $subItems->pluck('id')->all();

        // Tablas a excluir (case-insensitive)
        $blacklist = array('v3planeararray', 'v3_rutpdf');

        foreach ($modelList as $modelClass) {
            /** @var \Illuminate\Database\Eloquent\Model $model */
            $model = new $modelClass();
            $table = $model->getTable();
            $tableKey = strtolower($table);

            // 0) Excluir por nombre de tabla (blacklist)
            if (in_array($tableKey, $blacklist, true)) {
                continue;
            }

            // 1) Validar existencia de tabla
            if (!Schema::hasTable($table)) {
                continue;
            }

            // 2) Leer columnas y excluir si tiene 'id_principal'
            $cols = $this->getCachedColumns($table);
            if (in_array('id_principal', $cols, true)) {
                // Es una tabla secundaria: omitir
                continue;
            }

            // 3) Columnas mínimas requeridas
            $companyCol = in_array('id_company', $cols, true) ? 'id_company' : (in_array('id_empresa', $cols, true) ? 'id_empresa' : (in_array('company_id', $cols, true) ? 'company_id'   : null));

            $subItemCol = in_array('id_item', $cols, true) ? 'id_item' : (in_array('id_diagnostico', $cols, true) ? 'id_diagnostico' : null);

            $idCol = in_array('id', $cols, true) ? 'id' : null;

            $hasCreated = in_array('created_at', $cols, true);

            if (!$companyCol || !$subItemCol || !$hasCreated || !$idCol) {
                // Falta algún requisito crítico → omitir este modelo
                continue;
            }

            // 4) Query: empresa + subItems + AÑO ACTUAL, ordenado por más reciente
            $rows = DB::table($table)
                ->where($companyCol, $companyId)
                ->whereIn($subItemCol, $subItemIds)
                ->whereYear('created_at', $currentYear)
                ->select([$subItemCol . ' as __sub_id', $table . '.*'])
                ->orderByDesc('created_at')
                ->orderByDesc('id')
                ->get();

            // 5) Elegir el primer visto por cada subItem (más nuevo por el orden)
            foreach ($rows as $row) {
                $sid = (int) $row->__sub_id;
                if (!isset($latest[$sid])) {
                    // Hidratar como modelo Eloquent
                    $latest[$sid] = $model->newFromBuilder((array) $row);
                }
            }
        }

        return $latest;
    }

    /**
     * La tabla tiene columnas de firmas si:
     *  - Existe al menos una columna que cumpla /^firma\d+$/i (firma1, firma2, ...)
     *  - O existe la columna 'firma' (base).
     */
    private function tableHasSignatureColumns(array $columns)
    {
        foreach ($columns as $col) {
            if (preg_match('/^firma\d+$/i', $col)) {
                return true;
            }
            if (strcasecmp($col, 'firma') === 0) {
                return true;
            }
        }
        return false;
    }

    /**
     * Comprueba si todas las filas relacionadas del registro están firmadas.
     *
     * @param int $itemId       // key en $secondaryBDSignature
     * @param int $registroId   // id del registro principal
     * @param array $sources    // lista de "fuentes" a chequear. Cada item puede ser:
     *                           - string (nombre de tabla, busca por columna registro_id)
     *                           - callable (function($conn, $registroId) : Builder) -> devuelve Query Builder
     * @param string $registroColumn // nombre de la columna que relaciona (default 'registro_id')
     * @param string $firmaColumn    // nombre de la columna firma (default 'firma')
     * @return bool
     */
    public function isRegistroFullySigned(
        $itemId,
        $registroId,
        $subModule,
        $config
    ) {
        if (!$registroId) {
            return array('ok' => false, 'missing' => array('registro_id no encontrado'));
        }

        $itemId_val = $subModule == 'planear' ? 1 : $itemId;

        $secondaryBDSignature = $config['secondaryDBSignature'][$subModule] ?? [];

        if (!array_key_exists($itemId_val, $secondaryBDSignature)) {
            // No existe conexión para ese item
            return array('ok' => false, 'missing' => array('item no encontrado'));
        }

        $connectionName = $secondaryBDSignature[$itemId_val][0] ?? null;
        $registroCandidates = $secondaryBDSignature[$itemId_val][1] ?? [];
        $fieldItemString = $secondaryBDSignature[$itemId_val][2] ?? '';

        $attrs = DB::Table($connectionName)
            ->where($registroCandidates, $registroId)
            ->where($fieldItemString, $itemId)
            ->get();

        // 1) Comprobar si la tabla tiene columna "firma"
        $hasFirma = false;
        $cols = $this->getCachedColumns($connectionName);
        if ($this->tableHasSignatureColumns($cols)) {
            $hasFirma = true;
        }

        if (!$hasFirma) {
            return array('ok' => false, 'missing' => array('tabla sin firma'));
        }

        // Comprobar si el campo firma está llena
        foreach ($attrs as $record) {
            if (!empty($record->firma)) {
                continue;
            }
            return array('ok' => false, 'missing' => array('Firma vacía'));
        }

        return array('ok' => true, 'Done' => array('Firma llena'));
    }

    /**
     * Evalúa un registro "último por subitem" con reglas de firmas y documentos.
     * Reglas:
     *  - Si todos los documento* no vacíos son iguales => basta 1 firma numerada llena (o 'firma' cubre 'firma1').
     *  - Si difieren (o no hay documentos no vacíos) => TODAS las firmas numeradas deben estar llenas
     *    (con excepción: 'firma' base cubre 'firma1' vacía).
     *  - 'firma' (base) NUNCA es obligatoria por sí sola (no se lista como faltante).
     *  - Si no existen firmas numeradas en el registro => ok=true (no hay obligación).
     *
     * @param \Illuminate\Database\Eloquent\Model $record
     * @return array ['ok' => bool, 'missing' => string[]]
     */
    private function evaluateSignaturesWithRules($record)
    {
        if (!$record) {
            return array('ok' => false, 'missing' => array('registro no encontrado'));
        }

        $attrs = $record->getAttributes();

        // 1) Detectar claves relevantes
        $docKeys = $this->extractDocumentKeys($attrs);  // ['documento', 'documento1', ...]
        $firmaKeys = $this->extractNumberedSignatureKeys($attrs);  // ['firma1','firma2',...]
        $hasFirma = array_key_exists('firma', array_change_key_case($attrs, CASE_LOWER));  // 'firma' base

        // Si no hay firmas numeradas en el registro => no hay obligación
        if (empty($firmaKeys)) {
            return array('ok' => true, 'missing' => array());
        }

        // 2) Normalizar valores de documentos y decidir si "todos iguales"
        $docVals = $this->pluckNonEmptyValues($attrs, $docKeys);  // solo no vacíos
        $allDocsEqual = $this->allValuesEqual($docVals);  // true si 0<unique<=1

        // 3) Normalizar firmas numeradas y detectar llenas
        $filledMap = array();  // firmaN => bool
        foreach ($firmaKeys as $fk) {
            $filledMap[$fk] = $this->isFilled($this->valueOf($attrs, $fk));
        }

        // 4) Excepción: 'firma' base puede cubrir 'firma1' si existe y está vacía
        $firmaBaseFilled = $this->isFilled($this->valueOf($attrs, 'firma'));
        if ($hasFirma && in_array('firma1', $firmaKeys, true) && !$filledMap['firma1'] && $firmaBaseFilled) {
            $filledMap['firma1'] = true;  // cubierta por 'firma'
        }

        // 5) Decisión según documentos
        if ($allDocsEqual && !empty($docVals)) {
            // Caso "docs iguales": basta UNA firma numerada llena (considerando excepción)
            $anySigned = false;
            foreach ($firmaKeys as $fk) {
                if ($filledMap[$fk]) {
                    $anySigned = true;
                    break;
                }
            }

            if ($anySigned) {
                return array('ok' => true, 'missing' => array());
            } else {
                // Señalar TODAS las numeradas como faltantes (las no llenas)
                $missing = array();
                foreach ($firmaKeys as $fk) {
                    if (!$filledMap[$fk])
                        $missing[] = $fk;
                }
                return array('ok' => false, 'missing' => $missing);
            }
        } else {
            // Caso "docs distintos" (o todos vacíos): TODAS las numeradas deben estar llenas
            $missing = array();
            foreach ($firmaKeys as $fk) {
                if (!$filledMap[$fk])
                    $missing[] = $fk;
            }
            return array('ok' => empty($missing), 'missing' => $missing);
        }
    }

    /**
     * Cache simple por petición del listado de columnas de una tabla.
     *
     * @param string $table
     * @return array
     */
    private function getCachedColumns($table)
    {
        if (!isset($this->columnCache[$table])) {
            $this->columnCache[$table] = Schema::getColumnListing($table);
        }
        return $this->columnCache[$table];
    }

    /**
     * Devuelve la primera columna existente entre un conjunto posible.
     * (Compatible con PHP 5.6/7.0: sin types de retorno ni nullables.)
     *
     * @param array $possible
     * @param array $available
     * @return string|null
     */
    private function detectColumn(array $possible, array $available)
    {
        foreach ($possible as $p) {
            if (in_array($p, $available)) {
                return $p;
            }
        }
        return null;
    }

    /**
     * Obtiene y mapea las clases modelo disponibles para un sub-módulo específico.
     *
     * @param string $routeModels Ruta relativa desde app/ hasta la carpeta de modelos
     * @param string $subModule   Identificador del sub-módulo en la configuración
     * @return array [ $subModule => [FQCN_Model1, FQCN_Model2, ...] ]
     */
    private function getModelToDocs($routeModels, $subModule)
    {
        try {
            $modelFolderPath = app_path($routeModels);
            if (!is_dir($modelFolderPath)) {
                throw new Exception("La carpeta de modelos no existe: $modelFolderPath");
            }

            $namespace = str_replace('/', '\\', trim($routeModels, '/'));
            $modelFiles = glob($modelFolderPath . '/*.php');
            if (!$modelFiles) {
                return array($subModule => array());
            }

            $models = array();
            foreach ($modelFiles as $filePath) {
                $className = pathinfo($filePath, PATHINFO_FILENAME);
                $fullClassName = "App\\$namespace\\$className";
                // class_exists requiere que el autoloader conozca la clase
                if (is_readable($filePath) && class_exists($fullClassName)) {
                    $models[] = $fullClassName;
                }
            }

            return array($subModule => $models);
        } catch (Exception $e) {
            Log::error("Error obteniendo modelos para '$subModule': " . $e->getMessage());
            return array($subModule => array());
        }
    }

    /**
     * Procesa y estructura los modelos según la configuración del módulo.
     *
     * @param array  $config
     * @param string $subModule
     * @return array|false
     */
    private function processToGetModels($config, $subModule)
    {
        $models = array();
        $result = $this->getModelToDocs($config['basePath'] . $subModule, $subModule);
        if (!empty($result)) {
            $models[] = $result;
        }

        return !empty($models) ? $models : false;
    }

    /**
     * Devuelve todos los subitems desde la tabla configurada.
     *
     * @param string $table
     * @return \Illuminate\Support\Collection
     */
    private function getSubItems($table)
    {
        return DB::table($table)->get();
    }

    /**
     * Obtiene la configuración específica de un módulo.
     *
     * @param string $module
     * @return array
     */
    private function getSpecificConfig($module)
    {
        return isset($this->config[$module]) ? $this->config[$module] : array();
    }

    /**
     * Obtiene el último log de depuración generado durante el proceso de tracking.
     *
     * Este método devuelve información detallada sobre el estado de cada subitem
     * procesado, incluyendo el modelo utilizado, tabla, ID de registro, estado
     * final y elementos faltantes. Es útil para debugging y análisis del flujo
     * de evaluación de firmas.
     *
     * @return array Array asociativo con estructura:
     */
    public function getLastDebugLog()
    {
        return $this->lastDebugLog;
    }

    /* ============================ HELPERS ============================ */

    /**
     * Retorna el valor de una clave (case-insensitive) en attrs o null si no existe.
     */
    private function valueOf(array $attrs, $key)
    {
        $low = array_change_key_case($attrs, CASE_LOWER);
        $k = strtolower($key);

        return array_key_exists($k, $low) ? $low[$k] : null;
    }

    /**
     * ¿Valor "relleno"? (no null y trim != "")
     */
    private function isFilled($val)
    {
        if ($val === null)
            return false;
        // Convertir a string (por si llegan enteros/booleans)
        $s = is_string($val) ? $val : strval($val);
        return trim($s) !== '';
    }

    /**
     * Extrae claves 'documento' y 'documentoN' presentes en attrs, ordenadas por sufijo numérico.
     * Solo devuelve claves que EXISTEN en attrs.
     */
    private function extractDocumentKeys(array $attrs)
    {
        $keys = array();
        foreach ($attrs as $k => $v) {
            $lk = strtolower($k);
            if ($lk === 'documento') {
                $keys[] = 'documento';
            } elseif (preg_match('/^documento(\d+)$/i', $lk, $m)) {
                $keys[] = 'documento' . $m[1];
            }
        }
        // Orden natural por sufijo: documento, documento1, documento2, ...
        natcasesort($keys);
        return array_values($keys);
    }

    /**
     * Extrae claves 'firmaN' presentes en attrs, ordenadas por N ascendente.
     * Solo devuelve claves que EXISTEN en attrs.
     */
    private function extractNumberedSignatureKeys(array $attrs)
    {
        $nums = array();
        foreach ($attrs as $k => $v) {
            $lk = strtolower($k);
            if (preg_match('/^firma(\d+)$/i', $lk, $m)) {
                $nums[(int) $m[1]] = 'firma' . $m[1];
            }
        }
        ksort($nums, SORT_NUMERIC);
        return array_values($nums);
    }

    /**
     * Devuelve solo los valores NO vacíos de las claves dadas (case-insensitive), normalizados (trim).
     */
    private function pluckNonEmptyValues(array $attrs, array $keys)
    {
        $out = array();
        foreach ($keys as $k) {
            $val = $this->valueOf($attrs, $k);
            if ($this->isFilled($val)) {
                $out[] = trim((string) $val);
            }
        }
        return $out;
    }

    /**
     * true si hay 0 < unique(values) <= 1 (es decir, todos los no-vacíos son iguales).
     * Si no hay valores (array vacío) => false (se trata como "docs distintos/indefinidos").
     */
    private function allValuesEqual(array $values)
    {
        if (empty($values))
            return false;
        $uniq = array_values(array_unique($values, SORT_STRING));
        return count($uniq) <= 1;
    }
}
