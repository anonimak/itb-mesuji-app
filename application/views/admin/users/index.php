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
                        <div class="d-flex flex-grow-1 min-width-zero card-content">
                            <div class="card-body align-self-center d-flex flex-column flex-md-row justify-content-between min-width-zero align-items-md-center">
                                <div>
                                    <h3 class="text-uppercase"><?= $title; ?></h3>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="card-body">
                        <?php if ($this->session->flashdata('success')) : ?>
                            <div class="alert bg-success alert-success text-white alert-dismissible fade show" role="alert">
                                <?= $this->session->flashdata('success'); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <i class="ik ik-x"></i>
                                </button>
                            </div>
                        <?php elseif ($this->session->flashdata('error')) : ?>
                            <div class="alert bg-danger alert-danger text-white alert-dismissible fade show" role="alert">
                                <?= $this->session->flashdata('error'); ?>
                                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                    <i class="ik ik-x"></i>
                                </button>
                            </div>
                        <?php endif; ?>
                        <div class="dt-responsive">
                            <table id="simpletable" class="table table-hover" style="padding: 20px;">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Username</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($users as $user) :
                                    ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= $user->username; ?></td>
                                            <td><?= $user->role_name; ?></td>
                                            <td>
                                                <?php if ($user->is_active === '1') : ?>
                                                    <a class="btn btn-info" href="<?= base_url('admin/master/users/update/' . $user->id . '/0:status'); ?>">Aktif</a>
                                                <?php else : ?>
                                                    <a class="btn btn-danger" href="<?= base_url('admin/master/users/update/' . $user->id . '/1:status'); ?>">Tidak Aktif</a>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('admin/master/users/update/' . $user->id . '/2:password'); ?>" class="btn btn-success mb-1">Reset password</a>
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