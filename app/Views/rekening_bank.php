<!DOCTYPE html>
<html lang="en">
<?php $rand = "?v=" . rand(0, 2000); ?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rekening Bank - Promotor</title>
    <link href="<?= base_url() ?>assets/img/favicon.ico" rel="icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/styles-custom-homepage.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/styles-custom-portal.css">
    <style>
        .status-box {
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            text-align: center;
        }

        .status-box i {
            font-size: 3rem;
            display: block;
            margin-bottom: 10px;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
            border: 1px solid #ffc107;
        }

        .status-approved {
            background: #d4edda;
            color: #155724;
            border: 1px solid #28a745;
        }

        .status-rejected {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #dc3545;
        }

        .animated-icon {
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }

            50% {
                transform: scale(1.1);
            }

            100% {
                transform: scale(1);
            }
        }

        .file-preview {
            max-width: 150px;
            max-height: 150px;
            margin-top: 10px;
            border-radius: 6px;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include('nav_menu_upper.php'); ?>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <?php include('brand_logo.php'); ?>
            <div class="sidebar">
                <?php include('nav_menu_promotor.php'); ?>
            </div>
        </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Rekening Bank</h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-8 offset-lg-2">
                            <!-- Status Box -->
                            <?php if ($rekening): ?>
                                <?php $status = $rekening['status']; ?>
                                <div class="status-box status-<?= $status ?>">
                                    <?php if ($status == 'pending'): ?>
                                        <i class="fas fa-hourglass-half animated-icon"></i>
                                        <h4>Dokumen sedang diverifikasi</h4>
                                        <p>Data rekening Anda telah kami terima. Mohon tunggu konfirmasi dari admin.</p>
                                    <?php elseif ($status == 'approved'): ?>
                                        <i class="fas fa-check-circle animated-icon" style="color: #28a745;"></i>
                                        <h4>✅ Dokumen Terverifikasi!</h4>
                                        <p>Rekening Anda telah disetujui. Anda dapat melakukan penarikan saldo.</p>
                                        <a href="<?= base_url('riwayat-saldo') ?>" class="btn btn-success mt-2">
                                            <i class="fas fa-wallet"></i> Lihat Riwayat Saldo
                                        </a>
                                    <?php elseif ($status == 'rejected'): ?>
                                        <i class="fas fa-times-circle" style="color: #dc3545;"></i>
                                        <h4>Dokumen Ditolak</h4>
                                        <p>Data rekening Anda tidak lolos verifikasi. Silakan periksa kembali dan kirim ulang.</p>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Form (hanya tampil jika belum approved atau rejected) -->
                            <?php if (!$rekening || $rekening['status'] == 'rejected'): ?>
                                <div class="card">
                                    <div class="card-header">
                                        <h3 class="card-title">Isi Data Rekening</h3>
                                    </div>
                                    <div class="card-body">
                                        <form id="rekeningForm" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label>Nama Bank</label>
                                                <select name="nama_bank" class="form-control" required>
                                                    <option value="">-- Pilih Bank --</option>
                                                    <option value="Bank Mandiri">Bank Mandiri</option>
                                                    <option value="Bank Rakyat Indonesia (BRI)">Bank Rakyat Indonesia (BRI)</option>
                                                    <option value="Bank Negara Indonesia (BNI)">Bank Negara Indonesia (BNI)</option>
                                                    <option value="Bank Central Asia (BCA)">Bank Central Asia (BCA)</option>
                                                    <option value="Bank Tabungan Negara (BTN)">Bank Tabungan Negara (BTN)</option>
                                                    <option value="Bank CIMB Niaga">Bank CIMB Niaga</option>
                                                    <option value="Bank Danamon">Bank Danamon</option>
                                                    <option value="Bank Permata">Bank Permata</option>
                                                    <option value="Bank Maybank Indonesia">Bank Maybank Indonesia</option>
                                                    <option value="Bank Panin">Bank Panin</option>
                                                    <option value="Bank OCBC NISP">Bank OCBC NISP</option>
                                                    <option value="Bank UOB Indonesia">Bank UOB Indonesia</option>
                                                    <option value="Bank Artha Graha">Bank Artha Graha</option>
                                                    <option value="Bank Bukopin">Bank Bukopin</option>
                                                    <option value="Bank BNI Syariah">Bank BNI Syariah</option>
                                                    <option value="Bank BRI Syariah">Bank BRI Syariah</option>
                                                    <option value="Bank Mandiri Syariah">Bank Mandiri Syariah</option>
                                                    <option value="Bank Syariah Indonesia">Bank Syariah Indonesia</option>
                                                    <option value="Bank Mega">Bank Mega</option>
                                                    <option value="Bank Sinarmas">Bank Sinarmas</option>
                                                    <option value="Bank MNC">Bank MNC</option>
                                                    <option value="Bank Neo Commerce">Bank Neo Commerce</option>
                                                    <option value="Bank Jago">Bank Jago</option>
                                                    <option value="Bank Aladin Syariah">Bank Aladin Syariah</option>
                                                    <option value="Bank BCA Syariah">Bank BCA Syariah</option>
                                                    <option value="Bank DBS Indonesia">Bank DBS Indonesia</option>
                                                    <option value="Bank HSBC Indonesia">Bank HSBC Indonesia</option>
                                                    <option value="Bank Standard Chartered">Bank Standard Chartered</option>
                                                    <option value="Bank ANZ Indonesia">Bank ANZ Indonesia</option>
                                                    <option value="Bank Commonwealth">Bank Commonwealth</option>
                                                    <option value="Bank Mizuho Indonesia">Bank Mizuho Indonesia</option>
                                                    <option value="Bank Sumitomo Mitsui Indonesia">Bank Sumitomo Mitsui Indonesia</option>
                                                    <option value="Bank Woori Saudara">Bank Woori Saudara</option>
                                                    <option value="Bank BTPN">Bank BTPN</option>
                                                    <option value="Bank BTPN Syariah">Bank BTPN Syariah</option>
                                                    <option value="Bank QNB Indonesia">Bank QNB Indonesia</option>
                                                    <option value="Bank KEB Hana Indonesia">Bank KEB Hana Indonesia</option>
                                                    <option value="Bank ICBC Indonesia">Bank ICBC Indonesia</option>
                                                    <option value="Bank China Construction Bank Indonesia">Bank China Construction Bank Indonesia</option>
                                                    <option value="Bank BCA Digital">Bank BCA Digital</option>
                                                    <option value="Bank Seabank Indonesia">Bank Seabank Indonesia</option>
                                                    <option value="Bank Allo Bank">Bank Allo Bank</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Nama Pemilik Rekening</label>
                                                <input type="text" name="nama_pemilik" class="form-control" placeholder="Sesuai dengan KTP" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Nomor Rekening</label>
                                                <input type="text" name="nomor_rekening" class="form-control" placeholder="Nomor rekening" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Upload Foto KTP (depan)</label>
                                                <input type="file" name="foto_ktp" class="form-control" accept="image/*" required>
                                                <small class="text-muted">Format: JPG, PNG, maks 2MB</small>
                                            </div>
                                            <div class="form-group">
                                                <label>Upload Foto Selfie dengan KTP (face + KTP terlihat)</label>
                                                <input type="file" name="foto_selfie" class="form-control" accept="image/*" required>
                                                <small class="text-muted">Format: JPG, PNG, maks 2MB</small>
                                            </div>
                                            <div class="alert alert-info">
                                                <i class="fas fa-info-circle"></i> Nama pemilik harus sama dengan identitas KTP untuk memudahkan verifikasi.
                                            </div>
                                            <button type="submit" class="btn btn-primary btn-block" id="submitBtn">
                                                <i class="fas fa-paper-plane"></i> Kirim
                                            </button>
                                        </form>
                                        <div id="loadingSpinner" style="display:none; text-align:center; margin-top:20px;">
                                            <div class="spinner-border text-primary" role="status">
                                                <span class="sr-only">Loading...</span>
                                            </div>
                                            <p>Mengirim data...</p>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('footer.php'); ?>
        <?php include('modal_customer_services.php'); ?>
    </div>

    <!-- Scripts -->
    <script src="<?= base_url() ?>assets/js/jquery371.min.js<?= $rand; ?>"></script>
    <script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js<?= $rand; ?>"></script>
    <script src="<?= base_url() ?>assets/js/adminlte.js<?= $rand; ?>"></script>
    <script src="<?= base_url() ?>assets/js/sweetalert2@11.js<?= $rand; ?>"></script>
    <script>
        $(document).ready(function() {
            $('#rekeningForm').on('submit', function(e) {
                e.preventDefault();

                var formData = new FormData(this);
                var btn = $('#submitBtn');
                var spinner = $('#loadingSpinner');

                // Validasi sederhana
                var bank = $('select[name="nama_bank"]').val();
                if (!bank) {
                    Swal.fire('Perhatian', 'Silakan pilih bank.', 'warning');
                    return;
                }

                btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengirim...');
                spinner.show();

                $.ajax({
                    url: '<?= base_url('saldo/rekening-bank/submit') ?>',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: 'json',
                    success: function(res) {
                        spinner.hide();
                        btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Kirim');
                        if (res.status == 'success') {
                            Swal.fire('Berhasil!', res.message, 'success').then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire('Gagal', res.message, 'error');
                        }
                    },
                    error: function(xhr, status, error) {
                        spinner.hide();
                        btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> Kirim');
                        Swal.fire('Error', 'Terjadi kesalahan: ' + error, 'error');
                    }
                });
            });
        });
    </script>
</body>

</html>