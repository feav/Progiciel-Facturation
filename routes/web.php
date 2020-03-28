<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('index')->middleware('auth');

// Authentication Routes...
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('campagnes/parAnnonceur/{idAnnonceur}', 'CampagneController@indexByAnnonceurId');

Route::get('bases/parRouteur/{idRouteur}', 'BaseController@indexByRouteurId');

Route::get('routeurs/forStatistics', 'RouteurController@indexForStatistics');

Route::post('routeurs/applyFilter', 'RouteurController@applyFilter');

Route::get('annonceurs/forStatistics', 'AnnonceurController@indexForStatistics');

Route::post('annonceurs/applyFilter', 'AnnonceurController@applyFilter');

Route::get('bases/forStatistics', 'BaseController@indexForStatistics');

Route::post('bases/applyFilter', 'BaseController@applyFilter');

Route::post('resultats/applyFilter', 'ResultatController@applyFilter');

Route::apiResources([
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