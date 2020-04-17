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
Auth::routes(['register' => false, 'reset' => false]);

// Anonymous pages
Route::get('/', 'IndexController@index');
Route::get('/about', 'IndexController@about');
Route::get('/terms', 'IndexController@terms');
Route::get('/help', 'IndexController@help');

Route::get('/contact', 'ContactController@index');
Route::post('/help', 'ContactController@storeContact');
Route::get('/help/refreshcaptcha', 'ContactController@refreshCaptcha');

Route::post('/auth/jwt', 'Auth\RegisterController@awt');
Route::post('/auth/lti', 'Auth\RegisterController@lti');

// Analyse page
Route::get('/analyse/{code}', 'AnalyseController@index');

// Feedback API
Route::post('/processor', 'StringTokenizer@process');
Route::post('/feedback', 'FeedbackController@generateFeedback');
Route::post('/feedback/store', 'FeedbackController@storeFeedback');

// Document API
Route::get('/documents', 'DocumentController@index');
Route::post('/document/create', 'DocumentController@create');
Route::post('/document/update', 'DocumentController@update');
Route::post('/document/delete', 'DocumentController@delete');

// Assignment subscription API
Route::post('/subscribe', 'DocumentController@subscribe');

// Examples
Route::get('/example', 'ExampleController@index');
Route::get('/example/analyse', 'ExampleController@analyse');
Route::get('/example/analyse/{code}', 'ExampleController@analyse');

// Examples API
Route::get('/example/all', 'ExampleController@fetchExamples');

// Staff & admin only pages
Route::get('/assignments', 'AssignmentController@index');
Route::post('/assignment/create', 'AssignmentController@create');
Route::post('/assignment/delete', 'AssignmentController@delete');

// Admin only pages
Route::get('/admin/users', 'AdminController@showUsers');
Route::get('/admin/documents', 'DiffController@showDocuments');
Route::post('/admin/users', 'AdminController@updateUserRoles');
Route::post('/admin/addUser', 'AdminController@addUser');
Route::post('/admin/report', 'ReportController@fetchDocs');
Route::post('/admin/documentsByUser', 'DiffController@getDocuments');
Route::post('/admin/diffDocuments', 'DiffController@showDrafts');
Route::get('/admin/diffreport', 'DiffController@produceReport');
Route::get('/admin/reports', 'ReportController@index');
Route::get('/admin/download/{type}/{what}/{did}/{uid}', 'ReportController@export');
Route::post('/admin/reports', 'ReportController@fetchDocs');

// Generate and download pdf
Route::get('/analyse/{code}/pdf', 'PdfGeneratorController@pdfview');

// Activity logger
Route::post('/collect', 'ActivityController@collect');
