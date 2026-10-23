<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Management User (<?= $usertype; ?>) Portal - Kursus Komputer</title>
  <link href="<?= base_url() ?>assets/img/favicon.ico" rel="icon">
  <link href="<?= base_url() ?>assets/img/favicon.ico" rel="apple-touch-icon">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
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
    /* ====== Responsive tweaks — management user ====== */
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

    .table .col-check {
      width: 40px;
      text-align: center;
    }

    .card-header .card-title {
      font-weight: 600;
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

      /* Sembunyikan kolom checkbox di HP (bulk select gak praktis di mobile) */
      .table-cards thead tr th.col-check,
      .table-cards tbody tr td.col-check {
        display: none;
      }

      /* Cell "Nama Lengkap" punya img + text — rapiin vertikal */
      .table-cards tbody tr td[data-label="Nama Lengkap"] {
        align-items: center;
      }

      .table-cards tbody tr td[data-label="Nama Lengkap"] img {
        margin-right: .4rem !important;
      }

      /* Tombol Show password: jangan bikin baris aneh */
      .table-cards tbody tr td[data-label="Password"] a.toggle-pass {
        text-decoration: none;
      }

      /* Actions cell — full width */
      .table-cards tbody tr td[data-label="Actions"] {
        flex-direction: column;
        align-items: stretch;
        gap: .35rem;
      }

      .table-cards tbody tr td[data-label="Actions"]::before {
        text-align: left;
        margin-bottom: .15rem;
      }

      .table-cards tbody tr td[data-label="Actions"] .dropdown {
        width: 100%;
      }

      .table-cards tbody tr td[data-label="Actions"] .dropdown>button {
        width: 100%;
        text-align: left;
      }

      .table-cards tbody tr td[data-label="Actions"] .dropdown>button::after {
        float: right;
        margin-top: .5rem;
      }

      .table-cards tbody tr td[data-label="Actions"] .dropdown-menu {
        width: 100%;
        position: static !important;
        transform: none !important;
      }

      /* Empty state */
      .table-cards tbody tr td[colspan] {
        justify-content: center;
        text-align: center;
        padding: 1rem .75rem;
      }

      /* DataTables controls — biar rapi di HP */
      .dataTables_wrapper .dataTables_filter,
      .dataTables_wrapper .dataTables_length,
      .dataTables_wrapper .dataTables_info,
      .dataTables_wrapper .dataTables_paginate {
        text-align: center;
        margin: .35rem 0;
        float: none;
      }

      .dataTables_wrapper .dataTables_filter input {
        width: 100%;
        margin-left: 0 !important;
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
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6 col-12">
              <h1 class="m-0">User</h1>
            </div>
            <div class="col-sm-6 col-12">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Management</a></li>
                <li class="breadcrumb-item"><a href="#">User</a></li>
              </ol>
            </div>
          </div>
        </div>
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">

          <div class="row">
            <div class="col">
              <div class="card">
                <div class="card-header border-0">
                  <h3 class="card-title">Total Overall: <span><?= (isset($management_data) && $management_data != false) ? count($management_data) : 0; ?> data.</span></h3>
                  <div class="card-tools">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#userModal" class="btn btn-tool btn-sm">
                      <i class="fas fa-plus"></i>
                    </a>
                    <a href="#" id="delete-selected" class="btn btn-tool btn-sm">
                      <i class="fas fa-window-close"></i>
                    </a>
                    <a href="#" id="refresh-data" class="btn btn-tool btn-sm">
                      <i class="fas fa-random"></i>
                    </a>
                  </div>
                </div>
                <div class="card-body table-responsive p-0">
                  <table id="table-management-user" class="table table-striped table-valign-middle table-cards">
                    <thead>
                      <tr>
                        <th class="col-check"><input type="checkbox" id="select-all" class="data-selected"></th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Whatsapp</th>
                        <th>Usertype</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (isset($management_data) && !empty($management_data)): ?>
                        <?php foreach ($management_data as $row): ?>
                          <tr>
                            <td class="col-check">
                              <?php if ($row->username != 'admin'): ?>
                                <input type="checkbox" class="data-selected" data-id="<?= $row->id; ?>">
                              <?php endif; ?>
                            </td>
                            <td data-label="Nama Lengkap">
                              <img src="<?= base_url(); ?>assets/img/uploads/propic/<?= $row->propic; ?>" alt="Profile Picture" class="img-circle img-size-32 mr-2">
                              <?= $row->nama_lengkap; ?>
                            </td>
                            <td data-label="Username"><?= $row->username; ?></td>
                            <td data-label="Email"><?= $row->email; ?></td>
                            <td data-label="Password"><a class="toggle-pass" href="#" data-status="hide" data-value="<?= $row->pass; ?>">Show</a></td>
                            <td data-label="Whatsapp"><?= $row->whatsapp; ?></td>
                            <td data-label="Usertype"><?= $row->usertype; ?></td>

                            <td data-label="Actions">
                              <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">

                                </button>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                  <li><a class="dropdown-item edit-single" data-id="<?= $row->id; ?>" href="#">Edit</a></li>
                                  <?php if ($row->username != 'admin'): ?>
                                    <li><a class="dropdown-item delete-single" data-id="<?= $row->id; ?>" href="#">Delete</a></li>
                                  <?php endif; ?>
                                </ul>
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
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark"></aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <?php include('footer.php'); ?>
  </div>
  <!-- ./wrapper -->
  <?php include('modal_users.php'); ?>
  <?php include('modal_customer_services.php'); ?>
  <?php include('modal_usulan_materi.php'); ?>

  <!-- REQUIRED SCRIPTS -->
  <script src="<?= base_url() ?>assets/js/jquery371.min.js"></script>
  <script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url() ?>assets/vendor/datatables/jquery.dataTables.min.js"></script>

  <script type="text/javascript">
    const _URL_MAIN_WEBSITE = "<?= base_url(); ?>";
  </script>

  <script src="<?= base_url() ?>assets/js/manage-user.js"></script>
  <script src="<?= base_url() ?>assets/js/settings.js"></script>
  <script src="<?= base_url() ?>assets/js/customer-services.js"></script>
  <script src="<?= base_url() ?>assets/js/timer.js"></script>
  <script src="<?= base_url() ?>assets/js/adminlte.js"></script>

</body>

</html>