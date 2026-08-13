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

        <!-- Dashboard -->
        <li class="nav-item">
            <a href="<?= base_url() ?>homepage" class="nav-link <?= isset($menu_dashboard_active) ? $menu_dashboard_active : ''; ?>">
                <i class="nav-icon fa-thin fa-house"></i>
                <p>Dashboard</p>
            </a>
        </li>



        <!-- Khusus Afiliasi -->
        <li class="nav-header">Afiliasi</li>

        <!-- Program Afiliasi -->
        <li class="nav-item">
            <a href="<?= base_url() ?>afiliasi/program-afiliasi" class="nav-link <?= isset($menu_program_afiliasi_active) ? $menu_program_afiliasi_active : ''; ?>">
                <i class="nav-icon fa-solid fa-user-group"></i>
                <p>Program Afiliasi</p>
            </a>
        </li>

        <li class="nav-item">
            <a href="<?= base_url(); ?>afiliasi/text-generator" class="nav-link <?= isset($menu_text_generator_active) ? $menu_text_generator_active : ''; ?>">
                <i class="nav-icon fas fa-robot"></i>
                <p>Text Generator</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= base_url(); ?>afiliasi/media-promosi" class="nav-link <?= isset($menu_media_promosi_active) ? $menu_media_promosi_active : ''; ?>">
                <i class="nav-icon fa-solid fa-file-invoice-dollar"></i>
                <p>Media Promosi</p>
            </a>
        </li>

        <!-- Saldo -->
        <li class="nav-header">Saldo</li>
        <li class="nav-item">
            <a href="<?= base_url() ?>saldo/riwayat-saldo" class="nav-link <?= isset($menu_riwayat_saldo_active) ? $menu_riwayat_saldo_active : ''; ?>">
                <i class="nav-icon fa-solid fa-clock-rotate-left"></i>
                <p>Riwayat Saldo</p>
            </a>
        </li>
        <li class="nav-item">
            <a href="<?= base_url() ?>saldo/rekening-bank" class="nav-link <?= isset($menu_rekening_active) ? $menu_rekening_active  : ''; ?>">
                <i class="nav-icon fa-solid fa-file-invoice-dollar"></i>
                <p>Rekening Bank</p>
            </a>
        </li>

        <!-- Online CS -->
        <li class="nav-header">Online CS</li>
        <li class="nav-item">
            <a target="_blank" title="<?= $wa_cs01_name; ?>" href="<?= $wa_cs01_link; ?>" class="nav-link <?= $wa_cs01_display; ?>">
                <i class="nav-icon fas fa-headset"></i>
                <p>CS #01</p>
            </a>
        </li>
        <li class="nav-item">
            <a target="_blank" title="<?= $wa_cs02_name; ?>" href="<?= $wa_cs02_link; ?>" class="nav-link <?= $wa_cs02_display; ?>">
                <i class="nav-icon fas fa-headset"></i>
                <p>CS #02</p>
            </a>
        </li>

    </ul>
</nav>