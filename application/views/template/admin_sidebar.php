<!-- BEGIN SIDEBAR -->
<div class="app-sidebar colored">
   <div class="sidebar-header">
      <a class="header-brand" href="<?= site_url() ?>">
         <span class="text">ITB MESUJI</span>
      </a>
      <button type="button" class="nav-toggle"><i data-toggle="expanded" class="ik ik-toggle-right toggle-icon"></i></button>
      <button id="sidebarClose" class="nav-close"><i class="ik ik-x"></i></button>
   </div>

   <div class="sidebar-content">
      <div class="nav-container">
         <nav id="main-menu-navigation" class="navigation-main">
            <div class="nav-lavel">Menu</div>
            <?php $uri = $this->uri->segment(2); ?>
            <div class="nav-item <?= ($uri  === 'dashboard' ? 'active' : '') ?>">
               <a href="<?= base_url('admin/dashboard') ?>"><i class="ik ik-home"></i><span>Dashboard</span></a>
            </div>
            <div class="nav-item has-sub <?= ($this->uri->segment(3)  === 'academic_year' || $this->uri->segment(4)  === 'academic_year' || $this->uri->segment(3)  === 'registration_period' || $this->uri->segment(3) === 'location_period' || $this->uri->segment(3) === 'location_verification' || $this->uri->segment(2) === 'letter' || $this->uri->segment(4) === 'location_period' || $this->uri->segment(4) === 'registration_period' || $this->uri->segment(4) === 'location_verification' || $this->uri->segment(3) === 'letter' ? 'active open' : '') ?>">
               <a href="javascript:void(0)"><i class="ik ik-settings"></i><span>Konfigurasi</span></a>
               <div class="submenu-content">
                  <a href="<?= base_url('admin/config/academic_year') ?>" class="menu-item <?= ($this->uri->segment(3)  === 'academic_year' || $this->uri->segment(4) === 'academic_year' ? 'active' : '') ?>">Tahun Akademik</a>
               </div>
            </div>
            <div class="nav-item has-sub <?= ($this->uri->segment(3)  === 'major' || $this->uri->segment(3) === 'prodi' || $this->uri->segment(3) === 'student' || $this->uri->segment(3) === 'lecture' || $this->uri->segment(3) === 'course' || $this->uri->segment(3) === 'room' || $this->uri->segment(3) === 'users' || $this->uri->segment(3) === 'head-of-program-study' ? 'active open' : '') ?>">
               <a href="javascript:void(0)"><i class="ik ik-server"></i><span>Master Data</span></a>
               <div class="submenu-content">
                  <a href="<?= base_url('admin/master/major') ?>" class="menu-item <?= ($this->uri->segment(3)  === 'major' ? 'active' : '') ?>">Jurusan</a>
                  <a href="<?= base_url('admin/master/prodi') ?>" class="menu-item <?= ($this->uri->segment(3)  === 'prodi' ? 'active' : '') ?>">Program Studi</a>
                  <a href="<?= base_url('admin/master/course') ?>" class="menu-item <?= ($this->uri->segment(3)  === 'course' ? 'active' : '') ?>">Matakuliah</a>
                  <a href="<?= base_url('admin/master/student') ?>" class="menu-item <?= ($this->uri->segment(3)  === 'student' ? 'active' : '') ?>">Mahasiswa</a>
                  <a href="<?= base_url('admin/master/lecture') ?>" class="menu-item <?= ($this->uri->segment(3)  === 'lecture' ? 'active' : '') ?>">Dosen</a>
                  <a href="<?= base_url('admin/master/head-of-program-study') ?>" class="menu-item <?= ($this->uri->segment(3)  === 'head-of-program-study' ? 'active' : '') ?>">Ketua Program Studi</a>
                  <!-- <a href="<?= base_url('admin/master/room') ?>" class="menu-item <?= ($this->uri->segment(3)  === 'room' ? 'active' : '') ?>">Daftar Ruangan</a> -->
                  <!-- <a href="<?= base_url('admin/master/users') ?>" class="menu-item <?= ($this->uri->segment(3)  === 'users' ? 'active' : '') ?>">Data User</a> -->
               </div>
            </div>

            <!-- <div class="nav-item <?= ($uri  === 'registrations' ? 'active' : '') ?>">
               <a href="<?= base_url('admin/registrations') ?>"><i class="ik ik-cast"></i><span>Nilai KHS</span></a>
            </div> -->
         </nav>
      </div>
   </div>
</div>
<!-- END SIDEBAR -->