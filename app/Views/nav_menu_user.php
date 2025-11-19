<div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img id="sidebar-propic" src="/assets/img/uploads/propic/<?= $settings_user_data->propic; ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a data-bs-toggle="modal" data-bs-target="#settingsModal" href="#" id="username-link" title="<?= $username;?>" class="d-block"><?= ucfirst($nama_lengkap);?></a>
          <span id="usertype" ><?= $usertype;?></span>
        </div>
      </div>

<nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          
           <li class="nav-item">
            <a href="/homepage" class="nav-link <?= isset($menu_dashboard_active) ? $menu_dashboard_active : '' ;?>">
              <i class="nav-icon fa-thin fa-house"></i>
              <p>
               Dashboard
              </p>
            </a>
          </li>

          <?php //echo $menu_materi_open; ?>
          
         <li class="nav-item <?= isset($menu_materi_open) ? $menu_materi_open : "";?>">
            <a href="#" class="nav-link <?= isset($menu_materi_open) ? 'active' : "";?>">
              <i class="nav-icon fas fa-books"></i>
              <p>
                Materi
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/all-materi" class="nav-link <?= isset($menu_seluruh_materi_active) ? $menu_seluruh_materi_active : "";?> ">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Seluruh Materi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/materi-terpilih" class="nav-link <?= isset($menu_materi_terpilih_active) ? $menu_materi_terpilih_active : "" ;?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Materi Terpilih</p>
                </a>
              </li>
             
              <li class="nav-item">
                <a href="#" data-bs-toggle="modal" data-bs-target="#usulanMateriModal" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Usulan</p>
                </a>
              </li>
            </ul>
          </li>
         
           <li class="nav-item">
            <a href="/program-afiliasi" class="nav-link <?= isset($menu_program_afiliasi_active) ? $menu_program_afiliasi_active : "" ;?>">
     <i class="nav-icon fa-solid fa-user-group"></i>
              <p>
               Program Afiliasi
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a href="/perangkat-tautan" class="nav-link <?= isset($menu_perangkat_tautan_active) ? $menu_perangkat_tautan_active : "" ;?>">
              <i class="nav-icon fa-solid fa-square-arrow-up-right"></i>
              <p>
               Perangkat Tautan
              </p>
            </a>
          </li>

          <li class="nav-header">Saldo</li>
          <li class="nav-item">
            <a  href="/riwayat-saldo" class="nav-link <?= isset($menu_riwayat_saldo_active) ? $menu_riwayat_saldo_active : "" ;?>">
             <i class="nav-icon fa-solid fa-clock-rotate-left"></i>
              <p>
                Riwayat Saldo
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a  href="#" class="nav-link" data-bs-target="#isiSaldoModal" data-bs-toggle="modal" >
            <i class="nav-icon fa-solid fa-file-invoice-dollar"></i>
              <p>
                Isi Ulang
              </p>
            </a>
          </li>
          
         
          <li class="nav-header">Online CS</li>
          <li class="nav-item">
            <a target="_blank" title="<?= $wa_cs01_name;?>" href="<?= $wa_cs01_link; ?>" class="nav-link <?= $wa_cs01_display;?>">
             <i class="nav-icon fa-solid fa-comment"></i>
              <p>
                CS #01
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a target="_blank" title="<?= $wa_cs02_name;?>" href="<?= $wa_cs02_link; ?>" class="nav-link <?= $wa_cs02_display;?>">
                 <i class="nav-icon fa-solid fa-comment"></i>
              <p>
                CS #02
              </p>
            </a>
          </li>
         
        
        
        </ul>
      </nav>