<?php 
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 
 */
class pemilihan extends CI_Controller
{
	
	function __construct()
	{
		parent::__construct();
    	$this->load->model('M_pemilihan');
	}


	public function index()
	{
		if($this->session->userdata('status') == 'login' && $this->session->userdata('role') == 0 && $this->session->userdata('pilih_rt')=='belum' && $this->session->userdata('pilih_rw')=='belum'){
			$data['username'] = $this->session->userdata('name');
			$data['dataCalon'] = $this->M_pemilihan->select('calon','RW 01');
			$this->load->view('pemilihan/pilih_RW',$data);
		}
		else if($this->session->userdata('status') == 'login' && $this->session->userdata('role') == 0 && $kondisi == 'sudah'){
			$data['username'] = $this->session->userdata('name');
			$data['dataCalon'] = $this->M_pemilihan->select('calon','RW 01');
			$this->load->view('pemilihan/pilih_RT',$data);
		}
		else{
			session_destroy();
			$this->session->set_flashdata('msg','Anda Sudah Melakukan Hak Pilih Anda');
			$this->load->view('login/login');
		}
		
	}

	public function vote_rw()
	{
		$username = $this->session->userdata('id');
		$kondisi = $this->M_pemilihan->select_id('user', $username);
		$dapil = $this->session->userdata('dapil');
		foreach ($kondisi as $k){
			$kondisi = $k->pilih_rw;
			$kondisi_rt = $k->pilih_rt;
		}
		if ($this->session->userdata('status') == 'login' && $this->session->userdata('role') == 0 && $kondisi == 'belum'){
			$id= $this->uri->segment(3);
			$jumlah_lama = 0;
			$data_calon = $this->M_pemilihan->select_id('calon',$id);
			foreach ($data_calon as $key) {
				$jumlah_lama = $key->jumlah_suara;
			}
			$jumlah_baru = $jumlah_lama++;

			$data = array(
				'jumlah_suara' => $jumlah_lama
			);

			$where= array(
				'id' => $id
			);

			$status = array(
				'pilih_rw' => 'sudah'
			);

			$username = array(
				'username' => $this->session->userdata('name')
			);
			$this->M_pemilihan->update('calon',$data,$where);
			$this->M_pemilihan->update('user',$status,$username);
			$result['dataCalon'] = $this->M_pemilihan->select('calon',$dapil);
			$this->load->view('pemilihan/pilih_RT',$result); 
		}
		else{
			$this->session->set_flashdata('msg','Maaf Anda Hanya Bisa Menggunakan Menggunakan Hak Pilih Anda Sekali untuk Melakukan Pemilihan Ketua RW');
			$result['dataCalon'] = $this->M_pemilihan->select('calon',$dapil);
			if ($this->session->userdata('status') == 'login' && $this->session->userdata('role') == 0 && $kondisi_rt=='belum'){
				$this->load->view('pemilihan/pilih_RT',$result);
			}
			else{
				$this->session->set_flashdata('msg','Terimakasih Telah Menggunakan Hak Pilih Anda');
				$this->load->view('login/login');
			}

		}
	}

	public function vote_rt()
	{
		$username = $this->session->userdata('id');
		$kondisi = $this->M_pemilihan->select_id('user', $username);
		$dapil = $this->session->userdata('dapil');
		foreach ($kondisi as $k){
			$kondisi_rw = $k->pilih_rw;
			$kondisi_rt = $k->pilih_rt;
		}
		if ($this->session->userdata('status') == 'login' && $this->session->userdata('role') == 0 && $kondisi_rw == 'sudah' && $kondisi_rt == 'belum' ){
			$id= $this->uri->segment(3);
			$jumlah_lama = 0;
			$data_calon = $this->M_pemilihan->select_id('calon',$id);
			foreach ($data_calon as $key) {
				$jumlah_lama = $key->jumlah_suara;
			}
			$jumlah_baru = $jumlah_lama++;
	
			$data = array(
				'jumlah_suara' => $jumlah_lama,
			);
	
			$where= array(
				'id' => $id
			);
	
			$status = array(
				'pilih_rt' => 'sudah'
			);
	
			$username = array(
				'username' => $this->session->userdata('name')
			);
			$this->M_pemilihan->update('calon',$data,$where);
			$this->M_pemilihan->update('user',$status,$username);
	
			$result['dataCalon'] = $this->M_pemilihan->select('calon',$dapil);
			$this->session->set_flashdata('msg','Terimakasih Telah Menggunakan Hak Pilih Anda');
			$this->load->view('login/login');
		}
		else{
			session_destroy();
			$this->session->set_flashdata('msg','Terimakasih Telah Menggunakan Hak Pilih Anda');
			$this->load->view('login/login');
		}
	}
}

 ?>