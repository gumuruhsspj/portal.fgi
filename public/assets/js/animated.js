

$( document ).ready(function() {
    
	//alert('a');

	$('.featured').on('mouseover', function(){

		let idna = $(this).data('feature');
		//alert(idna);

		$('#picture-feature').removeClass();
		$('#picture-feature').addClass('col-xl-5');
		$('#picture-feature').addClass('feature-'+idna);

	});

	$('#kirim-pesan-wa').on('submit', function(e){
		e.preventDefault();

		// generate the data before submitting into whatsapp
		let phoneNumber = "6285795569337";
		let message = "Hello *admin Kursus Komputer!*";

		message = "saya *" + nama +"*";
		message = "ingin bertanya terkait *" + judul + "*";
		message = "begini...\n"+ pertanyaanlengkap;

		message = "semoga bisa mencerahkan saya, dan ini email saya *" + email + "*";

		const whatsappUrl = `https://wa.me/${phoneNumber}?text=${encodeURIComponent(message)}`;

	});

	kirimPesanEmail();
    clickDaftar();

});

function clickDaftar(){

    $('.btn-buy').on('click', function(){

        let jenis = $(this).data('jenis');

        $('#jenis-text').text(jenis);
        $('#jenis').val(jenis);

    });

    // kirim form nya
    $('#registerForm').on('submit', function(e) {
        e.preventDefault(); // Mencegah form submit biasa (page reload)
        
        var form = $(this);
        var submitButton = $('#submitBtn');
        var formData = form.serialize(); // Mengumpulkan data POST dari form

        // 1. Tampilkan SweetAlert Loading
        Swal.fire({
            title: 'Mengirim Data...',
            text: 'Mohon tunggu sebentar.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            },
        });

        // Disable tombol submit saat proses berlangsung
        submitButton.prop('disabled', true).text('Loading...');
        
        // 2. Lakukan Panggilan Ajax POST
        $.ajax({
            type: 'POST',
            url: '/register/send', // Endpoint di Controller CI4 Anda
            data: formData, // Mengirim nama_lengkap, email_user, no_wa
            dataType: 'json', // Harap respons dari server berupa JSON
            
            success: function(response) {
                // Sembunyikan Modal jika berhasil
                $('#registerModal').modal('hide');

                // Tampilkan SweetAlert hasil
                var iconType = response.status === 'success' ? 'success' : 'error';
                var titleText = response.status === 'success' ? 'Sukses Mendaftar!' : 'Gagal!';

                Swal.fire({
                    icon: iconType,
                    title: titleText,
                    text: response.message,
                    timer: 1800,
                    showConfirmButton: false
                }).then(() => {
                    // Opsional: Reset form jika sukses
                    if (response.status === 'success') {
                        form[0].reset();
                    }
                });
            },
            
            error: function(xhr, status, error) {
                // Tampilkan SweetAlert error
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Server/Jaringan',
                    text: 'Terjadi masalah saat mengirim data. Coba lagi.',
                    timer: 1800,
                    showConfirmButton: false
                });
            },
            
            complete: function() {
                // Re-enable tombol submit setelah proses selesai (baik sukses atau error)
                submitButton.prop('disabled', false).text('Kirim Data');
            }
        });
    });

}

function kirimPesanEmail(){


	$('#kirim-pesan-email').on('submit', function(e) {
        // Mencegah pengiriman formulir secara default (agar Ajax yang bekerja)
        e.preventDefault(); 
        
        // Tampilkan loading SweetAlert
        Swal.fire({
            title: 'Memproses...',
            text: 'Permintaan Anda sedang dikirim. Mohon tunggu.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading()
            },
        });

        // Kumpulkan semua data dari formulir
        var formData = $(this).serialize();
        
        // URL endpoint di CI4 Anda
        var postUrl = '/support/send'; 

        $.ajax({
            type: 'POST',
            url: postUrl,
            data: formData, // Mengirim 4 variabel: customer_name, email, title, descriptions
            dataType: 'json', // Harap respons dari Controller berupa JSON
            
            success: function(response) {
                // Response harusnya datang dari 'return $this->response->setJSON($hasil);' di Controller
                
                var titleText = response.status === 'success' ? 'Berhasil!' : 'Gagal!';
                var iconType = response.status === 'success' ? 'success' : 'error';

                // Tampilkan SweetAlert hasil
                Swal.fire({
                    icon: iconType,
                    title: titleText,
                    text: response.message,
                    timer: 1800, // Timer sesuai permintaan (1.8 detik)
                    showConfirmButton: false
                }).then(() => {
                    // Opsional: Reset formulir jika berhasil
                    if (response.status === 'success') {
                        $('#kirim-pesan-email')[0].reset();
                    }
                });
            },
            
            error: function(xhr, status, error) {
                // Tampilkan SweetAlert error jika ada masalah koneksi atau server
                Swal.fire({
                    icon: 'error',
                    title: 'Kesalahan Jaringan!',
                    text: 'Tidak dapat terhubung ke server. Coba lagi nanti.',
                    timer: 1800,
                    showConfirmButton: false
                });
                console.log("AJAX Error:", status, error);
            }
        });
    });
	
}