<!-- BEGIN SIDEBAR -->
<div class="app-sidebar colored">
  <div class="sidebar-header">
    <a class="header-brand" href="<?= site_url() ?>">
      <a class="header-brand" href="<?= site_url() ?>">
        <span class="text">ITB MESUJI</span>
      </a>
    </a>
    <button type="button" class="nav-toggle"><i data-toggle="expanded" class="ik ik-toggle-right toggle-icon"></i></button>
    <button id="sidebarClose" class="nav-close"><i class="ik ik-x"></i></button>
  </div>

  <?php
  $queryProfileCheck = "SELECT * FROM student WHERE email != '' AND address != '' AND birth_date != '' AND no_hp != '' AND email = '" . $this->session->userdata('user') . "'";
  $resultQueryProfileCheck = $this->db->query($queryProfileCheck)->row();
  ?>

  <div class="sidebar-content">
    <div class="nav-container">
      <nav id="main-menu-navigation" class="navigation-main">
        <div class="nav-lavel">Menu</div>
        <div class="nav-item <?= ($this->uri->segment(2) === 'dashboard' ? 'active' : '') ?>">
          <a href=" <?= site_url('mahasiswa/dashboard') ?>"><i class="ik ik-home"></i><span>Dashboard</span></a>
        </div>
        <?php if ($resultQueryProfileCheck) : ?>
          <div class="nav-item <?= ($this->uri->segment(2) === 'planning' ? 'active' : '') ?>">
            <a href=" <?= site_url('mahasiswa/planning') ?>"><i class="ik ik-check-circle"></i><span>Perencanaan KRS</span></a>
          </div>
          <div class="nav-item has-sub <?= ($this->uri->segment(3)  === 'log' || $this->uri->segment(3)  === 'check_point' ? 'active open' : '') ?>">
            <a href=" javascript:void(0)"><i class="ik ik-layers"></i><span>Daily</span></a>
            <div class="submenu-content">
              <a href="<?= base_url('mahasiswa/daily/log') ?>" class="menu-item <?= ($this->uri->segment(3) === 'log' ? 'active' : '') ?>">Note</a>
              <a href="<?= base_url('mahasiswa/daily/check_point') ?>" class="menu-item <?= ($this->uri->segment(3) === 'check_point' ? 'active' : '') ?>">Absensi</a>
            </div>
          </div>
          <div class="nav-item <?= ($this->uri->segment(2) === 'data_pkl' ? 'active' : '') ?>">
            <a href=" <?= site_url('mahasiswa/data_pkl') ?>"><i class="ik ik-airplay"></i><span>Data KHS</span></a>
          </div>
        <?php endif ?>
      </nav>
    </div>
  </div>
</div>
<!-- END SIDEBAR -->