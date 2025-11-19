<?php 

/*
Please Fill from the views the following variables :
$customer_name
$email
$title
$descriptions
*/

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permintaan Pelanggan Baru</title>
    <style type="text/css">
        /* Reset Styles */
        body, #bodyTable, #bodyCell {
            height: 100% !important; 
            margin: 0; 
            padding: 0; 
            width: 100% !important;
            font-family: Arial, sans-serif;
            background-color: #f6f6f6;
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
            .full-width-cell {
                width: 100% !important;
                display: block !important;
                padding: 15px !important;
            }
            .content-table {
                width: 90% !important;
            }
            .spacer {
                height: 10px !important;
            }
            .header-text {
                font-size: 24px !important;
            }
            .logo-img {
                max-width: 150px !important;
            }
        }
    </style>
</head>
<body style="margin: 0; padding: 0; background-color: #f6f6f6;">
    <center>
        <table align="center" border="0" cellpadding="0" cellspacing="0" height="100%" width="100%" id="bodyTable" style="border-collapse: collapse;">
            <tr>
                <td align="center" valign="top" id="bodyCell" style="padding: 20px;">
                    <table border="0" cellpadding="0" cellspacing="0" width="600" class="content-table" style="max-width: 600px; background-color: #ffffff; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.05);">
                        
                        <tr>
                            <td align="center" valign="top" style="padding: 20px 20px 0px 20px; background-color: #007bff; border-top-left-radius: 8px; border-top-right-radius: 8px;">
                                <a href="[Alamat Web Anda]" target="_blank" style="text-decoration: none;">
                                    <img src="[Link ke Logo Anda]" alt="Logo Perusahaan" class="logo-img" width="180" style="display: block; border: 0; max-width: 180px;">
                                </a>
                                <div style="height: 15px;"></div>
                            </td>
                        </tr>

                        <tr>
                            <td align="center" valign="top" style="padding: 0 20px 20px 20px; background-color: #007bff;">
                                <h1 class="header-text" style="color: #ffffff; font-size: 28px; margin: 0; padding: 0;">Permintaan Pelanggan Baru</h1>
                                <p style="color: #cce0ff; font-size: 16px; margin: 5px 0 0 0;">Diterima pada : <?= date('d-m-Y h:i') . ' WIB'; ?>.</p>
                            </td>
                        </tr>

                        <tr>
                            <td height="30" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
                        </tr>

                        <tr>
                            <td align="left" valign="top" style="padding: 0 30px;">
                                
                                <p style="font-size: 18px; font-weight: bold; color: #333333; margin: 0 0 5px 0;">
                                    Judul: <span style="font-weight: normal; color: #007bff;"><?= $title; ?></span>
                                </p>
                                <div style="height: 15px;"></div>

                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border-collapse: collapse;">
                                    <tr>
                                        <td valign="top" class="full-width-cell" width="50%" style="padding-right: 15px;">
                                            <p style="font-size: 14px; font-weight: bold; color: #555555; margin: 0 0 5px 0;">NAMA PELANGGAN</p>
                                            <p style="font-size: 16px; color: #333333; background-color: #f9f9f9; padding: 10px; border: 1px solid #eeeeee; border-radius: 4px; margin: 0 0 15px 0;">
                                                <?= $customer_name; ?>
                                            </p>
                                        </td>
                                        
                                        <td valign="top" class="full-width-cell" width="50%" style="padding-left: 15px;">
                                            <p style="font-size: 14px; font-weight: bold; color: #555555; margin: 0 0 5px 0;">EMAIL KONTAK</p>
                                            <p style="font-size: 16px; color: #007bff; background-color: #f9f9f9; padding: 10px; border: 1px solid #eeeeee; border-radius: 4px; margin: 0 0 15px 0;">
                                                <a href="mailto:<?=$email; ?>" style="color: #007bff; text-decoration: none;"><?= $email; ?></a>
                                            </p>
                                        </td>
                                    </tr>
                                </table>

                                <p style="font-size: 14px; font-weight: bold; color: #555555; margin: 0 0 5px 0;">PERTANYAAN / PESAN</p>
                                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="border: 1px solid #dddddd; border-radius: 4px; background-color: #f9f9f9;">
                                    <tr>
                                        <td style="padding: 15px;">
                                            <p style="font-size: 16px; line-height: 1.6; color: #333333; margin: 0;">
                                                <?= $descriptions; ?>
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>

                        <tr>
                            <td height="30" style="font-size: 1px; line-height: 1px;">&nbsp;</td>
                        </tr>


                         <!-- FOOTER -->
                        <tr>
                            <td align="center" valign="top" style="padding: 20px; background-color: #f0f4f8; border-bottom-left-radius: 12px; border-bottom-right-radius: 12px; border-top: 1px solid #e0e7ff;">
                                
                                <p style="font-size: 12px; color: #a0a0a0; margin: 5px 0 0 0;">
                                    (c)<?= date('Y'); ?> Web Portal FGroupIndonesia.com
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