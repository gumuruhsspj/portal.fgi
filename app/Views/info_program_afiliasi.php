<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Info Program Afiliasi (<?= $usertype; ?>) Portal - Kursus Komputer</title>
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
  <!-- for calendar purposes -->
  <link rel="stylesheet" href="/assets/vendor/jquery-calendar/style.css" />
  <link rel="stylesheet" href="/assets/vendor/jquery-calendar/theme.css" />

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
    <?php include('nav_menu_user.php'); ?>

      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper" style="min-height: 429.334px;">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Program Afiliasi</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Program Afiliasi</a></li>
              
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
            <?php if(isset($data_program_afiliasi)) : ?>
        <?php if($data_program_afiliasi!=false): ?>
          <?php foreach($data_program_afiliasi as $dpafiliasi) : ?>
          <div class="col-md-3 col-sm-6 col-12">
            
              <?php
              $jenis_icon = "";
              $jenis_warna = "";

               if($dpafiliasi->total_member >= 0 && $dpafiliasi->total_member < 25)
              {
                $jenis_icon = "fa-bookmark";
                $jenis_warna = "bg-gradient-danger";
              }

              if($dpafiliasi->total_member >= 25 && $dpafiliasi->total_member < 50)
              {
                $jenis_icon = "fa-thumbs-up";
                $jenis_warna = "bg-gradient-success";
              }

              if($dpafiliasi->total_member >= 50 && $dpafiliasi->total_member < 75)
              {
                $jenis_icon = "fa-star";
                $jenis_warna = "bg-gradient-info";
              }

            if($dpafiliasi->total_member >= 75 && $dpafiliasi->total_member < 100)
             {
              $jenis_icon = "fa-comments";
              $jenis_warna = "bg-gradient-success";
            }
               ?>
               <div class="info-box <?= $jenis_warna; ?>">
              <span class="info-box-icon"><i class="far <?= $jenis_icon; ?>"></i></span>

              <div class="info-box-content">
                <span class="info-box-text"><?= $dpafiliasi->nama; ?></span>
                <span class="info-box-number">Member : <?= $dpafiliasi->total_member; ?></span>

                <div class="progress">
                  <div class="progress-bar" style="width: 70%"></div>
                </div>
                <span class="progress-description">
                  <?= $dpafiliasi->deskripsi; ?>
                </span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
             <?php endforeach; ?>
          <?php endif; ?>
        <?php endif; ?>
         
         
          <!-- /.col -->
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
 <?php include('modal_usulan_materi.php'); ?>
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
<script src="/assets/js/manage-daily-notes.js"></script>

<script src="/assets/vendor/jquery-calendar/calendar.min.js"></script>
<script src="/assets/js/pages/dashboard3.js"></script>
<script src="/assets/js/saldo.js"></script>

<script defer src="/assets/vendor/fontawesome-free/js/all.js"></script>


</body>
</html>
