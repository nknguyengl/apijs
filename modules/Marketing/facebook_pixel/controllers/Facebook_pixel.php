<?php

defined('BASEPATH') or exit('No direct script access allowed');

class facebook_pixel extends AdminController
{
    public function __construct()
    {
        parent::__construct();

        if (!is_admin()) {
            access_denied('Facebook Pixel');
        }
        
        $this->load->helper('/facebook_pixel');
    }

    public function index()
    {
        $data['title'] = _l('facebook_pixel');
        $this->load->view('facebook_pixel', $data);
    }

    public function reset()
    {
        update_option('facebook_pixel', 'enable');
        redirect(admin_url('facebook_pixel'));
    }

    public function save()
    {
        hooks()->do_action('before_save_facebook_pixel');
        
        foreach(['admin_area','clients_area','clients_and_admin'] as $css_area) {
            // Also created the variables
            $$css_area = $this->input->post($css_area, FALSE);
            $$css_area = trim($$css_area);
            $$css_area = nl2br($$css_area);
        }
        
        update_option('facebook_pixel_admin_area', $admin_area);
        update_option('facebook_pixel_clients_area', $clients_area);
        update_option('facebook_pixel_clients_and_admin_area', $clients_and_admin);
    }
    
    public function enable()
    {
        hooks()->do_action('before_save_facebook_pixel');

        update_option('facebook_pixel', 'enable');
    }
    
    public function disable()
    {
        hooks()->do_action('before_save_facebook_pixel');

        update_option('facebook_pixel', 'disable');
    }
}
