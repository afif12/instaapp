<?php
?>
<div class="hero-wrap hero-wrap-2" style="background-image: url('images/bg_1.jpg');" data-stellar-background-ratio="0.5">
    <div class="overlay"></div>
    <div class="container">
    <div class="row no-gutters slider-text align-items-end justify-content-start">
        <div class="col-md-12 ftco-animate text-center mb-5">
        <h1 class="mb-3 bread">Preview</h1>
        </div>
    </div>
    </div>
</div>

<section class="ftco-section ftco-degree-bg">
  <div class="container">
    <div class="row">
      <div class="col-md-8 ftco-animate">
        <p>
          <img src="<?= base_url() ?>upload/<?=$data['upload_file']?>" alt="" class="img-fluid">
        </p>
        <p><?= $data['caption'] ?></p>
      </div>
      <div class="pt-5 mt-5">
      <h3 class="mb-5">Comments</h3>
      <?php
      if($comment != ""){
      foreach($comment->result_array() as $row){
      ?>
      <ul class="comment-list">
        <li class="comment">
          <div class="comment-body">
            <h3><?= $row['name'] ?></h3>
            <div class="meta"><?= $row['date'] ?></div>
            <p><?= $row['comment'] ?></p>
          </div>
        </li>
      </ul>
      <?php
      }
      }
      ?>
      <!-- END comment-list -->
      
      <div class="comment-form-wrap pt-5">
        <h3 class="mb-5">Leave a comment</h3>
        <form action="<?= site_url('profile/preview/'.$post_id); ?>" method="POST" name="form"  class="p-5 bg-light">
          <div class="form-group">
            <label for="comment">Comment</label>
            <textarea name="comment" id="comment" cols="30" rows="10" class="form-control"></textarea>
          </div>
          <div class="form-group">
            <input type="submit" value="Post Comment" class="btn py-3 px-4 btn-primary">
          </div>

        </form>
      </div>
    </div>
    </div>
  </div>
</section>
