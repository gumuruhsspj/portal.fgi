<?php namespace App\Libraries;

use CodeIgniter\HTTP\CURLRequest; // Menggunakan CURLRequest untuk melakukan permintaan HTTP

class EmailPostSender
{
    /**
     * @var string URL endpoint tujuan pengiriman data
     */
    protected $endpoint = 'https://apps.fgroupindonesia.com/test/send_email.php';

    /**
     * Mengirim data email ke endpoint eksternal.
     *
     * @param string $html Konten HTML email
     * @param string $emailTujuan Alamat email tujuan
     * @param string $emailSender Alamat email pengirim
     * @return array|bool Hasil respons dari endpoint atau FALSE jika gagal
     */
    public function sendPost( $html,  $emailTujuan,  $emailSender, $emailSubject, $emailReply)
    {
        // 1. Inisialisasi HTTP Client.
        // Lebih disarankan menggunakan Service daripada langsung instansiasi,
        // karena memudahkan mocking/testing di CI4.
        $client = \Config\Services::curlrequest([
            'timeout' => 5, // Atur timeout
        ]);
        
        if($emailSender == null){
            $emailSender = "no-reply@fgroupindonesia.com";
        }

        if($emailReply == null){
            $emailReply = "support@fgroupindonesia.com";
        }

        // Data yang akan dikirim
        $data = [
            'html'         => $html,
            'destination'  => $emailTujuan,
            'sender'  => $emailSender,
            'subject' => $emailSubject,
            'replyto' => $emailReply

        ];

        try {
            // 2. Lakukan permintaan POST ke endpoint
            // Endpoint lengkap akan menjadi: baseURI + 'send-email'
            $response = $client->post($this->endpoint, [
                // Mengirim data sebagai form_params (x-www-form-urlencoded)
                // Jika endpoint mengharapkan JSON, ganti dengan 'json' => $data
                'form_params' => $data, 
            ]);

            // 3. Periksa status code
            if ($response->getStatusCode() === 200) {
                // Berhasil. Mengembalikan body respons sebagai array (jika responsnya JSON)
                return $response->getBody();
                // Atau: return true; jika Anda hanya perlu tahu apakah sukses.
            } else {
                // Gagal, status code bukan 200
                log_message('error', 'API EmailSender gagal. Status: ' . $response->getStatusCode() . ' Body: ' . $response->getBody());
                return false;
            }

        } catch (\CodeIgniter\HTTP\ClientException $e) {
            // Tangani error koneksi atau timeout
            log_message('error', 'API EmailSender error: ' . $e->getMessage());
            return false;
        }
    }
}