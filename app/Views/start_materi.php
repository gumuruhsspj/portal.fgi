<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= isset($title) ? $title : 'Error'; ?> (<?= $usertype; ?>) Portal - Kursus Komputer</title>
  <link href="<?= base_url(); ?>assets/img/favicon.ico" rel="icon">
  <link href="<?= base_url(); ?>assets/img/favicon.ico" rel="apple-touch-icon">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/fontawesome-free/css/all.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/fontawesome-free/css/fontawesome.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/adminlte.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/styles-custom-homepage.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/styles-custom-portal.css">

  <style>
    .chapter-body .custom-checkbox {
      padding: 4px 6px 4px 26px;
      border-radius: 4px;
      transition: background-color 0.15s ease;
    }

    .chapter-body .custom-checkbox:hover {
      background-color: #f3f6fa;
    }

    .chapter-body .custom-checkbox.active-item {
      background-color: #e7f3ff;
      border-left: 3px solid #007bff;
    }

    .chapter-body .custom-checkbox.active-item label {
      font-weight: 600;
      color: #007bff;
    }

    .nav-header-grid {
      display: grid;
      grid-template-columns: 110px 1fr 110px;
      align-items: center;
      gap: 8px;
    }

    .nav-header-left {
      justify-self: start;
    }

    .nav-header-right {
      justify-self: end;
    }

    .materi-icon-wrapper {
      padding: 6px 0;
    }

    .materi-icon-display {
      max-width: 110px;
      max-height: 110px;
      width: auto;
      height: auto;
      border-radius: 8px;
      pointer-events: none;
      /* tidak bisa diklik */
      user-select: none;
      /* tidak bisa di-select/drag */
      -webkit-user-drag: none;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }
  </style>

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
        <img src="<?= base_urL() ?>assets/img/fgroupindonesia.jpg" alt="FGroupIndonesia Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
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
              <h1 class="m-0">Materi : <?= $title; ?></h1>

            </div><!-- /.col -->
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="#">Materi</a></li>
                <li class="breadcrumb-item"><a href="#"><?= $title; ?></a></li>
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

                <p><?= isset($error) ? $error : ''; ?></p>

                <?php if (!isset($error) && !empty($data_detail_materi)): ?>
                  <br>

                  <div class="card card-info col-md-3">
                    <div class="card-header">
                      <h3 class="card-title">
                        Poin Pembahasan
                      </h3>
                    </div>

                    <?php if (isset($data_detail_materi)) : ?>
                      <div class="card-body">

                        <?php if (!empty($materi_icon)): ?>
                          <div class="text-center mb-4 mt-2 materi-icon-wrapper">
                            <img
                              src="<?= base_url('assets/img/uploads/materi/' . $materi_icon); ?>"
                              alt="Icon Materi"
                              class="materi-icon-display">
                          </div>
                        <?php endif; ?>

                        <div class="card card-info card-outline">

                          <div class="card-body">
                            <?php if (!empty($data_chapter)) : ?>
                              <?php foreach ($data_chapter as $chapter): ?>
                                <div class="chapter-block mb-3">
                                  <div class="chapter-header" style="cursor:pointer;">
                                    <strong>
                                      <i class="fas fa-folder text-warning mr-1 chapter-icon"></i>
                                      <?= $chapter->judul; ?>
                                    </strong>
                                  </div>

                                  <div class="chapter-body mt-1 ml-3">
                                    <?php if (!empty($chapter->pembahasan)): ?>
                                      <?php
                                      $total_pb = count($chapter->pembahasan);
                                      foreach ($chapter->pembahasan as $k => $p):
                                        $has_back = ($k > 0) ? "true" : "false";
                                        $has_next = ($k < $total_pb - 1) ? "true" : "false";
                                      ?>
                                        <div class="custom-control custom-checkbox">
                                          <input data-id="<?= $p->id; ?>" class="custom-control-input" type="checkbox" disabled>
                                          <label
                                            data-has-next="<?= $has_next ?>"
                                            data-has-back="<?= $has_back ?>"
                                            data-target-id="<?= $p->id; ?>"
                                            class="custom-control-label"><?= $p->judul; ?></label>
                                        </div>
                                      <?php endforeach; ?>
                                    <?php else: ?>
                                      <small class="text-muted font-italic">Belum ada sub pembahasan.</small>
                                    <?php endif; ?>
                                  </div>
                                </div>
                              <?php endforeach; ?>
                            <?php else: ?>
                              <small class="text-muted font-italic">Belum ada topik pembahasan.</small>
                            <?php endif; ?>
                          </div>
                        </div>

                        <?php if (isset($url_alive)): ?>
                          <button data-url="<?= $url_alive; ?>" data-id="<?= $id_materi; ?>" type="button" class="btn btn-primary btn-connect">
                            <i class="fas fa-video"></i> CONNECT
                          </button>
                        <?php endif; ?>
                        <button data-id="<?= $id_materi; ?>" type="button" class="btn btn-success btn-complete"><i class="fas fa-check"></i> SAYA SELESAI</button>
                      </div>
                    <?php endif; ?>
                  </div>


                  <div class="col-md-9">
                    <div class="card card-primary card-outline">
                      <div class="card-header">
                        <div class="nav-header-grid">
                          <div class="nav-header-left">
                            <button type="button" class="btn btn-default btn-sm btn-back btn-nav" title="Back">
                              <i class="fas fa-reply"></i> Back
                            </button>
                          </div>
                          <h3 class="m-0 text-center" id="judul-detail-materi">Permulaan</h3>
                          <div class="nav-header-right">
                            <button data-target-id="<?= isset($data_detail_materi[0]) ? $data_detail_materi[0]->id_pembahasan : ''; ?>" type="button" class="btn btn-default btn-sm btn-next btn-nav" title="Next">
                              Next <i class="fas fa-share"></i>
                            </button>
                          </div>
                        </div>
                      </div>

                      <!-- /.card-header -->
                      <div class="card-body p-0">

                        <div class="mailbox-controls with-border text-right">
                          <button type="button" class="btn-print btn btn-default btn-sm" title="Print">
                            <i class="fas fa-print"></i> Print
                          </button>
                        </div>
                        <!-- /.mailbox-controls -->
                        <div class="mailbox-read-message">
                          <p>Deskripsi</p>
                        </div>
                        <!-- /.mailbox-read-message -->
                      </div>
                      <!-- /.card-body -->
                      <div class="card-footer bg-white">
                        <ul class="mailbox-attachments d-flex align-items-stretch clearfix">
                          <li>
                            <span class="mailbox-attachment-icon"><i class="far fa-file-pdf"></i></span>

                            <div class="mailbox-attachment-info">
                              <a href="#" data-id="<?= $id_materi; ?>" class="link-download mailbox-attachment-name"><i class="fas fa-paperclip"></i> <?= $data_detail_materi[0]->attachment; ?></a>
                              <span class="mailbox-attachment-size clearfix mt-1">
                                <span><?= $data_file_size; ?></span>
                                <a href="#" data-id="<?= $id_materi; ?>" class="btn btn-default btn-sm float-right link-download"><i class="fas fa-cloud-download-alt"></i></a>
                              </span>
                            </div>
                          </li>

                        </ul>

                        <div id="deskripsi-detail-materi">
                          <p> <?= $data_detail_materi[0]->deskripsi_utama; ?> </p>
                        </div>

                      </div>
                      <!-- /.card-footer -->
                      <div class="card-footer">
                        <div class="float-right">
                          <button type="button" class="btn btn-default btn-back btn-nav"><i class="fas fa-reply"></i> Back</button>
                          <button data-target-id="<?= isset($data_detail_materi[0]) ? $data_detail_materi[0]->id_pembahasan : ''; ?>" type="button" class="btn btn-default btn-next btn-nav"><i class="fas fa-share"></i> Next</button>
                          <button data-id="<?= $id_materi; ?>" type="button" class="btn btn-success btn-complete"><i class="fas fa-check"></i> SAYA SELESAI</button>
                        </div>

                        <button type="button" class="btn btn-default btn-print"><i class="fas fa-print"></i> Print</button>
                      </div>
                      <!-- /.card-footer -->
                    </div>
                    <!-- /.card -->
                  </div>

                <?php endif; ?>
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
    <?php include('modal_usulan_materi.php'); ?>
    <?php include('modal_customer_services.php'); ?>

    <!-- REQUIRED SCRIPTS -->


    <!-- jQuery -->
    <script src="<?= base_url(); ?>assets/js/jquery371.min.js<?= $random; ?>"></script>
    <script src="<?= base_url(); ?>assets/js/jquery-ui.min.js<?= $random; ?>"></script>
    <script src="<?= base_url(); ?>assets/js/sweetalert2@11.js<?= $random; ?>"></script>

    <!-- Bootstrap -->
    <script src="<?= base_url(); ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js<?= $random; ?>"></script>
    <!-- AdminLTE -->
    <script src="<?= base_url(); ?>assets/js/adminlte.js<?= $random; ?>"></script>

    <!-- OPTIONAL SCRIPTS -->
    <script type="text/javascript">
      const _URL_MAIN_WEBSITE = "<?= base_url(); ?>";
    </script>
    <script src="<?= base_url(); ?>assets/vendor/chart.js/Chart.min.js<?= $random; ?>"></script>
    <script src="<?= base_url(); ?>assets/js/settings.js<?= $random; ?>"></script>
    <script src="<?= base_url(); ?>assets/js/customer-services.js<?= $random; ?>"></script>
    <script src="<?= base_url(); ?>assets/js/start-materi.js<?= $random; ?>"></script>
    <script src="<?= base_url(); ?>assets/js/timer.js<?= $random; ?>"></script>
    <script src="<?= base_url(); ?>assets/js/pages/dashboard3.js<?= $random; ?>"></script>

</body>

</html>