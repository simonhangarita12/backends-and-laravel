<?php

namespace App\Http\Controllers\RequisitosLegales;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\RequisitosLegales\TipoPeligro;
use App\Models\RequisitosLegales\Resultado;
use App\Models\RequisitosLegales\PlanAccion;
use App\Models\RequisitosLegales\RequisitoLegal;
use App\Models\RequisitosLegales\RequisitoCriterio;
use App\Models\RequisitosLegales\PlanAdjunto;
use App\Models\RequisitosLegales\tbl_actualizacion;
use App\Models\RequisitosLegales\tbl_int_actualizacion;
use App\Models\RequisitosLegales\tbl_int_norma;
use App\Models\RequisitosLegales\tbl_int_criterio;
use App\Models\RequisitosLegales\tbl_inFormularioML;
use App\Models\RequisitosLegales\tbl_HistoinFormularioML;
use App\Models\RequisitosLegales\tbl_PlanAbo_ML;
use App\Models\RequisitosLegales\tbl_subirDocML;
use App\Models\RequisitosLegales\Criterio;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\User;
use Excel;
use Sentinel;
use Validator;
use Carbon\Carbon;
use File;

class RequisitosLegalesController extends Controller
{


  public function modulosRequi($id_empresa)
  {

    $company = DB::table('company')
      ->select('company.razonsocial', 'cat_riesgos.name')
      ->leftjoin('cat_riesgos', 'cat_riesgos.id', '=', 'company.cat_riesgos')
      ->where('company.id', $id_empresa)
      ->get();

    foreach ($company as  $value) {
      $nombre = $value->razonsocial;
      $cat_riesgos = $value->name;
    }


    return view('RequisitosLegales/modulosRequi', compact('id_empresa', 'nombre', 'cat_riesgos'));
  }

  public function modulosRequiAbog($id_empresa)
  {

    $company = DB::table('company')
      ->select('razonsocial')
      ->where('id', $id_empresa)
      ->get();
    foreach ($company as  $value) {
      $nombre = $value->razonsocial;
    }


    return view('RequisitosLegales/modulosRequiAbog', compact('id_empresa', 'nombre'));
  }

  public function graficas($id_empresa, $anio)
  {

    //modulo 1
    $valor_maximo_1 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 1], ['tbl_criterio_cumplimiento.id_modulo', 1], ['tbl_int_criterio.estado', 1]])
      ->get();

    foreach ($valor_maximo_1 as $key) {
      $valor_maximo_11 = $key->valor_maximo;
    }

    $valor_obtenido_1 = DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 1]])
      ->get();
    foreach ($valor_obtenido_1 as $key) {
      $valor_obtenido_11 = $key->valor_obtenido;
    }

    $criterios_cumplidos_1 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 1]])
      ->get();
    $criterios_cumplidos_11 = count($criterios_cumplidos_1);

    $criterios_cumplimiento_1 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 1]])
      ->get();
    $criterios_cumplimiento_11 = count($criterios_cumplimiento_1);

    //modulo 2

    $valor_maximo_2 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 2], ['tbl_criterio_cumplimiento.id_modulo', 2], ['tbl_int_criterio.estado', 1]])
      ->get();

    foreach ($valor_maximo_2 as $key) {
      $valor_maximo_22 = $key->valor_maximo;
    }

    $valor_obtenido_2 = DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 2]])
      ->get();
    foreach ($valor_obtenido_2 as $key) {
      $valor_obtenido_22 = $key->valor_obtenido;
    }

    $criterios_cumplidos_2 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 2]])
      ->get();
    $criterios_cumplidos_22 = count($criterios_cumplidos_2);

    $criterios_cumplimiento_2 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 2]])
      ->get();
    $criterios_cumplimiento_22 = count($criterios_cumplimiento_2);


    //modulo 3
    $valor_maximo_3 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 3], ['tbl_criterio_cumplimiento.id_modulo', 3], ['tbl_int_criterio.estado', 1]])
      ->get();
    foreach ($valor_maximo_3 as $key) {
      $valor_maximo_33 = $key->valor_maximo;
    }

    $valor_obtenido_3 = DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 3]])
      ->get();
    foreach ($valor_obtenido_3 as $key) {
      $valor_obtenido_33 = $key->valor_obtenido;
    }

    $criterios_cumplidos_3 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 3]])
      ->get();
    $criterios_cumplidos_33 = count($criterios_cumplidos_3);

    $criterios_cumplimiento_3 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 2]])
      ->get();
    $criterios_cumplimiento_33 = count($criterios_cumplimiento_3);




    //modulo 4
    $valor_maximo_4 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 4], ['tbl_criterio_cumplimiento.id_modulo', 4], ['tbl_int_criterio.estado', 1]])
      ->get();
    foreach ($valor_maximo_4 as $key) {
      $valor_maximo_44 = $key->valor_maximo;
    }

    $valor_obtenido_4 = DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 4]])
      ->get();
    foreach ($valor_obtenido_4 as $key) {
      $valor_obtenido_44 = $key->valor_obtenido;
    }

    $criterios_cumplidos_4 =  DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 4]])
      ->get();
    $criterios_cumplidos_44 = count($criterios_cumplidos_4);

    $criterios_cumplimiento_4 =  DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 4]])
      ->get();
    $criterios_cumplimiento_44 = count($criterios_cumplimiento_4);


    //modulo 5
    $valor_maximo_5 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 5], ['tbl_criterio_cumplimiento.id_modulo', 5], ['tbl_int_criterio.estado', 1]])
      ->get();

    foreach ($valor_maximo_5 as $key) {
      $valor_maximo_55 = $key->valor_maximo;
    }

    $valor_obtenido_5 = DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 5]])
      ->get();


    foreach ($valor_obtenido_5 as $key) {
      $valor_obtenido_55 = $key->valor_obtenido;
    }

    $criterios_cumplidos_5 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 5]])
      ->get();

    $criterios_cumplidos_55 = count($criterios_cumplidos_5);

    $criterios_cumplimiento_5 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 5]])
      ->get();

    $criterios_cumplimiento_55 = count($criterios_cumplimiento_5);


    //modulo 
    $valor_maximo_6 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 6], ['tbl_criterio_cumplimiento.id_modulo', 6], ['tbl_int_criterio.estado', 1]])
      ->get();
    foreach ($valor_maximo_6 as $key) {
      $valor_maximo_66 = $key->valor_maximo;
    }

    $valor_obtenido_6 =  DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 6]])
      ->get();

    foreach ($valor_obtenido_6 as $key) {
      $valor_obtenido_66 = $key->valor_obtenido;
    }

    $criterios_cumplidos_6 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 6]])
      ->get();
    $criterios_cumplidos_6 = count($criterios_cumplidos_6);

    $criterios_cumplimiento_6 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 6]])
      ->get();
    $criterios_cumplimiento_66 = count($criterios_cumplimiento_6);


    //modulo 7
    $valor_maximo_7 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 7], ['tbl_criterio_cumplimiento.id_modulo', 7], ['tbl_int_criterio.estado', 1]])
      ->get();
    foreach ($valor_maximo_7 as $key) {
      $valor_maximo_77 = $key->valor_maximo;
    }

    $valor_obtenido_7 =  DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 7]])
      ->get();
    foreach ($valor_obtenido_7 as $key) {
      $valor_obtenido_77 = $key->valor_obtenido;
    }

    $criterios_cumplidos_7 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 7]])
      ->get();
    $criterios_cumplidos_7 = count($criterios_cumplidos_7);

    $criterios_cumplimiento_7 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 7]])
      ->get();
    $criterios_cumplimiento_77 = count($criterios_cumplimiento_7);


    //modulo 8
    $valor_maximo_8 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 8], ['tbl_criterio_cumplimiento.id_modulo', 8], ['tbl_int_criterio.estado', 1]])
      ->get();
    foreach ($valor_maximo_8 as $key) {
      $valor_maximo_88 = $key->valor_maximo;
    }

    $valor_obtenido_8 =  DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 8]])
      ->get();
    foreach ($valor_obtenido_8 as $key) {
      $valor_obtenido_88 = $key->valor_obtenido;
    }

    $criterios_cumplidos_8 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 8]])
      ->get();
    $criterios_cumplidos_88 = count($criterios_cumplidos_8);

    $criterios_cumplimiento_8 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 8]])
      ->get();
    $criterios_cumplimiento_88 = count($criterios_cumplimiento_8);



    //modulo 9
    $valor_maximo_9 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 9], ['tbl_criterio_cumplimiento.id_modulo', 9], ['tbl_int_criterio.estado', 1]])
      ->get();
    foreach ($valor_maximo_9 as $key) {
      $valor_maximo_99 = $key->valor_maximo;
    }

    $valor_obtenido_9 =  DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 9]])
      ->get();
    foreach ($valor_obtenido_9 as $key) {
      $valor_obtenido_99 = $key->valor_obtenido;
    }

    $criterios_cumplidos_9 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 9]])
      ->get();
    $criterios_cumplidos_99 = count($criterios_cumplidos_9);

    $criterios_cumplimiento_9 =  DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 9]])
      ->get();
    $criterios_cumplimiento_99 = count($criterios_cumplimiento_9);


    //modulo 10
    $valor_maximo_10 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 10], ['tbl_criterio_cumplimiento.id_modulo', 10], ['tbl_int_criterio.estado', 1]])
      ->get();
    foreach ($valor_maximo_10 as $key) {
      $valor_maximo_1010 = $key->valor_maximo;
    }

    $valor_obtenido_10 = DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 10]])
      ->get();
    foreach ($valor_obtenido_10 as $key) {
      $valor_obtenido_1010 = $key->valor_obtenido;
    }

    $criterios_cumplidos_10 =  DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 10]])
      ->get();
    $criterios_cumplidos_1010 = count($criterios_cumplidos_10);

    $criterios_cumplimiento_10 =  DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 10]])
      ->get();
    $criterios_cumplimiento_1010 = count($criterios_cumplimiento_10);






    //modulo 11
    $valor_maximo_111 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 11], ['tbl_criterio_cumplimiento.id_modulo', 11], ['tbl_int_criterio.estado', 1]])
      ->get();
    foreach ($valor_maximo_111 as $key) {
      $valor_maximo_1111 = $key->valor_maximo;
    }

    $valor_obtenido_111 = DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 11]])
      ->get();
    foreach ($valor_obtenido_111 as $key) {
      $valor_obtenido_1111 = $key->valor_obtenido;
    }

    $criterios_cumplidos_111 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 11]])
      ->get();
    $criterios_cumplidos_1111 = count($criterios_cumplidos_111);

    $criterios_cumplimiento_111 =  DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 11]])
      ->get();
    $criterios_cumplimiento_1111 = count($criterios_cumplimiento_111);










    //modulo 11
    $porcentaje_cumplimiento_11 = 0;

    if ($criterios_cumplimiento_1111 != null) {
      $porcentaje_cumplimiento_11 = round((($criterios_cumplidos_1111 * 100) / $criterios_cumplimiento_1111));
    }

    $total_valor_maximo11 = ($valor_maximo_1111);
    $total_valor_obtenido11 = ($valor_obtenido_1111);
    $total_porcentaje11 = 0;
    if ($total_valor_maximo11 != 0) {
      $total_porcentaje11 = round((($total_valor_obtenido11 * 100) / $total_valor_maximo11));
    }




    //modulo 10
    $porcentaje_cumplimiento_10 = 0;

    if ($criterios_cumplimiento_1010 != null) {
      $porcentaje_cumplimiento_10 = round((($criterios_cumplidos_1010 * 100) / $criterios_cumplimiento_1010));
    }

    $total_valor_maximo10 = ($valor_maximo_1010);
    $total_valor_obtenido10 = ($valor_obtenido_1010);
    $total_porcentaje10 = 0;
    if ($total_valor_maximo10 != 0) {
      $total_porcentaje10 = round((($total_valor_obtenido10 * 100) / $total_valor_maximo10));
    }




    //modulo 9
    $porcentaje_cumplimiento_9 = 0;

    if ($criterios_cumplimiento_99 != null) {
      $porcentaje_cumplimiento_9 = round((($criterios_cumplidos_99 * 100) / $criterios_cumplimiento_99));
    }

    $total_valor_maximo9 = ($valor_maximo_99);
    $total_valor_obtenido9 = ($valor_obtenido_99);
    $total_porcentaje9 = 0;
    if ($total_valor_maximo9 != 0) {
      $total_porcentaje9 = round((($total_valor_obtenido9 * 100) / $total_valor_maximo9));
    }


    //modulo 8
    $porcentaje_cumplimiento_8 = 0;

    if ($criterios_cumplimiento_88 != null) {
      $porcentaje_cumplimiento_8 = round((($criterios_cumplidos_88 * 100) / $criterios_cumplimiento_88));
    }

    $total_valor_maximo8 = ($valor_maximo_88);
    $total_valor_obtenido8 = ($valor_obtenido_88);
    $total_porcentaje8 = 0;
    if ($total_valor_maximo8 != 0) {
      $total_porcentaje8 = round((($total_valor_obtenido8 * 100) / $total_valor_maximo8));
    }

    //modulo 7
    /*   $porcentaje_cumplimiento_7=0;

    if ($criterios_cumplimiento_77!=null) {
      $porcentaje_cumplimiento_7 = round((($criterios_cumplidos_77*100)/$criterios_cumplimiento_77));
    }

$total_valor_maximo7 = ($valor_maximo_77);
$total_valor_obtenido7 = ($valor_obtenido_77);
$total_porcentaje7=0;
if($total_valor_maximo7 != 0){
    $total_porcentaje7 = round((($total_valor_obtenido7*100)/$total_valor_maximo7));
}*/


    //modulo 6
    $porcentaje_cumplimiento_6 = 0;

    if ($criterios_cumplimiento_66 != null) {
      $porcentaje_cumplimiento_6 = round((($criterios_cumplidos_66 * 100) / $criterios_cumplimiento_66));
    }

    $total_valor_maximo6 = ($valor_maximo_66);
    $total_valor_obtenido6 = ($valor_obtenido_66);
    $total_porcentaje6 = 0;
    if ($total_valor_maximo6 != 0) {
      $total_porcentaje6 = round((($total_valor_obtenido6 * 100) / $total_valor_maximo6));
    }






    //modulo 5
    $porcentaje_cumplimiento_5 = 0;

    if ($criterios_cumplimiento_55 != null) {
      $porcentaje_cumplimiento_5 = round((($criterios_cumplidos_55 * 100) / $criterios_cumplimiento_55));
    }

    $total_valor_maximo5 = ($valor_maximo_55);
    $total_valor_obtenido5 = ($valor_obtenido_55);
    $total_porcentaje5 = 0;
    if ($total_valor_maximo5 != 0) {
      $total_porcentaje5 = round((($total_valor_obtenido5 * 100) / $total_valor_maximo5));
    }

    // dd($total_porcentaje5);
    //modulo 4
    $porcentaje_cumplimiento_4 = 0;

    if ($criterios_cumplimiento_44 != null) {
      $porcentaje_cumplimiento_4 = round((($criterios_cumplidos_44 * 100) / $criterios_cumplimiento_44));
    }

    $total_valor_maximo4 = ($valor_maximo_44);
    $total_valor_obtenido4 = ($valor_obtenido_44);
    $total_porcentaje4 = 0;
    if ($total_valor_maximo4 != 0) {
      $total_porcentaje4 = round((($total_valor_obtenido4 * 100) / $total_valor_maximo4));
    }





    //modulo 3
    $porcentaje_cumplimiento_3 = 0;

    if ($criterios_cumplimiento_33 != null) {
      $porcentaje_cumplimiento_3 = round((($criterios_cumplidos_33 * 100) / $criterios_cumplimiento_33));
    }

    $total_valor_maximo3 = ($valor_maximo_33);
    $total_valor_obtenido3 = ($valor_obtenido_33);
    $total_porcentaje3 = 0;
    if ($total_valor_maximo3 != 0) {
      $total_porcentaje3 = round((($total_valor_obtenido3 * 100) / $total_valor_maximo3));
    }

    //modulo 1

    /* $porcentaje_cumplimiento_1=0;

    if ($criterios_cumplimiento_11!=null) {
      $porcentaje_cumplimiento_1 = round((($criterios_cumplidos_11*100)/$criterios_cumplimiento_11));
    }

$total_valor_maximo1 = ($valor_maximo_11);
$total_valor_obtenido1 = ($valor_obtenido_11);
$total_porcentaje1=0;
if($total_valor_maximo1 != 0){
    $total_porcentaje1 = round((($total_valor_obtenido1*100)/$total_valor_maximo1));
}*/

    //modulo 2

    $porcentaje_cumplimiento_2 = 0;

    if ($criterios_cumplimiento_22 != null) {
      $porcentaje_cumplimiento_2 = round((($criterios_cumplidos_22 * 100) / $criterios_cumplimiento_22));
    }

    $total_valor_maximo2 = ($valor_maximo_22);
    $total_valor_obtenido2 = ($valor_obtenido_22);
    $total_porcentaje2 = 0;
    if ($total_valor_maximo2 != 0) {
      $total_porcentaje2 = round((($total_valor_obtenido2 * 100) / $total_valor_maximo2));
    }

    $TotalObtenido = $valor_obtenido_55 + $valor_obtenido_44 + $valor_obtenido_1010 + $valor_obtenido_88 + $valor_obtenido_1111 + $valor_obtenido_22 + $valor_obtenido_33;
    $totalObtenido = $TotalObtenido + 0;


    $TotalMaximo = $valor_maximo_55 + $valor_maximo_44 + $valor_maximo_1010 + $valor_maximo_88 + $valor_maximo_1111 + $valor_maximo_22 + $valor_maximo_33;
    $totalMaximo = $TotalMaximo + 0;

    $Modulo5Obtenido = ([
      $valor_obtenido_55 + 0,
      $valor_obtenido_44 + 0,
      $valor_obtenido_1010 + 0,
      $valor_obtenido_88 + 0,
      $valor_obtenido_1111 + 0,
      $valor_obtenido_22 + 0,
      $valor_obtenido_33 + 0,
    ]);

    // dd($Modulo5Obtenido);

    $modulo5Maximo = ([
      $valor_maximo_55 + 0,
      $valor_maximo_44 + 0,
      $valor_maximo_1010 + 0,
      $valor_maximo_88 + 0,
      $valor_maximo_1111 + 0,
      $valor_maximo_22 + 0,
      $valor_maximo_33 + 0,
    ]);

    $TotalObtenidov2 = ([
      $totalObtenido + 0
    ]);
    $TotalMaximov2 = ([
      $totalMaximo + 0
    ]);


    $planCu = DB::table('tbl_PlanAbo_ML')
      ->select('id_planAbo')
      ->where([['id_empresa', $id_empresa], ['seguimiento', 1]])
      ->get();

    $planPr = DB::table('tbl_PlanAbo_ML')
      ->select('id_planAbo')
      ->where([['id_empresa', $id_empresa], ['seguimiento', 2]])
      ->get();

    $planDe = DB::table('tbl_PlanAbo_ML')
      ->select('id_planAbo')
      ->where([['id_empresa', $id_empresa], ['seguimiento', 3]])
      ->get();

    $planNo = DB::table('tbl_PlanAbo_ML')
      ->select('id_planAbo')
      ->where([['id_empresa', $id_empresa], ['seguimiento', 4]])
      ->get();

    $planAccion = ([
      $planCu->count(),
      $planPr->count(),
      $planDe->count(),
      $planNo->count()
    ]);



    $dataUser = array('Modulo5Obtenido' => $Modulo5Obtenido, 'modulo5Maximo' => $modulo5Maximo, 'TotalObtenidov2' => $TotalObtenidov2, 'TotalMaximov2' => $TotalMaximov2, 'planAccion' => $planAccion);
    return $dataUser;
  }




  public function requisitos($id_empresa, $modulo)
  {
    $requisitos = DB::table('tbl_requisitos_legales')
      ->leftjoin('tbl_int_norma', 'tbl_int_norma.id_norma', '=', 'tbl_requisitos_legales.id')
      ->leftjoin('tbl_int_legales', 'tbl_int_legales.id_norma', '=', 'tbl_int_norma.id_norma')
      ->where([['tbl_requisitos_legales.estado', '=', 1], ['tbl_int_norma.id_empresa', $id_empresa], ['tbl_int_legales.id_modLegal', $modulo]])
      ->get();

    // dd($requisitos);
    //     if (isset($requisitos)){
    // $mensaje='NO TIENE MATRIZ LEGAL ASIGNADA';
    // }else{
    //     $mensaje='';
    // }
    $mensaje = '';
    $company = DB::table('company')
      ->select('razonsocial')
      ->where('id', $id_empresa)
      ->get();
    foreach ($company as  $value) {
      $nombre = $value->razonsocial;
    }

    $rolUser = Sentinel::getUser()->role_id;
    if ($rolUser == 2 || $rolUser == 6 || $rolUser == 9 || $rolUser == 10 || $rolUser == 11) {

      return view('RequisitosLegales/requisitosLegales', compact('mensaje', 'nombre', 'id_empresa', 'tipo', 'modulo'));
    } elseif ($rolUser == 1 || $rolUser == 13 || $rolUser == 15) {

      return view('RequisitosLegales.Modulos.listMedPreventiva2', compact('nombre', 'id_empresa', 'modulo'));
      // return redirect()->action('RequisitosLegales\RequisitoController@lisrequi',[$id_empresa,$modulo]);
    }





    // return view('RequisitosLegales/requisitosLegales',compact('id_empresa','tipo'));
  }
  public function requisitos2($id_empresa, $modulo)
  {

    $company = DB::table('company')
      ->select('razonsocial')
      ->where('id', $id_empresa)
      ->get();
    foreach ($company as  $value) {
      $nombre = $value->razonsocial;
    }
    $requisitos = DB::table('tbl_requisitos_legales')
      ->leftjoin('tbl_int_norma', 'tbl_int_norma.id_norma', '=', 'tbl_requisitos_legales.id')
      ->leftjoin('tbl_int_legales', 'tbl_int_legales.id_norma', '=', 'tbl_int_norma.id_norma')
      ->where([['tbl_requisitos_legales.estado', '=', 1], ['tbl_int_norma.id_empresa', $id_empresa], ['tbl_int_legales.id_modLegal', $modulo]])
      ->get();

    // dd($requisitos);
    //     if (empty($requisitos)){
    //    $mensaje='NO TIENE MATRIZ LEGAL ASIGNADA';
    // }else {
    //  $mensaje='';
    // }
    $mensaje = '';
    // dd($mensaje);

    return view('RequisitosLegales/requisitosLegales', compact('requisitos', 'mensaje', 'nombre', 'id_empresa', 'modulo'));
  }
  public function requisitosAbo($id_empresa, $modulo)
  {

    return redirect()->action('RequisitosLegales\RequisitoController@lisrequi', [$id_empresa, $modulo]);
  }

  public function requisitosAbo1($id_empresa, $modulo)
  {

    return redirect()->action('RequisitosLegales\RequisitoController@lisrequiAbo', [$id_empresa, $modulo]);
  }



  public function createRequi(Request $request)
  {


    $id_empresa = $request->id_empresa;

    $creaRequi = new RequisitoLegal;
    $creaRequi->create([
      'id_tipo_peligro' => $request->id_tipo_peligro,
      'id_empresa' => $id_empresa,
      'tipo_norma' => $request->tipo_norma,
      'emisor' => $request->emisor,
      'descripcion_norma' => $request->descripcion_norma,
      'fecha_emision' => $request->fecha_emision,
      'articulos_aplicables' => $request->articulos_aplicables,
      'subclasificacion' => $request->subclasificacion,
      'descripcion_requisito' => $request->descripcion_requisito,
      'estado' => 1

    ]);




    $requisi = \DB::table('tbl_requisitos_legales')
      ->select('id')
      ->get();

    foreach ($requisi as $value) {
      $id_req = $value->id;
      # code...
    }
    // dd($id_req);

    return view('RequisitosLegales/newCriterio', compact('id_req', 'id_empresa'));
  }




  public function archivosPlan($id)
  {
    $archivo = DB::table('tbl_plan_adjuntos')
      ->select('archivo')
      ->where('id_plan_accion', $id)
      ->get();

    return json_encode($archivo);
  }


  public function listMedPreventiva($id_empresa, $modulo)

  {

    $rolUser = Sentinel::getUser()->role_id;
    $company = DB::table('company')
      ->select('razonsocial')
      ->where('id', $id_empresa)
      ->get();
    foreach ($company as  $value) {
      $nombre = $value->razonsocial;
    }

    if ($rolUser == 2 || $rolUser == 6 || $rolUser == 9) {

      /*$requisitos = DB::table('tbl_requisitos_legales')
    ->leftjoin('tbl_int_norma','tbl_int_norma.id_norma','=','tbl_requisitos_legales.id')
    ->leftjoin('tbl_int_legales','tbl_int_legales.id_norma','=','tbl_int_norma.id_norma')
    ->where([['tbl_requisitos_legales.estado','=',1],['tbl_int_legales.id_modLegal',$modulo],['tbl_int_norma.id_empresa',$id_empresa]])
    ->get();*/
      $requisitos = DB::table('tbl_requisitos_legales')
        ->select('tbl_requisitos_legales.id', 'tbl_tipo_peligro.peligro', 'tbl_requisitos_legales.tipo_norma', 'tbl_requisitos_legales.emisor', 'tbl_requisitos_legales.descripcion_norma', 'tbl_requisitos_legales.fecha_emision', 'tbl_requisitos_legales.articulos_aplicables', 'tbl_requisitos_legales.subclasificacion', 'tbl_requisitos_legales.descripcion_requisito', 'tbl_requisitos_legales.estado', 'tbl_int_norma.id_tblNorma')
        ->leftjoin('tbl_int_legales', 'tbl_int_legales.id_norma', '=', 'tbl_requisitos_legales.id')
        ->leftjoin('tbl_int_norma', 'tbl_int_norma.id_norma', '=', 'tbl_requisitos_legales.id')
        ->leftjoin('tbl_tipo_peligro', 'tbl_tipo_peligro.id', '=', 'tbl_requisitos_legales.id_tipo_peligro')
        ->where([['tbl_requisitos_legales.estado', '=', 1], ['tbl_int_legales.id_modLegal', $modulo], ['tbl_int_norma.id_empresa', $id_empresa], ['tbl_int_norma.tipo', $modulo]])
        ->get();

      $requisitosAuditoria = DB::table('tbl_requisitos_legales')
        ->select('tbl_requisitos_legales.id', 'tbl_tipo_peligro.peligro', 'tbl_requisitos_legales.tipo_norma', 'tbl_requisitos_legales.emisor', 'tbl_requisitos_legales.descripcion_norma', 'tbl_requisitos_legales.fecha_emision', 'tbl_requisitos_legales.articulos_aplicables', 'tbl_requisitos_legales.subclasificacion', 'tbl_requisitos_legales.descripcion_requisito', 'tbl_requisitos_legales.estado', 'tbl_int_norma.id_tblNorma', 'tbl_PlanAbo_ML.fecha_revision as fechaR', 'tbl_PlanAbo_ML.id_empresa as idEmpresaAbo', 'tbl_plan_accion.fecha_revision as fechaS', 'tbl_plan_accion.id_empresa as idEmpresa', 'tbl_PlanAbo_ML.modulo', 'tbl_plan_accion.tipo')
        ->leftjoin('tbl_int_legales', 'tbl_int_legales.id_norma', '=', 'tbl_requisitos_legales.id')
        ->leftjoin('tbl_int_norma', 'tbl_int_norma.id_norma', '=', 'tbl_requisitos_legales.id')
        ->leftjoin('tbl_tipo_peligro', 'tbl_tipo_peligro.id', '=', 'tbl_requisitos_legales.id_tipo_peligro')
        ->leftjoin('tbl_plan_accion', 'tbl_plan_accion.id_requisito_legal', '=', 'tbl_requisitos_legales.id')
        ->leftjoin('tbl_PlanAbo_ML', 'tbl_PlanAbo_ML.id_requisito_legal', '=', 'tbl_requisitos_legales.id')
        ->where([['tbl_requisitos_legales.estado', '=', 1], ['tbl_int_legales.id_modLegal', $modulo], ['tbl_int_norma.id_empresa', $id_empresa], ['tbl_int_norma.tipo', $modulo], ['tbl_plan_accion.id_empresa', $id_empresa], ['tbl_plan_accion.tipo', $modulo]])
        ->orwhere([['tbl_requisitos_legales.estado', '=', 1], ['tbl_int_legales.id_modLegal', $modulo], ['tbl_int_norma.id_empresa', $id_empresa], ['tbl_PlanAbo_ML.id_empresa', $id_empresa], ['tbl_plan_accion.id_empresa', $id_empresa], ['tbl_plan_accion.tipo', $modulo], ['tbl_PlanAbo_ML.modulo', $modulo], ['tbl_int_norma.tipo', $modulo]])
        ->groupby('tbl_requisitos_legales.id')
        ->get();

      //dd( $requisitos);
      $mensaje = '';
      return view('RequisitosLegales.Modulos.listMedPreventiva', compact('requisitosAuditoria', 'mensaje', 'nombre', 'requisitos', 'id_empresa', 'tipo', 'modulo'));
    } elseif ($rolUser == 1 || $rolUser == 6 || $rolUser == 15 || (Sentinel::getUser()->role_id == 11 && Sentinel::getUser()->id_ciaAncla == 35)) {

      return view('RequisitosLegales.Modulos.listMedPreventiva2', compact('nombre', 'id_empresa', 'modulo'));

      // return redirect()->action('RequisitosLegales\RequisitoController@lisrequi',[$id_empresa,$modulo]);
    }
  }

  public function listMedPreventiva2($id_empresa, $modulo)
  {

    //dd($id_empresa);
    $company = DB::table('company')
      ->select('razonsocial')
      ->where('id', $id_empresa)
      ->get();
    foreach ($company as  $value) {
      $nombre = $value->razonsocial;
    }

    $requisitos = DB::table('tbl_requisitos_legales')
      ->select('tbl_requisitos_legales.id','tbl_requisitos_legales.total', 'tbl_tipo_peligro.peligro', 'tbl_requisitos_legales.tipo_norma', 
               'tbl_requisitos_legales.emisor', 'tbl_requisitos_legales.descripcion_norma', 
               'tbl_requisitos_legales.fecha_emision', 'tbl_requisitos_legales.articulos_aplicables',
               'tbl_requisitos_legales.subclasificacion', 'tbl_requisitos_legales.descripcion_requisito', 
               'tbl_requisitos_legales.estado', 'tbl_int_norma.id_tblNorma')
      ->leftjoin('tbl_int_legales', 'tbl_int_legales.id_norma', '=', 'tbl_requisitos_legales.id')
      ->leftjoin('tbl_int_norma', 'tbl_int_norma.id_norma', '=', 'tbl_requisitos_legales.id')
      ->leftjoin('tbl_tipo_peligro', 'tbl_tipo_peligro.id', '=', 'tbl_requisitos_legales.id_tipo_peligro')
      ->where([
        ['tbl_requisitos_legales.estado', '=', 1],
        ['tbl_int_legales.id_modLegal', $modulo],
        ['tbl_int_norma.id_empresa', $id_empresa], 
        ['tbl_int_norma.tipo', $modulo]
      ])
      ->groupBy('tbl_int_legales.id_norma') // Group by the second join table's norm ID
      ->get();



    $requisitosAuditoria = DB::table('tbl_requisitos_legales')
      ->select('tbl_requisitos_legales.id', 'tbl_tipo_peligro.peligro', 'tbl_requisitos_legales.tipo_norma', 'tbl_requisitos_legales.emisor', 'tbl_requisitos_legales.descripcion_norma', 'tbl_requisitos_legales.fecha_emision', 'tbl_requisitos_legales.articulos_aplicables', 'tbl_requisitos_legales.subclasificacion', 'tbl_requisitos_legales.descripcion_requisito', 'tbl_requisitos_legales.estado', 'tbl_int_norma.id_tblNorma', 'tbl_PlanAbo_ML.fecha_revision as fechaR', 'tbl_PlanAbo_ML.id_empresa as idEmpresaAbo', 'tbl_plan_accion.fecha_revision as fechaS', 'tbl_plan_accion.id_empresa as idEmpresa', 'tbl_PlanAbo_ML.modulo', 'tbl_plan_accion.tipo')
      ->leftjoin('tbl_int_legales', 'tbl_int_legales.id_norma', '=', 'tbl_requisitos_legales.id')
      ->leftjoin('tbl_int_norma', 'tbl_int_norma.id_norma', '=', 'tbl_requisitos_legales.id')
      ->leftjoin('tbl_tipo_peligro', 'tbl_tipo_peligro.id', '=', 'tbl_requisitos_legales.id_tipo_peligro')
      ->leftjoin('tbl_plan_accion', 'tbl_plan_accion.id_requisito_legal', '=', 'tbl_requisitos_legales.id')
      ->leftjoin('tbl_PlanAbo_ML', 'tbl_PlanAbo_ML.id_requisito_legal', '=', 'tbl_requisitos_legales.id')
      // ->where([['tbl_requisitos_legales.estado','=',1],['tbl_int_legales.id_modLegal',$modulo],['tbl_int_norma.id_empresa',$id_empresa],['tbl_int_norma.tipo',$modulo],['tbl_plan_accion.id_empresa',$id_empresa],['tbl_plan_accion.tipo',$modulo]])
      ->where([['tbl_requisitos_legales.estado', '=', 1], ['tbl_int_legales.id_modLegal', $modulo], ['tbl_int_norma.id_empresa', $id_empresa], ['tbl_PlanAbo_ML.id_empresa', $id_empresa], ['tbl_plan_accion.id_empresa', $id_empresa], ['tbl_plan_accion.tipo', $modulo], ['tbl_PlanAbo_ML.modulo', $modulo], ['tbl_int_norma.tipo', $modulo]])
      ->groupby('tbl_requisitos_legales.id')
      ->get();




    //dd($requisitosAuditoria);
    return view('RequisitosLegales.Modulos.listMedPreventiva', compact('mensaje', 'nombre', 'requisitos', 'id_empresa', 'tipo', 'modulo', 'requisitosAuditoria'));
  }


  public function listMedPreventivaAbo($id_empresa, $modulo)
  {



    return redirect()->action('RequisitosLegales\RequisitoController@lisrequi', [$id_empresa, $modulo]);
  }

  public function listMedPreventivaAbo1($id_empresa, $modulo)
  {



    return redirect()->action('RequisitosLegales\RequisitoController@lisrequiAbo', [$id_empresa, $modulo]);
  }


  public function MedPreventivaForm($id, $id_empresa, $modulo) //id_biologico
  {



    $requisitos = DB::table('tbl_requisitos_legales')
      ->where([['id', $id], ['estado', 1]])
      ->get();


    $criterios = DB::table('tbl_requisitos_legales')
      ->select('tbl_int_criterio.id_tblCriterio', 'tbl_requisitos_legales.id', 'tbl_requisitos_legales.id_tipo_peligro', 'tbl_requisitos_legales.tipo_norma', 'tbl_requisitos_legales.emisor', 'tbl_requisitos_legales.descripcion_norma', 'tbl_requisitos_legales.fecha_emision', 'tbl_requisitos_legales.articulos_aplicables', 'tbl_requisitos_legales.subclasificacion', 'tbl_requisitos_legales.descripcion_requisito', 'tbl_requisitos_legales.estado', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento as id_criterio', 'tbl_criterio_cumplimiento.criterio', 'tbl_criterio_cumplimiento.criterio_evidencia', 'tbl_criterio_cumplimiento.valor', 'tbl_resultados.respuesta', 'tbl_int_criterio.estado')
      ->leftjoin('tbl_criterio_cumplimiento', 'tbl_criterio_cumplimiento.id_requisito_legal', '=', 'tbl_requisitos_legales.id')
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->leftjoin('tbl_resultados', 'tbl_resultados.id_criterio_cumplimiento', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_requisitos_legales.id', $id], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_int_criterio.id_norma', $modulo]])
      ->groupBy('tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->get();

    $evidencias = DB::table('tbl_evidencias')
      ->where('id_empresa', $id_empresa)
      ->whereIn('id_criterio', $criterios->pluck('id_criterio'))
      ->get()
      ->keyBy('id_criterio');
    //dd($criterios);

    $valor = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(tbl_criterio_cumplimiento.valor) as valorTotal'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_criterio_cumplimiento.id_requisito_legal', $id], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_int_criterio.id_norma', $modulo], ['tbl_int_criterio.estado', 1]])
      ->distinct()
      ->get();

    foreach ($valor as $value) {
      $valorTotal = $value->valorTotal;
    }

    return view('RequisitosLegales.Modulos.MedPreventivaForm', compact('requisitos', 'evidencias', 'criterios', 'valorTotal', 'id', 'id_empresa', 'plan', 'tipo', 'modulo'));
  }

  public function guardarEvidencia(Request $request)
{
    $criterio_ids = $request->id_criterio;
    $empresa_ids = $request->id_empresa;
    $justificaciones = $request->justificacion;
    $observaciones = $request->observaciones;
    $archivos = $request->file('evidencia');

    // Ensure all are arrays
    $criterio_ids = is_array($criterio_ids) ? $criterio_ids : ($criterio_ids ? [$criterio_ids] : []);
    $empresa_ids = is_array($empresa_ids) ? $empresa_ids : ($empresa_ids ? [$empresa_ids] : []);
    $justificaciones = is_array($justificaciones) ? $justificaciones : ($justificaciones ? [$justificaciones] : []);
    $observaciones = is_array($observaciones) ? $observaciones : ($observaciones ? [$observaciones] : []);
    $archivos = is_array($archivos) ? $archivos : ($archivos ? [$archivos] : []);

    $max = count($criterio_ids);

    for ($i = 0; $i < $max; $i++) {
        $data = [
            'id_criterio' => $criterio_ids[$i] ?? null,
            'id_empresa' => $empresa_ids[$i] ?? null,
            'justificacion' => $justificaciones[$i] ?? '',
            'observaciones' => $observaciones[$i] ?? '',
        ];

        // Handle file if exists
        if (isset($archivos[$i]) && $archivos[$i]) {
            $file = $archivos[$i];
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $rutaFile = 'archivos/RequisitosLegales/Adjuntos/' . ($empresa_ids[$i] ?? 'default');
            $file->move($rutaFile, $filename);
            $data['archivo'] = '/' . $rutaFile . '/' . $filename;
        }

        // Only update if id_criterio and id_empresa are present
        if ($data['id_criterio'] !== null && $data['id_empresa'] !== null) {
            DB::table('tbl_evidencias')->updateOrInsert(
                ['id_criterio' => $data['id_criterio'], 'id_empresa' => $data['id_empresa']],
                $data
            );
        }
    }

    return redirect()->back()->with('success', 'Evidencias guardadas correctamente.');
}

  public function createMedPreventiva(Request $request)
  {
   
    $criterio = $request->criterio;
    $respuesta = $request->respuesta;

    if (Sentinel::getUser()->role_id == 15 || Sentinel::getUser()->role_id == 13) {
      $auditor = Sentinel::getUser()->name . ' ' . Sentinel::getUser()->last_name;
    }
    

    if ($respuesta != null || $respuesta != '') {
      $criterioX = array_filter($criterio);
      $criterioY = array_values($criterioX);

// Filter out empty values and get clean array
$respuestaX = array_filter($respuesta);

// Get array with just the values
$valores = array_values($respuestaX);

// Get array with positions/keys
$posiciones = array_keys($respuestaX);


      for ($a = 0; $a < count($posiciones); $a++) {
        $resultado = Resultado::updateOrCreate(['id_criterio_cumplimiento' => $posiciones[$a], 'tipo' => $request->modulo], [
          'id_requisito_legal' => $request->id,
          'id_empresa' => $request->id_empresa,
          'tipo' => $request->modulo,
          'respuesta' => $valores[$a],
          'estado' => 1
        ]);
      }
    }
// Update total in tbl_requisitos_legales table
if ($request->total) {
    DB::table('tbl_requisitos_legales')
        ->where('id', $request->id)
        ->update(['total' => $request->total]);
}
    $id_requisito_legal = $resultado->id_requisito_legal;
    $id_empresa = $resultado->id_empresa;
    $plan_accion = $request->plan_accion;
    $fecha_revision = $request->fecha_revision;
    $responsable = $request->responsable;
    $seguimiento = $request->seguimiento;
    $observaciones = $request->obs;


    $rol = Sentinel::getUser()->role_id;
    //Plan del Usuario
    if ($rol == 13 || $rol == 15) {

      // dd('hola abogado');
      $planAccion = tbl_PlanAbo_ML::Create([

        'id_requisito_legal' => $request->id,
        'id_empresa' => $request->id_empresa,
        'plan_accion' => $plan_accion,
        'responsable' => $responsable,
        'modulo' => $request->modulo,
        'fecha_revision' => $fecha_revision,
        'auditor' => $auditor,
        'observaciones' => $observaciones,
        'seguimiento' => $seguimiento,
        'estado' => 1
      ]);
      //auditoria del abogado
    } else {

      $planAccion = PlanAccion::create([
        'id_requisito_legal' => $request->id,
        'id_empresa' => $request->id_empresa,
        'plan_accion' => $plan_accion,
        'responsable' => $responsable,
        'tipo' => $request->modulo,
        'tipo_arch' => $request->tipo,
        'fecha_revision' => $fecha_revision,
        'obs' => $observaciones,
        'seguimiento' => $seguimiento,
        'estado' => 1
      ]);

      $id_plan = $planAccion->id;
      $adjunto = $request->file('archivo');

      // Only process if files were uploaded
      if (!empty($adjunto)) {

        $filePlan = array_filter($adjunto);
        $filePlan = array_values($filePlan);
        $filesplan = array();

        // Create array of files
        for ($i = 0; $i < count($filePlan); $i++) {
          $filesplan[$i] = array($filePlan[$i]);
        }

        // Process each file
        for ($a = 0; $a < count($filesplan); $a++) {
          // Create directory if it doesn't exist
          $uploadPath = public_path() . "/archivos/RequisitosLegales/Adjuntos/" . $id_empresa . "/" . $id_plan;
          if (!File::exists($uploadPath)) {
            File::makeDirectory($uploadPath, 0777, true);
          }

          // Sanitize filename
          $nameFile = $filesplan[$a][0]->getClientOriginalName();
          $nameFile = htmlentities($nameFile);
          $nameFile = preg_replace('/\&(.)[^;]*;/', '\\1', $nameFile);
          $nameFile = str_replace(' ', '', $nameFile);

          // Replace accented characters
          $replacements = [
            'á' => 'a',
            'é' => 'e',
            'í' => 'i',
            'ó' => 'o',
            'ú' => 'u',
            'ñ' => 'n',
            'Á' => 'A',
            'É' => 'E',
            'Í' => 'I',
            'Ó' => 'O',
            'Ú' => 'U',
            'Ñ' => 'N'
          ];
          $nameFile = str_replace(array_keys($replacements), array_values($replacements), $nameFile);

          // Save file
          $rutaFile = 'archivos/RequisitosLegales/Adjuntos/' . $id_empresa . '/' . $id_plan;
          $filesplan[$a][0]->move($rutaFile, $nameFile);
          $rutaArchivo = '/' . $rutaFile . '/' . $nameFile;

          // Create database record
          $adjuntosPlan = PlanAdjunto::create([
            'id_requisito_legal' => $id_requisito_legal,
            'id_plan_accion' => $id_plan,
            'archivo' => $rutaArchivo,
            'estado' => 1
          ]);
        }
      }
    }



    /*  return redirect()->action('RequisitosLegales\RequisitosLegalesController@listMedPreventiva',[$request->id_empresa,$request->modulo]); */
    return redirect()->back();
  }

  public function listMedPre($id, $id_empresa, $modulo)
  {


    $general = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('ROUND(SUM(tbl_criterio_cumplimiento.valor),1) as valor_obtenido'), 'tbl_requisitos_legales.tipo_norma', 'tbl_requisitos_legales.emisor', 'tbl_requisitos_legales.descripcion_requisito', 'tbl_requisitos_legales.descripcion_norma', 'tbl_requisitos_legales.articulos_aplicables')
      ->leftjoin('tbl_resultados', 'tbl_resultados.id_criterio_cumplimiento', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->leftjoin('tbl_requisitos_legales', 'tbl_requisitos_legales.id', '=', 'tbl_criterio_cumplimiento.id_requisito_legal')
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([/*['tbl_resultados.respuesta','=',1],*/['tbl_requisitos_legales.id', $id], ['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_int_criterio.id_norma', $modulo], ['tbl_resultados.tipo', $modulo]])
      ->groupby('tbl_requisitos_legales.tipo_norma', 'tbl_requisitos_legales.emisor', 'tbl_requisitos_legales.descripcion_norma', 'tbl_requisitos_legales.articulos_aplicables', 'tbl_requisitos_legales.descripcion_requisito')
      ->get();

    $id_plan = 0;


    $plan = DB::table('tbl_PlanAbo_ML')
      ->select('id_planAbo', 'plan_accion', 'responsable', 'fecha_revision', 'observaciones', 'seguimiento', 'auditor')
      ->where([['id_requisito_legal', $id], ['id_empresa', $id_empresa], ['modulo', $modulo]])
      ->get();

    $plan1 = DB::table('tbl_plan_accion')
      ->select('id', 'plan_accion', 'responsable', 'fecha_revision', 'obs', 'seguimiento')
      ->where([['id_requisito_legal', $id], ['tbl_plan_accion.id_empresa', $id_empresa], ['tbl_plan_accion.tipo', $modulo], ['tipo_arch', 2]])
      ->get();

    foreach ($plan as  $value) {
      $id_plan = $value->id_planAbo;
    }

    // dd($id_plan);
    $actualizacion = DB::table('tbl_actualizacion')
      ->leftjoin('tbl_PlanAbo_ML', 'tbl_PlanAbo_ML.id_planAbo', '=', 'tbl_actualizacion.id_planAccion')
      ->where([['tbl_PlanAbo_ML.id_requisito_legal', $id], ['tbl_PlanAbo_ML.id_empresa', $id_empresa], ['tbl_PlanAbo_ML.modulo', $modulo]])
      ->get();


    return view('RequisitosLegales.Modulos.listMedPre', compact('plan1', 'actualizacion', 'id_plan', 'general', 'plan', 'id', 'id_empresa', 'tipo', 'modulo'));
  }


  public function listPSR($id_empresa, $modulo)
  {


    return view('RequisitosLegales.Modulos.listPSR');
  }
















  public function listRepositorioMatrizLegal($id_empresa)
  {
    $requisitos = DB::table('repositorioMatrizLegal')
      ->select('id', 'anio_matriz', 'nombre_matriz', 'observaciones', 'adjunto')
      ->where([['id_empresa', $id_empresa], ['estado', 1]])
      ->get();

    return view('RequisitosLegales.Modulos.listRepositorioMatrizLegal', compact('requisitos', 'id_empresa', 'tipo'));
  }

  public function RepositorioMatrizLegal($id_empresa)
  {

    return view('RequisitosLegales.Modulos.RepositorioMatrizLegal', compact('id_empresa'));
  }

public function crearRepositorioMatrizLegal(Request $request)
{

    $id_empresa = $request->id_empresa;
    $id_registro = $request->id_registro;
    $anio_matriz = $request->anio_matriz;
    $nombre_matriz = $request->nombre_matriz;
    $observaciones = $request->observaciones;
    $adjunto = $request->file('adjunto');
    $user_id = Sentinel::getUser()->id;

    $adjunto = is_array($adjunto) ? $adjunto : ($adjunto ? [$adjunto] : []);
    $max = count($id_empresa);

  for ($i = 0; $i < $max; $i++) {
        $data = [
            'id_empresa' => $id_empresa ?? null,
            'anio_matriz' => $anio_matriz ?? null,
            'nombre_matriz' => $nombre_matriz ?? '',
            'observaciones' => $observaciones ?? '',
            'id_user' => $user_id,
        ];

        // Handle file if exists
        if (isset($adjunto[$i]) && $adjunto[$i]) {
            $file = $adjunto[$i];
            $filename = time() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
            $rutaFile = 'archivos/RequisitosLegales/Adjuntos/' . ($empresa_ids[$i] ?? 'default');
            $file->move($rutaFile, $filename);
            $data['adjunto'] = '/' . $rutaFile . '/' . $filename;
        }

        // Only update if id_registro is not null, otherwise skip
        
           DB::table('repositorioMatrizLegal')->updateOrInsert(
                ['id' => $id_registro ],
                $data
            );
        
    }

return $this->listRepositorioMatrizLegal($id_empresa);
}

  public function listCumplimientoGeneral($id_empresa, $modulo)
  {
    $company = DB::table('company')
      ->select('razonsocial')
      ->where('id', $id_empresa)
      ->get();
    foreach ($company as  $value) {
      $nombre = $value->razonsocial;
    }

    $valor_maximo_generico = 0;
    $valor_maximo_biologico = 0;
    $valor_maximo_condicion = 0;
    $valor_maximo_biomecanico = 0;
    $valor_maximo_fisico = 0;
    $valor_maximo_psicosocial = 0;
    $valor_maximo_quimico = 0;

    // dd($id_empresa.''.$tipo);
    $date = Carbon::now();
    $date = $date->format('Y');
    $date = $date + 0;

    //********************************************************//*********************************************************************//

    //********************************************************//*********************************************************************//

    //modulo 1
    $valor_maximo_1 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 1], ['tbl_criterio_cumplimiento.id_modulo', 1], ['tbl_int_criterio.estado', 1]])
      ->get();

    foreach ($valor_maximo_1 as $key) {
      $valor_maximo_11 = $key->valor_maximo;
    }

    $valor_obtenido_1 = DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 1]])
      ->get();
    foreach ($valor_obtenido_1 as $key) {
      $valor_obtenido_11 = $key->valor_obtenido;
    }

    $criterios_cumplidos_1 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 1]])
      ->get();
    $criterios_cumplidos_11 = count($criterios_cumplidos_1);

    $criterios_cumplimiento_1 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 1]])
      ->get();
    $criterios_cumplimiento_11 = count($criterios_cumplimiento_1);

    //modulo 2

    $valor_maximo_2 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 2], ['tbl_criterio_cumplimiento.id_modulo', 2], ['tbl_int_criterio.estado', 1]])
      ->get();

    foreach ($valor_maximo_2 as $key) {
      $valor_maximo_22 = $key->valor_maximo;
    }

    $valor_obtenido_2 = DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 2]])
      ->get();
    foreach ($valor_obtenido_2 as $key) {
      $valor_obtenido_22 = $key->valor_obtenido;
    }

    $criterios_cumplidos_2 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 2]])
      ->get();
    $criterios_cumplidos_22 = count($criterios_cumplidos_2);

    $criterios_cumplimiento_2 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 2]])
      ->get();
    $criterios_cumplimiento_22 = count($criterios_cumplimiento_2);


    //modulo 3
    $valor_maximo_3 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 3], ['tbl_criterio_cumplimiento.id_modulo', 3], ['tbl_int_criterio.estado', 1]])
      ->get();
    foreach ($valor_maximo_3 as $key) {
      $valor_maximo_33 = $key->valor_maximo;
    }

    $valor_obtenido_3 = DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 3]])
      ->get();
    foreach ($valor_obtenido_3 as $key) {
      $valor_obtenido_33 = $key->valor_obtenido;
    }

    $criterios_cumplidos_3 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 3]])
      ->get();
    $criterios_cumplidos_33 = count($criterios_cumplidos_3);

    $criterios_cumplimiento_3 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 2]])
      ->get();
    $criterios_cumplimiento_33 = count($criterios_cumplimiento_3);




    //modulo 4
    $valor_maximo_4 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 4], ['tbl_criterio_cumplimiento.id_modulo', 4], ['tbl_int_criterio.estado', 1]])
      ->get();
    foreach ($valor_maximo_4 as $key) {
      $valor_maximo_44 = $key->valor_maximo;
    }

    $valor_obtenido_4 = DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 4]])
      ->get();
    foreach ($valor_obtenido_4 as $key) {
      $valor_obtenido_44 = $key->valor_obtenido;
    }

    $criterios_cumplidos_4 =  DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 4]])
      ->get();
    $criterios_cumplidos_44 = count($criterios_cumplidos_4);

    $criterios_cumplimiento_4 =  DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 4]])
      ->get();
    $criterios_cumplimiento_44 = count($criterios_cumplimiento_4);


    //modulo 5
    $valor_maximo_5 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 5], ['tbl_criterio_cumplimiento.id_modulo', 5], ['tbl_int_criterio.estado', 1]])
      ->get();

    foreach ($valor_maximo_5 as $key) {
      $valor_maximo_55 = $key->valor_maximo;
    }

    $valor_obtenido_5 = DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 5]])
      ->get();


    foreach ($valor_obtenido_5 as $key) {
      $valor_obtenido_55 = $key->valor_obtenido;
    }

    $criterios_cumplidos_5 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 5]])
      ->get();

    $criterios_cumplidos_55 = count($criterios_cumplidos_5);

    $criterios_cumplimiento_5 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 5]])
      ->get();

    $criterios_cumplimiento_55 = count($criterios_cumplimiento_5);


    //modulo 
    $valor_maximo_6 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 6], ['tbl_criterio_cumplimiento.id_modulo', 6], ['tbl_int_criterio.estado', 1]])
      ->get();
    foreach ($valor_maximo_6 as $key) {
      $valor_maximo_66 = $key->valor_maximo;
    }

    $valor_obtenido_6 =  DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 6]])
      ->get();

    foreach ($valor_obtenido_6 as $key) {
      $valor_obtenido_66 = $key->valor_obtenido;
    }

    $criterios_cumplidos_6 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 6]])
      ->get();
    $criterios_cumplidos_6 = count($criterios_cumplidos_6);

    $criterios_cumplimiento_6 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 6]])
      ->get();
    $criterios_cumplimiento_66 = count($criterios_cumplimiento_6);


    //modulo 7
    $valor_maximo_7 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 7], ['tbl_criterio_cumplimiento.id_modulo', 7], ['tbl_int_criterio.estado', 1]])
      ->get();
    foreach ($valor_maximo_7 as $key) {
      $valor_maximo_77 = $key->valor_maximo;
    }

    $valor_obtenido_7 =  DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 7]])
      ->get();
    foreach ($valor_obtenido_7 as $key) {
      $valor_obtenido_77 = $key->valor_obtenido;
    }

    $criterios_cumplidos_7 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 7]])
      ->get();
    $criterios_cumplidos_7 = count($criterios_cumplidos_7);

    $criterios_cumplimiento_7 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 7]])
      ->get();
    $criterios_cumplimiento_77 = count($criterios_cumplimiento_7);


    //modulo 8
    $valor_maximo_8 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 8], ['tbl_criterio_cumplimiento.id_modulo', 8], ['tbl_int_criterio.estado', 1]])
      ->get();
    foreach ($valor_maximo_8 as $key) {
      $valor_maximo_88 = $key->valor_maximo;
    }

    $valor_obtenido_8 =  DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 8]])
      ->get();
    foreach ($valor_obtenido_8 as $key) {
      $valor_obtenido_88 = $key->valor_obtenido;
    }

    $criterios_cumplidos_8 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 8]])
      ->get();
    $criterios_cumplidos_88 = count($criterios_cumplidos_8);

    $criterios_cumplimiento_8 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 8]])
      ->get();
    $criterios_cumplimiento_88 = count($criterios_cumplimiento_8);



    //modulo 9
    $valor_maximo_9 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 9], ['tbl_criterio_cumplimiento.id_modulo', 9], ['tbl_int_criterio.estado', 1]])
      ->get();
    foreach ($valor_maximo_9 as $key) {
      $valor_maximo_99 = $key->valor_maximo;
    }

    $valor_obtenido_9 =  DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 9]])
      ->get();
    foreach ($valor_obtenido_9 as $key) {
      $valor_obtenido_99 = $key->valor_obtenido;
    }

    $criterios_cumplidos_9 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 9]])
      ->get();
    $criterios_cumplidos_99 = count($criterios_cumplidos_9);

    $criterios_cumplimiento_9 =  DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 9]])
      ->get();
    $criterios_cumplimiento_99 = count($criterios_cumplimiento_9);


    //modulo 10
    $valor_maximo_10 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 10], ['tbl_criterio_cumplimiento.id_modulo', 10], ['tbl_int_criterio.estado', 1]])
      ->get();
    foreach ($valor_maximo_10 as $key) {
      $valor_maximo_1010 = $key->valor_maximo;
    }

    $valor_obtenido_10 = DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 10]])
      ->get();
    foreach ($valor_obtenido_10 as $key) {
      $valor_obtenido_1010 = $key->valor_obtenido;
    }

    $criterios_cumplidos_10 =  DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 10]])
      ->get();
    $criterios_cumplidos_1010 = count($criterios_cumplidos_10);

    $criterios_cumplimiento_10 =  DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 10]])
      ->get();
    $criterios_cumplimiento_1010 = count($criterios_cumplimiento_10);






    //modulo 11
    $valor_maximo_111 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', 11], ['tbl_criterio_cumplimiento.id_modulo', 11], ['tbl_int_criterio.estado', 1]])
      ->get();
    foreach ($valor_maximo_111 as $key) {
      $valor_maximo_1111 = $key->valor_maximo;
    }

    $valor_obtenido_111 = DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 11]])
      ->get();
    foreach ($valor_obtenido_111 as $key) {
      $valor_obtenido_1111 = $key->valor_obtenido;
    }

    $criterios_cumplidos_111 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 11]])
      ->get();
    $criterios_cumplidos_1111 = count($criterios_cumplidos_111);

    $criterios_cumplimiento_111 =  DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', 11]])
      ->get();
    $criterios_cumplimiento_1111 = count($criterios_cumplimiento_111);










    //modulo 11
    $porcentaje_cumplimiento_11 = 0;

    if ($criterios_cumplimiento_1111 != null) {
      $porcentaje_cumplimiento_11 = round((($criterios_cumplidos_1111 * 100) / $criterios_cumplimiento_1111));
    }

    $total_valor_maximo11 = ($valor_maximo_1111);
    $total_valor_obtenido11 = ($valor_obtenido_1111);
    $total_porcentaje11 = 0;
    if ($total_valor_maximo11 != 0) {
      $total_porcentaje11 = round((($total_valor_obtenido11 * 100) / $total_valor_maximo11));
    }




    //modulo 10
    $porcentaje_cumplimiento_10 = 0;

    if ($criterios_cumplimiento_1010 != null) {
      $porcentaje_cumplimiento_10 = round((($criterios_cumplidos_1010 * 100) / $criterios_cumplimiento_1010));
    }

    $total_valor_maximo10 = ($valor_maximo_1010);
    $total_valor_obtenido10 = ($valor_obtenido_1010);
    $total_porcentaje10 = 0;
    if ($total_valor_maximo10 != 0) {
      $total_porcentaje10 = round((($total_valor_obtenido10 * 100) / $total_valor_maximo10));
    }




    //modulo 9
    $porcentaje_cumplimiento_9 = 0;

    if ($criterios_cumplimiento_99 != null) {
      $porcentaje_cumplimiento_9 = round((($criterios_cumplidos_99 * 100) / $criterios_cumplimiento_99));
    }

    $total_valor_maximo9 = ($valor_maximo_99);
    $total_valor_obtenido9 = ($valor_obtenido_99);
    $total_porcentaje9 = 0;
    if ($total_valor_maximo9 != 0) {
      $total_porcentaje9 = round((($total_valor_obtenido9 * 100) / $total_valor_maximo9));
    }


    //modulo 8
    $porcentaje_cumplimiento_8 = 0;

    if ($criterios_cumplimiento_88 != null) {
      $porcentaje_cumplimiento_8 = round((($criterios_cumplidos_88 * 100) / $criterios_cumplimiento_88));
    }

    $total_valor_maximo8 = ($valor_maximo_88);
    $total_valor_obtenido8 = ($valor_obtenido_88);
    $total_porcentaje8 = 0;
    if ($total_valor_maximo8 != 0) {
      $total_porcentaje8 = round((($total_valor_obtenido8 * 100) / $total_valor_maximo8));
    }

    //modulo 7
    /*   $porcentaje_cumplimiento_7=0;

    if ($criterios_cumplimiento_77!=null) {
      $porcentaje_cumplimiento_7 = round((($criterios_cumplidos_77*100)/$criterios_cumplimiento_77));
    }

    $total_valor_maximo7 = ($valor_maximo_77);
    $total_valor_obtenido7 = ($valor_obtenido_77);
    $total_porcentaje7=0;
    if($total_valor_maximo7 != 0){
        $total_porcentaje7 = round((($total_valor_obtenido7*100)/$total_valor_maximo7));
    }*/


    //modulo 6
    $porcentaje_cumplimiento_6 = 0;

    if ($criterios_cumplimiento_66 != null) {
      $porcentaje_cumplimiento_6 = round((($criterios_cumplidos_66 * 100) / $criterios_cumplimiento_66));
    }

    $total_valor_maximo6 = ($valor_maximo_66);
    $total_valor_obtenido6 = ($valor_obtenido_66);
    $total_porcentaje6 = 0;
    if ($total_valor_maximo6 != 0) {
      $total_porcentaje6 = round((($total_valor_obtenido6 * 100) / $total_valor_maximo6));
    }






    //modulo 5
    $porcentaje_cumplimiento_5 = 0;

    if ($criterios_cumplimiento_55 != null) {
      $porcentaje_cumplimiento_5 = round((($criterios_cumplidos_55 * 100) / $criterios_cumplimiento_55));
    }

    $total_valor_maximo5 = ($valor_maximo_55);
    $total_valor_obtenido5 = ($valor_obtenido_55);
    $total_porcentaje5 = 0;
    if ($total_valor_maximo5 != 0) {
      $total_porcentaje5 = round((($total_valor_obtenido5 * 100) / $total_valor_maximo5));
    }

    // dd($total_porcentaje5);
    //modulo 4
    $porcentaje_cumplimiento_4 = 0;

    if ($criterios_cumplimiento_44 != null) {
      $porcentaje_cumplimiento_4 = round((($criterios_cumplidos_44 * 100) / $criterios_cumplimiento_44));
    }

    $total_valor_maximo4 = ($valor_maximo_44);
    $total_valor_obtenido4 = ($valor_obtenido_44);
    $total_porcentaje4 = 0;
    if ($total_valor_maximo4 != 0) {
      $total_porcentaje4 = round((($total_valor_obtenido4 * 100) / $total_valor_maximo4));
    }





    //modulo 3
    $porcentaje_cumplimiento_3 = 0;

    if ($criterios_cumplimiento_33 != null) {
      $porcentaje_cumplimiento_3 = round((($criterios_cumplidos_33 * 100) / $criterios_cumplimiento_33));
    }

    $total_valor_maximo3 = ($valor_maximo_33);
    $total_valor_obtenido3 = ($valor_obtenido_33);
    $total_porcentaje3 = 0;
    if ($total_valor_maximo3 != 0) {
      $total_porcentaje3 = round((($total_valor_obtenido3 * 100) / $total_valor_maximo3));
    }

    //modulo 1

    /* $porcentaje_cumplimiento_1=0;

    if ($criterios_cumplimiento_11!=null) {
      $porcentaje_cumplimiento_1 = round((($criterios_cumplidos_11*100)/$criterios_cumplimiento_11));
    }

    $total_valor_maximo1 = ($valor_maximo_11);
    $total_valor_obtenido1 = ($valor_obtenido_11);
    $total_porcentaje1=0;
    if($total_valor_maximo1 != 0){
        $total_porcentaje1 = round((($total_valor_obtenido1*100)/$total_valor_maximo1));
    }*/

    //modulo 2

    $porcentaje_cumplimiento_2 = 0;

    if ($criterios_cumplimiento_22 != null) {
      $porcentaje_cumplimiento_2 = round((($criterios_cumplidos_22 * 100) / $criterios_cumplimiento_22));
    }

    $total_valor_maximo2 = ($valor_maximo_22);
    $total_valor_obtenido2 = ($valor_obtenido_22);
    $total_porcentaje2 = 0;
    if ($total_valor_maximo2 != 0) {
      $total_porcentaje2 = round((($total_valor_obtenido2 * 100) / $total_valor_maximo2));
    }

    $informe = DB::table('tbl_inFormularioML')
      ->select('tbl_inFormularioML.id_formML', 'tbl_inFormularioML.id_empresa', 'tbl_inFormularioML.modulo', 'tbl_inFormularioML.resp_SST', 'tbl_inFormularioML.repre_legal', 'tbl_inFormularioML.cedula_SST', 'tbl_inFormularioML.licenciaSST', 'tbl_inFormularioML.Copasst', 'tbl_inFormularioML.auditor', 'tbl_inFormularioML.cedula_audior', 'tbl_inFormularioML.fecha_entrega', 'tbl_inFormularioML.v_maximo', 'tbl_inFormularioML.v_obtenido', 'tbl_inFormularioML.porcentaje', 'tbl_inFormularioML.ve_imple', 'tbl_inFormularioML.cumplimiento', 'tbl_inFormularioML.asp_rele', 'tbl_inFormularioML.firma', 'tbl_inFormularioML.estado', 'tbl_inFormularioML.created_at', 'tbl_inFormularioML.updated_at', 'company.razonsocial', 'tbl_mod_legales.nombre')
      ->leftjoin('company', 'company.id', '=', 'tbl_inFormularioML.id_empresa')
      ->leftjoin('tbl_mod_legales', 'tbl_mod_legales.id_modLegal', '=', 'tbl_inFormularioML.modulo')
      ->where('tbl_inFormularioML.id_empresa', $id_empresa)
      ->get();

    // dd($informe);

    return view('RequisitosLegales.Cumplimiento.listCumplimientoGeneral', compact('nombre', 'modulo', 'id_empresa', 'tipo', 'total_valor_maximo1', 'total_valor_obtenido1', 'total_porcentaje1', 'total_valor_maximo3', 'total_valor_obtenido3', 'total_porcentaje3', 'total_valor_maximo4', 'total_valor_obtenido4', 'total_porcentaje4', 'total_valor_maximo5', 'total_valor_obtenido5', 'total_porcentaje5', 'total_valor_maximo2', 'total_valor_obtenido2', 'total_porcentaje2', 'total_valor_maximo6', 'total_valor_obtenido6', 'total_porcentaje6', 'total_valor_maximo7', 'total_valor_obtenido7', 'total_porcentaje7', 'total_valor_maximo8', 'total_valor_obtenido8', 'total_porcentaje8', 'total_valor_maximo9', 'total_valor_obtenido9', 'total_porcentaje9', 'total_valor_maximo10', 'total_valor_obtenido10', 'total_porcentaje10', 'total_valor_maximo11', 'total_valor_obtenido11', 'total_porcentaje11', 'informe'));
  }



  public function listaGeneralRequi($id_empresa, $modulo)
  {


    $general =  DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('ROUND(SUM(tbl_criterio_cumplimiento.valor),1) as valor_obtenido'), 'tbl_criterio_cumplimiento.id_tipo_peligro', 'tbl_requisitos_legales.id', 'tbl_requisitos_legales.tipo_norma', 'tbl_requisitos_legales.emisor', 'tbl_requisitos_legales.descripcion_requisito', 'tbl_requisitos_legales.descripcion_norma', 'tbl_requisitos_legales.articulos_aplicables')
      ->leftjoin('tbl_resultados', 'tbl_resultados.id_criterio_cumplimiento', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->leftjoin('tbl_requisitos_legales', 'tbl_requisitos_legales.id', '=', 'tbl_criterio_cumplimiento.id_requisito_legal')
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_resultados.respuesta', '=', 1], ['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_int_criterio.id_norma', $modulo], ['tbl_resultados.tipo', $modulo]])
      ->groupby('tbl_requisitos_legales.tipo_norma', 'tbl_requisitos_legales.emisor', 'tbl_requisitos_legales.descripcion_norma', 'tbl_requisitos_legales.articulos_aplicables', 'tbl_requisitos_legales.descripcion_requisito')
      ->get();

    foreach ($general as $key => $value) {
      $id_legal[] = $value->id;
    }


    $maximo = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->leftjoin('tbl_int_norma', 'tbl_int_norma.id_norma', '=', 'tbl_criterio_cumplimiento.id_requisito_legal')
      ->leftjoin('tbl_int_legales', 'tbl_int_legales.id_norma', '=', 'tbl_int_norma.id_norma')
      ->leftjoin('tbl_tipo_peligro', 'tbl_tipo_peligro.id', '=', 'tbl_criterio_cumplimiento.id_tipo_peligro')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_int_criterio.id_norma', $modulo], ['tbl_int_legales.id_modLegal', $modulo]])
      ->get();

    foreach ($maximo as  $value) {
      $maximo = $value->valor_maximo;
    }
    return view('RequisitosLegales.Cumplimiento.listaGeneralRequi', compact('general', 'id_empresa', 'modulo', 'maximo'));
  }


  public function maximo($id_peligro, $id_empresa, $modulo)
  {


    $maximo = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->leftjoin('tbl_int_norma', 'tbl_int_norma.id_norma', '=', 'tbl_criterio_cumplimiento.id_requisito_legal')
      ->leftjoin('tbl_int_legales', 'tbl_int_legales.id_norma', '=', 'tbl_int_norma.id_norma')
      ->leftjoin('tbl_tipo_peligro', 'tbl_tipo_peligro.id', '=', 'tbl_criterio_cumplimiento.id_tipo_peligro')
      ->where([['tbl_criterio_cumplimiento.id_tipo_peligro', $id_peligro], ['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_int_criterio.id_norma', $modulo], ['tbl_int_legales.id_modLegal', $modulo], ['tbl_int_criterio.estado', 1]])
      ->get();

    foreach ($maximo as  $value) {
      $maximo = $value->valor_maximo;
    }
    return json_encode($maximo);
  }












  public function createActualizacion(Request $request)
  {




    $creaAct = tbl_actualizacion::create([
      'id_planAccion' => $request->id_plan,
      'observaciones' => $request->obs,
      'cargo' => $request->cargo,
      'nombreRealizador' => $request->nombre,
      'fechaActualizacion' => $request->fecha,
      'estado' => 1


    ]);

    $id_plan = $creaAct->id_tblActu;



    if (!is_null($request->file('archivo'))) {
      $filePlan = array_filter($request->file('archivo'));

      $filePlan = array_values($filePlan);
      $filesplan = array();

      for ($i = 0; $i < count($filePlan); $i++) {
        $filesplan[$i] = array($filePlan[$i]);
      }

      for ($a = 0; $a < count($filesplan); $a++) {
        if (!File::exists(public_path() . "/archivos/RequisitosLegales/Adjuntos/" . $request->id_empresa . "/" . $id_plan)) {
          File::makeDirectory(public_path() . '/archivos/RequisitosLegales/Adjuntos/' . $request->id_empresa . "/" . $id_plan, 0777, true);
        }

        $nameFile = $filesplan[$a][0]->getClientOriginalName();
        $nameFile = htmlentities($nameFile);
        $nameFile = preg_replace('/\&(.)[^;]*;/', '\\1', $nameFile);
        $nameFile = str_replace(' ', '', $nameFile);
        $nameFile = str_replace('á', 'a', $nameFile);
        $nameFile = str_replace('é', 'e', $nameFile);
        $nameFile = str_replace('í', 'i', $nameFile);
        $nameFile = str_replace('ó', 'o', $nameFile);
        $nameFile = str_replace('ú', 'u', $nameFile);
        $nameFile = str_replace('ñ', 'n', $nameFile);
        $nameFile = str_replace('Á', 'A', $nameFile);
        $nameFile = str_replace('É', 'E', $nameFile);
        $nameFile = str_replace('Í', 'I', $nameFile);
        $nameFile = str_replace('Ó', 'O', $nameFile);
        $nameFile = str_replace('Ú', 'U', $nameFile);
        $nameFile = str_replace('Ñ', 'N', $nameFile);

        $extenFile = $filesplan[$a][0]->getClientOriginalExtension();
        $rutaFile = 'archivos/RequisitosLegales/Adjuntos/' . $request->id_empresa . '/' . $id_plan;
        $nombreFile = $nameFile;
        $filesplan[$a][0]->move($rutaFile, $nombreFile);
        $rutaArchivo = '/' . $rutaFile . '/' . $nombreFile;


        $creaActint = new tbl_int_actualizacion;
        $creaActint->create([
          'archivo' => $rutaArchivo,
          'id_actu' => $id_plan,
          'estado' => 1
        ]);
      }
    }


    return redirect()->back();
  }


  public function controlCMat($id_norma, $id_empresa, $modulo)
  {


    $control = DB::table('tbl_control_cambios')
      ->where([['id_empresa', $id_empresa], ['id_norma', $id_norma], ['modulo', $modulo]])
      ->get();
    return json_encode($control);
  }

  public function actualizarPlan($id)
  {

    $plan = DB::table('tbl_PlanAbo_ML')
      ->select('id_planAbo', 'plan_accion', 'responsable', 'fecha_revision', 'observaciones', 'seguimiento')
      ->where('id_planAbo', $id)
      ->get();


    return json_encode($plan);
  }

  public function editarPlan(Request $request)
  {

    $id = $request->id_plan;


    $planAccion = tbl_PlanAbo_ML::find($id);
    $planAccion->plan_accion = $request->plan_accion;
    $planAccion->responsable = $request->responsable;
    $planAccion->fecha_revision = $request->fecha_revision;
    $planAccion->observaciones = $request->obs;
    $planAccion->seguimiento = $request->seguimiento;
    $planAccion->update();






    return redirect()->back();
  }

  public function borrarRequi1($id, $id_empresa, $modulo)
  {


    // dd($id);

    $requisito = tbl_int_norma::where([['id_tblNorma', $id], ['id_empresa', $id_empresa], ['tipo', $modulo]])->delete();

    return redirect()->back();
  }

  public function borrarCrite($id, $id_empresa)
  {


    // dd($id);

    $criterio = tbl_int_criterio::where([['id_tblCriterio', $id], ['id_empresa', $id_empresa]])->delete();

    return redirect()->back();
  }

  public function borrarCriterioReq($id)
  {


    // dd($id);

    $criterio = Criterio::where('id_criterio_cumplimiento', $id)->delete();

    return redirect()->back();
  }

  public function informeML($id_empresa, $modulo)
  {

    //modulo 11
    $valor_maximo_111 = DB::table('tbl_criterio_cumplimiento')
      ->select(DB::raw('SUM(valor) as valor_maximo'))
      ->leftjoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
      ->where([['tbl_int_criterio.id_empresa', $id_empresa], ['tbl_criterio_cumplimiento.estado', 1], ['tbl_int_criterio.id_norma', $modulo], ['tbl_criterio_cumplimiento.id_modulo', $modulo], ['tbl_int_criterio.estado', 1]])
      ->get();
    foreach ($valor_maximo_111 as $key) {
      $valor_maximo_1111 = $key->valor_maximo;
    }

    $valor_obtenido_111 = DB::table('tbl_resultados')
      ->select(DB::raw('SUM(tbl_resultados.respuesta) as valor_obtenido'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', $modulo]])
      ->get();
    foreach ($valor_obtenido_111 as $key) {
      $valor_obtenido_1111 = $key->valor_obtenido;
    }

    $criterios_cumplidos_111 = DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.respuesta', 1], ['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', $modulo]])
      ->get();
    $criterios_cumplidos_1111 = count($criterios_cumplidos_111);

    $criterios_cumplimiento_111 =  DB::table('tbl_resultados')
      ->select(DB::raw('tbl_resultados.respuesta'))
      ->where([['tbl_resultados.id_empresa', $id_empresa], ['tbl_resultados.tipo', $modulo]])
      ->get();
    $criterios_cumplimiento_1111 = count($criterios_cumplimiento_111);





    //modulo 11
    $porcentaje_cumplimiento_11 = 0;

    if ($criterios_cumplimiento_1111 != null) {
      $porcentaje_cumplimiento_11 = round((($criterios_cumplidos_1111 * 100) / $criterios_cumplimiento_1111));
    }

    $total_valor_maximo11 = ($valor_maximo_1111);
    $total_valor_obtenido11 = ($valor_obtenido_1111);
    $total_porcentaje11 = 0;
    if ($total_valor_maximo11 != 0) {
      $total_porcentaje11 = round((($total_valor_obtenido11 * 100) / $total_valor_maximo11));
    }


    $company = DB::table('company')
      ->select('company.id', 'company.razonsocial', 'company.nit', 'company.contactoSST', 'company.id_asesor', 'company.gerente', 'users.name', 'users.last_name', 'users.num_documento')
      ->leftjoin('users', 'users.id', '=', 'company.id_asesor')
      ->where('company.id', $id_empresa)
      ->get();

    $user = DB::table('users')
      ->select('name', 'last_name', 'num_documento')
      ->where('id', Sentinel::getUser()->id)
      ->get();

    foreach ($user as  $value) {
      $name = $value->name;
      $last_name = $value->last_name;
      $cedula = $value->num_documento;
    }

    $nombreModulo = DB::table('tbl_mod_legales')
      ->select('nombre')
      ->where('id_modLegal', $modulo)
      ->get();

    foreach ($nombreModulo as  $value) {
      $nomMo = $value->nombre;
    }

    return view('RequisitosLegales.Cumplimiento.informeML', compact('total_valor_maximo11', 'total_valor_obtenido11', 'total_porcentaje11', 'id_empresa', 'modulo', 'company', 'nomMo', 'name', 'last_name', 'cedula'));
  }



  public function createInforme(Request $request)
  {





    $timer = time();
    $firmRecibe;
    if (isset($request->urlFirma)) {

      $nomb = "Auditor" . $timer;
      $nombreFirma = str_replace(' ', '', $nomb);
      $img = $request->urlFirma;
      $imgData = base64_decode(substr($img, 22));
      $file = 'archivos/ML/informe/firma/' . $nombreFirma . '.png';
      $fp = fopen($file, 'w');
      fwrite($fp, $imgData);
      fclose($fp);

      $firmRecibe = $file;
    } else {
      $firmRecibe = null;
    }

    if ($request->tipo == 1) {

      $informeML = tbl_inFormularioML::updateOrCreate(['id_empresa' => $request->id_empresa, 'modulo' => $request->modulo], [

        'id_empresa' => $request->id_empresa,
        'modulo' => $request->modulo,
        'resp_SST' => $request->responsableSST,
        'repre_legal' => $request->representanteLegal,
        'cedula_SST' => $request->cedulaSST,
        'Copasst' => $request->representanteCOPASST,
        'auditor' => $request->auditor,
        'cedula_audior' => $request->cedula_auditor,
        'fecha_entrega' => $request->fecha,
        'licenciaSST' => $request->licencia,
        'v_maximo' => $request->Vm,
        'v_obtenido' => $request->Vo,
        'porcentaje' => $request->Porc,
        've_imple' => $request->verificacion,
        'cumplimiento' => $request->cumplimiento,
        'asp_rele' => $request->aspectos,
        'firma' => $firmRecibe,
        'estado' => 1

      ]);
      $id_formML = DB::table('tbl_inFormularioML')
        ->select('id_formML')
        ->where('id_empresa', $request->id_empresa)
        ->orderby('created_at', 'DESC')->take(1)->get();
      // dd($id_formML);
      foreach ($id_formML as  $value) {
        $id = $value->id_formML;
      }

      $informe = tbl_HistoinFormularioML::create([

        'id_empresa' => $request->id_empresa,
        'modulo' => $request->modulo,
        'id_informulario' => $id,
        'resp_SST' => $request->responsableSST,
        'repre_legal' => $request->representanteLegal,
        'cedula_SST' => $request->cedulaSST,
        'Copasst' => $request->representanteCOPASST,
        'auditor' => $request->auditor,
        'cedula_audior' => $request->cedula_auditor,
        'fecha_Actualizacion' => $request->fecha,
        'licenciaSST' => $request->licencia,
        'v_maximo' => $request->Vm,
        'v_obtenido' => $request->Vo,
        'porcentaje' => $request->Porc,
        've_imple' => $request->verificacion,
        'cumplimiento' => $request->cumplimiento,
        'observaciones' => $request->Observaciones,
        'asp_rele' => $request->aspectos,
        'firma' => $firmRecibe,
        'estado' => 1

      ]);
    } elseif ($tipo == 2) {
      $informeML = tbl_inFormularioML::updateOrCreate(['id_empresa' => $request->id_empresa, 'modulo' => $request->modulo], [

        'id_empresa' => $request->id_empresa,
        'modulo' => $request->modulo,
        'resp_SST' => $request->responsableSST,
        'repre_legal' => $request->representanteLegal,
        'cedula_SST' => $request->cedulaSST,
        'Copasst' => $request->representanteCOPASST,
        'auditor' => $request->auditor,
        'cedula_audior' => $request->cedula_auditor,
        'fecha_entrega' => $request->fecha,
        'licenciaSST' => $request->licencia,
        'v_maximo' => $request->Vm,
        'v_obtenido' => $request->Vo,
        'porcentaje' => $request->Porc,
        've_imple' => $request->verificacion,
        'cumplimiento' => $request->cumplimiento,
        'asp_rele' => $request->aspectos,
        'firma' => $firmRecibe,
        'estado' => 1

      ]);


      $informe = tbl_HistoinFormularioML::create([

        'id_empresa' => $request->id_empresa,
        'modulo' => $request->modulo,
        'id_informulario' => $request->id_informulario,
        'resp_SST' => $request->responsableSST,
        'repre_legal' => $request->representanteLegal,
        'cedula_SST' => $request->cedulaSST,
        'Copasst' => $request->representanteCOPASST,
        'auditor' => $request->auditor,
        'cedula_audior' => $request->cedula_auditor,
        'fecha_Actualizacion' => $request->fecha,
        'licenciaSST' => $request->licencia,
        'v_maximo' => $request->Vm,
        'v_obtenido' => $request->Vo,
        'porcentaje' => $request->Porc,
        've_imple' => $request->verificacion,
        'cumplimiento' => $request->cumplimiento,
        'observaciones' => $request->Observaciones,
        'asp_rele' => $request->aspectos,
        'firma' => $firmRecibe,
        'estado' => 1

      ]);
    }





    return redirect()->action('RequisitosLegales\RequisitosLegalesController@listCumplimientoGeneral', [$request->id_empresa, 0]);
  }

  public function actuaInforme($id, $id_empresa)
  {

    $informe = DB::table('tbl_inFormularioML')
      ->select('tbl_inFormularioML.id_formML', 'tbl_inFormularioML.id_empresa', 'tbl_inFormularioML.modulo', 'tbl_inFormularioML.resp_SST', 'tbl_inFormularioML.repre_legal', 'tbl_inFormularioML.cedula_SST', 'tbl_inFormularioML.licenciaSST', 'tbl_inFormularioML.Copasst', 'tbl_inFormularioML.auditor', 'tbl_inFormularioML.cedula_audior', 'tbl_inFormularioML.fecha_entrega', 'tbl_inFormularioML.v_maximo', 'tbl_inFormularioML.v_obtenido', 'tbl_inFormularioML.porcentaje', 'tbl_inFormularioML.ve_imple', 'tbl_inFormularioML.cumplimiento', 'tbl_inFormularioML.asp_rele', 'tbl_inFormularioML.firma', 'tbl_inFormularioML.estado', 'tbl_inFormularioML.created_at', 'tbl_inFormularioML.updated_at', 'company.razonsocial', 'tbl_mod_legales.nombre', 'company.nit')
      ->leftjoin('company', 'company.id', '=', 'tbl_inFormularioML.id_empresa')
      ->leftjoin('tbl_mod_legales', 'tbl_mod_legales.id_modLegal', '=', 'tbl_inFormularioML.modulo')
      ->where('tbl_inFormularioML.id_formML', $id)
      ->get();


    return view('RequisitosLegales.Cumplimiento.actuaInforme', compact('informe', 'id_empresa', 'id'));
  }

  public function historialInformeML($id, $id_empresa)
  {

    $informe = DB::table('tbl_HistoinFormularioML')
      ->select('tbl_HistoinFormularioML.id_histo', 'tbl_HistoinFormularioML.id_empresa', 'tbl_HistoinFormularioML.modulo', 'tbl_HistoinFormularioML.resp_SST', 'tbl_HistoinFormularioML.repre_legal', 'tbl_HistoinFormularioML.cedula_SST', 'tbl_HistoinFormularioML.licenciaSST', 'tbl_HistoinFormularioML.Copasst', 'tbl_HistoinFormularioML.auditor', 'tbl_HistoinFormularioML.cedula_audior', 'tbl_HistoinFormularioML.fecha_Actualizacion', 'tbl_HistoinFormularioML.v_maximo', 'tbl_HistoinFormularioML.v_obtenido', 'tbl_HistoinFormularioML.porcentaje', 'tbl_HistoinFormularioML.ve_imple', 'tbl_HistoinFormularioML.cumplimiento', 'tbl_HistoinFormularioML.observaciones', 'tbl_HistoinFormularioML.asp_rele', 'tbl_HistoinFormularioML.firma', 'tbl_HistoinFormularioML.estado', 'tbl_HistoinFormularioML.created_at', 'tbl_HistoinFormularioML.updated_at', 'company.razonsocial', 'tbl_mod_legales.nombre', 'company.nit')
      ->leftjoin('company', 'company.id', '=', 'tbl_HistoinFormularioML.id_empresa')
      ->leftjoin('tbl_mod_legales', 'tbl_mod_legales.id_modLegal', '=', 'tbl_HistoinFormularioML.modulo')
      ->where('tbl_HistoinFormularioML.id_informulario', $id)
      ->get();

    return view('RequisitosLegales.Cumplimiento.historialInformeML', compact('informe', 'id_empresa', 'id'));
  }

  public function requisitosLegales(Request $request, $id_empresa)
  {
    $year = $request->input('year'); // e.g., from query ?year=2024

    $query = DB::table('requisitos_legales_historico')
        ->where('id_empresa', $id_empresa);

    if ($year) {
        $query->where('year', $year); // use correct column name here
    }

    $requisitos = $query->get();

    return view('RequisitosLegales.Modulos.requisitos_legales_historico', compact('requisitos', 'id_empresa', 'year'));
  }

  public function PDFinformeML($id, $tipo)
  {



    if ($tipo == 1) {
      $informe = DB::table('tbl_inFormularioML')
        ->select('tbl_inFormularioML.id_formML', 'tbl_inFormularioML.id_empresa', 'tbl_inFormularioML.modulo', 'tbl_inFormularioML.resp_SST', 'tbl_inFormularioML.repre_legal', 'tbl_inFormularioML.cedula_SST', 'tbl_inFormularioML.licenciaSST', 'tbl_inFormularioML.Copasst', 'tbl_inFormularioML.auditor', 'tbl_inFormularioML.cedula_audior', 'tbl_inFormularioML.fecha_entrega as fecha', 'tbl_inFormularioML.v_maximo', 'tbl_inFormularioML.v_obtenido', 'tbl_inFormularioML.porcentaje', 'tbl_inFormularioML.ve_imple', 'tbl_inFormularioML.cumplimiento', 'tbl_inFormularioML.asp_rele', 'tbl_inFormularioML.firma', 'tbl_inFormularioML.estado', 'tbl_inFormularioML.created_at', 'tbl_inFormularioML.updated_at', 'company.razonsocial', 'tbl_mod_legales.nombre', 'company.nit')
        ->leftjoin('company', 'company.id', '=', 'tbl_inFormularioML.id_empresa')
        ->leftjoin('tbl_mod_legales', 'tbl_mod_legales.id_modLegal', '=', 'tbl_inFormularioML.modulo')
        ->where('tbl_inFormularioML.id_formML', $id)
        ->get();
    } elseif ($tipo == 2) {

      $informe = DB::table('tbl_HistoinFormularioML')
        ->select('tbl_HistoinFormularioML.id_empresa', 'tbl_HistoinFormularioML.modulo', 'tbl_HistoinFormularioML.resp_SST', 'tbl_HistoinFormularioML.repre_legal', 'tbl_HistoinFormularioML.cedula_SST', 'tbl_HistoinFormularioML.licenciaSST', 'tbl_HistoinFormularioML.Copasst', 'tbl_HistoinFormularioML.auditor', 'tbl_HistoinFormularioML.cedula_audior', 'tbl_HistoinFormularioML.fecha_Actualizacion as fecha', 'tbl_HistoinFormularioML.v_maximo', 'tbl_HistoinFormularioML.v_obtenido', 'tbl_HistoinFormularioML.porcentaje', 'tbl_HistoinFormularioML.ve_imple', 'tbl_HistoinFormularioML.cumplimiento', 'tbl_HistoinFormularioML.observaciones', 'tbl_HistoinFormularioML.asp_rele', 'tbl_HistoinFormularioML.firma', 'tbl_HistoinFormularioML.estado', 'tbl_HistoinFormularioML.created_at', 'tbl_HistoinFormularioML.updated_at', 'company.razonsocial', 'tbl_mod_legales.nombre', 'company.nit')
        ->leftjoin('company', 'company.id', '=', 'tbl_HistoinFormularioML.id_empresa')
        ->leftjoin('tbl_mod_legales', 'tbl_mod_legales.id_modLegal', '=', 'tbl_HistoinFormularioML.modulo')
        ->where('tbl_HistoinFormularioML.id_histo', $id)
        ->get();
      // dd($id);
    }




    $pdf = \PDF::loadView('RequisitosLegales.Cumplimiento.PDFinformeML', compact('informe'));
    return $pdf->setPaper('a4', 'portrait')->stream('PDFinformeML.pdf');
  }



  public function subirDocML(Request $request)
  {
    //Subir archivo de scan
    $time = time();
    $rutaStore = '';


    if (!File::exists(public_path() . "/archivos/RequisitosLegales/Resultados/" . $request->id_informe . "/Documentos")) {

      File::makeDirectory(public_path() . '/archivos/RequisitosLegales/Resultados/' . $request->id_informe . '/Documentos', 0777, true);
    }
    $documento = $request->file('scanDocument');
    if ($documento) {

      $ext = $documento->getClientOriginalExtension();
      $nomb = 'Documento' . $time . '.' . $ext;
      $ruta = 'archivos/RequisitosLegales/Resultados/' . $request->id_informe . '/Documentos';
      $documento->move($ruta, $nomb);
      $rutaStore = $ruta . "/" . $nomb;
    } else {
      $rutaStore = null;
    }


    $informe = tbl_subirDocML::create([

      'id_reporte' => $request->id_informe,
      'url' => $rutaStore,
      'estado' => 1

    ]);





    // $extencionArchivo = '';
    //         $nombreArchivo = '';
    //         $files = $request->file('scanDocument');


    //             $extencionArchivo = $files->getClientOriginalExtension();
    //             $nameArch=$files->getClientOriginalName();
    //             $Arch2 = str_replace('á','a', $nameArch);
    //             $Arch3 = str_replace('é','e', $Arch2);
    //             $Arch4 = str_replace('í','i', $Arch3);
    //             $Arch5 = str_replace('ó','o', $Arch4);
    //             $Arch6 = str_replace('ú','u', $Arch5);
    //             $Arch7 = str_replace('ñ','n', $Arch6);
    //             $Arch8 = str_replace('Á','A', $Arch7);
    //             $Arch9 = str_replace('É','E', $Arch8);
    //             $Arch10 = str_replace('Í','I', $Arch9);
    //             $Arch11 = str_replace('Ó','O', $Arch10);
    //             $Arch12 = str_replace('Ú','U', $Arch11);
    //             $Arch13 = str_replace('Ñ','N', $Arch12);
    //             // Se crea el encarpetado si no existe
    //            if(!File::exists(public_path() . "/archivos/RequisitosLegales/Resultados/".$request->id_informe."/Documentos")){

    //             File::makeDirectory(public_path().'/archivos/RequisitosLegales/Resultados/'.$request->id_informe.'/Documentos',0777,true);

    //       }
    //             $rutaArchivo='/archivos/RequisitosLegales/Resultados/'.$request->id_informe.'/Documentos';
    //             $nombreArchivo='InformeML'.$request->id_informe.'.'.$Arch13;
    //             $files->move($rutaArchivo,$nombreArchivo);
    //             $rutaDeArchivo='/'.$rutaArchivo.'/'.$nombreArchivo;



    return redirect()->back();
  }

  public function verDocML($id_reporte)
  {


    $repor = DB::table('tbl_subirDocML')
      ->select('url as ruta', 'created_at')
      ->where('id_reporte', $id_reporte)
      ->get();

    return json_encode($repor);
  }

  public function noAplica($id, $tipo, $id_empresa, $id_req, $modulo)
  {

    if ($tipo == 0) {

      $crete = DB::table('tbl_int_criterio')
        ->where('id_tblCriterio', $id)
        ->update(['estado' => 0]);
    } elseif ($tipo == 1) {

      $crete = DB::table('tbl_int_criterio')
        ->where('id_tblCriterio', $id)
        ->update(['estado' => 1]);
    }

    return redirect()->action('RequisitosLegales\RequisitosLegalesController@MedPreventivaForm', [$id_req, $id_empresa, $modulo]);
  }

  //Borrar Archivos del Plan de Usuarios
  public function borrarArPlan($id)
  {
    $Plan = PlanAccion::where('id', $id)->delete();

    $Adjunto = PlanAdjunto::where('id_plan_accion', $id)->delete();

    return redirect()->back();
  }

public function listGraficosLegal($id_empresa)
{
    // Get company data
    $company = DB::table('company')
        ->select('razonsocial', 'cat_riesgos.name')
        ->leftJoin('cat_riesgos', 'cat_riesgos.id', '=', 'company.cat_riesgos')
        ->where('company.id', $id_empresa)
        ->first();

    // Get normas (requisitos legales) with their criteria and compliance data
    $normas = [];
    $modulo = [];
    
    for ($i = 1; $i <= 11; $i++) {
        // Get requisitos legales for this module
        $requisitosLegales = DB::table('tbl_requisitos_legales')
            ->select('tbl_requisitos_legales.id', 'tbl_requisitos_legales.total', 
                    'tbl_tipo_peligro.peligro','tbl_tipo_peligro.id as id_tipo_peligro', 'tbl_requisitos_legales.tipo_norma',
                    'tbl_requisitos_legales.emisor', 'tbl_requisitos_legales.descripcion_norma',
                    'tbl_requisitos_legales.fecha_emision', 'tbl_requisitos_legales.articulos_aplicables',
                    'tbl_requisitos_legales.subclasificacion', 'tbl_requisitos_legales.descripcion_requisito',
                    'tbl_requisitos_legales.estado', 'tbl_int_norma.id_tblNorma')
            ->leftJoin('tbl_int_legales', 'tbl_int_legales.id_norma', '=', 'tbl_requisitos_legales.id')
            ->leftJoin('tbl_int_norma', 'tbl_int_norma.id_norma', '=', 'tbl_requisitos_legales.id')
            ->leftJoin('tbl_tipo_peligro', 'tbl_tipo_peligro.id', '=', 'tbl_requisitos_legales.id_tipo_peligro')
            ->where([
                ['tbl_requisitos_legales.estado', '=', 1],
                ['tbl_int_legales.id_modLegal', $i],
                ['tbl_int_norma.id_empresa', $id_empresa],
                ['tbl_int_norma.tipo', $i]
            ])
            ->groupBy('tbl_int_legales.id_norma')
            ->get();

        foreach ($requisitosLegales as $requisito) {
            // Get criteria for this requisito
        $criterios = DB::table('tbl_criterio_cumplimiento')
            ->select(DB::raw('
                tbl_criterio_cumplimiento.id_criterio_cumplimiento,
                tbl_criterio_cumplimiento.criterio,
                tbl_criterio_cumplimiento.valor,
                tbl_resultados.respuesta
            '))
            ->leftJoin('tbl_int_criterio', 'tbl_int_criterio.id_criterio', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
            ->leftJoin('tbl_resultados', 'tbl_resultados.id_criterio_cumplimiento', '=', 'tbl_criterio_cumplimiento.id_criterio_cumplimiento')
            ->where([
                ['tbl_criterio_cumplimiento.id_requisito_legal', $requisito->id],
                ['tbl_criterio_cumplimiento.estado', 1],
                ['tbl_int_criterio.id_empresa', $id_empresa],
                ['tbl_int_criterio.id_norma', $i],
                ['tbl_criterio_cumplimiento.id_modulo', $i]
            ])
            ->distinct()
            ->get();

            $criteriosData = [];
            $totalPuntaje = 0;
            $puntajeObtenido = 0;
            
            foreach ($criterios as $criterio) {
                $puntaje = $criterio->respuesta == 1 ? $criterio->valor : 0;
                $criteriosData[] = [
                    'id' => $criterio->id_criterio_cumplimiento,
                    'nombre' => $criterio->criterio,
                    'puntaje' => $puntaje,
                    'valor_maximo' => $criterio->valor
                ];
                $totalPuntaje += $criterio->valor;
                $puntajeObtenido += $puntaje;
            }

            // Calculate compliance score (0-5 scale)
            $porcentajeCumplimiento = $totalPuntaje > 0 ? ($puntajeObtenido / $totalPuntaje) * 100 : 0;
            $puntajeEscala = 0;
            if ($porcentajeCumplimiento >= 90) $puntajeEscala = 5;
            elseif ($porcentajeCumplimiento >= 75) $puntajeEscala = 4; 
            elseif ($porcentajeCumplimiento >= 50) $puntajeEscala = 3;
            elseif ($porcentajeCumplimiento >= 25) $puntajeEscala = 2;
            elseif ($porcentajeCumplimiento > 0) $puntajeEscala = 1;

            $normas[] = [
                'id' => $requisito->id,
                'codigo' => $requisito->tipo_norma ?? 'N/A',
                'nombre' => $requisito->descripcion_norma ?? 'Sin descripción',
                'emisor' => $requisito->emisor ?? '',
                'puntaje' => $puntajeEscala,
                'peligro' => $requisito->peligro ?? '',
                'porcentaje_cumplimiento' => round($porcentajeCumplimiento),
                'criterios' => $criteriosData
            ];
        }
        
        $modulo[] = 2;
    }

    return view('RequisitosLegales.listGraficosLegal', compact('company', 'normas', 'id_empresa', 'modulo'));
}
}