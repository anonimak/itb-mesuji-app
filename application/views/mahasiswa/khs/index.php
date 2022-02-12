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
        <?php unsetFlash(); ?>
        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-body">
                        <div class="dt-responsive">
                            <table id="simpletable" class="table table-hover" style="padding: 20px;">
                                <thead>
                                    <tr>
                                        <th>Semester</th>
                                        <th>Tahun Akademik</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($dataKhs as $item) : ?>
                                        <tr>
                                            <td><a href="<?= base_url('mahasiswa/khs/detail/' . encodeEncrypt($item->id)) ?>" class="btn btn-link"><?= 'Semester ' . $item->semester; ?></a></td>
                                            <td><?= $item->academic_year_name . " " . $item->academic_year_semester; ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?= base_url('pdf/khs/' . encodeEncrypt($item->id)) ?>" class="btn btn-primary"><i class="ik ik-file"></i>Cetak KHS</a>
                                                </div>
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