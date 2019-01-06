<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\City;
use App\Models\Location\Country;
use App\Models\Location\District;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class LocationController extends BaseController
{
  // list country.
  public function getListCountry(Request $request)
  {

    $all_country = DB::table('countries');
    $sort = $request->sort?$request->sort:'';
    $input = request()->all();

    if (isset($input['keyword'])) {
      $keyword = $input['keyword'];
    } else {
      $keyword = '';
    }

    if (isset($keyword) && $keyword != '') {

      $all_country->Where(function ($query) use ($keyword) {
        $query->where('name', 'LIKE', '%' . $keyword . '%');
        $query->orWhere('zipcode', 'LIKE', '%' . $keyword . '%');
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
        $all_country->orderBy($key,$value);
      }
    }else{
      $all_country->orderBy('weight','asc');
    }

    $list_country = $all_country->paginate(10);
    return view('Admin.page_location.list_country', ['list_country' => $list_country,
      'keyword' => $keyword, 'sort'=> $arr_sort, 'qsort'=> $sort]);

  }

  public function getAddCountry()
  {
    return view('Admin.page_location.add_country');
  }

  public function postAddCountry(Request $request)
  {
    $rules = [
      'name' => 'required|unique:countries,name',
      'machine_name' => 'required|unique:countries,machine_name',
      'alias' => 'required|unique:countries,alias',
      'zipcode' => 'numeric',
    ];
    if ($request->zipcode == '') {
      unset($rules['zipcode']);
    }
    $messages = [
      'name.required' => trans('valid.name_required'),
      'name.unique' => trans('valid.name_unique'),
      'machine_name.required' => trans('valid.machine_name_required'),
      'machine_name.unique' => trans('valid.machine_name_unique'),
      'alias.required' => trans('valid.alias_required'),
      'alias.unique' => trans('valid.alias_unique'),
      'zipcode.numeric' => trans('valid.zipcode_numeric'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      Country::create([
        'name' => $request->name,
        'machine_name' => $request->machine_name,
        'alias' => $request->alias,
        'zipcode' => $request->zipcode,
      ]);
      return redirect()->route('list_country')->with(['status' => trans('valid.added_country')]);

    }
  }

  public function getUpdateCountry($id)
  {
    $country = Country::find($id);
    return view('Admin.page_location.update_country', ['country' => $country]);
  }

  public function postUpdateCountry(Request $request, $id)
  {
    $country = Country::find($id);
    $rules = [
      'name' => 'required|unique:countries,name',
      'zipcode' => 'numeric',
      'alias' => 'required|unique:countries,alias',
    ];

    if ($country->name == $request->name) {
      $rules['name'] = 'required';
    }
    if ($country->alias == $request->alias) {
      $rules['alias'] = 'required';
    }
    if ($request->zipcode == '') {
      unset($rules['zipcode']);
    }

    $messages = [
      'name.required' => trans('valid.name_required'),
      'name.unique' => trans('valid.name_unique'),
      'alias.required' => trans('valid.alias_required'),
      'alias.unique' => trans('valid.alias_unique'),
      'zipcode.numeric' => trans('valid.zipcode_numeric'),
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      $country->name = $request->name;
      $country->alias = $request->alias;
      $country->zipcode = $request->zipcode;
      $country->save();
      return redirect()->route('list_country')->with(['status' => 'Country đã được sữa thành công ']);

    }
  }

  public function getAddCountry1()
  {
    return view('Admin.page_location.add_country');
  }

  public function postAddCountry1(Request $request)
  {
    $rules = [
      'name' => 'required',
      'machine_name' => 'required',
      'alias' => 'required',
      'zipcode' => 'numeric',
    ];
    if ($request->zipcode == '') {
      unset($rules['zipcode']);
    }
    $messages = [
      'name.required' => trans('valid.name_required'),
      'machine_name.required' => trans('valid.machine_name_required'),
      'alias.required' => trans('valid.alias_required'),
      'zipcode.numeric' => trans('valid.zipcode_numeric'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      Country::create([
        'name' => $request->name,
        'machine_name' => $request->machine_name,
        'alias' => $request->alias,
        'zipcode' => $request->zipcode,
      ]);
      return redirect()->route('list_country')->with(['status' => trans('valid.added_country')]);

    }
  }

  public function getUpdateCountry1($id)
  {
    $country = Country::find($id);
    return view('Admin.page_location.update_country', ['country' => $country]);
  }

  public function postUpdateCountry1(Request $request, $id)
  {
    $country = Country::find($id);
    $rules = [
      'name' => 'required',
      'zipcode' => 'numeric',
      'alias' => 'required',
    ];

    if ($country->name == $request->name) {
      $rules['name'] = 'required';
    }
    if ($country->alias == $request->alias) {
      $rules['alias'] = 'required';
    }
    if ($request->zipcode == '') {
      unset($rules['zipcode']);
    }

    $messages = [
      'name.required' => trans('valid.name_required'),
      'alias.required' => trans('valid.alias_required'),
      'zipcode.numeric' => trans('valid.zipcode_numeric'),
    ];

    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      $country->name = $request->name;
      $country->alias = $request->alias;
      $country->zipcode = $request->zipcode;
      $country->save();
      return redirect()->route('list_country')->with(['status' => 'Country đã được sữa thành công ']);

    }
  }

  public function getDeleteCountry($id)
  {
    $check_city = City::where('id_country', '=', $id)->first();
    $country = Country::find($id);
    $country_name = $country->name;
    if (!$check_city) {
      $country->delete();
      return redirect()->route('list_country')->with(['status' => 'Country ' . $country_name . ' đã xóa thành công ']);
    } else {
      return redirect()->route('list_country')->with(['status' => 'Country ' . $country_name . ' đã xóa không thành công ']);
    }
  }


  // list city.
  public function getListCityCondition(Request $request, $id)
  {

    $all_city = City::where('id_country', '=', $id);
    $sort = $request->sort?$request->sort:'';
    $input = request()->all();

    if (isset($input['keyword'])) {
      $keyword = $input['keyword'];
    } else {
      $keyword = '';
    }

    if (isset($keyword) && $keyword != '') {

      $all_city->Where(function ($query) use ($keyword) {
        $query->where('name', 'LIKE', '%' . $keyword . '%');
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
        $all_city->orderBy($key,$value);
      }
    }else{
      $all_city->orderBy('weight','asc');
    }
    $list_city = $all_city->paginate(10);
    return view('Admin.page_location.list_city', ['list_city' => $list_city,
      'keyword' => $keyword, 'country_id' => $id, 'sort'=> $arr_sort, 'qsort'=> $sort]);

  }

  public function getAddCity($id)
  {
    return view('Admin.page_location.add_city', ['country_id' => $id]);
  }

  public function postAddCity(Request $request, $id)
  {
    $rules = [
      'name' => 'required|unique:cities,name',
      'machine_name' => 'required|unique:cities,machine_name',
      'alias' => 'required|unique:cities,alias',
    ];

    $messages = [
      'name.required' => trans('valid.name_required'),
      'name.unique' => trans('valid.name_unique'),
      'machine_name.required' => trans('valid.machine_name_required'),
      'machine_name.unique' => trans('valid.machine_name_unique'),
      'name.unique' => trans('valid.name_unique'),
      'alias.unique' => trans('valid.alias_unique'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      City::create([
        'name' => $request->name,
        'machine_name' => $request->machine_name,
        'alias' => $request->alias,
        'id_country' => $id,
      ]);
      return redirect()->route('list_city', ['id' => $id])->with(['status' => 'Country đã được thêm thành công ']);

    }
  }

  public function getUpdateCity($id, $id_city)
  {
    $city = City::find($id_city);
    return view('Admin.page_location.update_city', ['city' => $city, 'id' => $id]);
  }

  public function postUpdateCity(Request $request, $id, $id_city)
  {
    $city = City::find($id_city);

    $rules = [
      'name' => 'required|unique:cities,name',
      'alias' => 'required|unique:cities,alias',
    ];

    if ($city->name == $request->name) {
      $rules['name'] = 'required';
    }

    if ($city->alias == $request->alias) {
      $rules['alias'] = 'required';
    }

    $messages = [
      'name.required' => trans('valid.name_required'),
      'name.unique' => trans('valid.name_unique'),
      'alias.required' => trans('valid.alias_required'),
      'alias.unique' => trans('valid.alias_unique'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      $city->name = $request->name;
      $city->alias = $request->alias;
      $city->save();
      return redirect()->route('list_city', ['id' => $id])->with(['status' => 'City đã được sữa thành công ']);

    }
  }

  public function getAddCity1($id)
  {
    return view('Admin.page_location.add_city', ['country_id' => $id]);
  }

  public function postAddCity1(Request $request, $id)
  {
    $rules = [
      'name' => 'required',
      'machine_name' => 'required',
      'alias' => 'required',
    ];

    $messages = [
      'name.required' => trans('valid.name_required'),
      'alias.required' => trans('valid.alias_required'),
      'machine_name.required' => trans('valid.machine_name_required'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      City::create([
        'name' => $request->name,
        'machine_name' => $request->machine_name,
        'alias' => $request->alias,
        'id_country' => $id,
      ]);
      return redirect()->route('list_city', ['id' => $id])->with(['status' => 'Country đã được thêm thành công ']);

    }
  }

  public function getUpdateCity1($id, $id_city)
  {
    $city = City::find($id_city);
    return view('Admin.page_location.update_city', ['city' => $city, 'id' => $id]);
  }

  public function postUpdateCity1(Request $request, $id, $id_city)
  {
    $city = City::find($id_city);

    $rules = [
      'name' => 'required',
      'alias' => 'required',
    ];

    if ($city->name == $request->name) {
      $rules['name'] = 'required';
    }

    if ($city->alias == $request->alias) {
      $rules['alias'] = 'required';
    }

    $messages = [
      'name.required' => trans('valid.name_required'),
      'alias.required' => trans('valid.alias_required'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      $city->name = $request->name;
      $city->alias = $request->alias;
      $city->save();
      return redirect()->route('list_city', ['id' => $id])->with(['status' => 'City đã được sữa thành công ']);

    }
  }

  public function getDeleteCity($id, $city_id)
  {
    $check_district = District::where('id_city', '=', $city_id)->first();

    $city = City::find($city_id);
    $city_name = $city->name;
    if (!$check_district) {
      $city->delete();
      return redirect()->route('list_city', ['id' => $id])->with(['status' => 'City ' . $city_name . ' đã xóa thành công ']);
    } else {
      return redirect()->route('list_city', ['id' => $id])->with(['status' => 'City ' . $city_name . ' đã xóa không thành công ']);
    }
  }

  // list district
  public function getListDistrictCondition(Request $request, $id)
  {
    $country_id = City::find($id)->id_country;
    $all_district = District::where('id_city', '=', $id);
    $sort = $request->sort?$request->sort:'';
    $input = request()->all();

    if (isset($input['keyword'])) {
      $keyword = $input['keyword'];
    } else {
      $keyword = '';
    }

    if (isset($keyword) && $keyword != '') {

      $all_district->Where(function ($query) use ($keyword) {
        $query->where('name', 'LIKE', '%' . $keyword . '%');
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
        $all_district->orderBy($key,$value);
      }
    }else{
      $all_district->orderBy('weight','asc');
    }

    $list_district = $all_district->paginate(10);
    return view('Admin.page_location.list_district', ['list_district' => $list_district,
      'keyword' => $keyword, 'city_id' => $id, 'country_id' => $country_id, 'sort'=> $arr_sort, 'qsort'=> $sort]);

  }

  public function getAddDistrict($id)
  {
    return view('Admin.page_location.add_district', ['city_id' => $id]);
  }

  public function postAddDistrict(Request $request, $id)
  {
    $rules = [
      'name' => 'required|unique:districts,name',
      'machine_name' => 'required|unique:districts,machine_name',
      'alias' => 'required|unique:districts,alias',
    ];

    $messages = [
      'name.required' => 'Tên quận là trường bắt buộc',
      'name.unique' => trans('valid.name_unique'),
      'machine_name.required' => trans('valid.machine_name_required'),
      'machine_name.unique' => trans('valid.machine_name_unique'),
      'alias.required' => trans('valid.alias_required'),
      'alias.unique' => trans('valid.alias_unique'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      District::create([
        'name' => $request->name,
        'machine_name' => $request->machine_name,
        'alias' => $request->alias,
        'id_city' => $id,
      ]);
      return redirect()->route('list_district', ['id' => $id])->with(['status' => 'Quận đã được thêm thành công ']);

    }
  }

  public function getUpdateDistrict($id, $id_district)
  {
    $district = District::find($id_district);
    return view('Admin.page_location.update_district', ['district' => $district, 'id' => $id]);
  }

  public function postUpdateDistrict(Request $request, $id, $id_district)
  {
    $district = District::find($id_district);

    $rules = [
      'name' => 'required|unique:districts,name',
      'alias' => 'required|unique:districts,alias',
    ];

    if ($district->name == $request->name) {
      $rules['name'] = 'required';
    }

    if ($district->alias == $request->alias) {
      $rules['alias'] = 'required';
    }

    $messages = [
      'name.required' => 'Tên quận là trường bắt buộc',
      'name.unique' => trans('valid.name_unique'),
      'alias.required' => trans('valid.alias_required'),
      'alias.unique' => trans('valid.alias_unique'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      $district->name = $request->name;
      $district->alias = $request->alias;
      $district->save();
      return redirect()->route('list_district', ['id' => $id])->with(['status' => 'Quận đã được sữa thành công ']);

    }
  }

  public function getAddDistrict1($id)
  {
    return view('Admin.page_location.add_district', ['city_id' => $id]);
  }

  public function postAddDistrict1(Request $request, $id)
  {
    $rules = [
      'name' => 'required',
      'machine_name' => 'required',
      'alias' => 'required',
    ];

    $messages = [
      'name.required' => 'Tên quận là trường bắt buộc',
      'machine_name.required' => trans('valid.machine_name_required'),
      'alias.required' => trans('valid.alias_required'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      District::create([
        'name' => $request->name,
        'machine_name' => $request->machine_name,
        'alias' => $request->alias,
        'id_city' => $id,
      ]);
      return redirect()->route('list_district', ['id' => $id])->with(['status' => 'Quận đã được thêm thành công ']);

    }
  }

  public function getUpdateDistrict1($id, $id_district)
  {
    $district = District::find($id_district);
    return view('Admin.page_location.update_district', ['district' => $district, 'id' => $id]);
  }

  public function postUpdateDistrict1(Request $request, $id, $id_district)
  {
    $district = District::find($id_district);

    $rules = [
      'name' => 'required',
      'alias' => 'required',
    ];

    if ($district->name == $request->name) {
      $rules['name'] = 'required';
    }

    if ($district->alias == $request->alias) {
      $rules['alias'] = 'required';
    }

    $messages = [
      'name.required' => trans('valid.name_required'),
      'alias.required' => trans('valid.alias_required'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      $district->name = $request->name;
      $district->alias = $request->alias;
      $district->save();
      return redirect()->route('list_district', ['id' => $id])->with(['status' => 'Quận đã được sữa thành công ']);

    }
  }

  public function getDeleteDistrict($id, $district_id)
  {
    $district = District::find($district_id);
    $district_name = $district->name;
    $district->delete();
    return redirect()->route('list_district', ['id' => $id])->with(['status' => 'Quận ' . $district_name . ' đã xóa thành công ']);
  }

  public function getImportDistrict($id_city){
    return view('Admin.page_location.import_district', ['id_city' => $id_city]);
  }

  public function postImportDistrict(Request $request, $id_city){
    if($request->list_district){
      $list_district = explode("\r\n",$request->list_district);
      $check = true;
      foreach ($list_district as $key => $name) {
        $district = new District();
        $district->name = $name;
        $district->id_city = $id_city;
        $district->alias = str_slug($name);
        $district->machine_name = str_replace('-','_',str_slug($name));
        $check = $check && $district->save();
      }
      if($check){
        echo "<p>Thêm thành công</p>";
        echo '<p><a href="" title="/admin/location/import-district/'.$id_city.'">Back</a></p>';
      }else{
        echo "<p>Thêm thất bại</p>";
        echo '<p><a href="" title="/admin/location/import-district/'.$id_city.'">Back</a></p>';
      }
    }
  }

  public function getChangeWeightCountry($id,$weight){
    $country = Country::find($id);
    if($country){
      $country->weight = $weight;
      $country->save();
      return redirect()->route('list_country');
    }
  }

  public function getChangeWeightCity($country_id,$id,$weight){
    $city = City::find($id);
    if($city){
      $city->weight = $weight;
      $city->save();
      return redirect()->route('list_city',['country_id' => $country_id]);
    }
  }

  public function getChangeWeightDistrict($city_id,$id,$weight){
    $district = District::find($id);
    if($district){
      $district->weight = $weight;
      $district->save();
      return redirect()->route('list_district',['city_id' => $city_id]);
    }
  }
}
