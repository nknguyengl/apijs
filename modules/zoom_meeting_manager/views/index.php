<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
     <div class="content">
          <div class="row main_row">
               <div class="col-md-12">
                    <div class="panel_s">
                         <div class="panel-body">
                              <div>
                                   <div class="_buttons">
                                        <a href="#" class="btn btn-xs btn-info btn-with-tooltip toggle-small-view hidden-xs pull-right hidden mleft10" id="toggleTableBtn" onclick="toggle_meeting_notes_table(); return false;" data-toggle="tooltip" title="" data-original-title="Toggle Table">
                                             <i class="fa fa-angle-double-right"></i>
                                        </a>
                                   </div>
                                   <?php if (!$zoom->isAuth()) { ?>
                                        <h3 class='text-center'>
                                             <a href="<?= $zoom->getLoginUrl() ?>">
                                                  <?= _l('zmm_zoom_login') ?>
                                             </a>
                                        </h3>
                                        <?php } else {
                                        if (staff_can('create', 'zoom_meeting_manager')) { ?>
                                             <a class="btn btn-info pull-right mleft10 btn-xs" href="<?= admin_url('zoom_meeting_manager/index/createMeeting'); ?>">
                                                  <?= _l('zmm_create_meeting'); ?>
                                             </a>
                                        <?php } ?>
                                   <?php } ?>
                                   <h4><?= _l('zmm_module_name'); ?></h4>
                                   <hr class="hr-panel-heading">
                              </div>
                              <table class="table dt-table dt-inline scroll-responsive" id="meetings">
                                   <?php $this->load->view('partials/index-table-contents', $live); ?>
                              </table>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
<?php init_tail(); ?>
<!-- Include index js functionality file -->
<?php require('modules/zoom_meeting_manager/assets/js/index_js.php'); ?>
</body>

</html>