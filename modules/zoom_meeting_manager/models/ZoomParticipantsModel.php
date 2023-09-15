<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ZoomParticipantsModel extends App_Model
{
     function __construct()
     {
          parent::__construct();
     }

     public function addParticipantsToMeetingTable($participants, $meeting_id)
     {
          foreach ($participants as $type => $participants_array) {
               foreach ($participants_array as $participant) {
                    if ($type == 'staff') {
                         $this->insertParticipant($meeting_id, $participant, 'staff');
                    }
                    if ($type == 'leads') {
                         $this->insertParticipant($meeting_id, $participant, 'lead');
                    }
                    if ($type == 'contacts') {
                         $this->insertParticipant($meeting_id, $participant, 'contact');
                    }
               }
          }
     }

     private function insertParticipant($meeting_id, $participant, $type)
     {
          $this->db->insert(
               ZMM_TABLE_PARTICIPANTS,
               [
                    'meeting_id' => $meeting_id,
                    'user_type' => $type,
                    'user_email' => $participant->email,
                    'user_fullname' => $participant->firstname . ' ' . $participant->lastname
               ]
          );
     }
}
