<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Produk extends CI_Controller
{
  function __construct()
  {
    parent::__construct();

    $this->load->model('Produk_model');
  }

  public function index()
  {
    $data['get_all_produk'] = $this->Produk_model->get_all();

    $this->load->view('produk/index', $data);
  }

  public function create()
  {
    $data['judul_produk'] = array(
      'name'  => 'judul_produk',
      'id'    => 'judul_produk',
      'class' => 'form-control',
      'value' => $this->form_validation->set_value('judul_produk'),
    );
    $data['berat_produk'] = array(
      'name'  => 'berat_produk',
      'id'    => 'berat_produk',
      'class' => 'form-control',
      'value' => $this->form_validation->set_value('berat_produk'),
    );

    $this->load->view('produk/create_produk', $data);
  }

  public function create_process()
  {
    // siapkan form mana saja yg wajib divalidasi sehingga apabila error maka value akan tetap tersimpan sementara
    $this->form_validation->set_rules('judul_produk', 'Judul Produk', 'trim|required');
    $this->form_validation->set_rules('berat_produk', 'Berat Produk', 'trim');

    // set pesan form validasi error
    $this->form_validation->set_message('required', '{field} wajib diisi');

    // mulai proses validasi form
    if ($this->form_validation->run() == FALSE) {
      // kalau gagal validasi maka arahkan ke method create
      $this->create();
    }
    // lanjutkan ke tahap selanjutnya apabila lolos/tidak ada masalah di form_validation
    else {
      // cek apakah file memang ada untuk diupload atau tidak dengan mengambil nilai tmp_name nya
      if (file_exists($_FILES["file_foto"]["tmp_name"])) {
        // ganti nama file bawaan menjadi berdasarkan judul_produk dan waktu upload supaya menghindari foto tidak berubah
        // saat proses update data karena cache browser yang masih tersimpan
        $namaFile = strtolower(url_title($this->input->post('judul_produk'))) . '_-_' . date('YmdHis');

        // atur lokasi penyimpanan file foto
        $config['upload_path']      = './assets/images/produk/';
        // atur file apa saja yang boleh diupload berdasarkan mime types filenya
        $config['allowed_types']    = 'jpg|jpeg|png';
        // atur maksimum file yang boleh diupload, apabila ingin lebih dari 2mb maka atur jg di php.ini xampp/lampp Anda
        $config['max_size']         = '2048'; // 2 MB (dalam ukuran kilobytes)
        // atur nama file yang diupload
        $config['file_name']        = $namaFile; //nama yang terupload nantinya

        // panggil library upload CI3 dengan memasukkan config yang sudah diatur sebelumnya
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        // jika file yg di upload bermasalah
        if (!$this->upload->do_upload('file_foto')) {
          // siapkan variabel untuk ditampilkan pada view
          $error = array('error' => $this->upload->display_errors());
          $this->session->set_flashdata('message', $error['error']);

          $this->create();
        } else {
          $fileName          = $this->upload->data('file_name');
          $fileExtension     = $this->upload->data('file_ext');

          // panggil library image manipulation CI supaya dapat membuat thumbnail
          $config['image_library']    = "gd2";
          // ambil sumber foto yang akan diresize
          $config['source_image']     = "./assets/images/produk/" . $fileName . "";
          // buat file_foto_thumb ke direktori yang sama
          $config['new_image']        = "./assets/images/produk/";
          // buat thumbnail
          $config['create_thumb']     = TRUE;
          // maintain ratio
          $config['maintain_ratio']   = TRUE;
          // atur lebar foto
          $config['width']            = 100;
          // atur tinggi foto
          $config['height']           = 100;

          $this->load->library('image_lib', $config);
          $this->image_lib->resize();

          $data = array(
            'judul_produk'        => $this->input->post('judul_produk'),
            'berat_produk'        => $this->input->post('berat_produk'),
            'file_foto'           => $fileName,
            'file_foto_thumb'     => $namaFile . '_thumb' . $fileExtension,
          );

          // eksekusi query INSERT
          $this->Produk_model->insert($data);
          // ambil id produk yg baru masuk
          $produk_id = $this->db->insert_id();

          // apabila upload foto tambahan
          if (!empty($_FILES["foto_lainnya"]["name"])) {
            // menghitung jumlah file yang akan diupload
            $filesCount   = count($_FILES['foto_lainnya']['name']);
            // atur maksimum upload file size
            $maxFileSize  = 2000000; // dalam satuan byte            

            // cek apakah foto_lainnya melebihi batas yang telah ditentukan
            if ($_FILES['foto_lainnya']['size'][0] > $maxFileSize) {
              $this->session->set_flashdata('message', 'File yang Anda upload melebihi batas maksimum (2Mb)');
              $this->create();
            } else {

              // looping sebanyak jumlah foto yang diupload
              for ($i = 0; $i < $filesCount; $i++) {
                $namaFileLainnya = strtolower(url_title($this->input->post('judul_produk'))) . '_dll_-_' . date('YmdHis');

                // File upload configuration
                $config_lainnya['upload_path']    = './assets/images/produk_lainnya/';
                $config_lainnya['allowed_types']  = 'jpg|jpeg|png';
                $config_lainnya['file_name']      = $namaFileLainnya;

                $_FILES['file']['name']           = $_FILES['foto_lainnya']['name'][$i];
                $_FILES['file']['type']           = $_FILES['foto_lainnya']['type'][$i];
                $_FILES['file']['tmp_name']       = $_FILES['foto_lainnya']['tmp_name'][$i];
                $_FILES['file']['error']          = $_FILES['foto_lainnya']['error'][$i];
                $_FILES['file']['size']           = $_FILES['foto_lainnya']['size'][$i];

                // Load and initialize upload library
                $this->load->library('upload', $config_lainnya);
                $this->upload->initialize($config_lainnya);

                // Upload file to server
                if (!$this->upload->do_upload('file')) {
                  //file gagal diupload -> kembali ke form tambah
                  $error = array('error' => $this->upload->display_errors());
                  $this->session->set_flashdata('message', $error['error']);

                  $this->create();
                } else {
                  $fileNameLainnya          = $this->upload->data('file_name');

                  $data_foto_lainnya = array(
                    'produk_id'             => $produk_id,
                    'foto_lainnya'          => $fileNameLainnya,
                  );

                  // Insert files data into the database
                  $this->Produk_model->insert_foto_lainnya($data_foto_lainnya);
                }
              }
            }
          }

          // set pesan data berhasil disimpan
          $this->session->set_flashdata('message', 'Data berhasil disimpan');
          redirect('produk');
        }
      } else // Jika file upload kosong
      {
        $data = array(
          'judul_produk'          => $this->input->post('judul_produk'),
          'berat_produk'        => $this->input->post('berat_produk'),
        );

        // eksekusi query INSERT
        $this->Produk_model->insert($data);
        // set pesan data berhasil disimpan
        $this->session->set_flashdata('message', 'Data berhasil disimpan');
        redirect('produk');
      }
    }
  }

  public function update($id)
  {
    $data['produk']               = $this->Produk_model->get_by_id($id);
    $data['foto_produk_lainnya']  = $this->Produk_model->get_all_foto_lainnya_by_id_produk($id);

    if ($data['produk']) {
      $data['id_produk'] = array(
        'name'  => 'id_produk',
        'type'  => 'hidden',
      );
      $data['judul_produk'] = array(
        'name'  => 'judul_produk',
        'id'    => 'judul_produk',
        'class' => 'form-control',
      );
      $data['berat_produk'] = array(
        'name'  => 'berat_produk',
        'id'    => 'berat_produk',
        'class' => 'form-control',
      );

      $this->load->view('produk/update_produk', $data);
    } else {
      $this->session->set_flashdata('message', 'Data tidak ditemukan');
      redirect('produk');
    }
  }

  public function update_process()
  {
    // siapkan form mana saja yg wajib divalidasi sehingga apabila error maka value akan tetap tersimpan sementara
    $this->form_validation->set_rules('judul_produk', 'Judul Produk', 'trim|required');
    $this->form_validation->set_rules('berat_produk', 'Berat Produk', 'trim');

    // set pesan form validasi error
    $this->form_validation->set_message('required', '{field} wajib diisi');

    // mulai proses validasi form
    if ($this->form_validation->run() == FALSE) {
      // kalau gagal validasi maka arahkan ke method create
      $this->update($this->input->post('id_produk'));
    }
    // lanjutkan ke tahap selanjutnya apabila lolos/tidak ada masalah di form_validation
    else {
      // cek apakah file memang ada untuk diupload atau tidak dengan mengambil nilai tmp_name nya
      if (file_exists($_FILES["file_foto"]["tmp_name"])) {
        // ganti nama file bawaan menjadi berdasarkan judul_produk dan waktu upload supaya menghindari foto tidak berubah
        // saat proses update data karena cache browser yang masih tersimpan
        $namaFile = strtolower(url_title($this->input->post('judul_produk'))) . '_-_' . date('YmdHis');

        // atur lokasi penyimpanan file foto
        $config['upload_path']      = './assets/images/produk/';
        // atur file apa saja yang boleh diupload berdasarkan mime types filenya
        $config['allowed_types']    = 'jpg|jpeg|png';
        // atur maksimum file yang boleh diupload, apabila ingin lebih dari 2mb maka atur jg di php.ini xampp/lampp Anda
        $config['max_size']         = '2048'; // 2 MB (dalam ukuran kilobytes)
        // atur nama file yang diupload
        $config['file_name']        = $namaFile; //nama yang terupload nantinya

        // panggil library upload CI3 dengan memasukkan config yang sudah diatur sebelumnya
        $this->load->library('upload', $config);
        $this->upload->initialize($config);

        // jika file yg di upload bermasalah
        if (!$this->upload->do_upload('file_foto')) {
          // siapkan variabel untuk ditampilkan pada view
          $error = array('error' => $this->upload->display_errors());
          $this->session->set_flashdata('message', $error['error']);

          $this->update($this->input->post('id_produk'));
        } else {
          // siapkan data untuk ambil kolom file_foto untuk dihapus dari direktori file
          $data               = $this->Produk_model->get_by_id($this->input->post('id_produk'));
          // siapkan url foto beserta namanya
          $file_foto          = "assets/images/produk/" . $data->file_foto;
          $file_foto_thumb    = "assets/images/produk/" . $data->file_foto_thumb;

          // cek apabila file memang ada dengan file_exists maka hapus dengan fungsi unlink
          if (file_exists($file_foto)) {
            // Hapus foto dan thumbnail
            unlink($file_foto);
            unlink($file_foto_thumb);
          }

          $fileName          = $this->upload->data('file_name');
          $fileExtension     = $this->upload->data('file_ext');

          // panggil library image manipulation CI supaya dapat membuat thumbnail
          $config['image_library']    = "gd2";
          // ambil sumber foto yang akan diresize
          $config['source_image']     = "./assets/images/produk/" . $fileName . "";
          // buat file_foto_thumb ke direktori yang sama
          $config['new_image']        = "./assets/images/produk/";
          // buat thumbnail
          $config['create_thumb']     = TRUE;
          // maintain ratio
          $config['maintain_ratio']   = TRUE;
          // atur lebar foto
          $config['width']            = 100;
          // atur tinggi foto
          $config['height']           = 100;

          $this->load->library('image_lib', $config);
          $this->image_lib->resize();

          $data = array(
            'judul_produk'        => $this->input->post('judul_produk'),
            'berat_produk'        => $this->input->post('berat_produk'),
            'file_foto'           => $fileName,
            'file_foto_thumb'     => $namaFile . '_thumb' . $fileExtension,
          );

          // eksekusi query INSERT
          $this->Produk_model->update($this->input->post('id_produk'), $data);

          // apabila upload foto tambahan
          if (!empty($_FILES["foto_lainnya"]["name"])) {
            // menghitung jumlah file yang akan diupload
            $filesCount   = count($_FILES['foto_lainnya']['name']);
            // atur maksimum upload file size
            $maxFileSize  = 2000000; // dalam satuan byte

            // cek apakah foto_lainnya melebihi batas yang telah ditentukan
            if ($_FILES['foto_lainnya']['size'][0] > $maxFileSize) {
              $this->session->set_flashdata('message', 'File yang Anda upload melebihi batas maksimum (2Mb)');
              $this->update($this->input->post('id_produk'));
            } else {

              // looping sebanyak jumlah foto yang diupload
              for ($i = 0; $i < $filesCount; $i++) {
                $namaFileLainnya = strtolower(url_title($this->input->post('judul_produk'))) . '_dll_-_' . date('YmdHis');

                // File upload configuration
                $config_lainnya['upload_path']    = './assets/images/produk_lainnya/';
                $config_lainnya['allowed_types']  = 'jpg|jpeg|png';
                $config_lainnya['file_name']      = $namaFileLainnya;

                $_FILES['file']['name']           = $_FILES['foto_lainnya']['name'][$i];
                $_FILES['file']['type']           = $_FILES['foto_lainnya']['type'][$i];
                $_FILES['file']['tmp_name']       = $_FILES['foto_lainnya']['tmp_name'][$i];
                $_FILES['file']['error']          = $_FILES['foto_lainnya']['error'][$i];
                $_FILES['file']['size']           = $_FILES['foto_lainnya']['size'][$i];

                // Load and initialize upload library
                $this->load->library('upload', $config_lainnya);
                $this->upload->initialize($config_lainnya);

                // Upload file to server
                if (!$this->upload->do_upload('file')) {
                  //file gagal diupload -> kembali ke form tambah
                  $error = array('error' => $this->upload->display_errors());
                  $this->session->set_flashdata('message', $error['error']);

                  $this->update($this->input->post('id_produk'));
                } else {
                  $fileNameLainnya          = $this->upload->data('file_name');

                  $data_foto_lainnya = array(
                    'produk_id'             => $this->input->post('id_produk'),
                    'foto_lainnya'          => $fileNameLainnya,
                  );

                  // Insert files data into the database
                  $this->Produk_model->insert_foto_lainnya($data_foto_lainnya);
                }
              }
            }
          }

          // set pesan data berhasil disimpan
          $this->session->set_flashdata('message', 'Data berhasil disimpan');
          redirect('produk');
        }
      }
      // apabila upload foto tambahan saja       
      elseif (!empty($_FILES["foto_lainnya"]["name"])) {
        // menghitung jumlah file yang akan diupload
        $filesCount   = count($_FILES['foto_lainnya']['name']);
        // atur maksimum upload file size
        $maxFileSize  = 2000000; // dalam satuan byte

        // cek apakah foto_lainnya melebihi batas yang telah ditentukan
        if ($_FILES['foto_lainnya']['size'][0] > $maxFileSize) {
          $this->session->set_flashdata('message', 'File yang Anda upload melebihi batas maksimum (2Mb)');
          $this->update($this->input->post('id_produk'));
        } else {

          // looping sebanyak jumlah foto yang diupload
          for ($i = 0; $i < $filesCount; $i++) {
            $namaFileLainnya = strtolower(url_title($this->input->post('judul_produk'))) . '_dll_-_' . date('YmdHis');

            // File upload configuration
            $config_lainnya['upload_path']    = './assets/images/produk_lainnya/';
            $config_lainnya['allowed_types']  = 'jpg|jpeg|png';
            $config_lainnya['file_name']      = $namaFileLainnya;

            $_FILES['file']['name']           = $_FILES['foto_lainnya']['name'][$i];
            $_FILES['file']['type']           = $_FILES['foto_lainnya']['type'][$i];
            $_FILES['file']['tmp_name']       = $_FILES['foto_lainnya']['tmp_name'][$i];
            $_FILES['file']['error']          = $_FILES['foto_lainnya']['error'][$i];
            $_FILES['file']['size']           = $_FILES['foto_lainnya']['size'][$i];

            // Load and initialize upload library
            $this->load->library('upload', $config_lainnya);
            $this->upload->initialize($config_lainnya);

            // Upload file to server
            if (!$this->upload->do_upload('file')) {
              //file gagal diupload -> kembali ke form tambah
              $error = array('error' => $this->upload->display_errors());
              $this->session->set_flashdata('message', $error['error']);

              $this->update($this->input->post('id_produk'));
            } else {
              $fileNameLainnya          = $this->upload->data('file_name');

              $data_foto_lainnya = array(
                'produk_id'             => $this->input->post('id_produk'),
                'foto_lainnya'          => $fileNameLainnya,
              );

              // Insert files data into the database
              $this->Produk_model->insert_foto_lainnya($data_foto_lainnya);
            }
          }

          $this->session->set_flashdata('message', 'Data berhasil disimpan');
          redirect('produk');
        }
      } else // Jika file upload kosong
      {
        $data = array(
          'judul_produk'          => $this->input->post('judul_produk'),
          'berat_produk'          => $this->input->post('berat_produk'),
        );

        // eksekusi query INSERT
        $this->Produk_model->update($this->input->post('id_produk'), $data);
        // set pesan data berhasil disimpan
        $this->session->set_flashdata('message', 'Data berhasil disimpan');
        redirect('produk');
      }
    }
  }

  public function delete($id)
  {
    $file_foto          = $this->Produk_model->get_by_id($id);
    $file_foto_lainnya  = $this->Produk_model->get_foto_lainnya_by_id_produk($id);

    // simpan lokasi gambar dalam variable
    $foto          = "assets/images/produk/" . $file_foto->file_foto;
    $foto_thumb    = "assets/images/produk/" . $file_foto->file_foto_thumb;

    if (file_exists($foto)) {
      // Hapus foto dan thumbnail
      unlink($foto);
      unlink($foto_thumb);
    }

    // looping data foto berdasarkan id_produk yg akan dihapus
    foreach ($file_foto_lainnya as $data) {
      $data  = "assets/images/produk_lainnya/" . $data->foto_lainnya;
      // Hapus foto dan thumbnail
      unlink($data);
    }

    // Jika data ditemukan, maka hapus foto dan record nya
    if ($file_foto) {
      $this->Produk_model->delete($id);
      $this->Produk_model->delete_foto_lainnya_by_id_produk($id);

      $this->session->set_flashdata('message', 'Data berhasil dihapus');
      redirect('produk');
    }
    // Jika data tidak ada
    else {
      $this->session->set_flashdata('message', 'Data tidak ditemukan');
      redirect('produk');
    }
  }

  function delete_foto_lainnya_by_id_foto($id)
  {
    $file_foto_lainnya = $this->Produk_model->get_foto_lainnya_by_id_foto($id);

    if ($file_foto_lainnya) {
      // menyimpan lokasi gambar dalam variable
      $foto_lainnya         = "assets/images/produk_lainnya/" . $file_foto_lainnya->foto_lainnya;

      // Hapus foto
      unlink($foto_lainnya);

      var_dump($foto_lainnya);

      $this->Produk_model->delete_foto_lainnya_by_id_foto($id);

      $this->session->set_flashdata('message', 'File berhasil dihapus');
      redirect('produk/update/' . $file_foto_lainnya->produk_id);
    } else {
      $this->session->set_flashdata('message', 'Data tidak ditemukan');
      redirect('produk/update/' . $file_foto_lainnya->produk_id);
    }
  }
}
