<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller{

  public function __construct(){
		parent::__construct();
    $this->load->model('M_admin');
    $this->load->library('upload');
    $this->load->model('M_rekapitulasi');
	}

  public function index(){
    if($this->session->userdata('status') == 'login' && $this->session->userdata('role') == 1){
      $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
      $data['calon_rw'] = $this->M_admin->numrows_where('calon','dapil','RW 01');
      $data['calon_rt1'] = $this->M_admin->numrows_where('calon','dapil','RT 01');
      $data['calon_rt2'] = $this->M_admin->numrows_where('calon','dapil','RT 02');
      $data['calon_rt3'] = $this->M_admin->numrows_where('calon','dapil','RT 03');
      $data['calon'] = $this->M_admin->numrows('calon');
      $data['dataPemilih'] = $this->M_admin->numrows('pemilih');
      $data['pemilih'] = $this->M_admin->numrows('user');
      $data['suara_rw'] = $this->M_rekapitulasi->select('calon','RW 01');
      $data['suara_rt1'] = $this->M_rekapitulasi->select('calon','RT 01');
      $data['suara_rt2'] = $this->M_rekapitulasi->select('calon','RT 02');
      $data['suara_rt3'] = $this->M_rekapitulasi->select('calon','RT 03');
      $data['pemilih_rt1'] = $this->M_admin->numrows_where('pemilih','alamat','RT 01');
      $data['pemilih_rt2'] = $this->M_admin->numrows_where('pemilih','alamat','RT 02');
      $data['pemilih_rt3'] = $this->M_admin->numrows_where('pemilih','alamat','RT 03');
      $this->load->view('admin/index',$data);
    }else {
      $this->load->view('login/login');
    }
  }

  public function sigout(){
    session_destroy();
    redirect('login');
  }

  ####################################
              // Profile
  ####################################

  public function profile()
  {
    $data['token_generate'] = $this->token_generate();
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->session->set_userdata($data);
    $this->load->view('admin/profile',$data);
  }

  public function vote()
  {
    $data['token_generate'] = $this->token_generate();
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->session->set_userdata($data);
    $this->load->view('admin/form_voting',$data);
  }

  public function token_generate()
  {
    return $tokens = md5(uniqid(rand(), true));
  }

  private function hash_password($password)
  {
    return password_hash($password,PASSWORD_DEFAULT);
  }

  public function proses_new_password()
  {
    $this->form_validation->set_rules('email','Email','required');
    $this->form_validation->set_rules('new_password','New Password','required');
    $this->form_validation->set_rules('confirm_new_password','Confirm New Password','required|matches[new_password]');

    if($this->form_validation->run() == TRUE)
    {
      if($this->session->userdata('token_generate') === $this->input->post('token'))
      {
        $username = $this->input->post('username');
        $email = $this->input->post('email');
        $new_password = $this->input->post('new_password');

        $data = array(
            'email'    => $email,
            'password' => $this->hash_password($new_password)
        );

        $where = array(
            'id' =>$this->session->userdata('id')
        );

        $this->M_admin->update_password('user',$where,$data);

        $this->session->set_flashdata('msg_berhasil','Password Telah Diganti');
        redirect(base_url('admin/profile'));
      }
    }else {
      $this->load->view('admin/profile');
    }
  }

  public function proses_gambar_upload()
  {
    $config =  array(
                   'upload_path'     => "./assets/upload/user/img/",
                   'allowed_types'   => "gif|jpg|png|jpeg",
                   'encrypt_name'    => False, //
                   'max_size'        => "50000",  // ukuran file gambar
                   'max_height'      => "9680",
                   'max_width'       => "9024"
                 );
      $this->load->library('upload',$config);
      $this->upload->initialize($config);

      if( ! $this->upload->do_upload('userpicture'))
      {
        $this->session->set_flashdata('msg_error_gambar', $this->upload->display_errors());
        $this->load->view('admin/profile',$data);
      }else{
        $upload_data = $this->upload->data();
        $nama_file = $upload_data['file_name'];
        $ukuran_file = $upload_data['file_size'];

        //resize img + thumb Img -- Optional
        $config['image_library']     = 'gd2';
				$config['source_image']      = $upload_data['full_path'];
				$config['create_thumb']      = FALSE;
				$config['maintain_ratio']    = TRUE;
				$config['width']             = 150;
				$config['height']            = 150;

        $this->load->library('image_lib', $config);
        $this->image_lib->initialize($config);
				if (!$this->image_lib->resize())
        {
          $data['pesan_error'] = $this->image_lib->display_errors();
          $this->load->view('admin/profile',$data);
        }

        $where = array(
                'username_user' => $this->session->userdata('name')
        );

        $data = array(
                'nama_file' => $nama_file,
                'ukuran_file' => $ukuran_file
        );

        $this->M_admin->update('tb_upload_gambar_user',$data,$where);
        $this->session->set_flashdata('msg_berhasil_gambar','Gambar Berhasil Di Upload');
        redirect(base_url('admin/profile'));
      }
  }

  ####################################
           // End Profile
  ####################################



  ####################################
              // Users
  ####################################
  public function users()
  {
    $data['list_users'] = $this->M_admin->select('user',$this->session->userdata('name')); $data['token_generate'] = $this->token_generate();
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->session->set_userdata($data);
    $this->load->view('admin/users',$data);
  }

  public function pemilih()
  {
    $data['list_users'] = $this->M_admin->kecuali('pemilih',$this->session->userdata('name')); $data['token_generate'] = $this->token_generate();
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->session->set_userdata($data);
    $this->load->view('admin/pemilih',$data);
  }

  public function form_user()
  {
    // $data['list_satuan'] = $this->M_admin->select('tb_satuan');
    $data['token_generate'] = $this->token_generate();
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->session->set_userdata($data);
    $data['list_dapil'] = $this->M_admin->select('dapil');
    $this->load->view('admin/form_users/form_insert',$data);
  }

  public function update_user()
  {
    $nik = $this->uri->segment(3);
    $where = array('nik' => $nik);
    $data['token_generate'] = $this->token_generate();
    $data['list_data'] = $this->M_admin->get_data('pemilih',$where);
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->session->set_userdata($data);
    $this->load->view('admin/form_pemilih/form_update',$data);
  }

  public function proses_delete_user()
  {
    $id = $this->uri->segment(3);
    $where = array('id' => $id);
    $this->M_admin->delete('user',$where);
    $this->session->set_flashdata('msg_berhasil','User Behasil Di Delete');
    redirect(base_url('admin/users'));

  }

  public function proses_tambah_user()
  {
    $this->form_validation->set_rules('username','Username','required');
    $this->form_validation->set_rules('password','Password','required');
    $this->form_validation->set_rules('confirm_password','Confirm password','required|matches[password]');

    if($this->form_validation->run() == TRUE)
    {
      if($this->session->userdata('token_generate') === $this->input->post('token'))
      {

        $username     = $this->input->post('username',TRUE);
        $password     = $this->input->post('password',TRUE);
        $role         = $this->input->post('role',TRUE);
        $nama         = $this->input->post('nama', TRUE); 
        $dapil        = $this->input->post('dapil', TRUE); 

        $data = array(
              'username'     => $username,
              'password'     => $this->hash_password($password),
              'role'         => $role,
              'nama'         => $nama,
              'dapil'        => $dapil
        );
        $this->M_admin->insert('user',$data);

        $this->session->set_flashdata('msg_berhasil','User Berhasil Ditambahkan');
        redirect(base_url('admin/users'));
        }
      }else {
        $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
        $this->load->view('admin/form_users/form_insert',$data);
    }
  }

  public function proses_update_user()
  {
    $this->form_validation->set_rules('username','Username','required');
    $this->form_validation->set_rules('email','Email','required|valid_email');

    
    if($this->form_validation->run() == TRUE)
    {
      if($this->session->userdata('token_generate') === $this->input->post('token'))
      {
        $id           = $this->input->post('id',TRUE);        
        $username     = $this->input->post('username',TRUE);
        $email        = $this->input->post('email',TRUE);
        $role         = $this->input->post('role',TRUE);

        $where = array('id' => $id);
        $data = array(
              'username'     => $username,
              'email'        => $email,
              'role'         => $role,
        );
        $this->M_admin->update('user',$data,$where);
        $this->session->set_flashdata('msg_berhasil','Data User Berhasil Diupdate');
        redirect(base_url('admin/users'));
       }
    }else{
        $this->load->view('admin/form_users/form_update');
    }
  }


  ####################################
           // End Users
  ####################################



  ####################################
        // DATA BARANG MASUK
  ####################################

  public function form_barangmasuk()
  {
    $data['list_satuan'] = $this->M_admin->select('tb_satuan');
    $data['list_user'] = $this->M_admin->select('user');
    $data['list_barangproduksi'] = $this->M_admin->select('tb_barangproduksi');
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->load->view('admin/form_barangmasuk/form_insert',$data);
  }

  public function tabel_barangmasuk()
  {
    $data = array(
              'list_data' => $this->M_admin->select('tb_barang_masuk'),
              'avatar'    => $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'))
            );
    $this->load->view('admin/tabel/tabel_barangmasuk',$data);
  }

  public function update_barang($id_transaksi)
  {
    $where = array('id_transaksi' => $id_transaksi);
    $data['data_barang_update'] = $this->M_admin->get_data('tb_barang_masuk',$where);
    $data['list_satuan'] = $this->M_admin->select('tb_satuan');
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->load->view('admin/form_barangmasuk/form_update',$data);
  }

  public function delete_barang($id_transaksi)
  {
    $where = array('id_transaksi' => $id_transaksi);
    $this->M_admin->delete('tb_barang_masuk',$where);
    redirect(base_url('admin/tabel_barangmasuk'));
  }



  public function proses_databarang_masuk_insert()
  {
    $this->form_validation->set_rules('lokasi','Lokasi','required');
    $this->form_validation->set_rules('kode_barang','Kode Barang','required');
    $this->form_validation->set_rules('nama_barang','Nama Barang','required');
    $this->form_validation->set_rules('jumlah','Jumlah','required');

    if($this->form_validation->run() == TRUE)
    {
      $id_transaksi = $this->input->post('id_transaksi',TRUE);
      // $tanggal      = $this->input->post('tanggal',TRUE);
      $nama_instansi = $this->input->post('nama_instansi', TRUE);
      $barang_butuh = $this->input->post('barang_butuh', TRUE);
      $lokasi       = $this->input->post('lokasi',TRUE);
      $kode_barang  = $this->input->post('kode_barang',TRUE);
      $nama_barang  = $this->input->post('nama_barang',TRUE);
      $satuan       = $this->input->post('satuan',TRUE);
      $jumlah       = $this->input->post('jumlah',TRUE);

      $data = array(
            'id_transaksi' => $id_transaksi,
            // 'tanggal'      => $tanggal,
            'nama_instansi' => $nama_instansi,
            'barang_butuh' => $barang_butuh,
            'lokasi'       => $lokasi,
            'kode_barang'  => $kode_barang,
            'nama_barang'  => $nama_barang,
            'satuan'       => $satuan,
            'jumlah'       => $jumlah
      );
      $this->M_admin->insert('tb_barang_masuk',$data);

      $this->session->set_flashdata('msg_berhasil','Data Barang Berhasil Ditambahkan');
      redirect(base_url('admin/form_barangmasuk'));
    }else {
      $data['list_satuan'] = $this->M_admin->select('tb_satuan');
      $this->load->view('admin/form_barangmasuk/form_insert',$data);
    }
  }

  public function proses_databarang_masuk_update()
  {
    $this->form_validation->set_rules('lokasi','Lokasi','required');
    $this->form_validation->set_rules('kode_barang','Kode Barang','required');
    $this->form_validation->set_rules('nama_barang','Nama Barang','required');
    $this->form_validation->set_rules('jumlah','Jumlah','required');

    if($this->form_validation->run() == TRUE)
    {
      $id_transaksi = $this->input->post('id_transaksi',TRUE);
      // $tanggal      = $this->input->post('tanggal',TRUE);
      $lokasi       = $this->input->post('lokasi',TRUE);
      $kode_barang  = $this->input->post('kode_barang',TRUE);
      $nama_barang  = $this->input->post('nama_barang',TRUE);
      $satuan       = $this->input->post('satuan',TRUE);
      $jumlah       = $this->input->post('jumlah',TRUE);

      $where = array('id_transaksi' => $id_transaksi);
      $data = array(
            'id_transaksi' => $id_transaksi,
            'tanggal'      => $tanggal,
            'lokasi'       => $lokasi,
            'kode_barang'  => $kode_barang,
            'nama_barang'  => $nama_barang,
            'satuan'       => $satuan,
            'jumlah'       => $jumlah
      );
      $this->M_admin->update('tb_barang_masuk',$data,$where);
      $this->session->set_flashdata('msg_berhasil','Data Barang Berhasil Diupdate');
      redirect(base_url('admin/tabel_barangmasuk'));
    }else{
      $this->load->view('admin/form_barangmasuk/form_update');
    }
  }
  ####################################
      // END DATA BARANG MASUK
  ####################################


  ####################################
              // SATUAN
  ####################################

  public function form_satuan()
  {
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->load->view('admin/form_satuan/form_insert',$data);
  }

  public function form_barangproduksi()
  {
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->load->view('admin/form_barangproduksi/form_insert',$data);
  }
  public function tabel_barangproduksi()
  {
    $data['list_data'] = $this->M_admin->select('tb_barangproduksi');
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->load->view('admin/tabel/tabel_barangproduksi',$data);
  }
  public function proses_barangproduksi_insert()
  {
    $this->form_validation->set_rules('kode_barangproduksi','Kode Barang Produksi','trim|required|max_length[100]');
    $this->form_validation->set_rules('nama_barangproduksi','Nama Barang Produksi','trim|required|max_length[100]');

    if($this->form_validation->run() ==  TRUE)
    {
      $kode_barangproduksi = $this->input->post('kode_barangproduksi' ,TRUE);
      $nama_barangproduksi = $this->input->post('nama_barangproduksi' ,TRUE);

      $data = array(
            'kode_barangproduksi' => $kode_barangproduksi,
            'nama_barangproduksi' => $nama_barangproduksi
      );
      $this->M_admin->insert('tb_barangproduksi',$data);

      $this->session->set_flashdata('msg_berhasil','Data Jenis Barang Berhasil Ditambahkan');
      redirect(base_url('admin/form_barangproduksi'));
    }else {
      $this->load->view('admin/form_barangproduksi/form_insert');
    }
  }
   public function proses_barangproduksi_update()
  {
    $this->form_validation->set_rules('kode_barangproduksi','Kode Barang Produksi','trim|required|max_length[100]');
    $this->form_validation->set_rules('nama_barangproduksi','Nama Barang Produksi','trim|required|max_length[100]');

    if($this->form_validation->run() == TRUE)
    {
      $kode_barangproduksi = $this->input->post('kode_barangproduksi' ,TRUE);
      $nama_barangproduksi = $this->input->post('nama_barangproduksi' ,TRUE);
      $where = array('id_barangproduksi' => $id_barangproduksi);
      $data = array(
            'kode_barangproduksi' => $kode_barangproduksi,
            'nama_barangproduksi' => $nama_barangproduksi
      );
      $this->M_admin->update('tb_barangproduksi',$data,$where);
      $this->session->set_flashdata('msg_berhasil','Data Barang Berhasil Diupdate');
      redirect(base_url('admin/tabel_barangproduksi'));
    }else {
      $this->load->view('admin/form_barangproduksi/form_insert');
    }
  }
  public function update_barangproduksi()
  {
    $uri = $this->uri->segment(3);
    $where = array('id_barangproduksi' => $uri);
    $data['data_barangproduksi'] = $this->M_admin->get_data('tb_barangproduksi',$where);
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->load->view('admin/form_barangproduksi/update_barangproduksi',$data);
  }

  public function delete_barangproduksi()
  {
    $uri = $this->uri->segment(3);
    $where = array('id_barangproduksi' => $uri);
    $this->M_admin->delete('tb_barangproduksi',$where);
    redirect(base_url('admin/tabel_barangproduksi'));
  }
  public function tabel_satuan()
  {
    $data['list_data'] = $this->M_admin->select('tb_satuan');
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->load->view('admin/tabel/tabel_satuan',$data);
  }

  public function update_satuan()
  {
    $uri = $this->uri->segment(3);
    $where = array('id_satuan' => $uri);
    $data['data_satuan'] = $this->M_admin->get_data('tb_satuan',$where);
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->load->view('admin/form_satuan/form_update',$data);
  }

  public function delete_satuan()
  {
    $uri = $this->uri->segment(3);
    $where = array('id_satuan' => $uri);
    $this->M_admin->delete('tb_satuan',$where);
    redirect(base_url('admin/tabel_satuan'));
  }

  public function proses_satuan_insert()
  {
    $this->form_validation->set_rules('kode_satuan','Kode Satuan','trim|required|max_length[100]');
    $this->form_validation->set_rules('nama_satuan','Nama Satuan','trim|required|max_length[100]');

    if($this->form_validation->run() ==  TRUE)
    {
      $kode_satuan = $this->input->post('kode_satuan' ,TRUE);
      $nama_satuan = $this->input->post('nama_satuan' ,TRUE);

      $data = array(
            'kode_satuan' => $kode_satuan,
            'nama_satuan' => $nama_satuan
      );
      $this->M_admin->insert('tb_satuan',$data);

      $this->session->set_flashdata('msg_berhasil','Data satuan Berhasil Ditambahkan');
      redirect(base_url('admin/form_satuan'));
    }else {
      $this->load->view('admin/form_satuan/form_insert');
    }
  }

  public function proses_satuan_update()
  {
    $this->form_validation->set_rules('kode_satuan','Kode Satuan','trim|required|max_length[100]');
    $this->form_validation->set_rules('nama_satuan','Nama Satuan','trim|required|max_length[100]');

    if($this->form_validation->run() ==  TRUE)
    {
      $id_satuan   = $this->input->post('id_satuan' ,TRUE);
      $kode_satuan = $this->input->post('kode_satuan' ,TRUE);
      $nama_satuan = $this->input->post('nama_satuan' ,TRUE);

      $where = array(
            'id_satuan' => $id_satuan
      );

      $data = array(
            'kode_satuan' => $kode_satuan,
            'nama_satuan' => $nama_satuan
      );
      $this->M_admin->update('tb_satuan',$data,$where);

      $this->session->set_flashdata('msg_berhasil','Data satuan Berhasil Di Update');
      redirect(base_url('admin/tabel_satuan'));
    }else {
      $this->load->view('admin/form_satuan/form_update');
    }
  }

  ####################################
            // END SATUAN
  ####################################


  ####################################
     // DATA MASUK KE DATA KELUAR
  ####################################

  public function barang_keluar()
  {
    $uri = $this->uri->segment(3);
    $where = array( 'id_transaksi' => $uri);
    $data['list_data'] = $this->M_admin->get_data('tb_barang_masuk',$where);
    $data['list_satuan'] = $this->M_admin->select('tb_satuan');
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->load->view('admin/perpindahan_barang/form_update',$data);
  }

  public function proses_data_keluar()
  {
    $this->form_validation->set_rules('id_transaksi','ID Transaksi','required');
    if($this->form_validation->run() === TRUE)
    {
      $id_transaksi   = $this->input->post('id_transaksi',TRUE);
      $tanggal_masuk  = $this->input->post('tanggal',TRUE);
      // $tanggal_keluar = $this->input->post('tanggal_keluar',TRUE);
      $lokasi         = $this->input->post('lokasi',TRUE);
      $kode_barang    = $this->input->post('kode_barang',TRUE);
      $nama_barang    = $this->input->post('nama_barang',TRUE);
      $satuan         = $this->input->post('satuan',TRUE);
      $jumlah         = $this->input->post('jumlah',TRUE);
      $nama_instansi  = $this->input->post('nama_instansi',TRUE);
      $barang_butuh   = $this->input->post('barang_butuh',TRUE);
      $note           = $this->input->post('note',TRUE);
      $where = array( 'id_transaksi' => $id_transaksi);
      $data = array(
              'id_transaksi' => $id_transaksi,
              'tanggal_masuk' => $tanggal_masuk,
              // 'tanggal_keluar' => $tanggal_keluar,
              'lokasi' => $lokasi,
              'kode_barang' => $kode_barang,
              'nama_barang' => $nama_barang,
              'satuan' => $satuan,
              'jumlah' => $jumlah,
              'nama_instansi' => $nama_instansi,
              'note' => $note,
              'barang_tambahan' => $barang_butuh,
      );
        $this->M_admin->insert('tb_barang_keluar',$data);
        $this->session->set_flashdata('msg_berhasil_keluar','Data Berhasil Keluar');
        redirect(base_url('admin/tabel_barangmasuk'));
    }else {
      $this->load->view('perpindahan_barang/form_update/'.$id_transaksi);
    }

  }
  ####################################
    // END DATA MASUK KE DATA KELUAR
  ####################################


  ####################################
        // DATA BARANG KELUAR
  ####################################

  public function tabel_barangkeluar()
  {
    $data['list_data'] = $this->M_admin->select('tb_barang_keluar');
    $data['list_satuan'] = $this->M_admin->select('tb_satuan');
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->load->view('admin/tabel/tabel_barangkeluar',$data);
  }


  ####################################
        // ORDER - DETAIL BARANG
  ####################################

  public function proses_detailbarang_insert()
  {
    $this->form_validation->set_rules('lokasi','Lokasi','required');
    $this->form_validation->set_rules('kode_barang','Kode Barang','required');
    $this->form_validation->set_rules('nama_barang','Nama Barang','required');
    $this->form_validation->set_rules('jumlah','Jumlah','required');

    if($this->form_validation->run() == TRUE)
    {
      $id_transaksi = $this->input->post('id_transaksi',TRUE);
      // $tanggal      = $this->input->post('tanggal',TRUE);
      $lokasi       = $this->input->post('lokasi',TRUE);
      $kode_barang  = $this->input->post('kode_barang',TRUE);
      $nama_barang  = $this->input->post('nama_barang',TRUE);
      $satuan       = $this->input->post('satuan',TRUE);
      $jumlah       = $this->input->post('jumlah',TRUE);

      $data = array(
            'id_transaksi' => $id_transaksi,
            // 'tanggal'      => $tanggal,
            'lokasi'       => $lokasi,
            'kode_barang'  => $kode_barang,
            'nama_barang'  => $nama_barang,
            'satuan'       => $satuan,
            'jumlah'       => $jumlah
      );
      $data['list_satuan'] = $this->M_admin->select('tb_satuan');
      $this->M_admin->insert('tb_barang_masuk',$data);

      $this->session->set_flashdata('msg_berhasil','Data Barang Berhasil Ditambahkan');
      redirect(base_url('admin/form_barangmasuk'));
    }else {
      $data['list_satuan'] = $this->M_admin->select('tb_satuan');
      $this->load->view('admin/form_barangmasuk/form_insert',$data);
    }
  }


  public function form_pemilih()
  {
    $data['list_pemilih'] = $this->M_admin->select('pemilih');
    $data['list_dapil'] = $this->M_admin->select('dapil');
    $data['token_generate'] = $this->token_generate();
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->session->set_userdata($data);
    $this->load->view('admin/form_pemilih/form_insert',$data);
  }

  public function proses_pemilih_insert()
  {
    $this->form_validation->set_rules('nik','NIK','required');
    $this->form_validation->set_rules('nama','Nama','required');
   
    if($this->form_validation->run() == TRUE)
    {
      $nik = $this->input->post('nik',TRUE);
      $nama = $this->input->post('nama',TRUE);
      $tempat_lahir = $this->input->post('tempat_lahir', TRUE);
      $tanggal_lahir = $this->input->post('tanggal_lahir', TRUE);
      $jk       = $this->input->post('jk',TRUE);
      $alamat  = $this->input->post('alamat',TRUE);
      $agama  = $this->input->post('agama',TRUE);
      $status_perkawinan       = $this->input->post('status_perkawinan',TRUE);
      $pekerjaan       = $this->input->post('pekerjaan',TRUE);
      $kewarganegaraan       = $this->input->post('kewarganegaraan',TRUE);
      $password  = $this->input->post('password',TRUE);
      $role  = $this->input->post('role',TRUE);
      $data = array(
            'nik' => $nik,
            'nama' => $nama,
            'tempat_lahir' => $tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir,
            'jk'       => $jk,
            'alamat'  => $alamat,
            'agama'  => $agama,
            'status_perkawinan'       => $status_perkawinan,
            'pekerjaan'       => $pekerjaan,
            'kewarganegaraan'       => $kewarganegaraan
      );
      $this->M_admin->insert('pemilih',$data);

      $this->session->set_flashdata('msg_berhasil','Data Pemilih Berhasil Ditambahkan');
      redirect(base_url('admin/form_pemilih'));
    }else {
      $data['list_pemilih'] = $this->M_admin->select('pemilih');
      $this->load->view('admin/form_pemilih/form_insert',$data);
    }
  }

  public function proses_pemilih_update()
  {
    $this->form_validation->set_rules('nik','NIK','trim|required|max_length[100]');

    if($this->form_validation->run() ==  TRUE)
    {
      
      $nik = $this->input->post('nik' ,TRUE);
      $nama = $this->input->post('nama');
      $tempat_lahir = $this->input->post('tempat_lahir');
      $tanggal_lahir = $this->input->post('tanggal_lahir');
      $jk = $this->input->post('jk');
      $alamat = $this->input->post('alamat');
      $nama = $this->input->post('nama');
      $tempat_lahir = $this->input->post('tempat_lahir');
      $agama = $this->input->post('agama');
      $status_perkawinan = $this->input->post('status_perkawinan');
      $pekerjaan = $this->input->post('pekerjaan');
      $kewarganegaraan = $this->input->post('kewarganegaraan');
      
      $where = array(
            'nik' => $nik
      );

      $data = array(
            'nik' => $nik,
            'nama' => $nama,
            'tempat_lahir' => $tempat_lahir,
            'tanggal_lahir' => $tanggal_lahir,
            'jk' => $jk,
            'alamat' => $alamat,
            'agama' => $agama,
            'status_perkawinan' => $status_perkawinan,
            'pekerjaan' => $pekerjaan,
            'kewarganegaraan' => $kewarganegaraan,

      );
      $this->M_admin->update('pemilih',$data,$where);

      $this->session->set_flashdata('msg_berhasil','Data Pemilih Berhasil Di Update');
      redirect(base_url('admin/users'));
    }else {
      $this->load->view('admin/form_pemilih/form_update');
    }
  }

  public function proses_delete_pemilih()
  {
    $nik = $this->uri->segment(3);
    $where = array('nik' => $nik);
    $this->M_admin->delete('pemilih',$where);
    $this->session->set_flashdata('msg_berhasil','Pemilih Behasil Di Delete');
    redirect(base_url('admin/users'));

  }

  public function tabel_pemilih()
  {
    $data['list_data'] = $this->M_admin->select('pemilih');
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->load->view('admin/tabel/tabel_pemilih',$data);
  }

  public function calon()
  {
    $data['list_users'] = $this->M_admin->kecuali('calon',$this->session->userdata('name')); $data['token_generate'] = $this->token_generate();
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->session->set_userdata($data);
    $this->load->view('admin/calon',$data);
  }

  public function form_calon()
  {
    $data['list_pemilih'] = $this->M_admin->select('pemilih');
    $data['list_dapil'] = $this->M_admin->select('dapil');
    $data['token_generate'] = $this->token_generate();
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->session->set_userdata($data);
    $this->load->view('admin/form_calon/form_insert',$data);
  }

  public function proses_calon_insert()
  {

    $config =  array(
                   'upload_path'     => "./assets/upload/user/img/",
                   'allowed_types'   => "gif|jpg|png|jpeg",
                   'encrypt_name'    => False, //
                   'max_size'        => "50000",  // ukuran file gambar
                   'max_height'      => "9680",
                   'max_width'       => "9024"
                 );
      $this->load->library('upload',$config);
      $this->upload->initialize($config);

      if( ! $this->upload->do_upload('userpicture'))
      {
        $this->session->set_flashdata('msg_error_gambar', $this->upload->display_errors());
        $this->load->view('admin/form_calon',$data);
      }else{
        $upload_data = $this->upload->data();
        $nama_file = $upload_data['file_name'];
        $ukuran_file = $upload_data['file_size'];

        //resize img + thumb Img -- Optional
        $config['image_library']     = 'gd2';
        $config['source_image']      = $upload_data['full_path'];
        $config['create_thumb']      = FALSE;
        $config['maintain_ratio']    = TRUE;
        $config['width']             = 150;
        $config['height']            = 150;

        $this->load->library('image_lib', $config);
        $this->image_lib->initialize($config);
        if (!$this->image_lib->resize())
        {
          $data['pesan_error'] = $this->image_lib->display_errors();
          $this->load->view('admin/form_calon',$data);
        }
        $this->form_validation->set_rules('nik','NIK','required');
        $this->form_validation->set_rules('nama','Nama','required');
       
        if($this->form_validation->run() == TRUE)
        {
          $no_urut = $this->input->post('no_urut');
          $nik = $this->input->post('nik',TRUE);
          $nama = $this->input->post('nama',TRUE);
          $visi  = $this->input->post('visi',TRUE);
          $misi       = $this->input->post('misi',TRUE);
          $dapil       = $this->input->post('dapil',TRUE);

          $data = array(
                'nama_file' => $nama_file,
                'no_urut' => $no_urut,
                'nik' => $nik,
                'nama' => $nama,
                'visi'  => $visi,
                'misi'       => $misi,
                'dapil'       => $dapil
          );
          $this->M_admin->insert('calon',$data);

          $this->session->set_flashdata('msg_berhasil','Data Calon Berhasil Ditambahkan');
          redirect(base_url('admin/calon'));
        }else {
          $data['list_calon'] = $this->M_admin->select('calon');
          $this->load->view('admin/form_calon/form_insert',$data);
        }
      }

    
  }

  public function update_calon()
  {
    $nik = $this->uri->segment(3);
    $where = array('nik' => $nik);
    $data['token_generate'] = $this->token_generate();
    $data['list_data'] = $this->M_admin->get_data('calon',$where);
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->session->set_userdata($data);
    $this->load->view('admin/form_calon/form_update',$data);
  }

  public function proses_calon_update()
  {
    $this->form_validation->set_rules('nik','NIK','trim|required|max_length[100]');

    if($this->form_validation->run() ==  TRUE)
    {
      
      $nik = $this->input->post('nik' ,TRUE);
      $nama = $this->input->post('nama');
      $no_urut = $this->input->post('no_urut');
      $visi = $this->input->post('visi');
      $misi = $this->input->post('misi');
      $dapil = $this->input->post('dapil');
      
      
      $where = array(
            'nik' => $nik
      );

      $data = array(
            'nik' => $nik,
            'nama' => $nama,
            'no_urut' => $no_urut,
            'visi' => $visi,
            'misi' => $misi,
            'dapil' => $dapil

      );
      $this->M_admin->update('calon',$data,$where);

      $this->session->set_flashdata('msg_berhasil','Data Calon Berhasil Di Update');
      redirect(base_url('admin/calon'));
    }else {
      $this->load->view('admin/form_calon/form_update');
    }
  }

  public function proses_delete_calon()
  {
    $nik = $this->uri->segment(3);
    $where = array('nik' => $nik);
    $this->M_admin->delete('calon',$where);
    $this->session->set_flashdata('msg_berhasil','User Behasil Di Delete');
    redirect(base_url('admin/calon'));

  }

  public function dapil()
  {
    $data['list_dapil'] = $this->M_admin->select('dapil');
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->session->set_userdata($data);
    $this->load->view('admin/dapil',$data);
  }

  public function form_dapil()
  {
    // $data['list_satuan'] = $this->M_admin->select('tb_satuan');
    $data['token_generate'] = $this->token_generate();
    $data['avatar'] = $this->M_admin->get_data_gambar('tb_upload_gambar_user',$this->session->userdata('name'));
    $this->session->set_userdata($data);
    $this->load->view('admin/form_dapil/form_insert',$data);
  }

  public function proses_dapil_insert()
  {
    $this->form_validation->set_rules('detail_dapil','Nama Daerah Pemilihan','required');
   
    if($this->form_validation->run() == TRUE)
    {
      $detail_dapil = $this->input->post('detail_dapil',TRUE);
    

      $data = array(
            'detail_dapil' => $detail_dapil
      );
      $this->M_admin->insert('dapil',$data);

      $this->session->set_flashdata('msg_berhasil','Data Daerah Pemilihan Berhasil Ditambahkan');
      redirect(base_url('admin/dapil'));
    }else {
      // $data['list_pemilih'] = $this->M_admin->select('pemilih');
      // $this->load->view('admin/form_pemilih/form_insert',$data);
    }
  }
}
?>
