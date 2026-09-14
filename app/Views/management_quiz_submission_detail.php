<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Review Quiz - <?= esc($data_user->nama_lengkap ?? ''); ?></title>
    <link href="<?= base_url(); ?>assets/img/favicon.ico" rel="icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/styles-custom-homepage.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/styles-custom-portal.css">
    <style>
        .answer-card {
            border: 1px solid #e3e8ef;
            border-radius: 10px;
            padding: 18px;
            margin-bottom: 14px;
            background: #fff;
        }

        .answer-correct {
            border-left: 5px solid #28a745;
            background: #f5fff8;
        }

        .answer-wrong {
            border-left: 5px solid #dc3545;
            background: #fff5f5;
        }

        .answer-essay {
            border-left: 5px solid #ffc107;
            background: #fffdf4;
        }

        .kunci-badge {
            font-weight: 700;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">

        <?php include('nav_menu_upper.php'); ?>

        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <?php include('brand_logo.php'); ?>
            <div class="sidebar"><?php include('nav_menu_admin.php'); ?></div>
        </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0">Review Quiz</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="<?= base_url('manage/quiz-submission'); ?>">Quiz Submission</a></li>
                                <li class="breadcrumb-item active">Review</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">

                    <div class="row">
                        <div class="col-md-4">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h3 class="card-title">Info Peserta</h3>
                                </div>
                                <div class="card-body">
                                    <p class="mb-1"><strong><?= esc($data_user->nama_lengkap ?? '-'); ?></strong></p>
                                    <p class="mb-1 text-muted small"><?= esc($data_user->email ?? '-'); ?></p>
                                    <p class="mb-3 text-muted small">@<?= esc($data_user->username ?? '-'); ?></p>
                                    <hr>
                                    <p class="mb-1"><strong>Materi:</strong><br><?= esc($data_materi->judul ?? '-'); ?></p>
                                    <p class="mb-1 mt-2">
                                        <span class="badge badge-success"><?= (int)$attempt->pg_questions; ?> PG</span>
                                        <span class="badge badge-warning"><?= (int)$attempt->essay_questions; ?> Essay</span>
                                    </p>
                                    <p class="mb-1 mt-2">
                                        Auto Score PG: <strong><?= number_format((float)$attempt->auto_score, 0); ?></strong>
                                    </p>
                                    <p class="mb-0">
                                        Status:
                                        <?php if ($attempt->status === 'graded'): ?>
                                            <span class="badge badge-success">Dinilai</span>
                                        <?php else: ?>
                                            <span class="badge badge-warning">Pending</span>
                                        <?php endif; ?>
                                    </p>
                                </div>
                                <div class="card-footer">
                                    <a href="<?= base_url('manage/quiz-submission'); ?>" class="btn btn-default btn-block">
                                        <i class="fas fa-arrow-left"></i> Kembali
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-8">
                            <div class="card card-primary card-outline">
                                <div class="card-header">
                                    <h3 class="card-title"><i class="fas fa-list-check"></i> Detail Jawaban</h3>
                                </div>
                                <div class="card-body" style="max-height: 70vh; overflow-y:auto;">

                                    <form id="form-grading">
                                        <input type="hidden" name="attempt_id" value="<?= $attempt->id; ?>">

                                        <?php if (!empty($data_answers)): ?>
                                            <?php $n = 1;
                                            foreach ($data_answers as $a): ?>
                                                <?php
                                                $is_essay = ($a->jenis === 'essay');
                                                $cls = 'answer-essay';
                                                $lbl = '<span class="badge badge-warning">Essay — Perlu Penilaian</span>';
                                                if (!$is_essay) {
                                                    if ((int)$a->is_correct === 1) {
                                                        $cls = 'answer-correct';
                                                        $lbl = '<span class="badge badge-success">Benar</span>';
                                                    } else {
                                                        $cls = 'answer-wrong';
                                                        $lbl = '<span class="badge badge-danger">Salah</span>';
                                                    }
                                                }
                                                ?>
                                                <div class="answer-card <?= $cls; ?>">
                                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                                        <strong>Soal <?= $n++; ?>.</strong>
                                                        <?= $lbl; ?>
                                                    </div>
                                                    <div class="mb-2 small text-muted"><?= nl2br(esc($a->pertanyaan ?? '')); ?></div>

                                                    <?php if (!$is_essay): ?>
                                                        <?php
                                                        $opsi_map = ['A' => $a->opsi_a, 'B' => $a->opsi_b, 'C' => $a->opsi_c, 'D' => $a->opsi_d];
                                                        $kunci = strtoupper((string) $a->final_answer);
                                                        $pilihan_user = strtoupper((string) $a->jawaban);
                                                        ?>
                                                        <ul class="list-unstyled mb-2">
                                                            <?php foreach ($opsi_map as $k => $v): ?>
                                                                <?php if (!empty($v)): ?>
                                                                    <li class="<?= $k === $kunci ? 'text-success kunci-badge' : '' ?>">
                                                                        <strong><?= $k; ?>.</strong> <?= esc($v); ?>
                                                                        <?php if ($k === $pilihan_user): ?>
                                                                            <span class="badge badge-info ml-1">← Jawaban User</span>
                                                                        <?php endif; ?>
                                                                        <?php if ($k === $kunci): ?>
                                                                            <span class="badge badge-success ml-1">Kunci</span>
                                                                        <?php endif; ?>
                                                                    </li>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        </ul>

                                                    <?php else: ?>
                                                        <div class="mb-2">
                                                            <strong>Jawaban User:</strong>
                                                            <div class="p-2 bg-white border rounded"><?= nl2br(esc($a->jawaban ?: '—')); ?></div>
                                                        </div>

                                                        <?php if (!empty($a->keterangan)): ?>
                                                            <div class="small text-muted mb-2">
                                                                <em>Keterangan pembuat soal: <?= esc($a->keterangan); ?></em>
                                                            </div>
                                                        <?php endif; ?>

                                                        <div class="form-row">
                                                            <div class="col-md-3">
                                                                <label class="small mb-1">Skor (0-100)</label>
                                                                <input type="number" min="0" max="100"
                                                                    class="form-control form-control-sm input-score"
                                                                    name="scores[<?= $a->id; ?>]"
                                                                    value="<?= $a->score !== null ? (int)$a->score : ''; ?>"
                                                                    placeholder="0">
                                                            </div>
                                                            <div class="col-md-9">
                                                                <label class="small mb-1">Catatan Admin</label>
                                                                <input type="text"
                                                                    class="form-control form-control-sm"
                                                                    name="catatan[<?= $a->id; ?>]"
                                                                    value="<?= esc($a->catatan_admin ?? ''); ?>"
                                                                    placeholder="Catatan untuk user (opsional)">
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <p class="text-muted">Tidak ada jawaban.</p>
                                        <?php endif; ?>
                                    </form>

                                </div>
                                <div class="card-footer text-right">
                                    <button type="button" id="btn-save-grading" class="btn btn-success btn-lg">
                                        <i class="fas fa-check"></i> Simpan Penilaian
                                    </button>
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
    <script src="<?= base_url(); ?>assets/js/manage-quiz-submission-detail.js<?= $random; ?>"></script>
</body>

</html>