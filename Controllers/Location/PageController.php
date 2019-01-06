<?php

namespace App\Http\Controllers\Location;

use App\Models\Location\EmailTemplate;
use App\Models\Location\CustomPage;
use Illuminate\Support\Facades\Mail;

class PageController extends BaseController
{
	public function anyPage($link){

    $arrData['info_phone'] = $this->getSetting('info_phone');
    $arrData['info_mail'] = $this->getSetting('info_mail');

		$this->view->content = view('Location.page.'.$link,$arrData);
		return $this->setContent();
	}

	public function postContactPage($data)
  {

    $mail_template_admin = EmailTemplate::where('machine_name', 'email_contact_admin')->first();
    if($mail_template_admin)
    {
      $data_send_admin = [
        'full_name' => $data['name'],
        'phone' => $data['phone'],
        'email' => 'info@kingmap.vn',
        'content' => $data['content'],
      ];
      Mail::send([], [], function($message) use ($mail_template_admin, $data_send_admin)
      {
        $message->to($data_send_admin['email'], $data_send_admin['full_name'])
          ->subject($mail_template_admin['subject'])
          ->from('kingmapteam@gmail.com', 'KingMap Team')
          ->setBody($mail_template_admin->parse($data_send_admin));
      });
    }

    $mail_template_customer = EmailTemplate::where('machine_name', 'email_contact_customer')->first();
    if($mail_template_customer)
    {
      $data_send_customer = [
        'full_name' => $data['name'],
        'phone' => $data['phone'],
        'email' => $data['email'],
        'content' => $data['content'],
      ];
      Mail::send([], [], function($message) use ($mail_template_customer, $data_send_customer)
      {
        $message->to($data_send_customer['email'], $data_send_customer['full_name'])
          ->subject($mail_template_customer['subject'])
          ->from('kingmapteam@gmail.com', 'KingMap Team')
          ->setBody($mail_template_customer->parse($data_send_customer));
      });
    }
    return redirect('contact');
  }

    public function custom_page($page){
        $custom_page = CustomPage::where('alias',$page)->first();
        if(!$custom_page){
            abort(404);
        }else{
            $this->view->content = $custom_page->content;
            $this->view->title = $custom_page->title;
            return $this->setContent();
        }
    }
}