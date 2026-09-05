<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Kode OTP Reset Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 520px;
            margin: 40px auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        .header {
            background: #0d6efd;
            padding: 24px 30px;
            text-align: center;
        }

        .header img {
            max-height: 60px;
            margin-bottom: 8px;
        }

        .header h2 {
            color: #fff;
            margin: 0;
            font-weight: 600;
            font-size: 22px;
        }

        .body {
            padding: 32px 30px;
        }

        .body p {
            color: #333;
            line-height: 1.7;
            font-size: 15px;
            margin: 0 0 16px 0;
        }

        .otp-box {
            background: #f0f4ff;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
            border: 2px dashed #0d6efd;
        }

        .otp-code {
            font-size: 42px;
            font-weight: 700;
            letter-spacing: 12px;
            color: #0d6efd;
            font-family: 'Courier New', monospace;
        }

        .otp-expiry {
            font-size: 13px;
            color: #888;
            margin-top: 6px;
        }

        .footer {
            background: #f8fafc;
            padding: 18px 30px;
            text-align: center;
            border-top: 1px solid #e9edf4;
            font-size: 13px;
            color: #888;
        }

        .footer a {
            color: #0d6efd;
            text-decoration: none;
        }

        .btn {
            display: inline-block;
            background: #0d6efd;
            color: #fff;
            padding: 10px 28px;
            border-radius: 6px;
            text-decoration: none;
            font-weight: 600;
            margin-top: 8px;
        }

        .btn:hover {
            background: #0b5ed7;
        }

        @media (max-width: 480px) {
            .body {
                padding: 20px;
            }

            .otp-code {
                font-size: 30px;
                letter-spacing: 8px;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="<?= $link_logo ?? 'https://portal.fgroupindonesia.com/assets/img/logo.jpg' ?>" alt="Logo">
            <h2>Reset Password</h2>
        </div>
        <div class="body">
            <p>Halo <strong><?= esc($customer_name ?? 'Pengguna') ?></strong>,</p>
            <p>Kami menerima permintaan untuk mereset password akun Anda di <strong>Portal FGroupIndonesia</strong>.</p>
            <p>Gunakan kode OTP di bawah ini untuk melanjutkan proses reset password:</p>

            <div class="otp-box">
                <div class="otp-code"><?= esc($otp_code) ?></div>
                <div class="otp-expiry">⏱ Kode berlaku selama <?= esc($expires_in ?? '10 menit') ?></div>
            </div>

            <p style="font-size:14px; color:#666;">Jika Anda tidak meminta reset password, abaikan email ini. Password Anda tetap aman.</p>
            <p style="margin-top:8px;">
                <a href="<?= $link_portal ?? base_url() ?>" class="btn">Kembali ke Portal</a>
            </p>
        </div>
        <div class="footer">
            &copy; <?= date('Y') ?> <a href="<?= $link_portal ?? base_url() ?>">FGroupIndonesia</a> — Training &amp; Digital Solutions Provider
        </div>
    </div>
</body>

</html>