const _URL_UPDATE_PEMBAYARAN		= "/manage/pembayaran/update";

$(document).ready(function() {

    // Gunakan delegasi event jika button berada di dalam datatable/table dinamis
    $(document).on('click', '.approve-btn, .cancel-btn, .delete-btn', function(e) {
        e.preventDefault();

        const btn = $(this);
        const idData = btn.data('id');
        const statusData = btn.data('status');
        const originalText = btn.html();

        // 1. Konfirmasi sederhana
        if (!confirm('Apakah Anda yakin ingin memproses ini?')) {
            return false;
        }

        // 2. Visual Feedback: Loading State
        btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');

        // 3. Eksekusi AJAX
        $.ajax({
            url: _URL_UPDATE_PEMBAYARAN,
            type: 'POST',
            data: {
                id: idData,
                status: statusData,
                // Masukkan CSRF jika diaktifkan di CI4
                //"<?= csrf_token() ?>": "<?= csrf_hash() ?>"
            },
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success') {
                    // Beri notifikasi (bisa ganti alert dengan Toastr/SweetAlert jika ada)
                    //alert('Status berhasil diperbarui!');
                    
                    // Hilangkan tombol atau update baris table
                    btn.closest('tr').fadeOut(500, function() {
                        $(this).remove(); // Hapus baris setelah animasi selesai
                    });
                } else {
                    console.log('Gagal: ' + response.message);
                    btn.prop('disabled', false).html(originalText);
                }
            },
            error: function(xhr, status, error) {
                console.error("Error Log:", xhr.responseText);
                alert('Koneksi bermasalah atau server error.');
                
                // Kembalikan tombol jika error
                btn.prop('disabled', false).html(originalText);
            }
        });
    });

});