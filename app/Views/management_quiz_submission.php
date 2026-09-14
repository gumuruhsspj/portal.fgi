<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Quiz Submission (<?= $usertype ?? ''; ?>) Portal - Kursus Komputer</title>
    <link href="<?= base_url(); ?>assets/img/favicon.ico" rel="icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/datatables/dataTables.dataTables.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/styles-custom-homepage.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/styles-custom-portal.css">

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
                            <h1 class="m-0">Quiz Submission</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Management</a></li>
                                <li class="breadcrumb-item active">Quiz Submission</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">

                    <div class="card card-primary card-outline">
                        <div class="card-header">
                            <h3 class="card-title">
                                <i class="fas fa-filter"></i> Filter by Materi
                            </h3>
                            <div class="card-tools">
                                <span class="badge badge-info">
                                    Total: <?= is_array($submissions) ? count($submissions) : 0; ?> submission
                                </span>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <select id="filter-materi" class="form-control">
                                        <option value="">— Semua Materi —</option>
                                        <?php foreach ($materi_filter as $m): ?>
                                            <option value="<?= $m->id_materi; ?>" <?= ($selected_materi == $m->id_materi) ? 'selected' : ''; ?>>
                                                <?= esc($m->judul_materi); ?> (<?= $m->total_submissions; ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <div class="col-md-6 text-right">
                                    <a href="<?= base_url('manage/quiz-submission'); ?>" class="btn btn-default btn-sm">
                                        <i class="fas fa-undo"></i> Reset
                                    </a>
                                    <a href="#" id="refresh-data" class="btn btn-default btn-sm">
                                        <i class="fas fa-random"></i> Refresh
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-body table-responsive p-0">
                            <table id="table-quiz-submission" class="table table-striped table-valign-middle">
                                <thead>
                                    <tr>
                                        <th style="width:40px;">#</th>
                                        <th>User</th>
                                        <th>Materi</th>
                                        <th class="text-center">Soal</th>
                                        <th class="text-center">Auto Score</th>
                                        <th class="text-center">Status</th>
                                        <th class="text-center">Final</th>
                                        <th>Submitted</th>
                                        <th style="width:130px;"></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($submissions)): ?>
                                        <?php $no = 1;
                                        foreach ($submissions as $s): ?>
                                            <?php
                                            $is_graded = ($s->status === 'graded');
                                            $pg  = (int) $s->pg_questions;
                                            $ess = (int) $s->essay_questions;
                                            ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td>
                                                    <strong><?= esc($s->nama_lengkap ?: $s->username); ?></strong><br>
                                                    <small class="text-muted"><?= esc($s->email); ?></small>
                                                </td>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <?php if (!empty($s->icon)): ?>
                                                            <img src="<?= base_url('assets/img/uploads/materi/' . $s->icon); ?>"
                                                                style="width:32px;height:32px;object-fit:cover;border-radius:6px;margin-right:8px;">
                                                        <?php endif; ?>
                                                        <?= esc($s->judul_materi); ?>
                                                    </div>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($pg > 0): ?>
                                                        <span class="badge badge-success"><?= $pg; ?> PG</span>
                                                    <?php endif; ?>
                                                    <?php if ($ess > 0): ?>
                                                        <span class="badge badge-warning"><?= $ess; ?> Essay</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <strong><?= number_format((float) $s->auto_score, 0); ?></strong>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($is_graded): ?>
                                                        <span class="badge badge-success"><i class="fas fa-check"></i> Dinilai</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning"><i class="fas fa-hourglass-half"></i> Pending</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($is_graded): ?>
                                                        <span class="badge badge-info" style="font-size:.95rem;">
                                                            <?= number_format((float) $s->final_score, 0); ?>
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-muted">—</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <small><?= date('d M Y', strtotime($s->date_created)); ?><br>
                                                        <span class="text-muted"><?= date('H:i', strtotime($s->date_created)); ?></span>
                                                    </small>
                                                </td>
                                                <td class="text-center">
                                                    <a href="<?= base_url('manage/quiz-submission/quiz?id=' . $s->id_materi . '&id_user=' . $s->id_user); ?>"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="fas fa-search"></i> Review
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="9" class="text-center text-muted p-4">
                                                <i class="fas fa-inbox fa-2x mb-2"></i>
                                                <p class="mb-0">Belum ada submission.</p>
                                            </td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <?php include('footer.php'); ?>
    </div>

    <script src="<?= base_url(); ?>assets/js/jquery371.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/sweetalert2@11.js"></script>
    <script src="<?= base_url(); ?>assets/js/adminlte.js"></script>
    <script>
        const _URL_MAIN_WEBSITE = "<?= base_url(); ?>";
    </script>
    <script src="<?= base_url(); ?>assets/js/manage-quiz-submission.js<?= $random; ?>"></script>
</body>

</html>