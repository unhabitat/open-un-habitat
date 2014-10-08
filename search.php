<?php get_header(); ?>
<div class="spacer"></div>
<div class="container">
   <div class="col-md-12">  
      <?php if ( have_posts() ) : ?>
         <h1 class="entry-title"><?php printf( __( 'Search Results for: %s', 'unhabitat' ), get_search_query() ); ?></h1>
      <?php while ( have_posts() ) : the_post(); ?>
         <?php get_template_part( 'entry' ); ?>
      <?php endwhile; ?>
         <?php get_template_part( 'nav', 'below' ); ?>
      <?php else : ?>
         <h1 class="entry-title"><?php _e( 'Nothing Found', 'unhabitat' ); ?></h1>
         <p><?php _e( 'Sorry, nothing matched your search. Please try again.', 'unhabitat' ); ?></p>
         
         <form action="/" method="get">
         <div class="input-group">
            <input type="text" class="form-control" name="s">
            <span class="input-group-btn">
            <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
            </span> 
         </div>
         </form>

         
      <?php endif; ?>
   </div>
</div>
<div class="spacer"></div>
<div class="spacer"></div>
<div class="spacer"></div>
<?php get_footer(); ?>