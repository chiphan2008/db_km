<?php

namespace App\Http\Controllers\Admin;

use App\Models\Location\Category;
use App\Models\Location\Content;
use App\Models\Location\EmailTemplate;
use App\Models\Location\RoleUser;
use App\Models\Location\User;
use App\Models\Location\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\MessageBag;
use Validator;
use Illuminate\Support\Facades\Auth;

class UserController extends BaseController
{

  public function getListUser(Request $request)
  {
    $per_page = \Session::has('pagination.'.\Route::currentRouteName()) ? session('pagination.'.\Route::currentRouteName()) : 10;
    $list_role = Role::pluck('display_name','id');
    // unset($list_role['1']);

    $all_user = User::select('users.*')->where('users.id', '!=', '1')->with('_role_user');
    $sort = $request->sort?$request->sort:'';
    $input = $request->all();

    if (isset($input['keyword'])) {
      $keyword = $input['keyword'];
    } else {
      $keyword = '';
    }

    if (isset($keyword) && $keyword != '') {

      $all_user->Where(function ($query) use ($keyword) {
        $query->where('full_name', 'LIKE', '%' . $keyword . '%');
        $query->orWhere('email', 'LIKE', '%' . $keyword . '%');
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
        if($key=='role'){
          $all_user->selectRaw('roles.name as role_name')
                    ->leftJoin('role_user','users.id','=','role_user.user_id')
                    ->leftJoin('roles','roles.id','=','role_user.role_id')
                    ->orderBy('role_name',$value);
        }else{
          $all_user->orderBy($key,$value);
        }
      }
    }else{
      $all_user->orderBy('id','desc');
    }

    $list_user = $all_user->paginate($per_page);
    return view('Admin.page_user.list_user_admin', ['list_user' => $list_user, 'keyword' => $keyword, 'list_role' => $list_role, 'sort'=> $arr_sort, 'qsort'=> $sort]);

  }

  public function getThemUser()
  {
    $role = Role::pluck('display_name','id');
    unset($role['1']);

    return view('Admin.page_user.them_user_admin', ['role' => $role]);
  }

  public function postThemUser(Request $request)
  {

    $rules = [
      'full_name' => 'required|min:3|max:150',
      'password' => 'required|min:8|confirmed',
      'email' => 'email'
    ];
    $messages = [
      'full_name.required' => trans('valid.name_required'),
      'full_name.min' => trans('valid.password_min'),
      'full_name.max' => trans('valid.password_max'),
      'password.required' => trans('valid.password_required'),
      'password.min' => trans('valid.password_min'),
      'password.confirmed' => trans('valid.password_confirmed'),
      'email.email' => trans('valid.email_email'),
    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      $user = User::where('email', $request->email)->first();
      if (!$user) {

        if ($request->hasFile('avatar')) {
          $file = $request->file('avatar');
          if (in_array($file->getClientOriginalExtension(), ['gif', 'jpg', 'png'])) {
            $image_user = time() . '_' . $file->getClientOriginalName();
            $file->move('img_user', $image_user);
          } else {
            $errors = new MessageBag(['avatar' => 'Hình ảnh không hợp lệ']);
            return redirect()->back()->withErrors($errors)->withInput();
          }
        } else {
          $image_user = 'default.png';
        }

        $user = User::create([
          'password' => bcrypt($request->password),
          'full_name' => $request->full_name,
          'email' => $request->email,
          'avatar' => $image_user,
          'active' => 1,
          'parent' => 1,
          'created_by' => Auth::guard('web')->user()->id,
          'updated_by' => Auth::guard('web')->user()->id,
        ]);

        $lastIdUser = $user->id;

        RoleUser::create([
          'user_id' => $lastIdUser,
          'role_id' => $request->role,
        ]);

        $mail_template = EmailTemplate::where('machine_name', 'create_user')->first();
        if($mail_template)
        {
          $data = [
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => $request->password,
            'url' => url('/admin/login'),
          ];
          Mail::send([], [], function($message) use ($mail_template, $data)
          {
            $message->to($data['email'], $data['full_name'])
              ->subject($mail_template['subject'])
              ->from('kingmapteam@gmail.com', 'KingMap Team')
              ->setBody($mail_template->parse($data));
          });
        }

        return redirect()->route('list_user')->with(['status' => trans('valid.add_success_user')]);
      } else {
        $errors = new MessageBag(['error' => 'Email đã bị trùng']);
        return redirect()->back()->withErrors($errors)->withInput();
      }
    }
  }

  public function getSuaUser($id)
  {
    $role = Role::pluck('display_name','id');
    unset($role['1']);
    $user = User::find($id);
    $role_of_user = RoleUser::where('user_id','=',$id)->pluck('role_id')->first();
    return view('Admin.page_user.sua_user_admin', ['user' => $user, 'role' => $role, 'role_of_user' => $role_of_user]);
  }

  public function getProfileUser()
  {
    $id = Auth::guard('web')->user()->id;
    $role = Role::pluck('display_name','id');
    unset($role['1']);
    $user = User::find($id);
    $role_of_user = RoleUser::where('user_id','=',$id)->pluck('role_id')->first();
    return view('Admin.page_user.profile', ['user' => $user, 'role' => $role, 'role_of_user' => $role_of_user]);
  }

  public function postSuaUser(Request $request, $id)
  {
    $user_update = User::find($id);

    $rules = [
      'full_name' => 'required|min:3|max:150',
      'password' => 'required|min:8|confirmed',
      'email' => 'email'
    ];
    $messages = [
      'full_name.required' => trans('valid.name_required'),
      'full_name.min' => trans('valid.password_min'),
      'full_name.max' => trans('valid.password_max'),
      'password.required' => trans('valid.password_required'),
      'password.min' => trans('valid.password_min'),
      'password.confirmed' => trans('valid.password_confirmed'),
      'email.email' => trans('valid.email_email'),

    ];
    $validator = Validator::make($request->all(), $rules, $messages);

    if ($validator->fails()) {
      return redirect()->back()->withErrors($validator)->withInput();
    } else {

      if (isset($user_update)) {

        if ($request->hasFile('avatar')) {
          $file = $request->file('avatar');
          if (in_array($file->getClientOriginalExtension(), ['gif', 'jpg', 'png'])) {
            $image_user = time() . '_' . $file->getClientOriginalName();
            if ($file->move('img_user', $image_user)) {
              if ($user_update->avatar != 'default.png') {
                if (file_exists(public_path('img_user/' . $user_update->avatar))) {
                  unlink(public_path('img_user/' . $user_update->avatar));
                }
              }
            }
          } else {
            $errors = new MessageBag(['avatar' => trans('valid.invalid_image')]);
            return redirect()->back()->withErrors($errors)->withInput();
          }
        } else {
          $image_user = $user_update->avatar;
        }

        if ($user_update->password == $request->password) {
          $password = $user_update->password;
        } else {
          $password = bcrypt($request->password);
        }

        $user_update->password = $password;
        $user_update->full_name = $request->full_name;
        $user_update->avatar = $image_user;
        if($user_update->hasRole('super_admin') == false)
        {
          $user_update->active = $request->active;
        }

        $user_update->parent = 1;
        $user_update->updated_by = Auth::guard('web')->user()->id;
        $user_update->save();

        if($user_update->hasRole('super_admin') == false) {
          $role_of_user = RoleUser::where('user_id', '=', $user_update->id)->pluck('role_id')->first();
          if ($role_of_user != $request->role) {
            RoleUser::where('user_id', '=', $user_update->id)->delete();
            RoleUser::create([
              'user_id' => $user_update->id,
              'role_id' => $request->role,
            ]);
          }
        }

        return redirect()->route('list_user')->with(['status' => trans('valid.user').' <a href="' . route('update_user', ['id' => $user_update->id]) . '">' . $user_update->full_name . '</a> '.trans('valid.edit_success')]);
      } else {
        $errors = new MessageBag(['error' => 'User does not exist']);
        return redirect()->back()->withErrors($errors);
      }
    }
  }

  public function getXoaUser($id)
  {
    $user = User::find($id);
    $user_name = $user->full_name;
    $user->delete();
    return redirect()->route('list_user')->with(['status' => trans('valid.user'). $user_name . trans('valid.del_success')]);
  }

  public function getListContentUser(Request $request, $id, $moderation)
  {
    $user = User::find($id);
    $all_content = Content::where([['created_by', '=', $id], ['type_user', '=', 1]])->with('_country')->with('_city')
      ->with('_district')->with('_category_type')->orderBy('created_at','desc');

    if($moderation != 'all')
    {
      $all_content->where('moderation' , '=', $moderation);
    }

    $input = $request->all();
    if (isset($input['keyword'])) {
      $keyword = $input['keyword'];
    } else {
      $keyword = '';
    }

    if (isset($keyword) && $keyword != '') {

      $all_content->Where(function ($query) use ($keyword) {
        $query->where('name', 'LIKE', '%' . $keyword . '%');
        $query->orWhere('alias', 'LIKE', '%' . $keyword . '%');
      });
    }

    if(isset($request->date_from))
    {
      $date_from = $input['date_from'];
    } else {
      $date_from = '';
    }
    if (isset($date_from) && $date_from != '') {
      $all_content->where('created_at' , '>=', $date_from.' 00:00:00');
    }

    if(isset($request->date_to))
    {
      $date_to = $input['date_to'];
    } else {
      $date_to = '';
    }
    if (isset($date_to) && $date_to != '') {
      $all_content->where('created_at' , '<=', $date_to.' 23:59:00');
    }

    if (isset($input['category'])) {
      $category = $input['category'];
    } else {
      $category = '';
    }

    if (isset($category) && $category != '') {
      $all_content->join('category_content', 'contents.id', '=', 'category_content.id_content')
        ->where('contents.id_category', '=', $category)->groupBy('contents.id');
    }

    //dd($all_content);
    $content_of_user = $all_content->paginate(15);

    $list_category = Category::where([['machine_name', '!=', 'service'], ['active', '=', '1']])->pluck('name', 'id');
    return view('Admin.page_user.list_content_user', [
      'content_of_user' => $content_of_user,
      'user' => $user,
      'moderation' => $moderation,
      'list_category' => $list_category,
      'keyword' => $keyword,
      'category' => $category,
      'date_from' => $date_from,
      'date_to' => $date_to,
      ]);
  }

  public function getContentUser(Request $request, $moderation)
  {

    $id = Auth::guard('web')->user()->id;
    $user = User::find($id);
    $all_content = Content::where([['created_by', '=', $id], ['type_user', '=', 1]])->with('_country')->with('_city')
      ->with('_district')->with('_category_type')->orderBy('created_at','desc');

    if($moderation != 'all')
    {
      $all_content->where('moderation' , '=', $moderation);
    }

    $input = $request->all();
    if (isset($input['keyword'])) {
      $keyword = $input['keyword'];
    } else {
      $keyword = '';
    }

    if (isset($keyword) && $keyword != '') {

      $all_content->Where(function ($query) use ($keyword) {
        $query->where('name', 'LIKE', '%' . $keyword . '%');
        $query->orWhere('alias', 'LIKE', '%' . $keyword . '%');
      });
    }

    if(isset($request->date_from))
    {
      $date_from = $input['date_from'];
    } else {
      $date_from = '';
    }
    if (isset($date_from) && $date_from != '') {
      $all_content->where('created_at' , '>=', $date_from.' 00:00:00');
    }

    if(isset($request->date_to))
    {
      $date_to = $input['date_to'];
    } else {
      $date_to = '';
    }
    if (isset($date_to) && $date_to != '') {
      $all_content->where('created_at' , '<=', $date_to.' 23:59:00');
    }

    if (isset($input['category'])) {
      $category = $input['category'];
    } else {
      $category = '';
    }

    if (isset($category) && $category != '') {
      $all_content->join('category_content', 'contents.id', '=', 'category_content.id_content')
        ->where('contents.id_category', '=', $category)->groupBy('contents.id');
    }

    $content_of_user = $all_content->paginate(15);
    $list_category = Category::where([['machine_name', '!=', 'service'], ['active', '=', '1']])->pluck('name', 'id');
    return view('Admin.page_user.content_user', [
      'content_of_user' => $content_of_user,
      'user' => $user,
      'moderation' => $moderation,
      'list_category' => $list_category,
      'keyword' => $keyword,
      'category' => $category,
      'date_from' => $date_from,
      'date_to' => $date_to,
    ]);
  }
}
