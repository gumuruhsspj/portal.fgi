<style>
    /* **Custom CSS for Smaller Login Modal** */

    /* 1. Reduce the overall size of the modal dialog */
    #loginModal .modal-dialog {
        max-width: 50vh;
        /* Primary adjustment for overall size */
        width: 90%;
        /* Ensure responsiveness on smaller screens */
    }

    /* 2. Reduce the size of the prominent image */
    #loginModal .modal-body img {
        max-width: 100px;
        /* Smaller image to save space */
        height: auto;
        margin-bottom: 1rem;
        /* Add some space below the image */
    }

    /* 3. Reduce padding/margins for a tighter layout */
    #loginModal .modal-header,
    #loginModal .modal-body,
    #loginModal .modal-footer {
        padding: 1rem;
    }

    #loginModal .modal-body .mb-3,
    #loginModal .modal-body .mb-2 {
        /* Tighter spacing between form fields */
        margin-bottom: 0.35rem !important;
    }
</style>

<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content nexus-card">

            <form action="<?= base_url('/verify-login'); ?>" method="post">

                <div class="modal-header border-0 text-center flex-column nexus-header">
                    <h5 class="modal-title fw-semibold" id="loginModalLabel">
                        Akses Masuk
                    </h5>
                    <p class="text-muted small mt-1">Silakan login untuk melanjutkan</p>
                </div>

                <div class="modal-body">

                    <img class="d-block mx-auto img-fluid" src="<?= base_url('assets/img/locked.png'); ?>" alt="Locked">

                    <div class="mb-3">
                        <label for="usertypeSelect" class="form-label small fw-medium">Masuk Sebagai</label>
                        <select class="form-select nexus-input" id="usertypeSelect" name="usertype">
                            <option value="peserta">Peserta</option>
                            <option value="instruktur">Instruktur</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label small fw-medium">Username/Email</label>
                        <input class="form-control nexus-input" type="text" name="username" placeholder="Ketik Username atau Email" required>
                    </div>

                    <div class="mb-2">
                        <label class="form-label small fw-medium">Password</label>
                        <input class="form-control nexus-input" type="password" name="pass" placeholder="Ketik Password" required>
                    </div>

                    <div class="text-end mt-1">
                        <a href="#" id="fp-open-modal" class="fp-link" style="font-size:0.8rem;">Lupa Password?</a>
                    </div>



                </div>

                <div class="modal-footer border-0 d-flex flex-column align-items-center">
                    <div class="mb-2">
                        <button type="submit" class="btn btn-primary">Login Biasa</button>
                    </div>
                    <div class="mb-2 text-muted small">Atau</div>
                    <div>
                        <a href="/auth/google" class="btn btn-danger">
                            <i class="fa-brands fa-google"></i> Pakai Gmail
                        </a>
                    </div>
                </div>

            </form>

        </div>
    </div>
</div>