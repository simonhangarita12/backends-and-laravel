<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;  // <-- add this if missing
use App\Http\Controllers\SST\trackingCompleteDocs;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('loginSavanna/V1', 'Authentication\LoginController@apiLogin');
Route::get('login/V2', 'Authentication\LoginController@postLogin');
Route::get('login/V1', 'Api\ApiController@apiLogin');
Route::post('register/v1.0', 'Api\ApiController@apiCreate');
Route::post('updateUser/v1.0', 'Api\ApiController@apiUpdateUser');
Route::resource('/roles','Company\RolController');
Route::get('v1.0/access', 'Api\Access@access');
Route::get('eduTech','Authentication\LoginController@apiEdu');
Route::any('/holcim/{any}', 'Api\ApiController@apiholcim')
    ->where('any', '.*');

Route::any('/pesv/{any}', 'Api\ApiController@apipesv')
    ->where('any', '.*');
Route::any('/ventas/{any}', 'Api\ApiController@apiventas')
    ->where('any', '.*');
Route::any('/multimedia/{any}', 'Api\ApiController@apimultimedia')
    ->where('any', '.*');
Route::get('/get-tracking-docs', function (Request $request) {
    
    // if ($request->header('X-Api-Key') !== env('MY_SECRET_KEY')) {
    //     return response()->json(['error' => 'Unauthorized'], 401);
    // }
    // Create controller instance
    $controller = new trackingCompleteDocs();

    // Call the Laravel function
    $result = $controller->getTrackingDocs(
        $request->query('company_id'),
        $request->query('module'),
        $request->query('sub_module')
    );

    return response()->json($result);
});