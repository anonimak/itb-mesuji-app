<div id="app" data-module="StudentKrsIndexModule" class="main-content">
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
        <div class="alert alert-info alert-dismissible fade show mt-5" role="alert">
            Pendaftaran perencanaan KRS dibuka hingga tanggal <strong><?= $akademik->last_register_krs ?></strong>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <span class="badge badge-info attr-data-status"></span>
                                <?php if ($currentKrs && $currentKrs->status === 'verified' || $currentKrs && $currentKrs->status === 'unverified') : ?>
                                    <a href="<?= base_url('pdf/krs'); ?>" class="btn btn-primary float-right btn-sm" target="__blank"><i class="ik ik-file"></i>Cetak KRS</a>
                                <?php endif; ?>
                                <button type="button" class="btn btn-primary float-right submit-krs d-none">Submit KRS</button>
                            </div>
                            <div class="col-12 mt-4">
                                <div class="text-center">
                                    <h4>Kartu Rencana Study</h4>
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
                                        : <span class="attr-data-fullname"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        NIM
                                    </div>
                                    <div class="col">
                                        : <span class="attr-data-npm"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        Jenjang Akademik
                                    </div>
                                    <div class="col">
                                        : <span class="attr-data-degree"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        Semester
                                    </div>
                                    <div class="col">
                                        : <span class="attr-data-semester"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-12">
                                <div class="row">
                                    <div class="col">
                                        Program Studi
                                    </div>
                                    <div class="col">
                                        : <span class="attr-data-prodi_name"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        Tahun Akademik
                                    </div>
                                    <div class="col">
                                        : <span class="attr-data-academic_year_name"></span> <span class="attr-data-academic_year_semester"></span>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        Total kredit yang telah dicapai
                                    </div>
                                    <div class="col">
                                        : <span class="attr-data-total_kredit"></span>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col">
                                        IP Semester lalu
                                    </div>
                                    <div class="col">
                                        : <span class="attr-data-ip_latest"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <hr>
                        <div class="row mt-4">
                            <div class="col-12">
                                <table id="table-course-taken" class=" table table-stripped dataTable">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Sandi Matakuliah</th>
                                            <th>Nama Matakuliah</th>
                                            <th>Semester</th>
                                            <th>SKS</th>
                                            <th>Pengambilan ke</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>

                                    </tbody>
                                </table>
                                <button id="btn-modal-course" class="btn btn-primary d-none" data-toggle="modal" data-target="#modal-choose-course">
                                    Tambah Matakuliah
                                </button>
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