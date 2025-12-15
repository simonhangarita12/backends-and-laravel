<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

///////==========ACCESO AL SISTEMA USUARIOS CON LOGEO====================////////
Route::get('/', function () {
	return view('logout');
});

//rutas para mostra página de cierre de sesión
Route::get('/session.expired', function () {
    return view('errors.sesion_expirada');
})->name('session.expired');
//

Route::group(['middleware' => ['session.expired']], function () {

});
Route::get('/chartsOpinion', function () {
	return view('Graficas/graficasOpinion');
});
Route::get('/auth/registerAncla', function () {
	return view('/auth/registerAncla');
});

Route::get('/graficaPruebasPESV', function () {
	return view('Pesv/pruebaGrafica');
});

////******Landing page Bancolombia
Route::get('/bancolombia/index', function () {
	return view('/bancolombia/index');
});
//***Formulario de bancolombia***//
Route::post('/bancolombia', 'Authentication\RegisterControllerWeb@createBancolombia');

Route::get('/fechaprospectoB/{anio}', 'Users\UsersController@fechaprospectoB');
Route::get('/fechapropec/{anio}', 'Users\UsersController@fechapropec');

Route::post('/editProspecto', 'Users\UsersController@editProspecto');

////****** FIN Landing page Bancolombia

Route::get('firma/firma', function () {
	return view('firma/firma');
});

Route::get('lineabasal/index_linea_basal', function () {
	return view('lineabasal/index_linea_basal');
});

Route::get('pruebaInicial/lineaInicial', function () {
	return view('pruebaInicial/lineaInicial');
});

Route::get('getEditpost/Posts/PostController', function () {
	return view('Posts/edit-post');
});

Route::post('/logout', 'Authentication\LoginController@logout');

Route::get('pagomio/{id}/{nfactura}', ['as' => 'pagomio', 'uses' => 'PagomioController@pagomio']);

Auth::routes();
//Route::auth();
//*********Acceso al sistema*****************//
Route::group(['middleware' => 'guest'], function () {
	Route::get('/', function () {
		return redirect('/home');
	});

});

// Consentimiento Puesto de Trabajo (firmas)
Route::post('/consentimiento', [\App\Http\Controllers\PuestoT\ConsentimientoController::class, 'store'])->name('consentimiento.store');


Route::get('/home', 'Authentication\HomeController@index');
Route::get('/dasboardDesarrolloDiario', [
    'as' => 'dasboardDesarrolloDiario',
    'uses' => 'Desarrollo\DesarrolloController@index'
]);
Route::get('/desarrollo/create', [
    'as' => 'desarrollo.create',
    'uses' => 'Desarrollo\DesarrolloController@create'
]);
Route::post('/desarrollo', [
    'as' => 'desarrollo.store',
    'uses' => 'Desarrollo\DesarrolloController@store'
]);
Route::get('/desarrollo/{id}', [
    'as' => 'desarrollo.show',
    'uses' => 'Desarrollo\DesarrolloController@show'
]);
Route::post('/desarrollo/{id}/revision', [
    'as' => 'desarrollo.revision.store',
    'uses' => 'Desarrollo\DesarrolloController@storeRevision'
]);
//**********Acceso al sistema*************//
Route::get('/login', 'Authentication\LoginController@login');
Route::post('/login', 'Authentication\LoginController@postLogin');
//***********logeo enlace************//
Route::get('/enlace', 'Authentication\EnlaceController@enlace');
Route::post('/enlace', 'Authentication\EnlaceController@postEnlace');

//************Registro usuario por web***********//
Route::get('/indexregister', 'Authentication\RegisterControllerWeb@register');
Route::post('/indexregister', 'Authentication\RegisterControllerWeb@createWeb');

//************URL usuarios registrados*****************//
Route::group(['middleware' => 'guest'], function () {

	Route::get('dashboard/home', 'HomeController@index')->name('home');
	Route::get('dashboard/NEWdashboard', 'HomeController@NewHome')->name('NEWdashboard');
	Route::get('dashboard/homeCardio', 'HomeController@indexCardio')->name('homeCardio');
	Route::post('logout', 'Authentication\LoginController@logout');
});

//********************PQRF*********************//

Route::resource('formulario/pqrf', 'Formularios\PqrfController');
Route::get('/infoExtraPqrf/{id_pqrf}', 'Formularios\PqrfController@infoExtra');
Route::get('/pqrfA/{mes}/{anio}', [
		'as' => 'pqrfA',
		'uses' => 'Formularios\PqrfController@fechasPqrf'
]);

//********************Fin PQRF*********************//
//**************Encuestas de  opinión****************//

Route::get('excel/indexOpinion', [
	'as' => 'excel/indexOpinion',
	'uses' => 'Excel\ExcelController@indexOpinion'
]);

Route::get('/graficasINDICAsgsst1/{company}/{id}/{tipoid}/{anioactual}', [
	'as' => '/graficasINDICAsgsst1',
	'uses' => 'indicaSGSST\graficasIndicadoresController@graficasINDICAsgsst1'
]);
Route::get('opinion/create2/{tipo}', [
	'as' => 'opinion/create2',
	'uses' => 'Company\OpinionController@create2'
]);
Route::resource('/opinion', 'Company\OpinionController');
Route::post('/excelFiltro', ['as' => '/excelFiltro', 'uses' => 'Excel\ExcelController@filtroExcel']);
Route::get('dashboardInformes', function () {
	return view('/Graficas/dashboardInformes');
})->name('dashboardInformes');


Route::get('dashboardOpinion', function () {
	return view('/formularios/opinion/dashboardOpinion');
});
Route::get('/opinionEncuesta', [
	'as' => 'opinionEncuesta',
	'uses' => 'Company\OpinionController@encuesta',
]);

//nueva encuesta 2025
Route::get('encuestaCliente', function () {
	return view('/formularios/opinion/encuestaCliente');
});


//**************** Opinion del cliente *******************//
	Route::get('/createCustomer', [
		'as' => 'createCustomer',
		'uses' => 'Company\OpinionController@createCustomer'
	]);
	Route::post('/storeCustomer', [
		'as' => 'storeCustomer',
		'uses' => 'Company\OpinionController@storeCustomer'
	]);


//****************Calificación de servicio al cliente****************************//////
//Ruta Para Busqueda de empresas cuando se seleccione el asesor
Route::get('/searchCompany/{id_asesor}', 'Company\CaliServiClteController@searchCompany');
// Ruta para exportar excel de propuestas pendientes
Route::get('excel/indexControlAsesor', ['as' => 'excel/indexControlAsesor', 'uses' => 'Excel\ExcelController@indexControlAsesor']);
Route::resource('opinionAnalista', 'Company\CaliServiClteController');

//**************************Control llamadas Skype*********************************//
//Route::get('/skype/{id}','Users\SkypeController@show');
Route::get('/homes', 'HomeController@pruebaSkype');
Route::resource('/skype', 'Users\SkypeController');
Route::get('/skype/reloj', function () {
	return view('/skype/reloj');
});
Route::get('/skypeCltAll', ['as' => 'skypeCltAll', 'uses' => 'Users\SkypeController@index1']);

Route::get('/consullamada/{mes}/{anio}', 'Users\SkypeController@consullamada');

//************Descargos*****************//
Route::get('formulario/descargo', 'Formularios\DescargoController@descargo');
Route::get('/createCitacion', 'Formularios\descargo\DescargoController@createCitacion');
Route::get('/changeUser/{id_user}', 'Formularios\\descargo\DescargoController@changeUser');
Route::post('/storeDescargo', 'Formularios\DescargoController@storeDescargo');
Route::get('/createDescargo/{id_citacion}', 'Formularios\DescargoController@createDescargo');
Route::get('/editCitacion/{id_citacion}', 'Formularios\descargo\DescargoController@editCitacion');
Route::post('/updateCitacion', 'Formularios\descargo\DescargoController@updateCitacion');
Route::get('/generateCitacion', 'Formularios\descargo\DescargoController@generateCitacion');
Route::get('/generateActa', 'Formularios\descargo\DescargoController@generateActa');

Route::get('/listDescargo', [
	'as' => 'listDescargo',
	'uses' => 'Formularios\descargo\DescargoController@listDescargo',
]);

Route::get('/createCitacion', [
	'as' => 'createCitacion',
	'uses' => 'Formularios\descargo\DescargoController@createCitacion',
]);

Route::post('/storeCitacion', [
	'as' => 'storeCitacion',
	'uses' => 'Formularios\descargo\DescargoController@storeCitacion',
]);

Route::get('/showCitacion/{id_citacion}', [
	'as' => 'showCitacion',
	'uses' => 'Formularios\descargo\DescargoController@showCitacion',
]);

Route::get('/pdfCitacion/{id}', [
	'as' => 'pdfCitacion',
	'uses' => 'Formularios\descargo\DescargoController@pdfCitacion',
]);

Route::get('/listPregunta', [
	'as' => 'listPregunta',
	'uses' => 'Formularios\descargo\DescargoController@listPregunta',
]);

Route::get('/createPreguntaActa', [
	'as' => 'createPreguntaActa',
	'uses' => 'Formularios\descargo\DescargoController@createPreguntaActa',
]);

Route::post('/storePreguntaActa', [
	'as' => 'storePreguntaActa',
	'uses' => 'Formularios\descargo\DescargoController@storePreguntaActa',
]);

Route::get('/editPreguntaActa/{id}', [
	'as' => 'editPreguntaActa',
	'uses' => 'Formularios\descargo\DescargoController@editPreguntaActa',
]);

Route::post('/updatePregunta/{id}', [
	'as' => 'updatePregunta',
	'uses' => 'Formularios\descargo\DescargoController@updatePregunta',
]);

Route::get('/createActa/{id}', [
	'as' => 'createActa',
	'uses' => 'Formularios\descargo\DescargoController@createActa',
]);

Route::post('/storeActa/{id_descargo}', [
	'as' => 'storeActa',
	'uses' => 'Formularios\descargo\DescargoController@storeActa',
]);

Route::get('/editActa/{id_descargo}/{id_acta}', [
	'as' => 'editActa',
	'uses' => 'Formularios\descargo\DescargoController@editActa',
]);

Route::post('/updateActa/{id_descargo}/{id_acta}', [
	'as' => 'updateActa',
	'uses' => 'Formularios\descargo\DescargoController@updateActa',
]);

Route::get('/editColaborador/{id}/{id_descargo}', [
	'as' => 'editColaborador',
	'uses' => 'Formularios\descargo\DescargoController@editColaborador',
]);

Route::post('/updateColaborador/{id}/{id_descargo}', [
	'as' => 'updateColaborador',
	'uses' => 'Formularios\descargo\DescargoController@updateColaborador',
]);

Route::get('/showActa/{id_descargo}/{id_acta}', [
	'as' => 'showActa',
	'uses' => 'Formularios\descargo\DescargoController@showActa',
]);

Route::get('/pdfActa/{id_descargo}/{id_acta}', [
	'as' => 'pdfActa',
	'uses' => 'Formularios\descargo\DescargoController@pdfActa',
]);

Route::post('/correoDescargo', [
	'as' => 'correoDescargo',
	'uses' => 'Formularios\descargo\DescargoController@correoDescargo',
]);

Route::get('/verEmail/{id_descargo}', [
	'as' => 'verEmail',
	'uses' => 'Formularios\descargo\DescargoController@verEmail',
]);

Route::get('/lisDescargoUser/{id}', [
	'as' => 'lisDescargoUser',
	'uses' => 'Formularios\descargo\DescargoController@lisDescargoUser',
]);


//***************ACTA ENTREGA***************//
Route::resource('/actaEntrega', 'Formularios\ActaEntregaController');
//***************ACTA CERTIFICACIÓN**************//
Route::resource('/actaCertificado', 'Formularios\ActaCertificadoController');

//*********************CAPACITACIONES*******************************//

Route::get('/Capacitaciones/dashboardCapacitaciones', function () {
	return view('/Capacitaciones/dashboardCapacitaciones');
});

Route::get('/consulCapacitacion/{anio}/{id_empresa}', [
	'as' => 'consulCapacitacion',
	'uses' => 'Capacitaciones\CapacitacionesController@consulCapacitacion',
]);
Route::get('/consulCapacitacion1/{anio}/{id_empresa}', [
	'as' => 'consulCapacitacion1',
	'uses' => 'Capacitaciones\CapacitacionesController@consulCapacitacion1',
]);

//**Ruta para ir a formulario de registro de capacitacion**//
Route::get('/register/capacitacion', 'Capacitaciones\CapacitacionesController@register');
//**Crear Capacitación
Route::post('/capacitacion', 'Capacitaciones\CapacitacionesController@create');
//*************Ruta para ir a la lista de capitulos***************************//
Route::get('/capacitacion', 'Capacitaciones\CapacitacionesController@index');
//*************Ruta para ir a información de capacitación para lideres y user Cliente
Route::get('/capacitacions/{id_company}', [
	'as' => 'capacitacions',
	'uses' => 'Capacitaciones\CapacitacionesController@index1',
]);

Route::get('/resultCapacitacionUser/{id_user}', [
	'as' => 'resultCapacitacionUser',
	'uses' => 'Capacitaciones\CapacitacionesController@index2'
]);

Route::post('/capacitacionPdf', [
	'as' => 'capacitacionPdf',
	'uses' => 'Capacitaciones\CapacitacionesController@capacitacionPdf',
]);

Route::post('/capacitacionPersonasPdf', [
	'as' => 'capacitacionPersonasPdf',
	'uses' => 'Capacitaciones\CapacitacionesController@capacitacionPersonasPdf',
]);

Route::post('/listadoCapacitacionesPdf', [
	'as' => 'listadoCapacitacionesPdf',
	'uses' => 'Capacitaciones\CapacitacionesController@listadoCapacitacionesPdf',
]);

Route::post('/listadoCapacitaciones', [
	'as' => 'listadoCapacitaciones',
	'uses' => 'Capacitaciones\CapacitacionesController@listadoCapacitaciones',
]);
//*****************Busqueda de Capacitaciones****************************//
//Route::post('capacitacion/search', ['as' => 'capacitacion/search', 'uses'=>'Capacitaciones\CapacitacionesController@search']);

//*************Ruta para ir al contenido de capacitación************************//
Route::get('/capacitacion/{id_c}/{id_modulo}', 'Capacitaciones\CapacitacionesController@VerCapacitacion');
//*************Ruta para ir al formulario de edición de capacitación************//
Route::get('/editCapacitacion/{id_c}', 'Capacitaciones\CapacitacionesController@edit');
//*************Actualizar capacitación*************************************//
Route::post('/updateCapacitacion', 'Capacitaciones\CapacitacionesController@update');
//*************Inhabilitar Capacitación
Route::get('/disableCapacitacion/{id_cap}', 'Capacitaciones\CapacitacionesController@disable');

//*************Ruta para ir a capcitaciòn de lider************************//
Route::get('/capacitacionLider', 'Capacitaciones\CapacitacionesController@capacitacionLider');

//*************Ruta Para Certificado
Route::get('/certificado/{id}/{id_capacitacion}/{anio}', 'Capacitaciones\CapacitacionesController@certificado');

//*************EXAMENES********************************************//
//*************Ruta para ir a examen 1*****************************//
// Route::resource('/examen1','Examen\ExamenController');
// Route::get('/examen2','Examen\ExamenController@prueba2');
//************Ruta para ir al formulario de crear examen**********//
Route::get('/examen', 'Capacitaciones\ExamenController@register');
//***********Ruta para registrar examen***************************//
Route::post('/examen', 'Capacitaciones\ExamenController@create');
//***********Ruta para listar examenes****************************//
Route::get('listExamen', 'Capacitaciones\ExamenController@list');
//Route::post('/examenPregunta','Examen\ExamenController@createPregunta');
//**********Ruta para listar examen************//
Route::get('/examen/{id_examen}/{zona}',[
 'as'=>'examen',
 'uses'=>'Capacitaciones\ExamenController@viewExam'
]);
//**********Ruta para registrar respuestas*******//
Route::post('/examenRespuesta', ['as' => '/examenRespuesta', 'uses' => 'Capacitaciones\ExamenController@responseExam']);
//**********Ruta para crear pregunta**********//
Route::post('/examenPregunta', ['as' => '/examenPregunta', 'uses' => 'Capacitaciones\ExamenController@createPregunta']);
//**********Ruta para mostrar contenido del examen**********//
Route::get('/contentExamen/{id_examen}', 'Capacitaciones\ExamenController@viewContent');
//**********Recoge el id de la pregunta para mostrar las opciones***//
Route::post('/preguntaRespuesta', ['as' => '/preguntaRespuesta', 'uses' => 'Capacitaciones\ExamenController@preguntaRespuesta']);
//**********Ruta para actualizar las opciones de la pregunta*********//
Route::post('/opcionesPregunta', ['as' => '/opcionesPregunta', 'uses' => 'Capacitaciones\ExamenController@opcionesPregunta']);

Route::post('/descripcionExamen', ['as' => '/descripcionExamen', 'uses' => 'Capacitaciones\ExamenController@descripcionExamen']);

Route::post('/descripcionPregunta', ['as' => '/descripcionPregunta', 'uses' => 'Capacitaciones\ExamenController@descripcionPregunta']);

Route::post('/deletePregunta', ['as' => '/deletePregunta', 'uses' => 'Capacitaciones\ExamenController@deletePregunta']);

Route::post('/numPreguntas', 'Capacitaciones\ExamenController@editNumPreguntas');

Route::get('/capacitacionesList/{id_company}', [
	'as' => 'capacitacionesList',
	'uses' => 'Capacitaciones\CapacitacionesController@capacitacionesList',
]);

Route::get('/capacitacionesLista/{id_company}', [
	'as' => 'capacitacionesLista',
	'uses' => 'Capacitaciones\CapacitacionesController@capacitacionesLista',
]);

Route::get('/createListcapa', [
	'as' => 'createListcapa',
	'uses' => 'Capacitaciones\CapacitacionesController@createListcapa',
]);

Route::get('/VerArchivosCapa/{id_company}/{id_upList}', [
	'as' => 'VerArchivosCapa',
	'uses' => 'Capacitaciones\CapacitacionesController@VerArchivosCapa',
]);

Route::get('/consulcapaArch/{mes}/{anio}/{id_empresa}', [
	'as' => 'consulcapaArch',
	'uses' => 'Capacitaciones\CapacitacionesController@consulcapaArch',
]);

Route::get('/capacitacionesExcel', [
	'as' => 'capacitacionesExcel',
	'uses' => 'Excel\ExcelController@capacitacionesExcel',
]);

//Capacitaciones personalizadas CIA

Route::get('/listcapacitaciones/{id_company}', [
	'as' => 'listcapacitaciones',
	'uses' => 'Capacitaciones\CapacitacionesController@listcapacitaciones',
]);

Route::get('/NewCapacitacionInt/{id_company}', [
	'as' => 'NewCapacitacionInt',
	'uses' => 'Capacitaciones\CapacitacionesController@NewCapacitacionInt',
]);

Route::post('/createCapacitacionCia', [
	'as' => 'createCapacitacionCia',
	'uses' => 'Capacitaciones\CapacitacionesController@createCapacitacionCia',
]);

Route::get('/VerTemarioCapa/{id}/{id_company}', [
	'as' => 'VerTemarioCapa',
	'uses' => 'Capacitaciones\CapacitacionesController@VerTemarioCapa',
]);

Route::post('adjuntEviExamen', [
	'as' => 'adjuntEviExamen',
	'uses' => 'Capacitaciones\CapacitacionesController@adjuntEviExamen',
]);

Route::get('listEvidencias/{id}/{company_id}', [
	'as' => 'listEvidencias',
	'uses' => 'Capacitaciones\CapacitacionesController@listEvidencias',
]);

Route::post('/calificarEvidencia', [
	'as' => 'calificarEvidencia',
	'uses' => 'Capacitaciones\CapacitacionesController@calificarEvidencia',
]);






//fin módulo de Capacitacines

// ******************SISTEMA DE VIGILANCIA EPIDEMIOLOGICA*********************//
// ******************RUTAS DE SISTEMA DE VIGILANCIA EPIDEMIOLOGICA************//
// // ******************Ruta de evaluaciones*************************************//
Route::resource('evaluacion', 'Sve\Evaluacion\EvaluacionController');
// // ******************Ruta de control******************************************//
Route::resource('control', 'Sve\Control\ControlController');
// //******************Ruta de pregunta*****************************************//
Route::resource('pregunta', 'Sve\Control\PreguntaController');
// //******************Ruta para buscar usuario*********************************//
Route::get('/userControl/{documento}', 'Sve\Control\ControlController@searchUser');
// //******************Ruta para validar información del usuario
Route::get('/validInfoUser/{id_user}/{idControl}', 'Sve\Control\ControlController@validInfo');

Route::get('/control3/{datos}', 'Sve\Control\ControlController@control3');

//******************SISTEMA DE VIGILANCIA EPIDEMIOLOGICA VERSIÓN 1*********************//
//******************RUTAS DE SISTEMA DE VIGILANCIA EPIDEMIOLOGICA VERSIÓN 1************//
//******************Ruta de evaluaciones*************************************//
// Ruta para el dashboard de SVE
// Route::get('/opcionesSve/{id_company?}',[
//     'as'=>'opcionesSve',
//     'uses'=>'SVE\EvaluacionController@opcionesSve'
// ]);

// Route::resource('evaluacion', 'SVE\EvaluacionController');

// // Busqueda de usuario para validar datos
Route::get('/validDatosUser/{id_user}', 'SVE\EvaluacionController@validDatosUser');
// //******************Ruta de control******************************************//

Route::get('/verResult/{id_company?}', [
	'as' => 'verResult',
	'uses' => 'SVE\ControlController@SveResultado',
]);

//Inicio de grafica de sistegra previene superior

Route::get('/SveAnual/{id_company}/{anio}', [
	'as' => 'SveAnual',
	'uses' => 'SVE\GraficasSve@SveGraficasDatos',
]);


// //
// // //******************Ruta de pregunta*****************************************//
// Route::resource('pregunta', 'SVE\PreguntaController');
// //******************Ruta para buscar usuario*********************************//
// Route::get('/userControl/{documento}', 'SVE\ControlController@searchUser');
// //******************Ruta para validar información del usuario
// Route::get('/validInfoUser/{id_user}/{idControl}', 'SVE\ControlController@validInfo');
// //**********Ruta para grafica de fumadores**********//
Route::get('/graphic1/{id_company}', [
	'as' => 'graphic1',
	'uses' => 'SVE\ControlController@graphic1',
]);
// //
// // //**********Ruta para grafica de imc**********//
Route::get('/graphic2/{id_company}', [
	'as' => 'graphic2',
	'uses' => 'SVE\ControlController@graphic2',
]);
// //
// // //**********Ruta para grafica de diabetes**********//
Route::get('/graphic3/{id_company}', [
	'as' => 'graphic3',
	'uses' => 'SVE\ControlController@graphic3',
]);
// //
// // //**********Ruta para grafica de edad**********//
Route::get('/graphic4/{id_company}', [
	'as' => 'graphic4',
	'uses' => 'SVE\ControlController@graphic4',
]);
// //
// // //**********Ruta para grafica de levantar peso**********//
Route::get('/graphic5/{id_company}', [
	'as' => 'graphic5',
	'uses' => 'SVE\ControlController@graphic5',
]);
// // //**********Ruta para resultados**********//
// Route::get('/SveResultado','SVE\ControlController@SveResultado');
// //**********Ruta para graficas de resultado**********//
Route::get('/GraphicResultado/{id_user}/{id_company}/{id_resultado}', 'SVE\ControlController@GraphicResultado');
Route::get('/GraphicResultadoIG/{id_user}/{id_company}', 'SVE\ControlController@graficasInferiorGobal');
Route::get('/GraphicResultadoSG/{id_user}/{id_company}', 'SVE\ControlController@graficasSuperiorGobal');
Route::get('/GraphicResultadoI/{id_user}/{id_company}/{id_resultado}', 'SVE\ControlController@graficasInferior');
Route::get('/GraficasMostrar/{id_user}/{id_company}/{id_resultado}', 'SVE\ControlController@graficasMostrar');
// //**********Ruta para exportar a excel resultados principales**********//
Route::get('/exportResultado/{id_company}', [
	'as' => 'exportResultado',
	'uses' => 'Excel\ExcelController@exportResultado',
]);
// // //**********Ruta para exportar a excel resultados secundarios**********//
Route::get('/exportResultado1/{id_user}', [
	'as' => 'exportResultado1',
	'uses' => 'Excel\ExcelController@exportResultado1'
]);

Route::get('/exportResultadoI/{id_user}', [
	'as' => 'exportResultadoI',
	'uses' => 'Excel\ExcelController@exportResultadoI'
]);
// //**********Ruta para generar pdf resultados principales**********//
// Route::get('/pdfResultado','SVE\ControlController@pdfResultado');
// //**********Ruta para generar PDF individual**********//
Route::get('/pdfResultadoUser/{id_user}/{id_comp}', [
	'as' => 'pdfResultadoUser',
	'uses' => 'SVE\ControlController@pdfResultadoUser'
]);
// //**********Ruta para redireccion a vista de reportes**********//
Route::get('/SVEgraficasSupe/{id_company}/{id_user}', [
	'as' => 'SVEgraficasSupe',
	'uses' => 'SVE\ControlController@SVEgraficasSupe',
]);

// //**********Ruta para redireccion a vista de reportes**********//
Route::get('/SVEgraficasInfe/{id_company}/{id_user}', [
	'as' => 'SVEgraficasInfe',
	'uses' => 'SVE\ControlController@SVEgraficasInfe',
]);

// //**********Ruta para redireccion a vista de reportes**********//
Route::get('/SveReportes/{id_company}', [
	'as' => 'SveReportes',
	'uses' => 'SVE\ControlController@SveReportes',
]);

// //**********Ruta para redireccion a vista de graficas de sve**********//
Route::get('/SveGraficas/{id_company}', [
	'as' => 'SveGraficas',
	'uses' => 'SVE\GraficasSve@SveGraficas',
]);

// //**********Ruta para redireccion a vista de graficas de sve Inferior**********//
Route::get('/SveGraficasInferior/{id_company}', [
	'as' => 'SveGraficasInferior',
	'uses' => 'SVE\Inferior\GraficasSveInferior@SveGraficas',
]);

// //**********Ruta para redireccion a vista de graficas generales**********//
Route::get('/SVEGraficasGenerales/{id_company}', [
	'as' => 'SVEGraficasGenerales',
	'uses' => 'SVE\SVEGraficasGenerales@SVEGraficasGenerales',
]);

// //**********Ruta para redireccion a vista de graficas generales rol 1**********//
Route::get('/SVEGraficasGenerales1/{id_company}', [
	'as' => 'SVEGraficasGenerales1',
	'uses' => 'SVE\SVEGraficasGenerales@SVEGraficasGenerales1',
]);

// //**********Ruta para redireccion a vista de graficas generales rol 2**********//
Route::get('/SVEGraficasGenerales29/{id_company}', [
	'as' => 'SVEGraficasGenerales29',
	'uses' => 'SVE\SVEGraficasGenerales@SVEGraficasGenerales29',
]);
Route::get('/SVEGraficasInvestgacion', [
	'as' => 'SVEGraficasInvestgacion',
	'uses' => 'SVE\SVEGraficasGenerales@SVEGraficasInvestgacion',
]);

// //**********Ruta para redireccion a vista de graficas generales rol 10**********//
Route::get('/SVEGraficasGenerales10/{id_company}/{id_user}', [
	'as' => 'SVEGraficasGenerales10',
	'uses' => 'SVE\SVEGraficasGenerales@SVEGraficasGenerales10',
]);

//Rutas de graficas generales para los roles
Route::get('/SveGenerales10/{id_company}/{id}/{ano}', [
	'as' => 'SveGenerales10',
	'uses' => 'SVE\SVEGraficasGenerales@SveGenerales10',
]);

Route::get('/SveGenerales29/{id_company}/{id}/{ano}', [
	'as' => 'SveGenerales29',
	'uses' => 'SVE\SVEGraficasGenerales@SveGenerales29',
]);

Route::get('/SveGenerales1/{id_company}/{id}/{ano}', [
	'as' => 'SveGenerales1',
	'uses' => 'SVE\SVEGraficasGenerales@SveGenerales1',
]);

// //**********Ruta para redireccion a vista de graficas generales**********//
Route::get('/SVEGraficasIndividuales/{id_company}/{id}', [
	'as' => 'SVEGraficasIndividuales',
	'uses' => 'SVE\SVEGraficasIndividuales@SVEGraficasIndividuales',
]);

// //**********Ruta para redireccion a vista de graficas generales**********//
Route::get('/SVEGraficasIndividualesInfe/{id_company}/{id}', [
	'as' => 'SVEGraficasIndividualesInfe',
	'uses' => 'SVE\SVEGraficasIndividualesInfe@SVEGraficasIndividualesInfe',
]);

// // //**********Ruta para exportar excel de sindromes**********//
Route::get('/reporteSindrome/{id_company}', [
	'as' => 'reporteSindrome',
	'uses' => 'Excel\ExcelController@reporteSindrome',
]);
// // //**********Ruta para exportar excel de factores de riesgo**********//
Route::get('/reporteFactor/{id_company}', [
	'as' => 'reporteFactor',
	'uses' => 'Excel\ExcelController@reporteFactor',
]);
// // Capacitaciones SVE
Route::get('/capacitacionSve/{id_company}', [
	'as' => 'capacitacionSve',
	'uses' => 'SVE\ControlController@capacitacionSve',
]);



// *********************INICIA RUTAS DE SVE VERSION (2) *********************************//

Route::resource('evaluacion', 'SVE\testController');

Route::get('infoPersona/{id}', 'SVE\testController@infoPersona');

Route::post('/registroInfoUser', [
	'as' => 'registroInfoUser',
	'uses' => 'SVE\testController@registroInfoUser',
]);

Route::post('/registroInfoUser2', [
	'as' => 'registroInfoUser2',
	'uses' => 'SVE\testController@registroInfoUser2',
]);

Route::get('/opcionesSve/{id_company?}', [
	'as' => 'opcionesSve',
	'uses' => 'SVE\testController@opcionesSve',
]);

Route::get('/dashboardSveMii', [
	'as' => 'dashboardSveMii',
	'uses' => 'SVE\testController@dashboardSveMii',
]);

Route::get('/userForm/{id_tipo}', [
	'as' => 'userForm',
	'uses' => 'SVE\testController@userForm',
]);

Route::post('/createTest', [
	'as' => 'createTest',
	'uses' => 'SVE\testController@createTest',
]);

Route::post('/createTest2', [
	'as' => 'createTest2',
	'uses' => 'SVE\testController@createTest2',
]);

Route::get('/resultHistorico/{id_company}', [
	'as' => 'resultHistorico',
	'uses' => 'SVE\ControlController@resultHistorico',
]);

Route::get('/resultSeguimiento', [
	'as' => 'resultSeguimiento',
	'uses' => 'SVE\Seguimiento\SeguimientoController@resultSeguimiento',
]);
Route::get('/resultSeguimientoCia', [
	'as' => 'resultSeguimientoCia',
	'uses' => 'SVE\Seguimiento\SeguimientoController@resultSeguimientoCia',
]);


Route::post('/enviarcorreo', [
	'as' => 'enviarcorreo',
	'uses' => 'SVE\Seguimiento\SeguimientoController@enviarcorreo',
]);

Route::get('/verCorreo/{id}', [
	'as' => 'verCorreo',
	'uses' => 'SVE\Seguimiento\SeguimientoController@verCorreo',
]);
Route::get('/verCorreoCia/{id}', [
	'as' => 'verCorreoCia',
	'uses' => 'SVE\Seguimiento\SeguimientoController@verCorreoCia',
]);


Route::get('/sveServicioExcel', [
	'as' => 'sveServicioExcel',
	'uses' => 'Excel\ExcelController@sveServicioExcel',
]);

Route::get('/sveCtlEvaluacion', [
	'as' => 'sveCtlEvaluacion',
	'uses' => 'Excel\ExcelController@sveCtlEvaluacion',
]);

//Rutas para reportes PDF 
Route::get('dashboardReportesPdf', [
	'as' => 'dashboardReportesPdf',
	'uses' => 'SVE\ControlController@dashboardReportesPdf',
]);

Route::get('pdfresult/{id_company}', [
	'as' => 'pdfresult',
	'uses' => 'SVE\ControlController@pdfResultado',
]);

Route::get('pdfResultLumbalgia/{id_company}', [
	'as' => 'pdfResultLumbalgia',
	'uses' => 'SVE\ControlController@pdfResultLumbalgia',
]);

Route::get('pdfResultAbc/{id_company}', [
	'as' => 'pdfResultAbc',
	'uses' => 'SVE\ControlController@pdfResultAbc',
]);

Route::get('/sveResultDatePdf', [
	'as' => 'sveResultDatePdf',
	'uses' => 'SVE\ControlController@sveResultDatePdf',
]);
//*****FIN RUTAS SISTEMA DE VIGILANCIA VERSIÓN 2*****//
//*****inicio RUTAS SISTEMA DE VIGILANCIA VERSIÓN 1 miembro inferior*****//


Route::get('/sveOpciones/{id_company?}', [
	'as' => 'sveOpciones',
	'uses' => 'SVE\testController@sveOpciones',
]);

Route::get('/SVE/dashboardSpel', function () {
	return view('/SVE/dashboardSpel');
});

Route::get('/SVE/dashboardCtl', function () {
	return view('/SVE/dashboardCtl');
});

Route::get('/dashboardCapacitacionesOsteo', function () {
	return view('/SVE/dashboardCapacitacionesOsteo');
});

Route::post('registerQuestion', [
	'as' => 'registerQuestion',
	'uses' => 'SVE\Inferior\InferiorController@registerQuestion',
]);

Route::resource('inferior', 'SVE\Inferior\InferiorController');

Route::get('/resultInferior/{id_company?}', [
	'as' => 'resultInferior',
	'uses' => 'SVE\Inferior\InferiorController@resultInferior',
]);

Route::get('/exportResultInferior/{id_company}', [
	'as' => 'exportResultInferior',
	'uses' => 'Excel\ExcelController@exportResultInferior',
]);

Route::post('/pdfresultInferior/{id_company}', [
	'as' => 'pdfresultInferior',
	'uses' => 'SVE\Inferior\InferiorController@pdfresultInferior',
]);

Route::get('/dashboardResult/{id_company}', function ($id_company) {

	return view('/SVE/dashboardResult', compact('id_company'));
});

Route::get('/pdfResultadoMiiUser/{id_user}',[
		'as' => '/pdfResultadoMiiUser',
		'uses' => 'SVE\Inferior\InferiorController@pdfResultadoUser'
	]
);
//*****FIN RUTAS SISTEMA DE VIGILANCIA VERSIÓN 1 miembro inferior*****//
//*****INICIO RUTAS SISTEMA DE VIGILANCIA VERSIÓN 2 miembro inferior*****//


	Route::resource('newInferior', 'SVE\Inferior\newQuestion\NewInferiorController');

	Route::get('/inferior_initial/{id_initial}', [
		'as' =>'/inferior_initial',		
		'uses' => 'SVE\Inferior\newQuestion\NewInferiorController@getInitialQuestion',
	]);

    Route::resource('newInferior', 'SVE\Inferior\newQuestion\NewInferiorController');

	Route::get('/inferior_initial/{id_initial}', [
		'as' =>'/inferior_initial',		
		'uses' => 'SVE\Inferior\newQuestion\NewInferiorController@getInitialQuestion',
	]);

    Route::get('/resultNewInferior/{id_company}', [
		'as' => 'resultNewInferior',
		'uses' => 'SVE\Inferior\newQuestion\NewInferiorController@getResults',
	]);
	Route::get('/dashboardSveMiResult', function () {
	     return view('/SVE/dashboardSveMiResult');
    });


//*****FIN RUTAS SISTEMA DE VIGILANCIA VERSIÓN 2 miembro inferior*****//



//******************RUTAS DE GRAFICAS**********************************

//*************Ruta para grafica 1 de capacitacion************************//

Route::get('/contenidoGraph1/{id_capass}', 'Graficas\GraficasController@grafica1');

//*************Ruta para grafica 2 de capacitacion************************//
Route::get('contentGrafica2/{anio}/{mes}', 'Graficas\GraficasController@grafica2');
//*************Ruta para ir a graficas de facturación**********************//
Route::get('/mcharts', 'Graficas\GraficasController@graficasFacturacion')->name('mcharts');
//*************Ruta para ir a grafica de estados de facturas
Route::get('/gFacturacion1', 'Graficas\GraficasController@grafica1Facturacion')->name('gFacturacion1');
//*************Ruta para ir a graficas de usuarios***********************
Route::get('/graphicsUser', 'Graficas\GraficasController@graficasUser');
//*************Ruta para grafica de usuarios de sistegra*******************
Route::get('graphUserSis', 'Graficas\GraficasController@graficaUsersSistegra');
//*************Ruta para grafica de usuarios realizando capacitación************//
Route::get('graphUserCap', 'Graficas\GraficasController@graficaUsersCapacitacion');
//*************Ruta para grafica de usuarios creados en la web******************//
Route::get('usersNew', 'Graficas\GraficasController@usersNew');
//*************Ruta para grafica de estados de factura por mes******************//
Route::get('graphEstateBill/{anio}/{mes}/{dia}', 'Graficas\GraficasController@graphEstateBill');
//Ruta prueba de carrusel
Route::get('prueba', 'Graficas\GraficasController@pruebaCarousel');
//*************Ruta para grafica de capacitaciones terminadas por mes************//
Route::get('graphCapMonth', 'Graficas\GraficasController@graphCapMonth');
//*************Ruta para grafica de clientes activos y retirados**************//
Route::get('graphClientAct', 'Graficas\GraficasController@graphClientAct');
//*************Ruta para ir a graficas de balances***********************//

//*************Ruta para pruebas de javascript**********************//
Route::get('pruebaJ', 'Graficas\GraficasController@pruebaJ');

//*************Ruta para ir a graficas de capacitación***********************//
Route::get('/graphic', 'Graficas\GraficasController@graficas');

//*************Ruta para ir a graficas del perfil sociodemográfico***********************//
Route::get('/PerfilSD', 'Graficas\GraficasController@PerfilSD');

//*************Ruta para ir a los datos para graficas del perfil sociodemográfico***********************//
Route::get('/totalesPerfilSD/{id_comp}/{anio}', 'Graficas\GraficasController@totalesPerfilSD');

//*************Ruta para ir a graficas del perfil sociodemográfico***********************//
Route::get('/InformesCondi', 'Graficas\GraficasController@InformesCondi');

//*************Ruta para ir a los datos para graficas del perfil sociodemográfico***********************//
Route::get('/InformesCondicionantes/{id_comp}/{id_ciudadS}', 'Graficas\GraficasController@InformesCondicionantes');

//*************Ruta para dashboard Informes**********************//

//**********RUTA PARA GRAFICAS DEL PLANEAR, HACER, VERIFICAR, ACTUAR ***************//
// Sistema de Gestión de Seguridad y Salud en el Trabajo (SG-SST). Subir, Bajar y Ver Evidencia //
Route::get('/graphPlanear/{id_company}', [
	'as' => 'graphPlanear',
	'uses' => 'SVE\ControlController@graphPlanear',
]);

Route::prefix('reportes')->group(function () {

	// Route::get('proyectos', function () {

	//     return view('Graficas.graficasProyectos');
	// });

	Route::get('regresar', function () {
		return redirect()->route('dashboardInformes');
	});

	Route::get('proyectos', 'Graficas\GraficasController@graficaProyectos');

	Route::get('proyectos/{id_sed}', 'Graficas\GraficasController@getDataProyectossed');
	Route::get('proyecto/{id_comp}', 'Graficas\GraficasController@getDataProyectoscom');

	Route::get('contratistas', 'Graficas\GraficasController@graficaContratistas');

	Route::get('saludLaboral/ausentismo', 'Graficas\GraficasController@graficaAusentismo');

	Route::get('totalesAusentismo/{id_comp}/{anio}', 'Graficas\GraficasController@totalesAusentismo');

	Route::get('entidadEmisora/{id_comp}/{id_ent}', 'Graficas\GraficasController@entidadEmisora');

	Route::get('contratos', 'Graficas\GraficasController@GraffContrato')->name('contratos');

	Route::get('SGSST', 'Graficas\GraficasController@graficasSGSST')->name('sgsst');
	Route::get('SGSST', 'Graficas\GraficasController@graficasSGSST')->name('sgsst');

	Route::get('contratos/{id_comp}/{anio}', 'Graficas\GraficasController@getDataContratos');
});

//*************************Recuperacion de password****************//
/* Route::get('password/email','Auth\ForgotPasswordController@forgotPassword');
Route::post('password/email','Auth\ForgotPasswordController@postForgotPassword');
Route::get('password/reset/{resetCode}','Auth\ForgotPasswordController@resetPassword');
Route::post('/password/reset/{email}/{resetCode}','Auth\ForgotPasswordController@postResetPassword');*/

Route::get('password/reset/{resetCode}', 'Auth\ForgotPasswordController@resetPassword');
Route::get('/recuperaUsuario', [
	'as' => 'recuperaUsuario',
	'uses' => 'Auth\RecuperaUserController@recuperarindex',
]);

Route::post('/datoUsuario', [
	'as' => 'datoUsuario',
	'uses' => 'Auth\RecuperaUserController@datoUsuario',
]);
///////==============DASHBOARD==============//

//*********Barra lateral del menù***************//

Route::get('/table', 'Dashboard\DashboardController@index1')->name('table');
Route::get('/form', 'Dashboard\DashboardController@index2')->name('form');
Route::get('/panel', 'Dashboard\DashboardController@index3')->name('panel');
Route::get('/buttons', 'Dashboard\DashboardController@index4')->name('buttons');
Route::get('/notifications', 'Dashboard\DashboardController@index5')->name('notifications');
Route::get('/typography', 'Dashboard\DashboardController@index6')->name('typography');
Route::get('/icons', 'Dashboard\DashboardController@index7')->name('icons');
Route::get('/grid', 'Dashboard\DashboardController@index8')->name('grid');
Route::get('/blank', 'Dashboard\DashboardController@index9')->name('blank');
Route::get('/documentation', 'Dashboard\DashboardController@index10')->name('documentation');
Route::get('/widgets/panel', 'Dashboard\DashboardController@index11')->name('panel');
Route::get('/widgets/progress', 'Dashboard\DashboardController@index12')->name('progress');
Route::get('/sistassets/img', 'Dashboard\DashboardController@index13')->name('Pagos-Preautorizados.pdf');
Route::get('formularios/indexFormulario', 'Dashboard\DashboardController@index14')->name('indexFormulario');
//Route::get('debito/newdebito', 'Dashboard\DashboardController@index15')->name('newdebito');
//Route::get('debito/listdebito', 'Dashboard\DashboardController@index16')->name('listdebito');
Route::get('debito/novedadesbanco', 'Dashboard\DashboardController@index17')->name('novedadesbanco');
Route::get('debito/facturacionbanco', 'Dashboard\DashboardController@index18')->name('facturacionbanco');
Route::get('debito/cobrosbanco', 'Dashboard\DashboardController@index19')->name('cobrosbanco');
Route::get('/formularios', 'Dashboard\DashboardController@index20')->name('formularios');
Route::resource('/debito', 'Debito\DebitoController');
Route::get('/editNewDebito/{company_id}', [
    'as' => 'editNewDebito',
    'uses' => 'Debito\DebitoController@editNewDebito',
]);
Route::post('/updateNewDebito/{id}',[
	'as'=>'updateNewDebito',
	'uses'=>'Debito\DebitoController@updateNewDebito'
]);
Route::get('/newDebitoPDF/{company_id}', [
    'as' => 'newDebitoPDF',
    'uses' => 'Debito\DebitoController@newDebitoPDF',
]);


//***************Manuales y guías de uso**************//
Route::get('/manualesyguiasU', 'Formularios\AyudaController@manualesyguiasU');


//****busqueda de Listado Empresas con pago debito por fechas---***//
Route::get('/debitoF/{anio}', [
	'as' => 'debitoF',
	'uses' => 'Debito\DebitoController@debitoF'
]);

/////========FORMULARIOS=====///////////

//**********busqueda de formularios************//
Route::post('dashboard/search', ['as' => 'dashboard/search', 'uses' => 'dashboard\DashboardController@search']);

//******formulario contratoTTF*************//
Route::get('/contratoTTF/contratoTTF', 'Formularios\ContratoTTFController@index1')->name('formularioContratoTTF');
Route::get('/contratoTTF/contratoTTF/{id}', 'Formularios\ContratoTTFController@pdf');
Route::resource('/contratoTTF', 'Formularios\ContratoTTFController');

//******formulario contratoTIA******//
Route::get('/contratoTia/contratoTia', 'Formularios\ContratoTIAController@index1')->name('formularioContratoTia');
Route::get('/contratoTia/contratoTia/{id}', 'Formularios\ContratoTIAController@pdf');
Route::resource('/contratoTia', 'Formularios\ContratoTIAController');

//**********listado de pago por cliente***************//
Route::get('facturacion/index1', ['as' => 'facturacion/listpagoCliente', 'uses' => 'Debito\FacturacionController@index1']);

//******** PROPUESTA  DE SERVICIO************//

Route::get('/contratos/propuesta/condicionesUso', function () {
	return view('/contratos/propuesta/condicionesUso');
});

Route::get('/newsContrato', 'Contratos\ContratoController@createContrato');

/*Route::get('propuesta/pdf/{id}',['as' => 'propuesta/pdf', 'uses'=>'Contratos\PropuestaServicioController@pdf']);*/
Route::resource('/propuesta', 'Contratos\PropuestaServicioController');
//***************Lista de Propuestas aceptadas********************//
Route::get('/propuestaConf', 'Contratos\PropuestaServicioController@propuestaConfirmada');
Route::post('/aceptoPropuesta', 'Contratos\PropuestaServicioController@propuestaConfirmada');
//***************crear empresa de propuesta**********************//
Route::post('/storeCompanyPropuesta', 'Contratos\PropuestaServicioController@storeCompanyPropuesta');
//***************crear usuario de propuesta**********************//
Route::post('/storeUserPropuesta', 'Contratos\PropuestaServicioController@storeUserPropuesta');
//**************Subir adjunto de la propuesta*********************//
Route::post('/adjuntPropuesta', 'Contratos\PropuestaServicioController@adjuntoPropuesta');
//**************Subir Contrato firmado en empresa*****************//
Route::post('/adjuntContrato', 'Contratos\ContratoController@adjuntoContrato');

Route::get(
	'/propuestaProspecto/{id_user}',
	'Contratos\PropuestaServicioController@createProspecto'
);

Route::get('/searchPropuesta/{id_propuesta}', 'Contratos\PropuestaServicioController@searchPopuesta');
//*************Rutas de informe gerencial de propuestas**********************//

Route::get('/pdfTerminosCondiciones2022', [
	'as' => 'pdfTerminosCondiciones2022',
	'uses' => 'Dashboard\DashboardController@pdfTerminosCondiciones2022',
]);

// Ruta para lista de propuestas pendientes
Route::get('/propuestaResume', 'Contratos\PropuestaServicioController@propuestaResume');

//Ruta para mostrar los correos registrados
Route::get('/urlcorreo/{idpropuesta}', 'Contratos\PropuestaServicioController@urlcorreo');

//Ruta para mostrar los archivos registrados
Route::get('/urlarchivo/{idpropuesta}', 'Contratos\PropuestaServicioController@urlarchivo');

// Ruta para lista de propuestas aceptadas
Route::get('/propuestaConfirmResume', 'Contratos\PropuestaServicioController@propuestaConfirmResume');
Route::post('/aceptoPropuesta', 'Contratos\PropuestaServicioController@aceptoPropuesta');

// Ruta para exportar excel de propuestas pendientes
Route::get('/propuestaConfirmResume', 'Excel\ExcelController@exportPropuestaEnviada');

//****************CONTRATOS************************//

// Ruta para ir al formulario de creación de contrato
Route::get('propuestaContrato/{id_propuesta}', 'Contratos\ContratoController@propuestaContrato');

Route::resource('/contrato', 'Contratos\ContratoController');
//**************Contratos firmados y aceptados desde plataforma*******************************//
Route::get('/contratoConf', 'Contratos\ContratoController@contratoConf');
Route::post('/aceptoContrato', 'Contratos\ContratoController@aceptoContrato');

Route::get('/contratos/contrato/AceptacionTerminosCondicionesServicios', function () {
	return view('/contratos/contrato/AceptacionTerminosCondicionesServicios');
});
Route::get('/contratos/contrato/AceptacionTerminosCondicionesServiciosAnual', function () {
	return view('/contratos/contrato/AceptacionTerminosCondicionesServiciosAnual');
});
Route::get('/contratos2/contrato/AceptacionTerminosCondicionesServicios2', function () {
	return view('/contratos/contrato/AceptacionTerminosCondicionesServicios2');
});
//Prueba de edit
Route::get('/contratoUp/{id}', 'Contratos\ContratoController@editCont');
Route::get('/createNewProducto', 'Contratos\ContratoController@createNewProducto');
Route::post('storeProducto', 'Contratos\ContratoController@storeProducto');

Route::get('/pdfContrato1', 'Contratos\ContratoController@pdfContrato1');
Route::get('/pruebaFirma', 'Contratos\ContratoController@pruebaFirma');

//Prueba de anexo de contrato
Route::get('/anexoContra/{id_cont}', 'Contratos\ContratoController@anexoContra');

//*******************Rutas para informe de gerencia****************//

// Ruta para informe de contratos enviados
Route::get('/resumeContratosEnviados', 'Contratos\ContratoController@resumeContratosEnviados');

// Ruta para informe de contratos confirmados
Route::get('/resumeContratosConfirmados', 'Contratos\ContratoController@resumeContratosConfirmados');
Route::get('/contratoIP/{mes}/{anio}', 'Contratos\ContratoController@fechasContratoIP');

//****busqueda de contratos por fechas---***//

Route::get('/contratoIP/{mes}/{anio}', 'Contratos\ContratoController@fechasContratoIP');

//****busqueda de propuesta por fechas---***//

Route::get('/propuestaA/{anio}', [
	'as' => 'propuestaA',
	'uses' => 'Contratos\PropuestaServicioController@fechasPropuestas'
]);

Route::get('/propuestasAcep/{anio}', [
	'as' => 'propuestasAcep',
	'uses' => 'Contratos\PropuestaServicioController@fechasPropuestasAcep'
]);

//********************New client seguimineto***************//
Route::get('/newClientConsultafechas/{mes}/{anio}', 'Contratos\NewClientController@consultafechas');

Route::resource('/newClient', 'Contratos\NewClientController');
Route::get('/newClientExport', ['as' => 'newClientExport', 'uses' => 'Excel\ExcelController@newClientExport']);

Route::post('/correonewClient', [
	'as' => 'correonewClient',
	'uses' => 'Contratos\NewClientController@correonewClient',
]);

Route::get('/listEmailClient/{id}', [
	'as' => 'listEmailClient',
	'uses' => 'Contratos\NewClientController@listEmailClient'
]);


//gráficas de contratos y new client
Route::get('/graficasContratos', ['as' => 'graficasContratos', 'uses' => 'Contratos\GraficasContratoController@graficasContratos']);
Route::get('/graficasNewClient/{mes}/{anio}', ['as' => 'graficasNewClient', 'uses' => 'Contratos\GraficasContratoController@graficasNewClient']);
Route::get('/graficasVentaComercial/{anio}', ['as' => 'graficasVentaComercial', 'uses' => 'Contratos\GraficasContratoController@ventaComercial']);

//********/*Fin New cliente*******
//exportar contratos
Route::get('contratosIP', 'Excel\ExcelController@contratosIP');

// fin exportación

//********************PROPUESTA PSICOSOCIAL***************************//
Route::resource('/propuestaPsicosocial', 'Contratos\PropuestaPsicosocialController');

Route::get('/propuestaPsicosocialConfirmada', 'Contratos\PropuestaPsicosocialController@indexConfirm');

// Ruta para ir a formulario de crear empresa de propuesta psicosocial
Route::get('/companyPropuestaPsicosocial/{id_prop}', 'Contratos\PropuestaPsicosocialController@createCompany');

// Ruta para registrar empresa de la propuesta psicosocial
Route::post('/companyPropuestaPsicosocial', 'Contratos\PropuestaPsicosocialController@storeCompany');

// ********Rutas de informe gerencial de propuestas psicosociales*********//
// Ruta para informe de propuestas psicosociales enviadas
Route::get('/resumePsicosocialEnviadas', 'Contratos\PropuestaPsicosocialController@resumePsicosocialEnviadas');

// Ruta parainforme de propuestas psicosociales aceptadas
Route::get('/resumePsicosocialConfirm', 'Contratos\PropuestaPsicosocialController@resumePsicosocialConfirm');

// Pruebas JSON dataTable
Route::get('/pruebaDataTable', 'Contratos\PropuestaPsicosocialController@dataTable');

Route::get('/jsonDataTable', 'Contratos\PropuestaPsicosocialController@jsonDataTable');

//******** formulario contratoprestaciones************//

Route::resource('/contratoprestacion', 'Formularios\ContratoprestacionController');

//******** formulario contratoservicios************//

Route::resource('/contratoservicios', 'Formularios\ContratoserviciosController');

//***************Ayuda y respuesta**************//
Route::get('/ayudas', 'Formularios\AyudaController@indexVentana');


//******** formulario contratoLabor************//
Route::get('/contratolabor', [
	'as' => 'contratolabor',
	'uses' => 'Formularios\contratoLabor\contratoLaborController@indexcontratolabor',
]);

Route::get('/newcontratoLabor/{id}', [
	'as' => 'newcontratoLabor',
	'uses' => 'Formularios\contratoLabor\contratoLaborController@createcontratolabor',
]);

Route::post('/storerecontratolabor', [
	'as' => 'storerecontratolabor',
	'uses' => 'Formularios\contratoLabor\contratoLaborController@storerecontratolabor',
]);

Route::get('/viewcontratoLabor/{id}', [
	'as' => 'viewcontratoLabor',
	'uses' => 'Formularios\contratoLabor\contratoLaborController@viewcontratoLabor',
]);

Route::get('/editcontratoLabor/{id}', [
	'as' => 'editcontratoLabor',
	'uses' => 'Formularios\contratoLabor\contratoLaborController@editcontratoLabor',
]);

Route::put('/updatecontratoLabor/{id}', [
	'as' => 'updatecontratoLabor',
	'uses' => 'Formularios\contratoLabor\contratoLaborController@updatecontratoLabor',
]);

Route::post('/correocontratoLabor', [
	'as' => 'correocontratoLabor',
	'uses' => 'Formularios\contratoLabor\contratoLaborController@correocontratoLabor',
]);

Route::get('/listEmail/{id}', [
	'as' => 'listEmail',
	'uses' => 'Formularios\contratoLabor\contratoLaborController@listEmail'
]);

Route::get('/contratoLaborPdf/{id}', [
	'as' => 'contratoLaborPdf',
	'uses' => 'Formularios\contratoLabor\contratoLaborController@contratoLaborPdf',
]);

Route::get('/contratosExcel',[
	'as'=>'contratosExcel',
	'uses'=>'Excel\ExcelController@contratosExcel'
]);

Route::post('/avisoFinContrato',[
	'as'=>'avisoFinContrato',
	'uses'=>'Contratos\ContratoController@avisoFinContrato'
]);

/////========FIN FORMULARIOS=====///////////

//***********Debito******************//
Route::get('debito/formautorizacion', 'Debito\DebitoController@pdf');
Route::post('debito/search', ['as' => 'debito\search', 'uses' => 'Debito\DebitoController@search']);
//Route::post('debito/index1', ['as' => 'debito/index1', 'uses'=>'Debito\DebitoController@index1']);

//***********rutas de facturas para clientes***************************//
Route::resource('facturacion', 'Debito\FacturacionController');
Route::get('/listapagoano/{anio}', 'Debito\FacturacionController@listapagoano');
Route::get('/facturacion/updateRe', ['as' => 'facturacion/updateRe', 'uses' => 'Debito\FacturacionController@updateRe']);
Route::get('/facturacion/editRe/{id}', ['as' => 'facturacion/editRe', 'uses' => 'Debito\FacturacionController@editRe']);

//****busqueda de facturas por fechas---***//

Route::get('/facturasM/{mes}/{anio}', [
	'as' => 'facturasM',
	'uses' => 'Debito\FacturacionController@facturasM'
]);

//---------------------------FIN ACCESO AL SISTEMA USUARIOS CON LOGIN------------------------------------------------//

//*********************LÍNEA BASAL*******************************//
//**Ruta para ir a formulario de registro de SISTEMA DE GESTIÓN**//
Route::get('/register/LineaBasal', 'SST\LineaBasalController@register');
Route::get('/index/LineaBasal', 'SST\LineaBasalController@index');
Route::get('/Planear/LineaBasal/{id}', 'SST\LineaBasalController@indexPlanear');
Route::get('/Hacer/LineaBasal/{id}', 'SST\LineaBasalController@indexHacer');
Route::get('/Verificar/LineaBasal/{id}', 'SST\LineaBasalController@indexVerificar');
Route::get('/Actuar/LineaBasal/{id}', 'SST\LineaBasalController@indexActuar');
Route::post('/lineaBasal', 'SST\LineaBasalController@create');
Route::get('/editlineaBasal/{id}', 'SST\LineaBasalController@edit');
Route::put('/updatelineaBasal/{id}', 'SST\LineaBasalController@update');

Route::get('/calificarValores', 'SST\LineaBResultadoController@indexList'); //carpetas de línea basal
Route::get('/auditar/{id}', 'SST\LineaBResultadoController@showAuditor'); //carpetas de línea basal
Route::get('/boardauditar/{id}', 'SST\LineaBResultadoController@showboardauditar'); //carpetas de línea basal
Route::get('/evaluar/{id}/{accion}/{id_company}', 'SST\LineaBResultadoController@show');
Route::get('/calificar', 'SST\LineaBResultadoController@calificar');
Route::get('/caliPlanear/{id}', 'SST\LineaBResultadoController@showRPlanear');
Route::get('/caliHacer/{id}', 'SST\LineaBResultadoController@showRHacer');
Route::get('/caliVerificar/{id}', 'SST\LineaBResultadoController@showRVerificar');
Route::get('/caliActuar/{id}', 'SST\LineaBResultadoController@showRActuar');
Route::post('/valorar', 'SST\LineaBResultadoController@create');
Route::post('/calificAsesor/{id_lbd}', 'SST\LineaBResultadoController@update'); //calificación del auditor
Route::get('/result/{id_company}', [
	'as' => 'result',
	'uses' => 'SST\LineaBResultadoController@result',
]);
Route::get('/resultPdf/{id_company}', 'SST\LineaBResultadoController@resultPdf');
Route::get('/reportGenerate/{id_company}', 'SST\LineaBResultadoController@reportGenerate');
Route::get('/informeCalificacion/{id_company}', 'SST\LineaBResultadoController@informeCalificacion');
Route::post('/storeInformeCalificacion', 'SST\LineaBResultadoController@storeInformeCalificacion');
Route::get('/listInfomeCalificacion/{id_company}', 'SST\LineaBResultadoController@listInfomeCalificacion');
Route::get('/editInfome/{id_informe}', 'SST\LineaBResultadoController@editInfome');
Route::post('/updateInforme', 'SST\LineaBResultadoController@updateInforme');
Route::get('/pdfInforme/{id_informe}', 'SST\LineaBResultadoController@pdfInforme');
Route::post('/uploadDocumentInforme', 'SST\LineaBResultadoController@uploadDocumentInforme');
// Ruta para mostrar plan de trabajo
Route::get('/workPlan/{id_company}', [
	'as' => 'workPlan',
	'uses' => 'SST\LineaBResultadoController@workPlan',
]);

//--------------------Rutas para mostrar las preguntas---------------------------------------------//
Route::post('/createAnswer', ['as' => '/createAnswer', 'uses' => 'SST\LineaBResultadoController@createAnswer']);
Route::post('/createAnswerWeb', ['as' => '/createAnswerWeb', 'uses' => 'SST\LineaBResultadoController@createAnswerWeb']);
Route::get('/formularioTestInicial', 'SST\LineaBResultadoController@registerTest');
Route::post('/formularioTestInicial', 'SST\LineaBResultadoController@createWebTest');

//-----------------Rutas Linea Basal Inicial--------------------------------//
Route::get('/planearLineaBasal', 'SST\LineaBasalWebController@indexPlanear');
Route::get('/HacerLineaBasal', 'SST\LineaBasalWebController@indexHacer');
Route::get('/VerificarLineaBasal', 'SST\LineaBasalWebController@indexVerificar');
Route::get('/ActuarLineaBasal', 'SST\LineaBasalWebController@indexActuar');
Route::get('/lineaInicial', 'SST\LineaBasalWebController@indexInicial');
Route::get('/resultadoFinalPrueba', 'SST\LineaBResultadoController@indexFinal');
Route::get('/FormularioTest', 'users\UsersController@indexUserTest');

//---------------------Rutas Linea Basal Inicial WEB ------------------------//
Route::get('/formularioTestInicial', 'SST\LineaBasalWebController@indexInicialWeb');
Route::get('/planearLineaBasalWeb', 'SST\LineaBasalWebController@indexPlanearWeb');
Route::get('/HacerLineaBasalWeb/{email_user}', 'SST\LineaBasalWebController@indexHacerWeb');
Route::get('/VerificarLineaBasalWeb/{email_user}', 'SST\LineaBasalWebController@indexVerificarWeb');
Route::get('/ActuarLineaBasalWeb/{email_user}', 'SST\LineaBasalWebController@indexActuarWeb');
Route::get('/resultadoFinalPruebaWeb', 'SST\LineaBResultadoController@indexFinalWeb');
Route::get('/redirectFormularioTest/{id}', 'SST\LineaBResultadoController@redirectFormularioTest');
Route::get('pdfFinalPruebaWeb', [
	'as' => 'pdfFinalPruebaWeb',
	'uses' => 'SST\LineaBResultadoController@pdfFinalPruebaWeb'
]);

//***********************************CRUD de las preguntas del Test ***********************************//

Route::resource('preguntas', 'SST\LineaBasalController');

//-----------Sistema de Gestión de Seguridad y Salud en el Trabajo (SG-SST) carga de evidencias----------//

Route::get('/SistemaSGSS/{id_company}', [
	'as' => 'SistemaSGSS',
	'uses' => 'SST\SGSSTFileController@showindex',
]);

Route::get('/SistemaSGSST', [
	'as' => 'SistemaSGSST',
	'uses' => 'SST\SGSSTFileController@index',
]);
Route::get('/PlanearSGFile/{id_company}', [
	'as' => 'PlanearSGFile',
	'uses' => 'SST\SGSSTFileController@showPlanear',
]);

Route::get('/HacerSGFile/{id_company}', [
	'as' => 'HacerSGFile',
	'uses' => 'SST\SGSSTFileController@showHacer',
]);
Route::get('/VerificarSGFile/{id_company}', [
	'as' => 'VerificarSGFile',
	'uses' => 'SST\SGSSTFileController@showVerificar',
]);

Route::get('/PlanearSGFileD/{id_company}/{numeral}', [
	'as' => 'PlanearSGFileD',
	'uses' => 'SST\SGSSTFileController@storePlanear',
]);
Route::get('/HacerSGFileD/{id_company}/{numeral}', [
	'as' => 'HacerSGFileD',
	'uses' => 'SST\SGSSTFileController@storeHacer',
]);
Route::get('/VerificarSGFileD/{id_company}/{numeral}', [
	'as' => 'VerificarSGFileD',
	'uses' => 'SST\SGSSTFileController@storeVerificar',
]);
Route::get('/ActuarSGFileD/{id_company}/{numeral}', [
	'as' => 'ActuarSGFileD',
	'uses' => 'SST\SGSSTFileController@storeActuar',
]);
Route::post('/adjuntEvidencia', [
	'as' => 'adjuntEvidencia',
	'uses' => 'SST\SGSSTFileController@create',
]);

Route::post('/adjuntEvidenciaSS', [
	'as' => 'adjuntEvidenciaSS',
	'uses' => 'SST\SGSSTFileController@updateFileSS',
]);

Route::post('/adjuntEvidenciaedit', [
	'as' => 'adjuntEvidenciaedit',
	'uses' => 'SST\SGSSTFileController@adjuntEvidenciaedit',
]);
Route::get('/ActuarSGFile/{id_company}', [
	'as' => 'ActuarSGFile',
	'uses' => 'SST\SGSSTFileController@showActuar',
]);
Route::get('/editFile/{id_file}', [
	'as' => 'editFile',
	'uses' => 'SST\SGSSTFileController@editFile',
]);

Route::post('/editEstado', [
	'as' => 'editEstado',
	'uses' => 'SST\SGSSTFileController@editEstado',
]);

Route::post('/updateFile', [
	'as' => 'updateFile',
	'uses' => 'SST\SGSSTFileController@updateFile',
]);
Route::get('/editFileAudi/{id_file}', [
	'as' => 'updateFile',
	'uses' => 'SST\SGSSTFileController@editFileAudi',
]);
Route::get('/editFileAudiS/{id_lbd}', [
	'as' => 'editFileAudiS',
	'uses' => 'SST\SGSSTFileController@editFileAudiS',
]);
Route::get('/editFileAudiD/{id_file}/{id_item}', [
	'as' => 'editFileAudiD',
	'uses' => 'SST\SGSSTFileController@editFileAudiD',
]);
Route::get('/editFileAudiSD/{id_lbd}', [
	'as' => 'editFileAudiSD',
	'uses' => 'SST\SGSSTFileController@editFileAudiSD',
]);
Route::post('/auditarFileAudi', [
	'as' => 'auditarFileAudi',
	'uses' => 'SST\SGSSTFileController@createFileAudi',
]);
Route::post('/auditarFileAudiS/{id_lbd}', [
	'as' => 'auditarFileAudiS',
	'uses' => 'SST\SGSSTFileController@updateFileAudiS',
]);
//Muestra el resultado de la auditoria y plan de accion a realizar por el cliente y asesor//
// Route::get('/boardaccion/{id}','SST\SGSSTFileController@showboardaccion');
Route::get('/boardaccion/{id}', [
	'as' => 'boardaccion',
	'uses' => 'SST\SGSSTFileController@showboardaccion',
]);
Route::get('/PlanearAccion/{id}', [
	'as' => 'PlanearAccion',
	'uses' => 'SST\SGSSTFileController@storeAcPlanear',
]);
Route::get('/HacerAccion/{id}', [
	'as' => 'HacerAccion',
	'uses' => 'SST\SGSSTFileController@storeAcHacer',
]);
Route::get('/VerificarAccion/{id}', [
	'as' => 'VerificarAccion',
	'uses' => 'SST\SGSSTFileController@storeAcVerificar',
]);
Route::get('/ActuarAccion/{id}', [
	'as' => 'ActuarAccion',
	'uses' => 'SST\SGSSTFileController@storeAcActuar',
]);
// Calificación del Item
Route::post('/qualifyItem', [
	'as' => 'qualifyItem',
	'uses' => 'SST\SGSSTFileController@qualifyItem',
]);

Route::get('/SST/SistemaGestion/calificacionItem', function () {
	return view('/SST/SistemaGestion/calificacionItem');
});

//*********************CONTROL DE VISITAS*******************************//
//Route::post('newVisitas','Users\CtlVisitasController@create');
Route::resource('/visitas', 'Users\CtlVisitasController');

//---------------------------FIN ZONA COMERCIAL------------------------------------------------//

//---------------------------Empresas asociadas a asesor----------------------------------
Route::get('/asesoriaCompany', 'Company\CompanyController@asesoriaCompany');
Route::get('/editAsesoriaCompany/{id_company}', 'Company\CompanyController@editAsesoriaCompany');
Route::post('updateAsesorCompany/{id}', [
	'as' => 'updateAsesorCompany',
	'uses' => 'Company\CompanyController@updateAsesorCompany'
]);

Route::get('/userAsesoriaCompany', 'Company\CompanyController@userAsesoriaCompany');
Route::get('/numUsersComp/{id_company}', 'Company\CompanyController@numUsersComp');

//----------------------------------------------------------------------------------------

/////////////======ZONA ADMINISTRATIVA=====//////////////////
//Admin Empresas
//*********Rutas para el recurso company*************//


	//*********Roles***************************************//
	if (['middleware' == ['admin']]) {
		Route::post('roles/search', ['as' => 'roles/search', 'uses' => 'Company\RolController@search']);
		Route::resource('roles', 'Company\RolController');

		//*********Categoria de riesgos laborales**************//

		Route::resource('catriesgos', 'Company\CatriesgosController');

		//*********Productos de venta**************//

		Route::resource('productoVenta', 'Company\ProductoVentaController');

		Route::get('company/prospectCompany', ['as' => 'company/prospectCompany', 'uses' => 'Company\CompanyController@prospectCompany']);
		Route::get('company/supendedCompany', ['as' => 'company/supendedCompany', 'uses' => 'Company\CompanyController@supendedCompany']);

		//*********Url**************//

		Route::resource('arl', 'Company\ArlController');
		Route::resource('eps', 'Company\EpsController');
		Route::get('/neweps', 'Company\EpsController@index1');

		//*********Departamento**************//

		Route::resource('departamento', 'Company\DepartamentoController');
	}
	//*********Usuarios**********************************//
	Route::get('/register', 'Authentication\RegistrationController@register');
	Route::post('/register', [
		'as' => 'register',
		'uses' => 'Authentication\RegistrationController@postRegister'
	]);
	//Route::get('/activate/{email}/{activationCode}','Authentication\ActivationController@activate');
	Route::get('/userS', 'Users\UsersController@indexuseSistegra');
	Route::resource('users', 'Users\UsersController');
	Route::get('/userN', 'Users\UsersController@indexUserNew');
	Route::get('/userE', 'Users\UsersController@indexUserEvento');
	//*********Usuarios Formación***************
	Route::get('/usersFormation', 'Users\UsersController@indexUserFormation');
	Route::post('/newUsersFormation', 'Excel\ExcelController@importUsersFormation');

	Route::get('/listusersOut/{company_id}', [
		'as' => 'listusersOut',
		'uses' => 'Users\UsersController@indexOut'
	]);

	//// anti-caché  las ruta de subida de firma desde perfil

	Route::middleware(function ($request, $next) {
		return $next($request)
			->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
			->header('Pragma', 'no-cache')
			->header('Expires', 'Sat, 01 Jan 3000 00:00:00 GMT');
	})->group(function () {
	
		Route::post('/recordFirmUserAuth', [
			'as' => 'recordFirmUserAuth',
			'uses' => 'Users\UsersController@recordFirmUserAuth'
		]);
	
	});

	//Route::post('users/search', ['as' => 'users/search', 'uses'=>'Users\UsersController@search']);

// Menú para poder los datos personales del empleado por módulo
Route::get('/verDatoUsers/{id_users}', [
	'as' => 'verDatoUsers',
	'uses' => 'Users\UsersController@verDatoUsers',
 ]);

Route::get('/sLausentismoUsers/{id_users}/{tipo}', [
	'as' => 'sLausentismoUsers',
	'uses' => 'Users\UsersController@sLausentismoUsers',
]);

Route::get('/eppUsers/{id_users}/{tipo}', [
	'as' => 'eppUsers',
	'uses' => 'Users\UsersController@eppUsers',
]);

Route::get('/capacitacionesUsers/{id_users}/{tipo}', [
	'as' => 'capacitacionesUsers',
	'uses' => 'Users\UsersController@capacitacionesUsers',
]);

Route::get('/sveUsers/{id_users}/{tipo}', [
	'as' => 'sveUsers',
	'uses' => 'Users\UsersController@sveUsers',
]);

Route::get('/aptUsers/{id_users}', [
	'as' => 'aptUsers',
	'uses' => 'Users\UsersController@aptUsers',
]);

Route::get('/desacargosUsers/{id_users}', [
	'as' => 'desacargosUsers',
	'uses' => 'Users\UsersController@desacargosUsers',
]);
// Fin menú para poder los datos personalizados del empleado por módulo

Route::resource('tipoContrato', 'Company\tipoContratoController');


//********* listado de empresas con SVE********
Route::get('/listcompanySVE', [
	'as' => 'listcompanySVE',
	'uses' => 'Company\CompanyController@companySVE',
]);



//--------------------------FIN ZONA ADMINISTRATIVA--------------------------------------------//

/////////////////////=======ZONA Mixta=======///////////////
Route::resource('company', 'Company\CompanyController');
Route::get('indexSearch', ['as' => 'company/search', 'uses' => 'Company\CompanyController@indexSearch']);
Route::post('company/search', ['as' => 'company/search', 'uses' => 'Company\CompanyController@search']);
Route::get('/getSGSSTModules/{id_company}',['as' => 'getSGSSTModules', 'uses' => 'Company\CompanyController@getSGSSTModules']);

Route::group(['middleware' => ['guest', 'conta', 'admin', 'comerc', 'servcliente']], function () {

	//************Crear empresas**************************//
	Route::get('company/index1', ['as' => 'company/index1', 'uses' => 'Company\CompanyController@index1']);
	Route::post('company/update1', ['as' => 'company/update1', 'uses' => 'Company\CompanyController@update1']);
	Route::get('company/show1', ['as' => 'company/show1', 'uses' => 'Company\CompanyController@show1']);

	//*************Ruta para realizar busqueda de company********************//
	

	//Ruta Para Busqueda de ciudades cuando se seleccione la región
	Route::get('/searchCiudad/{id_region}', 'Company\CompanyController@searchCiudad');




	//Rutas para delegación
	Route::resource('/delegacion', 'Company\DelegacionController');
	Route::get('/delegacionInactive', 'Company\DelegacionController@delegacionInactive');

	//Rutas para proyectos
	Route::resource('/proyecto', 'Company\ProyectoController');

});
/////////////////////=======ZONA CONTABLE=======///////////////

Route::group(['middleware' => ['conta', 'admin']], function () {

	//***********Facturacion******************//
	//Route::get('facturacion/update',['as' =>'facturacion/update','uses'=>'Debito\FacturacionController@update']);

	//Route::get('facturacion/index1',['as'=> 'facturacion/listpagoCliente','uses'=>'Debito\FacturacionController@index1']);

	//***********************************Rutas del excel******************//

	Route::get('excel/indexCobros', ['as' => 'excel/indexCobros', 'uses' => 'Excel\ExcelController@indexCobros']);
	Route::get('excel/indexFactura', ['as' => 'excel/indexFactura', 'uses' => 'Excel\ExcelController@indexFactura']);
	Route::get('excel/indexCompany', ['as' => 'excel/indexCompany', 'uses' => 'Excel\ExcelController@indexCompany']);
	Route::get('excel/indexContratista/{id_company}', ['as' => 'excel/indexContratista', 'uses' => 'Excel\ExcelController@indexContratista']);

	//**subir archivos en excel**//
	Route::post('excel/Import', ['as' => 'facturacion', 'uses' => 'Excel\ExcelController@Import']);
	Route::post('excel/ImportUpdate', ['as' => 'facturacion', 'uses' => 'Excel\ExcelController@ImportUpdate']);
	Route::post('excel/ImportContratista', ['as' => 'contratista', 'uses' => 'Excel\ExcelController@ImportContratista']);

	route::get('/export-precios', 'Excel\ExcelController@exportPrecios');
	Route::resource('excel', 'Excel\ExcelController');



	//******************RUTAS DE BALANCES************************//
	//****************Ruta para acceder a la lista de balances*******//
	Route::get('/listBalance', 'Debito\BalanceController@read');
	//****************Ruta para acceder al formulario de registro de balances*****//
	Route::get('/registerBalance', 'Debito\BalanceController@register');
	//****************Ruta para crear balances
	Route::post('/createBalance', ['as' => '/createBalance', 'uses' => 'Debito\BalanceController@create']);
	//****************Ruta para anular balances
	Route::get('updateBalance/{id}/{observacion}', 'Debito\BalanceController@update');
	//***************Ruta para ver información del balance
	Route::get('infoBalance/{id}', 'Debito\BalanceController@infoBalance');

	Route::resource('actividadEco', 'Company\ActividadEcoController');

});
//--------------------------------FIN ZONA CONTABLE------------------------------------------------------//
//--------------------------------ZONA VITUAL SG-SST------------------------------------------------------//


/*CLIENTE*/
Route::get('/llenarFormulariosCliente/{company_id}', 'SST\SGSST\planear\PlanearController@indexForm');
Route::get('/listPlanearCliente/{company_id}', [
	'as' => 'listPlanearCliente',
	'uses' => 'SST\SGSST\planear\PlanearController@indexPlanearCliente',
]);

Route::get('/listAuditoria/{company_id}', [
	'as' => 'listAuditoria',
	'uses' => 'SST\SGSST\planear\PlanearController@itemsAuditoria',
]);

Route::get('/evidenciasDocsPlanear/{id_item}/{company_id}', [
	'as' => 'evidenciasDocsPlanear',
	'uses' => 'SST\SGSST\planear\PlanearController@evidenciasDocsPlanear',
]);

Route::post('/auditarDocs', [
	'as' => 'auditarDocs',
	'uses' => 'SST\SGSST\planear\PlanearController@auditarDocs',
]);
// Route::get('/listDocHacer/{company_id}', [
// 	'as' => 'listDocHacer',
// 	'uses' => 'SST\SGSST\hacer\HacerController@listDocHacer',
// ]);
Route::get('/listDocCliente/{id}/{company_id}', [
	'as' => 'listDocCliente',
	'uses' => 'SST\SGSST\planear\PlanearController@subModCliente',
]);
Route::get('/listaVariableCliente/{id_submodulo}/{company_id}/{numeral}', 'SST\SGSST\planear\PlanearController@listaVariableCliente');
Route::get('/ver/{id}/{id_rutaPDF}/{company_id}/{company_ids}', 'SST\SGSST\planear\PlanearController@ver');



//Funciones para formularios excel
Route::get('/listDocCliente2/{id}/{company_id}', 'SST\SGSST\planear\PlanearController@subModCliente2');
Route::any('/editListDocCliente/{id}/{company_id}/{idFila}', 'SST\SGSST\planear\PlanearController@editPlanCapacita');
Route::post('/updatePlanCapacita', 'SST\SGSST\planear\PlanearController@updatePlanCapacita');
Route::any('/editComiteParitario/{id}/{company_id}/{idFila}', 'SST\SGSST\planear\PlanearController@editComiteParitario');
Route::any('/editPlanTrabajoSST/{id}/{company_id}', 'SST\SGSST\planear\PlanearController@update241');

Route::post('/updatePlanCapacita', 'SST\SGSST\planear\PlanearController@updatePlanCapacita');

Route::post('/updateComiteParitario', 'SST\SGSST\planear\PlanearController@updateComiteParitario');

// funciones que ingresa los datos a las tablas
Route::post('/111ResponsableDelSGSST', 'SST\SGSST\planear\PlanearController@store111');
Route::post('/112ResponsabilidadesEnElSGSST', 'SST\SGSST\planear\PlanearController@store112');
Route::post('/113AsigancionDeRecursos', 'SST\SGSST\planear\PlanearController@store113');
Route::post('/113AsigancionDeRecursos_2', 'SST\SGSST\planear\PlanearController@store113_2');
Route::post('/116VigiaDeSeguridadYSaludEnElTrabajo', 'SST\SGSST\planear\PlanearController@store116');
Route::post('/117ActasCOPASSTVigiaSST', 'SST\SGSST\planear\PlanearController@store117');
Route::post('/updatePlanCapacita', 'SST\SGSST\planear\PlanearController@updatePlanCapacita');
Route::post('/updateComiteParitario', 'SST\SGSST\planear\PlanearController@updateComiteParitario');

Route::get('/listCalificacionSGSST/{id_empresa}', 'SST\SGSST\planear\PlanearController@listCalificacionSGSST');
Route::post('/118ComiteDeConvivenciaLaboral', 'SST\SGSST\planear\PlanearController@store118');
Route::post('/115VigiaDeSeguridadYSaludEnElTrabajo', 'SST\SGSST\planear\PlanearController@store115');
Route::post('/221_2111ComiteDeConvivenciaLaboral', 'SST\SGSST\planear\PlanearController@store221_2111');
Route::post('/planear281_cual', 'SST\SGSST\planear\PlanearController@crearPlanear281Cual');
Route::post('/118_2ComiteDeConvivenciaLaboral', 'SST\SGSST\planear\PlanearController@store118_2');
Route::post('/118_3ComiteDeConvivenciaLaboral', 'SST\SGSST\planear\PlanearController@store118_3');
Route::post('/118_4ComiteDeConvivenciaLaboral', 'SST\SGSST\planear\PlanearController@store118_4');
Route::post('/118_5ComiteDeConvivenciaLaboral', 'SST\SGSST\planear\PlanearController@store118_5');
Route::post('/118_6ComiteDeConvivenciaLaboral', 'SST\SGSST\planear\PlanearController@store118_6');

//Store Planear v3118 Programa de Capacitación Anual Comité de Convivencia Laboral by Edwin
Route::post('/118ConviLaboral', 'SST\SGSST\planear\PlanearController@v3118PlanearConviLaboral');
Route::post('/updateConviLaboral', 'SST\SGSST\planear\PlanearController@updatePlanearConviLaboral');

Route::get('/Capacitacion/{id_submodulo}/{company_id}', 'SST\SGSST\planear\PlanearController@matriz118create');

Route::post('/CapConvivenciaLaboral/{id_submodulo}/{company_id}', 'SST\SGSST\planear\PlanearController@matriz118create');

Route::get('/CapComAtras/{id_submodulo}/{company_id}', 'SST\SGSST\planear\PlanearController@prueba');

//Route::post('/121_2PlandeCapacitacion/{id}/{id_company}',['as' =>'plancapacitacion','uses'=>'PlanearController@v3121planear_planCapacita']);
Route::post('/121_2PlandeCapacitacion', 'SST\SGSST\planear\PlanearController@v3121planear_planCapacita');
Route::get('/excelPlanCapacitacion121/{company}/{id}', 'SST\SGSST\planear\PlanearVirtualController@excelPlanCapacitacion121');


Route::any('/117ComiteParitario', 'SST\SGSST\planear\PlanearController@v3117ComiteParitario');

Route::post('/121ProgramaDeCapacitacionAnual', 'SST\SGSST\planear\PlanearController@store121');
Route::post('/122Induccion', 'SST\SGSST\planear\PlanearController@store122');
Route::post('/123CursoDe50horas', 'SST\SGSST\planear\PlanearController@store123');
Route::post('/211PoliticaSST', 'SST\SGSST\planear\PlanearController@store211');
Route::post('/221Objetivos', 'SST\SGSST\planear\PlanearController@store221');
Route::post('/231EvaluacionInicial', 'SST\SGSST\planear\PlanearController@store231');
Route::post('/241PlanDeTrabajo', 'SST\SGSST\planear\PlanearController@store241');
Route::post('/241PlanDeTrabajoStore', 'SST\SGSST\planear\PlanearController@storePlanTrabajo241');
Route::post('/251RetencionDocumental', 'SST\SGSST\planear\PlanearController@store251');
Route::post('/261EvaluacionDeDesempeno', 'SST\SGSST\planear\PlanearController@store261');
Route::post('/271MatrizLegal', 'SST\SGSST\planear\PlanearController@store271');
Route::post('/281Comunicaciones', 'SST\SGSST\planear\PlanearController@store281');
Route::post('/281Matriz', 'SST\SGSST\planear\PlanearController@store281Matriz');
Route::post('/291Adquisiciones', 'SST\SGSST\planear\PlanearController@store291');
Route::post('/2101ProveedoresYContratistas', 'SST\SGSST\planear\PlanearController@store2101');
Route::post('/2111GestionDelCambio', 'SST\SGSST\planear\PlanearController@store2111');

Route::get('/verDocs/{id_company}/{numeral}', 'SST\SGSST\planear\PlanearController@archivoSubido');

Route::get('/editElementosDeProteccionPersonal/{id}/{company_id}', 'SST\SGSST\planear\PlanearController@elementosProteccion');
Route::post('/epp', 'SST\SGSST\planear\PlanearController@epp');
Route::get('/instruct', 'SST\SGSST\planear\PlanearController@instruct');
Route::post('/evaDesempeno', 'SST\SGSST\planear\PlanearController@store261EvalDesempeno');

Route::get('/editarDoc/{id}', 'SST\SGSST\planear\PlanearController@editListMaestro');

Route::post('/cambios/{id}', 'SST\SGSST\planear\PlanearController@updateListMaestro');

// Documentos Planear 

Route::get('/verstore111/{id_company}/{numeral}', [
	'as' => 'verstore111',
	'uses' => 'SST\SGSST\planear\PlanearController@verstore111',
]);

Route::get('/editarFirmas/{id_company}/{id_registro}/{id}/{ruta}/{item}', [
	'as' => 'editarFirmas',
	'uses' => 'SST\SGSST\planear\PlanearController@editarFirmas',
]);

Route::post('/crearOEditarFirmasPlanear', [
	'as' => 'crearOEditarFirmasPlanear',
	'uses' => 'SST\SGSST\planear\PlanearController@crearOEditarFirmasPlanear',
]);

Route::get('/VerHistoricoSgSSt/{id_registro}/{tipodoc}/{numeral}', [
	'as' => 'VerHistoricoSgSSt',
	'uses' => 'SST\SGSST\planear\PlanearController@VerHistoricoSgSSt',
]);

Route::get('/Veraniosgsst/{id_item}/{company_id}/{numeral}/{ano}', [
	'as' => 'Veraniosgsst',
	'uses' => 'SST\SGSST\planear\PlanearController@Veraniosgsst',
]);

Route::get('/Veraniosgsstfile/{id_item}/{company_id}/{numeral}/{ano}', [
	'as' => 'Veraniosgsstfile',
	'uses' => 'SST\SGSST\planear\PlanearController@Veraniosgsstfile',
]);

Route::post('/editstore111', [
	'as' => 'editstore111',
	'uses' => 'SST\SGSST\planear\PlanearController@editstore111',
]);

Route::get('/autorizacionFirmaPDF/{id_user}',[
	'as'=>'autorizacionFirmaPDF',
	'uses'=>'Users\UsersController@autorizacionFirmaPDF'
]);

Route::post('/editar241', [
	'as' => 'editar241',
	'uses' => 'SST\SGSST\planear\PlanearController@editar241',
]);

Route::post('/editstore111_2', [
	'as' => 'editstore111_2',
	'uses' => 'SST\SGSST\planear\PlanearController@editstore111_2',
]);

Route::post('/createstore111Pro', [
	'as' => 'createstore111Pro',
	'uses' => 'SST\SGSST\planear\PlanearController@createstore111Pro',
]);

Route::get('/store111Pro/{id_company}/{numeral}', [
	'as' => 'store111Pro',
	'uses' => 'SST\SGSST\planear\PlanearController@store111Pro',
]);

Route::get('/verstore111pro/{id_company}/{numeral}', [
	'as' => 'verstore111pro',
	'uses' => 'SST\SGSST\planear\PlanearController@verstore111pro',
]);

Route::post('/editstore112', [
	'as' => 'editstore112',
	'uses' => 'SST\SGSST\planear\PlanearController@editstore112',
]);

Route::post('/editstore116', [
	'as' => 'editstore116',
	'uses' => 'SST\SGSST\planear\PlanearController@editstore116',
]);

Route::post('/editstore221_2111', [
	'as' => 'editstore221_2111',
	'uses' => 'SST\SGSST\planear\PlanearController@editstore221_2111',
]);

Route::post('/editstore2111', [
	'as' => 'editstore2111',
	'uses' => 'SST\SGSST\planear\PlanearController@editstore2111',
]);

Route::post('/editstore281Matriz', [
	'as' => 'editstore281Matriz',
	'uses' => 'SST\SGSST\planear\PlanearController@editstore281Matriz',
]);

Route::post('/editstore291', [
	'as' => 'editstore291',
	'uses' => 'SST\SGSST\planear\PlanearController@editstore291',

]);

Route::post('/editstore2101', [
	'as' => 'editstore2101',
	'uses' => 'SST\SGSST\planear\PlanearController@editstore2101',
]);


Route::post('/editstore117', [
	'as' => 'editstore117',
	'uses' => 'SST\SGSST\planear\PlanearController@editstore117',

]);

Route::post('/editstore118', [
	'as' => 'editstore118',
	'uses' => 'SST\SGSST\planear\PlanearController@editstore118',

]);

Route::post('/editstore115', [
	'as' => 'editstore115',
	'uses' => 'SST\SGSST\planear\PlanearController@editstore115',

]);

Route::post('/editstore221_2111', [
	'as' => 'editstore221_2111',
	'uses' => 'SST\SGSST\planear\PlanearController@editstore221_2111',
]);

Route::post('/editstore271', [
	'as' => 'editstore271',
	'uses' => 'SST\SGSST\planear\PlanearController@editstore271',
]);

Route::post('/editstore281Cual', [
	'as' => 'editstore281Cual',
	'uses' => 'SST\SGSST\planear\PlanearController@editstore281Cual',
]);


Route::get('/verstore112/{id_registro}/{id_company}', [
	'as' => 'verstore112',
	'uses' => 'SST\SGSST\planear\PlanearController@verstore112',
]);

Route::get('/verstore116/{id_registro}/{id_company}', [
	'as' => 'verstore116',
	'uses' => 'SST\SGSST\planear\PlanearController@verstore116',

]);

Route::get('/verstore117/{id_registro}/{id_company}', [
	'as' => 'verstore117',
	'uses' => 'SST\SGSST\planear\PlanearController@verstore117',

]);

Route::get('/verstore118/{id_registro}/{id_company}', [
	'as' => 'verstore118',
	'uses' => 'SST\SGSST\planear\PlanearController@verstore118',

]);
Route::get('/verstore113_2/{id_registro}/{id_company}', [
	'as' => 'verstore113_2',
	'uses' => 'SST\SGSST\planear\PlanearController@verstore113_2',

]);

Route::post('/editStore113_Principal', [
	'as' => 'editStore113_Principal',
	'uses' => 'SST\SGSST\planear\PlanearController@editStore113_Principal',

]);
Route::post('/verstore113_Sem1', [
	'as' => 'verstore113_Sem1',
	'uses' => 'SST\SGSST\planear\PlanearController@verstore113_Sem1',

]);
Route::post('/verstore113_Sem2', [
	'as' => 'verstore113_Sem2',
	'uses' => 'SST\SGSST\planear\PlanearController@verstore113_Sem2',

]);

Route::post('/excelCronograma113', [
	'as' => 'excelCronograma113',
	'uses' => 'SST\SGSST\planear\PlanearVirtualController@excelCronograma113',
]);


Route::get('/verstore251/{id_registro}/{id_company}', [
	'as' => 'verstore251',
	'uses' => 'SST\SGSST\planear\PlanearController@verstore251',

]);

Route::get('/verstore241/{id_registro}/{id_company}', [
	'as' => 'verstore241',
	'uses' => 'SST\SGSST\planear\PlanearController@verstore241',

]);

Route::get('/editlistado3/{id_registro}/{id_company}/{id}', [
	'as' => 'editlistado3',
	'uses' => 'SST\SGSST\planear\PlanearController@editlistado3',
]);

Route::get('/crearPlanNewSgsst/{id_registro}/{id_company}/{id}', [
	'as' => 'crearPlanNewSgsst',
	'uses' => 'SST\SGSST\planear\PlanearController@crearPlanNewSgsst',

]);

Route::post('/storePlanNewSgsst', [
	'as' => 'storePlanNewSgsst',
	'uses' => 'SST\SGSST\planear\PlanearController@storePlanNewSgsst',

]);

Route::get('/verstore271/{id_registro}/{id_company}', [
	'as' => 'verstore271',
	'uses' => 'SST\SGSST\planear\PlanearController@verstore271',

]);

Route::get('/verstore281Matriz/{id_registro}/{id_company}', [
	'as' => 'verstore281Matriz',
	'uses' => 'SST\SGSST\planear\PlanearController@verstore281Matriz',

]);

Route::get('/veredit281Cual/{id_registro}/{id_company}/{id}/{columnas}', [
	'as' => 'veredit281Cual',
	'uses' => 'SST\SGSST\planear\PlanearController@veredit281Cual',

]);

Route::post('/edit281Cual', [
	'as' => 'edit281Cual',
	'uses' => 'SST\SGSST\planear\PlanearController@edit281Cual',

]);


Route::get('/verstore291/{id_registro}/{id_company}', [
	'as' => 'verstore291',
	'uses' => 'SST\SGSST\planear\PlanearController@verstore291',

]);

Route::get('/verstore2101/{id_registro}/{id_company}', [
	'as' => 'verstore2101',
	'uses' => 'SST\SGSST\planear\PlanearController@verstore2101',

]);

Route::get('/verstore115/{id_registro}/{id_company}', [
	'as' => 'verstore115',
	'uses' => 'SST\SGSST\planear\PlanearController@verstore115',

]);


/*** RUTAS 281 RESTANTES ***/

Route::get('/verupdate281Matriz/{id_registro}/{id_company}/{item}/{id}', [
	'as' => 'verupdate281Matriz',
	'uses' => 'SST\SGSST\planear\PlanearController@verupdate281Matriz',
]);


Route::post('/update281Matriz', [
	'as' => 'update281Matriz',
	'uses' => 'SST\SGSST\planear\PlanearController@update281Matriz',

]);


/*** END RUTAS 281 RESTANTES ***/


/*** RUTAS 2101 RESTANTES ***/

Route::get('/verupdate2101clasificar/{id_registro}/{id_company}/{item}/{id}/{grupo}', [
	'as' => 'verupdate2101clasificar',
	'uses' => 'SST\SGSST\planear\PlanearController@verupdate2101clasificar',
]);


Route::post('/update2101clasificar', [
	'as' => 'update2101clasificar',
	'uses' => 'SST\SGSST\planear\PlanearController@update2101clasificar',

]);


/*** END RUTAS 2101 RESTANTES ***/


/*** RUTAS 115 ***/

Route::get('/dataTrabajadores/{company_id}', [
	'as' => 'upDataWorkersAc',
	'uses' => 'SST\SGSST\planear\PlanearController@crearActividadespecificaEmpresa'
]);

Route::get('/listarac/{company_id}', [
	'as' => 'listar',
	'uses' => 'SST\SGSST\planear\PlanearController@listarActividadespecificaEmpresa'
]);

Route::get('/crearlistado/{company_id}/{idVal}', [
	'as' => 'crearlistado',
	'uses' => 'SST\SGSST\planear\PlanearController@crearlistado'
]);

Route::get('/listadomaster/{company_id}', [
	'as' => 'listadomaster',
	'uses' => 'SST\SGSST\planear\PlanearController@listadomaster'
]);

Route::get('/editTrimestre/{company_id}', [
	'as' => 'editTrimestre',
	'uses' => 'SST\SGSST\planear\PlanearController@editTrimestre'
]);

Route::get('/listarInactivos/{company_id}', [
	'as' => 'listarInactivos',
	'uses' => 'SST\SGSST\planear\PlanearController@listarActividadespecificaEmpresaInactivas'
]);

Route::post('/deshabilitadolistado/{company_id}', [
	'as' => 'deshabilitadolistado',
	'uses' => 'SST\SGSST\planear\PlanearController@deshabilitadolistado'
]);

//20/01/2023 macross
Route::post('/registrolistado', [
	'as' => 'registrolistado',
	'uses' => 'SST\SGSST\planear\PlanearController@registrolistado'
]);

Route::post('/registrolistadoUpdate', [
	'as' => 'registrolistadoUpdate',
	'uses' => 'SST\SGSST\planear\PlanearController@registrolistadoUpdate'
]);

Route::post('/registrolistadoPt', [
	'as' => 'registrolistadoPt',
	'uses' => 'SST\SGSST\planear\PlanearController@registrolistadoPt'
]);

Route::get('/inaclistado/{company_id}/{idVal}', [
	'as' => 'inaclistado',
	'uses' => 'SST\SGSST\planear\PlanearController@inaclistado'
]);

Route::post('/inaclistadomaster/{company_id}', [
	'as' => 'inaclistadomaster',
	'uses' => 'SST\SGSST\planear\PlanearController@inaclistadomaster'
]);

Route::post('/listadomasterprincipal', [
	'as' => 'listadomasterprincipal',
	'uses' => 'SST\SGSST\planear\PlanearController@listadomasterprincipal'
]);

Route::post('/newCTRLC', [
	'as' => 'newCTRLC',
	'uses' => 'SST\SGSST\planear\PlanearController@newCTRLC'
]);

Route::get('/createCTRLC/{id_registro}/{id_principal}/{id_empresa}', [
	'as' => 'createCTRLC',
	'uses' => 'SST\SGSST\planear\PlanearController@createCTRLC'
]);

Route::get('/dataCabeceraDoc/{id_submodulo}/{company_id}/{tipo}', [
	'as' => 'dataCabeceraDoc',
	'uses' => 'SST\SGSST\planear\PlanearController@dataCabeceraDoc'
]);

Route::get('/historicoCTRLC/{id_documento}/{id_registro}/{id_empresa}', [
	'as' => 'historicoCTRLC',
	'uses' => 'SST\SGSST\planear\PlanearController@historicoCTRLC'
]);

Route::get('/inaclistadomastervista/{company_id}', [
	'as' => 'inaclistadomastervista',
	'uses' => 'SST\SGSST\planear\PlanearController@inaclistadomastervista'
]);

Route::get('/editListar/{company_id}/{id}', [
	'as' => 'editListar',
	'uses' => 'SST\SGSST\planear\PlanearController@editActividadespecificaEmpresa'
]);

Route::get('/registrarlistado/{company_id}/{idVal}', [
	'as' => 'registrarlistado',
	'uses' => 'SST\SGSST\planear\PlanearController@registrarlistado'
]);

Route::get('/registrarlistadodoc/{company_id}/', [
	'as' => 'registrarlistadodoc',
	'uses' => 'SST\SGSST\planear\PlanearController@registrarlistadodoc'
]);

Route::get('/editlistado/{company_id}/{id}/{idVal}', [
	'as' => 'editlistado',
	'uses' => 'SST\SGSST\planear\PlanearController@editlistado'
]);

Route::get('/editlistado2/{company_id}/{id}/{id_registro}', [
	'as' => 'editlistado2',
	'uses' => 'SST\SGSST\planear\PlanearController@editlistado2'
]);
// ruta visata editar monkey
Route::get('/editlistadomaster/{company_id}/{id}', [
	'as' => 'editlistadomaster',
	'uses' => 'SST\SGSST\planear\PlanearController@editlistadomaster'
]);
// ruta visata editar monkey
Route::get('/editlistadomasterverdoc/{company_id}/{id}/{id_registro}/{id_principal}', [
	'as' => 'editlistadomasterverdoc',
	'uses' => 'SST\SGSST\planear\PlanearController@editlistadomasterverdoc'
]);



Route::POST('/updatelistado/{id}', [
	'as' => 'updatelistado',
	'uses' => 'SST\SGSST\planear\PlanearController@updatelistado'
]);

// ruta para guardar el edit listado master doc
Route::POST('/updatelistadomaster/{id}', [
	'as' => 'updatelistadomaster',
	'uses' => 'SST\SGSST\planear\PlanearController@updatelistadomaster'
]);
// ruta para guardar el edit listado master doc
Route::POST('/updatelistadoControl', [
	'as' => 'updatelistadoControl',
	'uses' => 'SST\SGSST\planear\PlanearController@updatelistadoControl'
]);

Route::get('/verstore115Trabajadores/{id_registro}/{company_id}', [
	'as' => 'verstore115Trabajadores',
	'uses' => 'SST\SGSST\planear\PlanearController@verstore115Trabajadores'
]);

Route::get('/edit115Trabajadores/{id_listado}/{id}/{id_company}', [
	'as' => 'edit115Trabajadores',
	'uses' => 'SST\SGSST\planear\PlanearController@edit115Trabajadores'
]);


Route::post('/editListar/{id}', [
	'as' => 'editListar',
	'uses' => 'SST\SGSST\planear\PlanearController@updateActividadespecificaEmpresa'
]);

Route::post('/dataListar', [
	'as' => 'upDataListar',
	'uses' => 'SST\SGSST\planear\PlanearController@storeActividadespecificaEmpresa'
]);

Route::post('/deleteDataListar/{id}', [
	'as' => 'deleteDataListar',
	'uses' => 'SST\SGSST\planear\PlanearController@deleteActividadespecificaEmpresa'
]);

Route::post('/v3115listadoDeTrabajadoresConActividadesDeAltoRiesgo', [
	'as' => 'listadoDeTrabajadoresConActividadesDeAltoRiesgo',
	'uses' => 'SST\SGSST\planear\PlanearController@v3115ListadoDeTrabajadoresConActividadesDeAltoRiesgo'
]);

Route::post('/update115Trabajadores/{id}', [
	'as' => 'update115Trabajadores',
	'uses' => 'SST\SGSST\planear\PlanearController@update115Trabajadores'
]);

Route::post('/update115TrabajadoresPrincipal/{id}', [
	'as' => 'update115TrabajadoresPrincipal',
	'uses' => 'SST\SGSST\planear\PlanearController@update115TrabajadoresPrincipal'
]);

Route::post('/storelistado', [
	'as' => 'storelistado',
	'uses' => 'SST\SGSST\planear\PlanearController@storelistado'
]);

Route::post('/storelistadodoc', [
	'as' => 'storelistadodoc',
	'uses' => 'SST\SGSST\planear\PlanearController@storelistadodoc'
]);

/*** END 115 ***/


/*** RUTAS 121 PLAN DE CAPACITACION ***/

Route::get('/ver121PlanDeCapacitacion/{id_registro}/{company_id}', [
	'as' => 'ver121PlanDeCapacitacion',
	'uses' => 'SST\SGSST\planear\PlanearController@ver121PlanDeCapacitacion'
]);

Route::get('/verUpdate121Capacitacion/{id}/{id_registro}', [
	'as' => 'verUpdate121Capacitacion',
	'uses' => 'SST\SGSST\planear\PlanearController@verUpdate121Capacitacion'
]);

Route::get('/verUpdate121Periodo/{id}/{id_registro}', [
	'as' => 'verUpdate121Periodo',
	'uses' => 'SST\SGSST\planear\PlanearController@verUpdate121Periodo'
]);

Route::post('/PlanDeCapacitacion121', [
	'as' => 'PlanDeCapacitacion121',
	'uses' => 'SST\SGSST\planear\PlanearController@PlanDeCapacitacion121'
]);

Route::post('/UpdatePlanDeCapacitacion121/{id}', [
	'as' => 'UpdatePlanDeCapacitacion121',
	'uses' => 'SST\SGSST\planear\PlanearController@UpdatePlanDeCapacitacion121'
]);

Route::post('/UpdateCapacitacion121/{id}', [
	'as' => 'UpdateCapacitacion121',
	'uses' => 'SST\SGSST\planear\PlanearController@UpdateCapacitacion121'
]);

Route::post('/UpdatePeriodo121/{id}/{id_registro}', [
	'as' => 'UpdatePeriodo121',
	'uses' => 'SST\SGSST\planear\PlanearController@UpdatePeriodo121'
]);

/*** END 121 PLAN DE CAPACITACION ***/



Route::get('/verstore221_2111/{id_registro}/{id_company}', [
	'as' => 'verstore221_2111',
	'uses' => 'SST\SGSST\planear\PlanearController@verstore221_2111',

]);

Route::post('/CalificarSGsst', [
	'as' => 'CalificarSGsst',
	'uses' => 'SST\SGSST\planear\PlanearController@CalificarSGsst',

]);


/*** RUTAS 261 Rendicion de Cuentas ***/
Route::get('/ver261RendiciondeCuentas/{id_registro}/{id_company}', [
	'as' => 'ver261RendiciondeCuentas',
	'uses' => 'SST\SGSST\planear\PlanearController@ver261RendiciondeCuentas'
]);

Route::get('/verUpdate261RendiciondeCuentas/{id}/{id_registro}/{grupo}/{company_id}', [
	'as' => 'verUpdate261RendiciondeCuentas',
	'uses' => 'SST\SGSST\planear\PlanearController@verUpdate261RendiciondeCuentas'
]);

Route::get('/nuevo261RendiciondeCuentas/{grupo}/{id_registro}/{comapany_id}', [
	'as' => 'nuevo261RendiciondeCuentas',
	'uses' => 'SST\SGSST\planear\PlanearController@nuevo261RendiciondeCuentas'
]);


Route::post('/v3261RendiciondeCuentas', [
	'as' => 'v3261RendiciondeCuentas',
	'uses' => 'SST\SGSST\planear\PlanearController@store261RendiciondeCuentas'
]);

Route::post('/updatev3261RendiciondeCuentas/{id_registro}', [
	'as' => 'updatev3261RendiciondeCuentas',
	'uses' => 'SST\SGSST\planear\PlanearController@updatev3261RendiciondeCuentas'
]);

Route::post('/update261RendiciondeCuentas/{id}/{id_registro}/{grupo}', [
	'as' => 'update261RendiciondeCuentas',
	'uses' => 'SST\SGSST\planear\PlanearController@update261RendiciondeCuentas'
]);

Route::post('/crearNuevo261RendiciondeCuentas/{grupo}/{id_registro}', [
	'as' => 'crearNuevo261RendiciondeCuentas',
	'uses' => 'SST\SGSST\planear\PlanearController@crearNuevo261RendiciondeCuentas'
]);

/*** END  261 Rendicion de Cuentas ***/


// RUTA DATACVFDOCS
Route::get('/dataCVFDocs/{id_diganostico}/{id_company}/{typeD}/{accion}', [
	'as' => 'dataCVFDocs',
	'uses' => 'SST\SGSST\hacer\HacerController@dataCVFDocs',
]);
// END RUTA DATACVFDOCS


// RUTAS HACER SG-SSTVIRTUAL //

// RUTAS GENERALES SGSSTV

Route::post('/createDocHacerSGSST', [
	'as' => 'createDocHacerSGSST',
	'uses' => 'SST\SGSST\hacer\HacerController@createDocHacerSGSST',
]);

Route::post('/createCalificacionHacerSGSST', [
	'as' => 'createCalificacionHacerSGSST',
	'uses' => 'SST\SGSST\hacer\HacerController@createCalificacionHacerSGSST',
]);

Route::get('/createRecordsDocsSGSST/{id_company}/{id_diagnostico}', [
	'as' => 'createRecordsDocsSGSST',
	'uses' => 'SST\SGSST\hacer\HacerController@createRecordsDocsSGSST',
]);

Route::get('/editRecordsDocsSGSST/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'editRecordsDocsSGSST',
	'uses' => 'SST\SGSST\hacer\HacerController@editRecordsDocsSGSST',
]);

Route::get('/pdfRecordsDocsSGSST/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'pdfRecordsDocsSGSST',
	'uses' => 'SST\SGSST\hacer\HacerController@pdfRecordsDocsSGSST',
]);

Route::get('/getGeneralRecordsExcelData/{id_company}/{id_diagnostico}', [
	'as' => 'getGeneralRecordsExcelData',
	'uses' => 'SST\SGSST\hacer\HacerController@getGeneralRecordsExcelData',
]);

// FIN RUTAS GENERALES HACER SGSSTV

//CALIFICACIÓN
Route::get('/listCalificacionHacerSGSST/{id_company}', 'SST\SGSST\hacer\HacerController@listCalificacionHacerSGSST');

Route::get('/itemsAuditoriaHacer/{id_company}', [
	'as' => 'itemsAuditoriaHacer',
	'uses' => 'SST\SGSST\hacer\HacerController@itemsAuditoriaHacer',
]);

Route::get('/evidenciasDocsHacer/{id_itm}/{id_company}', [
	'as' => 'evidenciasDocsHacer',
	'uses' => 'SST\SGSST\hacer\HacerController@evidenciasDocsHacer',
]);

Route::post('/auditarDocsHacer', [
	'as' => 'auditarDocsHacer',
	'uses' => 'SST\SGSST\hacer\HacerController@auditarDocsHacer',
]);

// 3.1.1.1 INICIO
Route::post('/ProcedimientoPerfilSociodeografico', 'SST\SGSST\hacer\HacerController@ProcedimientoPerfilSociodeografico');
// 3.1.1.1 FIN

// 3.1.1.4 INCIO
Route::post('/createOrUpdteConsentimientoInformado', 'SST\SGSST\hacer\HacerController@createOrUpdteConsentimientoInformado');
// 3.1.1.4 FIN

// 3.1.3.1 INICIO
Route::post('/createOrUpdteGuiaDescripcion', 'SST\SGSST\hacer\HacerController@createOrUpdteGuiaDescripcion');
// 3.1.3.1 FIN

// 3.1.4.3 INICIO
Route::post('/createOrUpdteProcedimientoExamenesMedicos', 'SST\SGSST\hacer\HacerController@createOrUpdteProcedimientoExamenesMedicos');
// 3.1.4.3 FIN

// 3.1.4.4 INICIO
Route::post('/createOrUpdteFormatoDeRemisionExamenesMedicos', 'SST\SGSST\hacer\HacerController@createOrUpdteFormatoDeRemisionExamenesMedicos');
// 3.1.4.4 FIN

// 3.1.4.5 INICIO
Route::post('/createOrUpdteSeguimientoExamenesMedicoLaborales', 'SST\SGSST\hacer\HacerController@createOrUpdteSeguimientoExamenesMedicoLaborales');
// 3.1.4.5 FIN

// 3.1.6.1 INICIO
Route::post('/createOrUpdteProcedimientoParaAtenderRecomendaciones', 'SST\SGSST\hacer\HacerController@createOrUpdteProcedimientoParaAtenderRecomendaciones');
// 3.1.6.1 FIN

// 3.1.6.1.1 INICIO
Route::post('/createOrUpdteImplementacionRecomendacionesLab', 'SST\SGSST\hacer\HacerController@createOrUpdteImplementacionRecomendacionesLab');
// 3.1.6.1.1 FIN

// 3.1.6.2 INICIO
Route::post('/createOrUpdteConsentimientoInformadoModificado', 'SST\SGSST\hacer\HacerController@createOrUpdteConsentimientoInformadoModificado');
// 3.1.6.2 FIN

// 3.1.6.3 INICIO
Route::post('/createOrUpdteGuiaReincorporacionLaboral', 'SST\SGSST\hacer\HacerController@createOrUpdteGuiaReincorporacionLaboral');
// 3.1.6.3 FIN

// 3.1.6.4 INICIO
Route::post('/createOrUpdteFormatoDescripcionCargo', 'SST\SGSST\hacer\HacerController@createOrUpdteFormatoDescripcionCargo');
// 3.1.6.4 FIN

// 3.1.6.5 INICIO
Route::post('/createOrUpdteFormatoSeguimientoLab', 'SST\SGSST\hacer\HacerController@createOrUpdteFormatoSeguimientoLab');
// 3.1.6.5 FIN

// 3.1.6.6 INICIO
Route::post('/createOrUpdteMatrizSeguimientoCasosMed', 'SST\SGSST\hacer\HacerController@createOrUpdteMatrizSeguimientoCasosMed');
// 3.1.6.6 FIN

// 3.1.7.1 INICIO
Route::post('/createOrUpdteConsentimientoInfor', 'SST\SGSST\hacer\HacerController@createOrUpdteConsentimientoInfor');
// 3.1.7.1 FIN

// 3.1.7.2 INICIO
Route::post('/createOrUpdteFormatoAceptacionPolitica', 'SST\SGSST\hacer\HacerController@createOrUpdteFormatoAceptacionPolitica');
// 3.1.7.2 FIN

// 3.1.7.3 INICIO
Route::post('/createOrUpdteFormatoRemisionPruebaSPA', 'SST\SGSST\hacer\HacerController@createOrUpdteFormatoRemisionPruebaSPA');
// 3.1.7.3 FIN

// 3.1.7.4 INICIO
Route::post('/createOrUpdtePoliticaAyD', 'SST\SGSST\hacer\HacerController@createOrUpdtePoliticaAyD');
// 3.1.7.4 FIN

// 3.1.7.5 INICIO
Route::post('/createOrUpdtePlegablePausasSaludables', 'SST\SGSST\hacer\HacerController@createOrUpdtePlegablePausasSaludables');
// 3.1.7.5 FIN

// 3.1.7.6 INICIO
Route::post('/createOrUpdteProgramaPausasSaludables', 'SST\SGSST\hacer\HacerController@createOrUpdteProgramaPausasSaludables');
// 3.1.7.6 FIN

// 3.1.7.7 INICIO
Route::post('/createOrUpdtePrevencialAlConsumoSPA', 'SST\SGSST\hacer\HacerController@createOrUpdtePrevencialAlConsumoSPA');
// 3.1.7.7 FIN

// 3.1.7.8 INICIO
Route::post('/createOrUpdteProgramaEstiloVidaSalud', 'SST\SGSST\hacer\HacerController@createOrUpdteProgramaEstiloVidaSalud');
// 3.1.7.8 FIN

// 3.1.7.9 INICIO
Route::post('/createOrUpdteRegistroPausasSalud', 'SST\SGSST\hacer\HacerController@createOrUpdteRegistroPausasSalud');
// 3.1.7.9 FIN

// 3.1.8.1 INICIO
Route::post('/createOrUpdteFacturaDeServiciosPublicos', 'SST\SGSST\hacer\HacerController@createOrUpdteFacturaDeServiciosPublicos');
// 3.1.8.1 FIN

// 3.1.9.1 INICIO
Route::post('/createOrUpdteDispocicionDeResiduos', 'SST\SGSST\hacer\HacerController@createOrUpdteDispocicionDeResiduos');
// 3.1.9.1 FIN

// 3.2.2.2 INICIO
Route::post('/createOrUpdteFormatosInvestigación', 'SST\SGSST\hacer\HacerController@createOrUpdteFormatosInvestigación');
// 3.2.2.2 FIN

// 3.2.3.1 INICIO
Route::post('/createOrUpdteAnalisisEstadisticoAt', 'SST\SGSST\hacer\HacerController@createOrUpdteAnalisisEstadisticoAt');
// 3.2.3.1 FIN
// 3.2.3.3 INICIO
Route::get('/SGSSTausentismo/{id_company}/{id_user}/{aus}', [
	'as' => 'SGSSTausentismo/{id_company}/{id_user}/{aus}',
	'uses' => 'SST\SGSST\hacer\HacerController@SGSSTausentismo',
]);
Route::get('/SGSSTausentismoEdit/{id_company}/{id_user}/{aus}', [
	'as' => 'SGSSTausentismoEdit/{id_company}/{id_user}/{aus}',
	'uses' => 'SST\SGSST\hacer\HacerController@SGSSTausentismoEdit',
]);

Route::get('/formIndObligatoriosH/{id_empresa}/{idISG}', [
	'as' => 'formIndObligatoriosH',
	'uses' => 'SST\SGSST\hacer\HacerController@formIndObligatoriosH',

]);
// 3.2.3.3 FIN

// 3.2.3.4 INICIO
Route::get('/caracterizacionAt/{id_company}/{id_user}/{id_accidente}', [
	'as' => 'caracterizacionAt/{id_company}/{id_user}/{id_accidente}',
	'uses' => 'SST\SGSST\hacer\HacerController@caracterizacionAt',
]);
Route::get('/caracterizacionAtEdit/{id_company}/{id_user}/{id_accidente}', [
	'as' => 'caracterizacionAtEdit/{id_company}/{id_user}/{id_accidente}',
	'uses' => 'SST\SGSST\hacer\HacerController@caracterizacionAtEdit',
]);
// 3.2.3.4 FIN

Route::get('/planesCreate/{id_company}/{id_user}/{id_accidente}', [
	'as' => 'planesCreate/{id_company}/{id_user}/{id_accidente}',
	'uses' => 'SST\SGSST\hacer\HacerController@planesCreate',
]);
Route::get('/planesEdit/{id_company}/{id_user}/{id_accidente}', [
	'as' => 'planesEdit/{id_company}/{id_user}/{id_accidente}',
	'uses' => 'SST\SGSST\hacer\HacerController@planesEdit',
]);

Route::get('/incidentesCreate/{id_company}/{id_user}/{id_accidente}', [
	'as' => 'incidentesCreate/{id_company}/{id_user}/{id_accidente}',
	'uses' => 'SST\SGSST\hacer\HacerController@incidentesCreate',
]);
Route::get('/incidentesEdit/{id_company}/{id_user}/{id_accidente}', [
	'as' => 'incidentesEdit/{id_company}/{id_user}/{id_accidente}',
	'uses' => 'SST\SGSST\hacer\HacerController@incidentesEdit',
]);

// 3.2.3.3 INICIO

// 4.1.1.2 INICIO
Route::post('/createOrUpdteIdentificacionDePeligros', 'SST\SGSST\hacer\HacerController@createOrUpdteIdentificacionDePeligros');
// 4.1.1.2 FIN

// 4.1.1.2 INICIO
Route::post('/createOrUpdatevulRecursos', 'SST\SGSST\hacer\HacerController@createOrUpdatevulRecursos');
// 4.1.1.2 FIN

// 4.1.1.2 INICIO
Route::post('/createOrUpdateplaneacionDeSimulacros', 'SST\SGSST\hacer\HacerController@createOrUpdateplaneacionDeSimulacros');
// 4.1.1.2 FIN

// 4.1.2.2 INICIO
Route::post('/createOrUpdateIdentificacionDePeligrosTrabajadores', 'SST\SGSST\hacer\HacerController@createOrUpdateIdentificacionDePeligrosTrabajadores');
// 4.1.2.2 FIN

// 4.1.2.3 INICIO
Route::post('/createOrUpdateVerificacionDeLosProcesos', 'SST\SGSST\hacer\HacerController@createOrUpdateVerificacionDeLosProcesos');
// 4.1.2.3 FIN

// 4.1.2.3 INICIO
Route::post('/createOrUpdateAVpersonas', 'SST\SGSST\hacer\HacerController@createOrUpdateAVpersonas');
// 4.1.2.3 FIN

// 5.1.2.7 INICIO
Route::post('/createOrUpdateAVrecursos', 'SST\SGSST\hacer\HacerController@createOrUpdateAVrecursos');
// 5.1.2.7 FIN

// 5.1.2.7 INICIO
Route::post('/createOrUpdateAVconsolidado', 'SST\SGSST\hacer\HacerController@createOrUpdateAVconsolidado');
// 5.1.2.7 FIN

// 5.1.2.7 INICIO
Route::post('/createOrUpdateAVriesgo', 'SST\SGSST\hacer\HacerController@createOrUpdateAVriesgo');
// 5.1.2.7 FIN

// 5.1.2.7 INICIO
Route::post('/createOrUpdateformatoEntregaDotacion', 'SST\SGSST\hacer\HacerController@createOrUpdateformatoEntregaDotacion');
// 5.1.2.7 FIN

// 5.1.2.7 INICIO
Route::post('/createOrUpdateactaReunionBrigada', 'SST\SGSST\hacer\HacerController@createOrUpdateactaReunionBrigada');
// 5.1.2.7 FIN

// 4.1.3.1 INICIO
Route::post('/createOrUpdteMatrizCompatibilidadSustanciasQuimicas', 'SST\SGSST\hacer\HacerController@createOrUpdteMatrizCompatibilidadSustanciasQuimicas');
// 4.1.3.1 FIN

// 4.1.3.1 INICIO
Route::post('/createOrUpdteMatrizCompatibilidadSustanciasQuimicasVyV', 'SST\SGSST\hacer\HacerController@createOrUpdteMatrizCompatibilidadSustanciasQuimicasVyV');
// 4.1.3.1 FIN

// 4.1.4.1 INICIO
Route::post('/createOrUpdteMedicionesAmbientales', 'SST\SGSST\hacer\HacerController@createOrUpdteMedicionesAmbientales');
// 4.1.4.1 FIN

// 4.1.3.3 INICIO
Route::post('/createOrUpdteMatrizSustanciasQuimicasyMaterialesPeligrosos', 'SST\SGSST\hacer\HacerController@createOrUpdteMatrizSustanciasQuimicasyMaterialesPeligrosos');
Route::post('/createOrUpdateSustanciasQuimicas', 'SST\SGSST\hacer\HacerController@createOrUpdateSustanciasQuimicas');
Route::get('/sustanciasQuimicas/{company_id}/{diagnostico}/{typeForm}', [
	'as' => 'sustanciasQuimicas',
	'uses' => 'SST\SGSST\hacer\HacerController@sustanciasQuimicas',
]);
Route::get('/sustanciasQuimicasUpdateOrCreate/{company_id}/{diagnostico}/{id}/{typeForm}', [
	'as' => 'sustanciasQuimicasUpdateOrCreate',
	'uses' => 'SST\SGSST\hacer\HacerController@sustanciasQuimicasUpdateOrCreate',
]);
// 4.1.3.3 FIN

// 4.2.1.1 INICIO
Route::post('/createOrUpdtePlanIntervencion', 'SST\SGSST\hacer\HacerController@createOrUpdtePlanIntervencion');
// 4.2.1.1 FIN

// 4.2.3.1 INICIO
Route::post('/createOrUpdateAroMontacarga', 'SST\SGSST\hacer\HacerController@createOrUpdateAroMontacarga');
// 4.2.3.1 FIN

// 4.2.3.2 INICIO
Route::post('/createOrUpdateEstBarrido', 'SST\SGSST\hacer\HacerController@createOrUpdateEstBarrido');
// 4.2.3.2 FIN

// 4.2.3.3 INICIO
Route::post('/createOrUpdateSeguridadRiesgoPublico', 'SST\SGSST\hacer\HacerController@createOrUpdateSeguridadRiesgoPublico');
// 4.2.3.3 FIN

// 4.2.3.4 INICIO
Route::post('/createOrUpdateEstandarEscurrido', 'SST\SGSST\hacer\HacerController@createOrUpdateEstandarEscurrido');
// 4.2.3.4 FIN

// 4.2.3.5 INICIO
Route::post('/createOrUpdateHerramientasManualesyElectricas', 'SST\SGSST\hacer\HacerController@createOrUpdateHerramientasManualesyElectricas');
// 4.2.3.5 FIN

// 4.2.3.6 INICIO
Route::post('/createOrUpdateLimpiezaVerticales', 'SST\SGSST\hacer\HacerController@createOrUpdateLimpiezaVerticales');
// 4.2.3.6 FIN

// 4.2.3.7 INICIO
Route::post('/createOrUpdateEstandarTrapeado', 'SST\SGSST\hacer\HacerController@createOrUpdateEstandarTrapeado');
// 4.2.3.7 FIN

// 4.2.4.1 INICIO
Route::post('/createOrUpdateInspecciones', 'SST\SGSST\hacer\HacerController@createOrUpdateInspecciones');
// 4.2.4.1 FIN

// 4.2.4.2 INICIO
Route::post('/createOrUpdateCronogramaEIP', 'SST\SGSST\hacer\HacerController@createOrUpdateCronogramaEIP');
// 4.2.4.2 FIN

// 4.2.4.3 INICIO
Route::post('/createOrUpdateInspeccionAlmacenamiento', 'SST\SGSST\hacer\HacerController@createOrUpdateInspeccionAlmacenamiento');
// 4.2.4.3 FIN

// 4.2.4.4 INICIO
Route::post('/createOrUpdateInspeccionBotiquin', 'SST\SGSST\hacer\HacerController@createOrUpdateInspeccionBotiquin');
// 4.2.4.4 FIN

// 4.2.4.5 INICIO
Route::post('/createOrUpdateInspecccionSeguridad', 'SST\SGSST\hacer\HacerController@createOrUpdateInspecccionSeguridad');
// 4.2.4.5 FIN

// 4.2.4.6 INICIO
Route::post('/createOrUpdateInspeccionExtintores', 'SST\SGSST\hacer\HacerController@createOrUpdateInspeccionExtintores');
// 4.2.4.6 FIN

// 4.2.4.7 INICIO
Route::post('/createOrUpdateInspeccionOficinas', 'SST\SGSST\hacer\HacerController@createOrUpdateInspeccionOficinas');
// 4.2.4.7 FIN

// 4.2.4.8 INICIO
Route::post('/createOrUpdateInspeccionOrden', 'SST\SGSST\hacer\HacerController@createOrUpdateInspeccionOrden');
// 4.2.4.8 FIN

// 4.2.4.9  INICIO
Route::post('/createOrUpdateInspeccionMontaCargas', 'SST\SGSST\hacer\HacerController@createOrUpdateInspeccionMontaCargas');
// 4.2.4.9 FIN

// 4.2.4.10 INICIO
Route::post('/createOrUpdateInspeccionEpp', 'SST\SGSST\hacer\HacerController@createOrUpdateInspeccionEpp');
// 4.2.4.10 FIN

// 4.2.5.1 INICIO
Route::post('/createOrUpdateGestiondeMantenimiento', 'SST\SGSST\hacer\HacerController@createOrUpdateGestiondeMantenimiento');
// 4.2.5.1 FIN

// 4.2.5.2 INICIO
Route::post('/createOrUpdateinventarioMyQ', 'SST\SGSST\hacer\HacerController@createOrUpdateinventarioMyQ');
// 4.2.5.2 FIN

// 4.2.5.2 INICIO
Route::post('/createOrUpdateProcedimientoReporteDeAccidentes', 'SST\SGSST\hacer\HacerController@createOrUpdateProcedimientoReporteDeAccidentes');
// 4.2.5.2 FIN

// 4.2.5.3 INICIO
Route::post('/createOrUpdatecronogramaMantenimiento', 'SST\SGSST\hacer\HacerController@createOrUpdatecronogramaMantenimiento');
Route::post('/createOrUpdateArrayMeses', 'SST\SGSST\hacer\HacerController@createOrUpdateArrayMeses');
Route::get('/verResultsCronograma/{id}/{idmes}/{id_registro}', 'SST\SGSST\hacer\HacerController@verResultsCronograma');
// 4.2.5.3 FIN

// 4.2.5.4 INICIO
Route::post('/createOrUpdateControlMantenimiento', 'SST\SGSST\hacer\HacerController@createOrUpdateControlMantenimiento');
// 4.2.5.4 FIN

// 4.2.5.5 INICIO
Route::post('/createOrUpdateInspeccion', 'SST\SGSST\hacer\HacerController@createOrUpdateInspeccion');
// 4.2.5.5 FIN

// 5.1.1.1 INICIO
Route::post('/createOrUpdateplanDePreparacionDeEmergencia', 'SST\SGSST\hacer\HacerController@createOrUpdateplanDePreparacionDeEmergencia');
// 5.1.1.1 FIN

// 5.1.1.1 INICIO
Route::post('/createOrUpdateponEvaluacion', 'SST\SGSST\hacer\HacerController@createOrUpdateponEvaluacion');
// 5.1.1.1 FIN

// 5.1.1.1 INICIO
Route::post('/createOrUpdateponIncendios', 'SST\SGSST\hacer\HacerController@createOrUpdateponIncendios');
// 5.1.1.1 FIN

// 5.1.1.1 INICIO
Route::post('/createOrUpdateponLesionados', 'SST\SGSST\hacer\HacerController@createOrUpdateponLesionados');
// 5.1.1.1 FIN

// 5.1.2.2 INICIO
Route::post('/createOrUpdatehojaVidaBrigadista', 'SST\SGSST\hacer\HacerController@createOrUpdatehojaVidaBrigadista');
// 5.1.2.2 FIN

// 4.2.6.1 INICIO
Route::post('/createOrUpdateCompraEpp', 'SST\SGSST\hacer\HacerController@createOrUpdateCompraEpp');
// 4.2.6.1 FIN

// 5.1.1.16 INICIO
Route::post('/createOrUpdateCCEmergencia', 'SST\SGSST\hacer\HacerController@createOrUpdateCCEmergencia');
// 5.1.1.16 FIN

// 5.1.1.16 INICIO
Route::post('/createOrUpdatecartaComunicacionSimulacro', 'SST\SGSST\hacer\HacerController@createOrUpdatecartaComunicacionSimulacro');
// 5.1.1.16 FIN

// 5.1.1.16 INICIO
Route::post('/createOrUpdateejecucionSimulacro', 'SST\SGSST\hacer\HacerController@createOrUpdateejecucionSimulacro');
// 5.1.1.16 FIN

// 5.1.1.16 INICIO
Route::post('/createOrUpdateAVsistemas', 'SST\SGSST\hacer\HacerController@createOrUpdateAVsistemas');
// 5.1.1.16 FIN

// 5.1.2.1 INICIO
Route::post('/createOrUpdateActaConformacion', 'SST\SGSST\hacer\HacerController@createOrUpdateActaConformacion');
//5.1.2.1 FIN

// 5.1.2.1 INICIO
Route::post('/createOrUpdatecaracterizacionPlanEmergencia', 'SST\SGSST\hacer\HacerController@createOrUpdatecaracterizacionPlanEmergencia');
//5.1.2.1 FIN

// 5.1.2.1 INICIO
Route::post('/createOrUpdatecaracterizacionPlanEmergencia2', 'SST\SGSST\hacer\HacerController@createOrUpdatecaracterizacionPlanEmergencia2');
//5.1.2.1 FIN

// 5.1.1.3 INICIO
Route::post('/createOrUpdateAVmetodologia', 'SST\SGSST\hacer\HacerController@createOrUpdateAVmetodologia');
//5.1.1.3 FIN

// 5.1.1.4 INICIO
Route::post('/createOrUpdateAVamenazas', 'SST\SGSST\hacer\HacerController@createOrUpdateAVamenazas');
//5.1.1.4 FIN

// 5.1.1.5 INICIO
Route::post('/createOrUpdateAVocurrencia', 'SST\SGSST\hacer\HacerController@createOrUpdateAVocurrencia');
//5.1.1.5 FIN

// 5.1.1.5 INICIO
Route::post('/createOrUpdateAVfoto', 'SST\SGSST\hacer\HacerController@createOrUpdateAVfoto');
//5.1.1.5 FIN

// 5.1.1.5 INICIO
Route::post('/createOrUpdateDefiniciones', 'SST\SGSST\hacer\HacerController@createOrUpdateDefiniciones');
//5.1.1.5 FIN

// 5.1.1.5 INICIO
Route::post('/createOrUpdateBaseDeDatosAT', 'SST\SGSST\hacer\HacerController@createOrUpdateBaseDeDatosAT');
//5.1.1.5 FIN

// 5.1.1.5 INICIO
Route::post('/createOrUpdateAusentismo', 'SST\SGSST\hacer\HacerController@createOrUpdateAusentismo');
// 5.1.1.5 FIN
// 3.2.3.6 INICIO
Route::post('/createOrUpdateConsolidado', 'SST\SGSST\hacer\HacerController@createOrUpdateConsolidado');
// 3.2.3.6 FIN
// 3.2.3.7 INICIO
Route::post('/createOrUpdateIfAt', 'SST\SGSST\hacer\HacerController@createOrUpdateIfAt');
// 3.2.3.7 FIN
// 3.2.3.8 INICIO
Route::post('/createOrUpdateIsAT', 'SST\SGSST\hacer\HacerController@createOrUpdateIsAT');
// 3.2.3.8 FIN
// 3.2.3.9 INICIO
Route::post('/createOrUpdateTmAt', 'SST\SGSST\hacer\HacerController@createOrUpdateTmAt');
// 3.2.3.9 FIN
// 3.2.3.10 INICIO
Route::post('/createOrUpdateIfAtEl', 'SST\SGSST\hacer\HacerController@createOrUpdateIfAtEl');
// 3.2.3.10 FIN
// 3.2.3.10 INICIO
Route::post('/createOrUpdateIsEl', 'SST\SGSST\hacer\HacerController@createOrUpdateIsEl');
// 3.2.3.10 FIN
// 3.2.3.11 INICIO
Route::post('/createOrUpdateIinEl', 'SST\SGSST\hacer\HacerController@createOrUpdateIinEl');
// 3.2.3.11 FIN
// 3.2.3.12 INICIO
Route::post('/createOrUpdateIpEl', 'SST\SGSST\hacer\HacerController@createOrUpdateIpEl');
// 3.2.3.12 FIN
// 3.2.3.13 INICIO
Route::post('/createOrUpdateAus', 'SST\SGSST\hacer\HacerController@createOrUpdateAus');
// 3.2.3.13 FIN
// 3.2.3.14 INICIO
Route::post('/createOrUpdateEg', 'SST\SGSST\hacer\HacerController@createOrUpdateEg');
// 3.2.3.14 FIN
// 3.2.3.15 INICIO
Route::post('/createOrUpdateIli', 'SST\SGSST\hacer\HacerController@createOrUpdateIli');
// 3.2.3.15 FIN




// FIN RUTAS HACER SG-SSTVIRTUAL //


// ///////////////////FIN RUTAS SG-SSTVIRTUAL -////////////////////////////////////////
// rutas para Identificación de Peligros, Evaluación y Valoración de Riesgos//////////////////

Route::get('/proceso/{company_id}', [
	'as' => 'proceso',
	'uses' => 'IPEVR\IPEVRcontroller@procesos',
]);

Route::get('/procesoForm/{company_id}', [
	'as' => 'procesoForm',
	'uses' => 'IPEVR\IPEVRcontroller@procesoForm',
]);

Route::post('/createPro', [
	'as' => 'createPro',
	'uses' => 'IPEVR\IPEVRcontroller@createPro',
]);

Route::get('/editProceso/{company_id}/{id_proceso}', [
	'as' => 'editProceso',
	'uses' => 'IPEVR\IPEVRcontroller@editProceso',
]);

Route::post('/updateProceso', [
	'as' => 'updateProceso',
	'uses' => 'IPEVR\IPEVRcontroller@updateProceso',
]);

Route::get('/listRiesgo/{company_id}/{id_proceso}', [
	'as' => 'listRiesgo',
	'uses' => 'IPEVR\IPEVRcontroller@listRiesgo',
]);

Route::get('/editRiesgo/{company_id}/{id_proceso}/{id_riesgo}', [
	'as' => 'editRiesgo',
	'uses' => 'IPEVR\IPEVRcontroller@editRiesgo',
]);

Route::get('/riesgoForm/{company_id}/{id_proceso}', [
	'as' => 'riesgoForm',
	'uses' => 'IPEVR\IPEVRcontroller@riesgoForm',
]);

Route::post('/createRiesgo', [
	'as' => 'createRiesgo',
	'uses' => 'IPEVR\IPEVRcontroller@createRiesgo',
]);

Route::post('/updateRiesgo', [
	'as' => 'updateRiesgo',
	'uses' => 'IPEVR\IPEVRcontroller@updateRiesgo',
]);

Route::get('/listIntervencion/{company_id}/{id_proceso}/{id_riesgo}', [
	'as' => 'listIntervencion',
	'uses' => 'IPEVR\IPEVRcontroller@listIntervencion',
]);

Route::get('/formIntervencion/{company_id}/{id_proceso}/{id_riesgo}', [
	'as' => 'formIntervencion',
	'uses' => 'IPEVR\IPEVRcontroller@formIntervencion',
]);

Route::post('/createIntervencion', [
	'as' => 'createIntervencion',
	'uses' => 'IPEVR\IPEVRcontroller@createIntervencion',
]);

Route::get('/editIntervencion/{company_id}/{id_proceso}/{id_riesgo}/{id_intervencion}', [
	'as' => 'editIntervencion',
	'uses' => 'IPEVR\IPEVRcontroller@editIntervencion',
]);

Route::post('/updateIntervencion', [
	'as' => 'updateIntervencion',
	'uses' => 'IPEVR\IPEVRcontroller@updateIntervencion',
]);

Route::get('/createExcelIPEVR/{company_id}', [
	'as' => 'createExcelIPEVR',
	'uses' => 'IPEVR\IPEVRcontroller@createExcelIPEVR',
]);

Route::post('/createArch', [
	'as' => 'createArch',
	'uses' => 'IPEVR\IPEVRcontroller@createArch',
]);

// peticiones ajax

Route::get('/riesgoSelect/{idPeligro}', [
	'as' => 'riesgoSelect',
	'uses' => 'IPEVR\IPEVRcontroller@riesgoSelect',
]);

Route::get('/selPeligro/{id_de_peligro}', [
	'as' => 'selPeligro',
	'uses' => 'IPEVR\IPEVRcontroller@selPeligro',
]);

Route::get('/descRiesgo/{id_tipoPeligro}', [
	'as' => 'descRiesgo',
	'uses' => 'IPEVR\IPEVRcontroller@descRiesgo',
]);

Route::get('/desc_riesgo/{id_pel}', [
	'as' => 'desc_riesgo',
	'uses' => 'IPEVR\IPEVRcontroller@desc_riesgo',
]);

Route::get('/descripcionRiesgo/{id_descripcion}', [
	'as' => 'descripcionRiesgo',
	'uses' => 'IPEVR\IPEVRcontroller@descripcionRiesgo',
]);

Route::get('/eliminacionIntervencion/{id_interv}', [
	'as' => 'eliminacionIntervencion',
	'uses' => 'IPEVR\IPEVRcontroller@eliminacionIntervencion',
]);

Route::get('/elemIntervencion/{id_interv}', [
	'as' => 'elemIntervencion',
	'uses' => 'IPEVR\IPEVRcontroller@elemIntervencion',
]);

Route::get('/contrlIngIntervencion/{id_interv}', [
	'as' => 'contrlIngIntervencion',
	'uses' => 'IPEVR\IPEVRcontroller@contrlIngIntervencion',
]);

Route::get('/ctrlAminIntervencion/{id_interv}', [
	'as' => 'ctrlAminIntervencion',
	'uses' => 'IPEVR\IPEVRcontroller@ctrlAminIntervencion',
]);

Route::get('/sustIntervencion/{id_interv}', [
	'as' => 'sustIntervencion',
	'uses' => 'IPEVR\IPEVRcontroller@sustIntervencion',
]);

Route::get('/verAdjuntosIpevr/{company_id}/{id_proceso}', 'IPEVR\IPEVRcontroller@verAdjuntosIpevr');

// fin rutas para Identificación de Peligros, Evaluación y Valoración de Riesgos

Route::get('/listHacerCliente/{company_id}', 'SST\SGSST\hacer\HacerController@indexHacerCliente');

Route::get('/listUserHacer/{company_id}', 'SST\SGSST\hacer\HacerController@index');

Route::get('/listDocHacer/{id}/{company_id}', 'SST\SGSST\hacer\HacerController@listDocHacer');

Route::get('/historicoAnualfile/{id_company}/{id_diganostico}/{anio}', [
	'as'=>'historicoAnualfile',
	'uses'=>'SST\SGSST\hacer\HacerController@historicoAnualfile'
]);

Route::post('/prueba316', 'SST\SGSST\hacer\HacerController@store316');
Route::post('/313Profesiograma', 'SST\SGSST\hacer\HacerController@store313');

Route::post('/314ExamenesMedicosLaboralespre', 'SST\SGSST\hacer\HacerController@store314');

Route::get('/exportHacerCliente/{id_company}', 'SST\SGSST\hacer\HacerController@export');

Route::post('/316GestiondeRestriccionesyRecomendaciones/{id}/{company_id}', 'SST\SGSST\hacer\HacerController@store316_1');

Route::post('/314ExamenesMedicos/{id}/{company_id}', 'SST\SGSST\hacer\HacerController@store3314');

Route::post('/316GestiondeRestriccionesyRecomendacionesMedicas2', 'SST\SGSST\hacer\HacerController@store316_2');

Route::post('/316GestiondeRestriccionesyRecomendacionesMedicas_3', 'SST\SGSST\hacer\HacerController@store316_3');

Route::post('/312SistemadeVigilanciaEpidiomologica', 'SST\SGSST\hacer\HacerController@store312');

Route::post('/317ProgramadeEstilodeVidaSaludables', 'SST\SGSST\hacer\HacerController@store317');

Route::post('/412MatrizI', 'SST\SGSST\hacer\HacerController@store412');

Route::post('/311consentimientoinformadomanejodatos', 'SST\SGSST\hacer\HacerController@storeV33111Hacer');
Route::post('/311formatoactualizaciondatospersonales', 'SST\SGSST\hacer\HacerController@store3311FADP');

Route::post('/adjuntEvidenciaH', 'SST\SGSSTFileController@createHacer');
// CLIENTE HACER

Route::get('/listaHacerVariableCliente/{id_submodulo}/{company_id}', 'SST\SGSST\hacer\HacerController@listaHacerVariableCliente');

Route::get('/verH/{id}/{id_rutaPDF}/{company_id}/{idE}', 'SST\SGSST\hacer\HacerController@verH');

Route::get('/verH2/{id}/{id_rutaPDF}/{company_id}', 'SST\SGSST\hacer\HacerController@verH2');

//**********INICIO ZONA EMPRESAS ANCLA**********//
Route::get('/companyAnclaInactive', [
	'as' => 'companyAnclaInactive',
	'uses' => 'CompanyAncla\CompanyAnclaController@companyAnclaInactive'
]);
Route::get('/contratistaAncla/{id}', [
	'as' => 'contratistaAncla',
	'uses' => 'CompanyAncla\CompanyAnclaController@indexcontra'
]);
Route::get('/usersAncla/{id_company}', [
	'as' => 'usersAncla',
	'uses' => 'Users\UsersController@showAncla'
]);
Route::get('/usersContratista/{id}', [
	'as' => 'usersContratista',
	'uses' => 'Users\UsersController@showContratista'
]);
Route::get('/createUsersContratista/{id}',[
		'as' => 'createUsersContratista',
		'uses' => 'Authentication\RegistrationController@registerUserContratista'
]);
Route::get('/createUsersAncla/{id_company}/{tipo}', [
	'as' => 'createUsersAncla',
	'uses' => 'Authentication\RegistrationController@registerUserAncla'
]);
Route::get('company/show2/{id_company}', [
	'as' => 'company/show2',
	'uses' => 'Company\CompanyController@show2'
]);
Route::get('/resultAuditoria', [
	'as' => 'resultAuditoria',
	'uses' => 'CompanyAncla\CompanyAnclaController@resultAuditoria'
]);
Route::get('listIndependiente/{id}', [
	'as' => 'listIndependiente',
	'uses' => 'CompanyAncla\CompanyAnclaController@listIndependiente'
]);

Route::get('/editUsersIdep/{id}', [
	'as' => 'editUsersIdep',
	'uses' => 'Users\UsersController@editUsersIdep',
]);

Route::post('upRssUser', [
	'as' => 'upRssUser',
	'uses' => 'CompanyAncla\CompanyAnclaController@upRssUser',
]);

Route::resource('/companyAncla', 'CompanyAncla\CompanyAnclaController');

// inicio empresas Matriz

Route::resource('/companyMatriz', 'CompanyMatriz\CompanyMatrizController');

// fin empresa matriz



//******rutas para las Bitácoras****

Route::get('/listBitacora/{id}', [
	'as' => 'listBitacora',
	'uses' => 'Company\BitacoraController@indexBitacora',
]);

Route::get('/newBitacora/{id}', [
	'as' => 'newBitacora',
	'uses' => 'Company\BitacoraController@createBitacora',
]);

Route::get('/newAsesorBitacora/{id}/{tipo}', [
	'as' => 'newAsesorBitacora',
	'uses' => 'Company\BitacoraController@newAsesorBitacora',
]);

Route::get('/updateBitacora/{id}/{company}', [
	'as' => 'updateBitacora',
	'uses' => 'Company\BitacoraController@updateBitacora',
]);

Route::get('/updateAsesorBitacora/{id}/{company}/{tipo}', [
	'as' => 'updateAsesorBitacora',
	'uses' => 'Company\BitacoraController@updateAsesorBitacora',
]);

Route::get('/analistaBitacora/{id}/{company}', [
	'as' => 'analistaBitacora',
	'uses' => 'Company\BitacoraController@verAnalistaBitacora',
]);

Route::get('/obtenerAdjunto', [
	'as' => 'obtenerAdjunto',
	'uses' => 'Company\BitacoraController@obtenerAdjunto',
]);

//envia variables al controller para registrar en BD.
Route::post('/storenewBitacora', [
	'as' => 'storenewBitacora',
	'uses' => 'Company\BitacoraController@storenewBitacora',
]);

Route::post('/storenewBitacoraAsesor', [
	'as' => 'storenewBitacoraAsesor',
	'uses' => 'Company\BitacoraController@storenewBitacoraAsesor',
]);

Route::post('/editBitacora', [
	'as' => 'editBitacora',
	'uses' => 'Company\BitacoraController@editBitacora',
]);

Route::post('/adjuntarData/{tipo}', [
	'as' => 'adjuntarData',
	'uses' => 'Company\BitacoraController@adjuntarData',
]);



//Rutas Excel
Route::get('/bitacoraExcel', [
	'as' => 'bitacoraExcel',
	'uses' => 'Company\BitacoraController@bitacoraExcel',
]);

Route::get('/bitacoraAsesorExcel', [
	'as' => 'bitacoraAsesorExcel',
	'uses' => 'Company\BitacoraController@bitacoraAsesorExcel',
]);

Route::get('/selecAnio/{anio}/{id}/{tipo}/{id_asesor}/{id_user}', [
	'as' => 'selecAnio',
	'uses' => 'Company\BitacoraController@selecAnio',
]);

Route::get('/selecAnioAsesor/{anio}/{id}/{company}', [
	'as' => 'selecAnioAsesor',
	'uses' => 'Company\BitacoraController@selecAnioAsesor',
]);

Route::get('/asesorBitacora/{id}/{tipo}/{id_asesor}/{id_user}', [
	'as' => 'asesorBitacora',
	'uses' => 'Company\BitacoraController@asesorBitacora',
]);

Route::get('/listarAsesorBitacora/{id}/{tipo}/{company}', [
	'as' => 'listarAsesorBitacora',
	'uses' => 'Company\BitacoraController@listarAsesorBitacora',
]);

Route::post('/enviarcorreosBitacora', [
	'as' => 'enviarcorreosBitacora',
	'uses' => 'Company\BitacoraController@enviarcorreosBitacora',
]);

Route::get('/verEmailbitacora/{id}/{tipo}', [
	'as' => 'verEmailbitacora',
	'uses' => 'Company\BitacoraController@verEmailbitacora'
]);

Route::get('/aviso/{id}', [
	'as' => 'aviso',
	'uses' => 'Company\BitacoraController@aviso'
]);

Route::get('/avisoAsesor/{id}', [
	'as' => 'avisoAsesor',
	'uses' => 'Company\BitacoraController@avisoAsesores'
]);

Route::get('/notificacionAsesor/{company}', [
	'as' => 'notificacionAsesor',
	'uses' => 'Company\BitacoraController@notificacionAsesor'
]);

Route::get('/notificacion/{company}', [
	'as' => 'notificacion',
	'uses' => 'Company\BitacoraController@notificacion'
]);


//**********FIN ZONA EMPRESAS ANCLA**********//

// RUTA PARA ZIPARCHIVE
Route::get('/zip', function () {
	//return view('SST/SGSST/hacer/formularios/311PerfilSociodemografico/311');
	//return "Done!";
});

Route::post('/311PerfilSociodemografico', [
	'as' => '311PerfilSociodemografico',
	'uses' => 'SST\SGSST\hacer\HacerController@v311Hacer_FAD',
]);

//función excel items 1.1.7, 1.1.8, 1.2.1, 2.4.1
Route::get('/item117Excel/{id_item}/{company_id}/{id_fila}', 'Excel\ExcelController@item117Excel');
Route::get('/item121Excel/{id_item}/{company_id}/{id_fila}', 'Excel\ExcelController@item121Excel');
Route::get('/item241Excel/{id_item}/{company_id}/{id_fila}', 'Excel\ExcelController@item241Excel');

// ***************Rutas y funciones para el módulo de planes comerciales*******************///


Route::get('/planesComerciales', [
	'as' => 'planesComerciales',
	'uses' => 'PlanesModulos\PlanesModulosController@index'
]);
Route::get('/listCiaPlan', [
	'as' => 'listCiaPlan',
	'uses' => 'PlanesModulos\PlanesModulosController@indexModulo'
]);


Route::resource('precios', 'Company\PrecioController');
Route::resource('planes', 'PlanesModulos\PlanesController');
Route::resource('planesClientes', 'PlanesModulos\PlanesModulosController');

Route::get('planesClientes/{id}/{tipoUp}', [
	'as' => 'planesClientes',
	'uses' => 'PlanesModulos\PlanesModulosController@edit'
]);


//***********fin planes comerciales y precios ***************************//

Route::get('/docblanco/{id}/{company_id}', 'SST\SGSST\planear\PlanearController@Documento');

Route::get('/docblancoH/{id}/{company_id}', 'SST\SGSST\hacer\HacerController@DocumentoH');

//CONTRATO TEXTO Y PROPUESTA TEXTO
Route::resource('/contratoTexto', 'Contratos\ContratoTextoController');
Route::resource('/propuestaTexto', 'Contratos\PropuestaTextoController');

//listas contratistas

Route::get('/listaChequeo/{id}', 'Company\listCheckController@listaChequeo');

/*LISTA DE CHEQUEO USUARIO*/
Route::get('/listaChequeoUsers/{id}/{id_user}', 'Users\listCheckUsersController@listaChequeoUsers');
Route::post('/createDatosEmp', [
	'as' => 'createDatosEmp',
	'uses' => 'Users\listCheckUsersController@createDatosEmp',
]);
Route::get('/panelCheckDos/{id_user}', [
	'as' => 'panelCheckDos',
	'uses' => 'Users\listCheckUsersController@panelCheckDos',
]);
Route::get('/listPDF/{id_user}', 'Users\listCheckUsersController@listPDF');

// lista de chequeo Mateo
Route::post('/createDatos', [
	'as' => 'createDatos',
	'uses' => 'Company\listCheckController@createDatos',
]);

Route::get('/panelCheck/{id}', 'Company\listCheckController@panelCheck');

Route::get('/listaPDF/{id_etapaPre}', 'Company\listCheckController@listaPDF');

//*******************************RUTAS DE SALUD LABORAL ************************************//

Route::get('/ausentismo/{id_empresa}', [
	'as' => 'ausentismo',
	'uses' => 'AusentismoLaboral\ausentismoController@ausentismo',
]);

Route::get('/buscarausentismo/{id_empresa}', [
	'as' => 'buscarausentismo',
	'uses' => 'AusentismoLaboral\ausentismoController@buscarausentismo',
]);
Route::get('/ausentismoUser', [
	'as' => 'ausentismoUser',
	'uses' => 'AusentismoLaboral\ausentismoController@ausentismoUser',
]);
Route::get('/ausentismoCont/{id}', 'AusentismoLaboral\ausentismoController@ausentismoCont');

Route::get('/listAusentismo/{id_empresa}', [
	'as' => 'listAusentismo',
	'uses' => 'AusentismoLaboral\ausentismoController@listAusentismo',
]);

Route::get('/newAusentismoLaboral/{id_empresa}/{id_emp}', [
	'as' => 'newAusentismoLaboral',
	'uses' => 'AusentismoLaboral\ausentismoController@newAusentismoLaboral',
]);

Route::get('/ausentismoForm/{id_empresa}/{id_emp}', [
	'as' => 'ausentismoForm',
	'uses' => 'AusentismoLaboral\ausentismoController@ausentismoForm',
]);

Route::get('/historialAusentismosEmp/{id_emp}', [
	'as' => 'historialAusentismosEmp',
	'uses' => 'AusentismoLaboral\ausentismoController@historialAusentismosEmp',
]);

Route::post('/createAusentismo', [
	'as' => 'createAusentismo',
	'uses' => 'AusentismoLaboral\ausentismoController@createAusentismo',
]);

Route::get('/prorrogaForm/{id_ausentismo}/{id_empleado}', [
	'as' => 'prorrogaForm',
	'uses' => 'AusentismoLaboral\ausentismoController@prorrogaForm',
]);

Route::get('/listPro/{id_ausentismo}', [
	'as' => 'listPro',
	'uses' => 'AusentismoLaboral\ausentismoController@listPro',
]);

Route::post('/prorrogaCreate', [
	'as' => 'prorrogaCreate',
	'uses' => 'AusentismoLaboral\ausentismoController@prorrogaCreate',
]);

Route::post('/fechaCaducada', [
	'as' => 'fechaCaducada',
	'uses' => 'AusentismoLaboral\ausentismoController@fechaCaducada',
]);

Route::post('/editAusentismo', [
	'as' => 'editAusentismo',
	'uses' => 'AusentismoLaboral\ausentismoController@editAusentismo',
]);

Route::get('/editausentismoForm/{id_ausentismo}', [
	'as' => 'editausentismoForm',
	'uses' => 'AusentismoLaboral\ausentismoController@editausentismoForm',
]);

Route::post('/editarprorroga', [
	'as' => 'editarprorroga',
	'uses' => 'AusentismoLaboral\ausentismoController@editarprorroga',
]);

Route::get('/editprorrogaForm/{id_ausentismo}/{id_pro}', [
	'as' => 'editprorrogaForm',
	'uses' => 'AusentismoLaboral\ausentismoController@editprorrogaForm',
]);


// INFORMES EN PDF ANUAL Y ANUAL + MES
Route::get('/pdfMes_Anio/{mes}/{anio}/{id_empresa}', 'AusentismoLaboral\ausentismoController@pdfMes_Anio');
Route::get('/pdfAnual/{anio}/{id_empresa}', 'AusentismoLaboral\ausentismoController@pdfAnual');
Route::get('/excelAnual/{anio}/{id_empresa}', 'AusentismoLaboral\ausentismoController@excelAnual');
// INFORMES EN PDF ANUAL YANUAL + MES

Route::get('/archivosProrrogas/{id_pro}', 'AusentismoLaboral\ausentismoController@archivosProrrogas');
Route::get('/listInformeMedico/{id_empresa}', [
	'as' => 'listInformeMedico',
	'uses' => 'AusentismoLaboral\informeController@listInformeMedico',
]);
Route::post('/subirInformeMedico', 'AusentismoLaboral\informeController@subirInformeMedico');
Route::get('/listArchivosInformeMedicos/{id_user}/{id_empresa}', 'AusentismoLaboral\informeController@listArchivosInformeMedicos');
Route::get('/informesVencidos/{id_empresa}', 'AusentismoLaboral\informeController@informesVencidos');
Route::post('/enviardatosinforme', [
	'as' => 'enviardatosinforme',
	'uses' => 'AusentismoLaboral\informeController@enviardatosinforme',
]);
Route::get('/emailusers/{id}', [
	'as' => 'emailusers',
	'uses' => 'AusentismoLaboral\informeController@mostrardatosusers',
]);

Route::post('/editEstadoInfo', [
	'as' => 'editEstadoInfo',
	'uses' => 'AusentismoLaboral\informeController@editEstadoInfo',
]);

Route::get('/ausentismoExcel', [
	'as' => 'ausentismoExcel',
	'uses' => 'Excel\ExcelController@ausentismoExcel',
]);
Route::get('/aptitudExcel', [
	'as' => 'aptitudExcel',
	'uses' => 'Excel\ExcelController@aptitudExcel',
]);

Route::get('/procesosExcel', [
	'as' => 'procesosExcel',
	'uses' => 'Excel\ExcelController@procesosExcel',
]);

Route::get('/accidentesExcel', [
	'as' => 'accidentesExcel',
	'uses' => 'Excel\ExcelController@accidentesExcel',
]);
\

	Route::get('/rehabilitacionExcel', [
			'as' => 'rehabilitacionExcel',
			'uses' => 'Excel\ExcelController@rehabilitacionExcel',
		]);

// peticiones ajax
Route::get('/prorroga/{id_ausentismo}/{id_emp}', 'AusentismoLaboral\ausentismoController@prorroga');
Route::get('/diagnostico/{id_CIE}', 'AusentismoLaboral\ausentismoController@diagnostico');
Route::get('/archivo/{id_pro}', 'AusentismoLaboral\ausentismoController@archivo');
Route::get('/archivo2/{id_pro}', 'AusentismoLaboral\ausentismoController@archivo2');
// peticiones ajax

Route::get('/rehabilitacion/{id_sede}', 'AusentismoLaboral\rehabilitacionController@rehabilitacion');
Route::get('/rehabilitacion', ['as' => 'rehabilitacion', 'uses' => 'AusentismoLaboral\rehabilitacionController@index']);
//informes medicos por JF

//Rutas de vigilancia
Route::get('/listVigilancia/{id_empresa}', 'AusentismoLaboral\vigilanciaController@listVigilancia');
Route::get('/listVigilanciasU/{id_user}/{id_empresa}', 'AusentismoLaboral\vigilanciaController@listVigilanciasU');
Route::get('/vigFormulario/{id_user}/{id_empresa}', 'AusentismoLaboral\vigilanciaController@vigFormulario');
Route::get('/observacionesList/{id_user}/{id_empresa}/{id_vigilancia}', 'AusentismoLaboral\vigilanciaController@observacionesList');
Route::get('/observacionesForm/{id_user}/{id_empresa}/{id_vigilancia}', 'AusentismoLaboral\vigilanciaController@observacionesForm');
Route::get('/datosEspecificosVig/{id_vigilancia}', 'AusentismoLaboral\vigilanciaController@datosEspecificosVig');
Route::get('/VerArchivosVigilancia/{id_vigilancia}', 'AusentismoLaboral\vigilanciaController@VerArchivosVigilancia');
Route::post('/createVigilancia', 'AusentismoLaboral\vigilanciaController@createVigilancia');
Route::post('/createObservaciones', 'AusentismoLaboral\vigilanciaController@createObservaciones');
Route::post('/enviardatosProceso', [
	'as' => 'enviardatosProceso',
	'uses' => 'AusentismoLaboral\vigilanciaController@enviardatosProceso',
]);
Route::get('/archivosVigilancia/{id_empresa}/{id_doc}/{id_user}', 'AusentismoLaboral\vigilanciaController@archivosVigilancia');
Route::post('/upArchivosVigilancia', 'AusentismoLaboral\vigilanciaController@upArchivosVigilancia');

//FORMULARIO – ENCUESTA DE MORBILIDAD MODULO DE SALUD LABORAL 

Route::resource('encuestaMorbilidad', 'AusentismoLaboral\MorbiditySurveyController')->names([
    'store' => 'encuestaMorbilidad.store'
]);
Route::get('/gracias', function() {
    return view('encuestaMorbilidad');
})->name('encuestaMorbilidad');

//*******************************FIN RUTAS DE SALUD LABORAL ************************************///
//***************AGENDA**********************************//
Route::get('/emailusers/{id_user}', 'AusentismoLaboral\informeController@mostrardatosusers');
Route::get('/agenda/{id?}', 'Agenda\AgendaController@index');
Route::any('/obtenerEvento', 'Agenda\AgendaController@obtener_evento');
Route::any('/describirEvento/{id}', 'Agenda\AgendaController@describir_evento');
Route::any('/borrarEvento/{id}', 'Agenda\AgendaController@destroy')->name('borrarEvento');
Route::any('/editEvento/{id}', 'Agenda\AgendaController@update');
Route::any('/storeEvento', 'Agenda\AgendaController@store');


Route::any('/storerevision', [
	'as' => 'storerevision',
	'uses' => 'Agenda\AgendaController@revision'
]);
Route::get('/contratistassus/{contratista}/{idUser}', 'Agenda\AgendaController@contratistassus');
Route::get('/emailsus/{iduser}', 'Agenda\AgendaController@emailsus');

//Rutas Cuatro R :: DAVID M
Route::get('/listPrograma/{id_empresa}', 'AusentismoLaboral\ProgramarController@listPrograma');
Route::get('/listProgramaU/{id_user}/{id_empresa}', 'AusentismoLaboral\ProgramarController@listProgramaU');
Route::get('/programaForm/{id_user}/{id_empresa}', 'AusentismoLaboral\ProgramarController@programaForm');
Route::post('/createPrograma', 'AusentismoLaboral\ProgramarController@createPrograma');
Route::get('/editPrograma/{id_programa}/{id_user}/{id_empresa}', 'AusentismoLaboral\ProgramarController@editPrograma');
Route::get('/listProrroga/{id_user}/{id_programa}/{id_empresa}', 'AusentismoLaboral\ProgramarController@listProrroga');
Route::get('/formProrroga/{id_user}/{id_programa}/{id_empresa}', 'AusentismoLaboral\ProgramarController@formProrroga');
Route::post('/createProrroga', 'AusentismoLaboral\ProgramarController@createProrroga');
Route::resource('programaR', 'AusentismoLaboral\ProgramarController');
Route::get('/archivosPrograma/{id_programa}', 'AusentismoLaboral\ProgramarController@archivosPrograma');

//Rutas Accidente Laboral :: DAVID M
Route::post('/createAccidentePlanes', 'AusentismoLaboral\AccidenteLaboralController@createAccidentePlanes');
Route::get('/planesForm/{id_user}/{id_accidente}/{id_empresa}', 'AusentismoLaboral\AccidenteLaboralController@planesForm');
Route::get('/detallePlanes/{id_user}/{id_accidente}/{id_empresa}/', 'AusentismoLaboral\AccidenteLaboralController@detallePlanes');
Route::get('/editPlanesMejora/{id_user}/{id_accidente}/{id_empresa}/{id}', [
	'as' => 'editPlanesMejora',
	'uses' => 'AusentismoLaboral\AccidenteLaboralController@editPlanesMejora'
]);
Route::POST('/updatePlanesMejora', [
	'as' => 'updatePlanesMejora',
	'uses' => 'AusentismoLaboral\AccidenteLaboralController@updatePlanesMejora'
]);

Route::get('/listAccidente/{id_empresa}', 'AusentismoLaboral\AccidenteLaboralController@listAccidente');
Route::get('/listAccidenteU/{id_user}/{id_empresa}', 'AusentismoLaboral\AccidenteLaboralController@listAccidenteU');
Route::get('/accidenteForm/{id_user}/{id_empresa}', 'AusentismoLaboral\AccidenteLaboralController@accidenteForm');
Route::post('/createAccidente', 'AusentismoLaboral\AccidenteLaboralController@createAccidente');
Route::get('/editAccidente/{id_accidente}/{id_user}/{id_empresa}', 'AusentismoLaboral\AccidenteLaboralController@editAccidente');
Route::resource('accidenteL', 'AusentismoLaboral\AccidenteLaboralController');
Route::get('/listInvestigacion/{id_user}/{id_accidente}/{id_empresa}', 'AusentismoLaboral\AccidenteLaboralController@listInvestigacion');
Route::get('/investigacionForm/{id_user}/{id_accidente}/{id_empresa}', 'AusentismoLaboral\AccidenteLaboralController@investigacionForm');
Route::post('/createInvestigacion', 'AusentismoLaboral\AccidenteLaboralController@createInvestigacion');
Route::get('/archivosIvg/{id_investigacion}', 'AusentismoLaboral\AccidenteLaboralController@archivosIvg');
Route::get('/detalleInvestigacion/{id_empresa}/{id_user}/{id_accidente}/{id_investigacion}', 'AusentismoLaboral\AccidenteLaboralController@detalleInvestigacion');
Route::get('/archivosAccidente/{id_accidente}', 'AusentismoLaboral\AccidenteLaboralController@archivosAccidente');
Route::get('/planespdf/{id_user}/{id_accidente}/{id_empresa}', [
	'as' => 'planespdf',
	'uses' => 'AusentismoLaboral\AccidenteLaboralController@planespdf',
]);
Route::get('/investigacionpdf/{id_empresa}/{id_user}/{id_accidente}/{id_investigacion}', [
	'as' => 'investigacionpdf',
	'uses' => 'AusentismoLaboral\AccidenteLaboralController@investigacionpdf',
]);
Route::post('/investigacionFirmada', [
	'as' => 'investigacionFirmada',
	'uses' => 'AusentismoLaboral\AccidenteLaboralController@investigacionFirmada',
]);
Route::get('/listInvestigacionFirmada/{id_investigacion}/{id_user}/{id_accidente}/{id_empresa}', [
	'as' => 'listInvestigacionFirmada',
	'uses' => 'AusentismoLaboral\AccidenteLaboralController@listInvestigacionFirmada',
]);
Route::post('/enviardatosAccidentes', [
	'as' => 'enviardatosAccidentes',
	'uses' => 'AusentismoLaboral\AccidenteLaboralController@enviardatosAccidentes',
]);

Route::get('/detalleEmailAccidentes/{id_accidente}', [
	'as' => 'detalleEmailAccidentes',
	'uses' => 'AusentismoLaboral\AccidenteLaboralController@detalleEmailAccidentes',
]);

Route::get('/verAccidentePdf/{id_accidente}/{id_company}', 'AusentismoLaboral\AccidenteLaboralController@verPdfAccidente');


//Informes
Route::post('/cambiarEstadoInformes', 'AusentismoLaboral\ausentismoController@cambiarEstadoInformes');


//RUTAS DE INFORMES PROXIMOS A VENCER BY MATEO

//Salud Laboral
Route::get('/graficosEinformes/{id_empresa}', 'AusentismoLaboral\ausentismoController@graficosEinformes');
Route::get('/informeSaludLaboral/{id_empresa}', 'AusentismoLaboral\ausentismoController@informeSaludLaboral');
Route::post('/enviarAptitud', 'AusentismoLaboral\ausentismoController@enviarAptitud');
Route::post('/enviarRehabilitacion', 'AusentismoLaboral\ausentismoController@enviarRehabilitacion');

//registro de correos enviados

Route::get('/detalleEmail/{id_informeMedico}', [
	'as' => 'detalleEmail',
	'uses' => 'AusentismoLaboral\informeController@detalleEmail',
]);


//registro de correos enviados Ausentismo

Route::get('/detalleEmailAusentismo/{id_ausentismo}', [
	'as' => 'detalleEmailAusentismo',
	'uses' => 'AusentismoLaboral\ausentismoController@detalleEmailAusentismo',
]);

//registro de correos enviados Rehabilitacion

Route::get('/detalleEmailRehabilitacion/{id_programa}', [
	'as' => 'detalleEmailRehabilitacion',
	'uses' => 'AusentismoLaboral\ausentismoController@detalleEmailRehabilitacion',
]);

//registro correos vigilancia
Route::get('/detalleEmailProceso/{id_vigilancia}', [
	'as' => 'detalleEmailProceso',
	'uses' => 'AusentismoLaboral\vigilanciaController@detalleEmailProceso',
]);

//correo salud laboral

//correo salud laboral
Route::get('/emailusersInfo/{id_informeMedico}', 'AusentismoLaboral\ausentismoController@emailusersInfo');
Route::get('/emailusersAu/{id_ausentismo}', 'AusentismoLaboral\ausentismoController@emailusersAu');
Route::get('/emailusersPro/{id_pro}', 'AusentismoLaboral\ausentismoController@emailusersPro');
Route::post('/enviarAusentismo', 'AusentismoLaboral\ausentismoController@enviarAusentismo');
Route::post('/enviarPro', 'AusentismoLaboral\ausentismoController@enviarPro');
Route::post('/enviarAccidente', 'AusentismoLaboral\ausentismoController@enviarAccidente');
Route::get('/emailusersAcci/{id_investigacion}', 'AusentismoLaboral\ausentismoController@emailusersAcci');
Route::post('/enviarRehabilitacion', 'AusentismoLaboral\ausentismoController@enviarRehabilitacion');
Route::get('/emailusersReh/{id_programa}', 'AusentismoLaboral\ausentismoController@emailusersReh');

//Capacitaciones
Route::get('/graficosEinformesCap', 'Capacitaciones\CapacitacionesController@graficosEinformesCap');
Route::get('/informeCapacitaciones/{id_empresa}', 'Capacitaciones\CapacitacionesController@informeCapacitaciones');
Route::post('/enviarCap', 'Capacitaciones\CapacitacionesController@enviarCap');
Route::get('/emailusersCap/{id_user}', 'Capacitaciones\CapacitacionesController@emailusersCap');
//SGSST

Route::get('/informeSgsst/{id}', 'SST\LineaBResultadoController@informeSgsst');
Route::post('/enviarSgsst', 'SST\LineaBResultadoController@enviarSgsst');
Route::get('/emailusersSgsst/{id_tvlb}', 'SST\LineaBResultadoController@emailusersSgsst');

//SOCIODEMOGRAFICO

Route::get('/graficosEinformesSoc', 'Users\UserController@graficosEinformesSoc');
Route::get('/informeSociodemografico/{id_empresa}', 'Users\UserController@informeSociodemografico');
//Seguridad Social

Route::get('/informeSS', 'Company\CompanyController@informeSS');
Route::post('/enviarSS', 'Company\CompanyController@enviarSS');
Route::get('/emailusersSS/{id_rss}', 'Company\CompanyController@emailusersSS');
Route::post('/enviarSocio', 'Users\UserController@enviarSocio');

//fin rutas correos

//ruta para mostrar total sgsst
Route::get('/result2/{id_company}', 'Company\CompanyController@result2');

//**********************RUTAS PARA PROYECTOS
Route::resource('/proyecto', 'Company\ProyectoController');

// Rutas de Areas
Route::resource('/area', 'Company\AreaController');

Route::get('areas/{id_company}',[
	'as'=>'areas',
	'uses'=>'Company\AreaController@index'
]);


// Rutas de cargos
Route::resource('/cargo', 'Users\CargoController');

// Rutas de proyectos(Nuevo desarrollo)
Route::resource('/proyectos', 'Proyectos\ProyectoController');

// Ruta para lista de sedes del proyecto
Route::get('/listSedesProyecto/{id_proyecto}/{accion}', [
	'as' => 'listSedesProyecto',
	'uses' => 'Proyectos\ProyectoController@listSedesProyecto',
]);

// Ruta para agregar nuevas sedes al proyecto
Route::post('/addSedeProyecto', [
	'as' => 'addSedeProyecto',
	'uses' => 'Proyectos\ProyectoController@addSedeProyecto',
]);

// Ruta Para retirar sedes de un proyecto
Route::post('/removeSedeProyecto', [
	'as' => 'removeSedeProyecto',
	'uses' => 'Proyectos\ProyectoController@removeSedeProyecto',
]);

// Ruta para lista de contratistas del proyecto
Route::get('/listContratistasProyecto/{id_proyecto}/{accion}', [
	'as' => 'listContratistasProyecto',
	'uses' => 'Proyectos\ProyectoController@listContratistasProyecto',
]);

// Ruta para agregar nuevos contratistas al proyecto
Route::post('/addContratistaProyecto', [
	'as' => 'addContratistaProyecto',
	'uses' => 'Proyectos\ProyectoController@addContratistaProyecto',
]);

// Ruta para actualizar datos proyecto del contratista
Route::post('/dataContratistaProyecto', [
	'as' => 'dataContratistaProyecto',
	'uses' => 'Proyectos\ProyectoController@dataContratistaProyecto',
]);

// Ruta para retirar contratistas del proyecto
Route::post('/removeContratistaProyecto', [
	'as' => 'removeContratistaProyecto',
	'uses' => 'Proyectos\ProyectoController@removeContratistaProyecto',
]);

// Ruta para listar empleados de los contratistas
Route::get('/listEmpleadosContratistaProyecto/{id_proyecto}/{id_contratista}/{accion}', [
	'as' => 'listEmpleadosContratistaProyecto',
	'uses' => 'Proyectos\ProyectoController@listEmpleadosContratistaProyecto',
]);

// Ruta para listar ampliaciones del proyecto
Route::get('/listAmpliacionesProyecto/{id_proyecto}/{accion}', [
	'as' => 'listAmpliacionesProyecto',
	'uses' => 'Proyectos\ProyectoController@listAmpliacionesProyecto',
]);

// Ruta para crear ampliación
Route::post('/addAmpliacionProyecto', [
	'as' => 'addAmpliacionProyecto',
	'uses' => 'Proyectos\ProyectoController@addAmpliacionProyecto',
]);

Route::post('/removeAmpliacionProyecto', [
	'as' => 'removeAmpliacionProyecto',
	'uses' => 'Proyectos\ProyectoController@removeAmpliacionProyecto',
]);

// Ruta para listar archivos del proyecto
Route::get('/listArchivosProyecto/{id_proyecto}/{accion}', [
	'as' => 'listArchivosProyecto',
	'uses' => 'Proyectos\ProyectoController@listArchivosProyecto',
]);

// Ruta para añadir adjuntos al proyecto
Route::post('/addArchivosProyecto', [
	'as' => 'addArchivosProyecto',
	'uses' => 'Proyectos\ProyectoController@addArchivosProyecto',
]);

// Ruta para retirar un archivo del proyecto
Route::post('/removeArchivosProyecto', [
	'as' => 'removeArchivosProyecto',
	'uses' => 'Proyectos\ProyectoController@removeArchivosProyecto',
]);

// Lista de contratos
Route::get('/listContratosProyecto/{id_proyecto}/{accion}', [
	'as' => 'listContratosProyecto',
	'uses' => 'Proyectos\ProyectoController@listContratosProyecto',
]);

// Añade contrato
Route::post('/addContratoProyecto', [
	'as' => 'addContratoProyecto',
	'uses' => 'Proyectos\ProyectoController@addContratoProyecto',
]);

Route::post('/removeContratoProyecto', [
	'as' => 'removeContratoProyecto',
	'uses' => 'Proyectos\ProyectoController@removeContratoProyecto',
]);

Route::get('/listAmpliacionesContratosProyecto/{id_contrato}/{accion}', [
	'as' => 'listAmpliacionesContratosProyecto',
	'uses' => 'Proyectos\ProyectoController@listAmpliacionesContratosProyecto',
]);

Route::post('/addAmpliacionContratoProyecto', [
	'as' => 'addAmpliacionContratoProyecto',
	'uses' => 'Proyectos\ProyectoController@addAmpliacionContratoProyecto',
]);

Route::post('/removeAmpliacionContratoProyecto', [
	'as' => 'removeAmpliacionContratoProyecto',
	'uses' => 'Proyectos\ProyectoController@removeAmpliacionContratoProyecto',
]);

// Ruta para listar los empleados que participan en el proyecto
Route::get('/listEmpleadosProyecto/{id_proyecto}/{accion}', [
	'as' => 'listEmpleadosProyecto',
	'uses' => 'Proyectos\ProyectoController@listEmpleadosProyecto',
]);

// Ruta para agregar empleados al proyecto
Route::post('/addEmpleadosProyecto', [
	'as' => 'addEmpleadosProyecto',
	'uses' => 'Proyectos\ProyectoController@addEmpleadosProyecto',
]);

// Lista general de empleados
Route::get('/listaGeneralEmpleadosProyecto/{id_proyecto}', 'Proyectos\ProyectoController@listaGeneralEmpleadosProyecto');

Route::post('/removeEmpleadoProyecto', [
	'as' => 'removeEmpleadoProyecto',
	'uses' => 'Proyectos\ProyectoController@removeEmpleadoProyecto',
]);

Route::get('/listResponsablesProyecto/{id_proyecto}/{accion}', [
	'as' => 'listResponsablesProyecto',
	'uses' => 'Proyectos\ProyectoController@listResponsablesProyecto',
]);

Route::post('/addResponsableProyecto', [
	'as' => 'addResponsableProyecto',
	'uses' => 'Proyectos\ProyectoController@addResponsableProyecto',
]);

Route::post('/removeResponsableProyecto', [
	'as' => 'removeResponsableProyecto',
	'uses' => 'Proyectos\ProyectoController@removeResponsableProyecto',
]);

Route::get('/cotizarProject/{id_proyecto}', 'Proyectos\ProyectoController@cotizarProject');

Route::post('/cotizacionProject', [
	'as' => 'cotizacionProject',
	'uses' => 'Proyectos\ProyectoController@cotizacionProject',
]);

Route::get('/cotizarProjectFinalizado/{id_proyecto}', 'Proyectos\ProyectoController@cotizarProjectFinalizado');

// Rutas Perfil Sociodemografico
Route::get('/perfilSD/{id_user}', [
	'as' => 'perfilSD',
	'uses' => 'Users\UsersController@perfilSD',
]);



Route::put('/perfilSDUP/{id_user}', [
	'as' => 'perfilSDUP',
	'uses' => 'Users\UsersController@perfilSDUP',
]);
Route::get('/avatarProfile/{id}', [
	'as' => 'avatarProfile',
	'uses' => 'Users\UsersController@avatarProfile'
]);

Route::get('/archivosPerfilsocio/{idUser}', 'Users\UsersController@archivosPerfilsocio');

/////************INICIO Indicadores SG-SST****************///////

Route::any('/ListIndicadores/{id_empresa}/{idISG}', 'indicaSGSST\IndicaSGSSTController@index');

Route::get('/listIndica/{id}', [
	'as' => 'listIndica',
	'uses' => 'indicaSGSST\IndicaSGSSTController@index1'
]);


// RUTA NUEVA 20-04-24
Route::get('/updateIndicador/{id_empresa}/{id_indicador}', [
	'as' => 'updateIndicador',
	'uses' => 'indicaSGSST\IndicaSGSSTController@updateIndicador'
]);

Route::get('/GraficasIndica/{id}', [
	'as' => 'GraficasIndica',
	'uses' => 'indicaSGSST\graficasIndicadoresController@GraficasIndica'
]);

Route::get('/newIndicadores', 'indicaSGSST\IndicaSGSSTController@register');

Route::post('/createIndicadores', 'indicaSGSST\IndicaSGSSTController@create');

Route::any('/editIndicadores/{idISG}', 'indicaSGSST\IndicaSGSSTController@edit');

Route::any('/updateIndicadores', 'indicaSGSST\IndicaSGSSTController@update');

Route::any('/Selectindica', 'indicaSGSST\IndicaSGSSTController@listarindicadores');

Route::get('/emailusersInfo/{id_informeMedico}', 'AusentismoLaboral\ausentismoController@emailusersInfo');

Route::get('/formIndObligatorios/{id_empresa}/{idISG}', [
	'as' => 'formIndObligatorios',
	'uses' => 'indicaSGSST\IndicaSGSSTController@formIndObligatorios',
]);

Route::post('/createObligatorios', 'indicaSGSST\IndicaSGSSTController@createObligatorios');

Route::get('/formIndEstructura/{id_empresa}/{idISG}', 'indicaSGSST\IndicaSGSSTController@formIndEstructura');

Route::post('/createEstructura', 'indicaSGSST\IndicaSGSSTController@createEstructura');

Route::get('/formIndProceso/{id_empresa}/{idISG}', 'indicaSGSST\IndicaSGSSTController@formIndProceso');

Route::post('/createProceso', 'indicaSGSST\IndicaSGSSTController@createProceso');

Route::get('/formIndResultado/{id_empresa}/{idISG}', 'indicaSGSST\IndicaSGSSTController@formIndResultado');

Route::post('/createResultado', 'indicaSGSST\IndicaSGSSTController@createResultado');

Route::get('/consultaAnio/{anio}/{idISG}/{id_empresa}', 'indicaSGSST\IndicaSGSSTController@consultaAnio');

Route::get('/consultaAnio2/{anio}/{idISG}/{id_empresa}', 'indicaSGSST\IndicaSGSSTController@consultaAnio2');

Route::get('/consultaAnio3/{anio}/{idISG}/{id_empresa}', 'indicaSGSST\IndicaSGSSTController@consultaAnio3');

Route::get('/consultaAnio4/{anio}/{idISG}/{id_empresa}', 'indicaSGSST\IndicaSGSSTController@consultaAnio4');

Route::get('/listGeneral/{id_empresa}', 'indicaSGSST\IndicaSGSSTController@listGeneral');

Route::post('/pdfIndica', 'indicaSGSST\IndicaSGSSTController@pdfIndica');

Route::get('/pdfObli/{anio}/{idISG}/{id_empresa}', 'indicaSGSST\IndicaSGSSTController@pdfObli');

Route::get('/pdfEstru/{anio}/{idISG}/{id_empresa}', 'indicaSGSST\IndicaSGSSTController@pdfEstru');

Route::get('/pdfProce/{anio}/{idISG}/{id_empresa}', 'indicaSGSST\IndicaSGSSTController@pdfProce');

Route::get('/pdfResult/{anio}/{idISG}/{id_empresa}', 'indicaSGSST\IndicaSGSSTController@pdfResult');

Route::post('/createExcelIndi', 'indicaSGSST\IndicaSGSSTController@createExcelIndi');

Route::get('/BorrarObligatorio/{id}', [
	'as' => 'BorrarObligatorio',
	'uses' => 'indicaSGSST\IndicaSGSSTController@BorrarObligatorio'
]);


Route::get('/BorrarEstructura/{id}', [
	'as' => 'BorrarEstructura',
	'uses' => 'indicaSGSST\IndicaSGSSTController@BorrarEstructura'
]);


Route::get('/BorrarProceso/{id}', [
	'as' => 'BorrarProceso',
	'uses' => 'indicaSGSST\IndicaSGSSTController@BorrarProceso'
]);

Route::get('/BorrarResultado/{id}', [
	'as' => 'BorrarResultado',
	'uses' => 'indicaSGSST\IndicaSGSSTController@BorrarResultado'
]);

Route::get('/consultaGen/{anio}/{id_empresa}', [
	'as' => 'consultaGen',
	'uses' => 'indicaSGSST\IndicaSGSSTController@consultaGen'
]);

////********************* FIN RUTAS INDICADORES ***********************///

//******************************RUTAS DE CONSULTAS POR AÑO****************************//

Route::get('/consultaHacer/{anio}/{id_company}/{numeral}', 'SST\SGSSTFileController@consultaHacer');

Route::get('/consulAusentismo/{mes}/{anio}/{id_empresa}', 'AusentismoLaboral\ausentismoController@consulAusentismo');

Route::get('/consulAusentismo2/{anio}/{id_empresa}', 'AusentismoLaboral\ausentismoController@consulAusentismo2');

Route::get('/consulAusentismoNew/{anio}/{id_empresa}/{id_emp}', 'AusentismoLaboral\ausentismoController@consulAusentismoNew');

Route::get('/consulAptitud/{anio}/{id_empresa}/{id_user}', [
	'as' => 'consulAptitud',
	'uses' => 'AusentismoLaboral\informeController@consulAptitud',
]);

Route::get('/consulAptitudVen/{anio}/{id_empresa}', [
	'as' => 'consulAptitudVen',
	'uses' => 'AusentismoLaboral\informeController@consulAptitudVen',
]);

Route::get('/consulVigilancia/{anio}/{id_empresa}', 'AusentismoLaboral\vigilanciaController@consulVigilancia');

Route::get('/consulVigilanciaU/{anio}/{id_empresa}/{id_user}', 'AusentismoLaboral\vigilanciaController@consulVigilanciaU');

Route::get('/consulAccidente/{anio}/{id_empresa}', 'AusentismoLaboral\AccidenteLaboralController@consulAccidente');

Route::get('/consulAccidenteU/{anio}/{id_empresa}/{id_user}', 'AusentismoLaboral\AccidenteLaboralController@consulAccidenteU');

Route::get('/consulPrograma/{anio}/{id_empresa}', 'AusentismoLaboral\ProgramarController@consulPrograma');

Route::get('/consulProgramaU/{anio}/{id_empresa}/{id_user}', 'AusentismoLaboral\ProgramarController@consulProgramaU');

//******************************FIN RUTAS DE CONSULTAS POR AÑO****************************//s

//****************************RUTAS MATRIZ REQUISITOS LEGALES**************************************//

Route::any('/noAplica/{id}/{tipo}/{id_empresa}/{id_req}/{modulo}', [
	'as' => 'noAplica',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@noAplica',

]);

Route::get('/informeML/{id_empresa}/{modulo}', [
	'as' => 'informeML',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@informeML',

]);

Route::get('/verDocML/{id_reporte}', [
	'as' => 'verDocML',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@verDocML',

]);

Route::get('/listGraficosLegal/{id_empresa}', [
	'as' => 'listGraficosLegal',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listGraficosLegal'
]);

Route::post('/subirDocML', [
	'as' => 'subirDocML',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@subirDocML',

]);

Route::get('/PDFinformeML/{id}/{tipo}', [
	'as' => 'PDFinformeML',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@PDFinformeML',

]);

Route::get('/pdfMatrizLegal/{id}/{id_empresa}/{modulo}', [
	'as' => 'pdfMatrizLegal',
	'uses' => 'RequisitosLegales\ExcelMLController@pdfInformeNorma',
]);

Route::get('/actuaInforme/{id}/{id_empresa}', [
	'as' => 'actuaInforme',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@actuaInforme',

]);

Route::get('/historialInformeML/{id}/{id_empresa}', [
	'as' => 'historialInformeML',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@historialInformeML',

]);

Route::post('/createInforme', [
	'as' => 'createInforme',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@createInforme',

]);

Route::get('/borrarRequi/{id}/{id_empresa}/{modulo}', [
	'as' => 'borrarRequi',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@borrarRequi',

]);

Route::get('/borrarRequi1/{id}/{id_empresa}/{modulo}', [
	'as' => 'borrarRequi1',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@borrarRequi1',

]);

Route::any('/borrarCrite/{id}/{id_empresa}', [
	'as' => 'borrarCrite',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@borrarCrite',

]);
Route::any('/borrarCriterioReq/{id}', [
	'as' => 'borrarCriterioReq',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@borrarCriterioReq',

]);

Route::get('/modulosRequiAbog/{id_empresa}', [
	'as' => 'modulosRequiAbog',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@modulosRequiAbog',

]);

Route::get('/actualizarPlan/{id}', [
	'as' => 'actualizarPlan',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@actualizarPlan',

]);

Route::post('/editarPlan', [
	'as' => 'editarPlan',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@editarPlan',

]);

Route::get('/legislacionRuta/{id}', [
	'as' => 'legislacionRuta',
	'uses' => 'RequisitosLegales\RequisitoController@legislacionRuta',

]);
Route::get('/listaGeneralRequi/{id_empresa}/{modulo}', [
	'as' => 'listaGeneralRequi',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listaGeneralRequi',

]);

Route::get('/maximo/{id_peligro}/{id_empresa}/{modulo}', [
	'as' => 'maximo',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@maximo',

]);

Route::post('/createActualizacion', [
	'as' => 'createActualizacion',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@createActualizacion',

]);

Route::post('/excelCumplimiento', [
	'as' => 'excelCumplimiento',
	'uses' => 'Excel\ExcelController@excelCumplimiento',

]);
Route::get('/excelRequisitos/{id_empresa}', [
	'as' => 'excelRequisitos',
	'uses' => 'Excel\ExcelController@excelRequisitos',

]);

Route::post('/subirRequisitos', [
	'as' => 'subirRequisitos',
	'uses' => 'Excel\ExcelController@subirRequisitos',

]);

Route::get('/excelCriterios', [
	'as' => 'excelCriterios',
	'uses' => 'Excel\ExcelController@excelCriterios',

]);

Route::get('/normasClientes/{id_empresa}', [
	'as' => 'normasClientes',
	'uses' => 'Excel\ExcelController@normasClientes',

]);

Route::post('/subirCriterio', [
	'as' => 'subirCriterio',
	'uses' => 'Excel\ExcelController@subirCriterio',

]);

Route::get('/modulosRequi/{id_empresa}', [
	'as' => 'modulosRequi',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@modulosRequi',

]);

Route::get('/requisitos/{id_empresa}/{modulo}', [
	'as' => 'requisitos',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@requisitos',

]);
Route::get('/requisitos2/{id_empresa}/{modulo}', [
	'as' => 'requisitos2',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@requisitos2',

]);
Route::get('/requisitosAbo/{id_empresa}/{modulo}', [
	'as' => 'requisitosAbo',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@requisitosAbo',

]);

Route::get('/requisitosAbo1/{id_empresa}/{modulo}', [
	'as' => 'requisitosAbo1',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@requisitosAbo1',

]);

Route::get('/newRequisitos/{id_empresa}', [
	'as' => 'newRequisitos',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@newRequisitos',
]);

Route::post('/createRequi', [
	'as' => 'createRequi',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@createRequi',
]);

Route::any('/borrarRequi/{id}/{id_empresa}', [
	'as' => 'borrarRequi',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@borrarRequi',

]);

Route::any('/borrarArPlan/{id}', [
	'as' => 'borrarArPlan',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@borrarArPlan',

]);

Route::get('/listRequisitosGenerales/{id_empresa}/{modulo}', [
	'as' => 'listRequisitosGenerales',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listRequisitosGenerales',
]);
Route::get('/listBiologicos/{id_empresa}/{modulo}', [
	'as' => 'listBiologicos',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listBiologicos',
]);
Route::get('/listFisicos/{id_empresa}/{modulo}', [
	'as' => 'listFisicos',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listFisicos',
]);
Route::get('/listQuimicos/{id_empresa}/{modulo}', [
	'as' => 'listQuimicos',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listQuimicos',
]);
Route::get('/listPsicosocial/{id_empresa}/{modulo}', [
	'as' => 'listPsicosocial',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listPsicosocial',
]);
Route::get('/listBiomecanicos/{id_empresa}/{modulo}', [
	'as' => 'listBiomecanicos',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listBiomecanicos',
]);
Route::get('/listCondiciones/{id_empresa}/{modulo}', [
	'as' => 'listCondiciones',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listCondiciones',
]);
Route::get('/listCumplimiento/{id_empresa}/{modulo}', [
	'as' => 'listCumplimiento',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listCumplimiento',
]);
Route::get('/consulCumplimiento/{id_empresa}/{anio}/{modulo}', [
	'as' => 'consulCumplimiento',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@consulCumplimiento',
]);
Route::get('/listCumplimiento1/{id_empresa}/{anio}', [
	'as' => 'listCumplimiento1',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@graficas',
]);
//*******************TIPO DE PELIGRO********************************//
Route::resource('/peligro', 'RequisitosLegales\TipoPeligroController');
//*******************CRITERIO DE CUMPLIMIENTO***********************//
Route::resource('/criterio', 'RequisitosLegales\CriterioController');
// *******************REQUISITO**********************

Route::get('/controlCMat/{id_norma}/{id_empresa}/{modulo}', [
	'as' => 'controlCMat',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@controlCMat',
]);
Route::get('/lisrequi/{id_empresa}/{modulo}', [
	'as' => 'lisrequi',
	'uses' => 'RequisitosLegales\RequisitoController@lisrequi',
]);

Route::get('/lisrequiAbo/{id_empresa}/{modulo}', [
	'as' => 'lisrequiAbo',
	'uses' => 'RequisitosLegales\RequisitoController@lisrequiAbo',
]);
Route::post('/AsosciarRequi', [
	'as' => 'AsosciarRequi',
	'uses' => 'RequisitosLegales\RequisitoController@AsosciarRequi',
]);
Route::post('/AsosciarMod', [
	'as' => 'AsosciarMod',
	'uses' => 'RequisitosLegales\RequisitoController@AsosciarMod',
]);
Route::post('/AsociarCrit', [
	'as' => 'AsociarCrit',
	'uses' => 'RequisitosLegales\RequisitoController@AsociarCrit',
]);

Route::get('/controlCambiosList/{id_empresa}', [
	'as' => 'controlCambiosList',
	'uses' => 'RequisitosLegales\RequisitoController@controlCambiosList',
]);
Route::post('/controlCambiosForm', [
	'as' => 'controlCambiosForm',
	'uses' => 'RequisitosLegales\RequisitoController@controlCambiosForm',
]);
Route::any('/borrarRequi1/{id}/{id_empresa}/{modulo}', [
	'as' => 'borrarRequi1',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@borrarRequi1'

]);

Route::any('/borrarArPlan/{id}', [
	'as' => 'borrarArPlan',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@borrarArPlan'

]);

Route::post('/guardarEvidencia', [
	'as' => 'guardarEvidencia',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@guardarEvidencia'
]);
Route::any('/updateRequisito/{id}', [
	'as' => 'updateRequisito',
	'uses' => 'RequisitosLegales\RequisitoController@updateRequisito',
]);

Route::get('/create1/{id_empresa}/{modulo}', [
	'as' => 'create1',
	'uses' => 'RequisitosLegales\RequisitoController@create1',
]);
Route::get('/edit1/{id}/{id_empresa}/{modulo}', [
	'as' => 'edit1',
	'uses' => 'RequisitosLegales\RequisitoController@edit1',
]);

Route::get('/edit1Abo/{id}/{id_empresa}/{modulo}', [
	'as' => 'edit1Abo',
	'uses' => 'RequisitosLegales\RequisitoController@edit1Abo',
]);

Route::resource('/requisito', 'RequisitosLegales\RequisitoController');

//*******************REQUISITO - CRITERIO***********************//

Route::get('/edit2/{id}/{id_empresa}/{modulo}', [
	'as' => 'edit2',
	'uses' => 'RequisitosLegales\RequisitoCriterioController@edit2',
]);
Route::get('/edit2Abo/{id}/{id_empresa}/{modulo}', [
	'as' => 'edit2Abo',
	'uses' => 'RequisitosLegales\RequisitoCriterioController@edit2Abo',
]);
Route::any('/updateCriterioAbo/{id}', [
	'as' => 'updateCriterioAbo',
	'uses' => 'RequisitosLegales\RequisitoCriterioController@updateCriterioAbo',
]);

Route::resource('/requisitoCriterio', 'RequisitosLegales\RequisitoCriterioController');

Route::get('/listRequisitosCriterios/{id}/{id_empresa}/{modulo}', [
	'as' => 'listRequisitosCriterios',
	'uses' => 'RequisitosLegales\RequisitoCriterioController@listRequisitosCriterios',
]);
Route::get('/listRequisitosCriteriosAbo/{id}/{id_empresa}/{modulo}', [
	'as' => 'listRequisitosCriteriosAbo',
	'uses' => 'RequisitosLegales\RequisitoCriterioController@listRequisitosCriteriosAbo',
]);

Route::get('/criterioForm/{id}/{id_empresa}/{modulo}', [
	'as' => 'criterioForm',
	'uses' => 'RequisitosLegales\RequisitoCriterioController@criterioForm',
]);
Route::any('/createCriterioAbo', [
	'as' => 'createCriterioAbo',
	'uses' => 'RequisitosLegales\RequisitoCriterioController@createCriterioAbo',
]);

Route::get('/criterioFormAbo/{id}/{id_empresa}/{modulo}', [
	'as' => 'criterioFormAbo',
	'uses' => 'RequisitosLegales\RequisitoCriterioController@criterioFormAbo',
]);

Route::get('/requisitoGeneralForm/{id}/{id_empresa}/{modulo}', [
	'as' => 'requisitoGeneralForm',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@requisitoGeneralForm',
]);
Route::post('/createRequisitoGeneral', [
	'as' => 'createRequisitoGeneral',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@createRequisitoGeneral',
]);
Route::get('/listRequisitoGeneral/{id}/{id_empresa}/{modulo}', [
	'as' => 'listRequisitoGeneral',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listRequisitoGeneral',
]);
Route::get('/archivosPlan/{id}', [
	'as' => 'archivosPlan',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@archivosPlan',
]);
Route::get('/biologicoForm/{id}/{id_empresa}/{modulo}', [
	'as' => 'biologicoForm',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@biologicoForm',
]);
Route::post('/createBiologico', [
	'as' => 'createBiologico',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@createBiologico',
]);
Route::get('/listBiologico/{id}/{id_empresa}/{modulo}', [
	'as' => 'listBiologico',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listBiologico',
]);
Route::get('/fisicoForm/{id}/{id_empresa}/{modulo}', [
	'as' => 'fisicoForm',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@fisicoForm',
]);
Route::post('/createFisico', [
	'as' => 'createFisico',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@createFisico',
]);
Route::get('/listFisico/{id}/{id_empresa}/{modulo}', [
	'as' => 'listFisico',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listFisico',
]);
Route::get('/quimicoForm/{id}/{id_empresa}/{modulo}', [
	'as' => 'quimicoForm',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@quimicoForm',
]);
Route::post('/createQuimico', [
	'as' => 'createQuimico',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@createQuimico',
]);
Route::get('/listQuimico/{id}/{id_empresa}/{modulo}', [
	'as' => 'listQuimico',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listQuimico',
]);
Route::get('/psicosocialForm/{id}/{id_empresa}/{modulo}', [
	'as' => 'psicosocialForm',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@psicosocialForm',
]);
Route::post('/createPsicosocial', [
	'as' => 'createPsicosocial',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@createPsicosocial',
]);

Route::get('/listPsico/{id}/{id_empresa}/{modulo}', [
	'as' => 'listPsico',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listPsico',
]);
Route::get('/biomecanicoForm/{id}/{id_empresa}/{modulo}', [
	'as' => 'biomecanicoForm',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@biomecanicoForm',
]);
Route::post('/createBiomecanico', [
	'as' => 'createBiomecanico',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@createBiomecanico',
]);
Route::get('/listBiomecanico/{id}/{id_empresa}/{modulo}', [
	'as' => 'listBiomecanico',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listBiomecanico',
]);
Route::get('/condicionForm/{id}/{id_empresa}/{modulo}', [
	'as' => 'condicionForm',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@condicionForm',
]);
Route::post('/createCondicion', [
	'as' => 'createCondicion',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@createCondicion',
]);
Route::get('/listCondicion/{id}/{id_empresa}/{modulo}', [
	'as' => 'listCondicion',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listCondicion',
]);

Route::get('/sectorConsulta/{sector}/{modulo}', [
	'as' => 'sectorConsulta',
	'uses' => 'RequisitosLegales\RequisitoController@sectorConsulta',
]);

Route::get('/MedPreventivaForm/{id}/{id_empresa}/{modulo}', [
	'as' => 'MedPreventivaForm',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@MedPreventivaForm',
]);
Route::post('/createMedPreventiva', [
	'as' => 'createMedPreventiva',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@createMedPreventiva',
]);
Route::get('/listMedPre/{id}/{id_empresa}/{modulo}', [
	'as' => 'listMedPre',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listMedPre',
]);
Route::get('/listMedPreventiva/{id_empresa}/{modulo}', [
	'as' => 'listMedPreventiva',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listMedPreventiva',
]);

Route::get('/listMedPreventivaAbo/{id_empresa}/{modulo}', [
	'as' => 'listMedPreventivaAbo',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listMedPreventivaAbo',
]);
Route::get('/listMedPreventivaAbo1/{id_empresa}/{modulo}', [
	'as' => 'listMedPreventivaAbo1',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listMedPreventivaAbo1',
]);
Route::get('/listMedPreventiva2/{id_empresa}/{modulo}', [
	'as' => 'listMedPreventiva2',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listMedPreventiva2',
]);

Route::get('/listCumplimientoGeneral/{id_empresa}/{modulo}', [
	'as' => 'listCumplimientoGeneral',
	'uses' => 'RequisitosLegales\RequisitosLegalesController@listCumplimientoGeneral',
]);

// ______________________________________________RUTAS DE ESTANDARES MINIMOS 0312

Route::get('/estandarMin/{id}', [
	'as' => 'estandarMin',
	'uses' => 'SST\estandarMinController@estandarMin',
]);

Route::get('/estandaresMinimos0312', [
	'as' => 'estandaresMinimos0312',
	'uses' => 'SST\estandarMinController@estandaresMinimos0312',
]);

Route::get('/estandarMinList/{id}/{accion}', [
	'as' => 'estandarMinList',
	'uses' => 'SST\estandarMinController@estandarMinList',
]);

Route::post('/createCumplimientoEstandaresMin', [
	'as' => 'createCumplimientoEstandaresMin',
	'uses' => 'SST\estandarMinController@createCumplimientoEstandaresMin',
]);

Route::post('/archivosCumplimientoEstandaresMin', [
	'as' => 'archivosCumplimientoEstandaresMin',
	'uses' => 'SST\estandarMinController@archivosCumplimientoEstandaresMin',
]);

Route::get('/verEstadoDeEstandar/{id_estandarMin}/{id}', [
	'as' => 'verEstadoDeEstandar',
	'uses' => 'SST\estandarMinController@verEstadoDeEstandar',
]);

Route::get('/verArchivosSubidos/{id_estandarMin}/{id}/{numeral}', [
	'as' => 'verArchivosSubidos',
	'uses' => 'SST\estandarMinController@verArchivosSubidos',
]);

Route::get('/misObservaciones/{id_estandarMin}/{id}', [
	'as' => 'misObservaciones',
	'uses' => 'SST\estandarMinController@misObservaciones',
]);

Route::get('/pdfInformeTrabajoEstMin/{id}/{categEmpresa}', [
	'as' => 'pdfInformeTrabajoEstMin',
	'uses' => 'SST\estandarMinController@pdfInformeTrabajoEstMin',
]);

Route::get('/archivosNoCumple/{id_estandarMin}/{id}/{accion}', [
	'as' => 'archivosNoCumple',
	'uses' => 'SST\estandarMinController@archivosNoCumple',
]);

Route::post('/EditArchivosEstMin', [
	'as' => 'EditArchivosEstMin',
	'uses' => 'SST\estandarMinController@EditArchivosEstMin',
]);

Route::get('/listArchivosEstMinPlanear/{id}/{id_estandarMin}', [
	'as' => 'listArchivosEstMinPlanear',
	'uses' => 'SST\estandarMinController@listArchivosEstMinPlanear',
]);

Route::get('/listArchivosEstMinHacer/{id}/{id_estandarMin}', [
	'as' => 'listArchivosEstMinHacer',
	'uses' => 'SST\estandarMinController@listArchivosEstMinHacer',
]);

Route::get('/listArchivosEstMinVerificar/{id}/{id_estandarMin}', [
	'as' => 'listArchivosEstMinVerificar',
	'uses' => 'SST\estandarMinController@listArchivosEstMinVerificar',
]);

Route::get('/listArchivosEstMinActuar/{id}/{id_estandarMin}', [
	'as' => 'listArchivosEstMinActuar',
	'uses' => 'SST\estandarMinController@listArchivosEstMinActuar',
]);

Route::get('/subirEvidenciasEstMin/{id}/{id_estandarMin}', [
	'as' => 'subirEvidenciasEstMin',
	'uses' => 'SST\estandarMinController@subirEvidenciasEstMin',
]);

Route::post('/EditArchivosEstMin_calificacion', [
	'as' => 'EditArchivosEstMin_calificacion',
	'uses' => 'SST\estandarMinController@EditArchivosEstMin_calificacion',
]);
// ______________________________________________RUTAS DE ESTANDARES MINIMOS 0312

Route::get('/estandarMin/{id}', [
	'as' => 'estandarMin',
	'uses' => 'SST\estandarMinController@estandarMin',
]);

Route::get('/estandaresMinimos0312', [
	'as' => 'estandaresMinimos0312',
	'uses' => 'SST\estandarMinController@estandaresMinimos0312',
]);

Route::get('/estandarMinList/{id}/{accion}', [
	'as' => 'estandarMinList',
	'uses' => 'SST\estandarMinController@estandarMinList',
]);

Route::post('/createCumplimientoEstandaresMin', [
	'as' => 'createCumplimientoEstandaresMin',
	'uses' => 'SST\estandarMinController@createCumplimientoEstandaresMin',
]);

Route::post('/archivosCumplimientoEstandaresMin', [
	'as' => 'archivosCumplimientoEstandaresMin',
	'uses' => 'SST\estandarMinController@archivosCumplimientoEstandaresMin',
]);

Route::get('/verEstadoDeEstandar/{id_estandarMin}/{id}', [
	'as' => 'verEstadoDeEstandar',
	'uses' => 'SST\estandarMinController@verEstadoDeEstandar',
]);

Route::get('/verArchivosSubidos/{id_estandarMin}/{id}/{numeral}', [
	'as' => 'verArchivosSubidos',
	'uses' => 'SST\estandarMinController@verArchivosSubidos',
]);

Route::get('/misObservaciones/{id_estandarMin}/{id}', [
	'as' => 'misObservaciones',
	'uses' => 'SST\estandarMinController@misObservaciones',
]);

Route::get('/pdfInformeTrabajoEstMin/{id}/{categEmpresa}', [
	'as' => 'pdfInformeTrabajoEstMin',
	'uses' => 'SST\estandarMinController@pdfInformeTrabajoEstMin',
]);

Route::get('/archivosNoCumple/{id_estandarMin}/{id}/{accion}', [
	'as' => 'archivosNoCumple',
	'uses' => 'SST\estandarMinController@archivosNoCumple',
]);

Route::post('/EditArchivosEstMin', [
	'as' => 'EditArchivosEstMin',
	'uses' => 'SST\estandarMinController@EditArchivosEstMin',
]);

Route::get('/listArchivosEstMinPlanear/{id}/{id_estandarMin}', [
	'as' => 'listArchivosEstMinPlanear',
	'uses' => 'SST\estandarMinController@listArchivosEstMinPlanear',
]);

Route::get('/listArchivosEstMinHacer/{id}/{id_estandarMin}', [
	'as' => 'listArchivosEstMinHacer',
	'uses' => 'SST\estandarMinController@listArchivosEstMinHacer',
]);

Route::get('/listArchivosEstMinVerificar/{id}/{id_estandarMin}', [
	'as' => 'listArchivosEstMinVerificar',
	'uses' => 'SST\estandarMinController@listArchivosEstMinVerificar',
]);

Route::get('/listArchivosEstMinActuar/{id}/{id_estandarMin}', [
	'as' => 'listArchivosEstMinActuar',
	'uses' => 'SST\estandarMinController@listArchivosEstMinActuar',
]);

Route::get('/subirEvidenciasEstMin/{id}/{id_estandarMin}', [
	'as' => 'subirEvidenciasEstMin',
	'uses' => 'SST\estandarMinController@subirEvidenciasEstMin',
]);

Route::post('/EditArchivosEstMin_calificacion', [
	'as' => 'EditArchivosEstMin_calificacion',
	'uses' => 'SST\estandarMinController@EditArchivosEstMin_calificacion',
]);

// _______________FIN RUTAS DE ESTANDARES MINIMOS 0312//____________________

//________________Rutas de agenda interna de sistegra//____________________

Route::get('/ListaGeneral', [
	'as' => 'ListaGeneral',
	'uses' => 'Agenda\agendainterna\agendaInternaController@index',
]);

Route::any('/ListAgendaint/{id}', [
	'as' => 'ListAgendaint',
	'uses' => 'Agenda\agendainterna\agendaInternaController@ListAgendaInt',
]);

Route::post('/storeinterno', [
	'as' => 'storeinterno',
	'uses' => 'Agenda\agendainterna\agendaInternaController@store',
]);

Route::any('/ListaEditado/{idasesor}/{id}', [
	'as' => 'ListaEditado',
	'uses' => 'Agenda\agendainterna\agendaInternaController@edit',
]);

Route::any('/updateagenda', [
	'as' => 'updateagenda',
	'uses' => 'Agenda\agendainterna\agendaInternaController@update',
]);

Route::any('/Updatecalendario/{idHorario}/{idAsesor}/{fecha}/{horaFin}/{horaIn}', [
	'as' => 'Updatecalendario',
	'uses' => 'Agenda\agendainterna\agendaInternaController@updatecalendario',
]);

Route::get('/Calendario', [
	'as' => 'Calendario',
	'uses' => 'Agenda\agendainterna\agendaInternaController@calendario',
]);

Route::get('/Calendario3/{id}', [
	'as' => 'Calendario3',
	'uses' => 'Agenda\agendainterna\agendaInternaController@calendario',
]);

Route::get('/Calendario2/{id}', [
	'as' => 'Calendario2',
	'uses' => 'Agenda\agendainterna\agendaInternaController@calendario2',
]);

Route::any('/Moatraragenda', [
	'as' => 'Moatraragenda',
	'uses' => 'Agenda\agendainterna\agendaInternaController@mostrar',
]);

Route::any('/Moatraragenda2/{id}', [
	'as' => 'Moatraragenda2',
	'uses' => 'Agenda\agendainterna\agendaInternaController@mostrar2',
]);

Route::any('/Prueba', [
	'as' => 'Prueba',
	'uses' => 'Agenda\agendainterna\agendaInternaController@destroy',
]);

Route::any('/ExcelAgendaInt/{id}', [
	'as' => 'ExcelAgendaInt',
	'uses' => 'Excel\ExcelController@ExcelAgendaInt',
]);

Route::any('/ExcelAgendaInt2/{id}', [
	'as' => 'ExcelAgendaInt2',
	'uses' => 'Excel\ExcelController@ExcelAgendaInt2',
]);

Route::any('/ImpoExcelAgendaInt/{id}', [
	'as' => 'ImpoExcelAgendaInt',
	'uses' => 'Excel\ExcelController@ImpoExcelAgendaInt',
]);

//________________Fin de rutas de agenda interna_______________________

//Id Company
Route::any('/idComp/{id}', [
	'as' => 'idComp',
	'uses' => 'Company\CompanyController@IdComp',
]);

//Modulo de Bioseguridad
Route::any('/bioseguridad/{id_empresa}', [
	'as' => 'bioseguridad',
	'uses' => 'Bioseguridad\BioseguridadController@index',
]);

Route::get('/verBio/{id}/{id_rutaPDF}/{company_id}', 'Bioseguridad\BioseguridadController@verBio');

Route::any('/listaChekCovid/{id_empresa}/{id}', [
	'as' => 'listaChekCovid',
	'uses' => 'Bioseguridad\BioseguridadController@listaChekCovid',
]);

Route::post('/createCovidC', [
	'as' => 'createCovidC',
	'uses' => 'Bioseguridad\BioseguridadController@createCovidC',
]);
Route::any('/PlanAccionCo/{id_empresa}/{id}', [
	'as' => 'PlanAccionCo',
	'uses' => 'Bioseguridad\BioseguridadController@PlanAccionCo',
]);
Route::any('/EvaluacionCono/{id_empresa}/{id}', [
	'as' => 'EvaluacionCono',
	'uses' => 'Bioseguridad\BioseguridadController@EvaluacionCono',
]);
Route::post('/createEvaCo', [
	'as' => 'createEvaCo',
	'uses' => 'Bioseguridad\BioseguridadController@createEvaCo',
]);
Route::any('/listEvaCo/{id_empresa}/{id}', [
	'as' => 'listEvaCo',
	'uses' => 'Bioseguridad\BioseguridadController@listEvaCo',
]);

Route::get('/verEncu/{id_user}/{id}/{id_empresa}', 'Bioseguridad\BioseguridadController@verEncu');

Route::any('/UpAdjBio', [
	'as' => 'UpAdjBio',
	'uses' => 'Bioseguridad\BioseguridadController@UpAdjBio',
]);
Route::any('/createPandemia', [
	'as' => 'createPandemia',
	'uses' => 'Bioseguridad\BioseguridadController@createPandemia',
]);

Route::any('/createPan', [
	'as' => 'createPan',
	'uses' => 'Bioseguridad\BioseguridadController@storeUpPan',
]);

Route::any('/createBio', [
	'as' => 'createBio',
	'uses' => 'Bioseguridad\BioseguridadController@storeUpBio',
]);

Route::any('/editPan/{id}', [
	'as' => 'editPan',
	'uses' => 'Bioseguridad\BioseguridadController@editPan',
]);

Route::any('/editBio/{idenfe}/{idbio}', [
	'as' => 'editBio',
	'uses' => 'Bioseguridad\BioseguridadController@editBio',
]);

Route::any('/deletePan/{id}', [
	'as' => 'deletePan',
	'uses' => 'Bioseguridad\BioseguridadController@deletePan',
]);

Route::any('/deleteBio/{idbio}/{idenfe}', [
	'as' => 'deleteBio',
	'uses' => 'Bioseguridad\BioseguridadController@deleteBio',
]);

Route::any('/updateBioPan', [
	'as' => 'updateBioPan',
	'uses' => 'Bioseguridad\BioseguridadController@updateBioPan',
]);

Route::any('/dashboardBio', [
	'as' => 'dashboardBio',
	'uses' => 'Bioseguridad\BioseguridadController@indezBio',
]);

//INICIA RUTAS DE DOTACIÓN

Route::get('/dotacionEmpleado', [
	'as' => 'dotacionEmpleado',
	'uses' => 'epp\dotacionController@dotacionEmpleado'
]);

Route::get('/reporteDot/{user}', 'epp\dotacionController@reporteDot');
Route::post('/updateDot', [
	'as' => 'updateDot',
	'uses' => 'epp\dotacionController@updateDot',
]);
Route::get('/editElementoDotacion/{id}', [
	'as' => 'editElementoDotacion',
	'uses' => 'epp\dotacionController@editElementoDotacion',
]);

Route::get('/elementosEntragadosDot/{id}', [
	'as' => 'elementosEntragadosDot',
	'uses' => 'epp\dotacionController@elementosEntragadosDot',
]);

Route::get('/elementosTrabajadores', [
	'as' => 'elementosTrabajadores',
	'uses' => 'epp\eppController@elementosTrabajadores',
]);
Route::get('/formElementosDotacion/{id_empresa}/{tipo}', [
	'as' => 'formElementosDotacion',
	'uses' => 'epp\dotacionController@formElementosDotacion',
]);
Route::get('/listDotacion/{id_empresa}/{tipo}', [
	'as' => 'listDotacion',
	'uses' => 'epp\dotacionController@listDotacion',
]);
Route::post('/formDotacion', [
	'as' => 'formDotacion',
	'uses' => 'epp\dotacionController@formDotacion',
]);
Route::get('/listElementosDotacion/{id_empresa}/{tipo}', [
	'as' => 'listElementosDotacion',
	'uses' => 'epp\dotacionController@listElementosDotacion',
]);
Route::post('/createAsignacion', [
	'as' => 'createAsignacion',
	'uses' => 'epp\dotacionController@createAsignacion',
]);

Route::get('/formularioDotacion/{id}/{id_company}/{tipo}', [
	'as' => 'formularioDotacion',
	'uses' => 'epp\dotacionController@formularioDotacion',
]);

Route::post('/createEntregaDot', 'epp\dotacionController@createEntregaDot');
Route::get('/traerCe/{id}', 'epp\dotacionController@traerCe');
Route::get('/asigAnualDot/{id_cargo}/{id_empresa}/{tipo}', 'epp\dotacionController@asigAnualDot');

Route::get('/dotacionEntregada/{id_empresa}/{tipo}', [
	'as' => 'dotacionEntregada',
	'uses' => 'epp\dotacionController@listDotacionEntregada'
]);

Route::get('/listUserDotacion/{id_empresa}/{tipo}', [
	'as' => 'listUserDotacion',
	'uses' => 'epp\dotacionController@listUserDotacion'
]);


//Fin rutas dotacion
//INICIA rutas EPP elementos de protección personal

Route::get('/eppContra/{id_company}', [
	'as' => 'eppContra',
	'uses' => 'epp\eppController@eppContra'
]);


Route::get('/listElements/{id_empresa}/{tipo}', [
	'as' => 'listElements',
	'uses' => 'epp\eppController@listElements'
]);
Route::get('/formElemento/{id_empresa}/{tipo}', 'epp\eppController@formElemento');
Route::post('/editEstadoElem', 'epp\eppController@editEstadoElem');
Route::post('/newElemento', 'epp\eppController@newElemento');
Route::post('/createAreaEpp', 'epp\eppController@createAreaEpp');
Route::post('/editAreaEpp', 'epp\eppController@editAreaEpp');
Route::post('/createElement', 'epp\eppController@createElement');
Route::post('/createHistory', 'epp\eppController@createHistory');
Route::post('/createElement2', 'epp\eppController@createElement2');
Route::get('/detalle/{id_pedido}', 'epp\eppController@detalle');
Route::get('/elemDetalle/{id_elemento}', 'epp\eppController@elemDetalle');
Route::get('/pedido/{id}/{id_empresa}/{tipo}/{id_cargo}', 'epp\eppController@pedido');
Route::post('/aceptarElementos', 'epp\eppController@aceptarElementos');
Route::post('/editStock', 'epp\eppController@editStock');
Route::post('/infoSolicitud', 'epp\eppController@infoSolicitud');
Route::post('/infoSolicitudEntregado', 'epp\eppController@infoSolicitudEntregado');
Route::get('/entregados/{id_empresa}/{tipo}', 'epp\eppController@entregados');
Route::get('/detalleSol/{id_pedido}', 'epp\eppController@detalleSol');
Route::get('/asigAnual/{id}/{id_empresa}/{tipo}', 'epp\eppController@asigAnual');
Route::get('/formEntrega/{id_pedido}/{id_empresa}/{tipo}/{id_cargo}', 'epp\eppController@formEntrega');
Route::get('/listDetalleSolicitud/{id_pedido}', 'epp\eppController@listDetalleSolicitud');
Route::get('/reporteEpp/{id_pedido}', 'epp\eppController@reporteEpp');
Route::get('/reporteEpp2/{id_pedido}', 'epp\eppController@reporteEpp2');
Route::get('/devolucion/{id}/{id_empresa}/{tipo}/{id_cargo}', 'epp\eppController@devolucion');
Route::post('/aceptarElementosSed', 'epp\eppController@aceptarElementosSed');
Route::post('/formEntregaFarmaSed', 'epp\eppController@formEntregaFarma');
Route::get('/formEntregaSed/{id_stock}/{id_empresa}/{tipo}/{id_cargo}', 'epp\eppController@formEntregaSed');
Route::get('/desatalleinfosede/{id_sede}', 'epp\eppController@desatalleinfosede');
Route::get('/infoeppsede/{id_sede}', 'epp\eppController@infoeppsede');
Route::get('/detalleEntregaSed/{id_pedido}/{id_user}', 'epp\eppController@detalleEntregaSed');
Route::get('/eppFarmaSed', 'epp\eppController@eppFarmaSed');
Route::post('/addStock', 'epp\eppController@addStock');
Route::get('/historialEpp/{id}', 'epp\eppController@historialEpp');
Route::post('/createElementPrimeraVez', 'epp\eppController@createElementPrimeraVez');
Route::post('/devElementos', 'epp\eppController@devElementos');
Route::get('/detalleDev/{id_dev}', 'epp\eppController@detalleDev');
Route::post('/eppDevolucionEntregado', 'epp\eppController@eppDevolucionEntregado');
Route::get('/entregados/{id_empresa}/{tipo}', 'epp\eppController@entregados');
Route::get('/solicitudesEntregadas/{id_empresa}/{tipo}', 'epp\eppController@solicitudesEntregadas');
Route::get('/detalleEntrega/{id_pedido}/{id_user}', 'epp\eppController@detalleEntrega');
Route::get('/detalleEntrega2/{id_pedido}/{id_user}', 'epp\eppController@detalleEntrega2');
Route::get('/detalleElemento/{id_elemento}', 'epp\eppController@detalleElemento');
Route::get('/selectAsig/{id_cargo}/{id_empresa}/{tipo}', 'epp\eppController@selectAsig');
Route::post('/editSolicitud', 'epp\eppController@editSolicitud');
Route::get('/elementDev/{id_pedido}', 'epp\eppController@elementDev');
Route::get('/pruebasDev/{id}/{id_empresa}/{tipo}/{id_cargo}', 'epp\eppController@pruebasDev');
Route::get('/devoluciones/{id_pedido}', 'epp\eppController@devoluciones');
Route::get('/historialDev/{id_empresa}/{tipo}', 'epp\eppController@historialDev');
Route::get('/elemDevoluciones/{id_dev}', 'epp\eppController@elemDevoluciones');
Route::get('/listElementosDev/{id_empresa}/{tipo}', 'epp\eppController@listElementosDev');
Route::get('/histSolicitudes/{id}', 'epp\eppController@histSolicitudes');
Route::get('/listElementosEdit/{id_elemento}/{id_empresa}/{tipo}/{id_cargo}', 'epp\eppController@listElementosEdit');
Route::post('/updateElemento', 'epp\eppController@updateElemento');
Route::get('/historicoPvp/{id_elemento}', [
	'as' => 'historicoPvp',
	'uses' => 'epp\eppController@historicoPvp'
]);


Route::post('/riseEPP', [
	'as' => 'riseEPP',
	'uses' => 'Excel\ExcelController@riseEPP'
]);

Route::get('/historicoReajuste/{id_elemento}', [
	'as' => 'historicoReajuste',
	'uses' => 'epp\eppController@historicoReajuste'
]);

Route::post('/deleteStock', [
	'as' => 'deleteStock',
	'uses' => 'epp\eppController@deleteStock'
]);

Route::get('/listUserEpp', [
	'as' => 'listUserEpp',
	'uses' => 'epp\eppController@listUserEpp'
]);

Route::get('/asigUserEpp/{$id_user}/{$id_company}', [
	'as' => 'asigUserEpp',
	'uses' => 'epp\eppController@asigUserEpp'
]);

Route::get('dashboardEpp', function () {
	return view('/epp/dashboardEpp');
});

Route::any('/agendaEPP', 'epp\Agenda\AgendaeppController@index');

Route::any('/obtenerEventoEPP', 'epp\Agenda\AgendaeppController@obtener_evento');

Route::any('/describirEventoEPP/{id}', 'epp\Agenda\AgendaeppController@describir_evento');

Route::any('/borrarEventoEPP/{id}', 'epp\Agenda\AgendaeppController@destroy');

Route::any('/editEventoEPP/{id}', 'epp\Agenda\AgendaeppController@update');

Route::any('/storeEventoEPP', 'epp\Agenda\AgendaeppController@store');

Route::get('/eppExcel', [
	'as' => 'eppExcel',
	'uses' => 'Excel\ExcelController@eppExcel',
]);

Route::get('/stockEppExcel', [
	'as' => 'stockEppExcel',
	'uses' => 'Excel\ExcelController@stockEppExcel',
]);

Route::get('/MatrizEPP/{id_empresa}/{tipoMatriz}/{id_registro}', 'epp\eppController@MatrizEPP');

Route::post('/createorupdateMatrizEpp', 'epp\eppController@createorupdateMatrizEpp');

Route::get('/areasElementosPP/{parametro1}/{parametro2}', 'epp\eppController@buscarElementoEPP')->name('areasElementos');
Route::get('/buscarElementoEPPOtrasAreas/{parametro1}/{parametro2}', 'epp\eppController@buscarElementoEPPOtrasAreas')->name('buscarElementoEPPOtrasAreas');

Route::get('/asigAnualsubarea/{id}/{id_empresa}/{tipo}', [
	'as' => 'asigAnualsubarea',
	'uses' => 'epp\eppController@asigAnualsubarea'
]);

/*Route::get('/eppExcel', function( Request $request)
{
return (new Export)->forDate($request->fechaDesde, $request->fechaHasta)->download('Registro de entrega EPP.xlsx');
});*/

// FIN rutas EPP elementos de protección personal

//------------------ Rutas PESV ----------------//


//**capacitaciones PESV**
Route::get('capacitacionPESV', [
	'as' => 'capacitacionPESV',
	'uses' => 'Capacitaciones\CapacitacionesController@capacitacionPESV',

]);

Route::get('/dashboardPesv  ', function () {
	return view('Pesv/dashboardPesv');
});

Route::get('/dashboardPesvCapacitaciones  ', function () {
	return view('Pesv/dashboardPesvCapacitaciones');
});

Route::get('/capacitacionesPesv/{id_company}', [
	'as' => 'capacitacionesPesv',
	'uses' => 'PESV\PesvController@capacitacionesPesv',
]);

Route::get('/capacitacionesUserPesv/{id_company}', [
	'as' => 'capacitacionesUserPesv',
	'uses' => 'PESV\PesvController@capacitacionesUserPesv',
]);

Route::get('/consulCapacitaPesv/{anio}/{id_empresa}', [
	'as' => 'consulCapacitaPesv',
	'uses' => 'PESV\PesvController@consulCapacitaPesv',
]);


Route::get('/pesv/{id_empresa}', [
	'as' => 'pesv',
	'uses' => 'PESV\PesvController@pesv',
]);

Route::get('/ListForm/{id_empresa}/{id_diagnostico}/{id}', [
	'as' => 'ListForm',
	'uses' => 'PESV\PesvController@ListForm',
]);

Route::get('/listFormHacer/{id_empresa}/{id_diagnostico}/{id}', [
	'as' => 'listFormHacer',
	'uses' => 'PESV\PesvHacerController@listFormHacer',
]);

Route::get('/listFormVerificar/{id_empresa}/{id_diagnostico}', [
	'as' => 'listFormVerificar',
	'uses' => 'PESV\PesvVerificarController@listFormVerificar',
]);

Route::get('/listFormActuar/{id_empresa}/{id_diagnostico}', [
	'as' => 'listFormActuar',
	'uses' => 'PESV\PesvActuarController@listFormActuar',
]);

Route::post('/createupdoc', [
	'as' => 'createupdoc',
	'uses' => 'PESV\PesvController@createdoc',
]);

Route::post('/createupdocHacer', [
	'as' => 'createupdocHacer',
	'uses' => 'PESV\PesvHacerController@createdoc',
]);

Route::post('/creategeneralidades', [
	'as' => 'creategeneralidades',
	'uses' => 'PESV\PesvController@creategeneralidades',
]);

Route::get('/panelCentralPesv/{id_empresa}', [
	'as' => 'panelCentralPesv',
	'uses' => 'PESV\PesvController@panelCentralPesv',
]);

Route::get('/panelSGSSTPesv/{id_empresa}', [
	'as' => 'panelSGSSTPesv',
	'uses' => 'PESV\PesvController@panelSGSSTPesv',
]);

Route::get('/panelCentralPesvSGSST/{id_empresa}/{id_diagnostico}', [
	'as' => 'panelCentralPesvSGSST',
	'uses' => 'PESV\PesvController@panelCentralPesvSGSST',
]);

Route::get('/generalidadesEmpresa/{id_empresa}/{id_diagnostico}/{id_para}', [
	'as' => 'generalidadesEmpresa',
	'uses' => 'PESV\PesvController@generalidadesEmpresa',
]);
Route::get('/listaPesvGenerica/{id_empresa}/{id}', [
	'as' => 'listaPesvGenerica',
	'uses' => 'PESV\PesvController@listaPesvGenerica',
]);

Route::get('/ActaConformación/{id_empresa}/{id}/{id_para}', [
	'as' => 'ActaConformación',
	'uses' => 'PESV\PesvController@ActaConformación',
]);

Route::post('/createencuesta', [
	'as' => 'createencuesta',
	'uses' => 'PESV\PesvController@createencuesta',
]);

Route::post('/createreporteencuesta', [
	'as' => 'createreporteencuesta',
	'uses' => 'PESV\PesvController@createreporteencuesta',
]);

Route::post('/createriesgopeligro', [
	'as' => 'createriesgopeligro',
	'uses' => 'PESV\PesvController@createriesgopeligro',
]);
Route::post('/updateriesgopeligro', [
	'as' => 'updateriesgopeligro',
	'uses' => 'PESV\PesvController@updateriesgopeligro',
]);



Route::post('/createcalificacion', [
	'as' => 'createcalificacion',
	'uses' => 'PESV\PesvController@createcalificacion',
]);

Route::post('/createCalificacionPesvHacer', [
	'as' => 'createCalificacionPesvHacer',
	'uses' => 'PESV\PesvHacerController@createCalificacionPesvHacer',
]);

Route::post('/createconformacion', [
	'as' => 'createconformacion',
	'uses' => 'PESV\PesvController@createconformacion',
]);

Route::get('/verencuesta/{id}/{id_company}', [
	'as' => 'verencuesta',
	'uses' => 'PESV\PesvController@verencuesta',
]);

Route::get('/VeraniosgPESV/{anio}/{id_diagnostico}/{id_company}', [
	'as' => 'VeraniosgPESV',
	'uses' => 'PESV\PesvController@VeraniosgPESV',
]);

Route::get('/VeraniosPesvFile/{anio}/{company_id}/{id_diagnostico}/{id}', [
	'as' => 'VeraniosPesvFile',
	'uses' => 'PESV\PesvController@VeraniosPesvFile',
]);

Route::get('/encuestapdf/{id}/{id_company}', [
	'as' => 'encuestapdf',
	'uses' => 'PESV\PesvController@encuestapdf',

]);

Route::get('/verconformacion/{id}/{id_company}', [
	'as' => 'verconformacion',
	'uses' => 'PESV\PesvController@verconformacion',
]);

Route::get('/conformacionpdf/{id}/{id_company}', [
	'as' => 'conformacionpdf',
	'uses' => 'PESV\PesvController@conformacionpdf',

]);

Route::get('/editdatospesv/{id}/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'editdatospesv',
	'uses' => 'PESV\PesvController@editdatospesv',
]);

Route::get('/vergeneralidades/{id}/{id_company}', [
	'as' => 'vergeneralidades',
	'uses' => 'PESV\PesvController@vergeneralidades',
]);

Route::get('/verreporteencuesta/{id}/{id_company}', [
	'as' => 'verreporteencuesta',
	'uses' => 'PESV\PesvController@verreporteencuesta',
]);

Route::get('/verriegoViales/{id}/{id_company}', [
	'as' => 'verriegoViales',
	'uses' => 'PESV\PesvController@verriegoViales',
]);

Route::get('/riesgoVialespdf/{id}/{id_company}', [
	'as' => 'riesgoVialespdf',
	'uses' => 'PESV\PesvHacerController@riesgoVialespdf',

]);

Route::get('/reporteencuesta/{id}/{id_company}', [
	'as' => 'reporteencuesta',
	'uses' => 'PESV\PesvController@reporteencuesta',
]);

Route::get('/reporteencuestapdf/{id}/{id_company}', [
	'as' => 'reporteencuestapdf',
	'uses' => 'PESV\PesvController@reporteencuestapdf',

]);

Route::get('/riegoViales/{id_empresa}/{id_diagnostico}/{id_para}', [
	'as' => 'riegoViales',
	'uses' => 'PESV\PesvController@riegoViales',
]);

Route::get('/encuestaRiesgo/{id_empresa}/{id_diagnostico}/{id_para}', [
	'as' => 'encuestaRiesgo',
	'uses' => 'PESV\PesvController@encuestaRiesgo',
]);

Route::get('/indicaForm/{id_empresa}/{id_diagnostico}/{id_para}/{id_indicadores}', [
	'as' => 'indicaForm',
	'uses' => 'PESV\PesvController@indicaForm',
]);

Route::get('/ListIndicadoresPesv/{id_empresa}/{id_diagnostico}/{id_para}/{id_indicadores}', [
	'as' => 'ListIndicadoresPesv',
	'uses' => 'PESV\PesvController@ListIndicadoresPesv',
]);

Route::get('/listIndicaGeneralPesv/{id_empresa}/{id_diagnostico}/{id_para}', [
	'as' => 'listIndicaGeneralPesv',
	'uses' => 'PESV\PesvController@listIndicaGeneralPesv',
]);

Route::get('/archivosPesv', [
	'as' => 'archivosPesv',
	'uses' => 'PESV\PesvController@archivosPesv',
]);

Route::get('/diagFPesv', [
	'as' => 'diagFPesv',
	'uses' => 'PESV\PesvController@diagFPesv',
]);

Route::post('/createIndicaPesv', [
	'as' => 'createIndicaPesv',
	'uses' => 'PESV\PesvController@createIndicaPesv',
]);

Route::get('/indicadoresPesv/{id_empresa}/{id_diagnostico}/{id_para}', [
	'as' => 'indicadoresPesv',
	'uses' => 'PESV\PesvController@indicadoresPesv',
]);

Route::get('/indicadoresPesvpdf/{id_empresa}/{id_diagnostico}/{id_para}', [
	'as' => 'indicadoresPesvpdf',
	'uses' => 'PESV\PesvController@indicadoresPesvpdf',
]);


Route::post('/CreateArcPesv', [
	'as' => 'CreateArcPesv',
	'uses' => 'PESV\PesvController@CreateArcPesv',
]);

Route::get('/InspecPesv', [
	'as' => 'InspecPesv',
	'uses' => 'PESV\PesvController@InspecPesv',
]);

Route::get('/Anioconsulta/{ano}/{id_indicadores}/{id_empresa}', [
	'as' => 'Anioconsulta',
	'uses' => 'PESV\PesvController@Anioconsulta',
]);

Route::get('/consultaGenPevs/{ano}/{id_empresa}', [
	'as' => 'consultaGenPevs',
	'uses' => 'PESV\PesvController@consultaGenPevs',
]);

Route::post('/CreateVehi', [
	'as' => 'CreateVehi',
	'uses' => 'PESV\PesvController@CreateVehi',
]);




Route::post('/CreateInspPesv', [
	'as' => 'CreateInspPesv',
	'uses' => 'PESV\PesvController@CreateInspPesv',
]);


//Rutas Excel
Route::get('/pesvExcel', [
	'as' => 'pesvExcel',
	'uses' => 'PESV\PesvController@pesvExcel',
]);

//Documentos digitados

Route::post('createDocuComite', [
	'as' => 'createDocuComite',
	'uses' => 'PESV\PesvDocController@createDocuComite',
]);

Route::get('/indexDocComite/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'indexDocComite',
	'uses' => 'PESV\PesvDocController@indexDocComite',

]);
Route::get('/verDocComite/{id_company}/{id_diagnostico}', [
	'as' => 'verDocComite',
	'uses' => 'PESV\PesvDocController@verDocComite',

]);

Route::get('/docComitepdf/{id_company}/{id_diagnostico}', [
	'as' => 'docComitepdf',
	'uses' => 'PESV\PesvDocController@docComitepdf',

]);

Route::post('createobjetivoComit', [
	'as' => 'createobjetivoComit',
	'uses' => 'PESV\PesvDocController@createobjetivoComit',

]);

Route::get('/objetivoComit/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'objetivoComit',
	'uses' => 'PESV\PesvDocController@objetivoComit',

]);

Route::get('/verObjetivoComite/{id_company}/{id_diagnostico}', [
	'as' => 'verObjetivoComite',
	'uses' => 'PESV\PesvDocController@verObjetivoComite',

]);

Route::get('/objetivoComitepdf/{id_company}/{id_diagnostico}', [
	'as' => 'objetivoComitepdf',
	'uses' => 'PESV\PesvDocController@objetivoComitepdf',

]);


Route::get('/responsableEstrategico/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'responsableEstrategico',
	'uses' => 'PESV\PesvDocController@indexResponComit',

]);


Route::post('responComit', [
	'as' => 'responComit',
	'uses' => 'PESV\PesvDocController@responComit',

]);

Route::get('/verResponComit/{id_company}/{id_diagnostico}', [
	'as' => 'verResponComit',
	'uses' => 'PESV\PesvDocController@verResponComit',

]);

Route::get('/responComitpdf/{id_company}/{id_diagnostico}', [
	'as' => 'responComitpdf',
	'uses' => 'PESV\PesvDocController@responComitpdf',

]);


Route::get('/politicaComit/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'politicaComit',
	'uses' => 'PESV\PesvDocController@politicaComit',

]);

Route::post('createpoliticaComit', [
	'as' => 'createpoliticaComit',
	'uses' => 'PESV\PesvDocController@createpoliticaComit',

]);

Route::get('/verpoliticaComite/{id_company}/{id_diagnostico}', [
	'as' => 'verpoliticaComite',
	'uses' => 'PESV\PesvDocController@verpoliticaComite',

]);

Route::get('/politicaComitepdf/{id_company}/{id_diagnostico}', [
	'as' => 'politicaComitepdf',
	'uses' => 'PESV\PesvDocController@politicaComitepdf',

]);

Route::get('/procedicompa/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'procedicompa',
	'uses' => 'PESV\PesvDocController@procedicompa',

]);


Route::post('createprocedicompa', [
	'as' => 'createprocedicompa',
	'uses' => 'PESV\PesvDocController@createprocedicompa',

]);

Route::get('/verprocedicompa/{id_company}/{id_diagnostico}', [
	'as' => 'verprocedicompa',
	'uses' => 'PESV\PesvDocController@verprocedicompa',

]);

Route::get('/procedicompapdf/{id_company}/{id_diagnostico}', [
	'as' => 'procedicompapdf',
	'uses' => 'PESV\PesvDocController@procedicompapdf',

]);


Route::post('createleccionApren', [
	'as' => 'createleccionApren',
	'uses' => 'PESV\PesvDocController@createleccionApren',
]);

Route::get('/leccionApren/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'leccionApren',
	'uses' => 'PESV\PesvDocController@leccionApren',

]);
Route::get('/verLeccionApren/{id_company}/{id_diagnostico}', [
	'as' => 'verLeccionApren',
	'uses' => 'PESV\PesvDocController@verLeccionApren',

]);

Route::get('/leccionAprenpdf/{id_company}/{id_diagnostico}', [
	'as' => 'leccionAprenpdf',
	'uses' => 'PESV\PesvDocController@leccionAprenpdf',

]);

Route::post('createpruebasyPerfilConduc', [
	'as' => 'createpruebasyPerfilConduc',
	'uses' => 'PESV\PesvHacerController@createpruebasyPerfilConduc',

]);

Route::get('/pruebasyPerfilConduc/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'pruebasyPerfilConduc',
	'uses' => 'PESV\PesvHacerController@pruebasyPerfilConduc',

]);

Route::get('/verpruebasyPerfilConduc/{id_company}/{id_diagnostico}', [
	'as' => 'verpruebasyPerfilConduc',
	'uses' => 'PESV\PesvHacerController@verpruebasyPerfilConduc',

]);
Route::post('/updatepruebasyPerfilConduc', [
	'as' => 'updatepruebasyPerfilConduc',
	'uses' => 'PESV\PesvHacerController@updatepruebasyPerfilConduc',

]);

Route::get('/pruebasyPerfilConducpdf/{id_company}/{id_diagnostico}', [
	'as' => 'pruebasyPerfilConducpdf',
	'uses' => 'PESV\PesvHacerController@pruebasyPerfilConducpdf',

]);

Route::post('createcriterioPruebas', [
	'as' => 'createcriterioPruebas',
	'uses' => 'PESV\PesvHacerController@createcriterioPruebas',

]);

Route::post('updateCriterioPruebas', [
	'as' => 'updateCriterioPruebas',
	'uses' => 'PESV\PesvHacerController@updateCriterioPruebas',

]);

Route::get('/criterioPruebas/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'criterioPruebas',
	'uses' => 'PESV\PesvHacerController@criterioPruebas',

]);

Route::get('/vercriterioPruebas/{id_company}/{id_diagnostico}', [
	'as' => 'vercriterioPruebas',
	'uses' => 'PESV\PesvHacerController@vercriterioPruebas',

]);

Route::get('/criterioPruebaspdf/{id_company}/{id_diagnostico}', [
	'as' => 'criterioPruebaspdf',
	'uses' => 'PESV\PesvHacerController@criterioPruebaspdf',

]);

Route::post('createpoliticaAlcoholyDrogas', [
	'as' => 'createpoliticaAlcoholyDrogas',
	'uses' => 'PESV\PesvDocController@createpoliticaAlcoholyDrogas',

]);

Route::get('/politicaAlcoholyDrogas/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'politicaAlcoholyDrogas',
	'uses' => 'PESV\PesvDocController@politicaAlcoholyDrogas',

]);

Route::get('/verpoliticaAlcoholyDrogas/{id_company}/{id_diagnostico}', [
	'as' => 'verpoliticaAlcoholyDrogas',
	'uses' => 'PESV\PesvDocController@verpoliticaAlcoholyDrogas',

]);

Route::get('/politicaAlcoholyDrogaspdf/{id_company}/{id_diagnostico}', [
	'as' => 'politicaAlcoholyDrogaspdf',
	'uses' => 'PESV\PesvDocController@politicaAlcoholyDrogaspdf',

]);

Route::post('createdirectricesDireccion', [
	'as' => 'createdirectricesDireccion',
	'uses' => 'PESV\PesvDocController@createdirectricesDireccion',

]);

Route::get('/directricesDireccion/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'directricesDireccion',
	'uses' => 'PESV\PesvDocController@directricesDireccion',

]);

Route::get('/verdirectricesDireccion/{id_company}/{id_diagnostico}', [
	'as' => 'verdirectricesDireccion',
	'uses' => 'PESV\PesvDocController@verdirectricesDireccion',

]);

Route::get('/directricesDireccionpdf/{id_company}/{id_diagnostico}', [
	'as' => 'directricesDireccionpdf',
	'uses' => 'PESV\PesvDocController@directricesDireccionpdf',

]);

Route::post('createobjetivoPevs', [
	'as' => 'createobjetivoPevs',
	'uses' => 'PESV\PesvDocController@createobjetivoPevs',

]);

Route::get('/objetivoPevs/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'objetivoPevs',
	'uses' => 'PESV\PesvDocController@objetivoPevs',

]);

Route::get('/verobjetivoPevs/{id_company}/{id_diagnostico}', [
	'as' => 'verobjetivoPevs',
	'uses' => 'PESV\PesvDocController@verobjetivoPevs',

]);

Route::get('/objetivoPevspdf/{id_company}/{id_diagnostico}', [
	'as' => 'objetivoPevspdf',
	'uses' => 'PESV\PesvDocController@objetivoPevspdf',

]);

Route::post('createpoliticasdeSeguridad', [
	'as' => 'createpoliticasdeSeguridad',
	'uses' => 'PESV\PesvDocController@createpoliticasdeSeguridad',

]);

Route::get('/politicasdeSeguridad/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'politicasdeSeguridad',
	'uses' => 'PESV\PesvDocController@politicasdeSeguridad',

]);

Route::get('/verpoliticasdeSeguridad/{id_company}/{id_diagnostico}', [
	'as' => 'verpoliticasdeSeguridad',
	'uses' => 'PESV\PesvDocController@verpoliticasdeSeguridad',

]);

Route::get('/politicasdeSeguridadpdf/{id_company}/{id_diagnostico}', [
	'as' => 'politicasdeSeguridadpdf',
	'uses' => 'PESV\PesvDocController@politicasdeSeguridadpdf',

]);

Route::post('createatencionaVictimas', [
	'as' => 'createatencionaVictimas',
	'uses' => 'PESV\PesvDocController@createatencionaVictimas',

]);

Route::get('/atencionaVictimas/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'atencionaVictimas',
	'uses' => 'PESV\PesvDocController@atencionaVictimas',

]);

Route::get('/veratencionaVictimas/{id_company}/{id_diagnostico}', [
	'as' => 'veratencionaVictimas',
	'uses' => 'PESV\PesvDocController@veratencionaVictimas',

]);

Route::get('/atencionaVictimaspdf/{id_company}/{id_diagnostico}', [
	'as' => 'atencionaVictimaspdf',
	'uses' => 'PESV\PesvDocController@atencionaVictimaspdf',

]);

Route::post('createprocedimientoInvest', [
	'as' => 'createprocedimientoInvest',
	'uses' => 'PESV\PesvHacerController@createprocedimientoInvest',

]);

Route::post('updateProcedimientoInvest', [
	'as' => 'updateProcedimientoInvest',
	'uses' => 'PESV\PesvHacerController@updateProcedimientoInvest',
]);

Route::get('/procedimientoInvest/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'procedimientoInvest',
	'uses' => 'PESV\PesvHacerController@procedimientoInvest',

]);

Route::get('/verprocedimientoInvest/{id_company}/{id_diagnostico}/{id_registro}/{id_para}', [
	'as' => 'verprocedimientoInvest',
	'uses' => 'PESV\PesvHacerController@verprocedimientoInvest',

]);

Route::get('/procedimientoInvestpdf/{id_company}/{id_diagnostico}', [
	'as' => 'procedimientoInvestpdf',
	'uses' => 'PESV\PesvHacerController@procedimientoInvestpdf',

]);


Route::get('/procedimientoRIS/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'procedimientoRIS',
	'uses' => 'PESV\PesvHacerController@procedimientoRIS',

]);

Route::get('/verProcedimientoRIS/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'verProcedimientoRIS',
	'uses' => 'PESV\PesvHacerController@verProcedimientoRIS',
]);

Route::get('/procedimientoRISpdf/{id_registro}/{id_company}', [
	'as' => 'procedimientoRISpdf',
	'uses' => 'PESV\PesvHacerController@procedimientoRISpdf',
]);

Route::post('storeProcedimientoRIS', [
	'as' => 'storeProcedimientoRIS',
	'uses' => 'PESV\PesvHacerController@storeProcedimientoRIS',

]);

Route::post('editProcedimientoRIS', [
	'as' => 'editProcedimientoRIS',
	'uses' => 'PESV\PesvHacerController@editProcedimientoRIS',
]);

Route::post('createplanesdeAccion', [
	'as' => 'createplanesdeAccion',
	'uses' => 'PESV\PesvHacerController@createplanesdeAccion',

]);

Route::get('/planesdeAccion/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'planesdeAccion',
	'uses' => 'PESV\PesvHacerController@planesdeAccion',

]);

Route::post('editplanesdeAccion', [
	'as' => 'editplanesdeAccion',
	'uses' => 'PESV\PesvHacerController@editplanesdeAccion',

]);

Route::get('verplanesdeAccion/{id_company}/{id_diagnostico}/{id_para}/{id_registro}', [
	'as' => 'verplanesdeAccion',
	'uses' => 'PESV\PesvHacerController@verplanesdeAccion',

]);

Route::get('/planesdeAccionpdf/{id_company}/{id_diagnostico}', [
	'as' => 'planesdeAccionpdf',
	'uses' => 'PESV\PesvHacerController@planesdeAccionpdf',

]);



Route::post('createcontrolDocuConductores', [
	'as' => 'createcontrolDocuConductores',
	'uses' => 'PESV\PesvDocController@createcontrolDocuConductores',

]);

Route::get('/controlDocuConductores/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'controlDocuConductores',
	'uses' => 'PESV\PesvDocController@controlDocuConductores',

]);

Route::get('/vercontrolDocuConductores/{id_company}/{id_diagnostico}', [
	'as' => 'vercontrolDocuConductores',
	'uses' => 'PESV\PesvDocController@vercontrolDocuConductores',

]);

Route::get('/controlDocuConductorespdf/{id_company}/{id_diagnostico}', [
	'as' => 'controlDocuConductorespdf',
	'uses' => 'PESV\PesvDocController@controlDocuConductorespdf',

]);

Route::post('createcontrolDocuVehiculos', [
	'as' => 'createcontrolDocuVehiculos',
	'uses' => 'PESV\PesvHacerController@createcontrolDocuVehiculos',

]);

Route::get('/controlDocuVehiculos/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'controlDocuVehiculos',
	'uses' => 'PESV\PesvHacerController@controlDocuVehiculos',

]);

Route::get('/controlDocuVehiculos2/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'controlDocuVehiculos2',
	'uses' => 'PESV\PesvHacerController@controlDocuVehiculos2',

]);

Route::get('/controlDocuVehiculos3/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'controlDocuVehiculos3',
	'uses' => 'PESV\PesvHacerController@controlDocuVehiculos3',
]);

Route::get('/controlDocuVehiculos4/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'controlDocuVehiculos4',
	'uses' => 'PESV\PesvHacerController@controlDocuVehiculos4',
]);

Route::post('editcontrolDocuVehiculos', [
	'as' => 'editcontrolDocuVehiculos',
	'uses' => 'PESV\PesvHacerController@editcontrolDocuVehiculos',

]);

Route::post('/eliminarConductor', 'PESV\PesvHacerController@eliminarConductor');


Route::get('/vercontrolDocuVehiculos/{id_company}/{id_diagnostico}/{id}', [
	'as' => 'vercontrolDocuVehiculos',
	'uses' => 'PESV\PesvHacerController@vercontrolDocuVehiculos',

]);

Route::get('/controlDocuVehiculospdf/{id_company}/{id}/{id_diagnostico}', [
	'as' => 'controlDocuVehiculospdf',
	'uses' => 'PESV\PesvHacerController@controlDocuVehiculospdf',

]);

Route::post('createpruebasIngresoConduc', [
	'as' => 'createpruebasIngresoConduc',
	'uses' => 'PESV\PesvHacerController@createpruebasIngresoConduc',

]);
Route::post('UpdatepruebasIngresoConduc', [
	'as' => 'UpdatepruebasIngresoConduc',
	'uses' => 'PESV\PesvHacerController@UpdatepruebasIngresoConduc',

]);

Route::post('/pesv_pruebasIngUp', [
	'as' => 'pesv_pruebasIngUp',
	'uses' => 'PESV\PesvHacerController@pesv_pruebasIngUp',
]);

Route::post('/pesv_examenesRiesgosUp', [
	'as' => 'pesv_examenesRiesgosUp',
	'uses' => 'PESV\PesvController@pesv_examenesRiesgosUp',
]);
Route::get('/Acuerdopdf/{id_company}', [
	'as' => 'Acuerdopdf',
	'uses' => 'PESV\PesvHacerController@Acuerdopdf',

]);

Route::get('/pruebasIngresoConduc/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'pruebasIngresoConduc',
	'uses' => 'PESV\PesvHacerController@pruebasIngresoConduc',

]);

Route::get('/verpruebasIngresoConduc/{id_company}/{id_diagnostico}', [
	'as' => 'verpruebasIngresoConduc',
	'uses' => 'PESV\PesvHacerController@verpruebasIngresoConduc',

]);

Route::get('/pruebasIngresoConducpdf/{id_company}/{id_diagnostico}', [
	'as' => 'pruebasIngresoConducpdf',
	'uses' => 'PESV\PesvHacerController@pruebasIngresoConducpdf',

]);

Route::post('createpruebascontrolConduc', [
	'as' => 'createpruebascontrolConduc',
	'uses' => 'PESV\PesvHacerController@createpruebascontrolConduc',

]);

Route::post('updatePruebasControlConduc', [
	'as' => 'updatePruebasControlConduc',
	'uses' => 'PESV\PesvHacerController@updatePruebasControlConduc',
]);

Route::get('/pruebascontrolConduc/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'pruebascontrolConduc',
	'uses' => 'PESV\PesvHacerController@pruebascontrolConduc',

]);

Route::get('/verpruebascontrolConduc/{id_company}/{id_diagnostico}', [
	'as' => 'verpruebascontrolConduc',
	'uses' => 'PESV\PesvHacerController@verpruebascontrolConduc',

]);

Route::get('/pruebascontrolConducpdf/{id_company}/{id_diagnostico}', [
	'as' => 'pruebascontrolConducpdf',
	'uses' => 'PESV\PesvDocController@pruebascontrolConducpdf',

]);

Route::post('createcapacitacionSeguridadVial', [
	'as' => 'createcapacitacionSeguridadVial',
	'uses' => 'PESV\PesvDocController@createcapacitacionSeguridadVial',

]);

Route::get('/capacitacionSeguridadVial/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'capacitacionSeguridadVial',
	'uses' => 'PESV\PesvDocController@capacitacionSeguridadVial',

]);

Route::get('/vercapacitacionSeguridadVial/{id_company}/{id_diagnostico}', [
	'as' => 'vercapacitacionSeguridadVial',
	'uses' => 'PESV\PesvDocController@vercapacitacionSeguridadVial',

]);

Route::get('/capacitacionSeguridadVialpdf/{id_company}/{id_diagnostico}', [
	'as' => 'capacitacionSeguridadVialpdf',
	'uses' => 'PESV\PesvDocController@capacitacionSeguridadVialpdf',

]);

Route::post('createprocediSeleccionConduc', [
	'as' => 'createprocediSeleccionConduc',
	'uses' => 'PESV\PesvHacerController@createprocediSeleccionConduc',

]);
Route::post('updateprocediSeleccionConduc', [
	'as' => 'updateprocediSeleccionConduc',
	'uses' => 'PESV\PesvHacerController@updateprocediSeleccionConduc',

]);

Route::get('/procediSeleccionConduc/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'procediSeleccionConduc',
	'uses' => 'PESV\PesvHacerController@procediSeleccionConduc',

]);

Route::get('/verprocediSeleccionConduc/{id_company}/{id_diagnostico}', [
	'as' => 'verprocediSeleccionConduc',
	'uses' => 'PESV\PesvHacerController@verprocediSeleccionConduc',

]);

Route::get('/procediSeleccionConducpdf/{id_company}/{id_diagnostico}', [
	'as' => 'procediSeleccionConducpdf',
	'uses' => 'PESV\PesvHacerController@procediSeleccionConducpdf',

]);


//pesv_Investigacion_Accidentes


Route::get('/investigacionAccidentes/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'investigacionAccidentes',
	'uses' => 'PESV\PesvHacerController@investigacionAccidentes',

]);

Route::get('/verInvestigacionAccidentes/{id_company}/{id_diagnostico}', [
	'as' => 'verInvestigacionAccidentes',
	'uses' => 'PESV\PesvHacerController@verInvestigacionAccidentes',

]);

Route::get('/investigacionAccidentesPdf/{id_company}/{id_diagnostico}', [
	'as' => 'investigacionAccidentesPdf',
	'uses' => 'PESV\PesvHacerController@investigacionAccidentesPdf',

]);

Route::post('createInvestigacionAccidentes', [
	'as' => 'createInvestigacionAccidentes',
	'uses' => 'PESV\PesvHacerController@createInvestigacionAccidentes',

]);

Route::post('updateInvestigacionAccidentes', [
	'as' => 'updateInvestigacionAccidentes',
	'uses' => 'PESV\PesvHacerController@updateInvestigacionAccidentes',
]);


//END

Route::post('createrutasInternas', [
	'as' => 'createrutasInternas',
	'uses' => 'PESV\PesvHacerController@createrutasInternas',

]);

Route::get('/rutasInternas/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'rutasInternas',
	'uses' => 'PESV\PesvHacerController@rutasInternas',

]);

Route::get('/verrutasInternas/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'verrutasInternas',
	'uses' => 'PESV\PesvHacerController@verrutasInternas',

]);

Route::get('/rutasInternaspdf/{id_company}/{id_diagnostico}', [
	'as' => 'rutasInternaspdf',
	'uses' => 'PESV\PesvHacerController@rutasInternaspdf',

]);
Route::post('updateRutasInternas', [
	'as' => 'updateRutasInternas',
	'uses' => 'PESV\PesvHacerController@updateRutasInternas',

]);
Route::get('rutasInternasArray/{id_company}/{id_principal}/{id_registro}', [
	'as' => 'rutasInternasArray',
	'uses' => 'PESV\PesvHacerController@rutasInternasArray',

]);
Route::post('editRutasInternasArray', [
	'as' => 'editRutasInternasArray',
	'uses' => 'PESV\PesvHacerController@editRutasInternasArray',

]);

Route::get('/protocoloOPM/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'protocoloOPM',
	'uses' => 'PESV\PesvHacerController@protocoloOPM',
]);

Route::get('/verProtocoloOPM/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'verProtocoloOPM',
	'uses' => 'PESV\PesvHacerController@verProtocoloOPM',

]);

Route::get('/pdfProtocoloOPM/{id_company}/{id_registro}', [
	'as' => 'pdfProtocoloOPM',
	'uses' => 'PESV\PesvHacerController@pdfProtocoloOPM',

]);

Route::post('storeProtocoloOPM', [
	'as' => 'storeProtocoloOPM',
	'uses' => 'PESV\PesvHacerController@storeProtocoloOPM',

]);

Route::post('updateProtocoloOPM', [
	'as' => 'updateProtocoloOPM',
	'uses' => 'PESV\PesvHacerController@updateProtocoloOPM',

]);

Route::get('/procedimientoPDL/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'procedimientoPDL',
	'uses' => 'PESV\PesvHacerController@procedimientoPDL',
]);

Route::get('/verProcedimientoPDL/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'verProcedimientoPDL',
	'uses' => 'PESV\PesvHacerController@verProcedimientoPDL',

]);

Route::get('/pdfProcedimientoPDL/{id_company}/{id_registro}', [
	'as' => 'pdfProcedimientoPDL',
	'uses' => 'PESV\PesvHacerController@pdfProcedimientoPDL',

]);

Route::post('storeProcedimientoPDL', [
	'as' => 'storeProcedimientoPDL',
	'uses' => 'PESV\PesvHacerController@storeProcedimientoPDL',

]);

Route::post('updateProcedimientoPDL', [
	'as' => 'updateProcedimientoPDL',
	'uses' => 'PESV\PesvHacerController@updateProcedimientoPDL',

]);


// INICIO GESTIÓN DEL CAMBIO Y GESTIÓN DE CONTRATISTAS //

Route::get('/gestionDelCambio/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'gestionDelCambio',
	'uses' => 'PESV\PesvHacerController@gestionDelCambio',

]);

Route::post('/createGestionDelCambio', [
	'as' => 'createGestionDelCambio',
	'uses' => 'PESV\PesvHacerController@createGestionDelCambio',

]);

Route::get('/verGestionDelCambio/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'verGestionDelCambio',
	'uses' => 'PESV\PesvHacerController@verGestionDelCambio',

]);

Route::post('/editGestionDelCambio', [
	'as' => 'editGestionDelCambio',
	'uses' => 'PESV\PesvHacerController@editGestionDelCambio',

]);

Route::get('/PdfGestionDelCambio/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'PdfGestionDelCambio',
	'uses' => 'PESV\PesvHacerController@PdfGestionDelCambio',

]);


// CIERRE GESTIÓN DEL CAMBIO Y GESTIÓN DE CONTRATISTAS // 

//INICIO DIAGNOSTICO DE LA EMPRESA 18.2
Route::get('/diagnosticoEmpresaId/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'diagnosticoEmpresaId',
	'uses' => 'PESV\PesvHacerController@diagnosticoEmpresaId',

]);

Route::post('/createDiagnosticoEmpresaId', [
	'as' => 'createDiagnosticoEmpresaId',
	'uses' => 'PESV\PesvHacerController@createDiagnosticoEmpresaId',

]);

Route::get('/verDiagnosticoEmpresaId/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'verDiagnosticoEmpresaId',
	'uses' => 'PESV\PesvHacerController@verDiagnosticoEmpresaId',

]);

Route::post('/editDiagnosticoEmpresaId', [
	'as' => 'editDiagnosticoEmpresaId',
	'uses' => 'PESV\PesvHacerController@editDiagnosticoEmpresaId',

]);

Route::get('/PdfDiagnosticoEmpresaId/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'PdfDiagnosticoEmpresaId',
	'uses' => 'PESV\PesvHacerController@PdfDiagnosticoEmpresaId',

]);
// FIN DIAGNOSTICO

//INICIO DIAGNOSTICO DE CLIMA 18.3
Route::get('/diagnosticoClima/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'diagnosticoClima',
	'uses' => 'PESV\PesvHacerController@diagnosticoClima',

]);

Route::post('/createDiagnosticoClima', [
	'as' => 'createDiagnosticoClima',
	'uses' => 'PESV\PesvHacerController@createDiagnosticoClima',

]);

Route::get('/verDiagnosticoClima/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'verDiagnosticoClima',
	'uses' => 'PESV\PesvHacerController@verDiagnosticoClima',

]);

Route::post('/editDiagnosticoClima', [
	'as' => 'editDiagnosticoClima',
	'uses' => 'PESV\PesvHacerController@editDiagnosticoClima',

]);

Route::get('/PdfDiagnosticoClima/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'PdfDiagnosticoClima',
	'uses' => 'PESV\PesvHacerController@PdfDiagnosticoClima',

]);
// FIN DIAGNOSTICO

//INICIO DIAGNOSTICO DE GESTION CONTRATISTAS 18.6
Route::get('/gestionContratistas/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'gestionContratistas',
	'uses' => 'PESV\PesvHacerController@gestionContratistas',

]);

Route::post('/createGestionContratistas', [
	'as' => 'createGestionContratistas',
	'uses' => 'PESV\PesvHacerController@createGestionContratistas',

]);

Route::get('/verGestionContratistas/{id_company}/{id_diagnostico}/{id_registro}/{id_principal}', [
	'as' => 'verGestionContratistas',
	'uses' => 'PESV\PesvHacerController@verGestionContratistas',

]);

Route::post('/editGestionContratistas', [
	'as' => 'editGestionContratistas',
	'uses' => 'PESV\PesvHacerController@editGestionContratistas',

]);

Route::get('/PdfGestionContratistas/{id_company}/{id_diagnostico}/{id_registro}/{id_principal}', [
	'as' => 'PdfGestionContratistas',
	'uses' => 'PESV\PesvHacerController@PdfGestionContratistas',

]);

Route::post('/enviarEmailGestionContratistas', [
	'as' => 'enviarEmailGestionContratistas',
	'uses' => 'PESV\PesvHacerController@enviarEmailGestionContratistas',
]);
// FIN DIAGNOSTICO

//INICIO EJECUCIÓN: IMPLEMENTAR Y HACER SEGUIMIENTO DE UN PLAN DE ACCIÓN CONCRETO 18.4
Route::get('/seguimientoPlanAccion/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'seguimientoPlanAccion',
	'uses' => 'PESV\PesvHacerController@seguimientoPlanAccion',

]);

Route::post('/createSeguimientoPlanAccion', [
	'as' => 'createSeguimientoPlanAccion',
	'uses' => 'PESV\PesvHacerController@createSeguimientoPlanAccion',

]);

Route::get('/verSeguimientoPlanAccion/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'verSeguimientoPlanAccion',
	'uses' => 'PESV\PesvHacerController@verSeguimientoPlanAccion',

]);

Route::post('/editSeguimientoPlanAccion', [
	'as' => 'editSeguimientoPlanAccion',
	'uses' => 'PESV\PesvHacerController@editSeguimientoPlanAccion',

]);

Route::get('/PdfSeguimientoPlanAccion/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'PdfSeguimientoPlanAccion',
	'uses' => 'PESV\PesvHacerController@PdfSeguimientoPlanAccion',

]);
// FIN EJECUCIÓN SEGUIMIENTO





//INICIO EJECUCIÓN: IMPLEMENTAR Y HACER SEGUIMIENTO DE UN PLAN DE ACCIÓN CONCRETO 18.5
Route::get('/MantenimientoFortalecimiento/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'MantenimientoFortalecimiento',
	'uses' => 'PESV\PesvHacerController@MantenimientoFortalecimiento',

]);

Route::post('/createMantenimientoFortalecimiento', [
	'as' => 'createMantenimientoFortalecimiento',
	'uses' => 'PESV\PesvHacerController@createMantenimientoFortalecimiento',

]);

Route::get('/verMantenimientoFortalecimiento/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'verMantenimientoFortalecimiento',
	'uses' => 'PESV\PesvHacerController@verMantenimientoFortalecimiento',

]);

Route::post('/editMantenimientoFortalecimiento', [
	'as' => 'editMantenimientoFortalecimiento',
	'uses' => 'PESV\PesvHacerController@editMantenimientoFortalecimiento',

]);

Route::get('/PdfMantenimientoFortalecimiento/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'PdfMantenimientoFortalecimiento',
	'uses' => 'PESV\PesvHacerController@PdfMantenimientoFortalecimiento',

]);
// FIN EJECUCIÓN SEGUIMIENTO


//Archivo y retención documental 19.1
Route::get('/archivoRetencion/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'archivoRetencion',
	'uses' => 'PESV\PesvHacerController@archivoRetencion',

]);

Route::post('/createArchivoRetencion', [
	'as' => 'createArchivoRetencion',
	'uses' => 'PESV\PesvHacerController@createArchivoRetencion',

]);

Route::get('/verArchivoRetencion/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'verArchivoRetencion',
	'uses' => 'PESV\PesvHacerController@verArchivoRetencion',

]);

Route::post('/editArchivoRetencion', [
	'as' => 'editArchivoRetencion',
	'uses' => 'PESV\PesvHacerController@editArchivoRetencion',

]);

Route::get('/PdfArchivoRetencion/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'PdfArchivoRetencion',
	'uses' => 'PESV\PesvHacerController@PdfArchivoRetencion',

]);
// FIN Archivo y retención documental 19.1

// comienzo pesv hacer 14.2 zonas Publicas
Route::post('createZonasPublicas', [
	'as' => 'createZonasPublicas',
	'uses' => 'PESV\PesvHacerController@createZonasPublicas',

]);

Route::get('/zonasPublicas/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'zonasPublicas',
	'uses' => 'PESV\PesvHacerController@zonasPublicas',

]);

Route::get('/verZonasPublicas/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'verZonasPublicas',
	'uses' => 'PESV\PesvHacerController@verZonasPublicas',

]);

Route::get('/zonasPublicaspdf/{id_company}/{id_diagnostico}', [
	'as' => 'zonasPublicaspdf',
	'uses' => 'PESV\PesvHacerController@zonasPublicaspdf',

]);
Route::post('updateZonasPublicas', [
	'as' => 'updateZonasPublicas',
	'uses' => 'PESV\PesvHacerController@updateZonasPublicas',

]);
Route::get('zonasPublicasArray/{id_company}/{id_principal}/{id_registro}', [
	'as' => 'zonasPublicasArray',
	'uses' => 'PESV\PesvHacerController@zonasPublicasArray',

]);
Route::post('editZonasPublicasArray', [
	'as' => 'editZonasPublicasArray',
	'uses' => 'PESV\PesvHacerController@editZonasPublicasArray',

]);
//  fin pesv hacer 14.2 
// pesv hacer 15.1
Route::post('createrutasExternas', [
	'as' => 'createrutasExternas',
	'uses' => 'PESV\PesvHacerController@createrutasExternas',

]);

Route::get('/rutasExternas/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'rutasExternas',
	'uses' => 'PESV\PesvHacerController@rutasExternas',

]);

Route::get('/verrutasExternas/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'verrutasExternas',
	'uses' => 'PESV\PesvHacerController@verrutasExternas',

]);

Route::post('updaterutasExternas', [
	'as' => 'updaterutasExternas',
	'uses' => 'PESV\PesvHacerController@updaterutasExternas',

]);

Route::get('/rutasExternaspdf/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'rutasExternaspdf',
	'uses' => 'PESV\PesvHacerController@rutasExternaspdf',

]);
// fin pesv hacer 15.1
// pesv hacer 15.2
Route::post('createrutasExternasZonas', [
	'as' => 'createrutasExternasZonas',
	'uses' => 'PESV\PesvHacerController@createrutasExternasZonas',

]);

Route::get('/rutasExternasZonas/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'rutasExternasZonas',
	'uses' => 'PESV\PesvHacerController@rutasExternasZonas',

]);

Route::get('/verrutasExternasZonas/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'verrutasExternasZonas',
	'uses' => 'PESV\PesvHacerController@verrutasExternasZonas',

]);

Route::post('updaterutasExternasZonas', [
	'as' => 'updaterutasExternasZonas',
	'uses' => 'PESV\PesvHacerController@updaterutasExternasZonas',

]);

Route::get('/rutasExternasZonaspdf/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'rutasExternasZonaspdf',
	'uses' => 'PESV\PesvHacerController@rutasExternasZonaspdf',

]);
// fin pesv hacer 15.2

Route::post('createrecomendacionesTecni', [
	'as' => 'createrecomendacionesTecni',
	'uses' => 'PESV\PesvHacerController@createrecomendacionesTecni',

]);

Route::get('/recomendacionesTecni/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'recomendacionesTecni',
	'uses' => 'PESV\PesvHacerController@recomendacionesTecni',

]);

Route::get('/verrecomendacionesTecni/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'verrecomendacionesTecni',
	'uses' => 'PESV\PesvHacerController@verrecomendacionesTecni',

]);

Route::get('/recomendacionesTecnipdf/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'recomendacionesTecnipdf',
	'uses' => 'PESV\PesvHacerController@recomendacionesTecnipdf',

]);

Route::post('editRecomendacionesTecni', [
	'as' => 'editRecomendacionesTecni',
	'uses' => 'PESV\PesvHacerController@editRecomendacionesTecni',

]);


Route::post('createcronogramaVehiculosPro', [
	'as' => 'createcronogramaVehiculosPro',
	'uses' => 'PESV\PesvHacerController@createcronogramaVehiculosPro',

]);

Route::get('/cronogramaVehiculosPro/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'cronogramaVehiculosPro',
	'uses' => 'PESV\PesvHacerController@cronogramaVehiculosPro',

]);

Route::post('editcronogramaVehiculosPro', [
	'as' => 'editcronogramaVehiculosPro',
	'uses' => 'PESV\PesvHacerController@editcronogramaVehiculosPro',

]);

Route::get('/vercronogramaVehiculosPro/{id_company}/{id_diagnostico}/{id_cronograma}/{id_registro}', [
	'as' => 'vercronogramaVehiculosPro',
	'uses' => 'PESV\PesvHacerController@vercronogramaVehiculosPro',

]);

Route::get('/cronogramaVehiculosPropdf/{id_company}/{id_diagnostico}/{id_cronograma}/{id_registro}', [
	'as' => 'cronogramaVehiculosPropdf',
	'uses' => 'PESV\PesvHacerController@cronogramaVehiculosPropdf',

]);

Route::post('createmantenimientoCorrectivo', [
	'as' => 'createmantenimientoCorrectivo',
	'uses' => 'PESV\PesvHacerController@createmantenimientoCorrectivo',

]);

Route::get('/mantenimientoCorrectivo/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'mantenimientoCorrectivo',
	'uses' => 'PESV\PesvHacerController@mantenimientoCorrectivo',

]);

Route::post('editMantenimientoCorrectivo', [
	'as' => 'editMantenimientoCorrectivo',
	'uses' => 'PESV\PesvHacerController@editMantenimientoCorrectivo',

]);

Route::get('/vermantenimientoCorrectivo/{id_company}/{id_diagnostico}/{id_mantenimiento}/{id_registro}', [
	'as' => 'vermantenimientoCorrectivo',
	'uses' => 'PESV\PesvHacerController@vermantenimientoCorrectivo',

]);

Route::get('/mantenimientoCorrectivopdf/{id_company}/{id_diagnostico}/{id_mantenimiento}/{id_registro}', [
	'as' => 'mantenimientoCorrectivopdf',
	'uses' => 'PESV\PesvHacerController@mantenimientoCorrectivopdf',

]);

Route::post('createidoneidadManteniPreven', [
	'as' => 'createidoneidadManteniPreven',
	'uses' => 'PESV\PesvDocController@createidoneidadManteniPreven',

]);

Route::get('/idoneidadManteniPreven/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'idoneidadManteniPreven',
	'uses' => 'PESV\PesvDocController@idoneidadManteniPreven',

]);

Route::get('/veridoneidadManteniPreven/{id_company}/{id_diagnostico}', [
	'as' => 'veridoneidadManteniPreven',
	'uses' => 'PESV\PesvDocController@veridoneidadManteniPreven',

]);

Route::get('/idoneidadManteniPrevenpdf/{id_company}/{id_diagnostico}', [
	'as' => 'idoneidadManteniPrevenpdf',
	'uses' => 'PESV\PesvDocController@idoneidadManteniPrevenpdf',

]);

Route::post('createidoneidadManteniCorrec', [
	'as' => 'createidoneidadManteniCorrec',
	'uses' => 'PESV\PesvDocController@createidoneidadManteniCorrec',

]);

Route::get('/idoneidadManteniCorrec/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'idoneidadManteniCorrec',
	'uses' => 'PESV\PesvDocController@idoneidadManteniCorrec',

]);

Route::get('/veridoneidadManteniCorrec/{id_company}/{id_diagnostico}', [
	'as' => 'veridoneidadManteniCorrec',
	'uses' => 'PESV\PesvDocController@veridoneidadManteniCorrec',

]);

Route::get('/idoneidadManteniCorrecpdf/{id_company}/{id_diagnostico}', [
	'as' => 'idoneidadManteniCorrecpdf',
	'uses' => 'PESV\PesvDocController@idoneidadManteniCorrecpdf',

]);

Route::post('createinspeccionPreoperacional', [
	'as' => 'createinspeccionPreoperacional',
	'uses' => 'PESV\PesvHacerController@createinspeccionPreoperacional',

]);

Route::get('/inspeccionPreoperacional/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'inspeccionPreoperacional',
	'uses' => 'PESV\PesvHacerController@inspeccionPreoperacional',

]);

Route::post('editInspeccionPreoperacional', [
	'as' => 'editInspeccionPreoperacional',
	'uses' => 'PESV\PesvHacerController@editInspeccionPreoperacional',

]);


Route::get('/verInspeccionPreoperacional/{id_company}/{id_diagnostico}/{id_registro}/{id_principal}', [
	'as' => 'verInspeccionPreoperacional',
	'uses' => 'PESV\PesvHacerController@verInspeccionPreoperacional',

]);

Route::get('/inspeccionPreoperacionalpdf/{id_company}/{id_diagnostico}/{id_registro}/{id_principal}', [
	'as' => 'inspeccionPreoperacionalpdf',
	'uses' => 'PESV\PesvHacerController@inspeccionPreoperacionalpdf',

]);



Route::post('createdocuPoliticaComite', [
	'as' => 'createdocuPoliticaComite',
	'uses' => 'PESV\PesvDocController@createdocuPoliticaComite',

]);

Route::get('/docuPoliticaComite/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'docuPoliticaComite',
	'uses' => 'PESV\PesvDocController@docuPoliticaComite',

]);

Route::get('/verdocuPoliticaComite/{id_company}/{id_diagnostico}', [
	'as' => 'verdocuPoliticaComite',
	'uses' => 'PESV\PesvDocController@verdocuPoliticaComite',

]);

Route::get('/docuPoliticaComitepdf/{id_company}/{id_diagnostico}', [
	'as' => 'docuPoliticaComitepdf',
	'uses' => 'PESV\PesvDocController@docuPoliticaComitepdf',

]);

Route::post('createdivulgacionPolitica', [
	'as' => 'createdivulgacionPolitica',
	'uses' => 'PESV\PesvDocController@createdivulgacionPolitica',

]);

Route::get('/divulgacionPolitica/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'divulgacionPolitica',
	'uses' => 'PESV\PesvDocController@divulgacionPolitica',

]);

Route::get('/verDatoCompany/{id_company}', [
	'as' => 'verDatoCompany',
	'uses' => 'Company\CompanyController@verDatoCompany',

]);


Route::get('/verRSSCompany/{id_company}', [
	'as' => 'verRSSCompany',
	'uses' => 'Company\CompanyController@verRSSCompany',

]);

Route::get('/verArlCompany/{id_company}', [
	'as' => 'verArlCompany',
	'uses' => 'Company\CompanyController@verArlCompany',

]);

Route::get('/verArchivoCompany/{id_company}', [
	'as' => 'verArchivoCompany',
	'uses' => 'Company\CompanyController@verArchivoCompany',

]);



Route::get('/verdivulgacionPolitica/{id_company}/{id_diagnostico}', [
	'as' => 'verdivulgacionPolitica',
	'uses' => 'PESV\PesvDocController@verdivulgacionPolitica',

]);

Route::get('/divulgacionPoliticapdf/{id_company}/{id_diagnostico}', [
	'as' => 'divulgacionPoliticapdf',
	'uses' => 'PESV\PesvDocController@divulgacionPoliticapdf',

]);

Route::post('createcaracteristicasEmpresa', [
	'as' => 'createcaracteristicasEmpresa',
	'uses' => 'PESV\PesvDocController@createcaracteristicasEmpresa',

]);

Route::get('/caracteristicasEmpresa/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'caracteristicasEmpresa',
	'uses' => 'PESV\PesvDocController@caracteristicasEmpresa',

]);

Route::get('/vercaracteristicasEmpresa/{id_company}/{id_diagnostico}', [
	'as' => 'vercaracteristicasEmpresa',
	'uses' => 'PESV\PesvDocController@vercaracteristicasEmpresa',

]);

Route::get('/caracteristicasEmpresapdf/{id_company}/{id_diagnostico}', [
	'as' => 'caracteristicasEmpresapdf',
	'uses' => 'PESV\PesvDocController@caracteristicasEmpresapdf',

]);

Route::post('createimplementacionAcciones', [
	'as' => 'createimplementacionAcciones',
	'uses' => 'PESV\PesvDocController@createimplementacionAcciones',

]);

Route::get('/implementacionAcciones/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'implementacionAcciones',
	'uses' => 'PESV\PesvDocController@implementacionAcciones',

]);

Route::get('/verimplementacionAcciones/{id_company}/{id_diagnostico}', [
	'as' => 'verimplementacionAcciones',
	'uses' => 'PESV\PesvDocController@verimplementacionAcciones',

]);

Route::get('/implementacionAccionespdf/{id_company}/{id_diagnostico}', [
	'as' => 'implementacionAccionespdf',
	'uses' => 'PESV\PesvDocController@implementacionAccionespdf',

]);

Route::post('createauditorias', [
	'as' => 'createauditorias',
	'uses' => 'PESV\PesvDocController@createauditorias',

]);

Route::get('/auditorias/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'auditorias',
	'uses' => 'PESV\PesvDocController@auditorias',

]);

Route::get('/verauditorias/{id_company}/{id_diagnostico}', [
	'as' => 'verauditorias',
	'uses' => 'PESV\PesvDocController@verauditorias',

]);

Route::get('/auditoriaspdf/{id_company}/{id_diagnostico}', [
	'as' => 'auditoriaspdf',
	'uses' => 'PESV\PesvDocController@auditoriaspdf',

]);

Route::post('createprotocolos', [
	'as' => 'createprotocolos',
	'uses' => 'PESV\PesvDocController@createprotocolos',

]);

Route::get('/protocolos/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'protocolos',
	'uses' => 'PESV\PesvDocController@protocolos',

]);

Route::get('/verprotocolos/{id_company}/{id_diagnostico}', [
	'as' => 'verprotocolos',
	'uses' => 'PESV\PesvDocController@verprotocolos',

]);

Route::get('/protocolospdf/{id_company}/{id_diagnostico}', [
	'as' => 'protocolospdf',
	'uses' => 'PESV\PesvDocController@protocolospdf',

]);

Route::post('createindicadoresAcci', [
	'as' => 'createindicadoresAcci',
	'uses' => 'PESV\PesvDocController@createindicadoresAcci',

]);

Route::get('/indicadoresAcci/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'indicadoresAcci',
	'uses' => 'PESV\PesvDocController@indicadoresAcci',

]);

Route::get('/verindicadoresAcci/{id_company}/{id_diagnostico}', [
	'as' => 'verindicadoresAcci',
	'uses' => 'PESV\PesvDocController@verindicadoresAcci',

]);

Route::get('/indicadoresAccipdf/{id_company}/{id_diagnostico}', [
	'as' => 'indicadoresAccipdf',
	'uses' => 'PESV\PesvDocController@indicadoresAccipdf',

]);

Route::post('createfuenteInfo', [
	'as' => 'createfuenteInfo',
	'uses' => 'PESV\PesvDocController@createfuenteInfo',

]);

Route::get('/fuenteInfo/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'fuenteInfo',
	'uses' => 'PESV\PesvDocController@fuenteInfo',

]);

Route::get('/verfuenteInfo/{id_company}/{id_diagnostico}', [
	'as' => 'verfuenteInfo',
	'uses' => 'PESV\PesvDocController@verfuenteInfo',

]);

Route::get('/fuenteInfopdf/{id_company}/{id_diagnostico}', [
	'as' => 'fuenteInfopdf',
	'uses' => 'PESV\PesvDocController@fuenteInfopdf',

]);

Route::post('createinfoDocumentada', [
	'as' => 'createinfoDocumentada',
	'uses' => 'PESV\PesvDocController@createinfoDocumentada',

]);

Route::get('/infoDocumentada/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'infoDocumentada',
	'uses' => 'PESV\PesvDocController@infoDocumentada',

]);

Route::get('/verinfoDocumentada/{id_company}/{id_diagnostico}', [
	'as' => 'verinfoDocumentada',
	'uses' => 'PESV\PesvDocController@verinfoDocumentada',

]);

Route::get('/infoDocumentadapdf/{id_company}/{id_diagnostico}', [
	'as' => 'infoDocumentadapdf',
	'uses' => 'PESV\PesvDocController@infoDocumentadapdf',

]);

Route::post('createvalorAgregado', [
	'as' => 'createvalorAgregado',
	'uses' => 'PESV\PesvDocController@createvalorAgregado',

]);

Route::get('/valorAgregado/{id_company}/{id_diagnostico}/{id_para}', [
	'as' => 'valorAgregado',
	'uses' => 'PESV\PesvDocController@valorAgregado',

]);

Route::get('/vervalorAgregado/{id_company}/{id_diagnostico}', [
	'as' => 'vervalorAgregado',
	'uses' => 'PESV\PesvDocController@vervalorAgregado',

]);

Route::get('/valorAgregadopdf/{id_company}/{id_diagnostico}', [
	'as' => 'valorAgregadopdf',
	'uses' => 'PESV\PesvDocController@valorAgregadopdf',

]);

Route::get('/newFormat1/{id_registro}/{id_empresa}/{id_diagnostico}', [
	'as' => 'newFormat1',
	'uses' => 'PESV\PesvController@newFormat1',
]);

Route::get('/newFormat1PDF/{id_registro}/{id_empresa}/{id_diagnostico}', [
	'as' => 'newFormat1PDF',
	'uses' => 'PESV\PesvController@newFormat1PDF',
]);

Route::post('/createnewFormat1', [
	'as' => 'createnewFormat1',
	'uses' => 'PESV\PesvController@createnewFormat1',
]);

Route::get('/newFormat2/{id_registro}/{id_empresa}/{id_diagnostico}', [
	'as' => 'newFormat2',
	'uses' => 'PESV\PesvController@newFormat2',
]);

Route::get('/newFormat2PDF/{id_registro}/{id_empresa}/{id_diagnostico}', [
	'as' => 'newFormat2PDF',
	'uses' => 'PESV\PesvController@newFormat2PDF',
]);

Route::post('/createnewFormat2', [
	'as' => 'createnewFormat2',
	'uses' => 'PESV\PesvController@createnewFormat2',
]);

Route::get('/newFormat3/{id_registro}/{id_empresa}/{id_diagnostico}', [
	'as' => 'newFormat3',
	'uses' => 'PESV\PesvController@newFormat3',
]);
Route::get('/newFormat3PDF/{id_registro}/{id_empresa}/{id_diagnostico}', [
	'as' => 'newFormat3PDF',
	'uses' => 'PESV\PesvController@newFormat3PDF',
]);

Route::post('/createnewFormat3', [
	'as' => 'createnewFormat3',
	'uses' => 'PESV\PesvController@createnewFormat3',
]);

Route::get('/newFormat4/{id_registro}/{id_empresa}/{id_diagnostico}', [
	'as' => 'newFormat4',
	'uses' => 'PESV\PesvController@newFormat4',
]);

Route::get('/newFormat4PDF/{id_registro}/{id_empresa}/{id_diagnostico}', [
	'as' => 'newFormat4PDF',
	'uses' => 'PESV\PesvController@newFormat4PDF',
]);

Route::post('/createnewFormat4', [
	'as' => 'createnewFormat4',
	'uses' => 'PESV\PesvController@createnewFormat4',
]);
Route::get('/newFormat5/{id_registro}/{id_empresa}/{id_diagnostico}', [
	'as' => 'newFormat5',
	'uses' => 'PESV\PesvController@newFormat5',
]);

Route::get('/newFormat5PDF/{id_registro}/{id_empresa}/{id_diagnostico}', [
	'as' => 'newFormat5PDF',
	'uses' => 'PESV\PesvController@newFormat5PDF',
]);
Route::get('/evaluavionNewFormat5PDF/{id_empresa}', [
	'as' => 'evaluavionNewFormat5PDF',
	'uses' => 'PESV\PesvController@evaluavionNewFormat5PDF',
]);

Route::post('/createnewFormat5', [
	'as' => 'createnewFormat5',
	'uses' => 'PESV\PesvController@createnewFormat5',
]);
Route::get('/newFormat6/{id_registro}/{id_empresa}/{id_diagnostico}', [
	'as' => 'newFormat6',
	'uses' => 'PESV\PesvController@newFormat6',
]);

Route::get('/newFormat6PDF/{id_registro}/{id_empresa}/{id_diagnostico}', [
	'as' => 'newFormat6PDF',
	'uses' => 'PESV\PesvController@newFormat6PDF',
]);
Route::get('/evaluavionNewFormat6PDF/{id_empresa}', [
	'as' => 'evaluavionNewFormat6PDF',
	'uses' => 'PESV\PesvController@evaluavionNewFormat6PDF',
]);

Route::post('/createnewFormat6', [
	'as' => 'createnewFormat6',
	'uses' => 'PESV\PesvController@createnewFormat6',
]);
Route::get('/newFormat7/{id_registro}/{id_empresa}/{id_diagnostico}', [
	'as' => 'newFormat7',
	'uses' => 'PESV\PesvController@newFormat7',
]);

Route::get('/newFormat7PDF/{id_registro}/{id_empresa}/{id_diagnostico}', [
	'as' => 'newFormat7PDF',
	'uses' => 'PESV\PesvController@newFormat7PDF',
]);
Route::get('/evaluavionNewFormat7PDF/{id_empresa}', [
	'as' => 'evaluavionNewFormat7PDF',
	'uses' => 'PESV\PesvController@evaluavionNewFormat7PDF',
]);

Route::post('/createnewFormat7', [
	'as' => 'createnewFormat7',
	'uses' => 'PESV\PesvController@createnewFormat7',
]);
Route::get('/newFormat8/{id_registro}/{id_empresa}/{id_diagnostico}', [
	'as' => 'newFormat8',
	'uses' => 'PESV\PesvController@newFormat8',
]);

Route::get('/newFormat8PDF/{id_registro}/{id_empresa}/{id_diagnostico}', [
	'as' => 'newFormat8PDF',
	'uses' => 'PESV\PesvController@newFormat8PDF',
]);
Route::get('/evaluavionNewFormat8PDF/{id_empresa}', [
	'as' => 'evaluavionNewFormat8PDF',
	'uses' => 'PESV\PesvController@evaluavionNewFormat8PDF',
]);

Route::post('/createnewFormat8', [
	'as' => 'createnewFormat8',
	'uses' => 'PESV\PesvController@createnewFormat8',
]);
Route::get('/newFormat9/{id_registro}/{id_empresa}/{id_diagnostico}', [
	'as' => 'newFormat9',
	'uses' => 'PESV\PesvController@newFormat9',
]);
Route::get('/newFormat9PDF/{id_registro}/{id_empresa}/{id_diagnostico}', [
	'as' => 'newFormat9PDF',
	'uses' => 'PESV\PesvController@newFormat9PDF',
]);
Route::get('/evaluavionNewFormat9PDF/{id_empresa}', [
	'as' => 'evaluavionNewFormat9PDF',
	'uses' => 'PESV\PesvController@evaluavionNewFormat9PDF',
]);

Route::post('/createnewFormat9', [
	'as' => 'createnewFormat9',
	'uses' => 'PESV\PesvController@createnewFormat9',
]);

Route::post('/updateEncuestaArrays', [
	'as' => 'updateEncuestaArrays',
	'uses' => 'PESV\PesvController@updateEncuestaArrays',
]);

Route::get('/listcompanyPesv', [
	'as' => 'listcompanyPesv',
	'uses' => 'Company\CompanyController@companyPesv',
]);
Route::get('/encabezado19/{id_company}/{id_diagnostico}', [
	'as' => 'encabezado19',
	'uses' => 'PESV\PesvDocController@encabezado19',
]);
// INCIO PESV VERIFICAR
Route::post('/pesvListadoMaestroStore', [
	'as' => 'pesvListadoMaestroStore',
	'uses' => 'PESV\PesvHacerController@pesvListadoMaestroStore',
]);
// RUTAS GENERALES PESV VERIFICAR
Route::post('/createCalificacionPesvVerificar', [
	'as' => 'createCalificacionPesvVerificar',
	'uses' => 'PESV\PesvVerificarController@createCalificacionPesvVerificar',
]);
Route::post('/createDocVerificar', [
	'as' => 'createDocVerificar',
	'uses' => 'PESV\PesvVerificarController@createDocVerificar',
]);
//

// REGISTRO DOCUMENTOS GENERAL
Route::get('/createRecordsDocs/{id_company}/{id_diagnostico}', [
	'as' => 'createRecordsDocs',
	'uses' => 'PESV\PesvVerificarController@createRecordsDocs',
]);
// FIN REGISTRO DOCUMENTOS GENERAL

// EDITAR DOCUMENTOS GENERAL
Route::get('/editRecordsDocs/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'editRecordsDocs',
	'uses' => 'PESV\PesvVerificarController@editRecordsDocs',
]);
// FIN REGISTRO DOCUMENTOS GENERAL

// PDF DOCUMENTOS GENERAL
Route::get('/pdfRecordsDocs/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'pdfRecordsDocs',
	'uses' => 'PESV\PesvVerificarController@pdfRecordsDocs',
]);
// FIN REGISTRO DOCUMENTOS GENERAL

// INICIO INFORME AUDITORIA
Route::post('/createOrUpdateInformeAu', [
	'as' => 'createOrUpdateInformeAu',
	'uses' => 'PESV\PesvVerificarController@createOrUpdateInformeAu',
]);
// FIN INFORME AUDITORIA

// INICIO PROCEDIMIENTO AUDITORIA
Route::post('/createOrUpdateProcedimientoAu', [
	'as' => 'createOrUpdateProcedimientoAu',
	'uses' => 'PESV\PesvVerificarController@createOrUpdateProcedimientoAu',
]);
// FIN PROCEDIMIENTO AUDITORIA

// INICIO CONSIDERACIONES GENERALES
Route::post('/createOrUpdateConsideraciones', [
	'as' => 'createOrUpdateConsideraciones',
	'uses' => 'PESV\PesvVerificarController@createOrUpdateConsideraciones',
]);
// FIN CONSIDERACIONES GENERALES

// INICIO DESCRIPCIÒN DE ACTIVIDADES
Route::post('/createOrUpdateDescripcion', [
	'as' => 'createOrUpdateDescripcion',
	'uses' => 'PESV\PesvVerificarController@createOrUpdateDescripcion',
]);
// FIN DESCRIPCIÒN DE ACTIVIDADES

// INICIO DATOS MENSUALES
Route::post('/createOrUpdateDatosMensuales', [
	'as' => 'createOrUpdateDatosMensuales',
	'uses' => 'PESV\PesvVerificarController@createOrUpdateDatosMensuales',
]);
// FIN DATOS MENSUALES

// INICIO COSTOS 20.2
Route::get('/tipoCosto/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'tipoCosto',
	'uses' => 'PESV\PesvVerificarController@tipoCosto',
]);

Route::get('/tipoCostoCreateOrEdit/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'tipoCostoCreateOrEdit',
	'uses' => 'PESV\PesvVerificarController@tipoCostoCreateOrEdit',
]);

Route::post('/createOrUpdateCostos', [
	'as' => 'createOrUpdateCostos',
	'uses' => 'PESV\PesvVerificarController@createOrUpdateCostos',
]);

Route::post('/createOrUpdateTipoCosto', [
	'as' => 'createOrUpdateTipoCosto',
	'uses' => 'PESV\PesvVerificarController@createOrUpdateTipoCosto',
]);
// FIN COSTOS 20.2

// INICIO INDICADORES 20.3
Route::get('/createOrEditRecordsIndicador/{id_company}/{id_diagnostico}/{id_principal}/{id_indicador}/{id_registro}', [
	'as' => 'createOrEditRecordsIndicador',
	'uses' => 'PESV\PesvVerificarController@createOrEditRecordsIndicador',
]);

Route::get('/listIndicadoresVerificar/{id_company}/{id_diagnostico}/{id_indicador}/{id_registro}', [
	'as' => 'listIndicadoresVerificar',
	'uses' => 'PESV\PesvVerificarController@listIndicadoresVerificar',
]);

Route::get('/PDFIndicadoresVerificar/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'PDFIndicadoresVerificar',
	'uses' => 'PESV\PesvVerificarController@PDFIndicadoresVerificar',
]);

Route::post('/createOrUpdateIndicadores', [
	'as' => 'createOrUpdateIndicadores',
	'uses' => 'PESV\PesvVerificarController@createOrUpdateIndicadores',
]);

Route::post('/createOrUpdateIndicador', [
	'as' => 'createOrUpdateIndicador',
	'uses' => 'PESV\PesvVerificarController@createOrUpdateIndicador',
]);

Route::get('/ExcelIndicadores/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'ExcelIndicadores',
	'uses' => 'PESV\PesvVerificarController@ExcelIndicadores',
]);
// FIN INDICADORES 20.3

// INICIO CARACTERIZACIÓN 20.4
Route::post('/createOrUpdateCaracterizacionAcc', [
	'as' => 'createOrUpdateCaracterizacionAcc',
	'uses' => 'PESV\PesvVerificarController@createOrUpdateCaracterizacionAcc',
]);
// FIN CARACTERIZACIÓN 20.4

// INICIO COMPARENDOS 20.5
Route::post('/createOrUpdateComparendos', [
	'as' => 'createOrUpdateComparendos',
	'uses' => 'PESV\PesvVerificarController@createOrUpdateComparendos',
]);
// FIN COMPARENDOS 20.5

// INICIO MATRIZ PERDIDA 21.1
Route::post('/createOrUpdateSiniestrosViales', [
	'as' => 'createOrUpdateSiniestrosViales',
	'uses' => 'PESV\PesvVerificarController@createOrUpdateSiniestrosViales',
]);
// FIN MATRIZ PERDIDA 21.1

// INICIO MATRIZ PERDIDA 21.2
Route::post('/createOrUpdateMatrizPerdida', [
	'as' => 'createOrUpdateMatrizPerdida',
	'uses' => 'PESV\PesvVerificarController@createOrUpdateMatrizPerdida',
]);
// FIN MATRIZ PERDIDA 21.2

// INICIO PIRAMIDE HYDEN 21.3
Route::post('/createOrUpdatePiramideHyden', [
	'as' => 'createOrUpdatePiramideHyden',
	'uses' => 'PESV\PesvVerificarController@createOrUpdatePiramideHyden',
]);
// FIN PIRAMIDE HYDEN 21.3

// INICIO ESTRATEGIAS 21.4
Route::post('/createOrUpdateEstrategias', [
	'as' => 'createOrUpdateEstrategias',
	'uses' => 'PESV\PesvVerificarController@createOrUpdateEstrategias',
]);
// FIN ESTRATEGIAS 21.4

// INICIO EXCEL DIAGNOSTICO 209 Y 210
Route::get('/routeExcelRegistros/{id_company}/{id_diagnostico}', [
	'as' => 'routeExcelRegistros',
	'uses' => 'PESV\PesvVerificarController@routeExcelRegistros',
]);
// FIN EXCEL DIAGNOSTICO 209 Y 210

// INICIO ACTA APRTURA 22.3
Route::post('/createOrUpdateActaApertura', [
	'as' => 'createOrUpdateActaApertura',
	'uses' => 'PESV\PesvVerificarController@createOrUpdateActaApertura',
]);
// FIN ACTA APRTURA 22.3

// INICIO ACTA CIERRE 22.5
Route::post('/createOrUpdateActaCierre', [
	'as' => 'createOrUpdateActaCierre',
	'uses' => 'PESV\PesvVerificarController@createOrUpdateActaCierre',
]);
// FIN ACTA CIERRE 22.5
// FIN PESV VERIFICAR

// INICIO PESV ACTUAR

// INICIO RUTAS GENERALES PESV ACTUAR

Route::post('/createCalificacionPesvActuar', [
	'as' => 'createCalificacionPesvActuar',
	'uses' => 'PESV\PesvActuarController@createCalificacionPesvActuar',
]);
Route::post('/createDocActuar', [
	'as' => 'createDocActuar',
	'uses' => 'PESV\PesvActuarController@createDocActuar',
]);

// REGISTRO DOCUMENTOS GENERAL
Route::get('/createRecordsDocsActuar/{id_company}/{id_diagnostico}', [
	'as' => 'createRecordsDocsActuar',
	'uses' => 'PESV\PesvActuarController@createRecordsDocsActuar',
]);

// EDITAR DOCUMENTOS GENERAL
Route::get('/editRecordsDocsActuar/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'editRecordsDocsActuar',
	'uses' => 'PESV\PesvActuarController@editRecordsDocsActuar',
]);

// PDF DOCUMENTOS GENERAL
Route::get('/pdfRecordsDocsActuar/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'pdfRecordsDocsActuar',
	'uses' => 'PESV\PesvActuarController@pdfRecordsDocsActuar',
]);


// FIN RUTAS GENERALES PESV ACTUAR


// INCIO PLAN DE MEJORA 23.1
Route::get('/editSelect/{id_company}/{id_diagnostico}/{parametro}/{id_registro}', [
	'as' => 'editSelect',
	'uses' => 'PESV\PesvActuarController@editSelect',
]);

Route::get('/editSelectVer/{id_company}/{id_diagnostico}/{parametro}/{id_principal}/{id_registro}', [
	'as' => 'editSelectVer',
	'uses' => 'PESV\PesvActuarController@editSelectVer',
]);

Route::post('/createOrUpdatePlanMejora', [
	'as' => 'createOrUpdatePlanMejora',
	'uses' => 'PESV\PesvActuarController@createOrUpdatePlanMejora',
]);

Route::post('/createOrUpdateActuarPlanMejora', [
	'as' => 'createOrUpdateActuarPlanMejora',
	'uses' => 'PESV\PesvActuarController@createOrUpdateActuarPlanMejora',
]);
// FIN PLAN DE MEJORA 23.1


// INCIO PLAN DE COMUNICACIONES 24.1
Route::get('/listadoComunicacion/{id_company}/{id_diagnostico}/{parametro}/{id_registro}', [
	'as' => 'listadoComunicacion',
	'uses' => 'PESV\PesvActuarController@listadoComunicacion',
]);

Route::get('/comunicacionEdit/{id_company}/{id_diagnostico}/{parametro}/{id_principal}/{id_registro}', [
	'as' => 'comunicacionEdit',
	'uses' => 'PESV\PesvActuarController@comunicacionEdit',
]);

Route::post('/createOrUpdateNormasComunicacion', [
	'as' => 'createOrUpdateNormasComunicacion',
	'uses' => 'PESV\PesvActuarController@createOrUpdateNormasComunicacion',
]);

Route::post('/createOrUpdatePlanComunicaciones', [
	'as' => 'createOrUpdatePlanComunicaciones',
	'uses' => 'PESV\PesvActuarController@createOrUpdatePlanComunicaciones',
]);
// FIN PLAN DE COMUNICACIONES 24.1

// FIN PESV ACTUAR

//**************** PLAN DE COMPETENCIA *****************************
Route::get('/planCompetencia/{id_empresa}/{id_diagnostico}/{id}', [
	'as' => 'planCompetencia',
	'uses' => 'PESV\PesvHacerController@planCompetencia',
]);

Route::get('/verPlanCompetencia/{id_empresa}/{id_diagnostico}/{id_registro}', [
	'as' => 'verPlanCompetencia',
	'uses' => 'PESV\PesvHacerController@verPlanCompetencia',
]);

Route::get('/verUpdatePlanCompetencia/{id}/{id_registro}', [
	'as' => 'verUpdatePlanCompetencia',
	'uses' => 'PESV\PesvHacerController@verUpdatePlanCompetencia',
]);

Route::get('/verUpdatePlanCompetenciaPE/{id}/{id_registro}', [
	'as' => 'verUpdatePlanCompetenciaPE',
	'uses' => 'PESV\PesvHacerController@verUpdatePlanCompetenciaPE',
]);

Route::get('/exportExcelPlanCompetencias/{company}/{id_registro}', [
	'as' => 'exportExcelPlanCompetencias',
	'uses' => 'PESV\PesvHacerController@exportExcelPlanCompetencias',
]);

Route::post('/storePlanCompetencia', [
	'as' => 'storePlanCompetencia',
	'uses' => 'PESV\PesvHacerController@storePlanCompetencia',
]);

Route::post('/editPlanCompetenciaInf', [
	'as' => 'editPlanCompetenciaInf',
	'uses' => 'PESV\PesvHacerController@editPlanCompetenciaInf',
]);

Route::post('/updatePlanCompetencia', [
	'as' => 'updatePlanCompetencia',
	'uses' => 'PESV\PesvHacerController@updatePlanCompetencia',
]);

Route::post('/updatePlanCompetenciaPE', [
	'as' => 'updatePlanCompetenciaPE',
	'uses' => 'PESV\PesvHacerController@updatePlanCompetenciaPE',
]);


//**************** Anexo 11 Plan de preparación y respuesta ante emergencias  *****************************
Route::get('/planPreparacion/{id_empresa}/{id_diagnostico}/{id}', [
	'as' => 'planPreparacion',
	'uses' => 'PESV\PesvHacerController@planPreparacion',
]);

Route::get('/verPlanPreparacion/{id_empresa}/{id_diagnostico}/{id_registro}', [
	'as' => 'verPlanPreparacion',
	'uses' => 'PESV\PesvHacerController@verPlanPreparacion',
]);

Route::get('/planPreparacionPDF/{id_registro}/{id_empresa}', [
	'as' => 'planPreparacionPDF',
	'uses' => 'PESV\PesvHacerController@planPreparacionPDF',
]);

Route::post('/storePlanPreparacion', [
	'as' => 'storePlanPreparacion',
	'uses' => 'PESV\PesvHacerController@storePlanPreparacion',
]);

Route::post('/updatePlanPreparacion', [
	'as' => 'updatePlanPreparacion',
	'uses' => 'PESV\PesvHacerController@updatePlanPreparacion',
]);

//**************** Anexo 10 Plan de preparación y respuesta ante emergencias  *****************************
Route::get('/EstrategiaSensi/{id_empresa}/{id_diagnostico}/{id}', [
	'as' => 'EstrategiaSensi',
	'uses' => 'PESV\PesvHacerController@EstrategiaSensi',
]);

Route::get('/verEstrategiaSensi/{id_empresa}/{id_diagnostico}/{id_registro}', [
	'as' => 'verEstrategiaSensi',
	'uses' => 'PESV\PesvHacerController@verEstrategiaSensi',
]);

Route::get('/EstrategiaSensiPDF/{id_registro}/{id_empresa}', [
	'as' => 'EstrategiaSensiPDF',
	'uses' => 'PESV\PesvHacerController@EstrategiaSensiPDF',
]);

Route::post('/storeEstrategiaSensi', [
	'as' => 'storeEstrategiaSensi',
	'uses' => 'PESV\PesvHacerController@storeEstrategiaSensi',
]);

Route::post('/updateEstrategiaSensi', [
	'as' => 'updateEstrategiaSensi',
	'uses' => 'PESV\PesvHacerController@updateEstrategiaSensi',
]);
// RUTA EMPRESA ROL 2 //

Route::get('/empresa/{id}', [
	'as' => 'empresa',
	'uses' => 'Company\CompanyController@empresa',
]);

Route::post('/editarCompany', [
	'as' => 'editarCompany',
	'uses' => 'Company\CompanyController@empresaEdit',
]);


//FINALIZA RUTA EMPRESA ROL 2//

// PLAN DE TRABAJO HACER // MMMM
Route::get('/pesvHacer_planTrabajo/{id_empresa}/{id_diagnostico}/{id}', [
	'as' => 'pesvHacer_planTrabajo',
	'uses' => 'PESV\PesvHacerController@pesvHacer_planTrabajo',
]);

Route::get('/edit_pesvHacer_planTrabajo/{id_empresa}/{id_diagnostico}', [
	'as' => 'edit_pesvHacer_planTrabajo',
	'uses' => 'PESV\PesvHacerController@edit_pesvHacer_planTrabajo',
]);

Route::get('/pdf_pH_PlanDeTrabajo/{id_registro}/{id_company}', 'PESV\PesvHacerController@pdf_pH_PlanDeTrabajo');

Route::get('/pesv_Hacer_crearlistado/{company_id}', [
	'as' => 'pesv_Hacer_crearlistado',
	'uses' => 'PESV\PesvHacerController@pesv_Hacer_crearlistado'
]);

Route::get('/pesvHacer_inaclistado/{company_id}', [
	'as' => 'pesvHacer_inaclistado',
	'uses' => 'PESV\PesvHacerController@pesvHacer_inaclistado'
]);

Route::get('/pesvHacer_editlistado/{company_id}/{id}', [
	'as' => 'pesvHacer_editlistado',
	'uses' => 'PESV\PesvHacerController@pesvHacer_editlistado'
]); //old

Route::get('/pH_editlistado/{company_id}/{id}', [
	'as' => 'pH_editlistado',
	'uses' => 'PESV\PesvHacerController@pH_editlistado'
]); // new

Route::get('/pH_editlistado2/{company_id}/{id}/{id_registro}', [
	'as' => 'pH_editlistado2',
	'uses' => 'PESV\PesvHacerController@pH_editlistado2'
]);

Route::post('/storePlanTrabajoPesv', [
	'as' => 'storePlanTrabajoPesv',
	'uses' => 'PESV\PesvHacerController@pesvHacer_registrolistado'
]);

Route::post('/pH_updatelistado/{id}', [
	'as' => 'pH_updatelistado',
	'uses' => 'PESV\PesvHacerController@pH_updatelistado'
]);

Route::post('/pH_registrolistado', [
	'as' => 'pH_registrolistado',
	'uses' => 'PESV\PesvHacerController@pH_registrolistado'
]);

Route::post('/pH_registrolistadoUpdate', [
	'as' => 'pH_registrolistadoUpdate',
	'uses' => 'PESV\PesvHacerController@pH_registrolistadoUpdate'
]);

Route::post('/pH_registrolistadoPt', [
	'as' => 'pH_registrolistadoPt',
	'uses' => 'PESV\PesvHacerController@pH_registrolistadoPt'
]);

Route::get('/pH_inaclistado/{company_id}', [
	'as' => 'pH_inaclistado',
	'uses' => 'PESV\PesvHacerController@pH_inaclistado'
]);

Route::get('/pH_verstore241/{id_registro}/{id_company}', [
	'as' => 'pH_verstore241',
	'uses' => 'PESV\PesvHacerController@pH_verstore241',

]);

Route::post('/pH_deshabilitadolistado/{company_id}', [
	'as' => 'pH_deshabilitadolistado',
	'uses' => 'PESV\PesvHacerController@pH_deshabilitadolistado'
]);

Route::get('/pH_registrarListado/{company_id}/', [
	'as' => 'pH_registrarListado',
	'uses' => 'PESV\PesvHacerController@pH_registrarListado'
]);

Route::get('/pdfPlanDeTrabajo/{company_id}/{id}', 'PESV\PesvHacerController@pdfPlanDeTrabajo');

Route::post('/pH_storelistado', [
	'as' => 'pH_storelistado',
	'uses' => 'PESV\PesvHacerController@pH_storelistado'
]);

Route::get('/pH_editlistado3/{id_company}/{id_registro}/{id}', [
	'as' => 'pH_editlistado3',
	'uses' => 'PESV\PesvHacerController@pH_editlistado3',
]);

Route::post('/pH_editar241', [
	'as' => 'pH_editar241',
	'uses' => 'PESV\PesvHacerController@pH_editar241',
]);

Route::get('indexPesvEvaPrevencion', [
	'as' => 'indexPesvEvaPrevencion',
	'uses' => 'PESV\PesvController@indexPesvEvaPrevencion'
]);

Route::post('pesvEvaluacion', [
	'as' => 'pesvEvaluacion',
	'uses' => 'PESV\PesvController@pesvEvaluacion',
]);

Route::get('listPesvEvaluaciones/{tipoEvaluacion}/{id_company}', [
	'as' => 'listPesvEvaluaciones',
	'uses' => 'PESV\PesvController@listPesvEvaluaciones',
]);

Route::get('listPesvEvaResult/{tipoEvaluacion}/{id_company}/{id_user}', [
	'as' => 'listPesvEvaResult',
	'uses' => 'PESV\PesvController@listPesvEvaResult',
]);



///// fin rutas PESV/////////////////////
/////  rutas puesto de trabajo/////////////////////

Route::get('/PuestoT/{id_empresa}/{id_user}/{id_cons}', [
	'as' => 'PuestoT',
	'uses' => 'PuestoT\PuestoTController@PuestoT',
]);

Route::get('/ListPuesto/{id_empresa}', [
	'as' => 'ListPuesto',
	'uses' => 'PuestoT\PuestoTController@ListPuesto',
]);

Route::get('/recomendacionescrear', [
	'as' => 'recomendacionescrear',
	'uses' => 'PuestoT\PuestoTController@newRecomendaciones',
]);

Route::get('/borrarrecomendacion/{id}', [
	'as' => 'borrarrecomendacion',
	'uses' => 'PuestoT\PuestoTController@borrarrecomendacion',
]);

Route::post('/createReco', [
	'as' => 'createReco',
	'uses' => 'PuestoT\PuestoTController@createReco',
]);

Route::get('/ListEmple/{id_empresa}', [
	'as' => 'ListEmple',
	'uses' => 'PuestoT\PuestoTController@ListEmple',
]);

Route::any('/createPT', [
	'as' => 'createPT',
	'uses' => 'PuestoT\PuestoTController@createPT',
]);

Route::any('/createcambio', [
	'as' => 'createcambio',
	'uses' => 'Agenda\AgendaController@createcambio',
]);

Route::get('/ListHistorico/{id_empresa}/{id_user}', [
	'as' => 'ListHistorico',
	'uses' => 'PuestoT\PuestoTController@ListHistorico',
]);

Route::get('/ListUser/{id_empresa}', [
	'as' => 'ListUser',
	'uses' => 'PuestoT\PuestoTController@ListUser',
]);

Route::get('/editreco/{id_empresa}', [
	'as' => 'editreco',
	'uses' => 'PuestoT\PuestoTController@editreco',
]);

Route::get('/borrarreco/{id_empresa}', [
	'as' => 'borrarreco',
	'uses' => 'PuestoT\PuestoTController@borrarreco',
]);

Route::get('/borrarrecoE/{id_empresa}', [
	'as' => 'borrarrecoE',
	'uses' => 'PuestoT\PuestoTController@borrarrecoE',
]);

Route::get('/editrecoE/{id_empresa}', [
	'as' => 'editrecoE',
	'uses' => 'PuestoT\PuestoTController@editrecoE',
]);

Route::post('/editarrecoE', [
	'as' => 'editarrecoE',
	'uses' => 'PuestoT\PuestoTController@editarrecoE',
]);

Route::post('/editarreco', [
	'as' => 'editarreco',
	'uses' => 'PuestoT\PuestoTController@editarreco',
]);

Route::get('/EmpresasAsociadas', [
	'as' => 'EmpresasAsociadas',
	'uses' => 'PuestoT\PuestoTController@EmpresasAsociadas',
]);

Route::get('/puestoIPT', [
	'as' => 'puestoIPT',
	'uses' => 'PuestoT\PuestoTController@puestoIPT',
]);

Route::get('/editRecomendaciones/{id}', [
	'as' => 'editRecomendaciones',
	'uses' => 'PuestoT\PuestoTController@editRecomendaciones',
]);

Route::get('/editplano/{id}', [
	'as' => 'editplano',
	'uses' => 'PuestoT\PuestoTController@editplano',
]);

Route::post('/editarplano', [
	'as' => 'editarplano',
	'uses' => 'PuestoT\PuestoTController@editarplano',
]);

Route::get('/editherramientas/{id}', [
	'as' => 'editherramientas',
	'uses' => 'PuestoT\PuestoTController@editherramientas',
]);

Route::post('/editarherramientas', [
	'as' => 'editarherramientas',
	'uses' => 'PuestoT\PuestoTController@editarherramientas',
]);

Route::get('/editpeso2/{id}', [
	'as' => 'editpeso2',
	'uses' => 'PuestoT\PuestoTController@editpeso2',
]);

Route::post('/editarpeso2', [
	'as' => 'editarpeso2',
	'uses' => 'PuestoT\PuestoTController@editarpeso2',
]);

Route::get('/editfunciones/{id}', [
	'as' => 'editfunciones',
	'uses' => 'PuestoT\PuestoTController@editfunciones',
]);

Route::post('/editarfunciones', [
	'as' => 'editarfunciones',
	'uses' => 'PuestoT\PuestoTController@editarfunciones',
]);

Route::get('/editnofunciones/{id}', [
	'as' => 'editnofunciones',
	'uses' => 'PuestoT\PuestoTController@editnofunciones',
]);

Route::post('/editarnofunciones', [
	'as' => 'editarnofunciones',
	'uses' => 'PuestoT\PuestoTController@editarnofunciones',
]);

Route::post('/updateRecomendaciones/', [
	'as' => 'updateRecomendaciones',
	'uses' => 'PuestoT\PuestoTController@updateRecomendaciones',
]);

Route::get('/historialLaboralAPT/{id_empresa}/{id_user}/{id_cons}', [
	'as' => 'historialLaboralAPT',
	'uses' => 'PuestoT\PuestoTController@historialLaboralAPT',
]);




///// fin rutas puesto de trabajo/////////////////////
///// rutas puesto de trabajo administrativo/////////////////////
Route::get('/reportePuesto/{id_empresa}/{id_cons}', [
	'as' => 'reportePuesto',
	'uses' => 'PuestoT\PuestoTController@reportePuesto',
]);

Route::get('/reportePuestoOP/{id_empresa}/{id_cons}', [
	'as' => 'reportePuestoOP',
	'uses' => 'PuestoT\PuestoTController@reportePuestoOP',
]);
Route::get('/EsperePDF', [
	'as' => 'EsperePDF',
	'uses' => 'PuestoT\PuestoTController@EsperePDF',
]);
Route::get('/ListHistoricoAd/{id_empresa}/{id_user}', [
	'as' => 'ListHistoricoAd',
	'uses' => 'PuestoTAd\PuestoTAdController@ListHistorico',
]);
///// fin rutas puesto de trabajo administrativo/////////////////////


//Rutas Samuel


Route::get('/listUsuario', [
	'as' => 'listUsuario',
	'uses' => 'RegistroSamuel\SamuelController@index',
]);
//rutas para listar formulario created
Route::get('/newSamuel', [
	'as' => 'newSamuel',
	'uses' => 'RegistroSamuel\SamuelController@create',
]);
//envia variables al controller para registrar en BD.
Route::post('/store', [
	'as' => 'store',
	'uses' => 'RegistroSamuel\SamuelController@store',
]);


//rutas para listar formularios edit
Route::get('/editUser/{id}', [
	'as' => 'editUser',
	'uses' => 'RegistroSamuel\SamuelController@edit',
]);

//envia variables al controller para editar en BD.
Route::put('/update/{id}', [
	'as' => 'update',
	'uses' => 'RegistroSamuel\SamuelController@update',
]);

Route::get('/delete/{id}', [
	'as' => 'delete',
	'uses' => 'RegistroSamuel\SamuelController@delete',
]);

Route::get('/viewPdf/{id}', [
	'as' => 'viewPdf',
	'uses' => 'RegistroSamuel\SamuelController@viewPdf',

]);

Route::get('/viewPdfgeneral/', [
	'as' => 'viewPdfgeneral',
	'uses' => 'RegistroSamuel\SamuelController@viewPdfgeneral',

]);

Route::get('/modalfechanac/{id}', [
	'as' => 'modalfechanac',
	'uses' => 'RegistroSamuel\SamuelController@modalfechanac',

]);


//vista de error


//vista de error


//Reportes de incidencias, actos y condiciones inseguras

Route::get('/listIncidentes/{id}', [
	'as' => 'listIncidentes',
	'uses' => 'Formularios\reportesIncidentes\reportesIncidentesController@indexreportesIncidentes',
]);

Route::get('/newIncidentes/{id}', [
	'as' => 'newIncidentes',
	'uses' => 'Formularios\reportesIncidentes\reportesIncidentesController@createreportesIncidentes',
]);

Route::post('/storereportesIncidentes', [
	'as' => 'storereportesIncidentes',
	'uses' => 'Formularios\reportesIncidentes\reportesIncidentesController@storereportesIncidentes',
]);
Route::post('/UpdateEvidencias/{id}', [
	'as' => 'UpdateEvidencias',
	'uses' => 'Formularios\reportesIncidentes\reportesIncidentesController@UpdateEvidencias',
]);

//rutas para listar formularios edit
Route::get('/editIncidentes/{id}', [
	'as' => 'editIncidentes',
	'uses' => 'Formularios\reportesIncidentes\reportesIncidentesController@editreportesIncidentes',
]);

Route::post('/modalenviarcorreos', [
	'as' => 'modalenviarcorreos',
	'uses' => 'Formularios\reportesIncidentes\reportesIncidentesController@modalenviarcorreos',
]);

Route::get('/anioSeleccion/{anio}/{id}/{id_reporte}', [
	'as' => 'anioSeleccion',
	'uses' => 'Formularios\reportesIncidentes\reportesIncidentesController@anioSeleccion'
]);

Route::get('/reporteIncidentesExcel/', [
	'as' => 'reporteIncidentesExcel',
	'uses' => 'Formularios\reportesIncidentes\reportesIncidentesController@reporteIncidentesExcel'
]);

Route::get('/reportePdf/{id}', [
	'as' => 'reportePdf',
	'uses' => 'Formularios\reportesIncidentes\reportesIncidentesController@reportePdf',

]);

Route::get('/verEmailInc/{id}', [
	'as' => 'verEmailInc',
	'uses' => 'Formularios\reportesIncidentes\reportesIncidentesController@verEmailInc'
]);

//fin ruta SAMUEL

//********************************EXPORTACIÓN E IMPORTACION DE  USUARIOS***********************************//
Route::post('/riseUsers', [
	'as' => 'riseUsers',
	'uses' => 'Authentication\RegistrationController@riseUsers'
]);

Route::post('/importUsers', [
	'as' => 'importUsers',
	'uses' => 'Excel\ExcelController@importUsers'
]);

Route::get('/usersExport/{company_id}', [
	'as' => 'usersExport',
	'uses' => 'Excel\ExcelController@usersExport'
]);
Route::get('/exportUsers/{id_users}', [
	'as' => 'exportUsers',
	'uses' => 'Excel\ExcelController@exportUsers'
]);

Route::get('/usersExportEdutech/{company_id}', [
	'as' => 'usersExportEdutech',
	'uses' => 'Excel\ExcelController@exportUserEdutech'
]);
//******************************************FIN EXPORTACIÓN E IMPORTACION DE  USUARIOS**************************//

//*************Encuestas********************//

Route::resource('encuesta', 'Encuestas\EncuestasController');

Route::get('/encuesta', [
	'as' => 'listEncuesta',
	'uses' => 'Encuestas\EncuestasController@index'
]);

Route::get('/showEncuesta/{id}', [
	'as' => 'showEncuesta',
	'uses' => 'Encuestas\EncuestasController@showEncuesta'
]);

Route::get('/showReultado/{id}', [
	'as' => 'showReultado',
	'uses' => 'Encuestas\EncuestasController@showReultado'
]);

Route::get('/newEncuesta', [
	'as' => 'newEncuesta',
	'uses' => 'Encuestas\EncuestasController@create'
]);

Route::post('/NewEncuesta', [
	'as' => 'NewEncuesta',
	'uses' => 'Encuestas\EncuestasController@store'
]);

Route::post('/NewEncuestaF2', [
	'as' => 'NewEncuestaF2',
	'uses' => 'Encuestas\EncuestasController@storeF2'
]);

Route::post('/NewEncuestaF3', [
	'as' => 'NewEncuestaF3',
	'uses' => 'Encuestas\EncuestasController@storeF3'
]);

Route::post('/respuesta', [
	'as' => 'respuesta',
	'uses' => 'Encuestas\EncuestasController@storeRespuesta'
]);

//******************************************FIN Encuestas**************************//

//*************Psicosocial********************//

Route::get('/psicosocial/dashboardPsico', function () {
	return view('/psicosocial/dashboardPsico');
});

Route::get('/psicosocial/dashboardPsicoResult', function () {
	return view('/psicosocial/dashboardPsicoResult');
});

Route::get('/verListadoPsicoWeb', function () {
	return view('/psicosocial/verListadoPsicoWeb');
});

Route::get('/dashboardCapacitacionesPsico', function () {
	return view('/psicosocial/dashboardCapacitacionesPsico');
});


Route::get('/usersCapacitadosPsico/{id_company}', [
	'as' => 'usersCapacitadosPsico',
	'uses' => 'Psicosocial\PsicoController@usersCapacitadosPsico',
]);


Route::resource('psicosocial', 'Psicosocial\PsicoController');

Route::get('/index1', [
	'as' => 'index1',
	'uses' => 'Psicosocial\PsicoController@index1'
]);

Route::get('/indexResultCia', [
	'as' => 'indexResultCia',
	'uses' => 'Psicosocial\PsicoController@indexResultCia'
]);

Route::get('/indexResultPublic', [
	'as' => 'indexResultPublic',
	'uses' => 'Psicosocial\PsicoController@indexResultPublic'
]);

Route::post('/storeF2', [
	'as' => 'storeF2',
	'uses' => 'Psicosocial\PsicoController@storeF2'
]);

Route::post('/storeF3', [
	'as' => 'storeF3',
	'uses' => 'Psicosocial\PsicoController@storeF3'
]);

Route::post('/response', [
	'as' => 'response',
	'uses' => 'Psicosocial\PsicoController@storeResponse'
]);

Route::post('/storeGroup', [
	'as' => 'storeGroup',
	'uses' => 'Psicosocial\PsicoController@storeGroup'
]);


Route::get('/showEvaluacion/{id}', [
	'as' => 'showEvaluacion',
	'uses' => 'Psicosocial\PsicoController@showEvaluacion'
]);

Route::get('/showResult/{id}/{id2}', [
	'as' => 'showResult',
	'uses' => 'Psicosocial\PsicoController@showResult'
]);

Route::get('/showResultCia/{id}/{id_cia}', [
	'as' => 'showResultCia',
	'uses' => 'Psicosocial\PsicoController@showResultCia'
]);

Route::get('/showResultWeb/{id}', [
	'as' => 'showResultWeb',
	'uses' => 'Psicosocial\PsicoController@showResultWeb'
]);

Route::get('/showResultUser/{id}/{id_cia}', [
	'as' => 'showResultUser',
	'uses' => 'Psicosocial\PsicoController@showResultUser'
]);

Route::get('showResultAll', [
	'as' => 'showResultAll',
	'uses' => 'Psicosocial\PsicoController@showResultAll'
]);

Route::get('/psicoFormUser/{id}', [
	'as' => 'psicoFormUser',
	'uses' => 'Psicosocial\PsicoController@psicoFormUser'
]);

Route::post('/showPsicoFormUser', [
	'as' => 'showPsicoFormUser',
	'uses' => 'Psicosocial\PsicoController@showPsicoFormUser'
]);


Route::get('/psicoPerfil/{id}', [
	'as' => 'psicoPerfil',
	'uses' => 'Psicosocial\PsicoController@showPerfil'
]);

Route::post('/createPerfil', [
	'as' => 'createPerfil',
	'uses' => 'Psicosocial\PsicoController@createPerfil'
]);

Route::get('showInforme/{id}/{id_tipo}', [
	'as' => 'showInforme',
	'uses' => 'Psicosocial\PsicoController@showInforme'
]);

Route::get('showResultAllUser/{id_encuesta}/{tipo}', [
	'as' => 'showResultAllUser',
	'uses' => 'Psicosocial\PsicoController@showResultAllUser'

]);

//reporte de investigación//

Route::get('/verListadoPsico', [
	'as' => 'verListadoPsico',
	'uses' => 'Psicosocial\ReportPsicoPdfController@verListadoPsico',
]);


Route::get('/saludExcel', [
	'as' => 'saludExcel',
	'uses' => 'Excel\ExcelController@saludExcel',
]);

Route::get('/publicoExcel', [
	'as' => 'publicoExcel',
	'uses' => 'Excel\ExcelController@publicoExcel',
]);

Route::get('/climaExcel', [
	'as' => 'climaExcel',
	'uses' => 'Excel\ExcelController@climaExcel',
]);

Route::get('/crearListadoPsico', [
	'as' => 'crearListadoPsico',
	'uses' => 'Psicosocial\ReportPsicoPdfController@crearListadoPsico',
]);

Route::get('/editListadoPsico/{id}/', [
	'as' => 'editListadoPsico',
	'uses' => 'Psicosocial\ReportPsicoPdfController@editListadoPsico',
]);

Route::get('/editTablasPsico/{id}/{id_tipo}', [
	'as' => 'editTablasPsico',
	'uses' => 'Psicosocial\ReportPsicoPdfController@editTablasPsico',
]);

Route::post('/updateTablasPsico/{id}', [
	'as' => 'updateTablasPsico',
	'uses' => 'Psicosocial\ReportPsicoPdfController@updateTablasPsico',
]);

Route::get('/verPsico/{id}', [
	'as' => 'verPsico',
	'uses' => 'Psicosocial\ReportPsicoPdfController@verPsico',
]);

Route::post('/enviarCorreoPsico/{id}', [
	'as' => 'enviarCorreoPsico',
	'uses' => 'Psicosocial\ReportPsicoPdfController@enviarCorreoPsico',
]);

Route::get('/verCorreoPsico/{id}/{id_Psico}', [
	'as' => 'verCorreoPsico',
	'uses' => 'Psicosocial\ReportPsicoPdfController@verCorreoPsico',
]);

Route::post('/enviarCorreosPsicosocial', [
	'as' => 'enviarCorreosPsicosocial',
	'uses' => 'Psicosocial\ReportPsicoPdfController@enviarCorreosPsicosocial',
]);

Route::post('/verConstanciaPsico', [
	'as' => 'verConstanciaPsico',
	'uses' => 'Psicosocial\ReportPsicoPdfController@verConstanciaPsico',
]);

Route::post('/verConstanciaPsicoPdf', [
	'as' => 'verConstanciaPsicoPdf',
	'uses' => 'Psicosocial\ReportPsicoPdfController@verConstanciaPsicoPdf',
]);

Route::resource('constanciaPsico', 'Psicosocial\ReportPsicoPdfController');


// ********Escala de valores********//

Route::get('createValor/{ordenValor}', [
	'as' => 'createValor',
	'uses' => 'Psicosocial\PsicoValorController@createValor'
]);

Route::get('destroy/{id}', [
	'as' => 'destroy',
	'uses' => 'Psicosocial\PsicoValorController@destroy'
]);

Route::get('showJson/{ordenValor}', [
	'as' => 'showJson',
	'uses' => 'Psicosocial\PsicoValorController@showJson'
]);

Route::get('/capacitacionSvePsico', [
	'as' => 'capacitacionSvePsico',
	'uses' => 'Psicosocial\PsicoController@capacitacionSvePsico',
]);

Route::get('/searchMunicipio/{id_region}', [
	'as' => 'searchMunicipio',
	'uses' => 'Psicosocial\PsicoController@searchMunicipio'
]);

Route::resource('psicoValor', 'Psicosocial\PsicoValorController');


// ********Graficas********//

Route::get('/graficasPsico', [
	'as' => 'graficasPsico',
	'uses' => 'Psicosocial\graficasPsicoController@graficasPsico'
]);

Route::get('/graficasPsicoLaboral', [
	'as' => 'graficasPsicoLaboral',
	'uses' => 'Psicosocial\graficasPsicoController@graficasPsicoLaboral'
]);

Route::get('/graficasPsicoAdmin', [
	'as' => 'graficasPsicoAdmin',
	'uses' => 'Psicosocial\graficasPsicoController@graficasPsicoAdmin'
]);

Route::get('/graficasPsicoAdminList/{id}', [
	'as' => 'graficasPsicoAdminList',
	'uses' => 'Psicosocial\graficasPsicoController@graficasPsicoAdminList'
]);

Route::get('/graficasPsicoLabAdminList/{id}', [
	'as' => 'graficasPsicoLabAdminList',
	'uses' => 'Psicosocial\graficasPsicoController@graficasPsicoLabAdminList'
]);

Route::get('/graficasPsicoLaboralAdmin', [
	'as' => 'graficasPsicoLaboralAdmin',
	'uses' => 'Psicosocial\graficasPsicoController@graficasPsicoLaboralAdmin'
]);

Route::get('/graficaspsicoTOT/{id}/{id_company}', [
	'as' => 'graficaspsicoTOT',
	'uses' => 'Psicosocial\graficasPsicoController@graficaspsicoTOT'
]);

Route::get('/graficaspsicoTOTonload/{id}/{id_company}', [
	'as' => 'graficaspsicoTOTonload',
	'uses' => 'Psicosocial\graficasPsicoController@graficaspsicoTOTonload'
]);

Route::get('/graficaspsicoTOTlaboralinves/{id}/{id_company}', [
	'as' => 'graficaspsicoTOTlaboralinves',
	'uses' => 'Psicosocial\graficasPsicoController@graficaspsicoTOTlaboralinves'
]);

Route::get('/graficaspsicoTOTlaboralNoinves/{id}/{id_company}', [
	'as' => 'graficaspsicoTOTlaboralNoinves',
	'uses' => 'Psicosocial\graficasPsicoController@graficaspsicoTOTlaboralNoinves'
]);

Route::get('/graficaspsicoTOTlaboral/{id}/{id_company}', [
	'as' => 'graficaspsicoTOTlaboral',
	'uses' => 'Psicosocial\graficasPsicoController@graficaspsicoTOTlaboral'
]);

Route::get('/graficaspsicoTOTonloadlaboral/{id}/{id_company}', [
	'as' => 'graficaspsicoTOTonloadlaboral',
	'uses' => 'Psicosocial\graficasPsicoController@graficaspsicoTOTonloadlaboral'
]);

Route::get('/graficasinves', [
	'as' => 'graficasinves',
	'uses' => 'Psicosocial\graficasPsicoController@graficasinves'
]);

//*************FIN Psicosocial***************//

//*************Inicio de creación de PDF automático***************//

Route::resource('PDFCreate', 'PDFCreate\PdfController');

Route::get('showPdf/{id_evaluacion}/{tipoRiesgo}', [
	'as' => 'showPdf',
	'uses' => 'PDFCreate\PdfController@showPdf'
]);

Route::post('/newPdfF2', [
	'as' => 'newPdfF2',
	'uses' => 'PDFCreate\PdfController@storeF2'
]);

Route::post('/newPdfF3', [
	'as' => 'newPdfF3',
	'uses' => 'PDFCreate\PdfController@storeF3'
]);

Route::post('updateBody', [
	'as' => 'updateBody',
	'uses' => 'PDFCreate\PdfController@updateBody'
]);

Route::get('showInformeCia/{id}/{id_tipo}', [
	'as' => 'showInformeCia',
	'uses' => 'PDFCreate\PdfController@showInformeCia'
]);

Route::get('showInformeComercial/{id}/{id_tipo}', [
	'as' => 'showInformeComercial',
	'uses' => 'PDFCreate\PdfController@showInformeComercial'
]);



//*************Fin de creación de PDF automático***************//

//************Buscador de usuarios****************//


Route::post('buscarUser', [
	'as' => 'buscarUser',
	'uses' => 'Users\UsersController@buscar',
]);


Route::get('buscador', [
	'as' => 'buscador',
	'uses' => 'Users\UsersController@index3',
]);
//***********fin buscador usurios***************//


//////////***********API BUK*********************///
Route::get('/bukCia', function () {
	return view('//buk/bukCia');
});

Route::get('/bukUser/{id_company}', [
	'as' => 'bukUser',
	'uses' => 'Api\ApiController@listUserApiBuk'
]);

Route::get('editUserApi/{dni}', [
	'as' => 'editUserApi',
	'uses' => 'Api\ApiController@editUserApi'
]);

Route::get('createUserApi/{data}/{id_company}',[
	'as'=>'editUserApi',
	'uses'=>'Api\ApiController@createUserApi'
]);



Route::get('apiUserBuk/{id_company}', [
	'as' => 'apiUserBuk',
	'uses' => 'Api\ApiController@apiUserBuk'
]);

Route::get('pageUsersBuk/{id_company}', [
	'as' => 'pageUsersBuk',
	'uses' => 'Api\ApiController@pageUsersBuk'
]);

Route::get('/apiNewUserBuk/{id_company}', [
	'as' => 'apiNewUserBuk',
	'uses' => 'Api\ApiController@apiNewUserBuk'
]);

Route::get('apiCiaBuk', [
	'as' => 'apiCiaBuk',
	'uses' => 'Api\ApiController@apiCiaBuk'
]);

//****************fin API Buk**********************//

//********API y carpetas NewStetic****************//
Route::resource('newStetic', 'Clientes\NewStetic\NewSteticController');

Route::post('upDocNewStetic', [
	'as' => 'upDocNewStetic',
	'uses' => 'Clientes\NewStetic\NewSteticController@importDoc'
]);

//****************fin API y carpetas NewStetic**********************//
//****************Calculadora**********************//

Route::get('/calculadora', function () {
	return view('/calculadora/calculadora');
});

Route::get('/calculadoraSistegra', function () {
	return view('/calculadora/calculadoraSistegra');
});

Route::get('/calculadoraSavanna', function () {
	return view('/calculadora/calculadoraSavanna');
});

Route::get('calculadoraPDF/{id}', [
	'as' => 'calculadoraPDF',
	'uses' => 'Calculadora\CalculadoraController@calculadoraPDF'
]);

Route::get('reportCia', [
	'as' => 'reportCia',
	'uses' => 'Excel\ExcelController@empresaSavanna'
]);


Route::resource('calculadora', 'Calculadora\CalculadoraController');



//****************fin Calculadora**********************//

//****************Inicio de generador de Evaluaciones**********************//

Route::get('/dashboardGenerador', function () {
	return view('/GeneradorEvaluaciones/dashboardGenerador');
});
Route::get('/dashboardSveOsteo', function () {
	return view('/GeneradorEvaluaciones/dashboardSveOsteo');
});

Route::resource('generador', 'Generador\GeneradorController');


//****************Monitoreo SVE osteomuscular**********************//

Route::get('formUserDate/{id}', function ($id) {
	return view('SVE/Monitoreo/formUserDate', compact('id'));
});
Route::get('SVE/dashboardMonitoreo', function () {
	return view('SVE/dashboardMonitoreo');
});
Route::get('NewConstancia', [
	'as' => 'NewConstancia',
	'uses' => 'SVE\ReportePdf\ReportePdfController@index'
]);
Route::get('createNewConstancia', [
	'as' => 'createNewConstancia',
	'uses' => 'SVE\ReportePdf\ReportePdfController@createNewConstancia'
]);
Route::get('editNewConstancia/{id}', [
	'as' => 'editNewConstancia',
	'uses' => 'SVE\ReportePdf\ReportePdfController@editNewConstancia'
]);
Route::post('reporteOsteomuscular', [
	'as' => 'reporteOsteomuscular',
	'uses' => 'SVE\ReportePdf\ReportePdfController@reporteOsteomuscular'
]);
Route::post('updateNewConstancia/{id}', [
	'as' => 'updateNewConstancia',
	'uses' => 'SVE\ReportePdf\ReportePdfController@updateNewConstancia'
]);
Route::get('PdfNewConstancia/{id}', [
	'as' => 'PdfNewConstancia',
	'uses' => 'SVE\ReportePdf\ReportePdfController@PdfNewConstancia'
]);
Route::post('PdfReporteOsteomuscular', [
	'as' => 'PdfReporteOsteomuscular',
	'uses' => 'SVE\ReportePdf\ReportePdfController@PdfReporteOsteomuscular'
]);
Route::get('PdfOsteomuscular', [
	'as' => 'PdfOsteomuscular',
	'uses' => 'SVE\ReportePdf\ReportePdfController@PdfOsteomuscular'
]);
// Route::post('/enviarcorreosBitacora', [ 
//     'as'=>'enviarcorreosBitacora',
//     'uses'=>'SVE\ReportePdf\ReportePdfController@enviarcorreosBitacora',
// ]);
Route::get('/verEmailMonitoreo/{id}/{tipo}', [
	'as' => 'verEmailMonitoreo',
	'uses' => 'SVE\ReportePdf\ReportePdfController@verEmailMonitoreo'
]);
Route::get('listShowResultUser/{id}', [
	'as' => 'listShowResultUser',
	'uses' => 'SVE\Monitoreo\MonitoreoController@showResultUser'
]);

Route::get('usersCapacitadosOsteo/{id_company}', [
	'as' => 'usersCapacitadosOsteo',
	'uses' => 'SVE\ControlController@usersCapacitadosOsteo',
]);


Route::resource('Constancia', 'SVE\ReportePdf\ReportePdfController');

Route::resource('monitoreo', 'SVE\Monitoreo\MonitoreoController');

//*******inicio de carga fisica

Route::resource('/cargaFisicaEvaluacion','SVE\CargaFisica\Evaluaciones\SveEvaluacionController');

Route::get('/dashboardSveCf', [
	'as' => 'dashboardSveCf',
	'uses' => 'SVE\CargaFisica\Test\CargaFaptController@index',
]);

Route::get('/dashboardAPT', [
	'as' => 'dashboardAPT',
	'uses' => 'SVE\CargaFisica\Test\CargaFaptController@indexAPT',
]);

Route::get('/dashboardCargaFisica', [
	'as' => 'dashboardCargaFisica',
	'uses' => 'SVE\CargaFisica\Test\CargaFaptController@indexCF',
]);

Route::get('/indexFormUser /{id_tipo}',[
	'as'=> 'indexFormUser',
	'uses'=>'SVE\CargaFisica\Test\CargaFaptController@indexFormUser',

]);

Route::get('showcf_question/{id_evaluacion}', [
	'as' => 'showcf_question',
	'uses' => 'SVE\CargaFisica\Evaluaciones\SveEvaluacionController@showcf_question',
]);

Route::get('showCFCampos/{id_evaluacion}', [
	'as' => 'showCFCampos',
	'uses' => 'SVE\CargaFisica\Evaluaciones\SveEvaluacionController@showCFCampos',
]);

Route::get('editcampo/{id_campo}', [
	'as' => 'editcampo',
	'uses' => 'SVE\CargaFisica\Evaluaciones\SveEvaluacionController@editcampo',
]);

Route::post('storeCFQuestion', [
	'as' => 'storeCFQuestion',
	'uses' => 'SVE\CargaFisica\Evaluaciones\SveEvaluacionController@storeCFQuestion',
]);

Route::post('updateCFQuestion/{id_campo}', [
	'as' => 'updateCFQuestion',
	'uses' => 'SVE\CargaFisica\Evaluaciones\SveEvaluacionController@updateCFQuestion',
]);

Route::post('createCFQuestion', [
	'as' => 'createCFQuestion',
	'uses' => 'SVE\CargaFisica\Evaluaciones\SveEvaluacionController@createCFQuestion',
]);

//Crear carga fisica

Route::get('parte1', function () {
	return view('SVE/CargaFisica/formulariosCFAPT/parte1');
});

Route::get('parte2', function () {
	return view('SVE/CargaFisica/formulariosCFAPT/parte2');
});

Route::get('parte3', function () {
	return view('SVE/CargaFisica/formulariosCFAPT/parte3');
});

Route::get('parte4', function () {
	return view('SVE/CargaFisica/formulariosCFAPT/parte4');
});

Route::get('parte5', function () {
	return view('SVE/CargaFisica/formulariosCFAPT/parte5');
});

//Editar carga fisica

Route::get('EditParte1', function () {
	return view('SVE/CargaFisica/formulariosVerCFAPT/parte1');
});

Route::get('EditParte2', function () {
	return view('SVE/CargaFisica/formulariosVerCFAPT/parte2');
});

Route::get('EditParte3', function () {
	return view('SVE/CargaFisica/formulariosVerCFAPT/parte3');
});

Route::get('EditParte4', function () {
	return view('SVE/CargaFisica/formulariosVerCFAPT/parte4');
});

Route::get('EditParte5', function () {
	return view('SVE/CargaFisica/formulariosVerCFAPT/parte5');
});

//valoracion 

Route::get('valoracion', function () {
	return view('SVE/CargaFisica/formulariosCFAPT/valoracion');
});


//pdf carga fisica

Route::get('PDFParte1', function () {
	return view('SVE/CargaFisica/formulariosPdfCFAPT/parte1');
});

Route::get('PDFParte2', function () {
	return view('SVE/CargaFisica/formulariosPdfCFAPT/parte2');
});

Route::get('PDFParte3', function () {
	return view('SVE/CargaFisica/formulariosPdfCFAPT/parte3');
});

Route::get('PDFParte4', function () {
	return view('SVE/CargaFisica/formulariosPdfCFAPT/parte4');
});

Route::get('PDFParte5', function () {
	return view('SVE/CargaFisica/formulariosPdfCFAPT/parte5');
});



//****************fin Monitoreo SVE osteomuscular**********************//

// ruta para la vista total usuarios****************//

Route::get(
	'/totalUsuarios',
	//aqui va '/totalUsuarios/id_company'
	[
		'as' => 'totalUsuarios',
		'uses' => 'Company\CompanyController@totalUsuarios',

	]
);

Route::get('/ActivosInactivos/{id}', [
	'as' => 'ActivosInactivos',
	'uses' => 'Company\CompanyController@ActivosInactivos'
]);

Route::get('/indicaEmpleados/{id}', [
	'as' => 'indicaEmpleados',
	'uses' => 'Company\CompanyController@indicaEmpleados'
]);

Route::get(
	'/contratistaAnclaEm/{id_company}',
	[
		'as' => 'contratistaAnclaEm',
		'uses' => 'Company\CompanyController@contratistaAnclaEm',

	]
);

//************* fin  ruta para la vista total usuarios ***//
//****************Cambio Climático**********************//


Route::get('cambioClimatico', function () {
	return view('CambioClimatico/dashboardClimatico');
});

Route::get('/climaPerfil/{id}', [
	'as' => 'climaPerfil',
	'uses' => 'CambioClimatico\ClimaticoController@showPerfil'
]);

Route::get('climaResultUser/{id}', [
	'as' => 'climaResultUser',
	'uses' => 'CambioClimatico\ClimaticoController@showResultUser'

]);


Route::resource('climatico', 'CambioClimatico\ClimaticoController');

//****************fin  Cambio Climático**********************
//**************** Savanna **************************

Route::get('/loginSavanna', function () {
	return view('/Savanna/loginSavanna');
});

Route::post('savannaLogin', [
	'as' => 'savannaLogin',
	'uses' => 'Authentication\LoginController@savannaLogin'
]);

Route::get('/indexResultSavanna/{tape}', [
	'as' => 'indexResultSavanna',
	'uses' => 'CompanySavanna\SavannaController@indexResultSavanna'
]);

Route::get('/indexResultClimaSavanna', [
	'as' => 'indexResultClimaSavanna',
	'uses' => 'CompanySavanna\SavannaController@indexResultClimaSavanna'
]);

Route::get('/indexResultSaludSavanna', [
	'as' => 'indexResultSaludSavanna',
	'uses' => 'CompanySavanna\SavannaController@indexResultSaludSavanna'
]);

Route::get('resultPsicoSavanna/{id}/{id_cia}/{tape}', [
	'as' => 'resultPsicoSavanna',
	'uses' => 'CompanySavanna\SavannaController@showResulPsicoSavanna'
]);

Route::get('resultClimaSavanna/{id}/{id_cia}', [
	'as' => 'resultClimaSavanna',
	'uses' => 'CompanySavanna\SavannaController@showResulClimaSavanna'
]);

Route::get('climaResultUserSavanna/{id}', [
	'as' => 'climaResultUserSavanna',
	'uses' => 'CompanySavanna\SavannaController@showResultUserClimaSavanna'
]);

Route::get('showInformeSavanna/{id}', [
	'as' => 'showInformeSavanna',
	'uses' => 'CompanySavanna\SavannaController@showInformeSavanna'
]);


Route::post('updateUserSavanna/{id}', [
	'as' => 'updateUserSavanna',
	'uses' => 'CompanySavanna\SavannaController@updateUserSavanna'
]);

Route::get('showResultWeb/{id}/{id_cia}', [
	'as' => 'showResultWeb',
	'uses' => 'CompanySavanna\SavannaController@showResultWeb'
]);

Route::get('userSavanna', [
	'as' => 'userSavanna',
	'uses' => 'CompanySavanna\SavannaController@indexUser'
]);

Route::post('verConstanciaPsicoSavanna', [
	'as' => 'verConstanciaPsicoSavanna',
	'uses' => 'CompanySavanna\SavannaController@verConstanciaPsicoSavanna',
]);

Route::post('/verConstanciaPsicoPdfSavanna', [
	'as' => 'verConstanciaPsicoPdfSavanna',
	'uses' => 'CompanySavanna\SavannaController@verConstanciaPsicoPdfSavanna',
]);

Route::post('verConstanciaPsicoSavanna2', [
	'as' => 'verConstanciaPsicoSavanna2',
	'uses' => 'CompanySavanna\SavannaController@verConstanciaPsicoSavanna2',
]);

Route::post('/verConstanciaPsicoPdfSavanna2', [
	'as' => 'verConstanciaPsicoPdfSavanna2',
	'uses' => 'CompanySavanna\SavannaController@verConstanciaPsicoPdfSavanna2',
]);

Route::post('verConstanciaPsicoSavanna3', [
	'as' => 'verConstanciaPsicoSavanna3',
	'uses' => 'CompanySavanna\SavannaController@verConstanciaPsicoSavanna3',
]);

Route::post('/verConstanciaPsicoPdfSavanna3', [
	'as' => 'verConstanciaPsicoPdfSavanna3',
	'uses' => 'CompanySavanna\SavannaController@verConstanciaPsicoPdfSavanna3',
]);

Route::get('dashboardSavanna', function () {
	return view('Savanna/dashboardSavanna');
});

Route::get('validationSavanna/{id}', [
	'as' => 'validationSavanna',
	'uses' => 'CompanySavanna\SavannaController@showValidation',
]);

Route::post('validationPerfil', [
	'as' => 'validationPerfil',
	'uses' => 'CompanySavanna\SavannaController@validationPerfil'
]);
Route::resource('savanna', 'CompanySavanna\SavannaController');

//buscador user Savanna

Route::post('seekerUser', [
	'as' => 'seekerUser',
	'uses' => 'CompanySavanna\SavannaController@seeker',
]);


Route::get('seeker', [
	'as' => 'seeker',
	'uses' => 'CompanySavanna\SavannaController@seekerIndex',
]);

Route::post('updateNitWeb', [
	'as' => 'updateNitWeb',
	'uses' => 'CompanySavanna\SavannaController@updateNitWeb',

]);

//****************fin  Savanna *****************************

//****************inicio  Cardiovascular *****************************

Route::get('/dashboardCardio', function () {
	return view('/Cardiovascular/dashboardCardio');
});

Route::get('/dashboardCapacitacionesCardio', function () {
	return view('/Cardiovascular/dashboardCapacitacionesCardio');
});

Route::get('dashboardCardioEvaluaciones', function () {
	return view('Cardiovascular/dashboardCardioEvaluaciones');
});

Route::get('dashboardCardioResult', function () {
	return view('Cardiovascular/dashboardCardioResult');
});

Route::get('resultCalculadora', [
	'as' => 'resultcalculadora',
	'uses' => 'CardioVascular\CardioController@indexCalcular'
]);

Route::get('indexResultCardio', [
	'as' => 'indexResultCardio',
	'uses' => 'CardioVascular\CardioController@indexResultCardio'
]);
Route::get('indexBrasilCardio', [
	'as' => 'indexBrasilCardio',
	'uses' => 'CardioVascular\CardioController@indexBrasilCardio'
]);

Route::get('cardioPerfil/{id}', [
	'as' => 'cardioPerfil',
	'uses' => 'CardioVascular\CardioController@showCardioPerfil'
]);

Route::get('showResultCardio/{id}/{id_cia}', [
	'as' => 'showResultCardio',
	'uses' => 'CardioVascular\CardioController@showResultCardio'
]);

Route::get('/showResultUserCardio/{id_user}/{id_cia}', [
	'as' => 'showResultUserCardio',
	'uses' => 'CardioVascular\CardioController@showResultUserCardio'
]);

Route::post('storeCardioFormUser', [
	'as' => 'storeCardioFormUser',
	'uses' => 'CardioVascular\CardioController@storeCardio'
]);

Route::get('/capacitacionSveCardio', [
	'as'   => 'capacitacionSveCardio',
	'uses' => 'CardioVascular\CardioController@capacitacionSveCardio',
]);

Route::get('/usersCapacitadosCardio/{id_company}', [
	'as' => 'usersCapacitadosCardio',
	'uses' => 'CardioVascular\CardioController@usersCapacitadosCardio',
]);

Route::resource('cardioVascular', 'CardioVascular\CardioController');

Route::post('/exportCalcuCardio', [
	'as' => 'exportCalcuCardio',
	'uses' => 'Excel\ExcelController@exportCalcuCardio'

]);

Route::get('/dashboardResultInvestigacion', function () {
	return view('/Cardiovascular/dashboardCardioResultMonitoreo');
});

Route::get('/listResultCalcuInv', [
	'as' => 'listResultCalcuInv',
	'uses' => 'CardioVascular\CardioController@IndexResultCalcuInv'
]);

Route::post('/exportCardioEva', [
	'as' => 'exportCardioEva',
	'uses' => 'Excel\ExcelController@exportCardioEva'

]);

Route::get('/listResultEvaInv', [
	'as' => 'listResultEvaInv',
	'uses' => 'CardioVascular\CardioController@IndexResultEvaInv'
]);

Route::post('/exportCardioConstancia', [
	'as' => 'exportCardioConstancia',
	'uses' => 'CardioVascular\ConstanciaCardioController@exportCardioConstancia'

]);

Route::post('/verPDFCardiovascular', [
	'as' => 'verPDFCardiovascular',
	'uses' => 'CardioVascular\ConstanciaCardioController@verPDFCardiovascular',
]);


//****************fin  Cardiovascular *****************************
//****************Inicio  NewStetic *****************************

Route::resource('newStetic', 'NewStetic\NewSteticController');
//****************fin  NewStetic *****************************

Route::get('/RutaQuemada', function () {
	return view('SST/SGSST/hacer/formularios/311PerfilSociodemografico/311ConsentimientoInformadoParaelManejodeDatosPersonales');
});
Route::get('/RutaQuemada2', function () {
	return view('SST/SGSST/hacer/formularios/311PerfilSociodemografico/ProcedimientoPerfilSociodeografico');
});
Route::get('/RutaQuemada3', function () {
	return view('SST/SGSST/hacer/formularios/314ExamenesMedicosLaborales/ProcedimientoparaRealizaciondeExamenesMe');
});
Route::get('/RutaQuemada4', function () {
	return view('SST/SGSST/hacer/formularios/314ExamenesMedicosLaborales/RemisionparaExamenesMedicosOcupacionales');
});
Route::get('/RutaQuemada5', function () {
	return view('SST/SGSST/hacer/formularios/314ExamenesMedicosLaborales/SeguimientoExamenesMedicosLaborales');
});

//**************************** BrasilSVE ******************************//
Route::get('dashboardBrasilSVE', function () {
	return view('BrasilSVE/dashboardBrasilSVE');
});

Route::get('dashboardBrasilSVEPsicosocial', function () {
	return view('BrasilSVE/dashboardBrasilSVEPsicosocial');
});

Route::get('dashboardBrasilSVECardio', function () {
	return view('BrasilSVE/dashboardBrasilSVECardio');
});

Route::get('dashboardCardioBrasil', function () {
	return view('BrasilSVE/CardioBrasil/dashboardCardioBrasil');
});

Route::get('dashboardCardioBrasilResultados', function () {
	return view('BrasilSVE/CardioBrasil/dashboardCardioBrasilResultados');
});

Route::get('dashboardBrasilCapacitacionesCardio', function () {
	return view('BrasilSVE/CardioBrasil/dashboardBrasilCapacitacionesCardio');
});

Route::get('dashboardBrasilCapacitacionesPsico', function () {
	return view('BrasilSVE/PsicoBrasil/dashboardBrasilCapacitacionesPsico');
});


//capacitaciones
Route::get('capacitacionesBrasilCardio', [
	'as' => 'capacitacionesBrasilCardio',
	'uses' => 'CardioVascular\CardioController@capacitacionesBrasilCardio',
]);

Route::get('capacitacionesBrasilPsico', [
	'as' => 'capacitacionesBrasilPsico',
	'uses' => 'Psicosocial\PsicoController@capacitacionesBrasilPsico',
]);

//user Brasil
Route::get('/userBrasil/{company_id}', [
	'as'=>'userBrasil',
	'uses'=> 'Users\UsersController@indexUserBrasil'
]);

Route::get('/registerBrasil',[
	'as'=>'registerBrasil',
	'uses'=> 'Authentication\RegistrationController@registerBrasil'
]);

Route::get('editUserBrasil/{idUser}', [
	'as' => 'editUserBrasil',
	'uses' => 'Users\UsersController@editBrasil',
]);

//cardio	
Route::get('cardioPerfilBrasil/{id}', [
	'as' => 'cardioPerfilBrasil',
	'uses' => 'CardioVascular\CardioController@showCardioPerfilBrasil'
]);

Route::get('indexBrasil',[
	'as'=> 'indexBrasil',
	'uses'=>'Psicosocial\PsicoController@indexBrasil'
]);

Route::get('showResultCiaCardio/{id}/{id_tipo}', [
	'as' => 'showResultCiaCardio',
	'uses' => 'CardioVascular\CardioController@showResultCiaCardio'
]);

Route::get('resultBrasilCardio', [
	'as' => 'resultBrasilCardio',
	'uses' => 'CardioVascular\CardioController@resultBrasilCardio'
]);

Route::get('dashboardIndicadores', [
	'as' => 'dashboardIndicadores',
	'uses' => 'controller_indicadores@dashboardIndicadores'
]);
Route::get('indexMatriz/{id_company}/{id_tipoDoc}', [
	'as' => 'indexMatriz',
	'uses' => 'controller_indicadores@indexMatriz'
]);


Route::get('createDoc/{id_cia}/{id_tipoDoc}', [
	'as' => 'createDoc',
	'uses' => 'controller_indicadores@createDoc'
]);

Route::get('updateDoc/{id_cia}/{id_tipoDoc}/{id_registro}', [
	'as' => 'updateDoc',
	'uses' => 'controller_indicadores@updateDoc'
]);

// Caracterizacion de residuos
Route::post('/createOrEditCarctResiduos', [
	'as'=> 'createOrEditCarctResiduos',
	'uses' => 'controller_indicadores@createOrEditCarctResiduos'
]);
// Generacion de residuos
Route::post('/createOrEditGenResiduos', [
	'as'=> 'createOrEditGenResiduos',
	'uses' => 'controller_indicadores@createOrEditGenResiduos'
]);
// Consumo de energia
Route::post('/createOrEditConsumoEnergia', [
	'as'=> 'createOrEditConsumoEnergia',
	'uses' => 'controller_indicadores@createOrEditConsumoEnergia'
]);
// Plan de contingencia
Route::post('/createOrEditPlanContingencia', [
	'as'=> 'createOrEditPlanContingencia',
	'uses' => 'controller_indicadores@createOrEditPlanContingencia'
]);
// Impactos
Route::post('/createOrEditIndicaImpactos', [
	'as'=> 'createOrEditIndicaImpactos',
	'uses' => 'controller_indicadores@createOrEditIndicaImpactos'
]);
// Consumo de agua
Route::post('/createOrEditConsumoAgua', [
	'as'=> 'createOrEditConsumoAgua',
	'uses' => 'controller_indicadores@createOrEditConsumoAgua'
]);
// Seguimientos Proyectos
Route::post('/createOrEditProyectoLic', [
	'as'=> 'createOrEditProyectoLic',
	'uses' => 'controller_indicadores@createOrEditProyectoLic'
]);
// Control vehicular
Route::post('/createOrEditControlVehi', [
	'as'=> 'createOrEditControlVehi',
	'uses' => 'controller_indicadores@createOrEditControlVehi'
]);
// Cierre y abandono
Route::post('/createOrEditCierreAbanadono', [
	'as'=> 'createOrEditCierreAbanadono',
	'uses' => 'controller_indicadores@createOrEditCierreAbanadono'
]);
// Mantenimiento equipos
Route::post('/createOrEditManEquipos', [
	'as'=> 'createOrEditManEquipos',
	'uses' => 'controller_indicadores@createOrEditManEquipos'
]);
// Vertidos de agua
Route::post('/createOrEditVertimientos', [
	'as'=> 'createOrEditVertimientos',
	'uses' => 'controller_indicadores@createOrEditVertimientos'
]);
// Emisiones Atmosféricas
Route::post('/createOrEditEmisionesADM', [
	'as'=> 'createOrEditEmisionesADM',
	'uses' => 'controller_indicadores@createOrEditEmisionesADM'
]);


Route::get('/constanciaCardioVascular/{id_company}/{anio}', [
	'as' => 'constanciaCardioVascular',
	'uses' => 'CardioVascular\ConstanciaCardioController@verConstanciaCardio'
]);

Route::get('/viewDocsToFirm/{id_company}/{numDocument}', [
	'as' => 'viewDocsToFirm',
	'uses' => 'ControlFirmas\controller_controlFirmas@viewDocsToFirm'
]);

Route::post('/sendEmailToFirmUser', [
	'as'=> 'sendEmailToFirmUser',
	'uses' => 'SST\SGSST\planear\PlanearController@sendEmailToFirmUser'
]);

Route::post('/sendEmailToUsersFirms', [
	'as' => 'sendEmailToUsersFirms',
	'uses' => 'ControlFirmas\controller_controlFirmas@sendEmailToUsers'
]);

Route::get('/viewDocsToFirmAdm/{id_company}/{id_user}/{rol}', [
	'as' => 'viewDocsToFirmAdm',
	'uses' => 'ControlFirmas\controller_controlFirmas@viewDocsToFirmAdm'
]);

// 	CONTROL FIRMAS V2

Route::get('/viewControlFirmasUser/{id_company}/{numDocument}/{user_id}', [
	'as' => 'viewControlFirmasUser',
	'uses' => 'ControlFirmas\controller_controlFirmasV2@viewControlFirmas_user'
]);

// FIN CONTROL FIRMA V2

Route::get('contrato/AceptacionTerminosCondicionesServicios', function () {
	return view('contrato/AceptacionTerminosCondicionesServicios');
});

Route::get('/listVerificarCliente/{company_id}', 'SST\SGSST\verificar\VerificarController@indexVerificarCliente');

Route::get('/listDocVerificar/{dioagnostico_id}/{company_id}', 'SST\SGSST\verificar\VerificarController@listDocVerificar');

Route::get('/createRecordsDocsSGSSTV/{id_company}/{id_diagnostico}', [
	'as' => 'createRecordsDocsSGSSTV',
	'uses' => 'SST\SGSST\verificar\VerificarController@createRecordsDocsSGSSTV',
]);

Route::get('/editRecordsDocsSGSSTV/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'editRecordsDocsSGSSTV',
	'uses' => 'SST\SGSST\verificar\VerificarController@editRecordsDocsSGSSTV',
]);

Route::get('/pdfRecordsDocsSGSSTV/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'pdfRecordsDocsSGSSTV',
	'uses' => 'SST\SGSST\verificar\VerificarController@pdfRecordsDocsSGSSTV',
]);

Route::post('/createCalificacionVerificarSGSST', [
	'as'=> 'createCalificacionVerificarSGSST',
	'uses' => 'SST\SGSST\verificar\VerificarController@createCalificacionVerificarSGSST'
]);

Route::post('/createDocVerificarSGSST', [
	'as'=> 'createDocVerificarSGSST',
	'uses' => 'SST\SGSST\verificar\VerificarController@createDocVerificarSGSST'
]);

Route::get('/listCalificacionVerificarSGSST/{id_empresa}', 'SST\SGSST\verificar\VerificarController@listCalificacionVerificarSGSST');

Route::post('/actaRevisionAltaDireccion', [
	'as'=> 'actaRevisionAltaDireccion',
	'uses' => 'SST\SGSST\verificar\VerificarController@actaRevisionAltaDireccion'
]);

Route::post('/procedimientoRevision', [
	'as'=> 'procedimientoRevision',
	'uses' => 'SST\SGSST\verificar\VerificarController@procedimientoRevision'
]);

Route::post('/procedimientoAuditoria', [
	'as'=> 'procedimientoAuditoria',
	'uses' => 'SST\SGSST\verificar\VerificarController@procedimientoAuditoria'
]);

Route::post('/planificacionAuditoria', [
	'as'=> 'planificacionAuditoria',
	'uses' => 'SST\SGSST\verificar\VerificarController@planificacionAuditoria'
]);

Route::post('/formatoInformeAuditoria', [
	'as'=> 'formatoInformeAuditoria',
	'uses' => 'SST\SGSST\verificar\VerificarController@formatoInformeAuditoria'
]);

Route::post('/actaSocializacion', [
	'as'=> 'actaSocializacion',
	'uses' => 'SST\SGSST\verificar\VerificarController@actaSocializacion'
]);

Route::get('/itemsAuditoriaVerificar/{id_company}', [
	'as' => 'itemsAuditoriaVerificar',
	'uses' => 'SST\SGSST\verificar\VerificarController@itemsAuditoriaVerificar',
]);

Route::get('/evidenciasDocsVerificar/{id_itm}/{id_company}', [
	'as' => 'evidenciasDocsVerificar',
	'uses' => 'SST\SGSST\verificar\VerificarController@evidenciasDocsVerificar',
]);

Route::post('/auditarDocsVerificar', [
	'as' => 'auditarDocsVerificar',
	'uses' => 'SST\SGSST\verificar\VerificarController@auditarDocsVerificar',
]);

Route::get('/listActuarCliente/{company_id}', 'SST\SGSST\actuar\ActuarController@indexActuarCliente');

Route::get('/listDocActuar/{dioagnostico_id}/{company_id}', 'SST\SGSST\actuar\ActuarController@listDocActuar');

Route::get('/createRecordsDocsSGSSTA/{id_company}/{id_diagnostico}', [
	'as' => 'createRecordsDocsSGSSTA',
	'uses' => 'SST\SGSST\actuar\ActuarController@createRecordsDocsSGSSTA',
]);

Route::get('/editRecordsDocsSGSSTA/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'editRecordsDocsSGSSTA',
	'uses' => 'SST\SGSST\actuar\ActuarController@editRecordsDocsSGSSTA',
]);

Route::get('/pdfRecordsDocsSGSSTA/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'pdfRecordsDocsSGSSTA',
	'uses' => 'SST\SGSST\actuar\ActuarController@pdfRecordsDocsSGSSTA',
]);

Route::get('/excelRecordsDocsSGSSTA/{id_company}/{id_diagnostico}/{id_registro}', [
	'as' => 'excelRecordsDocsSGSSTA',
	'uses' => 'SST\SGSST\actuar\ActuarController@excelRecordsDocsSGSSTA',
]);

Route::post('/matrizACPM', [
	'as'=> 'matrizACPM',
	'uses' => 'SST\SGSST\actuar\ActuarController@matrizACPM'
]);

Route::post('/planMejoramiento', [
	'as'=> 'planMejoramiento',
	'uses' => 'SST\SGSST\actuar\ActuarController@planMejoramiento'
]);

Route::get('/ProRiegoViales/{id_empresa}/{id_diagnostico}/{id_para}', [
	'as' => 'ProRiegoViales',
	'uses' => 'PESV\PesvController@ProRiegoViales',
]);

Route::get('/verProRiegoViales/{id_registro}/{id_empresa}', [
	'as' => 'verProRiegoViales',
	'uses' => 'PESV\PesvController@verProRiegoViales',
]);

Route::get('/pdfProRiegoViales/{id_registro}/{id_empresa}', [
	'as' => 'pdfProRiegoViales',
	'uses' => 'PESV\PesvController@pdfProRiegoViales',
]);

Route::post('/createOrUpdateProRiegoViales', [
	'as' => 'createOrUpdateProRiegoViales',
	'uses' => 'PESV\PesvController@createOrUpdateProRiegoViales',
]);

Route::post('/createCalificacionActuarSGSST', [
	'as'=> 'createCalificacionActuarSGSST',
	'uses' => 'SST\SGSST\actuar\ActuarController@createCalificacionActuarSGSST'
]);

Route::post('/createDocActuarSGSST', [
	'as'=> 'createDocActuarSGSST',
	'uses' => 'SST\SGSST\actuar\ActuarController@createDocActuarSGSST'
]);

Route::get('/listCalificacionActuarSGSST/{id_empresa}', 'SST\SGSST\actuar\ActuarController@listCalificacionActuarSGSST');

Route::get('/itemsAuditoriaActuar/{id_company}', [
	'as' => 'itemsAuditoriaActuar',
	'uses' => 'SST\SGSST\actuar\ActuarController@itemsAuditoriaActuar',
]);

Route::get('/evidenciasDocsActuar/{id_itm}/{id_company}', [
	'as' => 'evidenciasDocsActuar',
	'uses' => 'SST\SGSST\actuar\ActuarController@evidenciasDocsActuar',
]);

Route::post('/auditarDocsActuar', [
	'as' => 'auditarDocsActuar',
	'uses' => 'SST\SGSST\actuar\ActuarController@auditarDocsActuar',
]);


Route::get('/pruebaTBL', [
	'as' => 'pruebaTBL',
	'uses' => 'SST\SGSST\hacer\HacerController@pruebaTBL',
]);


Route::get('/moduleGroup/{id_company}/{group}', [
	'as' => 'moduleGroup',
	'uses' => 'Dashboard\DashboardController@modulesGroup',
]);

//  Módulo Diagnóstico de madurez del SG-SST


Route::get('/indexMadurez', [
	'as' => 'indexMadurez',
	'uses' => 'MadurezSGSST\MadurezController@index',
]);

Route::get('/informacionMadurez', function () {
	return view('MadurezSGSST/madurezInfo/informacionMadurez');
});

Route::get('/dashboardAskNivel', function () {
	return view('MadurezSGSST/madurezNivel/dashboardAskNivel');
});

Route::get('/dashboardDiagnostico', function () {
	return view('MadurezSGSST/madurezNivel/dashboardDiagnostico');
});

Route::get('/dashboardAskSalud', function () {
	return view('MadurezSGSST/madurezNivel/dashboardAskSalud');
});

Route::get('/madurezCia', [
	'as' => 'madurezCia',
	'uses' => 'MadurezSGSST\MadurezController@madurezCia',
]);

Route::get('/indexItem', [
	'as' => 'indexItem',
	'uses' => 'MadurezSGSST\MadurezController@indexItem',
]);


Route ::get('nivelesCia/{id_company}',[
	'as'=>'nivelesCia',
	'uses'=>'MadurezSGSST\MadurezController@nivelesCia',
]);

Route::get('/ciaMadurez', function () {
	return view('MadurezSGSST/madurezInfo/ciaMadurez');
});

Route::get('/resultadoMadurezGraficas',[
'as'=>'resultadoMadurezGraficas',
'uses' => 'MadurezSGSST\MadurezController@resultadoMadurezGraficas',
]);

Route::get('/reporteMadurezGraficas', [
	'as' => 'reporteMadurezGraficas',
	'uses' => 'MadurezSGSST\MadurezController@reporteMadurezGraficas',
]);

Route::get('createAuth',[
'as'=> 'createAuth',
'uses'=> 'MadurezSGSST\MadurezController@createAuth'
]);

Route::get('/createCategoria',[
'as'=>'createCategoria',
'uses' => 'MadurezSGSST\MadurezController@createCategoria',
]);

Route::get('/createDimension',[
'as'=>'createDimension',
'uses' => 'MadurezSGSST\MadurezController@createDimension',
]);

Route::get('/createItem',[
'as'=>'createItem',
'uses' => 'MadurezSGSST\MadurezController@createItem',
]);
Route::post('/storeCategoria',[
'as'=>'storeCategoria',
'uses' => 'MadurezSGSST\MadurezController@storeCategoria',
]);

Route::post('/storeItem',[
'as'=>'storeItem',
'uses' => 'MadurezSGSST\MadurezController@storeItem',
]);

Route::post('/storeDimension', [
	'as' => 'storeDimension',
	'uses' => 'MadurezSGSST\MadurezController@storeDimension',
]);

Route::get('/editCategoria/{id}',[
'as'=>'editCategoria',
'uses' => 'MadurezSGSST\MadurezController@editCategoria',
]);

Route::get('/editDimension/{id}',[
'as'=>'editDimension',
'uses' => 'MadurezSGSST\MadurezController@editDimension',
]);

Route::get('/editItem/{id}',[
'as'=>'editItem',
'uses' => 'MadurezSGSST\MadurezController@editItem',
]);

Route::post('/updateCategoria/{id}',[
'as'=>'updateCategoria',
'uses' => 'MadurezSGSST\MadurezController@updateCategoria',
]);

Route::post('/updateItem/{id}',[
	'as'=>'updateItem',
	'uses' => 'MadurezSGSST\MadurezController@updateItem',
	]);

Route::post('/updateLevel/{id_askNivel}', [
	'as' => 'updateLevel',
	'uses' => 'MadurezSGSST\MadurezController@updateLevel',
]);

Route::get('/nivelMadurez/{id}/{id_company}',[
'as'=>'nivelMadurez',
'uses' => 'MadurezSGSST\MadurezController@nivelMadurez',
]);

Route::get('/indexResult/{id_company}', [
	'as' => 'indexResult',
	'uses' => 'MadurezSGSST\MadurezController@indexResult',
]);

Route::get('/editNivel/{id}/{nivel}',[
'as'=>'editNivel',
'uses' => 'MadurezSGSST\MadurezController@editNivel',
]);

Route::post('/storeNivel', [
	'as' => 'storeNivel',
	'uses' => 'MadurezSGSST\MadurezController@storeNivel'
]);

Route::resource('madurez', 'MadurezSGSST\MadurezController');

Route::get('/resultadoMadurez', function () {
	return view('MadurezSGSST/madurezResultado/resultadoMadurez');
});

Route::get('/modulosResultadoMadurez', function () {
	return view('MadurezSGSST/madurezResultado/modulosResultadoMadurez');
});

Route::get('/formularioResultadoMadurez', [
	'as' => 'formularioResultadoMadurez',
	'uses' => 'MadurezSGSST\MadurezController@formularioResultadoMadurez',
]);

Route::post('/createOrUpdteFormularioResultadoMadurez', 'MadurezSGSST\MadurezController@createOrUpdteFormularioResultadoMadurez');

Route::get('/formularioResultadoMadurezEdit', [
	'as' => 'formularioResultadoMadurezEdit',
	'uses' => 'MadurezSGSST\MadurezController@formularioResultadoMadurezEdit',
]);

Route::get('/formularioResultadoGeneralMadurez', [
	'as' => 'formularioResultadoGeneralMadurez',
	'uses' => 'MadurezSGSST\MadurezController@formularioResultadoGeneralMadurez',
]);

Route::post('/createOrUpdteFormularioResultadoGeneralMadurez', 'MadurezSGSST\MadurezController@createOrUpdteFormularioResultadoGeneralMadurez');

Route::post('/updateNivel7', 'MadurezSGSST\MadurezController@updateNivel7');

Route::get('/formularioResultadoGeneralMadurezEdit/{id}', [
	'as' => 'formularioResultadoGeneralMadurezEdit',
	'uses' => 'MadurezSGSST\MadurezController@formularioResultadoGeneralMadurezEdit',
]);

Route::get('/formularioResultadoMadurezPDF', [
	'as' => 'formularioResultadoMadurezPDF',
	'uses' => 'MadurezSGSST\MadurezController@formularioResultadoMadurezPDF',
]);

Route::get('/formularioResultadoGeneralMadurezPDF/{id}', [
	'as' => 'formularioResultadoGeneralMadurezPDF',
	'uses' => 'MadurezSGSST\MadurezController@formularioResultadoGeneralMadurezPDF',
]);

Route::get('/listadoFormularioResultadoMadurez', [
	'as' => 'listadoFormularioResultadoMadurez',
	'uses' => 'MadurezSGSST\MadurezController@listadoFormularioResultadoMadurez',
]);

//****** Inicio rutas reportes contratos y valores rcv ***//


 Route:: resource('rcvalores', 'Rcvalores\RcvaloresController');

 Route::get('/dashboardRcvDatos', function () {
     return view('rcvalores/rcvInfo/dashboardRcvDatos');
});

Route::get('/dashboardRcvirtual', function () {
    return view('rcvalores/rcvInfo/dashboardRcvirtual');
});

Route::get('/dashboardRcvPresencial', function () {
    return view('rcvalores/rcvInfo/dashboardRcvPresencial');
});

Route::get('/dashboardRcvAdministrativa', function () {
    return view('rcvalores/rcvInfo/dashboardRcvAdministrativa');
});

Route::get('/dashboardRcGraficas', function () {
    return view('rcvalores/rcvInfo/dashboardRcGraficas');
});

Route::get('/dashboardRcReportes', function () {
    return view('rcvalores/rcvInfo/dashboardRcReportes');
});

Route::get('/listAnalistaCosto', function () {
    return view('rcvalores/rcvAnalista/listAnalistaCostoRcv');
});


Route::get('/createDatosCalculado',[
	'as' => 'createDatosCalculado',
	'uses' => 'Rcvalores\RcvaloresController@createDatosCalculado'
]);

Route::get('/indexDatos',[
	'as' => 'indexDatos',
	'uses' => 'Rcvalores\RcvaloresController@indexDatos'
]);

Route::get('/indexCalcalculoVirtu/{tipo_personal}',[
	'as'=> 'indexCalcalculoVirtu',
	'uses'=>'Rcvalores\RcvaloresController@indexCalcalculoVirtu'
]);

Route::get('indexGastoExtra/{tipo_personal}',[
	'as'=>'indexGastoExtra',
	'uses'=>'Rcvalores\RcvaloresController@indexGastoExtra'

]);

Route::get('/indexItemVirtu/{tipo_personal}',[
	'as'=> 'indexItemVirtu',
	'uses'=>'Rcvalores\RcvaloresController@indexItemVirtu'
]);

Route::get('/indexRcvItem',[
	'as '=> 'indexRcvItem',
	'uses'=> 'Rcvalores\RcvaloresController@indexRcvItem'
]);

Route::get('/indexCosto',[
	'as '=> 'indexCosto',
	'uses'=> 'Rcvalores\RcvaloresController@indexCosto'
]);

Route::get('/editRcvItem/{id}',[
	'as'=>'editRcvItem',
	'uses'=>'Rcvalores\RcvaloresController@editRcvItem'
]);

Route::get('/editCosto/{id}',[
	'as'=>'editCosto',
	'uses'=>'Rcvalores\RcvaloresController@editCosto'
]);

Route::get('/createCosto',[
	'as'=>'createCosto',
	'uses'=>'Rcvalores\RcvaloresController@createCosto'
]);
Route::post('/storeCosto',[
	'as'=>'storeCosto',
	'uses'=>'Rcvalores\RcvaloresController@storeCosto'
]);

Route::post('/updateRcvItem/{id}',[
	'as'=>'updateRcvItem',
	'uses'=>'Rcvalores\RcvaloresController@updateRcvItem'
]);

Route::post('/updateCosto/{id}',[
	'as'=>'updateCosto',
	'uses'=>'Rcvalores\RcvaloresController@updateCosto'
]);

Route::get('/contratoNew/{id}',[
	'as'=>'contratoNew',
	'uses'=>'Contratos\ContratoController@contratoNew'
]);

Route::post('/newContrato',[
	'as'=>'newContrato',
	'uses'=>'Contratos\ContratoController@newContrato'
]);

Route::get('/buscarAsesor/{tipo_asesor}',[
	'as'=>'buscarAsesor',
	'uses'=>'Rcvalores\RcvaloresController@analystSearch'
]);

Route::post('/guardarTotales', [
	'as'=>'guardarTotales',
	'uses'=>'Rcvalores\RcvaloresController@guardarTotales'
]);

Route::post('/guardarTotalCosto', [
	'as'=>'guardarTotalCosto',
	'uses'=>'Rcvalores\RcvaloresController@guardarTotalCosto'
]);

Route::get('/resultAnalista/{id_user}/{tipo_asesor}',[
	'as'=>'resultAnalista',
	'uses'=>'Rcvalores\RcvaloresController@resultAnalistaCapacidad'
]);

Route::get('/clienteServicio',[
'as' =>'clienteServicio',
'uses'=>'Rcvalores\RcvaloresController@getClientesPorServicio'
]);

Route::get('/analistaEstudio',[ 
'as' =>'analistaEstudio',
'uses'=>'Rcvalores\RcvaloresController@getAnalistasPorEstudio'
]);

Route::get('/obtenerDatosAnalista', [
	'as'=>'obtenerDatosAnalista',
	'uses'=>'Rcvalores\RcvaloresController@obtenerDatosAnalista'
]);

Route::get('/listAnalistaCostoNew', [
	'as'=>'listAnalistaCostoNew',
	'uses'=>'Rcvalores\RcvaloresController@listAnalistaCostoNew'
]);
	


//****** Fin rutas reportes contratos y valores rcv ***//

//Inicia Cuadro de VentasController
Route::get('/cuadroVentas',[
	'as'=>'cuadroVentas',
	'uses'=>'CuadroVentas\VentasController@EmpresasAsociadas'
]);

// Exportar excel para pesv
Route::post('/exportMulti', [
    'as' => 'exportMulti',
    'uses' => 'Excel\ExcelController@exportMulti',
]);
