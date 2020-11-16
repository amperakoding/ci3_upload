<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Daftar Berita</title>
</head>

<body>
  <h1>Daftar Berita</h1>

  <hr>

  <a href="<?php echo base_url('berita/create') ?>">Tambah Data Berita</a>

  <?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');} ?>

  <hr>

  <table border="1">
    <thead>
      <tr>
        <td>No.</td>
        <td>Judul Berita</td>
        <td>Deskripsi</td>
        <td>Foto</td>
        <td>Aksi</td>
      </tr>
    </thead>
    <tbody>
      <?php
      $no = 1;
      foreach ($get_all_berita as $data) {
      ?>
        <tr>
          <td><?php echo $no++ ?></td>
          <td><?php echo $data->judul_berita ?></td>
          <td><?php echo $data->deskripsi_berita ?></td>
          <td><img src="<?php echo base_url('assets/images/berita/' . $data->file_foto_thumb) ?>"></td>
          <td>
            <a href="<?php echo base_url('berita/update/' . $data->id_berita) ?>"><i class="fa fa-pencil-alt"></i> Ubah</a>
            <a href="<?php echo base_url('berita/delete/' . $data->id_berita) ?>"><i class="fa fa-trash"></i> Hapus</a>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>

</body>

</html>