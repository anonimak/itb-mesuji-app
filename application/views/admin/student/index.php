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
                                    <a href="<?= base_url('admin/master/student/export'); ?>" class="btn btn-success"><i class="ik ik-plus-square"></i>Export Data</a>
                                    <a href="<?= base_url('admin/master/student/import'); ?>" class="btn btn-info"><i class="ik ik-plus-square"></i>Import Excel</a>
                                    <a href="<?= base_url('admin/master/student/add'); ?>" class="btn btn-primary"><i class="ik ik-plus-square"></i>Tambah</a>
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
                                        <th>NPM</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Jurusan</th>
                                        <th>Prodi</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($students as $student) : ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $student->npm; ?></td>
                                            <td><?= $student->fullname; ?></td>
                                            <td><?= $student->email; ?></td>
                                            <td><?= $student->major_name; ?></td>
                                            <td><?= $student->prodi_name; ?></td>
                                            <td>
                                                <?php if ($student->status === 'active') : ?>
                                                    <span class="badge badge-pill badge-success mb-1">Aktif</span>
                                                <?php elseif ($student->status === 'graduated') : ?>
                                                    <span class="badge badge-pill badge-primary mb-1">Lulus</span>
                                                <?php else : ?>
                                                    <span class="badge badge-pill badge-danger mb-1">Tidak Aktif</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?= base_url('admin/master/student/edit/' . encodeEncrypt($student->id)) ?>" class="btn btn-success"><i class="ik ik-edit"></i>Edit</a>
                                                    <button type="button" class="btn btn-danger delete-student" data-id="<?= encodeEncrypt($student->id) ?>"><i class=" ik ik-trash"></i>Hapus</button>
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