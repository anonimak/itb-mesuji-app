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
      <div class="col-sm-12">
        <div class="card">
          <div class="card-header d-block">
            <h3 class="text-uppercase"><?= $title; ?></h3>
          </div>
          <div class="card-body">
            <form action="" method="POST">
              <div class="row">

                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="name">NPM</label>
                    <input type="text" class="form-control <?= form_error('npm') ? 'is-invalid' : ''; ?>" id="npm" placeholder="Masukan npm" name="npm" value="<?= set_value('npm') ? set_value('npm') : $student->npm; ?>">
                    <div class="invalid-feedback">
                      <?= form_error('npm'); ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="fullname">Nama lengkap</label>
                    <input type="text" class="form-control <?= form_error('fullname') ? 'is-invalid' : ''; ?>" id="fullname" placeholder="Masukan Nama Lengkap" name="fullname" value="<?= set_value('fullname') ? set_value('fullname') : $student->fullname;  ?>">
                    <div class="invalid-feedback">
                      <?= form_error('fullname'); ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="email">Email</label>
                    <input type="text" class="form-control <?= form_error('email') ? 'is-invalid' : ''; ?>" id="email" placeholder="Masukan Email" name="email" value="<?= set_value('email') ? set_value('email') : $student->email; ?>">
                    <div class="invalid-feedback">
                      <?= form_error('email'); ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="birthdate">Tanggal Lahir</label>
                    <input type="date" class="form-control <?= form_error('birthdate') ? 'is-invalid' : ''; ?>" id="birthdate" placeholder="Masukan Tanggal Lahir" name="birthdate" value="<?= set_value('birthdate') ? set_value('birthdate') : $student->birth_date; ?>">
                    <div class="invalid-feedback">
                      <?= form_error('birthdate'); ?>
                    </div>
                  </div>
                  <div class="form-group">
                    <label for="name">Status</label>
                    <div class="form-radio">
                      <div class="radio radio-inline">
                        <label>
                          <input type="radio" name="status" <?= $student->status === 'active' ? 'checked="checked"' : ''; ?> value="active">
                          <i class="helper"></i>Aktif
                        </label>
                      </div>
                      <div class="radio radio-inline">
                        <label>
                          <input type="radio" name="status" value="not_active" <?= $student->status === 'not_active' ? 'checked="checked"' : ''; ?>>
                          <i class="helper"></i>Tidak Aktif
                        </label>
                      </div>
                      <div class="radio radio-inline">
                        <label>
                          <input type="radio" name="status" value="graduated" <?= $student->status === 'graduated' ? 'checked="checked"' : ''; ?>>
                          <i class="helper"></i>Lulus
                        </label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-sm-6">
                  <div class="form-group">
                    <label for="address">Alamat</label>
                    <textarea id="address" name="address" class="form-control <?= form_error('address') ? 'is-invalid' : ''; ?>" rows="4" cols="50"><?= set_value('address') ? set_value('address') : $student->address; ?></textarea>
                    <div class="invalid-feedback">
                      <?= form_error('address'); ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="nohp">Nomor HP</label>
                    <input type="text" class="form-control <?= form_error('nohp') ? 'is-invalid' : ''; ?>" id="nohp" placeholder="Masukan Nomor Hp" name="nohp" value="<?= set_value('nohp') ? set_value('nohp') : $student->no_hp ?>">
                    <div class="invalid-feedback">
                      <?= form_error('nohp'); ?>
                    </div>
                  </div>

                  <div class="form-group">
                    <label for="prodi">Pilih Prodi</label>
                    <select class="get-prodi form-control <?= form_error('prodi') ? 'is-invalid' : ''; ?>" name="prodi" id="prodi" style="width: 100%">
                      <option value="<?= $student->prodi_id; ?>"><?= $student->major_name . ' - ' . $student->prodi_name; ?></option>
                    </select>
                    <div class="invalid-feedback">
                      <?= form_error('prodi'); ?>
                    </div>
                  </div>
                </div>
              </div>
              <button type="submit" class="btn btn-primary"><i class="ik ik-save"></i>Simpan</button>
              <a href="<?= base_url('admin/master/student') ?>" class="btn btn-danger"><i class="ik ik-skip-back"></i>Kembali</a>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>