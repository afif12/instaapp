<div class="hero-wrap hero-wrap-2" style="background-image: url('<?= base_url() ?>assets/images/bg_1.jpg');" data-stellar-background-ratio="0.5">
  <div class="overlay"></div>
  <div class="container">
    <div class="row no-gutters slider-text align-items-end justify-content-start">
      <div class="col-md-12 ftco-animate text-center mb-5">
        <h1 class="mb-3 bread">Beranda</h1>
      </div>
    </div>
  </div>
</div>

<section class="ftco-section">
  <div class="container">
    <div class="row d-flex">
      <?php
				foreach($post->result_array() as $row){
			?>
      <div class="col-md-3 d-flex ftco-animate">
        <div class="blog-entry align-self-stretch">
          <a href="<?= site_url('home/preview/'.$row['post_id']) ?>" class="block-20" style="background-image: url(<?= base_url() ?>upload/<?=$row['upload_file']?>);">
          </a>
          <div class="text mt-3">
            <div class="meta mb-2">
              <div><a href="<?= site_url('home/preview/'.$row['post_id']) ?>"><?= $row['date'] ?></a></div>
              <div><a href="<?= site_url('home/preview/'.$row['post_id']) ?>"><?= $row['name'] ?></a></div>
              <div><a href="<?= site_url('home/preview/'.$row['post_id']) ?>" class="meta-chat"><span class="icon-chat"></span><?= $row['jumlah'] ?></a></div>
            </div>
            <h3 class="heading"><a href="<?= site_url('home/preview/'.$row['post_id']) ?>"><?= $row['caption'] ?></a></h3>
          </div>
        </div>
      </div>
      <?php
				}
			?>
        </div>
      </div>
    </section>