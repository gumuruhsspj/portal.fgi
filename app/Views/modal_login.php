


<div class="modal fade" id="loginModal" tabindex="-1" aria-labelledby="loginModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            
            <form action="/verify-login" method="post">
                
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold" style="color:white;" id="loginModalLabel">
                        <i class="fas fa-lock me-2"></i> Akses Masuk Sistem
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body text-center">
                    
                    <img class="sm-logo mb-4" src="/assets/img/locked.png" alt="Ikon Terkunci" style="max-width: 80px;">
                    
                    <div class="mb-3 text-start">
                        <label for="usertypeSelect" class="form-label">Masuk Sebagai:</label>
                        <select class="form-select" id="usertypeSelect" name="usertype">
                            <option value="peserta"> Peserta</option>
                            <option value="instruktur"> Instruktur</option>
                        </select>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                        <input class="form-control" type="text" name="username" placeholder="Ketik Username" required>
                    </div>

                    <div class="input-group mb-3">
                        <span class="input-group-text"><i class="fas fa-key"></i></span>
                        <input class="form-control" type="password" name="pass" placeholder="Ketik Password" required>
                    </div>
                    
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary fw-bold">
                        <i class="fas fa-sign-in-alt me-1"></i> Login
                    </button>
                </div>
                
            </form>
            
        </div>
    </div>
</div>