<?php
/**
 * Created by PhpStorm.
 * User: Pham Trong Hieu
 * Date: 7/3/2017
 * Time: 9:26 AM
 */

namespace App\Http\Controllers\Admin;

use App\Models\Location\BlockText;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Validator;

class MakeMoneySettingController extends BaseController
{
  public function getIndex()
  {
    $arr_make_money = [
      'luu_y_make_money',
      'nap_tien_vao_vi',
      'tieu_de_make_money_ceo',
      'tieu_de_make_money_tdl',
      'tieu_de_make_money_ctv',
    ];
    $all_block_text = BlockText::with('_created_by')
                               ->whereIn('machine_name',$arr_make_money);
    $list_block_text = $all_block_text->paginate(15);
    session()->put("from_setting_make_money",url()->current());
    //session()->pull("from_setting_make_money");
    return view('Admin.setting.make_money', [
          'list_block_text' => $list_block_text,
          'keyword' => '',
          'sort'=> [],
          'qsort'=> ''
       ]);
  }

}
