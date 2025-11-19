<?php
header('Content-Type: text/plain; charset=UTF-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);

if (!function_exists('mail')) {
    die('❌ mail() function disabled on this server');
}

$email   = $_POST['destination'] ?? '';
$subject = $_POST['subject'] ?? '';
$emailnorep = $_POST['sender'] ?? 'no-reply@domainmu.com';
$replyTo = trim($_POST['replyto'] ?? '');
$body = $_POST['html'] ?? '<p>Empty message</p>';

$headers  = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=UTF-8\r\n";
$headers .= "From: No Reply <$emailnorep>\r\n";
if (!empty($replyTo)) $headers .= "Reply-To: <$replyTo>\r\n";

if (mail($email, $subject, $body, $headers)) {
    echo "✅ Email berhasil dikirim ke $email";
} else {
    echo "❌ Gagal mengirim email ke $email — cek konfigurasi mail() server";
}
?>
