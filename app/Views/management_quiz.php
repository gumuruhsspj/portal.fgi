<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Management Quiz (<?= $usertype; ?>) Portal - Kursus Komputer</title>
  <link href="<?= base_url(); ?>assets/img/favicon.ico" rel="icon">
  <link href="<?= base_url(); ?>assets/img/favicon.ico" rel="apple-touch-icon">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/google-fonts.css">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/datatables/dataTables.dataTables.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css">

  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/trix.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/adminlte.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/styles-custom-homepage.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/styles-custom-portal.css">



  <style>
    .quiz-section {
      border: 1px solid #e3e6ef;
      border-radius: 10px;
      overflow: hidden;
      background: #fff;
      transition: box-shadow .15s;
    }

    .quiz-section:hover {
      box-shadow: 0 2px 12px rgba(0, 0, 0, .05);
    }

    .quiz-section.ui-sortable-helper {
      box-shadow: 0 12px 32px rgba(0, 0, 0, .15);
      transform: rotate(-0.5deg);
    }

    .section-header {
      background: #eef3fa;
      padding: 10px 14px;
      border-bottom: 1px solid #e3e6ef;
      display: flex;
      align-items: center;
      gap: 8px;
    }

    .section-drag-handle {
      cursor: grab;
      padding: 4px 6px;
      border-radius: 4px;
      transition: background .15s;
    }

    .section-drag-handle:hover {
      background: #dbe7f5;
    }

    .section-drag-handle:active {
      cursor: grabbing;
    }

    .section-body {
      background: #f8f9fc;
      min-height: 70px;
      padding: 12px;
      transition: background .15s;
    }

    .section-body.ui-sortable-over {
      background: #e7f3ff;
    }

    .section-body .card-item.ui-sortable-placeholder {
      visibility: visible !important;
      border: 2px dashed #007bff;
      background: #e7f3ff;
      border-radius: 8px;
      min-height: 80px;
    }

    .section-body:empty::after {
      content: "Drop soal di sini...";
      display: flex;
      align-items: center;
      justify-content: center;
      height: 60px;
      color: #98a2b3;
      font-style: italic;
      font-size: .9rem;
    }

    .section-count {
      font-size: .75rem;
    }

    .quiz-section[data-is-ungrouped="1"] .section-header {
      background: #fff4e0;
    }

    .drag-handle {
      cursor: grab;
      padding: 4px 6px;
      border-radius: 4px;
      transition: background 0.15s;
    }

    .drag-handle:hover {
      background: #eef3fa;
    }

    .drag-handle:active {
      cursor: grabbing;
    }

    .order-badge {
      font-size: 0.85rem;
      padding: 4px 8px;
    }

    .card-item.ui-sortable-helper {
      opacity: 0.85;
      box-shadow: 0 10px 28px rgba(0, 0, 0, 0.2);
      transform: rotate(-1deg);
    }

    .card-item.ui-sortable-placeholder {
      visibility: visible !important;
      border: 2px dashed #007bff;
      background: #e7f3ff;
      border-radius: 8px;
      min-height: 180px;
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
                  <h3 class="card-title">Total Overall: <span id="total_data" data-jumlah="<?= $jumlah_data; ?>"><?= (isset($management_data) && $management_data != false) ? count($management_data) : 0; ?> soal quiz.</span></h3>
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

                <!-- ========== QUIZ GROUP PANEL ========== -->
                <div class="card-body border-bottom bg-light" id="group-panel">
                  <div class="d-flex justify-content-between align-items-center mb-2">
                    <h5 class="mb-0"><i class="fas fa-layer-group"></i> Grup Quiz (kategori sertifikat)</h5>
                    <button class="btn btn-sm btn-primary" id="add-group">
                      <i class="fas fa-plus"></i> Tambah Grup
                    </button>
                  </div>
                  <div id="group-list" class="row g-2">
                    <!-- JS render disini -->
                  </div>
                </div>
                <!-- ========== END QUIZ GROUP PANEL ========== -->


                <input type="hidden" id="id_materi_group_ctx" value="<?= $id_materi; ?>">

                <script>
                  window.__QUIZ_DATA__ = <?= json_encode(
                                            $quiz_payload,
                                            JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
                                          ) ?>;
                </script>

                <div class="card-body">
                  <div id="section-container">
                    <!-- JS akan render sections disini -->
                  </div>
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
  <script src="<?= base_url(); ?>assets/js/jquery371.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/jquery-ui.min.js<?= $random; ?>"></script>

  <!-- Bootstrap -->
  <script src="<?= base_url(); ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url(); ?>assets/vendor/datatables/jquery.dataTables.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/sweetalert2@11.js<?= $random; ?>"></script>

  <script type="text/javascript">
    const _URL_MAIN_WEBSITE = "<?= base_url(); ?>";
  </script>


  <script src="<?= base_url(); ?>assets/js/manage-quiz.js<?= $random; ?>"></script>
  <script src="<?= base_url(); ?>assets/js/settings.js<?= $random; ?>"></script>
  <script src="<?= base_url(); ?>assets/js/customer-services.js<?= $random; ?>"></script>
  <script src="<?= base_url(); ?>assets/js/timer.js"></script>
  <script src="<?= base_url(); ?>assets/js/trix.umd.min.js<?= $random; ?>"></script>
  <script src="<?= base_url(); ?>assets/js/adminlte.js<?= $random; ?>"></script>
  <script src="<?= base_url(); ?>assets/js/pages/dashboard3.js<?= $random; ?>"></script>

</body>

</html>