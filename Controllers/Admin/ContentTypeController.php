<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\Location\ContentType;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\MessageBag;
use Validator;

class ContentTypeController extends BaseController
{
  public function getListContentType(Request $request)
  {

    $all_content_type = DB::table('content_types');
    $sort = $request->sort?$request->sort:'';
    $input = request()->all();

    if (isset($input['keyword'])) {
      $keyword = $input['keyword'];
    } else {
      $keyword = '';
    }

    if (isset($keyword) && $keyword != '') {

      $all_content_type->Where(function ($query) use ($keyword) {
        $query->where('name', 'LIKE', '%' . $keyword . '%');
        $query->orWhere('machine_name', 'LIKE', '%' . $keyword . '%');
        $query->orWhere('description', 'LIKE', '%' . $keyword . '%');
        $query->orWhere('language', 'LIKE', '%' . $keyword . '%');
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
        $all_content_type->orderBy($key,$value);
      }
    }else{
      $all_content_type->orderBy('id','desc');
    }
    $list_content_type = $all_content_type->paginate(10);
    return view('Admin.content_type.list', ['list_content_type' => $list_content_type, 'keyword' => $keyword, 'sort'=> $arr_sort, 'qsort'=> $sort]);

  }

  public function getAddContentType()
  {
    return view('Admin.content_type.add');
  }

  public function postAddContentType(Request $request)
  {
    $rules = [
      'name' => 'required|unique:content_types,name',
      'machine_name' => 'required|unique:content_types,machine_name',
      'alias' => 'required|unique:content_types,alias',
    ];
    $messages = [
      'name.required' => trans('valid.name_required'),
      'alias.required' => trans('valid.alias_required'),
      'machine_name.required' => trans('valid.machine_name_required'),
      'machine_name.unique' => trans('valid.machine_name_unique'),
      'name.unique' => trans('valid.name_unique'),
      'alias.unique' => trans('valid.alias_unique'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      if ($request->hasFile('image')) {
        $file = $request->file('image');
        if (in_array($file->getClientOriginalExtension(), ['gif', 'jpg', 'png'])) {

          $path = public_path() . '/upload/img_content_type/';
          if (!\File::exists($path)) {
            \File::makeDirectory($path, $mode = 0777, true, true);
          }

          $image_content_type = time() . '_content_type_' .$file->getClientOriginalName();
          $file->move($path, $image_content_type);
          $image_content_type = '/upload/img_content_type/' . $image_content_type;
        } else {
          $errors = new MessageBag(['avatar' => 'Hình ảnh không hợp lệ']);
          return redirect()->back()->withErrors($errors)->withInput();
        }
      }

      $content_type = new ContentType();

      $content_type->name = $request->name;
      $content_type->machine_name = $request->machine_name;
      $content_type->alias = $request->alias;
      $content_type->image = isset($image_content_type) ? $image_content_type : '';
      $content_type->language = $request->language;
      $content_type->description = $request->description;

      if ($content_type->save()) {
        return redirect()->route('list_content_type')->with(['status' => 'Content Type đã được thêm thành công ']);
      } else {
        $errors = new MessageBag(['error' => 'Không tạo được category']);
        return redirect()->back()->withErrors($errors)->withInput();
      }
    }
  }

  public function getUpdateContentType($id)
  {
    $content_type = ContentType::find($id);
    return view('Admin.content_type.update', ['content_type' => $content_type]);
  }

  public function postUpdateContentType(Request $request, $id)
  {
    $content_type = ContentType::find($id);
    $rules = [
      'name' => 'required|unique:content_types,name',
      'alias' => 'required|unique:content_types,alias',
    ];
    $messages = [
      'name.required' => trans('valid.name_required'),
      'name.unique' => trans('valid.name_unique'),
      'alias.required' => trans('valid.alias_required'),
      'alias.unique' => trans('valid.alias_unique'),
    ];

    if ($content_type->name == $request->name) {
      $rules['name'] = 'required';
    }

    if ($content_type->alias == $request->alias) {
      $rules['alias'] = 'required';
    }

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      if ($request->hasFile('image')) {
        $file = $request->file('image');
        if (in_array($file->getClientOriginalExtension(), ['gif', 'jpg', 'png'])) {

          $path = public_path() . '/upload/img_content_type/';
          if (!\File::exists($path)) {
            \File::makeDirectory($path, $mode = 0777, true, true);
          }
          $image_content_type = time() . '_content_type_' . $file->getClientOriginalName();
          if ($file->move($path , $image_content_type)) {
            $image_content_type = '/upload/img_content_type/' . $image_content_type;
//            if($content_type->image) {
//              unlink(public_path($content_type->image));
//            }
          }
        }
      } else {
        $image_content_type = $content_type->image;
      }

      $content_type->name = $request->name;
      $content_type->alias = $request->alias;
      $content_type->image = $image_content_type;
      $content_type->language = $request->language;
      $content_type->description = $request->description;

      if( $content_type->save() ) {
        return redirect()->route('list_content_type')->with(['status' => 'Category đã được cập nhật thành công ']);
      } else {
        $errors = new MessageBag(['error' => 'Không tạo được content type']);
        return redirect()->back()->withErrors($errors)->withInput();
      }
    }
  }

  public function getDeleteContentType($id)
  {
    $content_type = ContentType::find($id);
    $content_type_name = $content_type->name;
    $content_type->delete();
    return redirect()->route('list_content_type')->with(['status' => 'Content Type ' . $content_type_name . ' đã xóa thành công ']);
  }
}
