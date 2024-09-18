<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estadisticas_model extends CI_Model {

    public function __construct() {
        $this->load->database();
    }

    public function insert_estadistica($data) {
        return $this->db->insert('estadisticas', $data);
    }

    public function get_all_estadisticas() {
        $query = $this->db->get('estadisticas');
        return $query->result_array();
    }
}
