<?php
Route::group(['prefix' => 'showroom', 'middleware' => 'checkactive'], function () {
	Route::any('/', 'Admin\\AdminController@getIndex')->name('index_showroom');

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

	Route::group(['prefix' => 'type'], function () {

	  Route::any('/', ['middleware' => ['permission:view_ProductType'], 'uses' => 'Admin\\ProductTypeController@getListProductType'])->name('list_product_type');
	  Route::get('add', ['middleware' => ['permission:add_ProductType'], 'uses' => 'Admin\\ProductTypeController@getAddProductType'])->name('add_product_type');
	  Route::post('add', 'Admin\\ProductTypeController@postAddProductType');

	  Route::get('update/{id}', ['middleware' => ['permission:edit_ProductType'], 'uses' => 'Admin\\ProductTypeController@getUpdateProductType'])->name('update_product_type');
	  Route::post('update/{id}', 'Admin\\ProductTypeController@postUpdateProductType');
	  Route::get('down-weight/{id}', ['middleware' => ['permission:edit_ProductType'], 'uses' => 'Admin\\ProductTypeController@getDownProductType']);
	  Route::get('up-weight/{id}', ['middleware' => ['permission:edit_ProductType'], 'uses' => 'Admin\\ProductTypeController@getUpProductType']);
	  Route::get('change-weight/{id}/{weight}', ['middleware' => ['permission:edit_ProductType'], 'uses' => 'Admin\\ProductTypeController@getChangeWeightProductType']);

	  Route::get('delete/{id}', ['middleware' => ['permission:delete_ProductType'], 'uses' => 'Admin\\ProductTypeController@getDeleteProductType'])->name('delete_product_type');
	});


});