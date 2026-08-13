<div class="user-panel mt-3 pb-3 mb-3 d-flex">
  <div class="image">
    <img id="sidebar-propic" src="<?= base_url() ?>assets/img/uploads/propic/<?= $settings_user_data->propic; ?>" class="img-circle elevation-2" alt="User Image">
  </div>
  <div class="info">
    <a data-bs-toggle="modal" data-bs-target="#settingsModal" href="#" title="<?= $username; ?>" class="d-block"><?= $nama_lengkap; ?></a>
    <span id="usertype" class="d-block text-muted" style="font-size: 0.8rem;"><?= ucwords($usertype); ?></span>

    <span class="d-block text-warning" style="font-size: 0.9rem; font-weight: bold;">
      <i class="fas fa-wallet mr-1"></i> Rp <?= number_format($saldo, 0, ',', '.'); ?>
    </span>


  </div>



</div>

<span class="d-block text-info text-center">
  <label id="current-date-time"> </label>
</span>



<nav class="mt-2">
  <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
    <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->

    <li class="nav-item">
      <a href="<?= base_url() ?>homepage" class="nav-link <?= isset($link_dashboard_active) ? 'active' : ''; ?>">
        <i class="nav-icon fa-thin fa-house"></i>
        <p>
          Dashboard
        </p>
      </a>
    </li>


    <li class="nav-item <?= isset($link_management_open) ? 'menu-open' : ''; ?>">
      <a href="#" class="nav-link <?= isset($link_management_open) ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-tachometer-alt"></i>
        <p>
          Management
          <i class="right fas fa-angle-left"></i>
        </p>
      </a>
      <ul class="nav nav-treeview">

        <?php if (isset($is_admin) && $is_admin === true): ?>
          <li class="nav-item">
            <a href="<?= base_url() ?>manage/user" class="nav-link <?= isset($link_management_user_active) ? 'active' : ''; ?>">
              <i class="far fa-circle nav-icon"></i>
              <p>User</p>
            </a>
          </li>
        <?php endif; ?>
        <li class="nav-item">
          <a href="<?= base_url() ?>manage/materi" class="nav-link <?= isset($link_management_materi_active) ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Materi</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url() ?>manage/pembayaran" class="nav-link <?= isset($link_management_pembayaran_active) ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Pembayaran</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url() ?>manage/group" class="nav-link <?= isset($link_management_group_active) ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Group Diskusi</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url() ?>manage/perangkat" class="nav-link <?= isset($link_management_perangkat_active) ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Perangkat Tautan</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url() ?>manage/media-promosi" class="nav-link <?= isset($link_management_media_promosi_active) ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Media Promosi</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url() ?>manage/program-afiliasi" class="nav-link <?= isset($link_management_program_afiliasi_active) ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Program Afiliasi</p>
          </a>
        </li>
        <li class="nav-item">
          <a href="<?= base_url() ?>manage/info-afiliasi" class="nav-link <?= isset($link_management_info_afiliasi_active) ? 'active' : ''; ?>">
            <i class="far fa-circle nav-icon"></i>
            <p>Info Afiliasi</p>
          </a>
        </li>
      </ul>
    </li>
    <li class="nav-item">
      <a href="<?= base_url() ?>customer-services" data-bs-toggle="modal" data-bs-target="#customerServicesModal" class="nav-link <?= isset($link_customer_service_active) ? 'active' : ''; ?>">
        <i class="nav-icon fas fa-headset"></i>
        <p>
          Customer Services
        </p>
      </a>
    </li>



    <li class="nav-header">Online CS</li>
    <li class="nav-item">
      <a target="_blank" title="<?= $wa_cs01_name; ?>" href="<?= $wa_cs01_link; ?>" class="nav-link <?= $wa_cs01_display; ?>">
        <i class="nav-icon fas fa-headset"></i>
        <p>
          CS #01
        </p>
      </a>
    </li>
    <li class="nav-item">
      <a target="_blank" title="<?= $wa_cs02_name; ?>" href="<?= $wa_cs02_link; ?>" class="nav-link <?= $wa_cs02_display; ?>">
        <i class="nav-icon fas fa-headset"></i>
        <p>
          CS #02
        </p>
      </a>
    </li>



  </ul>
</nav>