<form id="groupDiskusiForm" action="/manage/group-diskusi/add" method="post">
<div class="modal fade" id="groupDiskusiModal" tabindex="-1" role="dialog" aria-labelledby="groupDiskusiModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="materiModalLabel">Group Diskusi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       
        <input id="hidden_id-group-diskusi" name="id" type="hidden" >

        <div class="form-group">
            <label for="nama-group" class="col-form-label">Nama Group:</label>
            <input type="text" required placeholder="tuliskan namanya" name="nama" class="form-control" id="nama-group">
        </div>

         <div class="form-group">
            <label for="url-group" class="col-form-label">URL:</label>
            <input type="text" required name="url" placeholder="https://alamat-link" class="form-control" id="url-group">
        </div>

         <div class="form-group">
            <label for="admin-group" class="col-form-label">Admin Group:</label>
            <select required id="admin-group" class="form-control" name="username">
              <?php if(isset($data_user) && !empty($data_user)): ?>
              <?php foreach($data_user as $d_user): ?>
                <option value="<?=$d_user->username; ?>"><?=$d_user->username; ?></option>
              <?php endforeach; ?>
              <?php endif; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="jenis-group" class="col-form-label">Jenis:</label>
            <select id="jenis-group" name="jenis">
              <option>pilih salah satu</option>
              <option value="telegram" >Telegram</option>
              <option value="wa" >Whatsapp</option>
            </select>
        </div>
      
      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
</form>