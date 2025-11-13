<?php

use Illuminate\Support\Facades\Route;

Route::group(['middleware' => ['web', 'auth', 'SetSessionData', 'language', 'timezone', 'AdminSidebarMenu', 'CheckUserLogin'], 'prefix' => 'timesheet-management', 'namespace' => 'Modules\TimesheetManagement\Http\Controllers'], function () {

    // Employee Routes
    Route::prefix('employees')->group(function () {
        Route::get('/', 'EmployeeController@index')->name('timesheet.employees.index');
        Route::get('/create', 'EmployeeController@create')->name('timesheet.employees.create');
        Route::post('/store', 'EmployeeController@store')->name('timesheet.employees.store');
        Route::get('/{id}/show', 'EmployeeController@show')->name('timesheet.employees.show');
        Route::get('/{id}/edit', 'EmployeeController@edit')->name('timesheet.employees.edit');
        Route::post('/{id}/update', 'EmployeeController@update')->name('timesheet.employees.update');
        Route::delete('/{id}/destroy', 'EmployeeController@destroy')->name('timesheet.employees.destroy');
    });

    // Timesheet Routes
    Route::prefix('timesheets')->group(function () {
        Route::get('/', 'TimesheetController@index')->name('timesheet.timesheets.index');
        Route::get('/create', 'TimesheetController@create')->name('timesheet.timesheets.create');
        Route::post('/store', 'TimesheetController@store')->name('timesheet.timesheets.store');
        Route::get('/{id}/show', 'TimesheetController@show')->name('timesheet.timesheets.show');
        Route::get('/{id}/edit', 'TimesheetController@edit')->name('timesheet.timesheets.edit');
        Route::post('/{id}/update', 'TimesheetController@update')->name('timesheet.timesheets.update');
        Route::delete('/{id}/destroy', 'TimesheetController@destroy')->name('timesheet.timesheets.destroy');
        Route::get('/employee/{employee_id}', 'TimesheetController@getByEmployee')->name('timesheet.timesheets.by_employee');
        Route::post('/auto-generate/{employee_id}', 'TimesheetController@autoGenerate')->name('timesheet.timesheets.auto_generate');
    });
});

