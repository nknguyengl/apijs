<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="mtop40">
   <div class="col-md-4 col-md-offset-4 text-center">
      <h1 class="text-uppercase mbot20 login-heading">
         <?php
            echo _l(get_option('allow_registration') == 1 ? 'clients_login_heading_register': 'clients_login_heading_no_register');
         ?>
      </h1>
   </div>
   <div class="col-md-4 col-md-offset-4 col-sm-8 col-sm-offset-2">
      <?php echo form_open($this->uri->uri_string(),array('class'=>'login-form')); ?>
      <?php hooks()->do_action('clients_login_form_start'); ?>
      <div class="panel_s">
         <div class="panel-body">
            <div class="form-group">
               <label for="email"><?php echo _l('clients_login_email'); ?></label>
               <input type="text" autofocus="true" class="form-control" name="email" id="email">
               <?php echo form_error('email'); ?>
            </div>
            <div class="form-group">
               <label for="password"><?php echo _l('clients_login_password'); ?></label>
               <input type="password" class="form-control" name="password" id="password">
               <?php echo form_error('password'); ?>
            </div>
            <?php if(show_recaptcha_in_customers_area()){ ?>
            <div class="g-recaptcha mbot15" data-sitekey="<?php echo get_option('recaptcha_site_key'); ?>"></div>
            <?php echo form_error('g-recaptcha-response'); ?>
            <?php } ?>
            <div class="checkbox">
               <input type="checkbox" name="remember" id="remember">
               <label for="remember">
               <?php echo _l('clients_login_remember'); ?>
               </label>
            </div>
            <div class="form-group">
               <button type="submit" class="btn btn-info btn-block"><?php echo _l('clients_login_login_string'); ?></button>
               <?php if(get_option('allow_registration') == 1) { ?>
               
                  <a href="<?php echo site_url('authentication/register'); ?>" class="btn btn-success btn-block"><?php echo _l('clients_register_string'); ?>
                  </a>

                  <?php if(get_option('social_media_login_module_status') == "Active"){ ?>
                  
				  <div class="social_icon">
				  <br><br>
				  <?php echo _l('log_in_using_social_media'); ?>
				  </div>
					  
                     <div class="social_icon">
					 
                        <?php if(get_option('google_btn_status') == "Active") { ?>
                           <a href="<?php echo site_url('social_media_login/google_login'); ?>" class="border border-secondary p-1 rounded"><img class="" src="<?php echo module_dir_url('social_media_login','/assets/images/google.svg');?>">
                           </a>
                        <?php } ?>
                        <?php if(get_option('facebook_btn_status') == "Active") { ?>
                           <a href="<?php echo site_url('social_media_login/facebook_login'); ?>" class="border border-secondary mr-3 p-1 rounded"> <img class="" src="<?php echo module_dir_url('social_media_login','/assets/images/facebook.svg');?>">
                           </a>
                        <?php } ?>
                        <?php if(get_option('linkedin_btn_status') == "Active") { ?>
                           <a href="<?php echo site_url('social_media_login/linkedin_login'); ?>" class="border border-secondary mr-3 p-1 rounded"><img class="" src="<?php echo module_dir_url('social_media_login','/assets/images/linkedin.svg');?>">
                           </a>
                        <?php } ?>
                        <?php if(get_option('twitter_btn_status') == "Active") { ?>
                           <a href="<?php echo site_url('social_media_login/twitter_login'); ?>" class="border border-secondary mr-3 p-1 rounded"><img class="" src="<?php echo module_dir_url('social_media_login','/assets/images/twitter.svg');?>">
                           </a>
                        <?php } ?>
                     </div>
                  <?php } ?>

               <?php } ?>
			   
            </div>
            <a href="<?php echo site_url('authentication/forgot_password'); ?>"><?php echo _l('customer_forgot_password'); ?></a>
            <?php hooks()->do_action('clients_login_form_end'); ?>
            <?php echo form_close(); ?>
         </div>
      </div>
   </div>
</div>

<link href="<?php echo module_dir_url('social_media_login', 'assets/css/custom.css'); ?>" rel="stylesheet">