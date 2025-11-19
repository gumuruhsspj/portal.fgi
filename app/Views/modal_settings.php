<form action="/settings/user/update" id="settingsForm" method="post" >
<div class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="settingsModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="settingsModalLabel">Pengaturan Akun</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
       
        <input type="hidden" name="id" id="hidden-id" value="<?= $settings_user_data->id ;?>" >

       
        <div class="form-group">
            <label for="nama_lengkap" class="col-form-label">Nama Lengkap:</label>
            <input  name="nama_lengkap" type="text" class="form-control" value="<?= $settings_user_data->nama_lengkap ;?>" id="nama_lengkap">
        </div>

        <div class="form-group">
            <label for="username" class="col-form-label">Username:</label>
            <input name="username" type="text" readonly value="<?= $settings_user_data->username ;?>" class="form-control" id="username">
        </div>

        <div class="form-group">
            <label for="email" class="col-form-label">Email:</label>
            <input name="email" type="text" value="<?= $settings_user_data->email ;?>" class="form-control" id="email">
        </div>

        <div class="form-group">
            <label for="pass" class="col-form-label">Password:</label>
            <input name="pass" type="password" value="<?= $settings_user_data->pass ;?>" class="form-control" id="pass">
        </div>

        <div class="form-group">
            <label for="whatsapp" class="col-form-label">Whatsapp:</label>
            <input name="whatsapp" type="text" value="<?= $settings_user_data->whatsapp ;?>" class="form-control" id="whatsapp">
        </div>

          <div class="form-group">
            <label for="settings-propic" class="col-form-label">Profile Picture:</label>
            <input type="file" class="form-control" id="settings-propic">
            <img id="settings-propic-preview" src="/assets/img/uploads/propic/<?= $settings_user_data->propic ;?>" >
            <a href="#" id="delete-propic" class="<?= ($settings_user_data->propic != 'empty.png') ? 'link-shown' : 'link-hide';?> " ></a>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary">Save</button>
      </div>
    </div>
  </div>
</div>
</form>
