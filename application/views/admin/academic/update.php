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
                <label for="name">Tahun Akademik</label>
                <input type="text" class="form-control <?= form_error('name') ? 'is-invalid' : ''; ?>" id="name" placeholder="Tahun Akademik: example 2020/2021" name="name" value="<?= set_value('name') ? set_value('name') : $academic_row->name; ?>">
                <div class="invalid-feedback">
                  <?= form_error('name'); ?>
                </div>
              </div>
              <div class="form-group mt-4">
                <label for="name">Status</label>
                <div class="form-radio">
                  <div class="radio radio-inline">
                    <label>
                      <input type="radio" name="status" <?= ($academic_row->status === '1' ? 'checked="checked"' : ''); ?> value="1">
                      <i class="helper"></i>Aktif
                    </label>
                  </div>
                  <div class="radio radio-inline">
                    <label>
                      <input type="radio" name="status" <?= ($academic_row->status === '0' ? 'checked="checked"' : ''); ?> value="2">
                      <i class="helper"></i>Tidak Aktif
                    </label>
                  </div>
                </div>
              </div>

              <button type="submit" class="btn btn-primary"><i class="ik ik-save"></i>Simpan</button>
              <a href="<?= base_url('admin/config/academic_year') ?>" class="btn btn-danger"><i class="ik ik-skip-back"></i>Kembali</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>