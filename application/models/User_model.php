<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_model extends CI_Model
{
    public function __construct()
    {
        parent::__construct();
        $this->user_id  = $this->Auth_model->get_userid();
    }

    public function user_profile($user_id)
    {        
        $this->db->select('ud.fullname, ud.phone, ud.updated_at, ua.email')
                 ->from('users_data as ud')
                 ->join('users_account as ua', 'ud.user_id = ua.user_id')
                 ->where('ud.user_id', $user_id);
        $query = $this->db->get()->row();

        if ($query) {
            return array(
                'status'    => 200,
                'data'      => array(
                    'fullname'		=>  $query->fullname,
                    'email'			=>  $query->email,
                    'phone'			=>  $query->phone,
                )
            );
        } else {
            return array(
                'status'    => 404,
                'message'   => 'Not Found'
            );
        }
    }

    public function update_profile($body)
    {
        $query      = $this->db->where('user_id', $this->user_id)->update('users_data', $body);
        if ($query) {
            return array(
                'status'    => 200,
                'message'   => 'Data has been updated'
            );
        } else {
            return array(
                'status'    => 500,
                'message'   => 'Internal Server Error'
            );
        }
    }

    public function update_password($body)
    {
        $query      = $this->db->select('password')->from('users_account')->where('user_id', $this->user_id)->get()->row();
        $hashed_password = $query->password;
        if (password_verify($body['old_password'], $hashed_password)) {
            if ($body['new_password'] == $body['cn_password']) {
                $options = [
                    'cost' => 10,
                ];
                $new_password = password_hash($body['new_password'], PASSWORD_DEFAULT, $options);
                $query_update = $this->db->where('user_id', $this->user_id)->update('users_account', array(
                    'password'      => $new_password,
                    'updated_at'    => $body['updated_at']
                ));
                if ($query_update) {
                    return array(
                        'status'    => 200,
                        'message'   => 'Password has been updated'
                    );
                } else {
                    return array(
                        'status'    => 500,
                        'message'   => 'Internal server error'
                    );
                }
            } else {
                return array(
                    'status'    => 400,
                    'message'   => 'New password and confirmation password do not match'
                );
            }
        } else {
            return array(
                'status'    => 400,
                'message'   => 'Old password incorrect'
            );
        }
    }

}
