const _URL_TOPUP_SALDO = "/saldo/topup";

$(document).ready(function() {

    $('#metodePembayaran').on('change', function() {
        // Panggil kembali fungsi setFinalNominal untuk memeriksa apakah tombol submit harus diaktifkan
        setFinalNominal($('#finalNominalInput').val()); 
    });

    // ----------------------------------------------------
    // IN-5: HANDLE SUBMIT MODAL 2 (SIMPAN BUKTI)
    // ----------------------------------------------------
    $('#formKonfirmasi').on('submit', function(e) {
        e.preventDefault();
        
        // Ambil data bukti pembayaran (File upload butuh FormData)
        const fileInput = $('#buktiPembayaran')[0];
        
        if (fileInput.files.length === 0) {
            Swal.fire('Perhatian', 'Mohon upload bukti pembayaran.', 'warning');
            return;
        }

        let formData = new FormData(this);
        
        $.ajax({
            url: _URL_TOPUP_SALDO,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                 
                $('#konfirmasiBayarModal').modal('hide');
        
                // ganti swall pake timer
               Swal.fire({
                title: 'Sukses!',
                text : 'Menunggu verifikasi pembayaran anda.',
                icon : 'success',
                timer: 2000
               });
        
               setTimeout(function(){
                    location.reload();
               },2500);

            },
            error: function() {
                 Swal.fire('Gagal!', 'Terjadi kesalahan saat mengupload.', 'error');
            }
        });
        
    });

    // ----------------------------------------------------
    // IN-6: Bersihkan Timer Saat Modal 2 Ditutup Secara Eksternal
    // ----------------------------------------------------
    $('#konfirmasiBayarModal').on('hidden.bs.modal', function () {
        if (timerInterval) {
            clearInterval(timerInterval);
        }
    });

    
// A. Event Klik Tombol Nominal (Termasuk Custom)
$('.btn-nominal').on('click', function() {
    
    // 1. Hapus status 'active' dari SEMUA tombol nominal
    $('#nominal-button-group .btn-nominal').removeClass('active');
    
    // 2. Tambahkan status 'active' pada tombol yang diklik
    $(this).addClass('active');

    const isCustom = $(this).data('is-custom');

    if (isCustom === true) {
        // Jika tombol Custom diklik
        $('#custom-input-area').removeClass('d-none');
        // Kosongkan dan set nominal awal 0 (disabled submit)
        $('#inputCustomNominal').val(''); 
        setFinalNominal(0);
    } else {
        // Jika tombol nominal tetap (25, 50, 100) diklik
        $('#custom-input-area').addClass('d-none');
        const nominal = $(this).data('nominal');
        setFinalNominal(nominal);
    }
});

// B. Event Input Custom Nominal (Hanya jika custom area terlihat)
// Inisialisasi Cleave.js pada inputCustomNominal
var cleave = new Cleave('#inputCustomNominal', {
    numeral: true, // Mengaktifkan mode numerik
    numeralThousandsGroupStyle: 'thousand', // Menggunakan format ribuan
    numeralDecimalMark: ',', // Opsional: jika ingin menggunakan koma untuk desimal
    delimiter: '.', // Menggunakan titik sebagai pemisah ribuan
    prefix: 'Rp ', // Menambahkan prefix Rupiah
    noImmediatePrefix: true, // Prefix hanya muncul saat ada angka
    rawValueTrimPrefix: true // Pastikan prefix dihilangkan saat mengambil nilai murni
});

// Perubahan pada event input Anda
$('#inputCustomNominal').on('input', function() {
    
    // Ambil nilai murni (RAW VALUE) yang hanya berupa angka
    // Cleave menyimpan nilai murni dalam instance-nya.
    let valueRaw = cleave.getRawValue(); 
    
    let nominal = parseInt(valueRaw);
    
    if (nominal > 100000) {
        // Gunakan nilai RAW (angka murni) untuk set nominal final
        setFinalNominal(nominal); 
    } else {
        setFinalNominal(0);
    }
});

// C. Reset saat modal ditutup
$('#isiSaldoModal').on('hidden.bs.modal', function () {
    $('#nominal-button-group .btn-nominal').removeClass('active');
    $('#custom-input-area').addClass('d-none');
    $('#inputCustomNominal').val('');
    setFinalNominal(0); 
});

// D. Handle Submit (Contoh SweetAlert)
$('#formIsiSaldo').on('submit', function(e) {
    e.preventDefault();
    const finalNominal = $('#finalNominalInput').val();
    
    if (finalNominal > 100000 || finalNominal == 25000 || finalNominal == 50000 || finalNominal == 100000) {
         Swal.fire({
            icon: 'question',
            title: 'Konfirmasi Top Up',
            html: `Anda akan mengisi ulang saldo sebesar <strong>${asRupiah(parseInt(finalNominal))}</strong>. Lanjutkan?`,
            showCancelButton: true,
            confirmButtonText: 'Ya, Lanjutkan',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Lakukan proses AJAX ke Controller di sini
                console.log('Nominal yang akan disubmit (AJAX): ' + finalNominal);
                
                const nominal = parseInt($('#finalNominalInput').val());
                const metode = $('#metodePembayaran').val();
                
                if (nominal > 0 && metode !== '') {
                    
                    // 1. Hitung Angka Unik
                    const angkaUnik = Math.floor(Math.random() * 900) + 100; // 100 - 999
                    const totalBayar = nominal + angkaUnik;
                    
                    // 2. Isi Detail Modal 2
                    $('#paymentMethodDisplay').text(metode.toUpperCase());
                    $('#paymentMethodDisplay').hide();
                    $('#destinationNumberDisplay').text(destinationData[metode] || 'N/A');
                    $('#totalNominalDisplay').text(asRupiah(totalBayar));
                    $('#totalNominal').val(totalBayar);

                    let lokasiGambar = null;
                    if(metode.toLowerCase() === 'qris'){
                        lokasiGambar = '/assets/img/qris.png';
                        $('#paymentNonQRIS').hide();
                        $('#paymentQRIS').show();
                    }else if(metode.toLowerCase() === 'gopay'){
                        lokasiGambar = '/assets/img/gopay.png';
                        $('#paymentNonQRIS').show();
                        $('#paymentQRIS').hide();
                    }else if(metode.toLowerCase() === 'qris'){
                        lokasiGambar = '/assets/img/shopeepay.png';
                        $('#paymentNonQRIS').show();
                        $('#paymentQRIS').hide();
                    }else if(metode.toLowerCase() === 'bank'){
                        lokasiGambar = '/assets/img/bjb.png';
                        $('#paymentNonQRIS').show();
                        $('#paymentQRIS').hide();
                    }
                    // kasih logo icon berdasarkan payment method tsb
                    $('#paymentMethodIcon').attr('src', lokasiGambar);
                    
                    // 3. Mulai Timer (2 jam = 7200 detik)
                    const twoHours = 7200; 
                    startTimer(twoHours, $('#paymentTimer'));
                    
                   
                    // tampilkan swall pake timer 2 detik
                    $('#isiSaldoModal').modal('hide');

                        Swal.fire({
                            icon: 'info',
                            title: 'Silakan Tunggu',
                            text: 'Sedang diproses...',
                            timer: 2000,
                            timerProgressBar: true,
                        });
                    

                    setTimeout(function(){
                        $('#konfirmasiBayarModal').modal('show');
                    }, 3000);

        
                } 

              
            }
        });
    } else {
        Swal.fire('Peringatan', 'Mohon pilih atau masukkan nominal di atas Rp 100.000.', 'warning');
    }
});

});

function setFinalNominal(nominalValue) {
    const nominalNumber = parseInt(nominalValue);
    
    // Atur nilai yang akan disubmit
    $('#finalNominalInput').val(nominalNumber);
    
    // Aktifkan/Nonaktifkan tombol submit
    if (nominalNumber > 0) {
        $('#submitTopUp').prop('disabled', false);
    } else {
        $('#submitTopUp').prop('disabled', true);
    }
}


const destinationData = {
    'gopay': '0857-9556-9337 (a/n FGroupIndonesia)',
    'shopeepay': '0857-9556-337 (a/n Astri Lutfhiani [FGroupIndonesia])',
    'bank': '005-869-395-210-0 (BJB a/n Astri Luthfiani)',
    'qris': '-'
};



let timerInterval; // Variabel untuk menyimpan interval timer


// Fungsi Countdown Timer
function startTimer(duration, display) {
    let timer = duration, hours, minutes, seconds;
    
    // Hentikan timer sebelumnya jika ada
    if (timerInterval) clearInterval(timerInterval); 

    timerInterval = setInterval(function () {
        hours = parseInt(timer / 3600, 10);
        minutes = parseInt((timer % 3600) / 60, 10);
        seconds = parseInt(timer % 60, 10);

        hours = hours < 10 ? "0" + hours : hours;
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;

        display.text(hours + ":" + minutes + ":" + seconds);

        if (--timer < 0) {
            clearInterval(timerInterval);
            display.text("Waktu Habis!");
            // Tambahkan logika jika waktu habis (misal, batalkan transaksi)
        }
    }, 1000);
}

// Fungsi untuk menyalin teks ke clipboard (optional)
function copyToClipboard(elementSelector) {
    const text = $(elementSelector).text().trim();
    navigator.clipboard.writeText(text).then(() => {
        Swal.fire('Berhasil!', 'Nomor tujuan berhasil disalin.', 'success');
    }).catch(err => {
        Swal.fire('Gagal!', 'Gagal menyalin nomor tujuan.', 'error');
    });
}