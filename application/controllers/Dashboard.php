<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Sala_model');
        $this->load->model('Clientes_model');
        $this->load->model('Paquetes_model');
    }

    public function index() {
        $data['salas'] = $this->Sala_model->get_all_salas();
        $this->load->view('templates/sidebar');
        $this->load->view('dashboard/index', $data);
    }

    public function sala($id) {
        $data['sala'] = $this->Sala_model->get_sala($id);
        if (!$data['sala']) {
            show_404();
            return;
        }

        $data['clientes'] = $this->Clientes_model->get_all_clients();
        $data['paquetes'] = $this->Paquetes_model->get_all_paquetes();

        // Cargar la vista de la sala con los datos necesarios
        $this->load->view('dashboard/sala', $data);
    }

    public function obtener_precio_paquete() {
        $paquete_id = $this->input->post('paquete_id');
        $paquete = $this->Paquetes_model->get_paquete_by_id($paquete_id);

        if ($paquete) {
            $precio_por_hora = $paquete['precio_por_hora'];
            echo json_encode(['precio_por_hora' => $precio_por_hora]);
        } else {
            echo json_encode(['error' => 'Paquete no encontrado']);
        }
    }
}
