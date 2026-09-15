/* ==========================================================
   Certificate Editor — drag & drop variable overlay
   ========================================================== */

const _URL_TPL_SAVE = _URL_MAIN_WEBSITE + "manage/certificate/save";

/* Placeholder "kertas kosong" (SVG data URI) */
const _PLACEHOLDER_PAPER = 'data:image/svg+xml;charset=utf-8,' + encodeURIComponent(
    '<svg xmlns="http://www.w3.org/2000/svg" width="900" height="650">' +
    '<defs>' +
    '<pattern id="dots" x="0" y="0" width="20" height="20" patternUnits="userSpaceOnUse">' +
    '<circle cx="2" cy="2" r="1" fill="#e6e6e6"/>' +
    '</pattern>' +
    '</defs>' +
    '<rect width="100%" height="100%" fill="#ffffff" stroke="#cccccc" stroke-width="2"/>' +
    '<rect x="20" y="20" width="860" height="610" fill="url(#dots)" stroke="#dddddd" stroke-dasharray="6,4"/>' +
    '<text x="450" y="310" text-anchor="middle" font-family="Arial" font-size="30" fill="#b8b8b8" font-weight="bold">Kertas Kosong</text>' +
    '<text x="450" y="345" text-anchor="middle" font-family="Arial" font-size="14" fill="#c4c4c4">Upload gambar di panel kiri untuk mulai</text>' +
    '<text x="450" y="370" text-anchor="middle" font-family="Arial" font-size="12" fill="#cdcdcd">Kamu tetap bisa drag variable di sini</text>' +
    '</svg>'
);

let _tpl = null;             // data template
let _activeSide = 'front';   // 'front' | 'back'
let _files = { front_image: null, back_image: null };

$(document).ready(function () {
    _tpl = window.__TPL_DATA__ || {};

    // normalisasi state
    _tpl.front_variables = Array.isArray(_tpl.front_variables) ? _tpl.front_variables : [];
    _tpl.back_variables  = Array.isArray(_tpl.back_variables)  ? _tpl.back_variables  : [];

    // pastikan semua variable punya default font/font_style
    [_tpl.front_variables, _tpl.back_variables].forEach(arr => {
        arr.forEach(v => {
            if (!v.font)       v.font = 'arial';
            if (!v.font_style) v.font_style = 'regular';
            if (!v.font_size)  v.font_size = 24;
            if (!v.color)      v.color = '#000000';
        });
    });

    bindSideTabs();
    bindSideModeChange();
    bindPaketChange();
    bindFileUploads();
    bindAddVariable();
    bindSaveButton();
    bindDeleteVariable();
    bindEditVariable();

    // render canvas awal — pakai placeholder jika belum ada image
    renderCanvas('front');
});

/* ========== SIDE TABS ========== */
function bindSideTabs() {
    $(document).on('click', '.editor-side-tabs .nav-link', function (e) {
        e.preventDefault();
        let side = $(this).data('side');
        if (!side) return;
        _activeSide = side;
        $('.editor-side-tabs .nav-link').removeClass('active');
        $(this).addClass('active');
        renderCanvas(side);
    });
}

function bindSideModeChange() {
    $('#side_mode').on('change', function () {
        let mode = $(this).val();
        if (mode === 'double') {
            $('#back-upload-wrap').show();
            $('#tab-back-li').show();
        } else {
            $('#back-upload-wrap').hide();
            $('#tab-back-li').hide();
        }
    });
}

function bindPaketChange() {
    $('#paket').on('change', function () {
        if ($(this).val() === 'paket_kasus_custom') {
            $('#custom-wrap').show();
        } else {
            $('#custom-wrap').hide();
        }
    });
    // trigger saat load
    $('#paket').trigger('change');
}

/* ========== FILE UPLOADS (preview sementara) ========== */
function bindFileUploads() {
    $('#front_image').on('change', function () {
        let f = this.files[0];
        if (!f) return;
        _files.front_image = f;
        let url = URL.createObjectURL(f);
        let img = new Image();
        img.onload = function () {
            _tpl.front_image  = url;
            _tpl.front_width  = img.naturalWidth;
            _tpl.front_height = img.naturalHeight;
            if (_activeSide === 'front') renderCanvas('front');
        };
        img.src = url;
    });

    $('#back_image').on('change', function () {
        let f = this.files[0];
        if (!f) return;
        _files.back_image = f;
        let url = URL.createObjectURL(f);
        let img = new Image();
        img.onload = function () {
            _tpl.back_image  = url;
            _tpl.back_width  = img.naturalWidth;
            _tpl.back_height = img.naturalHeight;
            if (_activeSide === 'back') renderCanvas('back');
        };
        img.src = url;
    });
}

/* ========== RENDER CANVAS ========== */
function renderCanvas(side) {
    let imgSrc = side === 'front' ? _tpl.front_image : _tpl.back_image;
    let vars   = side === 'front' ? _tpl.front_variables : _tpl.back_variables;
    let $inner = $('#canvas-inner');
    let $img   = $('#canvas-img');

    // pakai placeholder kalau belum ada image
    let hasImage = !!imgSrc;
    if (!hasImage) imgSrc = _PLACEHOLDER_PAPER;

    $inner.empty().append($img);
    $img.attr('src', imgSrc).css({ width: 'auto', maxWidth: 'none' });

    // hapus overlay lama
    $inner.find('.var-overlay').remove();

    // append tiap variable sebagai overlay
    (vars || []).forEach((v, idx) => {
        let fontWeight = (v.font_style === 'bold' || v.font_style === 'bold_italic') ? 'bold' : 'normal';
        let fontStyle  = (v.font_style === 'italic' || v.font_style === 'bold_italic') ? 'italic' : 'normal';
        let fontFamily = v.font === 'times'   ? '"Times New Roman", Times, serif' :
                         v.font === 'courier' ? '"Courier New", Courier, monospace' :
                                                'Arial, Helvetica, sans-serif';

        let $el = $('<div class="var-overlay"></div>')
            .attr('data-idx', idx)
            .attr('data-side', side)
            .css({
                left:       (v.x || 0) + 'px',
                top:        (v.y || 0) + 'px',
                fontSize:   (v.font_size || 24) + 'px',
                color:      v.color || '#000000',
                fontWeight: fontWeight,
                fontStyle:  fontStyle,
                fontFamily: fontFamily,
            })
            .text(getVarLabel(v));

        $inner.append($el);
    });

    // draggable
    $inner.find('.var-overlay').draggable({
        containment: 'parent',
        cursor: 'grabbing',
        start: function () { $(this).addClass('dragging'); },
        stop: function () {
            $(this).removeClass('dragging');
            let idx = $(this).data('idx');
            let sd  = $(this).data('side');
            let arr = sd === 'front' ? _tpl.front_variables : _tpl.back_variables;
            if (arr[idx]) {
                arr[idx].x = parseInt($(this).css('left'));
                arr[idx].y = parseInt($(this).css('top'));
            }
        }
    });

    // tanda visual kalau ini placeholder
    if (!hasImage) {
        $inner.addClass('is-placeholder');
    } else {
        $inner.removeClass('is-placeholder');
    }

    renderVarList();
}

function getVarLabel(v) {
    if (v.type === 'custom') {
        return '[' + (v.value || 'Text') + ']';
    }
    let map = {
        'nama_user':     '{nama_user}',
        'tanggal_cetak': '{tanggal_cetak}',
        'judul_materi':  '{judul_materi}',
        'nilai':         '{nilai}',
    };
    return map[v.key] || '{' + v.key + '}';
}

/* ========== VARIABLE LIST (panel kiri) ========== */
function renderVarList() {
    let vars = _activeSide === 'front' ? _tpl.front_variables : _tpl.back_variables;
    let $list = $('#var-list').empty();
    if (!vars.length) {
        $list.html('<div class="text-muted small">Belum ada variable.</div>');
        return;
    }
    vars.forEach((v, idx) => {
        let styleInfo = (v.font_style || 'regular').replace('_', ' ');
        let fontInfo  = v.font || 'arial';
        let sizeInfo  = v.font_size || 24;
        $list.append(`
            <div class="var-item" data-idx="${idx}" data-side="${_activeSide}">
                <div class="d-flex align-items-center w-100">
                    <span class="badge bg-primary me-1">${v.type}</span>
                    <span class="flex-grow-1 text-truncate">${v.label || v.key}</span>
                    <small class="text-muted me-2" style="font-size:.7rem;white-space:nowrap;">${fontInfo} · ${styleInfo} · ${sizeInfo}px</small>
                    <a class="text-info me-1 edit-var" data-idx="${idx}" data-side="${_activeSide}" title="Edit style & ukuran">
                        <i class="fas fa-cog"></i>
                    </a>
                    <a class="text-danger delete-var" data-idx="${idx}" data-side="${_activeSide}" title="Hapus">
                        <i class="fas fa-trash"></i>
                    </a>
                </div>
            </div>
        `);
    });
}

/* ========== ADD VARIABLE ========== */
function bindAddVariable() {
    $('#btn-add-var').on('click', function () {
        let type = $('#var_type').val();

        if (type === 'custom') {
            Swal.fire({
                title: 'Custom Text Variable',
                html: `
                    <input id="swal-label" class="swal2-input" placeholder="Label (mis. Keterangan)">
                    <input id="swal-value" class="swal2-input" placeholder="Text yang ditampilkan">
                    <input id="swal-size"  class="swal2-input" type="number" value="24" placeholder="Font size (px)">
                    <input id="swal-color" class="swal2-input" type="color" value="#000000">
                `,
                showCancelButton: true,
                confirmButtonText: 'Tambah',
                preConfirm: () => ({
                    label: document.getElementById('swal-label').value.trim(),
                    value: document.getElementById('swal-value').value.trim(),
                    font_size: parseInt(document.getElementById('swal-size').value) || 24,
                    color: document.getElementById('swal-color').value || '#000000',
                })
            }).then(r => {
                if (!r.isConfirmed) return;
                let v = {
                    key: 'custom_' + Date.now(),
                    label: r.value.label || 'Custom',
                    type: 'custom',
                    value: r.value.value,
                    x: 100, y: 100,
                    font_size: r.value.font_size,
                    color: r.value.color,
                    font: 'arial',
                    font_style: 'regular'
                };
                pushVariable(v);
            });
        } else {
            let map = {
                'nama_user':     'Nama User',
                'tanggal_cetak': 'Tanggal Cetak',
                'judul_materi':  'Judul Materi',
                'nilai':         'Nilai Akhir',
            };
            let v = {
                key: type,
                label: map[type] || type,
                type: 'system',
                x: 100, y: 100,
                font_size: 24,
                color: '#000000',
                font: 'arial',
                font_style: 'regular'
            };
            pushVariable(v);
        }
    });
}

function pushVariable(v) {
    if (_activeSide === 'front') _tpl.front_variables.push(v);
    else                         _tpl.back_variables.push(v);
    renderCanvas(_activeSide);
}

/* ========== DELETE VARIABLE ========== */
function bindDeleteVariable() {
    $(document).on('click', '.delete-var', function (e) {
        e.preventDefault();
        e.stopPropagation();
        let idx = parseInt($(this).data('idx'));
        let side = $(this).data('side');
        let arr = side === 'front' ? _tpl.front_variables : _tpl.back_variables;
        arr.splice(idx, 1);
        renderCanvas(side);
    });
}

/* ========== EDIT VARIABLE (font, style, ukuran, warna) ========== */
function bindEditVariable() {
    $(document).on('click', '.edit-var', function (e) {
        e.preventDefault();
        e.stopPropagation();
        let idx  = parseInt($(this).data('idx'));
        let side = $(this).data('side');
        let arr  = side === 'front' ? _tpl.front_variables : _tpl.back_variables;
        let v    = arr[idx];
        if (!v) return;

        let isCustom = v.type === 'custom';

        Swal.fire({
            title: 'Edit Variable',
            width: 520,
            html: `
                <div class="text-start" style="font-size:.9rem;">
                  ${isCustom ? `
                    <label class="form-label mt-1 mb-1">Label</label>
                    <input id="ev-label" class="form-control form-control-sm mb-2" value="${escapeAttr(v.label || '')}">

                    <label class="form-label mb-1">Text</label>
                    <input id="ev-value" class="form-control form-control-sm mb-2" value="${escapeAttr(v.value || '')}">
                  ` : `
                    <div class="alert alert-light py-2 mb-2 small">
                      <strong>${escapeAttr(v.label || v.key)}</strong> — variable sistem
                    </div>
                  `}

                  <label class="form-label mb-1">Font</label>
                  <select id="ev-font" class="form-select form-select-sm mb-2">
                    <option value="arial"   ${v.font === 'arial'   ? 'selected' : ''}>Arial</option>
                    <option value="times"   ${v.font === 'times'   ? 'selected' : ''}>Times New Roman</option>
                    <option value="courier" ${v.font === 'courier' ? 'selected' : ''}>Courier New</option>
                  </select>

                  <label class="form-label mb-1">Style</label>
                  <select id="ev-style" class="form-select form-select-sm mb-2">
                    <option value="regular"     ${(v.font_style || 'regular') === 'regular'     ? 'selected' : ''}>Regular</option>
                    <option value="bold"        ${v.font_style === 'bold'                        ? 'selected' : ''}>Bold</option>
                    <option value="italic"      ${v.font_style === 'italic'                      ? 'selected' : ''}>Italic</option>
                    <option value="bold_italic" ${v.font_style === 'bold_italic'                 ? 'selected' : ''}>Bold Italic</option>
                  </select>

                  <label class="form-label mb-1">Ukuran Font (px)</label>
                  <input id="ev-size" type="number" class="form-control form-control-sm mb-2"
                         value="${v.font_size || 24}" min="8" max="200">

                  <label class="form-label mb-1">Warna</label>
                  <input id="ev-color" type="color" class="form-control form-control-color"
                         value="${v.color || '#000000'}">
                </div>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            preConfirm: () => ({
                font:       document.getElementById('ev-font').value,
                font_style: document.getElementById('ev-style').value,
                font_size:  parseInt(document.getElementById('ev-size').value) || 24,
                color:      document.getElementById('ev-color').value,
                label:      isCustom ? document.getElementById('ev-label').value.trim() : v.label,
                value:      isCustom ? document.getElementById('ev-value').value.trim() : v.value,
            })
        }).then(r => {
            if (!r.isConfirmed) return;
            Object.assign(v, r.value);
            renderCanvas(side);
        });
    });
}

function escapeAttr(s) {
    return String(s || '')
        .replace(/&/g, '&amp;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#39;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;');
}

/* ========== SAVE ========== */
function bindSaveButton() {
    $('#btn-save').on('click', function () {
        let id = $('#tpl_id').val();

        if (!$('#nama_template').val().trim()) {
            Swal.fire('Info', 'Nama template wajib diisi.', 'info'); return;
        }
        if (!id && !_files.front_image && !_tpl.front_image) {
            Swal.fire('Info', 'Front image wajib diupload.', 'info'); return;
        }

        let fd = new FormData();
        fd.append('id', id || '');
        fd.append('id_materi', $('#id_materi').val());
        fd.append('nama_template', $('#nama_template').val());
        fd.append('paket', $('#paket').val());
        fd.append('id_custom_materi', $('#id_custom_materi').val() || '');
        fd.append('side_mode', $('#side_mode').val());
        fd.append('orientation', $('#orientation').val());
        
        fd.append('front_variables', JSON.stringify(_tpl.front_variables));
        fd.append('back_variables',  JSON.stringify(_tpl.back_variables));

        if (_files.front_image) fd.append('front_image', _files.front_image);
        if (_files.back_image)  fd.append('back_image', _files.back_image);

        let btn = $(this);
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Saving...');

        $.ajax({
            url: _URL_TPL_SAVE,
            method: 'POST',
            data: fd,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function (res) {
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Template');
                if (res.status === 'valid') {
                    Swal.fire({ icon: 'success', title: 'Tersimpan', timer: 900, showConfirmButton: false })
                        .then(() => {
                            window.location.href = _URL_MAIN_WEBSITE + 'manage/certificate?materi_id=' + $('#id_materi').val();
                        });
                } else {
                    Swal.fire('Gagal', res.message || 'Error', 'error');
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan Template');
                Swal.fire('Error', 'Gagal menyimpan', 'error');
                console.error(xhr.responseText);
            }
        });
    });
}