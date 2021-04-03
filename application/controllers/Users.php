<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->load->model('DataUsers_model');
    }

    public function index()
    {
        json_output(404, array(
            'status'    => 404,
            'message'   => 'No route found with those values'
        ));
    }

    public function all()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if ($method != 'POST') {
            json_output(400, array(
                'status'    => 400,
                'message'   => 'Bad Request'
            ));
        } else {
            if (isset($_POST['search']['value']) && isset($_POST['length']) && isset($_POST['start']) ) {
                $search         = $_POST['search']['value'];
                $limit          = $_POST['length'];
                $start          = $_POST['start'];
                $order_index    = $_POST['order'][0]['column'];
                $order_field    = $_POST['columns'][$order_index]['data'];
                $order_ascdesc  = $_POST['order'][0]['dir'];

                $sql_total  = $this->DataUsers_model->count_all();
                $sql_data   = $this->DataUsers_model->filter($search, $limit, $start, $order_field, $order_ascdesc);
                $sql_filter = $this->DataUsers_model->count_filter($search);

                json_output(200, array(
                    'status'            => 200,
                    'message'           => 'OK',
                    'draw'              => $_POST['draw'],
                    'recordsTotal'      => $sql_total,
                    'recordsFiltered'   => $sql_filter,
                    'data'              => $sql_data
                ));
            } else {
                json_output(403, array(
                    'status'    => 403,
                    'message'   => 'Forbidden'
                ));
            }
        }
    }

}
