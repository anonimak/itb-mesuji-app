<div class="main-content">
    <div class="container-fluid">
        <div class="page-header">
            <div class="row align-items-end">
                <div class="col-lg-8">
                    <div class="page-header-title">
                        <i class="ik ik-inbox bg-blue"></i>
                        <div class="d-inline">
                            <h5>Data KHS <?= $title; ?></h5>
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
                            <li class="breadcrumb-item">
                                <a href="<?= base_url('mahasiswa/khs'); ?>">Data KHS</a>
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
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <a href="<?= base_url('mahasiswa/khs') ?>" class="btn btn-secondary float-right ml-2">Kembali</a>
                                <?php if ($score > 0) : ?>
                                    <a href="<?= base_url('pdf/khs/' . $this->uri->segment(4)) ?>" class="btn btn-primary float-right submit-krs" target="__blank"><i class="ik ik-file"></i>Cetak KHS</a>
                                <?php endif; ?>
                            </div>
                            <div class="col-12 mt-4">
                                <div class="text-center">
                                    <h4>Kartu Hasil Study</h4>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-6 col-12">
                                <div class="row">
                                    <div class="col">
                                        Nama
                                    </div>
                                    <div class="col">
                                        : <?= $dataKhs->fullname ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        NPM
                                    </div>
                                    <div class="col">
                                        : <?= $dataKhs->npm ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        Semester
                                    </div>
                                    <div class="col">
                                        : <?= $dataKhs->semester ?>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="row">
                                    <div class="col">
                                        Program Studi
                                    </div>
                                    <div class="col">
                                        : <?= $dataKhs->prodi_name ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        Tahun Akademik
                                    </div>
                                    <div class="col">
                                        : <?= $dataKhs->academic_year_name . " " . $dataKhs->academic_year_semester ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-4">
                            <div class="col-12">
                                <table class=" table table-stripped dataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Sandi Matakuliah</th>
                                            <th>Nama Matakuliah</th>
                                            <th>Kredit</th>
                                            <th>HM</th>
                                            <th>NA</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $i = 1;
                                        foreach ($detailKhs as $item) : ?>
                                            <tr>
                                                <td><?= $i++ ?></td>
                                                <td><?= $item->code ?></td>
                                                <td><?= $item->name ?></td>
                                                <td><?= $item->sks ?></td>
                                                <td><?= $item->grade ?></td>
                                                <td><?= $item->score ?></td>
                                                <td><?= $item->description ?></td>
                                            </tr>
                                        <?php
                                        endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="table-secondary">
                                            <th>Jumlah / Total</th>
                                            <th></th>
                                            <th></th>
                                            <th><?= $dataKhs->kredit ?></th>
                                            <th></th>
                                            <th><?= $score ?></th>
                                            <th></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                            <div class="col-xl-3 col-md-3 mt-4">
                                <table class=" table table-stripped dataTable">
                                    <tbody>
                                        <tr>
                                            <th>Indeks Prestasi</th>
                                            <th><?= $dataKhs->ip ?></th>
                                        </tr>
                                        <tr>
                                            <th>Total SKS</th>
                                            <th><?= $dataKhs->total_kredit ?></th>
                                        </tr>
                                        <tr>
                                            <th>Indeks Prestasi Kumulatif</th>
                                            <th><?= $dataKhs->ipk ?></th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- modal matakuliah -->
    <div class="modal fade" id="modal-choose-course" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" style="max-width:1140px;" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Matakuliah yang tersedia</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <table id="table-course" class="table table-stripped">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Sandi Matakuliah</th>
                                <th>Nama Matakuliah</th>
                                <th>Semester</th>
                                <th>SKS</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <hr />
                    <div class="row">
                        <div class="col-12">
                            <strong>Limit kredit : <span class="attr-data-limit-credit"></span></strong>
                        </div>
                        <div class="col-12">
                            Jumlah SKS yang dipilih : <span class="attr-data-credit-basket"></span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Kembali</button>
                    <button type="button" class="btn btn-primary submit-choose-course">Tambah</button>
                </div>
            </div>
        </div>
    </div>
</div>