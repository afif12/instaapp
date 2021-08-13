<div class="hero-wrap hero-wrap-2" style="background-image: url('<?= base_url() ?>assets/images/bg_1.jpg');" data-stellar-background-ratio="0.5">
  <div class="overlay"></div>
  <div class="container">
    <div class="row no-gutters slider-text align-items-end justify-content-start">
      <div class="col-md-12 ftco-animate text-center mb-5">
        <h1 class="mb-3 bread">Profil</h1>
      </div>
    </div>
  </div>
</div>

<section class="ftco-section">
  <div class="container">
    <div class="row d-flex">
      <div class="about-author d-flex p-4 bg-light">
        <div class="desc">
          <h3><?= $data['name'] ?></h3>
          <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ducimus itaque, autem necessitatibus voluptate quod mollitia delectus aut, sunt placeat nam vero culpa sapiente consectetur similique, inventore eos fugit cupiditate numquam!</p>
          <a href="<?= site_url("profile/posting") ?>" class="btn btn-primary">Post</a>
        </div>
      </div>
      <?php
        if($post != ""){
				foreach($post->result_array() as $row){
			?>
      <div class="col-md-3 d-flex ftco-animate">
        <div class="blog-entry align-self-stretch">
          <a href="blog-single.html" class="block-20" style="background-image: url(<?= base_url() ?>upload/<?=$row['upload_file']?>);">
          </a>
          <div class="text mt-3">
            <div class="meta mb-2">
              <div><a href="#">August 28, 2019</a></div>
              <div><a href="#"><?= $row['name'] ?></a></div>
              <div><a href="#" class="meta-chat"><span class="icon-chat"></span> 3</a></div>
            </div>
            <h3 class="heading"><a href="#"><?= $row['caption'] ?></a></h3>
          </div>
        </div>
      </div>
      <?php
				}
        }
			?>
      </div>
        </div>
      </div>
    </section>