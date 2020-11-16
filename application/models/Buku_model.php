<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Buku_model extends CI_Model
{
  public $table = 'buku';
  public $id    = 'id_buku';
  public $order = 'DESC';

  // ambil semua data
  function get_all()
  {
    $this->db->order_by($this->id, $this->order);
    return $this->db->get($this->table)->result();
  }

  function get_by_id($id)
  {
    $this->db->where($this->id, $id);
    return $this->db->get($this->table)->row();
  }

  // tambah data
  function insert($data)
  {
    $this->db->insert($this->table, $data);
  }

  // ubah data
  function update($id, $data)
  {
    $this->db->where($this->id, $id);
    $this->db->update($this->table, $data);
  }

  // hapus data
  function delete($id)
  {
    $this->db->where($this->id, $id);
    $this->db->delete($this->table);
  }
}
