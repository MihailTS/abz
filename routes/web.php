<?php
use \App\Employee;
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
Route::get('/', 'EmployeeController@index');
Route::get('/employee/children/{id}', 'EmployeeController@getChildren')->where('id', '[0-9]+')->middleware('ajaxOnly');
Route::get('/employee/change_head/{id}/{head}', 'EmployeeController@changeHead')->where('id', '[0-9]+')->middleware('ajaxOnly');
Route::get('/employees/', 'EmployeeController@getSorted')->middleware('ajaxOnly');
Route::get('/list/', 'EmployeeController@emplList');
Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
