const _URL_GRADE_SUBMISSION = _URL_MAIN_WEBSITE + 'manage/quiz-submission/grade';

$(document).ready(function () {

    $('#btn-save-grading').on('click', function () {
        const $btn = $(this);

        // Validasi: semua essay wajib diisi skor
        let allFilled = true;
        let missing = [];

        $('.input-score').each(function () {
            let v = $.trim($(this).val());
            if (v === '') {
                allFilled = false;
                let soalNo = $(this).closest('.answer-card').find('strong').first().text().trim();
                missing.push(soalNo);
            }
        });

        if (!allFilled) {
            Swal.fire({
                icon: 'warning',
                title: 'Belum lengkap',
                html: 'Skor belum diisi untuk: <br><strong>' + missing.join(', ') + '</strong>'
            });
            return;
        }

       
         $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

            // Serialize form
            let formData = $('#form-grading').serialize();

            $.ajax({
                url: _URL_GRADE_SUBMISSION,
                type: 'POST',
                data: formData,
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                       
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            timer: 2000,               // auto close 2 detik
                            timerProgressBar: true,    // progress bar kelihatan berkurang
                            showConfirmButton: false,
                            allowOutsideClick: false,  // biar ga bisa di-skip klik luar
                            allowEscapeKey: false      // biar ga bisa di-skip pakai ESC
                        }).then(() => {
                            window.location.href = _URL_MAIN_WEBSITE + 'manage/quiz-submission';
                        });

                    } else {
                        $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Simpan Penilaian');
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: res.message || 'Terjadi kesalahan.'
                        });
                    }
                },
                error: function () {
                    $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Simpan Penilaian');
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal menyimpan penilaian.' });
                }
            });
        

    });
});