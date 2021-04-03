<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Data extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Auth_model');
        $this->load->model('User_model');
        $this->user_id = $this->session->userdata('user_id');
    }
    
    public function index()
    {
        redirect(app_url());
    }

    public function users()
    {
        $user_profile   = $this->User_model->user_profile($this->user_id);
        $data = [
            'title'         => 'Manage Users',
            'content'       => 'app/data/users',
            'user_profile'  => $user_profile['data']
        ];
        $this->load->view('_layout/template', $data);
    }


}
