<!DOCTYPE html>
<html lang="en">
<?php $rand = "?v=" . rand(0, 1000); ?>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Program Afiliasi (<?= $usertype; ?>) Portal - Kursus Komputer</title>
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
    <?php include('nav_menu_upper.php'); ?>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <?php include('brand_logo.php'); ?>
      <div class="sidebar">
        <?php if ($usertype == 'peserta') :
          include('nav_menu_user.php');
        elseif ($usertype == 'promotor') :
          include('nav_menu_promotor.php');
        else :
          include('nav_menu_admin.php');
        endif; ?>
      </div>
    </aside>

    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
              <h1>Program Afiliasi</h1>
            </div>
          </div>
        </div>
      </div>

      <div class="content">
        <div class="container-fluid">
          <?php if (empty($programs)): ?>
            <div class="alert alert-info">Belum ada program afiliasi aktif.</div>
          <?php else: ?>
            <div class="row">
              <?php foreach ($programs as $p): ?>
                <div class="col-md-6 col-lg-4">
                  <div class="card card-outline card-primary">
                    <div class="card-header">
                      <h3 class="card-title"><?= esc($p['nama']) ?></h3>
                      <span class="badge badge-info float-right">Total Member: <?= $p['total_member'] ?? 0 ?></span>
                    </div>
                    <div class="card-body">
                      <p><?= esc($p['deskripsi']) ?></p>
                      <hr>
                      <strong>Kategori & Komisi:</strong>
                      <ul>
                        <?php if (!empty($p['kategori_nama_list'])): ?>
                          <?php for ($i = 0; $i < count($p['kategori_nama_list']); $i++): ?>
                            <li><?= esc($p['kategori_nama_list'][$i]) ?> – <?= $p['komisi_list'][$i] ?>%</li>
                          <?php endfor; ?>
                        <?php else: ?>
                          <li>Belum ada kategori</li>
                        <?php endif; ?>
                      </ul>
                      <div class="mt-3">
                        <?php if ($p['user_status']['status'] == 'joined'): ?>
                          <span class="badge badge-success">Anda sudah bergabung</span>
                          <br>
                          <strong>Kode Referal Anda:</strong>
                          <span class="badge badge-dark"><?= esc($p['user_status']['kode_referal']) ?></span>
                          <button class="btn btn-sm btn-secondary copy-btn" data-kode="<?= esc($p['user_status']['kode_referal']) ?>">Salin</button>
                        <?php else: ?>
                          <button class="btn btn-sm btn-primary join-btn" data-program="<?= $p['id'] ?>">Gabung Program</button>
                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>
          <?php endif; ?>
        </div>
      </div>
    </div>

    <?php include('footer.php'); ?>
  </div>

  <?php include('modal_customer_services.php'); ?>
  <?php include('modal_usulan_materi.php'); ?>
  <?php include('modal_isi_ulang_saldo.php'); ?>
  <?php include('modal_konfirmasi_pembayaran.php'); ?>


  <!-- ./wrapper -->

  <!-- REQUIRED SCRIPTS -->

  <!-- jQuery -->
  <script src="<?= base_url() ?>assets/js/jquery371.min.js<?= $rand; ?>"></script>
  <script src="<?= base_url() ?>assets/js/jquery-ui.min.js<?= $rand; ?>"></script>
  <!-- Bootstrap -->
  <script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js<?= $rand; ?>"></script>
  <!-- AdminLTE -->
  <script src="<?= base_url() ?>assets/js/sweetalert2@11.js<?= $rand; ?>"></script>
  <script src="<?= base_url() ?>assets/js/adminlte.js<?= $rand; ?>"></script>

  <!-- OPTIONAL SCRIPTS -->
  <script src="<?= base_url() ?>assets/js/cleave.min.js<?= $rand; ?>"></script>
  <script src="<?= base_url() ?>assets/vendor/chart.js/Chart.min.js<?= $rand; ?>"></script>
  <script src="<?= base_url() ?>assets/js/settings.js<?= $rand; ?>"></script>
  <script src="<?= base_url() ?>assets/js/customer-services.js<?= $rand; ?>"></script>
  <script src="<?= base_url() ?>assets/js/manage-daily-notes.js<?= $rand; ?>"></script>
  <script src="<?= base_url() ?>assets/js/timer.js<?= $rand; ?>"></script>
  <script src="<?= base_url() ?>assets/vendor/jquery-calendar/calendar.min.js<?= $rand; ?>"></script>
  <script src="<?= base_url() ?>assets/js/pages/dashboard3.js<?= $rand; ?>"></script>
  <script src="<?= base_url() ?>assets/js/saldo.js<?= $rand; ?>"></script>

  <script defer src="<?= base_url() ?>assets/vendor/fontawesome-free/js/all.js<?= $rand; ?>"></script>
  <script>
    $(document).ready(function() {
      // Join program
      $('.join-btn').click(function() {
        var id_program = $(this).data('program');
        var btn = $(this);
        $.ajax({
          url: '/program-afiliasi/join',
          method: 'POST',
          data: {
            id_program: id_program
          },
          dataType: 'json',
          success: function(res) {
            if (res.status == 'success') {
              Swal.fire('Berhasil', res.message, 'success').then(() => {
                location.reload();
              });
            } else {
              Swal.fire('Gagal', res.message, 'error');
            }
          },
          error: function() {
            Swal.fire('Error', 'Terjadi kesalahan', 'error');
          }
        });
      });

      // Copy kode referal
      $('.copy-btn').click(function() {
        var kode = $(this).data('kode');
        navigator.clipboard.writeText(kode).then(function() {
          Swal.fire('Tersalin', 'Kode referal: ' + kode, 'success');
        }).catch(function() {
          // Fallback
          var temp = $('<input>');
          $('body').append(temp);
          temp.val(kode).select();
          document.execCommand('copy');
          temp.remove();
          Swal.fire('Tersalin', 'Kode referal: ' + kode, 'success');
        });
      });
    });
  </script>

</body>

</html>