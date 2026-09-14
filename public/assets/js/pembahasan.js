const _URL_ADD_BAB_PEMBAHASAN       = _URL_MAIN_WEBSITE+ "manage/materi/pembahasan/bab/add";
const _URL_UPDATE_BAB_PEMBAHASAN    = _URL_MAIN_WEBSITE+ "manage/materi/pembahasan/bab/update";
const _URL_DELETE_BAB_PEMBAHASAN    = _URL_MAIN_WEBSITE+ "manage/materi/pembahasan/bab/delete";
const _URL_ADD_PEMBAHASAN           = _URL_MAIN_WEBSITE+ "manage/materi/pembahasan/add";
const _URL_DELETE_PEMBAHASAN        = _URL_MAIN_WEBSITE+ "manage/materi/pembahasan/delete";
const _URL_EDIT_PEMBAHASAN          = _URL_MAIN_WEBSITE+ "manage/materi/pembahasan/edit";
const _URL_UPDATE_PEMBAHASAN        = _URL_MAIN_WEBSITE+ "manage/materi/pembahasan/update";
const _URL_NEXT_NO_URUT_PEMBAHASAN  = _URL_MAIN_WEBSITE+ "manage/materi/pembahasan/no-urut/next";

function inputOnBab(){

    // deteck enter on input judul
    $(document).on("keypress", ".judul, .deskripsi", function(e){ 

            $(this).parent().find('.save-card').show();

    });

}

let _jumlah_bab = 0;
let _card_used = null;

function createCard() {
    let id_materi = $('#id_materi').val();
    incrementTotalData();

    return `
     <div class="col-md-6 mb-4 card-item" data-id-materi="${id_materi}" data-id="">
        <div class="card h-100">
            <div class="card-body">

                <input type="checkbox" class="form-check-input selected-card mb-2">

                <input type="text" class="form-control mb-2 judul" 
                       placeholder="Judul Bab" value="">

                <textarea class="form-control mb-2 deskripsi" 
                          placeholder="Deskripsi"></textarea>

                <div class="d-flex justify-content-between mb-3">
                    <button class="btn btn-sm btn-danger delete-card" data-id="${id_materi}">
                        Delete
                    </button>
                    <button class="btn btn-sm btn-success save-card" data-id="${id_materi}">
                        Save
                    </button>
                </div>

                <hr>

                <div class="pembahasan-section">
                    <a href="#" class="add-pembahasan" data-bs-toggle="modal" data-bs-target="#modalPembahasan">
                        + Add Pembahasan
                    </a>
                    <ul class="list-group mt-2"></ul>
                </div>

            </div>
        </div>
     </div>`;
}

function incrementTotalData(){
    _jumlah_bab++;
    $('#total_data').text(_jumlah_bab + ' bab.');
}

function decrementTotalData(){
    _jumlah_bab--;
    $('#total_data').text(_jumlah_bab + ' bab.');
}

$(document).ready(function() {  

    _jumlah_bab = $('#total_data').attr('data-jumlah');

    // showing save button when user changes data inform
    inputOnBab();

    $("#add-card").on("click", function(e){
        e.preventDefault();
        $("#card-mode").show();     
        $("#card-mode").append(createCard());
    });

    $(document).on("click", ".save-card", function(){
        let card = $(this).closest(".card-item");
        let id_userna = $('#id_user').val();
        let id_babna = card.data("id");          // ID bab (kosong jika baru)
        let id_materina = card.data("id-materi");
        let id_materi_custom = $('#id_materi_custom').val();

        let judul_na = card.find(".judul").val();
        let deskripsi_na = card.find(".deskripsi").val();
        let btn_save = card.find('.save-card');

        let dataNa = null;
        let urlTarget = '';

        if (id_babna) {
            // UPDATE bab yang sudah ada
            dataNa = {
                id: id_babna,
                id_materi: id_materina,
                id_user: id_userna,
                judul: judul_na,
                deskripsi: deskripsi_na
            };
            urlTarget = _URL_UPDATE_BAB_PEMBAHASAN;
        } else {
            // INSERT bab baru
            dataNa = {
                id_materi: id_materina,
                id_user: id_userna,
                judul: judul_na,
                deskripsi: deskripsi_na
            };
            if (id_materi_custom) {
                dataNa.id_materi_custom = id_materi_custom;
            }
            urlTarget = _URL_ADD_BAB_PEMBAHASAN;
        }

        $.ajax({
            url: urlTarget,
            method: "POST",
            data: dataNa,
            dataType: "json",
            success: function(response) {
                console.log("Card saved successfully:", response);
                btn_save.hide();

                // ✅ UPDATE data-id card dengan ID baru dari server
                if (!id_babna && response.id) {
                    card.attr("data-id", response.id);
                    card.find(".delete-card").data("id", response.id);
                    card.find(".save-card").data("id", response.id);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error saving card:", error);
                btn_save.show();
            }
        });
    });
    
    // DELETE
    $(document).on("click", ".delete-card", function(){
    let card = $(this).closest(".card-item");
    let idna = card.data("id");   // pakai data card, bukan tombol

    // Kalau card belum disimpan, langsung hapus dari DOM tanpa reload
    if (!idna) {
        card.remove();
        decrementTotalData();
        return;
    }

    Swal.fire({
        title: 'Menghapus...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => { Swal.showLoading(); }
    });

    $.ajax({
        url: _URL_DELETE_BAB_PEMBAHASAN,
        method: "POST",
        data: {id: idna},
        dataType: "json",
        success: function () {
            location.reload();
        },
        error: function (xhr, status, error) {
            console.error("Error deleting card:", error);
            Swal.close();
            Swal.fire('Error', 'Gagal menghapus bab.', 'error');
        }
    });
});
  
   

    // delete pembahasan yg dipilih
   $(document).on('click', '.remove-pembahasan', function(){
    let idna = $(this).data('id');

    Swal.fire({
        title: 'Menghapus...',
        text: 'Mohon tunggu sebentar',
        allowOutsideClick: false,
        allowEscapeKey: false,
        didOpen: () => { Swal.showLoading(); }
    });

    $.ajax({
        url: _URL_DELETE_PEMBAHASAN,
        method: "POST",
        data: {id: idna},
        dataType: "json",
        success: function () {
            location.reload();
        },
        error: function (xhr, status, error) {
            console.error("Error deleting pembahasan:", error);
            Swal.close();
            Swal.fire('Error', 'Gagal menghapus pembahasan.', 'error');
        }
    });
});

    // delete item dari card sesuai checkbox
   $(document).on('click', '#delete-selected', function(){
    let $checked = $('#card-mode .selected-card:checked');

    if ($checked.length === 0) {
        Swal.fire({
            icon: 'info',
            title: 'Tidak ada item',
            text: 'Pilih dulu item yang ingin dihapus.',
            confirmButtonText: 'OK'
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
            text: 'Mohon tunggu sebentar',
            allowOutsideClick: false,
            allowEscapeKey: false,
            didOpen: () => { Swal.showLoading(); }
        });

        let promises = [];

        $checked.each(function(){
            let card = $(this).closest(".card-item");
            let idna = card.data("id");

            if (idna) {
                promises.push($.ajax({
                    url: _URL_DELETE_BAB_PEMBAHASAN,
                    method: "POST",
                    data: {id: idna},
                    dataType: "json"
                }));
            } else {
                // belum disimpan, hapus di DOM saja
                card.remove();
                decrementTotalData();
            }
        });

        if (promises.length > 0) {
            $.when.apply($, promises).always(function(){
                location.reload();
            });
        } else {
            Swal.close();
        }
    });
});

    // saat mulai di klik
    $(document).on('click', '.add-pembahasan', function(e){
        e.preventDefault();

        let card = $(this).closest(".card-item");
        let id_babna = card.data("id");

        // ✅ Validasi: bab harus sudah disimpan
        if (!id_babna || id_babna === "") {
            Swal.fire({
                icon: 'warning',
                title: 'Bab belum disimpan',
                text: 'Simpan bab terlebih dahulu sebelum menambah pembahasan.',
                confirmButtonText: 'OK'
            });
            return;
        }

        _card_used = card;
        $('#formPembahasan')[0].reset();

        let id_materina = card.data('id-materi');
        let id_materi_customna = $('#id_materi_custom').val();

        $('#materi_id').val(id_materina);
        $('#pembahasan_id_bab').val(id_babna);
        $('#pembahasan_id_user').val($('#id_user').val());

        let dataNa = { id_bab: id_babna };
        if (id_materi_customna) {
            dataNa.id_materi_custom = id_materi_customna;
        }

        $.ajax({
            url: _URL_NEXT_NO_URUT_PEMBAHASAN,
            method: 'POST',
            data: dataNa,
            dataType: 'json',
            success: function(response) {
                $('#pembahasan_ordering_index').val(response.data);
            },
            error: function() {
                $('#pembahasan_ordering_index').val('1');
            }
        });

        $('#modalPembahasan').modal('show');
    });
  
    // edit pembahasan
    $(document).on('click', '.edit-pembahasan', function(){ 

        let idna = $(this).data('id');

        let el = $(this).closest('li');

        // AJAX get data pembahasan dari backend
        $.ajax({
            url: _URL_EDIT_PEMBAHASAN,
            method: "POST",
            data: {id: idna},
            dataType: "json",
            success: function(response) {

                console.log("Pembahasan fetched successfully:", response);

                // isi form dengan data yg didapat
                $('#formPembahasan')[0].reset();

                $('#pembahasan_id').val(response.data.id);
                $('#pembahasan_id_bab').val(response.data.id_bab);
                $('#pembahasan_id_user').val(response.data.id_user);
                $('#pembahasan_ordering_index').val(response.data.ordering_index);
                $('#judul').val(response.data.judul);
                
                // render ke editor  <trix-editor  
             
                $('#deskripsi').val(response.data.deskripsi);
                document.querySelector('trix-editor[input="deskripsi"]').editor.loadHTML(response.data.deskripsi);

                // simpan card yg digunakan
                _card_used = el.closest('.card-item');

                // tampilkan modal
                $('#modalPembahasan').modal('show');

            },
            error: function(xhr, status, error) {
                console.error("Error fetching pembahasan:", error);
            }
        });

    });

    // add pembahasan form works
    $('#formPembahasan').on('submit', function(event) {
        event.preventDefault();
        const formData = $(this).serialize();

        let judul = $(this).find('#judul').val();
        let urlTarget = _URL_ADD_PEMBAHASAN;
        let idna = $(this).find('#pembahasan_id').val();

        if (idna) {
            urlTarget = _URL_UPDATE_PEMBAHASAN;
        }

        $.ajax({
            url: urlTarget,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                console.log(response);
                $('#modalPembahasan').modal('hide');

                // ✅ Reload halaman agar data ter-render ulang dengan benar
                // Ini menghindari duplikasi/manipulasi DOM yang rapuh
                location.reload();
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });

});
