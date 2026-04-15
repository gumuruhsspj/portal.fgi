<?php 
/*
Variables Required:
$customer_name
$link_logo
$link_portal
$nama_materi
$nama_paket
$biaya
$reference_id
*/
$tanggal_now = date('d F Y H:i');
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran Berhasil - Selamat Belajar!</title>
   
    <style type="text/css">
        body, #bodyTable, #bodyCell {
            height: 100% !important; 
            margin: 0; 
            padding: 0; 
            width: 100% !important;
            font-family: 'Inter', Arial, sans-serif;
            background-color: #f0f4f8;
        }
        table { border-collapse: collapse; }
        @media only screen and (max-width: 600px) {
            .content-table { width: 95% !important; }
            .header-text { font-size: 22px !important; }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f0f4f8;">
    <center>
        <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable">
            <tr>
                <td align="center" valign="top" id="bodyCell" style="padding: 20px;">
                    <table border="0" cellpadding="0" cellspacing="0" width="600" class="content-table" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; border: 1px solid #e0e7ff; box-shadow: 0 8px 16px rgba(0,0,0,0.1);">
                        
                        <tr>
                            <td align="center" valign="top" style="padding: 30px 20px; background-color: #28a745; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                                <img src="<?= $link_logo; ?>" alt="Logo" width="150" style="display: block; max-width: 150px; margin-bottom: 15px;">
                                <h1 class="header-text" style="color: #ffffff; font-size: 26px; margin: 0;">Pembayaran Berhasil!</h1>
                            </td>
                        </tr>

                        <tr>
                            <td align="left" valign="top" style="padding: 30px;">
                                <p style="font-size: 16px; color: #333333; margin: 0 0 15px 0;">
                                    Halo <b><?= $customer_name; ?></b>,
                                </p>
                                <p style="font-size: 16px; color: #333333; line-height: 1.6; margin: 0 0 20px 0;">
                                    Hore! Checkout Anda **Sukses**. Pembayaran telah berhasil dipotong dari saldo akun Anda secara otomatis. Akses belajar Anda telah **aktif** dan bisa langsung digunakan sekarang.
                                </p>

                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border: 1px solid #d4edda; border-radius: 8px; background-color: #f4fff6; margin-bottom: 25px;">
                                    <tr>
                                        <td style="padding: 20px;">
                                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                <tr>
                                                    <td style="padding-bottom: 10px; font-size: 14px; color: #666;">Materi:</td>
                                                    <td style="padding-bottom: 10px; font-size: 14px; color: #333; font-weight: bold; text-align: right;"><?= $nama_materi; ?></td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-bottom: 10px; font-size: 14px; color: #666;">Paket:</td>
                                                    <td style="padding-bottom: 10px; font-size: 14px; color: #333; font-weight: bold; text-align: right;"><?= $nama_paket; ?></td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-bottom: 10px; font-size: 14px; color: #666;">Tanggal:</td>
                                                    <td style="padding-bottom: 10px; font-size: 14px; color: #333; font-weight: bold; text-align: right;"><?= $tanggal_now; ?></td>
                                                </tr>
                                                <tr>
                                                    <td style="padding-top: 10px; font-size: 16px; color: #155724; font-weight: bold; border-top: 1px dashed #c3e6cb;">Total Terbayar:</td>
                                                    <td style="padding-top: 10px; font-size: 18px; color: #28a745; font-weight: bold; text-align: right; border-top: 1px dashed #c3e6cb;">Rp <?= number_format($biaya, 0, ',', '.'); ?></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>

                                <p style="font-size: 15px; color: #555; line-height: 1.6; margin: 0 0 25px 0; text-align: center;">
                                    ID Transaksi: <b>#<?= $reference_id; ?></b><br>
                                    Silahkan klik tombol di bawah untuk langsung masuk ke ruang belajar.
                                </p>

                                <table border="0" cellspacing="0" cellpadding="0" align="center">
                                    <tr>
                                        <td align="center" style="border-radius: 6px; background-color: #007bff;">
                                            <a href="<?= $link_portal; ?>" target="_blank" style="font-size: 16px; color: #ffffff; text-decoration: none; border-radius: 6px; padding: 15px 35px; display: inline-block; font-weight: bold; letter-spacing: 0.5px;">
                                                MULAI BELAJAR SEKARANG
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td align="center" style="padding: 20px; background-color: #f8f9fa; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px; border-top: 1px solid #eee;">
                                <p style="font-size: 12px; color: #a0a0a0; margin: 0;">
                                    Email ini dikirim otomatis sebagai bukti pembayaran sah.
                                </p>
                                <p style="font-size: 12px; color: #a0a0a0; margin: 5px 0 0 0;">
                                    &copy; <?= date('Y'); ?> FGroupIndonesia.com | Support 24/7
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </center>
</body>
</html>