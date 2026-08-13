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
</head>

<!--
`body` tag options:

  Apply one or more of the following classes to to the body tag
  to get the desired effect

  * sidebar-collapse
  * sidebar-mini
-->

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php include('nav_menu_upper.php'); ?>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <?php include('brand_logo.php'); ?>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar user panel (optional) -->

        <!-- Sidebar Menu -->
        <?php include('nav_menu_admin.php'); ?>

        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Pembayaran</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Management</a></li>
                <li class="breadcrumb-item"><a href="#">Pembayaran</a></li>
              </ol>
            </div><!-- /.col -->
          </div><!-- /.row -->
        </div><!-- /.container-fluid -->
      </div>
      <!-- /.content-header -->

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
                  <br>
                  <h3 class="card-title">Total Overall: <span><?= (isset($management_data) && $management_data != false) ? count($management_data) : 0; ?> data.</span></h3>


                  <div class="card-tools">
                    <a href="#" data-bs-toggle="modal" data-bs-target="#materiModal" class="btn btn-tool btn-sm">
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

                  <table id="table-management-pembayaran" class="table table-striped table-valign-middle">
                    <thead>
                      <tr>
                        <th><input type="checkbox" id="select-all" class="data-selected"></th>
                        <th>Nama User</th>
                        <th>Saldo Sebelum</th>
                        <th>Nominal</th>
                        <th>Saldo Setelah</th>
                        <th>Jenis</th>
                        <th>Status</th>
                        <th>Keterangan</th>
                        <th>Date</th>
                        <th>Actions</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (isset($management_data) && !empty($management_data)): ?>
                        <?php foreach ($management_data as $row): ?>
                          <tr>
                            <td>
                              <input type="checkbox" class="data-selected" data-id="<?= $row->id; ?>">
                            </td>

                            <td><?= $row->nama_lengkap; ?></td>
                            <td><?= number_format($row->saldo_sebelum); ?></td>
                            <td><b><?= number_format($row->nominal); ?></b></td>
                            <td><?= number_format($row->saldo_setelah); ?></td>
                            <td><?= $row->jenis; ?></td>

                            <!-- STATUS -->
                            <td>
                              <?php if ($row->status == 'pending'): ?>
                                <span class="badge bg-warning">Pending</span>
                              <?php elseif ($row->status == 'approved'): ?>
                                <span class="badge bg-success">Approved</span>
                              <?php else: ?>
                                <span class="badge bg-danger">Cancelled</span>
                              <?php endif; ?>
                            </td>

                            <td><?= $row->keterangan; ?></td>
                            <td><?= $row->date_created; ?></td>

                            <!-- ACTION -->
                            <td>

                              <div class="btn-group">
                                <?php if ($row->status == 'pending'): ?>
                                  <button class="btn btn-success btn-sm approve-btn" data-status="approved" data-id="<?= $row->id; ?>">✔</button>
                                  <button class="btn btn-danger btn-sm cancel-btn" data-status="cancelled" data-id="<?= $row->id; ?>">✖</button>
                                <?php else: ?>
                                  <button class="btn btn-danger btn-sm delete-btn" data-status="deleted" data-id="<?= $row->id; ?>">✖</button>
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


          <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

    <!-- Main Footer -->
    <?php include('footer.php'); ?>
  </div>
  <!-- ./wrapper -->
  <?php include('modal_materi_paket.php'); ?>
  <?php include('modal_materi.php'); ?>
  <?php include('modal_customer_services.php'); ?>
  <?php include('modal_comments_rating.php'); ?>
  <?php include('modal_usulan_materi.php'); ?>

  <!-- REQUIRED SCRIPTS -->

  <!-- jQuery -->
  <script src="<?= base_url() ?>assets/js/jquery371.min.js"></script>

  <!-- Bootstrap -->
  <script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url() ?>assets/vendor/datatables/jquery.dataTables.min.js"></script>
  <!-- AdminLTE -->
  <script src="<?= base_url() ?>assets/js/manage-pembayaran.js<?= $random; ?>"></script>
  <script src="<?= base_url() ?>assets/js/settings.js<?= $random; ?>"></script>
  <script src="<?= base_url() ?>assets/js/customer-services.js<?= $random; ?>"></script>
  <script src="<?= base_url() ?>assets/js/timer.js"></script>
  <script src="<?= base_url() ?>assets/js/adminlte.js<?= $random; ?>"></script>

</body>

</html>