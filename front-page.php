<?php get_header(); ?>

<div class="container">
   <div class="map">
      <div class="map-menu">
         <p class="map-results">Results  <span id="project-list-amount"></span> of <span id="total-project-amount"></span> activities</p>
         <!-- <a href="#" class="map-btn bar-chart">Bar Chart</a> 
         <a href="#" class="map-btn scatter-plot">Scatter Plot</a> 
         <a href="#" class="map-btn circle-package">Circle Package</a>  -->
         <a href="#" class="btn-visualisation">Switch to Visualisation</a> 
         <a href="#" class="btn-map">Switch to Map</a>
      </div>

      <div id="map-container">
         <div id="map" class="project-vis-wrapper"></div>
         <div class="bar-chart-wrapper project-vis-wrapper"></div>
         <div class="scatter-plot-wrapper project-vis-wrapper">

            <div id="vis"></div>

            
         </div>
         <div class="circle-package-wrapper project-vis-wrapper"></div>
      </div>
   </div>
</div>

<div class="container map-alt-mobile">
   <div class="col-md-12"> <a href="projects.html" class="btn btn-primary btn-lg btn-block">View projects</a> </div>
   <div class="spacer"></div>
</div>
<div class="container">
   <div id="carousel-homepage" class="carousel slide" data-ride="carousel">
      <div class="carousel-header"><strong>UN-HABITAT</strong> - Open data IATI visualization 
         <!-- Indicators -->
         <ol class="carousel-indicators">
                     
            <?php 
               $args = array( 'post_type' => 'bs_slider' );
               $the_query = new WP_Query( $args ); 
               if ( $the_query->have_posts() ) {
                   $first = true;
                   $n = 0;
                   while ( $the_query->have_posts() ) {
                       $the_query->the_post(); 
            ?>
      
            <li data-target="#carousel-homepage" data-slide-to="<?php echo $n ?>"<?php if($first) echo ' class="active"'; ?>></li>

            <?php 
                  $first = false;
                  $n++;
               } // while
               wp_reset_postdata();
            ?>
            
            <?php } // end if have posts ?>
            
         </ol>
      </div>
      <!-- Wrapper for slides -->
      <div class="carousel-inner">
      
      <?php 
         $args = array( 'post_type' => 'bs_slider' );
         $the_query = new WP_Query( $args ); 
         if ( $the_query->have_posts() ) {
             $first = true;
             while ( $the_query->have_posts() ) {
                 $the_query->the_post(); 
      ?>
      
               <div class="item<?php if($first) echo ' active'; ?>">
                  <div class="col-md-4">
                     <h1 class="carousel-title"><?php the_title(); ?></h1>
                     <?php the_content(); ?> 
                  </div>
                  <div class="col-md-8"><?php the_post_thumbnail( 'full', array( 'class' => 'img-responsive' ) );?></div>
               </div>
      
      <?php 
            $first = false;
         } // while
         wp_reset_postdata();
      ?>
      
      <?php } else { ?>
         <p><?php _e( 'No slides found. Please insert some slides', 'unhabitat' ); ?></p>
      <?php } // end if have posts ?>

      </div>
   </div>
</div>
<div class="container">
   <?php the_content(); ?>
</div>
<?php include( TEMPLATEPATH .'/footer-scripts.php' ); ?>

<script>
   
   Oipa.pageType = "activities";

   var selection = new OipaSelection(1, 1);
   Oipa.mainSelection = selection;

   var map = new OipaMap();
   map.set_map("map", 'topright');
   map.selection = Oipa.mainSelection;
   map.selection.group_by = "country";
   Oipa.maps.push(map);

   map.refresh();


</script>

<script defer src="<?php echo get_stylesheet_directory_uri(); ?>/js/plugins.js"></script>
<script defer src="<?php echo get_stylesheet_directory_uri(); ?>/js/script.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/CustomTooltip.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/libs/coffee-script.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/libs/d3.js"></script>
<script type="text/coffeescript" src="<?php echo get_stylesheet_directory_uri(); ?>/coffee/vis.coffee"></script>

<?php get_footer(); ?>