<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home (<?= $usertype; ?>) Portal - Kursus Komputer</title>
  <link href="<?= base_url() ?>assets/img/favicon.ico" rel="icon">
  <link href="<?= base_url() ?>assets/img/favicon.ico" rel="apple-touch-icon">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/fontawesome-free/css/all.min.css">
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/adminlte.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/styles-custom-homepage.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/styles-custom-portal.css">
  <!-- for calendar purposes -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-calendar/style.css" />
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/jquery-calendar/theme.css" />

</head>


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
                <a href="<?= base_url(); ?>all-materi" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3><?= $total_progress_materi ?? 0; ?><sup style="font-size: 20px">%</sup></h3>
                  <p>
                    Progress Materi Anda
                    <?php if (isset($total_materi_completed) && isset($total_materi_enrolled)): ?>
                      <br><small style="font-size:.75rem;opacity:.85;">
                        <?= $total_materi_completed; ?> / <?= $total_materi_enrolled; ?> materi selesai
                      </small>
                    <?php endif; ?>
                  </p>
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
                    <?php if (isset($data_daily_notes)): ?>
                      <?php if ($data_daily_notes != false): ?>
                        <?php foreach ($data_daily_notes as $dnotes): ?>

                          <li>
                            <!-- drag handle -->

                            <!-- checkbox -->
                            <div class="icheck-primary d-inline ml-2">
                              <input type="checkbox" value="done" id="" class="todoCheck" data-id="<?= $dnotes->id; ?>">
                              <label class="label" for="todoCheck" data-id="<?= $dnotes->id; ?>"><?= $dnotes->notes; ?></label>
                              <input type="text" class="isian text-todoCheck" data-id="<?= $dnotes->id; ?>" />
                            </div>
                            <span class="text"></span>

                            <!-- General tools such as edit or delete-->
                            <div class="tools">
                              <i class="edit-todoCheck fas fa-edit"></i>
                              <i data-id="<?= $dnotes->id; ?>" class="delete-todoCheck fas fa-trash"></i>
                            </div>
                          </li>
                        <?php endforeach; ?>
                      <?php endif; ?>
                    <?php endif; ?>

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

          <!-- ===================================================== -->
          <!-- CARD STATUS QUIZ & SERTIFIKAT                         -->
          <!-- ===================================================== -->
          <div class="row mt-3">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">
                    <i class="fas fa-clipboard-check text-primary mr-1"></i>
                    Status Quiz &amp; Sertifikat
                  </h3>
                  <div class="card-tools">
                    <span class="badge badge-success">
                      <i class="fas fa-award"></i> <?= $total_sertifikat ?? 0; ?> Sertifikat
                    </span>
                  </div>
                </div>
                <div class="card-body p-0">

                  <?php if (!empty($quiz_attempts)): ?>
                    <div class="table-responsive">
                      <table class="table table-sm table-hover mb-0">
                        <thead class="bg-light">
                          <tr>
                            <th style="width:60px;" class="text-center">#</th>
                            <th>Materi</th>
                            <th style="width:140px;" class="text-center">Status</th>
                            <th style="width:100px;" class="text-center">Skor</th>
                            <th style="width:180px;" class="text-center">Aksi</th>
                          </tr>
                        </thead>
                        <tbody>
                          <?php $no = 1;
                          foreach ($quiz_attempts as $q): ?>
                            <?php
                            $is_graded   = ($q->status === 'graded');
                            $can_release = ($q->rilis_sertifikat ?? 'no') === 'yes';
                            $score       = $is_graded
                              ? number_format((float)$q->final_score, 0)
                              : '—';
                            ?>
                            <tr>
                              <td class="text-center align-middle"><?= $no++; ?></td>
                              <td class="align-middle">
                                <div class="d-flex align-items-center">
                                  <?php if (!empty($q->icon)): ?>
                                    <img src="<?= base_url('assets/img/uploads/materi/' . $q->icon); ?>"
                                      style="width:36px;height:36px;object-fit:cover;border-radius:6px;margin-right:10px;">
                                  <?php else: ?>
                                    <div class="bg-secondary d-flex align-items-center justify-content-center"
                                      style="width:36px;height:36px;border-radius:6px;margin-right:10px;">
                                      <i class="fas fa-book text-white"></i>
                                    </div>
                                  <?php endif; ?>
                                  <div>
                                    <strong><?= esc($q->judul_materi ?? '-'); ?></strong><br>
                                    <small class="text-muted">
                                      <i class="far fa-clock"></i>
                                      <?= date('d M Y H:i', strtotime($q->date_created)); ?>
                                    </small>
                                  </div>
                                </div>
                              </td>
                              <td class="text-center align-middle">
                                <?php if ($is_graded): ?>
                                  <span class="badge badge-success">
                                    <i class="fas fa-check-circle"></i> Dinilai
                                  </span>
                                <?php else: ?>
                                  <span class="badge badge-warning">
                                    <i class="fas fa-hourglass-half"></i> Menunggu Penilaian
                                  </span>
                                <?php endif; ?>
                              </td>
                              <td class="text-center align-middle">
                                <?php if ($is_graded): ?>
                                  <span class="badge badge-info" style="font-size:1rem;padding:6px 12px;">
                                    <?= $score; ?>
                                  </span>
                                <?php else: ?>
                                  <span class="text-muted">—</span>
                                <?php endif; ?>
                              </td>
                              <td class="text-center align-middle">
                                <?php if ($is_graded && $can_release): ?>
                                  <a href="<?= base_url('materi/certificate/' . $q->id_materi); ?>"
                                    class="btn btn-sm btn-primary">
                                    <i class="fas fa-download"></i> Unduh Sertifikat
                                  </a>
                                <?php elseif ($is_graded && !$can_release): ?>
                                  <span class="text-muted small">
                                    <i class="fas fa-info-circle"></i> Sertifikat belum dirilis
                                  </span>
                                <?php else: ?>
                                  <span class="text-muted small">
                                    <i class="fas fa-lock"></i> Menunggu nilai
                                  </span>
                                <?php endif; ?>
                              </td>
                            </tr>
                          <?php endforeach; ?>
                        </tbody>
                      </table>
                    </div>
                  <?php else: ?>
                    <div class="text-center p-4 text-muted">
                      <i class="fas fa-clipboard fa-2x mb-2"></i>
                      <p class="mb-0">Belum ada quiz yang Anda kerjakan.</p>
                      <small>Kerjakan materi sampai selesai untuk membuka quiz.</small>
                    </div>
                  <?php endif; ?>

                </div>
                <?php if (!empty($quiz_attempts)): ?>
                  <div class="card-footer text-right">
                    <a href="<?= base_url('materi-terpilih'); ?>" class="btn btn-sm btn-outline-primary">
                      <i class="fas fa-list"></i> Lihat Semua Materi
                    </a>
                  </div>
                <?php endif; ?>
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
  <script src="<?= base_url() ?>assets/js/jquery371.min.js<?= $random; ?>"></script>
  <script src="<?= base_url() ?>assets/js/jquery-ui.min.js<?= $random; ?>"></script>
  <!-- Bootstrap -->
  <script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js<?= $random; ?>"></script>
  <!-- AdminLTE -->
  <script src="<?= base_url() ?>assets/js/adminlte.js<?= $random; ?>"></script>
  <script src="<?= base_url() ?>assets/js/sweetalert2@11.js<?= $random; ?>"></script>

  <!-- OPTIONAL SCRIPTS -->
  <script src="<?= base_url() ?>assets/js/cleave.min.js<?= $random; ?>"></script>
  <script src="<?= base_url() ?>assets/vendor/chart.js/Chart.min.js<?= $random; ?>"></script>
  <script src="<?= base_url() ?>assets/js/settings.js<?= $random; ?>"></script>
  <script src="<?= base_url() ?>assets/js/customer-services.js<?= $random; ?>"></script>
  <script src="<?= base_url() ?>assets/js/manage-daily-notes.js<?= $random; ?>"></script>
  <script src="<?= base_url() ?>assets/js/timer.js<?= $random; ?>"></script>
  <script src="<?= base_url() ?>assets/vendor/jquery-calendar/calendar.min.js<?= $random; ?>"></script>
  <script src="<?= base_url() ?>assets/js/pages/dashboard3.js<?= $random; ?>"></script>
  <script src="<?= base_url() ?>assets/js/saldo.js<?= $random; ?>"></script>

  <script defer src="<?= base_url() ?>assets/vendor/fontawesome-free/js/all.js<?= $random; ?>"></script>


</body>

</html>