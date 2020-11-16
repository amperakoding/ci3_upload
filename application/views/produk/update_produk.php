<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Update Produk</title>
</head>

<body>
  <h1>Update Produk</h1>

  <?php echo validation_errors() ?>
  <?php if ($this->session->flashdata('message')) {
    echo $this->session->flashdata('message');
  } ?>

  <?php echo form_open_multipart('produk/update_process') ?>

  <p>
    <label>Judul Produk</label>
    <br>
    <?php echo form_input($judul_produk, $produk->judul_produk) ?>
  </p>

  <p>
    <label>Berat Produk</label>
    <br>
    <?php echo form_input($berat_produk, $produk->berat_produk) ?>
  </p>

  <p>
    <label>Foto Utama Saat Ini</label>
    <br>
    <img src="<?php echo base_url('assets/images/produk/' . $produk->file_foto_thumb) ?>">
  </p>

  <p>
    <label>Foto Tambahan Saat Ini</label>
    <br>
    <?php if ($foto_produk_lainnya == NULL) {
      echo "Belum ada file";
    } ?>
    <ol>
      <?php foreach ($foto_produk_lainnya as $foto_lainnya) { ?>
        <li>
          <b><?php echo $foto_lainnya->foto_lainnya ?></b>
          <br>
          <img src="<?php echo base_url('assets/images/produk_lainnya/' . $foto_lainnya->foto_lainnya) ?>" width="200px">
          <a href="<?php echo base_url('produk/delete_foto_lainnya_by_id_foto/' . $foto_lainnya->id_foto) ?>" class="btn btn-xs btn-danger">Hapus</a>
        </li>
        <br>
      <?php } ?>
    </ol>
  </p>

  <hr>

  <p>
    <label>Upload Foto Utama Baru</label>
    <br>
    <input type="file" id="file_foto" name="file_foto">
  </p>

  <p>
    <label>Upload Foto Tambahan Baru</label>
    <br>
    <input type="file" name="foto_lainnya[]" class="multi with-preview" data-maxfile="2048" accept="jpeg|jpg|png" multiple>
  </p>

  <?php echo form_input($id_produk, $produk->id_produk) ?>

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