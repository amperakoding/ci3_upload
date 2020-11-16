<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Berita</title>
</head>

<body>
  <h1>Update Berita</h1>

  <?php echo validation_errors() ?>
  <?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');} ?>

  <?php echo form_open_multipart('berita/update_process') ?>

  <p>
    <label>Judul Berita</label>
    <br>
    <?php echo form_input($judul_berita, $berita->judul_berita) ?>
  </p>

  <p>
    <label>Deskripsi Berita</label>
    <br>
    <?php echo form_textarea($deskripsi_berita, $berita->deskripsi_berita) ?>
  </p>

  <p>
    <label>Foto Saat Ini</label>
    <br>
    <img src="<?php echo base_url('assets/images/berita/'.$berita->file_foto_thumb) ?>">
  </p>

  <p>
    <label>Upload Foto Baru</label>
    <input type="file" id="file_foto" name="file_foto">
  </p>

  <?php echo form_input($id_berita, $berita->id_berita) ?>

  <button type="submit" name="submit">Simpan</button>
  <button type="reset" name="reset">Reset</button>
  <a href="<?php echo base_url('berita') ?>">Kembali ke Daftar Berita</a>

  <?php echo form_close() ?>
</body>

</html>