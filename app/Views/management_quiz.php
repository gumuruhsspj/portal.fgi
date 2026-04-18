<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Management Quiz (<?= $usertype; ?>) Portal - Kursus Komputer</title>
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
            <h1 class="m-0">Quiz </h1>
            <h4 class="m-4">Judul : <?= !empty($judul_materi) ? $judul_materi : ''; ?></h4>
            <input type="hidden" id="id_user" value="<?= session()->get('id'); ?>">
            <input type="hidden" id="id_materi" value="<?= $id_materi; ?>">
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Management</a></li>
              <li class="breadcrumb-item"><a href="#">Materi</a></li>
              <li class="breadcrumb-item"><a href="#">Quiz</a></li>
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
                <h3 class="card-title">Total Overall: <span id="total_data" data-jumlah="<?= $jumlah_data;?>" ><?= (isset($management_data) && $management_data!=false) ? count($management_data) : 0; ?> soal quiz.</span></h3>
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
            
              <div id="card-mode" class="row mt-4" style="<?= empty($management_data) ? 'display:none;' : '' ?>">
    <!-- JS akan append card-card disini -->
    <?php if(!empty($management_data)) : ?>
        <?php foreach($management_data as $data) : ?>
            <div class="col-md-6 mb-4 card-item" data-id-materi="<?= $data->id_materi ;?>" data-id="<?= $data->id ;?>">
                <div class="card h-100">
                    <div class="card-body" style="margin-left: 15px;">
                        <!-- Pertanyaan -->
                        <textarea class="form-control mb-2 pertanyaan" placeholder="Pertanyaan"><?= $data->pertanyaan ?? '' ?></textarea>

                        <!-- Jenis Soal -->
                        <select class="form-select mb-2 jenis-soal">
                            <option value="essay" <?= isset($data->jenis) && $data->jenis=='essay' ? 'selected' : '' ?>>Essay</option>
                            <option value="pg2" <?= isset($data->jenis) && $data->jenis=='pg2' ? 'selected' : '' ?>>PG 2 opsi</option>
                            <option value="pg4" <?= isset($data->jenis) && $data->jenis=='pg4' ? 'selected' : '' ?>>PG 4 opsi</option>
                        </select>

                        <!-- Opsi PG -->
                        <div class="pg-opsi mb-2" style="<?= isset($data->jenis) && $data->jenis!='essay' ? '' : 'display:none;' ?>">
                            <?php if(isset($data->jenis) && $data->jenis=='pg2'): ?>
                                <input type="text" class="form-control mb-1 opsi-a" placeholder="Opsi A" value="<?= $data->opsi_a ?? '' ?>">
                                <input type="text" class="form-control mb-1 opsi-b" placeholder="Opsi B" value="<?= $data->opsi_b ?? '' ?>">
                            <?php elseif(isset($data->jenis) && $data->jenis=='pg4'): ?>
                                <input type="text" class="form-control mb-1 opsi-a" placeholder="Opsi A" value="<?= $data->opsi_a ?? '' ?>">
                                <input type="text" class="form-control mb-1 opsi-b" placeholder="Opsi B" value="<?= $data->opsi_b ?? '' ?>">
                                <input type="text" class="form-control mb-1 opsi-c" placeholder="Opsi C" value="<?= $data->opsi_c ?? '' ?>">
                                <input type="text" class="form-control mb-1 opsi-d" placeholder="Opsi D" value="<?= $data->opsi_d ?? '' ?>">
                            <?php endif; ?>
                        </div>

                        <!-- Keterangan -->
                        <textarea class="form-control mb-2 keterangan" placeholder="Keterangan"><?= $data->keterangan ?? '' ?></textarea>

                        <!-- Final Answer -->
                        <input type="text" class="form-control mb-2 jawaban-akhir" placeholder="Jawaban Final" 
                               value="<?= $data->final_answer ?? '' ?>" 
                               style="<?= isset($data->jenis) && $data->jenis=='essay' ? 'display:none;' : '' ?>">

                        <!-- Buttons -->
                        <button class="btn btn-sm btn-danger delete-card" data-id="<?= $data->id ;?>">Delete</button>
                        <button class="btn btn-sm btn-success float-end save-card" data-id="<?= $data->id ;?>">Save</button>
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

<?php include('modal_customer_services.php'); ?>
<?php include('modal_comments_rating.php'); ?>
<?php include('modal_usulan_materi.php'); ?>

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="/assets/js/jquery371.min.js"></script>

<!-- Bootstrap -->
<script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/vendor/datatables/jquery.dataTables.min.js"></script>
<script src="/assets/js/sweetalert2@11.js<?=$random;?>"></script>
<!-- AdminLTE -->
<script src="/assets/js/manage-quiz.js<?=$random;?>"></script>
<script src="/assets/js/settings.js<?=$random;?>"></script>
<script src="/assets/js/customer-services.js<?=$random;?>"></script>
<script src="/assets/js/timer.js"></script>
<script src="/assets/js/trix.umd.min.js<?=$random;?>"></script>
<script src="/assets/js/adminlte.js<?=$random;?>"></script>
<script src="/assets/js/pages/dashboard3.js<?=$random;?>"></script>

</body>
</html>
