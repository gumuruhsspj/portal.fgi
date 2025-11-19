<form id="infoAfiliasiForm" action="/manage/info-afiliasi/add" method="post" >

<div class="modal fade" id="infoAfiliasiModal" tabindex="-1" role="dialog" aria-labelledby="infoAfiliasiModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="materiModalLabel">Info Afiliasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       
         <input type="hidden" id="hidden_id-info-afiliasi" name="id" >

        <div class="form-group">
            <label for="judul" class="col-form-label">Judul:</label>
            <input type="text" name="judul" id="judul" class="form-control" placeholder="judul informasi" >
        </div>

        <div class="form-group">
            <label for="berita" class="col-form-label">Berita:</label>
            <textarea name="berita" id="berita" class="form-control" ></textarea>
        </div>

         <div class="form-group">
            <label for="status" class="col-form-label">Status:</label>
            <select name="status" id="status" class="form-control" >
                <option value="enable" >Enable</option>
                <option value="disabled" >Disabled</option>
            </select>
        </div>


      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>

