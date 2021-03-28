<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->model('User_model');
        // $this->load->model('Dashboard_model');

        $this->user_id = $this->session->userdata('user_id');
        if (!$this->session->userdata('email')) {
            $this->session->set_flashdata('message', '<div class="alert alert-danger cfs-15" role="alert">Sesi anda sudah berakhir, silahkan login kembali untuk melanjutkan</div>');
            redirect(app_url() . 'auth/login');
        }
        
    }

    public function index()
    {
        $user_profile   = $this->User_model->user_profile($this->user_id);
        $data = [
            'title'         => 'Dashboard',
            'content'       => 'app/dashboard',
            'user_profile'  => $user_profile['data']
        ];
        $this->load->view('_layout/app/template', $data);
    }

}
