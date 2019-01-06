<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\Category;
use App\Models\Location\ContentType;
use App\Models\Location\Group;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Validator;

class GroupController extends BaseController
{
  public function getListGroup()
  {

    $per_page = \Session::has('pagination.'.\Route::currentRouteName()) ? session('pagination.'.\Route::currentRouteName()) : 10;

    $all_group = Group::with('_category_type');
    $input = request()->all();

    if (isset($input['keyword'])) {
      $keyword = $input['keyword'];
    } else {
      $keyword = '';
    }

    if (isset($keyword) && $keyword != '') {

      $all_group->Where(function ($query) use ($keyword) {
        $query->where('name', 'LIKE', '%' . $keyword . '%');
        $query->orWhere('machine_name', 'LIKE', '%' . $keyword . '%');
      });
    }

    $list_group = $all_group->paginate($per_page);

    return view('Admin.group.list', ['list_group' => $list_group,
      'keyword' => $keyword]);
  }

  public function getAddGroup()
  {
    $data['list_category_type'] = Category::pluck('name', 'id');
    return view('Admin.group.add', ['data' => $data]);
  }

  public function postAddGroup(Request $request)
  {
    $rules = [
      'name' => 'required|unique:groups,name',
      'machine_name' => 'required|unique:groups,machine_name',
      'alias' => 'required|unique:groups,alias',
      'id_category' => 'required',
    ];
    $messages = [
      'name.required' => trans('valid.name_required'),
      'alias.required' => trans('valid.alias_required'),
      'machine_name.required' => trans('valid.machine_name_required'),
      'machine_name.unique' => trans('valid.machine_name_unique'),
      'name.unique' => trans('valid.name_unique'),
      'alias.unique' => trans('valid.alias_unique'),
      'id_category.required' => trans('valid.id_category_required'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      $group = new Group();

      $group->name = $request->name;
      $group->machine_name = $request->machine_name;
      $group->alias = $request->alias;
      $group->id_category = $request->id_category;

      if ($group->save()) {
        return redirect()->route('list_group')->with(['status' => 'Group đã được thêm thành công ']);
      } else {
        $errors = new MessageBag(['error' => 'Không tạo được group']);
        return redirect()->back()->withErrors($errors)->withInput();
      }
    }
  }

  public function getUpdateGroup($id)
  {
    $group = Group::find($id);
    $data['list_category_type'] = Category::pluck('name', 'id');
    return view('Admin.group.update', ['group' => $group,'data'=>$data]);
  }

  public function postUpdateGroup(Request $request, $id)
  {
    $group = Group::find($id);
    $rules = [
      'name' => 'required|unique:groups,name',
      'alias' => 'required|unique:groups,alias',
    ];
    $messages = [
      'name.required' => trans('valid.name_required'),
      'name.unique' => trans('valid.name_unique'),
      'alias.required' => trans('valid.alias_required'),
      'alias.unique' => trans('valid.alias_unique'),
    ];

    if ($group->name == $request->name) {
      $rules['name'] = 'required';
    }

    if ($group->alias == $request->alias) {
      $rules['alias'] = 'required';
    }

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      $group->name = $request->name;
      $group->alias = $request->alias;

      if( $group->save() ) {
        return redirect()->route('list_group')->with(['status' => 'Group đã được cập nhật thành công ']);
      } else {
        $errors = new MessageBag(['error' => 'Không tạo được group']);
        return redirect()->back()->withErrors($errors)->withInput();
      }
    }
  }

  public function getDeleteGroup($id)
  {
    $group = Group::find($id);
    $group_name = $group->name;
    $group->delete();
    return redirect()->route('list_group')->with(['status' => 'Group ' . $group_name . ' đã xóa thành công ']);
  }
}
