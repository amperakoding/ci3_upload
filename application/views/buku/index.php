<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Buku</title>
</head>

<body>
  <h1>Daftar Buku</h1>

  <hr>

  <a href="<?php echo base_url('buku/create') ?>">Tambah Data Buku</a>

  <?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');} ?>

  <hr>

  <table border="1">
    <thead>
      <tr>
        <td>No.</td>
        <td>Judul Buku</td>
        <td>Penulis</td>        
        <td>Foto</td>
        <td>Aksi</td>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      foreach ($get_all_buku as $data) {        
      ?>
        <tr>
          <td><?php echo $no++ ?></td>
          <td><?php echo $data->judul_buku ?></td>
          <td><?php echo $data->penulis_buku ?></td>
          <td><img src="<?php echo base_url('assets/images/buku/'.$data->file_foto) ?>"></td>          
          <td>
            <a href="<?php echo base_url('buku/update/' . $data->id_buku) ?>"><i class="fa fa-pencil-alt"></i> Ubah</a>
            <a href="<?php echo base_url('buku/delete/' . $data->id_buku) ?>"><i class="fa fa-trash"></i> Hapus</a>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

</body>

</html>