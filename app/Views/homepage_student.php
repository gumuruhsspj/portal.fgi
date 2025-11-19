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
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-info">
              <div class="inner">
                <h3><?= $total_materi; ?></h3>

                <p>Total Materi</p>
              </div>
              <div class="icon">
                <i class="ion ion-bag"></i>
              </div>
              <a href="/all-materi" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-success">
              <div class="inner">
                <h3><?= $total_progress_materi; ?><sup style="font-size: 20px">%</sup></h3>

                <p>Progress Materi Anda</p>
              </div>
              <div class="icon">
                <i class="ion ion-stats-bars"></i>
              </div>
              <a href="/materi-terpilih" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-warning">
              <div class="inner">
                <h3><?= $total_users; ?></h3>

                <p>Total User</p>
              </div>
              <div class="icon">
                <i class="ion ion-person-add"></i>
              </div>
              <a href="/all-user" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
          <div class="col-lg-3 col-6">
            <!-- small box -->
            <div class="small-box bg-danger">
              <div class="inner">
                <h3><?= as_rupiah($total_pendapatan_afiliasi); ?></h3>

                <p>Pendapatan Afiliasi Anda</p>
              </div>
              <div class="icon">
                <i class="ion ion-pie-graph"></i>
              </div>
              <a href="/program-afiliasi" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
            </div>
          </div>
          <!-- ./col -->
        </div>
        <div class="row">
          <div class="col-lg-6">
           <div class="card bg-gradient-success">
              <div class="card-header border-0 ui-sortable-handle" style="cursor: move;">

                <h3 class="card-title">
                  <i class="far fa-calendar-alt"></i>
                  Calendar
                </h3>
                <!-- tools card -->
                <div class="card-tools">
                  <!-- button with a dropdown -->
                  
                  <button type="button" class="btn btn-success btn-sm" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                  </button>
                  <button type="button" class="btn btn-success btn-sm" data-card-widget="remove">
                    <i class="fas fa-times"></i>
                  </button>
                </div>
                <!-- /. tools -->
              </div>
              <!-- /.card-header -->
              <div class="card-body pt-0" style="display: block;">
                <!--The calendar -->
                <div id="calendar" style="width: 100%">
                </div>
              </div>
              <!-- /.card-body -->
            </div>
           
          </div>
          <!-- /.col-md-6 -->
          <div class="col-lg-6">
            <div class="card">
              <div class="card-header ui-sortable-handle" style="cursor: move;">
                <h3 class="card-title">
                  <i class="ion ion-clipboard mr-1"></i>
                  Daily Notes : <span id="date-chosen"><?= date('d/M/Y'); ?></span>
                </h3>

                <div class="card-tools">
                 <!-- <ul class="pagination pagination-sm">
                    <li class="page-item"><a href="#" class="page-link">«</a></li>
                    <li class="page-item"><a href="#" class="page-link">1</a></li>
                    <li class="page-item"><a href="#" class="page-link">2</a></li>
                    <li class="page-item"><a href="#" class="page-link">3</a></li>
                    <li class="page-item"><a href="#" class="page-link">»</a></li>
                  </ul> -->
                </div>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <ul id="todo-list-ul" class="todo-list ui-sortable" data-widget="todo-list">
                  <?php if(isset($data_daily_notes)): ?>
                    <?php if($data_daily_notes != false):?>
                    <?php foreach($data_daily_notes as $dnotes): ?>
                      
                  <li>
                    <!-- drag handle -->
                  
                    <!-- checkbox -->
                    <div class="icheck-primary d-inline ml-2">
                    <input type="checkbox" value="done" id="" class="todoCheck" data-id="<?=$dnotes->id; ?>">
                <label class="label" for="todoCheck" data-id="<?=$dnotes->id; ?>" ><?=$dnotes->notes; ?></label>
                <input type="text" class="isian text-todoCheck" data-id="<?=$dnotes->id; ?>" />
            </div>
            <span class="text"></span>
        
                    <!-- General tools such as edit or delete-->
                   <div class="tools">
                      <i class="edit-todoCheck fas fa-edit"></i>
                      <i data-id="<?=$dnotes->id; ?>" class="delete-todoCheck fas fa-trash"></i>
                  </div>
                  </li>
                <?php endforeach; ?>
                <?php endif;?>
                  <?php endif;?>
                  
                </ul>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                <button id="btn-add-new-note" type="button" class="btn btn-primary float-right"><i class="fas fa-plus"></i> Add New</button>
              </div>
            </div>
            <!-- /.card -->

           
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
<script src="/assets/js/adminlte.js"></script>
<script src="/assets/js/sweetalert2@11.js"></script>

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
