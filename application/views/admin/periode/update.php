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
                        <!-- <?= form_error(); ?> -->
                        <form action="" method="POST">
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="form-group">
                                        <label for="title">Judul</label>
                                        <input type="text" class="form-control <?= form_error('title') ? 'is-invalid' : ''; ?>" id="title" placeholder="Masukan Judul Periode" name="title" value="<?= set_value('title') ? set_value('title') : $data->title; ?>">
                                        <div class="invalid-feedback">
                                            <?= form_error('title'); ?>
                                        </div>
                                    </div>
                                    <?php if ($this->uri->segment(4) === 'registration_period') : ?>
                                        <div class="form-group">
                                            <label for="quantity">Jumlah Mahasiswa Per Kelompok</label> <span class="text-info">(Jika tidak diisi akan, nilai akan berisi nilai default <strong>3</strong>) mahasiswa</span>
                                            <input type="text" class="form-control <?= form_error('quantity') ? 'is-invalid' : ''; ?>" id="quantity" placeholder="Masukan jumlah" name="quantity" value="<?= set_value('quantity') ? set_value('quantity') : $data->quantity ?>">
                                            <div class="invalid-feedback">
                                                <?= form_error('quantity'); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                    <div class="form-group">
                                        <label for="starttime">Tanggal Mulai</label>
                                        <input type="date" class="form-control <?= form_error('starttime') ? 'is-invalid' : ''; ?>" id="starttime" name="starttime" value="<?= set_value('starttime') ? set_value('starttime') : $data->start_time; ?>">
                                        <div class="invalid-feedback">
                                            <?= form_error('starttime'); ?>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="finishtime">Tanggal Selesai</label>
                                        <input type="date" class="form-control <?= form_error('finishtime') ? 'is-invalid' : ''; ?>" id="finishtime" name="finishtime" value="<?= set_value('finishtime') ? set_value('finishtime') : $data->finish_time; ?>">
                                        <div class="invalid-feedback">
                                            <?= form_error('finishtime'); ?>
                                        </div>
                                    </div>
                                    <?php if ($this->uri->segment(4) === 'registration_period') : ?>
                                        <div class="form-group">
                                            <label for="starttimepkl">Tanggal Mulai PKL </label>
                                            <input type="date" class="form-control <?= form_error('starttimepkl') ? 'is-invalid' : ''; ?>" id="starttimepkl" name="starttimepkl" value="<?= set_value('starttimepkl') ? set_value('starttimepkl') : $data->start_time_pkl; ?>">
                                            <div class="invalid-feedback">
                                                <?= form_error('starttimepkl'); ?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="finishtimepkl">Tanggal Selesai PKL</label>
                                            <input type="date" class="form-control <?= form_error('finishtimepkl') ? 'is-invalid' : ''; ?>" id="finishtimepkl" name="finishtimepkl" value="<?= set_value('finishtimepkl') ? set_value('finishtimepkl') : $data->finish_time_pkl; ?>">
                                            <div class="invalid-feedback">
                                                <?= form_error('finishtimepkl'); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>

                            </div>

                            <button type="submit" class="btn btn-primary"><i class="ik ik-save"></i>Simpan</button>
                            <?php if ($this->uri->segment(4) === 'registration_period') {
                                $url    = 'admin/config/registration_period';
                            } else if ($this->uri->segment(4) === 'location_period') {
                                $url    = 'admin/config/location_period';
                            } else {
                                $url    = 'admin/config/location_verification';
                            } ?>
                            <a href="<?= base_url($url) ?>" class="btn btn-danger"><i class="ik ik-skip-back"></i>Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>