<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Buku</title>
</head>

<body>
  <h1>Update Buku</h1>

  <?php echo validation_errors() ?>
  <?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');} ?>

  <?php echo form_open_multipart('buku/update_process') ?>

  <p>
    <label>Judul Buku</label>
    <?php echo form_input($judul_buku, $buku->judul_buku) ?>
  </p>

  <p>
    <label>Penulis Buku</label>
    <?php echo form_input($penulis_buku, $buku->penulis_buku) ?>
  </p>

  <p>
    <label>Foto Saat Ini</label>
    <br>
    <img src="<?php echo base_url('assets/images/buku/'.$buku->file_foto) ?>" width="300px">
  </p>

  <p>
    <label>Upload Foto Baru</label>
    <input type="file" id="file_foto" name="file_foto">
  </p>

  <?php echo form_input($id_buku, $buku->id_buku) ?>

  <button type="submit" name="submit">Simpan</button>
  <button type="reset" name="reset">Reset</button>
  <a href="<?php echo base_url('buku') ?>">Kembali ke Daftar Buku</a>

  <?php echo form_close() ?>
</body>

</html>