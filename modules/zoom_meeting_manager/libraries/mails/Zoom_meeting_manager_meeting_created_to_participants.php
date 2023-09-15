<?php

defined('BASEPATH') or exit('No direct script access allowed');

class Zoom_meeting_manager_meeting_created_to_participants extends App_mail_template
{
     protected $for = 'staff';

     /**
      * Zoom Meeting
      *
      * @var object
      */
     protected $meeting;

     public $slug = 'zmm-meeting-created-to-participants';

     /**
      * Relation ID, e.q. staff
      * @var mixed
      */
     protected $staff;

     public function __construct($meeting, $staff)
     {
          parent::__construct();

          $this->meeting = $meeting;
          $this->staff = $staff;


          $this->set_merge_fields('zoom_meeting_manager_merge_fields', $this->meeting, $this->staff);
     }

     public function build()
     {
          $this->to($this->staff->email);
     }
}
