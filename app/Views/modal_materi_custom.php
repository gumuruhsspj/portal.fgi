<form id="materiCustomForm" action="/manage/materi/custom/add" method="post" >
  <div class="modal fade" id="materiCustomModal" tabindex="-1" role="dialog" aria-labelledby="materiCustomModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="materiCustomModalLabel">Materi</h5>
          
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <!-- hidden ID untuk edit -->
          <input type="hidden" name="id" id="hidden_id-materi-custom">
          <input type="hidden" name="id_materi" value="<?= $id_materi ?? 0; ?>">

          <!-- Judul -->
          <div class="form-group">
            <label for="judul-materi-custom" class="col-form-label">Judul:</label>
            <input required type="text" name="nama_template" class="form-control" id="judul-materi-custom">
          </div>

          <!-- Deskripsi -->
          <div class="form-group">
            <label for="deskripsi-materi-custom" class="col-form-label">Deskripsi:</label>
            <textarea id="deskripsi-materi-custom" name="deskripsi" class="form-control" placeholder="tulis disini"></textarea>
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </div>
</form>