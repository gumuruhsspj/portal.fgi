<style>
    /* === Modal Forgot Password === */
    #forgotPasswordModal .modal-dialog {
        max-width: 56vh;
        width: 92%;
    }

    #forgotPasswordModal .modal-header,
    #forgotPasswordModal .modal-body,
    #forgotPasswordModal .modal-footer {
        padding: 1rem 1.5rem;
    }

    #forgotPasswordModal .modal-body img {
        max-width: 80px;
        height: auto;
        margin-bottom: 0.5rem;
    }

    #forgotPasswordModal .step-content {
        display: none;
        animation: fpFadeIn 0.35s ease;
    }

    #forgotPasswordModal .step-content.active {
        display: block;
    }

    @keyframes fpFadeIn {
        0% {
            opacity: 0;
            transform: translateY(12px);
        }

        100% {
            opacity: 1;
            transform: translateY(0);
        }
    }

    /* OTP Inputs */
    .otp-container {
        display: flex;
        justify-content: center;
        gap: 8px;
        margin: 1.2rem 0;
    }

    .otp-container .otp-input {
        width: 48px;
        height: 58px;
        text-align: center;
        font-size: 26px;
        font-weight: 700;
        border: 2px solid #ced4da;
        border-radius: 10px;
        background: #f8faff;
        transition: all 0.15s ease;
        outline: none;
        font-family: 'Courier New', monospace;
        caret-color: #0d6efd;
    }

    .otp-container .otp-input:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 4px rgba(13, 110, 253, 0.2);
        transform: scale(1.04);
        background: #fff;
    }

    .otp-container .otp-input.filled {
        border-color: #198754;
        background: #eafff0;
    }

    .otp-container .otp-input.error-shake {
        animation: fpShake 0.45s ease;
        border-color: #dc3545;
        background: #fff0f0;
    }

    @keyframes fpShake {

        0%,
        100% {
            transform: translateX(0);
        }

        20% {
            transform: translateX(-8px);
        }

        40% {
            transform: translateX(8px);
        }

        60% {
            transform: translateX(-6px);
        }

        80% {
            transform: translateX(6px);
        }
    }

    .otp-container .otp-input.pop {
        animation: fpPop 0.25s ease;
    }

    @keyframes fpPop {
        0% {
            transform: scale(0.7);
        }

        60% {
            transform: scale(1.12);
        }

        100% {
            transform: scale(1);
        }
    }

    .fp-link {
        font-size: 0.85rem;
        color: #0d6efd;
        cursor: pointer;
        text-decoration: underline;
    }

    .fp-link:hover {
        color: #0a58ca;
    }

    .fp-timer {
        font-size: 0.85rem;
        color: #dc3545;
        font-weight: 600;
    }

    #fp-resend-otp.disabled {
        pointer-events: none;
        opacity: 0.5;
        color: #888;
        text-decoration: none;
    }
</style>

<div class="modal fade" id="forgotPasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header border-0 text-center flex-column">
                <h5 class="modal-title fw-semibold" id="fpModalLabel">🔐 Lupa Password</h5>
                <p class="text-muted small mt-1" id="fp-subtitle">Masukkan email untuk menerima kode OTP</p>
            </div>

            <div class="modal-body">

                <!-- STEP 1: Email -->
                <div id="fp-step-1" class="step-content active">
                    <img class="d-block mx-auto img-fluid" src="<?= base_url('assets/img/locked.png') ?>" alt="Locked">
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Alamat Email</label>
                        <input type="email" id="fp-email" class="form-control" placeholder="Masukkan email terdaftar" required>
                    </div>
                    <button id="fp-send-otp" class="btn btn-primary w-100">
                        <i class="bi bi-envelope-paper"></i> Kirim Kode OTP
                    </button>
                    <div class="text-center mt-2">
                        <a href="#" id="fp-back-to-login" class="fp-link">← Kembali ke Login</a>
                    </div>
                    <div id="fp-email-error" class="text-danger small mt-2" style="display:none;"></div>
                </div>

                <!-- STEP 2: OTP Input -->
                <div id="fp-step-2" class="step-content">
                    <p class="text-center text-muted small">Masukkan 7 digit kode yang dikirim ke <strong id="fp-email-display"></strong></p>
                    <div class="otp-container" id="otp-container">
                        <?php for ($i = 0; $i < 7; $i++): ?>
                            <input type="text" class="otp-input" maxlength="1" inputmode="numeric" pattern="[0-9]" data-index="<?= $i ?>" autocomplete="off">
                        <?php endfor; ?>
                    </div>
                    <button id="fp-verify-otp" class="btn btn-success w-100">
                        <i class="bi bi-check2-circle"></i> Verifikasi OTP
                    </button>
                    <div class="text-center mt-2">
                        <span class="small text-muted">Tidak menerima kode? </span>
                        <a href="#" id="fp-resend-otp" class="fp-link">Kirim ulang</a>
                        <span id="fp-timer" class="fp-timer" style="display:none;"></span>
                    </div>
                    <div id="fp-otp-error" class="text-danger small mt-2 text-center" style="display:none;"></div>
                </div>

                <!-- STEP 3: Reset Password -->
                <div id="fp-step-3" class="step-content">
                    <p class="text-center text-muted small">Buat password baru untuk akun <strong id="fp-email-display-2"></strong></p>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Password Baru</label>
                        <input type="password" id="fp-new-pass" class="form-control" placeholder="Minimal 6 karakter" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-medium">Konfirmasi Password</label>
                        <input type="password" id="fp-confirm-pass" class="form-control" placeholder="Ketik ulang password" required>
                    </div>
                    <button id="fp-reset-pass" class="btn btn-warning w-100 text-dark">
                        <i class="bi bi-arrow-clockwise"></i> Reset Password
                    </button>
                    <div id="fp-reset-error" class="text-danger small mt-2" style="display:none;"></div>
                </div>

                <!-- STEP 4: Success -->
                <div id="fp-step-4" class="step-content text-center">
                    <i class="bi bi-check-circle-fill text-success" style="font-size: 56px;"></i>
                    <h5 class="mt-2">✅ Password Berhasil Direset!</h5>
                    <p class="text-muted small">Silakan login dengan password baru Anda.</p>
                    <button id="fp-goto-login" class="btn btn-primary mt-2">
                        <i class="bi bi-box-arrow-in-right"></i> Kembali ke Login
                    </button>
                </div>

            </div>

            <div class="modal-footer border-0 justify-content-center">
                <span class="text-muted small">🔒 Aman &amp; Terenkripsi</span>
            </div>

        </div>
    </div>
</div>