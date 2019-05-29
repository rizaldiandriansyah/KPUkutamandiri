<?php
class M_pemilihan extends CI_Model{


  public function select($tabel,$dapil)
  {
    $query = $this->db->select('*')
                      ->from($tabel)
                      ->where('dapil',$dapil)
                      ->get();
    return $query->result();
  }

  public function select_id($tabel,$id)
  {
    $query = $this->db->select('*')
                      ->from($tabel)
                      ->where('id',$id)
                      ->get();
    return $query->result();
  }

  public function update($table,$data,$where)
  {
    $this->db->where($where);
    $this->db->update($table,$data);
  }
}
 ?>
