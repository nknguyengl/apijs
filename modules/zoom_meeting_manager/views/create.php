<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
     <div class="content">
          <div class="row">
               <form name="meeting-form" action="<?= admin_url('zoom_meeting_manager/index/create'); ?>" method="POST">
                    <div class="col-md-12">
                         <div class="panel_s">
                              <div class="panel-body">
                                   <h4> <?= ($user->type == 1)
                                             ? '<i class="fa fa-info-circle" data-toggle="tooltip" data-placement="right" title="' . _l('zmm_participants_account_info') . '"></i>'
                                             : ''; ?> <?= _l('zmm_select_participants'); ?>
                                        <small><?= _l('zmm_optional'); ?> </small>
                                   </h4>
                                   <hr class="hr-panel-heading">
                                   <div class="col-md-12">
                                        <div class="row">
                                             <div class="col-md-4">
                                                  <div class="form-group" id="select_contacts">
                                                       <input type="text" hidden id="rel_contact_type" value="contacts">
                                                       <label for="rel_contact_id"><?= _l('zmm_contacts'); ?></label>
                                                       <div id="rel_contact_id_select">
                                                            <select name="contacts[]" id="rel_contact_id" multiple="true" class="ajax-search" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                                 <?php
                                                                 if ($rel_contact_id != '' && $rel_contact_type != '') {
                                                                      $rel_cdata = get_relation_data($rel_contact_type, $rel_contact_id);
                                                                      $rel_c_val = get_relation_values($rel_cdata, $rel_contact_type);
                                                                      echo '<option value="' . $rel_val['id'] . '" selected>' . $rel_c_val['name'] . '</option>';
                                                                 }
                                                                 ?>
                                                            </select>
                                                       </div>
                                                  </div>
                                             </div>
                                             <div class="col-md-4">
                                                  <div class="form-group select-placeholder" id="rel_id_wrapper">
                                                       <input type="text" hidden id="rel_lead_type" value="leads">
                                                       <label for="rel_id"><?= _l('leads'); ?></label>
                                                       <div id="rel_id_select">
                                                            <select name="leads[]" id="rel_id" multiple="true" class="ajax-search" data-width="100%" data-live-search="true" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
                                                                 <?php
                                                                 if ($rel_id != '' && $rel_type != '') {
                                                                      $rel_data = get_relation_data($rel_type, $rel_id);
                                                                      $rel_val = get_relation_values($rel_data, $rel_type);
                                                                      echo '<option value="' . $rel_val['id'] . '" selected>' . $rel_val['name'] . '</option>';
                                                                 } ?>
                                                            </select>
                                                       </div>
                                                  </div>
                                             </div>
                                             <div class="col-md-4">
                                                  <div class="form-group">
                                                       <?php echo render_select('staff[]', $staff_members, array('staffid', array('firstname', 'lastname')), 'staff', [], array('multiple' => true), array(), '', '', false); ?>
                                                  </div>
                                             </div>
                                        </div>
                                   </div>
                                   <hr class="hr-panel-heading">
                                   <div class="col-md-12 no-padding">
                                        <h4><?= _l('zmm_create_meeting'); ?></h4>
                                        <span><?= _l('zmm_create_note'); ?></span>
                                        <hr>
                                        <input type="hidden" name="<?php echo get_instance()->security->get_csrf_token_name(); ?>" value="<?php echo get_instance()->security->get_csrf_hash(); ?>">
                                        <div class="col-md-6">
                                             <h4 class="mfont-bold-medium-size mtop1"><?= _l('zmm_general'); ?></h4>
                                             <hr>
                                             <div class="form-group">
                                                  <label for="topic"><small class="req text-danger">* </small><?= _l('zmm_topic_label'); ?></label>
                                                  <input type="text" name="topic" class="form-control" id="topic" placeholder="<?= _l('zmm_topic_label'); ?>">
                                             </div>
                                             <div class="form-group">
                                                  <label for="description"><?= _l('zmm_description_label'); ?></label>
                                                  <textarea name="description" class="form-control" id="description" placeholder="<?= _l('zmm_description_label'); ?>"></textarea>
                                             </div>
                                             <div class="form-group">
                                                  <div class="form-group" app-field-wrapper="date">
                                                       <label for="date" class="control-label">
                                                            <small class="req text-danger">* </small><?= _l('zmm_when_date'); ?>
                                                       </label>
                                                       <div class="input-group date">
                                                            <input type="text" id="date" name="date" class="form-control datetimepicker meeting-date" readonly="readonly" autocomplete="off">
                                                            <div class="input-group-addon">
                                                                 <i class="fa fa-calendar calendar-icon"></i>
                                                            </div>
                                                       </div>
                                                  </div>
                                             </div>
                                             <hr>
                                             <h4 class="mfont-bold-medium-size"><?= _l('zmm_meeting_duration'); ?></h4>
                                             <hr>
                                             <div class="form-group hour_mins">
                                                  <div class="pull-left">
                                                       <span><?= _l('zmm_hour'); ?></span>
                                                       <select class="selectpicker" name="hour" id="metting_hours">
                                                            <?php foreach (zmmGetHours() as $hour) { ?>
                                                                 <option value="<?php echo $hour['value']; ?>">
                                                                      <?php echo $hour['name']; ?>
                                                                 </option>
                                                            <?php } ?>
                                                       </select>
                                                       <label for="minutes"><?= _l('zmm_minutes'); ?></label>
                                                       <select class=" selectpicker" name="minutes" id="minutes">
                                                            <option value="0">0</option>
                                                            <option value="15">15</option>
                                                            <option value="30" selected>30</option>
                                                            <option value="45">45</option>
                                                       </select>
                                                  </div>
                                                  <div class="timezone_parent pull-right">
                                                       <select name="timezone" id="timezones" class="form-control selectpicker" data-live-search="true">
                                                            <?php foreach (get_timezones_list() as $key => $timezones) { ?>
                                                                 <optgroup label="<?php echo $key; ?>">
                                                                      <?php foreach ($timezones as $timezone) { ?>
                                                                           <option value="<?php echo $timezone; ?>"><?php echo $timezone; ?></option>
                                                                      <?php } ?>
                                                                 </optgroup>
                                                            <?php } ?>
                                                       </select>
                                                       <label for="timezones" id="timezones_label" class="control-label"><?php echo _l('zmm_timezone'); ?></label>
                                                  </div>
                                                  <div class="clearfix"></div>
                                             </div>
                                             <hr>

                                             <div class="input-group">
                                                  <input type="password" class="form-control pwd" name="password" placeholder="Password (optional)">
                                                  <span class="input-group-btn">
                                                       <button class="btn btn-default reveal" type="button"><i class="glyphicon glyphicon-eye-open"></i></button>
                                                  </span>
                                             </div>
                                        </div>
                                        <div class="col-md-6">
                                             <h4 class="mfont-bold-medium-size mtop1"><?= _l('zmm_additional_settings'); ?></h4>
                                             <hr>
                                             <div class="form-group">
                                                  <div class="checkbox checkbox-primary">
                                                       <input type="checkbox" name="join_before_host" id="join_before_host">
                                                       <label for="join_before_host"><?= _l('zmm_join_before_host'); ?></label>
                                                  </div>
                                                  <div class="checkbox checkbox-primary">
                                                       <input type="checkbox" name="host_video" id="host_video">
                                                       <label for="host_video"><?= _l('zmm_host_video'); ?></label>
                                                  </div>
                                                  <div class="checkbox checkbox-primary">
                                                       <input type="checkbox" name="participant_video" id="participant_video">
                                                       <label for="participant_video"><?= _l('zmm_participant_video'); ?></label>
                                                  </div>
                                                  <div class="checkbox checkbox-primary">
                                                       <input type="checkbox" name="mute_upon_entry" id="mute_upon_entry">
                                                       <label for="mute_upon_entry"><?= _l('zmm_mute_upon_entry'); ?></label>
                                                  </div>
                                                  <div class="checkbox checkbox-primary">
                                                       <input type="checkbox" name="waiting_room" id="waiting_room">
                                                       <label for="waiting_room"><?= _l('zmm_waiting_room'); ?></label>
                                                  </div>
                                                  <div class="ptop10">

                                                  </div>
                                             </div>
                                        </div>
                                        <div class="clearfix"></div>
                                        <hr class="hr-panel-heading">
                                        <a href="<?= admin_url('zoom_meeting_manager/index'); ?>" class="btn btn-default btn-xs"><?= _l('zmm_back_to_meetings'); ?></a>
                                        <button type="submit" id="btnScheduleMeeting" class="btn btn-primary btn-xs pull-right"><?= _l('zmm_shedule_label'); ?></button>
                                        <?php
                                        /**
                                         * User Types
                                         * 1 - Free
                                         * 2 - Licenced
                                         * 3 - On-perm
                                         */
                                        if ($user->type == 1) : ?>
                                             <hr class="hr-panel-heading">
                                             <span><span class="label label-info"><?= _l('zmm_user_type') ?></span> - <?= _l('zmm_user_basic_info'); ?> </span>
                                        <?php endif; ?>
                                   </div>
                              </div>
                         </div>
                    </div>
               </form>
          </div>
     </div>
</div>
<?php init_tail(); ?>
<!-- Include create js functionality file -->
<?php require('modules/zoom_meeting_manager/assets/js/create_js.php'); ?>
</body>

</html>