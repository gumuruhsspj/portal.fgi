<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Management Pembahasan (<?= $usertype; ?>) Portal - Kursus Komputer</title>
 <link href="/assets/img/favicon.ico" rel="icon">
        <link href="/assets/img/favicon.ico" rel="apple-touch-icon">
        
  <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="/assets/css/google-fonts.css">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="/assets/vendor/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="/assets/vendor/datatables/dataTables.dataTables.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css">

  <link rel="stylesheet" href="/assets/css/trix.css">
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
            <h1 class="m-0">Pembahasan </h1>
            <h4 class="m-4">Judul : <?= !empty($judul_materi) ? $judul_materi : ''; ?></h4>
            <input type="hidden" id="id_user" value="<?= session()->get('id'); ?>">
            <input type="hidden" id="id_materi" value="<?= $id_materi; ?>">
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Management</a></li>
              <li class="breadcrumb-item"><a href="#">Materi</a></li>
              <li class="breadcrumb-item"><a href="#">Pembahasan</a></li>
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
                <h3 class="card-title">Total Overall: <span id="total_data" data-jumlah="<?= $jumlah_data;?>" ><?= (isset($management_data) && $management_data!=false) ? count($management_data) : 0; ?> bab.</span></h3>
                <div class="card-tools">
                
                    <a href="#" id="add-card" class="btn btn-tool btn-sm">
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
            
              <?php if(empty($management_data)) : ?>
                  <div id="card-mode" class="row mt-4" style="display:none;">
                <?php else : ?>
                  <div id="card-mode" class="row mt-4" >
              <?php endif; ?>
                <!-- JS akan append card-card disini -->
                 <?php if(!empty($management_data)) : ?>
        <?php foreach($management_data as $data) : ?>
            <div class="col-md-4 mb-4 card-item" data-id-materi="<?= $data->id_materi ;?>" data-id="<?= $data->id ;?>">
            <div class="card h-100">
                <div class="card-body" style="margin-left: 15px;">
                    <input type="checkbox" class="form-check-input selected-card mb-2">

                    <input type="text" class="form-control mb-2 judul" 
                          placeholder="Judul Bab" value="<?= $data->judul ;?>">

                    <textarea class="form-control mb-2 deskripsi" 
                              placeholder="Deskripsi"><?= $data->deskripsi ;?></textarea>

                    <p class="text-muted">
                    
                    <a href="#" class="add-pembahasan" data-bs-toggle="modal" data-bs-target="#modalPembahasan" >Add Pembahasan</a>
                    
                    <div class="list-group">
                      <?php if(!empty($management_pembahasan[$data->id])) : ?>
                          <?php foreach($management_pembahasan[$data->id] as $pembahasan): ?>
                            <?php if($pembahasan->id_bab == $data->id): ?>
                              <li class="list-group-item">
                           <?= $pembahasan->judul ;?>
                            <button data-id="<?= $pembahasan->id ;?>" class="btn btn-warning btn-sm float-end edit-pembahasan"  >
                            <i class="fas fa-edit"></i>
                            </button>
                            <button data-id="<?= $pembahasan->id ;?>" class="btn btn-danger btn-sm float-end remove-pembahasan"  >
                            <i class="fas fa-trash-alt"></i>
                            </button>
                          </li>
                            <?php endif; ?>
                          <?php endforeach; ?>
                          <?php endif; ?>
                    </div>
                    </p>

                    <button class="btn btn-sm btn-danger delete-card" data-id="<?= $data->id ;?>">
                        Delete
                    </button>

                    <button class="btn btn-sm btn-success float-end save-card" data-id="<?= $data->id ;?>">
                        Save
                    </button>
                </div>
            </div>
        </div>
                  <?php endforeach; ?>
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
</div>
<!-- ./wrapper -->
<?php include('modal_materi_paket.php'); ?>
<?php include('modal_materi.php'); ?>
<?php include('modal_customer_services.php'); ?>
<?php include('modal_comments_rating.php'); ?>
<?php include('modal_pembahasan.php'); ?>


<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="/assets/js/jquery371.min.js"></script>

<!-- Bootstrap -->
<script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/js/sweetalert2@11.js<?=$random;?>"></script>
<!-- AdminLTE -->
<script src="/assets/js/manage-materi.js<?=$random;?>"></script>
<script src="/assets/js/settings.js<?=$random;?>"></script>
<script src="/assets/js/customer-services.js<?=$random;?>"></script>


<script src="/assets/js/trix.umd.min.js<?=$random;?>"></script>
<script src="/assets/js/adminlte.js<?=$random;?>"></script>
<script src="/assets/js/pembahasan.js<?=$random;?>"></script>
<script src="/assets/js/pages/dashboard3.js<?=$random;?>"></script>

</body>
</html>
