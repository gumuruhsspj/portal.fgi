<form action="<?= base_url(); ?>settings/user/update" id="settingsForm" method="post">
  <div class="modal fade" id="settingsModal" tabindex="-1" role="dialog" aria-labelledby="settingsModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="settingsModalLabel">Pengaturan Akun</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">

          <input type="hidden" name="id" id="hidden-id" value="<?= $settings_user_data->id; ?>">


          <div class="form-group">
            <label for="nama_lengkap" class="col-form-label">Nama Lengkap:</label>
            <input name="nama_lengkap" type="text" class="form-control" value="<?= $settings_user_data->nama_lengkap; ?>" id="nama_lengkap">
          </div>

          <div class="form-group">
            <label for="username" class="col-form-label">Username:</label>
            <input name="username" type="text" readonly value="<?= $settings_user_data->username; ?>" class="form-control" id="username">
          </div>

          <div class="form-group">
            <label for="email" class="col-form-label">Email:</label>
            <input name="email" type="text" value="<?= $settings_user_data->email; ?>" class="form-control" id="email">
          </div>

          <div class="form-group">
            <label for="pass" class="col-form-label">Password:</label>
            <input name="pass" type="password" value="<?= $settings_user_data->pass; ?>" class="form-control" id="pass">
          </div>

          <div class="form-group">
            <label for="whatsapp" class="col-form-label">Whatsapp:</label>
            <input name="whatsapp" type="text" value="<?= $settings_user_data->whatsapp; ?>" class="form-control" id="whatsapp">
          </div>

          <div class="form-group">
            <label for="settings-propic" class="col-form-label">Profile Picture:</label>
            <input type="file" class="form-control" id="settings-propic">
            <img id="settings-propic-preview" src="<?= base_url(); ?>assets/img/uploads/propic/<?= $settings_user_data->propic; ?>">
            <a href="#" id="delete-propic" class="<?= ($settings_user_data->propic != 'empty.png') ? 'link-shown' : 'link-hide'; ?> "></a>
          </div>

        </div>
        <div class="modal-footer">
          <button style="display:none;" type="submit" class="btn btn-primary">Save</button>
        </div>
      </div>
    </div>
  </div>
</form>

<style>
  /* ============================================
   MODAL SETTINGS - STYLING
   ============================================ */

  #settingsModal .modal-content {
    border-radius: 16px;
    border: none;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
    overflow: hidden;
  }

  #settingsModal .modal-header {
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    color: #fff;
    border-bottom: none;
    padding: 18px 24px;
  }

  #settingsModal .modal-header .modal-title {
    font-weight: 600;
    font-size: 1.15rem;
    letter-spacing: 0.3px;
  }

  #settingsModal .modal-header .btn-close {
    filter: invert(1) grayscale(100%) brightness(200%);
    opacity: 0.85;
  }

  #settingsModal .modal-header .btn-close:hover {
    opacity: 1;
  }

  #settingsModal .modal-body {
    padding: 24px;
    max-height: 70vh;
    overflow-y: auto;
  }

  /* Form input styling */
  #settingsModal .form-group {
    margin-bottom: 16px;
  }

  #settingsModal .form-group .col-form-label {
    font-weight: 600;
    font-size: 0.88rem;
    color: #4a5568;
    margin-bottom: 6px;
    padding-left: 2px;
  }

  #settingsModal .form-control {
    border-radius: 10px;
    border: 1.5px solid #e2e8f0;
    padding: 10px 14px;
    font-size: 0.92rem;
    transition: all 0.2s ease;
    background-color: #f8fafc;
  }

  #settingsModal .form-control:focus {
    border-color: #4e73df;
    background-color: #fff;
    box-shadow: 0 0 0 3px rgba(78, 115, 223, 0.15);
  }

  #settingsModal .form-control[readonly] {
    background-color: #edf2f7;
    color: #718096;
    cursor: not-allowed;
  }

  /* Footer */
  #settingsModal .modal-footer {
    border-top: 1px solid #edf2f7;
    padding: 16px 24px;
    background: #f8fafc;
  }

  #settingsModal .modal-footer .btn-primary {
    border-radius: 10px;
    padding: 10px 28px;
    font-weight: 600;
    background: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
    border: none;
    transition: transform 0.15s ease, box-shadow 0.15s ease;
  }

  #settingsModal .modal-footer .btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 6px 16px rgba(78, 115, 223, 0.4);
  }

  /* ============================================
   PROFILE PICTURE SECTION - INI YANG PENTING
   ============================================ */

  /* Wrapper biar propic + tombol delete sejajar */
  #settingsModal .form-group:has(#settings-propic) {
    display: flex;
    flex-direction: column;
  }

  /* Preview propic jadi BULET */
  #settings-propic-preview {
    display: block;
    width: 110px;
    height: 110px;
    border-radius: 50%;
    /* ini yang bikin bulet */
    object-fit: cover;
    /* biar gambar nggak ketarik/gepeng */
    object-position: center;
    border: 4px solid #fff;
    box-shadow: 0 4px 14px rgba(78, 115, 223, 0.35),
      0 0 0 2px #4e73df;
    margin: 12px auto 8px auto;
    background-color: #f1f5f9;
    transition: transform 0.25s ease;
  }

  #settings-propic-preview:hover {
    transform: scale(1.05);
  }

  /* Tombol delete propic */
  #delete-propic {
    display: block;
    text-align: center;
    font-size: 0.82rem;
    font-weight: 600;
    color: #e53e3e;
    text-decoration: none;
    margin: 0 auto 4px auto;
    padding: 4px 12px;
    border-radius: 20px;
    background: rgba(229, 62, 62, 0.08);
    transition: background 0.2s ease;
    width: fit-content;
  }

  #delete-propic:hover {
    background: rgba(229, 62, 62, 0.18);
    color: #c53030;
  }

  #delete-propic.link-hide {
    display: none !important;
  }

  #delete-propic.link-shown::before {
    content: "🗑️ Hapus Foto";
  }

  /* Input file biar lebih rapi */
  #settings-propic {
    border-radius: 10px;
    padding: 8px 12px;
    font-size: 0.85rem;
    border: 1.5px dashed #cbd5e0;
    background: #f8fafc;
    cursor: pointer;
  }

  #settings-propic:hover {
    border-color: #4e73df;
    background: #eef2ff;
  }

  /* Scrollbar modal body biar cantik */
  #settingsModal .modal-body::-webkit-scrollbar {
    width: 6px;
  }

  #settingsModal .modal-body::-webkit-scrollbar-thumb {
    background: #cbd5e0;
    border-radius: 3px;
  }

  #settingsModal .modal-body::-webkit-scrollbar-thumb:hover {
    background: #a0aec0;
  }
</style>