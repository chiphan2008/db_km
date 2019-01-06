<?php

namespace App\Models\Location;
use App\Models\Location\Base;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;


class Menu extends Base
{
  protected $table = 'menus';

  public static function updateMenuFrontEnd($parent=0,$id_category,$active=0,$weight=1){
  	if(isset($id_category)){
  		$menu = Menu::where('category_id',$id_category)->first();
  		if(!$menu){
  			$menu = new Menu();
  		}

  		$category = Category::find($id_category);
  		if($category){
  			$menu->category_id = $id_category;
  			$menu->parent_id = $parent;
  			$menu->name = $category->name;
  			$menu->link = '/list/'.$category->alias;
  			$menu->icon_img = $category->image;
  			$menu->type = 'frontend';
  			$menu->level = 2;
  			$menu->active = $active?$active:0;
  			$menu->weight = $weight?$weight:1;
  			$menu->created_by = 1;
  			$menu->updated_by = 1;
  			$menu->save();
  		}
  	}
  }

  public static function deleteMenuFrontEnd($id_category){
  	if(isset($id_category)){
  		Menu::where('category_id',$id_category)->delete();
  	}
  }

  public static function getMenu()
	{
		$arrMenu = self::orderBy('type','asc')
							->orderBy('parent_id','asc')
							->orderBy('weight','asc')
							->orderBy('name','asc')
							->get();
		if( $arrMenu->isEmpty() ) {
			return '';
		}
		$arrMenu = self::setMenu($arrMenu->toArray());
		return self::renderMenu($arrMenu);
	}

	public static function setMenu($menu)
	{
		$arrMenu = [];
		foreach($menu as $value){
			$arrMenu[$value['parent_id']][$value['id']] = $value;
		}
		return $arrMenu;
	}

	public static function getParent($arrCondition = [])
	{
		$arrMenu = self::select('id', 'name', 'parent_id', 'level', 'type','language','weight','module')
						->where('active', '=', 1)
						->where('level', '<', 5);
		if( isset($arrCondition['id']) ) {
			$arrMenu->where('id', '<>', $arrCondition['id']);
		}
		if( isset($arrCondition['type']) ) {
			$arrMenu->where('type', $type);
		}
		if( isset($arrCondition['module']) ) {
			$arrMenu->where('module', $type);
		}
		$arrMenu = $arrMenu->orderBy('weight','asc')
						->orderBy('name','asc')
						->get();
		if( $arrMenu->isEmpty() ) {
			return '';
		}
		$arrMenu = self::setMenu($arrMenu->toArray());

		return self::renderParent($arrMenu);
	}

  	private static function renderMenu(&$arrMenu, $parent_id = 0, $arrHTML = array())
	{
		$user = Auth::guard('web')->user();
		// dd($user);
		if( isset($arrMenu[$parent_id]) ){
			foreach($arrMenu[$parent_id] as $k => $menu){
				if( !$user->can('view_Menu')  ) {
					continue;
				}
				if(isset($menu['module'])){
					$key = $menu['type'].'-'.$menu['module'];
				}else{
					$key = $menu['type'];
				}
				
				if( !isset($arrHTML[$key]) ) {
					$arrHTML[$key] = '';
				}
				$id = $menu['id'];
				$style = $disable = $delete = '';
				if( !$menu['active'] ) {
					$style = 'style="background-color: #ccc"';
				}

				if( $user->can('delete_Menu') ) {
					$delete = '<span class="pull-right">
		                            <a data-function="delete" href="javascript:void(0)"  onclick="deleteMenu('. $menu['id'] .')">
		                                <i class="fa fa-times"></i>
		                            </a>
		                        </span>';
				}

				if( !$user->can('edit_Menu') ) {
					$disable = 'disabled-link';
					
				}
				$arrHTML[$key] .= '<li class="dd-item dd3-item" data-id="'. $menu['id'] .'">
									<div class="dd-handle dd3-handle">
									</div>
									<div class="dd3-content '. $disable .'" '. $style .'data-id="'.$menu['id'].'">
										'. $menu['name'] .'
										<input type="hidden" id="menu-'. $menu['id'] .'" value="' .e(json_encode($menu)) .'" />
										'. $delete .'
									</div>';
				if ( isset($arrMenu[$id]) ) {
					$data = self::renderMenu($arrMenu, $id);
					$arrHTML[$key] .=   '<ol class="dd-list">
										'. $data[$key] .'
										</ol>';
				}
				$arrHTML[$key] .= '</li>';
				unset($arrMenu[$parent_id][$k]);
			}
		}
		return $arrHTML;
	}

	private static function renderParent($arrMenu, $parent_id = 0, $arrHTML = array())
	{
		if( isset($arrMenu[$parent_id]) ){
			foreach($arrMenu[$parent_id] as $k => $menu) {
				$type = $menu['type'];
				$module= $menu['module'];
				if( !isset($arrHTML[$type]) ) {
					$arrHTML[$type] = [];
					if( !isset($arrHTML[$type][$module]) && $module!="") {
						$arrHTML[$type][$module] = [];
					}
				}
				$prefix = '';
				if( $parent_id ) {
					for($i = 1; $i < $menu['level']; $i++) {
						$prefix .= '--';
					}
				}
				$id = $menu['id'];
				if( isset($arrMenu[$id]) ) {
					if($type=='backend'){
						$arrHTML[$type][$module][] = '<option value="'. $menu['id'] .'">'. $prefix.$menu['name'] .'</option>';
					}else{
						$arrHTML[$type][] = '<option value="'. $menu['id'] .'">'. $prefix.$menu['name'] .'</option>';
					}
					$arrHTML = self::renderParent($arrMenu, $id, $arrHTML);
				} else {
					if($type=='backend'){
						$arrHTML[$type][$module][] = '<option value="'. $menu['id'] .'">'. $prefix.$menu['name'] .'</option>';
					}else{
						$arrHTML[$type][] = '<option value="'. $menu['id'] .'">'. $prefix.$menu['name'] .'</option>';
					}
					
				}
			}
		}
		return $arrHTML;
	}

	public static function updateRecursiveChildOrder($arrMenu, $parentID, $i)
	{
		if( $parentID )
			$parentID = $parentID;
		foreach($arrMenu as $key => $value){
			if( isset($value->children) ) {
				self::updateRecursiveChildOrder($value->children, $value->id, $i+1);
			}
			Menu::where('id', $value->id)
						->update([
								'parent_id' => 	$parentID,
								'weight' 	=>	($key+1),
								'level' 	=>	$i,
							]);
		}
	}

	public static function updateRecursive($parentID, $arrData)
	{
		$arrMenu = Menu::select('id')
							->where('parent_id', $parentID)
							->get();
		if( !$arrMenu->isEmpty() ) {
			foreach($arrMenu as $menu) {
				self::updateRecursive($menu->id, $arrData);
			}
		}
		Menu::where('parent_id', $parentID)
				->update($arrData);
	}

	public static function getSidebar()
	{
		$module_admin = session()->get('module_admin');
		if(!$module_admin){
			$module_admin = 'location';
		}
		$arrMenu = self::select('id','name', 'icon_class', 'link', 'type', 'parent_id')
							->where('active', 1)
							->where('type', 'backend')
							->where('module', $module_admin)
							->orderBy('parent_id','asc')
							->orderBy('weight','asc')
							->orderBy('name','asc')
							->get();
		if( $arrMenu->isEmpty() ) {
			return '';
		}
		$arrMenu = self::setMenu($arrMenu->toArray());
		return self::renderSidebar($arrMenu);
	}

	private static function renderSidebar($arrMenu, $parent_id = 0, $html = '')
	{
		$user = Auth::guard('web')->user();
		if( isset($arrMenu[$parent_id]) ){
			foreach($arrMenu[$parent_id] as $k => $menu){
				$id = $menu['id'];
				$arr_Change = [
					'ContentGroup'=>'Group',
					'Contentgroup'=>'Group',
					'contentgroup'=>'Group'
				];

				$name = str_replace(' ','',$menu['name']);
				if(isset($arr_Change[$name])){
					$name = $arr_Change[$name];
				}
				$arr_ban = [];
				if(
					($user->can('view_'.$name) && !in_array($name,$arr_ban)) 
					|| $user->hasRole('super_admin')
					|| (($user->can('view_Role')||$user->can('view_Permission') || $user->can('view_User'))&&$name=='ManageUser')
					){
					if ( isset($arrMenu[$id]) ) {
						$html .= '<li>
									<a href="javascript:void(0)">
										<i class="fa '.$menu['icon_class'].'"></i> '.$menu['name'].'
										<span class="fa fa-chevron-down"></span>
									</a>
									<ul class="nav child_menu">
									'. self::renderSidebar($arrMenu, $id) .'
									</ul>
								</li>';
					} else {
						$html .= '<li>
									<a href="'.$menu['link'].'">
										<i class="fa '.$menu['icon_class'].'"></i>
										<span class="title">'.$menu['name'].'</span>
									</a>
								</li>';
					}
				}
				unset($arrMenu[$parent_id][$k]);
			}
		}
		return $html;
	}

	public static function getFrontendMenu($lang='vn')
	{
		$arrMenu = self::select('id','name', 'icon_class','icon_img', 'link', 'type', 'parent_id')
							->where('active', 1)
							->where('type', 'frontend')
							// ->where('language','=',$lang)
							->orderBy('parent_id','asc')
							->orderBy('weight','asc')
							->orderBy('name','asc')
							->get();
		if( $arrMenu->isEmpty() ) {
			$arrMenu = self::select('id','name', 'icon_class','icon_img', 'link', 'type', 'parent_id')
							->where('active', 1)
							->where('type', 'frontend')
							->where('language','=','vn')
							->orderBy('parent_id','asc')
							->orderBy('weight','asc')
							->orderBy('name','asc')
							->get();
		}
		if( $arrMenu->isEmpty() ) {
			return '';
		}
		$arrMenu = self::setMenu($arrMenu->toArray());
		return $arrMenu;

	}

	private static function renderFrontendMenu($arrMenu, $parent_id = 0, $html = '')
	{
		if( isset($arrMenu[$parent_id]) ){
			foreach($arrMenu[$parent_id] as $k => $menu){
				$id = $menu['id'];
				if (isset($arrMenu[$id]) ) {
				  $html .= '<div class="nav-dropdown dropdown">
                      <a class="dropdown-toggle" href="" id="dropdownMenuLink_'.$menu['id'].'" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'.$menu['name'].'</a>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink_'.$menu['id'].'">';
          foreach ($arrMenu[$id] as $value)
          {
            $html .= '<a class="dropdown-item" href="'.$value['link'].'"><i class="icon-food"></i>'.app('translator')->getFromJson($value['name']).'</a>';
          }

          $html .= '</div></div>';
				}
				else {
          $html .= '<a href="'.$menu['link'].'" >'.$menu['name'].'</a>';
				}

			}
		}
		return $html;
	}
}
