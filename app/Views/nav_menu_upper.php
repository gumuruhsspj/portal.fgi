<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/homepage" class="nav-link">Dashboard</a>
      </li> 
      <li class="nav-item d-none d-sm-inline-block">
        <a href="/settings" data-bs-toggle="modal" data-bs-target="#settingsModal" class="nav-link">Pengaturan Akun</a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
      

      <!-- Messages Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
          <i class="far fa-comments"></i>
          <?php if($total_data_chat>0) : ?>
          <span class="badge badge-danger navbar-badge"><?=$total_data_chat;?></span>
          <?php endif; ?>
        </a>
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right dropdown-shown">
           <?php if(isset($data_chat)) : ?>
            <?php if(!empty($data_chat)): ?>
              <?php foreach($data_chat as $data_ch): ?>

          <a href="#" class="dropdown-item">
            <!-- Message Start -->
            <div class="media">
              <img src="/assets/img/user1-128x128.jpg" alt="User Avatar" class="img-size-50 mr-3 img-circle">
              <div class="media-body">
                <h3 class="dropdown-item-title">
                  <?= $data_ch->destination_username; ?>
                  <span class="float-right text-sm text-danger"><i class="fas fa-star"></i></span>
                </h3>
                <p class="text-sm"><?= get_portion_text($data_ch->messages, 10); ?></p>
                <p class="text-sm text-muted"><i class="far fa-clock mr-1"></i> <?= calculate_time_elapsed($data_ch->date_created); ?></p>
              </div>
            </div>
            <!-- Message End -->
          </a>
        
          <div class="dropdown-divider"></div>

          <?php endforeach; ?>
          <?php endif; ?>
          <?php endif; ?>
          

          <a href="/all-messages" class="dropdown-item dropdown-footer">See All Messages</a>
        </div>
      </li>

      <!-- Notifications Dropdown Menu -->
      <li class="nav-item dropdown">
        <a class="nav-link" data-bs-toggle="dropdown" href="#">
          <i class="far fa-bell"></i>
          <?php if($total_data_notification>0) : ?>
          <span class="badge badge-warning navbar-badge"><?=$total_data_notification;?></span>
        <?php endif; ?>
        </a>
       
        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right dropdown-shown">
          <span class="dropdown-item dropdown-header"><?=$total_data_notification;?> Notifications</span>
          <?php if(isset($data_notification)) : ?>
            <?php if(!empty($data_notification)): ?>
              <?php foreach($data_notification as $data_notif): ?>

          <div class="dropdown-divider"></div>
          <a href="#" class="dropdown-item">
            <i class="fas fa-file mr-2"></i> <?= $data_notif->messages; ?>
            <span class="float-right text-muted text-sm"><?= calculate_time_elapsed($data_notif->date_created); ?></span>
          </a>
        
          <?php endforeach; ?>
          <?php endif; ?>
          <?php endif; ?>
          
          <div class="dropdown-divider"></div>
          <a href="/all-notification" class="dropdown-item dropdown-footer">See All Notifications</a>
        </div>
      </li>

      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" href="/logout" role="button">
          <i class="fas fa-power-off"></i>
        </a>
      </li>
    </ul>
  </nav>
  <?php include('modal_settings.php'); ?>