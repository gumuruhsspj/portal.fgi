<!DOCTYPE html>
<html lang="en">
<?php $rand = "?v=" . rand(0, 2000); ?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Media Promosi - Promotor</title>
    <link href="<?= base_url() ?>assets/img/favicon.ico" rel="icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/styles-custom-homepage.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/styles-custom-portal.css">
    <style>
        .media-card {
            transition: transform 0.2s, box-shadow 0.2s;
            border-radius: 12px;
            overflow: hidden;
        }

        .media-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.15);
        }

        .media-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-bottom: 1px solid #eee;
        }

        .media-card .card-body {
            padding: 15px;
        }

        .media-card .card-title {
            font-size: 1rem;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .media-card .card-text {
            font-size: 0.85rem;
            color: #666;
        }

        .download-btn {
            border-radius: 50px;
            padding: 6px 20px;
            font-size: 0.85rem;
        }

        .badge-kategori {
            background: #e9ecef;
            color: #495057;
            font-size: 0.7rem;
            padding: 3px 10px;
            border-radius: 20px;
        }

        .download-loading {
            display: none;
            margin-left: 8px;
        }

        .toast-success {
            position: fixed;
            bottom: 30px;
            right: 30px;
            background: #28a745;
            color: white;
            padding: 15px 30px;
            border-radius: 8px;
            box-shadow: 0 5px 20px rgba(0, 0, 0, 0.2);
            z-index: 9999;
            font-weight: 600;
            animation: slideUp 0.4s ease;
            display: none;
        }

        @keyframes slideUp {
            from {
                transform: translateY(40px);
                opacity: 0;
            }

            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .toast-success.fade-out {
            animation: fadeOut 0.5s ease forwards;
        }

        @keyframes fadeOut {
            to {
                opacity: 0;
                transform: translateY(-20px);
            }
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
        }

        .empty-state i {
            font-size: 4rem;
            color: #ccc;
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
                            <h1>Media Promosi</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('homepage') ?>">Dashboard</a></li>
                                <li class="breadcrumb-item active">Media Promosi</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">

                    <!-- Info Box -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i>
                                Klik tombol <strong>Download</strong> pada poster untuk mendapatkan gambar dengan
                                <strong>WhatsApp</strong> dan <strong>Link Referal</strong> Anda secara otomatis.
                                <span class="badge badge-success ml-2">WA: <?= session()->get('whatsapp') ?? 'Belum diisi' ?></span>
                                <span class="badge badge-primary ml-2">Kode: <?= $memberships[0]['kode_referal'] ?? 'Belum join program' ?></span>
                            </div>
                        </div>
                    </div>

                    <!-- Media Grid -->
                    <div class="row" id="mediaGrid">
                        <?php if (!empty($media_list)): ?>
                            <?php foreach ($media_list as $media): ?>
                                <div class="col-md-4 col-lg-3 col-sm-6 mb-4">
                                    <div class="card media-card h-100">
                                        <img src="<?= base_url($media['image']) ?>" alt="<?= esc($media['nama']) ?>" loading="lazy">
                                        <div class="card-body">
                                            <h5 class="card-title"><?= esc($media['nama']) ?></h5>
                                            <div class="mt-1">
                                                <span class="badge-kategori"><?= esc($media['kategori_nama'] ?? 'Tanpa Kategori') ?></span>
                                            </div>
                                            <div class="mt-3 d-flex justify-content-between align-items-center">
                                                <button class="btn btn-primary btn-sm download-btn" data-id="<?= $media['id'] ?>">
                                                    <i class="fas fa-download"></i> Download
                                                    <span class="spinner-border spinner-border-sm download-loading" role="status"></span>
                                                </button>
                                                <small class="text-muted"><?= date('d/m/Y', strtotime($media['created_at'])) ?></small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="col-12">
                                <div class="empty-state">
                                    <i class="fas fa-images"></i>
                                    <h4 class="mt-3">Belum ada media promosi</h4>
                                    <p class="text-muted">Media promosi akan muncul setelah admin menambahkannya.</p>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <!-- Pagination atau load more jika perlu -->

                </div>
            </div>
        </div>

        <?php include('footer.php'); ?>
        <?php include('modal_customer_services.php'); ?>
    </div>

    <!-- Toast Notification -->
    <div class="toast-success" id="downloadToast">
        <i class="fas fa-check-circle mr-2"></i> Poster berhasil didownload!
    </div>

    <script src="<?= base_url() ?>assets/js/jquery371.min.js<?= $rand; ?>"></script>
    <script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js<?= $rand; ?>"></script>
    <script src="<?= base_url() ?>assets/js/adminlte.js<?= $rand; ?>"></script>
    <script src="<?= base_url() ?>assets/js/sweetalert2@11.js<?= $rand; ?>"></script>
    <script src="<?= base_url() ?>assets/js/timer.js<?= $rand; ?>"></script>

    <script>
        $(document).ready(function() {
            // Handle download button
            $('.download-btn').click(function() {
                var btn = $(this);
                var id = btn.data('id');
                var loading = btn.find('.download-loading');

                // Disable button & show loading
                btn.prop('disabled', true);
                loading.show();

                // Simulasikan delay agar user melihat feedback
                var downloadUrl = '<?= base_url('afiliasi/media-promosi/download/') ?>' + id;

                // Buat link download temporary
                var link = document.createElement('a');
                link.href = downloadUrl;
                link.download = 'poster_' + id + '.png';
                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                // Tampilkan toast success
                showToast('Poster berhasil didownload!');

                // Reset button setelah beberapa detik
                setTimeout(function() {
                    btn.prop('disabled', false);
                    loading.hide();
                }, 3000);
            });

            // Fungsi toast dengan fadeout
            function showToast(message) {
                var toast = $('#downloadToast');
                toast.text('').html('<i class="fas fa-check-circle mr-2"></i> ' + message);
                toast.css('display', 'block');
                toast.removeClass('fade-out');

                // Auto hide setelah 3 detik dengan fadeout
                setTimeout(function() {
                    toast.addClass('fade-out');
                    setTimeout(function() {
                        toast.css('display', 'none');
                    }, 500);
                }, 3000);
            }

            // Tampilkan toast saat halaman dimuat jika ada parameter dari download
            <?php if (isset($downloaded)): ?>
                showToast('Poster berhasil didownload!');
            <?php endif; ?>
        });
    </script>

</body>

</html>