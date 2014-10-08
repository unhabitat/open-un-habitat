<?php
/*
Template Name: Page with right sidebar
*/
?>
<?php get_header(); ?>
<div class="spacer"></div>
<div class="container">
   <div class="col-md-8">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
         <?php the_content(); ?>
      <?php endwhile; endif; ?>
   </div>
   
   <div class="col-md-4">
      <?php dynamic_sidebar('right-widget-area'); ?>
   </div>
</div>
<?php get_footer(); ?>