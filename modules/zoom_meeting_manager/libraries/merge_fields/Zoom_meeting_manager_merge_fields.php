<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Zoom_meeting_manager_merge_fields extends App_merge_fields
{
     public function build()
     {
          return [
               [
                    'name'      => 'Meeting Topic',
                    'key'       => '{meeting_topic}',
                    'available' => [
                         'zoom_meeting_manager',
                    ],
               ],
               [
                    'name'      => 'Meeting Description / Agenda',
                    'key'       => '{meeting_description}',
                    'available' => [
                         'zoom_meeting_manager',
                    ],
               ],
               [
                    'name'      => 'Meeting Client First Name',
                    'key'       => '{meeting_user_firstname}',
                    'available' => ['zoom_meeting_manager'],
               ],
               [
                    'name'      => 'Meeting Client Last Name',
                    'key'       => '{meeting_user_lastname}',
                    'available' => ['zoom_meeting_manager'],
               ],
               [
                    'name'      => 'Meeting Client Email',
                    'key'       => '{meeting_user_email}',
                    'available' => ['zoom_meeting_manager'],
               ],
               [
                    'name'      => 'Meeting Date and Time',
                    'key'       => '{meeting_datetime}',
                    'available' => ['zoom_meeting_manager'],
               ],
               [
                    'name'      => 'Meeting Timezone',
                    'key'       => '{meeting_timezone}',
                    'available' => ['zoom_meeting_manager'],
               ],
               [
                    'name'      => 'Meeting Duration',
                    'key'       => '{meeting_duration}',
                    'available' => ['zoom_meeting_manager'],
               ],
               [
                    'name'      => 'Meeting Web Url',
                    'key'       => '{meeting_web_url}',
                    'available' => ['zoom_meeting_manager'],
               ],
               [
                    'name'      => 'Meeting App Url',
                    'key'       => '{meeting_app_url}',
                    'available' => [],
                    'templates' => [
                         'zmm-meeting-created-to-user',
                    ]
               ]
          ];
     }

     /**
      * Merge field for zoom meeting manager
      * @param  mixed $meeting_id
      * @return array
      */
     public function format($meeting, $participant)
     {

          $fields = [];

          if (!$meeting) {
               return $fields;
          }

          $fields['{meeting_topic}']                    = $meeting->topic;
          $fields['{meeting_description}']              = isset($meeting->description) ? $meeting->description : _l('zmm_no_description');
          $fields['{meeting_user_firstname}']           = $participant->firstname;
          $fields['{meeting_user_lastname}']            = $participant->lastname;
          $fields['{meeting_user_email}']               = $participant->email;
          $fields['{meeting_datetime}']                 = _dt($meeting->form['start_time']);
          $fields['{meeting_timezone}']                 = $meeting->form['timezone'];
          $fields['{meeting_duration}']                 = convertToHoursMins($meeting->form['duration']);
          $fields['{meeting_web_url}']                  = $meeting->web_url;
          $fields['{meeting_app_url}']                  = $meeting->join_url;

          return $fields;
     }
}
