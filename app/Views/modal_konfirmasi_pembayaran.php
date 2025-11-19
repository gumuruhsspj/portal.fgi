<div class="modal fade" id="konfirmasiBayarModal" tabindex="-1" aria-labelledby="konfirmasiBayarModalLabel" aria-hidden="true">
<div class="modal-dialog modal-dialog-scrollable">

        <div class="modal-content">
            
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="konfirmasiBayarModalLabel">
                    <i class="fas fa-clock me-2"></i> Selesaikan Pembayaran
                </h5>
                </div>
            
            <form id="formKonfirmasi">
                <div class="modal-body text-center ">
                    
                    <input type="hidden" name="username" value="<?= session()->get('username'); ?>" >    

                    <p class="text-muted">Batas waktu pembayaran:</p>
                    <h2 class="text-danger mb-3" id="paymentTimer">01:59:59</h2>
                    
                    <hr>

                    <p>Pembayaran melalui:</p>
                    <section id="paymentNonQRIS">
                        <img id="paymentMethodIcon" src="" alt="Payment Method" style="width: 160px; height: auto;">
                        <h4 class="mb-3"><strong id="paymentMethodDisplay"></strong></h4>
                        <p>Nomor Tujuan:</p>
                        <h4 class="mb-3 text-info">
                        <strong id="destinationNumberDisplay"></strong>
                        <button type="button" class="btn btn-sm btn-outline-secondary ms-2" onclick="copyToClipboard('#destinationNumberDisplay')">
                            <i class="fas fa-copy"></i>
                        </button>
                        </h4>
                    </section>

                    <section id="paymentQRIS">
                    <img id="paymentTarget" class="img-fluid" src="/assets/img/qris-fgroupindonesia.png" alt="Payment Method" >
                    </section>
                    
                    
                    <hr>

                    <p>Total yang harus dibayar:</p>
                    <h3 class="text-success mb-4">
                        <strong id="totalNominalDisplay">Rp X.XXX.XXX</strong>
                        <input type="hidden" name="nominal" id="totalNominal" >
                    </h3>

                    <div class="form-group mb-3">
                        <label for="buktiPembayaran" class="form-label d-block text-start"><i class="fas fa-upload me-1"></i> Upload Bukti Pembayaran:</label>
                        <input class="form-control" type="file" id="buktiPembayaran" name="bukti_pembayaran" accept="image/*" required>
                    </div>
                    
                </div>
                
                <div class="modal-footer justify-content-center">
                    <button type="submit" class="btn btn-primary btn-lg" id="btnSimpanBukti">
                        <i class="fas fa-save me-1"></i> Selesai 
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>