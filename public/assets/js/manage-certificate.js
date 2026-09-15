const _URL_TPL_DELETE = _URL_MAIN_WEBSITE + "manage/certificate/delete";

$(document).ready(function () {

    // Filter materi
    $('#filter-materi').on('change', function () {
        let mid = $(this).val();
        window.location.href = _URL_MAIN_WEBSITE + "manage/certificate" + (mid ? "?materi_id=" + mid : "");
    });

    // Tombol tambah template
    $('#btn-add-template').on('click', function (e) {
        e.preventDefault();
        let selected = $('#filter-materi').val() || _SELECTED_MATERI;
        if (!selected) {
            Swal.fire('Info', 'Pilih materi dulu di filter.', 'info');
            return;
        }
        window.location.href = _URL_MAIN_WEBSITE + "manage/certificate/editor?materi_id=" + selected;
    });

    // Delete
    $(document).on('click', '.btn-delete-tpl', function () {
        let id = $(this).data('id');
        Swal.fire({
            icon: 'warning',
            title: 'Hapus template ini?',
            text: 'File image juga akan dihapus.',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus'
        }).then(r => {
            if (!r.isConfirmed) return;
            $.post(_URL_TPL_DELETE, { id }, function (res) {
                if (res.status === 'valid') {
                    Swal.fire({ icon: 'success', title: 'Terhapus', timer: 800, showConfirmButton: false })
                        .then(() => location.reload());
                } else {
                    Swal.fire('Gagal', res.message || 'Error', 'error');
                }
            }, 'json');
        });
    });
});