<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Sealer - Sukses</title>
    <!-- Bootstrap 5 & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem 1rem;
        }
        .card-result {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            backdrop-filter: blur(10px);
            background: rgba(255,255,255,0.9);
            max-width: 750px;
            width: 100%;
            padding: 2.5rem 2rem;
            text-align: center;
        }
        .success-icon {
            font-size: 5rem;
            color: #28a745;
            background: #d4edda;
            width: 120px;
            height: 120px;
            line-height: 120px;
            border-radius: 50%;
            margin: 0 auto 1.5rem;
        }
        .card-result h2 {
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        .card-result .subtitle {
            color: #6c757d;
            margin-bottom: 2rem;
            font-size: 1.1rem;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            background: #f8f9fa;
            border-radius: 15px;
            padding: 1.5rem;
            margin-bottom: 2rem;
            text-align: left;
        }
        .info-grid .label {
            font-weight: 600;
            color: #495057;
            font-size: 0.9rem;
        }
        .info-grid .value {
            color: #212529;
            font-weight: 500;
            word-break: break-all;
        }
        .qr-container {
            background: white;
            padding: 1rem;
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.05);
            display: inline-block;
            margin-bottom: 1.5rem;
        }
        .qr-container img {
            width: 180px;
            height: 180px;
            object-fit: contain;
        }
        .btn-download {
            background: linear-gradient(135deg, #28a745, #1e7e34);
            border: none;
            padding: 14px 50px;
            border-radius: 50px;
            font-weight: 600;
            font-size: 1.1rem;
            letter-spacing: 0.5px;
            transition: all 0.3s;
            color: white;
            text-decoration: none;
            display: inline-block;
        }
        .btn-download:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 30px rgba(40, 167, 69, 0.4);
            color: white;
        }
        .btn-download i {
            margin-right: 10px;
        }
        .btn-back {
            background: #6c757d;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            color: white;
            text-decoration: none;
            display: inline-block;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-back:hover {
            background: #5a6268;
            color: white;
        }
        .footer-actions {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        @media (max-width: 576px) {
            .card-result { padding: 1.5rem; }
            .info-grid { grid-template-columns: 1fr; gap: 0.75rem; }
            .qr-container img { width: 140px; height: 140px; }
        }
    </style>
</head>
<body>
    <div class="card-result">
        <!-- Icon sukses -->
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>

        <h2>Dokumen Berhasil Diverifikasi!</h2>
        <p class="subtitle">
            <i class="fas fa-shield-alt text-primary me-1"></i>
            Dokumen telah dilindungi dengan QR Code dan Watermark "VERIFIED".
        </p>

        <!-- Informasi dokumen -->
        <div class="info-grid">
            <div>
                <div class="label"><i class="fas fa-fingerprint me-1"></i> Document ID</div>
                <div class="value"><?= esc($document_id ?? '-') ?></div>
            </div>
            <div>
                <div class="label"><i class="fas fa-user me-1"></i> Pemilik</div>
                <div class="value"><?= esc($owner_name ?? '-') ?></div>
            </div>
            <div>
                <div class="label"><i class="fas fa-file-alt me-1"></i> Tipe File</div>
                <div class="value"><?= esc(strtoupper($file_type ?? '-')) ?></div>
            </div>
            <div>
                <div class="label"><i class="fas fa-calendar-alt me-1"></i> Tanggal</div>
                <div class="value"><?= isset($created_at) ? date('d-m-Y H:i', strtotime($created_at)) : '-' ?></div>
            </div>
        </div>

        <!-- QR Code -->
        <div class="qr-container">
            <?php if (!empty($qr_path) && file_exists(WRITEPATH . 'uploads/qr/' . $qr_path)): ?>
                <img src="<?= base_url('uploads/qr/' . $qr_path) ?>" alt="QR Code Verifikasi" class="img-fluid">
            <?php else: ?>
                <div class="text-muted">QR Code tidak tersedia</div>
            <?php endif; ?>
        </div>
        <p class="text-muted small">Scan QR Code untuk verifikasi dokumen</p>

        <!-- Tombol aksi -->
        <div class="footer-actions">
            <?php if (!empty($sealed_file)): ?>
                <a href="<?= site_url('result/download/' . $document_id) ?>" class="btn-download">
                    <i class="fas fa-download"></i> Download Dokumen Terverifikasi
                </a>
            <?php else: ?>
                <div class="alert alert-warning">File hasil tidak tersedia.</div>
            <?php endif; ?>
            <a href="<?= site_url('upload-seal') ?>" class="btn-back">
                <i class="fas fa-arrow-left me-1"></i> Upload Lainnya
            </a>
        </div>

        <hr class="my-4">
        <p class="text-muted small mb-0">
            <i class="fas fa-lock me-1"></i> Dokumen ini telah ditandai sebagai final dan dilindungi.
        </p>
    </div>

    <!-- Bootstrap JS (optional untuk tooltip) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>