<?php

use Illuminate\Http\Request;

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

Route::get('/third_part/best-hotel', 'ThirdPart\HotelController@getBestHotel');
Route::get('/third_part/crazy-hotel', 'ThirdPart\HotelController@getCrazyHotel');

Route::post('/v1/token','API\TokenController@token')->name('hotels.token');

Route::group(['middleware' => ['UserJwtAuth']], function (){
    Route::get('/v1/third-part-hotels', 'API\HotelController@getThirdPartHotels')->name('hotels.third.search');
    Route::get('/v1/local-hotels', 'API\HotelController@getLocalHotels')->name('hotels.local.get');
    Route::post('/v1/local-hotels', 'API\HotelController@createHotel')->name('hotels.local.create');
});
    
    
    
