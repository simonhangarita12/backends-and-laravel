<?php

namespace App\Http\Controllers\Excel;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\input;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithTitle;

use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Models\Debito\Debito;
use App\Models\Debito\Facturacion;
use App\Models\RequisitosLegales\RequisitoLegal;
use App\Models\RequisitosLegales\Criterio;
use App\Models\RequisitosLegales\tbl_int_legales;
use App\Models\RequisitosLegales\RequisitoCriterio;
use App\Models\Company\Company;
use App\Models\Company\CaliServiClte;
use App\Models\Company\Opinion;
use App\Models\Company\Rol;
use App\Models\Company\Arl;
use App\Models\Company\Catriesgo;
use App\Models\Company\Pais;
use App\Models\Company\Ciudades;
use App\Models\Company\Region;
use App\Models\Company\Skype;
use App\Models\Company\Actividad_economica;
use App\Models\Company\Areas;
use App\Models\Company\AreaOther;
use App\Models\Company\ArchivoRSS;
use App\Models\Company\FileCompany;
use App\Models\CompanyAncla\CompanyAncla;
use App\Models\CompanyAncla\ArlAncla;
use App\Models\SST\LineaBasal_total;
use App\Models\Contratos\Contrato;
use App\Models\Contratos\NewClient;
use App\Models\Epp\pedidoEpp;
use App\Models\Epp\epp_IntStock;
use App\Models\epp\ElementosEmpresa;
use App\Models\epp\ElementosPedidos;
use App\Models\epp\ElementosDetalle;
use App\Models\epp\PartesProteccion;
use App\Models\epp\PreciosElementos;
use App\Models\Epp\epp_stock_Hist_Epp;
use App\Models\Usuarios\FDAP_DatosContacto;
use App\Models\Usuarios\PerfilSocioDemo;
use App\Models\Usuarios\ArchivosPerfil;
use App\Models\Usuarios\AuxiliarDotacion;
use App\Models\Usuarios\SalRieCal;
use App\Models\Usuarios\DatosEmpPer;
use App\Models\Usuarios\NivelEstudios;
use App\Models\AusentismoLaboral\AusentismoLaboral;
use App\Models\AusentismoLaboral\Prorroga;
use App\Models\SVE\Inferior\RiesgoInferior;
use App\Models\Company\cajacompensacion;
use App\Models\Cardio\CalculadoraCardio;
use App\Models\Planes\PlanesModulosClientes;
use App\Models\Planes\Planes;
use App\User;
use Sentinel;


class ExcelController extends Controller
{

	//trae campos de seleccionado en excel.

	//EXCEL PARA MÓDULO DE DEBITO

	public function index()
	{

		Excel::create('Debito Excel', function ($excel) {

			$excel->sheet('Novedades', function ($sheet) {

				//$debitos = Debito::all();
				$debitos = Debito::orderBy('id', 'DESC')->where('estado', '=', 2)->get();

				$sheet->row(1, [
					/*'Posición','Nit', 'Nombre', 'Número de cuenta', 'Valor', 'Fecha debito','Teléfono','Ciudad'*/]);
				foreach ($debitos as $index => $debito) {
					$sheet->row($index + 2, [
						$debito->cc_titular,
						$debito->nombre_titular,
						$debito->numero_cuenta,
						$debito->tipo_transacion,
						$debito->referencia1,
						$debito->referencia2,
						$debito->fecha_novedad,
						$debito->tipo_novedad
					]);
				}
			});
		})->export('xls');
	}

	// FIN EXCEL PARA MÓDULO DE DEBITO

	//EXCEL PARA  DE SKYPE LLAMADAS ANALISTAS

	public function ExportSkype()
	{

		Excel::create('Skype Excel', function ($excel) {

			$excel->sheet('ctl_llamadas', function ($sheet) {

				//$debitos = Debito::all();
				$skype = DB::table('skype')
					->select('skype.idSky', 'skype.id_company', 'skype.id_asesor', 'skype.inicio', 'skype.finalizar', 'skype.observaciones', 'skype.skypeCliente', 'skype.estado', 'company.id', 'company.razonsocial', 'company.contactoSST', 'company.teleContactoSST', 'company.emailContactoSST', 'users.id', 'users.name', 'users.last_name', 'users.role_id')
					->leftjoin('company', 'company.id', '=', 'skype.id_company')
					->leftjoin('users', 'users.id', '=', 'skype.id_asesor')
					->where([['skype.estado', '=', 1], ['users.role_id', '=', 6]])
					->get();

				$sheet->row(1, [
					'Asesor',
					'Empresa',
					'Inicio conexión',
					'Finalización conexión',
					'Observaciones durante la conexión',
					'Contacto SST',
					'Skype SST'
				]);
				foreach ($skype as $index => $ExportSkype) {
					$sheet->row($index + 2, [
						$ExportSkype->name,
						$ExportSkype->razonsocial,
						$ExportSkype->inicio,
						$ExportSkype->finalizar,
						$ExportSkype->observaciones,
						$ExportSkype->contactoSST,
						$ExportSkype->skypeCliente,
						$ExportSkype->id_company
					]);
				}
			});
		})->export('xls');
	}

	//FIN EXCEL PARA  DE SKYPE LLAMADAS ANALISTAS

	//EXCEL PARA  DE SUBIR FACTURAS

	public function indexFactura()
	{

		Excel::create('Facturacion', function ($excel) {

			$excel->sheet('Facturacion', function ($sheet) {

				//$debitos = Debito::all();
				$debitos = Debito::orderBy('id', 'DESC')->where('estado', '=', 2)->get();

				$sheet->row(1, [
					'Nit Pagador ',
					'Nombre Pagador',
					'Valor transacción',
					'Referencia1 ',
					'Referencia 2',
					'Fecha vencimiento o aplicación',
					'Periodos facturados',
					'Ciclo'
				]);
				foreach ($debitos as $index => $debito) {
					$sheet->row($index + 2, [
						$debito->cc_titular,
						$debito->nombre_titular,
						$debito->valor_transacion,
						$debito->referncia1,
						$debito->refencia2,
						$debito->fecha_vencimiento,
						$debito->periodos_facturados,
						$debito->ciclo
					]);
				}
			});
		})->export('xls');
	}

	//FIN EXCEL PARA  DE SUBIR FACTURAS

	//EXCEL PARA  DE COBROS
	public function indexCobros()
	{

		Excel::create('Cobros', function ($excel) {

			$excel->sheet('Cobros', function ($sheet) {

				//$debitos = Debito::all();
				$debitos = Debito::orderBy('id', 'DESC')->where('estado', '=', 2)->get();

				$sheet->row(1, [
					/*'Nit Pagador ', 'Nombre Pagador','Banco cuenta del pagador','número de cuenta a debitar','Tipo transacción', 'Valor transacción','Indicador validación','Referencia1 ','Referencia 2','Fecha vencimiento o aplicación','Periodos facturados','Ciclo'*/]);
				foreach ($debitos as $index => $debito) {
					$sheet->row($index + 2, [
						$debito->cc_titular,
						$debito->nombre_titular,
						$debito->banco_pagador,
						$debito->numero_cuenta,
						$debito->tipo_transacion,
						$debito->valor_transacion,
						$debito->indicador_validacion,
						$debito->referencia1,
						$debito->referencia2,
						$debito->fecha_vencimiento_control,
						$debito->periodos_facturados,
						$debito->ciclo
					]);
				}
			});
		})->export('xls');
	}


	//EXCEL PARA  DE exportar EMPRESAS

	public function indexCompany()
	{
		Excel::create('Empresa', function ($excel) {
			$excel->sheet('Empresas', function ($sheet) {
				/*$company =Company:: orderBy('id','DESC')->where('estado','=',1)->get();
				 */

				 $company = DB::table('company')
				 ->select([
					 'company.id',
					 'company.razonsocial',
					 'company.nit',
					 'company.contacto',
					 'company.nume_empleados',
					 'company.estado',
					 'company.gerente',
					 'company.teleGerente',
					 'company.emailGerente',
					 'company.contactoSST',
					 'company.id_asesor',
					 'company.teleContactoSST',
					 'company.emailContactoSST',
					 'company.id_arl',
					 'company.categoria',
					 'arl.nombre as nombreArl',
					 'contrato.fecha_contrato',
					 'contrato.horasContrato',
					 'contrato.horasContratoMes',
					 'contrato.valorContrato',
					 'contrato.numEmpleados',
					 'contrato.id_tipo_contrato',
					 'contrato.ip_firma',
					 'contrato.tipoService',
					 'contrato.tipo_factura',
					 'ciudades.idCiudad',
					 'ciudades.nombre as nombreCiudad',
					 'contrato.observaciones',
					 'planes.nombre as nombrePlanes',
					 'users.name',
					 'users.last_name',
					 'NewClientSeg.timeContrato'
				 ])
				 ->leftJoin('users', 'users.id', '=', 'company.id_asesor')
				 ->leftJoin('ciudades', 'ciudades.idCiudad', '=', 'company.id_ciudad')
				 ->leftJoin('contrato', function($join) {
					 $join->on('contrato.id_empresa', '=', 'company.id')
						  ->where('contrato.estado', '=', 1);
				 })
				 ->leftJoin('arl', 'arl.id', '=', 'company.id_arl')
				 ->join('planesModulosClientes', function($join) {
					 $join->on('planesModulosClientes.id_company', '=', 'company.id')
						  ->whereIn('planesModulosClientes.id_plan', [1, 3])
						  ->where('planesModulosClientes.estado', '=', 1);
				 })
				 ->leftJoin('planes', 'planes.id', '=', 'planesModulosClientes.id_plan')
				 ->leftJoin('NewClientSeg', 'NewClientSeg.id_company', '=', 'company.id')
				 ->orderBy('company.razonsocial')
				 ->groupBy('company.id') 
				 ->get();

				$sheet->row(1, [
					'Razón Social',
					'Nit',
					'Ciudad',
					'Riesgo',
					'Representante legal',
					'Correo de representante',
					'Teléfono de representante',
					'Número de trabajadores vinculados',
					'Contacto SST',
					'Correo Contacto SST',
					'ARL',
					'Fecha contrato',
					'Tipo de facturación',
					'Tipo de plan',
					'Tiempo de contrato',
					'Tipo de servicio',
					'Num conexiones',
					'Valor contrato',
					'contrato con firma',
					'Asesor',
					'estado',
					'Observaciones'
				]);
				foreach ($company as $index => $companys) {


					switch ($companys->tipoService) {
						case 1:
							$tipoService = "Virtual";
							break;
						case 2:
							$tipoService = "Presencial";
							break;
						case 3:
							$tipoService = "Venta de servicios";
							break;
						case 4:
							$tipoService = "Mixto";
							break;
						default:
						    $tipoService = "Sin datos";
							break;
					}

					switch ($companys->tipo_factura) {
						case 1:
							$tipoFactura = "Anual";
							break;				
						case 2:
							$tipoFactura = "Mensual";
							break;
						default:
						    $tipoFactura = "Sin dato";
							break;
					}

					switch ($companys->timeContrato) {
						case 1:
							$timeContrato = "Tiempo indefinido";
							break;
						case 2:
							$timeContrato = "Tiempo fijo";
							break;
						default:
						$timeContrato = "Sin dato";
							break;
					}

					switch ($companys->estado) {
						case 5:
							$estado = "Suspensión cartera";
							break;
						case 2:
							$estado = "Inactiva";
							break;
						case 1:
							$estado = "Activo";
							break;
						default:
							$estado = "Sin dato";
							break;
					}

					if($companys->ip_firma == null){
						$ip_firma = "No disponible";
					}else{
						$ip_firma = "Contrato firmado desde la IP: " . $companys->ip_firma;
					}

					$sheet->row($index + 2, [
						$companys->razonsocial,
						$companys->nit,
						$companys->nombreCiudad,
						$companys->categoria,
						$companys->contacto,
						$companys->emailGerente,
						$companys->teleGerente,
						$companys->nume_empleados,
						$companys->contactoSST,	
						$companys->emailContactoSST,
						$companys->nombreArl,
						$companys->fecha_contrato,
						$tipoFactura,
						$companys->nombrePlanes,
						$timeContrato,
						$tipoService,
						$companys->horasContrato,
						$companys->valorContrato,
						$ip_firma,
						$companys->name . ' ' . $companys->last_name,
						$estado,
						$companys->observaciones,


					]);
				}
			});
		})->export('xls');
	}

	// FIN EXCEL PARA  exportar EMPRESAS

	//Carga de empresas

	public function ImportCias(Request $request)
	{

		Excel::load($request->upCias, function ($reader) {
			$company = $reader->get();
			$reader->each(function ($row) {
				Company::create([
					'razonsocial' => $row->razon_social,
					'nit' => $row->nit,
					'gerente' => $row->gerente,
					'emailGerente' => $row->email_gerente,
					'telefono' => $row->telefono_contacto,
					'activityCode' => $row->codigo_actividad,
					'categoria' => $row->categoria,
					'contactoSST' => $row->contacto_sst,
					'teleContactoSST' => $row->telefono_contacto_sst,
					'emailContactoSST' => $row->email_contacto_sst,
					'nume_empleados' => $row->numero_trabajadores,
					'tipo_empresa' => $row->tipo_empresa,
					'estado' => 1
				]);
				//Se consulta la última empresa creada para realizar registro en la tabla file_company
				$comp = Company::select('id')->orderby('created_at', 'DESC')->first();
				$idCompany = $comp->id;

				$user = Sentinel::registerAndActivate([
					'name' => $row->gerente,
					'email' => $row->email_gerente,
					'password' => $row->cedula_gerente,
					'cargo' => 'Gerente',
					'num_documento' => $row->cedula_gerente,
					'tipo_documento' => 13,
					'company_id' => $idCompany,
					'role_id' => 2,
					'estado' => 1
				]);

				$role = Sentinel::findRoleById(2);
				$role->users()->attach($user);

				// Insertamos los datos del plan con sus respectivos modulos
				$planesCliente = new PlanesModulosClientes;
				$planesCliente->create([
					'id_company' => $idCompany,
					'id_plan' => 13,
					'id_modulo' => '["4","5"]',
					'estado' => 1
				]);
			});
		});
		$companys = DB::table('company')
			->select(
				'company.id',
				'company.razonsocial',
				'company.id_ciudad',
				'company.direccion',
				'company.nit',
				'company.id_contrato',
				'company.id_propuesta',
				'company.categoria',
				'company.tipo_empresa',
				'company.estado',
				'company.id_tipo_contrato',
				'ciudades.nombre as nombreCiudad',
				'propuesta_psico.filePropuesta',
				'lineabasal_total.planearTotal',
				'lineabasal_total.hacerTotal',
				'lineabasal_total.verificarTotal',
				'lineabasal_total.actuarTotal',
				'lineabasal_total.totalTest',
				'tipo_de_contrato.id_tipoContrato',
				'tipo_de_contrato.tipoContrato',
				'planesModulosClientes.id_plan'
			)
			->leftjoin('lineabasal_total', 'lineabasal_total.id_company', '=', 'company.id')
			->leftjoin('pais', 'pais.id', '=', 'company.id_pais')
			->leftjoin('region', 'region.idRegion', '=', 'company.id_region')
			->leftjoin('ciudades', 'ciudades.idCiudad', '=', 'company.id_ciudad')
			->leftjoin('cat_riesgos', 'cat_riesgos.id', 'company.cat_riesgos')
			->leftjoin('arl', 'arl.id', '=', 'company.id_arl')
			->leftjoin('contrato', 'contrato.id_contrato', '=', 'company.id_contrato')
			->leftjoin('propuesta_psico', 'propuesta_psico.id_propuesta', '=', 'company.id_PropuestaPsico')
			->leftjoin('users', 'users.id', '=', 'company.id_asesor')
			->leftjoin('tipo_de_contrato', 'tipo_de_contrato.id_tipoContrato', '=', 'company.id_tipo_contrato')
			->leftjoin('planesModulosClientes', 'planesModulosClientes.id_company', '=', 'company.id')
			->where('company.estado', '=', 1)
			->get();
		$arl = FileCompany::all();
		$Rss = ArchivoRSS::all();
		$planes = Planes::orderBy('nombre', 'ASC')->select('nombre', 'id')->get();
		return \View::make('company/listcompany', compact('companys', 'arl', 'Rss', 'planes'));
		echo "Importación correcta";
	}


	//fin de carga de empresas



	//EXCEL PARA LISTADO CONTRATISTAS

	public function indexContratista($id_company)
	{

		Excel::create('Contratistas', function ($excel) use ($id_company) {
			$excel->sheet('Contratistas', function ($sheet) use ($id_company) {
				$company = DB::table('company')
					->select(
						'company.id',
						'company.razonsocial',
						'company.nit',
						'company.contacto',
						'company.nume_empleados',
						'company.estado',
						'company.id_ancla',
						'company.gerente',
						'company.teleGerente',
						'company.emailGerente',
						'company.contactoSST',
						'company.teleContactoSST',
						'company.emailContactoSST',
						'company.id_arl',
						'lineabasal_total.id_company',
						'lineabasal_total.planearTotal',
						'lineabasal_total.hacerTotal',
						'lineabasal_total.verificarTotal',
						'lineabasal_total.actuarTotal',
						'lineabasal_total.totalTest',
						'arl.nombre as nombreArl'
					)
					->leftjoin('lineabasal_total', 'lineabasal_total.id_company', '=', 'company.id')
					->leftjoin('arl', 'arl.id', '=', 'company.id_arl')
					->where([['company.estado', '=', 1], ['company.id_ancla', $id_company]])
					->get();
				$sheet->row(1, [
					'Razón Social',
					'Nit',
					'Representante legal',
					'Número de trabajadores vinculados',
					'Gerente',
					'Teléfono Gerente',
					'Correo Gerente',
					'Contacto SST',
					'Teléfono Contacto SST',
					'Correo Contacto SST',
					'ARL',
					'% Planear',
					'% Hacer',
					'% Verificar',
					'% Actuar',
					'% Total Cumplimiento',

				]);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera
				foreach ($company as $index => $companys) {
					$sheet->row($index + 2, [
						$companys->razonsocial,
						$companys->nit,
						$companys->contacto,
						$companys->nume_empleados,
						$companys->gerente,
						$companys->teleGerente,
						$companys->emailGerente,
						$companys->contactoSST,
						$companys->teleContactoSST,
						$companys->emailContactoSST,
						$companys->nombreArl,
						$companys->planearTotal,
						$companys->hacerTotal,
						$companys->verificarTotal,
						$companys->actuarTotal,
						$companys->totalTest
					]);
				}
			});
		})->export('xls');
	}

	//EXCEL PARA  SUBIR CONTRATISTAS
	public function ImportContratista(Request $request)
	{

		//dd($request);
		$id_ancla = $request->id_ancla;
		$estado = $request->estado;
		Excel::load($request->contratista, function ($reader) use ($id_ancla, $estado) {
			$contratista = $reader->get();
			$reader->each(function ($row) use ($id_ancla, $estado) {
				Company::create([
					'razonsocial' => $row->razon_social,
					'nit' => $row->nit,
					'contacto' => $row->representante_legal,
					'nume_empleados' => $row->num_trabajadores,
					'gerente' => $row->gerente,
					'teleGerente' => $row->tel_gerente,
					'emailGerente' => $row->correo_gerente,
					'contactoSST' => $row->contacto_sst,
					'teleContactoSST' => $row->tel_contacto_sst,
					'emailContactoSST' => $row->correo_contacto_sst,
					'id_ancla' => $id_ancla,
					'estado' => $estado
				]);
			});
		});
		$company = DB::table('company_ancla')
			->select('company_ancla.id_company', 'company_ancla.razonSocial', 'company_ancla.nit', 'company_ancla.id_pais', 'company_ancla.id_region', 'company_ancla.id_ciudad', 'company_ancla.direccion', 'company_ancla.telefono', 'company_ancla.email', 'company_ancla.contacto', 'company_ancla.logo', 'company_ancla.rut', 'company_ancla.camaraComercio', 'company_ancla.documento', 'company_ancla.cat_riesgos', 'company_ancla.id_arl', 'company_ancla.numEmpleados', 'company_ancla.id_asesor', 'company_ancla.skype', 'company_ancla.empleadosTemp', 'company_ancla.activityCode', 'company_ancla.activityName', 'company_ancla.activityCodeSeg', 'company_ancla.activityNameSeg', 'company_ancla.id_contrato', 'company_ancla.id_propuesta', 'company_ancla.aliaSkype', 'company_ancla.gerente', 'company_ancla.teleGerente', 'company_ancla.emailGerente', 'company_ancla.contactoSST', 'company_ancla.teleContactoSST', 'company_ancla.emailContactoSST', 'company_ancla.id_PropuestaPsico', 'company_ancla.nivelRiesgo', 'company_ancla.categoria', 'company_ancla.estado', 'pais.paisnombre', 'region.nombre as nombreRegion', 'ciudades.nombre as nombreCiudad', 'cat_riesgos.name as nombreCategoria', 'arl.nombre as nombreArl', 'contrato.fileContrato', 'propuesta_psico.filePropuesta', 'users.name', 'users.last_name')
			->leftjoin('pais', 'pais.id', '=', 'company_ancla.id_pais')
			->leftjoin('region', 'region.idRegion', '=', 'company_ancla.id_region')
			->leftjoin('ciudades', 'ciudades.idCiudad', '=', 'company_ancla.id_ciudad')
			->leftjoin('cat_riesgos', 'cat_riesgos.id', 'company_ancla.cat_riesgos')
			->leftjoin('arl', 'arl.id', '=', 'company_ancla.id_arl')
			->leftjoin('contrato', 'contrato.id_contrato', '=', 'company_ancla.id_contrato')
			->leftjoin('propuesta_psico', 'propuesta_psico.id_propuesta', '=', 'company_ancla.id_PropuestaPsico')
			->leftjoin('users', 'users.id', '=', 'company_ancla.id_asesor')
			->where('company_ancla.estado', '=', 1)
			->get();

		$arl = ArlAncla::all();
		return view('CompanyAncla.listCompanyAncla', compact('company', 'arl'));
		echo "Importación correcta";
	}

	//FIN EXCEL SUBIR CONTRATISTAS

	// CONTROL DE CALIDAD DEL ANALISTA
	public function indexControlAsesor()
	{
		Excel::create('ControlAnalista', function ($excel) {
			$excel->sheet('ControlAnalistas', function ($sheet) {

				$caliServi = DB::table('caliServiClte')
					->select('caliServiClte.idServiClte', 'caliServiClte.id_asesor', 'caliServiClte.id_company', 'caliServiClte.encuestador', 'caliServiClte.total', 'caliServiClte.created_at', 'caliServiClte.p1', 'caliServiClte.p2', 'caliServiClte.p3', 'company.id as companyId', 'company.razonsocial', 'company.id_asesor', 'users.id as usersId', 'users.name')
					->leftjoin('company', 'caliServiClte.id_company', '=', 'company.id')
					->leftjoin('users', 'caliServiClte.id_asesor', '=', 'users.id')
					->where('caliServiClte.estado', '=', 1)
					->get();
				//dd($caliServi);
				$sheet->row(1, [
					'Analista',
					'Empresa',
					'Satisfecho',
					'Amabilidad',
					'EAvance Gestión',
					'Encuestador',
					'Fecha encuesta ',
					'total calificación'
				]);
				foreach ($caliServi as $index => $lct) {
					$sheet->row($index + 2, [
						$lct->name,
						$lct->razonsocial,
						$lct->p1,
						$lct->p2,
						$lct->p3,
						$lct->encuestador,
						$lct->created_at,
						$lct->total
					]);
				}
			});
		})->export('xls');
	}

	// FIN CONTROL DE CALIDAD DEL ANALISTA

	// CONTROL DE OPINION	

	public function indexOpinion()
	{
		Excel::create('Opinion', function ($excel) {
			$excel->sheet('Opinions', function ($sheet) {
				$opinion = Opinion::orderBy('id', 'DESC')->get();
				//dd($opinion);
				$sheet->row(1, [
					'Número Opinion',
					'Email',
					'Empresa',
					'Servicio',
					'Opinión Servicio',
					'Facturación',
					'Opinión Facturación',
					'Cartera',
					'Opinión Cartera',
					'Servicio al cliente',
					'Opinión Servicio al Cliente',
					'Plataforma',
					'Renovación',
					'Valoracion',
					'Estado'
				]);
				foreach ($opinion as $index => $opinions) {
					if ($opinions->estado == "1") {
						$estado = 'Activo';
					} else {
						$estado = 'Inactiva';
					}
					switch ($opinions->servicio) {
						case 5:
							$servicio = "Excelente";
							break;
						case 4:
							$servicio = "Bueno";
							break;
						case 3:
							$servicio = "Regular";
							break;
						case 2:
							$servicio = "Malo";
							break;
						case 1:
							$servicio = "Muy malo";
							break;
						default:
							$servicio = "Sin dato";
							break;
					}

					switch ($opinions->facturacion) {
						case 5:
							$facturacion = "Excelente";
							break;
						case 4:
							$facturacion = "Bueno";
							break;
						case 3:
							$facturacion = "Regular";
							break;
						case 2:
							$facturacion = "Malo";
							break;
						case 1:
							$facturacion = "Muy malo";
							break;
						default:
							$facturacion = "Sin datos";
							break;
					}

					switch ($opinions->cartera) {
						case 5:
							$cartera = "Excelente";
							break;
						case 4:
							$cartera = "Bueno";
							break;
						case 3:
							$cartera = "Regular";
							break;
						case 2:
							$cartera = "Malo";
							break;
						case 1:
							$cartera = "Muy malo";
							break;
						default:
							$cartera = "Sin datos";
							break;
					}

					switch ($opinions->servicioclie) {
						case 5:
							$servicioclie = "Excelente";
							break;
						case 4:
							$servicioclie = "Bueno";
							break;
						case 3:
							$servicioclie = "Regular";
							break;
						case 2:
							$servicioclie = "Malo";
							break;
						case 1:
							$servicioclie = "Muy malo";
							break;
						default:
							$servicioclie = "Sin datos";
							break;
					}
					$sheet->row($index + 2, [
						$opinions->id,
						$opinions->email,
						$opinions->empresa,
						$servicio,
						$opinions->serviciopinion,
						$facturacion,
						$opinions->facturaopinion,
						$cartera,
						$opinions->carteraopinion,
						$servicioclie,
						$opinions->servicioclieopinion,
						$opinions->plataforma,
						$opinions->renovacion,
						$opinions->valoracion,
						$estado
					]);
				}
			});
		})->export('xls');
	}

	//FIN CONTROL DE OPINION

	// // CONTROL DE CALIDAD DEL SERVICIO
	public function filtroExcel(Request $request)
	{
		$dato = $request->input('acumulador');
		Excel::create('Opinion', function ($excel) use ($dato) {
			$excel->sheet('Opinions', function ($sheet) use ($dato) {
				$sheet->row(1, [
					'Número Opinion',
					'Email',
					'Empresa',
					'Servicio',
					'Opinión Servicio',
					'Facturación',
					'Opinión Facturación',
					'Cartera',
					'Opinión Cartera',
					'Servicio al cliente',
					'Opinión Servicio al Cliente',
					'Plataforma',
					'Renovación',
					'Valoracion'
				]);


				$opinion = DB::table('opinion')->where('estado', '=', $dato)->get();
				foreach ($opinion as $index => $value) {
					switch ($value->servicio) {
						case 5:
							$servicio = "Excelente";
							break;
						case 4:
							$servicio = "Bueno";
							break;
						case 3:
							$servicio = "Regular";
							break;
						case 2:
							$servicio = "Malo";
							break;
						case 1:
							$servicio = "Muy malo";
							break;
						default:
							$servicio = "Sin dato";
							break;
					}

					switch ($value->facturacion) {
						case 5:
							$facturacion = "Excelente";
							break;
						case 4:
							$facturacion = "Bueno";
							break;
						case 3:
							$facturacion = "Regular";
							break;
						case 2:
							$facturacion = "Malo";
							break;
						case 1:
							$facturacion = "Muy malo";
							break;
						default:
							$facturacion = "Sin datos";
							break;
					}

					switch ($value->cartera) {
						case 5:
							$cartera = "Excelente";
							break;
						case 4:
							$cartera = "Bueno";
							break;
						case 3:
							$cartera = "Regular";
							break;
						case 2:
							$cartera = "Malo";
							break;
						case 1:
							$cartera = "Muy malo";
							break;
						default:
							$cartera = "Sin datos";
							break;
					}

					switch ($value->servicioclie) {
						case 5:
							$servicioclie = "Excelente";
							break;
						case 4:
							$servicioclie = "Bueno";
							break;
						case 3:
							$servicioclie = "Regular";
							break;
						case 2:
							$servicioclie = "Malo";
							break;
						case 1:
							$servicioclie = "Muy malo";
							break;
						default:
							$servicioclie = "Sin datos";
							break;
					}
					$sheet->row($index + 2, [

						$value->id,
						$value->email,
						$value->empresa,
						$servicio,
						$value->serviciopinion,
						$facturacion,
						$value->facturaopinion,
						$cartera,
						$value->carteraopinion,
						$servicioclie,
						$value->servicioclieopinion,
						$value->plataforma,
						$value->renovacion,
						$value->valoracion

					]);
				}
			});
		})->export('xls');
	}
	// // FIN CONTROL DE CALIDAD DEL SERVICIO

	//importar facturas/

	public function Import(Request $request)
	{
		Excel::load($request->factura, function ($reader) {
			$factura = $reader->get();
			$reader->each(function ($row) {
				Facturacion::create([
					'nit' => $row->nit,
					'name' => $row->cliente,
					'fvencimiento' => $row->fvencimiento,
					'nfactura' => $row->factura,
					'valor' => $row->valor,
					'fecha_pago' => $row->fechapago,
					'estado' => $row->estado

				]);
			});
		});
		$factura = Facturacion::all();
		return \View::make('debito/facturacion/listfactura', compact('factura'));
		echo "Importación correcta";
	}

	//modifificar el estado de pago de la factura
	public function ImportUpdate(Request $request)
	{

		Excel::load($request->factura, function ($reader) {
			$factura = $reader->get();
			$reader->each(function ($row) {

				Facturacion::where('nfactura', $row->factura)->update([
					'estado' => $row->estado,
					'fecha_pago' => $row->fechapago
				]);
			});
		});
		$factura = Facturacion::all();
		return \View::make('debito/facturacion/listfactura', compact('factura'));
		echo "Importación correcta";
	}

	//EVENTOS SUBIDA DE CLIENTES PROSPESTOS

	public function importEvento(Request $request)
	{

		Excel::load($request->evento, function ($reader) {
			$evento = $reader->get();
			$reader->each(function ($lct) {
				$user = Sentinel::registerAndActivate([
					'empresa' => $lct->empresa,
					'name' => $lct->nombre,
					'cargo' => $lct->cargo,
					'email' => $lct->email,
					'telefono' => $lct->telefono,
					'numEmpleados' => $lct->empleados,
					'evento' => $lct->evento,
					'estado' => 5,
					'cliente_id' => $lct->comercial,
					'role_id' => 0,
					'password' => "@123456",
					'company_id' => 200
				]);
			});
		});
		$evento = User::orderBy('id', 'ASC')->where('estado', 5)->get();
		return \View::make('users/eventos/listusersEventos', compact('evento'));
		echo "Importación correcta";
	}

	//FIN EVENTOS SUBIDA DE CLIENTES PROSPESTOS

	// SUBIDA DE USUARIOS

	public function importUsers(Request $request)
	{
		//dd($request);

		Excel::load($request->upUsers, function ($reader) {
			$upUsers = $reader->get();
			$reader->each(function ($lct) {
				//$contrasena =$lct->documento;
				$user = Sentinel::registerAndActivate([
					'email' => $lct->email,
					'password' => $lct->documento,
					'name' => $lct->nombre,
					'last_name' => $lct->apellido,
					'direccion' => $lct->direccion,
					'telefono' => $lct->telefono,
					'tipo_documento' => 13,
					'num_documento' => $lct->documento,
					'company_id' => $lct->company_id,
					'cargo' => $lct->cargo,
					'genero' => $lct->genero,
					'estrato' => $lct->estrato,
					'nivel_estudio' => $lct->estudios,
					'role_id' => 10,
					'estado' => 1,
					'id_pais' => 82
				]);
				$role = Sentinel::findRoleById(10);
				$role->users()->attach($user);
			});
		});
		$pais = Pais::orderBy('paisnombre', 'ASC')->get();
		$ciudads = Ciudades::all();
		$depars = Region::all();
		$company = new Company;
		$role = new Rol;
		$role = Rol::orderBy('id')->where(['estado' => 1])->get();
		$user = User::orderBy('id', 'DESC')->get();

		/*	if((Sentinel::inRole('2'))){
		$company=\DB::table('company')
		->select('company.id','company.razonsocial','users.id as idUser','users.company_id','users.name','users.last_name','users.direccion','users.id_ciudad','users.id_pais','users.id_region','users.tipo_documento','users.num_documento','users.estado','users.email','users.telefono','users.role_id','users.skype','users.alias_skype','users.fecha_nacimiento','users.genero','users.estrato','users.cargo','users.nivel_estudio','users.created_at')
		->join ('users','company_id','=','company.id')
		-> where('company.id','=',Sentinel::getUser()->company_id )
		->where('users.estado',1)
		->get();
		}
		$id_company=\DB::table('company')
		->select('id')
		->where('id','=',Sentinel::getUser()->company_id )
		->get();
		foreach ($id_company as $value) {
		$company_id=$value->id;
		}*/

		return route('\home');
		echo "Importación correcta";
	}

	// FIN SUBIDA DE USUARIOS

	// *********EXCEL DE SVE*********//
	// *********Excel de resultado principal**********//
	public function exportResultado()
	{
		Excel::create('ResultadoSVE', function ($excel) {

			$excel->sheet('ResultadoSVE', function ($sheet) {

				$id_company = Sentinel::getUser()->company_id;
				$promedioUser = DB::table('sve_resultado_global')
					->select(
						'sve_resultado_global.id_user',
						'sve_resultado_global.fechaRevisionTest',
						'sve_resultado_global.created_at',
						'users.name',
						'users.last_name',
						'users.num_documento',
						'users.cargo',
						DB::raw("ROUND(AVG(sve_resultado_global.resultadoTest),1) as total")
					)
					->leftjoin('users', 'sve_resultado_global.id_user', '=', 'users.id')
					->where('id_company', $id_company)
					->groupBy('users.id')
					->get();



				$sheet->row(1, [
					'Nombre',
					'Número de documento',
					'Cargo',
					'Nivel Riesgo',
					'Fecha de realización',
					'Fecha próxima revisión'
				]);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				foreach ($promedioUser as $index => $result) {
					$riesgo = '';
					if ($result->total >= 0 && $result->total <= 25) {
						$riesgo = 'Riesgo Leve';
					} else if ($result->total >= 26 && $result->total <= 55) {
						$riesgo = 'Riesgo Medio';
					} else if ($result->total >= 56 && $result->total <= 80) {
						$riesgo = 'Riesgo Alto';
					} else if ($result->total >= 81) {
						$riesgo = 'Riesgo Grave';
					}
					$sheet->row($index + 2, [
						$result->name . " " . $result->last_name,
						$result->num_documento,
						$result->cargo,
						$riesgo,
						$result->created_at,
						$result->fechaRevisionTest

					]);
				}
			});
		})->export('xls');
	}

	public function exportResultado1($id_user)
	{


		Excel::create('ResultadoSVE1', function ($excel) use ($id_user) {

			$excel->sheet('ResultadoSVE1', function ($sheet) use ($id_user) {

				$total1 = 0;
				$total2 = 0;
				$total3 = 0;
				$total4 = 0;
				$total5 = 0;
				$ano = date('Y');

				$result = DB::table('sve_resultado_global')
					->select('sve_resultado_global.id_user', 'sve_resultado_global.resultadoTest as total1', 'sve_resultado_global.created_at', 'users.name', 'users.last_name', 'users.num_documento', 'users.cargo', 'company.razonsocial')
					->leftjoin('users', 'sve_resultado_global.id_user', '=', 'users.id')
					->leftjoin('company', 'company.id', '=', 'sve_resultado_global.id_company')
					->where([['sve_resultado_global.id_user', $id_user], ['sve_resultado_global.test', 2]])
					->get();

				// Resultado de riesgo individual

				foreach ($result as $value1) {
					$total1 = $value1->total1;
					$razonsocial = $value1->razonsocial;
					$nombre = $value1->name;
					$apellido = $value1->last_name;
					$cargo = $value1->cargo;
					$documento = $value1->num_documento;
					$fecha = $value1->created_at;
				}

				// Resultado de riesgo psicosocial
				$result2 = DB::table('sve_resultado_global')
					->select('resultadoTest as total2')
					->where([['id_user', $id_user], ['test', 1]])
					->get();

				foreach ($result2 as $value2) {
					$total2 = $value2->total2;
				}

				// Resultado de signos y sintomas clinicos
				$result3 = DB::table('sve_resultado_global')
					->select('resultadoTest as total3')
					->where([['id_user', $id_user], ['test', 3]])
					->get();

				foreach ($result3 as $value3) {
					$total3 = $value3->total3;
				}


				// Resultado de predictor de discapacidades
				$result4 = DB::table('sve_resultado_global')
					->select('resultadoTest as total4')
					->where([['id_user', $id_user], ['test', 4]])
					->get();

				foreach ($result4 as $value4) {
					$total4 = $value4->total4;
				}

				// Resultado de instrumentos frymoyer
				$result5 = DB::table('sve_resultado_global')
					->select('resultadoTest as total5')
					->where([['id_user', $id_user], ['test', 5]])
					->get();

				foreach ($result5 as $value5) {
					$total5 = $value5->total5;
				}


				$sheet->row(1, [
					'Empresa',
					'Empleado',
					'Documento',
					'Cargo',
					'Riesgo individual',
					'Estrés laboral',
					'Signos y Síntomas Clinicos',
					'Predictor de discapacidad',
					'Instrumentos sintomáticos Frymoyer',
					'Fecha de evaluación'
				]);

				$sheet->row(2, [
					$razonsocial,
					$nombre . ' ' . $apellido,
					$documento,
					$cargo,
					$value1->total1,
					$total2,
					$total3,
					$total4,
					$total5,
					$fecha
				]);
			});
		})->export('xls');
	}

	public function reporteSindrome()
	{
		Excel::create('Reporte de sindromes', function ($excel) {

			// Hoja 1(Tunel del carpo)
			$excel->sheet('Tunel del carpo', function ($sheet) {
				$id_company = Sentinel::getUser()->company_id;
				// Consulta para usuarios con sibdrome de tunel del carpo
				$sCarpo = DB::table('sve_respuestas_control1')
					->select('sve_respuestas_control1.created_at', 'users.id', 'users.name', 'users.last_name', 'users.num_documento', 'users.cargo', 'users1.name as asesor_name', 'users1.last_name as asesor_last', DB::raw("ROUND(AVG(sve_resultado_global.resultadoTest),1) as total"))
					->leftjoin('users', 'sve_respuestas_control1.id_user', '=', 'users.id')

					->leftjoin('sve_respuestas_control3', 'users.id', '=', 'sve_respuestas_control3.id_user')
					->leftjoin('sve_resultado_control', 'users.id', '=', 'sve_resultado_control.id_user')
					->leftjoin('sve_resultado_global', 'users.id', '=', 'sve_resultado_global.id_user')
					->leftjoin('users as users1', 'sve_resultado_control.id_auditorSST', '=', 'users1.id')
					->where([
						['sve_respuestas_control1.id_company', $id_company],
						['sve_respuestas_control1.p4', 1],
						['sve_respuestas_control1.p9', 1],
						['sve_respuestas_control3.p4', 1]
					])
					->groupBy('users.id')
					->get();

				// Protección de la hoja
				$passComp = '';
				$comp = DB::table('company')->select('nit')
					->where('id', $id_company)->get();

				foreach ($comp as $comps) {
					$passComp = $comps->nit;
				}
				$sheet->protect($passComp, function (\PHPExcel_Worksheet_Protection $protection) {
					$protection->setSort(true);
				});

				$sheet->row(1, [
					'Nombre',
					'Número de documento',
					'Cargo',
					'Total controles',
					'Nivel Riesgo',
					'Persona que diligenció el TEST',
					'Fecha Test'
				]);
				$sheet->row(1, function ($row) {
					$row->setBackground('#33ffec');
				}); //ponemos color a la cabecera

				$riesgo = '';
				foreach ($sCarpo as $key => $value) {
					$resulTotal = $value->total;
					if ($resulTotal >= 0 && $resulTotal <= 25) {
						$riesgo = 'Riesgo Leve';
					} else if ($resulTotal >= 26 && $resulTotal <= 55) {
						$riesgo = 'Riesgo Medio';
					} else if ($resulTotal >= 56 && $resulTotal <= 80) {
						$riesgo = 'Riesgo Alto';
					} else if ($resulTotal >= 81) {
						$riesgo = 'Riesgo Grave';
					}
					$sheet->row($key + 2, [
						$value->name . ' ' . $value->last_name,
						$value->num_documento,
						$value->cargo,
						$resulTotal,
						$riesgo,
						$value->asesor_name . ' ' . $value->asesor_last,
						$value->created_at

					]);
				}
			});

			// Hoja 2(Tendinitis del manguito de los rotadores)
			$excel->sheet('Manguito de los rotadores', function ($sheet) {
				$id_company = Sentinel::getUser()->company_id;
				// Consulta para usuarios con tendinitis del manguito de los rotadores
				$sRotadores = DB::table('sve_respuestas_control1')
					->select('sve_respuestas_control1.created_at', 'users.id', 'users.name', 'users.last_name', 'users.num_documento', 'users.cargo', 'users1.name as asesor_name', 'users1.last_name as asesor_last', DB::raw("ROUND(AVG(sve_resultado_global.resultadoTest),1) as total"))
					->leftjoin('users', 'sve_respuestas_control1.id_user', '=', 'users.id')

					->leftjoin('sve_respuestas_control3', 'users.id', '=', 'sve_respuestas_control3.id_user')
					->leftjoin('sve_resultado_control', 'users.id', '=', 'sve_resultado_control.id_user')
					->leftjoin('sve_resultado_global', 'users.id', '=', 'sve_resultado_global.id_user')
					->leftjoin('users as users1', 'sve_resultado_control.id_auditorSST', '=', 'users1.id')
					->where([
						['sve_respuestas_control1.id_company', $id_company],
						['sve_respuestas_control1.p6', 1],
						['sve_respuestas_control1.p8', 1],
						['sve_respuestas_control3.p5', 1]
					])
					->groupBy('users.id')
					->get();

				// Protección de la hoja
				$passComp = '';
				$comp = DB::table('company')->select('nit')
					->where('id', $id_company)->get();

				foreach ($comp as $comps) {
					$passComp = $comps->nit;
				}
				$sheet->protect($passComp, function (\PHPExcel_Worksheet_Protection $protection) {
					$protection->setSort(true);
				});

				$sheet->row(1, [
					'Nombre',
					'Número de documento',
					'Cargo',
					'Total controles',
					'Nivel Riesgo',
					'Persona que diligenció el TEST',
					'Fecha Test'
				]);

				$sheet->row(1, function ($row) {
					$row->setBackground('#33e9ff');
				}); //ponemos color a la cabecera

				$riesgo = '';
				foreach ($sRotadores as $key => $value) {
					$resulTotal = $value->total;
					if ($resulTotal >= 0 && $resulTotal <= 25) {
						$riesgo = 'Riesgo Leve';
					} else if ($resulTotal >= 26 && $resulTotal <= 55) {
						$riesgo = 'Riesgo Medio';
					} else if ($resulTotal >= 56 && $resulTotal <= 80) {
						$riesgo = 'Riesgo Alto';
					} else if ($resulTotal >= 81) {
						$riesgo = 'Riesgo Grave';
					}
					$sheet->row($key + 2, [
						$value->name . ' ' . $value->last_name,
						$value->num_documento,
						$value->cargo,
						$resulTotal,
						$riesgo,
						$value->asesor_name . ' ' . $value->asesor_last,
						$value->created_at

					]);
				}
			});

			// Hoja 3(Lumbalgia de origen ocupacional)
			$excel->sheet('Lumbalgia', function ($sheet) {
				$id_company = Sentinel::getUser()->company_id;
				// Consulta para usuarios con lumbalgia de origen ocupacional
				$sLumbalgia = DB::table('sve_respuestas_control1')
					->select('sve_respuestas_control1.created_at', 'users.id', 'users.name', 'users.last_name', 'users.num_documento', 'users.cargo', 'users1.name as asesor_name', 'users1.last_name as asesor_last', DB::raw("ROUND(AVG(sve_resultado_global.resultadoTest),1) as total"))
					->leftjoin('users', 'sve_respuestas_control1.id_user', '=', 'users.id')

					->leftjoin('sve_respuestas_control3', 'users.id', '=', 'sve_respuestas_control3.id_user')
					->leftjoin('sve_resultado_control', 'users.id', '=', 'sve_resultado_control.id_user')
					->leftjoin('sve_resultado_global', 'users.id', '=', 'sve_resultado_global.id_user')
					->leftjoin('users as users1', 'sve_resultado_control.id_auditorSST', '=', 'users1.id')
					->where([
						['sve_respuestas_control1.id_company', $id_company],
						['sve_respuestas_control1.p6', 1],
						['sve_respuestas_control1.p18', 1],
						['sve_respuestas_control3.p11', 1]
					])
					->groupBy('users.id')
					->get();

				// Protección de la hoja
				$passComp = '';
				$comp = DB::table('company')->select('nit')
					->where('id', $id_company)->get();

				foreach ($comp as $comps) {
					$passComp = $comps->nit;
				}
				$sheet->protect($passComp, function (\PHPExcel_Worksheet_Protection $protection) {
					$protection->setSort(true);
				});

				$sheet->row(1, [
					'Nombre',
					'Número de documento',
					'Cargo',
					'Total controles',
					'Nivel Riesgo',
					'Persona que diligenció el TEST',
					'Fecha Test'
				]);

				$sheet->row(1, function ($row) {
					$row->setBackground('#33ffff');
				}); //ponemos color a la cabecera

				$riesgo = '';
				foreach ($sLumbalgia as $key => $value) {
					$resulTotal = $value->total;
					if ($resulTotal >= 0 && $resulTotal <= 25) {
						$riesgo = 'Riesgo Leve';
					} else if ($resulTotal >= 26 && $resulTotal <= 55) {
						$riesgo = 'Riesgo Medio';
					} else if ($resulTotal >= 56 && $resulTotal <= 80) {
						$riesgo = 'Riesgo Alto';
					} else if ($resulTotal >= 81) {
						$riesgo = 'Riesgo Grave';
					}
					$sheet->row($key + 2, [
						$value->name . ' ' . $value->last_name,
						$value->num_documento,
						$value->cargo,
						$resulTotal,
						$riesgo,
						$value->asesor_name . ' ' . $value->asesor_last,
						$value->created_at

					]);
				}
			});
		})->export('xls');
	}

	public function reporteFactor()
	{
		Excel::create('Reporte de factores de riesgo', function ($excel) {
			// Hoja 1(Fumadores)
			$excel->sheet('Fumadores', function ($sheet) {
				$id_company = Sentinel::getUser()->company_id;
				// Consulta que trae fumadores
				$facFumadores = DB::table('sve_resultado_control')
					->select('sve_resultado_control.fechaRevision', 'users.id', 'users.name', 'users.last_name', 'users.num_documento', 'users.cargo', 'users1.name as asesor_name', 'users1.last_name as asesor_last', DB::raw("ROUND(AVG(sve_resultado_global.resultadoTest),1) as total"))
					->leftjoin('users', 'sve_resultado_control.id_user', '=', 'users.id')
					->leftjoin('sve_resultado_global', 'users.id', '=', 'sve_resultado_global.id_user')
					->leftjoin('users as users1', 'sve_resultado_control.id_auditorSST', '=', 'users1.id')
					->where([
						['sve_resultado_control.id_company', $id_company],
						['sve_resultado_control.tabaquismoUser', 1]
					])
					->groupBy('users.id')
					->get();

				// Protección de la hoja
				$passComp = '';
				$comp = DB::table('company')->select('nit')
					->where('id', $id_company)->get();

				foreach ($comp as $comps) {
					$passComp = $comps->nit;
				}
				$sheet->protect($passComp, function (\PHPExcel_Worksheet_Protection $protection) {
					$protection->setSort(true);
				});

				$sheet->row(1, [
					'Nombre',
					'Número de documento',
					'Cargo',
					'Total controles',
					'Nivel Riesgo',
					'Persona que diligenció el TEST',
					'Fecha Test'
				]);
				$sheet->row(1, function ($row) {
					$row->setBackground('#33ecff');
				}); //ponemos color a la cabecera

				foreach ($facFumadores as $index => $result) {
					$riesgo = '';
					$resulTotal = $result->total;
					if ($resulTotal >= 0 && $resulTotal <= 25) {
						$riesgo = 'Riesgo Leve';
					} else if ($resulTotal >= 26 && $resulTotal <= 55) {
						$riesgo = 'Riesgo Medio';
					} else if ($resulTotal >= 56 && $resulTotal <= 80) {
						$riesgo = 'Riesgo Alto';
					} else if ($resulTotal >= 81) {
						$riesgo = 'Riesgo Grave';
					}
					$sheet->row($index + 2, [
						$result->name . " " . $result->last_name,
						$result->num_documento,
						$result->cargo,
						$resulTotal,
						$riesgo,
						$result->asesor_name . ' ' . $result->asesor_last,
						$result->fechaRevision

					]);
				}
			});

			// Hoja 2(Riesgo de imc(Indice de masa corporal))
			$excel->sheet('Riesgo de IMC', function ($sheet) {
				$id_company = Sentinel::getUser()->company_id;
				// Consulta que trae fumadores
				$facFumadores = DB::table('sve_resultado_control')
					->select('sve_resultado_control.fechaRevision', 'users.id', 'users.name', 'users.last_name', 'users.num_documento', 'users.cargo', 'users1.name as asesor_name', 'users1.last_name as asesor_last', DB::raw("ROUND(AVG(sve_resultado_global.resultadoTest),1) as total"))
					->leftjoin('users', 'sve_resultado_control.id_user', '=', 'users.id')
					->leftjoin('sve_resultado_global', 'users.id', '=', 'sve_resultado_global.id_user')
					->leftjoin('users as users1', 'sve_resultado_control.id_auditorSST', '=', 'users1.id')
					->where([
						['sve_resultado_control.id_company', $id_company],
						['sve_resultado_control.riesgoImcUser', 1]
					])
					->groupBy('users.id')
					->get();

				// Protección de la hoja
				$passComp = '';
				$comp = DB::table('company')->select('nit')
					->where('id', $id_company)->get();

				foreach ($comp as $comps) {
					$passComp = $comps->nit;
				}
				$sheet->protect($passComp, function (\PHPExcel_Worksheet_Protection $protection) {
					$protection->setSort(true);
				});

				$sheet->row(1, [
					'Nombre',
					'Número de documento',
					'Cargo',
					'Total controles',
					'Nivel Riesgo',
					'Persona que diligenció el TEST',
					'Fecha test'
				]);
				$sheet->row(1, function ($row) {
					$row->setBackground('#33f9ff');
				}); //ponemos color a la cabecera

				foreach ($facFumadores as $index => $result) {
					$riesgo = '';
					$resulTotal = $result->total;
					if ($resulTotal >= 0 && $resulTotal <= 25) {
						$riesgo = 'Riesgo Leve';
					} else if ($resulTotal >= 26 && $resulTotal <= 55) {
						$riesgo = 'Riesgo Medio';
					} else if ($resulTotal >= 56 && $resulTotal <= 80) {
						$riesgo = 'Riesgo Alto';
					} else if ($resulTotal >= 81) {
						$riesgo = 'Riesgo Grave';
					}
					$sheet->row($index + 2, [
						$result->name . " " . $result->last_name,
						$result->num_documento,
						$result->cargo,
						$resulTotal,
						$riesgo,
						$result->asesor_name . ' ' . $result->asesor_last,
						$result->fechaRevision

					]);
				}
			});

			// Hoja 3(Riesgo de diabetes)
			$excel->sheet('Antecedentes de diabetes', function ($sheet) {
				$id_company = Sentinel::getUser()->company_id;
				// Consulta que trae fumadores
				$facFumadores = DB::table('sve_resultado_control')
					->select('sve_resultado_control.fechaRevision', 'users.id', 'users.name', 'users.last_name', 'users.num_documento', 'users.cargo', 'users1.name as asesor_name', 'users1.last_name as asesor_last', DB::raw("ROUND(AVG(sve_resultado_global.resultadoTest),1) as total"))
					->leftjoin('users', 'sve_resultado_control.id_user', '=', 'users.id')
					->leftjoin('sve_resultado_global', 'users.id', '=', 'sve_resultado_global.id_user')
					->leftjoin('users as users1', 'sve_resultado_control.id_auditorSST', '=', 'users1.id')
					->where([
						['sve_resultado_control.id_company', $id_company],
						['sve_resultado_control.antecedenteUser', 1]
					])
					->groupBy('users.id')
					->get();

				// Protección de la hoja
				$passComp = '';
				$comp = DB::table('company')->select('nit')
					->where('id', $id_company)->get();

				foreach ($comp as $comps) {
					$passComp = $comps->nit;
				}
				$sheet->protect($passComp, function (\PHPExcel_Worksheet_Protection $protection) {
					$protection->setSort(true);
				});

				$sheet->row(1, [
					'Nombre',
					'Número de documento',
					'Cargo',
					'Total controles',
					'Nivel Riesgo',
					'Persona que diligenció el TEST',
					'Fecha test'
				]);
				$sheet->row(1, function ($row) {
					$row->setBackground('#33fffc');
				}); //ponemos color a la cabecera

				foreach ($facFumadores as $index => $result) {
					$riesgo = '';
					$resulTotal = $result->total;
					if ($resulTotal >= 0 && $resulTotal <= 25) {
						$riesgo = 'Riesgo Leve';
					} else if ($resulTotal >= 26 && $resulTotal <= 55) {
						$riesgo = 'Riesgo Medio';
					} else if ($resulTotal >= 56 && $resulTotal <= 80) {
						$riesgo = 'Riesgo Alto';
					} else if ($resulTotal >= 81) {
						$riesgo = 'Riesgo Grave';
					}
					$sheet->row($index + 2, [
						$result->name . " " . $result->last_name,
						$result->num_documento,
						$result->cargo,
						$resulTotal,
						$riesgo,
						$result->asesor_name . ' ' . $result->asesor_last,
						$result->fechaRevision

					]);
				}
			});

			// Hoja 4(Riesgo de edad)
			$excel->sheet('Riesgo de edad', function ($sheet) {
				$id_company = Sentinel::getUser()->company_id;
				// Consulta que trae fumadores
				$facFumadores = DB::table('sve_resultado_control')
					->select('sve_resultado_control.fechaRevision', 'users.id', 'users.name', 'users.last_name', 'users.num_documento', 'users.cargo', 'users1.name as asesor_name', 'users1.last_name as asesor_last', DB::raw("ROUND(AVG(sve_resultado_global.resultadoTest),1) as total"))
					->leftjoin('users', 'sve_resultado_control.id_user', '=', 'users.id')
					->leftjoin('sve_resultado_global', 'users.id', '=', 'sve_resultado_global.id_user')
					->leftjoin('users as users1', 'sve_resultado_control.id_auditorSST', '=', 'users1.id')
					->where([
						['sve_resultado_control.id_company', $id_company],
						['sve_resultado_control.edadUser', '>', 2]
					])
					->groupBy('users.id')
					->get();

				// Protección de la hoja
				$passComp = '';
				$comp = DB::table('company')->select('nit')
					->where('id', $id_company)->get();

				foreach ($comp as $comps) {
					$passComp = $comps->nit;
				}
				$sheet->protect($passComp, function (\PHPExcel_Worksheet_Protection $protection) {
					$protection->setSort(true);
				});

				$sheet->row(1, [
					'Nombre',
					'Número de documento',
					'Cargo',
					'Total controles',
					'Nivel Riesgo',
					'Persona que diligenció el TEST',
					'Fecha test'
				]);
				$sheet->row(1, function ($row) {
					$row->setBackground('#33ffec');
				}); //ponemos color a la cabecera

				foreach ($facFumadores as $index => $result) {
					$riesgo = '';
					$resulTotal = $result->total;
					if ($resulTotal >= 0 && $resulTotal <= 25) {
						$riesgo = 'Riesgo Leve';
					} else if ($resulTotal >= 26 && $resulTotal <= 55) {
						$riesgo = 'Riesgo Medio';
					} else if ($resulTotal >= 56 && $resulTotal <= 80) {
						$riesgo = 'Riesgo Alto';
					} else if ($resulTotal >= 81) {
						$riesgo = 'Riesgo Grave';
					}
					$sheet->row($index + 2, [
						$result->name . " " . $result->last_name,
						$result->num_documento,
						$result->cargo,
						$resulTotal,
						$riesgo,
						$result->asesor_name . ' ' . $result->asesor_last,
						$result->fechaRevision

					]);
				}
			});

			// Hoja 5(Riesgo de levantar peso)
			$excel->sheet('Riesgo de levantar peso', function ($sheet) {
				$id_company = Sentinel::getUser()->company_id;
				// Consulta que trae fumadores
				$facFumadores = DB::table('sve_resultado_control')
					->select('sve_resultado_control.fechaRevision', 'users.id', 'users.name', 'users.last_name', 'users.num_documento', 'users.cargo', 'users1.name as asesor_name', 'users1.last_name as asesor_last', DB::raw("ROUND(AVG(sve_resultado_global.resultadoTest),1) as total"))
					->leftjoin('users', 'sve_resultado_control.id_user', '=', 'users.id')
					->leftjoin('sve_resultado_global', 'users.id', '=', 'sve_resultado_global.id_user')
					->leftjoin('users as users1', 'sve_resultado_control.id_auditorSST', '=', 'users1.id')
					->where([
						['sve_resultado_control.id_company', $id_company],
						['sve_resultado_control.cargaPesoUser', 1]
					])
					->groupBy('users.id')
					->get();

				// Protección de la hoja
				$passComp = '';
				$comp = DB::table('company')->select('nit')
					->where('id', $id_company)->get();

				foreach ($comp as $comps) {
					$passComp = $comps->nit;
				}
				$sheet->protect($passComp, function (\PHPExcel_Worksheet_Protection $protection) {
					$protection->setSort(true);
				});

				$sheet->row(1, [
					'Nombre',
					'Número de documento',
					'Cargo',
					'Total controles',
					'Nivel Riesgo',
					'Persona que diligenció el TEST',
					'Fecha test'
				]);

				$sheet->row(1, function ($row) {
					$row->setBackground('#33ffff');
				}); //ponemos color a la cabecera

				foreach ($facFumadores as $index => $result) {
					$riesgo = '';
					$resulTotal = $result->total;
					if ($resulTotal >= 0 && $resulTotal <= 25) {
						$riesgo = 'Riesgo Leve';
					} else if ($resulTotal >= 26 && $resulTotal <= 55) {
						$riesgo = 'Riesgo Medio';
					} else if ($resulTotal >= 56 && $resulTotal <= 80) {
						$riesgo = 'Riesgo Alto';
					} else if ($resulTotal >= 81) {
						$riesgo = 'Riesgo Grave';
					}
					$sheet->row($index + 2, [
						$result->name . " " . $result->last_name,
						$result->num_documento,
						$result->cargo,
						$resulTotal,
						$riesgo,
						$result->asesor_name . ' ' . $result->asesor_last,
						$result->fechaRevision

					]);
				}
			});
		})->export('xls');
	}



	//Miembro Inferior


	public function exportResultInferior($id_company)
	{
		Excel::create('Historial, resultado SVE inferior', function ($excel) {

			$excel->sheet('Historial SVE Inferior', function ($sheet) {

				$id_company = Sentinel::getUser()->company_id;

				$promedioUser = DB::table('sve_resultado_control')
					->select(
						'sve_resultado_control.id_resultado',
						'sve_resultado_control.id_user',
						'users.name',
						'users.last_name',
						'users.fecha_nacimiento',
						'users.num_documento',
						'sve_resultado_control.id_company',
						'sve_resultado_control.pesoUser',
						'sve_resultado_control.tallaUser',
						'sve_resultado_control.imcUser',
						'sve_resultado_control.id_evaluacion',
						'sve_resultado_control.fechaRevision',
						'sve_resultado_control.created_at as fecha',
						'sve_riesgoInferior.id_company',
						'sve_riesgoInferior.id_user',
						'sve_riesgoInferior.risk1',
						'sve_riesgoInferior.risk2',
						'sve_riesgoInferior.risk3',
						'sve_riesgoInferior.risk4',
						'sve_riesgoInferior.risk5',
						'sve_riesgoInferior.newFecha',
						'sve_resultado_control.id_control',
						'sve_resultado_control.resultado',
						'sve_resultado_control.riesgo as riesgoF',
						'users.cargo',
						'company.id',
						'company.razonsocial'
					)
					->leftjoin('users', 'sve_resultado_control.id_user', '=', 'users.id')
					->leftjoin('company', 'company.id', '=', 'sve_resultado_control.id_company')
					->leftjoin('sve_riesgoInferior', 'sve_riesgoInferior.id_user', '=', 'sve_resultado_control.id_user')
					->where([['sve_resultado_control.id_company', '=', $id_company], ['sve_resultado_control.modulo', 2]])
					->orderBy('users.name')
					->groupBy('sve_resultado_control.created_at')
					->get();

				$sheet->row(1, [
					'Compañia',
					'Empleado',
					'Número de documento',
					'Cargo',
					'Nivel riesgo',
					'Fecha evaluación'
				]);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				foreach ($promedioUser as $index => $result) {
					$riesgo = '';
					if ($result->riesgoF == 0) {
						$riesgo = 'Riesgo Mínimo';
					} else if ($result->riesgoF >= 1 && $result->riesgoF <= 2) {
						$riesgo = 'Riesgo Bajo';
					} else if ($result->riesgoF >= 3 && $result->riesgoF <= 5) {
						$riesgo = 'Riesgo Medio';
					} else if ($result->riesgoF >= 6) {
						$riesgo = 'Riesgo Alto';
					}


					$sheet->row($index + 2, [
						$result->razonsocial,
						$result->name . " " . $result->last_name,
						$result->num_documento,
						$result->cargo,
						$riesgo,
						$result->fecha
					]);
				}
			});
		})->export('xls');
	}


	// FIN SVE


	//**********Plan de trabajo de Linea Basal
	public function workPlan($id_company)
	{

		//dd($plan);

		Excel::create('INFORME DE AUDITORÍA Y PLAN DE TRABAJO', function ($excel) use ($id_company) {

			$excel->sheet('AUDITORÍA', function ($sheet) use ($id_company) {
				$plan = DB::table('lineabasal')
					->select('lineabasal.numeral', 'lineabasal.item', 'lineabasal.planAccion', 'lineabasal.accion', 'lineabasal_resultado.obseModificacion')
					->leftjoin('lineabasal_resultado', 'lineabasal.numeral', '=', 'lineabasal_resultado.id_numeral')
					->where([['lineabasal_resultado.id_company', $id_company], ['lineabasal_resultado.cumple', 0]])
					->get();
				//dd($plan);
				$total = DB::table('lineabasal_total')
					->select(DB::raw("MAX(totalTest) as totaltest"))
					->where('id_company', $id_company)
					->get();
				$sheet->row(1, [
					'CICLO',
					'RECURSOS',
					'ESTÁNDAR',
					'ÌTEM DEL ESTÁNDAR',
					'PLAN DE TRABAJO',
					'OBSERVACIÓN'
				]);
				$accion = '';
				$cont = 0;
				foreach ($plan as $key => $value) {
					$cont++;
					if ($value->accion == 1) {
						$accion = 'PLANEAR';
					} else if ($value->accion == 2) {
						$accion = 'HACER';
					} else if ($value->accion == 3) {
						$accion = 'VERIFICAR';
					} else if ($value->accion == 4) {
						$accion = 'ACTUAR';
					}

					$sheet->row($key + 2, [
						$accion,
						'RECURSOS (10%)',
						'Recursos financieros, técnicos,  humanos y de otra índole requeridos para coordinar y desarrollar el Sistema de Gestión de la Seguridad y la Salud en el Trabajo (SG-SST) (4%)',
						$value->numeral . ' ' . $value->item,
						$value->planAccion,
						$value->obseModificacion
					]);
				}

				$sheet->row($cont + 2, [
					'TOTALES'
				]);
				$sheet->row($cont + 3, [
					'Cuando se cumple con el ítem del estándar la calificación será la máxima del respectivo ítem, de lo contrario su calificación será igual a cero (0).'
				]);
				$sheet->row($cont + 4, [
					'Si el estándar No Aplica, se deberá justificar la situación y se calificará con el porcentaje máximo del ítem indicado para cada estándar. En caso de no justificarse, la calificación el estándar será igual a cero (0)'
				]);
				$sheet->row($cont + 5, [
					'El presente formulario es documento público, no se debe consignar hecho o manifestaciones falsas y está sujeto a las sanciones establecidas en los artículos 288 y 294 de la Ley 599 de 2000 (Código Penal Colombiano)'
				]);
				$sheet->row($cont + 6, [
					'FIRMA DEL PROFESIONAL RESPONSABLE DE LA VERIFICACIÓN:'
				]);
				$sheet->row($cont + 7, [
					'FIRMA DEL EMPLEADOR O CONTRATANTE'
				]);
				$sheet->row($cont + 8, [
					'FIRMA DEL RESPONSABLE DE LA EJECUCIÓN DEL SG-SST'
				]);
				$totalTest = 0;
				foreach ($total as $value) {
					$totalTest = $value->totaltest;
				}
				$sheet->row($cont + 9, [
					'EL NIVEL DE SU EVALUACIÓN ES:' . $totalTest
				]);
				$sheet->row($cont + 10, [
					'CRITERIO / VALORACIÓN',
					'ACCIÓN'
				]);
				$sheet->row($cont + 11, [
					'"Si el puntaje obtenido es menor al 60%',
					'ACCIÓN',
					'Realizar y tener a disposición del Ministerio del Trabajo un Plan de Mejoramiento de inmediato.'
				]);
				$sheet->row($cont + 12, [
					'CRÍTICO',
					'Enviar a la respectiva Administradora de Riesgos Laborales a la que se encuentre afiliada la empresa o contratante, un reporte de avances en el término máximo de tres (3) meses después de realizada la autoevaluación de Estándares Mínimos.'
				]);
				$sheet->row($cont + 13, [
					'CRÍTICO',
					'Seguimiento anual y plan de visita a la empresa con valoración crítica, por parte del Ministerio del Trabajo.'
				]);
				$sheet->row($cont + 14, [
					'Si el puntaje obtenido está entre el 60 y 85%',
					'Realizar y tener a disposición del Ministerio del Trabajo un Plan de Mejoramiento.'
				]);
				$sheet->row($cont + 15, [
					'MODERADAMENTE ACEPTABLE',
					'Enviar a la Administradora de Riesgos Laborales un reporte de avances en el término máximo de seis (6) meses después de realizada la autoevaluación de Estándares Mínimos.'
				]);
				$sheet->row($cont + 16, [
					'MODERADAMENTE ACEPTABLE',
					'Plan de visita por parte del Ministerio del Trabajo.'
				]);
				$sheet->row($cont + 17, [
					'Si el puntaje obtenido es mayor al 85%',
					'Mantener la calificación y evidencias a disposición del Ministerio del Trabajo, e incluir en el Plan de Anual de Trabajo las mejoras detectadas.'
				]);
				$sheet->row($cont + 18, [
					'ACEPTABLE',
					'Mantener la calificación y evidencias a disposición del Ministerio del Trabajo, e incluir en el Plan de Anual de Trabajo las mejoras detectadas.'
				]);
				// $sheet->row(2, [
				// 	'Myfrfvg', 
				// 	'num_documento',
				// 	'cargo',
				// 	'total',
				// 	'riesgo'
				// ]);

			});
		})->export('xls');
	}

	// FIN DE LINEA BASAL

	//Módulo de Matriz Legal
	public function subirRequisitosEnExcel(Request $request)
	{

		Excel::load($request->upLegal, function ($reader) use ($request) {
			$upLegal = $reader->get();
			$reader->each(function ($row) use ($request) {
				// Check if all relevant fields are empty or null
				if (
					empty($row->id_tipo_peligro) &&
					empty($row->descripcion_norma) &&
					empty($row->id_empresa)
				) {
					// Skip this row
					return;
				}

				$id_modlegal = $row->id_modlegal;
				$requi = RequisitoLegal::create([
					'id_tipo_peligro' => $row->id_tipo_peligro,
					'id_empresa' => $row->id_empresa,
					'tipo_norma' => $row->tipo_norma,
					'norm_aso' => $row->norm_aso,
					'emisor' => $row->emisor,
					'descripcion_norma' => $row->descripcion_norma,
					'fecha_emision' => $row->fecha_emision,
					'articulos_aplicables' => $row->articulos_aplicables,
					'subclasificacion' => $row->subclasificacion,
					'descripcion_requisito' => $row->descripcion_requisito,
					'estado' => 1
				]);

				tbl_int_legales::create([
					'id_modLegal' => $id_modlegal,
					'id_norma' => $requi->id,
					'estado' => 1
				]);
			});
		});


		return redirect()->back();
		echo "Importación correcta";
	}

public function subirCriterio(Request $request)
{
	$file = $request->file('upCriterio'); // aquí sí llega el archivo
	Excel::load($file->getRealPath(), function ($reader) {
		$reader->each(function ($row) {
			$criterios = Criterio::create([
				'criterio' => $row->criterio,
				'id_requisito_legal' => $row->id_requisito_legal,
				'id_modulo' => $row->id_modulo,
				'valor' => $row->valor,
				'id_tipo_peligro' => $row->id_tipo_peligro,
				'estado' => 1
			]);

			RequisitoCriterio::create([
				'id_requisito_legal' => $row->id_requisito_legal,
				'id_criterio_cumplimiento' => $criterios->id_criterio_cumplimiento,
				'estado' => 1
			]);
		});
	});

	return redirect()->back()->with('success', 'Importación correcta');
}

public function excelCumplimiento(Request $request)
	{
		Excel::create('Cumplimiento', function ($excel) use ($request) {
			$excel->sheet('Cumplimiento', function ($sheet) use ($request) {
				$sheet->row(1, [
					'Modulo',
					'Valor Maximo',
					'Valor Obtenido',
					'% de Cumplimienton'
				]);

				$sheet->row(2, [
					'Medicina Preventiva y del Trabajo',
					$request->total_valor_maximo,
					$request->total_valor_obtenido,
					$request->total_porcentaje
				]);
				$sheet->row(3, [
					'Seguridad e Higiene Industrial',
					$request->total_valor_maximo1,
					$request->total_valor_obtenido1,
					$request->total_porcentaje1
				]);
				$sheet->row(4, [
					'Sistema General de Seguridad Social en Salud',
					$request->total_valor_maximo3,
					$request->total_valor_obtenido3,
					$request->total_porcentaje3
				]);
				$sheet->row(5, [
					'Medio Ambiente',
					$request->total_valor_maximo4,
					$request->total_valor_obtenido4,
					$request->total_porcentaje4
				]);
				$sheet->row(6, [
					'Calidad',
					$request->total_valor_maximo5,
					$request->total_valor_obtenido5,
					$request->total_porcentaje5
				]);
			});
		})->export('xls');
	}



	public function excelRequisitos($id_empresa)
	{

		Excel::create('RequisitosLegales', function ($excel) use ($id_empresa) {
			$excel->sheet('RequisitosLegales', function ($sheet) use ($id_empresa) {

				$requisitos = DB::table('tbl_requisitos_legales')
					->select('tbl_requisitos_legales.id', 'tbl_requisitos_legales.id_tipo_peligro', 'tbl_requisitos_legales.id_empresa', 'tbl_requisitos_legales.norm_aso', 'tbl_tipo_peligro.peligro', 'tbl_requisitos_legales.tipo_norma', 'tbl_requisitos_legales.emisor', 'tbl_requisitos_legales.descripcion_norma', 'tbl_requisitos_legales.fecha_emision', 'tbl_requisitos_legales.articulos_aplicables', 'tbl_requisitos_legales.subclasificacion', 'tbl_requisitos_legales.descripcion_requisito', 'tbl_requisitos_legales.estado', 'tbl_int_legales.id_modLegal')
					->leftjoin('tbl_int_legales', 'tbl_int_legales.id_norma', '=', 'tbl_requisitos_legales.id')
					->leftjoin('tbl_tipo_peligro', 'tbl_tipo_peligro.id', '=', 'tbl_requisitos_legales.id_tipo_peligro')
					->where([['tbl_requisitos_legales.estado', '=', 1], ['tbl_requisitos_legales.id_empresa', 1]])
					->orwhere([['tbl_requisitos_legales.estado', '=', 1], ['tbl_requisitos_legales.id_empresa', $id_empresa]])
					->get();

				// dd($requisitos);
				$sheet->row(1, [
					'id_tipo_peligro',
					'id_empresa',
					'tipo_norma',
					'norm_aso',
					'emisor',
					'descripcion_norma',
					'fecha_emision',
					'articulos_aplicables',
					'subclasificacion',
					'descripcion_requisito',
					'id_modLegal'
				]);

				foreach ($requisitos as $index => $lct) {
					$sheet->row($index + 2, [
						$lct->id_tipo_peligro,
						$lct->id_empresa,
						$lct->tipo_norma,
						$lct->norm_aso,
						$lct->emisor,
						$lct->descripcion_norma,
						$lct->fecha_emision,
						$lct->articulos_aplicables,
						$lct->subclasificacion,
						$lct->descripcion_requisito,
						$lct->id_modLegal
					]);
				}
			});
		})->export('xls');
	}


	public function excelCriterios()
	{


		Excel::create('Criterios', function ($excel) {
			$excel->sheet('Criterios', function ($sheet) {

				$general = DB::table('tbl_criterio_cumplimiento')
					->select('tbl_criterio_cumplimiento.valor', 'tbl_criterio_cumplimiento.id_tipo_peligro', 'tbl_criterio_cumplimiento.id_requisito_legal', 'tbl_criterio_cumplimiento.criterio', 'tbl_criterio_cumplimiento.id_modulo')
					->get();

				// dd($requisitos);
				$sheet->row(1, [
					'id_tipo_peligro',
					'id_requisito_legal',
					'id_modulo',
					'criterio',
					'valor'
				]);

				foreach ($general as $index => $lct) {
					$sheet->row($index + 2, [
						$lct->id_tipo_peligro,
						$lct->id_requisito_legal,
						$lct->id_modulo,
						$lct->criterio,
						$lct->valor
					]);
				}
			});
		})->export('xls');
	}


	public function normasClientes($id_empresa)
	{
		Excel::create('Nomas Asociadas', function ($excel) use ($id_empresa) {
			$excel->sheet('Nomas Asociadas', function ($sheet) use ($id_empresa) {

				$general = DB::table('tbl_requisitos_legales')
					->select('tbl_int_norma.id_norma', 'tbl_int_norma.id_empresa', 'tbl_requisitos_legales.norm_aso', 'tbl_requisitos_legales.tipo_norma', 'tbl_requisitos_legales.emisor', 'tbl_requisitos_legales.descripcion_norma', 'tbl_requisitos_legales.fecha_emision', 'tbl_requisitos_legales.articulos_aplicables', 'tbl_requisitos_legales.subclasificacion', 'tbl_requisitos_legales.descripcion_requisito', 'tbl_mod_legales.nombre')
					->join('tbl_int_norma', 'tbl_int_norma.id_norma', '=', 'tbl_requisitos_legales.id')
					->join('tbl_int_legales', 'tbl_int_legales.id_norma', '=', 'tbl_requisitos_legales.id')
					->join('tbl_mod_legales', 'tbl_mod_legales.id_modLegal', '=', 'tbl_int_legales.id_modLegal')
					->where([['tbl_int_norma.id_empresa', $id_empresa]])
					->groupBy('tbl_int_norma.id_norma')
					->get();


				$sheet->row(1, [
					'Modulo',
					'Tipo Norma',
					'Emisor',
					'Descripcion',
					'fecha de Emision',
					'Articulos Aplicables',
					'Subclasificacion',
					'Descripcion del Requisito'
				]);

				foreach ($general as $index => $lct) {
					$sheet->row($index + 2, [
						$lct->nombre,
						$lct->tipo_norma,
						$lct->emisor,
						$lct->descripcion_norma,
						$lct->fecha_emision,
						$lct->articulos_aplicables,
						$lct->subclasificacion,
						$lct->descripcion_requisito
					]);
				}
			});
		})->export('xls');
	}
	// FIN DE EXCELL PARA MATRIZ LEGAL

	//	INICIO DE EXCELL PARA CONTRATOS Y NEW CLIENTES SEGUIMIENTO


	public function contratosIP()
	{
		Excel::create('Aceptación de uso de plataforma', function ($excel) {
			$excel->sheet('Contratos', function ($sheet) {

				$contrato = DB::table('contrato')
					->select('contrato.id_contrato', 'contrato.fecha_contrato', 'contrato.representante', 'contrato.cedulaRepresentante', 'contrato.razonSocial', 'contrato.nitRazon', 'contrato.domicilioRazon', 'contrato.horasContrato', 'contrato.horasContratoMes', 'contrato.valorContrato', 'contrato.fecha_firma', 'contrato.valorContratoLetras', 'contrato.numEmpleados', 'contrato.firma', 'contrato.ip_firma', 'contrato.estado', 'contrato.so_firma', 'contrato.id_propuesta', 'contrato.fileContrato', 'anexos_contrato.anexo_firmado', 'ciudades.idCiudad', 'ciudades.nombre as nombreCiudad', 'region.idRegion', 'region.nombre as nombreRegion')
					->leftjoin('ciudades', 'ciudades.idCiudad', '=', 'contrato.ciudadRazon')
					->leftjoin('region', 'region.idRegion', '=', 'contrato.regionRazon')
					->leftjoin('anexos_contrato', 'anexos_contrato.id_contrato', '=', 'contrato.id_contrato')
					->where('contrato.estado', '=', 1)
					->get();


				$sheet->row(1, [
					'Empresa',
					'Nit',
					'Representante',
					'Ciudad',
					'Dirección',
					'Valor',
					'Fecha de aceptación',
					'IP aceptación',
					'Sistema Operativo'
				]);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				foreach ($contrato as $index => $lct) {
					$sheet->row($index + 2, [
						$lct->razonSocial,
						$lct->nitRazon,
						$lct->representante,
						$lct->nombreCiudad,
						$lct->domicilioRazon,
						$lct->valorContrato,
						$lct->fecha_firma,
						$lct->ip_firma,
						$lct->so_firma
					]);
				}
			});
		})->export('xls');
	}

	// FIN EXCELL PARA CONTRATOS Y NEW CLIENTES SEGUIMIENTO
	// NEW CLIENTES SEGUIMIENTO


	public function newClientExport()
	{
		Excel::create('Ficha Clientes Nuevos', function ($excel) {
			$excel->sheet('Ficha Clientes Nuevos', function ($sheet) {

				$user = User::orderBy('name', 'ASC')->where([['company_id', 1], ['role_id', 6]])->get();

				$NewClient = DB::table('NewClientSeg')
					->select('NewClientSeg.id_NewClient', 'NewClientSeg.id_company', 'NewClientSeg.id_contrato', 'NewClientSeg.id_usersCo', 'NewClientSeg.estadoComercial', 'NewClientSeg.debitoAuto', 'NewClientSeg.tipoPago', 'NewClientSeg.tipoVenta', 'NewClientSeg.fechaInService', 'NewClientSeg.fechaFacturacion', 'NewClientSeg.fechaOffFactura', 'NewClientSeg.facturado', 'NewClientSeg.pagado', 'NewClientSeg.timeContrato', 'NewClientSeg.observaciones', 'NewClientSeg.estado', 'NewClientSeg.created_at', 'NewClientSeg.updated_at', 'contrato.id_contrato as contrato_id', 'contrato.num_contrato', 'contrato.representante', 'contrato.cedulaRepresentante', 'contrato.razonSocial', 'contrato.nitRazon', 'contrato.domicilioRazon', 'contrato.ciudadRazon', 'contrato.horasContrato', 'contrato.valorContrato', 'contrato.nivelRiesgo', 'contrato.numEmpleados', 'contrato.id_usuario', 'contrato.fecha_contrato', 'contrato.tipoService', 'contrato.estado as conEstado', 'company.id', 'company.nit', 'company.razonsocial', 'company.direccion', 'company.cat_riesgos', 'company.telefono', 'company.contactoSST', 'company.teleContactoSST', 'company.emailContactoSST', 'company.estado as compaEstado', 'company.id_region', 'company.id_ciudad', 'company.origen_regi', 'company.id_asesor', 'ciudades.idCiudad', 'ciudades.nombre as nombreCiudad', 'region.idRegion', 'region.nombre as nombreRegion', 'users.id as idUser', 'users.name', 'cat_riesgos.id as id_cat_riesgos', 'cat_riesgos.descripcion')
					->leftjoin('contrato', 'contrato.id_contrato', '=', 'NewClientSeg.id_contrato')
					->leftjoin('company', 'company.id', '=', 'NewClientSeg.id_company')
					->leftjoin('ciudades', 'ciudades.idCiudad', '=', 'contrato.ciudadRazon')
					->leftjoin('region', 'region.idRegion', '=', 'company.id_region')
					->leftjoin('users', 'users.id', '=', 'NewClientSeg.id_usersCo')
					->leftjoin('cat_riesgos', 'cat_riesgos.id', '=', 'company.cat_riesgos')
					->where('NewClientSeg.estado', 1)
					->get();


				$sheet->row(1, [
					'Empresa',
					'Estado',
					'Fecha de agendamiento',
					'Debito automático',
					'Facturación & Inicio servicio',
					'Facturado',
					'Vencimiento 1° factura',
					'Pagado',
					'Analista asignado',
					'Vendedor',
					'Tiempo contrato',
					'Clase de Riesgo según la certificación de ARL',
					'# Trabajadores',
					'Modalidad de servicio',
					'Nombre comercial',
					'Razón social',
					'Nit',
					'Recompra o nueva',
					'# Conexiones',
					'Sector económico',
					'Origen cliente',
					'Nombre representante',
					'Cédula representante',
					'Teléfono representante',
					'Dirección empresa',
					'Departamento',
					'Ciudad',
					'Contacto SST',
					'Telefóno',
					'Correo',
					'valor',
					'Notas'
				]);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				foreach ($NewClient as $index => $lct) {

					switch ($lct->estadoComercial) {
						case 1:
							$estadoComercial = "Agendado";
							break;
						case 2:
							$estadoComercial = "No Agendado";
							break;
						case 3:
							$estadoComercial = "Entregado";
							break;
						case 4:
							$estadoComercial = "No Entregado";
							break;
						case 5:
							$estadoComercial = "Pendiente";
							break;
						case 6:
							$estadoComercial = "	Faltan Datos";
							break;
					}

					switch ($lct->tipoVenta) {
						case 1:
							$tipoVenta = "Cliente nuevo";
							break;
						case 2:
							$tipoVenta = "Recompra";
							break;
						case 3:
							$tipoVenta = "Material";
							break;
						case 4:
							$tipoVenta = "Sistegra Previene";
							break;
						case 5:
							$tipoVenta = "Matriz Legal";
							break;
						case 6:
							$tipoVenta = "Matriz Psicosocial";
							break;
						case 7:
							$tipoVenta = "PERSV";
							break;
					}

					switch ($lct->origen_regi) {
						case 1:
							$origen_regi = "Comercial";
							break;
						case 2:
							$origen_regi = "Comercial externo";
							break;
						case 3:
							$origen_regi = "Asesor";
							break;
						case 4:
							$origen_regi = "Web";
							break;
						case 5:
							$origen_regi = "Bancolombia";
							break;
						case 6:
							$origen_regi = "Recomendado";
							break;
						case 7:
							$origen_regi = "Redes sociales";
							break;
					}

					switch ($lct->debitoAuto) {
						case 1:
							$debitoAuto = 'Si';
							break;
						case 2:
							$debitoAuto = 'No';
							break;
						case 3:
							$debitoAuto = 'Pago único';
							break;
					}

					switch ($lct->pagado) {
						case 1:
							$pagado = 'Si';
							break;
						case 2:
							$pagado = 'No';
							break;
						case 3:
							$pagado = 'Pendiente';
							break;
					}

					switch ($lct->facturado) {
						case 1:
							$facturado = 'Si';
							break;
						case 2:
							$facturado = 'No';
							break;
						case 3:
							$facturado = 'Pendiente';
							break;
					}

					switch ($lct->tipoService) {
						case 1:
							$tipoService = 'Virtual';
							break;
						case 2:
							$tipoService = 'Presencial';
							break;
						case 3:
							$tipoService = 'Venta directa';
							break;
					}

					switch ($lct->timeContrato) {
						case 1:
							$timeContrato = 'Abierto';
							break;
						case 2:
							$timeContrato = 'Temporal';
							break;
					}

					$sheet->row($index + 2, [
						$lct->razonSocial,
						$estadoComercial,
						$lct->fechaInService,
						$debitoAuto,
						$lct->fechaFacturacion,
						$facturado,
						$lct->fechaOffFactura,
						$pagado,
						$lct->id_asesor,
						$lct->name,
						$timeContrato,
						$lct->nivelRiesgo,
						$lct->numEmpleados,
						$tipoService,
						$lct->razonsocial,
						$lct->razonSocial,
						$lct->nit,
						$tipoVenta,
						$lct->horasContrato,
						$lct->descripcion,
						$origen_regi,
						$lct->representante,
						$lct->cedulaRepresentante,
						$lct->telefono,
						$lct->direccion,
						$lct->nombreRegion,
						$lct->nombreCiudad,
						$lct->contactoSST,
						$lct->teleContactoSST,
						$lct->emailContactoSST,
						$lct->valorContrato,
						$lct->observaciones
					]);
				}
			});
		})->export('xls');
	}

	//	FIN INICIO DE EXCELL PARA CONTRATOS Y NEW CLIENTES SEGUIMIENTO


	// INICIO DE EXPORTACIÓN DE USUARIOS  PERFIL SOCIODEMOGRÁFICO por company	

	public function usersExport($company_id)
	{

		Excel::create('Perfilsociodemografico', function ($excel) use ($company_id) {


			//****hoja 1 del perfir sociodemografico Datos Personales*****
			$excel->sheet('Datos del Empleado', function ($sheet) use ($company_id) {
				$users = DB::table('users')
					->select(
						'users.id',
						'users.name',
						'users.last_name',
						'users.num_documento',
						'users.email',
						'users.fecha_nacimiento',
						'users.genero',
						'users.nivel_estudio',
						'users.estrato',
						'users.cargo',
						'users.role_id',
						'users.telefono',
						'users.tipo_documento',
						'users.id_region',
						'users.id_ciudad',
						'users.direccion',
						'users.company_id',
						'users.estado',
						'users.nivel_estudio',
						'ciudades.nombre as nombreCiudad',
						'ciudades.idCiudad',
						'company.razonsocial',
						'region.idRegion',
						'region.nombre as nombreRegion',
						'areas.id as areasId',
						'areas.name as nameAreas',
						'nivelEstudios.id as id_nivel',
						'nivelEstudios.nivelDesc',
						'v311FDAP_DatosContacto.name as nameC',
						'v311FDAP_DatosContacto.last_name as last_nameC',
						'v311FDAP_DatosContacto.numerodoc as numerodocC',
						'v311FDAP_DatosContacto.email as emailC',
						'v311FDAP_DatosContacto.tel as telC',
						'v311FDAP_DatosContacto.direccion as direccionC',
						'v311hacerFormatoActualizacionDatosPersonales.id_lugarexp',
						'v311hacerFormatoActualizacionDatosPersonales.barrio',
						'v311hacerFormatoActualizacionDatosPersonales.tipovivienda',
						'v311hacerFormatoActualizacionDatosPersonales.id_lugarnace',
						'v311hacerFormatoActualizacionDatosPersonales.edad',
						'v311hacerFormatoActualizacionDatosPersonales.factorRH',
						'v311hacerFormatoActualizacionDatosPersonales.id_estadocivil',
						'v311hacerFormatoActualizacionDatosPersonales.fechaingreso',
						'v311hacerFormatoActualizacionDatosPersonales.areatrabajo',
						'v311hacerFormatoActualizacionDatosPersonales.salario',
						'v311hacerFormatoActualizacionDatosPersonales.tipocontrato',
						'v311hacerFormatoActualizacionDatosPersonales.id_jtrabajo',
						'v311hacerFormatoActualizacionDatosPersonales.grupoEtnico',
						'v311hacerFormatoActualizacionDatosPersonales.jefeinmediato',
						'v311hacerFormatoActualizacionDatosPersonales.recursoshumanos',
						'v311hacerFormatoActualizacionDatosPersonales.cantRRHH',
						'v311hacerFormatoActualizacionDatosPersonales.eps',
						'v311hacerFormatoActualizacionDatosPersonales.fondopensiones',
						'v311hacerFormatoActualizacionDatosPersonales.fondocesantias',
						'v311hacerFormatoActualizacionDatosPersonales.arl',
						'v311hacerFormatoActualizacionDatosPersonales.cajacompensacion',
						'v311hacerFormatoActualizacionDatosPersonales.padeceenfermedad',
						'v311hacerFormatoActualizacionDatosPersonales.fuma',
						'v311hacerFormatoActualizacionDatosPersonales.frecFuma',
						'v311hacerFormatoActualizacionDatosPersonales.bebe',
						'v311hacerFormatoActualizacionDatosPersonales.frecBebe',
						'v311hacerFormatoActualizacionDatosPersonales.altura',
						'v311hacerFormatoActualizacionDatosPersonales.peso',
						'v311hacerFormatoActualizacionDatosPersonales.tallacamisa',
						'v311hacerFormatoActualizacionDatosPersonales.tallapantalon',
						'v311hacerFormatoActualizacionDatosPersonales.tallazapatos',
						'v311hacerFormatoActualizacionDatosPersonales.updated_at',
						'jornadaTrabajo.id_jtrabajo',
						'jornadaTrabajo.nombre as jornada',
						'estadoCivil.id as estadoCivil_id',
						'estadoCivil.nombre as estadoCivil',
						'arl.id as id_arl',
						'arl.nombre as arlName',
						'eps_arl.id_tipo_admin',
						'eps_arl.nombre as epsName',
						'cajaCompensacion.id as id_compesacion',
						'cajaCompensacion.nombre as nameCompensacion'
					)

					->leftjoin('ciudades', 'ciudades.idCiudad', '=', 'users.id_ciudad')
					->leftjoin('region', 'region.idRegion', '=', 'users.id_region')
					->leftjoin('company', 'company.id', '=', 'users.company_id')
					->leftjoin('v311FDAP_DatosContacto', 'v311FDAP_DatosContacto.id_user', '=', 'users.id')
					->leftjoin('v311hacerFormatoActualizacionDatosPersonales', 'v311hacerFormatoActualizacionDatosPersonales.id_user', '=', 'users.id')
					->leftjoin('jornadaTrabajo', 'jornadaTrabajo.id_jtrabajo', '=', 'v311hacerFormatoActualizacionDatosPersonales.id_jtrabajo')
					->leftjoin('eps_arl', 'eps_arl.id_tipo_admin', '=', 'v311hacerFormatoActualizacionDatosPersonales.eps')
					->leftjoin('arl', 'arl.id', '=', 'v311hacerFormatoActualizacionDatosPersonales.arl')
					->leftjoin('estadoCivil', 'estadoCivil.id', '=', 'v311hacerFormatoActualizacionDatosPersonales.id_estadocivil')
					->leftjoin('tipoContratos', 'tipoContratos.id', '=', 'v311hacerFormatoActualizacionDatosPersonales.tipocontrato')
					->leftjoin('nivelEstudios', 'nivelEstudios.id', '=', 'users.nivel_estudio')
					->leftjoin('areas', 'areas.id', '=', 'users.id_area')
					->leftjoin('cajaCompensacion', 'cajaCompensacion.id', '=', 'v311hacerFormatoActualizacionDatosPersonales.cajacompensacion')
					->where('users.company_id', $company_id)
					->groupBy('users.id')
					->get();



				$sheet->row(1, [
					'Empresa',
					'Nombre Empleado',
					'Tipo documento',
					'Num. Documento',
					'Correo',
					'Región',
					'Ciudad',
					'Barrio',
					'Domicilio',
					'Estrato',
					'Tipo de vivienda',
					'Teléfono',
					'Fecha nacimiento',
					'Lugar de nacimiento',
					'edad',
					'Genero',
					'Estado civil',
					'Nivel de estudios',
					'Grupo etnico',
					'Factor R.H.',
					'Persona de Contacto',
					'Teléfono persona contacto',
					'Dirección persona contacto',
					'Última actualización',
					'Estado'
				]);

				$sheet->row(1, function ($row) {
					$row->setBackground('#A5E9E7 ');
				}); //ponemos color a la cabecera

				foreach ($users as $value => $lct) {


					//tipo documento
					if ($lct->tipo_documento == '' || $lct->tipo_documento == 'null') {
						$documento = "campo vacio";
					} else {
						if ($lct->tipo_documento == 12) {
							$documento = "Tarjeta de Identidad";
						} elseif ($lct->tipo_documento == 13) {
							$documento = "Cédula Ciudadanía";
						} elseif ($lct->tipo_documento == 21) {
							$documento = "Tarjeta de extranjería";
						} elseif ($lct->tipo_documento == 22) {
							$documento = "Cédula de extranjería";
						} elseif ($lct->tipo_documento == 31) {
							$documento = "NIT";
						} elseif ($lct->tipo_documento == 41) {
							$documento = "Pasaporte";
						} elseif ($lct->tipo_documento == 42) {
							$documento = "Tipo documento extranjero";
						} elseif ($lct->tipo_documento == 43) {
							$documento = "Permiso especial de permanencia";
						}
					}

					// Genero

					if ($lct->genero == '' || $lct->genero == 'null') {
						$genero = 'campo vacio';
					} else {
						if ($lct->genero == 1) {
							$genero = "Femenino";
						} elseif ($lct->genero == 2) {
							$genero = "Masculino";
						} elseif ($lct->genero == 3) {
							$genero = "Indefinido";
						}
					}

					// Nivel estudio
					/*    if(assert($lct->nivel_estudio)) {
					$nivel="Campo Vacio";
					}else{
					if ($lct->nivel_estudio==1) {
					$nivel="Educación básica";
					}elseif ($lct->nivel_estudio==2) {
					$nivel="Bachiller";
					}elseif ($lct->nivel_estudio==3) {
					$nivel="Técnico";
					}elseif ($lct->nivel_estudio==4) {
					$nivel="Técnologo";
					}elseif ($lct->nivel_estudio==5) {
					$nivel="Universitario";
					}elseif ($lct->nivel_estudio==6) {
					$nivel="Especialista ";
					}elseif ($lct->nivel_estudio==7) {
					$nivel="Magister";
					}elseif ($lct->nivel_estudio==8) {
					$nivel="Doctorado";
					}elseif ($lct->nivel_estudio==9) {
					$nivel="Pos doctorado";
					}}*/

					// Caja de compensaciones
					/*if ($lct->cajacompensacion == '' || $lct->cajacompensacion == 'null') {
						$compensaciones = "Campo Vacio";
					} else {
						if ($lct->cajacompensacion == 1) {
							$compensaciones = "Comfenalco";
						} elseif ($lct->cajacompensacion == 2) {
							$compensaciones = "Comfama";
						} elseif ($lct->cajacompensacion == 3) {
							$compensaciones = "Compensar";
						}
					}*/

					//Fondocesantias
					if ($lct->fondopensiones == '' || $lct->fondopensiones == 'null') {
						$pensiones = "Campo Vacio";
					} else {
						if ($lct->fondopensiones == 1) {
							$pensiones = "Colfondos Pensiones y Cesantías";
						} elseif ($lct->fondopensiones == 2) {
							$pensiones = "Colpensiones";
						} elseif ($lct->fondopensiones == 3) {
							$pensiones = "Protección S.A.";
						} elseif ($lct->fondopensiones == 4) {
							$pensiones = "Porvenir S.A.";
						} elseif ($lct->fondopensiones == 5) {
							$pensiones = "Skandia";
						} elseif ($lct->fondocesantias == 6) {
							$cesantia = "Fondo nacional del ahorro";
						}
					}

					//fuma y bebe y frecuencia de ambas
					if ($lct->fuma == '' || $lct->fuma == 'null') {
						$fuma = "Campo Vacio";
					} else {
						if ($lct->fuma == 1) {
							$fuma = "Si";
						} elseif ($lct->fuma == 0) {
							$fuma = "No";
						}
					}
					/////////////////////////
					if ($lct->frecFuma == '' || $lct->frecFuma == 'null') {
						$fumaF = "Campo Vacio";
					} else {
						if ($lct->frecFuma == 1) {
							$fumaF = "Diario";
						} elseif ($lct->frecFuma == 2) {
							$fumaF = "Semanal";
						} elseif ($lct->frecFuma == 3) {
							$fumaF = "Mensual";
						} elseif ($lct->frecFuma == 4) {
							$fumaF = "Casual";
						}
					}
					//***************************
					if ($lct->bebe == '' || $lct->bebe == 'null') {
						$bebe = "Campo Vacio";
					} else {
						if ($lct->bebe == 1) {
							$bebe = "Si";
						} elseif ($lct->bebe == 0) {
							$bebe = "No";
						}
					}
					//////////////////////////
					if ($lct->frecBebe == '' || $lct->frecBebe == 'null') {
						$bebeB = "Campo Vacio";
					} else {
						if ($lct->frecBebe == 1) {
							$bebeB = "Diario";
						} elseif ($lct->frecBebe == 2) {
							$bebeB = "Semanal";
						} elseif ($lct->frecBebe == 3) {
							$bebeB = "Mensual";
						} elseif ($lct->frecBebe == 4) {
							$bebeB = "Casual";
						}
					}

					// Recursos humanos
					if ($lct->cantRRHH == '' || $lct->cantRRHH == 'null') {
						$rHumano = "Campo Vacio";
					} else {

						if ($lct->cantRRHH == 1) {
							$rHumano = "Si";
						} elseif ($lct->cantRRHH == 2) {
							$rHumano = "No";
						}
					}
					// Grupo etnico

					$etnia = "Campo Vacio";

					if (!empty($lct->grupoEtnico) && $lct->grupoEtnico !== 'null') {
						switch ($lct->grupoEtnico) {
							case 1:
								$etnia = "Población Negra";
								break;
							case 2:
								$etnia = "Raizal Palenquera – NARP";
								break;
							case 3:
								$etnia = "Afrocolombiano o Afrodescendiente";
								break;
							case 4:
								$etnia = "Pueblos indígenas";
								break;
							case 5:
								$etnia = "Raizales";
								break;
							case 6:
								$etnia = "Rom ó Gitano";
								break;
							case 7:
								$etnia = "Árabes";
								break;
							case 8:
								$etnia = "Judío";
								break;
							case 9:
								$etnia = "Blanco";
								break;
							case 10:
								$etnia = "Negro";
								break;
							case 11:
								$etnia = "Amerindio";
								break;
							case 12:
								$etnia = "Mulato";
								break;
						}
					}


					// Factor RH

					if ($lct->factorRH == '' || $lct->factorRH == 'null') {
						$factorRH = "Campo Vacio";
					} else {
						if ($lct->factorRH == 1) {
							$factorRH = "O+";
						} elseif ($lct->factorRH == 2) {
							$factorRH = "O-";
						} elseif ($lct->factorRH == 3) {
							$factorRH = "A+";
						} elseif ($lct->factorRH == 4) {
							$factorRH = "A-";
						} elseif ($lct->factorRH == 5) {
							$factorRH = "B+";
						} elseif ($lct->factorRH == 6) {
							$factorRH = "B-";
						} elseif ($lct->factorRH == 7) {
							$factorRH = "ÁB+";
						} elseif ($lct->factorRH == 8) {
							$factorRH = "AB-";
						}
					}

					//Tipo de vivienda

					if ($lct->tipovivienda == '' || $lct->tipovivienda == 'null') {
						$vivienda = "campo vacio";
					} else {
						if ($lct->tipovivienda == 1) {
							$vivienda = "Propia";
						} elseif ($lct->tipovivienda == 2) {
							$vivienda = "Arrenda";
						} elseif ($lct->tipovivienda == 3) {
							$vivienda = "Familiar";
						} elseif ($lct->tipovivienda == 4) {
							$vivienda = "Colectiva";
						}
					}


					$sheet->row($value + 2, [
						$lct->razonsocial,
						$lct->name . ' ' . $lct->last_name,
						$documento,
						$lct->num_documento,
						$lct->email,
						$lct->nombreRegion,
						$lct->nombreCiudad,
						$lct->barrio,
						$lct->direccion,
						$lct->estrato,
						$vivienda,
						$lct->telefono,
						$lct->fecha_nacimiento,
						$lct->id_lugarnace,
						$lct->edad,
						$genero,
						$lct->estadoCivil,
						$lct->nivelDesc,
						$etnia,
						$factorRH,
						$lct->nameC . ' ' . $lct->last_nameC,
						$lct->telC,
						$lct->direccionC,
						$lct->updated_at,
						$lct->estado == 1 ? 'Activo' : 'Inactivo'

					]);
				} //foreacht

			}); // fin primera hoja de excel

			//**********************Segunda hoja del perfilsociodemográfico Datos Laborales*********

			$excel->sheet('Datos Laborales', function ($sheet) use ($company_id) {
				$users = DB::table('users')
					->select(
						'users.id',
						'users.name',
						'users.last_name',
						'users.num_documento',
						'users.email',
						'users.fecha_nacimiento',
						'users.genero',
						'users.nivel_estudio',
						'users.estrato',
						'users.cargo',
						'users.role_id',
						'users.telefono',
						'users.tipo_documento',
						'users.id_region',
						'users.id_ciudad',
						'users.direccion',
						'users.company_id',
						'users.estado',
						'users.nivel_estudio',
						'company.razonsocial',
						'tipoContratos.id',
						'tipoContratos.nombre as contato',
						'areas.id as areasId',
						'areas.name as nameAreas',
						'nivelEstudios.id as id_nivel',
						'nivelEstudios.nivelDesc',
						'v311FDAP_DatosContacto.name as nameC',
						'v311FDAP_DatosContacto.last_name as last_nameC',
						'v311FDAP_DatosContacto.numerodoc as numerodocC',
						'v311FDAP_DatosContacto.email as emailC',
						'v311hacerFormatoActualizacionDatosPersonales.factorRH',
						'v311hacerFormatoActualizacionDatosPersonales.id_estadocivil',
						'v311hacerFormatoActualizacionDatosPersonales.fechaingreso',
						'v311hacerFormatoActualizacionDatosPersonales.areatrabajo',
						'v311hacerFormatoActualizacionDatosPersonales.salario',
						'v311hacerFormatoActualizacionDatosPersonales.tipocontrato',
						'v311hacerFormatoActualizacionDatosPersonales.id_jtrabajo',
						'v311hacerFormatoActualizacionDatosPersonales.jefeinmediato',
						'v311hacerFormatoActualizacionDatosPersonales.recursoshumanos',
						'v311hacerFormatoActualizacionDatosPersonales.cantRRHH',
						'jornadaTrabajo.id_jtrabajo',
						'jornadaTrabajo.nombre as jornada',
						'estadoCivil.id as estadoCivil_id',
						'estadoCivil.nombre as estadoCivil'
					)
					->leftjoin('company', 'company.id', '=', 'users.company_id')
					->leftjoin('v311FDAP_DatosContacto', 'v311FDAP_DatosContacto.id_user', '=', 'users.id')
					->leftjoin('v311hacerFormatoActualizacionDatosPersonales', 'v311hacerFormatoActualizacionDatosPersonales.id_user', '=', 'users.id')
					->leftjoin('jornadaTrabajo', 'jornadaTrabajo.id_jtrabajo', '=', 'v311hacerFormatoActualizacionDatosPersonales.id_jtrabajo')
					->leftjoin('estadoCivil', 'estadoCivil.id', '=', 'v311hacerFormatoActualizacionDatosPersonales.id_estadocivil')
					->leftjoin('tipoContratos', 'tipoContratos.id', '=', 'v311hacerFormatoActualizacionDatosPersonales.tipocontrato')
					->leftjoin('nivelEstudios', 'nivelEstudios.id', '=', 'users.nivel_estudio')
					->leftjoin('areas', 'areas.id', '=', 'users.id_area')
					->where('users.company_id', $company_id)
					->groupBy('users.id')
					->get();

				//dd($users);

				$sheet->row(1, [
					'Empresa',
					'Nombre Empleado',
					'Tipo documento',
					'Nun. Documento',
					'Fecha ingreso a la empresa',
					'Cargo',
					'Area de trabajo ',
					'Salario',
					'tipo de contrato',
					'Jornada laboral',
					'Jefe inmediato',
					'Recursos humano',
					'Cantidad de RRHH',
					'Estado'
				]);

				$sheet->row(1, function ($row) {
					$row->setBackground('#33FFDD');
				}); //ponemos color a la cabecera

				foreach ($users as $value => $lct) {


					//tipo documento
					if ($lct->tipo_documento == '' || $lct->tipo_documento == 'null') {
						$documento = "Campo Vacio";
					} else {
						if ($lct->tipo_documento == 12) {
							$documento = "Tarjeta de Identidad";
						} elseif ($lct->tipo_documento == 13) {
							$documento = "Cédula Ciudadanía";
						} elseif ($lct->tipo_documento == 21) {
							$documento = "Tarjeta de extranjería";
						} elseif ($lct->tipo_documento == 22) {
							$documento = "Cédula de extranjería";
						} elseif ($lct->tipo_documento == 31) {
							$documento = "NIT";
						} elseif ($lct->tipo_documento == 41) {
							$documento = "Pasaporte";
						} elseif ($lct->tipo_documento == 42) {
							$documento = "Tipo documento extranjero";
						} elseif ($lct->tipo_documento == 43) {
							$documento = "Permiso especial de permanencia";
						}
					}

					// Recursos humanos
					if ($lct->recursoshumanos == '' || $lct->recursoshumanos == 'null') {
						$rHumano = "Campo Vacio";
					} else {
						if ($lct->recursoshumanos == 1) {
							$rHumano = "Si";
						} elseif ($lct->recursoshumanos == 2) {
							$rHumano = "No";
						}
					}

					$sheet->row($value + 2, [
						$lct->razonsocial,
						$lct->name . ' ' . $lct->last_name,
						$documento,
						$lct->num_documento,
						$lct->fechaingreso,
						$lct->cargo,
						$lct->nameAreas,
						$lct->salario,
						$lct->contato,
						$lct->jornada,
						$lct->jefeinmediato,
						$rHumano,
						$lct->cantRRHH,
						$lct->estado == 1 ? 'Activo' : 'Inactivo'

					]);
				} //foreacht

			}); // fin segunda hoja de excel
			//***********************tercera hoja del perfil Sociodemográfico Datos de Salud *******
			$excel->sheet('Datos de Salud', function ($sheet) use ($company_id) {
				$users = DB::table('users')
					->select(
						'users.id',
						'users.name',
						'users.last_name',
						'users.num_documento',
						'users.email',
						'users.fecha_nacimiento',
						'users.genero',
						'users.nivel_estudio',
						'users.estrato',
						'users.cargo',
						'users.role_id',
						'users.telefono',
						'users.tipo_documento',
						'users.id_region',
						'users.id_ciudad',
						'users.direccion',
						'users.company_id',
						'users.estado',
						'ciudades.nombre as nombreCiudad',
						'ciudades.idCiudad',
						'company.razonsocial',
						'region.idRegion',
						'region.nombre as nombreRegion',
						'nivelEstudios.id as id_nivel',
						'nivelEstudios.nivelDesc',
						'v311hacerFormatoActualizacionDatosPersonales.eps',
						'v311hacerFormatoActualizacionDatosPersonales.fondopensiones',
						'v311hacerFormatoActualizacionDatosPersonales.fondocesantias',
						'v311hacerFormatoActualizacionDatosPersonales.arl',
						'v311hacerFormatoActualizacionDatosPersonales.cajacompensacion',
						'v311hacerFormatoActualizacionDatosPersonales.padeceenfermedad',
						'v311hacerFormatoActualizacionDatosPersonales.fuma',
						'v311hacerFormatoActualizacionDatosPersonales.frecFuma',
						'v311hacerFormatoActualizacionDatosPersonales.bebe',
						'v311hacerFormatoActualizacionDatosPersonales.frecBebe',
						'v311hacerFormatoActualizacionDatosPersonales.altura',
						'v311hacerFormatoActualizacionDatosPersonales.peso',
						'v311hacerFormatoActualizacionDatosPersonales.tallacamisa',
						'v311hacerFormatoActualizacionDatosPersonales.tallapantalon',
						'v311hacerFormatoActualizacionDatosPersonales.tallazapatos',
						'jornadaTrabajo.id_jtrabajo',
						'jornadaTrabajo.nombre as jornada',
						'estadoCivil.id as estadoCivil_id',
						'estadoCivil.nombre as estadoCivil',
						'arl.id as id_arl',
						'arl.nombre as arlName',
						'eps_arl.id_tipo_admin',
						'eps_arl.nombre as epsName',
						'cajaCompensacion.id as id_compesacion',
						'cajaCompensacion.nombre as nameCompensacion'
					)

					->leftjoin('ciudades', 'ciudades.idCiudad', '=', 'users.id_ciudad')
					->leftjoin('region', 'region.idRegion', '=', 'users.id_region')
					->leftjoin('company', 'company.id', '=', 'users.company_id')
					->leftjoin('v311FDAP_DatosContacto', 'v311FDAP_DatosContacto.id_user', '=', 'users.id')
					->leftjoin('v311hacerFormatoActualizacionDatosPersonales', 'v311hacerFormatoActualizacionDatosPersonales.id_user', '=', 'users.id')
					->leftjoin('jornadaTrabajo', 'jornadaTrabajo.id_jtrabajo', '=', 'v311hacerFormatoActualizacionDatosPersonales.id_jtrabajo')
					->leftjoin('eps_arl', 'eps_arl.id_tipo_admin', '=', 'v311hacerFormatoActualizacionDatosPersonales.eps')
					->leftjoin('arl', 'arl.id', '=', 'v311hacerFormatoActualizacionDatosPersonales.arl')
					->leftjoin('estadoCivil', 'estadoCivil.id', '=', 'v311hacerFormatoActualizacionDatosPersonales.id_estadocivil')
					->leftjoin('tipoContratos', 'tipoContratos.id', '=', 'v311hacerFormatoActualizacionDatosPersonales.tipocontrato')
					->leftjoin('nivelEstudios', 'nivelEstudios.id', '=', 'users.nivel_estudio')
					->leftjoin('cajaCompensacion', 'cajaCompensacion.id', '=', 'v311hacerFormatoActualizacionDatosPersonales.cajacompensacion')
					->where('users.company_id', $company_id)
					->groupBy('users.id')
					->get();

				//dd($users);

				$sheet->row(1, [
					'Empresa',
					'Nombre Empleado',
					'Tipo documento',
					'Num. Documento',
					'EPS',
					'ARL',
					'Fondo de pensiones',
					'Fondo de cesantias',
					'Caja de compensaciones',
					'Padece enfermedades',
					'Fuma',
					'Frecuencia',
					'Bebe',
					'Fecuencia',
					'Altura',
					'Peso',
					'talla camisa',
					'talla pantalón',
					'talla calzado',
					'Estado'
				]);

				$sheet->row(1, function ($row) {
					$row->setBackground('#33E0FF');
				}); //ponemos color a la cabecera

				foreach ($users as $value => $lct) {

					//tipo documento
					if ($lct->tipo_documento == '' || $lct->tipo_documento == 'null') {
						$documento = "Campo Vacio";
					} else {
						if ($lct->tipo_documento == 12) {
							$documento = "Tarjeta de Identidad";
						} elseif ($lct->tipo_documento == 13) {
							$documento = "Cédula Ciudadanía";
						} elseif ($lct->tipo_documento == 21) {
							$documento = "Tarjeta de extranjería";
						} elseif ($lct->tipo_documento == 22) {
							$documento = "Cédula de extranjería";
						} elseif ($lct->tipo_documento == 31) {
							$documento = "NIT";
						} elseif ($lct->tipo_documento == 41) {
							$documento = "Pasaporte";
						} elseif ($lct->tipo_documento == 42) {
							$documento = "Tipo documento extranjero";
						} elseif ($lct->tipo_documento == 43) {
							$documento = "Permiso especial de permanencia";
						}
					}


					// Caja de compensaciones
					/*			if ($lct->cajacompensacion == '' || $lct->cajacompensacion == 'null') {
						$compensaciones = "Campo Vacio";
					} else {

						if ($lct->cajacompensacion == 1) {
							$compensaciones = "Comfenalco";
						} elseif ($lct->cajacompensacion == 2) {
							$compensaciones = "Comfama";
						} elseif ($lct->cajacompensacion == 3) {
							$compensaciones = "Compensar";
						}
					}*/

					//Fondo pensiones
					if ($lct->fondopensiones == '' || $lct->fondopensiones == 'null') {
						$pensiones = "Campo Vacio";
					} else {

						if ($lct->fondopensiones == 1) {
							$pensiones = "Colfondos Pensiones y Cesantías";
						} elseif ($lct->fondopensiones == 2) {
							$pensiones = "Colpensiones";
						} elseif ($lct->fondopensiones == 3) {
							$pensiones = "Protección S.A.";
						} elseif ($lct->fondopensiones == 4) {
							$pensiones = "Porvenir S.A.";
						} elseif ($lct->fondopensiones == 5) {
							$pensiones = "Skandia";
						}
					}

					//Fondo de Cesantías
					if ($lct->fondocesantias == '' || $lct->fondocesantias == 'null') {
						$cesantia = "Campo Vacio";
					} else {

						if ($lct->fondocesantias == 1) {
							$cesantia = "Colfondos Pensiones y Cesantías";
						} elseif ($lct->fondocesantias == 2) {
							$cesantia = "Colpensiones";
						} elseif ($lct->fondocesantias == 3) {
							$cesantia = "Protección S.A.";
						} elseif ($lct->fondocesantias == 4) {
							$cesantia = "Porvenir S.A.";
						} elseif ($lct->fondocesantias == 5) {
							$cesantia = "Skandia";
						} elseif ($lct->fondocesantias == 6) {
							$cesantia = "Fondo nacional del ahorro";
						}
					}

					//fuma y bebe y frecuencia de ambas
					if ($lct->fuma == '' || $lct->fuma == 'null') {
						$fuma = "Campo Vacio";
					} else {
						if ($lct->fuma == 1) {
							$fuma = "Si";
						} elseif ($lct->fuma == 0) {
							$fuma = "No";
						}
					}
					/////////////////////////
					if ($lct->frecFuma == '' || $lct->frecFuma == 'null') {
						$fumaF = "Campo Vacio";
					} else {
						if ($lct->frecFuma == 1) {
							$fumaF = "Diario";
						} elseif ($lct->frecFuma == 2) {
							$fumaF = "Semanal";
						} elseif ($lct->frecFuma == 3) {
							$fumaF = "Mensual";
						} elseif ($lct->frecFuma == 4) {
							$fumaF = "Casual";
						}
					}
					//***************************
					if ($lct->bebe == '' || $lct->bebe == 'null') {
						$bebe = "Campo Vacio";
					} else {
						if ($lct->bebe == 1) {
							$bebe = "Si";
						} elseif ($lct->bebe == 0) {
							$bebe = "No";
						}
					}
					//////////////////////////
					if ($lct->frecBebe == '' || $lct->frecBebe == 'null') {
						$bebeB = "Campo Vacio";
					} else {
						if ($lct->frecBebe == 1) {
							$bebeB = "Diario";
						} elseif ($lct->frecBebe == 2) {
							$bebeB = "Semanal";
						} elseif ($lct->frecBebe == 3) {
							$bebeB = "Mensual";
						} elseif ($lct->frecBebe == 4) {
							$bebeB = "Casual";
						}
					}



					$sheet->row($value + 2, [
						$lct->razonsocial,
						$lct->name . ' ' . $lct->last_name,
						$documento,
						$lct->num_documento,
						$lct->epsName,
						$lct->arlName,
						$pensiones,
						$cesantia,
						$lct->nameCompensacion,
						$lct->padeceenfermedad,
						$fuma,
						$fumaF,
						$bebe,
						$bebeB,
						$lct->altura,
						$lct->peso,
						$lct->tallacamisa,
						$lct->tallapantalon,
						$lct->tallazapatos,
						$lct->estado == 1 ? 'Activo' : 'Inactivo'

					]);
				} //foreacht

			}); // fin tercera hoja de excel

			//***********************Cuarta hoja del perfil Sociodemográfico Certificados*******
			$excel->sheet('Certificados', function ($sheet) use ($company_id) {
				$users = DB::table('users')
					->select(
						'users.id',
						'users.name',
						'users.last_name',
						'users.num_documento',
						'users.email',
						'users.fecha_nacimiento',
						'users.genero',
						'users.nivel_estudio',
						'users.estrato',
						'users.cargo',
						'users.role_id',
						'users.telefono',
						'users.tipo_documento',
						'users.id_region',
						'users.id_ciudad',
						'users.direccion',
						'users.company_id',
						'users.estado',
						'users.nivel_estudio',
						'company.razonsocial',
						'archivosPerfilsociodemografico.certAlt',
						'archivosPerfilsociodemografico.certAltTipo',
						'archivosPerfilsociodemografico.certAltFechaC',
						'archivosPerfilsociodemografico.certAltFechaA',
						'archivosPerfilsociodemografico.entidadCertAlt',
						'archivosPerfilsociodemografico.certAltArchivo',
						'archivosPerfilsociodemografico.urlAlt',
						'archivosPerfilsociodemografico.certCal',
						'archivosPerfilsociodemografico.entidadCertCal',
						'archivosPerfilsociodemografico.certCalTipo',
						'archivosPerfilsociodemografico.certCalFechaC',
						'archivosPerfilsociodemografico.certCalFechaA',
						'archivosPerfilsociodemografico.certCalArchivo',
						'archivosPerfilsociodemografico.urlCal',
						'archivosPerfilsociodemografico.certMet',
						'archivosPerfilsociodemografico.entidadCertMet',
						'archivosPerfilsociodemografico.certMetTipo',
						'archivosPerfilsociodemografico.certMetFechaC',
						'archivosPerfilsociodemografico.certMetFechaA',
						'archivosPerfilsociodemografico.certMetArchivo',
						'archivosPerfilsociodemografico.urlMet',
						'archivosPerfilsociodemografico.certEsp',
						'archivosPerfilsociodemografico.entidadCertEsp',
						'archivosPerfilsociodemografico.certEspTipo',
						'archivosPerfilsociodemografico.certEspFechaC',
						'archivosPerfilsociodemografico.certEspFechaA',
						'archivosPerfilsociodemografico.certEspArchivo',
						'archivosPerfilsociodemografico.urlEsp',
						'archivosPerfilsociodemografico.certQui',
						'archivosPerfilsociodemografico.entidadCertQui',
						'archivosPerfilsociodemografico.certQuiTipo',
						'archivosPerfilsociodemografico.certQuiFechaC',
						'archivosPerfilsociodemografico.certQuiFechaA',
						'archivosPerfilsociodemografico.certQuiArchivo',
						'archivosPerfilsociodemografico.urlQui',
						'archivosPerfilsociodemografico.certIza',
						'archivosPerfilsociodemografico.entidadCertIza',
						'archivosPerfilsociodemografico.certIzaTipo',
						'archivosPerfilsociodemografico.certIzaFechaC',
						'archivosPerfilsociodemografico.certIzaFechaA',
						'archivosPerfilsociodemografico.certIzaArchivo',
						'archivosPerfilsociodemografico.urlIza'
					)

					->leftjoin('company', 'company.id', '=', 'users.company_id')
					->leftjoin('v311hacerFormatoActualizacionDatosPersonales', 'v311hacerFormatoActualizacionDatosPersonales.id_user', '=', 'users.id')
					->leftjoin('archivosPerfilsociodemografico', 'archivosPerfilsociodemografico.id_v311hacer', '=', 'users.id')
					->where('users.company_id', $company_id)
					->get();


				$sheet->row(1, [
					'Empresa',
					'Nombre Empleado',
					'Tipo Documento',
					'Num.Documento',
					'Certificado en alturas',
					'Entidad certificadora',
					'Tipo de certificado',
					'Fecha de certificación',
					'Fecha de actualizacion',
					'Certificado en tabajo en caliente',
					'Entidad certificadora',
					'Tipo de certificado',
					'Fecha de certificación',
					'Fecha de actualizacion',
					'Certificado en espacios confinados',
					'Entidad certificadora',
					'Tipo de certificado',
					'Fecha de certificación',
					'Fecha de actualizacion',
					'Certificado Químicos peligrosos',
					'Entidad certificadora',
					'Tipo de certificado',
					'Fecha de certificación',
					'Fecha de actualizacion',
					'Certificado alta exposición metales pesados',
					'Entidad certificadora',
					'Tipo de certificado',
					'Fecha de certificación',
					'Fecha de actualizacion',
					'Certificado izaje de carga',
					'Entidad certificadora',
					'Tipo de certificado',
					'Fecha de certificación',
					'Fecha de actualizacion',
					'Estado'
				]);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				foreach ($users as $value => $lct) {

					//tipo documento

					if ($lct->tipo_documento == '' || $lct->tipo_documento == 'null') {
						$documento = "Campo Vacio";
					} else {
						if ($lct->tipo_documento == 12) {
							$documento = "Tarjeta de Identidad";
						} elseif ($lct->tipo_documento == 13) {
							$documento = "Cédula Ciudadanía";
						} elseif ($lct->tipo_documento == 21) {
							$documento = "Tarjeta de extranjería";
						} elseif ($lct->tipo_documento == 22) {
							$documento = "Cédula de extranjería";
						} elseif ($lct->tipo_documento == 31) {
							$documento = "NIT";
						} elseif ($lct->tipo_documento == 41) {
							$documento = "Pasaporte";
						} elseif ($lct->tipo_documento == 42) {
							$documento = "Tipo documento extranjero";
						} elseif ($lct->tipo_documento == 43) {
							$documento = "Permiso especial de permanencia";
						}
					}

					// Certifica Certificado de Alturas
					if ($lct->certAlt == 'on') {
						$certAlt = "Si";
					} else {
						$certAlt = "No";
					}

					//Certificado de Trabajos en Caliente
					if ($lct->certCal == 'on') {
						$certCal = "Si";
					} else {
						$certCal = "No";
					}

					//Certifica Alta Exposición Metales Pesados 
					if ($lct->certMet == 'on') {
						$certMet = "Si";
					} else {
						$certMet = "No";
					}

					//"Entidad Certificado de Espacios Confinados
					if ($lct->certEsp == 'on') {
						$certEsp = "Si";
					} else {
						$certEsp = "No";
					}

					//Certificado de Químicos Peligrosos
					if ($lct->certQui == 'on') {
						$certQui = "Si";
					} else {
						$certQui = "No";
					}

					//Entidad  Izaje de Carga
					if ($lct->certIza == 'on') {
						$certIza = "Si";
					} else {
						$certIza = "No";
					}

					$sheet->row($value + 2, [
						$lct->razonsocial,
						$lct->name . ' ' . $lct->last_name,
						$documento,
						$lct->num_documento,
						$certAlt,
						$lct->entidadCertAlt,
						$lct->certAltTipo,
						$lct->certAltFechaC,
						$lct->certAltFechaA,
						$certCal,
						$lct->entidadCertCal,
						$lct->certCalTipo,
						$lct->certCalFechaC,
						$lct->certCalFechaA,
						$certMet,
						$lct->entidadCertMet,
						$lct->certMetTipo,
						$lct->certMetFechaC,
						$lct->certMetFechaA,
						$certEsp,
						$lct->entidadCertEsp,
						$lct->certEspTipo,
						$lct->certEspFechaC,
						$lct->certEspFechaA,
						$certQui,
						$lct->entidadCertQui,
						$lct->certQuiTipo,
						$lct->certQuiFechaC,
						$lct->certQuiFechaA,
						$certIza,
						$lct->entidadCertIza,
						$lct->certIzaTipo,
						$lct->certIzaFechaC,
						$lct->certIzaFechaA
					]);
				} //foreacht

			}); // fin de la tercera hoja

		})->export('xls');
	}

	//************************** FIN DE EXPORTACIÓN DATOS DE USURIO Y PERFIL SOCIODEMOGRÁFICO por company****************************	
	//INICIO DE EXPORTACIÓN DE USUARIOS  PERFIL SOCIODEMOGRÁFICO por empleado	

	public function exportUsers($id_users)
	{
		Excel::create('Perfilsociodemografico empleado', function ($excel) use ($id_users) {
			
			//****hoja 1 del perfir sociodemografico Datos Personales*****
			$excel->sheet('Datos del Empleado', function ($sheet) use ($id_users) {
				$users = DB::table('users')
					->select(
						'users.id',
						'users.name',
						'users.last_name',
						'users.num_documento',
						'users.email',
						'users.fecha_nacimiento',
						'users.genero',
						'users.nivel_estudio',
						'users.estrato',
						'users.cargo',
						'users.role_id',
						'users.telefono',
						'users.tipo_documento',
						'users.id_region',
						'users.id_ciudad',
						'users.direccion',
						'users.company_id',
						'users.estado',
						'users.nivel_estudio',
						'ciudades.nombre as nombreCiudad',
						'ciudades.idCiudad',
						'company.razonsocial',
						'region.idRegion',
						'region.nombre as nombreRegion',
						'areas.id as areasId',
						'areas.name as nameAreas',
						'nivelEstudios.id as id_nivel',
						'nivelEstudios.nivelDesc',
						'v311FDAP_DatosContacto.name as nameC',
						'v311FDAP_DatosContacto.last_name as last_nameC',
						'v311FDAP_DatosContacto.numerodoc as numerodocC',
						'v311FDAP_DatosContacto.email as emailC',
						'v311FDAP_DatosContacto.tel as telC',
						'v311FDAP_DatosContacto.direccion as direccionC',
						'v311hacerFormatoActualizacionDatosPersonales.id_lugarexp',
						'v311hacerFormatoActualizacionDatosPersonales.barrio',
						'v311hacerFormatoActualizacionDatosPersonales.tipovivienda',
						'v311hacerFormatoActualizacionDatosPersonales.id_lugarnace',
						'v311hacerFormatoActualizacionDatosPersonales.edad',
						'v311hacerFormatoActualizacionDatosPersonales.factorRH',
						'v311hacerFormatoActualizacionDatosPersonales.id_estadocivil',
						'v311hacerFormatoActualizacionDatosPersonales.fechaingreso',
						'v311hacerFormatoActualizacionDatosPersonales.areatrabajo',
						'v311hacerFormatoActualizacionDatosPersonales.salario',
						'v311hacerFormatoActualizacionDatosPersonales.tipocontrato',
						'v311hacerFormatoActualizacionDatosPersonales.id_jtrabajo',
						'v311hacerFormatoActualizacionDatosPersonales.grupoEtnico',
						'v311hacerFormatoActualizacionDatosPersonales.jefeinmediato',
						'v311hacerFormatoActualizacionDatosPersonales.recursoshumanos',
						'v311hacerFormatoActualizacionDatosPersonales.cantRRHH',
						'v311hacerFormatoActualizacionDatosPersonales.eps',
						'v311hacerFormatoActualizacionDatosPersonales.fondopensiones',
						'v311hacerFormatoActualizacionDatosPersonales.fondocesantias',
						'v311hacerFormatoActualizacionDatosPersonales.arl',
						'v311hacerFormatoActualizacionDatosPersonales.cajacompensacion',
						'v311hacerFormatoActualizacionDatosPersonales.padeceenfermedad',
						'v311hacerFormatoActualizacionDatosPersonales.fuma',
						'v311hacerFormatoActualizacionDatosPersonales.frecFuma',
						'v311hacerFormatoActualizacionDatosPersonales.bebe',
						'v311hacerFormatoActualizacionDatosPersonales.frecBebe',
						'v311hacerFormatoActualizacionDatosPersonales.altura',
						'v311hacerFormatoActualizacionDatosPersonales.peso',
						'v311hacerFormatoActualizacionDatosPersonales.tallacamisa',
						'v311hacerFormatoActualizacionDatosPersonales.tallapantalon',
						'v311hacerFormatoActualizacionDatosPersonales.tallazapatos',
						'v311hacerFormatoActualizacionDatosPersonales.updated_at',
						'jornadaTrabajo.id_jtrabajo',
						'jornadaTrabajo.nombre as jornada',
						'estadoCivil.id as estadoCivil_id',
						'estadoCivil.nombre as estadoCivil',
						'arl.id as id_arl',
						'arl.nombre as arlName',
						'eps_arl.id_tipo_admin',
						'eps_arl.nombre as epsName',
						'cajaCompensacion.id as id_compesacion',
						'cajaCompensacion.nombre as nameCompensacion'
					)

					->leftjoin('ciudades', 'ciudades.idCiudad', '=', 'users.id_ciudad')
					->leftjoin('region', 'region.idRegion', '=', 'users.id_region')
					->leftjoin('company', 'company.id', '=', 'users.company_id')
					->leftjoin('v311FDAP_DatosContacto', 'v311FDAP_DatosContacto.id_user', '=', 'users.id')
					->leftjoin('v311hacerFormatoActualizacionDatosPersonales', 'v311hacerFormatoActualizacionDatosPersonales.id_user', '=', 'users.id')
					->leftjoin('jornadaTrabajo', 'jornadaTrabajo.id_jtrabajo', '=', 'v311hacerFormatoActualizacionDatosPersonales.id_jtrabajo')
					->leftjoin('eps_arl', 'eps_arl.id_tipo_admin', '=', 'v311hacerFormatoActualizacionDatosPersonales.eps')
					->leftjoin('arl', 'arl.id', '=', 'v311hacerFormatoActualizacionDatosPersonales.arl')
					->leftjoin('estadoCivil', 'estadoCivil.id', '=', 'v311hacerFormatoActualizacionDatosPersonales.id_estadocivil')
					->leftjoin('tipoContratos', 'tipoContratos.id', '=', 'v311hacerFormatoActualizacionDatosPersonales.tipocontrato')
					->leftjoin('nivelEstudios', 'nivelEstudios.id', '=', 'users.nivel_estudio')
					->leftjoin('areas', 'areas.id', '=', 'users.id_area')
					->leftjoin('cajaCompensacion', 'cajaCompensacion.id', '=', 'v311hacerFormatoActualizacionDatosPersonales.cajacompensacion')
					->where('users.id', $id_users)
					->groupBy('users.id')
					->get();



				$sheet->row(1, [
					'Empresa',
					'Nombre Empleado',
					'Tipo documento',
					'Num. Documento',
					'Correo',
					'Región',
					'Ciudad',
					'Barrio',
					'Domicilio',
					'Estrato',
					'Tipo de vivienda',
					'Teléfono',
					'Fecha nacimiento',
					'Lugar de nacimiento',
					'edad',
					'Genero',
					'Estado civil',
					'Nivel de estudios',
					'Grupo etnico',
					'Factor R.H.',
					'Persona de Contacto',
					'Teléfono persona contacto',
					'Dirección persona contacto',
					'Última actualización',
					'Estado'
				]);

				$sheet->row(1, function ($row) {
					$row->setBackground('#A5E9E7 ');
				}); //ponemos color a la cabecera

				foreach ($users as $value => $lct) {


					//tipo documento
					if ($lct->tipo_documento == '' || $lct->tipo_documento == 'null') {
						$documento = "campo vacio";
					} else {
						if ($lct->tipo_documento == 12) {
							$documento = "Tarjeta de Identidad";
						} elseif ($lct->tipo_documento == 13) {
							$documento = "Cédula Ciudadanía";
						} elseif ($lct->tipo_documento == 21) {
							$documento = "Tarjeta de extranjería";
						} elseif ($lct->tipo_documento == 22) {
							$documento = "Cédula de extranjería";
						} elseif ($lct->tipo_documento == 31) {
							$documento = "NIT";
						} elseif ($lct->tipo_documento == 41) {
							$documento = "Pasaporte";
						} elseif ($lct->tipo_documento == 42) {
							$documento = "Tipo documento extranjero";
						} elseif ($lct->tipo_documento == 43) {
							$documento = "Permiso especial de permanencia";
						}
					}

					// Genero

					if ($lct->genero == '' || $lct->genero == 'null') {
						$genero = 'campo vacio';
					} else {
						if ($lct->genero == 1) {
							$genero = "Femenino";
						} elseif ($lct->genero == 2) {
							$genero = "Masculino";
						} elseif ($lct->genero == 3) {
							$genero = "Indefinido";
						}
					}

					// Nivel estudio
					/*    if(assert($lct->nivel_estudio)) {
					$nivel="Campo Vacio";
					}else{
					if ($lct->nivel_estudio==1) {
					$nivel="Educación básica";
					}elseif ($lct->nivel_estudio==2) {
					$nivel="Bachiller";
					}elseif ($lct->nivel_estudio==3) {
					$nivel="Técnico";
					}elseif ($lct->nivel_estudio==4) {
					$nivel="Técnologo";
					}elseif ($lct->nivel_estudio==5) {
					$nivel="Universitario";
					}elseif ($lct->nivel_estudio==6) {
					$nivel="Especialista ";
					}elseif ($lct->nivel_estudio==7) {
					$nivel="Magister";
					}elseif ($lct->nivel_estudio==8) {
					$nivel="Doctorado";
					}elseif ($lct->nivel_estudio==9) {
					$nivel="Pos doctorado";
					}}*/

					// Caja de compensaciones
					/*if ($lct->cajacompensacion == '' || $lct->cajacompensacion == 'null') {
						$compensaciones = "Campo Vacio";
					} else {
						if ($lct->cajacompensacion == 1) {
							$compensaciones = "Comfenalco";
						} elseif ($lct->cajacompensacion == 2) {
							$compensaciones = "Comfama";
						} elseif ($lct->cajacompensacion == 3) {
							$compensaciones = "Compensar";
						}
					}*/

					//Fondocesantias
					if ($lct->fondopensiones == '' || $lct->fondopensiones == 'null') {
						$pensiones = "Campo Vacio";
					} else {
						if ($lct->fondopensiones == 1) {
							$pensiones = "Colfondos Pensiones y Cesantías";
						} elseif ($lct->fondopensiones == 2) {
							$pensiones = "Colpensiones";
						} elseif ($lct->fondopensiones == 3) {
							$pensiones = "Protección S.A.";
						} elseif ($lct->fondopensiones == 4) {
							$pensiones = "Porvenir S.A.";
						} elseif ($lct->fondopensiones == 5) {
							$pensiones = "Skandia";
						} elseif ($lct->fondocesantias == 6) {
							$cesantia = "Fondo nacional del ahorro";
						}
					}

					//fuma y bebe y frecuencia de ambas
					if ($lct->fuma == '' || $lct->fuma == 'null') {
						$fuma = "Campo Vacio";
					} else {
						if ($lct->fuma == 1) {
							$fuma = "Si";
						} elseif ($lct->fuma == 0) {
							$fuma = "No";
						}
					}
					/////////////////////////
					if ($lct->frecFuma == '' || $lct->frecFuma == 'null') {
						$fumaF = "Campo Vacio";
					} else {
						if ($lct->frecFuma == 1) {
							$fumaF = "Diario";
						} elseif ($lct->frecFuma == 2) {
							$fumaF = "Semanal";
						} elseif ($lct->frecFuma == 3) {
							$fumaF = "Mensual";
						} elseif ($lct->frecFuma == 4) {
							$fumaF = "Casual";
						}
					}
					//***************************
					if ($lct->bebe == '' || $lct->bebe == 'null') {
						$bebe = "Campo Vacio";
					} else {
						if ($lct->bebe == 1) {
							$bebe = "Si";
						} elseif ($lct->bebe == 0) {
							$bebe = "No";
						}
					}
					//////////////////////////
					if ($lct->frecBebe == '' || $lct->frecBebe == 'null') {
						$bebeB = "Campo Vacio";
					} else {
						if ($lct->frecBebe == 1) {
							$bebeB = "Diario";
						} elseif ($lct->frecBebe == 2) {
							$bebeB = "Semanal";
						} elseif ($lct->frecBebe == 3) {
							$bebeB = "Mensual";
						} elseif ($lct->frecBebe == 4) {
							$bebeB = "Casual";
						}
					}

					// Recursos humanos
					if ($lct->cantRRHH == '' || $lct->cantRRHH == 'null') {
						$rHumano = "Campo Vacio";
					} else {

						if ($lct->cantRRHH == 1) {
							$rHumano = "Si";
						} elseif ($lct->cantRRHH == 2) {
							$rHumano = "No";
						}
					}
					// Grupo etnico

					$etnia = "Campo Vacio";

					if (!empty($lct->grupoEtnico) && $lct->grupoEtnico !== 'null') {
						switch ($lct->grupoEtnico) {
							case 1:
								$etnia = "Población Negra";
								break;
							case 2:
								$etnia = "Raizal Palenquera – NARP";
								break;
							case 3:
								$etnia = "Afrocolombiano o Afrodescendiente";
								break;
							case 4:
								$etnia = "Pueblos indígenas";
								break;
							case 5:
								$etnia = "Raizales";
								break;
							case 6:
								$etnia = "Rom ó Gitano";
								break;
							case 7:
								$etnia = "Árabes";
								break;
							case 8:
								$etnia = "Judío";
								break;
							case 9:
								$etnia = "Blanco";
								break;
							case 10:
								$etnia = "Negro";
								break;
							case 11:
								$etnia = "Amerindio";
								break;
							case 12:
								$etnia = "Mulato";
								break;
						}
					}


					// Factor RH

					if ($lct->factorRH == '' || $lct->factorRH == 'null') {
						$factorRH = "Campo Vacio";
					} else {
						if ($lct->factorRH == 1) {
							$factorRH = "O+";
						} elseif ($lct->factorRH == 2) {
							$factorRH = "O-";
						} elseif ($lct->factorRH == 3) {
							$factorRH = "A+";
						} elseif ($lct->factorRH == 4) {
							$factorRH = "A-";
						} elseif ($lct->factorRH == 5) {
							$factorRH = "B+";
						} elseif ($lct->factorRH == 6) {
							$factorRH = "B-";
						} elseif ($lct->factorRH == 7) {
							$factorRH = "ÁB+";
						} elseif ($lct->factorRH == 8) {
							$factorRH = "AB-";
						}
					}

					//Tipo de vivienda

					if ($lct->tipovivienda == '' || $lct->tipovivienda == 'null') {
						$vivienda = "campo vacio";
					} else {
						if ($lct->tipovivienda == 1) {
							$vivienda = "Propia";
						} elseif ($lct->tipovivienda == 2) {
							$vivienda = "Arrenda";
						} elseif ($lct->tipovivienda == 3) {
							$vivienda = "Familiar";
						} elseif ($lct->tipovivienda == 4) {
							$vivienda = "Colectiva";
						}
					}


					$sheet->row($value + 2, [
						$lct->razonsocial,
						$lct->name . ' ' . $lct->last_name,
						$documento,
						$lct->num_documento,
						$lct->email,
						$lct->nombreRegion,
						$lct->nombreCiudad,
						$lct->barrio,
						$lct->direccion,
						$lct->estrato,
						$vivienda,
						$lct->telefono,
						$lct->fecha_nacimiento,
						$lct->id_lugarnace,
						$lct->edad,
						$genero,
						$lct->estadoCivil,
						$lct->nivelDesc,
						$etnia,
						$factorRH,
						$lct->nameC . ' ' . $lct->last_nameC,
						$lct->telC,
						$lct->direccionC,
						$lct->updated_at,
						$lct->estado == 1 ? 'Activo' : 'Inactivo'

					]);
				} //foreacht

			}); // fin primera hoja de excel

			//**********************Segunda hoja del perfilsociodemográfico Datos Laborales*********

			$excel->sheet('Datos Laborales', function ($sheet) use ($id_users) {
				$users = DB::table('users')
					->select(
						'users.id',
						'users.name',
						'users.last_name',
						'users.num_documento',
						'users.email',
						'users.fecha_nacimiento',
						'users.genero',
						'users.nivel_estudio',
						'users.estrato',
						'users.cargo',
						'users.role_id',
						'users.telefono',
						'users.tipo_documento',
						'users.id_region',
						'users.id_ciudad',
						'users.direccion',
						'users.company_id',
						'users.estado',
						'users.nivel_estudio',
						'company.razonsocial',
						'tipoContratos.id',
						'tipoContratos.nombre as contato',
						'areas.id as areasId',
						'areas.name as nameAreas',
						'nivelEstudios.id as id_nivel',
						'nivelEstudios.nivelDesc',
						'v311FDAP_DatosContacto.name as nameC',
						'v311FDAP_DatosContacto.last_name as last_nameC',
						'v311FDAP_DatosContacto.numerodoc as numerodocC',
						'v311FDAP_DatosContacto.email as emailC',
						'v311hacerFormatoActualizacionDatosPersonales.factorRH',
						'v311hacerFormatoActualizacionDatosPersonales.id_estadocivil',
						'v311hacerFormatoActualizacionDatosPersonales.fechaingreso',
						'v311hacerFormatoActualizacionDatosPersonales.areatrabajo',
						'v311hacerFormatoActualizacionDatosPersonales.salario',
						'v311hacerFormatoActualizacionDatosPersonales.tipocontrato',
						'v311hacerFormatoActualizacionDatosPersonales.id_jtrabajo',
						'v311hacerFormatoActualizacionDatosPersonales.jefeinmediato',
						'v311hacerFormatoActualizacionDatosPersonales.recursoshumanos',
						'v311hacerFormatoActualizacionDatosPersonales.cantRRHH',
						'jornadaTrabajo.id_jtrabajo',
						'jornadaTrabajo.nombre as jornada',
						'estadoCivil.id as estadoCivil_id',
						'estadoCivil.nombre as estadoCivil'
					)
					->leftjoin('company', 'company.id', '=', 'users.company_id')
					->leftjoin('v311FDAP_DatosContacto', 'v311FDAP_DatosContacto.id_user', '=', 'users.id')
					->leftjoin('v311hacerFormatoActualizacionDatosPersonales', 'v311hacerFormatoActualizacionDatosPersonales.id_user', '=', 'users.id')
					->leftjoin('jornadaTrabajo', 'jornadaTrabajo.id_jtrabajo', '=', 'v311hacerFormatoActualizacionDatosPersonales.id_jtrabajo')
					->leftjoin('estadoCivil', 'estadoCivil.id', '=', 'v311hacerFormatoActualizacionDatosPersonales.id_estadocivil')
					->leftjoin('tipoContratos', 'tipoContratos.id', '=', 'v311hacerFormatoActualizacionDatosPersonales.tipocontrato')
					->leftjoin('nivelEstudios', 'nivelEstudios.id', '=', 'users.nivel_estudio')
					->leftjoin('areas', 'areas.id', '=', 'users.id_area')
					->where('users.id', $id_users)
					->groupBy('users.id')
					->get();

				//dd($users);

				$sheet->row(1, [
					'Empresa',
					'Nombre Empleado',
					'Tipo documento',
					'Nun. Documento',
					'Fecha ingreso a la empresa',
					'Cargo',
					'Area de trabajo ',
					'Salario',
					'tipo de contrato',
					'Jornada laboral',
					'Jefe inmediato',
					'Recursos humano',
					'Cantidad de RRHH',
					'Estado'
				]);

				$sheet->row(1, function ($row) {
					$row->setBackground('#33FFDD');
				}); //ponemos color a la cabecera

				foreach ($users as $value => $lct) {


					//tipo documento
					if ($lct->tipo_documento == '' || $lct->tipo_documento == 'null') {
						$documento = "Campo Vacio";
					} else {
						if ($lct->tipo_documento == 12) {
							$documento = "Tarjeta de Identidad";
						} elseif ($lct->tipo_documento == 13) {
							$documento = "Cédula Ciudadanía";
						} elseif ($lct->tipo_documento == 21) {
							$documento = "Tarjeta de extranjería";
						} elseif ($lct->tipo_documento == 22) {
							$documento = "Cédula de extranjería";
						} elseif ($lct->tipo_documento == 31) {
							$documento = "NIT";
						} elseif ($lct->tipo_documento == 41) {
							$documento = "Pasaporte";
						} elseif ($lct->tipo_documento == 42) {
							$documento = "Tipo documento extranjero";
						} elseif ($lct->tipo_documento == 43) {
							$documento = "Permiso especial de permanencia";
						}
					}

					// Recursos humanos
					if ($lct->recursoshumanos == '' || $lct->recursoshumanos == 'null') {
						$rHumano = "Campo Vacio";
					} else {
						if ($lct->recursoshumanos == 1) {
							$rHumano = "Si";
						} elseif ($lct->recursoshumanos == 2) {
							$rHumano = "No";
						}
					}

					$sheet->row($value + 2, [
						$lct->razonsocial,
						$lct->name . ' ' . $lct->last_name,
						$documento,
						$lct->num_documento,
						$lct->fechaingreso,
						$lct->cargo,
						$lct->nameAreas,
						$lct->salario,
						$lct->contato,
						$lct->jornada,
						$lct->jefeinmediato,
						$rHumano,
						$lct->cantRRHH,
						$lct->estado == 1 ? 'Activo' : 'Inactivo'

					]);
				} //foreacht

			}); // fin segunda hoja de excel
			//***********************tercera hoja del perfil Sociodemográfico Datos de Salud *******
			$excel->sheet('Datos de Salud', function ($sheet) use ($id_users) {
				$users = DB::table('users')
					->select(
						'users.id',
						'users.name',
						'users.last_name',
						'users.num_documento',
						'users.email',
						'users.fecha_nacimiento',
						'users.genero',
						'users.nivel_estudio',
						'users.estrato',
						'users.cargo',
						'users.role_id',
						'users.telefono',
						'users.tipo_documento',
						'users.id_region',
						'users.id_ciudad',
						'users.direccion',
						'users.company_id',
						'users.estado',
						'ciudades.nombre as nombreCiudad',
						'ciudades.idCiudad',
						'company.razonsocial',
						'region.idRegion',
						'region.nombre as nombreRegion',
						'nivelEstudios.id as id_nivel',
						'nivelEstudios.nivelDesc',
						'v311hacerFormatoActualizacionDatosPersonales.eps',
						'v311hacerFormatoActualizacionDatosPersonales.fondopensiones',
						'v311hacerFormatoActualizacionDatosPersonales.fondocesantias',
						'v311hacerFormatoActualizacionDatosPersonales.arl',
						'v311hacerFormatoActualizacionDatosPersonales.cajacompensacion',
						'v311hacerFormatoActualizacionDatosPersonales.padeceenfermedad',
						'v311hacerFormatoActualizacionDatosPersonales.fuma',
						'v311hacerFormatoActualizacionDatosPersonales.frecFuma',
						'v311hacerFormatoActualizacionDatosPersonales.bebe',
						'v311hacerFormatoActualizacionDatosPersonales.frecBebe',
						'v311hacerFormatoActualizacionDatosPersonales.altura',
						'v311hacerFormatoActualizacionDatosPersonales.peso',
						'v311hacerFormatoActualizacionDatosPersonales.tallacamisa',
						'v311hacerFormatoActualizacionDatosPersonales.tallapantalon',
						'v311hacerFormatoActualizacionDatosPersonales.tallazapatos',
						'jornadaTrabajo.id_jtrabajo',
						'jornadaTrabajo.nombre as jornada',
						'estadoCivil.id as estadoCivil_id',
						'estadoCivil.nombre as estadoCivil',
						'arl.id as id_arl',
						'arl.nombre as arlName',
						'eps_arl.id_tipo_admin',
						'eps_arl.nombre as epsName',
						'cajaCompensacion.id as id_compesacion',
						'cajaCompensacion.nombre as nameCompensacion'
					)

					->leftjoin('ciudades', 'ciudades.idCiudad', '=', 'users.id_ciudad')
					->leftjoin('region', 'region.idRegion', '=', 'users.id_region')
					->leftjoin('company', 'company.id', '=', 'users.company_id')
					->leftjoin('v311FDAP_DatosContacto', 'v311FDAP_DatosContacto.id_user', '=', 'users.id')
					->leftjoin('v311hacerFormatoActualizacionDatosPersonales', 'v311hacerFormatoActualizacionDatosPersonales.id_user', '=', 'users.id')
					->leftjoin('jornadaTrabajo', 'jornadaTrabajo.id_jtrabajo', '=', 'v311hacerFormatoActualizacionDatosPersonales.id_jtrabajo')
					->leftjoin('eps_arl', 'eps_arl.id_tipo_admin', '=', 'v311hacerFormatoActualizacionDatosPersonales.eps')
					->leftjoin('arl', 'arl.id', '=', 'v311hacerFormatoActualizacionDatosPersonales.arl')
					->leftjoin('estadoCivil', 'estadoCivil.id', '=', 'v311hacerFormatoActualizacionDatosPersonales.id_estadocivil')
					->leftjoin('tipoContratos', 'tipoContratos.id', '=', 'v311hacerFormatoActualizacionDatosPersonales.tipocontrato')
					->leftjoin('nivelEstudios', 'nivelEstudios.id', '=', 'users.nivel_estudio')
					->leftjoin('cajaCompensacion', 'cajaCompensacion.id', '=', 'v311hacerFormatoActualizacionDatosPersonales.cajacompensacion')
					->where('users.id', $id_users)
					->groupBy('users.id')
					->get();

				//dd($users);

				$sheet->row(1, [
					'Empresa',
					'Nombre Empleado',
					'Tipo documento',
					'Num. Documento',
					'EPS',
					'ARL',
					'Fondo de pensiones',
					'Fondo de cesantias',
					'Caja de compensaciones',
					'Padece enfermedades',
					'Fuma',
					'Frecuencia',
					'Bebe',
					'Fecuencia',
					'Altura',
					'Peso',
					'talla camisa',
					'talla pantalón',
					'talla calzado',
					'Estado'
				]);

				$sheet->row(1, function ($row) {
					$row->setBackground('#33E0FF');
				}); //ponemos color a la cabecera

				foreach ($users as $value => $lct) {

					//tipo documento
					if ($lct->tipo_documento == '' || $lct->tipo_documento == 'null') {
						$documento = "Campo Vacio";
					} else {
						if ($lct->tipo_documento == 12) {
							$documento = "Tarjeta de Identidad";
						} elseif ($lct->tipo_documento == 13) {
							$documento = "Cédula Ciudadanía";
						} elseif ($lct->tipo_documento == 21) {
							$documento = "Tarjeta de extranjería";
						} elseif ($lct->tipo_documento == 22) {
							$documento = "Cédula de extranjería";
						} elseif ($lct->tipo_documento == 31) {
							$documento = "NIT";
						} elseif ($lct->tipo_documento == 41) {
							$documento = "Pasaporte";
						} elseif ($lct->tipo_documento == 42) {
							$documento = "Tipo documento extranjero";
						} elseif ($lct->tipo_documento == 43) {
							$documento = "Permiso especial de permanencia";
						}
					}


					// Caja de compensaciones
					/*			if ($lct->cajacompensacion == '' || $lct->cajacompensacion == 'null') {
						$compensaciones = "Campo Vacio";
					} else {

						if ($lct->cajacompensacion == 1) {
							$compensaciones = "Comfenalco";
						} elseif ($lct->cajacompensacion == 2) {
							$compensaciones = "Comfama";
						} elseif ($lct->cajacompensacion == 3) {
							$compensaciones = "Compensar";
						}
					}*/

					//Fondo pensiones
					if ($lct->fondopensiones == '' || $lct->fondopensiones == 'null') {
						$pensiones = "Campo Vacio";
					} else {

						if ($lct->fondopensiones == 1) {
							$pensiones = "Colfondos Pensiones y Cesantías";
						} elseif ($lct->fondopensiones == 2) {
							$pensiones = "Colpensiones";
						} elseif ($lct->fondopensiones == 3) {
							$pensiones = "Protección S.A.";
						} elseif ($lct->fondopensiones == 4) {
							$pensiones = "Porvenir S.A.";
						} elseif ($lct->fondopensiones == 5) {
							$pensiones = "Skandia";
						}
					}

					//Fondo de Cesantías
					if ($lct->fondocesantias == '' || $lct->fondocesantias == 'null') {
						$cesantia = "Campo Vacio";
					} else {

						if ($lct->fondocesantias == 1) {
							$cesantia = "Colfondos Pensiones y Cesantías";
						} elseif ($lct->fondocesantias == 2) {
							$cesantia = "Colpensiones";
						} elseif ($lct->fondocesantias == 3) {
							$cesantia = "Protección S.A.";
						} elseif ($lct->fondocesantias == 4) {
							$cesantia = "Porvenir S.A.";
						} elseif ($lct->fondocesantias == 5) {
							$cesantia = "Skandia";
						} elseif ($lct->fondocesantias == 6) {
							$cesantia = "Fondo nacional del ahorro";
						}
					}

					//fuma y bebe y frecuencia de ambas
					if ($lct->fuma == '' || $lct->fuma == 'null') {
						$fuma = "Campo Vacio";
					} else {
						if ($lct->fuma == 1) {
							$fuma = "Si";
						} elseif ($lct->fuma == 0) {
							$fuma = "No";
						}
					}
					/////////////////////////
					if ($lct->frecFuma == '' || $lct->frecFuma == 'null') {
						$fumaF = "Campo Vacio";
					} else {
						if ($lct->frecFuma == 1) {
							$fumaF = "Diario";
						} elseif ($lct->frecFuma == 2) {
							$fumaF = "Semanal";
						} elseif ($lct->frecFuma == 3) {
							$fumaF = "Mensual";
						} elseif ($lct->frecFuma == 4) {
							$fumaF = "Casual";
						}
					}
					//***************************
					if ($lct->bebe == '' || $lct->bebe == 'null') {
						$bebe = "Campo Vacio";
					} else {
						if ($lct->bebe == 1) {
							$bebe = "Si";
						} elseif ($lct->bebe == 0) {
							$bebe = "No";
						}
					}
					//////////////////////////
					if ($lct->frecBebe == '' || $lct->frecBebe == 'null') {
						$bebeB = "Campo Vacio";
					} else {
						if ($lct->frecBebe == 1) {
							$bebeB = "Diario";
						} elseif ($lct->frecBebe == 2) {
							$bebeB = "Semanal";
						} elseif ($lct->frecBebe == 3) {
							$bebeB = "Mensual";
						} elseif ($lct->frecBebe == 4) {
							$bebeB = "Casual";
						}
					}



					$sheet->row($value + 2, [
						$lct->razonsocial,
						$lct->name . ' ' . $lct->last_name,
						$documento,
						$lct->num_documento,
						$lct->epsName,
						$lct->arlName,
						$pensiones,
						$cesantia,
						$lct->nameCompensacion,
						$lct->padeceenfermedad,
						$fuma,
						$fumaF,
						$bebe,
						$bebeB,
						$lct->altura,
						$lct->peso,
						$lct->tallacamisa,
						$lct->tallapantalon,
						$lct->tallazapatos,
						$lct->estado == 1 ? 'Activo' : 'Inactivo'

					]);
				} //foreacht

			}); // fin tercera hoja de excel

			//***********************Cuarta hoja del perfil Sociodemográfico Certificados*******
			$excel->sheet('Certificados', function ($sheet) use ($id_users) {
				$users = DB::table('users')
					->select(
						'users.id',
						'users.name',
						'users.last_name',
						'users.num_documento',
						'users.email',
						'users.fecha_nacimiento',
						'users.genero',
						'users.nivel_estudio',
						'users.estrato',
						'users.cargo',
						'users.role_id',
						'users.telefono',
						'users.tipo_documento',
						'users.id_region',
						'users.id_ciudad',
						'users.direccion',
						'users.company_id',
						'users.estado',
						'users.nivel_estudio',
						'company.razonsocial',
						'archivosPerfilsociodemografico.certAlt',
						'archivosPerfilsociodemografico.certAltTipo',
						'archivosPerfilsociodemografico.certAltFechaC',
						'archivosPerfilsociodemografico.certAltFechaA',
						'archivosPerfilsociodemografico.entidadCertAlt',
						'archivosPerfilsociodemografico.certAltArchivo',
						'archivosPerfilsociodemografico.urlAlt',
						'archivosPerfilsociodemografico.certCal',
						'archivosPerfilsociodemografico.entidadCertCal',
						'archivosPerfilsociodemografico.certCalTipo',
						'archivosPerfilsociodemografico.certCalFechaC',
						'archivosPerfilsociodemografico.certCalFechaA',
						'archivosPerfilsociodemografico.certCalArchivo',
						'archivosPerfilsociodemografico.urlCal',
						'archivosPerfilsociodemografico.certMet',
						'archivosPerfilsociodemografico.entidadCertMet',
						'archivosPerfilsociodemografico.certMetTipo',
						'archivosPerfilsociodemografico.certMetFechaC',
						'archivosPerfilsociodemografico.certMetFechaA',
						'archivosPerfilsociodemografico.certMetArchivo',
						'archivosPerfilsociodemografico.urlMet',
						'archivosPerfilsociodemografico.certEsp',
						'archivosPerfilsociodemografico.entidadCertEsp',
						'archivosPerfilsociodemografico.certEspTipo',
						'archivosPerfilsociodemografico.certEspFechaC',
						'archivosPerfilsociodemografico.certEspFechaA',
						'archivosPerfilsociodemografico.certEspArchivo',
						'archivosPerfilsociodemografico.urlEsp',
						'archivosPerfilsociodemografico.certQui',
						'archivosPerfilsociodemografico.entidadCertQui',
						'archivosPerfilsociodemografico.certQuiTipo',
						'archivosPerfilsociodemografico.certQuiFechaC',
						'archivosPerfilsociodemografico.certQuiFechaA',
						'archivosPerfilsociodemografico.certQuiArchivo',
						'archivosPerfilsociodemografico.urlQui',
						'archivosPerfilsociodemografico.certIza',
						'archivosPerfilsociodemografico.entidadCertIza',
						'archivosPerfilsociodemografico.certIzaTipo',
						'archivosPerfilsociodemografico.certIzaFechaC',
						'archivosPerfilsociodemografico.certIzaFechaA',
						'archivosPerfilsociodemografico.certIzaArchivo',
						'archivosPerfilsociodemografico.urlIza'
					)

					->leftjoin('company', 'company.id', '=', 'users.company_id')
					->leftjoin('v311hacerFormatoActualizacionDatosPersonales', 'v311hacerFormatoActualizacionDatosPersonales.id_user', '=', 'users.id')
					->leftjoin('archivosPerfilsociodemografico', 'archivosPerfilsociodemografico.id_v311hacer', '=', 'users.id')
					->where('users.id', $id_users)
					->get();


				$sheet->row(1, [
					'Empresa',
					'Nombre Empleado',
					'Tipo Documento',
					'Num.Documento',
					'Certificado en alturas',
					'Entidad certificadora',
					'Tipo de certificado',
					'Fecha de certificación',
					'Fecha de actualizacion',
					'Certificado en tabajo en caliente',
					'Entidad certificadora',
					'Tipo de certificado',
					'Fecha de certificación',
					'Fecha de actualizacion',
					'Certificado en espacios confinados',
					'Entidad certificadora',
					'Tipo de certificado',
					'Fecha de certificación',
					'Fecha de actualizacion',
					'Certificado Químicos peligrosos',
					'Entidad certificadora',
					'Tipo de certificado',
					'Fecha de certificación',
					'Fecha de actualizacion',
					'Certificado alta exposición metales pesados',
					'Entidad certificadora',
					'Tipo de certificado',
					'Fecha de certificación',
					'Fecha de actualizacion',
					'Certificado izaje de carga',
					'Entidad certificadora',
					'Tipo de certificado',
					'Fecha de certificación',
					'Fecha de actualizacion',
					'Estado'
				]);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				foreach ($users as $value => $lct) {

					//tipo documento

					if ($lct->tipo_documento == '' || $lct->tipo_documento == 'null') {
						$documento = "Campo Vacio";
					} else {
						if ($lct->tipo_documento == 12) {
							$documento = "Tarjeta de Identidad";
						} elseif ($lct->tipo_documento == 13) {
							$documento = "Cédula Ciudadanía";
						} elseif ($lct->tipo_documento == 21) {
							$documento = "Tarjeta de extranjería";
						} elseif ($lct->tipo_documento == 22) {
							$documento = "Cédula de extranjería";
						} elseif ($lct->tipo_documento == 31) {
							$documento = "NIT";
						} elseif ($lct->tipo_documento == 41) {
							$documento = "Pasaporte";
						} elseif ($lct->tipo_documento == 42) {
							$documento = "Tipo documento extranjero";
						} elseif ($lct->tipo_documento == 43) {
							$documento = "Permiso especial de permanencia";
						}
					}

					// Certifica Certificado de Alturas
					if ($lct->certAlt == 'on') {
						$certAlt = "Si";
					} else {
						$certAlt = "No";
					}

					//Certificado de Trabajos en Caliente
					if ($lct->certCal == 'on') {
						$certCal = "Si";
					} else {
						$certCal = "No";
					}

					//Certifica Alta Exposición Metales Pesados 
					if ($lct->certMet == 'on') {
						$certMet = "Si";
					} else {
						$certMet = "No";
					}

					//"Entidad Certificado de Espacios Confinados
					if ($lct->certEsp == 'on') {
						$certEsp = "Si";
					} else {
						$certEsp = "No";
					}

					//Certificado de Químicos Peligrosos
					if ($lct->certQui == 'on') {
						$certQui = "Si";
					} else {
						$certQui = "No";
					}

					//Entidad  Izaje de Carga
					if ($lct->certIza == 'on') {
						$certIza = "Si";
					} else {
						$certIza = "No";
					}

					$sheet->row($value + 2, [
						$lct->razonsocial,
						$lct->name . ' ' . $lct->last_name,
						$documento,
						$lct->num_documento,
						$certAlt,
						$lct->entidadCertAlt,
						$lct->certAltTipo,
						$lct->certAltFechaC,
						$lct->certAltFechaA,
						$certCal,
						$lct->entidadCertCal,
						$lct->certCalTipo,
						$lct->certCalFechaC,
						$lct->certCalFechaA,
						$certMet,
						$lct->entidadCertMet,
						$lct->certMetTipo,
						$lct->certMetFechaC,
						$lct->certMetFechaA,
						$certEsp,
						$lct->entidadCertEsp,
						$lct->certEspTipo,
						$lct->certEspFechaC,
						$lct->certEspFechaA,
						$certQui,
						$lct->entidadCertQui,
						$lct->certQuiTipo,
						$lct->certQuiFechaC,
						$lct->certQuiFechaA,
						$certIza,
						$lct->entidadCertIza,
						$lct->certIzaTipo,
						$lct->certIzaFechaC,
						$lct->certIzaFechaA
					]);
				} //foreacht

			}); // fin de la tercera hoja

		})->export('xls');
	}

	//************************** FIN DE EXPORTACIÓN DATOS DE USURIO PERFIL SOCIODEMOGRÁFICO****************************	

	public function eppExcel(request $request)
	{
		//dd($request);
		$id_empresa = $request->id_empresa;
		$fechaIni = $request->fechaDesde;
		$fechaFin = $request->fechaHasta;
		Excel::create('Reporte de EPP entregados', function ($excel) use ($id_empresa, $fechaIni, $fechaFin) {

			$excel->sheet('epp_entregados', function ($sheet) use ($id_empresa, $fechaIni, $fechaFin) {

				//$debitos = Debito::all();
				$entregado = DB::table('epp_pedidoEpp')
					->select('epp_pedidoEpp.id_pedidoEpp', 'epp_pedidoEpp.id_empresa', 'epp_pedidoEpp.id_pedido', 'epp_pedidoEpp.id_epp', 'epp_pedidoEpp.id_area', 'epp_pedidoEpp.cantidad', 'company.id', 'company.razonsocial', 'users.id', 'users.estado', 'users.name', 'users.last_name', 'epp_pedidoElementos.id_pedido as pedido_id', 'epp_pedidoElementos.codPedido', 'epp_pedidoElementos.fecha', 'areas.id as id_areas', 'areas.name as nameArea', 'epp_elementosEmpresa.id_elemento', 'epp_elementosEmpresa.nombre', 'epp_elementosEmpresa.reff')
					->leftjoin('company', 'company.id', '=', 'epp_pedidoEpp.id_empresa')
					->leftjoin('users', 'users.id', '=', 'epp_pedidoEpp.id_user')
					->leftjoin('areas', 'areas.id', '=', 'epp_pedidoEpp.id_area')
					->leftjoin('epp_elementosEmpresa', 'epp_elementosEmpresa.id_elemento', '=', 'epp_pedidoEpp.id_epp')
					->leftjoin('epp_pedidoElementos', 'epp_pedidoElementos.id_pedido', '=', 'epp_pedidoEpp.id_pedido')
					->where([['epp_pedidoEpp.estado', '=', 1], ['epp_pedidoEpp.id_empresa', '=', $id_empresa]])
					->whereBetween('epp_pedidoElementos.updated_at', [$fechaIni, $fechaFin])
					->get();

				$sheet->row(1, ['Empresa', 'Area', 'Empleado', 'Código del pedido', 'Referencia', 'EPP entregado', 'Cantidad', 'Fecha de entrega']);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				foreach ($entregado as $index => $Export) {
					$sheet->row($index + 2, [
						$Export->razonsocial,
						$Export->nameArea,
						$Export->name . ' ' . $Export->last_name,
						$Export->codPedido,
						$Export->reff,
						$Export->nombre,
						$Export->cantidad,
						$Export->fecha,
						$Export->estado == 1 ? 'Activo' : 'Inactivo'
					]);
				}
			});
		})->export('xls');
	}

	//Carga desde excel EPP
	public function riseEPP(Request $request)
	{

		$date = Carbon::now();
		$ano = $date->format('Y');
		$mes = $date->format('m');
		$dia = $date->format('d');
		$fecha = $ano . "-" . $mes . "-" . $dia;

		$typeCompany = $request->id_empresa;
		$tipo = $request->tipo;
		$id_cargo = 1;

		Excel::load($request->archEPP, function ($reader) use ($typeCompany, $tipo, $fecha, $date) {
			$archEPP = $reader->get();


			$reader->each(function ($row) use ($typeCompany, $tipo, $fecha, $date) {


				$id_elemento = ElementosEmpresa::insertGetId([
					'id_empresa' => $typeCompany,
					'nombre' => $row->elemento,
					'reff' => $row->referencia,
					'tipo' => $tipo,
					'id_cargo' => 1,
					'created_at' => $date,
					'updated_at' => $date,
					'estado' => 1
				]);

				StockEpp::create([
					'id_empresa' => $typeCompany,
					'id_epp' => $id_elemento,
					'tipo' => $tipo,
					'cantidad' => $row->cantidad,
					'fechaIngreso' => $fecha,
					'id_cargo' => 1,
					'estado' => 1
				]);

				$id_Hstock = Stock_Hist_Epp::insertGetId([
					'id_empresa' => $typeCompany,
					'tipo' => $tipo,
					'id_epp' => $id_elemento,
					'cantidad' => $row->cantidad,
					'pvp' => $row->precio,
					'fechaIngreso' => $fecha,
					'created_at' => $date,
					'updated_at' => $date,
					'estado' => 1
				]);

				ElementosDetalle::create([
					'id_elemento' => $id_elemento,
					'id_partCuerpo' => $row->partecuerpo,
					'tipoE' => $row->tipoepp,
					'peligro' => $row->peligro,
					'id_precio' => $id_Hstock,
					'norma' => $row->norma,
					'periodoCambio' => $row->periodocambio,
					'marca' => $row->marca,
					'observaciones' => $row->observaciones,
					'estado' => 1
				]);

				epp_IntStock::create([
					'id_empresa' => $typeCompany,
					'id_elemento' => $id_elemento,
					'stock_total' => $row->cantidad,
					'stock_virtual' => $row->cantidad,
					'estado' => 1
				]);
				PreciosElementos::create([
					'id_empresa' => $typeCompany,
					'id_epp' => $id_elemento,
					'Pvp1' => $id_Hstock,
					'fecha' => $fecha,
					'estado' => 1
				]);
			});
		});

		$request->session()->flash('riseEPP', 'Subida realizada correctamente');
		return redirect()->action('epp\eppController@listElements', [$typeCompany, $tipo, $id_cargo]);
	}





	public function ausentismoExcel(request $request)
	{
		//dd($request);
		$id_empresa = $request->id_empresa;
		$fechaIni = $request->fechaDesde;
		$fechaFin = $request->fechaHasta;
		Excel::create('Reporte de Ausentismo Laboral', function ($excel) use ($id_empresa, $fechaIni, $fechaFin) {

			$excel->sheet('Ausentismo', function ($sheet) use ($id_empresa, $fechaIni, $fechaFin) {
				$ausen = \DB::table('users')
					->select('users.id as id_user', 'users.name', 'users.last_name', 'users.num_documento', 'users.company_id', 'ausentismoLaboral.id_ausentismo', 'ausentismoLaboral.prorroga', 'ausentismoLaboral.fechaInicio', 'ausentismoLaboral.fechafin', 'ausentismoLaboral.numDias', 'ausentismoLaboral.diasCargo', 'ausentismoLaboral.id_empleado', 'ausentismoLaboral.gastos', 'ausentismoLaboral.entidadEmisora', 'ausentismoLaboral.causaAusentismo', 'ausentismoLaboral.tipoIncapacidad', 'ausentismoLaboral.id_diagnostico', 'ausentismoLaboral.id_empresa', 'ausentismoLaboralCausas.causaAusentismo as causa', 'prorrogas.id_pro', 'prorrogas.id_empleado as empleado_id', 'prorrogas.id_ausentismo as ausentismo_id', 'prorrogas.fechaInicioP', 'prorrogas.fechaFinP', 'prorrogas.numDiasP', 'areas.id as id_areas', 'areas.name as nameArea', 'company.id', 'company.razonsocial', 'diagnostico_CIE_10.id_CIE', 'diagnostico_CIE_10.codigo_CIE', 'diagnostico_CIE_10.descripcion_CIE', 'eps_arl.codigo')
					->leftjoin('ausentismoLaboral', 'ausentismoLaboral.id_empleado', '=', 'users.id')
					->leftjoin('ausentismoLaboralCausas', 'ausentismoLaboral.causaAusentismo', '=', 'ausentismoLaboralCausas.id')
					->leftjoin('prorrogas', 'prorrogas.id_ausentismo', '=', 'ausentismoLaboral.id_ausentismo')
					->leftjoin('areas', 'areas.id', '=', 'users.id_area')
					->leftjoin('diagnostico_CIE_10', 'diagnostico_CIE_10.id_CIE', '=', 'ausentismoLaboral.id_diagnostico')
					->leftjoin('eps_arl', 'eps_arl.id_tipo_admin', '=', 'ausentismoLaboral.codEntidadEmisora')
					->leftjoin('company', 'company.id', '=', 'users.company_id')
					->where('ausentismoLaboral.id_empresa', $id_empresa)
					->whereBetween('ausentismoLaboral.fechaInicio', [$fechaIni, $fechaFin])
					->groupBy('ausentismoLaboral.id_ausentismo')
					->get();

				// dd($ausen);

				$sheet->row(1, ['Empresa', 'Area', 'Empleado', 'Cédula', 'Código CIE', 'Enfermedad', 'Causa de ausentismo', 'Tipo de incapacidad', 'Entidad emisora', 'Fecha de inicio incapacidad ', 'Fecha fin incapacidad', 'Total días incapacitado', 'Días de cargo', 'Gastos', '¿Hubo prorroga?']);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera


				foreach ($ausen as $index => $Export) {

					if ($Export->prorroga == '1') {
						$prorroga = "Si";
					} else {
						$prorroga = "No";
					}

					$sheet->row($index + 2, [
						$Export->razonsocial,
						$Export->nameArea,
						$Export->name . ' ' . $Export->last_name,
						$Export->num_documento,
						$Export->codigo_CIE == null ? 'No aplica' : $Export->codigo_CIE,
						$Export->descripcion_CIE == null ? 'No aplica' : $Export->descripcion_CIE,
						$Export->causa,
						$Export->tipoIncapacidad,
						$Export->codigo,
						$Export->fechaInicio,
						$Export->fechafin,
						$Export->numDias,
						$Export->diasCargo,
						$Export->gastos,
						$prorroga
					]);
				}
			});

			// Hoja 2(Prorrogas)
			$excel->sheet('Prorrogas', function ($sheet) use ($id_empresa, $fechaIni, $fechaFin) {
				$prorroga = \DB::table('users')
					->select('users.id as id_user', 'users.name', 'users.last_name', 'users.num_documento', 'users.company_id', 'ausentismoLaboral.id_ausentismo', 'ausentismoLaboral.prorroga', 'ausentismoLaboral.fechaInicio', 'ausentismoLaboral.fechafin', 'ausentismoLaboral.numDias', 'ausentismoLaboral.diasCargo', 'ausentismoLaboral.id_empleado', 'ausentismoLaboral.gastos', 'ausentismoLaboral.entidadEmisora', 'ausentismoLaboral.causaAusentismo', 'ausentismoLaboral.tipoIncapacidad', 'ausentismoLaboral.id_diagnostico', 'ausentismoLaboral.id_empresa', 'ausentismoLaboralCausas.causaAusentismo as causa', 'prorrogas.id_pro', 'prorrogas.id_empleado as empleado_id', 'prorrogas.id_ausentismo as ausentismo_id', 'prorrogas.fechaInicioP', 'prorrogas.fechaFinP', 'prorrogas.numDiasP', 'company.id', 'company.razonsocial', 'diagnostico_CIE_10.id_CIE', 'diagnostico_CIE_10.codigo_CIE', 'diagnostico_CIE_10.descripcion_CIE', 'eps_arl.codigo')
					->leftjoin('ausentismoLaboral', 'ausentismoLaboral.id_empleado', '=', 'users.id')
					->leftjoin('prorrogas', 'prorrogas.id_ausentismo', '=', 'ausentismoLaboral.id_ausentismo')
					->leftjoin('diagnostico_CIE_10', 'diagnostico_CIE_10.id_CIE', '=', 'ausentismoLaboral.id_diagnostico')
					->leftjoin('company', 'company.id', '=', 'users.company_id')
					->leftjoin('ausentismoLaboralCausas', 'ausentismoLaboral.causaAusentismo', '=', 'ausentismoLaboralCausas.id')
					->leftjoin('eps_arl', 'eps_arl.id_tipo_admin', '=', 'ausentismoLaboral.codEntidadEmisora')
					->where('ausentismoLaboral.id_empresa', $id_empresa)
					->whereBetween('prorrogas.fechaInicioP', [$fechaIni, $fechaFin])
					->get();



				$sheet->row(1, ['Empresa', 'Empleado', 'Cédula', 'Causa de ausentismo', 'Entidad emisora', 'Fecha de inicio prorroga ', 'Fecha fin prorroga', 'Total días prorroga']);
				$sheet->row(1, function ($row) {
					$row->setBackground('#33f9ff');
				}); //ponemos color a la cabecera

				foreach ($prorroga as $index => $comps) {

					$sheet->row($index + 2, [
						$comps->razonsocial,
						$comps->name . ' ' . $comps->last_name,
						$comps->num_documento,
						$comps->causa,
						$comps->codigo,
						$comps->fechaInicioP,
						$comps->fechaFinP,
						$comps->numDiasP
					]);
				}
			});
		})->export('xls');
	}


	public function aptitudExcel(request $request)
	{



		$id_empresa = $request->id_empresa;
		$fechaIni = $request->fechaDesde;
		$fechaFin = $request->fechaHasta;
		Excel::create('Reporte informes concepto de aptitud médico laboral', function ($excel) use ($id_empresa, $fechaIni, $fechaFin) {

			//INGRESO
			$excel->sheet('informes de Ingreso', function ($sheet) use ($id_empresa, $fechaIni, $fechaFin) {

				//$debitos = Debito::all();
				$informe = DB::table('archivosInformesMedicos')
					->select('archivosInformesMedicos.id_informeMedico', 'archivosInformesMedicos.id_empleado as id_user', 'archivosInformesMedicos.id_empresa', 'archivosInformesMedicos.url', 'archivosInformesMedicos.nombreArchivo', 'archivosInformesMedicos.fecha1', 'archivosInformesMedicos.fecha2', 'archivosInformesMedicos.categoria', 'archivosInformesMedicos.periocidad', 'archivosInformesMedicos.estado', 'users.id', 'users.name', 'users.last_name', 'users.num_documento', 'users.email', 'company.id as idCompany', 'company.razonsocial', 'areas.id as id_areas', 'areas.name as nameArea')
					->leftjoin('users', 'users.id', '=', 'archivosInformesMedicos.id_empleado')
					->leftjoin('areas', 'areas.id', '=', 'users.id_area')
					->leftjoin('company', 'company.id', '=', 'users.company_id')
					->where([['archivosInformesMedicos.id_empresa', $id_empresa], ['archivosInformesMedicos.categoria', 1]])
					->whereBetween('archivosInformesMedicos.created_at', [$fechaIni, $fechaFin])
					->get();

				$sheet->row(1, ['Empresa', 'Área', 'Empleado', 'Núm. Documento', 'Informe médico', 'Fecha de informe', 'Fecha de vencimiento', 'Adjuntos']);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				foreach ($informe as $index => $Export) {
					if ($Export->categoria == 1) {
						$tipo = "Ingreso";
					} elseif ($Export->categoria == 2) {
						$tipo = "Periódico";
					} else {
						$tipo = "Egreso";
					}

					$sheet->row($index + 2, [
						$Export->razonsocial,
						$Export->nameArea,
						$Export->name . ' ' . $Export->last_name,
						$Export->num_documento,
						$tipo,
						$Export->fecha1,
						$Export->fecha2,
						$Export->url ? 'Tiene adjuntos' : 'No tiene adjuntos'
					]);
				}
			});

			//PERIODICO
			$excel->sheet('informes Periódicos', function ($sheet) use ($id_empresa, $fechaIni, $fechaFin) {

				//$debitos = Debito::all();
				$informe = DB::table('archivosInformesMedicos')
					->select('archivosInformesMedicos.id_informeMedico', 'archivosInformesMedicos.id_empleado as id_user', 'archivosInformesMedicos.id_empresa', 'archivosInformesMedicos.url', 'archivosInformesMedicos.nombreArchivo', 'archivosInformesMedicos.fecha1', 'archivosInformesMedicos.fecha2', 'archivosInformesMedicos.categoria', 'archivosInformesMedicos.periocidad', 'archivosInformesMedicos.estado', 'users.id', 'users.name', 'users.last_name', 'users.num_documento', 'users.email', 'company.id as idCompany', 'company.razonsocial', 'areas.id as id_areas', 'areas.name as nameArea')
					->leftjoin('users', 'users.id', '=', 'archivosInformesMedicos.id_empleado')
					->leftjoin('areas', 'areas.id', '=', 'users.id_area')
					->leftjoin('company', 'company.id', '=', 'users.company_id')
					->where([['archivosInformesMedicos.id_empresa', $id_empresa], ['archivosInformesMedicos.categoria', 2]])
					->whereBetween('archivosInformesMedicos.created_at', [$fechaIni, $fechaFin])
					->get();

				$sheet->row(1, ['Empresa', 'Área', 'Empleado', 'Núm. Documento', 'Informe médico', 'Fecha de informe', 'Fecha de vencimiento', 'Adjuntos']);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				foreach ($informe as $index => $Export) {
					if ($Export->categoria == 1) {
						$tipo = "Ingreso";
					} elseif ($Export->categoria == 2) {
						$tipo = "Periódico";
					} else {
						$tipo = "Egreso";
					}

					$sheet->row($index + 2, [
						$Export->razonsocial,
						$Export->nameArea,
						$Export->name . ' ' . $Export->last_name,
						$Export->num_documento,
						$tipo,
						$Export->fecha1,
						$Export->fecha2,
						$Export->url ? 'Tiene adjuntos' : 'No tiene adjuntos'
					]);
				}
			});

			//EGRESO
			$excel->sheet('informes de Egreso', function ($sheet) use ($id_empresa, $fechaIni, $fechaFin) {

				//$debitos = Debito::all();
				$informe = DB::table('archivosInformesMedicos')
					->select('archivosInformesMedicos.id_informeMedico', 'archivosInformesMedicos.id_empleado as id_user', 'archivosInformesMedicos.id_empresa', 'archivosInformesMedicos.url', 'archivosInformesMedicos.nombreArchivo', 'archivosInformesMedicos.fecha1', 'archivosInformesMedicos.fecha2', 'archivosInformesMedicos.categoria', 'archivosInformesMedicos.periocidad', 'archivosInformesMedicos.estado', 'users.id', 'users.name', 'users.last_name', 'users.num_documento', 'users.email', 'company.id as idCompany', 'company.razonsocial', 'areas.id as id_areas', 'areas.name as nameArea')
					->leftjoin('users', 'users.id', '=', 'archivosInformesMedicos.id_empleado')
					->leftjoin('areas', 'areas.id', '=', 'users.id_area')
					->leftjoin('company', 'company.id', '=', 'users.company_id')
					->where([['archivosInformesMedicos.id_empresa', $id_empresa], ['archivosInformesMedicos.categoria', 3]])
					->whereBetween('archivosInformesMedicos.created_at', [$fechaIni, $fechaFin])
					->get();

				$sheet->row(1, ['Empresa', 'Área', 'Empleado', 'Núm. Documento', 'Informe médico', 'Fecha de informe', 'Fecha de vencimiento', 'Adjuntos']);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				foreach ($informe as $index => $Export) {
					if ($Export->categoria == 1) {
						$tipo = "Ingreso";
					} elseif ($Export->categoria == 2) {
						$tipo = "Periódico";
					} else {
						$tipo = "Egreso";
					}

					$sheet->row($index + 2, [
						$Export->razonsocial,
						$Export->nameArea,
						$Export->name . ' ' . $Export->last_name,
						$Export->num_documento,
						$tipo,
						$Export->fecha1,
						$Export->fecha2,
						$Export->url ? 'Tiene adjuntos' : 'No tiene adjuntos'
					]);
				}
			});
		})->export('xls');
	}


	public function procesosExcel(Request $request)
	{
		$id_empresa = $request->id_empresa;
		$fechaIni = $request->fechaDesde;
		$fechaFin = $request->fechaHasta;
		Excel::create('Reporte seguimiento a proceso', function ($excel) use ($id_empresa, $fechaIni, $fechaFin) {

			// PROCESOS
			$excel->sheet('Empleados con proceso', function ($sheet) use ($id_empresa, $fechaIni, $fechaFin) {

				//$debitos = Debito::all();
				$informe = DB::table('vigilancia')
					->select('vigilancia.id_vigilancia', 'vigilancia.id_empleado as id_user', 'vigilancia.id_empresa', 'vigilancia.fecha', 'vigilancia.codigo_CIE', 'vigilancia.descripcion_CIE', 'vigilancia.tipo_Riesgo', 'vigilancia.tipo_intervenciones', 'vigilancia.resultados_test', 'vigilancia.proceso_Reu', 'vigilancia.evaluación_puesto as evaluacion', 'vigilancia.calificacion_capacidad', 'vigilancia.estado_Actual', 'vigilancia.promedio_horas', 'vigilancia.estado', 'vigilancia.observaciones', 'users.id', 'users.name', 'users.last_name', 'users.num_documento', 'users.email', 'company.id as idCompany', 'company.razonsocial', 'areas.id as id_areas', 'areas.name as nameArea')
					->leftjoin('users', 'users.id', '=', 'vigilancia.id_empleado')
					->leftjoin('areas', 'areas.id', '=', 'users.id_area')
					->leftjoin('company', 'company.id', '=', 'users.company_id')
					->where([['vigilancia.id_empresa', $id_empresa]])
					->whereBetween('vigilancia.created_at', [$fechaIni, $fechaFin])
					->get();


				$archivos = DB::table('archivosVigilancia')
					->select('archivosVigilancia.archivoAsis', 'archivosVigilancia.archivoTest', 'archivosVigilancia.archivoReu', 'archivosVigilancia.archivoEva', 'archivosVigilancia.archivoCali', 'archivosVigilancia.archivoProce', 'archivosVigilancia.archivoPerm', 'archivosVigilancia.archivoInter', 'vigilancia.id_vigilancia')
					->leftjoin('vigilancia', 'vigilancia.id_vigilancia', '=', 'archivosVigilancia.id_vigilancia')
					->where([['vigilancia.id_empresa', $id_empresa]])
					->whereBetween('vigilancia.created_at', [$fechaIni, $fechaFin])
					->where(function ($query) {
						$query->whereNotNull('archivosVigilancia.archivoAsis')
							->orWhereNotNull('archivosVigilancia.archivoTest')
							->orWhereNotNull('archivosVigilancia.archivoReu')
							->orWhereNotNull('archivosVigilancia.archivoEva')
							->orWhereNotNull('archivosVigilancia.archivoCali')
							->orWhereNotNull('archivosVigilancia.archivoProce')
							->orWhereNotNull('archivosVigilancia.archivoPerm')
							->orWhereNotNull('archivosVigilancia.archivoInter');
					})
					->orderBy('vigilancia.id_vigilancia', 'DEC')
					->get();

				// dd($informe);

				$sheet->row(1, ['Empleado', 'Diagnostico', 'Tipo de Riesgo', 'Tipo de Intervencion', 'Estado Actual', 'Exposicion al Riesgo', 'Observacion', 'Fecha', 'Adjuntos']);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				foreach ($informe as $index => $Export) {


					foreach ($archivos as $arch) {

						if ($Export->id_vigilancia == $arch->id_vigilancia) {
							$adjunto = 'Tiene adjuntos';
						} else {
							$adjunto = 'No tiene adjuntos';
						}
					}

					$sheet->row($index + 2, [
						$Export->name . ' ' . $Export->last_name,
						$Export->descripcion_CIE,
						$Export->tipo_Riesgo,
						$Export->tipo_intervenciones,
						$Export->estado_Actual,
						$Export->promedio_horas,
						$Export->observaciones,
						$Export->fecha,
						$adjunto
					]);
				}
			});

			// OBSERVACIONES

			$excel->sheet('Observaciones', function ($sheet) use ($id_empresa, $fechaIni, $fechaFin) {


				$observaciones = DB::table('prorrogas')
					->select('prorrogas.fechaObser', 'prorrogas.observaciones', 'prorrogas.id_vigilancia', 'users.name', 'users.last_name')
					->leftjoin('vigilancia', 'prorrogas.id_vigilancia', '=', 'vigilancia.id_vigilancia')
					->leftjoin('users', 'users.id', '=', 'prorrogas.id_empleado')
					->where([['prorrogas.id_empresa', $id_empresa]])
					->whereBetween('prorrogas.created_at', [$fechaIni, $fechaFin])
					->where(function ($query) {
						$query->whereNotNull('prorrogas.fechaObser')
							->orWhereNotNull('prorrogas.observaciones')
							->orWhereNotNull('prorrogas.id_vigilancia');
					})
					->get();


				$sheet->row(1, ['Empleado', 'Fecha', 'Observacion']);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				foreach ($observaciones as $index => $Export) {

					$sheet->row($index + 2, [
						$Export->name . ' ' . $Export->last_name,
						$Export->fechaObser,
						$Export->observaciones
					]);
				}
			});
		})->export('xls');
	}

	public function capacitacionesExcel(request $request)
	{

		$id_empresa = $request->id_empresa;
		$fechaIni = $request->fechaDesde;
		$fechaFin = $request->fechaHasta;
		$modulo = $request->id_modulo;



		if ($modulo == 2 || $modulo == 3 || $modulo == 4 || $modulo == 5 || $modulo == 6 || $modulo == 7) {

			Excel::create('Reporte de empleados capacitados', function ($excel) use ($id_empresa, $fechaIni, $fechaFin, $modulo) {

				$excel->sheet('Empleados capacitados', function ($sheet) use ($id_empresa, $fechaIni, $fechaFin, $modulo) {

					//$debitos = Debito::all();
					$resultado = DB::table('users')
						->select(
							'users.name',
							'users.id',
							'users.last_name',
							'users.num_documento',
							'users.id_area',
							'users.id_otherArea',
							'users.company_id',
							'capacitaciones.nombre',
							'capacitaciones.id_modulo',
							'capacitaciones.id as id_capacitacion',
							'company.id as idCompany',
							'company.razonsocial',
							'company.origen_regi',
							'areas.id as id_areas',
							'areas.name as nameArea',
							'resultado.id as idResultado',
							'resultado.id_user',
							'resultado.id_capacitacion',
							'resultado.estado',
							'resultado.created_at',
							'areasOther.id as id_areaOther',
							'areasOther.name as nameAreaOther'
						)
						->leftjoin('resultado', 'resultado.id_user', '=', 'users.id')
						->leftjoin('capacitaciones', 'capacitaciones.id', '=', 'resultado.id_capacitacion')
						->leftjoin('areas', 'areas.id', '=', 'users.id_area')
						->leftjoin('areasOther', 'areasOther.id', '=', 'users.id_otherArea')
						->leftjoin('company', 'company.id', '=', 'users.company_id')
						->where([['users.company_id', '=', $id_empresa], ['resultado.estado', '=', 1], ['capacitaciones.id_modulo', '=', $modulo]])
						->whereBetween('resultado.created_at', [$fechaIni, $fechaFin])
						->get();

					foreach ($resultado as $value) {
						$zone = $value->origen_regi;
					}

					if ($zone != 11) {
						$sheet->row(1, ['Empresa', 'Área', ' SubÁrea', 'Empleado', 'Núm. Documento', 'Módulo', 'Capacitación Superada', 'Fecha de superación']);
						$sheet->row(1, function ($row) {
							$row->setBackground('#00A79D');
						}); //ponemos color a la cabecera

					} else {
						$sheet->row(1, ['Empresa', 'Área', ' SubÁrea', 'Funcionário', 'Núm. do documento', 'Módulo', 'Treinamento aprovado', 'Data de passagem']);
						$sheet->row(1, function ($row) {
							$row->setBackground('#00A79D');
						}); //ponemos color a la cabecera
					}



					foreach ($resultado as $index => $Export) {

						if ($Export->id_modulo == 1) {
							$moduloSve = "Capacitaciones";
						} elseif ($Export->id_modulo == 2) {
							$moduloSve = "SVE Osteosmuscular";
						} elseif ($Export->id_modulo == 3) {
							$moduloSve = "PESV";
						} elseif ($Export->id_modulo == 4) {
							$moduloSve = "SVE Psicosocial";
						} elseif ($Export->id_modulo == 5) {
							$moduloSve = "SVE Cardiovascular";
						} elseif ($Export->id_modulo == 6) {
							$moduloSve = "SVE Auditivo";
						} else {
							$moduloSve = "SVE Respiratorio";
						}

						$sheet->row($index + 2, [
							$Export->razonsocial,
							$Export->nameArea,
							$Export->nameAreaOther,
							$Export->name . ' ' . $Export->last_name,
							$Export->num_documento,
							$moduloSve,
							$Export->nombre,
							$Export->created_at
						]);
					}
				});
			})->export('xls');
		} else {


			Excel::create('Reporte de empleados capacitados', function ($excel) use ($id_empresa, $fechaIni, $fechaFin, $modulo) {

				$excel->sheet('Empleados capacitados', function ($sheet) use ($id_empresa, $fechaIni, $fechaFin, $modulo) {

					//$debitos = Debito::all();
					$resultado = DB::table('users')
						->select('users.name', 'users.id', 'users.last_name', 'users.num_documento', 'users.id_area', 'users.id_otherArea', 'users.company_id', 'capacitaciones.nombre', 'capacitaciones.id_modulo', 'capacitaciones.id as id_capacitacion', 'company.id as idCompany', 'company.razonsocial', 'areas.id as id_areas', 'areas.name as nameArea', 'resultado.id as idResultado', 'resultado.id_user', 'resultado.id_capacitacion', 'resultado.estado', 'resultado.created_at', 'areasOther.id as id_areaOther', 'areasOther.name as nameAreaOther')
						->leftjoin('resultado', 'resultado.id_user', '=', 'users.id')
						->leftjoin('capacitaciones', 'capacitaciones.id', '=', 'resultado.id_capacitacion')
						->leftjoin('areas', 'areas.id', '=', 'users.id_area')
						->leftjoin('areasOther', 'areasOther.id', '=', 'users.id_otherArea')
						->leftjoin('company', 'company.id', '=', 'users.company_id')
						->where([['users.company_id', '=', $id_empresa], ['resultado.estado', '=', 1]])
						->whereBetween('resultado.created_at', [$fechaIni, $fechaFin])
						->get();

					$sheet->row(1, ['Empresa', 'Área', 'Sub Área', 'Empleado', 'Núm. Documento', 'Módulo de la capacitación', 'Capacitación Superada', 'Fecha de superación']);
					$sheet->row(1, function ($row) {
						$row->setBackground('#00A79D');
					}); //ponemos color a la cabecera

					foreach ($resultado as $index => $Export) {

						if ($Export->id_modulo == 1) {
							$moduloS = "Capacitaciones";
						} elseif ($Export->id_modulo == 2) {
							$moduloS = "SVE Osteosmuscular";
						} elseif ($Export->id_modulo == 3) {
							$moduloS = "PESV";
						} elseif ($Export->id_modulo == 4) {
							$moduloS = "SVE Psicosocial";
						} elseif ($Export->id_modulo == 5) {
							$moduloS = "SVE Cardiovascular";
						} elseif ($Export->id_modulo == 6) {
							$moduloS = "SVE Auditivo";
						} else {
							$moduloS = "SVE Respiratorio";
						}


						$sheet->row($index + 2, [
							$Export->razonsocial,
							$Export->nameArea,
							$Export->nameAreaOther,
							$Export->name . ' ' . $Export->last_name,
							$Export->num_documento,
							$moduloS,
							$Export->nombre,
							$Export->created_at
						]);
					}
				});
			})->export('xls');
		}
	}




	public function accidentesExcel(request $request)
	{
		//dd($request);
		$id_empresa = $request->id_empresa;
		$fechaIni = $request->fechaDesde;
		$fechaFin = $request->fechaHasta;
		Excel::create('Reporte de Accidentes Laborales', function ($excel) use ($id_empresa, $fechaIni, $fechaFin) {

			$excel->sheet('Empleados con Accidente Laboral', function ($sheet) use ($id_empresa, $fechaIni, $fechaFin) {

				$accidentes = DB::table('accidenteLaboral')
					->select('accidenteLaboral.id_accidente', 'accidenteLaboral.id_tipoaccidente', 'accidenteLaboral.fecha_accidente', 'accidenteLaboral.dia_semana as dia', 'accidenteLaboral.lugar_accidente', 'accidenteLaboral.horatrabajo', 'accidenteLaboral.id_tiposuceso', 'parteCuerpoAfectada.nombre as parteCuerpoAfec', 'accidenteLaboral.perdida', 'identificacionLesion.nombre as identifiLesion', 'accidenteLaboral.id_Tlesion', 'accidenteLaboral.formaProdujo', 'accidenteLaboral.nivelaccidente', 'users.name', 'users.last_name', 'company.razonsocial', 'users.id_area', 'areas.name as nameArea', 'users.num_documento')
					->leftjoin('users', 'users.id', '=', 'accidenteLaboral.id_users')
					->leftjoin('parteCuerpoAfectada', 'parteCuerpoAfectada.id_cuerpo', '=', 'accidenteLaboral.id_cuerpo')
					->leftjoin('identificacionLesion', 'identificacionLesion.id_identLesion', '=', 'accidenteLaboral.id_identlesion')
					->leftjoin('company', 'company.id', '=', 'users.company_id')
					->leftjoin('areas', 'areas.id', '=', 'users.id_area')
					->where('accidenteLaboral.id_empresa', $id_empresa)
					->whereBetween('accidenteLaboral.fecha_accidente', [$fechaIni, $fechaFin])
					->orderBy('accidenteLaboral.id_accidente', 'DESC')
					->get();

				$adjuntos = DB::table('accidenteLabFile')
					->select('accidenteLabFile.id_accidente', 'accidenteLabFile.urlLugar', 'accidenteLabFile.archivoAgente', 'accidenteLaboral.id_empresa', 'accidenteLaboral.fecha_accidente')
					->leftjoin('accidenteLaboral', 'accidenteLaboral.id_accidente', '=', 'accidenteLabFile.id_accidente')
					->where([['accidenteLaboral.id_empresa', $id_empresa]])
					->whereBetween('accidenteLaboral.fecha_accidente', [$fechaIni, $fechaFin])
					->where(function ($query) {
						$query->whereNotNull('accidenteLabFile.urlLugar')
							->orWhereNotNull('accidenteLabFile.archivoAgente');
					})
					->get();

				$sheet->row(1, ['Empresa', 'Area', 'Empleado', 'Cédula', 'Tipo de Accidente', 'Fecha del Accidente', 'Hora del Accidente', 'Día de la Semana', 'Tipo de Suceso', 'Parte del Cuerpo Afectada', 'Pérdidas', 'Tipo de Lesión', 'Identificación de la Lesión', 'Lugar del Accidente', 'Forma en que se produjo', 'Nivel de Accidente', 'Adjuntos']);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera


				foreach ($accidentes as $index => $Export) {

					if ($Export->dia == 1) {
						$dias = 'Lunes';
					} else if ($Export->dia == 2) {
						$dias = 'Martes';
					} else if ($Export->dia == 3) {
						$dias = 'Miercoles';
					} else if ($Export->dia == 4) {
						$dias = 'Jueves';
					} else if ($Export->dia == 5) {
						$dias = 'Viernes';
					} else if ($Export->dia == 6) {
						$dias = 'Sabado';
					} else if ($Export->dia == 7) {
						$dias = 'Domingo';
					}

					$adjunto = 'No tiene adjuntos';

					foreach ($adjuntos as  $index => $value) {
						if ($value->id_accidente == $Export->id_accidente) {
							$adjunto = 'Tiene adjuntos';
						}
					}


					$tiposuceso = '';
					if ($Export->id_tiposuceso == 1) {
						$tiposuceso = 'INCIDENTE';
					} else if ($Export->id_tiposuceso == 2) {
						$tiposuceso = 'ACCIDENTE DE TRABAJO';
					} else if ($Export->id_tiposuceso == 3) {
						$tiposuceso = 'ACCIDENTE GRAVE';
					} else if ($Export->id_tiposuceso == 4) {
						$tiposuceso = 'ACCIDENTE SEVERO';
					} else if ($Export->id_tiposuceso == 5) {
						$tiposuceso = 'ACCIDENTE LEVE';
					} else if ($Export->id_tiposuceso == 6) {
						$tiposuceso = 'ACCIDENTE DE TRANSITO QUE COMPRENDE LOS CRITERIOS PARA ACCIDENTE DE TRABAJO';
					} else if ($Export->id_tiposuceso == 7) {
						$tiposuceso = 'SINIESTRO MORTAL';
					}

					$perdidas = '';
					if ($Export->perdida == 1) {
						$perdidas = 'Materiales';
					} else if ($Export->perdida == 2) {
						$perdidas = 'Humanas';
					}



					$sheet->row($index + 2, [
						$Export->razonsocial,
						$Export->nameArea,
						$Export->name . ' ' . $Export->last_name,
						$Export->num_documento,
						$Export->id_tipoaccidente,
						$Export->fecha_accidente,
						$Export->horatrabajo,
						$dias,
						$tiposuceso,
						$Export->parteCuerpoAfec,
						$perdidas,
						$Export->id_Tlesion,
						$Export->identifiLesion,
						$Export->lugar_accidente,
						$Export->formaProdujo,
						$Export->nivelaccidente,
						$adjunto
					]);
				}
			});

			// Hoja 2
			$excel->sheet('Investigacion de Accidentes.', function ($sheet) use ($id_empresa, $fechaIni, $fechaFin) {

				$investigacion = DB::table('accidenteLabInvestigacion')
					->select('accidenteLabInvestigacion.id_users', 'accidenteLabInvestigacion.id_investigacion', 'accidenteLabInvestigacion.fechaIvg', 'accidenteLabInvestigacion.relatoAccidentado', 'accidenteLabInvestigacion.obs', 'accidenteLabInvestigacion.testigo1', 'accidenteLabInvestigacion.testigo2', 'accidenteLabInvestigacion.testigo3', 'accidenteLabInvestigacion.relatoTestigo1', 'accidenteLabInvestigacion.relatoTestigo2', 'accidenteLabInvestigacion.relatoTestigo3', 'accidente_investigadores.nombre_responsable', 'accidente_investigadores.cargo_responsable', 'accidente_investigadores.cedula_responsable', 'accidente_investigadores.obs_responsable', 'accidente_investigadores.nombre_integrante', 'accidente_investigadores.cargo_integrante', 'accidente_investigadores.cedula_integrante', 'accidente_investigadores.obs_integrante', 'accidente_investigadores.nombre_jefe', 'accidente_investigadores.cargo_jefe', 'accidente_investigadores.cedula_jefe', 'accidente_investigadores.obs_jefe', 'accidente_variables_costo.num7', 'accidente_variables_costo.num12', 'accidente_variables_costo.num16', 'accidente_variables_costo.num17', 'users.name', 'users.last_name', 'users.num_documento', 'accidenteLaboral.id_empresa', 'company.razonsocial')
					->leftjoin('users', 'users.id', '=', 'accidenteLabInvestigacion.id_users')
					->leftjoin('accidenteLaboral', 'accidenteLaboral.id_accidente', '=', 'accidenteLabInvestigacion.id_accidente')
					->leftjoin('accidente_investigadores', 'accidente_investigadores.id_investigacion', '=', 'accidenteLabInvestigacion.id_investigacion')
					->leftjoin('accidente_variables_costo', 'accidente_variables_costo.id_investigacion', '=', 'accidenteLabInvestigacion.id_investigacion')
					->leftjoin('company', 'company.id', '=', 'users.company_id')
					->where([['accidenteLaboral.id_empresa', $id_empresa]])
					->whereBetween('accidenteLaboral.fecha_accidente', [$fechaIni, $fechaFin])
					->get();

				$adjuntos = DB::table('accidenteLabFile')
					->select('accidenteLabFile.id_investigacion', 'accidenteLabFile.archivoInvestigacion', 'accidenteLabFile.urlInvestigacion', 'accidenteLabFile.investigacion_firmada', 'accidenteLabFile.archivoFoto', 'accidenteLabFile.archivoZona', 'accidenteLabFile.archivoAccidente', 'accidenteLabFile.archivoInvestM', 'accidenteLabFile.archivoInvestA', 'accidenteLabFile.archivoInvest', 'accidenteLabInvestigacion.id_empresa', 'accidenteLabInvestigacion.fechaIvg')
					->leftjoin('accidenteLabInvestigacion', 'accidenteLabInvestigacion.id_investigacion', '=', 'accidenteLabFile.id_investigacion')
					->where([['accidenteLabInvestigacion.id_empresa', $id_empresa]])
					->whereBetween('accidenteLabInvestigacion.fechaIvg', [$fechaIni, $fechaFin])
					->where(function ($query) {
						$query->whereNotNull('accidenteLabFile.urlInvestigacion')
							->orWhereNotNull('accidenteLabFile.investigacion_firmada')
							->orWhereNotNull('accidenteLabFile.investigacion_firmada')
							->orWhereNotNull('accidenteLabFile.archivoFoto')
							->orWhereNotNull('accidenteLabFile.archivoZona')
							->orWhereNotNull('accidenteLabFile.archivoAccidente')
							->orWhereNotNull('accidenteLabFile.archivoInvestM')
							->orWhereNotNull('accidenteLabFile.archivoInvestA')
							->orWhereNotNull('accidenteLabFile.archivoInvest');
					})
					->orderBy('accidenteLabInvestigacion.id_investigacion', 'DESC')
					->get();

				// dd($investigacion);

				$sheet->row(1, ['Empresa', 'Empleado', 'Cédula', 'Fecha Investigacion', 'Relato Trabajador Accidentado', 'Observaciones', 'Testigo 1', 'Relato', 'Testigo 2', 'Relato', 'Testigo 3', 'Relato', 'Responsable del SG-SST', 'Cargo', 'Cedula', 'Observacion', 'Integrante del COPPAST o Vigía', 'Cargo', 'Cedula', 'Observacion', 'Jefe Inmediato', 'Cargo', 'Cedula', 'Observacion', 'Costo de tiempo perdido', 'Costo por daños materiales', 'Gastos generales', 'Costo total', 'Adjuntos']);
				$sheet->row(1, function ($row) {
					$row->setBackground('#33f9ff');
				}); //ponemos color a la cabecera

				foreach ($investigacion as $index => $comps) {

					$adjunto = 'No tiene adjuntos';
					foreach ($adjuntos as $value) {
						if ($comps->id_investigacion == $value->id_investigacion) {
							$adjunto = 'Tiene adjuntos';
						}
					}

					$sheet->row($index + 2, [
						$comps->razonsocial,
						$comps->name . ' ' . $comps->last_name,
						$comps->num_documento,
						$comps->fechaIvg,
						$comps->relatoAccidentado,
						$comps->obs,
						$comps->testigo1,
						$comps->relatoTestigo1,
						$comps->testigo2,
						$comps->relatoTestigo2,
						$comps->testigo3,
						$comps->relatoTestigo3,
						$comps->nombre_responsable,
						$comps->cargo_responsable,
						$comps->cedula_responsable,
						$comps->obs_responsable,
						$comps->nombre_integrante,
						$comps->cargo_integrante,
						$comps->cedula_integrante,
						$comps->obs_integrante,
						$comps->nombre_jefe,
						$comps->cargo_jefe,
						$comps->cedula_jefe,
						$comps->obs_jefe,
						$comps->num7,
						$comps->num12,
						$comps->num16,
						$comps->num17,
						$adjunto
					]);
				}
			});

			$excel->sheet('Plan de Mejora.', function ($sheet) use ($id_empresa, $fechaIni, $fechaFin) {


				$plan = DB::table('accidente_planes')
					->select('accidente_planes.*', 'users.name', 'users.last_name', 'users.num_documento as cedula', 'company.razonsocial')
					->leftjoin('accidenteLaboral', 'accidenteLaboral.id_accidente', '=', 'accidente_planes.id_accidente')
					->leftjoin('users', 'users.id', '=', 'accidente_planes.id_user')
					->leftjoin('company', 'company.id', '=', 'users.company_id')
					->where([['accidente_planes.id_company', $id_empresa]])
					->whereBetween('accidente_planes.FechaRegis', [$fechaIni, $fechaFin])
					->get();


				$sheet->row(1, ['Empresa', 'Empleado', 'Cédula', 'Fecha de Registro', 'Medida a Implementar', 'Tipo de Medida', 'Responsable', 'Fecha límite de cumplimiento', 'Observaciones', 'Fuente', 'Medio', 'Persona']);
				$sheet->row(1, function ($row) {
					$row->setBackground('#33f9ff');
				}); //ponemos color a la cabecera

				foreach ($plan as $index => $comps) {

					$medida = '';
					if ($comps->tipoMedida == 1) {
						$medida = 'Preventiva';
					} else {
						$medida = 'Correctiva';
					}

					$sheet->row($index + 2, [
						$comps->razonsocial,
						$comps->name . ' ' . $comps->last_name,
						$comps->cedula,
						$comps->FechaRegis,
						$comps->medida,
						$medida,
						$comps->responsable,
						$comps->fecha,
						$comps->observaciones,
						$comps->fuente,
						$comps->medio,
						$comps->persona
					]);
				}
			});
		})->export('xls');
	}

	public function rehabilitacionExcel(request $request)
	{
		//dd($request);
		$id_empresa = $request->id_empresa;
		$fechaIni = $request->fechaDesde;
		$fechaFin = $request->fechaHasta;
		Excel::create('Reporte de Usuarios con Rehabilitación Medico Laboral', function ($excel) use ($id_empresa, $fechaIni, $fechaFin) {

			$excel->sheet('Empleados con Rehabilitación', function ($sheet) use ($id_empresa, $fechaIni, $fechaFin) {

				$rehabilitacion = DB::table('pRehabilitacion')
					->select('pRehabilitacion.id_programa', 'pRehabilitacion.tipoPrograma as programa', 'pRehabilitacion.area', 'pRehabilitacion.fechaInicio', 'pRehabilitacion.fechaFinal', 'pRehabilitacion.motivo', 'pRehabilitacion.obs as observaciones', 'company.razonsocial', 'users.name', 'users.last_name', 'users.num_documento')
					->leftjoin('users', 'users.id', '=', 'pRehabilitacion.id_users')
					->leftjoin('company', 'company.id', '=', 'pRehabilitacion.id_empresa')
					->where('pRehabilitacion.id_empresa', $id_empresa)
					->whereBetween('pRehabilitacion.fechaInicio', [$fechaIni, $fechaFin])
					->orderBy('pRehabilitacion.id_programa', 'DESC')
					->get();

				$adjuntos = DB::table('archivoRehabilitacion')
					->select('archivoRehabilitacion.id_programa', 'archivoRehabilitacion.urlA', 'archivoRehabilitacion.urlD', 'pRehabilitacion.id_empresa', 'pRehabilitacion.fechaInicio')
					->leftjoin('pRehabilitacion', 'pRehabilitacion.id_programa', '=', 'archivoRehabilitacion.id_programa')
					->where([['pRehabilitacion.id_empresa', $id_empresa]])
					->whereBetween('pRehabilitacion.fechaInicio', [$fechaIni, $fechaFin])
					->where(function ($query) {
						$query->whereNotNull('archivoRehabilitacion.urlA')
							->orWhereNotNull('archivoRehabilitacion.urlD');
					})
					->get();


				$sheet->row(1, ['Empresa', 'Área', 'Empleado', 'Cédula', 'Programa', 'Fecha de Inicio', 'Fecha Fin', 'Motivo', 'Observaciones', 'Adjuntos']);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera


				foreach ($rehabilitacion as $index => $Export) {

					if ($Export->programa == 1) {
						$programas = 'Restricción';
					} else if ($Export->programa == 2) {
						$programas = 'Recomendación';
					} else if ($Export->programa == 3) {
						$programas = 'Reubicación';
					} else if ($Export->programa == 4) {
						$programas = 'Readaptación';
					}

					foreach ($adjuntos as $value) {
						if ($value->id_programa == $Export->id_programa) {
							$adjunto = 'Tiene adjuntos';
						} else {
							$adjunto = 'No tiene adjuntos';
						}
					}

					$sheet->row($index + 2, [
						$Export->razonsocial,
						$Export->area,
						$Export->name . ' ' . $Export->last_name,
						$Export->num_documento,
						$programas,
						$Export->fechaInicio,
						$Export->fechaFinal,
						$Export->motivo,
						$Export->observaciones,
						$adjunto
					]);
				}
			});

			// Hoja 2
			$excel->sheet('Prorrogas.', function ($sheet) use ($id_empresa, $fechaIni, $fechaFin) {

				$prorroga = DB::table('prorrogas')
					->select('prorrogas.fechaInicioP', 'prorrogas.fechaFinP', 'prorrogas.observaciones', 'prorrogas.id_cuatroR', 'users.name', 'users.last_name', 'users.num_documento', 'pRehabilitacion.id_empresa', 'company.razonsocial', 'pRehabilitacion.id_programa')
					->leftjoin('pRehabilitacion', 'prorrogas.id_cuatroR', '=', 'pRehabilitacion.id_programa')
					->leftjoin('users', 'users.id', '=', 'prorrogas.id_empleado')
					->leftjoin('company', 'company.id', '=', 'pRehabilitacion.id_empresa')
					->where([['prorrogas.id_empresa', $id_empresa]])
					->whereBetween('prorrogas.created_at', [$fechaIni, $fechaFin])
					->where(function ($query) {
						$query->WhereNotNull('prorrogas.id_cuatroR');
					})
					->get();

				// dd($prorroga);	


				$sheet->row(1, ['Empresa', 'Empleado', 'Cédula', 'Fecha Inicio', 'Fecha Final', 'Observaciones']);
				$sheet->row(1, function ($row) {
					$row->setBackground('#33f9ff');
				}); //ponemos color a la cabecera

				foreach ($prorroga as $index => $comps) {

					$sheet->row($index + 2, [
						$comps->razonsocial,
						$comps->name . ' ' . $comps->last_name,
						$comps->num_documento,
						$comps->fechaInicioP,
						$comps->fechaFinP,
						$comps->observaciones
					]);
				}
			});
		})->export('xls');
	}




	public function stockEppExcel(request $request)
	{
		//dd($request);
		$id_empresa = $request->id_empresa;
		$fechaIni = $request->fechaDesde;
		$fechaFin = $request->fechaHasta;
		Excel::create('Reporte stock EPP', function ($excel) use ($id_empresa, $fechaIni, $fechaFin) {

			$excel->sheet('Stock EPP', function ($sheet) use ($id_empresa, $fechaIni, $fechaFin) {

				//$debitos = Debito::all();
				$sctock = DB::table('epp_elementosEmpresa')
					->select('epp_elementosEmpresa.id_elemento', 'epp_elementosEmpresa.id_empresa', 'epp_elementosEmpresa.reff', 'epp_elementosEmpresa.nombre', 'epp_elementosEmpresa.estado', 'company.id', 'company.razonsocial', 'epp_IntStock.id_empresa as idEmpresa', 'epp_IntStock.id_elemento as idElemento', 'epp_IntStock.stock_total', 'epp_IntStock.stock_pendiente', 'epp_IntStock.stock_virtual', 'epp_preciosElementos.id_precio', 'epp_preciosElementos.id_empresa as id_cia', 'epp_preciosElementos.id_epp', 'epp_preciosElementos.Pvp1', 'epp_preciosElementos.Pvp2', 'epp_preciosElementos.Pvp3', 'epp_preciosElementos.Pvp4', 'epp_elementosDetalle.id_elemento as Elemento_id', 'epp_elementosDetalle.tipoE', 'epp_elementosDetalle.peligro', 'epp_elementosDetalle.marca', 'epp_elementosDetalle.id_partCuerpo', 'epp_partesProteccion.id as id_partes', 'epp_partesProteccion.proteccion', 'epp_stock_Hist_Epp.id_Hstock', 'epp_stock_Hist_Epp.pvp')
					->leftjoin('company', 'company.id', '=', 'epp_elementosEmpresa.id_empresa')
					->leftjoin('epp_IntStock', 'epp_IntStock.id_elemento', '=', 'epp_elementosEmpresa.id_elemento')
					->leftjoin('epp_preciosElementos', 'epp_preciosElementos.id_epp', '=', 'epp_elementosEmpresa.id_elemento')
					->leftjoin('epp_elementosDetalle', 'epp_elementosDetalle.id_elemento', '=', 'epp_elementosEmpresa.id_elemento')
					->leftjoin('epp_partesProteccion', 'epp_partesProteccion.id', '=', 'epp_elementosDetalle.id_partCuerpo')
					->leftjoin('epp_stock_Hist_Epp', 'epp_stock_Hist_Epp.id_Hstock', '=', 'epp_preciosElementos.Pvp1')
					->where([['epp_elementosEmpresa.estado', '=', 1], ['epp_elementosEmpresa.id_empresa', '=', $id_empresa]])
					->whereBetween('epp_elementosEmpresa.created_at', [$fechaIni, $fechaFin])
					->get();


				$sheet->row(1, ['Empresa', 'Referencia', 'EPP', 'Marca', 'Tipo de elemeto', 'Peligro', 'Protección', 'Cantidad en stock', 'Cantidad pendiente entregar', 'Cantidad virtual', 'Precio de compra', 'Precio de costo', 'Precio de comparación', 'Último precio de compra']);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				foreach ($sctock as $index => $Export) {

					$tipo = $Export->tipoE;

					if ($tipo == 1) {
						$tipo = 'Desechable';
					} else {
						$tipo = 'No Desechable';
					}
					$sheet->row($index + 2, [
						$Export->razonsocial,
						$Export->reff,
						$Export->nombre,
						$Export->marca,
						$tipo,
						$Export->peligro,
						$Export->proteccion,
						$Export->stock_total,
						$Export->stock_pendiente,
						$Export->stock_virtual,
						$Export->pvp,
						$Export->Pvp2,
						$Export->Pvp3,
						$Export->Pvp4
					]);
				}
			});
		})->export('xls');
	}

	// CONTROL DE EMPRESAS CON SVE CONTRATATADO 
	public function sveServicioExcel(request $request)
	{

		$fechaIni = $request->fechaDesde;
		$fechaFin = $request->fechaHasta;

		Excel::create(
			' Cias Contratos SVE',
			function ($excel) use ($fechaIni, $fechaFin) {
				$excel->sheet('Contratos SVE', function ($sheet) use ($fechaIni, $fechaFin) {

					$promedioCia = DB::table('company')
					->select([
						'company.id',
						'company.razonsocial',
						'company.emailContactoSST',
						'company.teleContactoSST',
						'company.contactoSST',
						'company.estado',
						'pmc.id_company',
						'pmc.id_modulo',
						'pmc.created_at',
						're.email',
						're.cabecera',
						're.cuerpo',
						're.fecha'
					])
					->from('company')
					->leftJoin('planesModulosClientes as pmc', function($join) use ($fechaIni, $fechaFin) {
						$join->on('pmc.id_company', '=', 'company.id')
							->where(function($query) {
								$query->where('pmc.id_modulo', '=', 3)
									  ->orWhere('pmc.id_plan', '=', 3);
							})
							->whereBetween('pmc.created_at', [$fechaIni, $fechaFin]);
					})
					->leftJoin('sve_reporteEmail as re', 're.id_company', '=', 'company.id')
					->where('company.estado', '=', 1)
					->groupBy('pmc.id_company')
					->orderBy('company.razonsocial')
					->get();

					$sheet->row(1, [
						'Empresa',
						'Contacto SST',
						'Email SST',
						'Teléfono',
						'Fecha de Inicio',
						'cuerpo',
						'fecha envio email'
					]);
					foreach ($promedioCia as $index => $lct) {
						//**$difDia = $lct->difDia;
						//**if ($difDia <= 0) {
						//**	$difDia = (($difDia) * -1) . ' ' . 'Días vencido';
						//**} else {
						//**	$difDia = 'Próximo a vencer' . ' ' . $difDia . ' ' . 'Días';
						//**}

						$sheet->row($index + 2, [
							$lct->razonsocial,
							$lct->contactoSST,
							$lct->emailContactoSST,
							$lct->teleContactoSST,
							$lct->created_at,
							$lct->cuerpo,
							$lct->fecha
						]);
					}
				});
			}
		)->export('xls');
	}


	// FIN CONTROL DE EMPRESAS CON SVE CONTRATATADO 

	//INICIO INFORMES SVE  CTL EVALUACIONES

	public function sveCtlEvaluacion(request $request)
	{

		$fechaIni = $request->fechaDesde;
		$fechaFin = $request->fechaHasta;

		Excel::create(' Control Evaluaciones SVE', function ($excel) use ($fechaIni, $fechaFin) {
			$excel->sheet('Evaluación Etapa 1', function ($sheet) use ($fechaIni, $fechaFin) {

				$promedioUser = DB::table('sve_resultado_globalH')
					->select('sve_resultado_globalH.id_resultado', 'sve_resultado_globalH.id_user', 'sve_resultado_globalH.fechaRevisionTest', 'users.name', 'users.last_name', 'users.num_documento', 'users.cargo', 'sve_resultado_globalH.test', 'sve_resultado_globalH.riesgoTest', 'sve_resultado_control.created_at', 'sve_resultado_control.pesoUser', 'sve_resultado_control.tallaUser', 'sve_resultado_control.imcUser', 'users.fecha_nacimiento', 'sve_resultado_control.fechaRevision', 'sve_resultado_globalH.id_company', 'company.id as id_cia', 'company.razonsocial', DB::raw("DATEDIFF(sve_resultado_globalH.fechaRevisionTest,CURDATE()) as difDia"), 'sve_reporteEmail.email', 'sve_reporteEmail.cabecera', 'sve_reporteEmail.cuerpo', 'sve_reporteEmail.fecha')
					->leftjoin('users', 'sve_resultado_globalH.id_user', '=', 'users.id')
					->leftjoin('sve_resultado_control', 'sve_resultado_globalH.id_user', '=', 'sve_resultado_control.id_user')
					->leftjoin('company', 'company.id', '=', 'sve_resultado_globalH.id_company')
					->leftjoin('sve_reporteEmail', 'sve_reporteEmail.id_user', 'sve_resultado_globalH.id_user')
					->where('users.estado', 1)
					->whereBetween('sve_resultado_control.created_at', [$fechaIni, $fechaFin])
					->groupBy('sve_resultado_globalH.created_at')
					->orderBy('company.razonsocial')
					->get();

				$sheet->row(1, [
					'Empresa',
					'Empleado',
					'Cargo',
					'IMC',
					'Resultado',
					'Riesgo',
					'Última evaluación',
					'Próxima evaluación',
					'Días faltantes o vencidos',
					'email',
					'cabecera',
					'cuerpo',
					'fecha envio email'
				]);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				foreach ($promedioUser as $index => $lct) {
					$riesgoTest = $lct->riesgoTest;

					if ($riesgoTest == 1) {
						$riesgoTest = 'Riesgo leve de enfermedad';
					} elseif ($riesgoTest == 2) {
						$riesgoTest = 'Riesgo moderado de enfermedad';
					} elseif ($riesgoTest >= 3) {
						$riesgoTest = 'Riesgo alto de enfermedad';
					}

					$difDia = $lct->difDia;
					if ($difDia <= 0) {
						$difDia = (($difDia) * -1) . ' ' . 'Días vencido';
					} else {
						$difDia = 'Próximo a vencer' . ' ' . $difDia . ' ' . 'Días';
					}


					$sheet->row($index + 2, [
						$lct->razonsocial,
						$lct->name . ' ' . $lct->name,
						$lct->cargo,
						$lct->imcUser,
						$riesgoTest,
						$lct->cabecera,
						$lct->created_at,
						$lct->fechaRevisionTest,
						$difDia,
						$lct->email,
						$lct->cabecera,
						$lct->cuerpo,
						$lct->fecha
					]);
				}
			});

			//segunda hoja

			$excel->sheet('Evaluación Etapa 2.1', function ($sheet) use ($fechaIni, $fechaFin) {
				$promedioUser4 = DB::table('sve_resultado_global')
					->select('sve_resultado_global.id_resultado', 'sve_resultado_global.id_user as idUsusario', 'users.id as idUsusario', 'users.name', 'users.last_name', 'users.num_documento', 'users.cargo', 'sve_resultado_global.test', 'sve_resultado_global.resultadoTest', 'sve_resultado_global.riesgoTest', 'sve_resultado_global.created_at', 'sve_resultado_global.fechaRevisionTest', 'company.id as id_cia', 'company.razonsocial', 'sve_resultado_control.imcUser', 'sve_resultado_control.id_user', DB::raw("DATEDIFF(sve_resultado_global.fechaRevisionTest,CURDATE()) as difDia"), 'sve_reporteEmail.email', 'sve_reporteEmail.cabecera', 'sve_reporteEmail.cuerpo', 'sve_reporteEmail.fecha')
					->leftjoin('users', 'sve_resultado_global.id_user', '=', 'users.id')
					->leftjoin('company', 'company.id', '=', 'sve_resultado_global.id_company')
					->leftjoin('sve_resultado_control', 'sve_resultado_control.id_user', '=', 'sve_resultado_global.id_user')
					->leftjoin('sve_reporteEmail', 'sve_reporteEmail.id_user', 'sve_resultado_global.id_user')
					->where([['users.estado', 1], ['sve_resultado_global.test', '=', 4]])
					->whereBetween('sve_resultado_global.created_at', [$fechaIni, $fechaFin])
					->groupBy('sve_resultado_global.fechaRevisionTest')
					->orderBy('company.razonsocial')
					->get();

				$sheet->row(1, [
					'Empresa',
					'Empleado',
					'Cargo',
					'IMC',
					'Resultado',
					'Riesgo',
					'Última evaluación',
					'Próxima evaluación',
					'Días faltantes o vencidos',
					'email',
					'cabecera',
					'cuerpo',
					'fecha envio email'
				]);
				$sheet->row(1, function ($row) {
					$row->setBackground('#33FFF0');
				}); //ponemos color a la cabecera

				foreach ($promedioUser4 as $index => $lct) {
					$riesgoTest = $lct->riesgoTest;

					if ($riesgoTest == 1) {
						$riesgoTest = 'Riesgo leve de enfermedad lumbar';
					} elseif ($riesgoTest == 2) {
						$riesgoTest = 'Riesgo moderado de enfermedad lumbar ';
					} elseif ($riesgoTest >= 3) {
						$riesgoTest = 'Riesgo alto de enfermedad lumbar';
					}

					$difDia = $lct->difDia;
					if ($difDia <= 0) {
						$difDia = (($difDia) * -1) . ' ' . 'Días vencido';
					} else {
						$difDia = 'Próximo a vencer' . ' ' . $difDia . ' ' . 'Días';
					}
					$sheet->row($index + 2, [
						$lct->razonsocial,
						$lct->name . ' ' . $lct->name,
						$lct->cargo,
						$lct->imcUser,
						$riesgoTest,
						$lct->cabecera,
						$lct->created_at,
						$lct->fechaRevisionTest,
						$difDia,
						$lct->email,
						$lct->cabecera,
						$lct->cuerpo,
						$lct->fecha
					]);
				}
			});

			//tercera hoja
			$excel->sheet('Evaluación Etapa 2.2', function ($sheet) use ($fechaIni, $fechaFin) {
				$promedioUser5 = DB::table('sve_resultado_global')
					->select('sve_resultado_global.id_resultado', 'sve_resultado_global.id_user as idUsusario', 'users.id as idUsusario', 'users.name', 'users.last_name', 'users.num_documento', 'users.cargo', 'sve_resultado_global.test', 'sve_resultado_global.resultadoTest', 'sve_resultado_global.riesgoTest', 'sve_resultado_global.created_at', 'sve_resultado_global.fechaRevisionTest', 'company.id as id_cia', 'company.razonsocial', 'sve_resultado_control.imcUser', 'sve_resultado_control.id_user as user_id', DB::raw("DATEDIFF(sve_resultado_global.fechaRevisionTest,CURDATE()) as difDia"), 'sve_reporteEmail.email', 'sve_reporteEmail.cabecera', 'sve_reporteEmail.cuerpo', 'sve_reporteEmail.fecha')
					->leftjoin('users', 'sve_resultado_global.id_user', '=', 'users.id')
					->leftjoin('company', 'company.id', '=', 'sve_resultado_global.id_company')
					->leftjoin('sve_resultado_control', 'sve_resultado_control.id_user', '=', 'sve_resultado_global.id_user')
					->leftjoin('sve_reporteEmail', 'sve_reporteEmail.id_user', 'sve_resultado_global.id_user')
					->where([['users.estado', 1], ['sve_resultado_global.test', '=', 5]])
					->whereBetween('sve_resultado_global.created_at', [$fechaIni, $fechaFin])
					->groupBy('sve_resultado_global.fechaRevisionTest')
					->orderBy('company.razonsocial')
					->get();

				$sheet->row(1, [
					'Empresa',
					'Empleado',
					'Cargo',
					'IMC',
					'Resultado',
					'Riesgo',
					'Última evaluación',
					'Próxima evaluación',
					'Días faltantes o vencidos',
					'email',
					'cabecera',
					'cuerpo',
					'fecha envio email'
				]);
				$sheet->row(1, function ($row) {
					$row->setBackground('#33F0FF');
				}); //ponemos color a la cabecera

				foreach ($promedioUser5 as $index => $lct) {
					$riesgoTest = $lct->riesgoTest;

					if ($riesgoTest == 1) {
						$riesgoTest = 'Riesgo discapacidad leve en sus ABC';
					} elseif ($riesgoTest == 2) {
						$riesgoTest = 'Riesgo discapacidad moderada en sus ABC';
					} elseif ($riesgoTest >= 3) {
						$riesgoTest = 'Riesgo discapacidad alta en sus ABC';
					}

					$difDia = $lct->difDia;
					if ($difDia <= 0) {
						$difDia = (($difDia) * -1) . ' ' . 'Días vencido';
					} else {
						$difDia = 'Próximo a vencer' . ' ' . $difDia . ' ' . 'Días';
					}

					$sheet->row($index + 2, [
						$lct->razonsocial,
						$lct->name . ' ' . $lct->name,
						$lct->cargo,
						$lct->imcUser,
						$riesgoTest,
						$lct->cabecera,
						$lct->created_at,
						$lct->fechaRevisionTest,
						$difDia,
						$lct->email,
						$lct->cabecera,
						$lct->cuerpo,
						$lct->fecha
					]);
				}
			});


			//cuarta hoja

			$excel->sheet('Evaluación MII', function ($sheet) use ($fechaIni, $fechaFin) {
				$resultInferior = DB::table('sve_riesgoInferior')
					->select(
						'sve_resultado_control.id_resultado',
						'sve_resultado_control.id_user as idUser',
						'users.name',
						'users.last_name',
						'users.fecha_nacimiento',
						'users.cargo',
						'users.num_documento',
						'sve_resultado_control.id_company',
						'sve_resultado_control.pesoUser',
						'sve_resultado_control.tallaUser',
						'sve_resultado_control.imcUser',
						'sve_resultado_control.fechaRevision',
						'sve_riesgoInferior.created_at',
						'sve_riesgoInferior.id_company',
						'sve_riesgoInferior.id_user',
						'sve_riesgoInferior.risk1',
						'sve_riesgoInferior.risk2',
						'sve_riesgoInferior.risk3',
						'sve_riesgoInferior.risk4',
						'sve_riesgoInferior.risk5',
						'sve_riesgoInferior.newFecha',
						'sve_resultado_control.modulo',
						'sve_resultado_control.resultado',
						'sve_resultado_control.riesgo',
						'company.id as id_cia',
						'company.razonsocial',
						DB::raw("DATEDIFF(sve_riesgoInferior.fecha,CURDATE()) as difDia"),
						'sve_reporteEmail.email',
						'sve_reporteEmail.cabecera',
						'sve_reporteEmail.cuerpo',
						'sve_reporteEmail.fecha'
					)
					->leftjoin('users', 'sve_riesgoInferior.id_user', '=', 'users.id')
					->leftjoin('company', 'company.id', '=', 'sve_riesgoInferior.id_company')
					->leftjoin('sve_resultado_control', 'sve_resultado_control.id_user', '=', 'sve_riesgoInferior.id_user')
					->leftjoin('sve_reporteEmail', 'sve_reporteEmail.id_user', 'sve_riesgoInferior.id_user')
					->where('sve_resultado_control.modulo', 2)
					->whereBetween('sve_riesgoInferior.created_at', [$fechaIni, $fechaFin])
					->groupBy('company.id')
					->orderBy('company.razonsocial')
					->get();

				$sheet->row(1, [
					'Empresa',
					'Empleado',
					'Cargo',
					'IMC',
					'Resultado',
					'Riesgo',
					'Última evaluación',
					'Próxima evaluación',
					'Días faltantes o vencidos',
					'email',
					'cabecera',
					'cuerpo',
					'fecha envio email'
				]);
				$sheet->row(1, function ($row) {
					$row->setBackground('#33C7FF');
				}); //ponemos color a la cabecera

				foreach ($resultInferior as $index => $lct) {
					$riesgoTest = $lct->riesgo;

					if ($riesgoTest == 1) {
						$riesgoTest = 'El trabajador no presenta sintomas';
					} elseif ($riesgoTest == 2) {
						$riesgoTest = 'Riesgo Sintomático de columna lumbosacra';
					} elseif ($riesgoTest >= 3) {
						$riesgoTest = 'El trabajador requiere atención y seguimiento para evitar complicación del cuadro clínico de afectación de la columna L-S ';
					}

					$difDia = $lct->difDia;
					if ($difDia <= 0) {
						$difDia = (($difDia) * -1) . ' ' . 'Días vencido';
					} else {
						$difDia = 'Próximo a vencer' . ' ' . $difDia . ' ' . 'Días';
					}
					$sheet->row($index + 2, [
						$lct->razonsocial,
						$lct->name . ' ' . $lct->name,
						$lct->cargo,
						$lct->imcUser,
						$riesgoTest,
						$lct->cabecera,
						$lct->created_at,
						$lct->fechaRevision,
						$difDia,
						$lct->email,
						$lct->cabecera,
						$lct->cuerpo,
						$lct->fecha
					]);
				}
			});
		})->export('xls');
	}
	// FIN INICIO SVE CTL EVALUACIONES

	// Informe individual resultado de usuarios


	public function exportResultadoI($id_user)
	{


		Excel::create('Resultados SVE MII Empleado', function ($excel) use ($id_user) {

			$excel->sheet('Resultados SVE MII Empleado', function ($sheet) use ($id_user) {


				$promedioUser = DB::table('sve_questionInferior')
					->select('sve_questionInferior.id_user', 'sve_questionInferior.id_question', 'sve_questionInferior.fecha', 'users.name', 'users.last_name', 'users.num_documento', 'users.cargo', DB::raw("count(sve_questionInferior.estado) as total"), 'company.razonsocial', 'sve_riesgoInferior.id_user as user_id', 'sve_riesgoInferior.newFecha', 'sve_riesgoInferior.risk1', 'sve_riesgoInferior.risk2', 'sve_riesgoInferior.risk3', 'sve_riesgoInferior.risk4', 'sve_riesgoInferior.risk5')
					->leftjoin('users', 'sve_questionInferior.id_user', '=', 'users.id')
					->leftjoin('company', 'company.id', '=', 'users.company_id')
					->leftjoin('sve_riesgoInferior', 'sve_riesgoInferior.id_user', '=', 'users.id')
					->where([['sve_questionInferior.id_empresa', $id_user], ['users.estado', 1]])
					->get();

				$sheet->row(1, [
					'Empresa',
					'Empleado',
					'Documento',
					'Cargo',
					'Grupo A.Columna lumbo sacra ',
					'Grupo B. Extremidad inferior ',
					'Grupo C.Rodilla ',
					'Grupo D. Cadera ',
					'Grupo E. Tobillo y pie',
					'riesgo',
					'Fecha de evaluación'
				]);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				foreach ($promedioUser as $index => $result) {
					$riesgo = '';
					if ($result->id_question == 0) {
						$riesgo = 'Riesgo Mínimo';
					} else if ($result->total >= 1 && $result->total <= 2) {
						$riesgo = 'Riesgo Bajo';
					} else if ($result->total >= 3 && $result->total <= 5) {
						$riesgo = 'Riesgo Medio';
					} else if ($result->total >= 6) {
						$riesgo = 'Riesgo Alto';
					}

					if ($result->id_question == 0) {
						$total = 0;
					} else {
						$total = $result->total;
					}


					$sheet->row($index + 2, [
						$result->razonsocial,
						$result->name . " " . $result->last_name,
						$result->num_documento,
						$result->cargo,
						$result->risk1,
						$result->risk2,
						$result->risk3,
						$result->risk4,
						$result->risk5,
						$riesgo,
						$result->fecha,

					]);
				}
			});
		})->export('xls');
	}

	public function saludExcel(request $request)
	{
		//dd($request);

		$fechaIni = $request->fechaDesde;
		$fechaFin = $request->fechaHasta;
		Excel::create('Reporte de Evaluaciones salud laboral', function ($excel) use ($fechaIni, $fechaFin) {

			$excel->sheet('Resultados psico laboral', function ($sheet) use ($fechaIni, $fechaFin) {

				//$debitos = Debito::all();
				$laboral = DB::table('psicoPerfilUserCia')
					->select(
						'psicoEvaluacion.titulo',
						'psicoPerfilUserCia.id_encuesta',
						'psicoPerfilUserCia.riesgo',
						'psicoPerfilUserCia.riesgo',
						'psicoPerfilUserCia.created_at',
						'company.razonsocial',
						'users.name',
						'users.last_name',
						'users.id_region',
						'users.id_ciudad',
						'users.telefono',
						'region.idRegion',
						'region.nombre as nameRegion',
						'ciudades.idCiudad',
						'ciudades.nombre as nameCiudad'
					)
					->leftjoin('company', 'psicoPerfilUserCia.id_company', '=', 'company.id')
					->leftjoin('users', 'psicoPerfilUserCia.id_user', '=', 'users.id')
					->leftjoin('region', 'region.idRegion', '=', 'users.id_region')
					->leftjoin('ciudades', 'ciudades.idCiudad', '=', 'users.id_ciudad')
					->leftjoin('psicoEvaluacion', 'psicoEvaluacion.id', '=', 'psicoPerfilUserCia.id_encuesta')
					->where('psicoPerfilUserCia.estado', 1)
					->whereBetween('psicoPerfilUserCia.updated_at', [$fechaIni, $fechaFin])
					->get();

				$sheet->row(1, ['Titulo ', 'Empresa', 'Empleado', 'Teléfono', 'Región', 'Ciudad', 'Valor Obtenido', 'Fecha de realización']);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				foreach ($laboral as $index => $Export) {
					$riesgo = '';
					if ($Export->id_encuesta ==  111 || $Export->id_encuesta ==  118) {

						if ($Export->riesgo == 1) {
							$riesgo = 'Riesgo Alto';
						} elseif ($Export->riesgo == 2) {
							$riesgo = 'Riesgo Medio';
						} else {
							$riesgo = 'Riesgo Bajo';
						}
					} elseif ($Export->id_encuesta !=  111 || $Export->id_encuesta !=  118) {
						if ($Export->riesgo == 1) {
							$riesgo = 'Riesgo Bajo';
						} elseif ($Export->riesgo == 2) {
							$riesgo = 'Riesgo Medio';
						} else {
							$riesgo = 'Riesgo Alto';
						}
					}


					$sheet->row($index + 2, [
						$Export->titulo,
						$Export->razonsocial,
						$Export->name . ' ' . $Export->last_name,
						$Export->telefono,
						$Export->nameRegion,
						$Export->nameCiudad,
						$riesgo,
						$Export->created_at
					]);
				}
			});
		})->export('xls');
	}

	public function publicoExcel(request $request)
	{
		//dd($request);

		$fechaIni = $request->fechaDesde;
		$fechaFin = $request->fechaHasta;
		Excel::create('Reporte de Evaluaciones salud pública', function ($excel) use ($fechaIni, $fechaFin) {

			$excel->sheet('Resultados psico publica', function ($sheet) use ($fechaIni, $fechaFin) {

				//$debitos = Debito::all();
				$publica = DB::table('psicoPerfilUserWeb')
					->select('psicoEvaluacion.titulo', 'psicoPerfilUserWeb.id_evaluacion', 'psicoPerfilUserWeb.occupation', 'psicoPerfilUserWeb.tipoTrabajo', 'psicoPerfilUserWeb.gender', 'psicoPerfilUserWeb.edad', 'psicoPerfilUserWeb.zone', 'psicoPerfilUserWeb.poblacional', 'psicoPerfilUserWeb.phone', 'psicoPerfilUserWeb.departamento', 'psicoPerfilUserWeb.municipio', 'psicoPerfilUserWeb.ethnic', 'psicoPerfilUserWeb.riesgo', 'psicoPerfilUserWeb.created_at', 'psicoPerfilUserWeb.organization', 'psicoPerfilUserWeb.first_name', 'psicoPerfilUserWeb.last_name', 'region.idRegion', 'region.nombre as nameRegion', 'municipio.codigoMunicipio', 'municipio.nombreMunicipio')
					->leftjoin('psicoEvaluacion', 'psicoEvaluacion.id', '=', 'psicoPerfilUserWeb.id_evaluacion')
					->leftjoin('region', 'region.idRegion', '=', 'psicoPerfilUserWeb.departamento')
					->leftjoin('municipio', 'municipio.codigoMunicipio', '=', 'psicoPerfilUserWeb.municipio')
					->where('psicoPerfilUserWeb.estado', 1)
					->whereBetween('psicoPerfilUserWeb.updated_at', [$fechaIni, $fechaFin])
					->get();

				$sheet->row(1, ['Titulo ', 'Empresa', 'Empleado', 'Genero', 'Edad', 'Teléfono', 'Tipo de trabajo', 'Etnia', 'Población', 'Zona', 'Departamnto', 'Municipio', 'Valor Obtenido', 'Fecha de realización']);

				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				foreach ($publica as $index => $Export) {
					$riesgo = '';
					if ($Export->id_evaluacion ==  111 || $Export->id_evaluacion ==  118) {

						if ($Export->riesgo == 1) {
							$riesgo = 'Riesgo Alto';
						} elseif ($Export->riesgo == 2) {
							$riesgo = 'Riesgo Medio';
						} else {
							$riesgo = 'Riesgo Bajo';
						}
					} elseif ($Export->id_evaluacion !=  111 || $Export->id_evaluacion !=  118) {

						if ($Export->riesgo == 1) {
							$riesgo = 'Riesgo Bajo';
						} elseif ($Export->riesgo == 2) {
							$riesgo = 'Riesgo Medio';
						} else {
							$riesgo = 'Riesgo Alto';
						}
					}

					if ($Export->gender == 1) {
						$genero = 'Mujer';
					} elseif ($Export->gender == 2) {
						$genero = 'Hombre';
					}

					if ($Export->edad == 1) {
						$edad = '18 - 25 años';
					} elseif ($Export->edad == 2) {
						$edad = '26 - 35 años';
					} elseif ($Export->edad == 3) {
						$edad = '36 - 45 años';
					} elseif ($Export->edad == 4) {
						$edad = '46 - 55 años';
					} elseif ($Export->edad == 5) {
						$edad = '56 años o más';
					}

					if ($Export->ethnic == 1) {
						$ethnic = 'Discapacidad';
					} elseif ($Export->ethnic == 2) {
						$ethnic = 'Indígenas';
					} elseif ($Export->ethnic == 3) {
						$ethnic = 'Desplazados';
					} elseif ($Export->ethnic == 4) {
						$ethnic = 'LGTBI+';
					} elseif ($Export->ethnic == 5) {
						$ethnic = 'No aplica';
					}


					if ($Export->poblacional == 1) {
						$poblacional = 'Campesino';
					} elseif ($Export->poblacional == 2) {
						$poblacional = 'Comunidad afro colombiana';
					} elseif ($Export->poblacional == 3) {
						$poblacional = 'No aplica';
					}

					if ($Export->tipoTrabajo == 1) {
						$tipoTrabajo = 'Administrativo';
					} elseif ($Export->tipoTrabajo == 2) {
						$tipoTrabajo = 'Operativo';
					} elseif ($Export->tipoTrabajo == 3) {
						$tipoTrabajo = 'Ambos';
					}

					if ($Export->zone == 1) {
						$zone = 'Urbana';
					} elseif ($Export->zone == 2) {
						$zone = 'Rural';
					}


					$sheet->row($index + 2, [
						$Export->titulo,
						$Export->organization,
						$Export->first_name . ' ' . $Export->last_name,
						$genero,
						$edad,
						$Export->phone,
						$tipoTrabajo,
						$ethnic,
						$poblacional,
						$zone,
						$Export->nameRegion,
						$Export->nombreMunicipio,
						$riesgo,
						$Export->created_at
					]);
				}
			});
		})->export('xls');
	}

	public function climaExcel(request $request)
	{
		//dd($request);

		$fechaIni = $request->fechaDesde;
		$fechaFin = $request->fechaHasta;
		Excel::create('Reporte de Evaluaciones Cambio Climático', function ($excel) use ($fechaIni, $fechaFin) {

			$excel->sheet('Resultados cambio climático', function ($sheet) use ($fechaIni, $fechaFin) {

				//$debitos = Debito::all();
				$clima = DB::table('climaticoPerfilUserWeb')
					->select('psicoEvaluacion.titulo', 'climaticoPerfilUserWeb.id_evaluacion', 'climaticoPerfilUserWeb.organization', 'climaticoPerfilUserWeb.tipoTrabajo', 'climaticoPerfilUserWeb.valorObtenido', 'climaticoPerfilUserWeb.riesgo', 'climaticoPerfilUserWeb.created_at', 'climaticoPerfilUserWeb.zone', 'climaticoPerfilUserWeb.first_name', 'climaticoPerfilUserWeb.last_name', 'climaticoPerfilUserWeb.poblacional', 'climaticoPerfilUserWeb.gender', 'climaticoPerfilUserWeb.edad', 'climaticoPerfilUserWeb.ethnic', 'climaticoPerfilUserWeb.occupation')
					->leftjoin('psicoEvaluacion', 'psicoEvaluacion.id', '=', 'climaticoPerfilUserWeb.id_evaluacion')
					->where('climaticoPerfilUserWeb.estado', '=', 1)
					->whereBetween('climaticoPerfilUserWeb.updated_at', [$fechaIni, $fechaFin])
					->get();

				$sheet->row(1, ['Titulo ', 'Empresa', 'Empleado', 'Genero', 'Ocupación', 'Tipo de trabajo', 'Etnia', 'Población', 'Zona', 'Valor Obtenido', 'Fecha de realización']);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				foreach ($clima as $index => $Export) {
					$riesgo = '';


					if ($Export->riesgo == 1) {
						$riesgo = 'Riesgo Alto';
					} elseif ($Export->riesgo == 2) {
						$riesgo = 'Riesgo Medio';
					} else {
						$riesgo = 'Riesgo Bajo';
					}

					if ($Export->gender == 1) {
						$genero = 'Mujer';
					} elseif ($Export->gender == 2) {
						$genero = 'Hombre';
					}

					if ($Export->edad == 1) {
						$edad = '18 - 25 años';
					} elseif ($Export->edad == 2) {
						$edad = '26 - 35 años';
					} elseif ($Export->edad == 3) {
						$edad = '36 - 45 años';
					} elseif ($Export->edad == 4) {
						$edad = '46 - 55 años';
					} elseif ($Export->edad == 5) {
						$edad = '56 años o más';
					}

					if ($Export->ethnic == 1) {
						$ethnic = 'Discapacidad';
					} elseif ($Export->ethnic == 2) {
						$ethnic = 'Indígenas';
					} elseif ($Export->ethnic == 3) {
						$ethnic = 'Desplazados';
					} elseif ($Export->ethnic == 4) {
						$ethnic = 'LGTBI+';
					} elseif ($Export->ethnic == 5) {
						$ethnic = 'No aplica';
					}


					if ($Export->poblacional == 1) {
						$poblacional = 'Campesino';
					} elseif ($Export->poblacional == 2) {
						$poblacional = 'Comunidad afro colombiana';
					} elseif ($Export->poblacional == 3) {
						$poblacional = 'No aplica';
					}

					if ($Export->tipoTrabajo == 1) {
						$tipoTrabajo = 'Administrativo';
					} elseif ($Export->tipoTrabajo == 2) {
						$tipoTrabajo = 'Operativo';
					} elseif ($Export->tipoTrabajo == 3) {
						$tipoTrabajo = 'Ambos';
					}

					if ($Export->zone == 1) {
						$zone = 'Urbana';
					} elseif ($Export->zone == 2) {
						$zone = 'Rural';
					}


					$sheet->row($index + 2, [
						$Export->titulo,
						$Export->organization,
						$Export->first_name . ' ' . $Export->last_name,
						$genero,
						$edad,
						$tipoTrabajo,
						$zone,
						$poblacional,
						$ethnic,
						$riesgo,
						$Export->created_at
					]);
				}
			});
		})->export('xls');
	}

	public function empresaSavanna()
	{
		Excel::create('Empresa Savanna', function ($excel) {
			$excel->sheet('Empresas registradas Savanna', function ($sheet) {
				$company = DB::table('company_savanna')
					->select('company_savanna.id', 'company_savanna.razonsocial', 'company_savanna.nit', 'company_savanna.contacto', 'company_savanna.nume_empleados', 'company_savanna.estado', 'company_savanna.telefono', 'company_savanna.id_ciudad', 'company_savanna.id_region', 'region.idRegion', 'region.nombre as nameRegion', 'ciudades.idCiudad', 'ciudades.nombre as nameCiudad')
					->leftjoin('region', 'region.idRegion', '=', 'company_savanna.id_region')
					->leftjoin('ciudades', 'ciudades.idCiudad', '=', 'company_savanna.id_ciudad')
					->where('company_savanna.estado', '=', 1)
					->get();

				$sheet->row(1, [
					'Razón Social',
					'Contacto',
					'Teléfono',
					'Número de trabajadores vinculados',
					'Región',
					'Ciudad'
				]);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				foreach ($company as $index => $companys) {
					$sheet->row($index + 2, [
						$companys->razonsocial,
						$companys->contacto,
						$companys->telefono,
						$companys->nume_empleados,
						$companys->nameRegion,
						$companys->nameCiudad
					]);
				}
			});
		})->export('xls');
	}

	public function exportCalcuCardio(Request $request)
	{

		$id_company = $request->id_company;
		$anio = $request->anio;
		$inv = $request->inv;


		Excel::create('Resultados caculadora cardio', function ($excel) use ($id_company, $anio, $inv) {
			$excel->sheet('Resultados caculadora cardio', function ($sheet) use ($id_company, $anio, $inv) {

				if ($inv == 1) {
					$calculadora = DB::table('calculadoraCardio')
						->select(
							'calculadoraCardio.id',
							'calculadoraCardio.id_company',
							'calculadoraCardio.id_user',
							'calculadoraCardio.imc',
							'calculadoraCardio.fechaProEva',
							'calculadoraCardio.valorObtenido',
							'calculadoraCardio.riesgo',
							'calculadoraCardio.id_genero',
							'calculadoraCardio.id_edad',
							'calculadoraCardio.created_at',
							'calculadoraCardio.zona',
							'users.id as user_id',
							'users.name',
							'users.last_name',
							'users.num_documento',
							'users.estrato',
							'users.nivel_estudio',
							'users.cargo',
							'company.id as company_id',
							'company.razonsocial'
						)
						->leftjoin('users', 'users.id', 'calculadoraCardio.id_user')
						->leftjoin('company', 'company.id', 'calculadoraCardio.id_company')
						->where('calculadoraCardio.id_company', $id_company)
						->whereYear('calculadoraCardio.created_at', $anio)
						->get();
				} else {

					$calculadora = DB::table('calculadoraCardio')
						->select(
							'calculadoraCardio.id',
							'calculadoraCardio.id_company',
							'calculadoraCardio.id_user',
							'calculadoraCardio.imc',
							'calculadoraCardio.fechaProEva',
							'calculadoraCardio.valorObtenido',
							'calculadoraCardio.riesgo',
							'calculadoraCardio.id_genero',
							'calculadoraCardio.id_edad',
							'calculadoraCardio.created_at',
							'calculadoraCardio.estado',
							'users.id as user_id',
							'users.name',
							'users.last_name',
							'users.num_documento',
							'users.estrato',
							'users.nivel_estudio',
							'users.cargo',
							'company.id as company_id',
							'company.razonsocial'
						)
						->leftjoin('users', 'users.id', 'calculadoraCardio.id_user')
						->leftjoin('company', 'company.id', 'calculadoraCardio.id_company')
						->where('calculadoraCardio.estado', 1)
						->whereYear('calculadoraCardio.created_at', $anio)
						->get();
				}
				$sheet->row(1, [
					'Razón Social',
					'Trabajador',
					'Cargo',
					'Genero',
					'Edad',
					'IMC',
					'Fecha de realización',
					'Próxima realización',
					'Riesgo',
					'Observaciones'
				]);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				foreach ($calculadora as $index => $Export) {

					if ($Export->riesgo == 1) {
						$riesgo = 'Riesgo Bajo';
					} elseif ($Export->riesgo == 2) {
						$riesgo = 'Riesgo Medio';
					} else {
						$riesgo = 'Riesgo Alto';
					}

					if ($Export->id_genero == 1) {
						$genero = 'Mujer';
					} elseif ($Export->id_genero == 2) {
						$genero = 'Hombre';
					}

					if ($Export->id_edad == 1) {
						$edad = '18 - 25 años';
					} elseif ($Export->id_edad == 2) {
						$edad = '26 - 35 años';
					} elseif ($Export->id_edad == 3) {
						$edad = '36 - 45 años';
					} elseif ($Export->id_edad == 4) {
						$edad = '46 - 55 años';
					} elseif ($Export->id_edad == 5) {
						$edad = '56 años o más';
					}

					switch (true) {
						case ($Export->valorObtenido >= 12 && $Export->valorObtenido <= 14 && $Export->riesgo == 1):
							$observaciones = 'Resultado obtenido; Riesgo bajo';
							break;
					
						case ($Export->valorObtenido === 15 && $Export->riesgo == 1):
							$observaciones = 'Riesgo bajo';
							break;
					
						case ($Export->valorObtenido >= 16 && $Export->valorObtenido <= 18 && $Export->riesgo == 1):
							$observaciones = 'Riesgo bajo que debe monitorearlo';
							break;
					
						case ($Export->valorObtenido >= 19 && $Export->valorObtenido <= 20 && $Export->riesgo == 2):
							$observaciones = 'Riesgo medio - debe mitigar los factores de riesgo presentes';
							break;
					
						case ($Export->valorObtenido >= 21 && $Export->valorObtenido <= 23 && $Export->riesgo == 2):
							$observaciones = 'Riesgo medio que debe monitorear y mitigar los factores de riesgo que ha identificado';
							break;
					
						case ($Export->valorObtenido >= 24 && $Export->valorObtenido <= 25 && $Export->riesgo == 2):
							$observaciones = 'Riesgo medio alto que requiere intervenir los factores de riesgo y acudir a médico especialista';
							break;
					
						case ($Export->valorObtenido >= 26 && $Export->valorObtenido <= 30 && $Export->riesgo == 3):
							$observaciones = 'Riesgo alto que requiere seguimiento estricto, terapia farmacológica y prevención secundaria';
							break;
					
						case ($Export->valorObtenido >= 31 && $Export->valorObtenido <= 36 && $Export->riesgo == 3):
							$observaciones = 'Riesgo muy alto, requiere manejo por equipo interdisciplinario, seguimiento continuo y medidas inmediatas';
							break;
					
						default:
							$observaciones = 'Sin observación definida para este caso';
							break;
					}
					

					$sheet->row($index + 2, [
						$Export->razonsocial,
						$Export->name . ' ' . $Export->last_name,
						$Export->cargo,
						$genero,
						$edad,
						$Export->imc,
						$Export->created_at,
						$Export->fechaProEva,
						$riesgo,
						$observaciones
					]);
				}
			});
		})->export('xls');
	}



	public function exportCardioEva(Request $request)
	{
		
		$id_company = $request->id_company;
		$id_evaluacion = $request->id_evaluacion;
		$anio = $request->anio;
		$inv = $request->inv;

		Excel::create('Resultados evaluación cardio', function ($excel) use ($id_evaluacion, $id_company, $anio, $inv) {
			$excel->sheet('Resultados evaluación cardio', function ($sheet) use ($id_evaluacion, $id_company, $anio, $inv) {

				if ($inv == 2) {
					$resultCia = DB::table('cardioPerfilUserCia')
						->select(
							'cardioPerfilUserCia.id_encuesta',
							'cardioPerfilUserCia.id_company',
							'cardioPerfilUserCia.id_user',
							'cardioPerfilUserCia.fechaProEva',
							'cardioPerfilUserCia.id_edad',
							'cardioPerfilUserCia.valorObtenido',
							'cardioPerfilUserCia.created_at',
							'users.id as usersId',
							'users.name',
							'users.last_name',
							'users.cargo',
							'users.genero',
							'psicoEvaluacion.id',
							'psicoEvaluacion.id_plataforma as plataforma',
							'psicoEvaluacion.titulo',
							'psicoEvaluacion.rangoL',
							'psicoEvaluacion.rangoLm',
							'psicoEvaluacion.rangoM',
							'psicoEvaluacion.rangoMa',
							'psicoEvaluacion.rangoA',
							'psicoEvaluacion.rangoLobserva',
							'psicoEvaluacion.rangoLmobserva',
							'psicoEvaluacion.rangoMobserva',
							'psicoEvaluacion.rangoMaobserva',
							'psicoEvaluacion.rangoAobserva',
							'company.id as id_cia',
							'company.razonsocial',
							'cardioPerfilUserCia.riesgo'
						)
						->leftjoin('users', 'users.id', '=', 'cardioPerfilUserCia.id_user')
						->leftjoin('company', 'company.id', '=', 'cardioPerfilUserCia.id_company')
						->leftjoin('psicoEvaluacion', 'psicoEvaluacion.id', '=', 'cardioPerfilUserCia.id_encuesta')
						->where([['cardioPerfilUserCia.id_encuesta', $id_evaluacion], ['cardioPerfilUserCia.id_company', $id_company]])
						->whereNotNull('cardioPerfilUserCia.riesgo')
						->whereYear('cardioPerfilUserCia.created_at', $anio)
						->get();
				} elseif ($inv == 3) {
					$resultCia = DB::table('cardioPerfilUserCia')
						->select(
							'cardioPerfilUserCia.id_encuesta',
							'cardioPerfilUserCia.id_company',
							'cardioPerfilUserCia.id_user',
							'cardioPerfilUserCia.id_edad',
							'cardioPerfilUserCia.fechaProEva',
							'cardioPerfilUserCia.valorObtenido',
							'cardioPerfilUserCia.created_at',
							'users.id as usersId',
							'users.name',
							'users.last_name',
							'users.cargo',
							'users.genero',
							'psicoEvaluacion.id',
							'psicoEvaluacion.id_plataforma as plataforma',
							'psicoEvaluacion.titulo',
							'psicoEvaluacion.rangoL',
							'psicoEvaluacion.rangoLm',
							'psicoEvaluacion.rangoM',
							'psicoEvaluacion.rangoMa',
							'psicoEvaluacion.rangoA',
							'psicoEvaluacion.rangoLobserva',
							'psicoEvaluacion.rangoLmobserva',
							'psicoEvaluacion.rangoMobserva',
							'psicoEvaluacion.rangoMaobserva',
							'psicoEvaluacion.rangoAobserva',
							'company.id as id_cia',
							'company.razonsocial',
							'cardioPerfilUserCia.riesgo'
						)
						->leftjoin('users', 'users.id', '=', 'cardioPerfilUserCia.id_user')
						->leftjoin('company', 'company.id', '=', 'cardioPerfilUserCia.id_company')
						->leftjoin('psicoEvaluacion', 'psicoEvaluacion.id', '=', 'cardioPerfilUserCia.id_encuesta')
						->where('cardioPerfilUserCia.estado', 1)
						->whereNotNull('cardioPerfilUserCia.riesgo')
						->orderBy('company.razonsocial')
						->get();
				} else {
					$resultCia = DB::table('cardioPerfilUserCia')
						->select(
							'cardioPerfilUserCia.id_encuesta',
							'cardioPerfilUserCia.id_company',
							'cardioPerfilUserCia.id_user',
							'cardioPerfilUserCia.id_edad',
							'cardioPerfilUserCia.fechaProEva',
							'cardioPerfilUserCia.valorObtenido',
							'cardioPerfilUserCia.created_at',
							'users.id as usersId',
							'users.name',
							'users.last_name',
							'users.cargo',
							'users.genero',
							'psicoEvaluacion.id',
							'psicoEvaluacion.areaPsico',
							'psicoEvaluacion.id_plataforma as plataforma',
							'psicoEvaluacion.titulo',
							'psicoEvaluacion.rangoL',
							'psicoEvaluacion.rangoLm',
							'psicoEvaluacion.rangoM',
							'psicoEvaluacion.rangoMa',
							'psicoEvaluacion.rangoA',
							'psicoEvaluacion.rangoLobserva',
							'psicoEvaluacion.rangoLmobserva',
							'psicoEvaluacion.rangoMobserva',
							'psicoEvaluacion.rangoMaobserva',
							'psicoEvaluacion.rangoAobserva',
							'company.id as id_cia',
							'company.razonsocial',
							'cardioPerfilUserCia.riesgo'
						)
						->leftjoin('users', 'users.id', '=', 'cardioPerfilUserCia.id_user')
						->leftjoin('company', 'company.id', '=', 'cardioPerfilUserCia.id_company')
						->leftjoin('psicoEvaluacion', 'psicoEvaluacion.id', '=', 'cardioPerfilUserCia.id_encuesta')
						->where([['psicoEvaluacion.id_plataforma', 1], ['psicoEvaluacion.areaPsico',5]])
						->whereNotNull('cardioPerfilUserCia.riesgo')
						->whereYear('cardioPerfilUserCia.created_at', $anio)
						->get();
				}

				$sheet->row(1, [
					'Razón Social',
					'Trabajador',
					'Cargo',
					'Genero',
					'Edad',
					'Fecha de realización',
					'Próxima realización',
					'Riesgo',
					'Recomendación'
				]);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera
//dd($resultCia);
				foreach ($resultCia as $index => $Export) {

					if ($Export->riesgo == 1) {
						$riesgo = 'Riesgo Bajo';
					} elseif ($Export->riesgo == 2) {
						$riesgo = 'Riesgo Medio';
					} else {
						$riesgo = 'Riesgo Alto';
					}

					if ($Export->genero == 1) {
						$genero = 'Mujer';
					} elseif ($Export->genero == 2) {
						$genero = 'Hombre';
					}
					
					if (empty($Export->id_edad)) {
						$edad = 'No especificado';
					} elseif ($Export->id_edad == 1) {
						$edad = 'De 18 - 25 años';
					} elseif ($Export->id_edad == 2) {
						$edad = 'De 26 - 35 años';
					} elseif ($Export->id_edad == 3) {
						$edad = 'De 36 - 45 años';
					} elseif ($Export->id_edad == 4) {
						$edad = 'De 46 - 55 años';
					} else  {
						$edad = 'Mayor de 56 años';
					}
					


					if ($Export->valorObtenido == $Export->rangoL) {
						$recomendacion =  $Export->rangoLobserva;
					} elseif ($Export->rangoLm != '' || $Export->rangoLm != 0) {

						if ($Export->valorObtenido >= $Export->rangoL && $Export->valorObtenido <= $Export->rangoLm) {
							$recomendacion =  $Export->rangoLmobserva;
						} elseif ($Export->valorObtenido >= $Export->rangoLm && $Export->valorObtenido <= $Export->rangoM) {
							$recomendacion =  $Export->rangoMobserva;
						} elseif ($Export->valorObtenido >= $Export->rangoM && $Export->valorObtenido <= $Export->rangoMa) {
							$recomendacion =  $Export->rangoMaobserva;
						} elseif ($Export->valorObtenido >= $Export->rangoA) {
							$recomendacion = $Export->rangoAobserva;
						}
					}

					$sheet->row($index + 2, [
						$Export->razonsocial,
						$Export->name . ' ' . $Export->last_name,
						$Export->cargo,
						$genero,
						$edad,
						$Export->fechaProEva,
						$Export->created_at,
						$riesgo,
						$recomendacion
					]);
				}
			});
		})->export('xls');
	}

	public function planTEmpresasPeriodo(request $request)
	{
		$fechaIni = $request->fechaDesde;
		$fechaFin = $request->fechaHasta;
		Excel::create('Informe Anual Plan de Trabajo', function ($excel) use ($fechaIni, $fechaFin) {
			$excel->sheet('Informe General', function ($sheet) use ($fechaIni, $fechaFin) {
				$dataCompanys = DB::table('madurezSGSST as m')
					->whereBetween('p.fecha', [$fechaIni, $fechaFin])
					->join('planTrabajoPrincipal as p', 'm.id_company', '=', 'p.id_company')
					->groupBy('m.id_company')
					->get();

				$sheet->row(1, ['Mes ', 'Fecha', 'Nit', 'Razón social', 'Actividad Económica', 'Actividades ejecutadas', 'Actividades no ejecutadas']);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				$tipoFatura = [
					1  => 'Anual',
					2 => 'Mensual'
				];

				foreach ($dataCompanys as $index => $el) {
					$company = DB::table('company')->where('id', $el->id_company)->select('razonsocial', 'nit', 'activityName', 'activityNameSeg')->first();

					// Paso 1: Seleccionar los IDs de los registros más recientes para cada combinación de company_id, mes y item
					$ultimosRegistrosIds = DB::table('planTrabajoResultados as ptr1')
						->select(DB::raw('MAX(ptr1.id) as id'))
						->join('trabajoItem as ti', 'ptr1.item', '=', 'ti.id')
						->groupBy('ptr1.company_id', 'ptr1.mes', 'ptr1.item')
						->pluck('id');

					// Paso 2: Contar las actividades ejecutadas y no ejecutadas de los últimos registros para cada empresa y mes
					$resultados = DB::table('planTrabajoResultados as ptr2')
						->select(
							'ptr2.company_id',
							'ptr2.mes',
							DB::raw('SUM(CASE WHEN ptr2.ejecutadoNoejecutado = 1 THEN 1 ELSE 0 END) as total_actividades_ejecutadas'),
							DB::raw('SUM(CASE WHEN ptr2.ejecutadoNoejecutado = 2 THEN 1 ELSE 0 END) as total_actividades_no_ejecutadas')
						)
						->whereIn('ptr2.id', $ultimosRegistrosIds) // Usar solo los registros más recientes
						->where('ptr2.company_id', $el->id_company)
						->groupBy('ptr2.company_id', 'ptr2.mes')
						->orderBy('ptr2.company_id')
						->orderBy('ptr2.mes')
						->get();

					// Estructurar los datos para Highcharts
					$dataMeses = [];
					$ejecutadas = [];
					$noEjecutadas = [];

					foreach ($resultados as $resultadoMes) {
						$mes = $resultadoMes->mes;
						$dataMeses[$mes] = true; // Para asegurar que se cubren todos los meses
						$ejecutadas[$mes] = ($ejecutadas[$mes] ?? 0) + $resultadoMes->total_actividades_ejecutadas;
						$noEjecutadas[$mes] = ($noEjecutadas[$mes] ?? 0) + $resultadoMes->total_actividades_no_ejecutadas;
					}

					// Asegurarse de que haya datos para todos los meses del 1 al 14
					$meses = range(1, 14); // Generar un rango de meses del 1 al 14

					// Establecer valores de actividades ejecutadas y no ejecutadas
					$dataEjecutadas = array_map(function ($mes) use ($ejecutadas) {
						return isset($ejecutadas[$mes]) ? $ejecutadas[$mes] : 0; // Si no existe el mes, usar 0
					}, $meses);

					$dataNoEjecutadas = array_map(function ($mes) use ($noEjecutadas) {
						return isset($noEjecutadas[$mes]) ? $noEjecutadas[$mes] : 0; // Si no existe el mes, usar 0
					}, $meses);

					$sheet->row($index + 2, [
						$el->planMes,
						$el->fecha,
						$company->nit,
						$company->razonsocial,
						$company->activityName,
						$ejecutadas[$el->planMes],
						$noEjecutadas[$el->planMes],
					]);
				}
			});
		})->export('xls');
	}

	public function planTEmpresas(request $request)
	{
		$company = DB::table('company')->where('id', $request->empresa)->select('id', 'razonsocial', 'nit', 'activityName', 'activityNameSeg')->first();
		Excel::create('Informe Anual Plan de Trabajo', function ($excel) use ($company) {
			$excel->sheet("Informe $company->razonsocial", function ($sheet) use ($company) {
				$dataCompanys = DB::table('madurezSGSST as m')
					->where('m.id_company', $company->id)
					->join('planTrabajoPrincipal as p', 'm.id_company', '=', 'p.id_company')
					->groupBy('m.id_company')
					->get();

				$sheet->row(1, ['Mes ', 'Año', 'Nit', 'Razón social', 'Actividad Económica', 'Actividades ejecutadas', 'Actividades no ejecutadas']);
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				}); //ponemos color a la cabecera

				// Paso 1: Seleccionar los IDs de los registros más recientes para cada combinación de company_id, mes y item
				$ultimosRegistrosIds = DB::table('planTrabajoResultados as ptr1')
					->select(DB::raw('MAX(ptr1.id) as id'))
					->join('trabajoItem as ti', 'ptr1.item', '=', 'ti.id')
					->groupBy('ptr1.company_id', 'ptr1.mes', 'ptr1.item')
					->pluck('id');

				// Paso 2: Contar las actividades ejecutadas y no ejecutadas de los últimos registros para cada empresa y mes
				$resultados = DB::table('planTrabajoResultados as ptr2')
					->select(
						'ptr2.company_id',
						'ptr2.mes',
						DB::raw('SUM(CASE WHEN ptr2.ejecutadoNoejecutado = 1 THEN 1 ELSE 0 END) as total_actividades_ejecutadas'),
						DB::raw('SUM(CASE WHEN ptr2.ejecutadoNoejecutado = 2 THEN 1 ELSE 0 END) as total_actividades_no_ejecutadas')
					)
					->whereIn('ptr2.id', $ultimosRegistrosIds) // Usar solo los registros más recientes
					->where('ptr2.company_id', $company->id)
					->groupBy('ptr2.company_id', 'ptr2.mes')
					->orderBy('ptr2.company_id')
					->orderBy('ptr2.mes')
					->get();

				// Estructurar los datos para Highcharts
				$dataMeses = [];
				$ejecutadas = [];
				$noEjecutadas = [];

				foreach ($resultados as $resultadoMes) {
					$mes = $resultadoMes->mes;
					$dataMeses[$mes] = true; // Para asegurar que se cubren todos los meses
					$ejecutadas[$mes] = ($ejecutadas[$mes] ?? 0) + $resultadoMes->total_actividades_ejecutadas;
					$noEjecutadas[$mes] = ($noEjecutadas[$mes] ?? 0) + $resultadoMes->total_actividades_no_ejecutadas;
				}

				// Asegurarse de que haya datos para todos los meses del 1 al 14
				$meses = range(1, 14); // Generar un rango de meses del 1 al 14

				// Establecer valores de actividades ejecutadas y no ejecutadas
				$dataEjecutadas = array_map(function ($mes) use ($ejecutadas) {
					return isset($ejecutadas[$mes]) ? $ejecutadas[$mes] : 0; // Si no existe el mes, usar 0
				}, $meses);

				$dataNoEjecutadas = array_map(function ($mes) use ($noEjecutadas) {
					return isset($noEjecutadas[$mes]) ? $noEjecutadas[$mes] : 0; // Si no existe el mes, usar 0
				}, $meses);

				foreach ($dataCompanys as $index => $el) {
					$sheet->row($index + 2, [
						$el->planMes,
						$el->anio,
						$company->nit,
						$company->razonsocial,
						$company->activityName,
						$ejecutadas[$el->planMes],
						$noEjecutadas[$el->planMes],
					]);
				}
			});
		})->export('xls');
	}

	// Exportar usuarios edultech
	public function usersExportEdutech($company_id)
	{
		$fileName = 'Usuarios_para_registrar_en_Edutech';
		$sheetTitle = 'Usuarios_Edutech';

		Excel::create($fileName, function ($excel) use ($company_id, $sheetTitle) {
			$excel->sheet($sheetTitle, function ($sheet) use ($company_id) {
				// Consultar datos de la base de datos
				$usersWithModules = DB::table('company')
					->select(
						'company.id as id_company',
						'company.razonsocial',
						'company.nit',
						'users.id',
						'users.name',
						'users.last_name',
						'users.num_documento',
						'users.email',
						'users.cargo',
						'users.company_id',
						'users.role_id',
						'users.estado',
						'planesModulosClientes.id_plan',
						'planesModulosClientes.id_modulo'
					)
					->leftJoin('users', 'users.company_id', '=', 'company.id')
					->leftJoin('planesModulosClientes', 'planesModulosClientes.id_company', '=', 'company.id')
					->where([
						['company.id', $company_id],
						['users.estado', 1],
					])
					->get();

				// Agrupar usuarios y concatenar módulos
				$groupedUsers = [];
				foreach ($usersWithModules as $user) {
					$userId = $user->id;
					if (!isset($groupedUsers[$userId])) {
						$groupedUsers[$userId] = [
							'num_documento' => $user->num_documento,
							'name' => $user->name,
							'last_name' => $user->last_name,
							'email' => $user->email,
							'cargo' => $user->cargo,
							'razonsocial' => $user->razonsocial,
							'nit' => $user->nit,
							'id_plan' => $user->id_plan,
							'role_id' => $user->role_id,
							'modules' => [], // Almacenar los módulos asociados
						];
					}
					$groupedUsers[$userId]['modules'][] = $user->id_modulo;
				}

				// Configurar las cabeceras del archivo
				$sheet->row(1, [
					'username',
					'password',
					'firstname',
					'lastname',
					'email',
					'cohort1',
					'cohort2',
					'cohort3',
					'cohort4',
					'cohort5',
					'cohort6',
					'profile_field_cargo',
					'profile_field_nombre_empresa',
					'profile_field_nit_empresa',

				]);

				// Colorear la cabecera
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				});

				// Definir cohortes y agregar filas al archivo
				$moduleCohorts = [
					3 => 'PresentacionesSVEOsteomuscular',
					4 => 'PESV',
					6 => 'CapacitacionesSVEPsicosocial',
					7 => 'CapacitacionesSVECardiovascular',
				];

				$rowIndex = 2;
				foreach ($groupedUsers as $user) {
					// Inicializar cohortes
					$cohort1 = $cohort2 = $cohort3 = $cohort4 = $cohort5 = $cohort6 = '';
					$modules = $user['modules'];

					// Asignar cohortes para id_plan == 3
					if ($user['id_plan'] == 3) {
						$cohort1 = 'CapacitacionesSVEPsicosocial';
						$cohort2 = 'CapacitacionesUsuarios';
						$cohort3 = 'CapacitacionesLideres';
						$cohort4 = 'CapacitacionesSVECardiovascular';
						$cohort5 = 'PESV';
						$cohort6 = 'PresentacionesSVEOsteomuscular';
					} else {
						// Asignar cohortes según módulos
						foreach ($modules as $module) {
							if (isset($moduleCohorts[$module])) {
								switch ($module) {
									case 3:
										$cohort6 = $moduleCohorts[3];
										break;
									case 4:
										$cohort5 = $moduleCohorts[4];
										break;
									case 6:
										$cohort1 = $moduleCohorts[6];
										break;
									case 7:
										$cohort4 = $moduleCohorts[7];
										break;
								}
							}
						}

						// Asignar cohort2 para planes específicos
						if ($user['id_plan'] == 1) {
							$cohort2 = 'CapacitacionesUsuarios';
						}

						// Asignar cohort3 para roles específicos
						if (in_array($user['role_id'], [1, 2, 9])) {
							$cohort3 = 'CapacitacionesLideres';
						}
					}

					// Concatenar los módulos asociados
					$associatedModules = implode(', ', $modules);

					// Agregar fila al archivo
					$sheet->row($rowIndex++, [
						$user['num_documento'],
						$user['num_documento'],
						$user['name'],
						$user['last_name'],
						$user['email'],
						$cohort1,
						$cohort2,
						$cohort3,
						$cohort4,
						$cohort5,
						$cohort6,
						$user['cargo'],
						$user['razonsocial'],
						$user['nit'],

					]);
				}
			});
		})->export('xls');
	}

	// Exportar usuarios edultech
	public function usersExportEdutechAncla($company_id)
	{
		$fileName = 'Usuarios_para_registrar_en_Edutech_Ancla';
		$sheetTitle = 'Usuarios_Edutech_Ancla';

		Excel::create($fileName, function ($excel) use ($company_id, $sheetTitle) {
			$excel->sheet($sheetTitle, function ($sheet) use ($company_id) {
				// Consultar datos de la base de datos
				$usersWithModules = DB::table('company_ancla')
					->select(
						'company.id_company as id_company',
						'company.razonSocial',
						'company.nit',
						'users.id',
						'users.name',
						'users.last_name',
						'users.num_documento',
						'users.email',
						'users.cargo',
						'users.id_ciaAncla as company_id',
						'users.role_id',
						'users.estado'
					)
					->leftJoin('users', 'users.id_ciaAncla', '=', 'company_ancla.id_company')
					->leftJoin('planesModulosClientes', 'planesModulosClientes.id_company', '=', 'company_ancla.id_company')
					->where([
						['company_ancla.id_company', $company_id],
						['users.estado', 1],
					])
					->get();

				// Agrupar usuarios y concatenar módulos
				$groupedUsers = [];
				foreach ($usersWithModules as $user) {
					$userId = $user->id;
					if (!isset($groupedUsers[$userId])) {
						$groupedUsers[$userId] = [
							'num_documento' => $user->num_documento,
							'name' => $user->name,
							'last_name' => $user->last_name,
							'email' => $user->email,
							'cargo' => $user->cargo,
							'razonsocial' => $user->razonSocial,
							'nit' => $user->nit,
							'role_id' => $user->role_id,
							'modules' => [], // Almacenar los módulos asociados
						];
					}
				}

				// Configurar las cabeceras del archivo
				$sheet->row(1, [
					'username',
					'password',
					'firstname',
					'lastname',
					'email',
					'cohort1',
					'cohort2',
					'cohort3',
					'cohort4',
					'cohort5',
					'cohort6',
					'profile_field_cargo',
					'profile_field_nombre_empresa',
					'profile_field_nit_empresa',

				]);

				// Colorear la cabecera
				$sheet->row(1, function ($row) {
					$row->setBackground('#00A79D');
				});

				// Definir cohortes y agregar filas al archivo
				$moduleCohorts = [
					3 => 'PresentacionesSVEOsteomuscular',
					4 => 'PESV',
					6 => 'CapacitacionesSVEPsicosocial',
					7 => 'CapacitacionesSVECardiovascular',
				];

				$rowIndex = 2;
				foreach ($groupedUsers as $user) {
					// Inicializar cohortes
					$cohort1 = $cohort2 = $cohort3 = $cohort4 = $cohort5 = $cohort6 = '';
					$modules = $user['modules'];

					// Asignar cohortes para id_plan == 3
					if ($user['id_plan'] == 3) {
						$cohort1 = 'CapacitacionesSVEPsicosocial';
						$cohort2 = 'CapacitacionesUsuarios';
						$cohort3 = 'CapacitacionesLideres';
						$cohort4 = 'CapacitacionesSVECardiovascular';
						$cohort5 = 'PESV';
						$cohort6 = 'PresentacionesSVEOsteomuscular';
					} else {
						// Asignar cohortes según módulos
						foreach ($modules as $module) {
							if (isset($moduleCohorts[$module])) {
								switch ($module) {
									case 3:
										$cohort6 = $moduleCohorts[3];
										break;
									case 4:
										$cohort5 = $moduleCohorts[4];
										break;
									case 6:
										$cohort1 = $moduleCohorts[6];
										break;
									case 7:
										$cohort4 = $moduleCohorts[7];
										break;
								}
							}
						}

						// Asignar cohort3 para roles específicos
						if (in_array($user['role_id'], [1, 2, 9])) {
							$cohort3 = 'CapacitacionesLideres';
						}
					}

					// Concatenar los módulos asociados
					$associatedModules = implode(', ', $modules);

					// Agregar fila al archivo
					$sheet->row($rowIndex++, [
						$user['num_documento'],
						$user['num_documento'],
						$user['name'],
						$user['last_name'],
						$user['email'],
						$cohort1,
						$cohort2,
						$cohort3,
						$cohort4,
						$cohort5,
						$cohort6,
						$user['cargo'],
						$user['razonsocial'],
						$user['nit'],

					]);
				}
			});
		})->export('xls');
	}
    public function exportMulti(Request $request)
		{
			$data = $request->json()->all();

			

			Excel::create('Reporte_Multisheet', function($excel) use ($data) {

				/** -----------------------
				 *  Hoja 1: Tipo documento
				 * ----------------------- */
				$excel->sheet('Tipo de documento', function($sheet) use ($data) {

					$sheet->row(1, [
						'Tipo de documento',
						'Porcentaje',
						'Numero de personas'
					]);

					foreach ($data["Tipo de documento"] as $i => $item) {
						$sheet->row($i + 2, [
							$item["Tipo de documento"],
							$item["Porcentaje"],
							$item["Numero de personajes"]
						]);
					}
				});

				/** -----------------------
				 *  Hoja 2: Edad
				 * ----------------------- */
				$excel->sheet('Edad', function($sheet) use ($data) {

					$sheet->row(1, [
						'Edad',
						'Porcentaje',
						'Numero de personas'
					]);

					foreach ($data["Edad"] as $i => $item) {
						$sheet->row($i + 2, [
							$item["Edad"],
							$item["Porcentaje"],
							$item["Numero de personas"]
						]);
					}
				});

				/** -----------------------
				 *  Hoja 3: Género
				 * ----------------------- */
				$excel->sheet('Genero', function($sheet) use ($data) {

					$sheet->row(1, [
						'Genero',
						'Porcentaje',
						'Numero de personas'
					]);

					foreach ($data["Genero"] as $i => $item) {
						$sheet->row($i + 2, [
							$item["Genero"],
							$item["Porcentaje"],
							$item["Numero de personas"]
						]);
					}
				});
				/** -----------------------
				 *  Hoja 4: Escolaridad
				 * ----------------------- */
				$excel->sheet('Escolaridad', function($sheet) use ($data) {

					$sheet->row(1, [
						'Escolaridad',
						'Porcentaje',
						'Numero de personas'
					]);

					foreach ($data["Escolaridad"] as $i => $item) {
						$sheet->row($i + 2, [
							$item["Escolaridad"],
							$item["Porcentaje"],
							$item["Numero de personas"]
						]);
					}
				});
				/** -----------------------
				 *  Hoja 5: Estado civil
				 * ----------------------- */
				$excel->sheet('Estado civil', function($sheet) use ($data) {

					$sheet->row(1, [
						'Estado civil',
						'Porcentaje',
						'Numero de personas'
					]);

					foreach ($data["Estado civil"] as $i => $item) {
						$sheet->row($i + 2, [
							$item["Estado civil"],
							$item["Porcentaje"],
							$item["Numero de personas"]
						]);
					}
				});
				/** -----------------------
				 *  Hoja 6: Categoria licencia de auto
				 * ----------------------- */
				$excel->sheet('Categoria licencia de auto', function($sheet) use ($data) {

					$sheet->row(1, [
						'Categoria licencia de auto',
						'Porcentaje',
						'Numero de personas'
					]);

					foreach ($data["Categoria licencia auto"] as $i => $item) {
						$sheet->row($i + 2, [
							$item["Categoria de licencia"],
							$item["Porcentaje"],
							$item["Numero de personas"]
						]);
					}
				});
				/** -----------------------
				 *  Hoja 7: Categoria licencia de moto
				 * ----------------------- */
				$excel->sheet('Categoria licencia de moto', function($sheet) use ($data) {

					$sheet->row(1, [
						'Categoria licencia de moto',
						'Porcentaje',
						'Numero de personas'
					]);

					foreach ($data["Categoria licencia moto"] as $i => $item) {
						$sheet->row($i + 2, [
							$item["Categoria de licencia"],
							$item["Porcentaje"],
							$item["Numero de personas"]
						]);
					}
				});
				
				/** -----------------------
				 *  Hoja 8: Capacitacion
				 * ----------------------- */
				$excel->sheet('Capacitacion', function($sheet) use ($data) {

					$sheet->row(1, [
						'Capacitación',
						'Porcentaje',
						'Numero de personas'
					]);

					foreach ($data["Capacitacion"] as $i => $item) {
						$sheet->row($i + 2, [
							$item["Capacitación"],
							$item["Porcentaje"],
							$item["Numero de personas"]
						]);
					}
				});
				/** -----------------------
				 *  Hoja 9: Siniestros
				 * ----------------------- */
				$excel->sheet('Siniestros', function($sheet) use ($data) {

					$sheet->row(1, [
						'Siniestros',
						'Porcentaje',
						'Numero de personas'
					]);

					foreach ($data["Siniestros"] as $i => $item) {
						$sheet->row($i + 2, [
							$item["Siniestros"],
							$item["Porcentaje"],
							$item["Numero de personas"]
						]);
					}
				});
				/** -----------------------
				 *  Hoja 10: Rol accidente
				 * ----------------------- */
				$excel->sheet('Rol accidente', function($sheet) use ($data) {

					$sheet->row(1, [
						'Rol accidente',
						'Porcentaje',
						'Numero de personas'
					]);

					foreach ($data["Rol accidente"] as $i => $item) {
						$sheet->row($i + 2, [
							$item["Rol accidente"],
							$item["Porcentaje"],
							$item["Numero de personas"]
						]);
					}
				});
				/** -----------------------
				 *  Hoja 11: Cantidad infracciones
				 * ----------------------- */
				$excel->sheet('Cantidad de infracciones', function($sheet) use ($data) {

					$sheet->row(1, [
						'Cantidad de infracciones',
						'Porcentaje',
						'Numero de personas'
					]);

					foreach ($data["Cantidad infracciones"] as $i => $item) {
						$sheet->row($i + 2, [
							$item["Cantidad infracciones"],
							$item["Porcentaje"],
							$item["Numero de personas"]
						]);
					}
				});
				/** -----------------------
				 *  Hoja 12: Tipo infracciones
				 * ----------------------- */
				$excel->sheet('Tipo de infracciones', function($sheet) use ($data) {

					$sheet->row(1, [
						'Tipo de infracciones',
						'Porcentaje',
						'Numero de personas'
					]);

					foreach ($data["Tipo infracciones"] as $i => $item) {
						$sheet->row($i + 2, [
							$item["Tipo de infracción"],
							$item["Porcentaje"],
							$item["Numero de personas"]
						]);
					}
				});
				/** -----------------------
				 *  Hoja 13: Estado pago
				 * ----------------------- */
				$excel->sheet('Estado de pago', function($sheet) use ($data) {

					$sheet->row(1, [
						'Estado de pago',
						'Porcentaje',
						'Numero de personas'
					]);

					foreach ($data["Estado pago"] as $i => $item) {
						$sheet->row($i + 2, [
							$item["Estado de pago"],
							$item["Porcentaje"],
							$item["Numero de personas"]
						]);
					}
				});
				/** -----------------------
                 * Hoja 14: Medio transporte trabajo (Hoja Problemática)
                 * Sanitización Extrema aplicada.
                 * ----------------------- */
                if (isset($data["Medio transporte trabajo"])) {
                    // Nombre de hoja sin tildes, eñes o espacios por seguridad máxima.
                    $excel->sheet('Transporte desplazamiento', function($sheet) use ($data) {

                        $sheet->row(1, [
                            'Medio Transporte', 
                            'Porcentaje',
                            'Numero Personas'
                        ]);

                        foreach ($data["Medio transporte trabajo"] as $i => $item) {
                            // Uso de operador ?? para que si la clave no existe, use un valor seguro ('')
                            $sheet->row($i + 2, [
                                (string)($item["Medio de transporte para ir al trabajo"] ?? 'N/A'), 
                                (float)($item["Porcentaje"] ?? 0.0),
                                (int)($item["Numero de personas"] ?? 0)
                            ]);
                        }
                    });
                }

				/** -----------------------
				 *  Hoja 15: Conductor laboral
				 * ----------------------- */
				$excel->sheet('Conductor laboral', function($sheet) use ($data) {

					$sheet->row(1, [
						'Conductor laboral',
						'Porcentaje',
						'Numero de personas'
					]);

					foreach ($data["Conductor laboral"] as $i => $item) {
						$sheet->row($i + 2, [
							$item["Conductor laboral"],
							$item["Porcentaje"],
							$item["Numero de personas"]
						]);
					}
				});
				/** -----------------------
				 *  Hoja 16: Tipo vehiculo laboral
				 * ----------------------- */
				$excel->sheet('Tipo de vehiculo laboral', function($sheet) use ($data) {

					$sheet->row(1, [
						'Tipo de vehiculo laboral',
						'Porcentaje',
						'Numero de personas'
					]);

					foreach ($data["Tipo vehiculo laboral"] as $i => $item) {
						$sheet->row($i + 2, [
							$item["Tipo de vehiculo laboral"],
							$item["Porcentaje"],
							$item["Numero de personas"]
						]);
					}
				});    


				

			})->export('xls');
		}






}
