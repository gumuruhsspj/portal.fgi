<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content nexus-card">

            <form action="/verify-login" method="post">

                <div class="modal-header border-0 text-center flex-column nexus-header">
                    <h5 class="modal-title fw-semibold" id="loginModalLabel">
                        Akses Masuk Sistem
                    </h5>
                    <p class="text-muted small mt-1">Silakan login untuk melanjutkan</p>
                </div>

                <div class="modal-body">

                    <img class="d-block mx-auto img-fluid" src="/assets/img/locked.png" alt="Locked">

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

                </div>

                <div class="modal-footer border-0 d-flex justify-content-between">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Batal
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Login
                    </button>

                </div>

            </form>

        </div>
    </div>
</div>
