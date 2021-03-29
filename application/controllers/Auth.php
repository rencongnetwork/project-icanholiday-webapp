<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('Auth_model');
    }

    public function index()
    {
        redirect(app_url() . 'auth/login');
    }

    public function login()
    {
        if ($this->session->userdata('email')) {
            redirect(app_url() . 'dashboard');
        } else {
            $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email', [
                'required'      => 'Email wajib diisi',
                'valid_email'   => 'Email tidak valid'
            ]);
            $this->form_validation->set_rules('password', 'Password', 'required|trim', [
                'required'      => 'Password wajib diisi'
            ]);
            if ($this->form_validation->run() == false) {
                $data = [
                    'title'     => 'Login',
                    'type'      => 'auth',
                ];
                $this->load->view('app/auth/login', $data);
            } else {
                $email              = $this->input->post('email');
                $password           = $this->input->post('password');
                $response           = $this->Auth_model->login($email, $password);
                if ($response['status'] == 200) {
                    $data = [
                        'user_id'           => $response['data']['user_id'],
                        'email'             => $email,
                        'access_role'       => $response['data']['access_role'],
                        'access_token'      => $response['data']['access_token'],
                    ];
                    $this->session->set_userdata($data);
                    redirect(app_url() . 'dashboard');
                } else {
                    $this->session->set_flashdata('email_login', $email);
                    $this->session->set_flashdata('message', '<div class="alert alert-danger cfs-15 mb-2" role="alert">' . $response['message'] . '</div>');
                    redirect(app_url(). 'auth/login');
                }
            }
        }
    }

    // public function register()
    // {
    //  if ($this->session->userdata('email')) {
    //      redirect(app_url());
    //  } else {
    //      $this->form_validation->set_rules('fullname', 'Fullname', 'required|trim', [
    //          'required'      => 'Nama wajib diisi'
    //      ]);
    //      $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email|is_unique[users_account.email]', [
    //          'required'      => 'Email wajib diisi',
    //          'valid_email'   => 'Email tidak valid',
    //          'is_unique'     => 'Email sudah terdaftar'
    //      ]);
    //      $this->form_validation->set_rules('phone', 'Phone', 'required|trim', [
    //          'required'      => 'No. HP/WhatsApp wajib diisi'
    //      ]);
    //      $this->form_validation->set_rules('password', 'Password', 'required|trim|min_length[5]|matches[cpassword]', [
    //          'required'      => 'Password wajib diisi',
    //          'matches'       => 'Password tidak sama',
    //          'min_length'    => 'Password terlalu pendek'
    //      ]);
    //      $this->form_validation->set_rules('cpassword', 'Password', 'required|trim|min_length[5]|matches[password]', [
    //          'required'      => 'Password wajib diisi',
    //      ]);
    //      if ($this->form_validation->run() == false) {
    //          $data = [
    //              'title'     => 'Register',
    //              'type'      => 'auth',
    //              'content'   => 'app/auth/register'
    //          ];
    //          $this->load->view('_layout/app/template', $data);
    //      } else {
    //          $fullname       = htmlspecialchars($this->input->post('fullname', true));
    //          $email          = htmlspecialchars($this->input->post('email', true));
    //          $phone          = htmlspecialchars($this->input->post('phone', true));
    //          $password       = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
    //          $response       = $this->Auth_model->register($fullname, $email, $phone, $password);
    //          if ($response['status'] == 200) {
    //              $this->session->set_flashdata('message', '<div class="alert alert-success cfs-15 mb-2" role="alert">' .$response['message']. '</div>');
    //              redirect(app_url().'auth/login');
    //          } else {
    //              $this->session->set_flashdata('message', '<div class="alert alert-danger cfs-15 mb-2" role="alert">' .$response['message']. '</div>');
    //              redirect(app_url().'auth/register');
    //          }
    //      }
    //  }
    // }

    public function logout()
    {
        $this->session->sess_destroy();
		$this->session->set_flashdata('message', '<div class="alert alert-success cfs-15 mb-2" role="alert">' . $response['message'] . '</div>');
		redirect(app_url());
    }
}
