<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <title>Certificate Editor</title>
  <link href="<?= base_url(); ?>assets/img/favicon.ico" rel="icon">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/adminlte.min.css">
  <style>
    .canvas-wrap {
      position: relative;
      border: 2px dashed #cfd4dd;
      background: #f5f7fb;
      overflow: auto;
      max-height: 70vh;
      border-radius: 6px;
    }

    .canvas-inner {
      position: relative;
      display: inline-block;
      line-height: 0;
      background: #fff;
    }

    .canvas-inner img {
      display: block;
      user-select: none;
      pointer-events: none;
    }

    .var-overlay {
      position: absolute;
      cursor: grab;
      padding: 2px 6px;
      border: 1px dashed #007bff;
      background: rgba(0, 123, 255, 0.08);
      white-space: nowrap;
      font-family: Arial, sans-serif;
      user-select: none;
      transform: translate(0, -100%);
      /* Y=baseline */
    }

    .var-overlay.selected {
      outline: 2px solid #ff5722;
      background: rgba(255, 87, 34, 0.12);
    }

    .var-overlay.dragging {
      cursor: grabbing;
      opacity: 0.8;
    }

    .editor-side-tabs .nav-link {
      cursor: pointer;
    }

    .var-list .var-item {
      display: flex;
      align-items: center;
      gap: 6px;
      padding: 4px 6px;
      border-radius: 4px;
      font-size: .85rem;
      cursor: pointer;
    }

    .var-list .var-item:hover {
      background: #eef3fa;
    }

    .var-list .var-item.active {
      background: #dbe7f5;
    }
  </style>
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
          <h1 class="m-0">Certificate Editor</h1>
          <p class="text-muted">Materi: <strong><?= esc($data_materi->judul) ?></strong></p>
        </div>
      </div>

      <div class="content">
        <div class="container-fluid">
          <div class="row">
            <!-- LEFT: FORM -->
            <div class="col-md-4">
              <div class="card">
                <div class="card-header"><strong>Template Info</strong></div>
                <div class="card-body">
                  <input type="hidden" id="tpl_id" value="<?= $template->id ?? '' ?>">
                  <input type="hidden" id="id_materi" value="<?= $id_materi ?>">

                  <div class="mb-2">
                    <label>Nama Template</label>
                    <input type="text" id="nama_template" class="form-control" value="<?= esc($template->nama_template ?? '') ?>">
                  </div>

                  <div class="mb-2">
                    <label>Orientation</label>
                    <select id="orientation" class="form-select">
                      <option value="auto" <?= ($template->orientation ?? 'auto') === 'auto'      ? 'selected' : '' ?>>Auto (dari gambar)</option>
                      <option value="portrait" <?= ($template->orientation ?? '') === 'portrait'  ? 'selected' : '' ?>>Portrait</option>
                      <option value="landscape" <?= ($template->orientation ?? '') === 'landscape' ? 'selected' : '' ?>>Landscape</option>
                    </select>
                  </div>

                  <div class="mb-2">
                    <label>Paket</label>
                    <select id="paket" class="form-select">
                      <option value="paket_belajar_sendiri" <?= ($template->paket ?? '') === 'paket_belajar_sendiri' ? 'selected' : '' ?>>Paket Belajar Sendiri</option>
                      <option value="paket_bimbingan" <?= ($template->paket ?? '') === 'paket_bimbingan' ? 'selected' : '' ?>>Paket Bimbingan</option>
                      <option value="paket_kasus_custom" <?= ($template->paket ?? '') === 'paket_kasus_custom' ? 'selected' : '' ?>>Paket Kasus Custom</option>
                    </select>
                  </div>

                  <div class="mb-2" id="custom-wrap" style="<?= ($template->paket ?? '') === 'paket_kasus_custom' ? '' : 'display:none' ?>">
                    <label>Custom Materi (opsional)</label>
                    <select id="id_custom_materi" class="form-select">
                      <option value="">-- Pilih Custom Materi --</option>
                      <?php foreach (($materi_custom_list ?? []) as $mc): ?>
                        <option value="<?= $mc->id ?>" <?= ($template->id_custom_materi ?? '') == $mc->id ? 'selected' : '' ?>>
                          <?= esc($mc->nama_template) ?>
                        </option>
                      <?php endforeach; ?>
                    </select>
                  </div>

                  <div class="mb-2">
                    <label>Side Mode</label>
                    <select id="side_mode" class="form-select">
                      <option value="single" <?= ($template->side_mode ?? 'single') === 'single' ? 'selected' : '' ?>>Single Side</option>
                      <option value="double" <?= ($template->side_mode ?? '') === 'double' ? 'selected' : '' ?>>Double Side</option>
                    </select>
                  </div>

                  <hr>
                  <label class="small text-muted">Front Image (base)</label>
                  <input type="file" id="front_image" class="form-control mb-2" accept="image/*">
                  <?php if (!empty($template->front_image)): ?>
                    <div class="small text-success">
                      <i class="fas fa-check"></i> Sudah ada: <?= $template->front_width ?>×<?= $template->front_height ?>
                    </div>
                  <?php endif; ?>

                  <div id="back-upload-wrap" style="<?= ($template->side_mode ?? 'single') === 'double' ? '' : 'display:none' ?>">
                    <label class="small text-muted">Back Image (opsional)</label>
                    <input type="file" id="back_image" class="form-control mb-2" accept="image/*">
                    <?php if (!empty($template->back_image)): ?>
                      <div class="small text-success">
                        <i class="fas fa-check"></i> Sudah ada: <?= $template->back_width ?>×<?= $template->back_height ?>
                      </div>
                    <?php endif; ?>
                  </div>

                  <hr>
                  <label>Add Variable</label>
                  <div class="input-group input-group-sm mb-2">
                    <select id="var_type" class="form-select">
                      <option value="nama_user">Nama User</option>
                      <option value="tanggal_cetak">Tanggal Cetak</option>
                      <option value="judul_materi">Judul Materi</option>
                      <option value="nilai">Nilai Akhir</option>
                      <option value="custom">Custom Text</option>
                    </select>
                    <button class="btn btn-primary" id="btn-add-var" type="button">
                      <i class="fas fa-plus"></i> Add
                    </button>
                  </div>

                  <div class="var-list mt-2" id="var-list"></div>

                  <hr>
                  <button id="btn-save" class="btn btn-success w-100">
                    <i class="fas fa-save"></i> Simpan Template
                  </button>
                </div>
              </div>
            </div>

            <!-- RIGHT: CANVAS -->
            <div class="col-md-8">
              <div class="card">
                <div class="card-header p-0">
                  <ul class="nav nav-tabs editor-side-tabs" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" id="tab-front" data-side="front">Front</a>
                    </li>
                    <li class="nav-item" id="tab-back-li" style="<?= ($template->side_mode ?? 'single') === 'double' ? '' : 'display:none' ?>">
                      <a class="nav-link" id="tab-back" data-side="back">Back</a>
                    </li>
                  </ul>
                </div>
                <div class="card-body">
                  <div class="mb-2 small text-muted">
                    <i class="fas fa-info-circle"></i>
                    Klik variable lalu drag untuk mengatur posisi. Posisi disimpan dalam pixel gambar ASLI.
                  </div>
                  <div class="canvas-wrap" id="canvas-wrap">
                    <div class="canvas-inner" id="canvas-inner">
                      <img id="canvas-img" src="" alt="Upload image dulu">
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <?php include('footer.php'); ?>
    </div>
  </div>

  <script src="<?= base_url(); ?>assets/js/jquery371.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/jquery-ui.min.js"></script>
  <script src="<?= base_url(); ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?= base_url(); ?>assets/js/sweetalert2@11.js"></script>
  <script>
    const _URL_MAIN_WEBSITE = "<?= base_url(); ?>";
    window.__TPL_DATA__ = {
      id: <?= (int)($template->id ?? 0) ?>,
      side_mode: "<?= $template->side_mode ?? 'single' ?>",
      front_image: "<?= !empty($template->front_image) ? base_url($template->front_image) : '' ?>",
      front_width: <?= (int)($template->front_width ?? 0) ?>,
      front_height: <?= (int)($template->front_height ?? 0) ?>,
      front_variables: <?= $template->front_variables ?? '[]' ?>,
      back_image: "<?= !empty($template->back_image) ? base_url($template->back_image) : '' ?>",
      back_width: <?= (int)($template->back_width ?? 0) ?>,
      back_height: <?= (int)($template->back_height ?? 0) ?>,
      back_variables: <?= $template->back_variables ?? '[]' ?>,
    };
  </script>
  <script src="<?= base_url(); ?>assets/js/certificate-editor.js"></script>
  <script src="<?= base_url(); ?>assets/js/adminlte.js"></script>
</body>

</html>