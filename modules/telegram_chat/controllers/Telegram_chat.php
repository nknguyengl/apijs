<?php

defined('BASEPATH') or exit('No direct script access allowed');
set_time_limit(0);
// define('SURVEYS_MODULE_NAME', 'telegram');
class Telegram_chat extends AdminController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('telegram_model');
    }

    /* List all surveys */
    public function index()
    {
        // $body = 'This is test SMTP email. <br />If you received this message that means that your SMTP settings is set correctly.';
        // $demo = get_option('email_header') .$body  . get_option('email_footer');
        // send_message_telegram($body);
        // echo $demo; exit;

        $currentUserID = $GLOBALS['current_user']->staffid;
        $userTelegramInfo = $this->telegram_model->get($currentUserID);
        $data['title'] = 'Telegram';
        $data['userTeleInfo'] = $userTelegramInfo;
        $this->load->view('telegram_chat/index', $data);
    }

    function addTelegramInfo() {
        $currentUserID = $GLOBALS['current_user']->staffid;
        $obj = array(
            'chat_id' =>    $this->input->post('chat_id'), 
            'bot_token'=>   $this->input->post('bot_token'),
            'user_id' =>    $GLOBALS['current_user']->staffid,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
    );
    $userInfo = $this->telegram_model->get($currentUserID);
    if(isset($userInfo) && $userInfo->id) {
        $this->telegram_model->update($obj, $currentUserID);
    }else {
        $this->telegram_model->add($obj);
    }
        // $this->load->view('telegram_chat/index', $data);
        redirect('/telegram_chat');
    }

    
}