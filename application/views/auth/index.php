<!doctype html>
<html class="no-js" lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="x-ua-compatible" content="ie=edge">
  <title>Login | PKL - Politeknik Negeri Lampung</title>
  <meta name="description" content="">
  <meta name="keywords" content="">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link rel="icon" href="../favicon.ico" type="image/x-icon" />

  <link href="https://fonts.googleapis.com/css?family=Nunito+Sans:300,400,600,700,800" rel="stylesheet">

  <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/ionicons/dist/css/ionicons.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/icon-kit/dist/css/iconkit.min.css">
  <link rel="stylesheet" href="<?= base_url('assets/') ?>plugins/perfect-scrollbar/css/perfect-scrollbar.css">
  <link rel="stylesheet" href="<?= base_url('assets/') ?>dist/css/theme.min.css">
  <script src="<?= base_url('assets/') ?>src/js/vendor/modernizr-2.8.3.min.js"></script>
</head>

<body>
  <div class="auth-wrapper">
    <div class="container-fluid h-100">
      <div class="row flex-row h-100 bg-white">
        <div class="col-xl-8 col-lg-6 col-md-5 p-0 d-md-block d-lg-block d-sm-none d-none">
          <div class="lavalite-bg"
            style="background-image: url('https://pmb.polinela.ac.id/wp-content/uploads/2021/01/pmb-polinela-1.jpg')">
            <div class="lavalite-overlay"></div>
          </div>
        </div>
        <div class="col-xl-4 col-lg-6 col-md-7 my-auto p-0">
          <div class="authentication-form mx-auto">
            <div class="logo-centered">
              <a href=""><img src="<?= base_url('assets/') ?>img/auth/portal-pkl.jpg" alt=""></a>
            </div>
            <h3>Sign In to PKL Polinela Apps</h3>
            <?= $this->session->flashdata('error_login'); ?>
            <form action="" method="POST">
              <!-- <input type="hidden" name="<?= $this->security->get_csrf_token_name(); ?>"
                value="<?= $this->security->get_csrf_hash(); ?>"> -->
              <div class="form-group">
                <input type="text" class="form-control" placeholder="Username" name="username"
                  value="<?= set_value('username'); ?>">
                <div class="text-danger">
                  <?= form_error('username'); ?>
                  <?= $this->session->flashdata('errorusername'); ?>
                </div>
                <i class="ik ik-user"></i>
              </div>
              <div class="form-group">
                <input type="password" class="form-control" placeholder="Password" name="password"
                  value="<?= set_value('password'); ?>">
                <div class="text-danger">
                  <?= form_error('password'); ?>
                  <?= $this->session->flashdata('errorpassword'); ?>
                </div>
                <i class="ik ik-lock"></i>
              </div>
              <div class="row">
                <div class="col text-left">
                  <label class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="item_checkbox" name="item_checkbox"
                      value="option1">
                    <span class="custom-control-label">&nbsp;Remember Me</span>
                  </label>
                </div>
                <div class="col text-right">
                  <a href="forgot-password.html">Forgot Password ?</a>
                </div>
              </div>
              <div class="sign-btn text-center">
                <button class="btn btn-theme" type="submit">Sign In</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
  <script>
  window.jQuery || document.write(
    '<script src="<?= base_url('assets/') ?>src/js/vendor/jquery-3.3.1.min.js"><\/script>')
  </script>
  <script src="<?= base_url('assets/') ?>plugins/popper.js/dist/umd/popper.min.js"></script>
  <script src="<?= base_url('assets/') ?>plugins/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="<?= base_url('assets/') ?>plugins/perfect-scrollbar/dist/perfect-scrollbar.min.js"></script>
  <script src="<?= base_url('assets/') ?>plugins/screenfull/dist/screenfull.js"></script>
  <script src="<?= base_url('assets/') ?>dist/js/theme.js"></script>
  <!-- Google Analytics: change UA-XXXXX-X to be your site's ID. -->
  <script>
  (function(b, o, i, l, e, r) {
    b.GoogleAnalyticsObject = l;
    b[l] || (b[l] =
      function() {
        (b[l].q = b[l].q || []).push(arguments)
      });
    b[l].l = +new Date;
    e = o.createElement(i);
    r = o.getElementsByTagName(i)[0];
    e.src = 'https://www.google-analytics.com/analytics.js';
    r.parentNode.insertBefore(e, r)
  }(window, document, 'script', 'ga'));
  ga('create', 'UA-XXXXX-X', 'auto');
  ga('send', 'pageview');
  </script>
</body>

</html>