<?php defined('BASEPATH') or exit('No direct script access allowed');
$__zoomAppId = get_option('zmm_app_id');
$_zoomAppSecret = get_option('zmm_app_secret');
$_zoomAppRedirectUri = get_option('zmm_app_redirect_uri');

if (is_admin()) : ?>
     <h4>Zoom API Settings</h4>
     <hr>
     <div class="form-group">
          <label for="zmm_app_id"><?= _l('zmm_app_id_label'); ?></label>
          <input type="text" class="form-control" value="<?= $__zoomAppId; ?>" id="zmm_app_id" name="settings[zmm_app_id]">
     </div>
     <div class="form-group">
          <label for="zmm_app_secret"><?= _l('zmm_app_secret_label'); ?></label>
          <input type="text" class="form-control" value="<?= $_zoomAppSecret; ?>" id="zmm_app_secret" name="settings[zmm_app_secret]">
     </div>
     <div class="form-group">
          <div class="alert alert-info alert-dismissible mtop15" role="alert">
               <?= _l('zmm_app_redirect_url_label'); ?>: <strong> <?= $_zoomAppRedirectUri; ?></strong>
               <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">×</span>
               </button>
          </div>
     </div>
<?php endif; ?>