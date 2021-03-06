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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('admin/logout', 'Auth\LoginController@logout')->name('logout');
// admin
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => ['auth'],
], function() {

    Route::get('/', function() { return view('admin.home'); })->name('admin');

    Route::get('blogs/get/{page?}/{rubric_id?}', 'BlogsController@getBlogsList')->name('blogs.list');
    Route::post('blogs/save', 'BlogsController@store')->name('blog.store');
    Route::post('blogs/save/{id}', 'BlogsController@update');
    Route::get('blogs/single/get/{id}', 'BlogsController@getBlogSingle');
    Route::get('blogs/single/{id}', 'BlogsController@show')->name('blog.show');
    Route::get('blogs/edit/{id}', 'BlogsController@edit')->name('blog.edit');
    Route::get('blogs/create', 'BlogsController@create')->name('blog.create');
    Route::delete('blogs/delete/{id}', 'BlogsController@destroy')->name('blog.destroy');
    Route::get('blogs/{page?}/{rubric_id?}', 'BlogsController@index')->name('blogs.index');

    Route::get('rubrics/get/all', 'RubricsController@getAll');
    Route::get('rubrics/get/search', 'RubricsController@getSearch');
    Route::get('rubrics/get/{page?}/{rubric_id?}', 'RubricsController@getList')->name('rubrics.list');
    Route::post('rubrics/save', 'RubricsController@store')->name('rubric.store');
    Route::post('rubrics/save/{id}', 'RubricsController@update');
    Route::get('rubrics/single/get/{id}', 'RubricsController@getRubricSingle');
    Route::get('rubrics/edit/{id}', 'RubricsController@edit')->name('rubric.edit');
    Route::get('rubrics/create', 'RubricsController@create')->name('rubric.create');
    Route::delete('rubrics/delete/{id}', 'RubricsController@destroy')->name('rubric.destroy');
    Route::get('rubrics/{page?}', 'RubricsController@index')->name('rubrics.index');
});