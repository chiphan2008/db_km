<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\EmailTemplate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class EmailTemplateController extends BaseController
{
  public function getListEmailTemplate(Request $request)
  {
    $all_email = DB::table('email_templates');
    $sort = $request->sort?$request->sort:'';
    $input = $request->all();

    if (isset($input['keyword'])) {
      $keyword = $input['keyword'];
    } else {
      $keyword = '';
    }

    if (isset($keyword) && $keyword != '') {

      $all_email->Where(function ($query) use ($keyword) {
        $query->where('name', 'LIKE', '%' . $keyword . '%');
        $query->orWhere('machine_name', 'LIKE', '%' . $keyword . '%');
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
        $all_email->orderBy($key,$value);
      }
    }else{
      $all_email->orderBy('id','desc');
    }

    $list_email = $all_email->paginate(10);
    return view('Admin.email_template.list', [
                                  'list_email' => $list_email,
                                  'keyword' => $keyword,
                                  'sort'=> $arr_sort, 'qsort'=> $sort
                                ]);

  }

  public function getAddEmailTemplate()
  {
    return view('Admin.email_template.add');
  }

  public function postAddEmailTemplate(Request $request)
  {
    $rules = [
      'name' => 'required',
      'machine_name' => 'required',
      'subject' => 'required',
      'body' => 'required',
    ];

    $messages = [
      'name.required' => trans('valid.name_required'),
      'machine_name' => 'Tên máy là trường bắt buộc',
      'subject.required' => 'Subject là trường bắt buộc',
      'body.required' => 'Nội dung Tên là trường bắt buộc',
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      EmailTemplate::create([
        'name' => $request->name,
        'machine_name' => $request->machine_name,
        'subject' => $request->subject,
        'body' => $request->body,
      ]);
      return redirect()->route('list_email')->with(['status' => 'Email Template đã được thêm thành công ']);

    }
  }

  public function getUpdateEmailTemplate($id)
  {
    $email = EmailTemplate::find($id);
    return view('Admin.email_template.update', ['email' => $email]);
  }

  public function postUpdateEmailTemplate(Request $request, $id)
  {
    $email = EmailTemplate::find($id);
    $rules = [
      'name' => 'required',
      'subject' => 'required',
      'body' => 'required',
    ];

    $messages = [
      'name.required' => trans('valid.name_required'),
      'subject.required' => 'Subject là trường bắt buộc',
      'body.required' => 'Nội dung Tên là trường bắt buộc',
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      $email->name = $request->name;
      $email->subject = $request->subject;
      $email->body = $request->body;
      $email->save();
      return redirect()->route('list_email')->with(['status' => 'Email Template đã được sữa thành công ']);

    }
  }

  public function getDeleteEmailTemplate($id)
  {
    $email = EmailTemplate::find($id);
    $email_name = $email->name;
    $email->delete();
    return redirect()->route('list_email')->with(['status' => 'Email Template ' . $email_name . ' đã xóa thành công ']);

  }
}
