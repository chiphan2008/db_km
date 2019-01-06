<?php
/**
 * Created by PhpStorm.
 * User: Pham Trong Hieu
 * Date: 7/3/2017
 * Time: 9:26 AM
 */

namespace App\Http\Controllers\Admin;

use App\Models\Location\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class SettingController extends BaseController
{
  public function getIndex()
  {

    $list_setting = Setting::all()->pluck('value', 'key');
    return view('Admin.setting.list', ['list_setting' => $list_setting]);
  }

  public function postSaveSetting(Request $request)
  {
    $data = $request->all();

    $check_data  = Setting::where('key', '=', 'site_name')->pluck('value')->first();

    unset($data['_token']);
    if ($request->file('favicon')) {
      $path = public_path() . '/upload/setting/';
      $file = $request->file('favicon');
      if (!\File::exists($path)) {
        \File::makeDirectory($path, $mode = 0777, true, true);
      }
      $name = time() . '-favicon.' . $file->getClientOriginalExtension();
      $file->move($path, $name);
      $favicon = '/upload/setting/' . $name;
    } else {
      $favicon = Setting::where('key', '=', 'favicon')->pluck('value')->first();
      $favicon = isset($favicon) ? $favicon : '';
    }

    if ($request->file('logo')) {
      $path = public_path() . '/upload/setting/';
      $file = $request->file('logo');
      if (!\File::exists($path)) {
        \File::makeDirectory($path, $mode = 0777, true, true);
      }
      $name = time() . '-logo.' . $file->getClientOriginalExtension();
      $file->move($path, $name);
      $logo = '/upload/setting/' . $name;

    } else {
      $logo = Setting::where('key', '=', 'logo')->pluck('value')->first();
      $logo = isset($logo) ? $logo : '';
    }

    $data['favicon'] = $favicon;
    $data['logo'] = $logo;

    if(isset($check_data)){
      foreach ($data as $k => $v){
        $setting = Setting::where("key","=",$k)->first();
        if(isset($setting))
        {
          $setting->value = isset($v) ? $v : '';
          $setting->save();
        }
        else {
          Setting::create([
            'key' => $k,
            'value' => isset($v) ? $v : '',
          ]);
        }
      }
      return redirect()->route('list_setting')->with(['status' => trans('valid.update_success_setting')]);
    }
    else {
      foreach ($data as $k => $v){
        Setting::create([
          'key' => $k,
          'value' => isset($v) ? $v : '',
        ]);
      }
      return redirect()->route('list_setting')->with(['status' => trans('valid.update_success_setting')]);
    }
  }

}
