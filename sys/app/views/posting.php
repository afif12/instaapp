<div class="hero-wrap hero-wrap-2" style="background-image: url('<?= base_url() ?>assets/images/bg_1.jpg');" data-stellar-background-ratio="0.5">
<div class="overlay"></div>
    <div class="container">
    <div class="row no-gutters slider-text align-items-end justify-content-start">
        <div class="col-md-12 ftco-animate text-center mb-5">
        <h1 class="mb-3 bread">Posting</h1>
        </div>
    </div>
    </div>
</div>

<section class="ftco-section contact-section bg-light">
    <div class="container">
    <div class="row block-9">
        <div class="col-md-12 order-md-last d-flex">
        <form  action="<?= site_url('profile/posting'); ?>" method="POST" name="form" class="bg-white p-5 contact-form" enctype="multipart/form-data">
            <input type="file" name="file-upload" id="file-upload" style="display:none">
            <div class="form-group">
                <textarea rows="2" class="form-control no-resize" name="caption" placeholder="Caption" required></textarea>
            </div>
            <div class="form-group" style="width: 50%">
                <input type="text" class="form-control" name="upload" id="upload" placeholder="File Upload (jpg, png, gif)">
            </div>
            <div class="form-group">
                <input type="submit" value="Posting" class="btn btn-primary">
            </div>
        </form>
        </div>
    </div>
    </div>
</section>
<script>
    $(function(){
        $('#post').click(function(){
            $('#form').submit();
            return false;
        });
        $('#upload').click(function() {
            $('#file-upload').click();
            return false;
        });
        $("#file-upload").change(function() {
            $('#upload').val($(this).val());
        });
    });
</script>