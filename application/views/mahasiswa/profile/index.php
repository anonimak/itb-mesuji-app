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
      <div class="col-12">
        <div class="card">
          <div class="card-header d-block">
            <h3 class="text-uppercase"><?= $title; ?></h3>
          </div>
          <div class="card-body">
            <form action="" method="POST">
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group has-error">
                    <label for="npm">NPM</label>
                    <input type="text" class="form-control" name="npm" value="<?= set_value('npm') ? set_value('npm') : $profile->npm; ?>" readonly>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group has-error">
                    <label for="fullname">Nama Mahasiswa</label>
                    <input type="text" class="form-control <?= form_error('fullname') ? 'is-invalid' : ''; ?>" id="pic" placeholder="Nama mahasiswa" name="fullname" value="<?= set_value('fullname') ? set_value('fullname') : $profile->fullname; ?>">
                    <div class="invalid-feedback">
                      <?= form_error('fullname'); ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group has-error">
                    <label for="prodi">Program Studi</label>
                    <input type="text" class="form-control" id="prodi" value="<?= set_value('prodi_name') ? set_value('prodi_name') : $profile->prodi_name; ?>" readonly>
                    <div class="invalid-feedback">
                      <?= form_error('prodi_name'); ?>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group has-error">
                    <label for="email">Email</label>
                    <input type="text" class="form-control <?= form_error('email') ? 'is-invalid' : ''; ?>" id="email" placeholder="Email e.g email@mail.com" name="email" value="<?= set_value('email') ? set_value('email') : $profile->email; ?>">
                    <div class="invalid-feedback">
                      <?= form_error('email'); ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6">
                  <div class="form-group has-error">
                    <label for="name">Tanggal Lahir</label>
                    <input type="date" class="form-control <?= form_error('birth_date') ? 'is-invalid' : ''; ?>" id="birth_date" name="birth_date" value="<?= set_value('birth_date') ? set_value('birth_date') : $profile->birth_date; ?>">
                    <div class="invalid-feedback">
                      <?= form_error('birth_date'); ?>
                    </div>
                  </div>
                </div>
                <div class="col-md-6">
                  <div class="form-group has-error">
                    <label for="no_hp">No Handphone</label>
                    <input type="text" class="form-control <?= form_error('no_hp') ? 'is-invalid' : ''; ?>" id="no_hp" name="no_hp" value="<?= set_value('no_hp') ? set_value('no_hp') : $profile->no_hp; ?>">
                    <div class="invalid-feedback">
                      <?= form_error('no_hp'); ?>
                    </div>
                  </div>
                </div>
              </div>
              <div class="form-group has-error">
                <label for="address">Alamat</label>
                <textarea name="address" class="form-control html-editor <?= form_error('address') ? 'is-invalid' : ''; ?>" id="address" cols="30" rows="3">
                <?= $profile->address ?>
                </textarea>
                <div class="invalid-feedback">
                  <?= form_error('address'); ?>
                </div>
              </div>
              <button type="submit" class="btn btn-primary"><i class="ik ik-save"></i>Simpan</button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>