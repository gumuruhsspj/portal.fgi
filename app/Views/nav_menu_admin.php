<div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img id="sidebar-propic" src="/assets/img/uploads/propic/<?= $settings_user_data->propic; ?>" class="img-circle elevation-2" alt="User Image">
        </div>
        <div class="info">
          <a data-bs-toggle="modal" data-bs-target="#settingsModal" href="#" title="<?= $username;?>" class="d-block"><?= $nama_lengkap;?></a>
           <span id="usertype" ><?= $usertype;?></span>
        </div>
      </div>

    

<nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
          <!-- Add icons to the links using the .nav-icon class
               with font-awesome or any other icon font library -->
          
           <li class="nav-item">
            <a href="/homepage" class="nav-link <?= isset($link_dashboard_active) ? 'active' : '' ;?>">
              <i class="nav-icon fas fa-book"></i>
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
              <li class="nav-item">
                <a href="/manage/user" class="nav-link <?= isset($link_management_user_active) ? 'active' : '' ;?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>User</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="/manage/materi" class="nav-link <?= isset($link_management_materi_active) ? 'active' : '' ;?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Materi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/manage/group" class="nav-link <?= isset($link_management_group_active) ? 'active' : '' ;?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Group Diskusi</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/manage/perangkat" class="nav-link <?= isset($link_management_perangkat_active) ? 'active' : '' ;?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Perangkat Tautan</p>
                </a>
              </li>
                <li class="nav-item">
                <a href="/manage/program-afiliasi" class="nav-link <?= isset($link_management_program_afiliasi_active) ? 'active' : '' ;?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Program Afiliasi</p>
                </a>
              </li>
               <li class="nav-item">
                <a href="/manage/info-afiliasi" class="nav-link <?= isset($link_management_info_afiliasi_active) ? 'active' : '' ;?>">
                  <i class="far fa-circle nav-icon"></i>
                  <p>Info Afiliasi</p>
                </a>
              </li>
            </ul>
          </li>
          <li class="nav-item">
            <a href="/customer-services" data-bs-toggle="modal" data-bs-target="#customerServicesModal" class="nav-link <?= isset($link_customer_service_active) ? 'active' : '' ;?>">
              <i class="nav-icon far fa-image"></i>
              <p>
               Customer Services
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
              <i class="nav-icon fas fa-calendar-alt"></i>
              <p>
                CS #01
              </p>
            </a>
          </li>
          <li class="nav-item">
            <a target="_blank" title="<?= $wa_cs02_name;?>" href="<?= $wa_cs02_link; ?>" class="nav-link <?= $wa_cs02_display;?>">
              <i class="nav-icon far fa-image"></i>
              <p>
                CS #02
              </p>
            </a>
          </li>
         
        
        
        </ul>
      </nav>