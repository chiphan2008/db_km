<?php
Route::group(['prefix' => 'discount', 'middleware' => 'checkactive'], function () {
  Route::any('/', ['middleware' => ['permission:view_Discount'], 'uses' => 'Admin\\DiscountController@getListDiscount'])->name('list_discount');
  // Route::get('searchContent', ['uses' => 'Admin\\DiscountController@getSearchContent'])->name('search_content');
  
  Route::get('add', ['middleware' => ['permission:add_Discount'], 'uses' => 'Admin\\DiscountController@getAddDiscount'])->name('add_discount');
  Route::post('add', ['middleware' => ['permission:add_Discount'], 'uses' => 'Admin\\DiscountController@postAddDiscount'])->name('add_discount');

  Route::get('update/{id}', ['middleware' => ['permission:edit_Discount'], 'uses' => 'Admin\\DiscountController@getUpdateDiscount'])->name('update_discount');
  Route::post('update/{id}', ['middleware' => ['permission:edit_Discount'], 'uses' => 'Admin\\DiscountController@postUpdateDiscount']);

  Route::post('postDeleteImage', ['middleware' => ['permission:edit_Discount'], 'uses' => 'Admin\\DiscountController@postDeleteImage']);
  

  Route::get('delete/{id}', ['middleware' => ['permission:delete_Discount'], 'uses' => 'Admin\\DiscountController@getDeleteDiscount'])->name('delete_discount');
});