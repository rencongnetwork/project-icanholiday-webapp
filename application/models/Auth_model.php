<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
    }

    public function login($email, $password)
    {
        $get_status = $this->db->select('app_status')->from('_settings')->get()->row();
        $app_status = $get_status->app_status;

        if ($app_status != 1) {
            return array(
                'status'    => 503,
                'message'   => 'Application under maintenance'
            );
            die();
        }

        $query  = $this->db->select('password, user_id, access_role, is_active')->from('users_account')->where('email', $email)->get()->row();
        if ($query == "") {
            return array(
                'status'    => 401,
                'message'   => 'Email is not registered'
            );
            die();
        } else {
            $hashed_password = $query->password;
            $is_active       = $query->is_active;
            if (password_verify($password, $hashed_password)) {
                $hash_key   = bin2hex(openssl_random_pseudo_bytes(64));
                $last_login = date('Y-m-d H:i:s');
                $token      = base64_encode($hash_key);
                $expired_at = date("Y-m-d H:i:s", strtotime('+12 hours'));
                
                $this->db->trans_start();
                $this->db->where('user_id', $query->user_id)->update('users_account', array(
                    'last_login'    => $last_login
                ));
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    return array(
                        'status'    => 500,
                        'message'   => 'Internal server error'
                    );
                } else {
                    if ($is_active == 1) {
                        $this->db->trans_commit();
                        return array(
                            'status'    => 200,
                            'message'   => 'Successfully login',
                            'data'      => array(
                                'user_id'           => $query->user_id,
                                'access_role'       => $query->access_role,
                                'access_token'      => $token,
                                'expired_at'        => $expired_at
                            ) 
                        );
                    } else if ($is_active == 2) {
                        return array(
                            'status'    => 403,
                            'message'   => 'Your account has been suspended due to a violation of our terms agreement'
                        );
                    } else {
                        return array(
                            'status'    => 403,
                            'message'   => 'Your account is inactive, please check your email and follow instructions to complete your registration.'
                        );
                    }
                }
            } else {
                return array(
                    'status'    => 401,
                    'message'   => 'Password invalid'
                );
            }
        }
    }

    public function register($email, $password)
    {
        $get_status = $this->db->select('app_status')->from('_settings')->get()->row();
        $app_status = $get_status->app_status;

        if ($app_status != 1) {
            return array(
                'status'    => 503,
                'message'   => 'Application under maintenance'
            );
            die();
        }

        $user_id = generate_uuid();
        $this->db->trans_start();
        $this->db->insert('users_data', array(
            'user_id'       => $user_id,
        ));
        $this->db->insert('users_account', array(
            'user_id'       => $user_id,
            'email'         => $email,
            'password'      => $password,
            'access_role'   => 2
        ));
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return array(
                'status'    => 500,
                'message'   => 'Internal server error'
            );
        } else {
            $this->db->trans_commit();
            return array(
                'status'    => 200,
                'message'   => 'Pendaftaran berhasil. Silahkan cek inbox/spam email kamu untuk aktivasi akun.'
            );
        }
    }

    function get_userid()
    {
        $email      = $this->session->userdata('email');
        $query      = $this->db->select('user_id')->from('users_account')->where('email', $email)->get();
        if ($query->num_rows() > 0) {
            $row = $query->row()->user_id;
            return $row;
        } else {
            return false;
        }
    }

}
