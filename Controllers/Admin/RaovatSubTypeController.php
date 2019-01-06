<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\Raovat;
use App\Models\Location\RaovatType;
use App\Models\Location\RaovatSubType;
use App\Models\Location\SubTypeRaovat;
use App\Models\Location\RaovatImage;

use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Illuminate\Support\Facades\Auth;
use Validator;

class RaovatSubTypeController extends BaseController
{

  public function getListRaovatSubType(Request $request, $raovat_type_id)
  {
    $per_page = \Session::has('pagination.'.\Route::currentRouteName()) ? session('pagination.'.\Route::currentRouteName()) : 10;

    $all_raovat_subtype = RaovatSubType::with('_created_by')->Where('deleted', '=', 0)->Where('raovat_type_id', '=',$raovat_type_id);

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

      $all_raovat_subtype->Where(function ($query) use ($keyword) {
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
        $all_raovat_subtype->orderBy($key,$value);
      }
    }else{
      $all_raovat_subtype->orderBy('weight','asc');
    }
    // $all_raovat_subtype->orderBy('weight');
    $list_raovat_subtype = $all_raovat_subtype->paginate($per_page);
    // pr($list_raovat_subtype->toArray());die;
    return view('Admin.raovat_subtype.list', ['list_raovat_subtype' => $list_raovat_subtype, 'keyword' => $keyword, 'raovat_type_id' => $raovat_type_id, 'sort'=> $arr_sort, 'qsort'=> $sort]);

  }

  public function getListApproveRaovatSubType(Request $request, $raovat_type_id)
  {
    $per_page = \Session::has('pagination.'.\Route::currentRouteName()) ? session('pagination.'.\Route::currentRouteName()) : 10;

    $all_raovat_subtype = RaovatSubType::with('_created_by')->Where('deleted', '=', 0)->Where('raovat_type_id', '=',$raovat_type_id)->Where('approved', '=', 0);

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

      $all_raovat_subtype->Where(function ($query) use ($keyword) {
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
        $all_raovat_subtype->orderBy($key,$value);
      }
    }else{
      $all_raovat_subtype->orderBy('weight','asc');
    }
    // $all_raovat_subtype->orderBy('weight');
    $list_raovat_subtype = $all_raovat_subtype->paginate($per_page);
    // pr($list_raovat_subtype->toArray());die;
    return view('Admin.raovat_subtype.approve', ['list_raovat_subtype' => $list_raovat_subtype, 'keyword' => $keyword, 'raovat_type_id' => $raovat_type_id, 'sort'=> $arr_sort, 'qsort'=> $sort]);

  }


  public function getApproveRaovatSubType($raovat_type_id, $id){
    $raovat_subtype = RaovatSubType::find($id);
    $name = $raovat_subtype->name;
    $raovat_subtype->approved = 1;
    $raovat_subtype->active = 1;
    $raovat_subtype->save();
    return redirect()->route('list_raovat_subtype',['raovat_type_id' => $raovat_type_id])->with(['status' => 'Sub type ' . $name . ' đã duyệt thành công']);
  }

  public function getAddRaovatSubType($raovat_type_id)
  {
    return view('Admin.raovat_subtype.add',['raovat_type_id' => $raovat_type_id]);
  }

  function postAddRaovatSubType(Request $request, $raovat_type_id)
  {
    $rules = [
      'name' => 'required',
      'machine_name' => 'required|unique:raovat_subtype,machine_name',
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

      $weight = RaovatSubType::where('raovat_type_id', '=',$raovat_type_id)->max('weight');

      $raovat_subtype = new RaovatSubType();

      $raovat_subtype->name = $request->name;
      $raovat_subtype->machine_name = $request->machine_name;
      $raovat_subtype->alias = $request->alias;
      $raovat_subtype->language = $request->language;
      $raovat_subtype->weight = isset($weight)?$weight + 1:0;
      $raovat_subtype->raovat_type_id = $raovat_type_id;
      $raovat_subtype->description = $request->description;
      $raovat_subtype->active =  isset($request->active);
      $raovat_subtype->created_by = Auth::guard('web')->user()->id;
      $raovat_subtype->updated_by = Auth::guard('web')->user()->id;
      if($request->file('image')) {
        $path = public_path().'/upload/raovat_subtype/';
        $file = $request->file('image');
        if(!\File::exists($path)) {
          \File::makeDirectory($path, $mode = 0777, true, true);
        }
        $name =time(). '.' . $file->getClientOriginalExtension();
        if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
$file->move($path,$name);
        $raovat_subtype->image = '/upload/raovat_subtype/'.$name;
      }else{
        $raovat_subtype->image ='/frontend/assets/img/upload/cate3.png';
      }
      if( $raovat_subtype->save() ) {
        return redirect()->route('list_raovat_subtype',['raovat_type_id' => $raovat_type_id])->with(['status' => 'Sub type đã được thêm thành công ']);
      } else {
        $errors = new MessageBag(['error' => 'Không tạo được category item']);
        return redirect()->back()->withErrors($errors)->withInput();
      }
    }
  }

  public function getUpdateRaovatSubType($raovat_type_id, $id)
  {
    $list_category = RaovatType::Where('deleted', '=', 0)
                                        ->Where('id', '<>', $id)
                                        ->get();
    $raovat_subtype = RaovatSubType::find($id);
    return view('Admin.raovat_subtype.update', ['raovat_subtype' => $raovat_subtype, 'list_category' => $list_category, 'raovat_type_id' => $raovat_type_id]);
  }

  function postUpdateRaovatSubType(Request $request, $raovat_type_id, $id)
  {
    $raovat_subtype = RaovatSubType::find($id);
    $old_active = $raovat_subtype->active;

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
      $raovat_subtype->name = $request->name;
      $raovat_subtype->alias = $request->alias;
      $raovat_subtype->language = $request->language;
      $raovat_subtype->raovat_type_id = $raovat_type_id;
      $raovat_subtype->description = $request->description;
      $raovat_subtype->active =  isset($request->active);
      $raovat_subtype->updated_by = Auth::guard('web')->user()->id;
      if($request->file('image')) {
        $path = public_path().'/upload/raovat_subtype/';
        $file = $request->file('image');
        if(!\File::exists($path)) {
          \File::makeDirectory($path, $mode = 0777, true, true);
        }
        $name =time(). '.' . $file->getClientOriginalExtension();
        if(isset(getimagesize($file)[2]) && getimagesize($file)[2]!=0)
$file->move($path,$name);
        $raovat_subtype->image = '/upload/raovat_subtype/'.$name;
      }
      // else{
      //   if(!$raovat_subtype->image)
      //     $raovat_subtype->image ='/frontend/assets/img/upload/cate3.png';
        
      // }

      if( $raovat_subtype->save() ) {

        $active = isset($request->active) ? 1 : 0;
        if($old_active != $active)
        {
          $raovat_id = SubTypeRaovat::where('raovat_subtype_id','=',$id)->pluck('raovat_id')->toArray();
          foreach ($raovat_id as $id)
          {
            $data = SubTypeRaovat::where('raovat_id','=',$id)->get();
            if(count($data) == 1)
            {
              $data = Raovat::find($id);
              $data->active = $active;
              $data->save();
            }
          }
        }

        return redirect()->route('list_raovat_subtype',['raovat_type_id' => $raovat_type_id])->with(['status' => 'Sub type đã được cập nhật thành công ']);
      } else {
        $errors = new MessageBag(['error' => 'Không tạo được raovat_subtype']);
        return redirect()->back()->withErrors($errors)->withInput();
      }
    }
  }

  public function getDeleteRaovatSubType($raovat_type_id, $id)
  {

    $content = SubTypeRaovat::where('raovat_subtype_id','=',$id)->count();
    if($content > 0)
    {
      $raovat_subtype = RaovatSubType::find($id);
      $name = $raovat_subtype->name;
      return redirect()->route('list_raovat_subtype',['raovat_type_id' => $raovat_type_id])->with(['err' => 'Sub type ' . $name . ' không thể xóa, Vui lòng kiểm tra lại toàn bộ content trước khi xóa !']);
    }
    else {
      $raovat_subtype = RaovatSubType::find($id);
      $name = $raovat_subtype->name;
      $raovat_subtype->delete();
      return redirect()->route('list_raovat_subtype',['raovat_type_id' => $raovat_type_id])->with(['status' => 'Sub type ' . $name . ' đã xóa thành công']);
    }
  }

  public function getDownRaovatType($raovat_type_id, $id){
    $current_category = RaovatSubType::find($id);
    $change_category = RaovatSubType::where('weight','>',$current_category->weight)->orderBy('weight','asc')->first();
    $old_weight = $current_category->weight;
    $current_category->weight = $change_category->weight;
    $change_category->weight = $old_weight;
    $current_category->save();
    $change_category->save();
    return redirect()->route('list_raovat_subtype',['raovat_type_id' => $raovat_type_id]);
  }

  public function getUpRaovatType($raovat_type_id, $id){
    $current_category = RaovatSubType::find($id);
    $change_category = RaovatSubType::where('weight','<',$current_category->weight)->orderBy('weight','desc')->first();
    $old_weight = $current_category->weight;
    $current_category->weight = $change_category->weight;
    $change_category->weight = $old_weight;
    $current_category->save();
    $change_category->save();
    return redirect()->route('list_raovat_subtype',['raovat_type_id' => $raovat_type_id]);
  }

  public function getChangeWeightRaovatType($raovat_type_id, $id,$weight){
    $current_category = RaovatSubType::find($id);
    if($current_category){
      $current_category->weight = $weight;
      $current_category->save();
      return redirect()->route('list_raovat_subtype',['raovat_type_id' => $raovat_type_id]);
    }
  }
}
