<?php get_header(); ?>
<div class="spacer"></div>
<div class="container">
   <div class="col-md-12">
      <?php if ( have_posts() ) : while ( have_posts() ) : the_post(); ?>
         <?php the_content(); ?>
      <?php endwhile; endif; ?>
   </div>
</div>
<?php get_footer(); ?>