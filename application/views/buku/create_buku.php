<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Buku</title>
</head>

<body>
  <h1>Tambah Buku Baru</h1>

  <?php echo validation_errors() ?>
  <?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');} ?>

  <?php echo form_open_multipart('buku/create_process') ?>

  <p>
    <label>Judul Buku</label>
    <?php echo form_input($judul_buku) ?>
  </p>

  <p>
    <label>Penulis Buku</label>
    <?php echo form_input($penulis_buku) ?>
  </p>

  <p>
    <label>Upload Foto</label>
    <input type="file" id="file_foto" name="file_foto">
  </p>

  <button type="submit" name="submit">Simpan</button>
  <button type="reset" name="reset">Reset</button>
  <a href="<?php echo base_url('buku') ?>">Kembali ke Daftar Buku</a>

  <?php echo form_close() ?>
</body>

</html>