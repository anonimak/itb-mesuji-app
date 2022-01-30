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
                <label for="name">Nama Matakuliah</label>
                <input type="text" class="form-control <?= form_error('name') ? 'is-invalid' : ''; ?>" id="name" placeholder="Masukan Nama Matakuliah" name="name" value="<?= set_value('name') ?>">
                <div class="invalid-feedback">
                  <?= form_error('name'); ?>
                </div>
              </div>
              <div class="form-group">
                <label for="code">Kode</label>
                <input type="text" class="form-control <?= form_error('code') ? 'is-invalid' : ''; ?>" id="code" placeholder="Masukan Kode Matakuliah contoh: BG-10001" name="code" value="<?= set_value('code') ?>">
                <div class="invalid-feedback">
                  <?= form_error('code'); ?>
                </div>
              </div>
              <div class="form-group">
                <label for="code">SKS</label>
                <input type="number" class="form-control <?= form_error('sks') ? 'is-invalid' : ''; ?>" id="sks" placeholder="Masukan SKS Matakuliah" name="sks" value="<?= set_value('sks') ?>">
                <div class="invalid-feedback">
                  <?= form_error('sks'); ?>
                </div>
              </div>
              <div class="form-group">
                <label for="code">Semester</label>
                <input type="number" class="form-control <?= form_error('semester') ? 'is-invalid' : ''; ?>" id="semester" placeholder="Masukan semester Matakuliah" name="semester" value="<?= set_value('semester') ?>">
                <div class="invalid-feedback">
                  <?= form_error('semester'); ?>
                </div>
              </div>
              <div class="form-group">
                <label for="prodi">Program Studi</label>
                <select class="get-program-study form-control <?= form_error('prodi') ? 'is-invalid' : ''; ?>" name="prodi" id="prodi" style="width: 100%">
                  <option></option>
                  <?php foreach ($getprodi as $prodi) : ?>
                    <option value="<?= $prodi->id ?>"><?= $prodi->name; ?></option>
                  <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">
                  <?= form_error('prodi'); ?>
                </div>
              </div>
              <button type="submit" class="btn btn-primary"><i class="ik ik-save"></i>Simpan</button>
              <a href="<?= base_url('admin/master/course') ?>" class="btn btn-danger"><i class="ik ik-skip-back"></i>Kembali</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>