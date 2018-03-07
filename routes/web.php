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
    return view('auth.login');
});

Route::get('/page/{which}', 'PageController@page');

Route::post('/page/contact', 'PageController@storeContact');

Route::get('/log',function(){
   return view('log');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');





Route::post('/auth/jwt', 'Auth\RegisterController@awt');

Route::get('/analyse', 'AnalyseController@index');

Route::get('/analyse/{code}', 'AnalyseController@index');




Route::post('/processor', 'StringTokenizer@process');
Route::post('/feedback', 'FeedbackController@generateFeedback');

/**axios call **/
Route::post('/feedback/store', 'FeedbackController@storeFeedback');



Route::get('/hb', function(){
    dd(Artisan::call('dashboard:send-heartbeat'));
});


//users (students) access

Route::get('/assignment/search', 'AssignmentController@search');

Route::post('assignments/toUser', 'AssignmentController@subscribeUserToAssignment');

/* axois calls **/
    Route::post('assignments/action', 'AssignmentController@action');




//document controller
/* axois calls **/
    Route::post('documents/action', 'DocumentController@action');

    //get all documents belonging to logged in user
    Route::get('/documents/all', 'DocumentController@fetchDocuments');

/* direct calls */
    Route::post('/document', 'DocumentController@store');



//example texts
    Route::get('/example', 'ExampleController@index');
    Route::get('/example/analyse', 'ExampleController@analyse');
    Route::get('/example/analyse/{code}', 'ExampleController@analyse');

/* axois calls **/
    Route::get('/example/all', 'ExampleController@fetchExamples');
    Route::post('/example/store', 'ExampleController@store');



//staff & admin only pages

Route::get('/assignment', 'AssignmentController@index');


Route::post('/assignment', 'AssignmentController@store');


//admin only pages
Route::get('/admin/users', 'AdminController@showUsers');

Route::post('/admin/users', 'AdminController@updateUserRoles');

Route::post('/admin/addUser', 'AdminController@addUser');
