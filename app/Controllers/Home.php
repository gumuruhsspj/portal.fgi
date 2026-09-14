<?php

namespace App\Controllers;

class Home extends BaseController
{

    public function __construct()
    {

        // $this->session = session();


    }

    public function index(): string
    {
        $angka = '?' . rand(1, 11);
        $data = array('random' => $angka);

        // get error variable from url
        $error = $this->request->getGet('error');
        $status_register = $this->request->getGet('register');

        if ($error == 'invalid') {
            $data['message'] = 'Login username & password salah!';
            $data['error'] = $error;
        }

        if ($error == 'no-cridentials') {
            $data['message'] = 'Harap login dulu agar bisa masuk!';
            $data['error'] = $error;
        }

        if ($status_register == 'new') {
            $nama_lengkap = $this->request->getGet('nama_lengkap');
            $email = $this->request->getGet('email');
            $data['email'] = $email;
            $data['nama_lengkap'] = $nama_lengkap;
            $data['show_registration'] = true;
        }

        return view('landing-page', $data);
    }

    public function display_program_afiliasi()
    {
        $this->is_logged_in();
        $data = $this->get_user_data();

        // Ambil semua program aktif beserta kategori

        $programs = $this->model_program_afiliasi->get_all_with_categori();

        // Ambil keanggotaan user saat ini

        $myMemberships = $this->model_member_afiliasi->get_member_with_program($data['id_user']);

        // Map program_id => status dan kode
        $myStatus = [];
        foreach ($myMemberships as $m) {
            $myStatus[$m['id_program_afiliasi']] = [
                'status' => 'joined',
                'kode_referal' => $m['kode_referal'],
                'member_id' => $m['id']
            ];
        }

        foreach ($programs as &$p) {
            $p['user_status'] = isset($myStatus[$p['id']]) ? $myStatus[$p['id']] : ['status' => 'not_joined'];
            // Parse kategori_ids menjadi array
            if ($p['kategori_ids']) {
                $p['kategori_list'] = explode(',', $p['kategori_ids']);
                $p['kategori_nama_list'] = explode(',', $p['kategori_namas']);
                $p['komisi_list'] = explode(',', $p['komisi_persens']);
            } else {
                $p['kategori_list'] = [];
                $p['kategori_nama_list'] = [];
                $p['komisi_list'] = [];
            }
        }

        $data['programs'] = $programs;
        $data['menu_program_afiliasi_active'] = 'active';

        return view('info_program_afiliasi', $data);
    }

    public function management_quiz_materi()
    {
        $this->is_logged_in();

        $data = $this->get_user_data();
        $id = $this->request->getGet('materi_id');

        $data_materi = $this->model_materi->get_by(['id' => $id]);

        // data returned is in object instead of array
        $data_all_quiz = $this->model_materi->get_all_quiz_by_materi_id($id);

        $data['judul_materi'] = $data_materi->judul;
        $data['id_materi'] = $data_materi->id;
        $data['management_data'] = $data_all_quiz;

        $data['jumlah_data'] = !empty($data_all_quiz) ? sizeof($data_all_quiz) : 0;

        $data['link_management_open'] = 'menu-open';
        $data['link_management_materi_active'] = 'active';
        $data['random'] = '?' . rand(0, 11);

        return view('management_quiz', $data);
    }

    public function management_pembayaran()
    {
        $this->is_logged_in();

        $data = $this->get_user_data();


        // hitung saja ada brp balance yg user miliki
        // karena sistemnya uang user ada di admin (pemilik system)


        $data_saldo = $this->model_history_saldo->get_all();

        $data['management_data'] = $data_saldo;

        $data['jumlah_data'] = !empty($data_saldo) ? sizeof($data_saldo) : 0;

        $data['link_management_open'] = 'menu-open';
        $data['link_management_pembayaran_active'] = 'active';
        $data['random'] = '?' . rand(0, 11);

        return view('management_pembayaran', $data);
    }

    public function management_pembahasan_materi()
    {
        $this->is_logged_in();
        $data = $this->get_user_data();

        $id  = $this->request->getGet('materi_id');
        $cid = $this->request->getGet('custom_id');

        $data_materi        = $this->model_materi->get_by(['id' => $id]);
        $data_materi_custom = $this->model_materi->get_custom_by(['id' => $cid]);

        $data_all_bab = $this->model_materi->get_all_bab_by_materi_id($id, $cid);

        $data_all_pembahasan = [];

        if ($data_all_bab != false && count($data_all_bab) > 0) {
            // Ambil pembahasan orphaned (id_bab = 0) untuk materi ini
            $orphaned = $this->model_materi->get_orphaned_pembahasan($id, $cid);

            $first = true;
            foreach ($data_all_bab as $bab) {
                $list = $this->model_materi->get_all_pembahasan_by_bab_id($bab->id);

                // Jika tidak ada pembahasan & ini bab pertama, pakai orphaned
                if (($list == false || empty($list)) && $first && !empty($orphaned)) {
                    $list = $orphaned;
                }

                $data_all_pembahasan[$bab->id] = $list;
                $first = false;
            }
        }

        $data['judul_materi']        = $data_materi->judul ?? '';
        $data['judul_materi_custom'] = $data_materi_custom->nama_template ?? '';
        $data['id_materi']           = $data_materi->id ?? 0;
        $data['id_materi_custom']    = $cid;
        $data['management_data']     = $data_all_bab;
        $data['management_pembahasan'] = $data_all_pembahasan;
        $data['jumlah_data']         = ($data_all_bab != false) ? sizeof($data_all_bab) : 0;
        $data['link_management_open'] = 'menu-open';
        $data['link_management_materi_active'] = 'active';
        $data['random'] = '?v=' . rand(0, 11);

        return view('management_pembahasan', $data);
    }

    public function management_program_afiliasi()
    {

        $this->is_logged_in();

        $data = $this->get_user_data();

        $data_all_program = $this->model_program_afiliasi->get_all();

        $data['management_data'] = $data_all_program;
        $data['link_management_open'] = 'menu-open';
        $data['link_management_program_afiliasi_active'] = 'active';

        return view('management_program_afiliasi', $data);
    }


    public function management_info_afiliasi()
    {

        $this->is_logged_in();

        $data = $this->get_user_data();

        $data_all_info = $this->model_info_afiliasi->get_all();

        $data['management_data'] = $data_all_info;
        $data['link_management_open'] = 'menu-open';
        $data['link_management_info_afiliasi_active'] = 'active';

        return view('management_info_afiliasi', $data);
    }

    public function management_user()
    {

        $this->is_logged_in();

        $data = $this->get_user_data();
        $data_all_user = $this->model_user->get_all();
        $data['management_data'] = $data_all_user;
        $data['link_management_open'] = 'menu-open';
        $data['link_management_user_active'] = 'active';



        return view('management_user', $data);
    }


    public function management_materi()
    {

        $this->is_logged_in();

        $data = $this->get_user_data();

        $username = $this->session->get('username');
        $as = $this->session->get('usertype');

        if (!$this->is_admin()) {
            $data_materi = $this->model_materi->get_all($username);
        } else {
            $data_materi = $this->model_materi->get_all();
        }

        $data_user = $this->model_user->get_all();

        $data['management_data'] = $data_materi;
        $data['link_management_open'] = 'menu-open';
        $data['link_management_materi_active'] = 'active';

        $data['usertype'] = $as;
        $data['data_user'] = $data_user;
        $data['random'] = '?v=' . rand(1, 1000);

        //echo var_dump($username);
        return view('management_materi', $data);
    }

    public function management_quiz_submission()
    {
        $this->is_logged_in();
        $data = $this->get_user_data();

        $id_materi = $this->request->getGet('materi_id');

        $data['submissions']     = $this->model_materi->get_all_quiz_attempts_with_relations($id_materi);
        $data['materi_filter']   = $this->model_materi->get_materi_with_quiz_attempts();
        $data['selected_materi'] = $id_materi;

        $data['link_management_open'] = 'menu-open';
        $data['link_management_quiz_submission_active'] = 'active';
        $data['random'] = '?v=' . time();

        return view('management_quiz_submission', $data);
    }

    public function management_quiz_submission_detail()
    {
        $this->is_logged_in();
        $data = $this->get_user_data();

        $id_materi = $this->request->getGet('id');
        $id_user   = $this->request->getGet('id_user');

        $attempt = $this->model_materi->get_quiz_attempt_by_materi_and_user($id_materi, $id_user);
        if (!$attempt) {
            return redirect()->to('/manage/quiz-submission')->with('error', 'Data submission tidak ditemukan.');
        }

        $data['attempt']       = $attempt;
        $data['data_answers']  = $this->model_materi->get_quiz_answers($attempt->id);
        $data['data_materi']   = $this->model_materi->get_by(['id' => $id_materi]);
        $data['data_user']     = $this->model_user->find($id_user);

        $data['link_management_open'] = 'menu-open';
        $data['link_management_quiz_submission_active'] = 'active';
        $data['random'] = '?v=' . time();

        return view('management_quiz_submission_detail', $data);
    }

    public function management_materi_custom()
    {

        $this->is_logged_in();

        $data = $this->get_user_data();

        $username = $this->session->get('username');
        $as = $this->session->get('usertype');

        $id_materi = $this->request->getGet('materi_id');
        $filter = ['id' => $id_materi];
        $data_materi_utama = $this->model_materi->get_by($filter);

        if (!$this->is_admin()) {
            $data_materi = $this->model_materi->get_all_custom($username, $id_materi);
        } else {
            $data_materi = $this->model_materi->get_all_custom();
        }

        $data_user = $this->model_user->get_all();

        $data['management_data'] = $data_materi;
        $data['title_materi_utama'] = $data_materi_utama->judul ?? '';
        $data['link_management_open'] = 'menu-open';
        $data['link_management_materi_active'] = 'active';

        $data['usertype'] = $as;
        $data['id_materi'] = $id_materi;
        $data['data_user'] = $data_user;
        $data['random'] = '?v=' . rand(1, 1000);

        //echo var_dump($username);
        return view('management_materi_custom', $data);
    }

    public function management_group_diskusi()
    {

        $this->is_logged_in();

        $data = $this->get_user_data();
        $username = $this->session->get('username');
        $as = $this->session->get('usertype');

        if (!$this->is_admin()) {
            $data_group_diskusi = $this->model_group_diskusi->get_all($username);
        } else {
            $data_group_diskusi = $this->model_group_diskusi->get_all();
        }

        $data_user = $this->model_user->get_all();

        $data['management_data'] = $data_group_diskusi;
        $data['link_management_open'] = 'menu-open';
        $data['link_management_group_active'] = 'active';

        $data['usertype'] = $as;
        $data['data_user'] = $data_user;

        return view('management_group_diskusi', $data);
        //echo var_dump($as);

    }

    public function management_perangkat_tautan()
    {

        $this->is_logged_in();

        $data = $this->get_user_data();
        $username = $this->session->get('username');
        $id_user = $this->session->get('id_user');
        $as = $this->session->get('usertype');

        if (!$this->is_admin()) {
            $data_perangkat_tautan = $this->model_perangkat_tautan->get_all_by_userid($id_user);
        } else {
            $data_perangkat_tautan = $this->model_perangkat_tautan->get_all();
        }

        $data_user = $this->model_user->get_all();

        $data['management_data'] = $data_perangkat_tautan;
        $data['link_management_open'] = 'menu-open';
        $data['link_management_perangkat_active'] = 'active';

        $data['usertype'] = $as;
        $data['username'] = $username;

        return view('management_perangkat_tautan', $data);
    }

    public function display_start_quiz()
    {
        $this->is_logged_in();
        $id_materi = $this->request->getGet('id');
        $data      = $this->get_user_data();
        $id_user   = $data['id_user'];

        $data_student_materi = $this->model_materi->get_subscribed_materi($id_materi, $id_user);
        if (!$data_student_materi) {
            return redirect()->to('/all-materi')->with('error', 'Anda belum mendaftar untuk materi ini!');
        }

        $data_quiz = $this->model_materi->get_all_quiz_by_materi_id($id_materi);
        if (!$data_quiz) {
            return redirect()->to('/materi/start?id=' . $id_materi);
        }

        // sudah pernah submit → langsung ke result
        $existing = $this->model_materi->get_quiz_attempt_by_user($id_user, $id_materi);
        if ($existing) {
            return redirect()->to('/materi/quiz/result?attempt=' . $existing->id);
        }

        $data['title']         = 'Quiz : ' . $data_student_materi->judul;
        $data['id_materi']     = $id_materi;
        $data['judul_materi']  = $data_student_materi->judul;
        $data['data_quiz']     = $data_quiz;
        $data['jumlah_quiz']   = count($data_quiz);
        $data['materi_icon']   = $data_student_materi->icon ?? '';
        $data['random']        = '?v=' . rand(0, 245);

        return view('start_quiz', $data);
    }

    public function display_quiz_result()
    {
        $this->is_logged_in();
        $data      = $this->get_user_data();
        $id_user   = $data['id_user'];
        $attempt_id = $this->request->getGet('attempt');

        $attempt = $this->model_materi->get_quiz_attempt_by_id($attempt_id);
        if (!$attempt || (int)$attempt->id_user !== (int)$id_user) {
            return redirect()->to('/all-materi')->with('error', 'Hasil quiz tidak ditemukan.');
        }

        $data_materi  = $this->model_materi->get_by(['id' => $attempt->id_materi]);
        $data_answers = $this->model_materi->get_quiz_answers($attempt->id);

        $data['title']            = 'Hasil Quiz : ' . ($data_materi->judul ?? '');
        $data['attempt']          = $attempt;
        $data['data_materi']      = $data_materi;
        $data['data_answers']     = $data_answers;
        $data['rilis_sertifikat'] = $data_materi->rilis_sertifikat ?? 'no';
        $data['random']           = '?v=' . rand(0, 245);

        return view('quiz_result', $data);
    }

    public function display_all_perangkat_tautan()
    {

        $this->is_logged_in();

        $data = $this->get_user_data();

        $id = $data['id_user'];

        $dperangkat = $this->model_perangkat_tautan->get_all_by_userid($id);

        $data['data_perangkat_tautan'] = $dperangkat;
        $data['menu_perangkat_tautan_active']  = "active";

        return view('all_perangkat_tautan', $data);
    }

    public function display_all_materi()
    {

        $this->is_logged_in();

        $data = $this->get_user_data();

        $cat = $this->request->getGet('kategori');

        if (isset($cat)) {
            $cat = str_replace('-', ' ', $cat);
            // gedein huruf
            $cat = ucwords($cat);

            $filter = array('kategori' => $cat);

            $data_materi = $this->model_materi->get_all_by($filter);
        } else {
            $data_materi = $this->model_materi->get_all();
        }

        $data['data_materi'] = $data_materi;

        $data['title'] = "Seluruh Materi";
        $data['category'] = $cat;
        $data['menu_materi_open'] = "menu-open";
        $data['menu_seluruh_materi_active'] = "active";
        $data['menu_materi_terpilih_active'] = "";

        return view('all_materi', $data);
    }

    public function display_start_materi()
    {
        $this->is_logged_in();
        $id_materi = $this->request->getGet('id');

        $data    = $this->get_user_data();
        $id_user = $data['id_user'];

        $data_student_materi = $this->model_materi->get_subscribed_materi($id_materi, $id_user);
        $url = '';

        if ($data_student_materi != false) {
            $data['title']        = $data_student_materi->judul;
            $data['materi_icon']  = $data_student_materi->icon ?? '';
            $paket = $data_student_materi->paket;

            if ($paket == 'paket_kasus_custom') {
                $url = $data_student_materi->custom_url_alive;
            } else {
                $url = $data_student_materi->url_alive;
            }

            $filter = [
                'id_user'   => $id_user,
                'id_materi' => $id_materi,   // ← penting biar ga nyampur
            ];

            $is_custom = ($paket == 'paket_kasus_custom');

            if (!$is_custom) {
                $data_detail_materi = $this->model_materi->get_all_detail_by($filter);
                $data_chapter       = $this->model_materi->get_bab_list_with_pembahasan($id_materi, null);
            } else {
                $data_detail_materi = $this->model_materi->get_all_custom_detail_by($filter);
                $custom_id          = $data_student_materi->id_custom_materi ?? null;
                $data_chapter       = $this->model_materi->get_bab_list_with_pembahasan($id_materi, $custom_id);
            }

            if ($data_detail_materi != false) {
                $fileName    = $data_student_materi->attachment;
                $filePath    = 'assets/attachment/uploads/materi/' . $fileName;
                $sizeDisplay = "0 KB";

                if (file_exists($filePath)) {
                    $bytes = filesize($filePath);
                    if ($bytes >= 1048576) {
                        $sizeDisplay = number_format($bytes / 1048576, 2) . ' MB';
                    } elseif ($bytes >= 1024) {
                        $sizeDisplay = number_format($bytes / 1024, 1) . ' KB';
                    } else {
                        $sizeDisplay = $bytes . ' Bytes';
                    }
                }

                if ($data_student_materi->status == 'pending') {
                    $data['error'] = "Materi ini belum bisa anda akses! Harap lunasi dulu pembayarannya.";
                } else if ($data_student_materi->status == 'delete request' || $data_student_materi->status == 'error') {
                    $data['error'] = "Terjadi Kesalahan!";
                }

                $data['id_materi']          = $data_student_materi->id;
                $data['data_file_size']     = $sizeDisplay;
                $data['url_alive']          = $url;
                $data['data_detail_materi'] = $data_detail_materi;
                $data['data_chapter']       = $data_chapter;   // ← kirim ke view
            } else {
                $data['error'] = "Materi ini belum lengkap! Hubungi admin untuk lebih lanjut.";
            }
        } else {
            $data['title'] = "-";
            $data['error'] = "Anda belum mendaftar untuk materi ini!";
        }

        $data['random'] = "?v=" . time();

        return view('start_materi', $data);
    }



    public function management_saldo_history()
    {

        $this->is_logged_in();

        $data = $this->get_user_data();

        $data_history_saldo = $this->model_history_saldo->get_history_with_user();
        $balance = 0;

        if ($data['usertype'] != 'admin') {
            $filter_1 = array(
                'id_user' => $data['id_user']
            );

            $filter_2 = array(
                'id_user' => $data['id_user'],
                'status' => 'approved'
            );

            $data_history_saldo = $this->model_history_saldo->get_all_by($filter_1);
            $balance = $this->model_history_saldo->get_saldo_by($filter_2);
        }

        $data['management_data'] = $data_history_saldo;
        $data['nomer'] = 1;
        $data['balance'] = $balance;
        $data['menu_riwayat_saldo_active'] = 'active';

        //echo var_dump($data_history_saldo);
        return view('management_saldo_history', $data);
    }

    public function display_rekening_bank()
    {
        $this->is_logged_in();
        $data = $this->get_user_data();

        if ($data['usertype'] != 'promotor') {
            return redirect()->to('/homepage')->with('error', 'Akses ditolak.');
        }

        // Ambil status rekening
        $rekening = $this->model_member_afiliasi->get_rekening_status($data['id_user']);
        $data['rekening'] = $rekening;
        $data['menu_rekening_active'] = 'active';
        $data['menu_dashboard_active'] = '';

        return view('rekening_bank', $data);
    }

    public function display_single_materi()
    {
        $this->is_logged_in();
        $data = $this->get_user_data();

        $materi = $this->request->getGet('title');
        $filter_data = ['url' => $materi];
        $data_materi = $this->model_materi->get_by($filter_data);
        $data_comments = null;

        if ($data_materi != false) {
            $idna = $data_materi->id;
            $data_comments = $this->model_materi->get_all_comments_rating($idna);
            $data['data_comments'] = $data_comments;
        }

        $filter_data_materi = [
            'id_materi' => $data_materi != false ? $data_materi->id : 0
        ];
        $materi_terdaftar = $this->model_materi->get_student_materi_by($filter_data_materi);

        if ($data_materi != false) {
            $data['title'] = "Materi : " . $data_materi->judul;
        } else {
            $data['title'] = "Materi : Not Found!";
        }

        $data['participate'] = $materi_terdaftar != false ? true : false;
        $data['data_materi'] = $data_materi;

        // ============ LOGIC PAKET DEFAULT ============
        if ($data_materi != false) {
            $paket_tersedia = [];
            if ($data_materi->paket_bimbingan == 'yes')       $paket_tersedia[] = 'paket_bimbingan';
            if ($data_materi->paket_kasus_custom == 'yes')    $paket_tersedia[] = 'paket_kasus_custom';
            if ($data_materi->paket_belajar_sendiri == 'yes') $paket_tersedia[] = 'paket_belajar_sendiri';

            // Priority: bimbingan > kasus_custom > belajar_sendiri
            $default_paket = null;
            if (in_array('paket_bimbingan', $paket_tersedia)) {
                $default_paket = 'paket_bimbingan';
            } elseif (in_array('paket_kasus_custom', $paket_tersedia)) {
                $default_paket = 'paket_kasus_custom';
            } elseif (in_array('paket_belajar_sendiri', $paket_tersedia)) {
                $default_paket = 'paket_belajar_sendiri';
            }

            $biaya_map = [
                'paket_bimbingan'       => (float) $data_materi->biaya_pokok,
                'paket_kasus_custom'    => (float) $data_materi->biaya_kasus_custom,
                'paket_belajar_sendiri' => (float) $data_materi->biaya_belajar_sendiri,
            ];

            $data['paket_tersedia'] = $paket_tersedia;
            $data['paket_ada']      = !empty($paket_tersedia); // <-- TAMBAHAN
            $data['default_paket']  = $default_paket;
            $data['default_biaya']  = $default_paket ? $biaya_map[$default_paket] : 0;
        }

        // =============================================

        return view('single_materi', $data);
    }

    public function display_all_user()
    {

        $this->is_logged_in();

        $data = $this->get_user_data();

        return view('all_user', $data);
    }

    public function display_selected_materi()
    {
        $this->is_logged_in();

        $data = $this->get_user_data();
        $id   = $data['id_user'];

        $data_materi_user = $this->model_materi->get_all_by_student($id);

        if ($data_materi_user != false) {
            $data['data_materi_user'] = $data_materi_user;
        }

        // === MAP quiz attempts: [id_materi => attempt obj] ===
        $data['quiz_map'] = $this->model_materi->get_quiz_attempts_map_by_user($id);

        $data['title'] = "Materi Terpilih";
        $data['menu_materi_open'] = "menu-open";
        $data['menu_seluruh_materi_active'] = "";
        $data['menu_materi_terpilih_active'] = "active";

        return view('all_materi', $data);
    }

    public function display_all_notification()
    {

        $this->is_logged_in();

        $data = $this->get_user_data();
        $data['title'] = "All Notification";

        return view('all_notification', $data);
    }

    public function display_all_message()
    {

        $this->is_logged_in();

        $data = $this->get_user_data();
        $data['title'] = "All Message";

        return view('all_message', $data);
    }


    private function calculate_cash_paid($all_data)
    {

        $nilai = 0;

        foreach ($all_data as $data) {

            $nilai += $data->user_cash_paid;
        }

        return $nilai;
    }

    private function calculate_progress_materi($all_data)
    {

        $progress_summary = [];
        $last_id_index = -1;
        $current_index = 0;
        $a_percentage = -1;

        // get the specific materi only
        foreach ($all_data as $data_progress) {
            $id_materi = $data_progress->id_materi;

            // Initialize if not set
            if (!isset($progress_summary[$id_materi])) {
                $progress_summary[$id_materi] = [
                    'total_progress' => 0,
                    'total_subs' => 0
                ];
            }

            // Sum the progress
            $progress_summary[$id_materi]['total_progress'] += $data->progress;
            $progress_summary[$id_materi]['total_subs']++;


            if ($current_index < sizeof($all_data)) {
                $last_id_index = $id_materi;
            }

            $current_index++;
        }

        if (sizeof($progress_summary) > 0) {

            $percentage_summary = [];
            foreach ($progress_summary as $id_materi => $summary) {

                $max_progress = $summary['total_subs'] * 100;
                $percentage = ($summary['total_progress'] / $max_progress) * 100;

                $percentage_summary[$id_materi] = [
                    'percentage' => $percentage,
                    'total_progress' => $summary['total_progress'],
                    'total_subs' => $summary['total_subs']
                ];
            }

            // now we grab the last item value stored here
            $a_percentage = $percentage_summary[$last_id_index]['percentage'];
        }

        return $a_percentage;
    }


    public function display_affiliate_media_promosi()
    {
        $this->is_logged_in();
        $data = $this->get_user_data();

        // Pastikan hanya promotor yang bisa akses
        if ($data['usertype'] != 'promotor') {
            return redirect()->to('/homepage')->with('error', 'Akses ditolak.');
        }

        // Ambil semua media promosi (hanya yang aktif dan memiliki kategori)
        // Kita ambil dari model media_promosi
        $media = $this->model_media_promosi->getAllWithCategory();

        // Filter hanya yang memiliki kategori (atau tampilkan semua)
        $data['media_list'] = $media;
        $data['downloaded'] = null;
        $data['menu_media_promosi_active'] = 'active';
        $data['menu_dashboard_active'] = '';

        return view('media_promosi_promotor', $data);
    }

    public function display_affiliate_text_generator()
    {
        $this->is_logged_in();
        $data = $this->get_user_data();

        // Pastikan hanya promotor yang bisa akses (opsional)
        if ($data['usertype'] != 'promotor') {
            return redirect()->to('/homepage')->with('error', 'Akses ditolak.');
        }

        // Ambil daftar program afiliasi yang diikuti user (untuk dropdown link)
        $memberships = $this->model_member_afiliasi->get_member_with_program($data['id_user']);
        $data['memberships'] = $memberships;
        $data['menu_text_generator_active'] = 'active';
        $data['menu_dashboard_active'] = '';

        return view('text_generator', $data);
    }

    public function display_home()
    {
        $this->is_logged_in();
        $data = $this->get_user_data();
        $data['menu_dashboard_active'] = 'active';
        $data['random'] = "?v=" . time();

        if ($data['usertype'] == 'peserta') {

            $id_user = $data['id_user'];

            // ====== PROGRESS MATERI ======
            $progress = $this->model_materi->get_student_materi_progress($id_user);
            $data['total_progress_materi'] = $progress['percentage'];
            $data['total_materi_enrolled'] = $progress['total_enrolled'];
            $data['total_materi_completed'] = $progress['total_completed'];

            // ====== TOTAL MATERI TERSEDIA ======
            $all_materi = $this->model_materi->get_all();
            $data['total_materi'] = is_array($all_materi) ? count($all_materi) : 0;

            // ====== QUIZ ATTEMPTS (card baru) ======
            $quiz_attempts = $this->model_materi->get_quiz_attempts_by_user($id_user);
            $data['quiz_attempts'] = $quiz_attempts;

            // hitung sertifikat yang SUDAH bisa didownload
            $total_cert = 0;
            foreach ($quiz_attempts as $q) {
                if ($q->status === 'graded' && ($q->rilis_sertifikat ?? 'no') === 'yes') {
                    $total_cert++;
                }
            }
            $data['total_sertifikat'] = $total_cert;

            // total user & pendapatan afiliasi (biar gak null)
            $data['total_users'] = 0;
            $data['total_pendapatan_afiliasi'] = 0;

            return view('homepage_student', $data);
        } else if ($data['usertype'] == 'promotor') {
            $id_user = $data['id_user'];

            // Ambil semua keanggotaan program afiliasi aktif
            $memberships = $this->model_member_afiliasi->get_member_with_program($id_user);
            $data['memberships'] = $memberships;
            $data['total_programs'] = count($memberships);

            // Total user yang direferensikan & daftar detail
            $total_referred = 0;
            $referred_users = [];
            foreach ($memberships as $m) {
                $referred = $this->model_referal->get_referred_users($m['id']);
                $total_referred += count($referred);
                foreach ($referred as $r) {
                    $referred_users[] = (object) [
                        'username'   => $r->username,
                        'email'      => $r->email,
                        'date_created' => $r->date_created,
                        'program'    => $m['program_nama']
                    ];
                }
            }
            $data['total_referred'] = $total_referred;
            $data['referred_users'] = $referred_users;

            // Total pendapatan (bonus approved)
            $total_earnings = $this->model_history_saldo
                ->where(['id_user' => $id_user, 'jenis' => 'bonus', 'status' => 'approved'])
                ->selectSum('nominal')
                ->get()
                ->getRow()
                ->nominal ?? 0;
            $data['total_earnings'] = $total_earnings;

            // Link share untuk setiap program
            $share_links = [];
            foreach ($memberships as $m) {
                $share_links[] = (object) [
                    'program' => $m['program_nama'],
                    'kode'    => $m['kode_referal'],
                    'url'     => base_url('register?ref=' . $m['kode_referal'])
                ];
            }
            $data['share_links'] = $share_links;

            return view('homepage_promotor', $data);
        }

        // default admin
        return view('homepage_admin', $data);
    }

    public function management_media_promosi()
    {
        $this->is_logged_in();
        $data = $this->get_user_data();

        // Ambil parameter dari DataTables (draw, start, length, search, order)
        $start = (int) ($this->request->getGet('start') ?: 0);
        $length = (int) ($this->request->getGet('length') ?: 10);
        $search = $this->request->getGet('search')['value'] ?? '';

        // Panggil model dengan paginasi
        $media = $this->model_media_promosi->getAllWithCategory($length, $start, $search);
        $total = $this->model_media_promosi->countAllWithCategory($search);

        $data['kategori_list'] = $this->model_media_category->findAll();
        $data['media_promosi'] = $media;
        $data['total_records'] = $total;
        $data['link_management_open'] = 'menu-open';
        $data['link_management_media_promosi_active'] = 'active';
        $data['title'] = 'Management Media Promosi';

        // Jika request AJAX dari DataTables, kembalikan JSON
        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'draw' => (int) $this->request->getGet('draw'),
                'recordsTotal' => $total,
                'recordsFiltered' => $total,
                'data' => $media
            ]);
        }

        return view('management_media_promosi', $data);
    }

    public function test()
    {

        return view('test');
    }
}
