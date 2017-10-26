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

use App\Events\OperationLog;
use Illuminate\Support\Facades\Artisan;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/log',function(){
   return view('log');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::post('/auth/jwt', 'Auth\RegisterController@awt');

Route::get('/analyse', function(){
   return view('analyse');
});

Route::post('/processor', 'StringTokenizer@process');

Route::get('/hb', function(){
    dd(Artisan::call('dashboard:send-heartbeat'));
});


//staff only pages

Route::get('/assignment', 'AssignmentController@index');


Route::post('/assignment', 'AssignmentController@store');


//admin only pages
Route::get('/admin/users', 'AdminController@showUsers');
