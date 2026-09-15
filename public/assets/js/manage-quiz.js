/* =========================================================
   Management Quiz — Section-based UI (Trevor/Kanban style)
   ========================================================= */

const _URL_DELETE_MATERI_QUIZ = _URL_MAIN_WEBSITE + "manage/materi/quiz/delete";
const _URL_ADD_MATERI_QUIZ    = _URL_MAIN_WEBSITE + "manage/materi/quiz/add";
const _URL_UPDATE_MATERI_QUIZ = _URL_MAIN_WEBSITE + "manage/materi/quiz/update";
const _URL_REORDER_MATERI_QUIZ = _URL_MAIN_WEBSITE + "manage/materi/quiz/reorder";

const _URL_GROUP_ADD    = _URL_MAIN_WEBSITE + "manage/materi/quiz/group/add";
const _URL_GROUP_UPDATE = _URL_MAIN_WEBSITE + "manage/materi/quiz/group/update";
const _URL_GROUP_DELETE = _URL_MAIN_WEBSITE + "manage/materi/quiz/group/delete";
const _URL_GROUP_REORDER = _URL_MAIN_WEBSITE + "manage/materi/quiz/group/reorder";
const _URL_ASSIGN_GROUP = _URL_MAIN_WEBSITE + "manage/materi/quiz/assign-group";

let _D = null;
let _jumlah_soal = 0;

$(document).ready(function () {
    _D = window.__QUIZ_DATA__ || { id_materi: 0, groups: [], ungrouped: [] };

    renderSections(_D);
    refreshSortables();

    muncullinJenisSoal();
    saveCard();
    deleteSingleSoal();
    deleteSelectedSoal();

    addGroupHandler();
    editGroupHandler();
    deleteGroupHandler();

    // toolbar +
    $('#add-card').on('click', function (e) {
        e.preventDefault();
        addNewCardToSection('');
    });

    // tombol + di tiap header section
    $(document).on('click', '.section-add-card', function (e) {
        e.preventDefault();
        let gid = $(this).closest('.quiz-section').data('id-group') || '';
        addNewCardToSection(gid);
    });

    // tandai card berubah → tampilkan save
    $(document).on('input change',
        '.pertanyaan, .jenis-soal, .keterangan, .opsi-a, .opsi-b, .opsi-c, .opsi-d, .jawaban-akhir',
        function () {
            $(this).closest('.card-item').find('.save-card').show();
        });

    $('#refresh-data').on('click', function (e) {
        e.preventDefault();
        location.reload();
    });
});

/* =========================================================
   RENDER
   ========================================================= */

function escapeHtml(s) {
    if (s === null || s === undefined) return '';
    return String(s).replace(/[&<>"']/g, m => ({
        '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
    }[m]));
}

function renderSections(D) {
    const $c = $('#section-container').empty();

    // 1) Section Ungrouped — SELALU di paling atas, gak bisa di-drag
    $c.append(buildSection({
        id: 0,
        nama: 'Belum Masuk Grup',
        deskripsi: 'Tarik soal ke sini untuk melepas dari grup',
        is_ungrouped: true,
        cards: D.ungrouped || []
    }));

    // 2) Section grup — sortable
    (D.groups || [])
        .sort((a, b) => (a.ordering_index - b.ordering_index) || (a.id - b.id))
        .forEach(g => $c.append(buildSection(g)));

    updateCardNumbering();
    updateSectionCounts();
    updateTotalData();
}

function buildSection(g) {
    const isUng = !!g.is_ungrouped;
    const cards = (g.cards || []).map((c, i) => buildCard(c, i + 1)).join('');

    return `
        <div class="quiz-section card mb-3"
             data-id-group="${g.id}"
             data-is-ungrouped="${isUng ? 1 : 0}">
            <div class="section-header">
                ${!isUng ? `<span class="section-drag-handle" title="Drag untuk pindah urutan section">
                                <i class="fas fa-grip-vertical text-muted"></i>
                            </span>` : ''}
                <h5 class="mb-0 mr-2">
                    <i class="fas ${isUng ? 'fa-inbox' : 'fa-layer-group'}"></i>
                    ${escapeHtml(g.nama)}
                </h5>
                <span class="badge bg-secondary section-count">0</span>
                ${g.deskripsi ? `<small class="text-muted ms-2">${escapeHtml(g.deskripsi)}</small>` : ''}
                <div class="ms-auto">
                    <button class="btn btn-sm btn-outline-success section-add-card" title="Tambah soal ke section ini">
                        <i class="fas fa-plus"></i>
                    </button>
                    ${!isUng ? `
                        <button class="btn btn-sm btn-outline-primary edit-group" data-id="${g.id}" title="Edit grup">
                            <i class="fas fa-pen"></i>
                        </button>
                        <button class="btn btn-sm btn-outline-danger delete-group" data-id="${g.id}" title="Hapus grup">
                            <i class="fas fa-trash"></i>
                        </button>
                    ` : ''}
                </div>
            </div>
            <div class="section-body row" data-id-group="${g.id}">
                ${cards}
            </div>
        </div>
    `;
}

function buildCard(c, nomor) {
    const jenis = c.jenis || 'essay';
    const isPg2 = jenis === 'pg2';
    const isPg4 = jenis === 'pg4';
    const isPg  = isPg2 || isPg4;
    const opsiFinal = isPg4 ? ['A','B','C','D'] : ['A','B'];

    return `
        <div class="col-md-6 mb-3 card-item"
             data-id="${c.id || ''}"
             data-id-materi="${c.id_materi || ''}">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-2">
                        <input type="checkbox" class="form-check-input is-selected">
                        <span class="drag-handle" title="Drag untuk pindah">
                            <i class="fas fa-grip-vertical text-muted"></i>
                        </span>
                        <span class="order-badge badge bg-secondary">#${String(nomor).padStart(2,'0')}</span>
                    </div>

                    <label>Pertanyaan:</label>
                    <textarea class="form-control mb-2 pertanyaan" placeholder="tulis Pertanyaan disini">${escapeHtml(c.pertanyaan || '')}</textarea>

                    <select class="form-select mb-2 jenis-soal">
                        <option value="essay" ${jenis==='essay'?'selected':''}>Essay</option>
                        <option value="pg2"   ${isPg2?'selected':''}>PG 2 opsi</option>
                        <option value="pg4"   ${isPg4?'selected':''}>PG 4 opsi</option>
                    </select>

                    <div class="pg-opsi mb-2" style="${isPg?'':'display:none'}">
                        <input type="text" class="form-control mb-1 opsi-a" placeholder="Opsi A" value="${escapeHtml(c.opsi_a||'')}">
                        <input type="text" class="form-control mb-1 opsi-b" placeholder="Opsi B" value="${escapeHtml(c.opsi_b||'')}">
                        <input type="text" class="form-control mb-1 opsi-c" placeholder="Opsi C" value="${escapeHtml(c.opsi_c||'')}" style="${isPg4?'':'display:none'}">
                        <input type="text" class="form-control mb-1 opsi-d" placeholder="Opsi D" value="${escapeHtml(c.opsi_d||'')}" style="${isPg4?'':'display:none'}">
                    </div>

                    <textarea class="form-control mb-2 keterangan" placeholder="Keterangan">${escapeHtml(c.keterangan || '')}</textarea>

                    <div class="mb-2 jawaban-akhir-part ${jenis==='essay'?'d-none':''}">
                        <label>Jawaban Final:</label>
                        <select class="form-select mb-2 jawaban-akhir">
                            ${opsiFinal.map(v => `<option value="${v}" ${c.final_answer===v?'selected':''}>Opsi ${v}</option>`).join('')}
                        </select>
                    </div>

                    <button class="btn btn-sm btn-danger delete-card">Delete</button>
                    <button class="btn btn-sm btn-success float-end save-card" style="display:none;">Save</button>
                </div>
            </div>
        </div>
    `;
}

/* =========================================================
   SORTABLE — Sections & Cards
   ========================================================= */

function refreshSortables() {
    // 1) Section sortable (parent)
    if ($('#section-container').hasClass('ui-sortable')) {
        try { $('#section-container').sortable('destroy'); } catch (e) {}
    }
    $('#section-container').sortable({
        handle: '.section-drag-handle',
        items: '.quiz-section:not([data-is-ungrouped="1"])',
        axis: 'y',
        cursor: 'move',
        opacity: 0.9,
        tolerance: 'pointer',
        update: function () {
            saveSectionOrdering();
        }
    });

    // 2) Card sortable (per section-body) — connectWith semua section-body
    if ($('.section-body').hasClass('ui-sortable')) {
        try { $('.section-body').sortable('destroy'); } catch (e) {}
    }
    $('.section-body').sortable({
        handle: '.drag-handle',
        items: '.card-item',
        connectWith: '.section-body',
        cursor: 'move',
        opacity: 0.9,
        tolerance: 'pointer',
        placeholder: 'col-md-6 mb-3 card-item ui-sortable-placeholder',
        forcePlaceholderSize: true,
        start: function (event, ui) {
            ui.placeholder.height(ui.item.outerHeight());
        },
        over: function () {
            $(this).addClass('ui-sortable-over');
        },
        out: function () {
            $(this).removeClass('ui-sortable-over');
        },
        stop: function (event, ui) {
            $('.section-body').removeClass('ui-sortable-over');

            let card = ui.item;
            let cardId = card.data('id');
            let newGroupId = card.closest('.quiz-section').data('id-group') || '';

            // update attr di card
            card.attr('data-id-group', newGroupId);

            updateCardNumbering();
            updateSectionCounts();
            saveOrdering();

            // kalau card sudah ada di DB → pindah grup
            if (cardId) {
                $.post(_URL_ASSIGN_GROUP, { id_quiz: cardId, id_group: newGroupId }, function (res) {
                    if (res.status !== 'valid') {
                        Swal.fire('Gagal', res.message || 'Gagal pindah grup', 'error');
                    }
                }, 'json');
            }
        }
    });
}

/* =========================================================
   NUMBERING & COUNTS
   ========================================================= */

function updateCardNumbering() {
    $('.section-body').each(function () {
        let n = 1;
        $(this).find('.card-item').each(function () {
            $(this).find('.order-badge').text('#' + String(n).padStart(2, '0'));
            n++;
        });
    });
}

function updateSectionCounts() {
    $('.quiz-section').each(function () {
        let cnt = $(this).find('.card-item').length;
        $(this).find('.section-count').text(cnt);
    });
}

function updateTotalData() {
    _jumlah_soal = $('#section-container .card-item').length;
    $('#total_data').text(_jumlah_soal + ' soal quiz.');
}

/* =========================================================
   SIMPAN ORDER (global flat index biar kompatibel backend)
   ========================================================= */

function saveOrdering() {
    let items = [];
    let n = 1;
    $('#section-container .quiz-section').each(function () {
        $(this).find('.card-item').each(function () {
            let id = $(this).data('id');
            if (id) {
                items.push({ id: id, ordering_index: n });
            }
            n++;
        });
    });
    if (!items.length) return;

    $.ajax({
        url: _URL_REORDER_MATERI_QUIZ,
        method: 'POST',
        data: { items: items },
        dataType: 'json',
        error: xhr => console.error('Gagal reorder:', xhr.responseText)
    });
}

function saveSectionOrdering() {
    let items = [];
    let n = 0;
    $('#section-container .quiz-section').each(function () {
        let gid = $(this).data('id-group');
        if (gid && parseInt(gid) > 0) {
            n++;
            items.push({ id: gid, ordering_index: n });
        }
    });
    if (!items.length) return;

    $.ajax({
        url: _URL_GROUP_REORDER,
        method: 'POST',
        data: { items: items },
        dataType: 'json',
        error: xhr => console.error('Gagal reorder section:', xhr.responseText)
    });
}

/* =========================================================
   ADD NEW CARD
   ========================================================= */

function addNewCardToSection(groupId) {
    let id_materi = _D.id_materi;
    let $section = $('#section-container .quiz-section[data-id-group="' + (groupId || '') + '"]');

    // Kalau ungrouped (groupId kosong) → pilih section ungrouped (yg ada data-is-ungrouped=1)
    if (!groupId) {
        $section = $('#section-container .quiz-section[data-is-ungrouped="1"]');
    }
    if (!$section.length) {
        Swal.fire('Error', 'Section tidak ditemukan', 'error');
        return;
    }

    const $body = $section.find('.section-body');
    const nomor = $body.find('.card-item').length + 1;
    const cardHtml = buildCard({ id: '', id_materi: id_materi }, nomor);

    $body.append(cardHtml);

    // Card baru → save button langsung tampil
    $body.find('.card-item:last').find('.save-card').show();

    updateCardNumbering();
    updateSectionCounts();
    updateTotalData();
}

/* =========================================================
   TOGGLE JENIS SOAL
   ========================================================= */

function muncullinJenisSoal() {
    $(document).on('change', '.jenis-soal', function () {
        let jenis = $(this).val();
        let $body = $(this).closest('.card-body');
        let pgOpsi = $body.find('.pg-opsi');
        let jawaban = $body.find('.jawaban-akhir');
        let jawabanContainer = $body.find('.jawaban-akhir-part');

        if (jenis === 'essay') {
            pgOpsi.hide();
            pgOpsi.find('input').val('');
            jawaban.hide().val('');
            jawabanContainer.addClass('d-none');
        } else if (jenis === 'pg2') {
            pgOpsi.show();
            pgOpsi.find('.opsi-a, .opsi-b').show();
            pgOpsi.find('.opsi-c, .opsi-d').hide().val('');
            jawaban.html(`<option value="A">Opsi A</option><option value="B">Opsi B</option>`);
            jawaban.show();
            jawabanContainer.removeClass('d-none');
        } else if (jenis === 'pg4') {
            pgOpsi.show();
            pgOpsi.find('input').show();
            jawaban.html(`
                <option value="A">Opsi A</option>
                <option value="B">Opsi B</option>
                <option value="C">Opsi C</option>
                <option value="D">Opsi D</option>
            `);
            jawaban.show();
            jawabanContainer.removeClass('d-none');
        }
    });
}

/* =========================================================
   SAVE CARD
   ========================================================= */

function saveCard() {
    $(document).on('click', '.save-card', function (e) {
        e.preventDefault();

        let card = $(this).closest('.card-item');
        let btn  = $(this);

        let idna      = card.data('id');
        let id_materi = card.data('id-materi') || _D.id_materi;
        let id_group  = card.closest('.quiz-section').data('id-group') || '';

        let pertanyaan = card.find('.pertanyaan').val();
        let jenis      = card.find('.jenis-soal').val();
        let keterangan = card.find('.keterangan').val();
        let final_ans  = card.find('.jawaban-akhir').val() || '';

        let opsi_a = card.find('.opsi-a').val() || '';
        let opsi_b = card.find('.opsi-b').val() || '';
        let opsi_c = card.find('.opsi-c').val() || '';
        let opsi_d = card.find('.opsi-d').val() || '';

        if (!pertanyaan || pertanyaan.trim() === '') {
            Swal.fire({ icon: 'warning', title: 'Pertanyaan kosong', text: 'Isi pertanyaan dulu.' });
            return;
        }

        let dataNa = {
            id_materi: id_materi,
            pertanyaan: pertanyaan,
            jenis: jenis,
            keterangan: keterangan,
            final_answer: final_ans,
            opsi_a: opsi_a,
            opsi_b: opsi_b,
            opsi_c: opsi_c,
            opsi_d: opsi_d,
            id_group: id_group
        };

        let urlTarget = _URL_ADD_MATERI_QUIZ;
        if (idna) {
            dataNa.id = idna;
            urlTarget = _URL_UPDATE_MATERI_QUIZ;
        }

        btn.prop('disabled', true).text('Saving...');

        $.ajax({
            url: urlTarget,
            method: 'POST',
            data: dataNa,
            dataType: 'json',
            success: function (response) {
                if (response.status === 'valid') {
                    btn.hide().prop('disabled', false).text('Save');

                    if (!idna && response.id) {
                        card.attr('data-id', response.id);
                        saveOrdering();
                    }

                    Swal.fire({
                        icon: 'success',
                        title: 'Tersimpan',
                        timer: 800,
                        showConfirmButton: false
                    });
                } else {
                    btn.prop('disabled', false).text('Save');
                    Swal.fire('Gagal', response.message || 'Error', 'error');
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                btn.prop('disabled', false).text('Save');
                Swal.fire('Error', 'Gagal menyimpan', 'error');
            }
        });
    });
}

/* =========================================================
   DELETE SINGLE
   ========================================================= */

function deleteSingleSoal() {
    $(document).on('click', '.delete-card', function (e) {
        e.preventDefault();
        let card = $(this).closest('.card-item');
        let idna = card.data('id');

        if (!idna) {
            card.remove();
            updateCardNumbering();
            updateSectionCounts();
            updateTotalData();
            return;
        }

        Swal.fire({
            icon: 'warning',
            title: 'Hapus soal ini?',
            showCancelButton: true,
            confirmButtonColor: '#d33'
        }).then(r => {
            if (!r.isConfirmed) return;

            $.post(_URL_DELETE_MATERI_QUIZ, { id: idna }, function (res) {
                if (res.status === 'valid') {
                    card.remove();
                    updateCardNumbering();
                    updateSectionCounts();
                    updateTotalData();
                    saveOrdering();
                } else {
                    Swal.fire('Gagal', res.message || 'Error', 'error');
                }
            }, 'json');
        });
    });
}

/* =========================================================
   DELETE BULK
   ========================================================= */

function deleteSelectedSoal() {
    $('#delete-selected').on('click', function (e) {
        e.preventDefault();
        let $checked = $('.is-selected:checked');

        if (!$checked.length) {
            Swal.fire({ icon: 'info', title: 'Pilih item dulu' });
            return;
        }

        Swal.fire({
            icon: 'warning',
            title: `Hapus ${$checked.length} soal?`,
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus'
        }).then(r => {
            if (!r.isConfirmed) return;

            let ids = [];
            $checked.each(function () {
                let id = $(this).closest('.card-item').data('id');
                if (id) ids.push(id);
            });

            let afterDelete = function () {
                $checked.each(function () {
                    $(this).closest('.card-item').remove();
                });
                updateCardNumbering();
                updateSectionCounts();
                updateTotalData();
                saveOrdering();
            };

            if (!ids.length) { afterDelete(); return; }

            $.post(_URL_DELETE_MATERI_QUIZ, { id: ids }, function (res) {
                if (res.status === 'valid') afterDelete();
                else Swal.fire('Gagal', res.message || 'Error', 'error');
            }, 'json');
        });
    });
}

/* =========================================================
   GROUP CRUD HANDLERS
   ========================================================= */

function addGroupHandler() {
    $(document).on('click', '#add-group', function (e) {
        e.preventDefault();
        Swal.fire({
            title: 'Grup Baru',
            html: `
                <input id="swal-nama" class="swal2-input" placeholder="Nama grup (mis. Code Syntax)">
                <textarea id="swal-desk" class="swal2-textarea" placeholder="Deskripsi (opsional)"></textarea>
            `,
            showCancelButton: true,
            confirmButtonText: 'Simpan',
            preConfirm: () => ({
                nama: document.getElementById('swal-nama').value.trim(),
                deskripsi: document.getElementById('swal-desk').value.trim(),
            })
        }).then(r => {
            if (!r.isConfirmed || !r.value.nama) return;
            $.post(_URL_GROUP_ADD, {
                id_materi: _D.id_materi,
                nama: r.value.nama,
                deskripsi: r.value.deskripsi,
            }, function (res) {
                if (res.status === 'valid') {
                    // append section baru di bawah (sebelum ungrouped? atau setelah section terakhir)
                    let newGroup = {
                        id: res.id,
                        nama: r.value.nama,
                        deskripsi: r.value.deskripsi,
                        ordering_index: 9999,
                        cards: []
                    };
                    _D.groups.push(newGroup);
                    $('#section-container').append(buildSection(newGroup));
                    refreshSortables();
                    updateSectionCounts();
                    Swal.fire({ icon: 'success', title: 'Grup ditambahkan', timer: 900, showConfirmButton: false });
                } else {
                    Swal.fire('Gagal', res.message || 'error', 'error');
                }
            }, 'json');
        });
    });
}

function editGroupHandler() {
    $(document).on('click', '.edit-group', function () {
        let id = $(this).data('id');
        let g = _D.groups.find(x => String(x.id) === String(id));
        if (!g) return;

        Swal.fire({
            title: 'Edit Grup',
            html: `
                <input id="swal-nama" class="swal2-input" value="${escapeHtml(g.nama)}">
                <textarea id="swal-desk" class="swal2-textarea">${escapeHtml(g.deskripsi || '')}</textarea>
            `,
            showCancelButton: true,
            confirmButtonText: 'Update',
            preConfirm: () => ({
                nama: document.getElementById('swal-nama').value.trim(),
                deskripsi: document.getElementById('swal-desk').value.trim(),
            })
        }).then(r => {
            if (!r.isConfirmed || !r.value.nama) return;
            $.post(_URL_GROUP_UPDATE, { id, ...r.value }, function (res) {
                if (res.status === 'valid') {
                    let $section = $(`.quiz-section[data-id-group="${id}"]`);
                    $section.find('.section-header h5').html(`<i class="fas fa-layer-group"></i> ${escapeHtml(r.value.nama)}`);
                    g.nama = r.value.nama;
                    g.deskripsi = r.value.deskripsi;
                    Swal.fire({ icon: 'success', title: 'Diupdate', timer: 800, showConfirmButton: false });
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            }, 'json');
        });
    });
}

function deleteGroupHandler() {
    $(document).on('click', '.delete-group', function () {
        let id = $(this).data('id');
        let $section = $(this).closest('.quiz-section');
        let cnt = $section.find('.card-item').length;

        Swal.fire({
            icon: 'warning',
            title: 'Hapus grup ini?',
            html: cnt > 0
                ? `${cnt} soal di dalamnya akan pindah ke <b>Belum Masuk Grup</b>.`
                : 'Grup akan dihapus.',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus'
        }).then(r => {
            if (!r.isConfirmed) return;

            $.post(_URL_GROUP_DELETE, { id }, function (res) {
                if (res.status === 'valid') {
                    // pindahkan card ke ungrouped section
                    let $ung = $('#section-container .quiz-section[data-is-ungrouped="1"] .section-body');
                    $section.find('.card-item').each(function () {
                        $(this).appendTo($ung);
                        // set id_group null di DB
                        let cid = $(this).data('id');
                        if (cid) {
                            $.post(_URL_ASSIGN_GROUP, { id_quiz: cid, id_group: 0 }, function(){}, 'json');
                        }
                    });
                    $section.remove();

                    _D.groups = _D.groups.filter(x => String(x.id) !== String(id));

                    updateCardNumbering();
                    updateSectionCounts();
                    updateTotalData();
                    refreshSortables();
                    Swal.fire({ icon: 'success', title: 'Grup dihapus', timer: 900, showConfirmButton: false });
                } else {
                    Swal.fire('Gagal', res.message, 'error');
                }
            }, 'json');
        });
    });
}