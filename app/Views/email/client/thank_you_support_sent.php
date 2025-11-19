<?php 

/*
Please Fill from the views the following variables :
$customer_name
$link_logo
$link_portal
$title
$reference_id
*/

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Anda Kami Terima!</title>
   
    <style type="text/css">
        /* Reset Styles */
        body, #bodyTable, #bodyCell {
            height: 100% !important; 
            margin: 0; 
            padding: 0; 
            width: 100% !important;
            font-family: 'Inter', Arial, sans-serif;
            background-color: #f0f4f8; /* Warna latar belakang yang lebih lembut */
        }
        table { 
            border-collapse: collapse; 
            mso-table-lspace: 0pt; 
            mso-table-rspace: 0pt; 
        }
        td { 
            mso-table-lspace: 0pt; 
            mso-table-rspace: 0pt; 
        }
        /* Mobile Styles */
        @media only screen and (max-width: 600px) {
            .content-table {
                width: 90% !important;
            }
            .header-text {
                font-size: 24px !important;
            }
            .logo-img {
                max-width: 120px !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f0f4f8;">
    <!-- CONTAINER UTAMA -->
    <center>
        <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="border-collapse: collapse;">
            <tr>
                <td align="center" valign="top" id="bodyCell" style="padding: 20px;">
                    <!-- TABLE KONTEN -->
                    <table border="0" cellpadding="0" cellspacing="0" width="600" class="content-table" style="max-width: 600px; background-color: #ffffff; border-radius: 12px; border: 1px solid #e0e7ff; box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);">
                        
                        <!-- HEADER DAN LOGO -->
                        <tr>
                            <td align="center" valign="top" style="padding: 30px 20px 20px 20px; background-color: #007bff; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                                <img src="<?= $link_logo; ?>" alt="Logo Perusahaan" class="logo-img" width="150" style="display: block; border: 0; max-width: 150px; margin-bottom: 10px;">
                                <h1 class="header-text" style="color: #ffffff; font-size: 28px; margin: 0; padding: 0;">Permintaan Anda Kami Terima!</h1>
                            </td>
                        </tr>

                        <!-- SPASI -->
                        <tr>
                            <td height="25" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
                        </tr>

                        <!-- ISI PESAN -->
                        <tr>
                            <td align="left" valign="top" style="padding: 0 30px 20px 30px;">
                                <p style="font-size: 16px; color: #333333; line-height: 1.6; margin: 0 0 15px 0;">
                                    Halo <b><?= $customer_name; ?></b>,
                                </p>

                                <p style="font-size: 16px; color: #333333; line-height: 1.6; margin: 0 0 15px 0;">
                                    Terima kasih telah menghubungi kami. Kami ingin mengonfirmasi bahwa permintaan Anda di bawah ini telah berhasil kami terima dan terdaftar dalam sistem kami.
                                </p>

                                <!-- RINGKASAN PERMINTAAN -->
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border: 1px solid #cfe2ff; border-radius: 8px; background-color: #f8faff; margin: 20px 0;">
                                    <tr>
                                        <td style="padding: 15px;">
                                            <p style="font-size: 14px; color: #007bff; font-weight: bold; margin: 0 0 5px 0;">JUDUL PERMINTAAN:</p>
                                            <p style="font-size: 18px; font-weight: bold; color: #333333; margin: 0;">
                                                <?= $title; ?>
                                            </p>
                                        </td>
                                    </tr>
                                </table>

                                <p style="font-size: 16px; color: #333333; line-height: 1.6; margin: 0 0 15px 0;">
                                    Tim manajemen kami akan segera meninjau detail permintaan Anda dan kami akan segera menghubungi Anda dengan langkah selanjutnya atau jawaban dalam waktu <b>24-48 jam kerja</b>.
                                </p>

                                <p style="font-size: 16px; color: #333333; line-height: 1.6; margin: 0 0 25px 0;">
                                    Nomor referensi Anda adalah: <b>#<?= $reference_id; ?></b>. Mohon cantumkan nomor ini jika Anda perlu menanyakan status permintaan Anda.
                                </p>

                                <p style="font-size: 16px; color: #333333; line-height: 1.6; margin: 0;">
                                    Hormat kami,<br>
                                    <b>Manajemen</b>
                                </p>
                            </td>
                        </tr>
                        
                        <!-- BUTTON CALL TO ACTION (Opsional) -->
                        <tr>
                            <td align="center" style="padding: 10px 30px 25px 30px;">
                                <table border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td align="center" style="border-radius: 6px; background-color: #28a745;" bgcolor="#28a745">
                                            <a href="<?= $link_portal; ?>" target="_blank" style="font-size: 16px; font-family: 'Inter', Arial, sans-serif; color: #ffffff; text-decoration: none; border-radius: 6px; padding: 12px 25px; border: 1px solid #28a745; display: inline-block;">
                                                Lihat Status Permintaan Anda
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <!-- FOOTER -->
                        <tr>
                            <td align="center" valign="top" style="padding: 20px; background-color: #f0f4f8; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px; border-top: 1px solid #e0e7ff;">
                                <p style="font-size: 12px; color: #a0a0a0; margin: 0;">
                                    Anda menerima email ini sebagai konfirmasi atas permintaan Anda.
                                </p>
                                <p style="font-size: 12px; color: #a0a0a0; margin: 5px 0 0 0;">
                                    (c) <?= date('Y'); ?> Web Portal FGroupIndonesia.com
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