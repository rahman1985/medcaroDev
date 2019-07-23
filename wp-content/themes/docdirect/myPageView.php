<?php
/*
Template Name: My Training Page
*/
get_header();
$args = array(
    'author' => $url_identity,
    'post_type' => 'my-training-page',
    'post_status' => 'publish',
    'order'	=> 'DESC',
    'orderby'	=> 'ID',
  );
$query = new WP_Query( $args );
?>
<div class="featuresAndService">
  <h6>FEATURES & SERVICES</h6>
  <h2>Services We Can Help You With</h2>
  <div class="container">
    <div class="row">
      <?php
        if ($query->have_posts()){
        while($query->have_posts()) : $query->the_post();
          $contentStr = get_the_content();
          $contentStr = substr($contentStr,0,250);
	        $contentStr = substr($contentStr,0,strrpos($contentStr,' '));
	        $contentStr = $contentStr." ...";
      ?>
        <div class="col-xs-12 col-sm-6">
          <div class="serviceBox">
            <span class="icon"><i class="<?php echo get_field("icon_image"); ?>"></i></span>
            <h5><?php echo get_the_title(); ?></h5>
            <p><?php echo $contentStr;?></p>
            <a href="<?php echo get_field("link"); ?>" class="moreBtn">FIND OUT MORE</a>        
          </div>
        </div>
      <?php endwhile; }else {?>
        <div class="col-xs-12 col-sm-12">
          <div class="serviceBox">
            <h6>No Training Content Available</h6>
          </div>
        </div>
      <?php } ?>
    </div>
  </div>
</div>
<?php wp_reset_query(); ?> 
<?php get_footer(); ?>