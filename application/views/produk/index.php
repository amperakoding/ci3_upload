<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Produk</title>
</head>

<body>
  <h1>Daftar Produk</h1>

  <hr>

  <a href="<?php echo base_url('produk/create') ?>">Tambah Data Produk</a>

  <?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');} ?>

  <hr>

  <table border="1">
    <thead>
      <tr>
        <td>No.</td>
        <td>Judul Produk</td>
        <td>Berat</td>
        <td>Foto</td>
        <td>Aksi</td>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      foreach ($get_all_produk as $data) {
      ?>
        <tr>
          <td><?php echo $no++ ?></td>
          <td><?php echo $data->judul_produk ?></td>
          <td><?php echo $data->berat_produk ?></td>
          <td><img src="<?php echo base_url('assets/images/produk/' . $data->file_foto_thumb) ?>"></td>
          <td>
            <a href="<?php echo base_url('produk/update/' . $data->id_produk) ?>"><i class="fa fa-pencil-alt"></i> Ubah</a>
            <a href="<?php echo base_url('produk/delete/' . $data->id_produk) ?>"><i class="fa fa-trash"></i> Hapus</a>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

</body>

</html>