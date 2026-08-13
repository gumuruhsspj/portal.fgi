// manage-program-afiliasi.js

const _URL_ADD_PROGRAM_AFILIASI   = _URL_MAIN_WEBSITE+ "manage/program-afiliasi/add";
const _URL_DELETE_PROGRAM_AFILIASI  = _URL_MAIN_WEBSITE+ "manage/program-afiliasi/delete";
const _URL_EDIT_PROGRAM_AFILIASI    = _URL_MAIN_WEBSITE+"manage/program-afiliasi/edit";
const _URL_UPDATE_PROGRAM_AFILIASI  = _URL_MAIN_WEBSITE+"manage/program-afiliasi/update";
const _URL_GET_KATEGORI_MEDIA       = _URL_MAIN_WEBSITE+"manage/media-category/list";
const _URL_ADD_KATEGORI_MEDIA       = _URL_MAIN_WEBSITE+"manage/media-category/add";

const _DEBUG = true;

$(document).ready(function() {

    // Saat modal ditampilkan (tambah), kosongkan form dan muat kategori
    $('#programAfiliasiModal').on('show.bs.modal', function() {
        // Jika tidak ada id (tambah), reset form
        if ($('#hidden_id-program-afiliasi').val() == '') {
            clearForm();
            loadKategoriDropdown(function() {
                // Tambahkan satu baris kosong default
                addKategoriRow();
            });
            $('#programAfiliasiForm').attr('action', _URL_ADD_PROGRAM_AFILIASI);
            $('#programAfiliasiModalLabel').text('Tambah Program Afiliasi');
        }
    });

    // Submit form
    $('body').on('submit', '#programAfiliasiForm', function(e){
        e.preventDefault();
        var formData = new FormData(this);
        // Tambahkan array kategori dan komisi
        var kategori_ids = [];
        var komisi_persens = [];
        $('#kategoriContainer .kategori-row').each(function() {
            var id = $(this).find('.kategori-select').val();
            var komisi = $(this).find('.komisi-input').val();
            if (id) {
                kategori_ids.push(id);
                komisi_persens.push(komisi || 0);
            }
        });
        kategori_ids.forEach(function(val) {
            formData.append('kategori_ids[]', val);
        });
        komisi_persens.forEach(function(val) {
            formData.append('komisi_persens[]', val);
        });

        var url = $('#programAfiliasiForm').attr('action');
        sendRequest(formData, url);
    });

    // Tombol tambah kategori
    $('body').on('click', '#addKategoriRow', function() {
        addKategoriRow();
    });

    // Hapus baris kategori
    $('body').on('click', '.remove-kategori-row', function() {
        $(this).closest('.kategori-row').remove();
    });

    // Edit: ambil data
    $('body').on('click', '.edit-single', function(e){
        e.preventDefault();
        var dataId = $(this).data('id');
        clearForm();
        $('#kategoriContainer').empty();
        $.ajax({
            url: _URL_EDIT_PROGRAM_AFILIASI + '/' + dataId,
            type: 'POST',
            data: {id: dataId},
            dataType: 'json',
            success: function(res) {
                if (res.status == 'success') {
                    var data = res.data;
                    $('#hidden_id-program-afiliasi').val(data.id);
                    $('#nama-program').val(data.nama);
                    $('#deskripsi-program').text(data.deskripsi);
                    if (data.icon && data.icon != 'question.png') {
                        $('#previewIcon').attr('src', _URL_MAIN_WEBSITE + 'assets/img/uploads/afiliasi/' + data.icon);
                        $('#delete-icon').show();
                    } else {
                        $('#previewIcon').attr('src', _URL_MAIN_WEBSITE +'assets/img/uploads/afiliasi/question.png');
                        $('#delete-icon').hide();
                    }
                    // Load kategori yang sudah ada
                    loadKategoriDropdown(function() {
                        if (data.kategori && data.kategori.length > 0) {
                            data.kategori.forEach(function(item) {
                                addKategoriRow(item.id_kategori, item.komisi_persen);
                            });
                        } else {
                            // Tambahkan satu baris kosong
                            addKategoriRow();
                        }
                    });
                    $('#programAfiliasiForm').attr('action', _URL_UPDATE_PROGRAM_AFILIASI);
                    $('#programAfiliasiModalLabel').text('Edit Program Afiliasi');
                    $('#programAfiliasiModal').modal('show');
                } else {
                    alert('Gagal mengambil data');
                }
            },
            error: function() {
                alert('Terjadi kesalahan');
            }
        });
    });

    // Delete single
    $('body').on('click', '.delete-single', function(e){
        e.preventDefault();
        if (!confirm('Yakin hapus?')) return;
        var id = $(this).data('id');
        sendRequest({id: id}, _URL_DELETE_PROGRAM_AFILIASI);
    });

    // Delete selected
    $('#delete-selected').click(function(e) {
        e.preventDefault();
        var checkedIds = [];
        $('#table-management-program-afiliasi input[type="checkbox"]:checked').each(function() {
            var dataId = $(this).data('id');
            if (dataId) checkedIds.push(dataId);
        });
        if (checkedIds.length === 0) return;
        if (!confirm('Hapus ' + checkedIds.length + ' data?')) return;
        checkedIds.forEach(function(id) {
            sendRequest({id: id}, _URL_DELETE_PROGRAM_AFILIASI);
        });
    });

    // Refresh
    $('#refresh-data').click(function() { location.reload(); });

    // Select all
    $('#select-all').change(function() {
        var checked = $(this).prop('checked');
        $('#table-management-program-afiliasi input[type="checkbox"]').prop('checked', checked);
    });

    // Hapus icon
    $('body').on('click', '#delete-icon', function(e) {
        e.preventDefault();
        $('#previewIcon').attr('src', _URL_MAIN_WEBSITE + 'assets/img/uploads/afiliasi/question.png');
        $('#icon-program').val('');
        $('#delete-icon').hide();
        // Jika edit, kita perlu memberi tahu server bahwa icon dihapus
        // Bisa dengan menambahkan hidden input
        if ($('#hidden_id-program-afiliasi').val()) {
            // Tambahkan field tersembunyi untuk menandakan hapus icon
            if (!$('#delete_icon_flag').length) {
                $('<input>').attr({type: 'hidden', id: 'delete_icon_flag', name: 'delete_icon', value: '1'}).appendTo('#programAfiliasiForm');
            }
        }
    });

    // ---- Tambah Kategori Media (modal kecil) ----
    $('body').on('click', '#btnAddCategoryMedia', function() {
        $('#modalAddCategoryMedia').modal('show');
    });

    $('body').on('click', '#btnSaveCategoryMedia', function() {
        var nama = $('#newCategoryName').val().trim();
        if (!nama) {
            alert('Nama kategori harus diisi');
            return;
        }
        $.ajax({
            url: _URL_ADD_KATEGORI_MEDIA,
            type: 'POST',
            data: {nama: nama},
            dataType: 'json',
            success: function(res) {
                if (res.status == 'success') {
                    // Tutup modal kecil
                    $('#modalAddCategoryMedia').modal('hide');
                    $('#newCategoryName').val('');
                    // Refresh dropdown di semua baris
                    loadKategoriDropdown(function() {
                        // Tambahkan baris baru dengan kategori yang baru dibuat
                        // Kita perlu tahu id kategori yang baru, dari response
                        if (res.data && res.data.id) {
                            addKategoriRow(res.data.id);
                        } else {
                            // Jika response tidak mengembalikan id, reload dropdown dan tambahkan baris kosong
                            addKategoriRow();
                        }
                    });
                } else {
                    alert('Gagal menambah kategori: ' + (res.message || ''));
                }
            },
            error: function() {
                alert('Terjadi kesalahan');
            }
        });
    });

    // Saat modal kecil ditutup, reset form
    $('#modalAddCategoryMedia').on('hidden.bs.modal', function() {
        $('#newCategoryName').val('');
    });

    // Inisialisasi DataTable
    new DataTable('#table-management-program-afiliasi');
});

// Fungsi untuk load dropdown kategori media
function loadKategoriDropdown(callback) {
    $.ajax({
        url: _URL_GET_KATEGORI_MEDIA,
        type: 'GET',
        dataType: 'json',
        success: function(res) {
            if (res.status == 'success') {
                var options = '<option value="">-- Pilih Kategori --</option>';
                res.data.forEach(function(kat) {
                    options += '<option value="' + kat.id + '">' + kat.nama + '</option>';
                });
                window.kategoriOptions = options;
                // Update semua dropdown yang sudah ada
                $('.kategori-select').each(function() {
                    var currentVal = $(this).val();
                    $(this).html(window.kategoriOptions);
                    if (currentVal) $(this).val(currentVal);
                });
                if (typeof callback === 'function') callback();
            } else {
                alert('Gagal memuat kategori');
            }
        },
        error: function() {
            alert('Terjadi kesalahan saat memuat kategori');
        }
    });
}

// Fungsi untuk menambah baris kategori
function addKategoriRow(selectedId, komisiVal) {
    var container = $('#kategoriContainer');
    var row = $('<div class="kategori-row row mb-2">');
    // Kolom dropdown
    var col1 = $('<div class="col-md-6">');
    var select = $('<select class="form-control kategori-select">');
    if (window.kategoriOptions) {
        select.html(window.kategoriOptions);
    } else {
        select.html('<option value="">-- Pilih Kategori --</option>');
    }
    if (selectedId) {
        select.val(selectedId);
    }
    col1.append(select);

    // Kolom komisi
    var col2 = $('<div class="col-md-4">');
    var inputKomisi = $('<input type="number" class="form-control komisi-input" placeholder="Komisi %" min="0" max="100">');
    if (komisiVal !== undefined && komisiVal !== null) {
        inputKomisi.val(komisiVal);
    }
    col2.append(inputKomisi);

    // Tombol hapus
    var col3 = $('<div class="col-md-2">');
    var btnRemove = $('<button type="button" class="btn btn-sm btn-danger remove-kategori-row"><i class="fas fa-times"></i></button>');
    col3.append(btnRemove);

    row.append(col1).append(col2).append(col3);
    container.append(row);
}

function clearForm() {
    $('#hidden_id-program-afiliasi').val('');
    $('#nama-program').val('');
    $('#deskripsi-program').text('');
    $('#icon-program').val('');
    $('#previewIcon').attr('src', _URL_MAIN_WEBSITE +'assets/img/uploads/afiliasi/question.png');
    $('#delete-icon').hide();
    $('#kategoriContainer').empty();
    $('#programAfiliasiForm').attr('action', _URL_ADD_PROGRAM_AFILIASI);
    $('#delete_icon_flag').remove();
}

// Fungsi sendRequest
function sendRequest(datana, URLna) {
    if (datana instanceof FormData) {
        sendRequestForm(datana, URLna);
    } else {
        sendRequestReguler(datana, URLna);
    }
}

function sendRequestForm(datana, URLna) {
    $.ajax({
        url: URLna,
        type: 'POST',
        data: datana,
        processData: false,
        contentType: false,
        dataType: 'json',
        success: function(response) {
            if (response.status == 'success') {
                location.reload();
            } else {
                alert('Gagal: ' + (response.message || ''));
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}

function sendRequestReguler(datana, URLna) {
    $.ajax({
        url: URLna,
        type: 'POST',
        data: datana,
        dataType: 'json',
        success: function(response) {
            if (response.status == 'success') {
                location.reload();
            } else {
                alert('Gagal: ' + (response.message || ''));
            }
        },
        error: function(xhr, status, error) {
            console.error('Error:', error);
        }
    });
}