<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= $title ?? 'Quiz'; ?> - Portal</title>
    <link href="<?= base_url(); ?>assets/img/favicon.ico" rel="icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/fontawesome-free/css/all.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/styles-custom-homepage.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/styles-custom-portal.css">
    <style>
        .quiz-item {
            border: 1px solid #e3e8ef;
            border-radius: 10px;
            padding: 20px 22px;
            background: #fff;
            animation: fadeIn .18s ease;
        }

        .quiz-item .no-soal {
            font-weight: 700;
            color: #007bff;
            margin-right: 6px;
            font-size: 1.05rem;
        }

        .form-check-label {
            cursor: pointer;
        }

        .quiz-progress {
            height: 8px;
            border-radius: 6px;
        }

        .quiz-nav-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            padding: 6px 0 14px 0;
            border-bottom: 1px solid #eef2f7;
            margin-bottom: 16px;
        }

        .quiz-tab {
            min-width: 40px;
            height: 40px;
            border-radius: 8px;
            border: 1px solid #d0d7e2;
            background: #f8fafc;
            color: #475569;
            font-weight: 600;
            cursor: pointer;
            transition: all .15s;
            padding: 0 10px;
        }

        .quiz-tab:hover {
            border-color: #007bff;
            color: #007bff;
        }

        .quiz-tab.filled {
            background: #d4f5df;
            border-color: #28a745;
            color: #1e7e34;
        }

        .quiz-tab.active {
            background: #007bff !important;
            border-color: #007bff !important;
            color: #fff !important;
            box-shadow: 0 3px 8px rgba(0, 123, 255, .35);
        }

        .quiz-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding-top: 14px;
            margin-top: 16px;
            border-top: 1px solid #eef2f7;
            gap: 8px;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(4px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
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
                        <div class="col-sm-6">
                            <h1 class="m-0">Quiz : <?= esc($judul_materi); ?></h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('homepage'); ?>">Dashboard</a></li>
                                <li class="breadcrumb-item"><a href="<?= base_url('all-materi'); ?>">Materi</a></li>
                                <li class="breadcrumb-item active">Quiz</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Informasi</h3>
                                </div>
                                <div class="card-body">
                                    <?php if (!empty($materi_icon)): ?>
                                        <div class="text-center mb-3">
                                            <img src="<?= base_url('assets/img/uploads/materi/' . $materi_icon); ?>" style="max-width:110px;border-radius:8px;">
                                        </div>
                                    <?php endif; ?>
                                    <ul class="list-unstyled mb-0">
                                        <li><i class="fas fa-list-ol text-primary mr-1"></i> Total soal : <strong><?= $jumlah_quiz; ?></strong></li>
                                        <li class="mt-2"><i class="fas fa-info-circle text-warning mr-1"></i> Jawab tiap soal, klik <strong>Next</strong> untuk lanjut.</li>
                                        <li class="mt-2"><i class="fas fa-save text-success mr-1"></i> Jawaban otomatis tersimpan. Aman jika halaman ter-refresh.</li>
                                        <li class="mt-2"><i class="fas fa-hourglass-half text-danger mr-1"></i> Soal essay dinilai manual oleh admin.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-pen"></i> Lembar Jawaban</h3>
                                    <div class="card-tools">
                                        <span class="badge badge-info" id="progress-label">0 / <?= $jumlah_quiz; ?> terjawab</span>
                                    </div>
                                </div>
                                <div class="card-body">

                                    <div class="progress mb-3 quiz-progress">
                                        <div id="progress-bar" class="progress-bar bg-success" style="width:0%"></div>
                                    </div>

                                    <!-- Tabs nomor soal -->
                                    <div class="quiz-nav-tabs" id="quiz-tabs">
                                        <?php $i = 0;
                                        foreach ($data_quiz as $q): ?>
                                            <button type="button" class="quiz-tab" data-index="<?= $i; ?>"><?= $i + 1; ?></button>
                                        <?php $i++;
                                        endforeach; ?>
                                    </div>

                                    <form id="form-quiz">
                                        <input type="hidden" name="id_materi" value="<?= $id_materi; ?>">

                                        <div class="quiz-stage" id="quiz-stage">
                                            <?php $i = 0;
                                            $no = 1;
                                            foreach ($data_quiz as $q): ?>
                                                <div class="quiz-item"
                                                    data-index="<?= $i; ?>"
                                                    data-id="<?= $q->id; ?>"
                                                    data-jenis="<?= $q->jenis; ?>"
                                                    style="display:none;">
                                                    <div class="mb-3">
                                                        <span class="no-soal">Soal <?= $no++; ?>.</span>
                                                        <span class="badge badge-<?= $q->jenis === 'essay' ? 'warning' : 'success'; ?>">
                                                            <?= strtoupper($q->jenis); ?>
                                                        </span>
                                                    </div>
                                                    <div class="mb-3"><?= nl2br(esc($q->pertanyaan)); ?></div>

                                                    <?php if ($q->jenis === 'essay'): ?>
                                                        <textarea class="form-control jawaban" rows="5"
                                                            name="answers[<?= $q->id; ?>]"
                                                            placeholder="Tulis jawaban Anda..."></textarea>

                                                    <?php elseif ($q->jenis === 'pg2'): ?>
                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input jawaban" type="radio" name="answers[<?= $q->id; ?>]" value="A" id="q<?= $q->id; ?>A">
                                                            <label class="form-check-label" for="q<?= $q->id; ?>A"><strong>A.</strong> <?= esc($q->opsi_a); ?></label>
                                                        </div>
                                                        <div class="form-check">
                                                            <input class="form-check-input jawaban" type="radio" name="answers[<?= $q->id; ?>]" value="B" id="q<?= $q->id; ?>B">
                                                            <label class="form-check-label" for="q<?= $q->id; ?>B"><strong>B.</strong> <?= esc($q->opsi_b); ?></label>
                                                        </div>

                                                    <?php elseif ($q->jenis === 'pg4'): ?>
                                                        <?php foreach (['A' => 'opsi_a', 'B' => 'opsi_b', 'C' => 'opsi_c', 'D' => 'opsi_d'] as $key => $field): ?>
                                                            <div class="form-check mb-2">
                                                                <input class="form-check-input jawaban" type="radio" name="answers[<?= $q->id; ?>]" value="<?= $key; ?>" id="q<?= $q->id; ?><?= $key; ?>">
                                                                <label class="form-check-label" for="q<?= $q->id; ?><?= $key; ?>"><strong><?= $key; ?>.</strong> <?= esc($q->$field); ?></label>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                            <?php $i++;
                                            endforeach; ?>
                                        </div>

                                        <!-- Footer navigasi -->
                                        <div class="quiz-footer">
                                            <button type="button" id="btn-prev" class="btn btn-default" style="display:none;">
                                                <i class="fas fa-arrow-left"></i> Back
                                            </button>
                                            <div>
                                                <button type="button" id="btn-next" class="btn btn-primary">
                                                    Next <i class="fas fa-arrow-right"></i>
                                                </button>
                                                <button type="button" id="btn-submit-quiz" class="btn btn-success" style="display:none;">
                                                    <i class="fas fa-paper-plane"></i> SUBMIT JAWABAN
                                                </button>
                                            </div>
                                        </div>
                                    </form>

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
    <script src="<?= base_url(); ?>assets/js/sweetalert2@11.js"></script>
    <script src="<?= base_url(); ?>assets/js/adminlte.js"></script>
    <script>
        const _URL_MAIN_WEBSITE = "<?= base_url(); ?>";
    </script>
    <script src="<?= base_url(); ?>assets/js/start-quiz.js<?= $random; ?>"></script>
</body>

</html>