<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('campagnes/parAnnonceur/{idAnnonceur}', 'CampagneController@indexByAnnonceurId');

Route::get('bases/parRouteur/{idRouteur}', 'BaseController@indexByRouteurId');

Route::get('routeurs/forStatistics', 'RouteurController@indexForStatistics');

Route::get('annonceurs/forStatistics', 'AnnonceurController@indexForStatistics');

Route::get('bases/forStatistics', 'BaseController@indexForStatistics');

Route::apiResources([
    'annonceurs' => 'AnnonceurController',
    'bases' => 'BaseController',
    'campagnes' => 'CampagneController',
    'plannings' => 'PlanningController',
    'routeurs' => 'RouteurController',
    'resultats' => 'ResultatController'
]);
