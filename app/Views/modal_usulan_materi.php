
<form  id="usulanMateriForm" action="/usulan/add" method="post" enctype="multipart/form-data">
<div class="modal fade" id="usulanMateriModal" tabindex="-1" role="dialog" aria-labelledby="usulanMateriModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="usulanMateriModalLabel">Usulan Materi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="hidden_id-usulan" >

        <div class="form-group">
            <label for="judul-usulan" class="col-form-label">Judul:</label>
            <input required type="text" name="judul" class="form-control" id="judul-usulan">
        </div>


        <div class="form-group">
            <label for="prioritas-usulan" class="col-form-label">Prioritas:</label>
            <input type="text" class="form-control"  placeholder="tulis prioritas" id="prioritas">

            <select required id="prioritas-usulan" class="form-control" name="prioritas">
              <option>pilih salah satu</option>
                <option value="normal">Biasa</option>
                  <option value="medium">Menengah</option>
                  <option value="urgent">Sangat Dibutuhkan</option>
            </select>
            
        </div>

        <div class="form-group">
            <label for="deskripsi-usulan" class="col-form-label">Deskripsi:</label>
            <textarea id="deskripsi-usulan" name="deskripsi" class="form-control" placeholder="tulis disini" ></textarea>
        </div>

          <div class="form-group">
            <label for="attachment-usulan" class="col-form-label">Attachment:</label>
            <input type="file" class="form-control" data-param="attachment" id="attachment-usulan">
            <input type="hidden" name="attachment" id="attachment-name" >
             <span id="attachment-error"></span>
              <img src="/assets/img/loading.gif" id="attachment-loading" >
            <a href="#" id="delete-attachment" ></a>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
</form>