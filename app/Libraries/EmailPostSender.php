<?php

namespace App\Libraries;

use Config\Services;

class EmailPostSender
{
    public function sendPost($html, $emailTujuan, $emailSubject, $emailSender = null, $emailReply = null)
    {
        $emailSender = $emailSender ?? 'no-reply@fgroupindonesia.com';
        $emailReply  = $emailReply  ?? 'support@fgroupindonesia.com';

        $config = [
            'protocol' => 'mail',
            'mailType' => 'html',
            'charset'  => 'utf-8',
            'newline'  => "\r\n",
            'wordwrap' => true,
        ];

        $email = Services::email();
        $email->initialize($config);
        $email->setMailType('html');

        $email->setFrom($emailSender, 'FGroupIndonesia');
        $email->setTo($emailTujuan);
        $email->setReplyTo($emailReply);
        $email->setSubject($emailSubject);
        $email->setMessage($html);

        if ($email->send()) {
            log_message('info', "✅ Email berhasil dikirim ke {$emailTujuan}");
            return true;
        } else {
            $error = $email->printDebugger(['headers']);
            log_message('error', "❌ Gagal kirim email ke {$emailTujuan} — " . $error);
            return false;
        }
    }
}
