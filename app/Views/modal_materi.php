
<form  id="materiForm" action="/manage/materi/add" method="post" enctype="multipart/form-data">
<div class="modal fade" id="materiModal" tabindex="-1" role="dialog" aria-labelledby="materiModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="materiModalLabel">Materi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <input type="hidden" name="id" id="hidden_id-materi" >

        <div class="form-group">
            <label for="judul-materi" class="col-form-label">Judul:</label>
            <input required type="text" name="judul" class="form-control" id="judul-materi">
        </div>

        <div class="form-group">
        <label for="icon-materi" class="col-form-label">Icon:</label>
        <input type="file" class="form-control" data-param="icon" id="icon-materi">
        <input type="hidden" name="icon" id="icon-name" >
        <span id="icon-error"></span>
        <img src="/assets/img/loading.gif" id="icon-loading" style="display: none;"> 
        <div class="icon-preview-container">
            <img src="/assets/img/uploads/question.png" id="preview-icon-materi" class="img-thumbnail">
            <a href="#" id="delete-icon-materi" class="btn btn-danger btn-sm delete-button">
                &times; </a>
        </div>

    </div>

         <div class="form-group">
            <label for="owner-materi" class="col-form-label">Owner:</label>
            <select disabled  id="owner-materi" class="form-control" name="username">
              <?php if(isset($data_user) && !empty($data_user)): ?>
              <?php foreach($data_user as $d_user): ?>
                <?php $selected = ($d_user->username == session()->get('username')) ? 'selected' : ''; ?>
                <option <?= $selected; ?> value="<?=$d_user->username; ?>"><?=$d_user->username; ?></option>
              <?php endforeach; ?>
              <?php endif; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="kategori-materi" class="col-form-label">Kategori:</label>
            <input type="text" class="form-control"  placeholder="tulis kategori" id="kategori-materi-custom">

            <select required id="kategori-materi" class="form-control" name="kategori">
              <option>pilih salah satu</option>
            </select>
            <a href="" id="save-kategori" >Save</a>
            <a href="" id="tambah-kategori" >Tambah Kategori baru</a> | 
            <a href="" id="delete-kategori" >Delete Kategori ini.</a>
        </div>

        <div class="form-group">
            <label for="deskripsi-materi" class="col-form-label">Deskripsi:</label>
            <textarea id="deskripsi-materi" name="deskripsi" class="form-control" placeholder="tulis disini" ></textarea>
        </div>

        <div class="form-group">
          <label for="attachment-materi" class="col-form-label">Attachment:</label>
          <input type="file" class="form-control" data-param="attachment" id="attachment-materi">
          <input type="hidden" name="attachment" id="attachment-name" >
          <span id="attachment-error"></span>
          <img src="/assets/img/loading.gif" id="attachment-loading" style="display: none;">

          <div class="attachment-action-container">
              <a href="#" target="_blank" id="preview-attachment-materi">Preview</a> 
              
              <a href="#" id="delete-attachment" class="btn btn-danger btn-sm delete-button">
                &times; </a>
          </div>

      </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
</form>