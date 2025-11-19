<div class="modal fade" id="isiSaldoModal" tabindex="-1" aria-labelledby="isiSaldoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="isiSaldoModalLabel">
                    <i class="fas fa-wallet me-2"></i> Isi Ulang Saldo
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="formIsiSaldo">
                <div class="modal-body">
                    
                    <div class="alert alert-info text-center shadow-sm">
                        Saldo Anda saat ini: <br>
                        <h4 class="mb-0"><strong><i class="fas fa-money-bill-wave me-1"></i> <span id="currentSaldoDisplay">Rp. 1.250.000</span></strong></h4>
                    </div>
                    
                    <h6 class="mt-4 mb-3 text-success"><i class="fas fa-hand-holding-dollar me-1"></i> Pilih Nominal:</h6>
                    
                    <div class="row row-cols-2 g-2 mb-3" id="nominal-button-group">
                        <div class="col">
                            <button type="button" class="btn btn-outline-primary w-100 btn-lg btn-nominal" data-nominal="25000" data-is-custom="false">
                                <i class="fas fa-money-bill me-2"></i> Rp 25.000
                            </button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-outline-warning w-100 btn-lg btn-nominal" data-nominal="50000" data-is-custom="false">
                                <i class="fas fa-sack-dollar me-2"></i> Rp 50.000
                            </button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-outline-danger w-100 btn-lg btn-nominal" data-nominal="100000" data-is-custom="false">
                                <i class="fas fa-sack-dollar me-2"></i> Rp 100.000
                            </button>
                        </div>
                        <div class="col">
                            <button type="button" class="btn btn-outline-secondary w-100 btn-lg btn-nominal" data-nominal="0" data-is-custom="true" id="btnCustomNominal">
                                <i class="fas fa-edit me-2"></i> Custom
                            </button>
                        </div>
                    </div>
                    
                    <div id="custom-input-area" class="mt-3 p-3 border rounded-3 bg-light d-none">
                        <small class="text-danger mb-2 d-block">Minimal pengisian: Rp 100.001</small>
                        <div class="input-group">
                            <input type="text" class="form-control form-control-lg" id="inputCustomNominal" placeholder="Cth: 150000" min="100001"> 
                        </div>
                    </div>
                    
                    <h6 class="mt-4 mb-3 text-primary"><i class="fas fa-credit-card me-1"></i> Pilih Metode Pembayaran:</h6>
                    
                    <div class="mb-3">
                        <select class="form-select form-select-lg" id="metodePembayaran" required>
                            <option value="">-- Pilih Metode --</option>
                            <option value="gopay" data-icon="fab fa-google-pay">Gopay</option>
                            <option value="shopeepay" data-icon="fas fa-shopping-bag">Shopeepay</option>
                            <option value="bank" data-icon="fas fa-bank">Bank Transfer</option>
                            <option value="qris" data-icon="fas fa-qrcode">QRIS</option>
                        </select>
                    </div>
                    
                  
                    
                    <input type="hidden" name="nominal" id="finalNominalInput">
                    
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-success" id="submitTopUp" disabled>
                        <i class="fas fa-paper-plane me-1"></i> Lanjutkan Pembayaran
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>