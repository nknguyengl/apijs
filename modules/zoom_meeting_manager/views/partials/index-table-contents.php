<thead>
     <tr>
          <th><?= _l('zmm_topic_label'); ?></th>
          <th><?= _l('zmm_description_label'); ?></th>
          <th><?= _l('zmm_web_url_label'); ?></th>
          <?php if (isset($user->type) && $user->type == 2) : ?>
               <th><?= _l('zmm_password_label'); ?></th>
          <?php endif; ?>
          <th><?= _l('zmm_type_label'); ?></th>
          <th><?= _l('zmm_start_time_label'); ?></th>
          <th><?= _l('zmm_timezone_label'); ?></th>
          <th><?= _l('zmm_created_at_label'); ?></th>
          <th><?= _l('zmm_app_url_label'); ?></th>
     </tr>
</thead>
<tbody>
     <?php if ($live !== 'unauthenticated' && is_array($live->meetings) && !empty($live->meetings)) : ?>
          <?php foreach ($live->meetings as $meeting) : ?>
               <tr class="has-row-options">
                    <td>
                         <span data-topic="<?= $meeting->id  ?>"><?= isset($meeting->topic) ? $meeting->topic : ''; ?></span>
                         <div class="row-options">
                              <a href="<?= admin_url('zoom_meeting_manager/index/view/?mid=' . $meeting->id . ''); ?>"><?= _l('view'); ?></a> |
                              | <a data-toggle="tooltip" title="<?= _l('zmm_edit_history_notes') ?>" data-id="<?= $meeting->id;  ?>" onclick="editMeetingNotes(this)" style="cursor:pointer;"><?= _l('zmm_edit_history_notes'); ?></a>

                              <?php
                              if (staff_can('delete', 'zoom_meeting_manager')) : ?>
                                   |
                                   <a href="<?= admin_url('zoom_meeting_manager/index/delete/?mid=' . $meeting->id . ''); ?>" class="text-danger _delete"><?= _l('delete'); ?> </a>
                              <?php endif; ?>

                         </div>
                    </td>
                    <td class="zmm_td_agenda">
                         <?= isset($meeting->agenda) ? $meeting->agenda : ''; ?>
                    </td>
                    <td>
                         <a href=" <?= $meeting->web_url; ?>" target="_blank"><?= _l('zmm_join_label'); ?></a>
                    </td>
                    <?php if ($user->type == 2) : ?>
                         <td>
                              <?= isset($meeting->password) ? $meeting->password : 'no password'; ?>
                         </td>
                    <?php endif; ?>
                    <td>
                         <?= zoom_get_meeting_type($meeting->type) ?>
                    </td>
                    <td>
                         <?= isset($meeting->start_time) ? _dt($meeting->start_time) : ''; ?>
                    </td>
                    <td>
                         <?= isset($meeting->timezone) ? $meeting->timezone : ''; ?>
                    </td>
                    <td>
                         <?= _dt($meeting->created_at); ?>
                    </td>
                    <td>
                         <a href="<?= $meeting->join_url; ?>" target="_blank"><?= _l('zmm_join_label'); ?></a>
                    </td>
               </tr>
          <?php endforeach; ?>
</tbody>
<?php else :
          if ($zoom->isAuth()) {
               if ($zoom->isAuth() && staff_can('create', 'zoom_meeting_manager')) {
                    echo '<h4 class="text-center">' . _l('zmm_no_meetings_yet') . '<a href="' . admin_url('zoom_meeting_manager/index/createMeeting') . '">' . _l('zmm_create_meeting') . '</a></h4>';
               } else {
                    echo '<h4 class="text-center">' . _l('zmm_create_permissions') . '</h4>';
               }
          }
?>
<?php endif; ?>