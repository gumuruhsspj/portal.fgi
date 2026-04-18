<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

use App\Models\ChatModel;
use App\Models\CustomerServicesModel;
use App\Models\DailyNotesModel;
use App\Models\GroupDiskusiModel;
use App\Models\HistorySaldoModel;
use App\Models\InfoAfiliasiModel;
use App\Models\MateriModel;
use App\Models\MemberAfiliasiModel;
use App\Models\PerangkatTautanModel;
use App\Models\ProgramAfiliasiModel;
use App\Models\ProgressMateriModel;
use App\Models\SubscriptionModel;
use App\Models\SupportTicketsModel;
use App\Models\SystemNotificationModel;
use App\Models\UserModel;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    /**
     * Instance of the main Request object.
     *
     * @var CLIRequest|IncomingRequest
     */
    protected $request;

    /**
     * An array of helpers to be loaded automatically upon
     * class instantiation. These helpers will be available
     * to all other controllers that extend BaseController.
     *
     * @var list<string>
     */
    protected $helpers = [];

    /**
     * Be sure to declare properties for any property fetch you initialized.
     * The creation of dynamic property is deprecated in PHP 8.2.
     */
     protected $session;

       // private $session;
   protected $model_materi;
    protected $model_history_saldo;
    protected $model_user;
    protected $model_group_diskusi;
    protected $model_perangkat_tautan;
    protected $model_program_afiliasi;
    protected $model_member_afiliasi;
    protected $model_info_afiliasi;
    protected $model_customer_services;
    protected $model_system_notification;
    protected $model_chat;
    protected $model_daily_notes;
    protected $model_progress_materi;
    protected $model_support_tickets;
    protected $model_subscription;

    /**
     * @return void
     */
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        // Do Not Edit This Line
        parent::initController($request, $response, $logger);

        // Preload any models, libraries, etc, here.

        // E.g.: $this->session = \Config\Services::session();
        $this->session = session();

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

     public function is_logged_in(){

       $logged_status = $this->session->get('status-logged-in');
       $url = "location: " . site_url() . "?error=no-cridentials";
        
        if(is_null($logged_status)) {
          //  echo site_url();
          header($url);
          exit;
        }       

       if($logged_status == 'invalid') {
          header($url);
          exit;
        }

    }

    public function is_admin(){

       $logged_usertype = $this->session->get('usertype');
       
       if($logged_usertype == 'admin') {
          return true;
        }

        return false;

    }

     private function combine_both_data($data_source, $data_result){

            $data_nama = get_data_as_key($data_source, 'nama' );
            $data_url = get_data_as_key($data_source, 'url');

            for($i=0; $i<sizeof($data_nama); $i++){
                $data_result [] = get_data_as_achor($data_nama[$i], $data_url[$i]);
            }

            return $data_result;

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

            $filter_progress = array(
                'id_user' => $data_user->id
            );

            $data_progress_materi = $this->model_progress_materi->get_all_by($filter_progress);
            $data_member_afiliasi = $this->model_member_afiliasi->get_all_by($filter_progress);
            $data_daily_notes = $this->model_daily_notes->get_all_by($filter_daily_notes);

            if($data_progress_materi != false){

                $score_progress_materi = $this->calculate_progress_materi($data_progress_materi);

            }

             if($data_member_afiliasi != false){

                $cash_paid = $this->calculate_cash_paid($data_member_afiliasi);

            }

             $filter_saldo = array('id_user' => $data_user->id, 'status' => 'approved');
             $saldo = $this->model_history_saldo->get_saldo_by($filter_saldo);

        }else {
            $saldo = $this->model_user->get_total_balance();
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

        $tmateri = $this->model_materi->countAll();

        

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
            'saldo' => $saldo,
            'total_users' => $tmale+$tfmale,
            'total_progress_materi' => $tprogress_materi,
            'total_pendapatan_afiliasi' => $tpendapatan_afiliasi,
            'data_group_diskusi_tg' => $data_tg,
            'data_group_diskusi_wa' => $data_wa,
            
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
            'balance' => $data_user->balance,
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

}
