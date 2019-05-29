<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class Rekapitulasi extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
        $this->load->model('M_admin');
        $this->load->model('M_rekapitulasi');
	}

	public function jumlah_suara_rw()
	{
		if ($this->session->userdata('status') == 'login' && $this->session->userdata('role') == 1){
            $data['list_users'] = $this->M_rekapitulasi->select('calon','RW 01');
            $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
            $this->session->set_userdata($data);
			$this->load->view('rekap/rekap_rw',$data);
        }
        else{
            $this->load->view('login/login');
        }
    }
    
    public function jumlah_suara_rt1()
	{
		if ($this->session->userdata('status') == 'login' && $this->session->userdata('role') == 1){
            $data['list_users'] = $this->M_rekapitulasi->select('calon','RT 01');
            $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
            $this->session->set_userdata($data);
			$this->load->view('rekap/rekap_rt1',$data);
        }
        else{
            $this->load->view('login/login');
        }
    }
    
    public function jumlah_suara_rt2()
	{
		if ($this->session->userdata('status') == 'login' && $this->session->userdata('role') == 1){
            $data['list_users'] = $this->M_rekapitulasi->select('calon','RT 02');
            $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
            $this->session->set_userdata($data);
			$this->load->view('rekap/rekap_rt2',$data);
        }
        else{
            $this->load->view('login/login');
        }
	}


    public function jumlah_suara_rt3()
	{
		if ($this->session->userdata('status') == 'login' && $this->session->userdata('role') == 1){
            $data['list_users'] = $this->M_rekapitulasi->select('calon','RT 03');
            $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
            $this->session->set_userdata($data);
			$this->load->view('rekap/rekap_rt3',$data);
        }
        else{
            $this->load->view('login/login');
        }
	}


	
}

 ?>