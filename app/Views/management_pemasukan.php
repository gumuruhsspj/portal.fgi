<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Management Pemasukan (<?= $usertype; ?>) Portal</title>
    <link href="<?= base_url() ?>assets/img/favicon.ico" rel="icon">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/google-fonts.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/styles-custom-portal.css">

    <style>
        /* ====== Responsive tweaks — management pemasukan ====== */
        .content-header h1 {
            font-size: 1.6rem;
        }

        .small-box {
            border-radius: .5rem;
        }

        .small-box h3 {
            font-size: 1.25rem;
            margin: 0 0 .25rem;
        }

        .small-box p {
            font-size: .85rem;
            margin: 0;
        }

        .small-box .icon {
            font-size: 45px;
        }

        .card-header .card-title {
            font-weight: 600;
        }

        .card-header .card-tools {
            margin-top: .5rem;
        }

        .card-body.table-responsive {
            -webkit-overflow-scrolling: touch;
        }

        .table th,
        .table td {
            vertical-align: middle;
            font-size: .9rem;
        }

        .table .col-num {
            white-space: nowrap;
            text-align: right;
        }

        .table .col-idx {
            width: 40px;
        }

        .modal-body label {
            font-weight: 500;
        }

        /* ========== TABLET ========== */
        @media (max-width: 767.98px) {
            .content-header {
                padding: .75rem .75rem 0;
            }

            .content-header h1 {
                font-size: 1.35rem;
            }

            .breadcrumb {
                font-size: .8rem;
                margin-bottom: 0;
            }

            .content {
                padding: .75rem .5rem;
            }

            .small-box h3 {
                font-size: 1.1rem;
            }

            .small-box p {
                font-size: .75rem;
            }

            .small-box .icon {
                font-size: 35px;
                right: .75rem;
                top: .5rem;
            }

            #btn-cairkan {
                width: 100%;
                margin-top: .5rem;
            }

            .card-header .card-tools {
                width: 100%;
                text-align: right;
                margin-top: .5rem;
            }

            .modal-dialog {
                margin: .5rem;
            }

            .modal-body {
                padding: 1rem;
            }

            .modal-footer .btn {
                flex: 1 1 0;
            }

            /* ==========================================
               TABLE -> CARD on mobile
               ========================================== */
            .table-cards {
                border-collapse: separate;
                border-spacing: 0 .5rem;
                margin: 0;
            }

            .table-cards thead {
                display: none;
            }

            .table-cards tbody tr {
                display: block;
                background: #fff;
                border: 1px solid #dee2e6;
                border-radius: .5rem;
                overflow: hidden;
                margin-bottom: .5rem;
                box-shadow: 0 1px 2px rgba(0, 0, 0, .04);
            }

            /* setiap td jadi baris label:value */
            .table-cards tbody tr td,
            .table-cards.table-striped>tbody>tr>td {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                gap: .75rem;
                padding: .5rem .75rem;
                border: 0;
                background: transparent !important;
                font-size: .85rem;
                text-align: right;
                word-break: break-word;
                --bs-table-accent-bg: transparent;
            }

            .table-cards tbody tr td+td {
                border-top: 1px solid #f1f3f5;
            }

            /* label di kiri dari data-label */
            .table-cards tbody tr td::before {
                content: attr(data-label);
                font-weight: 600;
                color: #6c757d;
                text-align: left;
                white-space: nowrap;
                flex: 0 0 auto;
            }

            /* td tanpa data-label (mis. sel # atau empty state) gak dirender labelnya */
            .table-cards tbody tr td:not([data-label])::before {
                content: none;
            }

            /* empty state row — jangan dijadikan flex 2 kolom */
            .table-cards tbody tr td[colspan] {
                justify-content: center;
                text-align: center;
                padding: 1rem .75rem;
            }

            /* biar angka & action rapi di card */
            .table-cards tbody tr td.col-num {
                text-align: right;
            }

            .table-cards tbody tr td .badge {
                font-size: .75rem;
            }
        }

        /* ========== MOBILE KECIL ========== */
        @media (max-width: 575.98px) {

            .btn-approve,
            .btn-reject {
                display: block;
                width: 100%;
                margin-bottom: .25rem;
            }

            .btn-reject:last-child {
                margin-bottom: 0;
            }

            /* Actions cell di card: full width & rapi */
            .table-cards tbody tr td[data-label="Actions"] {
                flex-direction: column;
                align-items: stretch;
                gap: .35rem;
            }

            .table-cards tbody tr td[data-label="Actions"]::before {
                text-align: left;
                margin-bottom: .15rem;
            }

            .small-box h3 {
                font-size: 1rem;
            }

            .small-box .icon {
                font-size: 30px;
                right: .5rem;
            }
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
                        <div class="col-sm-6 col-12">
                            <h1 class="m-0">Pemasukan</h1>
                        </div>
                        <div class="col-sm-6 col-12">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Management</a></li>
                                <li class="breadcrumb-item"><a href="#">Pemasukan</a></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">

                    <?php if ($view_mode === 'instruktur'): ?>
                        <!-- ============ INSTRUKTUR ============ -->
                        <div class="row">
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="small-box bg-warning">
                                    <div class="inner">
                                        <h3>Rp <?= number_format($summary->total_pending, 0, ',', '.') ?></h3>
                                        <p>Pending</p>
                                    </div>
                                    <div class="icon"><i class="fas fa-hourglass-half"></i></div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="small-box bg-info">
                                    <div class="inner">
                                        <h3>Rp <?= number_format($summary->total_cair_request, 0, ',', '.') ?></h3>
                                        <p>Menunggu Approve</p>
                                    </div>
                                    <div class="icon"><i class="fas fa-paper-plane"></i></div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="small-box bg-success">
                                    <div class="inner">
                                        <h3>Rp <?= number_format($summary->total_approved, 0, ',', '.') ?></h3>
                                        <p>Sudah Dicairkan</p>
                                    </div>
                                    <div class="icon"><i class="fas fa-check-circle"></i></div>
                                </div>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-3">
                                <div class="small-box bg-primary">
                                    <div class="inner">
                                        <h3>Rp <?= number_format($summary->total_all, 0, ',', '.') ?></h3>
                                        <p>Total Pemasukan</p>
                                    </div>
                                    <div class="icon"><i class="fas fa-wallet"></i></div>
                                </div>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title d-block"><i class="fas fa-coins"></i> Pemasukan dari Student</h3>
                                <div class="card-tools">
                                    <button id="btn-cairkan" class="btn btn-sm btn-primary"
                                        <?= ($summary->total_pending <= 0 ? 'disabled' : '') ?>>
                                        <i class="fas fa-hand-holding-usd"></i> Cairkan
                                    </button>
                                </div>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-striped table-cards">
                                    <thead>
                                        <tr>
                                            <th class="col-idx">#</th>
                                            <th>Student</th>
                                            <th>Materi</th>
                                            <th class="col-num">Bruto</th>
                                            <th class="col-num">Komisi</th>
                                            <th class="col-num">Untuk Anda</th>
                                            <th>Status</th>
                                            <th class="col-num">Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($pemasukan_list)): $n = 1;
                                            foreach ($pemasukan_list as $r): ?>
                                                <tr>
                                                    <td class="col-idx" data-label="#"><?= $n++ ?></td>
                                                    <td data-label="Student"><?= htmlspecialchars($r->nama_student ?? '-') ?></td>
                                                    <td data-label="Materi"><?= htmlspecialchars($r->judul_materi ?? '-') ?></td>
                                                    <td class="col-num" data-label="Bruto">Rp <?= number_format($r->nominal_bruto, 0, ',', '.') ?></td>
                                                    <td class="col-num" data-label="Komisi"><?= (float)$r->komisi_persen ?>%</td>
                                                    <td class="col-num" data-label="Untuk Anda"><b>Rp <?= number_format($r->nominal_instruktur, 0, ',', '.') ?></b></td>
                                                    <td data-label="Status">
                                                        <?php if ($r->status === 'pending'): ?><span class="badge bg-warning">Pending</span>
                                                        <?php elseif ($r->status === 'cair_request'): ?><span class="badge bg-info">Cair Request</span>
                                                        <?php else: ?><span class="badge bg-success">Approved</span><?php endif; ?>
                                                    </td>
                                                    <td class="col-num" data-label="Tanggal"><?= $r->date_created ?></td>
                                                </tr>
                                            <?php endforeach;
                                        else: ?>
                                            <tr>
                                                <td colspan="8" class="text-center text-muted">Belum ada pemasukan.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-history"></i> Riwayat Pencairan</h3>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-striped table-cards">
                                    <thead>
                                        <tr>
                                            <th class="col-idx">#</th>
                                            <th class="col-num">Request</th>
                                            <th class="col-num">Approved</th>
                                            <th>Status</th>
                                            <th class="col-num">Tgl Request</th>
                                            <th class="col-num">Tgl Approved</th>
                                            <th>Admin</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($pencairan_list)): $n = 1;
                                            foreach ($pencairan_list as $r): ?>
                                                <tr>
                                                    <td class="col-idx" data-label="#"><?= $n++ ?></td>
                                                    <td class="col-num" data-label="Request">Rp <?= number_format($r->nominal_request, 0, ',', '.') ?></td>
                                                    <td class="col-num" data-label="Approved"><?= $r->nominal_approved !== null ? 'Rp ' . number_format($r->nominal_approved, 0, ',', '.') : '-' ?></td>
                                                    <td data-label="Status">
                                                        <?php if ($r->status === 'pending'): ?><span class="badge bg-warning">Pending</span>
                                                        <?php elseif ($r->status === 'approved'): ?><span class="badge bg-success">Approved</span>
                                                        <?php else: ?><span class="badge bg-danger">Rejected</span><?php endif; ?>
                                                    </td>
                                                    <td class="col-num" data-label="Tgl Request"><?= $r->tanggal_request ?></td>
                                                    <td class="col-num" data-label="Tgl Approved"><?= $r->tanggal_approved ?? '-' ?></td>
                                                    <td data-label="Admin"><?= htmlspecialchars($r->nama_admin ?? '-') ?></td>
                                                </tr>
                                            <?php endforeach;
                                        else: ?>
                                            <tr>
                                                <td colspan="7" class="text-center text-muted">Belum ada riwayat pencairan.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    <?php else: ?>
                        <!-- ============ ADMIN ============ -->
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-inbox"></i> Request Pencairan Pending</h3>
                            </div>
                            <div class="card-body table-responsive p-0">
                                <table class="table table-striped table-cards">
                                    <thead>
                                        <tr>
                                            <th class="col-idx">#</th>
                                            <th>Instruktur</th>
                                            <th>WA</th>
                                            <th class="col-num">Nominal Request</th>
                                            <th class="col-num">Tgl Request</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (!empty($pending_pencairan)): $n = 1;
                                            foreach ($pending_pencairan as $r): ?>
                                                <tr>
                                                    <td class="col-idx" data-label="#"><?= $n++ ?></td>
                                                    <td data-label="Instruktur"><?= htmlspecialchars($r->nama_instruktur ?? '-') ?><br>
                                                        <small class="text-muted"><?= htmlspecialchars($r->username_instruktur ?? '') ?></small>
                                                    </td>
                                                    <td data-label="WA"><?= htmlspecialchars($r->wa_instruktur ?? '-') ?></td>
                                                    <td class="col-num" data-label="Nominal Request"><b>Rp <?= number_format($r->nominal_request, 0, ',', '.') ?></b></td>
                                                    <td class="col-num" data-label="Tgl Request"><?= $r->tanggal_request ?></td>
                                                    <td data-label="Actions">
                                                        <button class="btn btn-sm btn-success btn-approve"
                                                            data-id="<?= $r->id ?>"
                                                            data-nominal="<?= $r->nominal_request ?>">
                                                            <i class="fas fa-check"></i> Approve
                                                        </button>
                                                        <button class="btn btn-sm btn-danger btn-reject"
                                                            data-id="<?= $r->id ?>">
                                                            <i class="fas fa-times"></i> Reject
                                                        </button>
                                                    </td>
                                                </tr>
                                            <?php endforeach;
                                        else: ?>
                                            <tr>
                                                <td colspan="6" class="text-center text-muted">Tidak ada request pending.</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>

                </div>
            </div>
        </div>

        <aside class="control-sidebar control-sidebar-dark"></aside>
        <?php include('footer.php'); ?>
    </div>

    <!-- Modal Approve -->
    <div class="modal fade" id="modalApprove" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Approve Pencairan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="approve-id">
                    <div class="mb-2">
                        <label>Nominal yang ditransfer (Rp)</label>
                        <input type="number" id="approve-nominal" class="form-control" min="1" required>
                        <small class="text-muted">Boleh kurang dari request (sisa akan kembali ke pending).</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-success" id="btn-do-approve">Approve</button>
                </div>
            </div>
        </div>
    </div>

    <script src="<?= base_url() ?>assets/js/jquery371.min.js"></script>
    <script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript">
        const _URL_MAIN_WEBSITE = "<?= base_url(); ?>";
    </script>
    <script src="<?= base_url() ?>assets/js/manage-pemasukan.js<?= $random; ?>"></script>
    <script src="<?= base_url() ?>assets/js/adminlte.js<?= $random; ?>"></script>
</body>

</html>