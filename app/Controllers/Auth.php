<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    private $clientID = null;
    private $clientSecret = null;
    private $redirectUri = null;
    private $model_user = null;

    public function __construct()
    {
        $this->clientID = getenv('GOOGLE_CLIENT_ID');
        $this->clientSecret = getenv('GOOGLE_CLIENT_SECRET');
        $this->redirectUri = getenv('GOOGLE_REDIRECT_URI');

        $this->model_user = new UserModel();
    }

    public function googleLogin()
    {
        $googleURL = "https://accounts.google.com/o/oauth2/v2/auth?"
            . "client_id=".$this->clientID
            . "&redirect_uri=".$this->redirectUri
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

        if(!empty($data_user)){
            
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
        return redirect()->to('/?register=new&email=' . urlencode($userData['email']) . '&nama_lengkap=' . urlencode($userData['name']) );

    }
}
