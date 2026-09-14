<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Hasil Quiz'; ?> - Portal</title>
    <link href="<?= base_url(); ?>assets/img/favicon.ico" rel="icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/fontawesome-free/css/all.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/styles-custom-homepage.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/styles-custom-portal.css">
    <style>
        .score-circle {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            color: #fff;
            font-size: 3rem;
            font-weight: 700;
            box-shadow: 0 10px 32px rgba(0, 0, 0, .18);
            position: relative;
            overflow: hidden;
        }

        .score-circle::after {
            content: '';
            position: absolute;
            inset: 6px;
            border-radius: 50%;
            border: 3px dashed rgba(255, 255, 255, .35);
            pointer-events: none;
        }

        .score-graded {
            background: linear-gradient(135deg, #28a745, #20c997);
        }

        .score-pending {
            background: linear-gradient(135deg, #ffc107, #fd7e14);
            font-size: 4rem;
        }

        .result-card {
            max-width: 620px;
            margin: 0 auto;
            border-radius: 16px;
            box-shadow: 0 8px 40px rgba(0, 0, 0, .08);
            border: none;
            overflow: hidden;
        }

        .result-card .card-body {
            padding: 42px 32px;
        }

        .result-card .card-footer {
            background: #f8fafc;
            border-top: 1px solid #eef2f7;
            padding: 18px 32px;
        }

        .badge-stat {
            font-size: .85rem;
            padding: 6px 12px;
            margin: 0 3px;
            border-radius: 8px;
            font-weight: 600;
        }

        .result-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 6px;
        }

        .info-pending-note {
            background: #fff8e1;
            border-left: 4px solid #ffc107;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: .9rem;
            text-align: left;
            margin-top: 18px;
        }

        .info-graded-note {
            background: #e8f5e9;
            border-left: 4px solid #28a745;
            padding: 12px 16px;
            border-radius: 8px;
            font-size: .9rem;
            text-align: left;
            margin-top: 18px;
        }

        .btn-certificate {
            padding: 14px;
            font-weight: 600;
            border-radius: 10px;
            font-size: 1.05rem;
            box-shadow: 0 4px 14px rgba(0, 123, 255, .35);
            transition: all .15s;
        }

        .btn-certificate:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 123, 255, .45);
        }

        .waiting-icon {
            animation: pulse 1.8s infinite;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: .7;
                transform: scale(1.05);
            }
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <?php include('nav_menu_upper.php'); ?>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <a href="/" class="brand-link">
                <img src="<?= base_url() ?>assets/img/fgroupindonesia.jpg" class="brand-image img-circle elevation-3" style="opacity:.8">
                <span class="brand-text font-weight-light">FGroupIndonesia</span>
            </a>
            <div class="sidebar"><?php include('nav_menu_user.php'); ?></div>
        </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-12">
                            <h1 class="m-0 text-center">Hasil Quiz</h1>
                            <p class="text-center text-muted mb-0"><?= esc($data_materi->judul ?? ''); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">

                    <div class="row justify-content-center">
                        <div class="col-lg-8 col-md-10">
                            <div class="card result-card">

                                <div class="card-body text-center">

                                    <?php if ($attempt->status === 'graded'): ?>

                                        <!-- ============ GRADED ============ -->
                                        <div class="score-circle score-graded mb-4">
                                            <?= number_format((float)$attempt->final_score, 0); ?>
                                        </div>

                                        <div class="result-title text-success">
                                            <i class="fas fa-check-circle"></i> Selamat!
                                        </div>
                                        <p class="text-muted mb-4">Quiz Anda telah selesai dinilai</p>

                                        <div class="mb-4">
                                            <span class="badge badge-success badge-stat">
                                                <i class="fas fa-check"></i> <?= $attempt->pg_questions; ?> Soal PG
                                            </span>
                                            <?php if ((int)$attempt->essay_questions > 0): ?>
                                                <span class="badge badge-warning badge-stat">
                                                    <i class="fas fa-pen"></i> <?= $attempt->essay_questions; ?> Soal Essay
                                                </span>
                                            <?php endif; ?>
                                            <span class="badge badge-info badge-stat">
                                                <i class="fas fa-star"></i> Skor <?= number_format((float)$attempt->final_score, 0); ?>/100
                                            </span>
                                        </div>

                                        <div class="info-graded-note">
                                            <i class="fas fa-info-circle text-success"></i>
                                            <strong>Penilaian selesai.</strong>
                                            <?php if (!empty($rilis_sertifikat) && $rilis_sertifikat === 'yes'): ?>
                                                Anda dapat mengunduh sertifikat di bawah ini.
                                            <?php else: ?>
                                                Sertifikat akan dirilis oleh admin untuk materi ini.
                                            <?php endif; ?>
                                        </div>

                                        <?php if (!empty($rilis_sertifikat) && $rilis_sertifikat === 'yes'): ?>
                                            <a href="<?= base_url('materi/certificate/' . $attempt->id_materi); ?>"
                                                class="btn btn-primary btn-block btn-certificate mt-4">
                                                <i class="fas fa-download"></i> Download Sertifikat
                                            </a>
                                        <?php else: ?>
                                            <div class="alert alert-warning mt-4 mb-0">
                                                <i class="fas fa-certificate"></i>
                                                Sertifikat belum dirilis untuk materi ini.
                                                <br><small>Hubungi admin untuk informasi lebih lanjut.</small>
                                            </div>
                                        <?php endif; ?>

                                    <?php else: ?>

                                        <!-- ============ PENDING ============ -->
                                        <div class="score-circle score-pending mb-4">
                                            <i class="fas fa-hourglass-half waiting-icon"></i>
                                        </div>

                                        <div class="result-title text-warning">
                                            <i class="fas fa-clock"></i> Menunggu Penilaian Admin
                                        </div>
                                        <p class="text-muted mb-4">
                                            Jawaban essay Anda sedang diperiksa.<br>
                                            Anda akan mendapat notifikasi setelah dinilai.
                                        </p>

                                        <div class="mb-4">
                                            <span class="badge badge-success badge-stat">
                                                <i class="fas fa-check"></i> <?= $attempt->pg_questions; ?> Soal PG
                                            </span>
                                            <?php if ((int)$attempt->essay_questions > 0): ?>
                                                <span class="badge badge-warning badge-stat">
                                                    <i class="fas fa-pen"></i> <?= $attempt->essay_questions; ?> Soal Essay
                                                </span>
                                            <?php endif; ?>
                                        </div>

                                        <div class="info-pending-note">
                                            <i class="fas fa-info-circle text-warning"></i>
                                            <strong>Estimasi:</strong> Penilaian essay biasanya membutuhkan waktu 1×24 jam.
                                            Sertifikat akan tersedia setelah admin menyelesaikan penilaian.
                                        </div>

                                        <p class="mt-4 mb-0 small text-muted">
                                            <i class="fas fa-calendar-alt"></i>
                                            Disubmit: <?= date('d M Y H:i', strtotime($attempt->date_created)); ?>
                                        </p>

                                    <?php endif; ?>

                                </div>

                                <div class="card-footer text-center">
                                    <a href="<?= base_url('all-materi'); ?>" class="btn btn-default">
                                        <i class="fas fa-arrow-left"></i> Kembali ke Daftar Materi
                                    </a>
                                    <a href="<?= base_url('homepage'); ?>" class="btn btn-outline-secondary">
                                        <i class="fas fa-home"></i> Dashboard
                                    </a>
                                </div>

                            </div>
                        </div>
                    </div>

                    <!-- =====================================================
                         DETAIL JAWABAN (DISEMBUNYIKAN dari user)
                         Tidak dihapus dari DOM agar tetap bisa diakses
                         admin bila perlu debug via source-code inspection.
                         JANGAN aktifkan display tanpa pertimbangan keamanan.
                         ===================================================== -->
                    <div class="row" style="display:none !important;">
                        <div class="col-md-12">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-list-check"></i> Detail Jawaban</h3>
                                </div>
                                <div class="card-body" style="max-height: 65vh; overflow-y:auto;">
                                    <?php if (!empty($data_answers)): ?>
                                        <?php $n = 1;
                                        foreach ($data_answers as $a): ?>
                                            <?php
                                            $cls = 'answer-pending';
                                            $lbl = '<span class="badge badge-warning">Menunggu Admin</span>';
                                            if ($a->jenis !== 'essay') {
                                                if ((int)$a->is_correct === 1) {
                                                    $cls = 'answer-correct';
                                                    $lbl = '<span class="badge badge-success">Benar</span>';
                                                } else {
                                                    $cls = 'answer-wrong';
                                                    $lbl = '<span class="badge badge-danger">Salah</span>';
                                                }
                                            }
                                            ?>
                                            <div class="answer-box <?= $cls; ?>">
                                                <div class="d-flex justify-content-between align-items-start mb-2">
                                                    <strong>Soal <?= $n++; ?>.</strong>
                                                    <?= $lbl; ?>
                                                </div>
                                                <div class="small text-muted mb-2"><?= nl2br(esc($a->pertanyaan ?? '')); ?></div>
                                                <div class="mb-1"><strong>Jawaban Anda:</strong> <?= esc($a->jawaban ?: '-'); ?></div>
                                                <?php if ($a->jenis !== 'essay'): ?>
                                                    <div class="small text-success"><strong>Kunci:</strong> <?= esc($a->final_answer ?? ''); ?></div>
                                                <?php else: ?>
                                                    <?php if (!empty($a->score)): ?>
                                                        <div class="small text-primary"><strong>Skor:</strong> <?= $a->score; ?></div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($a->catatan_admin)): ?>
                                                        <div class="small text-muted mt-1"><em>Catatan admin: <?= esc($a->catatan_admin); ?></em></div>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <p class="text-muted">Tidak ada data jawaban.</p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <?php include('footer.php'); ?>
    </div>

    <script src="<?= base_url(); ?>assets/js/jquery371.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/adminlte.js"></script>
</body>

</html>