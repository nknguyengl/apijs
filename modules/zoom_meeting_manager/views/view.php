<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php
/**
 * Current meeting
 */
$meeting = $this->zoom->getMeeting($id);
init_head();
?>
<div id="wrapper">
     <div class="content">
          <div class="row">
               <div class="col-md-12">
                    <div class="panel_s">
                         <div class="panel-body">
                              <div style="display: flex;justify-content:center;">
                                   <div class="meeting_info_headers">
                                        <h4><?= _l('zmm_meeting_info'); ?></h4>
                                   </div>
                              </div>
                              <hr class="hr-panel-heading">
                              <div class="col-md-6">
                                   <div class="form-group">
                                        <h4><strong><?= _l('zmm_topic_label'); ?>:</strong> <?= $meeting->topic ?></h4>
                                        <hr>
                                   </div>
                                   <div class="form-group">
                                        <h4><strong><?= _l('zmm_desc_agenda'); ?>:</strong> <?= $meeting->agenda ?></h4>
                                        <hr>
                                   </div>
                                   <div class="form-group">
                                        <h4 class="<?= (ucfirst($meeting->status) === 'Started') ? 'text-success' : 'text-info' ?>" data-toggle="tooltip" title="<?= _l('zmm_start_url_info'); ?>">
                                             <strong><?= _l('zmm_meeting_status'); ?>:</strong>
                                             <?= ucfirst($meeting->status) ?>
                                             <?php if ($meeting->status === 'waiting') : ?>
                                                  <a class="pull-right" href="<?= $meeting->start_url; ?>" target="_blank"><strong><?= _l('zmm_meeting_start_url'); ?></strong></a>
                                             <?php endif; ?>
                                        </h4>
                                        <hr>
                                   </div>
                                   <div class="form-group">
                                        <h4><strong><?= _l('zmm_start_time_label'); ?>:</strong> <?= _dt($meeting->start_time) ?></h4>
                                        <hr>
                                   </div>
                                   <div class="form-group">
                                        <h4><strong><?= _l('zmm_meeting_duration'); ?>:</strong> <?= ($meeting->duration) ? $meeting->duration : _l('zmm_meeting_not_set'); ?></h4>
                                        <hr>
                                   </div>
                                   <div class="form-group">
                                        <h4><strong><?= _l('zmm_timezone_label'); ?>:</strong> <?= $meeting->timezone; ?></h4>
                                        <hr>
                                   </div>
                                   <div class="form-group">
                                        <h4><strong><?= _l('zmm_password_label'); ?>:</strong> <?= $meeting->password; ?></h4>
                                        <span><?= _l('zmm_password_info'); ?></span>
                                        <hr>
                                   </div>
                              </div>
                              <div class="col-md-6">
                                   <?php $settings = $meeting->settings; ?>
                                   <div class="form-group">
                                        <h4><strong><?= _l('zmm_meeting_type'); ?>:</strong> <?= zoom_get_meeting_type($meeting->type); ?></h4>
                                        <hr>
                                   </div>
                                   <div class="form-group">
                                        <h4><strong><?= _l('zmm_meeting_host_video'); ?>:</strong> <?= ($settings->host_video) ? _l('yes') : _l('no'); ?></h4>
                                        <hr>
                                   </div>
                                   <div class="form-group">
                                        <h4><strong><?= _l('zmm_meeting_participant_video'); ?>:</strong> <?= ($settings->participant_video) ? _l('yes') : _l('no'); ?></h4>
                                        <hr>
                                   </div>
                                   <div class="form-group">
                                        <h4><strong><?= _l('zmm_join_before_host'); ?>:</strong> <?= ($settings->join_before_host) ? _l('yes') : _l('no'); ?></h4>
                                        <hr>
                                   </div>
                                   <div class="form-group">
                                        <h4><strong><?= _l('zmm_mute_upon_entry'); ?>:</strong> <?= ($settings->mute_upon_entry) ? _l('yes') : _l('no'); ?></h4>
                                        <hr>
                                   </div>
                                   <div class="form-group">
                                        <h4><strong><?= _l('zmm_waiting_room'); ?>:</strong> <?= ($settings->waiting_room) ? _l('yes') : _l('no'); ?></h4>
                                        <hr>
                                   </div>
                                   <div class="form-group">
                                        <h4><strong><?= _l('zmm_meeting_auth'); ?>:</strong> <?= ($settings->meeting_authentication) ? _l('yes') : _l('no'); ?></h4>
                                        <hr>
                                   </div>
                                   <div class="form-group">
                                        <h4>
                                             <a href="<?= str_replace('j/', 'wc/join/', $meeting->join_url); ?>" target="_blank">
                                                  <strong><?= _l('zmm_join_web_url'); ?></strong>
                                             </a>
                                        </h4>
                                        <hr>
                                   </div>
                              </div>
                              <div class="clearfix"></div>
                              <a href="<?= admin_url('zoom_meeting_manager/index'); ?>" class="btn btn-default btn-xs"><?= _l('zmm_back_to_meetings'); ?></a>
                         </div>
                    </div>
               </div>
          </div>
     </div>
</div>
</div>
<?php init_tail(); ?>
<script>
     /**
      * Just toggles the menu to be active
      */
     $('.menu-item-zoom_meeting_manager').addClass('active');
</script>
</body>

</html>