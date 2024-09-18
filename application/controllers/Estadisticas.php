


<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Estadisticas extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Estadisticas_model');
    }

    public function index() {
        $data['estadisticas'] = $this->Estadisticas_model->get_all_estadisticas();
        $this->load->view('templates/sidebar');
        $this->load->view('estadisticas', $data);
    }
}



