<?php
/*
Template Name: FAQ template
*/
?>
<?php get_header(); ?>
<div class="spacer"></div>
<div class="container">
<?php
while ( have_posts() ) : the_post();
   the_content();
endwhile;
?>
</div>
<?php get_footer(); ?>