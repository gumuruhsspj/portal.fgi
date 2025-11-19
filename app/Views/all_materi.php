<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= $title;?> (<?= $usertype; ?>) Portal - Kursus Komputer</title>
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
    <?php include('nav_menu_user.php'); ?>

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
            <h1 class="m-0"><?= $title;?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Materi</a></li>
              <li class="breadcrumb-item"><a href="#"><?= $title;?></a></li>
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
         
         <div class="card card-success">
          <div class="card-body">
            <div class="row">
              <?php if($title=="Seluruh Materi"): ?>
              <?php if(isset($data_materi)) : ?>
                <?php foreach ($data_materi as $dmateri): ?>
              <div class="col-md-12 col-lg-6 col-xl-4">
                <div class="card mb-2 bg-gradient-dark">
                  <img class="card-img-top" src="/assets/img/uploads/materi/<?= $dmateri->icon ;?>" alt="Dist Photo 1">
                  <div class="card-img-overlay d-flex flex-column justify-content-end">
                    <a href="/materi?title=<?= string_to_url($dmateri->judul) ;?>"><h5 class="card-title text-primary text-white"><?= strtoupper($dmateri->judul);?></h5></a>
                    <a href="/materi/kategori/<?= string_to_url($dmateri->kategori) ;?>"><h5 class="card-title text-primary text-white"><?= $dmateri->kategori;?></h5></a>
                    <p class="card-text text-white pb-2 pt-1"><?= $dmateri->deskripsi;?></p>
                    <span>Last update <?= calculate_time_elapsed($dmateri->date_created); ?></span>
                  </div>
                </div>
              </div>
              <?php endforeach; ?>
              <?php endif; ?>

            <?php else : ?>

            
        <div class="card-header">
          
        </div>
        <div class="card-body p-0">
          <table class="table table-striped projects">
              <thead>
                  <tr>
                      <th style="width: 1%">
                          #
                      </th>
                      <th style="width: 20%">
                          Nama Materi
                      </th>
                      <th style="width: 30%">
                          Kategori
                      </th>
                      <th>
                          Deskripsi
                      </th>
                      <th style="width: 8%" class="text-center">
                          Status
                      </th>
                      <th style="width: 20%">
                      </th>
                  </tr>
              </thead>
              <tbody>
                <?php if(isset($data_materi_user)) : ?>
                  <?php if(sizeof($data_materi_user)>0) : ?>
                    <?php foreach($data_materi_user as $duser) : ?>
                  <tr>
                      <td>
                          #
                      </td>
                      <td>
                          <a href="/materi?title=<?=$duser->url;?>">
                              <?= $duser->judul; ?>
                          </a>
                          <br>
                          <small>
                              Sejak : <?= $duser->date_created; ?>
                          </small>
                      </td>
                      <td>
                         <?= $duser->kategori; ?>
                      </td>
                      <td class="project_progress">
                          <?= $duser->deskripsi; ?>
                      </td>
                      <td class="project-state">
                        <?php
                        $badge = "";

                         if($duser->status == 'pending')
                          $badge = "badge-info";

                          if($duser->status == 'in progress')
                          $badge = "badge-warning";

                          if($duser->status == 'completed')
                          $badge = "badge-success";

                        if($duser->status == 'error' || $duser->status == 'delete request')
                          $badge = "badge-error";

                           ?>
                          <span class="badge <?= $badge; ?>"><?=$duser->status;?></span>
                      </td>
                      <td class="project-actions text-right">
                         <?php  if($duser->status == 'in progress'): ?>
                          <a class="btn btn-primary btn-sm" href="/materi/start/?id=<?=$duser->id_materi ;?>">
                              <i class="fa-solid fa-play"></i>
                              </i>
                              Lanjutkan
                          </a>
                        <?php endif; ?>
                         
                         <?php if($duser->status != 'error' || $duser->status != 'delete request'): ?>  
                          <a class="btn btn-danger btn-sm" href="#">
                              <i class="fa-solid fa-xmark"></i>
                              </i>
                              Batalkan
                          </a>
                           <?php endif; ?>
                      </td>
                  </tr>
                <?php endforeach; ?>
                  <?php endif;?>
                 <?php endif;?>
              </tbody>
          </table>
      
        <!-- /.card-body -->
      </div>

            <?php endif; ?>
            
             
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

<script src="/assets/js/pages/dashboard3.js"></script>
<script src="/assets/js/saldo.js"></script>

</body>
</html>
