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
              <li class="breadcrumb-item" aria-current="page">
                <a href="<?= base_url('admin/master/student') ?>">Data Mahasiswa</a>
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
                  <h3 class="text-uppercase"><?= $title; ?> : <?= $student->fullname . ' ' . $student->npm ?></h3>
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
                    <th>Tahun Akademik</th>
                    <th>Semester</th>
                    <th>Kredit</th>
                    <th>IP</th>
                    <th>Status</th>
                    <th>Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <?php $i = 1;
                  foreach ($datakrs as $krs) : ?>
                    <tr>
                      <td><?= $i++; ?></td>
                      <td><?= $krs->ta ?></td>
                      <td><?= $krs->semester ?></td>
                      <td><?= $krs->kredit ?></td>
                      <td><?= $krs->ip ?></td>
                      <td><?= strtoupper($krs->status); ?></td>
                      <td>
                        <div class="btn-group" role="group">
                          <a href="#" class="btn btn-success"><i class="ik ik-edit"></i> Ini Tombol Aksi</a>
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