<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Berita extends CI_Controller
{
  function __construct()
  {
    parent::__construct();

    $this->load->model('Berita_model');
  }

  public function index()
  {
    $data['get_all_berita'] = $this->Berita_model->get_all();

    $this->load->view('berita/index', $data);
  }

  public function create()
  {
    $data['judul_berita'] = array(
      'name'  => 'judul_berita',
      'id'    => 'judul_berita',
      'class' => 'form-control',
      'value' => $this->form_validation->set_value('judul_berita'),
    );
    $data['deskripsi_berita'] = array(
      'name'  => 'deskripsi_berita',
      'id'    => 'deskripsi_berita',
      'class' => 'form-control',
      'value' => $this->form_validation->set_value('deskripsi_berita'),
    );

    $this->load->view('berita/create_berita', $data);
  }

  public function create_process()
  {
    // siapkan form mana saja yg wajib divalidasi sehingga apabila error maka value akan tetap tersimpan sementara
    $this->form_validation->set_rules('judul_berita', 'Judul Berita', 'trim|required');
    $this->form_validation->set_rules('deskripsi_berita', 'Deskripsi Berita', 'trim');

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
        // ganti nama file bawaan menjadi berdasarkan judul_berita dan waktu upload supaya menghindari foto tidak berubah
        // saat proses update data karena cache browser yang masih tersimpan
        $namaFile = strtolower(url_title($this->input->post('judul_berita'))) . date('YmdHis');

        // atur lokasi penyimpanan file foto
        $config['upload_path']      = './assets/images/berita/';
        // atur file apa saja yang boleh diupload berdasarkan mime types filenya
        $config['allowed_types']    = 'jpg|jpeg|png';
        // atur maksimum file yang boleh diupload, apabila ingin lebih dari 2mb maka atur jg di php.ini xampp/lampp Anda
        $config['max_size']         = '2048'; // 2 MB (dalam ukuran kilobytes)
        // atur nama file yang diupload
        $config['file_name']        = $namaFile; //nama yang terupload nantinya

        // panggil library upload CI3 dengan memasukkan config yang sudah diatur sebelumnya
        $this->load->library('upload', $config);

        // jika file yg di upload bermasalah
        if (!$this->upload->do_upload('file_foto')) {
          // siapkan variabel untuk ditampilkan pada view
          $error = array('error' => $this->upload->display_errors());
          $this->session->set_flashdata('message', $error['error']);

          $this->create();
        } else {
          // ambil nama file yang sudah diatur untuk disimpan ke dalam tabel di db
          // file_name dan file_ext dibawah ini diambil dari helper method data dari CI yg bisa diliat lengkapnya pada user_guide
          // user_guide/libraries/file_uploading.html?highlight=upload#CI_Upload::data
          // siapkan nama, ekstensi/tipe file dan size/ukuran file
          $fileName          = $this->upload->data('file_name');
          $fileExtension     = $this->upload->data('file_ext');
          $fileSize          = $this->upload->data('file_size');

          // panggil library image manipulation CI supaya dapat membuat thumbnail
          $config['image_library']    = "gd2";
          // ambil sumber foto yang akan diresize
          $config['source_image']     = "./assets/images/berita/" . $fileName . "";
          // buat file_foto_thumb ke direktori yang sama
          $config['new_image']        = "./assets/images/berita/";
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
            'judul_berita'        => $this->input->post('judul_berita'),
            'deskripsi_berita'    => $this->input->post('deskripsi_berita'),
            'file_foto'           => $fileName,
            'file_foto_thumb'     => $namaFile . '_thumb' . $fileExtension,
            'file_foto_size'      => $fileSize,
          );

          // eksekusi query INSERT
          $this->Berita_model->insert($data);
          // set pesan data berhasil disimpan
          $this->session->set_flashdata('message', 'Data berhasil disimpan');
          redirect('berita');
        }
      } else // Jika file upload kosong
      {
        $data = array(
          'judul_berita'          => $this->input->post('judul_berita'),
          'deskripsi_berita'        => $this->input->post('deskripsi_berita'),
        );

        // eksekusi query INSERT
        $this->Berita_model->insert($data);
        // set pesan data berhasil disimpan
        $this->session->set_flashdata('message', 'Data berhasil disimpan');
        redirect('berita');
      }
    }
  }

  public function update($id)
  {
    $data['berita'] = $this->Berita_model->get_by_id($id);

    if ($data['berita']) {
      $data['id_berita'] = array(
        'name'  => 'id_berita',
        'type'  => 'hidden',
      );
      $data['judul_berita'] = array(
        'name'  => 'judul_berita',
        'id'    => 'judul_berita',
        'class' => 'form-control',
      );
      $data['deskripsi_berita'] = array(
        'name'  => 'deskripsi_berita',
        'id'    => 'deskripsi_berita',
        'class' => 'form-control',
      );

      $this->load->view('berita/update_berita', $data);
    } else {
      $this->session->set_flashdata('message', 'Data tidak ditemukan');
      redirect('berita');
    }
  }

  public function update_process()
  {
    // siapkan form mana saja yg wajib divalidasi
    $this->form_validation->set_rules('judul_berita', 'Judul Berita', 'trim|required');

    // set pesan form validasi error
    $this->form_validation->set_message('required', '{field} wajib diisi');

    if ($this->form_validation->run() == FALSE) {
      $this->update($this->input->post('id_berita'));
    } else {
      /* Jika file upload diisi */
      if (file_exists($_FILES["file_foto"]["tmp_name"])) {
        $namaFile = strtolower(url_title($this->input->post('judul_berita'))) . date('YmdHis');

        //load uploading file library
        $config['upload_path']      = './assets/images/berita/';
        $config['allowed_types']    = 'jpg|jpeg|png';
        $config['max_size']         = '2048'; // 2 MB
        $config['file_name']        = $namaFile; //nama yang terupload nantinya

        $this->load->library('upload', $config);

        // jika file yg di upload bermasalah
        if (!$this->upload->do_upload('file_foto')) {
          $error = array('error' => $this->upload->display_errors());
          $this->session->set_flashdata('message', $error['error']);

          $this->update($this->input->post('id_berita'));
        } else {
          // siapkan data untuk ambil kolom file_foto untuk dihapus dari direktori file
          $data               = $this->Berita_model->get_by_id($this->input->post('id_berita'));
          // siapkan url foto beserta namanya
          $file_foto          = "assets/images/berita/" . $data->file_foto;
          $file_foto_thumb    = "assets/images/berita/" . $data->file_foto_thumb;

          // cek apabila file memang ada dengan file_exists maka hapus dengan fungsi unlink
          if (file_exists($file_foto)) {
            // Hapus foto dan thumbnail
            unlink($file_foto);
            unlink($file_foto_thumb);
          }
          
          // ambil nama file yang sudah diatur untuk disimpan ke dalam tabel di db
          // file_name dan file_ext dibawah ini diambil dari helper method data dari CI yg bisa diliat lengkapnya pada user_guide
          // user_guide/libraries/file_uploading.html?highlight=upload#CI_Upload::data
          // siapkan nama, ekstensi/tipe file dan size/ukuran file
          $fileName          = $this->upload->data('file_name');
          $fileExtension     = $this->upload->data('file_ext');
          $fileSize          = $this->upload->data('file_size');

          // panggil library image manipulation CI supaya dapat membuat thumbnail
          $config['image_library']    = "gd2";
          // ambil sumber foto yang akan diresize
          $config['source_image']     = "./assets/images/berita/" . $fileName . "";
          // buat file_foto_thumb ke direktori yang sama
          $config['new_image']        = "./assets/images/berita/";
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

          // siapkan data dalam format array
          $data = array(
            'judul_berita'        => $this->input->post('judul_berita'),
            'deskripsi_berita'    => $this->input->post('deskripsi_berita'),
            'file_foto'           => $fileName,
            'file_foto_thumb'     => $namaFile . '_thumb' . $fileExtension,
            'file_foto_size'      => $fileSize,
          );

          $this->Berita_model->update($this->input->post('id_berita'), $data);
          $this->session->set_flashdata('message', 'Data berhasil disimpan');
          redirect(site_url('berita'));
        }
      }
      // Jika file upload kosong
      else {
        $data = array(
          'judul_berita'          => $this->input->post('judul_berita'),
          'deskripsi_berita'      => $this->input->post('deskripsi_berita'),
        );

        $this->Berita_model->update($this->input->post('id_berita'), $data);
        $this->session->set_flashdata('message', 'Data berhasil disimpan');
        redirect(site_url('berita'));
      }
    }
  }

  public function delete($id)
  {
    $delete = $this->Berita_model->get_by_id($id);

    // simpan lokasi gambar dalam variable
    $file_foto        = "assets/images/berita/" . $delete->file_foto;
    $file_foto_thumb  = "assets/images/berita/" . $delete->file_foto_thumb;

    if (file_exists($file_foto)) {
      // Hapus foto dan thumbnail
      unlink($file_foto);
      unlink($file_foto_thumb);
    }

    // Jika data ditemukan, maka hapus foto dan record nya
    if ($delete) {
      $this->Berita_model->delete($id);

      $this->session->set_flashdata('message', 'Data berhasil dihapus');
      redirect('berita');
    }
    // Jika data tidak ada
    else {
      $this->session->set_flashdata('message', 'Data tidak ditemukan');
      redirect('berita');
    }
  }
}
