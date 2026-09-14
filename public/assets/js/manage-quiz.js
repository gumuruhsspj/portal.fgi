const _URL_DELETE_MATERI_QUIZ = _URL_MAIN_WEBSITE + "manage/materi/quiz/delete";
const _URL_ADD_MATERI_QUIZ    = _URL_MAIN_WEBSITE + "manage/materi/quiz/add";
const _URL_UPDATE_MATERI_QUIZ = _URL_MAIN_WEBSITE + "manage/materi/quiz/update";
const _URL_EDIT_MATERI_QUIZ   = _URL_MAIN_WEBSITE + "manage/materi/quiz/edit";
const _URL_REORDER_MATERI_QUIZ = _URL_MAIN_WEBSITE + "manage/materi/quiz/reorder";

let _jumlah_soal = 0;

$(document).ready(function () {

    _jumlah_soal = parseInt($('#total_data').attr('data-jumlah')) || 0;

    muncullinJenisSoal();
    addSoalBaru();
    deleteSelectedSoal();
    deleteSingleSoal();
    saveCard();

    initSortable();
    updateOrderNumbers();

    // tandai card berubah → tampilkan save
    $(document).on('input change', '.pertanyaan, .jenis-soal, .keterangan, .opsi-a, .opsi-b, .opsi-c, .opsi-d, .jawaban-akhir', function () {
        $(this).closest('.card-item').find('.save-card').show();
    });

});

/* ========== SORTABLE / DRAG & DROP ========== */
function initSortable() {
    if (!$.fn.sortable) {
        console.warn('jQuery UI sortable tidak tersedia');
        return;
    }

    $('#card-mode').sortable({
        handle: '.drag-handle',
        items: '.card-item',
        cursor: 'move',
        opacity: 0.85,
        tolerance: 'pointer',
        placeholder: 'col-md-6 mb-4 card-item ui-sortable-placeholder',
        forcePlaceholderSize: true,
        update: function () {
            updateOrderNumbers();
            saveOrdering();
        }
    });

    //$('#card-mode').disableSelection();
}

/* ========== AUTO-NUMBERING ========== */
function updateOrderNumbers() {
    let n = 1;
    $('#card-mode .card-item').each(function () {
        let padded = String(n).padStart(2, '0');
        $(this).find('.order-badge').text('#' + padded);
        n++;
    });
}

/* ========== SIMPAN URUTAN KE SERVER ========== */
function saveOrdering() {
    let items = [];

    $('#card-mode .card-item').each(function (index) {
        let idna = $(this).data('id');
        if (idna) {
            items.push({
                id: idna,
                ordering_index: index + 1
            });
        }
    });

    if (items.length === 0) return;

    $.ajax({
        url: _URL_REORDER_MATERI_QUIZ,
        method: 'POST',
        data: { items: items },
        dataType: 'json',
        success: function (response) {
            console.log('Reorder saved:', response);
        },
        error: function (xhr) {
            console.error('Gagal simpan urutan:', xhr.responseText);
        }
    });
}

/* ========== TAMBAH SOAL ========== */
function addSoalBaru() {
    $('#add-card').on('click', function (e) {
        e.preventDefault();

        let id_materi = $('#id_materi').val();

        let cardHtml = `
        <div class="col-md-6 mb-4 card-item" data-id-materi="${id_materi}" data-id="">
            <div class="card h-100">
                <div class="card-body">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <input type="checkbox" class="form-check-input is-selected">
                        <span class="drag-handle" title="Drag untuk pindah">
                            <i class="fas fa-grip-vertical text-muted"></i>
                        </span>
                        <span class="order-badge badge bg-secondary">#00</span>
                    </div>

                    <label>Pertanyaan:</label>
                    <textarea class="form-control mb-2 pertanyaan" placeholder="tulis Pertanyaan disini"></textarea>

                    <select class="form-select mb-2 jenis-soal">
                        <option value="essay">Essay</option>
                        <option value="pg2">PG 2 opsi</option>
                        <option value="pg4">PG 4 opsi</option>
                    </select>

                    <div class="pg-opsi mb-2" style="display:none;">
                        <input type="text" class="form-control mb-1 opsi-a" placeholder="Opsi A">
                        <input type="text" class="form-control mb-1 opsi-b" placeholder="Opsi B">
                        <input type="text" class="form-control mb-1 opsi-c" placeholder="Opsi C" style="display:none;">
                        <input type="text" class="form-control mb-1 opsi-d" placeholder="Opsi D" style="display:none;">
                    </div>

                    <textarea class="form-control mb-2 keterangan" placeholder="Keterangan"></textarea>

                    <div class="mb-2 jawaban-akhir-part d-none">
                        <label>Jawaban Final:</label>
                        <select class="form-select mb-2 jawaban-akhir"></select>
                    </div>

                    <button class="btn btn-sm btn-danger delete-card">Delete</button>
                    <button class="btn btn-sm btn-success float-end save-card">Save</button>
                </div>
            </div>
        </div>`;

        $('#card-mode').append(cardHtml).show();
        incrementTotalData();
        updateOrderNumbers();   // <-- tambahkan ini biar nomor langsung terisi
    });
}

/* ========== TOGGLE JENIS SOAL ========== */
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
            jawaban.html(`
                <option value="A">Opsi A</option>
                <option value="B">Opsi B</option>
            `);
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

/* ========== SAVE ========== */
function saveCard() {
    $(document).on('click', '.save-card', function (e) {
        e.preventDefault();

        let card = $(this).closest('.card-item');
        let btn  = $(this);

        let idna      = card.data('id');
        let id_materi = card.data('id-materi') || $('#id_materi').val();
       // let id_user   = $('#id_user').val();

        let pertanyaan = card.find('.pertanyaan').val();
        let jenis      = card.find('.jenis-soal').val();
        let keterangan = card.find('.keterangan').val();
        let final_ans  = card.find('.jawaban-akhir').val() || '';

        let opsi_a = card.find('.opsi-a').val() || '';
        let opsi_b = card.find('.opsi-b').val() || '';
        let opsi_c = card.find('.opsi-c').val() || '';
        let opsi_d = card.find('.opsi-d').val() || '';

        if (!pertanyaan || pertanyaan.trim() === '') {
            Swal.fire({
                icon: 'warning',
                title: 'Pertanyaan kosong',
                text: 'Isi pertanyaan terlebih dahulu.'
            });
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
            opsi_d: opsi_d
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
                console.log('Quiz saved:', response);

                if (response.status === 'valid') {
                    btn.hide();

                    // kalau baru, simpan ID ke card
                    if (!idna && response.id) {
                        card.attr('data-id', response.id);

                        // setelah ID ada, sinkronkan urutan ke server
                        saveOrdering();
                    }
                } else {
                    btn.prop('disabled', false).text('Save');
                    Swal.fire('Gagal', response.message || 'Error', 'error');
                }
            },
            error: function (xhr) {
                console.error(xhr.responseText);
                btn.prop('disabled', false).text('Save');
                Swal.fire('Error', 'Gagal menyimpan quiz', 'error');
            }
        });
    });
}

/* ========== DELETE SINGLE ========== */
function deleteSingleSoal() {
    $(document).on('click', '.delete-card', function (e) {
        e.preventDefault();

        let card = $(this).closest('.card-item');
        let idna = card.data('id');

        // belum disimpan → langsung buang
        if (!idna) {
            card.remove();
            decrementTotalData();
            return;
        }

        Swal.fire({
            title: 'Menghapus...',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => Swal.showLoading()
        });

        $.ajax({
            url: _URL_DELETE_MATERI_QUIZ,
            method: 'POST',
            data: { id: idna },
            dataType: 'json',
            success: function (response) {
                if (response.status === 'valid') {
                    card.remove();
                    decrementTotalData();
                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus',
                        timer: 1000,
                        showConfirmButton: false
                    });
                } else {
                    Swal.close();
                    Swal.fire('Gagal', response.message || 'Error', 'error');
                }
            },
            error: function () {
                Swal.close();
                Swal.fire('Error', 'Gagal menghapus quiz', 'error');
            }
        });
    });
}

/* ========== DELETE BULK (via checkbox) ========== */
function deleteSelectedSoal() {
    $('#delete-selected').on('click', function (e) {
        e.preventDefault();

        let $checked = $('.is-selected:checked');

        if ($checked.length === 0) {
            Swal.fire({
                icon: 'info',
                title: 'Tidak ada item',
                text: 'Pilih dulu item yang ingin dihapus.'
            });
            return;
        }

        Swal.fire({
            icon: 'warning',
            title: 'Konfirmasi Hapus',
            text: `Yakin ingin menghapus ${$checked.length} item yang dipilih?`,
            showCancelButton: true,
            confirmButtonText: 'Ya, Hapus',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#d33'
        }).then((result) => {
            if (!result.isConfirmed) return;

            Swal.fire({
                title: 'Menghapus...',
                allowOutsideClick: false,
                allowEscapeKey: false,
                didOpen: () => Swal.showLoading()
            });

            let idsToDelete = [];
            let $cardsToRemove = [];

            $checked.each(function () {
                let card = $(this).closest('.card-item');
                let idna = card.data('id');

                if (idna) {
                    idsToDelete.push(idna);
                } else {
                    // belum disimpan → buang langsung
                    card.remove();
                    decrementTotalData();
                }
                $cardsToRemove.push(card);
            });

            if (idsToDelete.length === 0) {
                Swal.close();
                return;
            }

            $.ajax({
                url: _URL_DELETE_MATERI_QUIZ,
                method: 'POST',
                data: { id: idsToDelete },
                dataType: 'json',
                success: function (response) {
                    $cardsToRemove.forEach(c => {
                        if (c.data('id')) {
                            c.remove();
                            decrementTotalData();
                        }
                    });

                    Swal.fire({
                        icon: 'success',
                        title: 'Terhapus',
                        timer: 1000,
                        showConfirmButton: false
                    });
                },
                error: function () {
                    Swal.close();
                    Swal.fire('Error', 'Gagal menghapus quiz', 'error');
                }
            });
        });
    });
}

/* ========== UTIL ========== */
function incrementTotalData() {
    _jumlah_soal++;
    $('#total_data').text(_jumlah_soal + ' soal quiz.');
}

function decrementTotalData() {
    _jumlah_soal--;
    if (_jumlah_soal < 0) _jumlah_soal = 0;
    $('#total_data').text(_jumlah_soal + ' soal quiz.');
}