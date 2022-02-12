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
      Hai <strong> <?= $showName['fullname'] ?>!</strong> selamat kamu sudah dapat menggunakan semua fasilitas yang ada di aplikasi E-PKL, silahkan gunakan aplikasi ini dengan bijak ya.
      <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <i class="ik ik-x"></i>
      </button>
    </div>
    <div class="card latest-update-card">
      <div class="card-header">
        <h3>Informasi</h3>
        <div class="card-header-right">
          <ul class="list-unstyled card-option">
            <li><i class="ik ik-chevron-left action-toggle"></i></li>
            <li><i class="ik ik-minus minimize-card"></i></li>
            <li><i class="ik ik-x close-card"></i></li>
          </ul>
        </div>
      </div>
      <div class="card-block">
        <?php if ($resultQueryProfileCheck) : ?>
          <div class="scroll-widget">
            <div class="latest-update-box">
              <div class="row pt-20 pb-30">
                <div class="col-auto text-right update-meta pr-0">
                  <i class="b-success update-icon ring"></i>
                </div>
                <div class="col pl-5">
                  <a href="#!">
                    <h6>Buku Panduan PKL</h6>
                  </a>
                  <a href="<?= site_url('assets/uploads/guidebook/' . @$guidebook->file) ?>">
                    <button class="btn btn-success"><i class="ik ik-download-cloud"></i><span></span>Download Buku Panduan PKL</button></a>
                </div>
              </div>
            </div>
          </div>
        <?php
        else :
          echo '<div class="alert alert-info alert-dismissible fade show mt-5" role="alert">
          Untuk dapat mengakses semua menu, silahkan lengkapi biodata anda di menu profil yang dapat di akses di link berikut <strong> <a href="' . site_url('mahasiswa/profile') . '">' . site_url('mahasiswa/profile') . '</a></strong>
          <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <i class="ik ik-x"></i>
          </button>
        </div>';
        endif ?>
      </div>
    </div>
  </div>
</div>