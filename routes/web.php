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

// admin
Route::group([
    'prefix' => 'admin',
    'namespace' => 'Admin',
    'middleware' => ['auth'],
], function() {
    
    Route::get('blogs/all/{page?}/{rubric_id?}', 'BlogsController@index')->name('blogs.list');
    Route::post('blogs/save', 'BlogsController@store')->name('blog.store');
    Route::post('blogs/save/{id}', 'BlogsController@update');
    Route::get('blogs/single/get/{id}', 'BlogsController@getBlogsSingle');
    // Route::get('blogs/single/{id}', 'BlogsController@show')->name('blog.show');
    // Route::get('blogs/edit/get/{id}', 'BlogsController@getBlogsEdit');
    // Route::get('blogs/edit/{id}', 'BlogsController@edit')->name('blog.edit');
    // Route::get('blogs/create', 'BlogsController@create')->name('blog.create');
    // Route::get('blogs/get/{method?}/{page?}/{rubric_id?}', 'BlogsController@getNewsList');
    Route::delete('blogs/delete/{id}', 'BlogsController@destroy')->name('blog.destroy');

    Route::get('rubrics/all/{page?}/{rubric_id?}', 'RubricsController@index')->name('rubrics.list');
    Route::post('rubrics/save', 'RubricsController@store')->name('rubric.store');
    Route::post('rubrics/save/{id}', 'RubricsController@update');
    Route::get('rubrics/single/get/{id}', 'RubricsController@getBlogsSingle');
    // Route::get('rubrics/single/{id}', 'RubricsController@show')->name('rubric.show');
    // Route::get('rubrics/edit/get/{id}', 'RubricsController@getBlogsEdit');
    // Route::get('rubrics/edit/{id}', 'RubricsController@edit')->name('rubric.edit');
    // Route::get('rubrics/create', 'RubricsController@create')->name('rubric.create');
    // Route::get('rubrics/get/{method?}/{page?}/{rubric_id?}', 'RubricsController@getNewsList');
    Route::delete('rubrics/delete/{id}', 'RubricsController@destroy')->name('rubric.destroy');
});