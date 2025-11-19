const _URL_ADD_COMMENTS 			= "/comments-rating/add";
const _URL_ALL_COMMENTS 			= "/comments-rating/all";

$(document).ready(function() {

    submit_comments();
    update_info_paket();
    lanjutkan_pilihan();
    
});

function lanjutkan_pilihan(){

    $('.btn-pilih-materi').on('click', function(e) { 

        e.preventDefault();

        let id_materi = $('#materi_id').val();
        let opsi_paket = $('.btn_opsi_paket:checked').val();

        if (!opsi_paket) {
            // ganti ke swall warning
            Swal.fire({
                icon: 'warning',
                title: 'Peringatan',
                text: 'Silakan pilih opsi paket sebelum melanjutkan!',
                timer: 1500
            });
            
            return;
        }

        // Redirect ke halaman checkout dengan parameter yang sesuai
        let checkoutUrl = `/checkout?materi_id=${id_materi}&opsi_paket=${opsi_paket}`;
        window.location.href = checkoutUrl;

    });

}

function update_info_paket(){

    $('.btn_opsi_paket').on('change', function() {
        
        // 1. Bersihkan status 'active' dari SEMUA label dalam grup
        //    (Pastikan selector ini hanya mencakup opsi paket Anda)
        //    Selector yang aman: cari semua label di dalam .btn-group-toggle
        $(this).closest('.btn-group-toggle').find('label').removeClass('active');
        
        // 2. Tambahkan kelas 'active' HANYA ke label yang baru dicentang
        if ($(this).is(":checked")) {
            $(this).parent().addClass('active');
            
            // 3. Update Biaya
            let biaya = $(this).data('biaya');
            $('#materi-harga').text(asRupiah(biaya));
            let biayaPajak = biaya * 0.1; // 10% Pajak
            $('#biayapajak').text(asRupiah(biayaPajak));
        }
        
    });

}



function submit_comments(){

	$('#add-comment-form').on('submit', function(e) {
        e.preventDefault(); // Mencegah submit form bawaan HTML

        var form = $(this);
        var submitBtn = $('#submit-comment-btn');
        var originalBtnContent = submitBtn.html();

        // Tampilkan loading saat proses
        submitBtn.attr('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengirim...');

        $.ajax({
            url: _URL_ADD_COMMENTS, // Pastikan route ini sudah benar
            type: 'POST',
            data: form.serialize(), // Mengambil semua data form + CSRF token
            dataType: 'json',
            success: function(response) {
                if (response.status === 'success' || response.status === 'valid') {
                   
					// balik ke tab komentar
                    var triggerEl = document.getElementById("product-comments-tab");
                    var tab = new bootstrap.Tab(triggerEl);
                    tab.show();
                    
                    let id_materi = $('#materi_id').val();
                    loadComments(id_materi);

                    // Bersihkan form setelah submit sukses
                    form[0].reset();
                    
                } else {
                    // Jika ada error dari Controller
                    alert('Gagal: ' + response.message);
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                // Jika ada error jaringan/server
                alert('Terjadi kesalahan saat menghubungi server: ' + errorThrown);
            },
            complete: function(jqXHR) {
                // Kembalikan tombol ke keadaan semula
                submitBtn.attr('disabled', false).html(originalBtnContent);
               
            }
        });
    });


}

function loadComments(postId) {

    $.post(_URL_ALL_COMMENTS, { id: postId }, function(res) {

        let html = "";
        if (typeof res === 'string') res = JSON.parse(res);

        if (!res.data || res.data.length === 0) {
            html = `<p>Belum ada yang berikan komentar disini...</p>`;
        } else {
            res.data.forEach(dc => {

                let stars = "";
                for (let i = 1; i <= 5; i++) {
                    stars += (i <= dc.ratings)
                        ? `<i class="fa fa-star text-warning"></i>`
                        : `<i class="fa-regular fa-star text-warning"></i>`;
                }

                html += `
                    <div class="mb-2 pb-2 border-bottom">
                        <strong>${dc.username}</strong>
                        <span class="ms-1">${stars}</span>
                        <br>
                        <small class="text-muted">${dc.date_created}</small>
                        <p class="mt-1">${dc.comments}</p>
                    </div>
                `;
            });
        }

        $("#comment-list-area").html(html);
    });
}
