<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\CategoryContent;
use App\Models\Location\CategoryItem;
use App\Models\Location\Category;
use App\Models\Location\Content;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;

class CategoryItemController extends BaseController
{

  public function getListCategoryItem(Request $request, $category_id)
  {
    $per_page = \Session::has('pagination.'.\Route::currentRouteName()) ? session('pagination.'.\Route::currentRouteName()) : 10;

    $all_category_item = CategoryItem::with('_created_by')->Where('deleted', '=', 0)->Where('category_id', '=',$category_id)->Where('approved', '=', 1);

    $input = request()->all();
    $sort = $request->sort?$request->sort:'';
    $sort = str_replace('%2C', ',', $sort);
    $sort = str_replace(',', ',', $sort);

    if (isset($input['keyword'])) {
      $keyword = $input['keyword'];
    } else {
      $keyword = '';
    }

    if (isset($keyword) && $keyword != '') {

      $all_category_item->Where(function ($query) use ($keyword) {
        $query->where('name', 'LIKE', '%' . $keyword . '%');
        $query->orWhere('description', 'LIKE', '%' . $keyword . '%');
        $query->orWhere('language', 'LIKE', '%' . $keyword . '%');
        $query->orWhere('weight', 'LIKE', '%' . $keyword . '%');
      });
    }
    $arr_sort = [];
    if($sort!=''){
      $listSort = explode(',',$sort);
      foreach ($listSort as $key => $value) {
        $item = explode('-',$value);
        if(isset($item[1])){
          $arr_sort[$item[0]] = $item[1];
        }
      }
    }
    if(count($arr_sort)){
      foreach ($arr_sort as $key => $value) {
        $all_category_item->orderBy($key,$value);
      }
    }else{
      $all_category_item->orderBy('weight','asc');
    }
    // $all_category_item->orderBy('weight');
    $list_category_item = $all_category_item->paginate($per_page);
    // pr($list_category_item->toArray());die;
    return view('Admin.category_item.list', ['list_category_item' => $list_category_item, 'keyword' => $keyword, 'category_id' => $category_id, 'sort'=> $arr_sort, 'qsort'=> $sort]);

  }

  public function getListApproveCategoryItem(Request $request, $category_id)
  {
    $per_page = \Session::has('pagination.'.\Route::currentRouteName()) ? session('pagination.'.\Route::currentRouteName()) : 10;

    $all_category_item = CategoryItem::with('_created_by')->Where('deleted', '=', 0)->Where('category_id', '=',$category_id)->Where('approved', '=', 0);

    $input = request()->all();
    $sort = $request->sort?$request->sort:'';
    $sort = str_replace('%2C', ',', $sort);
    $sort = str_replace(',', ',', $sort);

    if (isset($input['keyword'])) {
      $keyword = $input['keyword'];
    } else {
      $keyword = '';
    }

    if (isset($keyword) && $keyword != '') {

      $all_category_item->Where(function ($query) use ($keyword) {
        $query->where('name', 'LIKE', '%' . $keyword . '%');
        $query->orWhere('description', 'LIKE', '%' . $keyword . '%');
        $query->orWhere('language', 'LIKE', '%' . $keyword . '%');
        $query->orWhere('weight', 'LIKE', '%' . $keyword . '%');
      });
    }
    $arr_sort = [];
    if($sort!=''){
      $listSort = explode(',',$sort);
      foreach ($listSort as $key => $value) {
        $item = explode('-',$value);
        if(isset($item[1])){
          $arr_sort[$item[0]] = $item[1];
        }
      }
    }
    if(count($arr_sort)){
      foreach ($arr_sort as $key => $value) {
        $all_category_item->orderBy($key,$value);
      }
    }else{
      $all_category_item->orderBy('weight','asc');
    }
    // $all_category_item->orderBy('weight');
    $list_category_item = $all_category_item->paginate($per_page);
    // pr($list_category_item->toArray());die;
    return view('Admin.category_item.approve', ['list_category_item' => $list_category_item, 'keyword' => $keyword, 'category_id' => $category_id, 'sort'=> $arr_sort, 'qsort'=> $sort]);

  }


  public function getApproveCategoryItem($category_id, $id){
    $category_item = CategoryItem::find($id);
    $name = $category_item->name;
    $category_item->approved = 1;
    $category_item->active = 1;
    $category_item->save();
    return redirect()->route('list_category_item',['category_id' => $category_id])->with(['status' => 'Category Item ' . $name . ' đã duyệt thành công']);
  }

  public function getAddCategoryItem($category_id)
  {
    return view('Admin.category_item.add',['category_id' => $category_id]);
  }

  function postAddCategoryItem(Request $request, $category_id)
  {
    $rules = [
      'name' => 'required',
      'machine_name' => 'required|unique:category_items,machine_name',
      'alias'=>'required'
    ];
    $messages = [
      'name.required' => trans('valid.name_required'),
      'machine_name.required' => trans('valid.machine_name_required'),
      'machine_name.unique' => trans('valid.machine_name_unique'),
      'alias.required' => trans('valid.alias_required')
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      $weight = CategoryItem::where('category_id', '=',$category_id)->max('weight');

      $category_item = new CategoryItem();

      $category_item->name = $request->name;
      $category_item->machine_name = $request->machine_name;
      $category_item->alias = $request->alias;
      $category_item->language = $request->language;
      $category_item->weight = isset($weight)?$weight + 1:0;
      $category_item->category_id = $category_id;
      $category_item->description = $request->description;
      $category_item->active =  isset($request->active);
      $category_item->created_by = Auth::guard('web')->user()->id;
      $category_item->updated_by = Auth::guard('web')->user()->id;
      if($request->file('image')) {
        $path = public_path().'/upload/category_item/';
        $file = $request->file('image');
        if(!\File::exists($path)) {
          \File::makeDirectory($path, $mode = 0777, true, true);
        }
        $name =time(). '.' . $file->getClientOriginalExtension();
        if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
$file->move($path,$name);
        $category_item->image = '/upload/category_item/'.$name;
      }else{
        $category_item->image ='/frontend/assets/img/upload/cate3.png';
      }
      if( $category_item->save() ) {
        return redirect()->route('list_category_item',['category_id' => $category_id])->with(['status' => 'Category Item đã được thêm thành công ']);
      } else {
        $errors = new MessageBag(['error' => 'Không tạo được category item']);
        return redirect()->back()->withErrors($errors)->withInput();
      }
    }
  }

  public function getUpdateCategoryItem($category_id, $id)
  {
    $list_category = Category::Where('deleted', '=', 0)
                                        ->Where('id', '<>', $id)
                                        ->get();
    $category_item = CategoryItem::find($id);
    return view('Admin.category_item.update', ['category_item' => $category_item, 'list_category' => $list_category, 'category_id' => $category_id]);
  }

  function postUpdateCategoryItem(Request $request, $category_id, $id)
  {
    $category_item = CategoryItem::find($id);
    $old_active = $category_item->active;

    $rules = [
      'name' => 'required',
      'alias' => 'required'

    ];
    $messages = [
      'name.required' => trans('valid.name_required'),
      'name.unique' => trans('valid.name_unique')

    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {
      $category_item->name = $request->name;
      $category_item->alias = $request->alias;
      $category_item->language = $request->language;
      $category_item->category_id = $category_id;
      $category_item->description = $request->description;
      $category_item->active =  isset($request->active);
      $category_item->updated_by = Auth::guard('web')->user()->id;
      if($request->file('image')) {
        $path = public_path().'/upload/category_item/';
        $file = $request->file('image');
        if(!\File::exists($path)) {
          \File::makeDirectory($path, $mode = 0777, true, true);
        }
        $name =time(). '.' . $file->getClientOriginalExtension();
        if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
$file->move($path,$name);
        $category_item->image = '/upload/category_item/'.$name;
      }
      // else{
      //   if(!$category_item->image)
      //     $category_item->image ='/frontend/assets/img/upload/cate3.png';
        
      // }

      if( $category_item->save() ) {

        $active = isset($request->active) ? 1 : 0;
        if($old_active != $active)
        {
          $id_content = CategoryContent::where('id_category_item','=',$id)->pluck('id_content')->toArray();
          foreach ($id_content as $id)
          {
            $data = CategoryContent::where('id_content','=',$id)->get();
            if(count($data) == 1)
            {
              $data = Content::find($id);
              if($data->moderation == 'publish')
              {
                $data->active = $active;
                $data->save();
              }
            }
          }
        }

        return redirect()->route('list_category_item',['category_id' => $category_id])->with(['status' => 'Category Item đã được cập nhật thành công ']);
      } else {
        $errors = new MessageBag(['error' => 'Không tạo được category_item']);
        return redirect()->back()->withErrors($errors)->withInput();
      }
    }
  }

  public function getDeleteCategoryItem($category_id, $id)
  {

    $content = CategoryContent::where('id_category_item','=',$id)->count();
    if($content > 0)
    {
      $category_item = CategoryItem::find($id);
      $name = $category_item->name;
      return redirect()->route('list_category_item',['category_id' => $category_id])->with(['err' => 'Category Item ' . $name . ' không thể xóa, Vui lòng kiểm tra lại toàn bộ content trước khi xóa !']);
    }
    else {
      $category_item = CategoryItem::find($id);
      $name = $category_item->name;
      $category_item->delete();
      return redirect()->route('list_category_item',['category_id' => $category_id])->with(['status' => 'Category Item ' . $name . ' đã xóa thành công']);
    }
  }

  public function getDownCategory($category_id, $id){
    $current_category = CategoryItem::find($id);
    $change_category = CategoryItem::where('weight','>',$current_category->weight)->orderBy('weight','asc')->first();
    $old_weight = $current_category->weight;
    $current_category->weight = $change_category->weight;
    $change_category->weight = $old_weight;
    $current_category->save();
    $change_category->save();
    return redirect()->route('list_category_item',['category_id' => $category_id]);
  }

  public function getUpCategory($category_id, $id){
    $current_category = CategoryItem::find($id);
    $change_category = CategoryItem::where('weight','<',$current_category->weight)->orderBy('weight','desc')->first();
    $old_weight = $current_category->weight;
    $current_category->weight = $change_category->weight;
    $change_category->weight = $old_weight;
    $current_category->save();
    $change_category->save();
    return redirect()->route('list_category_item',['category_id' => $category_id]);
  }

  public function getChangeWeightCategory($category_id, $id,$weight){
    $current_category = CategoryItem::find($id);
    if($current_category){
      $current_category->weight = $weight;
      $current_category->save();
      return redirect()->route('list_category_item',['category_id' => $category_id]);
    }
  }
}
