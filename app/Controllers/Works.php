<?php

namespace App\Controllers;
use App\Models\MateriModel;
use App\Models\UserModel;
use App\Models\PerangkatTautanModel;
use App\Models\GroupDiskusiModel;
use App\Models\ProgramAfiliasiModel;
use App\Models\InfoAfiliasiModel;
use App\Models\CustomerServicesModel;
use App\Models\DailyNotesModel;
use App\Models\SupportTicketsModel;
use App\Models\SystemNotificationModel;
use App\Models\SubscriptionModel;
use App\Models\HistorySaldoModel;

use App\Libraries\EmailPostSender;


class Works extends BaseController
{
    // private $session;
     private $model_materi;
     private $model_user;
     private $model_group_diskusi;
     private $model_perangkat_tautan;
     private $model_program_afiliasi;
     private $model_info_afiliasi;
     private $model_customer_services;
     private $model_daily_notes;
     private $model_support_tickets;
     private $model_system_notif;
     private $model_subscription;
     private $model_history_saldo;

     private $link_logo = 'https://portal.fgroupindonesia.com/assets/img/logo.jpg';
    
    public function __construct(){

         $this->model_history_saldo = new HistorySaldoModel();
         $this->model_materi = new MateriModel();
         $this->model_user = new UserModel();
         $this->model_group_diskusi = new GroupDiskusiModel();
         $this->model_perangkat_tautan = new PerangkatTautanModel();
         $this->model_program_afiliasi = new ProgramAfiliasiModel();
         $this->model_info_afiliasi = new InfoAfiliasiModel();
         $this->model_customer_services = new CustomerServicesModel();
         $this->model_daily_notes = new DailyNotesModel();
         $this->model_support_tickets = new SupportTicketsModel();
         $this->model_system_notif = new SystemNotificationModel();
         $this->model_subscription = new SubscriptionModel();

    }

    public function register_new_user(){

        // default respond
        $respond = array(
            'status' => 'failed',
            'message' => 'registrasi gagal! Silakan coba lagi.'
        );

        // ambil data dari view\modal_registrasi.php

        // jawab disini
        $nama_lengkap   = $this->request->getPost('nama_lengkap');
        $email          = $this->request->getPost('email_user');
        $wa             = $this->request->getPost('no_wa');
        $jenis             = $this->request->getPost('jenis');

        // we have 4 types:
        // GRATIS, PELAJAR, PENGAJAR REGULER, and PENGAJAR MASTER
        $jenis = strtolower($jenis);

        // cari id nya dari table subscription
       $data_subscription = $this->model_subscription->where('jenis', $jenis)->first();
      
        if($jenis=='gratis' || $jenis=='pelajar'){
            $usertype = 'peserta';
        }

        if($jenis=='pengajar reguler' || $jenis=='pengajar master'){
            $usertype = 'instruktur';
        }

        $username = substr($email, 0, strpos($email, '@'));

        // buat array data agar masuk ke db
        $data = array(
            'nama_lengkap' => $nama_lengkap,
            'username'  => $username,
            'email' => $email,
            'pass' => generateRandomMixedString(8), // generate random password
            'whatsapp' => $wa,
            'usertype' => $usertype
        );

        // sisipkan id subscription
        if($usertype == 'peserta' || 
            $usertype == 'instruktur'){
            $data['subscription_id'] = $data_subscription->id;
        }

        // pastikan tdk ada email yg sama sudah mendaftar
        $data_user = $this->model_user->where('email', $email)->first();

        if(!$data_user){

            $hasil = $this->model_user->insert($data);

            if(!empty($hasil)){
                $respond = array(
                'status' => 'success',
                'message' => 'registrasi berhasil! Silakan cek email Anda untuk informasi selanjutnya.'
                );

                // kirim email
                $data_email = array(
                    'link_portal' => base_url(),
                    'customer_name' => $nama_lengkap,   
                    'customer_email' => $email,
                    'temporary_password' => $data['pass'],
                    'link_logo' => $this->link_logo
                );
                $emailSender = new EmailPostSender();

                $html_client = view('email/client/registration_success', $data_email, ['save' => true]);

                // kirim dulu ke client
                $result = $emailSender->sendPost($html_client, $email, null, 'Akun Anda Berhasil Dibuat!', null);
        
            }

        }else{
            $respond = array(
                'status' => 'failed',
                'message' => 'registrasi gagal! Email sudah pernah dipakai.'
            );
        }


        echo json_encode($respond);

    }

    public function send_support_email(){

        $cs     = $this->request->getPost('customer_name');
        $e      = $this->request->getPost('email');
        $t      = $this->request->getPost('title');
        $d      = $this->request->getPost('descriptions');

        $ref = generateRandomMixedString(7);

        $data_db = array(
            'email'         => $e,
            'customer_name' => $cs,
            'title'         => $t,
            'descriptions'  => $d,
            'status'        => 'pending',
            'ref_number'    => $ref
        );

        $data_db2 = array(
            'messages'  => 'permintaan baru dari pelanggan bernama ' . $cs,
            'username'  => 'admin', // ditujukan ke admin
            'status'    => 'new'
        );

        // simpan data permintaan ke db
        $this->model_support_tickets->insert($data_db);

        // simpan lagi ke notif system nnti agar dicheck oleh admin
        $this->model_system_notif->insert($data_db2);

        $se = 'no-reply@fgroupindonesia.com';
        $er = 'support@fgroupindonesia.com';
      

       

        $emailSender = new EmailPostSender();

        $data_email1 = array(
            'customer_name' => $cs,
            'email'         => $e,
            'title'         => $t,
            'descriptions'  => $d,
            'link_logo'     => $this->link_logo,
            'link_portal'   => base_url(),
            'reference_id' => $ref
        );

        $data_email2 = array(
            'customer_name' => $cs,
            'email'         => $e,
            'title'         => $t,
            'descriptions'  => $d,
            'link_logo'     => $this->link_logo
        );

        $html_client = view('email/client/thank_you_support_sent', $data_email1, ['save' => true]);

        $html_admin = view('email/admin/support_notifications', $data_email2, ['save' => true]);

        // kirim dulu ke client
        $result = $emailSender->sendPost($html_client, $e, $se, 'Permintaan Anda Kami Terima!', $er);

        // kirim lagi ke admin
        $result = $emailSender->sendPost($html_admin, $er, $se, 'Permintaan Pelanggan Baru', $e);

        $hasil = array(
            'status' => 'failed',
            'message' => 'support gagal!',
          );

        if ($result !== false) {
          $hasil = array(
            'status' => 'success',
            'message' => 'berhasil!',
          );
        } 

          echo json_encode($hasil);

    }

    public function verify_login()
    {
        $u = $this->request->getPost('username');
        $p = $this->request->getPost('pass');
        $ut = $this->request->getPost('usertype');

        if(strpos($u, '@') !== -1){
            // if username contain email
            // then take the username from email
            $u = substr($u, 0, strpos($u, '@'));
        }

        $data = array(
          'username' => $u,
          'pass' => $p
        );

        $valid_pass = $this->model_user->valid($data);

        // store important data
        if($valid_pass){
            $data_user = $this->model_user->get_by_username($u);
            $this->session->set('propic', $data_user->propic); 
            $this->session->set('usertype', $data_user->usertype);
            $this->session->set('id', $data_user->id);
        }

        if(!$valid_pass){
           
          $this->session->set('status-logged-in', 'invalid');
          return redirect()->to('/?error=invalid');

        }
         
         $this->session->set('status-logged-in', 'valid');
         $this->session->set('username', $u);
         
         $this->session->set('nama_lengkap', $data_user->nama_lengkap);

        return redirect()->to('/homepage');

    }

     public function comments_rating_all(){

           $result = array(
            'status' => 'invalid'
        );

           // id is idmateri
        $id_materi = $this->request->getPost('id');
        
     
       $result = $this->model_materi->get_all_comments_rating($id_materi);

         $end_result = array(
                'status' => 'invalid'
        );

       if(!$result){
            $end_result['status'] = "invalid";
       }else{
            $end_result['status'] = "valid";
            $end_result['data'] = $result;
       }

      echo json_encode($end_result);

    }

     public function daily_notes_all(){

           $result = array(
            'status' => 'invalid'
        );

        $d = $this->request->getPost('date_created');
        
        $data = array(
          'DATE(date_created)' => $d
     );

       $result = $this->model_daily_notes->get_all_by($data);

         $end_result = array(
                'status' => 'invalid'
        );

       if(!$result){
            $end_result['status'] = "invalid";
       }else{
            $end_result['status'] = "valid";
            $end_result['data'] = $result;
       }

      echo json_encode($end_result);

    }

     public function daily_notes_update(){

           $result = array(
            'status' => 'invalid'
        );

        $u = $this->request->getPost('username');
        $n = $this->request->getPost('notes');
        $id    = $this->request->getPost('id');

        $data = array(
          'username' => $u,
          'notes' => $n
     );

       $result = $this->model_daily_notes->update_existing($data, $id);

         $end_result = array(
                'status' => 'invalid'
        );

       if(!$result){
            $end_result['status'] = "invalid";
       }else{
            $end_result['status'] = "valid";
       }

      echo json_encode($end_result);

    }

    public function daily_notes_delete(){

        $id          = $this->request->getPost('id');

        $returned_value = $this->model_daily_notes->delete_existing($id);

         $result = array(
            'status' => 'invalid'
        );

        if($returned_value){
            $result['status'] = 'valid';
        }

        echo json_encode($result);

    }

     public function daily_notes_add(){

        $result = array(
            'status' => 'invalid'
        );

        $u = $this->request->getPost('username');
        $n = $this->request->getPost('notes');
        
     $data = array(
          'username'    => $u,
          'notes'       => $n
     );

     $this->model_daily_notes->insert_new($data);

      $result['status'] = 'valid';

      echo json_encode($result);

    }

    public function materi_delete(){

        $id          = $this->request->getPost('id');

        $returned_value = $this->model_materi->delete_existing($id);

         $result = array(
            'status' => 'invalid'
        );

        if($returned_value){
            $result['status'] = 'valid';
        }

        echo json_encode($result);

    }

     public function materi_edit(){

         $id          = $this->request->getPost('id');

         $filter = array(
            'id' => $id
         );

         $returned_value = $this->model_materi->get_by($filter);

          $result = array(
            'status' => 'invalid'
            );

         if($returned_value){
            $result['status'] = 'valid';
            $result['data'] = $returned_value;
         }

         echo json_encode($result);


    }

    public function comments_rating_add(){
        $result = array(
            'status' => 'invalid'
        );

        $id_materi = $this->request->getPost('materi_id');
        $username = $this->request->getPost('username');
        $comments = $this->request->getPost('comments');
        $rating = $this->request->getPost('rating');

     $data = array(
          'id_materi' => $id_materi,
          'username'  => $username,
          'comments'  => $comments,
          'ratings'    => $rating
     );

     $this->model_materi->insert_new_comments_rating($data);

      $result['status'] = 'valid';

      echo json_encode($result);
    }

    public function materi_paket_update(){

        // terima data dari ajax
        $id_materi = $this->request->getPost('materi_id');
        $paket = $this->request->getPost('paket');
        $biaya_pokok = $this->request->getPost('biaya_pokok');
        $biaya_belajar_sendiri = $this->request->getPost('biaya_belajar_sendiri');
        $biaya_kasus_custom = $this->request->getPost('biaya_kasus_custom');
        $rilis_sertifikat = $this->request->getPost('rilis_sertifikat');

        $data = array(
            'biaya_pokok' => $biaya_pokok,
            'biaya_belajar_sendiri' => $biaya_belajar_sendiri,
            'biaya_kasus_custom'    => $biaya_kasus_custom,
            'rilis_sertifikat' => $rilis_sertifikat
        );

        foreach($paket as $p){
            if($p == 'paket_belajar_sendiri'){
                $data['paket_belajar_sendiri'] = 'yes';
            }
            if($p == 'paket_bimbingan'){
                $data['paket_bimbingan'] = 'yes';
            }
            if($p == 'paket_kasus_custom'){
                $data['paket_kasus_custom'] = 'yes';
            }
        }

        $result = $this->model_materi->update($id_materi, $data);

         $end_result = array(
                'status' => 'invalid',
                'message' => 'error'
        );

        if(!empty($result)){
            $end_result['status'] = "valid";
            $end_result['message'] = "paket berhasil diupdate!";
        }

        echo json_encode($end_result);

    }

    public function management_pembahasan_bab_add(){

        $result = array(
            'status' => 'invalid',
            'message' => 'error'
        );

        $id_materi = $this->request->getPost('id_materi');
        $id_user = $this->request->getPost('id_user');
        $judul = $this->request->getPost('judul');
        $deskripsi = $this->request->getPost('deskripsi');

     $data = array(
          'id_materi'   => $id_materi,
          'id_user'     => $id_user,
          'judul'       => $judul,
          'deskripsi'  => $deskripsi
     );

     $this->model_materi->insert_new_pembahasan_bab($data);

      $result['status'] = 'valid';
      $result['message'] = 'bab berhasil ditambahkan!';

      echo json_encode($result);

    }

    public function management_pembahasan_add(){

        $result = array(
            'status' => 'invalid',
            'message' => 'error'
        );

        $id_bab = $this->request->getPost('id_bab');
        $id_user = $this->request->getPost('id_user');
        $ordering_index = $this->request->getPost('ordering_index');
        $judul = $this->request->getPost('judul');
        $deskripsi = $this->request->getPost('deskripsi');

     $data = array(
          'id_bab'   => $id_bab,
          'id_user'     => $id_user,
          'ordering_index'   => $ordering_index,
          'judul'       => $judul,
          'deskripsib'  => $deskripsi
     );

     $this->model_materi->insert_new_pembahasan($data);

      $result['status'] = 'valid';
      $result['message'] = 'pembahasan berhasil ditambahkan!';

      echo json_encode($result);

    }

    public function saldo_topup()
    {
       $nominal = $this->request->getPost('nominal');
       $username = $this->request->getPost('username');

        $data_user = $this->model_user->get_by_username($username);

        $balance= $data_user->balance;
        $id_user = $data_user->id;

        $uang_after = $balance + $nominal;

         $data = array(
              'nominal' => $nominal,
              'status' => 'pending',
              'saldo_sebelum' => $balance,
              'saldo_setelah' => $uang_after,
              'jenis'   => 'isi saldo',
              'id_user' => $id_user,
              'keterangan' => 'Topup saldo oleh user sendiri'
        );

        // 1. Validasi Input
        $validation = \Config\Services::validation();
        $validation->setRules([
            // ID transaksi/pembelian (asumsi hidden input di form)
            // 'id_transaksi' => 'required|integer', 
            'bukti_pembayaran' => [
                'rules' => 'uploaded[bukti_pembayaran]|max_size[bukti_pembayaran,1024]|is_image[bukti_pembayaran]|mime_in[bukti_pembayaran,image/jpg,image/jpeg,image/png]',
                'errors' => [
                    'uploaded' => 'Anda harus mengunggah bukti pembayaran.',
                    'max_size' => 'Ukuran file terlalu besar (maks 1MB).',
                    'is_image' => 'File harus berupa gambar.',
                    'mime_in' => 'Format file tidak didukung (gunakan JPG, JPEG, atau PNG).',
                ]
            ]
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            // Jika validasi gagal
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $validation->getErrors()
            ]);
        }

        // 2. Proses Upload File
        $buktiPembayaran = $this->request->getFile('bukti_pembayaran');

        if ($buktiPembayaran->isValid() && !$buktiPembayaran->hasMoved()) {
            // Tentukan nama file baru secara unik
            $newName = $buktiPembayaran->getRandomName();
        
            // LOKASI BARU: Tentukan direktori tujuan
            // `ROOTPATH` mengarah ke root folder project (portal-fgroupindonesia\)
            // Path lengkap: portal-fgroupindonesia\public\assets\attachment\uploads\payment
            $uploadPath = ROOTPATH . 'public/assets/attachment/uploads/payment'; 
        
            // Pindahkan file ke folder yang diminta
            $buktiPembayaran->move($uploadPath, $newName);
            
            // Path yang akan disimpan di database (untuk diakses melalui web)
            $filePath = 'assets/attachment/uploads/payment/' . $newName;
        
            // 3. Simpan Data ke Database
            // -- Lakukan proses update status transaksi dan simpan nama file bukti ke database di sini --
            // Contoh: $pembelianModel->update($id_transaksi, ['status' => 'menunggu_verifikasi', 'bukti_bayar_path' => $filePath]);
            $this->model_history_saldo->insert($data);


            // 4. Berikan Respon Sukses
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Bukti pembayaran berhasil diunggah. Menunggu verifikasi.',
                'file_path' => $filePath
            ]);
        
        } else {
            // Jika ada masalah lain dalam upload (jarang terjadi jika validasi sudah ketat)
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal mengunggah file bukti pembayaran.'
            ]);
        }
    }

    public function materi_add(){

        $result = array(
            'status' => 'invalid'
        );

        $j = $this->request->getPost('judul');
        $k = $this->request->getPost('kategori');
        $d = $this->request->getPost('deskripsi');
        $a = $this->request->getPost('attachment');
        $i = $this->request->getPost('icon');
         $u = $this->request->getPost('username');

     $data = array(
          'judul' => $j,
          'kategori' => $k,
          'deskripsi' => $d,
          'icon' => $i,
          'username' => $u,
          'url' => url_title($j, '-', true)
     );

     if(!empty($a)){
        $data['attachment'] = $a;
     }

     $this->model_materi->insert_new($data);

      $result['status'] = 'valid';

      echo json_encode($result);

    }

    public function materi_update(){

           $result = array(
            'status' => 'invalid'
        );

        $j = $this->request->getPost('judul');
        $k = $this->request->getPost('kategori');
        $d = $this->request->getPost('deskripsi');
        $a = $this->request->getPost('attachment');
        $i = $this->request->getPost('icon');
         $u = $this->request->getPost('username');

        $id             = $this->request->getPost('id');

        $data = array(
          'judul' => $j,
          'kategori' => $k,
          'deskripsi' => $d,
          'attachment' => $a,
          'icon' => $i,
          'username' => $u
     );

       $result = $this->model_materi->update_existing($data, $id);

         $end_result = array(
                'status' => 'invalid'
        );

       if(!$result){
            $end_result['status'] = "invalid";
       }else{
            $end_result['status'] = "valid";
       }

      echo json_encode($end_result);

    }

     public function materi_kategori_delete(){

           $result = array(
            'status' => 'invalid'
        );

     $u = $this->request->getPost('username');
     $k = $this->request->getPost('kategori');
     
     $data = array(
          'username' => $u,
          'kategori' => $k
     );

     $this->model_materi->delete_existing_where_kategori($data);

     $result['status'] = 'valid';

      echo json_encode($result);

    }

     public function materi_icon_add(){

         $validationRule = [
            'icon' => [
                'rules' => [
                    'uploaded[icon]',
                    'is_image[icon]',
                    'mime_in[icon,image/jpg,image/jpeg,image/gif,image/png]',
                    'max_size[icon,2048]'
                ],
            ],
        ];

        if (!$this->validate($validationRule)) {
              $result = array(
                'status' => 'invalid'
                );

              echo json_encode($result);

            return;
        }

          $icon         = $this->request->getFile('icon');

        if ($icon->isValid() && !$icon->hasMoved()) {
            // Move the file to the writable/uploads directory
            $newName = $icon->getRandomName();
            //$propic->move(WRITEPATH . '/uploads', $newName);

            // move to another location
            $icon->move(FCPATH . '/assets/img/uploads/materi', $newName);

            // resize the file image dimension right away
             $image = \Config\Services::image();
                          $image->withFile(FCPATH . '/assets/img/uploads/materi/' . $newName)
                                ->resize(128, 128, false)
                                ->save(FCPATH . '/assets/img/uploads/materi/' . $newName);

            $datana = array(
                'icon' => $newName
            );

            $result = array(
                'status' => 'valid',
                'filename' => $newName
            );

              echo json_encode($result);
        }

    }

    public function materi_attachment_add(){

        $validationRule = [
            'attachment' => [
                'rules' => [
                    'uploaded[attachment]',
                    'mime_in[attachment,' .
                        'application/pdf,' .
                        'application/msword,' .
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document,' .
                        'application/vnd.ms-excel,' .
                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,' .
                        'text/plain,' .
                        // 👇 TAMBAHAN UNTUK GAMBAR
                        'image/jpeg,' .
                        'image/png' .
                        ']',
                    'max_size[attachment,2048]', // 2MB
                    'ext_in[attachment,pdf,doc,docx,xls,xlsx,txt,' .
                        // 👇 TAMBAHAN UNTUK GAMBAR
                        'jpg,jpeg,png' .
                        ']'
                ],
            ],
        ];

        if (!$this->validate($validationRule)) {
              $result = array(
                'status' => 'invalid'
                );

              echo json_encode($result);

            return;
        }

          $attachment         = $this->request->getFile('attachment');

        if ($attachment->isValid() && !$attachment->hasMoved()) {
            // Move the file to the writable/uploads directory
            $newName = $attachment->getRandomName();
            //$propic->move(WRITEPATH . '/uploads', $newName);

            // move to another location
            $attachment->move(FCPATH . '/assets/attachment/uploads/materi', $newName);

            $datana = array(
                'attachment' => $newName
            );

            $result = array(
                'status' => 'valid',
                'filename' => $newName
            );

              echo json_encode($result);
        }

    }

    public function logout(){

        $this->session->destroy();

        return redirect()->to('/');

    }

    public function materi_kategori_add(){

        $us     = $this->request->getPost('username');
        $kat    = $this->request->getPost('kategori');

        $data = array(
            'username' => $us,
            'kategori' => $kat
        );

        $returned_result = $this->model_materi->insert_new_kategori($data);

        $result = array(
            'status' => 'invalid'
        );

        if($returned_result){
            $result['status'] = 'valid';
        }

        echo json_encode($result);

    }

    public function materi_kategori_all(){

        $us = $this->request->getPost('username');

        $returned_result = $this->model_materi->get_all_kategori($us);

        $result = array(
            'status' => 'invalid'
        );

        if($returned_result){
            $result['status'] = 'valid';
            $result['data'] = $returned_result;
        }

        echo json_encode($result);

    }



    public function user_add(){

      
        $nama_lengkap   = $this->request->getPost('nama_lengkap');
        $usertype       = $this->request->getPost('usertype');
        $username       = $this->request->getPost('username');
        $email          = $this->request->getPost('email');
        $pass           = $this->request->getPost('pass');
        $wa             = $this->request->getPost('whatsapp');
        $gender             = $this->request->getPost('gender');
        $email_notification             = $this->request->getPost('email_notification');
        
        //echo var_dump($email_notification);


        $data = array(
            'nama_lengkap' => $nama_lengkap,
            'username'  => $username,
            'email' => $email,
            'pass' => $pass,
            'whatsapp' => $wa,
            'gender' => $gender,
            'usertype' => $usertype
        );

        $validationRule = [
            'propic' => [
                'rules' => [
                    'uploaded[propic]',
                    'is_image[propic]',
                    'mime_in[propic,image/jpg,image/jpeg,image/gif,image/png]',
                    'max_size[propic,2048]'
                ],
            ],
        ];

         if ($this->validate($validationRule)) {

             $propic         = $this->request->getFile('propic');

            // a propic image
             if ($propic->isValid() && !$propic->hasMoved()) {
            // Move the file to the writable/uploads directory
            $newName = $propic->getRandomName();
            //$propic->move(WRITEPATH . '/uploads', $newName);

            // move to another location
            $propic->move(FCPATH . '/assets/img/uploads/propic', $newName);

           $data['propic'] = $newName;

            }
            
        }

       $result = $this->model_user->insert_new($data);

         $end_result = array(
                'status' => 'invalid'
        );

       if(!$result){
            $end_result['status'] = "invalid";
       }else{
            $end_result['status'] = "valid";

            if(!empty($email_notification)){
                $data_email = array(
                    'link_portal' => base_url(),
                    'customer_name' => $nama_lengkap,   
                    'customer_email' => $email,
                    'temporary_password' => $pass,
                    'link_logo' => $this->link_logo
                );

                $emailSender = new EmailPostSender();

                $html_client = view('email/client/registration_success', $data_email, ['save' => true]);

                // kirim dulu ke client
                $result = $emailSender->sendPost($html_client, $email, null, 'Akun Anda Berhasil Dibuat!', null);
                
                $end_result['email_status'] = $result;

            }

       }

      echo json_encode($end_result);


    }

    public function user_delete(){

        $id          = $this->request->getPost('id');

        $returned_value = $this->model_user->delete_existing($id);

         $result = array(
            'status' => 'invalid'
        );

        if($returned_value){
            $result['status'] = 'valid';
        }

        echo json_encode($result);

    }

    public function user_edit(){

         $id          = $this->request->getPost('id');

         $filter = array(
            'id' => $id
         );

         $returned_value = $this->model_user->get_by($filter);

          $result = array(
            'status' => 'invalid'
            );

         if($returned_value){
            $result['status'] = 'valid';
            $result['data'] = $returned_value;
         }

         echo json_encode($result);


    }

    public function user_delete_propic(){

        // we reset back the propic to default

          $id          = $this->request->getPost('id');

          $datana = array(
            'propic' => 'empty.png'
          );

        $this->model_user->update_existing($id, $datana);

        $result = array(
            'status' => 'valid'
        );

        echo json_encode($result);

    }

    public function user_update_propic(){

         $validationRule = [
            'propic' => [
                'rules' => [
                    'uploaded[propic]',
                    'is_image[propic]',
                    'mime_in[propic,image/jpg,image/jpeg,image/gif,image/png]',
                    'max_size[propic,2048]'
                ],
            ],
        ];

        if (!$this->validate($validationRule)) {
              $result = array(
                'status' => 'invalid'
                );

              echo json_encode($result);

            return;
        }

          $id             = $this->request->getPost('id');
          $propic         = $this->request->getFile('propic');

        if ($propic->isValid() && !$propic->hasMoved()) {
            // Move the file to the writable/uploads directory
            $newName = $propic->getRandomName();
            //$propic->move(WRITEPATH . '/uploads', $newName);

            // move to another location
            $propic->move(FCPATH . '/assets/img/uploads/propic', $newName);

            $datana = array(
                'propic' => $newName
            );

            $this->model_user->update_existing($id, $datana);
            
            $result = array(
                'status' => 'valid',
                'filename' => $newName
            );

              echo json_encode($result);
        }

    }

    // this is opened by get Request 
    // to reinforce the Session data
    public function reinforce_user_settings(){

        $us = $this->request->getGet('username');

        $data = $this->model_user->get_by_username($us);

        $this->save_again_session($data);

        header('Location: /homepage');
        exit; // Always call exit after header redirection

    }

    private function save_again_session($data_user){

        $this->session->set('propic', $data_user->propic);
        $this->session->set('nama_lengkap', $data_user->nama_lengkap);
        $this->session->set('email', $data_user->email);
        $this->session->set('pass', $data_user->pass);
        $this->session->set('whatsapp', $data_user->whatsapp);
        $this->session->set('username', $data_user->username);

    }

    public function user_update(){

        $nama_lengkap   = $this->request->getPost('nama_lengkap');
        
        $username       = $this->request->getPost('username');
        $email          = $this->request->getPost('email');
        $pass           = $this->request->getPost('pass');
        $wa             = $this->request->getPost('whatsapp');
        $gender             = $this->request->getPost('gender');
        $id             = $this->request->getPost('id');

        $data = array(
            'nama_lengkap' => $nama_lengkap,
            'username'  => $username,
            'email' => $email,
            'pass' => $pass,
            'gender' => $gender,
            'whatsapp' => $wa
        );

       $result = $this->model_user->update_existing($data, $id);

         $end_result = array(
                'status' => 'invalid'
        );

       if(!$result){
            $end_result['status'] = "invalid";
       }else{
            $end_result['status'] = "valid";
       }

      echo json_encode($end_result);

    }


    public function group_diskusi_update(){

        $n   = $this->request->getPost('nama');
        $url = $this->request->getPost('url');
        $j   = $this->request->getPost('jenis');
        $u   = $this->request->getPost('username');
        $id             = $this->request->getPost('id');

        $data = array(
            'username' => $u,
            'url'  => $url,
            'jenis' => $j,
            'nama' => $n
        );


       $result = $this->model_group_diskusi->update_existing($data, $id);

         $end_result = array(
                'status' => 'invalid'
        );

       if(!$result){
            $end_result['status'] = "invalid";
       }else{
            $end_result['status'] = "valid";
       }

      echo json_encode($end_result);

    }

     public function group_diskusi_delete(){

        $id          = $this->request->getPost('id');

        $returned_value = $this->model_group_diskusi->delete_existing($id);

         $result = array(
            'status' => 'invalid'
        );

        if($returned_value){
            $result['status'] = 'valid';
        }

        echo json_encode($result);

    }

     public function group_diskusi_edit(){

         $id          = $this->request->getPost('id');

         $filter = array(
            'id' => $id
         );

         $returned_value = $this->model_group_diskusi->get_by($filter);

          $result = array(
            'status' => 'invalid'
            );

         if($returned_value){
            $result['status'] = 'valid';
            $result['data'] = $returned_value;
         }

         echo json_encode($result);


    }

    public function group_diskusi_add(){

        $n   = $this->request->getPost('nama');
        $url = $this->request->getPost('url');
        $j   = $this->request->getPost('jenis');
        $u   = $this->request->getPost('username');
        
        $data = array(
            'username' => $u,
            'url'  => $url,
            'jenis' => $j,
            'nama' => $n
        );

        //echo json_encode($data);

       $result = $this->model_group_diskusi->insert_new($data);

         $end_result = array(
                'status' => 'invalid'
        );

       if(!$result){
            $end_result['status'] = "invalid";
       }else{
            $end_result['status'] = "valid";
       }

      echo json_encode($end_result);

    }

    public function program_afiliasi_delete(){

        $id          = $this->request->getPost('id');

        $returned_value = $this->model_program_afiliasi->delete_existing($id);

         $result = array(
            'status' => 'invalid'
        );

        if($returned_value){
            $result['status'] = 'valid';
        }

        echo json_encode($result);

    }

     public function info_afiliasi_update(){

        $j   = $this->request->getPost('judul');
        $b       = $this->request->getPost('berita');
        $s          = $this->request->getPost('status');
        $id             = $this->request->getPost('id');

        $data = array(
            'judul' => $j,
            'berita'  => $b,
            'status' => $s
        );

       $result = $this->model_info_afiliasi->update_existing($data, $id);

        $end_result = array(
                'status' => 'invalid'
        );

       if(!$result){
            $end_result['status'] = "invalid";
       }else{
            $end_result['status'] = "valid";
       }

      echo json_encode($end_result);

    }

     public function customer_services_reset(){

        // this is for resetting all account
        // coz we will got the array values here
        $ids = $this->request->getPost('id');
        
        foreach($ids as $id){

        $data = array(
            'whatsapp' => null,
            'nama'  => null
        );

       $result = $this->model_customer_services->update_existing($data, $id);

       }

         $end_result = array(
                'status' => 'invalid'
        );

       if(!$result){
            $end_result['status'] = "invalid";
       }else{
            $end_result['status'] = "valid";
       }

      echo json_encode($end_result);

    }

    public function customer_services_list(){

         $datana = $this->model_customer_services->get_all();

         $data = array(
            'status' => 'invalid',
            'data' => $datana
         );

         
         if($datana){
            $data['status'] = 'valid';
         }

         echo json_encode($data);

    }

    public function customer_services_update(){

        $wa           = $this->request->getPost('whatsapp');
        $nama         = $this->request->getPost('nama');
        $status         = $this->request->getPost('status');
        $ids           = $this->request->getPost('id');

        for($n=0; $n<sizeof($ids); $n++){
            $id = $ids[$n];
         
                 $data = array(
                        'nama' => $nama[$n],
                        'whatsapp'  => $wa[$n],
                        'status'  => $status[$n],
                    );

                     $result = $this->model_customer_services->update_existing($data, $id);
        }
    

     

         $end_result = array(
                'status' => 'invalid'
        );

       if(!$result){
            $end_result['status'] = "invalid";
       }else{
            $end_result['status'] = "valid";
       }

      echo json_encode($end_result);

    }

      public function info_afiliasi_edit(){

         $id          = $this->request->getPost('id');

         $filter = array(
            'id' => $id
         );

         $returned_value = $this->model_info_afiliasi->get_by($filter);

          $result = array(
            'status' => 'invalid'
            );

         if($returned_value){
            $result['status'] = 'valid';
            $result['data'] = $returned_value;
         }

         echo json_encode($result);


    }

      public function info_afiliasi_delete(){

        $id          = $this->request->getPost('id');

        $returned_value = $this->model_info_afiliasi->delete_existing($id);

         $result = array(
            'status' => 'invalid'
        );

        if($returned_value){
            $result['status'] = 'valid';
        }

        echo json_encode($result);

    }

    public function info_afiliasi_add(){

      
        $j   = $this->request->getPost('judul');
        $b   = $this->request->getPost('berita');
        $s   = $this->request->getPost('status');
        
        $data = array(
            'judul'     => $j,
            'berita'    => $b,
            'status'    => $s
        );

       $result = $this->model_info_afiliasi->insert_new($data);

         $end_result = array(
                'status' => 'invalid'
        );

       if(!$result){
            $end_result['status'] = "invalid";
       }else{
            $end_result['status'] = "valid";
       }

      echo json_encode($end_result);

    }

      public function program_afiliasi_add(){

      
        $n   = $this->request->getPost('nama');
        $d       = $this->request->getPost('deskripsi');
        
        $data = array(
            'nama' => $n,
            'deskripsi'  => $d,
            'total_member' => 0
        );

        $validationRule = [
            'icon' => [
                'rules' => [
                    'uploaded[icon]',
                    'is_image[icon]',
                    'mime_in[icon,image/jpg,image/jpeg,image/gif,image/png]',
                    'max_size[icon,2048]'
                ],
            ],
        ];

         if ($this->validate($validationRule)) {

             $iconic         = $this->request->getFile('icon');

            // a propic image
             if ($iconic->isValid() && !$iconic->hasMoved()) {
            // Move the file to the writable/uploads directory
            $newName = $iconic->getRandomName();
            //$iconic->move(WRITEPATH . '/uploads', $newName);

            // move to another location
            $iconic->move(FCPATH . '/assets/img/uploads/afiliasi', $newName);

           $data['icon'] = $newName;

            }
            
        }

       $result = $this->model_program_afiliasi->insert_new($data);

         $end_result = array(
                'status' => 'invalid'
        );

       if(!$result){
            $end_result['status'] = "invalid";
       }else{
            $end_result['status'] = "valid";
       }

      echo json_encode($end_result);

    }


     public function perangkat_tautan_add(){

      
        $n   = $this->request->getPost('nama');
        $d       = $this->request->getPost('deskripsi');
        $u       = $this->request->getPost('url');
        $idm     = $this->request->getPost('id_materi');

        $result = false;
       
        if(isset($idm)){
            foreach($idm as $s_data){
                 $data = array(
                        'nama' => $n,
                        'deskripsi'  => $d,
                        'url' => $u,
                        'id_materi' => $s_data
                    );

                   $result = $this->model_perangkat_tautan->insert_new($data);
            }
        }else{

            // submit data without id materi
                    $data = array(
                        'nama' => $n,
                        'deskripsi'  => $d,
                        'url' => $u,
                        'id_materi' => -1
                    );

                   $result = $this->model_perangkat_tautan->insert_new($data);

        }

         $end_result = array(
                'status' => 'invalid'
        );

       if(!$result){
            $end_result['status'] = "invalid";
       }else{
            $end_result['status'] = "valid";
       }

      echo json_encode($end_result);

    }


    public function perangkat_tautan_edit(){

        $id  = $this->request->getPost('id');

         $filter = array(
            'id' => $id
         );

        $returned_value = $this->model_perangkat_tautan->get_by($filter);

        $result = array(
            'status' => 'invalid'
        );

         if($returned_value){
            $result['status'] = 'valid';
            $result['data'] = $returned_value;
         }

         echo json_encode($result);


    }

     public function perangkat_tautan_delete(){

        $id             = $this->request->getPost('id');

        $returned_value = $this->model_perangkat_tautan->delete_existing($id);

         $result = array(
            'status' => 'invalid'
        );

        if($returned_value){
            $result['status'] = 'valid';
        }

        echo json_encode($result);

    }

     public function perangkat_tautan_update(){

        $n   = $this->request->getPost('nama');
        $d       = $this->request->getPost('deskripsi');
        $u       = $this->request->getPost('url');
        $idm          = $this->request->getPost('id_materi');
        $id             = $this->request->getPost('id');

        $data = array(
            'nama' => $n,
            'deskripsi'  => $d,
            'url' => $u,
            'id_materi' => $idm
        );


       $result = $this->model_perangkat_tautan->update_existing($data, $id);

         $end_result = array(
                'status' => 'invalid'
        );

       if(!$result){
            $end_result['status'] = "invalid";
       }else{
            $end_result['status'] = "valid";
       }

      echo json_encode($end_result);

    }

    public function perangkat_tautan_browse_materi(){

        $u   = $this->request->getPost('username');

        if($u == 'admin'){
            $result = $this->model_materi->get_all();
        }else{
            $result = $this->model_materi->get_all($u);
        }

        $end_result = array(
                'status' => 'invalid'
        );

        if(!$result){
          $end_result['status'] = "invalid";
       }else{
            $end_result['status'] = "valid";
            $end_result['data'] = $result;
       }

      echo json_encode($end_result);

    }

    public function generate_chart_user_gender(){


        // gender either male / female
        //$gender = $this->request->getGet('gender');
        $gender = $this->request->getPost('gender');

        // we need months name
     $months = [
    "Jan" => 0, "Feb" => 0, "Mar" => 0, "Apr" => 0,
    "May" => 0, "Jun" => 0, "Jul" => 0, "Aug" => 0,
    "Sep" => 0, "Oct" => 0, "Nov" => 0, "Des" => 0
    ];

    if(isset($gender)){

    $filter = array(
        'gender' => $gender
    );

        $data_all_user = $this->model_user->get_all_by($filter);

    }else{

        $data_all_user = $this->model_user->get_all();    

    }

    foreach ($data_all_user as $entry) {
        $monthIndex = date("n", strtotime($entry->date_created)) - 1;
        $monthNames = array_keys($months);
        $months[$monthNames[$monthIndex]]++;
    }

    $data_final = [];

    foreach ($months as $month => $count) {
        $object = array();
        $object['bulan'] = $month;
        $object['jumlah'] = $count;
        $data_final[] = (object) $object;
    }

    echo json_encode($data_final);


    }
  

}
