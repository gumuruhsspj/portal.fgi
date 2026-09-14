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

                <div id="card-mode" class="row mt-4" style="<?= empty($management_data) ? 'display:none;' : '' ?>">
                  <!-- JS akan append card-card disini -->
                  <?php if (!empty($management_data)) : ?>
                    <?php $nomor = 1; ?>
                    <?php foreach ($management_data as $data) : ?>
                      <div class="col-md-6 mb-4 card-item" data-id-materi="<?= $data->id_materi; ?>" data-id="<?= $data->id; ?>">
                        <div class="card h-100">
                          <div class="card-body">
                            <div class="d-flex align-items-center gap-2 mb-3">
                              <input type="checkbox" class="form-check-input is-selected">
                              <span class="drag-handle" title="Drag untuk pindah">
                                <i class="fas fa-grip-vertical text-muted"></i>
                              </span>
                              <span class="order-badge badge bg-secondary">#<?= str_pad($nomor, 2, '0', STR_PAD_LEFT) ?></span>
                            </div>

                            <label>Pertanyaan:</label>
                            <textarea class="form-control mb-2 pertanyaan" placeholder="tulis Pertanyaan disini"><?= $data->pertanyaan ?? '' ?></textarea>
                            <!-- Jenis Soal -->
                            <select class="form-select mb-2 jenis-soal">
                              <option value="essay" <?= isset($data->jenis) && $data->jenis == 'essay' ? 'selected' : '' ?>>Essay</option>
                              <option value="pg2" <?= isset($data->jenis) && $data->jenis == 'pg2' ? 'selected' : '' ?>>PG 2 opsi</option>
                              <option value="pg4" <?= isset($data->jenis) && $data->jenis == 'pg4' ? 'selected' : '' ?>>PG 4 opsi</option>
                            </select>

                            <!-- Opsi PG -->
                            <div class="pg-opsi mb-2" style="<?= isset($data->jenis) && $data->jenis != 'essay' ? '' : 'display:none;' ?>">
                              <?php if (isset($data->jenis) && $data->jenis == 'pg2'): ?>
                                <input type="text" class="form-control mb-1 opsi-a" placeholder="Opsi A" value="<?= $data->opsi_a ?? '' ?>">
                                <input type="text" class="form-control mb-1 opsi-b" placeholder="Opsi B" value="<?= $data->opsi_b ?? '' ?>">
                              <?php elseif (isset($data->jenis) && $data->jenis == 'pg4'): ?>
                                <input type="text" class="form-control mb-1 opsi-a" placeholder="Opsi A" value="<?= $data->opsi_a ?? '' ?>">
                                <input type="text" class="form-control mb-1 opsi-b" placeholder="Opsi B" value="<?= $data->opsi_b ?? '' ?>">
                                <input type="text" class="form-control mb-1 opsi-c" placeholder="Opsi C" value="<?= $data->opsi_c ?? '' ?>">
                                <input type="text" class="form-control mb-1 opsi-d" placeholder="Opsi D" value="<?= $data->opsi_d ?? '' ?>">
                              <?php endif; ?>
                            </div>

                            <!-- Keterangan -->
                            <textarea class="form-control mb-2 keterangan" placeholder="Keterangan"><?= $data->keterangan ?? '' ?></textarea>

                            <!-- Final Answer -->
                            <div class="mb-2 jawaban-akhir-part <?= isset($data->jenis) && $data->jenis == 'essay' ? 'd-none' : '' ?>">
                              <label>Jawaban Final:</label>
                              <select class="form-select mb-2 jawaban-akhir">
                                <?php
                                $jenis_na = $data->jenis ?? 'essay';
                                $opsi_list = ['A' => 'Opsi A', 'B' => 'Opsi B'];
                                if ($jenis_na === 'pg4') {
                                  $opsi_list['C'] = 'Opsi C';
                                  $opsi_list['D'] = 'Opsi D';
                                }
                                foreach ($opsi_list as $val => $label):
                                ?>
                                  <option value="<?= $val ?>" <?= (isset($data->final_answer) && $data->final_answer == $val) ? 'selected' : '' ?>>
                                    <?= $label ?>
                                  </option>
                                <?php endforeach; ?>
                              </select>
                            </div>

                            <!-- Buttons -->
                            <button class="btn btn-sm btn-danger delete-card" data-id="<?= $data->id; ?>">Delete</button>
                            <button class="btn btn-sm btn-success float-end save-card" data-id="<?= $data->id; ?>">Save</button>
                          </div>
                        </div>
                      </div>
                      <?php $nomor++; ?>
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