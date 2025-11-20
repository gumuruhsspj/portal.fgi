<?php

namespace App\Controllers;
use App\Models\MateriModel;
use App\Models\GroupDiskusiModel;
use App\Models\PerangkatTautanModel;
use App\Models\UserModel;
use App\Models\ProgramAfiliasiModel;
use App\Models\MemberAfiliasiModel;
use App\Models\InfoAfiliasiModel;
use App\Models\CustomerServicesModel;
use App\Models\SystemNotificationModel;
use App\Models\ChatModel;
use App\Models\DailyNotesModel;
use App\Models\ProgressMateriModel;
use App\Models\HistorySaldoModel;

class Home extends BaseController
{
   
   // private $session;
    private $model_materi;
    private $model_history_saldo;
    private $model_user;
    private $model_group_diskusi;
    private $model_perangkat_tautan;
    private $model_program_afiliasi;
    private $model_member_afiliasi;
    private $model_info_afiliasi;
    private $model_customer_services;
    private $model_system_notification;
    private $model_chat;
    private $model_daily_notes;
    private $model_progress_materi;

     public function __construct(){

       // $this->session = session();
        $this->model_history_saldo = new HistorySaldoModel();
        $this->model_materi = new MateriModel();
        $this->model_user = new UserModel();
        $this->model_group_diskusi = new GroupDiskusiModel();
        $this->model_perangkat_tautan = new PerangkatTautanModel();
        $this->model_program_afiliasi = new ProgramAfiliasiModel();
        $this->model_member_afiliasi = new MemberAfiliasiModel();
        $this->model_info_afiliasi = new InfoAfiliasiModel();
        $this->model_customer_services = new CustomerServicesModel();
        $this->model_system_notification = new SystemNotificationModel();
        $this->model_chat = new ChatModel();
        $this->model_daily_notes = new DailyNotesModel();
        $this->model_progress_materi = new ProgressMateriModel();

    }

    public function index(): string
    {
        $angka = '?' . rand(1, 11);
        $data = array('random'=>$angka);

        // get error variable from url
        $error = $this->request->getGet('error');

        if($error == 'invalid'){
            $data['message'] = 'Login username & password salah!';
            $data['error'] = $error;
        } 

        if($error == 'no-cridentials'){
            $data['message'] = 'Harap login dulu agar bisa masuk!';
            $data['error'] = $error;
        }

        return view('landing-page', $data);
    }

    public function display_program_afiliasi(){

        $this->is_logged_in();

         $data = $this->get_user_data();
         
         $dpa = $this->model_program_afiliasi->get_all();

         $data['data_program_afiliasi'] = $dpa;
          $data['menu_program_afiliasi_active']  = "active";

        return view('info_program_afiliasi', $data);

    }

    public function management_pembahasan_materi(){

        $this->is_logged_in();

         $data = $this->get_user_data();

         $id = $this->request->getGet('materi_id');
         
         $data_materi = $this->model_materi->get_by(['id'=>$id]);
         
         // data returned is in object instead of array
         $data_all_bab = $this->model_materi->get_all_bab_by_materi_id($id);

         // loop lagi
         $data_all_pembahasan = array();

         foreach ($data_all_bab as $data_bab){
            $data_all_pembahasan[$data_bab->id] = $this->model_materi->get_all_pembahasan_by_bab_id($data_bab->id);
         }
         
         $data['judul_materi'] = $data_materi->judul;    
         $data['id_materi'] = $data_materi->id;
         $data['management_data'] = $data_all_bab;
         $data['management_pembahasan'] = $data_all_pembahasan;
        $data['jumlah_data'] = sizeof($data_all_bab);
        $data['link_management_open'] = 'menu-open';
        $data['link_management_materi_active'] = 'active';
        $data['random'] = '?' . rand(0,11);

        return view('management_pembahasan', $data);

    }

    public function management_program_afiliasi(){

        $this->is_logged_in();

         $data = $this->get_user_data();

         $data_all_program = $this->model_program_afiliasi->get_all();

         $data['management_data'] = $data_all_program;
        $data['link_management_open'] = 'menu-open';
        $data['link_management_program_afiliasi_active'] = 'active';

        return view('management_program_afiliasi', $data);

    }


     public function management_info_afiliasi(){

        $this->is_logged_in();

         $data = $this->get_user_data();

           $data_all_info = $this->model_info_afiliasi->get_all();

             $data['management_data'] = $data_all_info;
         $data['link_management_open'] = 'menu-open';
         $data['link_management_info_afiliasi_active'] = 'active';

        return view('management_info_afiliasi', $data);

    }

     public function management_user(){

        $this->is_logged_in();

         $data = $this->get_user_data();
         $data_all_user = $this->model_user->get_all();
         $data['management_data'] = $data_all_user;
         $data['link_management_open'] = 'menu-open';
         $data['link_management_user_active'] = 'active';

        return view('management_user', $data);

    }

     public function management_materi(){

        $this->is_logged_in();

         $data = $this->get_user_data();

         $username = $this->session->get('username');
         $as = $this->session->get('usertype');

         if(!$this->is_admin()){
            $data_materi = $this->model_materi->get_all($username);
         }else{
            $data_materi = $this->model_materi->get_all();
         }

        $data_user = $this->model_user->get_all();

        $data['management_data'] = $data_materi;
        $data['link_management_open'] = 'menu-open';
        $data['link_management_materi_active'] = 'active';

        $data['usertype'] = $as;
        $data['data_user'] = $data_user;
        $data['random'] = '?' . rand(1,1000);
        

        //echo var_dump($username);
        return view('management_materi', $data);

    }

      public function management_group_diskusi(){

        $this->is_logged_in();

         $data = $this->get_user_data();
         $username = $this->session->get('username');
         $as = $this->session->get('usertype');

         if(!$this->is_admin()){
            $data_group_diskusi = $this->model_group_diskusi->get_all($username);
         }else{
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

  public function management_perangkat_tautan(){

    $this->is_logged_in();

          $data = $this->get_user_data();
         $username = $this->session->get('username');
         $as = $this->session->get('usertype');

         if(!$this->is_admin()){
            $data_perangkat_tautan = $this->model_perangkat_tautan->get_all_by_username($username);
         }else{
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

    private function combine_both_data($data_source, $data_result){

            $data_nama = get_data_as_key($data_source, 'nama' );
            $data_url = get_data_as_key($data_source, 'url');

            for($i=0; $i<sizeof($data_nama); $i++){
                $data_result [] = get_data_as_achor($data_nama[$i], $data_url[$i]);
            }

            return $data_result;

    }

    public function display_all_perangkat_tautan(){

         $this->is_logged_in();

         $data = $this->get_user_data();

         $us = $data['username'];

         $dperangkat = $this->model_perangkat_tautan->get_all_by_username($us);

         $data['data_perangkat_tautan'] = $dperangkat;
         $data['menu_perangkat_tautan_active']  = "active";

         return view('all_perangkat_tautan', $data);

    }

    public function display_all_materi(){

          $this->is_logged_in();

         $data = $this->get_user_data();
         $data['title'] = "Seluruh Materi";
         $data['menu_materi_open'] = "menu-open";
         $data['menu_seluruh_materi_active'] = "active";
         $data['menu_materi_terpilih_active'] = "";
         

         return view('all_materi', $data);

    }

    public function display_start_materi(){

       $this->is_logged_in();

        $id_materi = $this->request->getGet('id');

        $data = $this->get_user_data();
        $us = $data['username'];

        // pastikan user ini terdaftar dalam id materi tersebut
        $data_student_materi = $this->model_materi->get_by_student($us);

        if($data_student_materi != false){

            $data['title'] = $data_student_materi->judul;

            $filter  = array(
                'id_materi' => $data_student_materi->id_materi
            );

            $data_detail_materi = $this->model_materi->get_all_detail_by_username($us);


            if($data_detail_materi != false){

                if($data_detail_materi->status=='pending' ){
                    $data['error'] = "Materi ini belum bisa anda akses! Harap lunasi dulu pembayarannya.";   
                }else if($data_detail_materi->status=='delete request' || $data_detail_materi->status=='error'){
                    $data['error'] = "Terjadi Kesalahan!";   
                }

                $data['data_detail_materi'] = $data_detail_materi;
            }else {
                $data['error'] = "Materi ini belum lengkap! Hubungi admin untuk lebih lanjut.";    
            }

        }else{
            $data['error'] = "Anda belum mendaftar untuk materi ini!";
        }

        return view('start_materi', $data);

    }

    public function management_saldo_history(){

            $this->is_logged_in();
    
            $data = $this->get_user_data();
        
            $data_history_saldo = $this->model_history_saldo->findAll();

            if($data['usertype'] != 'admin' ){
                $filter_data = array(
                    'id_user' => $data['username']
                );
    
                $data_history_saldo = $this->model_history_saldo->get_all_by($filter_data);
            }

            $data['management_data'] = $data_history_saldo;
           
            $data['menu_riwayat_saldo_active'] = 'active';
    
            return view('management_saldo_history', $data);

    }

    public function display_single_materi(){

             $this->is_logged_in();

        $data = $this->get_user_data();

             // the title is url based format
         $materi = $this->request->getGet('title');

         $filter_data = array(
            'url' => $materi
         );

         $data_materi = $this->model_materi->get_by($filter_data);
         $data_comments = null;

         if($data_materi != false){
            $idna = $data_materi->id;
            $data_comments = $this->model_materi->get_all_comments_rating($idna);
            $data['data_comments'] = $data_comments;
         }

         $filter_data_materi = array(
            'id_materi' => $data_materi != false ? $data_materi->id : 0
         );   
        $materi_terdaftar = $this->model_materi->get_student_materi_by($filter_data_materi);

         if($data_materi!=false){
            $data['title'] = "Materi : " . $data_materi->judul;
         }else{
            $data['title'] = "Materi : Not Found!";
            
         }

         $data['participate'] = $materi_terdaftar != false ? true : false;

         $data['data_materi'] = $data_materi;

        return view('single_materi', $data);

    }

     public function display_all_user(){

         $this->is_logged_in();

         $data = $this->get_user_data();

         return view('all_user', $data);

    }

    public function display_selected_materi(){

         $this->is_logged_in();

         $data = $this->get_user_data();

         $us = $data['username'];

         $data_materi_user = $this->model_materi->get_all_by_student($us);

         if($data_materi_user != false){
            $data['data_materi_user'] = $data_materi_user;
         }
         
         $data['title'] = "Materi Terpilih";
         $data['menu_materi_open'] = "menu-open";
         $data['menu_seluruh_materi_active'] = "";
         $data['menu_materi_terpilih_active'] = "active";

         return view('all_materi', $data);

    }

    public function display_all_notification(){

         $this->is_logged_in();

         $data = $this->get_user_data();
          $data['title'] = "All Notification";
        
         return view('all_notification', $data);

    }

     public function display_all_message(){

         $this->is_logged_in();

         $data = $this->get_user_data();
          $data['title'] = "All Message";
        
         return view('all_message', $data);

    }


    private function calculate_cash_paid($all_data){

        $nilai = 0;

        foreach($all_data as $data){

            $nilai += $data->user_cash_paid;

        }

        return $nilai;

    }

    private function calculate_progress_materi($all_data){

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


            if($current_index < sizeof($all_data)){
                $last_id_index = $id_materi;
            }

            $current_index++;

        }

        if(sizeof($progress_summary)>0){

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

    public function get_user_data(){

        $as      = $this->session->get('usertype');
        $usname  = $this->session->get('username');
        $propic  = $this->session->get('propic');
        $nama_lengkap  = $this->session->get('nama_lengkap');

        $data_user = $this->model_user->get_by_username($usname);
        $data_all_users = $this->model_user->get_all();
        $data_cs = $this->model_customer_services->get_all();
        $data_chat = $this->model_chat->get_all_by_status('new');
        $data_materi = $this->model_materi->get_all();
        $data_progress_materi = null;
        $data_member_afiliasi = null;
        $data_daily_notes = null;

        if($as == 'peserta'){
            // this is as a student
            $tgl = date('Y-m-d');

            $filter_daily_notes = array(
                'username' => $usname,
                'DATE(date_created)' => $tgl
            );

            $data_progress_materi = $this->model_progress_materi->get_all_by_username($usname);
            $data_member_afiliasi = $this->model_member_afiliasi->get_all_by_username($usname);
            $data_daily_notes = $this->model_daily_notes->get_all_by($filter_daily_notes);

            if($data_progress_materi != false){

                $score_progress_materi = $this->calculate_progress_materi($data_progress_materi);

            }

             if($data_member_afiliasi != false){

                $cash_paid = $this->calculate_cash_paid($data_member_afiliasi);

            }

        }

        $filter_tg = array(
            'jenis' => 'telegram'
        );

        $filter_wa = array(
            'jenis' => 'wa'
        );

        $data_group_tg = $this->model_group_diskusi->get_all_by($filter_tg);        
        $data_group_wa = $this->model_group_diskusi->get_all_by($filter_wa);        

        if($usname == 'admin'){
            $data_notification = $this->model_system_notification->get_all();
        }else {
            $data_notification = $this->model_system_notification->get_all_by_username($usname);
        }

        $t = $data_notification!=false ? sizeof($data_notification) : 0;
        $c = $data_chat!=false ? sizeof($data_chat) : 0;

        $tt = $data_group_tg!=false ? sizeof($data_group_tg) : 0;
        $twa = $data_group_wa!=false ? sizeof($data_group_wa) : 0;

        $tmale = $data_all_users!=false ? sizeof(get_data_as_key_value($data_all_users, 'gender', 'male')) : 0;
        $tfmale = $data_all_users!=false ? sizeof(get_data_as_key_value($data_all_users, 'gender', 'female')) : 0;

        $tmateri = $data_materi!=false ? sizeof($data_materi) : 0;

        $tprogress_materi = $data_progress_materi!=false ? $score_progress_materi : 0;
        $tpendapatan_afiliasi = $data_member_afiliasi!=false ? $cash_paid : 0;

        $data_tg = array();
        $data_wa = array();

        if($tt!=0){
            $data_tg = $this->combine_both_data($data_group_tg, $data_tg);
       }

       if($twa!=0){
            $data_wa = $this->combine_both_data($data_group_wa, $data_wa);
        }
       

        $data = array(
            'total_male' => $tmale,
            'total_female' => $tfmale,
            'total_materi' => $tmateri,
            'total_users' => $tmale+$tfmale,
            'total_progress_materi' => $tprogress_materi,
            'total_pendapatan_afiliasi' => $tpendapatan_afiliasi,
            'data_group_diskusi_tg' => $data_tg,
            'data_group_diskusi_wa' => $data_wa,
            'data_materi' => $data_materi,
            'data_daily_notes' => $data_daily_notes,
            'data_chat' => $data_chat,
            'data_notification' => $data_notification,
            'total_data_notification' => $t,
            'total_data_chat' => $c,
            'total_telegram_group' => $tt,
            'total_whatsapp_group' => $twa,
            'nama_lengkap' => $nama_lengkap,
            'usertype'  => $as,
            'username'  => $usname,
            'id_user' => $data_user->id,
            'propic'    => $propic,
            'wa_cs01_name' => $data_cs[0]->nama,
            'wa_cs02_name' => $data_cs[1]->nama,
            'wa_cs01_link' => $this->generate_wa_link($data_cs[0]->whatsapp),
            'wa_cs02_link' => $this->generate_wa_link($data_cs[1]->whatsapp),
            'wa_cs01_display' => 'wa_cs01_' . $data_cs[0]->status,
            'wa_cs02_display' => 'wa_cs02_' . $data_cs[1]->status
        );

        $data['settings_user_data'] = $data_user;

        return $data;
    }

    private function generate_wa_link($numberPhone){

        return "https://wa.me/" . $numberPhone . "?text=hello!";

    }


     public function display_home()
    {

       $this->is_logged_in();
        
        $data = $this->get_user_data();
        $data['menu_dashboard_active'] = 'active';


        if($data['usertype'] == 'peserta'){
            return view('homepage_student', $data);
        }

        return view('homepage_admin', $data);
        
    }

}
