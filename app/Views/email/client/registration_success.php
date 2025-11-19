<?php 

/*
Please Fill from the views the following variables :
$customer_name        // Nama lengkap pengguna
$customer_email       // Email pengguna yang terdaftar
$temporary_password   // Password acak yang dihasilkan
$link_logo            // URL logo perusahaan
$link_portal          // URL halaman login portal
*/

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun Anda Berhasil Terdaftar!</title>
    
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
                            <td align="center" valign="top" style="padding: 30px 20px 20px 20px; background-color: #28a745; border-top-left-radius: 12px; border-top-right-radius: 12px;">
                                <img src="<?= $link_logo; ?>" alt="Logo Perusahaan" class="logo-img" width="150" style="display: block; border: 0; max-width: 150px; margin-bottom: 10px;">
                                <h1 class="header-text" style="color: #ffffff; font-size: 28px; margin: 0; padding: 0;">Akun Anda Berhasil Dibuat!</h1>
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
                                    Kami senang mengonfirmasi bahwa akun Anda di portal telah berhasil didaftarkan. Anda dapat langsung <b>login</b> menggunakan detail akun berikut:
                                </p>

                                <!-- DETAIL AKUN -->
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border: 1px solid #c3e6cb; border-radius: 8px; background-color: #e2f0e6; margin: 20px 0;">
                                    <tr>
                                        <td style="padding: 15px;">
                                            <p style="font-size: 14px; color: #1e7e34; font-weight: bold; margin: 0 0 5px 0;">DETAIL AKUN:</p>
                                            <p style="font-size: 16px; color: #333333; margin: 0 0 5px 0;">
                                                Nama Lengkap: <b><?= $customer_name; ?></b>
                                            </p>
                                            <p style="font-size: 16px; color: #333333; margin: 0 0 5px 0;">
                                                Email: <b><?= $customer_email; ?></b>
                                            </p>
                                            <p style="font-size: 16px; color: #dc3545; font-weight: bold; margin: 10px 0 0 0;">
                                                Password Sementara: 
                                                <span style="background-color: #f7f7f7; padding: 5px 10px; border-radius: 4px; border: 1px dashed #cccccc; user-select: text;">
                                                    <?= $temporary_password; ?>
                                                </span>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                                <!-- END DETAIL AKUN -->

                                <p style="font-size: 16px; color: #333333; line-height: 1.6; margin: 0 0 15px 0;">
                                    <b style="color: #dc3545;">Penting:</b> Demi keamanan akun Anda, setelah berhasil <b>login</b> ke portal, <b style="color: #007bff;">harap segera perbarui password sementara ini</b> melalui menu <b style="color: #007bff;">Dashboard | Pengaturan Akun</b>.
                                </p>
                                
                                <p style="font-size: 16px; color: #333333; line-height: 1.6; margin: 0 0 25px 0;">
                                    Silakan klik tombol di bawah ini untuk menuju halaman <b>login</b> portal Anda.
                                </p>

                                <!-- BUTTON CALL TO ACTION -->
                                <table border="0" cellspacing="0" cellpadding="0" align="center" style="margin-bottom: 20px;">
                                    <tr>
                                        <td align="center" style="border-radius: 6px; background-color: #007bff;" bgcolor="#007bff">
                                            <a href="<?= $link_portal; ?>" target="_blank" style="font-size: 16px; font-family: 'Inter', Arial, sans-serif; color: #ffffff; text-decoration: none; border-radius: 6px; padding: 12px 25px; border: 1px solid #007bff; display: inline-block; font-weight: bold;">
                                                Login Sekarang
                                            </a>
                                        </td>
                                    </tr>
                                </table>
                                <!-- END BUTTON -->

                                <p style="font-size: 16px; color: #333333; line-height: 1.6; margin: 0;">
                                    Terima kasih,<br>
                                    <b>Tim Manajemen</b>
                                </p>
                            </td>
                        </tr>
                        
                        <!-- FOOTER -->
                        <tr>
                            <td align="center" valign="top" style="padding: 20px; background-color: #f0f4f8; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px; border-top: 1px solid #e0e7ff;">
                                <p style="font-size: 12px; color: #a0a0a0; margin: 0;">
                                    Anda menerima email ini karena akun Anda baru saja terdaftar di sistem kami. Jika Anda tidak melakukan registrasi, abaikan email ini.
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