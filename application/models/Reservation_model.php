<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reservation_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function insertReservation($session_id, $student_id)
    {
        $data = array(
            'session_id' => $session_id,
            'student_id' => $student_id
        );

        $this->db->insert('reservation', $data);
        return $this->db->insert_id();
    }
    public function reservationCheck($session_id, $student_id) {
        $this->db->from('reservation');
        $this->db->where('session_id', $session_id);
        $this->db->where('student_id', $student_id);
        $query = $this->db->get();
    
        return $query->num_rows();
    }
}