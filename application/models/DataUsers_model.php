<?php
defined('BASEPATH') or exit('No direct script access allowed');

class DataUsers_model extends CI_Model
{
    public function filter($search, $limit, $start, $order_field, $order_ascdesc)
    {
        $this->db->select('ud.user_id, ud.fullname, ud.phone, ua.email, ua.access_role as role, ua.is_active as status');
		$this->db->from('users_account as ua');
        $this->db->join('users_data as ud', 'ud.user_id = ua.user_id');
        $this->db->group_start();
        $this->db->like('ud.fullname', $search);
        $this->db->or_like('ud.phone', $search);
        $this->db->or_like('ua.email', $search);
        $this->db->order_by($order_field, $order_ascdesc);
        $this->db->limit($limit, $start);
        $this->db->group_end();
        $result = $this->db->get()->result_array();

        return $result;
    }

    public function count_all()
    {
        $this->db->from('users_account');
        $count = $this->db->count_all_results();
        return $count;
    }
    
    public function count_filter($search)
    {
        $this->db->select('ud.user_id, ud.fullname, ud.phone, ua.email, ua.access_role as role, ua.is_active as status');
		$this->db->from('users_account as ua');
        $this->db->join('users_data as ud', 'ud.user_id = ua.user_id');
        $this->db->group_start();
        $this->db->like('ud.fullname', $search);
        $this->db->or_like('ud.phone', $search);
        $this->db->or_like('ua.email', $search);
        $this->db->group_end();
        $row = $this->db->get()->num_rows();
        return $row;
    }

    
}