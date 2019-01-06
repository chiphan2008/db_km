<?php



// page dashboard + logout.
Route::get('/', 'Admin\\AdminController@getIndex')->name('dashboard');
Route::get('logout', 'Admin\\AdminController@logout')->name('logout');
Route::any('session_pagination', 'Admin\\AdminController@sessionPagination');
Route::get('profile', ['uses' => 'Admin\\UserController@getProfileUser'])->name('profile');
Route::get('user_content/{moderation}', ['uses' => 'Admin\\UserController@getContentUser'])->name('content_user');

// route group list user.
Route::group(['prefix' => 'user'], function () {
  Route::any('/', ['middleware' => ['permission:view_User'], 'uses' => 'Admin\\UserController@getListUser'])->name('list_user');

  Route::get('add', ['middleware' => ['permission:add_User'], 'uses' => 'Admin\\UserController@getThemUser'])->name('add_user');
  Route::post('add', ['middleware' => ['permission:add_User'], 'uses' => 'Admin\\UserController@postThemUser']);

  Route::get('list_content/{id}/{moderation}', ['uses' => 'Admin\\UserController@getListContentUser'])->name('list_content_user');

  Route::get('update/{id}', ['middleware' => ['permission:edit_User'], 'uses' => 'Admin\\UserController@getSuaUser'])->name('update_user');
  Route::post('update/{id}', ['uses' => 'Admin\\UserController@postSuaUser']);

  Route::get('delete/{id}', ['middleware' => ['permission:delete_User'], 'uses' => 'Admin\\UserController@getXoaUser'])->name('delete_user');
});

//client
Route::group(['prefix' => 'client'], function () {
  Route::any('/', ['middleware' => ['permission:view_Client'], 'uses' => 'Admin\\ClientController@getListClient']);
  Route::get('/detail/{id}/{type}', ['middleware' => ['permission:view_Client'], 'uses' => 'Admin\\ClientController@getDetailClient'])->name('detail_client');
  Route::get('/delete/{id}', ['middleware' => ['permission:delete_Client'], 'uses' => 'Admin\\ClientController@getXoaClient'])->name('delete_client');
  Route::get('/changeStatus/{id}', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@changeStatus'])->name('changeStatus_client');
  Route::get('/grant/{id}', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@getGrantClient'])->name('grant_client');
  Route::post('/grant/{id}', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@postGrantClient'])->name('grant_client');

  Route::get('/list-dai-ly', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@getListDaiLy'])->name('list_dai_ly');
  Route::get('/add-dai-ly', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@getAddDaiLy'])->name('add_dai_ly');
  Route::post('/add-dai-ly', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@postAddDaiLy'])->name('add_dai_ly');
  Route::get('/delete-dai-ly/{id}', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@getDeleteDaiLy'])->name('delete_dai_ly');

  Route::get('/lock_dai_ly/{id}', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@getLockDaily'])->name('lock_dai_ly');
  Route::get('/unlock_dai_ly/{id}', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@getUnlockDaily'])->name('unlock_dai_ly');

  Route::get('/detail-dai-ly/{id}/{type}', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@getDetailDaily'])->name('detail_dai_ly');

  Route::get('/detail-ctv/{id}/{type}', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@getDetailCTV'])->name('detail_ctv');


  Route::get('/search_user_add_daily', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@getSearchUserAddDaily'])->name('search_user_add_daily');

  
  Route::post('/loadRole', ['uses' => 'Admin\\ClientController@loadRole']);
  

  Route::get('/area/{id}', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@getArea'])->name('client_area');
  Route::post('/area/{id}', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@postArea'])->name('client_area');


  Route::get('/lock_ctv/{code}/{id}', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@getLockCTV'])->name('lock_ctv');
  Route::get('/unlock_ctv/{code}/{id}', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@getUnlockCTV'])->name('unlock_ctv');
  


  Route::get('/list-ctv/{code}', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@getListCTV'])->name('list_ctv');
  Route::get('/add-ctv/{code}', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@getAddCTV'])->name('add_ctv');
  Route::post('/add-ctv/{code}', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@postAddCTV'])->name('add_ctv');
  Route::get('/remove-ctv/{code}/{id}', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@getRemoveCTV'])->name('remove_ctv');
  Route::get('/accept-ctv/{code}/{id}', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@getAcceptCTV'])->name('accept_ctv');
  Route::get('/decline-ctv/{code}/{id}', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@getDeclineCTV'])->name('decline_ctv');
  
  Route::get('/update-ctv','Admin\\ClientController@updateIDCTV');
  Route::get('/find-ctv','Admin\\ClientController@findCTV')->name('find_ctv');

  Route::get('/move-ctv','Admin\\ClientController@getMoveCTV')->name('move_ctv');
  Route::get('/get-ctv/{daily_client_id}','Admin\\ClientController@getCTV')->name('get_ctv');
  Route::post('/move-ctv','Admin\\ClientController@postMoveCTV');

  Route::get('/setup-client','Admin\\ClientController@getSetupClient')->name('setup_client');
  Route::get('change-rate/{id}/{rate}', ['middleware' => ['permission:edit_Client'], 'uses' => 'Admin\\ClientController@getChangeRateClient']);

  Route::get('/setting-make-money','Admin\\MakeMoneySettingController@getIndex')->name('setting_make_money');
});

Route::group(['prefix' => 'client_role'], function () {
  Route::any('/{group_id}',['middleware' => ['permission:view_ClientRole'], 'uses' => 'Admin\\ClientRoleController@getListClientRole'])->name('list_client_role');

  Route::get('/{group_id}/add',['middleware' => ['permission:add_ClientRole'], 'uses' => 'Admin\\ClientRoleController@getAddClientRole'])->name('add_client_role');
  Route::post('/{group_id}/add',['middleware' => ['permission:add_ClientRole'], 'uses' => 'Admin\\ClientRoleController@postAddClientRole']);

  Route::get('/{group_id}/update/{id}',['middleware' => ['permission:edit_ClientRole'], 'uses' => 'Admin\\ClientRoleController@getUpdateClientRole'])->name('update_client_role');
  Route::post('/{group_id}/update/{id}',['middleware' => ['permission:edit_ClientRole'], 'uses' => 'Admin\\ClientRoleController@postUpdateClientRole']);

  Route::get('/{group_id}/delete/{id}',['middleware' => ['permission:delete_ClientRole'], 'uses' => 'Admin\\ClientRoleController@getDeleteClientRole'])->name('delete_client_role');

  // Route::get('grant/{id}',['middleware' => ['permission:edit_ClientRole'], 'uses' => 'Admin\\ClientRoleController@getGrantClientRole'])->name('grant_client_role');
  // Route::post('grant/{id}',['middleware' => ['permission:edit_ClientRole'], 'uses' => 'Admin\\ClientRoleController@postGrantClientRole']);
});

Route::group(['prefix' => 'client_group'], function () {
  Route::any('/',['middleware' => ['permission:view_ClientGroup'], 'uses' => 'Admin\\ClientGroupController@getListClientGroup'])->name('list_client_group');

  Route::get('add',['middleware' => ['permission:add_ClientGroup'], 'uses' => 'Admin\\ClientGroupController@getAddClientGroup'])->name('add_client_group');
  Route::post('add',['middleware' => ['permission:add_ClientGroup'], 'uses' => 'Admin\\ClientGroupController@postAddClientGroup']);

  Route::get('update/{id}',['middleware' => ['permission:edit_ClientGroup'], 'uses' => 'Admin\\ClientGroupController@getUpdateClientGroup'])->name('update_client_group');
  Route::post('update/{id}',['middleware' => ['permission:edit_ClientGroup'], 'uses' => 'Admin\\ClientGroupController@postUpdateClientGroup']);

  Route::get('delete/{id}',['middleware' => ['permission:delete_ClientGroup'], 'uses' => 'Admin\\ClientGroupController@getDeleteClientGroup'])->name('delete_client_group');
});

// Route::group(['prefix' => 'client_permission'], function () {


//   Route::any('/',['middleware' => ['permission:view_ClientPermission'], 'uses' => 'Admin\\ClientPermissionController@getListClientPermission'])->name('list_client_permission');

//   Route::get('add',['middleware' => ['permission:add_ClientPermission'], 'uses' => 'Admin\\ClientPermissionController@getAddClientPermission'])->name('add_client_permission');
//   Route::post('add',['middleware' => ['permission:add_ClientPermission'], 'uses' => 'Admin\\ClientPermissionController@postAddClientPermission']);

//   Route::get('update/{id}',['middleware' => ['permission:edit_ClientPermission'], 'uses' => 'Admin\\ClientPermissionController@getUpdateClientPermission'])->name('update_client_permission');
//   Route::post('update/{id}',['middleware' => ['permission:edit_ClientPermission'], 'uses' => 'Admin\\ClientPermissionController@postUpdateClientPermission']);

//   Route::get('delete/{id}',['middleware' => ['permission:delete_ClientPermission'], 'uses' => 'Admin\\ClientPermissionController@getDeleteClientPermission'])->name('delete_client_permission');

//   //Route::get('default', 'Admin\\ClientPermissionController@getDefaultClientPermission')->name('default_client_permission');
//   //Route::get('add-by-module/{module}', 'Admin\\ClientPermissionController@getAddByModuleClientPermission');
//   //Route::any('list-content/', 'Admin\\ClientPermissionController@getListContent');
// });



 // route group list manage ad.
Route::group(['prefix' => 'manage-ad','middleware' => ['role:super_admin|admin|admin_content']], function () {
  Route::any('/', ['uses' => 'Admin\\ManageAdController@anyIndex'])->name('list_manage_ad');
  Route::post('/update', ['uses' => 'Admin\\ManageAdController@postUpdate'])->name('update_manage_ad');
});

// Setting page
Route::group(['prefix' => 'setting'], function () {
  Route::any('/', ['middleware' => ['permission:view_Setting'], 'uses' => 'Admin\\SettingController@getIndex'])->name('list_setting');
  Route::post('save', ['middleware' => ['permission:edit_Setting'], 'uses' => 'Admin\\SettingController@postSaveSetting'])->name('save_setting');
});

// route category.
Route::group(['prefix' => 'template-notifi'], function () {
  Route::any('/', ['middleware' => ['permission:view_TemplateNotifi'], 'uses' => 'Admin\\TemplateNotifiController@getListTemplateNotifi'])->name('list_template_notifi');

  Route::get('add', ['middleware' => ['permission:add_TemplateNotifi'], 'uses' => 'Admin\\TemplateNotifiController@getAddTemplateNotifi'])->name('add_template_notifi');

  Route::post('add', 'Admin\\TemplateNotifiController@postAddTemplateNotifi');

  Route::get('update/{id}', ['middleware' => ['permission:edit_TemplateNotifi'], 'uses' => 'Admin\\TemplateNotifiController@getUpdateTemplateNotifi'])->name('update_template_notifi');
  Route::post('update/{id}', 'Admin\\TemplateNotifiController@postUpdateTemplateNotifi');

  Route::get('translate/{id}/{lang}', ['middleware' => ['permission:edit_TemplateNotifi'], 'uses' => 'Admin\\TemplateNotifiController@getTranslateTemplateNotifi'])->name('translate_template_notifi');
  Route::post('translate/{id}/{lang}', 'Admin\\TemplateNotifiController@postTranslateTemplateNotifi');

  Route::get('delete/{id}', ['middleware' => ['permission:delete_TemplateNotifi'], 'uses' => 'Admin\\TemplateNotifiController@getDeleteTemplateNotifi'])->name('delete_template_notifi');
});

// route category.
Route::group(['prefix' => 'category'], function () {

  Route::any('/', ['middleware' => ['permission:view_Category'], 'uses' => 'Admin\\CategoryController@getListCategory'])->name('list_category');
  Route::get('add', ['middleware' => ['permission:add_Category'], 'uses' => 'Admin\\CategoryController@getAddCategory'])->name('add_category');
  Route::post('add', 'Admin\\CategoryController@postAddCategory');

  Route::get('update/{id}', ['middleware' => ['permission:edit_Category'], 'uses' => 'Admin\\CategoryController@getUpdateCategory'])->name('update_category');
  Route::post('update/{id}', 'Admin\\CategoryController@postUpdateCategory');
  Route::get('down-weight/{id}', ['middleware' => ['permission:edit_Category'], 'uses' => 'Admin\\CategoryController@getDownCategory']);
  Route::get('up-weight/{id}', ['middleware' => ['permission:edit_Category'], 'uses' => 'Admin\\CategoryController@getUpCategory']);
  Route::get('change-weight/{id}/{weight}', ['middleware' => ['permission:edit_Category'], 'uses' => 'Admin\\CategoryController@getChangeWeightCategory']);

  Route::get('delete/{id}', ['middleware' => ['permission:delete_Category'], 'uses' => 'Admin\\CategoryController@getDeleteCategory'])->name('delete_category');

  Route::get('approve', ['middleware' => ['permission:edit_Category'], 'uses' => 'Admin\\CategoryController@getListApproveCategory'])->name('list_approve_category');
  Route::get('approve/{id}', 'Admin\\CategoryController@getApproveCategory')->name('approve_category');

  Route::get('move', ['middleware' => ['permission:edit_Category'], 'uses' => 'Admin\\CategoryController@getMoveCategory'])->name('move_category');

  Route::post('move', ['middleware' => ['permission:edit_Category'], 'uses' => 'Admin\\CategoryController@postMoveCategory'])->name('move_category');
});

// route category.
Route::group(['prefix' => 'block-text'], function () {

  Route::any('/', ['middleware' => ['permission:view_BlockText'], 'uses' => 'Admin\\BlockTextController@getListBlockText'])->name('list_block_text');
  Route::get('add', ['middleware' => ['permission:add_BlockText'], 'uses' => 'Admin\\BlockTextController@getAddBlockText'])->name('add_block_text');
  Route::post('add', 'Admin\\BlockTextController@postAddBlockText');

  Route::get('update/{id}', ['middleware' => ['permission:edit_BlockText'], 'uses' => 'Admin\\BlockTextController@getUpdateBlockText'])->name('update_block_text');
  Route::post('update/{id}', 'Admin\\BlockTextController@postUpdateBlockText');
 

  Route::get('delete/{id}', ['middleware' => ['permission:delete_BlockText'], 'uses' => 'Admin\\BlockTextController@getDeleteBlockText'])->name('delete_block_text');


});

// route category.
Route::group(['prefix' => 'module'], function () {

  Route::any('/', ['middleware' => ['permission:view_ModuleApp'], 'uses' => 'Admin\\ModuleAppController@getListModuleApp'])->name('list_module_app');
  Route::get('add', ['middleware' => ['permission:add_ModuleApp'], 'uses' => 'Admin\\ModuleAppController@getAddModuleApp'])->name('add_module_app');
  Route::post('add', 'Admin\\ModuleAppController@postAddModuleApp');

  Route::get('update/{id}', ['middleware' => ['permission:edit_ModuleApp'], 'uses' => 'Admin\\ModuleAppController@getUpdateModuleApp'])->name('update_module_app');
  Route::post('update/{id}', 'Admin\\ModuleAppController@postUpdateModuleApp');
  Route::get('down-weight/{id}', ['middleware' => ['permission:edit_ModuleApp'], 'uses' => 'Admin\\ModuleAppController@getDownModuleApp']);
  Route::get('up-weight/{id}', ['middleware' => ['permission:edit_ModuleApp'], 'uses' => 'Admin\\ModuleAppController@getUpModuleApp']);
  Route::get('change-weight/{id}/{weight}', ['middleware' => ['permission:edit_ModuleApp'], 'uses' => 'Admin\\ModuleAppController@getChangeWeightModuleApp']);

  Route::get('delete/{id}', ['middleware' => ['permission:delete_ModuleApp'], 'uses' => 'Admin\\ModuleAppController@getDeleteModuleApp'])->name('delete_module_app');
});

// route category item.
Route::group(['prefix' => 'category_item'], function () {
  Route::any('/',function() {
    return redirect()->route('list_category');
  });
  Route::any('/{category_id}', ['middleware' => ['permission:view_CategoryItem'], 'uses' => 'Admin\\CategoryItemController@getListCategoryItem'])->name('list_category_item')->where(['category_id' => '[0-9]+']);

  Route::get('add/{category_id}',['middleware' => ['permission:add_CategoryItem'], 'uses' => 'Admin\\CategoryItemController@getAddCategoryItem'])->name('add_category_item')->where(['category_id' => '[0-9]+']);
  Route::post('add/{category_id}',['middleware' => ['permission:add_CategoryItem'], 'uses' => 'Admin\\CategoryItemController@postAddCategoryItem'])->name('create_category_item')->where(['category_id' => '[0-9]+']);

  Route::get('update/{category_id}/{id}',['middleware' => ['permission:edit_CategoryItem'], 'uses' => 'Admin\\CategoryItemController@getUpdateCategoryItem'])->name('update_category_item')->where(['category_id' => '[0-9]+']);
  Route::post('update/{category_id}/{id}',['middleware' => ['permission:edit_CategoryItem'], 'uses' => 'Admin\\CategoryItemController@postUpdateCategoryItem'])->name('save_category_item')->where(['category_id' => '[0-9]+']);

  Route::get('down-weight/{category_id}/{id}', ['middleware' => ['permission:edit_CategoryItem'], 'uses' => 'Admin\\CategoryItemController@getDownCategory']);
  Route::get('up-weight/{category_id}/{id}', ['middleware' => ['permission:edit_CategoryItem'], 'uses' => 'Admin\\CategoryItemController@getUpCategory']);

  Route::get('change-weight/{category_id}/{id}/{weight}', ['middleware' => ['permission:edit_CategoryItem'], 'uses' => 'Admin\\CategoryItemController@getChangeWeightCategory']);

  Route::get('delete/{category_id}/{id}',['middleware' => ['permission:delete_CategoryItem'], 'uses' => 'Admin\\CategoryItemController@getDeleteCategoryItem'])->name('delete_category_item')->where(['category_id' => '[0-9]+']);

  Route::get('{category_id}/approve', ['middleware' => ['permission:edit_CategoryItem'], 'uses' => 'Admin\\CategoryItemController@getListApproveCategoryItem'])->name('list_approve_category_item');

  Route::get('{category_id}/approve/{id}', 'Admin\\CategoryItemController@getApproveCategoryItem')->name('approve_category_item');
});

// route category service.
Route::group(['prefix' => 'category_service'], function () {
  Route::any('/',function() {
    return redirect()->route('list_category');
  });
  Route::get('/{category_id}', ['middleware' => ['permission:edit_Category'], 'uses' => 'Admin\\ServiceItemController@getListCategoryService'])->name('list_category_service')->where(['category_id' => '[0-9]+']);
  Route::post('/{category_id}', ['middleware' => ['permission:edit_Category'], 'uses' => 'Admin\\ServiceItemController@postListCategoryService'])->where(['category_id' => '[0-9]+']);
});

// route location.
Route::group(['prefix' => 'location'], function () {

  Route::any('/',function() {
    return redirect()->route('list_country');
  });

  // START route country.
  Route::any('country',['middleware' => ['permission:view_Location'], 'uses' => 'Admin\\LocationController@getListCountry'])->name('list_country');

  Route::get('country/add',['middleware' => ['permission:add_Location'], 'uses' => 'Admin\\LocationController@getAddCountry'])->name('add_country');
  Route::post('country/add',['middleware' => ['permission:add_Location'], 'uses' => 'Admin\\LocationController@postAddCountry']);

  Route::get('country/update/{id}',['middleware' => ['permission:edit_Location'], 'uses' => 'Admin\\LocationController@getUpdateCountry'])->name('update_country');
  Route::post('country/update/{id}',['middleware' => ['permission:edit_Location'], 'uses' => 'Admin\\LocationController@postUpdateCountry']);


  Route::get('country1/add',['middleware' => ['permission:add_Location'], 'uses' => 'Admin\\LocationController@getAddCountry1'])->name('add_country1');
  Route::post('country1/add',['middleware' => ['permission:add_Location'], 'uses' => 'Admin\\LocationController@postAddCountry1']);

  Route::get('country1/update/{id}',['middleware' => ['permission:edit_Location'], 'uses' => 'Admin\\LocationController@getUpdateCountry1'])->name('update_country1');
  Route::post('country1/update/{id}',['middleware' => ['permission:edit_Location'], 'uses' => 'Admin\\LocationController@postUpdateCountry1']);


  Route::get('country/delete/{id}',['middleware' => ['permission:delete_Location'], 'uses' => 'Admin\\LocationController@getDeleteCountry'])->name('delete_country');
  // END route country.

  // START route city.
  Route::any('/city', function() {
    return redirect()->route('list_country');
  });
  Route::any('city/{id}',['middleware' => ['permission:view_Location'], 'uses' => 'Admin\\LocationController@getListCityCondition'])->name('list_city');

  Route::get('city/{id}/add',['middleware' => ['permission:add_Location'], 'uses' => 'Admin\\LocationController@getAddCity'])->name('add_city');
  Route::post('city/{id}/add',['middleware' => ['permission:add_Location'], 'uses' => 'Admin\\LocationController@postAddCity']);

  Route::get('city/{id}/update/{id_city}',['middleware' => ['permission:edit_Location'], 'uses' => 'Admin\\LocationController@getUpdateCity'])->name('update_city');
  Route::post('city/{id}/update/{id_city}',['middleware' => ['permission:edit_Location'], 'uses' => 'Admin\\LocationController@postUpdateCity']);

  Route::get('city1/{id}/add',['middleware' => ['permission:add_Location'], 'uses' => 'Admin\\LocationController@getAddCity1'])->name('add_city1');
  Route::post('city1/{id}/add',['middleware' => ['permission:add_Location'], 'uses' => 'Admin\\LocationController@postAddCity1']);

  Route::get('city1/{id}/update/{id_city}',['middleware' => ['permission:edit_Location'], 'uses' => 'Admin\\LocationController@getUpdateCity1'])->name('update_city1');
  Route::post('city1/{id}/update/{id_city}',['middleware' => ['permission:edit_Location'], 'uses' => 'Admin\\LocationController@postUpdateCity1']);

  Route::get('city/{id}/delete/{id_city}',['middleware' => ['permission:delete_Location'], 'uses' => 'Admin\\LocationController@getDeleteCity'])->name('delete_city');
  // END route city.

  // START route district.
  Route::any('/district', function() {
    return redirect()->route('list_city');
  });
  Route::any('district/{id}',['middleware' => ['permission:view_Location'], 'uses' => 'Admin\\LocationController@getListDistrictCondition'])->name('list_district');

  Route::get('district/{id}/add',['middleware' => ['permission:add_Location'], 'uses' => 'Admin\\LocationController@getAddDistrict'])->name('add_district');
  Route::post('district/{id}/add',['middleware' => ['permission:add_Location'], 'uses' => 'Admin\\LocationController@postAddDistrict']);

  Route::get('district/{id}/update/{id_district}',['middleware' => ['permission:edit_Location'], 'uses' => 'Admin\\LocationController@getUpdateDistrict'])->name('update_district');
  Route::post('district/{id}/update/{id_district}',['middleware' => ['permission:edit_Location'], 'uses' => 'Admin\\LocationController@postUpdateDistrict']);

  Route::get('district1/{id}/add',['middleware' => ['permission:add_Location'], 'uses' => 'Admin\\LocationController@getAddDistrict1'])->name('add_district1');
  Route::post('district1/{id}/add',['middleware' => ['permission:add_Location'], 'uses' => 'Admin\\LocationController@postAddDistrict1']);

  Route::get('district1/{id}/update/{id_district}',['middleware' => ['permission:edit_Location'], 'uses' => 'Admin\\LocationController@getUpdateDistrict1'])->name('update_district1');
  Route::post('district1/{id}/update/{id_district}',['middleware' => ['permission:edit_Location'], 'uses' => 'Admin\\LocationController@postUpdateDistrict1']);

  Route::get('district/{id}/delete/{id_district}',['middleware' => ['permission:delete_Location'], 'uses' => 'Admin\\LocationController@getDeleteDistrict'])->name('delete_district');
  // END route city.


  // Add District Lớn
  Route::get('import-district/{id_city}',['middleware' => ['role:super_admin'],'uses' => 'Admin\\LocationController@getImportDistrict']);
  Route::post('import-district/{id_city}',['middleware' => ['role:super_admin'],'uses' => 'Admin\\LocationController@postImportDistrict'])->name('import_district');

  Route::get('country/change-weight/{id}/{weight}', ['middleware' => ['permission:edit_Location'], 'uses' => 'Admin\\LocationController@getChangeWeightCountry']);

  Route::get('city/change-weight/{country_id}/{id}/{weight}', ['middleware' => ['permission:edit_Location'], 'uses' => 'Admin\\LocationController@getChangeWeightCity']);

  Route::get('district/change-weight/{city_id}/{id}/{weight}', ['middleware' => ['permission:edit_Location'], 'uses' => 'Admin\\LocationController@getChangeWeightDistrict']);
});

// route content type.
Route::group(['prefix' => 'content_type'], function () {
  Route::any('/',['middleware' => ['permission:view_ContentType'], 'uses' => 'Admin\\ContentTypeController@getListContentType'])->name('list_content_type');

  Route::get('add',['middleware' => ['permission:add_ContentType'], 'uses' => 'Admin\\ContentTypeController@getAddContentType'])->name('add_content_type');
  Route::post('add',['middleware' => ['permission:add_ContentType'], 'uses' => 'Admin\\ContentTypeController@postAddContentType']);

  Route::get('update/{id}',['middleware' => ['permission:edit_ContentType'], 'uses' => 'Admin\\ContentTypeController@getUpdateContentType'])->name('update_content_type')->where(['id' => '[0-9]+']);
  Route::post('update/{id}',['middleware' => ['permission:edit_ContentType'], 'uses' => 'Admin\\ContentTypeController@postUpdateContentType'])->where(['id' => '[0-9]+']);

  Route::get('delete/{id}',['middleware' => ['permission:delete_ContentType'], 'uses' => 'Admin\\ContentTypeController@getDeleteContentType'])->name('delete_content_item')->where(['id' => '[0-9]+']);
});


// route service item.
Route::group(['prefix' => 'service_item'], function () {
  Route::get('/',['middleware' => ['permission:view_ServiceItem'], 'uses' => 'Admin\\ServiceItemController@getListServiceItem'])->name('list_service_item');

  Route::get('add',['middleware' => ['permission:add_ServiceItem'], 'uses' => 'Admin\\ServiceItemController@getAddServiceItem'])->name('add_service_item');
  Route::post('add',['middleware' => ['permission:add_ServiceItem'], 'uses' => 'Admin\\ServiceItemController@postAddServiceItem']);

  Route::get('update/{id}',['middleware' => ['permission:edit_ServiceItem'], 'uses' => 'Admin\\ServiceItemController@getUpdateServiceItem'])->name('update_service_item')->where(['id' => '[0-9]+']);
  Route::post('update/{id}',['middleware' => ['permission:edit_ServiceItem'], 'uses' => 'Admin\\ServiceItemController@postUpdateServiceItem'])->where(['id' => '[0-9]+']);

  Route::get('approve', ['middleware' => ['permission:edit_ServiceItem'], 'uses' => 'Admin\\ServiceItemController@getListApproveServiceItem'])->name('list_approve_service_item');
  Route::get('approve/{id}', 'Admin\\ServiceItemController@getApproveServiceItem')->name('approve_service_item');


});

// route permission
Route::group(['prefix' => 'permission'], function () {


  Route::any('/',['middleware' => ['permission:view_Permission'], 'uses' => 'Admin\\PermissionController@getListPermission'])->name('list_permission');

  Route::get('add',['middleware' => ['permission:add_Permission'], 'uses' => 'Admin\\PermissionController@getAddPermission'])->name('add_permission');
  Route::post('add',['middleware' => ['permission:add_Permission'], 'uses' => 'Admin\\PermissionController@postAddPermission']);

  Route::get('update/{id}',['middleware' => ['permission:edit_Permission'], 'uses' => 'Admin\\PermissionController@getUpdatePermission'])->name('update_permission');
  Route::post('update/{id}',['middleware' => ['permission:edit_Permission'], 'uses' => 'Admin\\PermissionController@postUpdatePermission']);

  Route::get('delete/{id}',['middleware' => ['permission:delete_Permission'], 'uses' => 'Admin\\PermissionController@getDeletePermission'])->name('delete_permission');

  Route::get('default', 'Admin\\PermissionController@getDefaultPermission')->name('default_permission');
  Route::get('add-by-module/{module}', 'Admin\\PermissionController@getAddByModulePermission');
  Route::any('list-content/', 'Admin\\PermissionController@getListContent');
});

// route role
Route::group(['prefix' => 'role'], function () {
  Route::any('/',['middleware' => ['permission:view_Role'], 'uses' => 'Admin\\RoleController@getListRole'])->name('list_role');

  Route::get('add',['middleware' => ['permission:add_Role'], 'uses' => 'Admin\\RoleController@getAddRole'])->name('add_role');
  Route::post('add',['middleware' => ['permission:add_Role'], 'uses' => 'Admin\\RoleController@postAddRole']);

  Route::get('update/{id}',['middleware' => ['permission:edit_Role'], 'uses' => 'Admin\\RoleController@getUpdateRole'])->name('update_role');
  Route::post('update/{id}',['middleware' => ['permission:edit_Role'], 'uses' => 'Admin\\RoleController@postUpdateRole']);

  Route::get('delete/{id}',['middleware' => ['permission:delete_Role'], 'uses' => 'Admin\\RoleController@getDeleteRole'])->name('delete_role');

  Route::get('grant/{id}',['middleware' => ['permission:edit_Role'], 'uses' => 'Admin\\RoleController@getGrantRole'])->name('grant_role');
  Route::post('grant/{id}',['middleware' => ['permission:edit_Role'], 'uses' => 'Admin\\RoleController@postGrantRole']);
});

// route Menu
Route::group(['prefix' => 'menu','middleware' => ['permission:view_Menu']], function () {
  Route::any('/', 'Admin\\MenuController@getListMenu')->name('list_menu');

  Route::post('update', 'Admin\\MenuController@postUpdateMenu')->name('update_menu');
  Route::post('reorder', 'Admin\\MenuController@postReorderMenu')->name('reorder_menu');
  Route::get('delete/{id}', 'Admin\\MenuController@getDeleteMenu')->name('delete_menu');
});

// route Language
Route::group(['prefix' => 'language'], function () {
  Route::any('/',['middleware' => ['permission:view_Language'], 'uses' => 'Admin\\LanguageController@getIndex'])->name('list_language');
  Route::any('/save',['middleware' => ['permission:edit_Language'], 'uses' => 'Admin\\LanguageController@getSave'])->name('save_language');
});

// route Content
Route::group(['prefix' => 'content'], function () {
  Route::get('/',['middleware' => ['permission:view_Content'], 'uses' => 'Admin\\ContentController@getListContent'])->name('list_content');

  Route::get('add/{category_type}',['middleware' => ['permission:add_Content'], 'uses' => 'Admin\\ContentController@getAddContent'])->name('add_content');

  Route::get('import/{category_type}',['middleware' => ['permission:add_Content'], 'uses' => 'Admin\\ContentController@getImportContent'])->name('import_content');

  Route::get('notify_content/{id}', 'Admin\\ContentController@getNotifyContent')->name('notify_content')->where(['id' => '[0-9]+']);
  Route::post('notify_content/{id}', 'Admin\\ContentController@postNotifyContent');

  Route::get('update/{category_type}/{id}',['middleware' => ['permission:edit_Content'], 'uses' => 'Admin\\ContentController@getUpdateContent'])->name('update_content');

    Route::get('update_category/{id}',['middleware' => ['permission:edit_Content'], 'uses' => 'Admin\\ContentController@getUpdateCategoryContent'])->name('update_category_of_content');
    Route::get('ajaxListService/{id}', 'Admin\\ContentController@getListService');
    Route::post('post_update_category/{id}',['middleware' => ['permission:edit_Content'], 'uses' => 'Admin\\ContentController@postUpdateCategoryContent'])->name('post_update_category_of_content');

    Route::post('updateLike',['middleware' => ['permission:edit_Content'], 'uses' => 'Admin\\ContentController@updateLike']);

  Route::get('delete/{id}',['middleware' => ['permission:delete_Content'], 'uses' => 'Admin\\ContentController@getDeleteContent'])->name('delete_content')->where(['id' => '[0-9]+']);

  Route::get('note/{id}', 'Admin\\ContentController@getNoteContent')->name('note_content')->where(['id' => '[0-9]+']);
  Route::post('note/{id}', 'Admin\\ContentController@postNoteContent');

  Route::post('add/food', 'Admin\\ContentController@postAddFoodContent')->name('add_food_content');
  // Route::post('import/food', 'Admin\\ContentController@postImportFoodContent');
  // Route::post('import/drinks', 'Admin\\ContentController@postImportFoodContent');
  // Route::post('import/shop', 'Admin\\ContentController@postImportFoodContent');
  // Route::post('import/bank', 'Admin\\ContentController@postImportFoodContent');
  // Route::post('import/hotel', 'Admin\\ContentController@postImportFoodContent');

  Route::post('import/{category_type}', 'Admin\\ContentController@postImportFoodContent');


  
  Route::post('import/entertainment', 'Admin\\ContentController@postImportFoodContent');
  Route::get('migrate', 'Admin\\ContentController@getMigrate');

  Route::post('update/food/{id}', 'Admin\\ContentController@postUpdateFoodContent')->name('update_food_content');

  Route::post('add/bank', 'Admin\\ContentController@postAddBankContent')->name('add_bank_content');
  Route::post('update/bank/{id}', 'Admin\\ContentController@postUpdateBankContent')->name('update_bank_content');

  Route::post('add/hotel', 'Admin\\ContentController@postAddHotelContent')->name('add_hotel_content');
  Route::post('update/hotel/{id}', 'Admin\\ContentController@postUpdateHotelContent')->name('update_hotel_content');

  Route::post('add/shop', 'Admin\\ContentController@postAddShopContent')->name('add_shop_content');
  Route::post('update/shop/{id}', 'Admin\\ContentController@postUpdateShopContent')->name('update_shop_content');

  Route::post('change_status', 'Admin\\ContentController@changeStatus')->name('changeStatus');

  Route::post('deleteImg', 'Admin\\ContentController@postDeleteImg');
  Route::post('deleteProduct', 'Admin\\ContentController@postDeleteProduct');
  Route::post('deleteGroupProduct', 'Admin\\ContentController@postDeleteGroupProduct');

  Route::post('ajaxLocation', 'Admin\\ContentController@getAjaxLocation');
  Route::post('ajaxCategoryItem', 'Admin\\ContentController@getAjaxCategoryItem');

  Route::get('insert_content/{site}',['middleware' => ['permission:add_Content'], 'uses' => 'Admin\\ContentController@getInsertContent'])->name('insert_content');
  Route::post('insert_content/{site}','Admin\\ContentController@getImportContentTest');

  // Route::get('demotest', 'Admin\\AdminController@getDemoTestInsert');

  Route::get('update_location_foody','Admin\\ContentController@getUpdateLocationFoody')->name('update_location_foody');
  Route::post('update_location_foody','Admin\\ContentController@postUpdateLocationFoody');

  Route::post('ajax_post_amt','Admin\\ContentController@postAjaxAtm');

  // Route::get('change_owner/{id}', 'Admin\\ContentController@getChangeOwner')->name('change_owner')->where(['id' => '[0-9]+']);
  // Route::post('change_owner/{id}', 'Admin\\ContentController@postChangeOwner');

  Route::get('change_owner', 'Admin\\ContentController@getChangeOwnerNew')->name('change_owner');
  Route::post('change_owner', 'Admin\\ContentController@postChangeOwnerNew');

  Route::get('change_ctv', 'Admin\\ContentController@getChangeCTV')->name('change_ctv');
  Route::post('change_ctv', 'Admin\\ContentController@postChangeCTV');

  Route::get('search_daily_content', 'Admin\\ContentController@getSearchDailyContent')->name('search_daily_content');
  Route::get('search_ctv_content', 'Admin\\ContentController@getSearchCTVContent')->name('search_ctv_content');

  // Route::get('demo_test','Admin\\ContentController@demo_test');

  // Route::get('update_image_avatar','Admin\\ContentController@update_image_content');
  // Route::get('rename_image_avatar','Admin\\ContentController@rename_image_content');
  // Route::get('rename_image_thumbnail','Admin\\ContentController@rename_image_thumbnail');

  Route::get('update_location','Admin\\ContentController@getUpdateLocation')->name('update_location');
  Route::post('check_update_location','Admin\\ContentController@checkUpdateLocation');
  Route::post('update_location','Admin\\ContentController@postUpdateLocation');
  Route::get('update_tag', 'Admin\\ContentController@getUpdateTag')->name('update_tag');
  Route::post('update_tag', 'Admin\\ContentController@postUpdateTag');

  Route::get('category_more/{id_category}/{id_category_item}', 'Admin\\ContentController@getUpdateCategoryMore');

  Route::get('addidclient', 'Admin\\ContentController@addIdClientToContent');
  Route::get('updateidclient', 'Admin\\ContentController@updateIdClient');
  Route::get('updatedailycode', 'Admin\\ContentController@updateDailycode');
});

// route Email
Route::group(['prefix' => 'email_template'], function () {
  Route::any('/',['middleware' => ['permission:view_EmailTemplate'], 'uses' => 'Admin\\EmailTemplateController@getListEmailTemplate'])->name('list_email');

  Route::get('add',['middleware' => ['permission:add_EmailTemplate'], 'uses' => 'Admin\\EmailTemplateController@getAddEmailTemplate'])->name('add_email');
  Route::post('add',['middleware' => ['permission:add_EmailTemplate'], 'uses' => 'Admin\\EmailTemplateController@postAddEmailTemplate']);

  Route::get('update/{id}',['middleware' => ['permission:edit_EmailTemplate'], 'uses' => 'Admin\\EmailTemplateController@getUpdateEmailTemplate'])->name('update_email');
  Route::post('update/{id}',['middleware' => ['permission:edit_EmailTemplate'], 'uses' => 'Admin\\EmailTemplateController@postUpdateEmailTemplate']);

  Route::get('delete/{id}',['middleware' => ['permission:delete_EmailTemplate'], 'uses' => 'Admin\\EmailTemplateController@getDeleteEmailTemplate'])->name('delete_email');
});


Route::group(['prefix' => 'notifi'], function () {
  Route::get('/',['middleware' => ['permission:view_Notifi'], 'uses' => 'Admin\\NotifiController@getListNotifi'])->name('list_notifi');

  Route::get('add',['middleware' => ['permission:add_Notifi'], 'uses' => 'Admin\\NotifiController@getAddNotifi'])->name('add_notifi');
  Route::post('add',['middleware' => ['permission:add_Notifi'], 'uses' => 'Admin\\NotifiController@postAddNotifi']);

  Route::get('update/{id}',['middleware' => ['permission:edit_Notifi'], 'uses' => 'Admin\\NotifiController@getUpdateNotifi'])->name('update_notifi');
  Route::post('update/{id}',['middleware' => ['permission:edit_Notifi'], 'uses' => 'Admin\\NotifiController@postUpdateNotifi']);
  Route::get('delete/{id}',['middleware' => ['permission:delete_Notifi'], 'uses' => 'Admin\\NotifiController@getDeleteNotifi'])->name('delete_notifi');
  Route::get('setting/{id}', 'Admin\\NotifiController@getSettingNotifi')->name('setting_notifi');
  Route::post('setting/{id}','Admin\\NotifiController@postSettingNotifi');

});

// route Group
Route::group(['prefix' => 'group'], function () {
  Route::any('',['middleware' => ['permission:view_Group'], 'uses' => 'Admin\\GroupController@getListGroup'])->name('list_group');

  Route::get('add',['middleware' => ['permission:add_Group'], 'uses' => 'Admin\\GroupController@getAddGroup'])->name('add_group');
  Route::post('add',['middleware' => ['permission:add_Group'], 'uses' => 'Admin\\GroupController@postAddGroup']);

  Route::get('update/{id}',['middleware' => ['permission:edit_Group'], 'uses' => 'Admin\\GroupController@getUpdateGroup'])->name('update_group');
  Route::post('update/{id}',['middleware' => ['permission:edit_Group'], 'uses' => 'Admin\\GroupController@postUpdateGroup']);

  Route::get('delete/{id}',['middleware' => ['permission:delete_Group'], 'uses' => 'Admin\\GroupController@getDeleteGroup'])->name('delete_group');
});

// route custom page
Route::group(['prefix' => 'custom_page'], function () {
  Route::get('/',['middleware' => ['permission:view_CustomPage'], 'uses' => 'Admin\\CustomPageController@getListCustomPage'])->name('list_custom_page');

  Route::get('add',['middleware' => ['permission:add_CustomPage'], 'uses' => 'Admin\\CustomPageController@getAddCustomPage'])->name('add_custom_page');
  Route::post('add',['middleware' => ['permission:add_CustomPage'], 'uses' => 'Admin\\CustomPageController@postAddCustomPage']);

  Route::get('update/{id}',['middleware' => ['permission:edit_CustomPage'], 'uses' => 'Admin\\CustomPageController@getUpdateCustomPage'])->name('update_custom_page');
  Route::post('update/{id}',['middleware' => ['permission:edit_CustomPage'], 'uses' => 'Admin\\CustomPageController@postUpdateCustomPage']);

  Route::get('{id}/{lang}',['middleware' => ['permission:edit_CustomPage'], 'uses' => 'Admin\\CustomPageController@getCustomPageLang'])->name('custom_page_lang');
  Route::post('{id}/{lang}',['middleware' => ['permission:edit_CustomPage'], 'uses' => 'Admin\\CustomPageController@postCustomPageLang']);

});

// route Error
Route::group(['prefix' => 'error'], function () {
  Route::any('/{code}', 'Admin\\ErrorController@anyIndex');
});

// route Error
Route::group(['prefix' => 'comment'], function () {
  Route::any('/', ['middleware' => ['permission:edit_Comment'], 'uses' => 'Admin\\CommentController@anyIndex'])->name('approve_comment');
  Route::any('/list', ['middleware' => ['permission:edit_Comment'], 'uses' => 'Admin\\CommentController@anyList'])->name('list_all_comment');
  Route::any('/approve/{comment_id}', ['middleware' => ['permission:edit_Comment'], 'uses' => 'Admin\\CommentController@anyApprove']);
  Route::any('/decline/{comment_id}', ['middleware' => ['permission:edit_Comment'], 'uses' => 'Admin\\CommentController@anyDecline']);
  Route::any('/delete/{comment_id}', ['middleware' => ['permission:delete_Comment'], 'uses' => 'Admin\\CommentController@anyDelete']);
});

// route suggest.
Route::group(['prefix' => 'suggest'], function () {
  Route::any('/', ['middleware' => ['permission:view_Suggest'], 'uses' => 'Admin\\SuggestController@getListSuggest'])->name('list_suggest');
  Route::any('/list', ['middleware' => ['permission:view_Suggest'], 'uses' => 'Admin\\SuggestController@getListSuggest']);
  Route::get('add', ['middleware' => ['permission:add_Suggest'], 'uses' => 'Admin\\SuggestController@getAddSuggest'])->name('add_suggest');
  Route::post('add', 'Admin\\SuggestController@postAddSuggest');

  Route::post('deleteMulti', 'Admin\\SuggestController@postDeleteMultiSuggest');

  Route::get('update/{id}', ['middleware' => ['permission:edit_Suggest'], 'uses' => 'Admin\\SuggestController@getUpdateSuggest'])->name('update_suggest');
  Route::post('update/{id}', 'Admin\\SuggestController@postUpdateSuggest');
  Route::get('down-weight/{id}', ['middleware' => ['permission:edit_Suggest'], 'uses' => 'Admin\\SuggestController@getDownSuggest']);
  Route::get('up-weight/{id}', ['middleware' => ['permission:edit_Suggest'], 'uses' => 'Admin\\SuggestController@getUpSuggest']);
  Route::get('change-weight/{id}/{weight}', ['middleware' => ['permission:edit_Suggest'], 'uses' => 'Admin\\SuggestController@getChangeWeightSuggest']);
  Route::get('change-keyword/{id}/{keyword}', ['middleware' => ['permission:edit_Suggest'], 'uses' => 'Admin\\SuggestController@getChangeKeywordSuggest']);
  Route::get('delete/{id}', ['middleware' => ['permission:delete_Suggest'], 'uses' => 'Admin\\SuggestController@getDeleteSuggest'])->name('delete_suggest');
});

Route::get('/updateDataMakeMoney', 'Admin\\AdminController@updateDataMakeMoney');

Route::get('/migrateDataMakeMoney', 'Admin\\AdminController@migrateDataMakeMoney');

// route Error
Route::group(['prefix' => 'clone'], function () {
  Route::get('/thongtincongty', 'Admin\\CloneDataController@getThongTinCongTy')->name("clone_thongtincongty");
  Route::post('/thongtincongty', 'Admin\\CloneDataController@postThongTinCongTy');
  Route::post('/getPageThongTinCongTy', 'Admin\\CloneDataController@getPageThongTinCongTy');
  
});