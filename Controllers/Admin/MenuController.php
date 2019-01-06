<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\Menu;
use Illuminate\Http\Request;
use App\Models\Location\Http\Requests;

class MenuController extends BaseController
{

	public function getListMenu() {
		$arrMenu = [
				'frontend'=>'',
				'backend'=>'',
		];
		$arrParent = [];
		$arrType = [];

		$arrMenu = Menu::getMenu();
		if(!$arrMenu){
			$arrMenu = [
				'frontend'=>'',
				'backend'=>'',
			];
		}

		foreach($arrMenu as $type => $html) {
				$arrMenu[$type] = '<ol class="dd-list">'.$html.'</ol>';
				$arrType[] = $type;
		}

		$arrParent = Menu::getParent();
		return view('Admin.menu.list', [
													'arrMenu'     => $arrMenu,
													'arrParent'   => $arrParent,
													'arrType'     => $arrType,
			]);
	}

	public function postUpdateMenu(Request $request) {
		$arrPost = $request->toArray();
		// dd($arrPost);
		$arrReturn = [
			'status' => 'error',
			'message' => ''
		];
		if(!empty($arrPost)){
			$permission = true;
			if(  $arrPost['id'] ) {
				$message = trans('valid.updated_successful');
				$menu = Menu::find($arrPost['id']);
			} else {
				$message = trans('valid.added_successful');
				$menu = new Menu;
			}
			$menu->name = $arrPost['name'];
			if($arrPost['type'] == 'backend'){
				$menu->icon_class = isset($arrPost['icon_class']) ? trim($arrPost['icon_class']) : '';
				$menu->module = isset($arrPost['module'])?$arrPost['module']:null;
			}
			
			if($arrPost['type'] == 'frontend'){
				$menu->icon_class = isset($arrPost['icon_class_fe']) ? trim($arrPost['icon_class_fe']) : '';
				if($request->file('image')) {
					$path = public_path().'/upload/menu/';
					$file = $request->file('image');
					if(!\File::exists($path)) {
						\File::makeDirectory($path, $mode = 0777, true, true);
					}
					$name =time(). '.' . $file->getClientOriginalExtension();
					if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0){
						$image = crop_image($file,100,100);
						$image->save($path.$name);
					}else{
						if($file->getClientOriginalExtension() === 'svg'){
							$file->move($path,$name);
						}
					}
					$menu->icon_img = '/upload/menu/'.$name;
				}
				$menu->module = null;
			}
			

			if( empty($menu->icon_class) )
				$menu->icon_class = 'fa-cog';
			$menu->link = '/'.rtrim(ltrim($arrPost['link'], '/'), '/');
			$menu->type = $arrPost['type'];
			$menu->language = $arrPost['language'];
			$menu->parent_id = !$arrPost['parent_id'] ? 0 : $arrPost['parent_id'];
			$menu->weight = (int)$arrPost['weight'];
			$menu->active = isset($arrPost['active']) && (int)$arrPost['active'] ? 1 : 0;
			$menu->level = 1;
			if( $menu->parent_id ) {
				$level = Menu::select('level')
								->where('id', $menu->parent_id)
								->value('level');
				$menu->level = ++$level;
			}
			// if(  $arrPost['id'] ) {
			//   $permission = Permission::can($this->layout->admin, "menus{$menu->type}_edit_all");
			//   $errorMessage = 'You do not have permission to edit menu';
			// } else {
			//   $permission = Permission::can($this->layout->admin, "menus{$menu->type}_create_all");
			//   $errorMessage = 'You do not have permission to create menu';
			// }
			if( $permission ) {
				if($menu->save()){
					Menu::updateRecursive($menu->id, ['type' => $menu->type]);
					$arrReturn['status'] = 'ok';
					$arrReturn['message'] = $message;
					$arrMenu = Menu::getMenu();
					$arrReturn['menu']  = $arrMenu;
					$arrReturn['sidebar']  = Menu::getSidebar();
					$arrParent = Menu::getParent();
					$arrReturn['parent']  = $arrParent;
					return response()->json($arrReturn);
				}else{
					$response = Response::json(['message' => trans('valid.can_not_save_menu')]);
				}
			} else {
				$response = Response::json(['message' => trans('valid.do_not_permission')]);
			}
		} else {
			$response = Response::json(['message' => trans('valid.data_empty')]);
		}
	}

	public function postReorderMenu(Request $request) {
		$arrPost = $request->toArray();
		unset($arrPost['_token']);
		$updated = false;
		if(!empty($arrPost)){
			foreach($arrPost as $type => $menu){
				if(empty($menu)) continue;
				$menu = json_decode($menu);
				foreach($menu as $key => $value) {
					$i = 1;
					Menu::where('id', $value->id)
							->update([
									'parent_id' =>  0,
									'weight'  =>  ($key+1),
									'level'   =>  $i,
								]);
					if( isset($value->children) ){
						Menu::updateRecursiveChildOrder($value->children, $value->id, $i+1);
					}
				}
				$updated = true;
			}
			$arrParent = Menu::getParent();
			$sidebar = Menu::getSidebar();
			$arrReturn = ['status'=> 'ok', 'sidebar'=> $sidebar, 'parent'=> $arrParent];
		}
		return response()->json($arrReturn);
	}

	public function getDeleteMenu($id){
		$arrReturn = ['status' => 'error', 'message' => trans('valid.delete_menu_fail')];
		$menu = Menu::find($id);
		if(!is_null($menu)){
			$name = $menu->name;
			$type = $menu->type;
			// if( Permission::can($this->layout->admin, "menus{$type}_delete_all") ) {
				self::deleteRecursiveMenu($menu->id, $menu);
				$arrReturn['status']  = 'success';
				$arrReturn['message'] = trans('valid.delete_menu_success',['name'=>$name]);
				if( $menu->destroy($menu->id) ) {
					if( $type == 'backend' ) {
						$sidebar = Menu::getSidebar();
						$arrReturn['sidebar']  = $sidebar;
					}
				}
			// } else {
			// 	$arrReturn['message'] = 'You do not have permission to delete menu.';
			// }
		}
       	return response()->json($arrReturn);
	}

	private static function deleteRecursiveMenu($id, Menu $menu){
		$arrMenu = Menu::select('id')
					->where('parent_id', $id)
					->get();
		if( !is_null($arrMenu) ) {
			foreach($arrMenu as $menu) {
				self::deleteRecursiveMenu($menu->id, $menu);
				$menu::destroy($menu->id);
			}
		}
		return true;
	}

}
