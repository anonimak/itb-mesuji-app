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
    <div class="row">
      <div class="col-sm-6">
        <div class="card">
          <div class="card-header d-block">
            <h3 class="text-uppercase"><?= $title; ?></h3>
          </div>
          <div class="card-body">
            <form action="" method="POST">
              <div class="form-group">
                <label for="nip">NIP</label>
                <input type="text" class="form-control <?= form_error('nip') ? 'is-invalid' : ''; ?>" id="nip" placeholder="Masukan NIP" name="nip" value="<?= set_value('nip') ?>">
                <div class="invalid-feedback">
                  <?= form_error('nip'); ?>
                </div>
              </div>
              <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" class="form-control <?= form_error('name') ? 'is-invalid' : ''; ?>" id="name" placeholder="Masukan Nama" name="name" value="<?= set_value('name') ?>">
                <div class="invalid-feedback">
                  <?= form_error('name'); ?>
                </div>
              </div>
              <div class="form-group">
                <label for="email">Email</label>
                <input type="text" class="form-control <?= form_error('email') ? 'is-invalid' : ''; ?>" id="email" placeholder="Masukan Email" name="email" value="<?= set_value('email') ?>">
                <div class="invalid-feedback">
                  <?= form_error('email'); ?>
                </div>
              </div>
              <div class="form-group">
                <label for="major">Jurusan</label>
                <select class="get-major form-control <?= form_error('major') ? 'is-invalid' : ''; ?>" name="major" id="major" style="width: 100%">
                  <option></option>
                  <?php foreach ($getmajor as $major) : ?>
                    <option value="<?= $major->id . ':' . $major->prodi_id ?>"><?= $major->name . ' - ' . $major->prodi; ?></option>
                  <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">
                  <?= form_error('major'); ?>
                </div>
              </div>
              <button type="submit" class="btn btn-primary"><i class="ik ik-save"></i>Simpan</button>
              <a href="<?= base_url('admin/master/lecture') ?>" class="btn btn-danger"><i class="ik ik-skip-back"></i>Kembali</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>