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
                                <div>
                                    <?php if ($this->uri->segment(3) === 'registration_period') {
                                        $url    = 'admin/config/add/registration_period';
                                    } else if ($this->uri->segment(3) === 'location_period') {
                                        $url    = 'admin/config/add/location_period';
                                    } else {
                                        $url    = 'admin/config/add/location_verification';
                                    } ?>
                                    <?php
                                    $today  = date('Y-m-d');
                                    ?>
                                    <?php if (!$activePeriode) : ?>
                                        <a href="<?= base_url($url); ?>" class="btn btn-primary"><i class="ik ik-plus-square"></i>Tambah</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="dt-responsive">
                            <table id="simpletable" class="table table-hover" style="padding: 20px;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Judul</th>
                                        <?php if ($this->uri->segment(3) === 'registration_period') : ?>
                                            <th>Jumlah Per Kelompok</th>
                                            <th>Waktu Pelaksanaan PKL</th>
                                        <?php endif; ?>
                                        <th>Periode Pendaftaran</th>
                                        <th>Tahun Akademik</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($allPeriode as $periode) : ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $periode->title; ?></td>
                                            <?php if ($periode->type === '3') : ?>
                                                <td><?= $periode->quantity; ?> Mahasiswa</td>
                                                <td><?= date('d-m-Y', strtotime($periode->start_time_pkl)) . ' - ' . date('d-m-Y', strtotime($periode->finish_time_pkl)); ?></td>
                                            <?php endif; ?>
                                            <td><?= date('d-m-Y', strtotime($periode->start_time)) . ' - ' . date('d-m-Y', strtotime($periode->finish_time)); ?></td>
                                            <td><?= $periode->academic; ?></td>
                                            <td>
                                                <?php if ($this->uri->segment(3) === 'registration_period') {
                                                    $url    = 'admin/config/edit/registration_period/' . encodeEncrypt($periode->id);
                                                } else if ($this->uri->segment(3) === 'location_period') {
                                                    $url    = 'admin/config/edit/location_period/' . encodeEncrypt($periode->id);
                                                } else {
                                                    $url    = 'admin/config/edit/location_verification/' . encodeEncrypt($periode->id);
                                                } ?>

                                                <a href="<?= base_url($url); ?>" class="btn btn-success">Edit</a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>