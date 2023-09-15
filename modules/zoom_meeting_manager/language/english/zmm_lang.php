<?php
defined('BASEPATH') or exit('No direct script access allowed');

# Version 1.0.0
# Meeting labels
#zmm = Zoom Meeting Manager
$lang['zmm_instant_label'] = 'Instant Meeting';
$lang['zmm_scheduled_label'] = 'Scheduled meeting';
$lang['zmm_recurring1_label'] = 'Recurring meeting with no fixed time';
$lang['zmm_recurring2_label'] = 'Recurring meeting with fixed time';

# Other
$lang['zmm_settings_yes'] = 'Yes';
$lang['zmm_settings_no'] = 'No';
$lang['zmm_module_name'] = 'Zoom Meeting Manager';
$lang['zmm_module_name_menu'] = 'Zoom Meetings';
$lang['zmm_create_meeting'] = 'Schedule a Meeting';
$lang['zmm_no_meetings_yet'] = 'You have no meetings yet. ';
$lang['zmm_zoom_login'] = 'Login with Zoom';
$lang['zmm_meeting_deleted'] = 'Your meeting was successfully deleted';
$lang['zmm_meeting_created'] = 'Your new meeting was created successfully';
$lang['zmm_shedule_label'] = 'Schedule';
$lang['zmm_back_to_meetings'] = 'Back To Meetings';
$lang['zmm_topic_label'] = 'Topic';
$lang['zmm_description_label'] = 'Description (optional)';
$lang['zmm_when_date'] = 'When';
$lang['zmm_join_label'] = 'Join';
$lang['zmm_contacts'] = 'Contacts';
$lang['zmm_web_url_label'] = 'Web url';
$lang['zmm_password_label'] = 'Password';
$lang['zmm_type_label'] = 'Type';
$lang['zmm_start_time_label'] = 'Start Time';
$lang['zmm_timezone_label'] = 'Time Zone';
$lang['zmm_created_at_label'] = 'Created At';
$lang['zmm_general'] = 'General';
$lang['zmm_additional_settings'] = 'Additional settings';
$lang['zmm_app_url_label'] = 'App url';
$lang['zmm_meeting_duration'] = 'Duration';
$lang['zmm_create_note'] = 'Note: All meetings created will be stored into Zoom Servers Database';
$lang['zmm_optional'] = 'optional';
$lang['zmm_timezone'] = 'Timezone';
$lang['zmm_hour'] = 'Hour';
$lang['zmm_minutes'] = 'Minutes';
$lang['zmm_hours_and'] = 'Hours and';
$lang['zmm_hours'] = 'Hours';
$lang['zmm_no_description'] = 'Description was not set by the host';
$lang['zmm_select_participants'] = 'Select participants / registrants';

#Zoom Settings labels
$lang['zmm_join_before_host'] = 'Allow participants to join the meeting before the host starts the meeting.';
$lang['zmm_host_video'] = 'Start video when the host joints the meeting.';
$lang['zmm_participant_video'] = 'Start video when participants join the meeting.';
$lang['zmm_mute_upon_entry'] = 'Mute participants upon entry.';
$lang['zmm_waiting_room'] = 'Enable waiting room.';
$lang['zmm_app_id_label'] = 'Client ID';
$lang['zmm_app_secret_label'] = 'Client Secret';
$lang['zmm_app_redirect_url_label'] = 'Zoom Authorization Redirect URI';

# Zoom Account Info
$lang['zmm_user_type'] = 'User Type: Basic';
$lang['zmm_user_basic_info'] = 'The meetings you host will be limited to 40 mins if you have more than 2 participants.';
$lang['zmm_participants_account_info'] = 'Note: You are using a Basic User type account eg. Free Account. The participants / registrants you add will not be added as participants into your Zoom meeting, but Zoom Meeting Manager module enables you to add your participants into Perefex CRM database and also send emails to all of them that are assigned (added) as paticipants or registrants to the meeting. Also WEB and APP url will be send to the participants so they can easily join the meeting.';


# Zoom View Meeting 
$lang['zmm_meeting_info'] = 'Meeting Information';
$lang['zmm_started'] = 'Started';
$lang['zmm_desc_agenda'] = 'Description / Agenda';
$lang['zmm_meeting_status'] = 'Status';
$lang['zmm_start_url_info'] = 'After clicking on the Start URL your browser will open new tab, after the tab is fully loaded you can close the tab. Then you can join the meeting by click in Join URL(Web)';
$lang['zmm_meeting_start_url'] = 'Start URL';
$lang['zmm_meeting_not_set'] = 'Not set';
$lang['zmm_meeting_type'] = 'Meeting Type';
$lang['zmm_meeting_host_video'] = 'Host Video';
$lang['zmm_meeting_participant_video'] = 'Participant Video';
$lang['zmm_join_before_host'] = 'Join Before Host';
$lang['zmm_mute_upon_entry'] = 'Mute Upon Entry';
$lang['zmm_waiting_room'] = 'Waiting Room';
$lang['zmm_meeting_auth'] = 'Meeting Authentication';
$lang['zmm_join_web_url'] = 'Join URL (Web)';
$lang['zmm_password_info'] = 'If this is not the password you set when creating the meeting it means you are using a basic free account and password will be always set, but your registrants / participants to the meeting will not be required to enter the password even if it is set by default. Unless this is the password you set when creating the meeting all members will be required to enter the password in order to join the meeting.';
$lang['zmm_create_permissions'] = 'You are logged in but you don\'t have <b>create permissions</b> for Zoom Meetings Module, please contact an administrator for more details.';
$lang['zmm_viewing_notes'] = 'Viewing notes for meeting with topic: ';
$lang['zmm_edit_history_notes'] = 'Notes';
$lang['zmm_meeting_notes_updated'] = 'Meeting notes was updated successfully';
