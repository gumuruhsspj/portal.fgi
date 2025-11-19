<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Management Riwayat Saldo (<?= $usertype; ?>) Portal - Kursus Komputer</title>

  <link href="/assets/img/favicon.ico" rel="icon">
  <link href="/assets/img/favicon.ico" rel="apple-touch-icon">

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="/assets/vendor/fontawesome-free/css/all.min.css">

  <!-- DataTables -->
  <link rel="stylesheet" href="/assets/vendor/datatables/dataTables.dataTables.min.css">

  <!-- Bootstrap -->
  <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css">

  <!-- Theme style -->
  <link rel="stylesheet" href="/assets/css/adminlte.min.css">
  <link rel="stylesheet" href="/assets/css/styles-custom-homepage.css">
  <link rel="stylesheet" href="/assets/css/styles-custom-portal.css">
</head>

<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <?php include('nav_menu_upper.php'); ?>

  <!-- Sidebar -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
      <img src="/assets/img/fgroupindonesia.jpg" class="brand-image img-circle elevation-3" alt="Logo">
      <span class="brand-text font-weight-light">FGroupIndonesia</span>
    </a>

    <div class="sidebar">
      <?php if(session()->get('usertype') == 'peserta'): ?>
        <?php include('nav_menu_user.php'); ?>
        <?php else : ?>
          <?php include('nav_menu_admin.php'); ?>
      <?php endif; ?>
    </div>
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    
    <!-- Page Header -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
        <div class="col-sm-6">

<h1 class="fw-bold mb-2">Riwayat Saldo</h1>

<div class="d-flex align-items-center mt-3">
    <i class="fas fa-wallet fa-2x me-3 text-warning"></i>

    <div>
        <div class="text-muted small">Saldo Terkini</div>
        <div class="fw-bold fs-3" id="balance">
            <?= as_rupiah(session()->get('balance')); ?>
        </div>
    </div>
</div>

</div>

          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Management</a></li>
              <li class="breadcrumb-item active">Riwayat Saldo</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <!-- Main content -->
    <div class="content">
      <div class="container-fluid">

        <div class="card">
          <div class="card-header border-0">
            <h3 class="card-title">
              Total Overall: 
              <span><?= isset($management_data) ? count($management_data) : 0; ?> data.</span>
            </h3>

            <div class="card-tools">
            <?php if(session()->get('usertype') == 'admin'): ?>
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
            <table id="table-history-saldo" class="table table-striped table-valign-middle">
              <thead>
                <tr>
                <?php if(session()->get('usertype') == 'admin'): ?>
                  <th><input type="checkbox" id="select-all"></th>
                <?php endif; ?>
                  <th>No.</th>
                  <th>Status</th>
                  <th>Jenis</th>
                  <th>Keterangan</th>
                  <th>Saldo Sebelum</th>
                  <th>Nominal</th>
                  <th>Saldo Setelah</th>
                  <?php if(session()->get('usertype') == 'admin'): ?>
                  <th>Username</th>
                  <?php endif; ?>
                  <th>Date Created</th>
                  <?php if(session()->get('usertype') == 'admin'): ?>
                  <th>Actions</th>
                  <?php endif; ?>
                </tr>
              </thead>

              <tbody>
                <?php if(isset($management_data) && !empty($management_data)): ?>
                <?php foreach($management_data as $row): ?>
                <tr>
                <?php if(session()->get('usertype') == 'admin'): ?>
                  <td><input type="checkbox" class="data-selected" data-id="<?= $row->id; ?>"></td>
                <?php endif; ?>
                <td><?= $nomer++; ?></td>
                  <td><?= $row->status; ?></td>
                  <td><?= $row->jenis; ?></td>
                  <td><?= $row->keterangan; ?></td>
                  <td><?= as_rupiah($row->saldo_sebelum); ?></td>
                  <td><?= as_rupiah($row->nominal); ?></td>
                  <td><?= as_rupiah($row->saldo_setelah); ?></td>
                  <?php if(session()->get('usertype') == 'admin'): ?>
                  <td><?= $row->username; ?></td>
                  <?php endif; ?>
                  <td><?= $row->date_created; ?></td>

                  <?php if(session()->get('usertype') == 'admin'): ?>
                  <td>
                    <div class="dropdown">
                      <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown"></button>
                      <ul class="dropdown-menu">
                        <li><a class="dropdown-item edit-single" data-id="<?= $row->id; ?>" href="#">Edit</a></li>
                        <li><a class="dropdown-item delete-single" data-id="<?= $row->id; ?>" href="#">Delete</a></li>
                      </ul>
                    </div>
                  </td>
                <?php endif; ?>

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

  <?php include('footer.php'); ?>
  
 <?php include('modal_isi_ulang_saldo.php'); ?>
<?php include('modal_konfirmasi_pembayaran.php'); ?>

</div>

<!-- Scripts -->
<script src="/assets/js/jquery371.min.js"></script>
<script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/vendor/datatables/jquery.dataTables.min.js"></script>

<script src="/assets/js/manage-history-saldo.js"></script>
<script src="/assets/js/settings.js"></script>

<script src="/assets/js/sweetalert2@11.js"></script>
<script src="/assets/js/adminlte.js"></script>

<script src="/assets/js/cleave.min.js"></script>
<script src="/assets/js/pages/dashboard3.js"></script>
<script src="/assets/js/saldo.js"></script>

</body>
</html>
