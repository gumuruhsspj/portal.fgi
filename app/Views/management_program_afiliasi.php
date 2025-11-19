<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Management Program Afiliasi (<?= $usertype; ?>) Portal - Kursus Komputer</title>
 <link href="/assets/img/favicon.ico" rel="icon">
 <link href="/assets/img/favicon.ico" rel="apple-touch-icon">
        
  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="/assets/vendor/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="/assets/vendor/datatables/dataTables.dataTables.min.css">
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
    <a href="index3.html" class="brand-link">
      <img src="/assets/img/fgroupindonesia.jpg" alt="FGroupIndonesia Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
      <span class="brand-text font-weight-light">FGroupIndonesia</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
     

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
            <h1 class="m-0">Program Afiliasi</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Management</a></li>
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
          <div class="col">
            <div class="card">
              <div class="card-header border-0">
                <h3 class="card-title">Total Data : <span>0</span></h3>
                <div class="card-tools">
                  <a href="#" data-bs-toggle="modal" data-bs-target="#programAfiliasiModal" class="btn btn-tool btn-sm">
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
                <table id="table-management-program-afiliasi" class="table table-striped table-valign-middle">
                  <thead>
                   <tr>
                      <th><input type="checkbox" id="select-all" class="data-selected" ></th>

                    <th>Nama</th>
                    <th>Deskripsi</th>
                    <th>Total Member</th>
                    <th>Date Created</th>
                    <th>Actions</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?php if(isset($management_data) && !empty($management_data)): ?>
                    <?php foreach($management_data as $row): ?>
                  <tr>
                    <td>
                       <input type="checkbox" class="data-selected" data-id="<?= $row->id ;?>" > 
                     </td>
                    <td>
                      <img src="/assets/img/uploads/afiliasi/<?= $row->icon; ?>" alt="Product 1" class="img-circle img-size-32 mr-2">
                      <?= $row->nama; ?>
                    </td>
                    <td><?= $row->deskripsi; ?></td>

                     <td><?= $row->total_member; ?></td>

                      <td><?= $row->date_created; ?></td>

                    <td>
                     <div class="dropdown">
                      <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        
                      </button>
                      <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                        <li><a class="dropdown-item edit-single" data-id="<?= $row->id; ?>" href="#">Edit</a></li>
                        <li><a class="dropdown-item delete-single" data-id="<?= $row->id; ?>" href="#">Delete</a></li>
                        
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
<?php include('modal_program_afiliasi.php'); ?>
<?php include('modal_customer_services.php'); ?>

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="/assets/js/jquery371.min.js"></script>
<!-- Bootstrap -->
<script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/vendor/datatables/jquery.dataTables.min.js"></script>
<!-- AdminLTE -->
<script src="/assets/js/manage-program-afiliasi.js"></script>
<script src="/assets/js/settings.js"></script>
<script src="/assets/js/customer-services.js"></script>

<script src="/assets/js/adminlte.js"></script>

</body>
</html>
