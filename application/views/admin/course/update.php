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
                <input type="text" class="form-control <?= form_error('name') ? 'is-invalid' : ''; ?>" id="name" placeholder="Masukan Nama Matakuliah" name="name" value="<?= set_value('name') ? set_value('name') : $course->name; ?>">
                <div class="invalid-feedback">
                  <?= form_error('name'); ?>
                </div>
              </div>
              <div class="form-group">
                <label for="code">Kode</label>
                <input type="text" class="form-control <?= form_error('code') ? 'is-invalid' : ''; ?>" id="code" placeholder="Masukan Kode Matakuliah contoh: BG-10001" name="code" value="<?= set_value('code') ? set_value('code') : $course->code; ?>">
                <div class="invalid-feedback">
                  <?= form_error('code'); ?>
                </div>
              </div>
              <div class="form-group">
                <label for="code">SKS</label>
                <input type="number" class="form-control <?= form_error('sks') ? 'is-invalid' : ''; ?>" id="sks" placeholder="Masukan SKS Matakuliah" name="sks" value="<?= set_value('sks') ? set_value('sks') : $course->sks; ?>">
                <div class="invalid-feedback">
                  <?= form_error('sks'); ?>
                </div>
              </div>
              <div class="form-group">
                <label for="code">Semester</label>
                <input type="number" class="form-control <?= form_error('semester') ? 'is-invalid' : ''; ?>" id="semester" placeholder="Masukan semester Matakuliah" name="semester" value="<?= set_value('semester') ? set_value('semester') : $course->semester; ?>">
                <div class="invalid-feedback">
                  <?= form_error('semester'); ?>
                </div>
              </div>
              <div class="form-group">
                <label for="major">Program Studi</label>
                <select class="get-major form-control <?= form_error('prodi') ? 'is-invalid' : ''; ?>" name="prodi" id="prodi" style="width: 100%">
                  <option></option>
                  <?php foreach ($getprodi as $prodi) : ?>
                    <?php if ($course->prodi_id === $prodi->id) : ?>
                      <option value="<?= $prodi->id ?>" selected><?= $prodi->name; ?></option>
                    <?php else : ?>
                      <option value="<?= $prodi->id ?>"><?= $prodi->name; ?></option>
                    <?php endif; ?>
                  <?php endforeach; ?>
                </select>
                <div class="invalid-feedback">
                  <?= form_error('prodi'); ?>
                </div>
              </div>
              <div class="form-group">
                <label for="name">Mata Kuliah Pilihan:</label>
                <div class="form-radio">
                  <div class="radio radio-inline">
                    <label>
                      <input type="radio" name="is-option" <?= $course->is_option === '1' ? 'checked="checked"' : ''; ?> value="1">
                      <i class="helper"></i>Ya
                    </label>
                  </div>
                  <div class="radio radio-inline">
                    <label>
                      <input type="radio" name="is-option" <?= $course->is_option === '0' ? 'checked="checked"' : ''; ?> value="0">
                      <i class="helper"></i>Tidak
                    </label>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-success"><i class="ik ik-save"></i>Update Data</button>
              <a href="<?= base_url('admin/master/course') ?>" class="btn btn-danger"><i class="ik ik-skip-back"></i>Kembali</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>