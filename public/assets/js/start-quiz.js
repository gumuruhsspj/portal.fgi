const _URL_QUIZ_SUBMIT = _URL_MAIN_WEBSITE + "materi/quiz/submit";

$(document).ready(function () {

    const idMateri   = $('input[name=id_materi]').val();
    const STORAGE_KEY = 'quiz_answers_materi_' + idMateri;
    const totalQ     = $('.quiz-item').length;
    let   currentIdx = 0;

    // ===== 1. RESTORE dari localStorage =====
    restoreAnswers();

    // ===== 2. Tampilkan soal pertama =====
    showQuestion(0);
    updateProgress();
    updateTabStatus();

    // ===== 3. Auto-save tiap kali user jawab =====
    $(document).on('change', '.jawaban', function () {
        saveCurrentAnswer($(this));
    });
    $(document).on('keyup', 'textarea.jawaban', function () {
        saveCurrentAnswer($(this));
    });

    // ===== 4. Navigasi Prev / Next =====
    $('#btn-prev').on('click', function () {
        if (currentIdx > 0) showQuestion(currentIdx - 1);
    });
    $('#btn-next').on('click', function () {
        if (currentIdx < totalQ - 1) showQuestion(currentIdx + 1);
        $("#btn-prev").show();
    });

    // ===== 5. Tab click → lompat ke soal =====
    $(document).on('click', '.quiz-tab', function () {
        showQuestion(parseInt($(this).data('index')));
    });

    // ===== 6. Submit =====
    $('#btn-submit-quiz').on('click', function () {
        const $btn = $(this);

        // validasi
        const answers  = {};
        let allFilled  = true;
        const missing  = [];

        $('.quiz-item').each(function () {
            const idx   = parseInt($(this).data('index'));
            const id    = $(this).data('id');
            const jenis = $(this).data('jenis');

            let val = '';
            if (jenis === 'essay') {
                val = $.trim($(this).find('textarea.jawaban').val() || '');
            } else {
                val = $(this).find('input.jawaban:checked').val() || '';
            }

            if (val === '') { allFilled = false; missing.push(idx + 1); }
            answers[id] = val;
        });

        if (!allFilled) {
            Swal.fire({
                icon: 'warning',
                title: 'Belum lengkap',
                html: 'Soal berikut belum dijawab: <br><strong>' + missing.join(', ') + '</strong>'
            }).then(() => {
                // lompat ke soal pertama yang belum terjawab
                if (missing.length > 0) showQuestion(missing[0] - 1);
            });
            return;
        }

         $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Mengirim...');

            $.ajax({
                url: _URL_QUIZ_SUBMIT,
                type: 'POST',
                data: { id_materi: idMateri, answers: answers },
                dataType: 'json',
                success: function (res) {
                    if (res.status === 'success') {
                        // bersihkan localStorage setelah sukses
                        localStorage.removeItem(STORAGE_KEY);

                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: res.message,
                            confirmButtonText: 'Lihat Hasil'
                        }).then(() => {
                            window.location.href = res.redirect;
                        });
                    } else {
                        $btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> SUBMIT JAWABAN');
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: res.message || 'Terjadi kesalahan.'
                        }).then(() => {
                            if (res.redirect) window.location.href = res.redirect;
                        });
                    }
                },
                error: function () {
                    $btn.prop('disabled', false).html('<i class="fas fa-paper-plane"></i> SUBMIT JAWABAN');
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Gagal mengirim jawaban.' });
                }
            });

    });

    // ===== Helper: restore dari localStorage =====
    function restoreAnswers() {
        let saved = {};
        try {
            saved = JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}');
        } catch (e) { saved = {}; }

        $('.quiz-item').each(function () {
            const id    = $(this).data('id');
            const jenis = $(this).data('jenis');
            const val   = saved[id];
            if (val === undefined || val === '') return;

            if (jenis === 'essay') {
                $(this).find('textarea.jawaban').val(val);
            } else {
                $(this).find('input.jawaban[value="' + val + '"]').prop('checked', true);
            }
        });
    }

    // ===== Helper: simpan jawaban 1 soal ke localStorage =====
    function saveCurrentAnswer($field) {
        const $item = $field.closest('.quiz-item');
        const id    = $item.data('id');
        const jenis = $item.data('jenis');

        let val = '';
        if (jenis === 'essay') {
            val = $field.val();
        } else {
            val = $item.find('input.jawaban:checked').val() || '';
        }

        let saved = {};
        try { saved = JSON.parse(localStorage.getItem(STORAGE_KEY) || '{}'); } catch (e) { saved = {}; }
        saved[id] = val;
        localStorage.setItem(STORAGE_KEY, JSON.stringify(saved));

        updateProgress();
        updateTabStatus();
    }

    // ===== Helper: tampilkan 1 soal =====
    function showQuestion(idx) {
        if (idx < 0 || idx >= totalQ) return;
        currentIdx = idx;

        // sembunyikan semua, tampilkan yang aktif
        $('.quiz-item').hide();
        const $cur = $('.quiz-item[data-index="' + idx + '"]');
        $cur.fadeIn(120);

        // tab active state
        $('.quiz-tab').removeClass('active');
        $('.quiz-tab[data-index="' + idx + '"]').addClass('active');

        // tombol nav
        $('#btn-prev').prop('disabled', idx === 0);
        if (idx === totalQ - 1) {
            $('#btn-next').hide();
            $('#btn-submit-quiz').show();
        } else {
            $('#btn-next').show();
            $('#btn-submit-quiz').hide();
        }

        // scroll ke atas konten
        $('html, body').animate({ scrollTop: 0 }, 150);
    }

    // ===== Helper: update progress bar & label =====
    function updateProgress() {
        let answered = 0;
        $('.quiz-item').each(function () {
            const jenis = $(this).data('jenis');
            if (jenis === 'essay') {
                if ($.trim($(this).find('textarea.jawaban').val() || '') !== '') answered++;
            } else {
                if ($(this).find('input.jawaban:checked').length > 0) answered++;
            }
        });

        const pct = totalQ > 0 ? (answered / totalQ) * 100 : 0;
        $('#progress-bar').css('width', pct + '%');
        $('#progress-label').text(answered + ' / ' + totalQ + ' terjawab');
    }

    // ===== Helper: update warna tab =====
    function updateTabStatus() {
        $('.quiz-tab').each(function () {
            const idx = parseInt($(this).data('index'));
            const $item = $('.quiz-item[data-index="' + idx + '"]');
            const jenis = $item.data('jenis');

            let filled = false;
            if (jenis === 'essay') {
                filled = $.trim($item.find('textarea.jawaban').val() || '') !== '';
            } else {
                filled = $item.find('input.jawaban:checked').length > 0;
            }
            $(this).toggleClass('filled', filled);
        });
    }

});