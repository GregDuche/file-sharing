<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Authentication routes...
Route::get('auth/login', 'Auth\AuthController@getLogin');
Route::post('auth/login', 'Auth\AuthController@postLogin');
Route::get('auth/logout', 'Auth\AuthController@getLogout');


Route::group(['middleware' => 'auth'], function() {
	Route::get('users', 'Settings@users');

	Route::get('/', [
		'uses' => 'Share@index',
		'as' => 'share.index']);

	Route::get('/my-files', [
		'uses' => 'Share@myFiles',
		'as' => 'share.my-files']);

	Route::get('/thanks', function() {
		return view('share.thanks');
	});
	Route::get('/download/{id}', [
		'uses' => 'Download@download',
		'as' => '',
		]);

});


Route::group(['prefix' => 'api/', 'middleware' => ['services'], 'namespace' => 'API'], function () {
	Route::resource('users', 'Users');
	Route::resource('shared-files', 'SharedFiles');
	Route::resource('shared-files-recipient', 'SharedFilesRecipient');
	
	Route::post('file/upload', [
		'uses' => '\App\Http\Controllers\API\File@upload',
		'as' => 'file.upload']);
});

