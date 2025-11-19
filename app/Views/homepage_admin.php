<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home (<?= $usertype; ?>) Portal - Kursus Komputer</title>
 <link href="assets/img/favicon.ico" rel="icon">
        <link href="assets/img/favicon.ico" rel="apple-touch-icon">
        
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="/assets/vendor/fontawesome-free/css/all.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/adminlte.min.css">
  <link rel="stylesheet" href="/assets/css/styles-custom-homepage.css">
  <link rel="stylesheet" href="/assets/css/styles-custom-portal.css">

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
    <a href="/" class="brand-link">
      <img src="/assets/img/fgroupindonesia.jpg" alt="FGroupIndonesia Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">FGroupIndonesia</span>
    </a>

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
            <h1 class="m-0">Dashboard</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              
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
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Registered Users</h3>
                  <a href="#" class="btn btn-tool btn-sm">
                    <i class="fas fa-download"></i> Download Report
                  </a>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <p class="d-flex flex-column">
                    <span class="text-bold text-lg">Total : <?= $total_users; ?></span>
                    <span>Overall</span>
                  </p>
                  <p class="ml-auto d-flex flex-column text-right">
                    <span class="text-success">
                      <i class="fas fa-arrow-up"></i> 12.5%
                    </span>
                    <span class="text-muted">Since last week</span>
                  </p>
                </div>
                <!-- /.d-flex -->

                <div class="position-relative mb-4">
                  <canvas id="visitors-chart" height="200"></canvas>
                </div>

                <div class="d-flex flex-row justify-content-end">
                  <span class="mr-2">
                    <i class="fas fa-square text-primary"></i> Total Users 
                  </span>

                  
                </div>
              </div>
            </div>
            <!-- /.card -->

            <div class="card">
              <div class="card-header border-0">
                <h3 class="card-title">Materi</h3>
                <div class="card-tools">
                  <a href="#" class="btn btn-tool btn-sm">
                    <i class="fas fa-download"></i> Download Report
                  </a>
                  
                </div>
              </div>
              <div class="card-body table-responsive p-0">
                <table class="table table-striped table-valign-middle">
                  <thead>
                  <tr>
                    <th>Judul</th>
                    <th>Kategori</th>
                    <th>Deskripsi</th>
                    <th>Username</th>
                    <th>Date Created</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php if(isset($data_materi)) : ?>
                      <?php foreach($data_materi as $dm): ?>
                  <tr>
                    <td>
                      <?= $dm->judul; ?>
                    </td>
                    <td>
                      <?= $dm->kategori; ?>
                    </td>
                    <td>
                      <?= $dm->deskripsi; ?>
                    </td>
                    <td>
                      <?= $dm->username; ?>
                    </td>
                    <td>
                      <?= calculate_time_elapsed($dm->date_created); ?>
                    </td>
                  </tr>
                <?php endforeach; ?>
                <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
            <!-- /.card -->
          </div>
          <!-- /.col-md-6 -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header border-0">
                <div class="d-flex justify-content-between">
                  <h3 class="card-title">Gender Comparison</h3>
                  <a href="#" class="btn btn-tool btn-sm">
                    <i class="fas fa-download"></i> Download Report
                  </a>
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex">
                  <p class="d-flex flex-column">
                    <span class="text-bold text-lg">Male : <?= $total_male; ?>, Female : <?= $total_female; ?></span>
                    <span>Overall</span>
                  </p>
                  <p class="ml-auto d-flex flex-column text-right">
                    <span class="text-success">
                      <i class="fas fa-arrow-up"></i> 33.1%
                    </span>
                    <span class="text-muted">Since last month</span>
                  </p>
                </div>
                <!-- /.d-flex -->

                <div class="position-relative mb-4">
                  <canvas id="sales-chart" height="200"></canvas>
                </div>

                <div class="d-flex flex-row justify-content-end">
                  <span class="mr-2">
                    <i class="fas fa-square text-primary"></i> Male
                  </span>

                  <span>
                    <i class="fas fa-square text-gray"></i> Female
                  </span>
                </div>
              </div>
            </div>
            <!-- /.card -->

            <div class="card">
              <div class="card-header border-0">
                <h3 class="card-title">Group Diskusi</h3>
                <div class="card-tools">
                  <a href="#" class="btn btn-sm btn-tool">
                    <i class="fas fa-download"></i> Download Report
                  </a>
                  
                </div>
              </div>
              <div class="card-body">
                <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                  <p class="text-success text-xl">
                    <i class="ion ion-ios-refresh-empty"></i>
                  </p>
                  <p class="d-flex flex-column text-right">
                    <span class="font-weight-bold">
                      <i class="ion ion-android-arrow-up text-success"></i> <?= $total_telegram_group; ?>
                    </span>
                    <span class="text-muted">Telegram : 
                      <?php if(isset($data_group_diskusi_tg)) : ?>
                         <?= get_text_data_from_array($data_group_diskusi_tg); ?>
                      <?php endif; ?>
                    </span>
                  </p>
                </div>
                <!-- /.d-flex -->
                <div class="d-flex justify-content-between align-items-center border-bottom mb-3">
                  <p class="text-warning text-xl">
                    <i class="ion ion-ios-cart-outline"></i>
                  </p>
                  <p class="d-flex flex-column text-right">
                    <span class="font-weight-bold">
                      <i class="ion ion-android-arrow-up text-warning"></i> <?= $total_whatsapp_group; ?>
                    </span>
                    <span class="text-muted">Whatsapp :
                      <?php if(isset($data_group_diskusi_wa)) : ?>
                         <?= get_text_data_from_array($data_group_diskusi_wa); ?>
                      <?php endif; ?>
                    </span>
                  </p>
                </div>
                
              </div>
            </div>
          </div>
          <!-- /.col-md-6 -->
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
 <?php include('modal_customer_services.php'); ?>
 
 <?php include('modal_isi_ulang_saldo.php'); ?>
<?php include('modal_konfirmasi_pembayaran.php'); ?>

</div>

<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="/assets/js/jquery371.min.js"></script>
<script src="/assets/js/jquery-ui.min.js"></script>
<!-- Bootstrap -->
<script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE -->
<script src="/assets/js/sweetalert2@11.js"></script>
<script src="/assets/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
 
<script src="/assets/js/cleave.min.js"></script>
<script src="/assets/vendor/chart.js/Chart.min.js"></script>
<script src="/assets/js/settings.js"></script>
<script src="/assets/js/customer-services.js"></script>

<script src="/assets/js/pages/dashboard3.js"></script>
<script src="/assets/js/saldo.js"></script>
</body>
</html>
