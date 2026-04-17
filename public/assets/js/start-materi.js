const _URL_DISPLAY_PEMBAHASAN 			= "/materi/pembahasan";
const _URL_DOWNLOAD_MATERI 			= "/materi/download";
const _URL_COMPLETED_MATERI         = "/materi/pembahasan/completed";

$(document).ready(function() {

    $('.btn-back').hide();
    $('.btn-complete').hide();

    download_materi();
    display_materi();
    print_page();
    complete_study();
    connect_alive();
    
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

function complete_study(){

  
$('.btn-complete').on('click', function() {
    var id_materi = $(this).data('id');
    var $btn = $(this); // simpan referensi tombol (opsional, untuk disable)

    // Opsional: disable tombol sementara agar tidak double klik
    $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

    $.ajax({
        url: _URL_COMPLETED_MATERI,
        type: 'POST',
        data: { id_materi: id_materi },
        success: function(response) {
            console.log('Berhasil:', response);

            // Tampilkan SweetAlert sukses
            Swal.fire({
                icon: 'success',
                title: 'Berhasil!',
                text: 'Materi telah selesai.',
                timer: 2000,          // auto close setelah 2 detik
                showConfirmButton: false
            }).then(() => {
                // Redirect setelah alert tertutup (atau auto close)
                window.location.href = '/all-materi';
            });

        },
        error: function(xhr, status, error) {
            console.error('Gagal:', error);
            // Kembalikan tombol ke keadaan semula jika error
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
        
        //alert('kirim ' + idPembahasan);

          $.post(_URL_DISPLAY_PEMBAHASAN, {
            id_pembahasan: idPembahasan
        }, function(response) {
    
             if(response.status === 'success') {
                    // Ambil status dan ID navigasi dari response
                    let hasNext = response.hasNext; 
                    let hasBack = response.hasBack;
                    let nextId  = response.next_id; // ID asli dari DB
                    let prevId  = response.prev_id; // ID asli dari DB

                    $('#deskripsi-detail-materi').html(response.data.deskripsi);
                    $('#judul-detail-materi').text(response.data.judul);

                    // Logika Tombol Next
                    if(hasNext){
                        $('.btn-next').show();
                        // Pakai nextId dari server, jangan +1 manual
                        $('.btn-next').data('target-id', nextId);
                         $('.btn-complete').hide();
                    } else {
                        $('.btn-next').hide();
                        $('.btn-complete').show();
                    }

                    // Logika Tombol Back
                    if(hasBack){
                        $('.btn-back').show();
                        // Pakai prevId dari server, jangan -1 manual
                        $('.btn-back').data('target-id', prevId);
                        
                    } else {
                        $('.btn-back').hide();
                    }

                }
            
            }
    
        );

    });

}

function download_materi(){

    $('.link-download').on('click', function(){
        let idMateri = $(this).data('id');
        window.location.href = _URL_DOWNLOAD_MATERI + '/' + idMateri;
    });

}