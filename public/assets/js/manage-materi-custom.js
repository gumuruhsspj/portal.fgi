const _URL_DELETE_CUSTOM_MATERI = '/manage/materi/custom/delete';
const _URL_DISPLAY_PEMBAHASAN = '/manage/materi/pembahasan?';
const _URL_EDIT_CUSTOM_MATERI = '/manage/materi/custom/edit';
const _URL_UPDATE_CUSTOM_MATERI = '/manage/materi/custom/update';

var table;

$(document).ready(function(){

    initiateTable();
    submitMateriCustom();
    editData();
    deleteAction();
    deleteSelectedData();
    refreshData();
    selectAllData();
    displayPembahasan();
    formDefault();

});

function formDefault(){

     $('#materiCustomModal').on('hidden.bs.modal', function () {
        $('#materiCustomForm')[0].reset();
        $('#hidden_id-materi-custom').val('');
        $('#materiCustomForm').attr('action', '/manage/materi/custom/add');
    });

}

function editData(){

     $(document).on('click', '.edit-single', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        // Panggil AJAX untuk mengambil data custom materi berdasarkan id
        $.ajax({
            url: _URL_EDIT_CUSTOM_MATERI, // sesuai route yang Anda buat
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(res) {
                
                if (res.status === 'success') {
                    // Isi form di modal yang sama
                    $('#hidden_id-materi-custom').val(res.data.id);
                    $('#judul-materi-custom').val(res.data.nama_template);
                    $('#deskripsi-materi-custom').val(res.data.deskripsi);
                    // Ubah action form ke update
                    $('#materiCustomForm').attr('action', _URL_UPDATE_CUSTOM_MATERI);
                    // Tampilkan modal
                    $('#materiCustomModal').modal('show');
                } else {
                    showError();
                }

            },
            error: function() {
                showError();
            }
        });
    });

}

function deleteSelectedData(){

      $('#delete-selected').click(function(e) {
        e.preventDefault();
        var selectedIds = [];
        $('.data-selected:checked').each(function() {
            selectedIds.push($(this).data('id'));
        });

        if (selectedIds.length === 0) {
            showWarning('Tidak ada data yang dipilih.');
            
            return;
        }

        Swal.fire({
            title: 'Hapus ' + selectedIds.length + ' data?',
            text: "Data akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus semua!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Kirim semua id dalam satu request (bisa array)
                $.ajax({
                    url: _URL_DELETE_CUSTOM_MATERI, // Anda perlu buat endpoint ini di controller
                    type: 'POST',
                    data: { ids: selectedIds },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            // Hapus semua baris yang dipilih dari DataTable
                            selectedIds.forEach(function(id) {
                                var rowToRemove = table.row(function(idx, data, node) {
                                    return $(node).find('.data-selected').data('id') == id;
                                });
                                rowToRemove.remove();
                            });
                            table.draw();
                            showSuccess('Data terpilih berhasil dihapus!');

                            setTimeout(function(){
                                location.reload();
                            },2000);
                            
                        } else {
                            showError();
                        }
                    },
                    error: function() {
                        showError();
                    }
                });
            }
        });
    });

}

function displayPembahasan(){

    $(document).on('click', '.pembahasan-single', function(e) {
        e.preventDefault();
        var cid = $(this).data('id');
        var id = $(this).data('id-materi');
        // Redirect ke halaman pembahasan custom materi
        let param = 'materi_id=' + id + '&custom_id=' + cid;
        window.location.href = _URL_DISPLAY_PEMBAHASAN + param;
    });

}

function selectAllData(){

    $('#select-all').click(function() {
        var isChecked = $(this).prop('checked');
        $('.data-selected').prop('checked', isChecked);
    });

}

function refreshData(){

      $('#refresh-data').click(function(e) {
        e.preventDefault();
        // Reload tabel tanpa refresh halaman dengan memuat ulang data via AJAX
        showInfo('Memuat ulang data...');
        
        location.reload(); // simple reload, bisa juga panggil AJAX get
    });


}

function initiateTable(){

      table = $('#table-management-materi').DataTable({
        "pageLength": 10,
        "language": {
            //"url": "/assets/js/datatables.id.json" // jika ada file bahasa indo
        }
    });

}

function deleteAction(){

     $(document).on('click', '.delete-single', function(e) {
        e.preventDefault();
        var id = $(this).data('id');
        var row = $(this).closest('tr');

        Swal.fire({
            title: 'Yakin hapus?',
            text: "Data ini akan dihapus permanen!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: _URL_DELETE_CUSTOM_MATERI,
                    type: 'POST',
                    data: { id: id },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status === 'success') {
                            // Hapus baris dari tabel (DataTable)
                            table.row(row).remove().draw();
                            
                            showSuccess('Data Berhasil dihapus'); 

                        } else {
                            
                             showError();

                        }
                    },
                    error: function() {
                        showError();
                    }
                });
            }
        });
    });

}


function showWarning(msg){

 Swal.fire({
                                icon: 'warning',
                                title: msg,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true
                            });

}

function showInfo(msg){

 Swal.fire({
                                icon: 'info',
                                title: msg,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true
                            });

}

function showError(){

 Swal.fire({
                                icon: 'error',
                                title: 'Server Error.',
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true
                            });

}

function showSuccess(msg){

     Swal.fire({
                                icon: 'success',
                                title: msg,
                                toast: true,
                                position: 'top-end',
                                showConfirmButton: false,
                                timer: 2000,
                                timerProgressBar: true
                            });   

}

function submitMateriCustom(){

    $('#materiCustomForm').on('submit', function(e) {
        e.preventDefault(); // Mencegah form reload halaman secara default

        let form = $(this);
        let url = form.attr('action');
        let formData = form.serialize(); // Ambil semua data input

        $.ajax({
            type: "POST",
            url: url,
            data: formData,
            dataType: "JSON",
            success: function(response) {
                // Asumsi backend kirim JSON { status: 'success', message: '...' }
               showSuccess('Data Berhasil disimpan'); 

                // Refresh halaman setelah 2 detik
                setTimeout(function() {
                    location.reload();
                }, 2000);
            },
            error: function(xhr, status, error) {
                // Handle jika ada error dari server
               showError();
            }
        });
    });


}