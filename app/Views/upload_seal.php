<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document Sealer - Upload</title>
    <!-- Bootstrap 5 & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        .card-sealer {
            border: none;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.85);
            max-width: 800px;
            margin: 3rem auto;
            padding: 2rem;
        }

        .dropzone {
            border: 3px dashed #6c757d;
            border-radius: 20px;
            padding: 3rem 2rem;
            text-align: center;
            cursor: pointer;
            transition: all 0.3s ease;
            background: #f8f9fa;
            position: relative;
        }

        .dropzone.dragover {
            border-color: #0d6efd;
            background: #e9f0ff;
            transform: scale(1.02);
        }

        .dropzone i {
            font-size: 4rem;
            color: #6c757d;
            margin-bottom: 1rem;
        }

        #fileInput {
            display: none;
        }

        .progress-container {
            display: none;
            margin-top: 1.5rem;
        }

        .progress {
            height: 25px;
            border-radius: 50px;
            background: #e9ecef;
            overflow: hidden;
        }

        .progress-bar {
            background: linear-gradient(90deg, #0d6efd, #0dcaf0);
            font-weight: 600;
            transition: width 0.4s ease;
        }

        .upload-btn {
            background: linear-gradient(135deg, #0d6efd, #0a58ca);
            border: none;
            padding: 12px 40px;
            border-radius: 50px;
            font-weight: 600;
            letter-spacing: 0.5px;
            transition: all 0.3s;
        }

        .upload-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(13, 110, 253, 0.4);
        }

        .upload-btn:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }

        .info-icon {
            color: #0d6efd;
            margin-right: 8px;
        }

        .file-preview {
            display: none;
            margin-top: 1.5rem;
            padding: 1rem;
            background: #e9f0ff;
            border-radius: 15px;
            align-items: center;
            gap: 1rem;
        }

        .file-preview .file-icon {
            font-size: 2.5rem;
            color: #0d6efd;
        }

        .file-preview .file-detail {
            flex: 1;
        }

        .file-preview .file-detail .name {
            font-weight: 600;
        }

        .file-preview .file-detail .size {
            font-size: 0.85rem;
            color: #6c757d;
        }

        .file-preview .remove-btn {
            background: none;
            border: none;
            color: #dc3545;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0 10px;
        }

        .footer-text {
            text-align: center;
            margin-top: 2rem;
            color: #6c757d;
            font-size: 0.9rem;
        }

        .alert-custom {
            border-radius: 15px;
            border-left: 5px solid #0d6efd;
        }

        @media (max-width: 576px) {
            .card-sealer {
                padding: 1.5rem;
                margin: 1rem;
            }

            .dropzone {
                padding: 2rem 1rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="card-sealer">
            <h2 class="text-center mb-4">
                <i class="fas fa-file-signature text-primary me-2"></i>
                Document Sealer
            </h2>
            <p class="text-center text-muted mb-4">
                Unggah dokumen Anda dan dapatkan versi terverifikasi dengan QR Code & Watermark.
            </p>

            <!-- Alert messages -->
            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show alert-custom" role="alert">
                    <i class="fas fa-exclamation-circle me-2"></i> <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show alert-custom" role="alert">
                    <i class="fas fa-check-circle me-2"></i> <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form id="uploadForm" action="https://apps.fgroupindonesia.com/portal/upload-seal/process" method="post" enctype="multipart/form-data">
                <?= csrf_field() ?>

                <!-- Owner Name -->
                <div class="mb-3">
                    <label for="ownerName" class="form-label fw-semibold">
                        <i class="fas fa-user info-icon"></i> Nama Pemilik Dokumen
                    </label>
                    <input type="text" class="form-control form-control-lg" id="ownerName" name="owner_name"
                        placeholder="Masukkan nama Anda" required value="<?= old('owner_name') ?>">
                    <small class="text-muted">Nama ini akan tercantum di dalam dokumen sebagai pemilik terverifikasi.</small>
                </div>

                <!-- Dropzone -->
                <div class="dropzone" id="dropzone">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <h5 class="mt-2">Seret & Letakkan file di sini</h5>
                    <p class="text-muted">atau klik untuk memilih file</p>
                    <p class="mb-0"><span class="badge bg-primary me-1">DOCX</span> <span class="badge bg-success me-1">XLSX</span> <span class="badge bg-warning text-dark me-1">PPTX</span> <span class="badge bg-danger">PDF</span></p>
                    <input type="file" id="fileInput" name="document" accept=".docx,.xlsx,.pptx,.pdf">
                </div>

                <!-- File Preview -->
                <div class="file-preview" id="filePreview">
                    <div class="file-icon"><i class="fas fa-file-alt"></i></div>
                    <div class="file-detail">
                        <div class="name" id="fileName">nama_file.docx</div>
                        <div class="size" id="fileSize">2.4 MB</div>
                    </div>
                    <button type="button" class="remove-btn" id="removeFileBtn"><i class="fas fa-times-circle"></i></button>
                </div>

                <!-- Progress Bar -->
                <div class="progress-container" id="progressContainer">
                    <div class="d-flex justify-content-between mb-1">
                        <span class="fw-semibold" id="progressLabel">Mengunggah...</span>
                        <span id="progressPercent">0%</span>
                    </div>
                    <div class="progress">
                        <div class="progress-bar" id="progressBar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="d-grid gap-2 mt-4">
                    <button type="submit" class="btn btn-primary btn-lg upload-btn" id="submitBtn" disabled>
                        <i class="fas fa-shield-alt me-2"></i> Unggah
                    </button>
                </div>
            </form>

            <div class="footer-text">
                <i class="fas fa-lock me-1"></i> Dokumen akan diproteksi secara khusus.
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        $(document).ready(function() {
            var baseUrl = 'https://apps.fgroupindonesia.com/portal';

            var dropzone = $('#dropzone');
            var fileInput = $('#fileInput');
            var filePreview = $('#filePreview');
            var fileName = $('#fileName');
            var fileSize = $('#fileSize');
            var removeBtn = $('#removeFileBtn');
            var submitBtn = $('#submitBtn');
            var form = $('#uploadForm');
            var progressContainer = $('#progressContainer');
            var progressBar = $('#progressBar');
            var progressLabel = $('#progressLabel');
            var progressPercent = $('#progressPercent');

            var selectedFile = null;

            // Trigger file browse saat dropzone diklik (mencegah loop/double trigger jika target sudah fileInput)
            dropzone.on('click', function(e) {
                if (e.target.id !== 'fileInput') {
                    fileInput.trigger('click');
                }
            });

            // Drag & Drop event handling
            dropzone.on('dragover dragenter', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.addClass('dragover');
            });

            dropzone.on('dragleave dragend', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.removeClass('dragover');
            });

            dropzone.on('drop', function(e) {
                e.preventDefault();
                e.stopPropagation();
                dropzone.removeClass('dragover');

                var files = e.originalEvent.dataTransfer.files;
                if (files.length > 0) {
                    // Set file ke elemen input secara manual agar tersimpan di DOM
                    var dt = new DataTransfer();
                    dt.items.add(files[0]);
                    fileInput[0].files = dt.files;

                    handleFile(files[0]);
                }
            });

            // File input change
            fileInput.on('change', function() {
                if (this.files.length > 0) {
                    handleFile(this.files[0]);
                }
            });

            // Validasi & Tampilan Preview
            function handleFile(file) {
                var allowed = ['docx', 'xlsx', 'pptx', 'pdf'];
                var ext = file.name.split('.').pop().toLowerCase();
                if (!allowed.includes(ext)) {
                    alert('Tipe file tidak didukung. Gunakan: ' + allowed.join(', '));
                    resetFileInput();
                    return;
                }

                selectedFile = file;

                var sizeMB = (file.size / (1024 * 1024)).toFixed(2);
                var sizeStr = sizeMB + ' MB';
                if (file.size < 1024 * 1024) {
                    sizeStr = (file.size / 1024).toFixed(0) + ' KB';
                }

                fileName.text(file.name);
                fileSize.text(sizeStr);
                filePreview.css('display', 'flex');
                submitBtn.prop('disabled', false);

                var icon = filePreview.find('.file-icon i');
                if (ext === 'pdf') icon.attr('class', 'fas fa-file-pdf');
                else if (['docx', 'doc'].includes(ext)) icon.attr('class', 'fas fa-file-word');
                else if (['xlsx', 'xls'].includes(ext)) icon.attr('class', 'fas fa-file-excel');
                else if (['pptx', 'ppt'].includes(ext)) icon.attr('class', 'fas fa-file-powerpoint');
                else icon.attr('class', 'fas fa-file-alt');
            }

            // Hapus file terpilih
            removeBtn.on('click', function() {
                resetFileInput();
            });

            function resetFileInput() {
                fileInput.val('');
                selectedFile = null;
                filePreview.css('display', 'none');
                submitBtn.prop('disabled', true);
                progressContainer.hide();
                progressBar.css('width', '0%');
                progressPercent.text('0%');
                progressLabel.text('Mengunggah...');
            }

            // Handle Submit Form dengan jQuery AJAX
            form.on('submit', function(e) {
                e.preventDefault();

                if (!selectedFile) {
                    alert('Harap pilih file terlebih dahulu.');
                    return;
                }

                var owner = $('#ownerName').val().trim();
                if (!owner) {
                    alert('Harap isi nama pemilik dokumen.');
                    $('#ownerName').focus();
                    return;
                }

                // Siapkan data Form
                var formData = new FormData(this);

                progressContainer.show();
                submitBtn.prop('disabled', true);
                submitBtn.html('<i class="fas fa-spinner fa-spin me-2"></i> Memproses...');

                $.ajax({
                    url: form.attr('action'),
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    xhr: function() {
                        var xhr = $.ajaxSettings.xhr();
                        if (xhr.upload) {
                            xhr.upload.addEventListener('progress', function(e) {
                                if (e.lengthComputable) {
                                    var percent = Math.round((e.loaded / e.total) * 100);
                                    progressBar.css('width', percent + '%');
                                    progressPercent.text(percent + '%');
                                    progressLabel.text(percent < 100 ? 'Mengunggah...' : 'Memproses dokumen...');
                                }
                            }, false);
                        }
                        return xhr;
                    },
                    success: function(response) {
                        // Jika respon berupa objek JSON
                        if (typeof response === 'object') {
                            if (response.redirect) {
                                window.location.href = response.redirect;
                            } else if (response.success && response.document_id) {
                                window.location.href = baseUrl + '/result/' + response.document_id;
                            } else {
                                alert('Gagal memproses: ' + (response.message || 'Error tidak diketahui'));
                                resetState();
                            }
                        } else {
                            // Jika response berupa String HTML / Text
                            try {
                                var resJson = JSON.parse(response);
                                if (resJson.redirect) {
                                    window.location.href = resJson.redirect;
                                } else if (resJson.document_id) {
                                    window.location.href = baseUrl + '/result/' + resJson.document_id;
                                } else {
                                    window.location.href = baseUrl;
                                }
                            } catch (err) {
                                var match = response.match(/\/result\/([A-Z0-9]{16})/i);
                                if (match) {
                                    window.location.href = baseUrl + '/result/' + match[1];
                                } else {
                                    // Fallback sukses standard
                                    alert('Upload berhasil!');
                                    resetState();
                                }
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        alert('Terjadi kesalahan server (' + xhr.status + '): ' + (error || 'Periksa jaringan Anda'));
                        resetState();
                    }
                });
            });

            function resetState() {
                progressContainer.hide();
                submitBtn.prop('disabled', false);
                submitBtn.html('<i class="fas fa-shield-alt me-2"></i> Sealing Dokumen');
                progressBar.css('width', '0%');
                progressPercent.text('0%');
                progressLabel.text('Mengunggah...');
            }
        });
    </script>
</body>

</html>