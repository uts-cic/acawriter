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

use Illuminate\Support\Facades\Artisan;

// Anonymous pages
Route::get('/', 'IndexController@index');

Route::get('/page/{which}', 'PageController@page');
Route::get('/page/contact/refreshcaptcha', 'PageController@refreshCaptcha');
Route::post('/page/contact', 'PageController@storeContact');

Route::get('/log', function () {
    return view('log');
});

Auth::routes(['register' => false, 'reset' => false]);

// Authenticated pages
Route::post('/auth/jwt', 'Auth\RegisterController@awt');
Route::post('/auth/lti', 'Auth\RegisterController@lti');

Route::get('/home', 'HomeController@index')->name('home');

// Analyse pages
Route::get('/analyse', 'AnalyseController@index');
Route::get('/analyse/{code}', 'AnalyseController@index');

// Feedback API
Route::post('/processor', 'StringTokenizer@process');
Route::post('/feedback', 'FeedbackController@generateFeedback');
Route::post('/feedback/store', 'FeedbackController@storeFeedback');

// Heartbeat check API
Route::get('/hb', function () {
    dd(Artisan::call('dashboard:send-heartbeat'));
});

// Assignment subscription API
Route::post('/subscribe', 'AssignmentController@subscribe');

// File upload
Route::get('/doc-upload', 'S3Controller@docUpload');
Route::post('/doc-upload', 'S3Controller@processUpload');

// Document API
Route::post('/documents/action', 'DocumentController@action');
Route::get('/documents/all', 'DocumentController@fetchDocuments');
Route::post('/document', 'DocumentController@store');
\

// Examples
Route::get('/example', 'ExampleController@index');
Route::get('/example/analyse', 'ExampleController@analyse');
Route::get('/example/analyse/{code}', 'ExampleController@analyse');

// Examples API
Route::get('/example/all', 'ExampleController@fetchExamples');
Route::post('/example/store', 'ExampleController@store');

// Staff & admin only pages
Route::get('/assignment', 'AssignmentController@index');
Route::post('/assignment', 'AssignmentController@store');

// Assignment API
Route::post('/assignments/action', 'AssignmentController@action');

// Admin only pages
Route::get('/admin/users', 'AdminController@showUsers');
Route::get('/admin/reports', 'ReportController@index');
Route::get('/admin/download/{type}/{what}/{did}/{uid}', 'ReportController@export');
Route::post('/admin/users', 'AdminController@updateUserRoles');
Route::post('/admin/addUser', 'AdminController@addUser');
Route::post('/admin/report', 'ReportController@fetchDocs');


// Generate and download pdf
Route::get('/generate-pdf/{draftId}', 'PdfGeneratorController@pdfview');


// Activity logger
Route::post('/collect', 'ActivityController@collect');
