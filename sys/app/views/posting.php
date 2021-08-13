<div class="hero-wrap hero-wrap-2" style="background-image: url('<?= base_url() ?>assets/images/bg_1.jpg');" data-stellar-background-ratio="0.5">
<div class="overlay"></div>
    <div class="container">
    <div class="row no-gutters slider-text align-items-end justify-content-start">
        <div class="col-md-12 ftco-animate text-center mb-5">
        <h1 class="mb-3 bread">Registrasi Akun</h1>
        </div>
    </div>
    </div>
</div>

<section class="ftco-section contact-section bg-light">
    <div class="container">
    <div class="row block-9">
        <div class="col-md-12 order-md-last d-flex">
        <form  action="<?= site_url('register/account'); ?>" method="POST" name="form" class="bg-white p-5 contact-form">
            <div class="form-group" style="width: 50%">
            <input type="text" class="form-control" name="phone" placeholder="Mobile Number" required>
            </div>
            <div class="form-group" style="width: 50%">
            <input type="email" class="form-control" name="email" placeholder="Email" required>
            </div>
            <div class="form-group" style="width: 50%">
            <input type="text" class="form-control" name="name" placeholder="Full Name" required>
            </div>
            <div class="form-group" style="width: 50%">
            <input type="text" class="form-control" name="login" placeholder="Username" required>
            </div>
            <div class="form-group" style="width: 50%">
            <input type="password" class="form-control" name="password" placeholder="Password" required>
            </div>
            <div class="form-group">
            <input type="submit" value="Registrasi Akun" class="btn btn-primary">
            </div>
            <div class="form-group" style="width: 50%">
            <span style="color: #666">atau, <a href="<?= site_url("home") ?>">klik di sini</a> untuk proses Login</span>
            </div>
        </form>
        </div>
    </div>
    </div>
</section>