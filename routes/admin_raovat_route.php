<?php
Route::group(['prefix' => 'raovat', 'middleware' => 'checkactive'], function () {
	Route::any('/', ['middleware' => ['permission:view_Raovat'], 'uses' => 'Admin\\RaovatController@getListRaovat'])->name('list_raovat');
	Route::group(['prefix' => 'raovat', 'middleware' => 'checkactive'], function () {

		Route::any('/', ['middleware' => ['permission:view_Raovat'], 'uses' => 'Admin\\RaovatController@getListRaovat'])->name('list_raovat');
		// Route::get('searchContent', ['uses' => 'Admin\\RaovatController@getSearchContent'])->name('search_content');
  
	  Route::get('add', ['middleware' => ['permission:add_Raovat'], 'uses' => 'Admin\\RaovatController@getAddRaovat'])->name('add_raovat');
	  Route::post('add', ['middleware' => ['permission:add_Raovat'], 'uses' => 'Admin\\RaovatController@postAddRaovat'])->name('add_raovat');

	  Route::get('update/{id}', ['middleware' => ['permission:edit_Raovat'], 'uses' => 'Admin\\RaovatController@getUpdateRaovat'])->name('update_raovat');
	  Route::post('update/{id}', ['middleware' => ['permission:edit_Raovat'], 'uses' => 'Admin\\RaovatController@postUpdateRaovat']);

	  Route::post('postDeleteImage', ['middleware' => ['permission:edit_Raovat'], 'uses' => 'Admin\\RaovatController@postDeleteImage']);
	  

	  Route::get('delete/{id}', ['middleware' => ['permission:delete_Raovat'], 'uses' => 'Admin\\RaovatController@getDeleteRaovat'])->name('delete_raovat');
	  
	  Route::get('getRaovat/{id}', [ 'uses' => 'Admin\\RaovatController@getRaovat']);
	  Route::get('approveRaovat/{id}', [ 'uses' => 'Admin\\RaovatController@getApproveRaovat']);
	  Route::post('declineRaovat', [ 'uses' => 'Admin\\RaovatController@postDeclineRaovat'])->name('decline_raovat');
	});
	
	Route::group(['prefix' => 'raovat_type'], function () {

	  Route::any('/', ['middleware' => ['permission:view_RaovatType'], 'uses' => 'Admin\\RaovatTypeController@getListRaovatType'])->name('list_raovat_type');
	  Route::get('add', ['middleware' => ['permission:add_RaovatType'], 'uses' => 'Admin\\RaovatTypeController@getAddRaovatType'])->name('add_raovat_type');
	  Route::post('add', 'Admin\\RaovatTypeController@postAddRaovatType');

	  Route::get('update/{id}', ['middleware' => ['permission:edit_RaovatType'], 'uses' => 'Admin\\RaovatTypeController@getUpdateRaovatType'])->name('update_raovat_type');
	  Route::post('update/{id}', 'Admin\\RaovatTypeController@postUpdateRaovatType');
	  Route::get('down-weight/{id}', ['middleware' => ['permission:edit_RaovatType'], 'uses' => 'Admin\\RaovatTypeController@getDownRaovatType']);
	  Route::get('up-weight/{id}', ['middleware' => ['permission:edit_RaovatType'], 'uses' => 'Admin\\RaovatTypeController@getUpRaovatType']);
	  Route::get('change-weight/{id}/{weight}', ['middleware' => ['permission:edit_RaovatType'], 'uses' => 'Admin\\RaovatTypeController@getChangeWeightRaovatType']);

	  Route::get('delete/{id}', ['middleware' => ['permission:delete_RaovatType'], 'uses' => 'Admin\\RaovatTypeController@getDeleteRaovatType'])->name('delete_raovat_type');
	});

	Route::group(['prefix' => 'raovat_subtype'], function () {
	  Route::any('/',function() {
	    return redirect()->route('list_raovat_type');
	  });
	  Route::any('/{raovat_type_id}', ['middleware' => ['permission:view_RaovatSubType'], 'uses' => 'Admin\\RaovatSubTypeController@getListRaovatSubType'])->name('list_raovat_subtype')->where(['raovat_type_id' => '[0-9]+']);

	  Route::get('add/{raovat_type_id}',['middleware' => ['permission:add_RaovatSubType'], 'uses' => 'Admin\\RaovatSubTypeController@getAddRaovatSubType'])->name('add_raovat_subtype')->where(['raovat_type_id' => '[0-9]+']);
	  Route::post('add/{raovat_type_id}',['middleware' => ['permission:add_RaovatSubType'], 'uses' => 'Admin\\RaovatSubTypeController@postAddRaovatSubType'])->name('create_raovat_subtype')->where(['raovat_type_id' => '[0-9]+']);

	  Route::get('update/{raovat_type_id}/{id}',['middleware' => ['permission:edit_RaovatSubType'], 'uses' => 'Admin\\RaovatSubTypeController@getUpdateRaovatSubType'])->name('update_raovat_subtype')->where(['raovat_type_id' => '[0-9]+']);
	  Route::post('update/{raovat_type_id}/{id}',['middleware' => ['permission:edit_RaovatSubType'], 'uses' => 'Admin\\RaovatSubTypeController@postUpdateRaovatSubType'])->name('save_raovat_subtype')->where(['raovat_type_id' => '[0-9]+']);

	  Route::get('down-weight/{raovat_type_id}/{id}', ['middleware' => ['permission:edit_RaovatSubType'], 'uses' => 'Admin\\RaovatSubTypeController@getDownCategory']);
	  Route::get('up-weight/{raovat_type_id}/{id}', ['middleware' => ['permission:edit_RaovatSubType'], 'uses' => 'Admin\\RaovatSubTypeController@getUpCategory']);

	  Route::get('change-weight/{raovat_type_id}/{id}/{weight}', ['middleware' => ['permission:edit_RaovatSubType'], 'uses' => 'Admin\\RaovatSubTypeController@getChangeWeightCategory']);

	  Route::get('delete/{raovat_type_id}/{id}',['middleware' => ['permission:delete_RaovatSubType'], 'uses' => 'Admin\\RaovatSubTypeController@getDeleteRaovatSubType'])->name('delete_raovat_subtype')->where(['raovat_type_id' => '[0-9]+']);

	  Route::get('{raovat_type_id}/approve', ['middleware' => ['permission:edit_RaovatSubType'], 'uses' => 'Admin\\RaovatSubTypeController@getListApproveRaovatSubType'])->name('list_approve_raovat_subtype');

	  Route::get('{raovat_type_id}/approve/{id}', 'Admin\\RaovatSubTypeController@getApproveRaovatSubType')->name('approve_raovat_subtype');
	});


	Route::group(['prefix' => 'module'], function () {

	  Route::any('/', ['middleware' => ['permission:view_Module'], 'uses' => 'Admin\\ModuleController@getListModule'])->name('list_module');
	  Route::get('add', ['middleware' => ['permission:add_Module'], 'uses' => 'Admin\\ModuleController@getAddModule'])->name('add_module');
	  Route::post('add', 'Admin\\ModuleController@postAddModule');

	  Route::get('update/{id}', ['middleware' => ['permission:edit_Module'], 'uses' => 'Admin\\ModuleController@getUpdateModule'])->name('update_module');
	  Route::post('update/{id}', 'Admin\\ModuleController@postUpdateModule');
	  Route::get('down-weight/{id}', ['middleware' => ['permission:edit_Module'], 'uses' => 'Admin\\ModuleController@getDownModule']);
	  Route::get('up-weight/{id}', ['middleware' => ['permission:edit_Module'], 'uses' => 'Admin\\ModuleController@getUpModule']);
	  Route::get('change-weight/{id}/{weight}', ['middleware' => ['permission:edit_Module'], 'uses' => 'Admin\\ModuleController@getChangeWeightModule']);

	  Route::get('delete/{id}', ['middleware' => ['permission:delete_Module'], 'uses' => 'Admin\\ModuleController@getDeleteModule'])->name('delete_module');
	});

	Route::group(['prefix' => 'category'], function () {

	  Route::any('/', ['middleware' => ['permission:view_RaovatCategory'], 'uses' => 'Admin\\RaovatCategoryController@getListRaovatCategory'])->name('list_raovat_category');
	  Route::get('add', ['middleware' => ['permission:add_RaovatCategory'], 'uses' => 'Admin\\RaovatCategoryController@getAddRaovatCategory'])->name('add_raovat_category');
	  Route::post('add', 'Admin\\RaovatCategoryController@postAddRaovatCategory');

	  Route::get('update/{id}', ['middleware' => ['permission:edit_RaovatCategory'], 'uses' => 'Admin\\RaovatCategoryController@getUpdateRaovatCategory'])->name('update_raovat_category');
	  Route::post('update/{id}', 'Admin\\RaovatCategoryController@postUpdateRaovatCategory');
	  Route::get('down-weight/{id}', ['middleware' => ['permission:edit_RaovatCategory'], 'uses' => 'Admin\\RaovatCategoryController@getDownRaovatCategory']);
	  Route::get('up-weight/{id}', ['middleware' => ['permission:edit_RaovatCategory'], 'uses' => 'Admin\\RaovatCategoryController@getUpRaovatCategory']);
	  Route::get('change-weight/{id}/{weight}', ['middleware' => ['permission:edit_RaovatCategory'], 'uses' => 'Admin\\RaovatCategoryController@getChangeWeightRaovatCategory']);

	  Route::get('delete/{id}', ['middleware' => ['permission:delete_RaovatCategory'], 'uses' => 'Admin\\RaovatCategoryController@getDeleteRaovatCategory'])->name('delete_raovat_category');
	});

	Route::group(['prefix' => 'category-item'], function () {
	  Route::any('/', ['middleware' => ['permission:view_RaovatCategoryItem'], 'uses' => 'Admin\\RaovatCategoryItemController@getListRaovatCategoryItem'])->name('list_raovat_category_item');
	  Route::get('add', ['middleware' => ['permission:add_RaovatCategoryItem'], 'uses' => 'Admin\\RaovatCategoryItemController@getAddRaovatCategoryItem'])->name('add_raovat_category_item');
	  Route::post('add', 'Admin\\RaovatCategoryItemController@postAddRaovatCategoryItem');

	  Route::get('update/{id}', ['middleware' => ['permission:edit_RaovatCategoryItem'], 'uses' => 'Admin\\RaovatCategoryItemController@getUpdateRaovatCategoryItem'])->name('update_raovat_category_item');
	  Route::post('update/{id}', 'Admin\\RaovatCategoryItemController@postUpdateRaovatCategoryItem');
	 

	  Route::get('delete/{id}', ['middleware' => ['permission:delete_RaovatCategoryItem'], 'uses' => 'Admin\\RaovatCategoryItemController@getDeleteRaovatCategoryItem'])->name('delete_raovat_category_item');
	});

	Route::group(['prefix' => 'module-category'], function () {

	  Route::any('/{module_id}', ['uses' => 'Admin\\ModuleCategoryController@getListModuleCategory'])->name('list_module_category');
	  Route::get('/{module_id}/add', ['middleware' => ['permission:add_Module'], 'uses' => 'Admin\\ModuleCategoryController@getAddModuleCategory'])->name('add_module_category');
	  Route::post('/{module_id}/add', 'Admin\\ModuleCategoryController@postAddModuleCategory');

	  Route::get('/{module_id}/update/{category_id}', ['middleware' => ['permission:edit_Module'], 'uses' => 'Admin\\ModuleCategoryController@getUpdateModuleCategory'])->name('update_module_category');
	  Route::post('/{module_id}/update/{category_id}', 'Admin\\ModuleCategoryController@postUpdateModuleCategory');

	  Route::get('/{module_id}/change-weight/{category_id}/{weight}', ['middleware' => ['permission:edit_Module'], 'uses' => 'Admin\\ModuleCategoryController@getChangeWeightModuleCategory']);

	  Route::get('/{module_id}/delete/{category_id}', ['middleware' => ['permission:delete_Module'], 'uses' => 'Admin\\ModuleCategoryController@getDeleteModuleCategory'])->name('delete_module_category');
	});

	Route::group(['prefix' => 'module-category-item'], function () {

	  Route::any('/{module_id}/{category_id}', ['uses' => 'Admin\\ModuleCategoryItemController@getListModuleCategoryItem'])->name('list_module_category_item');

	  Route::get('/{module_id}/{category_id}/add', ['middleware' => ['permission:add_Module'], 'uses' => 'Admin\\ModuleCategoryItemController@getAddModuleCategoryItem'])->name('add_module_category_item');
	  Route::post('/{module_id}/{category_id}/add', 'Admin\\ModuleCategoryItemController@postAddModuleCategoryItem');

	  Route::get('/{module_id}/{category_id}/update/{category_item_id}', ['middleware' => ['permission:edit_Module'], 'uses' => 'Admin\\ModuleCategoryItemController@getUpdateModuleCategoryItem'])->name('update_module_category_item');
	  Route::post('/{module_id}/{category_id}/update/{category_item_id}', 'Admin\\ModuleCategoryItemController@postUpdateModuleCategoryItem');

	  Route::get('/{module_id}/{category_id}/change-weight/{category_item_id}/{weight}', ['middleware' => ['permission:edit_Module'], 'uses' => 'Admin\\ModuleCategoryItemController@getChangeWeightModuleCategoryItem']);

	  Route::get('/{module_id}/{category_id}/delete/{category_item_id}', ['middleware' => ['permission:delete_Module'], 'uses' => 'Admin\\ModuleCategoryItemController@getDeleteModuleCategoryItem'])->name('delete_module_category_item');
	});
});