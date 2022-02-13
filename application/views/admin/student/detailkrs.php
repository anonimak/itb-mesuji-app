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
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <?php if ($krs->status === 'unverified') : ?>
                  <button type="button" class="btn btn-danger" id="verifed-krs" data-id="<?= encodeEncrypt($krs->id) ?>">Verifikasi</button>
                <?php endif; ?>
                <?php if ($krs->status === 'verified') : ?>
                  <a class="btn btn-info" href="#">KRS Telah Di Verifikasi</a>
                <?php endif; ?>
              </div>
              <div class="col-12 mt-4">
                <div class="text-center">
                  <?php if ($krs->status === 'edit' || $krs->status === 'unverified') : ?>
                    <h4>Kartu Rencana Study</h4>
                  <?php endif; ?>
                  <?php if ($krs->status === 'verified') : ?>
                    <h4>Kartu Hasil Studi</h4>
                  <?php endif; ?>
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
                    : <?= $krs->fullname; ?>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    NIM
                  </div>
                  <div class="col">
                    : <?= $krs->npm; ?>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    Jenjang Akademik
                  </div>
                  <div class="col">
                    : <?= $krs->degree; ?>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    Semester
                  </div>
                  <div class="col">
                    : <?= $krs->semester; ?>
                  </div>
                </div>
              </div>
              <div class="col-lg-6 col-12">
                <div class="row">
                  <div class="col">
                    Program Studi
                  </div>
                  <div class="col">
                    : <?= $krs->prodi; ?>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    Tahun Akademik
                  </div>
                  <div class="col">
                    : <?= $krs->ta; ?> - <?= $krs->ta_semester; ?>
                  </div>
                </div>
                <div class="row">
                  <div class="col">
                    Total kredit yang telah dicapai
                  </div>
                  <div class="col">
                    : <?= $totalKreditTercapai; ?>
                  </div>
                </div>

                <div class="row">
                  <div class="col">
                    IP Semester lalu
                  </div>
                  <div class="col">
                    : <?= $ipSebelumnya; ?>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="row mt-4">
              <div class="col-12">
                <table class="table table-stripped table-bordered">
                  <thead>
                    <tr>
                      <th>#</th>
                      <th>Sandi Matakuliah</th>
                      <th>Nama Matakuliah</th>
                      <?php if ($krs->status === 'edit' || $krs->status === 'unverified') : ?>
                        <th style="text-align:center">Semester</th>
                        <th style="text-align:center">SKS</th>
                        <!-- <th>Pengambilan ke</th> -->
                      <?php endif; ?>
                      <?php if ($krs->status === 'verified') : ?>
                        <th style="text-align:center">Kredit</th>
                        <th style="text-align:center">Huruf Mutu</th>
                        <th style="text-align:center">Skor</th>
                        <th style="text-align:center">Keterangan</th>
                        <th style="text-align:center">Aksi</th>
                      <?php endif; ?>

                    </tr>
                  </thead>
                  <tbody>
                    <?php $i = 1;
                    $totalSks = 0;
                    $totalSkor  = 0;
                    foreach ($detailKrs as $courseTaken) :
                      $totalSks += $courseTaken->sks;
                      $totalSkor  += $courseTaken->score;
                    ?>
                      <tr>
                        <td style="width: 5%; text-align:center"><?= $i++; ?></td>
                        <td style="width: 15"><?= $courseTaken->code; ?></td>
                        <td style="width: 20"><?= $courseTaken->matkul; ?></td>
                        <?php if ($krs->status === 'edit' || $krs->status === 'unverified') : ?>
                          <!-- <th>Pengambilan ke</th> -->
                          <td style="width: 10;text-align:center"><?= $courseTaken->semester; ?></td>
                          <td style="width: 10;text-align:center"><?= $courseTaken->sks; ?></td>
                          <!-- <td style="width: 15 ;">-</td> -->
                          <!-- <td style="width: 25">-</td> -->
                        <?php endif; ?>
                        <?php if ($krs->status === 'verified') : ?>
                          <td style="width: 10;text-align:center"><?= $courseTaken->sks; ?></td>
                          <td style="width: 10;text-align:center"><?= $courseTaken->grade; ?></td>
                          <td style="width: 10;text-align:center"><?= $courseTaken->score; ?></td>
                          <td style="width: 10;text-align:center"><?= $courseTaken->description; ?></td>
                          <td style="width: 10;text-align:center">Aksi</td>
                        <?php endif; ?>
                      </tr>
                    <?php endforeach; ?>
                    <?php if ($krs->status === 'edit' || $krs->status === 'unverified') : ?>
                      <tr>
                        <td colspan="4" style="text-align: right;font-weight:bold">Total SKS</td>
                        <td style="text-align:center;font-weight:bold"><?= $totalSks; ?></td>
                      </tr>
                    <?php endif; ?>

                    <?php if ($krs->status === 'verified') : ?>
                      <tr>
                        <td colspan="3" style="text-align: right;font-weight:bold">Jumlah/Total</td>
                        <td style="text-align:center;font-weight:bold"><?= $totalSks; ?></td>
                        <td style="background-color: bisque;"></td>
                        <td style="text-align:center;font-weight:bold"><?= $totalSkor; ?></td>
                        <td style="background-color: bisque;"></td>
                        <td style="background-color: bisque;"></td>
                      </tr>
                      <tr>
                        <td colspan="3" style="text-align: right;font-weight:bold">IP <br> (Total Skor/Total Kredit)</td>
                        <td colspan="5" style="font-weight:bold"><?= $totalSkor / $totalSks; ?></td>
                      </tr>
                    <?php endif; ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>