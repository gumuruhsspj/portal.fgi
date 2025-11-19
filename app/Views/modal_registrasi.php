

<div class="modal fade" id="registerModal" tabindex="-1" aria-labelledby="registerModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="registerForm">

        <input type="hidden" id="jenis" name="jenis" value="">

        <div class="modal-header">
          <h5 class="modal-title" id="registerModalLabel">📝 Pendaftaran Cepat</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
        	<div class="mb-3">
            <label for="jenis" class="form-label">Akun Pilihan : </label>
            <b><span id="jenis-text"> </span></b>
          </div>

          <div class="mb-3">
            <label for="nama_lengkap" class="form-label">Nama Lengkap</label>
            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
          </div>
          <div class="mb-3">
            <label for="email_user" class="form-label">Alamat Email</label>
            <input type="email" class="form-control" id="email_user" name="email_user" required>
          </div>
          <div class="mb-3">
            <label for="no_wa" class="form-label">Nomor WhatsApp</label>
            <input type="tel" class="form-control" id="no_wa" name="no_wa" placeholder="Contoh: 62812xxxxxx" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary" id="submitBtn">Kirim Data</button>
        </div>
      </form>
    </div>
  </div>
</div>