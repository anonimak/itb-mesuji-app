<!-- BEGIN HEADER -->
<header class="header-top" header-theme="light">
  <div class="container-fluid">
    <div class="d-flex justify-content-between">
      <div class="top-menu d-flex align-items-center">
        <button type="button" class="btn-icon mobile-nav-toggle d-lg-none"><span></span></button>
        <div class="header-search">
          <div class="input-group">
            <span class="input-group-addon search-close"><i class="ik ik-x"></i></span>
            <input type="text" class="form-control">
            <span class="input-group-addon search-btn"><i class="ik ik-search"></i></span>
          </div>
        </div>
        <button type="button" id="navbar-fullscreen" class="nav-link"><i class="ik ik-maximize"></i></button>
      </div>
      <div class="top-menu d-flex align-items-center">
        <div class="dropdown">
          <a class="dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><img class="avatar" src="<?= base_url('assets/') ?>img/user.jpg" alt=""></a>
          <div class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
            <?php if ($this->session->userdata('role') === 'Mahasiswa') : ?>
              <a class="dropdown-item" href="<?= site_url('mahasiswa/profile') ?>"><i class="ik ik-user dropdown-icon"></i> Profile</a>
            <?php endif ?>
            <a class="dropdown-item" href="<?= site_url('auth/logout') ?>"><i class="ik ik-power dropdown-icon"></i>
              Logout</a>
          </div>
        </div>

      </div>
    </div>
  </div>
</header>

<!-- BEGIN CONTAINER -->
<div class="page-wrap">