<!-- Modal Media Promosi -->
<div class="modal fade" id="mediaModal" tabindex="-1" aria-labelledby="mediaModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="mediaModalLabel">Tambah Media Promosi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="formMedia" enctype="multipart/form-data" data-mode="add">
                    <input type="hidden" name="id" id="mediaId">
                    <input type="hidden" name="config" id="mediaConfig">

                    <div class="row">
                        <div class="col-md-6">
                            <!-- Nama -->
                            <div class="mb-3">
                                <label>Nama <span class="text-danger">*</span></label>
                                <input type="text" name="nama" id="mediaNama" class="form-control" required>
                            </div>

                            <!-- Kategori -->
                            <div class="mb-3">
                                <label>Kategori <span class="text-danger">*</span></label>
                                <select name="id_kategori" id="mediaKategori" class="form-control" required>
                                    <option value="">-- Pilih --</option>
                                </select>
                            </div>

                            <!-- Upload Gambar -->
                            <div class="mb-3">
                                <label>Upload Gambar <span class="text-danger">*</span></label>
                                <input type="file" name="image" id="mediaImage" class="form-control" accept="image/*">
                                <small class="text-muted">Max 2MB (jpg, jpeg, png)</small>
                                <div id="previewImageOldContainer" style="display:none; margin-top:5px;">
                                    <img id="previewImageOld" src="" style="max-height:100px;">
                                    <span class="badge bg-warning">Gambar lama</span>
                                </div>
                            </div>

                            <!-- Form konfigurasi -->
                            <div class="row">
                                <div class="col-12">
                                    <h6 class="border-bottom pb-2">Konfigurasi WA</h6>
                                </div>
                                <div class="col-6">
                                    <label>Teks</label>
                                    <input type="text" id="waText" class="form-control form-control-sm" value="Klik untuk chat">
                                </div>
                                <div class="col-3">
                                    <label>X</label>
                                    <input type="number" id="waX" class="form-control form-control-sm" value="50">
                                </div>
                                <div class="col-3">
                                    <label>Y</label>
                                    <input type="number" id="waY" class="form-control form-control-sm" value="50">
                                </div>
                                <div class="col-4">
                                    <label>Font Size (px)</label>
                                    <input type="number" id="waFontSize" class="form-control form-control-sm" value="24">
                                </div>
                                <div class="col-4">
                                    <label>Warna</label>
                                    <input type="color" id="waColor" class="form-control form-control-sm" value="#ffffff">
                                </div>
                            </div>

                            <hr>

                            <div class="row">
                                <div class="col-12">
                                    <h6 class="border-bottom pb-2">Konfigurasi QR</h6>
                                </div>
                                <div class="col-3">
                                    <label>X</label>
                                    <input type="number" id="qrX" class="form-control form-control-sm" value="200">
                                </div>
                                <div class="col-3">
                                    <label>Y</label>
                                    <input type="number" id="qrY" class="form-control form-control-sm" value="200">
                                </div>
                                <div class="col-3">
                                    <label>Lebar</label>
                                    <input type="number" id="qrWidth" class="form-control form-control-sm" value="80">
                                </div>
                                <div class="col-3">
                                    <label>Tinggi</label>
                                    <input type="number" id="qrHeight" class="form-control form-control-sm" value="80">
                                </div>
                            </div>
                        </div>

                        <!-- Preview -->
                        <div class="col-md-6">
                            <div id="previewArea">
                                <p class="text-muted">Preview akan muncul setelah upload gambar.</p>
                                <div id="previewContainer" style="position:relative; display:none; border:1px solid #ddd; min-height:300px;">
                                    <img id="previewImage" src="" style="max-width:100%; display:block;">
                                    <!-- WA Element -->
                                    <div id="waElement" style="position:absolute; background:rgba(0,0,0,0.6); padding:8px 15px; border-radius:30px; color:white; display:none; font-weight:bold; user-select:none; white-space:nowrap;" class="draggable-element">
                                        Klik untuk chat
                                        <div class="resize-handle"></div>
                                    </div>
                                    <!-- QR Element -->
                                    <div id="qrElement" style="position:absolute; background:#fff; border:2px solid #333; display:none; text-align:center; line-height:80px; font-size:12px; color:#666;" class="draggable-element">
                                        QR
                                        <div class="resize-handle"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-primary" id="btnSaveMedia">Simpan</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {

        // ========== FUNGSI DROPDOWN KATEGORI ==========
        //refreshCategoryDropdown -> sudah ada di modal sblah awalnya

        // Saat modal ditampilkan, refresh dropdown
        $('#mediaModal').on('show.bs.modal', function() {
            refreshCategoryDropdown();

            setTimeout(function() {
                if (window._editKategoriId) {
                    $('#mediaKategori').val(window._editKategoriId);
                    window._editKategoriId = null;
                }
            }, 150);
        });

        // ========== FUNGSI UPDATE PREVIEW DARI FORM ==========
        function updatePreviewFromForm() {
            var wa = $('#waElement');
            var qr = $('#qrElement');

            // WA
            wa.css({
                left: parseInt($('#waX').val()) || 0,
                top: parseInt($('#waY').val()) || 0,
                fontSize: (parseInt($('#waFontSize').val()) || 24) + 'px',
                color: $('#waColor').val() || '#ffffff'
            });
            wa.text($('#waText').val() || 'Klik untuk chat');

            // QR
            qr.css({
                left: parseInt($('#qrX').val()) || 0,
                top: parseInt($('#qrY').val()) || 0,
                width: (parseInt($('#qrWidth').val()) || 80) + 'px',
                height: (parseInt($('#qrHeight').val()) || 80) + 'px'
            });

            // Update config hidden
            updateConfigFromForm();
        }

        // ========== FUNGSI UPDATE FORM DARI PREVIEW (drag/resize) ==========
        function updateFormFromPreview() {
            var wa = $('#waElement');
            var qr = $('#qrElement');

            // Baca posisi/ukuran dari elemen
            $('#waX').val(parseInt(wa.css('left')) || 0);
            $('#waY').val(parseInt(wa.css('top')) || 0);
            $('#waText').val(wa.text());
            $('#waFontSize').val(parseInt(wa.css('font-size')) || 24);
            $('#waColor').val(wa.css('color') || '#ffffff');

            $('#qrX').val(parseInt(qr.css('left')) || 0);
            $('#qrY').val(parseInt(qr.css('top')) || 0);
            $('#qrWidth').val(parseInt(qr.css('width')) || 80);
            $('#qrHeight').val(parseInt(qr.css('height')) || 80);

            // Update hidden config secara langsung
            updateConfigFromForm();
        }

        // ========== UPDATE CONFIG HIDDEN ==========
        function updateConfigFromForm() {
            var img = $('#previewImage')[0];
            var naturalW = img.naturalWidth || 0;
            var naturalH = img.naturalHeight || 0;
            var displayW = $('#previewImage').width() || 0;
            var displayH = $('#previewImage').height() || 0;

            var config = {
                wa_text: $('#waText').val(),
                wa_x: parseInt($('#waX').val()) || 0,
                wa_y: parseInt($('#waY').val()) || 0,
                wa_font_size: parseInt($('#waFontSize').val()) || 24,
                wa_color: $('#waColor').val() || '#ffffff',
                qr_x: parseInt($('#qrX').val()) || 0,
                qr_y: parseInt($('#qrY').val()) || 0,
                qr_width: parseInt($('#qrWidth').val()) || 80,
                qr_height: parseInt($('#qrHeight').val()) || 80,
                qr_image: '',
                natural_width: naturalW,
                natural_height: naturalH,
                display_width: displayW,
                display_height: displayH
            };
            $('#mediaConfig').val(JSON.stringify(config));
        }

        // ========== EVENT UNTUK FORM INPUT ==========
        $('#waText, #waX, #waY, #waFontSize, #waColor, #qrX, #qrY, #qrWidth, #qrHeight').on('input change', function() {
            updatePreviewFromForm();
        });

        // ========== DRAG & RESIZE ==========
        function makeDraggableAndResizable(element) {
            // Bersihkan event sebelumnya agar tidak terjadi duplikasi
            element.off('mousedown');
            element.find('.resize-handle').off('mousedown');

            // Drag
            element.on('mousedown', function(e) {
                if ($(e.target).hasClass('resize-handle')) return;
                e.preventDefault();
                var container = $('#previewContainer');
                var parentOffset = container.offset();
                var offsetX = e.pageX - parentOffset.left - parseFloat($(this).css('left'));
                var offsetY = e.pageY - parentOffset.top - parseFloat($(this).css('top'));

                $(document).on('mousemove.drag', function(ev) {
                    var newX = ev.pageX - parentOffset.left - offsetX;
                    var newY = ev.pageY - parentOffset.top - offsetY;
                    newX = Math.max(0, Math.min(newX, container.width() - $(element).outerWidth()));
                    newY = Math.max(0, Math.min(newY, container.height() - $(element).outerHeight()));
                    $(element).css({
                        left: newX,
                        top: newY
                    });
                    updateFormFromPreview(); // update input & config
                });
                $(document).on('mouseup.drag', function() {
                    $(document).off('mousemove.drag mouseup.drag');
                });
            });

            // Resize (handle)
            element.find('.resize-handle').on('mousedown', function(e) {
                e.preventDefault();
                e.stopPropagation();
                var parent = element;
                var container = $('#previewContainer');
                var parentOffset = container.offset();
                var startX = e.pageX;
                var startY = e.pageY;
                var startW = parent.width();
                var startH = parent.height();

                $(document).on('mousemove.resize', function(ev) {
                    var deltaX = ev.pageX - startX;
                    var deltaY = ev.pageY - startY;
                    var newW = Math.max(20, startW + deltaX);
                    var newH = Math.max(20, startH + deltaY);
                    parent.css({
                        width: newW,
                        height: newH
                    });
                    updateFormFromPreview(); // update input & config
                });
                $(document).on('mouseup.resize', function() {
                    $(document).off('mousemove.resize mouseup.resize');
                });
            });
        }

        // ========== PREVIEW GAMBAR ==========
        $('#mediaImage').on('change', function(e) {
            var file = e.target.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(ev) {
                    $('#previewImage').attr('src', ev.target.result);
                    $('#previewContainer').show();

                    // Tampilkan elemen WA dan QR
                    $('#waElement').show();
                    $('#qrElement').show();

                    // Set default konfigurasi jika belum ada
                    if (!$('#mediaConfig').val()) {
                        var defaultConfig = {
                            "wa_text": "Klik untuk chat",
                            "wa_x": 50,
                            "wa_y": 50,
                            "wa_font_size": 24,
                            "wa_color": "#ffffff",
                            "qr_x": 200,
                            "qr_y": 200,
                            "qr_width": 80,
                            "qr_height": 80,
                            "qr_image": ""
                        };
                        // Isi form & preview
                        $('#waText').val(defaultConfig.wa_text);
                        $('#waX').val(defaultConfig.wa_x);
                        $('#waY').val(defaultConfig.wa_y);
                        $('#waFontSize').val(defaultConfig.wa_font_size);
                        $('#waColor').val(defaultConfig.wa_color);
                        $('#qrX').val(defaultConfig.qr_x);
                        $('#qrY').val(defaultConfig.qr_y);
                        $('#qrWidth').val(defaultConfig.qr_width);
                        $('#qrHeight').val(defaultConfig.qr_height);
                        updatePreviewFromForm();
                    }

                    // Aktifkan drag & resize
                    makeDraggableAndResizable($('#waElement'));
                    makeDraggableAndResizable($('#qrElement'));

                    // Set posisi awal dari form
                    updatePreviewFromForm();
                };
                reader.readAsDataURL(file);
            }
        });



        // ========== SAVE ==========
        $('#btnSaveMedia').click(function() {
            // Pastikan config terakhir terupdate
            updateConfigFromForm();

            var form = $('#formMedia');
            var formData = new FormData(form[0]);
            var mode = form.data('mode');
            var url = (mode == 'add') ? '<?= base_url("manage/media-promosi/store") ?>' : '<?= base_url("manage/media-promosi/update") ?>';
            // Config sudah di hidden input
            var configVal = $('#mediaConfig').val();
            formData.set('config', configVal);

            $.ajax({
                url: url,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(res) {
                    if (res.status == 'success') {
                        location.reload();
                    } else {
                        alert('Gagal: ' + (res.message || 'unknown error'));
                    }
                }
            });
        });

        // ========== LOAD DATA EDIT ==========
        // Fungsi ini dipanggil dari management_media_promosi.php saat tombol edit diklik

        window.loadMediaData = function(data) {
            // 1. Reset form & UI
            $('#formMedia')[0].reset();
            $('#mediaId').val('');
            $('#mediaConfig').val('');
            $('#previewImageOld').hide();
            $('#previewImageOldContainer').hide();

            // 2. Set mode edit & isi field dasar
            $('#mediaModalLabel').text('Edit Media Promosi');
            $('#formMedia').data('mode', 'edit');
            $('#mediaId').val(data.id);
            $('#mediaNama').val(data.nama);
            window._editKategoriId = data.id_kategori;

            if (data.image) {
                var imgUrl = '<?= base_url() ?>' + data.image;
                $('#previewImageOld').attr('src', imgUrl).show();
                $('#previewImageOldContainer').show();

                // 3. Tampilkan modal DULU agar container preview mendapatkan dimensi lebar/tinggi nyata di DOM
                $('#mediaModal').modal('show');

                // 4. Setelah modal selesai terbuka, set src gambar
                $('#mediaModal').one('shown.bs.modal', function() {
                    var $previewImg = $('#previewImage');

                    // Tampilkan container & element
                    $('#previewContainer').show();
                    $('#waElement').show();
                    $('#qrElement').show();

                    $previewImg.off('load').on('load', function() {
                        var img = $previewImg[0];
                        var currentNaturalW = img.naturalWidth || 1;
                        var currentNaturalH = img.naturalHeight || 1;
                        var currentDisplayW = $previewImg.width() || currentNaturalW;
                        var currentDisplayH = $previewImg.height() || currentNaturalH;

                        var configObj = {};
                        if (data.config) {
                            try {
                                configObj = JSON.parse(data.config);
                            } catch (e) {
                                configObj = {};
                            }
                        }

                        configObj = $.extend({
                            wa_text: 'Klik untuk chat',
                            wa_x: 50,
                            wa_y: 50,
                            wa_font_size: 24,
                            wa_color: '#ffffff',
                            qr_x: 200,
                            qr_y: 200,
                            qr_width: 80,
                            qr_height: 80,
                            display_width: currentDisplayW,
                            display_height: currentDisplayH
                        }, configObj);

                        // Hitung Rasio Skala
                        var scaleX = 1;
                        var scaleY = 1;
                        if (configObj.display_width && configObj.display_width > 0) {
                            scaleX = currentDisplayW / configObj.display_width;
                            scaleY = currentDisplayH / configObj.display_height;
                        }

                        // Masukkan Nilai Form Terkalkulasi
                        $('#waX').val(Math.round(configObj.wa_x * scaleX));
                        $('#waY').val(Math.round(configObj.wa_y * scaleY));
                        $('#waFontSize').val(Math.round(configObj.wa_font_size * scaleY));
                        $('#qrX').val(Math.round(configObj.qr_x * scaleX));
                        $('#qrY').val(Math.round(configObj.qr_y * scaleY));
                        $('#qrWidth').val(Math.round(configObj.qr_width * scaleX));
                        $('#qrHeight').val(Math.round(configObj.qr_height * scaleY));
                        $('#waText').val(configObj.wa_text);
                        $('#waColor').val(configObj.wa_color || '#ffffff');

                        // Terapkan ke tampilan elemen draggable & pasang handler drag/resize
                        updatePreviewFromForm();
                        makeDraggableAndResizable($('#waElement'));
                        makeDraggableAndResizable($('#qrElement'));

                    }).attr('src', imgUrl);

                    // Jika gambar sudah ter-cache browser
                    if ($previewImg[0].complete) {
                        $previewImg.trigger('load');
                    }
                });

            } else {
                $('#previewContainer').hide();
                $('#waElement').hide();
                $('#qrElement').hide();
                $('#mediaModal').modal('show');
            }
        };

        // Reset modal
        $('#mediaModal').on('hidden.bs.modal', function() {
            $('#formMedia')[0].reset();
            $('#previewImageOld').hide();
            $('#previewImageOldContainer').hide();
            $('#mediaModalLabel').text('Tambah Media Promosi');
            $('#formMedia').data('mode', 'add');
            $('#mediaId').val('');
            $('#mediaConfig').val('');
            $('#previewContainer').hide();
            $('#waElement').hide();
            $('#qrElement').hide();
            // Kosongkan form config
            $('#waText, #waX, #waY, #waFontSize, #waColor, #qrX, #qrY, #qrWidth, #qrHeight').val('');
        });

        // Inisialisasi default
        $('#mediaConfig').val(''); // kosongkan dulu
    });
</script>