<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Buku extends CI_Controller
{
  function __construct()
  {
    parent::__construct();

    $this->load->model('Buku_model');
  }

  public function index()
  {
    $data['get_all_buku'] = $this->Buku_model->get_all();

    $this->load->view('buku/index', $data);
  }

  public function create()
  {
    $data['judul_buku'] = array(
      'name'  => 'judul_buku',
      'id'    => 'judul_buku',
      'class' => 'form-control',
      'value' => $this->form_validation->set_value('judul_buku'),
    );
    $data['penulis_buku'] = array(
      'name'  => 'penulis_buku',
      'id'    => 'penulis_buku',
      'class' => 'form-control',
      'value' => $this->form_validation->set_value('penulis_buku'),
    );

    $this->load->view('buku/create_buku', $data);
  }

  public function create_process()
  {
    // siapkan form mana saja yg wajib divalidasi sehingga apabila error maka value akan tetap tersimpan sementara
    $this->form_validation->set_rules('judul_buku', 'Judul Buku', 'trim|required');
    $this->form_validation->set_rules('penulis_buku', 'Penulis Buku', 'trim');

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
        // ganti nama file bawaan menjadi berdasarkan judul_buku dan waktu upload supaya menghindari foto tidak berubah
        // saat proses update data karena cache browser yang masih tersimpan
        $namaFile = strtolower(url_title($this->input->post('judul_buku'))) . date('YmdHis');

        // atur lokasi penyimpanan file foto
        $config['upload_path']      = './assets/images/buku/';
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

          // echo "<b>Nama File: </b>";
          // echo "<br>";
          // var_dump($fileName);
          // echo "<br><br>";
          // echo "<b>Nama Extension: </b>";
          // echo "<br>";
          // var_dump($fileExtension);
          // echo "<br><br>";
          // echo "<b>Size File: </b>";
          // echo "<br>";
          // var_dump($fileSize);

          $data = array(
            'judul_buku'          => $this->input->post('judul_buku'),
            'penulis_buku'        => $this->input->post('penulis_buku'),
            'file_foto'           => $fileName,
            'file_foto_ext'       => $fileExtension,
            'file_foto_size'      => $fileSize,
          );

          // eksekusi query INSERT
          $this->Buku_model->insert($data);
          // set pesan data berhasil disimpan
          $this->session->set_flashdata('message', 'Data berhasil disimpan');
          redirect('buku');
        }
      } else // Jika file upload kosong
      {
        $data = array(
          'judul_buku'          => $this->input->post('judul_buku'),
          'penulis_buku'        => $this->input->post('penulis_buku'),
        );

        // eksekusi query INSERT
        $this->Buku_model->insert($data);
        // set pesan data berhasil disimpan
        $this->session->set_flashdata('message', 'Data berhasil disimpan');
        redirect('buku');
      }
    }
  }

  public function update($id)
  {
    $data['buku'] = $this->Buku_model->get_by_id($id);

    if ($data['buku']) {
      $data['id_buku'] = array(
        'name'  => 'id_buku',
        'type'  => 'hidden',
      );
      $data['judul_buku'] = array(
        'name'  => 'judul_buku',
        'id'    => 'judul_buku',
        'class' => 'form-control',
      );
      $data['penulis_buku'] = array(
        'name'  => 'penulis_buku',
        'id'    => 'penulis_buku',
        'class' => 'form-control',
      );

      $this->load->view('buku/update_buku', $data);
    } else {
      $this->session->set_flashdata('message', 'Data tidak ditemukan');
      redirect('buku');
    }
  }

  public function update_process()
  {
    // siapkan form mana saja yg wajib divalidasi
    $this->form_validation->set_rules('judul_buku', 'Judul Buku', 'trim|required');

    // set pesan form validasi error
    $this->form_validation->set_message('required', '{field} wajib diisi');

    if ($this->form_validation->run() == FALSE) {
      $this->update($this->input->post('id_buku'));
    } else {
      /* Jika file upload diisi */
      if (file_exists($_FILES["file_foto"]["tmp_name"])) {
        $namaFile = strtolower(url_title($this->input->post('judul_buku'))) . date('YmdHis');

        //load uploading file library
        $config['upload_path']      = './assets/images/buku/';
        $config['allowed_types']    = 'jpg|jpeg|png';
        $config['max_size']         = '2048'; // 2 MB
        $config['file_name']        = $namaFile; //nama yang terupload nantinya

        $this->load->library('upload', $config);

        // jika file yg di upload bermasalah
        if (!$this->upload->do_upload('file_foto')) {
          $error = array('error' => $this->upload->display_errors());
          $this->session->set_flashdata('message', $error['error']);

          $this->update($this->input->post('id_buku'));
        } else {
          // siapkan nama, ekstensi/tipe file dan size/ukuran file
          $fileName           = $this->upload->data('file_name');
          $fileExtension      = $this->upload->data('file_ext');
          $fileSize           = $this->upload->data('file_size');

          // siapkan data untuk ambil kolom file_foto untuk dihapus dari direktori file
          $data               = $this->Buku_model->get_by_id($this->input->post('id_buku'));
          // siapkan url foto beserta namanya
          $file_foto          = "assets/images/buku/" . $data->file_foto;

          // cek apabila file memang ada dengan file_exists maka hapus dengan fungsi unlink
          if (file_exists($file_foto)) {
            // Hapus foto dan thumbnail
            unlink($file_foto);
          }

          // siapkan data dalam format array
          $data = array(
            'judul_buku'          => $this->input->post('judul_buku'),
            'penulis_buku'        => $this->input->post('penulis_buku'),
            'file_foto'           => $fileName,
            'file_foto_ext'       => $fileExtension,
            'file_foto_size'      => $fileSize,
          );

          $this->Buku_model->update($this->input->post('id_buku'), $data);
          $this->session->set_flashdata('message', 'Data berhasil disimpan');
          redirect(site_url('buku'));
        }
      }
      // Jika file upload kosong
      else {
        $data = array(
          'judul_buku'          => $this->input->post('judul_buku'),
          'penulis_buku'        => $this->input->post('penulis_buku'),
        );

        $this->Buku_model->update($this->input->post('id_buku'), $data);
        $this->session->set_flashdata('message', 'Data berhasil disimpan');
        redirect(site_url('buku'));
      }
    }
  }

  public function delete($id)
  {
    $delete = $this->Buku_model->get_by_id($id);

    // simpan lokasi gambar dalam variable
    $file_foto        = "assets/images/buku/" . $delete->file_foto;

    if (file_exists($file_foto)) {
      // Hapus foto dan thumbnail
      unlink($file_foto);
    }

    // Jika data ditemukan, maka hapus foto dan record nya
    if ($delete) {
      $this->Buku_model->delete($id);

      $this->session->set_flashdata('message', 'Data berhasil dihapus');
      redirect('buku');
    }
    // Jika data tidak ada
    else {
      $this->session->set_flashdata('message', 'Data tidak ditemukan');
      redirect('buku');
    }
  }
}
