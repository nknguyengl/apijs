<?php

defined('BASEPATH') or exit('No direct script access allowed');

class ZoomMeetingManager extends App_Model
{

    /**
     * Get meeting notes
     *
     * @param string $meeting_id
     * @return void
     */
    public function get_meeting_notes($meeting_id)
    {
        $this->db->where('meeting_id', $meeting_id);
        $result = $this->db->get(ZMM_TABLE_NOTES);

        if ($result->num_rows() !== 0) {
            return $result->row();
        }
        return false;
    }

    /**
     * Update meeting notes
     *
     * @param araay $data
     * @return boolean
     */
    public function update_meeting_notes($data)
    {
        $this->db->where('meeting_id', $data['meeting_id']);
        $this->db->update(ZMM_TABLE_NOTES, ['note' => $data['note']]);

        if ($this->db->affected_rows() !== 0) {
            return true;
        }
        return false;
    }
}
