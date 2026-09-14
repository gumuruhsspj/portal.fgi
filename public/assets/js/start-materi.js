const _URL_DISPLAY_PEMBAHASAN 		= _URL_MAIN_WEBSITE + "materi/pembahasan";
const _URL_DOWNLOAD_MATERI 			= _URL_MAIN_WEBSITE + "materi/download";
const _URL_COMPLETED_MATERI         = _URL_MAIN_WEBSITE + "materi/pembahasan/completed";

$(document).ready(function() {

    $('.btn-back').hide();
    $('.btn-complete').hide();

    download_materi();
    display_materi();
    print_page();
    complete_study();
    connect_alive();

     
   // Toggle chapter (induk) -- klik judul bab untuk show/hide sub
$(document).on('click', '.chapter-header', function () {
    let $body = $(this).siblings('.chapter-body');
    let $icon = $(this).find('.chapter-icon');

    $body.slideToggle(180, function () {
        // Setelah animasi selesai, cek state-nya
        if ($body.is(':visible')) {
            $icon.removeClass('fa-folder').addClass('fa-folder-open');
        } else {
            $icon.removeClass('fa-folder-open').addClass('fa-folder');
        }
    });
});
    
});

function print_page(){

  $('.btn-print').on('click', function() {
    window.print();
});

}

function connect_alive(){

    $('.btn-connect').on('click', function(){
        let url = $(this).data('url');

        window.location.href = url;

    });

}

function complete_study() {
    // ✅ .off() dulu untuk hapus handler lama, baru pasang yang baru
    $(document).off('click', '.btn-complete').on('click', '.btn-complete', function () {

        var id_materi = $(this).data('id');
        var $btn = $(this);

        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

        $.ajax({
            url: _URL_COMPLETED_MATERI,
            type: 'POST',
            data: { id_materi: id_materi },
            dataType: 'json',
            success: function (response) {

                // ===== PRIORITAS 1: ada quiz → tendang ke quiz =====
                if (response.status === 'success' && response.has_quiz === true && response.redirect) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Materi Selesai!',
                        text: 'Lanjut ke Quiz untuk menyelesaikan materi ini.',
                        confirmButtonText: 'Mulai Quiz',
                        allowOutsideClick: false,
                        allowEscapeKey: false
                    }).then(function () {
                        window.location.href = response.redirect;
                    });
                    return;
                }

                // ===== FALLBACK: tidak ada quiz =====
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Materi telah selesai.',
                    timer: 2000,
                    showConfirmButton: false
                }).then(function () {
                    window.location.href = _URL_MAIN_WEBSITE + 'all-materi';
                });
            },
            error: function (xhr, status, error) {
                console.error('Gagal:', error);
                $btn.prop('disabled', false).html('<i class="fas fa-check"></i> SAYA SELESAI');
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan, silakan coba lagi.',
                    confirmButtonText: 'OK'
                });
            }
        });
    });
}

function display_materi(){

    $('.btn-nav, .custom-checkbox label').on('click', function(){

        let idPembahasan = $(this).data('target-id');

        $.post(_URL_DISPLAY_PEMBAHASAN, {
            id_pembahasan: idPembahasan
        }, function(response) {

            if (response.status === 'success') {
                let hasNext = response.hasNext;
                let hasBack = response.hasBack;
                let nextId  = response.next_id;
                let prevId  = response.prev_id;

                $('#deskripsi-detail-materi').html(response.data.deskripsi);
                $('#judul-detail-materi').text(response.data.judul);

                if (hasNext) {
                    $('.btn-next').show();
                    $('.btn-next').data('target-id', nextId);
                    $('.btn-complete').hide();
                } else {
                    $('.btn-next').hide();
                    $('.btn-complete').show();
                }

                if (hasBack) {
                    $('.btn-back').show();
                    $('.btn-back').data('target-id', prevId);
                } else {
                    $('.btn-back').hide();
                }

                // ✅ Highlight item aktif di tree
                highlightActiveItem(idPembahasan);
            }

        });

    });

}

// ✅ Fungsi highlight + auto-expand parent chapter
function highlightActiveItem(idPembahasan) {
    if (!idPembahasan) return;

    // Reset semua highlight
    $('.chapter-body .custom-checkbox').removeClass('active-item');

    // Cari label yang cocok
    let $label = $('.custom-control-label[data-target-id="' + idPembahasan + '"]');
    if (!$label.length) return;

    let $wrapper = $label.closest('.custom-checkbox');
    $wrapper.addClass('active-item');

    // Auto-expand parent chapter jika collapsed
    let $chapterBody = $wrapper.closest('.chapter-body');
    if ($chapterBody.length && !$chapterBody.is(':visible')) {
        $chapterBody.slideDown(180);
        $chapterBody.siblings('.chapter-header')
            .find('.chapter-icon')
            .removeClass('fa-folder')
            .addClass('fa-folder-open');
    }
}

function download_materi(){

    $('.link-download').on('click', function(){
        let idMateri = $(this).data('id');
        window.location.href =  _URL_DOWNLOAD_MATERI + '/' + idMateri;
    });

}