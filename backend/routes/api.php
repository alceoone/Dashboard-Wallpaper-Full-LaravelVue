<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
//--- Version Api 1.0
Route::group(['prefix' => 'v1'], function()  { 
    //--- Image All
    Route::get('{key}/image/all/show', 'ApiApllication@ShowImangeAll');
    
});

//--- Version Api 2.0
Route::group(['prefix' => 'v2'], function()  {
    
    Route::get('ujicoba', 'UjiCobaController@ujicoba');
    Route::post('login', 'AuthController@login');

    //-- Show Users --//
    Route::get('user/show', 'UserDashboard@ShowUsers')->middleware('jwt.verify');
    //-- Add Users --//
    Route::post('user/insert', 'UserDashboard@AddUsers')->middleware('jwt.verify');
    //-- Delete Users
    //-- Edit Users
    // Route::post('user/akun/edit', 'UserDashboard@EditUsers');

    //---------------------------------------------------------------------------------------------
    //-- Show Dashboard --//
    Route::get('dashboard/data/{id}/show', 'Dashboard@Dashboard')->middleware('jwt.verify');

    //---------------------------------------------------------------------------------------------
    //-- Show Application --//
    Route::get('application/{id}/show', 'Dashboard@ApplicationShow')->middleware('jwt.verify');
    //-- Add Application --//
    Route::post('application/insert', 'Dashboard@ApplicationInsert')->middleware('jwt.verify');
    //-- Show Detail --//
    Route::get('application/{id}/{id_user}/detail', 'Dashboard@ApplicationShowById')->middleware('jwt.verify');
    //-- Show Detail --//
    Route::get('application/{id}/{id_user}/image/detail', 'Dashboard@ApplicationShowCountId')->middleware('jwt.verify');
    //-- Edit Detail --//
    Route::post('application/{id}/edit', 'Dashboard@ApplicationEdit')->middleware('jwt.verify');
    //-- Delete Application --//
    Route::delete('application/{id}/delete', 'Dashboard@ApplicationDelete')->middleware('jwt.verify');

    //---------------------------------------------------------------------------------------------
    //-- Show Categories --//
    Route::get('categories/{id}/show', 'Dashboard@CategoryShow')->middleware('jwt.verify');
    //-- Show Categories By Id --//
    Route::get('categories/{id}/detail', 'Dashboard@CategoryShowById')->middleware('jwt.verify'); //---> user cek
    //-- Delete Categories By Id --//
    Route::delete('categories/{id}/delete', 'Dashboard@CategoryDelete')->middleware('jwt.verify'); 
    //-- Edit Detail --//
    Route::post('categories/{id}/edit', 'Dashboard@CategoryEdit')->middleware('jwt.verify'); //---> user cek
    //-- Add Application --//
    Route::post('categories/insert', 'Dashboard@CategoryInsert')->middleware('jwt.verify');
    
    //---------------------------------------------------------------------------------------------
    //-- Show Categories --//
    Route::get('upload/{id}/show', 'UploadImage@showApplicationCategory')->middleware('jwt.verify');
    //-- Upload Assets --//
    Route::post('upload/{app}/{category}/image', 'UploadImage@uploadImage');
    //-- Upload Url --//
    Route::post('upload/image/url', 'UploadImage@uploadImageUrl')->middleware('jwt.verify');
    //-- Delete Image Menu Application --//
    Route::delete('image/{id}/{id_app}/delete', 'UploadImage@deleteImage')->middleware('jwt.verify');
    //-- Delete Image Menu Category --//
    Route::delete('image/{id}/{id_app}/c/delete', 'UploadImage@deleteImageCategory')->middleware('jwt.verify');


    //---------------------------------------------------------------------------------------------
    //-- Setting admin Limit Apps
    Route::get('application/setting/limit/show', 'UserDashboard@limitApps')->middleware('jwt.verify');
    //-- Setting admin Limit Apps
    Route::post('application/setting/limit/edit', 'UserDashboard@limitAppsEdit')->middleware('jwt.verify');

});
Route::group(['prefix' => 'v2/android'], function()  { 
    
    //---------------------------------------------------------------------------------------------
    //--- Image All
    Route::get('{key}/image/all/show', 'ApiApllication@ShowImangeAll');
    //--- Image Category
    Route::get('{key}/image/category/all/show', 'ApiApllication@ShowCategoryAll');
    //--- Image Category
    Route::get('{key}/image/category/id/show', 'ApiApllication@ShowImageByIdCategory');
    //---------------------------------------------------------------------------------------------
    //
    // -- Progress
    //
    //---------------------------------------------------------------------------------------------
    //--- Crausel
    //--- Deskripsi

});