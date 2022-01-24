<div class="col-lg-3 col-md-6 col-sm-12">
  <div class="widget">
    <div class="widget-body">
      <div class="d-flex justify-content-between align-items-center">
        <div class="state">
          <h6>Tahun Akademik</h6>
          <h2 class="text-warning">
            <?php if (@$academic) {
              echo @$academic->name;
            } else {
              echo '-';
            }
            ?>
          </h2>
        </div>
        <div class="icon">
          <i class="fa fa-calendar-alt"></i>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-lg-3 col-md-6 col-sm-12">
  <div class="widget">
    <div class="widget-body">
      <div class="d-flex justify-content-between align-items-center">
        <div class="state">
          <h6>Lokasi</h6>
          <h2 class="text-success">
            <?php
            if (@$location) {
              echo @$location->location_count;
            } else {
              echo '-';
            }
            ?>
          </h2>
        </div>
        <div class="icon">
          <i class="fa fa-map-marked-alt"></i>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-lg-3 col-md-6 col-sm-12">
  <div class="widget">
    <div class="widget-body">
      <div class="d-flex justify-content-between align-items-center">
        <div class="state">
          <h6>Pendaftar</h6>
          <h2 class="text-info">
            <?php
            if (@$registration) {
              echo @$registration->registration_count;
            } else {
              echo '-';
            } ?>
          </h2>
        </div>
        <div class="icon">
          <i class="fa fa-users-cog"></i>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="col-lg-3 col-md-6 col-sm-12">
  <div class="widget">
    <div class="widget-body">
      <div class="d-flex justify-content-between align-items-center">
        <div class="state">
          <h6>Kelulusan</h6>
          <h2 class="text-danger">
            <?php
            if (@$graduation) {
              echo @$graduation->graduation_count;
            } else {
              echo '-';
            }
            ?>
          </h2>
        </div>
        <div class="icon">
          <i class="fa fa-graduation-cap"></i>
        </div>
      </div>
    </div>
  </div>
</div>