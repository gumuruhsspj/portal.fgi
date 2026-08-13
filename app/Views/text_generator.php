<!DOCTYPE html>
<html lang="en">
<?php $rand = "?v=" . rand(0, 2000); ?>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Text Generator - Promotor</title>
    <link href="<?= base_url() ?>assets/img/favicon.ico" rel="icon">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/styles-custom-homepage.css">
    <link rel="stylesheet" href="<?= base_url() ?>assets/css/styles-custom-portal.css">
    <style>
        .result-section {
            border-left: 4px solid #007bff;
            padding: 15px;
            margin-bottom: 20px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .copy-btn {
            cursor: pointer;
        }

        .copy-btn.copied {
            background-color: #28a745;
            color: white;
            border-color: #28a745;
        }

        .loading-spinner {
            display: none;
            margin-top: 20px;
            text-align: center;
        }

        .spinner-border {
            width: 3rem;
            height: 3rem;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include('nav_menu_upper.php'); ?>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <?php include('brand_logo.php'); ?>
            <div class="sidebar">
                <?php include('nav_menu_promotor.php'); ?>
            </div>
        </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Text Generator </h1>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-8">
                            <!-- Form -->
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Buat Pesan Promosi</h3>
                                </div>
                                <div class="card-body">
                                    <form id="textGeneratorForm">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Target Pelanggan</label>
                                                    <select name="customer_type" class="form-control" required>
                                                        <option value="karyawan">Karyawan</option>
                                                        <option value="ibu_rumah_tangga">Ibu Rumah Tangga</option>
                                                        <option value="owner_bisnis">Owner Bisnis</option>
                                                        <option value="umum">Umum</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>Status Prospect</label>
                                                    <select name="prospect_status" class="form-control" required>
                                                        <option value="aware">Baru Tahu</option>
                                                        <option value="interested">Tertarik</option>
                                                        <option value="considering">Membandingkan</option>
                                                        <option value="ready">Siap Beli</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Jumlah Variasi</label>
                                                    <input type="number" name="variasi" class="form-control" value="3" min="1" max="10" required>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Program Afiliasi</label>
                                                    <select name="program_id" class="form-control" required>
                                                        <option value="">-- Pilih --</option>
                                                        <?php if (!empty($memberships)): ?>
                                                            <?php foreach ($memberships as $m): ?>
                                                                <option value="<?= $m['id_program_afiliasi'] ?>"><?= esc($m['program_nama']) ?></option>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <option value="">Anda belum join program afiliasi</option>
                                                        <?php endif; ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" name="use_iconic" value="1" id="useIconic">
                                                            <label class="form-check-label" for="useIconic">Pakai Iconic</label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input" type="checkbox" name="include_wa" value="1" id="includeWA" checked>
                                                            <label class="form-check-label" for="includeWA">Sertakan WA</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary btn-lg btn-block" id="generateBtn">
                                            <i class="fas fa-magic"></i> Generate
                                        </button>
                                    </form>

                                    <!-- Loading -->
                                    <div class="loading-spinner" id="loadingSpinner">
                                        <div class="spinner-border text-primary" role="status">
                                            <span class="sr-only">Loading...</span>
                                        </div>
                                        <p class="mt-2">AI sedang menulis pesan promosi...</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Tips</h3>
                                </div>
                                <div class="card-body">
                                    <ul>
                                        <li>Pilih target pelanggan yang sesuai dengan produk.</li>
                                        <li>Status prospect menentukan tone pesan.</li>
                                        <li>Semakin banyak variasi, semakin banyak pilihan.</li>
                                        <li>Hasil bisa disalin langsung ke clipboard.</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Hasil -->
                    <div class="row" id="resultContainer" style="display: none;">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Hasil Generate</h3>
                                    <div class="card-tools">
                                        <button class="btn btn-sm btn-secondary" id="clearResults"><i class="fas fa-times"></i> Bersihkan</button>
                                    </div>
                                </div>
                                <div class="card-body" id="resultBody">
                                    <!-- Dinamis -->
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <?php include('footer.php'); ?>
        <?php include('modal_customer_services.php'); ?>
    </div>

    <script src="<?= base_url() ?>assets/js/jquery371.min.js<?= $rand; ?>"></script>
    <script src="<?= base_url() ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js<?= $rand; ?>"></script>
    <script src="<?= base_url() ?>assets/js/adminlte.js<?= $rand; ?>"></script>
    <script src="<?= base_url() ?>assets/js/sweetalert2@11.js<?= $rand; ?>"></script>
    <script src="<?= base_url() ?>assets/js/timer.js<?= $rand; ?>"></script>

    <script>
        $(document).ready(function() {
            $('#textGeneratorForm').submit(function(e) {
                e.preventDefault();

                // Validasi program afiliasi
                var prog = $('select[name="program_id"]').val();
                if (!prog) {
                    Swal.fire('Peringatan', 'Silakan pilih program afiliasi terlebih dahulu.', 'warning');
                    return;
                }

                // Tampilkan loading
                $('#loadingSpinner').show();
                $('#generateBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Generating...');
                $('#resultContainer').hide();

                var formData = $(this).serialize();

                $.ajax({
                    url: '<?= base_url('affiliasi/text-generator/generate') ?>',
                    method: 'POST',
                    data: formData,
                    dataType: 'json',
                    success: function(res) {
                        $('#loadingSpinner').hide();
                        $('#generateBtn').prop('disabled', false).html('<i class="fas fa-magic"></i> Generate');

                        if (res.error) {
                            Swal.fire('Error', res.error, 'error');
                            return;
                        }

                        if (res.success && res.variasi.length > 0) {
                            displayResults(res.variasi);
                            $('#resultContainer').show();
                        } else {
                            Swal.fire('Info', 'Tidak ada hasil yang dihasilkan. Coba lagi.', 'info');
                        }
                    },
                    error: function(xhr, status, error) {
                        $('#loadingSpinner').hide();
                        $('#generateBtn').prop('disabled', false).html('<i class="fas fa-magic"></i> Generate');
                        Swal.fire('Error', 'Terjadi kesalahan: ' + error, 'error');
                    }
                });
            });

            function displayResults(variasi) {
                var html = '';
                variasi.forEach(function(item, index) {
                    var teks = item.teks.replace(/\n/g, '<br>');
                    html += `
        <div class="result-section" id="result-${index}">
          <div class="d-flex justify-content-between align-items-start">
            <h5><span class="badge badge-primary">Variasi ${item.nomor}</span></h5>
            <button class="btn btn-sm btn-outline-primary copy-btn" data-target="result-${index}">
              <i class="fas fa-copy"></i> Salin
            </button>
          </div>
          <div class="mt-2">${teks}</div>
        </div>
      `;
                });
                $('#resultBody').html(html);
            }

            // Copy function
            $(document).on('click', '.copy-btn', function() {
                var targetId = $(this).data('target');
                var textElement = $('#' + targetId + ' .mt-2');
                var text = textElement.text().trim();

                // Salin ke clipboard
                navigator.clipboard.writeText(text).then(function() {
                    var btn = $('#' + targetId).find('.copy-btn');
                    var originalHtml = btn.html();
                    btn.html('<i class="fas fa-check"></i> Tersalin!').addClass('btn-success').removeClass('btn-outline-primary');
                    setTimeout(function() {
                        btn.html(originalHtml).removeClass('btn-success').addClass('btn-outline-primary');
                    }, 2000);
                }).catch(function() {
                    // Fallback
                    var temp = $('<textarea>');
                    $('body').append(temp);
                    temp.val(text).select();
                    document.execCommand('copy');
                    temp.remove();
                    var btn = $('#' + targetId).find('.copy-btn');
                    btn.html('<i class="fas fa-check"></i> Tersalin!').addClass('btn-success').removeClass('btn-outline-primary');
                    setTimeout(function() {
                        btn.html(originalHtml).removeClass('btn-success').addClass('btn-outline-primary');
                    }, 2000);
                });
            });

            // Clear results
            $('#clearResults').click(function() {
                $('#resultContainer').hide();
                $('#resultBody').empty();
            });
        });
    </script>

</body>

</html>