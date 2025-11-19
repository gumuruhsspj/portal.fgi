

<div class="modal fade" id="paketModal" tabindex="-1" aria-labelledby="paketModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        
    <form id="paketForm">
      <div class="modal-header">
        <h5 class="modal-title" id="paketModalLabel">Paket Pembelajaran</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       
            <input type="hidden" id="materiId" name="materi_id" value="">

            <div class="mb-3">
            <label for="judulMateri" class="form-label">Materi:</label>
            <input type="text" class="form-control" id="judulMateri" name="title" readonly >
          </div>

          <div class="mb-3">
            <label class="form-label">Pilih Model Paket:</label>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="paket_belajar_sendiri" id="paket1" name="paket[]">
              <label class="form-check-label" for="paket1">
                Belajar Sendiri
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="paket_bimbingan" id="paket2" name="paket[]">
              <label class="form-check-label" for="paket2">
                Belajar + Bimbingan (Live)
              </label>
            </div>
            <div class="form-check">
              <input class="form-check-input" type="checkbox" value="paket_kasus_custom" id="paket3" name="paket[]">
              <label class="form-check-label" for="paket3">
                Belajar + Kasus Spesifik (Extra)
              </label>
            </div>
          </div>

          <div class="mb-3">
            <label for="biayaPokok" class="form-label">Biaya Pokok (Bimbingan):</label>
            <input type="text" class="form-control" value="0" id="biayaPokok" name="biaya_pokok" required >
          </div>

          <div class="mb-3">
            <label for="biayaBelajarSendiri" class="form-label">Biaya (Belajar Sendiri):</label>
            <input type="text" class="form-control" value="0" id="biayaBelajarSendiri" name="biaya_belajar_sendiri" required >
          </div>

          <div class="mb-3">
            <label for="biayaKasusCustom" class="form-label">Biaya  (Kasus Custom):</label>
            <input type="text" class="form-control" value="0" id="biayaKasusCustom" name="biaya_kasus_custom" required >
          </div>

          <div class="mb-3">
            <label class="form-label">Rilis Sertifikat?</label>
            <div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="rilis_sertifikat" id="sertifikatYes" value="yes" required>
                <label class="form-check-label" for="sertifikatYes">Yes</label>
              </div>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="radio" name="rilis_sertifikat" id="sertifikatNo" value="no">
                <label class="form-check-label" for="sertifikatNo">No</label>
              </div>
            </div>
          </div>
          
          <div class="alert alert-info" role="alert">
            Pajak: 10% dari Biaya Pokok 
            <div id="pajakDisplay">Pajak (10%): Rp <span id="nilaiPajak"> </span></div>
            
          </div>
          
      
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
        <button type="submit" class="btn btn-primary" id="submitUpdate">Simpan Perubahan</button>
      </div>

      </form>
    </div>
  </div>
</div>