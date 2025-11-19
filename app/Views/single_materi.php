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
  <link rel="stylesheet" href="/assets/vendor/fontawesome-free/css/all.css">
  
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="/assets/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="/assets/css/adminlte.min.css">
  
  
  <link rel="stylesheet" href="/assets/css/styles-custom-homepage.css">
  <link rel="stylesheet" href="/assets/css/styles-custom-portal.css">
  
  <link rel="stylesheet" href="/assets/css/sweetalert2.min.css">
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
              <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
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
        
        <div class="card card-solid">
        <div class="card-body">
          <div class="row">

            <div class="col-12 col-sm-6">
              <h3 class="d-inline-block d-sm-none"><?= ($data_materi!=false && isset($data_materi))? $data_materi->judul : ""; ?></h3>
              <div class="col-12">
              <img src="/assets/img/uploads/materi/<?= ($data_materi!=false && isset($data_materi))? $data_materi->icon : ""; ?>" 
            class="product-image img-fluid img-card-effect"  alt="Product Image">
              </div>
              
            </div>
            <div class="col-12 col-sm-6">
              <h3 class="my-3"><?= ($data_materi!=false && isset($data_materi))? $data_materi->deskripsi : ""; ?></h3>

              <hr>
             
              <h4 class="mt-3">Pilihan <small>Paket</small></h4>
              
              <div class="btn-group btn-group-toggle" data-toggle="buttons">
    
  
    <label class="btn btn-default text-center">
        <input class="btn_opsi_paket" type="radio" name="color_option" data-biaya="<?= $data_materi->biaya_belajar_sendiri; ?>" id="paket_belajar_sendiri" autocomplete="off" value="paket_belajar_sendiri" >
        <i class="fas fa-book fa-2x"></i> <br>
        Belajar Sendiri + Materi (Video + Modul)
    </label>

    <label class="btn btn-default text-center">
        <input class="btn_opsi_paket" type="radio" name="color_option" data-biaya="<?= $data_materi->biaya_pokok; ?>" id="paket_bimbingan" autocomplete="off" value="paket_bimbingan">
        <i class="fas fa-users fa-2x"></i> <br>
        Bimbingan (Live) + Materi (Video + Modul)
    </label>

    <label class="btn btn-default text-center ">
        <input class="btn_opsi_paket" type="radio" name="color_option" data-biaya="<?= $data_materi->biaya_kasus_custom; ?>" id="paket_kasus_custom" autocomplete="off" value="paket_kasus_custom">
        <i class="fas fa-cogs fa-2x"></i> <br>
        Kasus Custom (Spesifik)
    </label>

</div>

              <div class="bg-gray py-2 px-3 mt-4">
                <h2 id="materi-harga" class="mb-0" >
                 <?= as_rupiah($data_materi->biaya_pokok + (0.1 * $data_materi->biaya_pokok)); ?>
                </h2>
                <h4 class="mt-0">
                  <small>Sudah Termasuk Pajak 10%: <span id="biayapajak"> <?= as_rupiah(0.1 * $data_materi->biaya_pokok) ?></span> </small>
                  <br>
                  <?php if($data_materi->rilis_sertifikat == 'yes') : ?>
                    <small><i class="fa-solid fa-check"></i> Bonus Sertifikat.  </small>
                  <?php else : ?>
                    <small><i class="fa-solid fa-xmark"></i> Tanpa Sertifikat.  </small>
                  <?php endif; ?>
                </h4>
              </div>

              <div class="mt-4">
                <div class="btn btn-primary btn-lg btn-flat btn-pilih-materi" >
                  <?php if(empty($participate)) :?>
                  <i class="fas fa-plus-circle fa-lg mr-2"></i>
                  Mau Ini Sekarang
                  <?php else : ?>
                    <i class="fas fa-arrow-right-to-bracket fa-lg mr-2"></i>
                    Masuk ke Pembahasan
                  <?php endif; ?>
                </div>

                <div class="btn btn-default btn-lg btn-flat">
                  <i class="fa-brands fa-whatsapp"></i>
                  Konsultasi Sebentar
                </div>
              </div>

            

            </div>
          </div>

         <div class="row mt-4">
    <nav class="w-100">
        <div class="nav nav-tabs" id="product-tab" role="tablist">
            <a class="nav-item nav-link active" id="product-comments-tab" data-bs-toggle="tab" href="#product-comments" role="tab" aria-controls="product-comments" aria-selected="true">Comments &amp; Rating</a>
            
            <a class="nav-item nav-link" id="product-add-comment-tab" data-bs-toggle="tab" href="#product-add-comment" role="tab" aria-controls="product-add-comment" aria-selected="false"> 
                <i class="fas fa-edit"></i> Add Comment
            </a>
        </div>
    </nav>
    <div class="tab-content p-3" id="nav-tabContent" style="width: 100%;">
        
        <div class="tab-pane fade show active" id="product-comments" role="tabpanel" aria-labelledby="product-comments-tab"> 
            
        <div id="comment-list-area">
    <?php if (!empty($data_comments)) : ?>
        <?php foreach ($data_comments as $dc): ?>
            <div class="mb-2 pb-2 border-bottom">

                <strong><?= $dc->username; ?></strong>

                <span class="text-warning">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <?= ($i <= $dc->ratings) 
                            ? '<i class="fa fa-star"></i>' 
                            : '<i class="fa-regular fa-star"></i>'; ?>
                    <?php endfor; ?>
                </span>

                <br>

                <small class="text-muted"><?= ($dc->date_created); ?></small>

                <p class="mt-1"><?= ($dc->comments); ?></p>

            </div>
        <?php endforeach; ?>
    <?php else : ?>
        <p>Belum ada yang berikan komentar disini...</p>
    <?php endif; ?>
</div>

            
        </div>
        
        <div class="tab-pane fade" id="product-add-comment" role="tabpanel" aria-labelledby="product-add-comment-tab">
            
            <form id="add-comment-form">
                
                <input type="hidden" id="materi_id" name="materi_id" value="<?= ($data_materi != false && isset($data_materi)) ? $data_materi->id : ''; ?>">
                <input type="hidden" name="username" value="<?=  session()->get('username'); ?>" >

                <div class="form-group">
                    <label for="rating">Rating</label>
                    <select name="rating" id="rating" class="form-control form-control-sm" required>
                        <option value="">Pilih Rating</option>
                        <option value="5">⭐⭐⭐⭐⭐ 5 Bintang</option>
                        <option value="4">⭐⭐⭐⭐ 4 Bintang</option>
                        <option value="3">⭐⭐⭐ 3 Bintang</option>
                        <option value="2">⭐⭐ 2 Bintang</option>
                        <option value="1">⭐ 1 Bintang</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="comments">Komentar</label>
                    <textarea name="comments" id="comments" class="form-control" rows="3" placeholder="Tulis komentar Anda di sini..." required></textarea>
                </div>

                <button type="submit" class="btn btn-success btn-sm" id="submit-comment-btn">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Komentar
                </button>
            </form>

        </div>
        </div>
        </div>


        </div>
        <!-- /.card-body -->
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

  
 

</div>

  <!-- Main Footer -->
<?php include('footer.php'); ?>

<?php include('modal_customer_services.php'); ?>
<?php include('modal_isi_ulang_saldo.php'); ?>
<?php include('modal_konfirmasi_pembayaran.php'); ?>

<!-- ./wrapper -->

<!-- REQUIRED SCRIPTS -->

<!-- jQuery -->
<script src="/assets/js/jquery371.min.js"></script>
<script src="/assets/js/jquery-ui.min.js"></script>

<script src="/assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- SweetAlert2 -->
<script src="/assets/js/sweetalert2@11.js"></script>
<script src="/assets/js/adminlte.js"></script>

<!-- OPTIONAL SCRIPTS -->
<script src="/assets/js/cleave.min.js"></script>
<script src="/assets/vendor/chart.js/Chart.min.js"></script>
<script src="/assets/js/settings.js"></script>
<script src="/assets/js/customer-services.js"></script>
<script src="/assets/js/single-materi.js"></script>

<script src="/assets/js/pages/dashboard3.js"></script>
<script src="/assets/js/saldo.js"></script>
</body>
</html>
