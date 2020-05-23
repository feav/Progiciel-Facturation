<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('index')->middleware('auth');

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('statisticsForDashboard', 'DashboardController@statistics');

Route::post('routeurs/update/{id}', 'RouteurController@updateRouteur');
Route::post('routeurs/delete/{id}', 'RouteurController@deleteRouteur');
Route::get('routeurs/paginate/{per_page}', 'RouteurController@indexPaginate');
Route::get('routeurs/paginate/{per_page}/searchText/{search_text}', 'RouteurController@indexSearchPaginate');
Route::get('routeurs/forStatistics', 'RouteurController@indexForStatistics');
Route::get('routeurs/forStatistics/searchText/{search_text}', 'RouteurController@indexSearchForStatistics');
Route::post('routeurs/applyFilterForStatistics', 'RouteurController@applyFilterForStatistics');

Route::post('annonceurs/update/{id}', 'AnnonceurController@updateAnnonceur');
Route::post('annonceurs/delete/{id}', 'AnnonceurController@deleteAnnonceur');
Route::get('annonceurs/paginate/{per_page}', 'AnnonceurController@indexPaginate');
Route::get('annonceurs/paginate/{per_page}/searchText/{search_text}', 'AnnonceurController@indexSearchPaginate');
Route::get('annonceurs/forStatistics', 'AnnonceurController@indexForStatistics');
Route::get('annonceurs/forStatistics/searchText/{search_text}', 'AnnonceurController@indexSearchForStatistics');
Route::post('annonceurs/applyFilterForStatistics', 'AnnonceurController@applyFilterForStatistics');

Route::post('bases/update/{id}', 'BaseController@updateBase');
Route::post('bases/delete/{id}', 'BaseController@deleteBase');
Route::get('bases/paginate/{per_page}', 'BaseController@indexPaginate');
Route::get('bases/paginate/{per_page}/searchText/{search_text}', 'BaseController@indexSearchPaginate');
Route::get('bases/forStatistics', 'BaseController@indexForStatistics');
Route::get('bases/forStatistics/searchText/{search_text}', 'BaseController@indexSearchForStatistics');
Route::post('bases/applyFilterForStatistics', 'BaseController@applyFilterForStatistics');
Route::get('bases/parRouteur/{idRouteur}', 'BaseController@indexByRouteurId');

Route::post('campagnes/update/{id}', 'CampagneController@updateCampagne');
Route::post('campagnes/delete/{id}', 'CampagneController@deleteCampagne');
Route::get('campagnes/paginate/{per_page}', 'CampagneController@indexPaginate');
Route::get('campagnes/paginate/{per_page}/searchText/{search_text}', 'CampagneController@indexSearchPaginate');
Route::get('campagnes/forStatistics', 'CampagneController@indexForStatistics');
Route::post('campagnes/forStatistics/searchText/{search_text}', 'CampagneController@indexSearchForStatistics');
Route::post('campagnes/applyFilterForStatistics', 'CampagneController@applyFilterForStatistics');
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

Route::fallback(function () {
    return view('pages.errors.404');
});

Route::get('/clear-cache', function() {
    $exitCode = Artisan::call('cache:clear');
    return redirect()->route('index');
});

Route::get('/passport-install', function() {
    $exitCode = Artisan::call('passport:install');
    return $exitCode;
});