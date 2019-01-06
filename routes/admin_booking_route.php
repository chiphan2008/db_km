<?php
Route::group(['prefix' => 'booking', 'middleware' => 'checkactive'], function () {
  Route::get('/', 'Admin\\AdminController@getIndex');


  Route::group(['prefix' => 'homepage', 'middleware' => 'checkactive'], function () {
    Route::get('/', 'Admin\\AdminController@getHomePage')->name('list_home_booking');
    
    Route::get('add','Admin\\AdminController@getAddHomePage')->name('add_home_booking');
    Route::post('add','Admin\\AdminController@postAddHomePage');

    Route::post('ajaxLocation','Admin\\AdminController@ajaxLocation');

    Route::get('update/{id}','Admin\\AdminController@getUpdateHomePage')->name('update_home_booking');
    Route::post('update/{id}','Admin\\AdminController@postUpdateHomePage');

    Route::get('delete/{id}','Admin\\AdminController@getDeleteHomePage')->name('delete_home_booking');

    Route::get('change-weight/{id}/{weight}','Admin\\AdminController@getChangeWeightHomePage');

  });
  

  Route::group(['prefix' => 'hotel', 'middleware' => 'checkactive'], function () {
    Route::get('/', 'Admin\\HotelController@getListHotel')->name('list_hotel');
    Route::get('add', ['middleware' => ['permission:add_Hotel'], 'uses' => 'Admin\\HotelController@getAddHotel'])->name('add_hotel_from_content');
    Route::post('add', 'Admin\\HotelController@postAddHotel');
    Route::get('update/{id}', ['middleware' => ['permission:edit_Hotel'], 'uses' => 'Admin\\HotelController@getUpdateHotel'])->name('update_hotel');
    Route::post('update/{id}', 'Admin\\HotelController@postUpdateHotel');

    Route::get('search', 'Admin\\HotelController@getSearchHotel')->name('search_hotel');
    Route::get('delete/{id}', ['middleware' => ['permission:delete_Hotel'], 'uses' => 'Admin\\HotelController@getDeleteHotel'])->name('delete_hotel');
  });

  Route::group(['prefix' => 'room-type', 'middleware' => 'checkactive'], function () {
    Route::get('/{hotel_id}', 'Admin\\RoomTypeController@getListRoomType')->name('list_room_type');
     Route::get('add/{hotel_id}', ['middleware' => ['permission:add_RoomType'], 'uses' => 'Admin\\RoomTypeController@getAddRoomType'])->name('add_room_type');
    Route::post('add/{hotel_id}', 'Admin\\RoomTypeController@postAddRoomType');
    Route::get('update/{id}', ['middleware' => ['permission:edit_RoomType'], 'uses' => 'Admin\\RoomTypeController@getUpdateRoomType'])->name('update_room_type');
    Route::post('update/{id}', 'Admin\\RoomTypeController@postUpdateRoomType');

    Route::get('search', 'Admin\\RoomTypeController@getSearchRoomType')->name('search_room_type');
    Route::get('delete/{id}', ['middleware' => ['permission:delete_RoomType'], 'uses' => 'Admin\\RoomTypeController@getDeleteRoomType'])->name('delete_room_type');
    Route::post('deleteImg', 'Admin\\RoomTypeController@postDeleteImg');
  });

  Route::group(['prefix' => 'option', 'middleware' => 'checkactive'], function () {
    Route::get('/{hotel_id}', 'Admin\\OptionController@getListOption')->name('list_option');
     Route::get('add/{hotel_id}', ['middleware' => ['permission:add_Option'], 'uses' => 'Admin\\OptionController@getAddOption'])->name('add_option');
    Route::post('add/{hotel_id}', 'Admin\\OptionController@postAddOption');
    Route::get('update/{id}', ['middleware' => ['permission:edit_Option'], 'uses' => 'Admin\\OptionController@getUpdateOption'])->name('update_option');
    Route::post('update/{id}', 'Admin\\OptionController@postUpdateOption');

    Route::get('search', 'Admin\\OptionController@getSearchOption')->name('search_option');
    Route::get('delete/{id}', ['middleware' => ['permission:delete_Option'], 'uses' => 'Admin\\OptionController@getDeleteOption'])->name('delete_option');
    Route::post('deleteImg', 'Admin\\OptionController@postDeleteImg');
  });

  Route::group(['prefix' => 'type', 'middleware' => 'checkactive'], function () {
    Route::get('/', 'Admin\\TypeController@getListType')->name('list_type');
    Route::get('add', ['middleware' => ['permission:add_Type'], 'uses' => 'Admin\\TypeController@getAddType'])->name('add_type');
    Route::post('add', 'Admin\\TypeController@postAddType');

    Route::get('update/{id}', ['middleware' => ['permission:edit_Type'], 'uses' => 'Admin\\TypeController@getUpdateType'])->name('update_type');
    Route::post('update/{id}', 'Admin\\TypeController@postUpdateType');
    Route::get('down-weight/{id}', ['middleware' => ['permission:edit_Type'], 'uses' => 'Admin\\TypeController@getDownType']);
    Route::get('up-weight/{id}', ['middleware' => ['permission:edit_Type'], 'uses' => 'Admin\\TypeController@getUpType']);
    
    Route::get('change-weight/{id}/{weight}', ['middleware' => ['permission:edit_Type'], 'uses' => 'Admin\\TypeController@getChangeWeightType']);

    Route::get('delete/{id}', ['middleware' => ['permission:delete_Type'], 'uses' => 'Admin\\TypeController@getDeleteType'])->name('delete_type');
  });
});