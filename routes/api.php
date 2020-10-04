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
    Route::post('routeurs/enable/{id}', 'RouteurController@enable');
    Route::get('routeurs/paginate/{per_page}', 'RouteurController@indexPaginate');
    Route::get('routeurs/paginate/{per_page}/searchText/{search_text}', 'RouteurController@indexSearchPaginate');
    Route::get('routeurs/forStatistics/{page?}/{per_page?}', 'RouteurController@indexForStatistics')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::get('routeurs/forStatisticsForBases/{routeur_id}/{page?}/{per_page?}', 'RouteurController@indexForStatisticsForBases')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::get('routeurs/forStatisticsForAnnonceurs/{routeur_id}/{base_id}/{page?}/{per_page?}', 'RouteurController@indexForStatisticsForAnnonceurs')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::get('routeurs/forStatisticsForCampagnes/{routeur_id}/{base_id}/{annonceur_id}/{page?}/{per_page?}', 'RouteurController@indexForStatisticsForCampagnes')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::post('routeurs/applyFilterForStatistics/{page?}/{per_page?}', 'RouteurController@applyFilterForStatistics')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::post('routeurs/applyFilterForStatisticsForBases/{routeur_id}/{page?}/{per_page?}', 'RouteurController@applyFilterForStatisticsForBases')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::post('routeurs/applyFilterForStatisticsForAnnonceurs/{routeur_id}/{base_id}/{page?}/{per_page?}', 'RouteurController@applyFilterForStatisticsForAnnonceurs')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::post('routeurs/applyFilterForStatisticsForCampagnes/{routeur_id}/{base_id}/{annonceur_id}/{page?}/{per_page?}', 'RouteurController@applyFilterForStatisticsForCampagnes')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::get('routeurs/forStatistics/searchText/{search_text}', 'RouteurController@indexSearchForStatistics');

    Route::post('annonceurs/update/{id}', 'AnnonceurController@updateAnnonceur');
    Route::post('annonceurs/delete/{id}', 'AnnonceurController@deleteAnnonceur');
    Route::post('annonceurs/enable/{id}', 'AnnonceurController@enable');
    Route::get('annonceurs/paginate/{per_page}', 'AnnonceurController@indexPaginate');
    Route::get('annonceurs/paginate/{per_page}/searchText/{search_text}', 'AnnonceurController@indexSearchPaginate');
    Route::get('annonceurs/forStatistics/{page?}/{per_page?}', 'AnnonceurController@indexForStatistics')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::get('annonceurs/forStatisticsForCampagnes/{annonceur_id}/{page?}/{per_page?}', 'AnnonceurController@indexForStatisticsForCampagnes')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::get('annonceurs/forStatisticsForRouteurs/{annonceur_id}/{campagne_id}/{page?}/{per_page?}', 'AnnonceurController@indexForStatisticsForRouteurs')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::get('annonceurs/forStatisticsForBases/{annonceur_id}/{campagne_id}/{routeur_id}/{page?}/{per_page?}', 'AnnonceurController@indexForStatisticsForBases')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::post('annonceurs/applyFilterForStatistics/{page?}/{per_page?}', 'AnnonceurController@applyFilterForStatistics')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::post('annonceurs/applyFilterForStatisticsForCampagnes/{annonceur_id}/{page?}/{per_page?}', 'AnnonceurController@applyFilterForStatisticsForCampagnes')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::post('annonceurs/applyFilterForStatisticsForRouteurs/{annonceur_id}/{campagne_id}/{page?}/{per_page?}', 'AnnonceurController@applyFilterForStatisticsForRouteurs')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::post('annonceurs/applyFilterForStatisticsForBases/{annonceur_id}/{campagne_id}/{routeur_id}/{page?}/{per_page?}', 'AnnonceurController@applyFilterForStatisticsForBases')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::get('annonceurs/forStatistics/searchText/{search_text}', 'AnnonceurController@indexSearchForStatistics');

    Route::post('bases/update/{id}', 'BaseController@updateBase');
    Route::post('bases/delete/{id}', 'BaseController@deleteBase');
    Route::post('bases/enable/{id}', 'BaseController@enable');
    Route::get('bases/paginate/{per_page}', 'BaseController@indexPaginate');
    Route::get('bases/paginate/{per_page}/searchText/{search_text}', 'BaseController@indexSearchPaginate');
    Route::get('bases/forStatistics/{page?}/{per_page?}', 'BaseController@indexForStatistics')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::get('bases/forStatisticsForAnnonceurs/{base_id}/{page?}/{per_page?}', 'BaseController@indexForStatisticsForAnnonceurs')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::get('bases/forStatisticsForCampagnes/{base_id}/{annonceur_id}/{page?}/{per_page?}', 'BaseController@indexForStatisticsForCampagnes')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::get('bases/forStatisticsForRouteurs/{base_id}/{annonceur_id}/{campagne_id}/{page?}/{per_page?}', 'BaseController@indexForStatisticsForRouteurs')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::post('bases/applyFilterForStatistics/{page?}/{per_page?}', 'BaseController@applyFilterForStatistics')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::post('bases/applyFilterForStatisticsForAnnonceurs/{base_id}/{page?}/{per_page?}', 'BaseController@applyFilterForStatisticsForAnnonceurs')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::post('bases/applyFilterForStatisticsForCampagnes/{base_id}/{annonceur_id}/{page?}/{per_page?}', 'BaseController@applyFilterForStatisticsForCampagnes')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::post('bases/applyFilterForStatisticsForRouteurs/{base_id}/{annonceur_id}/{campagne_id}/{page?}/{per_page?}', 'BaseController@applyFilterForStatisticsForRouteurs')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::get('bases/forStatistics/searchText/{search_text}', 'BaseController@indexSearchForStatistics');
    Route::get('bases/parRouteur/{idRouteur}', 'BaseController@indexByRouteurId');

    Route::post('campagnes/update/{id}', 'CampagneController@updateCampagne');
    Route::post('campagnes/delete/{id}', 'CampagneController@deleteCampagne');
    Route::post('campagnes/enable/{id}', 'CampagneController@enable');
    Route::get('campagnes/paginate/{per_page}', 'CampagneController@indexPaginate');
    Route::get('campagnes/paginate/{per_page}/searchText/{search_text}', 'CampagneController@indexSearchPaginate');
    Route::get('campagnes/forStatistics/{page?}/{per_page?}', 'CampagneController@indexForStatistics')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::get('campagnes/forStatisticsForRouteurs/{campagne_id}/{page?}/{per_page?}', 'CampagneController@indexForStatisticsForRouteurs')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::get('campagnes/forStatisticsForBases/{campagne_id}/{routeur_id}/{page?}/{per_page?}', 'CampagneController@indexForStatisticsForBases')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::get('campagnes/forStatisticsForAnnonceurs/{campagne_id}/{routeur_id}/{base_id}/{page?}/{per_page?}', 'CampagneController@indexForStatisticsForAnnonceurs')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::post('campagnes/applyFilterForStatistics/{page?}/{per_page?}', 'CampagneController@applyFilterForStatistics')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::post('campagnes/applyFilterForStatisticsForRouteurs/{campagne_id}/{page?}/{per_page?}', 'CampagneController@applyFilterForStatisticsForRouteurs')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::post('campagnes/applyFilterForStatisticsForBases/{campagne_id}/{routeur_id}/{page?}/{per_page?}', 'CampagneController@applyFilterForStatisticsForBases')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::post('campagnes/applyFilterForStatisticsForAnnonceurs/{campagne_id}/{routeur_id}/{base_id}/{page?}/{per_page?}', 'CampagneController@applyFilterForStatisticsForAnnonceurs')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::post('campagnes//{page?}/{per_page?}', 'CampagneController@applyFilterForStatistics')->where(['page'=>'[0-9]+', 'per_page'=>'[0-9]+']);
    Route::post('campagnes/forStatistics/searchText/{search_text}', 'CampagneController@indexSearchForStatistics');
    Route::get('campagnes/parAnnonceur/{idAnnonceur}', 'CampagneController@indexByAnnonceurId');

    Route::post('resultats/update/{id}', 'ResultatController@updateResultat');
    Route::get('resultats/paginate/{per_page}', 'ResultatController@indexPaginate');
    Route::get('resultats/paginate/{per_page}/searchText/{search_text}', 'ResultatController@indexSearchPaginate');
    Route::post('resultats/applyFilter/{per_page}', 'ResultatController@applyFilter');

    Route::post('plannings/update/{id}', 'PlanningController@updatePlanning');
    Route::post('plannings/delete/{id}', 'PlanningController@deletePlanning');
    Route::post('plannings/enable/{id}', 'PlanningController@enable');
    Route::post('plannings/hide/{id}', 'PlanningController@hide');
    Route::get('plannings/paginate/{per_page}', 'PlanningController@indexPaginate');
    Route::get('plannings/paginate/{per_page}/searchText/{search_text}', 'PlanningController@indexSearchPaginate');
    Route::post('plannings/applyFilter/{per_page}', 'PlanningController@applyFilter');

    Route::post('users/update/{id}', 'UserController@updateUser');
    Route::post('users/delete/{id}', 'UserController@deleteUser');
    Route::post('users/enable/{id}', 'UserController@enable');
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