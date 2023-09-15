<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>

<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <a href="#" onclick="save_facebook_pixel(); return false;" class="btn btn-info">Save Changes</a>
                        <a href="#" onclick="enable_facebook_pixel(); return false;" class="btn btn-info" <?php if (get_option('facebook_pixel') == 'enable') echo 'disabled';?>>Enable Facebook Pixel Support</a>
                        <a href="#" onclick="disable_facebook_pixel(); return false;" class="btn btn-info"<?php if (get_option('facebook_pixel') == 'disable') echo 'disabled';?>>Disable Facebook Pixel Support</a>
                    </div>
                </div>
            </div>
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="bold" for="facebook_pixel_clients_and_admin_area">Facebook Pixel for both Admin & Clients area (frontend & backend) <i class="fa fa-question-circle" data-toggle="tooltip" data-title="If you paste your code here, your Facebook Pixel service will load in Admin area and Customers area aswell."></i></label>
                            <textarea name="facebook_pixel_clients_and_admin_area" id="facebook_pixel_clients_and_admin_area" rows="10" class="form-control"><?php echo clear_textarea_breaks(get_option('facebook_pixel_clients_and_admin_area')); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label class="bold" for="facebook_pixel_admin_area">Facebook Pixel for Admin area only (backend) <i class="fa fa-question-circle" data-toggle="tooltip" data-title="If you paste your code here, your Facebook Pixel service will load in Admin area only."></i></label>
                            <textarea name="facebook_pixel_admin_area" id="facebook_pixel_admin_area" rows="10" class="form-control"><?php echo clear_textarea_breaks(get_option('facebook_pixel_admin_area')); ?></textarea>
                        </div>
                        <div class="form-group">
                            <label class="bold" for="facebook_pixel_clients_area">Facebook Pixel for Clients area only (frontend) <i class="fa fa-question-circle" data-toggle="tooltip" data-title="If you paste your code here, your Facebook Pixel service will load in Customers area only."></i></label>
                            <textarea name="facebook_pixel_clients_area" id="facebook_pixel_clients_area" rows="10" class="form-control"><?php echo clear_textarea_breaks(get_option('facebook_pixel_clients_area')); ?></textarea>
                        </div>
                        <br>
                        <br>
                        <span style="color:rgba(0, 0, 0, 0.5);"><i>Thank you for using our module!
                        <br>
                        If you face any issues, our team is always ready to help you at <a href="https://themesic.com/support" target="_blank"><b>Clients Area</b></a>
                        <br>
                        <br>
                        Rating our module with your honest feedback on CodeCanyon is appreciated in advance and will help us.</i></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript" src="<?php echo $this->config->base_url(); ?>modules/facebook_pixel/js/main.js"></script>
</body>
</html>