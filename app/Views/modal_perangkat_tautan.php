
<form id="perangkatTautanForm" action="/manage/perangkat-tautan/add" method="post" >
<div class="modal fade" id="perangkatTautanModal" tabindex="-1" role="dialog" aria-labelledby="perangkatTautanModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="materiModalLabel">Perangkat Tautan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       
        <input type="hidden" id="hidden_id-perangkat-tautan" name="id" >

        <div class="form-group">
            <label for="nama-perangkat" class="col-form-label">Nama Perangkat:</label>
            <input type="text" required name="nama" placeholder="isilah dengan namanya" class="form-control" id="nama-perangkat">
        </div>

         <div class="form-group">
            <label for="deskripsi-perangkat" class="col-form-label">Deskripsi:</label>
            <textarea class="form-control" placeholder="diisi disini" name="deskripsi" id="deskripsi-perangkat"></textarea>
        </div>

         <div class="form-group">
            <label for="url-perangkat" class="col-form-label">URL:</label>
            <input type="text" name="url" placeholder="https://alamat-link" class="form-control" id="url-perangkat">
        </div>

           <div class="form-group">
            <label for="nama-materi" class="col-form-label">Nama Materi:</label>
            <div id="nama-materi-perangkat">
            </div>
           
             <a href="#" id="browse-materi" data-username="<?= $username; ?>" data-bs-toggle="modal" data-bs-target="#browseMateriModal">Browse...</a>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
</form>