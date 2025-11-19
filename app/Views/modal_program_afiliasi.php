
<form id="programAfiliasiForm" action="/manage/program-afiliasi/add" method="post" >
<div class="modal fade" id="programAfiliasiModal" tabindex="-1" role="dialog" aria-labelledby="programAfiliasiModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="materiModalLabel">Program Afiliasi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       
        <input type="hidden" id="hidden_id-program-afiliasi" name="id" >

        <div class="form-group">
            <label for="nama-program" class="col-form-label">Nama Program:</label>
            <input type="text" name="nama" class="form-control" id="nama-program">
        </div>

         <div class="form-group">
            <label for="deskripsi-program" class="col-form-label">Deskripsi:</label>
            <textarea class="form-control" name="deskripsi" id="deskripsi-program"></textarea>
        </div>

        <div class="form-group">
            <label for="icon-program" class="col-form-label">Icon:</label>
             <img src="/assets/img/uploads/afiliasi/question.png" >
             <a href="#" id="delete-icon">Delete...</a>
             <input type="file" name="icon" id="icon-program" >
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>

