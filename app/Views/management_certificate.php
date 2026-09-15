<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Management Certificate - Portal</title>
  <link href="<?= base_url(); ?>assets/img/favicon.ico" rel="icon">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/adminlte.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/styles-custom-portal.css">
</head>

<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <?php include('nav_menu_upper.php'); ?>
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <?php include('brand_logo.php'); ?>
      <div class="sidebar"><?php include('nav_menu_admin.php'); ?></div>
    </aside>

    <div class="content-wrapper">
      <div class="content-header">
        <div class="container-fluid">
          <h1 class="m-0">Management Certificate</h1>
          <p class="text-muted">Template sertifikat per materi + per paket.</p>
        </div>
      </div>

      <div class="content">
        <div class="container-fluid">
          <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
              <div class="d-flex align-items-center gap-2">
                <label class="mb-0">Filter Materi:</label>
                <select id="filter-materi" class="form-select form-select-sm" style="width:auto;">
                  <option value="">-- Semua Materi --</option>
                  <?php foreach (($materi_filter ?? []) as $m): ?>
                    <option value="<?= $m->id ?>" <?= $selected_materi == $m->id ? 'selected' : '' ?>>
                      <?= esc($m->judul) ?>
                    </option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div>
                <a href="#" id="btn-add-template" class="btn btn-sm btn-primary">
                  <i class="fas fa-plus"></i> Tambah Template
                </a>
              </div>
            </div>

            <div class="card-body table-responsive p-0">
              <table class="table table-hover table-striped">
                <thead>
                  <tr>
                    <th>#</th>
                    <th>Nama Template</th>
                    <th>Materi</th>
                    <th>Paket</th>
                    <th>Mode</th>
                    <th>Ukuran</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $n = 1;
                  foreach (($templates ?? []) as $t): ?>
                    <tr>
                      <td><?= $n++ ?></td>
                      <td><strong><?= esc($t->nama_template) ?></strong></td>
                      <td><?= esc($t->judul_materi) ?></td>
                      <td>
                        <span class="badge bg-secondary"><?= esc($t->paket) ?></span>
                        <?php if ($t->id_custom_materi): ?>
                          <small class="text-muted">#<?= (int)$t->id_custom_materi ?></small>
                        <?php endif; ?>
                      </td>
                      <td>
                        <?php if ($t->side_mode === 'double'): ?>
                          <span class="badge bg-info">Double</span>
                        <?php else: ?>
                          <span class="badge bg-light text-dark">Single</span>
                        <?php endif; ?>
                      </td>
                      <td>
                        <small>F: <?= $t->front_width ?>×<?= $t->front_height ?></small>
                        <?php if ($t->side_mode === 'double'): ?>
                          <br><small>B: <?= $t->back_width ?>×<?= $t->back_height ?></small>
                        <?php endif; ?>
                      </td>
                      <td>
                        <span class="badge bg-<?= $t->status === 'active' ? 'success' : 'secondary' ?>">
                          <?= esc($t->status) ?>
                        </span>
                      </td>
                      <td>
                        <a class="btn btn-sm btn-info" href="<?= base_url('manage/certificate/editor?materi_id=' . $t->id_materi . '&id=' . $t->id) ?>">
                          <i class="fas fa-edit"></i> Edit
                        </a>
                        <a class="btn btn-sm btn-success" target="_blank"
                          href="<?= base_url('manage/certificate/preview/' . $t->id) ?>">
                          <i class="fas fa-eye"></i> Preview
                        </a>
                        <button class="btn btn-sm btn-danger btn-delete-tpl" data-id="<?= $t->id ?>">
                          <i class="fas fa-trash"></i>
                        </button>
                      </td>
                    </tr>
                  <?php endforeach; ?>
                  <?php if (empty($templates)): ?>
                    <tr>
                      <td colspan="8" class="text-center text-muted py-4">
                        Belum ada template. Klik <b>Tambah Template</b>.
                      </td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <?php include('footer.php'); ?>
    </div>
  </div>

  <script src="<?= base_url(); ?>assets/js/jquery371.min.js"></script>
  <script src="<?= base_url(); ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/sweetalert2@11.js<?= $random ?>"></script>
  <script>
    const _URL_MAIN_WEBSITE = "<?= base_url(); ?>";
    const _SELECTED_MATERI = "<?= (int)($selected_materi ?? 0) ?>";
  </script>
  <script src="<?= base_url(); ?>assets/js/manage-certificate.js<?= $random ?>"></script>
  <script src="<?= base_url(); ?>assets/js/adminlte.js<?= $random ?>"></script>
</body>

</html>