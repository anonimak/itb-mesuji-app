<?php
$queryProfileCheck = "SELECT * FROM student WHERE email != '' AND address != '' AND birth_date != '' AND no_hp != '' AND npm = '" . $this->session->userdata('user') . "'";
$resultQueryProfileCheck = $this->db->query($queryProfileCheck)->row();
?>
<div class="main-content">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row align-items-end">
        <div class="col-lg-8">
          <div class="page-header-title">
            <i class="ik ik-home bg-blue"></i>
            <div class="d-inline">
              <h5><?= $title ?></h5>
              <span><?= $desc ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      Hai <strong> <?= $showName['fullname'] ?>!</strong> selamat kamu sudah dapat menggunakan semua fasilitas yang ada di aplikasi, silahkan gunakan aplikasi ini dengan bijak ya.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <i class="ik ik-x"></i>
      </button>
    </div>
    <?php
    if (!$resultQueryProfileCheck) :
      echo '
      <div class="card latest-update-card">
        <div class="card-block">
        <div class="alert alert-info alert-dismissible fade show mt-5" role="alert">
          Untuk dapat mengakses semua menu, silahkan lengkapi biodata anda di menu profil yang dapat di akses di link berikut <strong> <a href="' . site_url('mahasiswa/profile') . '">' . site_url('mahasiswa/profile') . '</a></strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <i class="ik ik-x"></i>
          </button>
        </div>
        </div>
      </div>';
    endif ?>
    <div class="jumbotron">
      <h1 class="display-4">Selamat Datang!</h1>
      <p class="lead">Di Aplikasi ITB Mesuji</p>
    </div>
  </div>
</div>