<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\CategoryService;
use App\Models\Location\ServiceItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Validator;

class ServiceItemController extends BaseController
{
  public function getListServiceItem(Request $request)
  {
    $per_page = \Session::has('pagination.'.\Route::currentRouteName()) ? session('pagination.'.\Route::currentRouteName()) : 10;
    $all_service_item = DB::table('service_items')->Where('approved', '=', 1);

    $input = request()->all();

    if (isset($input['keyword'])) {
      $keyword = $input['keyword'];
    } else {
      $keyword = '';
    }

    if (isset($keyword) && $keyword != '') {

      $all_service_item->Where(function ($query) use ($keyword) {
        $query->where('name', 'LIKE', '%' . $keyword . '%');
        $query->orWhere('machine_name', 'LIKE', '%' . $keyword . '%');
      });
    }

    $list_service_item = $all_service_item->paginate($per_page);
    return view('Admin.service_item.list', ['list_service_item' => $list_service_item, 'keyword' => $keyword]);

  }

  public function getListApproveServiceItem(Request $request)
  {
    $per_page = \Session::has('pagination.'.\Route::currentRouteName()) ? session('pagination.'.\Route::currentRouteName()) : 10;
    $all_service_item = DB::table('service_items')->Where('approved', '=', 0);

    $input = request()->all();

    if (isset($input['keyword'])) {
      $keyword = $input['keyword'];
    } else {
      $keyword = '';
    }

    if (isset($keyword) && $keyword != '') {

      $all_service_item->Where(function ($query) use ($keyword) {
        $query->where('name', 'LIKE', '%' . $keyword . '%');
        $query->orWhere('machine_name', 'LIKE', '%' . $keyword . '%');
      });
    }

    $list_service_item = $all_service_item->paginate($per_page);
    return view('Admin.service_item.approve', ['list_service_item' => $list_service_item, 'keyword' => $keyword]);

  }


  public function getApproveServiceItem($id){
    $service = ServiceItem::find($id);
    if(!$service){
      abort(404);
    }
    $service->approved = 1;
    $service->active = 1;
    $service->save();
    return redirect()->route('list_service_item')->with(['status' => 'Service Item ' . $service->name . ' đã duyệt thành công ']);
  }



  public function getAddServiceItem()
  {
    return view('Admin.service_item.add');
  }

  public function postAddServiceItem(Request $request)
  {
    $rules = [
      'name' => 'required|unique:service_items,name',
      'machine_name' => 'required|unique:service_items,machine_name',
    ];
    $messages = [
      'name.required' => trans('valid.name_required'),
      'name.unique' => trans('valid.name_unique'),
      'machine_name.required' => trans('valid.machine_name_required'),
      'machine_name.unique' => trans('valid.machine_name_unique'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      $service_item = ServiceItem::create([
        'name' => $request->name,
        'machine_name' => $request->machine_name,
        'active' => isset($request->active) ? 1 : 0,
        'created_by' => Auth::guard('web')->user()->id,
        'updated_by' => Auth::guard('web')->user()->id,
      ]);

      return redirect()->route('list_service_item')->with(['status' => 'Service Item đã tạo thành công']);

    }
  }

  public function getUpdateServiceItem($id)
  {
    $service_item = ServiceItem::find($id);
    return view('Admin.service_item.update', ['service_item' => $service_item]);
  }

  public function postUpdateServiceItem(Request $request, $id)
  {
    $service_item = ServiceItem::find($id);
    $rules = [
      'name' => 'required|unique:service_items,name',
    ];
    $messages = [
      'name.required' => trans('valid.name_required'),
      'name.unique' => trans('valid.name_unique'),
    ];

    if ($service_item->name == $request->name) {
      $rules['name'] = 'required';
    }

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      $service_item->name = $request->name;
      $service_item->active = isset($request->active) ? 1 : 0;
      $service_item->updated_by = Auth::guard('web')->user()->id;


      if( $service_item->save() ) {
        return redirect()->route('list_service_item')->with(['status' => 'Service đã được cập nhật thành công ']);
      } else {
        $errors = new MessageBag(['error' => 'Không tạo được content type']);
        return redirect()->back()->withErrors($errors)->withInput();
      }
    }
  }


  public function getListCategoryService($id)
  {
    $list_service_item = ServiceItem::where('active','=',1)->get();
    $list_service_of_cate = CategoryService::where('id_category','=',$id)->pluck('id_service_item')->toArray();
    return view('Admin.category_service.list', [
      'category_id' => $id,
      'list_service_item' => $list_service_item,
      'list_service_of_cate' => $list_service_of_cate,
    ]);
  }

  public function postListCategoryService(Request $request, $category_id)
  {
    $data = CategoryService::where('id_category','=',$category_id)->get();
    if(count($data) == 0)
    {
      foreach ($request->service_item as $value)
      {
        CategoryService::create([
          'id_category' => $category_id,
          'id_service_item' => $value,
        ]);
      }
      return redirect()->route('list_category')->with(['status' => 'Đã cập nhập service thành công']);
    }
    else {
      $service_old = CategoryService::where('id_category','=',$category_id)->pluck('id_service_item')->toArray();
      $service_new = $request->service_item;

      if (!$service_new) {
        $service_new = [];
      }
      $unchecked = array_values(array_diff($service_old, $service_new));
      $addcheck = array_values(array_diff($service_new, $service_old));

      if (!empty($unchecked)) {
        foreach ($unchecked as $value) {
          CategoryService::where([['id_category', '=', $category_id], ['id_service_item', '=', $value]])->delete();
        }
      }
      if (!empty($addcheck)) {
        foreach ($addcheck as $value) {
          CategoryService::create([
            'id_category' => $category_id,
            'id_service_item' => $value,
          ]);
        }
      }
      return redirect()->route('list_category')->with(['status' => 'Service đã được sữa thành công ']);
    }
  }
}
