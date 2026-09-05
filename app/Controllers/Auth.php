<?php

namespace App\Controllers;

use App\Libraries\EmailPostSender;
use App\Models\UserModel;
use App\Models\PasswordResetModel;
use CodeIgniter\Controller;


class Auth extends Controller
{
    private $clientID = null;
    private $clientSecret = null;
    private $redirectUri = null;
    private $model_user = null;
    private $model_password_reset = null;
    private $library_email_post_sender = null;

    public function __construct()
    {
        $this->clientID = getenv('GOOGLE_CLIENT_ID');
        $this->clientSecret = getenv('GOOGLE_CLIENT_SECRET');
        $this->redirectUri = getenv('GOOGLE_REDIRECT_URI');

        $this->model_user = new UserModel();
        $this->model_password_reset = new PasswordResetModel();
        $this->library_email_post_sender = new EmailPostSender();
    }

    public function googleLogin()
    {
        $googleURL = "https://accounts.google.com/o/oauth2/v2/auth?"
            . "client_id=" . $this->clientID
            . "&redirect_uri=" . $this->redirectUri
            . "&response_type=code"
            . "&scope=email%20profile"
            . "&access_type=offline"
            . "&prompt=select_account";

        return redirect()->to($googleURL);
        //return redirect()->to('http://' . $this->clientID);
    }

    public function googleCallback()
    {
        $code = $this->request->getVar('code');

        if (!$code) {
            return "Tidak dapat authorization code dari Google!";
        }

        // Tukar code => access token
        $token_url = "https://oauth2.googleapis.com/token";

        $data = [
            'code' => $code,
            'client_id' => $this->clientID,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUri,
            'grant_type' => 'authorization_code'
        ];

        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $token_url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);
        curl_close($curl);

        $tokenInfo = json_decode($response, true);

        if (!isset($tokenInfo['access_token'])) {
            return "Gagal mendapatkan access token!";
        }

        $accessToken = $tokenInfo['access_token'];

        // Ambil profil user
        $userInfoUrl = "https://www.googleapis.com/oauth2/v3/userinfo";

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $userInfoUrl);
        curl_setopt($curl, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $accessToken
        ]);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $userResponse = curl_exec($curl);
        curl_close($curl);

        $userData = json_decode($userResponse, true);

        /* dia dpt ini 
        Array
(
    [sub] => 112954324327305730342
    [name] => Gumuruh Samudra Sabar
    [given_name] => Gumuruh
    [family_name] => Samudra Sabar
    [picture] => https://lh3.googleusercontent.com/a/ACg8ocLN_vpul3aeKD9eehdeJl1gZaGtVTzAOlSYBxdG-eKWcwiH7Yju=s96-c
    [email] => gumuruh@gmail.com
    [email_verified] => 1
)
        */

        // check dulu di database ada kaga?
        $filter = [
            'email' => $userData['email']
        ];

        $data_user = $this->model_user->get_by($filter);

        if (!empty($data_user)) {

            // jika ada maka lanjut ke dashboard
            // simpen di session dulu
            // mirip works verify_login

            $session = session();
            $session->set('status-logged-in', 'valid');
            $session->set('propic', $data_user->propic);
            $session->set('usertype', $data_user->usertype);
            $session->set('id', $data_user->id);
            $session->set('username', $data_user->username);
            $session->set('nama_lengkap', $data_user->nama_lengkap);

            return redirect()->to('/homepage');
        }

        //return "<pre>" . print_r($userData, true) . "</pre>";
        // pass ke depan dengan attribute untuk register
        return redirect()->to('/?register=new&email=' . urlencode($userData['email']) . '&nama_lengkap=' . urlencode($userData['name']));
    }


    /**
     * Forgot Password - Request OTP
     */
    public function forgotPassword()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $email = trim($this->request->getPost('email'));

        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Email tidak valid.']);
        }

        // Cek apakah user dengan email ini terdaftar
        $userModel = $this->model_user;
        $user = $userModel->get_by(['email' => $email]);

        if (!$user) {
            // Jangan kasih tahu email tidak terdaftar (hindari email enumeration)
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Jika email terdaftar, kode OTP akan dikirim.'
            ]);
        }

        // Cek cooldown
        $resetModel = $this->model_password_reset;
        if ($resetModel->isInCooldown($email)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terlalu banyak percobaan. Silakan tunggu 2 menit sebelum mencoba lagi.'
            ]);
        }

        // Generate OTP
        $token = $resetModel->createResetToken($email);

        // Kirim email
        $emailSender = $this->library_email_post_sender;
        $dataEmail = [
            'customer_name' => $user->nama_lengkap ?? 'Pengguna',
            'customer_email' => $email,
            'otp_code' => $token,
            'link_portal' => base_url(),
            'link_logo' => 'https://portal.fgroupindonesia.com/assets/img/logo.jpg',
            'expires_in' => '10 menit'
        ];

        $htmlClient = view('email/client/password_reset_otp', $dataEmail, ['save' => true]);
        $result = $emailSender->sendPost(
            $htmlClient,
            $email,
            null,
            'Kode OTP Reset Password Anda',
            null
        );

        if ($result === false) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal mengirim email. Silakan coba lagi.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Kode OTP telah dikirim ke email Anda.'
        ]);
    }

    /**
     * Verify OTP Code
     */
    public function verifyOtp()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $email = trim($this->request->getPost('email'));
        $otp = trim($this->request->getPost('otp'));

        if (empty($email) || empty($otp) || strlen($otp) !== 7 || !ctype_digit($otp)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Kode OTP harus 7 digit angka.'
            ]);
        }

        $resetModel = $this->model_password_reset;
        $result = $resetModel->verifyOTP($email, $otp);

        if ($result['valid']) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'OTP valid. Silakan buat password baru.'
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => $result['message']
        ]);
    }

    /**
     * Reset Password (after OTP verified)
     */
    public function resetPassword()
    {
        if (!$this->request->isAJAX()) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Invalid request']);
        }

        $email = trim($this->request->getPost('email'));
        $newPassword = trim($this->request->getPost('new_password'));
        $confirmPassword = trim($this->request->getPost('confirm_password'));

        if (empty($email) || empty($newPassword) || empty($confirmPassword)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Semua field harus diisi.'
            ]);
        }

        if (strlen($newPassword) < 6) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Password minimal 6 karakter.'
            ]);
        }

        if ($newPassword !== $confirmPassword) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Password dan konfirmasi tidak sama.'
            ]);
        }

        // Cek apakah ada token yang sudah terverifikasi (used=1) untuk email ini
        $resetModel = $this->model_password_reset;
        $tokenRecord = $resetModel->where('email', $email)
            ->where('used', 1)
            ->orderBy('created_at', 'DESC')
            ->first();

        if (!$tokenRecord) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Sesi tidak valid. Silakan minta OTP ulang.'
            ]);
        }

        // Update password
        $userModel = $this->model_user;
        $user = $userModel->get_by(['email' => $email]);

        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User tidak ditemukan.'
            ]);
        }

        $updated = $userModel->update_existing(
            ['pass' => $newPassword],
            $user->id
        );

        if (!$updated) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal mereset password. Silakan coba lagi.'
            ]);
        }

        // Hapus semua token untuk email ini (sudah selesai)
        $resetModel->where('email', $email)->delete();

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Password berhasil direset. Silakan login dengan password baru.'
        ]);
    }
}
