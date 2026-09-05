$(document).ready(function () {

    // ============================================
    // FORGOT PASSWORD FLOW
    // ============================================

    var fpEmail = '';
    var fpStep = 1; // 1=email, 2=otp, 3=reset, 4=success
    var fpTimerInterval = null;
    var fpCooldownSeconds = 0;

    // --- Buka modal dari link di login ---
    $(document).on('click', '#fp-open-modal', function (e) {
        e.preventDefault();
        $('#loginModal').modal('hide');
        setTimeout(function () {
            resetForgotPasswordModal();
            $('#forgotPasswordModal').modal('show');
        }, 300);
    });

    // --- Kembali ke login ---
    $(document).on('click', '#fp-back-to-login, #fp-goto-login', function (e) {
        e.preventDefault();
        $('#forgotPasswordModal').modal('hide');
        setTimeout(function () {
            $('#loginModal').modal('show');
        }, 300);
    });

    // --- Reset modal saat ditutup ---
    $('#forgotPasswordModal').on('hidden.bs.modal', function () {
        resetForgotPasswordModal();
        if (fpTimerInterval) {
            clearInterval(fpTimerInterval);
            fpTimerInterval = null;
        }
    });

    function resetForgotPasswordModal() {
        fpStep = 1;
        fpEmail = '';
        showStep(1);
        $('#fp-email').val('');
        $('#fp-email-error').hide();
        $('#fp-otp-error').hide();
        $('#fp-reset-error').hide();
        $('#fp-new-pass').val('');
        $('#fp-confirm-pass').val('');
        $('#fp-subtitle').text('Masukkan email untuk menerima kode OTP');
        // Reset OTP inputs
        $('.otp-input').val('').removeClass('filled error-shake pop');
        $('#otp-container .otp-input:first').focus();
        $('#fp-resend-otp').removeClass('disabled').text('Kirim ulang');
        $('#fp-timer').hide();
        if (fpTimerInterval) {
            clearInterval(fpTimerInterval);
            fpTimerInterval = null;
        }
    }

    function showStep(step) {
        fpStep = step;
        $('.step-content').removeClass('active');
        $('#fp-step-' + step).addClass('active');
        if (step === 2) {
            $('#fp-subtitle').text('Masukkan kode 7 digit yang dikirim ke email Anda');
            $('#otp-container .otp-input:first').focus();
        } else if (step === 3) {
            $('#fp-subtitle').text('Buat password baru untuk akun Anda');
            $('#fp-email-display-2').text(fpEmail);
        } else if (step === 4) {
            $('#fp-subtitle').text('Password berhasil direset!');
        } else {
            $('#fp-subtitle').text('Masukkan email untuk menerima kode OTP');
        }
    }

    // --- OTP Input: auto-focus, hanya angka, pop animation ---
    $('.otp-input').on('input', function () {
        var $this = $(this);
        var val = this.value.replace(/\D/g, '');
        this.value = val;

        if (val.length === 1) {
            $this.addClass('filled pop');
            setTimeout(function () { $this.removeClass('pop'); }, 250);
            var next = $this.closest('.otp-container').find('.otp-input[data-index="' + (parseInt($this.data('index')) + 1) + '"]');
            if (next.length) {
                next.focus();
            } else {
                // Terakhir, fokus ke tombol verify
                $('#fp-verify-otp').focus();
            }
        } else {
            $this.removeClass('filled');
        }
    });

    // --- Backspace: pindah ke input sebelumnya ---
    $('.otp-input').on('keydown', function (e) {
        if (e.key === 'Backspace' && this.value === '') {
            var prev = $(this).closest('.otp-container').find('.otp-input[data-index="' + (parseInt($(this).data('index')) - 1) + '"]');
            if (prev.length) {
                prev.focus();
                prev.val('');
                prev.removeClass('filled');
            }
            e.preventDefault();
        }
        // Arrow kiri/kanan
        if (e.key === 'ArrowLeft' || e.key === 'ArrowRight') {
            var idx = parseInt($(this).data('index'));
            var target = e.key === 'ArrowLeft' ? idx - 1 : idx + 1;
            var $target = $(this).closest('.otp-container').find('.otp-input[data-index="' + target + '"]');
            if ($target.length) {
                $target.focus();
                e.preventDefault();
            }
        }
    });

    // --- Paste OTP ---
    $('#otp-container').on('paste', function (e) {
        var clipboard = (e.originalEvent.clipboardData || window.clipboardData).getData('text');
        var digits = clipboard.replace(/\D/g, '').slice(0, 7);
        if (digits.length === 0) return;
        var inputs = $('.otp-input');
        inputs.each(function (i) {
            if (i < digits.length) {
                $(this).val(digits[i]);
                $(this).addClass('filled');
            } else {
                $(this).val('');
                $(this).removeClass('filled');
            }
        });
        e.preventDefault();
        // Fokus ke input terakhir yang terisi
        var lastIdx = Math.min(digits.length, 7) - 1;
        if (lastIdx >= 0) {
            inputs.eq(lastIdx).focus();
        }
    });

    // --- STEP 1: Kirim OTP ---
    $(document).on('click', '#fp-send-otp', function () {
        var email = $('#fp-email').val().trim();
        if (!email || !isValidEmail(email)) {
            $('#fp-email-error').text('Masukkan email yang valid.').show();
            return;
        }
        $('#fp-email-error').hide();

        var $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Mengirim...');

        let _FORGOT_PASS_URL = _MAIN_URL + "auth/forgot-password";

        $.ajax({
            url: _FORGOT_PASS_URL,
            method: 'POST',
            data: { email: email },
            dataType: 'json',
            success: function (res) {
                $btn.prop('disabled', false).html('<i class="bi bi-envelope-paper"></i> Kirim Kode OTP');
                if (res.status === 'success') {
                    fpEmail = email;
                    $('#fp-email-display').text(email);
                    showStep(2);
                    // Reset OTP inputs
                    $('.otp-input').val('').removeClass('filled error-shake');
                    $('#otp-container .otp-input:first').focus();
                    $('#fp-otp-error').hide();
                    // Timer cooldown 120 detik untuk resend
                    startResendCooldown(120);
                } else {
                    $('#fp-email-error').text(res.message || 'Terjadi kesalahan.').show();
                }
            },
            error: function () {
                $btn.prop('disabled', false).html('<i class="bi bi-envelope-paper"></i> Kirim Kode OTP');
                $('#fp-email-error').text('Gagal terhubung ke server. Coba lagi.').show();
            }
        });
    });

    function isValidEmail(email) {
        return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
    }

    function startResendCooldown(seconds) {
        fpCooldownSeconds = seconds;
        $('#fp-resend-otp').addClass('disabled').text('Kirim ulang (' + seconds + 's)');
        $('#fp-timer').show().text('Tunggu ' + seconds + ' detik');
        if (fpTimerInterval) clearInterval(fpTimerInterval);
        fpTimerInterval = setInterval(function () {
            fpCooldownSeconds--;
            if (fpCooldownSeconds <= 0) {
                clearInterval(fpTimerInterval);
                fpTimerInterval = null;
                $('#fp-resend-otp').removeClass('disabled').text('Kirim ulang');
                $('#fp-timer').hide();
            } else {
                $('#fp-resend-otp').text('Kirim ulang (' + fpCooldownSeconds + 's)');
                $('#fp-timer').text('Tunggu ' + fpCooldownSeconds + ' detik');
            }
        }, 1000);
    }

    // --- STEP 2: Verifikasi OTP ---
    $(document).on('click', '#fp-verify-otp', function () {
        var otp = getOtpValue();
        if (otp.length !== 7) {
            $('#fp-otp-error').text('Masukkan 7 digit kode OTP.').show();
            shakeOtpInputs();
            return;
        }
        $('#fp-otp-error').hide();

        var $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Memverifikasi...');

        let _VERIFY_URL = _MAIN_URL + "auth/verify-otp";

        $.ajax({
            url: _VERIFY_URL,
            method: 'POST',
            data: { email: fpEmail, otp: otp },
            dataType: 'json',
            success: function (res) {
                $btn.prop('disabled', false).html('<i class="bi bi-check2-circle"></i> Verifikasi OTP');
                if (res.status === 'success') {
                    showStep(3);
                    $('#fp-new-pass').focus();
                } else {
                    $('#fp-otp-error').text(res.message || 'Kode OTP salah.').show();
                    shakeOtpInputs();
                    // Reset OTP inputs setelah error
                    $('.otp-input').val('').removeClass('filled');
                    $('#otp-container .otp-input:first').focus();
                    // Aktifkan cooldown resend 120 detik
                    startResendCooldown(120);
                }
            },
            error: function () {
                $btn.prop('disabled', false).html('<i class="bi bi-check2-circle"></i> Verifikasi OTP');
                $('#fp-otp-error').text('Gagal terhubung ke server. Coba lagi.').show();
            }
        });
    });

    function getOtpValue() {
        var val = '';
        $('.otp-input').each(function () {
            val += this.value;
        });
        return val;
    }

    function shakeOtpInputs() {
        $('.otp-input').addClass('error-shake');
        setTimeout(function () {
            $('.otp-input').removeClass('error-shake');
        }, 500);
    }

    // --- Resend OTP ---
    $(document).on('click', '#fp-resend-otp', function (e) {
        e.preventDefault();
        if ($(this).hasClass('disabled')) return;

        // Kirim ulang OTP tanpa cooldown (kecuali ada cooldown dari failed attempt)
        // Tapi kita pakai API yang sama, nanti backend cek cooldown 2 menit
        $('#fp-otp-error').hide();
        var $btn = $(this);
        $btn.text('Mengirim...').addClass('disabled');

        let _FORGOT_PASS_URL = _MAIN_URL + "auth/forgot-password";

        $.ajax({
            url: _FORGOT_PASS_URL,
            method: 'POST',
            data: { email: fpEmail },
            dataType: 'json',
            success: function (res) {
                $btn.removeClass('disabled');
                if (res.status === 'success') {
                    $('#fp-otp-error').text('Kode baru telah dikirim ke email Anda.').css('color', '#198754').show();
                    // Reset OTP
                    $('.otp-input').val('').removeClass('filled');
                    $('#otp-container .otp-input:first').focus();
                    startResendCooldown(120);
                    // Hilangkan pesan sukses setelah 4 detik
                    setTimeout(function () {
                        $('#fp-otp-error').fadeOut();
                    }, 4000);
                } else {
                    $('#fp-otp-error').text(res.message || 'Gagal mengirim ulang.').css('color', '#dc3545').show();
                    // Jika error karena cooldown, aktifkan timer
                    if (res.message && res.message.toLowerCase().includes('tunggu')) {
                        startResendCooldown(120);
                    }
                }
                setTimeout(function () {
                    $btn.text('Kirim ulang');
                    if (!fpTimerInterval) {
                        $btn.removeClass('disabled');
                    }
                }, 1500);
            },
            error: function () {
                $btn.removeClass('disabled').text('Kirim ulang');
                $('#fp-otp-error').text('Gagal terhubung ke server.').css('color', '#dc3545').show();
            }
        });
    });

    // --- STEP 3: Reset Password ---
    $(document).on('click', '#fp-reset-pass', function () {
        var newPass = $('#fp-new-pass').val();
        var confirmPass = $('#fp-confirm-pass').val();
        $('#fp-reset-error').hide();

        if (newPass.length < 6) {
            $('#fp-reset-error').text('Password minimal 6 karakter.').show();
            return;
        }
        if (newPass !== confirmPass) {
            $('#fp-reset-error').text('Password dan konfirmasi tidak sama.').show();
            return;
        }

        var $btn = $(this);
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Mereset...');

        let _RESET_PASS_URL = _MAIN_URL + "auth/reset-password";

        $.ajax({
            url: _RESET_PASS_URL,
            method: 'POST',
            data: { email: fpEmail, new_password: newPass, confirm_password: confirmPass },
            dataType: 'json',
            success: function (res) {
                $btn.prop('disabled', false).html('<i class="bi bi-arrow-clockwise"></i> Reset Password');
                if (res.status === 'success') {
                    showStep(4);
                    // Hapus semua OTP input
                    $('.otp-input').val('');
                } else {
                    $('#fp-reset-error').text(res.message || 'Gagal mereset password.').show();
                }
            },
            error: function () {
                $btn.prop('disabled', false).html('<i class="bi bi-arrow-clockwise"></i> Reset Password');
                $('#fp-reset-error').text('Gagal terhubung ke server. Coba lagi.').show();
            }
        });
    });

    // --- Enter key support ---
    $('#fp-email').on('keypress', function (e) {
        if (e.which === 13) $('#fp-send-otp').click();
    });
    $('#fp-new-pass, #fp-confirm-pass').on('keypress', function (e) {
        if (e.which === 13) $('#fp-reset-pass').click();
    });
    // OTP: enter di input terakhir trigger verify
    $('.otp-input').on('keypress', function (e) {
        if (e.which === 13) {
            var idx = parseInt($(this).data('index'));
            if (idx === 6) {
                $('#fp-verify-otp').click();
            } else {
                var next = $(this).closest('.otp-container').find('.otp-input[data-index="' + (idx + 1) + '"]');
                if (next.length) next.focus();
            }
            e.preventDefault();
        }
    });

});