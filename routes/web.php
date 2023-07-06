<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', 'Auth\AuthController@loginForm')->name('login');
Route::post('/login', 'Auth\AuthController@login')->name('login.store');

Route::get('/dashboard', 'DashboardController@dashboard')->name('admin.dashboard');
Route::get('/individual-dashboard', 'DashboardController@individualDashboard')->name('individual.dashboard');
Route::post('/get-all-kpi-dashboard-data', 'DashboardController@getIndividualDashboard')->name('get.kpi.dashboard.data');

Route::group(['middleware' => 'auth'], function () {
    //kpi info
    Route::get('kpi/kpi-list', 'KPIInfoController@kpiList')->name('kpiData.list');
    Route::get('kpi/kpi-info-create', 'KPIInfoController@kpiCreate')->name('create.kpi.info');
    Route::post('kpi/kpi-info-store', 'KPIInfoController@kpiStore')->name('store.kpi.info');
    Route::get('kpi/kpi-info-edit/{KPICode}', 'KPIInfoController@kpiEdit')->name('edit.kpi.info');
    Route::post('kpi/kpi-info-update/{KPICode}', 'KPIInfoController@kpiUpdate')->name('update.kpi.info');

    Route::get('kpi/entry-list', 'KPIEntryController@kpiEntryList')->name('kpi.entry.list');
    Route::get('kpi/kpi-entry-create', 'KPIEntryController@kpiEntryCreate')->name('create.kpi.entry');
    Route::post('kpi/kpi-entry-store', 'KPIEntryController@kpiEntryStore')->name('kpi.entry.store');
    Route::get('kpi/kpi-entry-edit/{KPIEntryMasterCode}', 'KPIEntryController@kpiEntryEdit')->name('kpi.entry.edit');
    Route::post('kpi/kpi-entry-update/{KPIEntryMasterCode}', 'KPIEntryController@kpiEntryUpdate')->name('kpi.entry.update');
    Route::post('kpi-data-import', 'KPIEntryController@kpiDataImport')->name('kpi.data.import');
});


//route for logout
Route::post('logout', 'Auth\AuthController@logout')->name('logout');

