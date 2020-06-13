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
Route::post('login', 'Auth\LoginController@login');

Route::middleware(['auth:api'])->group(function () {
    // Authentication Routes...
    Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
    
    Route::post('logout', 'Auth\LoginController@logout')->name('logout');

    Route::get('statisticsForDashboard', 'DashboardController@statistics');

    Route::post('routeurs/update/{id}', 'RouteurController@updateRouteur');
    Route::post('routeurs/delete/{id}', 'RouteurController@deleteRouteur');
    Route::get('routeurs/paginate/{per_page}', 'RouteurController@indexPaginate');
    Route::get('routeurs/paginate/{per_page}/searchText/{search_text}', 'RouteurController@indexSearchPaginate');
    Route::get('routeurs/forStatistics/{page?}/{per_page?}', 'RouteurController@indexForStatistics')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::get('routeurs/forStatistics/searchText/{search_text}', 'RouteurController@indexSearchForStatistics');
    Route::post('routeurs/applyFilterForStatistics/{page?}/{per_page?}', 'RouteurController@applyFilterForStatistics')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);

    Route::post('annonceurs/update/{id}', 'AnnonceurController@updateAnnonceur');
    Route::post('annonceurs/delete/{id}', 'AnnonceurController@deleteAnnonceur');
    Route::get('annonceurs/paginate/{per_page}', 'AnnonceurController@indexPaginate');
    Route::get('annonceurs/paginate/{per_page}/searchText/{search_text}', 'AnnonceurController@indexSearchPaginate');
    Route::get('annonceurs/forStatistics/{page?}/{per_page?}', 'AnnonceurController@indexForStatistics')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::get('annonceurs/forStatistics/searchText/{search_text}', 'AnnonceurController@indexSearchForStatistics');
    Route::post('annonceurs/applyFilterForStatistics/{page?}/{per_page?}', 'AnnonceurController@applyFilterForStatistics')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);

    Route::post('bases/update/{id}', 'BaseController@updateBase');
    Route::post('bases/delete/{id}', 'BaseController@deleteBase');
    Route::get('bases/paginate/{per_page}', 'BaseController@indexPaginate');
    Route::get('bases/paginate/{per_page}/searchText/{search_text}', 'BaseController@indexSearchPaginate');
    Route::get('bases/forStatistics/{page?}/{per_page?}', 'BaseController@indexForStatistics')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::get('bases/forStatistics/searchText/{search_text}', 'BaseController@indexSearchForStatistics');
    Route::post('bases/applyFilterForStatistics/{page?}/{per_page?}', 'BaseController@applyFilterForStatistics')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::get('bases/parRouteur/{idRouteur}', 'BaseController@indexByRouteurId');

    Route::post('campagnes/update/{id}', 'CampagneController@updateCampagne');
    Route::post('campagnes/delete/{id}', 'CampagneController@deleteCampagne');
    Route::get('campagnes/paginate/{per_page}', 'CampagneController@indexPaginate');
    Route::get('campagnes/paginate/{per_page}/searchText/{search_text}', 'CampagneController@indexSearchPaginate');
    Route::get('campagnes/forStatistics/{page?}/{per_page?}', 'CampagneController@indexForStatistics')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::post('campagnes/forStatistics/searchText/{search_text}', 'CampagneController@indexSearchForStatistics');
    Route::post('campagnes/applyFilterForStatistics', 'CampagneController@applyFilterForStatistics')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::get('campagnes/parAnnonceur/{idAnnonceur}', 'CampagneController@indexByAnnonceurId');

    Route::post('resultats/update/{id}', 'ResultatController@updateResultat');
    Route::get('resultats/paginate/{per_page}', 'ResultatController@indexPaginate');
    Route::get('resultats/paginate/{per_page}/searchText/{search_text}', 'ResultatController@indexSearchPaginate');
    Route::post('resultats/applyFilter/{per_page}', 'ResultatController@applyFilter');

    Route::post('plannings/delete/{id}', 'PlanningController@deletePlanning');
    Route::get('plannings/paginate/{per_page}', 'PlanningController@indexPaginate');
    Route::get('plannings/paginate/{per_page}/searchText/{search_text}', 'PlanningController@indexSearchPaginate');
    Route::post('plannings/applyFilter/{per_page}', 'PlanningController@applyFilter');

    Route::post('users/update/{id}', 'UserController@updateUser');
    Route::post('users/delete/{id}', 'UserController@deleteUser');
    Route::get('users/paginate/{per_page}', 'UserController@indexPaginate');
    Route::get('users/paginate/{per_page}/searchText/{search_text}', 'UserController@indexSearchPaginate');

    Route::resources([
        'annonceurs' => 'AnnonceurController',
        'bases' => 'BaseController',
        'campagnes' => 'CampagneController',
        'plannings' => 'PlanningController',
        'routeurs' => 'RouteurController',
        'resultats' => 'ResultatController',
        'users' => 'UserController'
    ]);

    Route::resource('roles', 'RoleController', ['only' => ['index']]);
});