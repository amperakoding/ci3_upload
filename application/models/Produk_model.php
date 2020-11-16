<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Produk_model extends CI_Model
{
  public $table = 'produk';
  public $id    = 'id_produk';
  public $order = 'DESC';

  // ambil semua data
  function get_all()
  {
    $this->db->order_by($this->id, $this->order);

    return $this->db->get($this->table)->result();
  }

  function get_all_foto_lainnya_by_id_produk($id)
  {
    $this->db->where('produk_id', $id);

    return $this->db->get('produk_foto')->result();
  }

  function get_by_id($id)
  {
    $this->db->where($this->id, $id);

    return $this->db->get($this->table)->row();
  }

  function get_foto_lainnya_by_id_foto($id)
  {
    $this->db->where('id_foto', $id);

    return $this->db->get('produk_foto')->row();
  }

  function get_foto_lainnya_by_id_produk($id)
  {
    $this->db->where('produk_id', $id);

    return $this->db->get('produk_foto')->result();
  }

  // tambah data
  function insert($data)
  {
    $this->db->insert($this->table, $data);
  }

  function insert_foto_lainnya($data)
  {
    $this->db->insert('produk_foto', $data);
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

  function delete_foto_lainnya_by_id_foto($id)
  {
    $this->db->where('id_foto', $id);

    $this->db->delete('produk_foto');
  }

  function delete_foto_lainnya_by_id_produk($id)
  {
    $this->db->where('produk_id', $id);

    $this->db->delete('produk_foto');
  }
}
