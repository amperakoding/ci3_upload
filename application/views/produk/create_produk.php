<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tambah Produk</title>
</head>

<body>
  <h1>Tambah Produk Baru</h1>

  <?php echo validation_errors() ?>
  <?php if($this->session->flashdata('message')){echo $this->session->flashdata('message');} ?>

  <?php echo form_open_multipart('produk/create_process') ?>

  <p>
    <label>Judul Produk</label>
    <br>
    <?php echo form_input($judul_produk) ?>
  </p>

  <p>
    <label>Berat Produk</label>
    <br>
    <?php echo form_input($berat_produk) ?>
  </p>

  <p>
    <label>Upload Foto Utama</label>
    <input type="file" class="form-control" name="file_foto" id="file_foto"/>
  </p>

  <p>
    <label>Upload Foto Tambahan</label>
    <input type="file" name="foto_lainnya[]" class="multi with-preview" data-maxfile="2048" accept="jpeg|jpg|png" multiple>
  </p>

  <button type="submit" name="submit">Simpan</button>
  <button type="reset" name="reset">Reset</button>
  <a href="<?php echo base_url('produk') ?>">Kembali ke Daftar Produk</a>

  <?php echo form_close() ?>

  <!-- jQuery 3 -->
  <script src="<?php echo base_url('assets/plugins/jquery/') ?>jquery.min.js"></script>
  <!-- multifile -->
  <script src="<?php echo base_url('assets/plugins/multifile/') ?>jquery.MultiFile.js"></script>
</body>

</html>