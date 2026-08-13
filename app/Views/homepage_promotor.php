<!DOCTYPE html>
<html lang="en">
<?php $rand = "?v=" . rand(0, 2000); ?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Home (<?= $usertype; ?>) Portal - Kursus Komputer</title>
  <link href="<?= base_url() ?>assets/img/favicon.ico" rel="icon">
  <link href="<?= base_url() ?>assets/img/favicon.ico" rel="apple-touch-icon">

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/fontawesome-free/css/all.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/adminlte.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/styles-custom-homepage.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/styles-custom-portal.css">
  <!-- DataTables -->
  <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/datatables/dataTables.dataTables.min.css">
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <!-- Navbar -->
    <?php include('nav_menu_upper.php'); ?>

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <?php include('brand_logo.php'); ?>
      <div class="sidebar">
        <!-- Sidebar menu khusus promotor -->
        <?php include('nav_menu_promotor.php'); ?>
      </div>
    </aside>

    <!-- Content Wrapper -->
    <div class="content-wrapper">
      <!-- Content Header -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1 class="m-0">Dashboard Promotor</h1>
            </div>
            <div class="col-sm-6">
              <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
              </ol>
            </div>
          </div>
        </div>
      </div>

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">

          <!-- Statistik -->
          <div class="row">
            <div class="col-lg-3 col-6">
              <div class="small-box bg-info">
                <div class="inner">
                  <h3><?= $total_programs ?? 0 ?></h3>
                  <p>Total Program Afiliasi</p>
                </div>
                <div class="icon"><i class="ion ion-bag"></i></div>
                <a href="<?= base_url('program-afiliasi') ?>" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box bg-success">
                <div class="inner">
                  <h3><?= $total_referred ?? 0 ?></h3>
                  <p>User Direferensikan</p>
                </div>
                <div class="icon"><i class="ion ion-person-add"></i></div>
                <a href="#table-referred" class="small-box-footer">Lihat Daftar <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3>Rp <?= number_format($total_earnings ?? 0, 0, ',', '.') ?></h3>
                  <p>Total Pendapatan</p>
                </div>
                <div class="icon"><i class="ion ion-pie-graph"></i></div>
                <a href="<?= base_url('riwayat-saldo') ?>" class="small-box-footer">Lihat Riwayat <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
            <div class="col-lg-3 col-6">
              <div class="small-box bg-danger">
                <div class="inner">
                  <h3><?= count($share_links ?? []) ?></h3>
                  <p>Link Share Aktif</p>
                </div>
                <div class="icon"><i class="ion ion-share"></i></div>
                <a href="#share-links" class="small-box-footer">Salin Link <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>
          </div>

          <!-- Share Links -->
          <div class="row" id="share-links">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-link"></i> Link Referal Anda</h3>
                </div>
                <div class="card-body">
                  <?php if (!empty($share_links)): ?>
                    <div class="row">
                      <?php foreach ($share_links as $link): ?>
                        <div class="col-md-6 col-lg-4 mb-3">
                          <div class="input-group">
                            <span class="input-group-text"><strong><?= esc($link->program) ?></strong></span>
                            <input type="text" class="form-control" value="<?= esc($link->url) ?>" id="share-link-<?= $link->kode ?>" readonly>
                            <button class="btn btn-primary copy-link" data-link="<?= esc($link->url) ?>" data-kode="<?= $link->kode ?>">
                              <i class="fas fa-copy"></i>
                            </button>
                          </div>
                          <small class="text-muted">Kode: <?= esc($link->kode) ?></small>
                        </div>
                      <?php endforeach; ?>
                    </div>
                  <?php else: ?>
                    <p class="text-muted">Anda belum bergabung dengan program afiliasi manapun. <a href="<?= base_url('program-afiliasi') ?>">Gabung sekarang</a>.</p>
                  <?php endif; ?>
                </div>
              </div>
            </div>
          </div>

          <!-- Tabel User Direferensikan -->
          <div class="row" id="table-referred">
            <div class="col-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title"><i class="fas fa-users"></i> Daftar User yang Mendaftar Melalui Anda</h3>
                </div>
                <div class="card-body table-responsive">
                  <table id="referred-table" class="table table-striped">
                    <thead>
                      <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Program Afiliasi</th>
                        <th>Tanggal Daftar</th>
                      </tr>
                    </thead>
                    <tbody>
                      <?php if (!empty($referred_users)): ?>
                        <?php $no = 1;
                        foreach ($referred_users as $user): ?>
                          <tr>
                            <td><?= $no++ ?></td>
                            <td><?= esc($user->username) ?></td>
                            <td><?= esc($user->email) ?></td>
                            <td><?= esc($user->program) ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($user->date_created)) ?></td>
                          </tr>
                        <?php endforeach; ?>
                      <?php else: ?>
                        <tr>
                          <td colspan="5" class="text-center">Belum ada user yang mendaftar melalui referal Anda.</td>
                        </tr>
                      <?php endif; ?>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>

        </div><!-- /.container-fluid -->
      </div><!-- /.content -->
    </div><!-- /.content-wrapper -->

    <!-- Footer & Modals -->
    <?php include('footer.php'); ?>
    <?php include('modal_customer_services.php'); ?>
    <?php include('modal_isi_ulang_saldo.php'); ?>
    <?php include('modal_konfirmasi_pembayaran.php'); ?>
    <!-- Modal untuk Text Generator (placeholder) -->
    <div class="modal fade" id="textGeneratorModal" tabindex="-1">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Text Generator (AI Message)</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body">
            <p class="text-muted">Fitur ini akan segera hadir untuk membantu Anda membuat pesan promosi.</p>
            <!-- placeholder -->
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- Scripts -->
  <script src="<?= base_url() ?>assets/js/jquery371.min.js<?= $rand; ?>"></script>
  <script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js<?= $rand; ?>"></script>
  <script src="<?= base_url() ?>assets/js/adminlte.js<?= $rand; ?>"></script>
  <script src="<?= base_url() ?>assets/js/sweetalert2@11.js<?= $rand; ?>"></script>
  <script src="<?= base_url() ?>assets/vendor/datatables/jquery.dataTables.min.js<?= $rand; ?>"></script>
  <script>
    $(document).ready(function() {
      // DataTable
      $('#referred-table').DataTable({
        "order": [
          [4, "desc"]
        ],
        "language": {
          "url": "https://cdn.datatables.net/plug-ins/1.13.4/i18n/id.json"
        }
      });

      // Copy link
      $('.copy-link').click(function() {
        var link = $(this).data('link');
        var kode = $(this).data('kode');
        navigator.clipboard.writeText(link).then(function() {
          Swal.fire('Tersalin!', 'Link referal untuk kode ' + kode + ' telah disalin.', 'success');
        }).catch(function() {
          // fallback
          var temp = $('<input>');
          $('body').append(temp);
          temp.val(link).select();
          document.execCommand('copy');
          temp.remove();
          Swal.fire('Tersalin!', 'Link referal untuk kode ' + kode + ' telah disalin.', 'success');
        });
      });
    });
  </script>
</body>

</html>