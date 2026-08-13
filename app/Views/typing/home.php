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
 <?= view('nav_menu_upper', $data); ?>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <?= view('brand_logo', $data ); ?>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      
      <!-- Sidebar Menu -->
    <?= view('nav_menu_admin' , $data); ?>

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
       <!-- Tambahan: Typing Tutor -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-keyboard"></i> Latihan Mengetik (Typing Tutor)</h3>
                        <div class="card-tools">
                            <a href="/exercise/typing/highscores" class="btn btn-warning btn-sm">
                                <i class="fas fa-trophy"></i> High Scores
                            </a>
                            <!-- Opsional: tombol pilih bahasa, jika diperlukan -->
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <?php if (isset($chapters) && is_array($chapters)): ?>
                                <?php foreach ($chapters as $chapter): ?>
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card chapter-card h-100" 
                                            onclick="location.href='/exercise/typing/chapter/<?= $chapter['id'] ?>'" 
                                            style="cursor: pointer; transition: transform 0.2s, box-shadow 0.2s;">
                                            <div class="card-body">
                                                <h5 class="card-title">Bab <?= $chapter['chapter_number'] ?>: <?= $chapter['title'] ?></h5>
                                                <p class="card-text text-muted"><?= $chapter['description'] ?? 'Latihan mengetik untuk meningkatkan kecepatan dan akurasi' ?></p>
                                                <div class="mt-3">
                                                    <div class="d-flex justify-content-between small mb-1">
                                                        <span>Progress</span>
                                                        <span><?= $chapter['progress_percent'] ?? 0 ?>%</span>
                                                    </div>
                                                    <div class="progress" style="height:8px; border-radius:4px;">
                                                        <div class="progress-bar bg-success" role="progressbar" 
                                                            style="width: <?= $chapter['progress_percent'] ?? 0 ?>%;"></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <div class="col-12">
                                    <p class="text-muted">Belum ada data bab latihan.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Akhir Tambahan -->
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
  <?php view('footer', $data); ?>
 <?php view('modal_customer_services'); ?>
 
 <?php view('modal_isi_ulang_saldo'); ?>
<?php view('modal_konfirmasi_pembayaran'); ?>

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
<script src="/assets/js/timer.js"></script>
<script src="/assets/js/pages/dashboard3.js"></script>
<script src="/assets/js/saldo.js"></script>
</body>
</html>
