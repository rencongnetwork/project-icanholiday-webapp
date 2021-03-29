<?php

// @title : PHP helper
// @author : aulianza
// @language : PHP

defined('BASEPATH') or exit('No direct script access allowed');

function app_url()
{
    return '/';
}

function protocol($type)
{
    if ($type == 'ssl') {
        return 'https://';
    } else {
        return 'http://';
    }
}

function json_output($statusHeader, $response)
{
    $ci = &get_instance();
    $ci->output->set_content_type('application/json');
    $ci->output->set_status_header($statusHeader);
    $ci->output->set_output(json_encode($response));
}

function api_call($url=null, $header=null, $payload=null)
{
    $curl_handle = curl_init();
    curl_setopt($curl_handle, CURLOPT_URL, $url);
    if ($header != null) {
        curl_setopt($curl_handle, CURLOPT_HTTPHEADER, $header);
    }
    curl_setopt($curl_handle, CURLOPT_HEADER, 0);
    curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($curl_handle, CURLOPT_POST, 1);
    curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 0);
    curl_setopt($curl_handle, CURLOPT_TIMEOUT, 20);
    if ($payload != null) {
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $payload);
    }
    $result = curl_exec($curl_handle);
    curl_close($curl_handle);
    $response = json_decode($result);
    return  $response;
}

function generate_uuid()
{
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}

function format_date_id($tanggal, $cetak_hari = false)
{
	$hari = array ( 1 =>    'Senin',
				'Selasa',
				'Rabu',
				'Kamis',
				'Jumat',
				'Sabtu',
				'Minggu'
			);
			
	$bulan = array (1 =>   'Januari',
				'Februari',
				'Maret',
				'April',
				'Mei',
				'Juni',
				'Juli',
				'Agustus',
				'September',
				'Oktober',
				'November',
				'Desember'
			);
	$split 	  = explode('-', $tanggal);
	$tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];
	
	if ($cetak_hari) {
		$num = date('N', strtotime($tanggal));
		return $hari[$num] . ', ' . $tgl_indo;
	}
	return $tgl_indo;
}

function menu_listing()
{

    $ci = &get_instance();
    $id = $ci->session->userdata('access_role');

    $ci->db->order_by('menu_sequence', 'asc');
    $getmenu = $ci->db->get_where('menu', ['status' => '1', 'menu_parent_id' => null])->result();

    if (!empty($getmenu)) {
        foreach ($getmenu as $key) {
            $check_menu = explode(',', $key->permited_to);
            if (in_array($id, $check_menu) == true) {
                $ci->db->order_by('menu_sequence', 'asc');
                $submenu = $ci->db->get_where('menu', array('menu_parent_id' => $key->id, 'status' => '1'))->result();
                if ($submenu) {
                    echo '
                    <li class="nav-item has-treeview">
                        <a href="' . base_url('/') . $key->url . '" class="nav-link">
                            <i class="nav-icon ' . $key->icon . '"></i>
                            <p>
                                ' . $key->menu . '
                                <i class="right fas fa-angle-left"></i>
                            </p>
                        </a>
                        <ul class="nav nav-treeview">';
                    foreach ($submenu as $sbm) {
                        $check_menu = explode(',', $sbm->permited_to);
                        if (in_array($id, $check_menu) == true) {
                            if ($ci->uri->segment(1) == $sbm->url) {
                                echo '
                                    <li class="nav-item pl-2">
                                        <a href="' . base_url('/') . $sbm->url . '" class="nav-link active">
                                        <i class="nav-icon ' . $sbm->icon . '"></i>
                                        <p>' . $sbm->menu . '</p>
                                        </a>
                                    </li>
                                ';
                            } else {
                                echo '
                                    <li class="nav-item pl-2">
                                        <a href="' . base_url('/') . $sbm->url . '" class="nav-link">
                                        <i class="nav-icon ' . $sbm->icon . '"></i>
                                        <p>' . $sbm->menu . '</p>
                                        </a>
                                    </li>
                                ';
                            }
                        }
                    }
                    echo '</ul>
                    </li>';
                } else {
                    echo '
                    <li class="nav-item has-treeview">
                        <a href="' . base_url('/') .     $key->url . '" class="nav-link">
                            <i class="nav-icon ' . $key->icon . '"></i>
                            <p>
                                ' . $key->menu . '
                            </p>
                        </a>
                    </li>';
                }
            }
        }

        //Jika Foreach tidak menemukan menu apapun yang di izinkan maka
    } else {
        echo '
            <li class="nav-item">
                <a href="#" class="nav-link">
                <p> - Tidak Ada Menu - </p>
                </a>
            </li>
        ';
    }
    // Selesai
}