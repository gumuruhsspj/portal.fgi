<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Management Pembayaran (<?= $usertype; ?>) Portal - Kursus Komputer</title>
  <link href="<?= base_url() ?>assets/img/favicon.ico" rel="icon">
  <link href="<?= base_url() ?>assets/img/favicon.ico" rel="apple-touch-icon">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/google-fonts.css">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/datatables/dataTables.dataTables.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap/css/bootstrap.min.css">

  <link rel="stylesheet" href="<?= base_url() ?>assets/css/adminlte.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/styles-custom-homepage.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/styles-custom-portal.css">

  <style>
    /* ====== Responsive tweaks — management pembayaran ====== */
    .content-header h1 {
      font-size: 1.6rem;
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

    /* Card header — biar dua card-title (Pendapatan + Total) rapi di HP */
    .card-header .card-title {
      display: block;
      font-weight: 600;
      line-height: 1.5;
    }

    .card-header .card-title+.card-title {
      margin-top: .15rem;
    }

    .card-header .card-tools {
      margin-top: .5rem;
    }

    /* ========== TABLET / MOBILE ========== */
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

      /* Card tools: tombol turun ke bawah, rata kanan */
      .card-header .card-tools {
        width: 100%;
        text-align: right;
        margin-top: .5rem;
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

      .table-cards tbody tr td::before {
        content: attr(data-label);
        font-weight: 600;
        color: #6c757d;
        text-align: left;
        white-space: nowrap;
        flex: 0 0 auto;
        max-width: 45%;
      }

      .table-cards tbody tr td:not([data-label])::before {
        content: none;
      }

      /* Checkbox cell: sembunyikan di mobile (bulk-select gak praktis di HP) */
      .table-cards thead tr th.col-check,
      .table-cards tbody tr td.col-check {
        display: none;
      }

      /* Empty state */
      .table-cards tbody tr td[colspan] {
        justify-content: center;
        text-align: center;
        padding: 1rem .75rem;
      }

      .table-cards tbody tr td.col-num {
        text-align: right;
      }

      .table-cards tbody tr td .badge {
        font-size: .75rem;
      }

      /* Actions: stack vertical full-width */
      .table-cards tbody tr td[data-label="Actions"] {
        flex-direction: column;
        align-items: stretch;
        gap: .35rem;
      }

      .table-cards tbody tr td[data-label="Actions"]::before {
        text-align: left;
        margin-bottom: .15rem;
      }

      .table-cards tbody tr td[data-label="Actions"] .btn-group {
        display: flex;
        width: 100%;
      }

      .table-cards tbody tr td[data-label="Actions"] .btn-group .btn {
        flex: 1 1 0;
      }
    }

    @media (max-width: 575.98px) {
      .card-header .card-tools .btn {
        margin-left: .15rem;
      }
    }
  </style>
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php include('nav_menu_upper.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <?php include('brand_logo.php'); ?>
      <div class="sidebar">
        <?php include('nav_menu_admin.php'); ?>
      </div>
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6 col-12">
              <h1 class="m-0">Pembayaran</h1>
            </div>
            <div class="col-sm-6 col-12">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Management</a></li>
                <li class="breadcrumb-item"><a href="#">Pembayaran</a></li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">

          <div class="row">
            <div class="col">
              <div class="card">
                <div class="card-header border-0">
                  <h3 class="card-title">
                    <i class="fas fa-wallet"></i> Pendapatan : <span><?= as_rupiah($saldo); ?></span>
                  </h3>
                  <h3 class="card-title">
                    Total Overall: <span><?= (isset($management_data) && $management_data != false) ? count($management_data) : 0; ?> data.</span>
                  </h3>

                  <div class="card-tools">
                    <?php if ($usertype === 'admin'): ?>
                      <a href="#" data-bs-toggle="modal" data-bs-target="#materiModal" class="btn btn-tool btn-sm">
                        <i class="fas fa-plus"></i>
                      </a>
                      <a href="#" id="delete-selected" class="btn btn-tool btn-sm">
                        <i class="fas fa-window-close"></i>
                      </a>
                    <?php endif; ?>
                    <a href="#" id="refresh-data" class="btn btn-tool btn-sm">
                      <i class="fas fa-random"></i>
                    </a>
                  </div>
                </div>

                <div class="card-body table-responsive p-0">
                  <table id="table-management-pembayaran" class="table table-striped table-valign-middle table-cards">
                    <thead>
                      <tr>
                        <th class="col-check"><input type="checkbox" id="select-all" class="data-selected"></th>
                        <th>Nama User</th>
                        <th>Saldo Sebelum</th>
                        <th>Nominal</th>
                        <th>Saldo Setelah</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Date</th>
                        <?php if ($usertype === 'admin'): ?>
                          <th>Actions</th>
                        <?php endif; ?>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (isset($management_data) && !empty($management_data)): ?>
                        <?php foreach ($management_data as $row): ?>
                          <tr>
                            <td class="col-check">
                              <input type="checkbox" class="data-selected" data-id="<?= $row->id; ?>">
                            </td>

                            <td data-label="Nama User"><?= $row->nama_lengkap; ?></td>
                            <td class="col-num" data-label="Saldo Sebelum"><?= number_format($row->saldo_sebelum); ?></td>
                            <td class="col-num" data-label="Nominal"><b><?= number_format($row->nominal); ?></b></td>
                            <td class="col-num" data-label="Saldo Setelah"><?= number_format($row->saldo_setelah); ?></td>
                            <td data-label="Jenis"><?= $row->jenis; ?></td>

                            <td data-label="Status">
                              <?php if ($row->status == 'pending'): ?>
                                <span class="badge bg-warning">Pending</span>
                              <?php elseif ($row->status == 'approved'): ?>
                                <span class="badge bg-success">Approved</span>
                              <?php else: ?>
                                <span class="badge bg-danger">Cancelled</span>
                              <?php endif; ?>
                            </td>

                            <td data-label="Keterangan"><?= $row->keterangan; ?></td>
                            <td class="col-num" data-label="Date"><?= $row->date_created; ?></td>

                            <td data-label="Actions">
                              <div class="btn-group">
                                <?php if ($row->status == 'pending'): ?>
                                  <button class="btn btn-success btn-sm approve-btn" data-status="approved" data-id="<?= $row->id; ?>">✔</button>
                                  <button class="btn btn-danger btn-sm cancel-btn" data-status="cancelled" data-id="<?= $row->id; ?>">✖</button>
                                <?php else: ?>
                                  <?php if ($usertype === 'admin'): ?>
                                    <button class="btn btn-danger btn-sm delete-btn" data-status="deleted" data-id="<?= $row->id; ?>">✖</button>
                                  <?php endif; ?>
                                <?php endif; ?>
                              </div>
                            </td>
                          </tr>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

    <aside class="control-sidebar control-sidebar-dark"></aside>
    <?php include('footer.php'); ?>
  </div>

  <?php include('modal_customer_services.php'); ?>
  <?php include('modal_comments_rating.php'); ?>
  <?php include('modal_usulan_materi.php'); ?>

  <script src="<?= base_url() ?>assets/js/jquery371.min.js"></script>
  <script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url() ?>assets/vendor/datatables/jquery.dataTables.min.js"></script>
  <script type="text/javascript">
    const _URL_MAIN_WEBSITE = "<?= base_url(); ?>";
  </script>

  <script src="<?= base_url() ?>assets/js/manage-pembayaran.js<?= $random; ?>"></script>
  <script src="<?= base_url() ?>assets/js/settings.js<?= $random; ?>"></script>
  <script src="<?= base_url() ?>assets/js/customer-services.js<?= $random; ?>"></script>
  <script src="<?= base_url() ?>assets/js/timer.js"></script>
  <script src="<?= base_url() ?>assets/js/adminlte.js<?= $random; ?>"></script>

</body>

</html>