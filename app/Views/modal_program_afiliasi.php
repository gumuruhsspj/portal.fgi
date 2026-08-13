<!-- modal_program_afiliasi.php -->
<form id="programAfiliasiForm" action="<?= base_url(); ?>manage/program-afiliasi/add" method="post" enctype="multipart/form-data">
  <div class="modal fade" id="programAfiliasiModal" tabindex="-1" role="dialog" aria-labelledby="programAfiliasiModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="programAfiliasiModalLabel">Program Afiliasi</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <input type="hidden" id="hidden_id-program-afiliasi" name="id">

          <div class="form-group">
            <label for="nama-program" class="col-form-label">Nama Program:</label>
            <input type="text" name="nama" class="form-control" id="nama-program" required>
          </div>

          <div class="form-group">
            <label for="deskripsi-program" class="col-form-label">Deskripsi:</label>
            <textarea class="form-control" name="deskripsi" id="deskripsi-program" rows="3"></textarea>
          </div>

          <div class="form-group">
            <label for="icon-program" class="col-form-label">Icon:</label>
            <div>
              <img src="<?= base_url(); ?>assets/img/uploads/afiliasi/question.png" id="previewIcon" style="max-height:50px;">
              <a href="#" id="delete-icon" style="display:none;">Hapus</a>
              <input type="file" name="icon" id="icon-program" accept="image/*" class="form-control-file">
            </div>
          </div>

          <!-- ===== KATEGORI & KOMISI ===== -->
          <div class="form-group">
            <label class="col-form-label">Kategori & Komisi (%):</label>
            <div id="kategoriContainer">
              <!-- baris akan ditambahkan di sini -->
            </div>
            <div class="mt-2">
              <button type="button" class="btn btn-sm btn-secondary" id="addKategoriRow">
                <i class="fas fa-plus"></i> Tambah Kategori
              </button>
              <button type="button" class="btn btn-sm btn-link" id="btnAddCategoryMedia">
                <i class="fas fa-plus-circle"></i> Buat Kategori Baru
              </button>
            </div>
          </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </div>
  </div>
</form>

<!-- Modal kecil untuk tambah kategori media -->
<div class="modal fade" id="modalAddCategoryMedia" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Tambah Kategori Media</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label>Nama Kategori</label>
          <input type="text" id="newCategoryName" class="form-control" placeholder="Nama kategori">
        </div>
        <button type="button" class="btn btn-primary btn-block" id="btnSaveCategoryMedia">Simpan</button>
      </div>
    </div>
  </div>
</div>