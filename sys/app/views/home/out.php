<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
// print_r($content);die('-');
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <title>JDIH Biro Hukum dan Organisasi - Badan POM RI</title>
  <meta charset="utf-8">
  <link rel="icon" type="image/png" href="<?= base_url() ?>assets/images/favicon.png">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/font.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/open-iconic-bootstrap.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/animate.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/owl.carousel.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/owl.theme.default.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/magnific-popup.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/aos.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/ionicons.min.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/bootstrap-datepicker.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/jquery.timepicker.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/flaticon.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/icomoon.css">
  <link rel="stylesheet" href="<?= base_url() ?>assets/css/style.css?v6">
  <link rel="stylesheet" href="<?= base_url(); ?>assets/css/newtable-3.0-blue.css" />
    <link href="<?= base_url() ?>assets/dflip/css/dflip.css" rel="stylesheet" type="text/css">
    <link href="<?= base_url() ?>assets/dflip/css/themify-icons.css" rel="stylesheet" type="text/css">
  <script src="<?= base_url() ?>assets/js/jquery-1.2.6.min.js"></script>
    <script src="<?= base_url() ?>assets/js/newtable.js" type="text/javascript"></script>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark ftco_navbar bg-dark ftco-navbar-light" id="ftco-navbar">
    <div class="container-fluid px-md-4 ">
      <a class="navbar-brand" href="index.html">InstaApp</a>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#ftco-nav" aria-controls="ftco-nav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="oi oi-menu"></span> Menu
      </button>
      <div class="collapse navbar-collapse" id="ftco-nav">
        <ul class="navbar-nav ml-auto">
          <li class="nav-item"><a href="<?= site_url("home") ?>" class="nav-link">Login</a></li>
        </ul>
      </div>
    </div>
  </nav>
  <div class="hero-wrap hero-wrap-2" style="background-image: url('<?= base_url() ?>assets/images/bg_1.jpg');" data-stellar-background-ratio="0.5">
  <div class="overlay"></div>
      <div class="container">
        <div class="row no-gutters slider-text align-items-end justify-content-start">
          <div class="col-md-12 ftco-animate text-center mb-5">
            <h1 class="mb-3 bread">Login</h1>
          </div>
        </div>
      </div>
    </div>

    <section class="ftco-section contact-section bg-light">
      <div class="container">
        <div class="row block-9">
          <div class="col-md-12 order-md-last d-flex">
            <form action="#" class="bg-white p-5 contact-form">
              <div class="form-group" style="width: 50%">
                <input type="text" class="form-control" placeholder="Username">
              </div>
              <div class="form-group" style="width: 50%">
                <input type="password" class="form-control" placeholder="Password">
              </div>
              <div class="form-group">
                <input type="submit" value="Login" class="btn btn-primary">
              </div>
              <div class="form-group" style="width: 50%">
                <span style="color: #666">atau, <a href="<?= site_url("register") ?>">klik di sini</a> untuk proses Registrasi Akun</span>
              </div>
            </form>
          </div>
        </div>
      </div>
    </section>
  <footer class="ftco-footer ftco-bg-dark ftco-section">
      <div class="container">
        <div class="row mb-5">
          <div class="col-md">
            <div class="ftco-footer-widget mb-4">
              <ul class="list-unstyled">
                <li><a href="#" class="pb-1 d-block">About</a></li>
                <li><a href="#" class="pb-1 d-block">API</a></li>
                <li><a href="#" class="pb-1 d-block">Hashtags</a></li>
                <li><a href="#" class="pb-1 d-block">Fitness</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4 ml-md-4">
              <ul class="list-unstyled">
                <li><a href="#" class="pb-1 d-block">Blog</a></li>
                <li><a href="#" class="pb-1 d-block">Privacy</a></li>
                <li><a href="#" class="pb-1 d-block">Locations</a></li>
                <li><a href="#" class="pb-1 d-block">Food & Drinks</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4 ml-md-4">
              <ul class="list-unstyled">
                <li><a href="#" class="pb-1 d-block">Job</a></li>
                <li><a href="#" class="pb-1 d-block">Term</a></li>
                <li><a href="#" class="pb-1 d-block">Beauty</a></li>
                <li><a href="#" class="pb-1 d-block">Music</a></li>
              </ul>
            </div>
          </div>
          <div class="col-md">
            <div class="ftco-footer-widget mb-4 ml-md-4">
              <ul class="list-unstyled">
                <li><a href="#" class="pb-1 d-block">Help</a></li>
                <li><a href="#" class="pb-1 d-block">Top Account</a></li>
                <li><a href="#" class="pb-1 d-block">Dance</a></li>
                <li><a href="#" class="pb-1 d-block">Visual Arts</a></li>
              </ul>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12 text-center">
    <div class="row"><div class="col-md-12 text-center">
    &copy; 2021 - Test Sevima</div></div>
  </div></footer>
  <div id="ftco-loader" class="show fullscreen"><svg class="circular" width="48px" height="48px"><circle class="path-bg" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke="#eeeeee"/><circle class="path" cx="24" cy="24" r="22" fill="none" stroke-width="4" stroke-miterlimit="10" stroke="#F96D00"/></svg></div>
  <script src="<?= base_url() ?>assets/js/jquery.min.js"></script>
  <script src="<?= base_url() ?>assets/js/jquery-migrate-3.0.1.min.js"></script>
  <script src="<?= base_url() ?>assets/js/popper.min.js"></script>
  <script src="<?= base_url() ?>assets/js/bootstrap.min.js"></script>
  <script src="<?= base_url() ?>assets/js/jquery.easing.1.3.js"></script>
  <script src="<?= base_url() ?>assets/js/jquery.waypoints.min.js"></script>
  <script src="<?= base_url() ?>assets/js/jquery.stellar.min.js"></script>
  <script src="<?= base_url() ?>assets/js/owl.carousel.min.js"></script>
  <script src="<?= base_url() ?>assets/js/jquery.magnific-popup.min.js"></script>
  <script src="<?= base_url() ?>assets/js/aos.js"></script>
  <script src="<?= base_url() ?>assets/js/jquery.animateNumber.min.js"></script>
  <script src="<?= base_url() ?>assets/js/scrollax.min.js"></script>
  <script src="<?= base_url() ?>assets/js/main.js?v2"></script>
</body>
</html>