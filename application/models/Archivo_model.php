<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Archivo_model extends MY_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function obtenerArchivoPorId($documento_id) {
        $this->db->select('*');
        $this->db->from('document');
        $this->db->where('id', $documento_id);
        $query = $this->db->get();

        return $query->row_array();
    }
}
