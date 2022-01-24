<div class="main-content">
  <div class="container-fluid">
    <div class="page-header">
      <div class="row align-items-end">
        <div class="col-lg-8">
          <div class="page-header-title">
            <i class="ik ik-inbox bg-blue"></i>
            <div class="d-inline">
              <h5><?= $title; ?></h5>
              <span><?= $desc; ?></span>
            </div>
          </div>
        </div>
        <div class="col-lg-4">
          <nav class="breadcrumb-container" aria-label="breadcrumb">
            <ol class="breadcrumb">
              <li class="breadcrumb-item">
                <a href="<?= base_url(); ?>">Dashboard</a>
              </li>
              <li class="breadcrumb-item active" aria-current="page"><?= $title; ?></li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
    <?php if ($this->session->flashdata('success')) : ?>
      <div class="flashdata" data-flashdata=" <?= $this->session->flashdata('success') ?>" data-type="success"></div>
    <?php elseif ($this->session->flashdata('error')) : ?>
      <div class="flashdata" data-flashdata=" <?= $this->session->flashdata('error') ?>" data-type="error"></div>
    <?php endif; ?>
    <div class="row">
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header d-block">
            <div class="d-flex flex-grow-1 min-width-zero card-content">
              <div class="card-body align-self-center d-flex flex-column flex-md-row justify-content-between min-width-zero align-items-md-center">
                <div>
                  <h3 class="text-uppercase"><?= $title; ?></h3>
                </div>
              </div>
            </div>
            <div class="card-body">
              <div class="row">
                <div class="col-sm-6">
                  <form action="<?= base_url('admin/master/student/importstudent'); ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                      <label>File upload</label>
                      <input type="file" name="importstudent" accept=".xlsx,.xls" class="file-upload-default">
                      <div class="input-group col-xs-12">
                        <input type="text" class="form-control file-upload-info" disabled="" placeholder="Upload File Excel">
                        <span class="input-group-append">
                          <button class="file-upload-browse btn btn-primary" type="button">Pilih File</button>
                        </span>
                      </div>
                    </div>
                    <button type="submit" class="btn btn-primary"><i class="ik ik-plus-square"></i>Upload Data</button>
                    <a href="<?= base_url('master/admin/student') ?>" class="btn btn-danger"><i class="ik ik-skip-back"></i>Kembali</a>
                  </form>
                </div>
                <div class="col-sm-6">
                  <div class="card latest-update-card">
                    <div class="card-header">
                      <h3>Tata Cara Import</h3>
                    </div>
                    <div class="card-block">
                      <div class="scroll-widget">
                        <div class="latest-update-box">
                          <div class="row pt-20 pb-30">
                            <div class="col-auto text-right update-meta pr-0">
                              <i class="b-primary update-icon ring"></i>
                            </div>
                            <div class="col pl-5">
                              <a href="#!">
                                <h6>Pastikan Data Prodi sudah terisi</h6>
                              </a>
                              <p class="text-muted mb-0">Isilah data prodi terlebih dahulu untuk mendapatkan ID Prodi</p>
                            </div>
                          </div>
                          <div class="row pb-30">
                            <div class="col-auto text-right update-meta pr-0">
                              <i class="b-primary update-icon ring"></i>
                            </div>
                            <div class="col pl-5">
                              <a href="#!">
                                <h6>Download Format Import Excel Mahasiswa</h6>
                              </a>
                              <p class="text-muted mb-0">Download File template
                                <a href="<?= base_url('assets/uploads/template_mahasiswa.xlsx'); ?>" class="text-blue"> Disini</a>
                              </p>
                            </div>
                          </div>
                          <div class="row pb-30">
                            <div class="col-auto text-right update-meta pr-0">
                              <i class="b-success update-icon ring"></i>
                            </div>
                            <div class="col pl-5">
                              <a href="#!">
                                <h6>Isi data sesuai format</h6>
                              </a>
                              <p class="text-muted mb-0">Isilah data sesuai format template, kesalahan pengisian data akan
                                mengakibatkan data tidak bisa masuk ke database</a></p>
                            </div>
                          </div>
                          <div class="row pb-30">
                            <div class="col-auto text-right update-meta pr-0">
                              <i class="b-primary update-icon ring"></i>
                            </div>
                            <div class="col pl-5">
                              <a href="#!">
                                <h6>Klik Pilih File</h6>
                              </a>
                              <p class="text-muted mb-0">Klik tombol pilih file untuk menggunggah file yang telah di edit
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>