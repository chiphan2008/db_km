<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\CustomPage;
use App\Models\Location\CustomPageLanguage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\MessageBag;
use Validator;

class CustomPageController extends BaseController
{
   public function getListCustomPage(Request $request)
   {
     $all_custompage = CustomPage::with('_created_by');
     $input = $request->all();

     if (isset($input['keyword'])) {
       $keyword = $input['keyword'];
     } else {
       $keyword = '';
     }

     if (isset($keyword) && $keyword != '') {

       $all_custompage->Where(function ($query) use ($keyword) {
         $query->where('name', 'LIKE', '%' . $keyword . '%');
       });
     }

     $list_custompage = $all_custompage->paginate(10);

     return view('Admin.custom_page.list', ['list_custompage' => $list_custompage,
       'keyword' => $keyword]);
   }

   public function getAddCustomPage()
   {
     return view('Admin.custom_page.add');
   }

   public function postAddCustomPage(Request $request)
   {
     $rules = [
       'title' => 'required',
       'machine_name' => 'required|unique:custom_pages,machine_name',
       'alias'=> 'required|unique:custom_pages,alias',
     ];
     $messages = [
       'title.required' => trans('valid.title_required'),
       'machine_name.required' => trans('valid.machine_name_required'),
       'machine_name.unique' => trans('valid.machine_name_unique'),
       'alias.required' => trans('valid.alias_required'),
       'alias.unique'=>'Tên đường dẫn đã tồn tại',
     ];
     $validator = Validator::make($request->all(), $rules, $messages);

     if ($validator->fails()) {
       return redirect()->back()->withErrors($validator)->withInput();
     } else {
       $custom_page = CustomPage::create([
         'title' => $request->title,
         'machine_name' => $request->machine_name,
         'alias' => $request->alias,
         'content' => $request->content_custom,
         'status' => isset($request->active) ? 1 : 0,
         'created_by' => Auth::guard('web')->user()->id,
         'updated_by' => Auth::guard('web')->user()->id,
       ]);

       return redirect()->route('list_custom_page')->with(['status' => 'Trang đã được thêm thành công ']);
     }
   }

   public function getUpdateCustomPage($id)
   {
     $custom_page = CustomPage::find($id);
     return view('Admin.custom_page.update', ['custom_page' => $custom_page]);
   }

   public function postUpdateCustomPage(Request $request, $id)
   {
     $custom_page = CustomPage::find($id);
     $rules = [
       'title' => 'required',
       'alias'=> 'required|unique:custom_pages,alias',
     ];
     $messages = [
       'title.required' => trans('valid.title_required'),
       'alias.required' => trans('valid.alias_required'),
       'alias.unique'=>'Tên đường dẫn đã tồn tại',
     ];

     if ($custom_page->alias == $request->alias) {
       $rules['alias'] = 'required';
     }


     $validator = Validator::make($request->all(), $rules, $messages);

     if ($validator->fails()) {
       return redirect()->back()->withErrors($validator)->withInput();
     } else {

       $custom_page->title = $request->title;
       $custom_page->alias = $request->alias;
       $custom_page->content = $request->content_custom;
       $custom_page->status = isset($request->active) ? 1 : 0;
       $custom_page->created_by = Auth::guard('web')->user()->id;

       if($custom_page->save() ) {
         return redirect()->route('list_custom_page')->with(['status' => 'Trang đã được cập nhật thành công ']);
       } else {
         $errors = new MessageBag(['error' => 'Không tạo được trang']);
         return redirect()->back()->withErrors($errors)->withInput();
       }
     }
   }

   public function getCustomPageLang($id, $lang)
   {
     $custom_page = CustomPageLanguage::where([['id_custom_page','=',$id],['lang','=',$lang]])->first();
     if(isset($custom_page))
     {
       return view('Admin.custom_page.add_language', ['id' => $id,'lang' => $lang,'custom_page' => $custom_page]);
     }else {
       return view('Admin.custom_page.add_language', ['id' => $id,'lang' => $lang]);
     }
   }

   public function postCustomPageLang(Request $request, $id, $lang)
   {
     $custom_page = CustomPageLanguage::where([['id_custom_page','=',$id],['lang','=',$lang]])->first();
     if(isset($custom_page))
     {
       $rules = [
         'title' => 'required',
         'content_custom' => 'required',

       ];
       $messages = [
         'title.required' => trans('valid.title_required'),
         'content_custom.required'=>'Nội dung là trường bắt buộc',
       ];

       $validator = Validator::make($request->all(), $rules, $messages);

       if ($validator->fails()) {
         return redirect()->back()->withErrors($validator)->withInput();
       } else {

         $custom_page->title = $request->title;
         $custom_page->content = $request->content_custom;

         if($custom_page->save() ) {
           return redirect()->route('list_custom_page')->with(['status' => 'Trang đã được cập nhật thành công ']);
         } else {
           $errors = new MessageBag(['error' => 'Không tạo được trang']);
           return redirect()->back()->withErrors($errors)->withInput();
         }
       }

     }else {
       $rules = [
         'title' => 'required',
         'content_custom' => 'required',

       ];
       $messages = [
         'title.required' => trans('valid.title_required'),
         'content_custom.required'=>'Nội dung là trường bắt buộc',
       ];
       $validator = Validator::make($request->all(), $rules, $messages);

       if ($validator->fails()) {
         return redirect()->back()->withErrors($validator)->withInput();
       } else {
         $custom_page_lang = CustomPageLanguage::create([
           'id_custom_page' => $id,
           'title' => $request->title,
           'content' => $request->content_custom,
           'lang' => $lang,
         ]);

         return redirect()->route('list_custom_page')->with(['status' => 'Trang đã được dịch thành công ']);
       }
     }
   }
}
