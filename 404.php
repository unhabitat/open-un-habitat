<?php get_header(); ?>

<div class="spacer"></div>
<div class="container">
   <div class="col-md-12">
      <h1 class="title"><?php _e( 'Not Found', 'unhabitat' ); ?></h1>
      <p><?php _e( 'Nothing found for the requested page. Try a search instead?', 'unhabitat' ); ?></p>
      <form action="/" method="get">
      <div class="input-group">
         <input type="text" class="form-control" name="s">
         <span class="input-group-btn">
         <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
         </span> 
      </div>
      </form>
   </div>
</div>
<div class="spacer"></div>
<div class="spacer"></div>
<div class="spacer"></div>

<?php get_footer(); ?>