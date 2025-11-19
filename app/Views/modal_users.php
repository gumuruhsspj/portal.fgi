<form id="userForm" action="/manage/users/add" method="post" enctype="multipart/form-data" >
<div class="modal fade" id="userModal" tabindex="-1" role="dialog" aria-labelledby="userModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="userModalLabel">User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       
        <input type="hidden" value="" id="hidden_id_user" name="id" >

        <div class="form-group">
            <label for="nama_lengkap" class="col-form-label">Nama Lengkap:</label>
            <input type="text" class="form-control" id="nama_lengkap_user" name="nama_lengkap">
        </div>

         <div class="form-group">
            <label for="propic_user" class="col-form-label">Profile Picture:</label>
            <input type="file" class="form-control" id="propic_user" name="propic" >
            
            <a href="#" id="delete-propic" ></a>
        </div>

        <div class="form-group">
            <label for="username" class="col-form-label">Username:</label>
            <input type="text" class="form-control" id="username_user" name="username" >
        </div>

        <div class="form-group">
            <label for="email" class="col-form-label">Email:</label>
            <input type="text" class="form-control" id="email_user" name="email">
        </div>

        <div class="form-group">
            <label for="pass" class="col-form-label">Password:</label>
            <input type="password" class="form-control" id="pass_user" name="pass" >
        </div>

        <div class="form-group">
            <label for="whatsapp" class="col-form-label">Whatsapp:</label>
            <input type="text" class="form-control" id="whatsapp_user" name="whatsapp" >
        </div>

      
        <div class="form-group">
            <label for="email_notification" class="col-form-label">Kirim Email Notification:</label>
            <input type="checkbox" id="email_notification" name="email_notification" >
        </div>

          <div class="form-group">
            <label for="whatsapp" class="col-form-label">Usertype:</label>
            <select id="usertype_user" name="usertype">
                <option value="peserta">Peserta</option>
                <option value="instruktur">Instruktur</option>
                <option value="admin">Admin</option>
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
