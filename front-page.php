<?php get_header(); ?>

<div class="container">
   <div class="map">
      <div class="map-menu">
         <p class="map-results">Results  <span id="project-list-amount"></span> of <span id="total-project-amount"></span> activities</p>
         <!-- <a href="#" class="map-btn bar-chart">Bar Chart</a> 
         <a href="#" class="map-btn scatter-plot">Scatter Plot</a> 
         <a href="#" class="map-btn circle-package">Circle Package</a>  -->
         <a href="#" class="btn-visualisation">View a visualization of the project data</a> 
         <a href="#" class="btn-map">View the project data on a map</a>
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
   <div id="carousel-homepage" class="carousel slide" data-ride="carousel" data-interval="10000">
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
         $args = array( 'post_type' => 'bs_slider', 'posts_per_page' => 1, 'offset' => 0 );
         $the_query = new WP_Query( $args ); 
         if ( $the_query->have_posts() ) {
             while ( $the_query->have_posts() ) {
                 $the_query->the_post(); 
      ?>
      
       <div class="item active">
          <div class="col-md-4">
             <h1 class="carousel-title"><?php the_title(); ?></h1>
             <?php the_content(); ?> 
          </div>
          <div class="col-md-8">
            <div id="hp-sector-slide" class="hp-total-wrapper">
              <h2>Expenditure per sector</h2>
              <hr>
              <div class="row">
                <div class="col-md-4">
                  <canvas id="hp-sector-pie-chart" width="183" height="183"></canvas>
                  <div id="chartjs-tooltip"></div>
                </div>
                <div class="col-md-8">
                  <div class="hp-table-title"> Top 5 expenditures</div>
                  <table class="table table-striped">
                    <tbody>
                      <tr>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td></td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
       </div>
      
      <?php 
         } // while
        } 
        wp_reset_postdata();



         $args = array( 'post_type' => 'bs_slider', 'posts_per_page' => 1, 'offset' => 1 );
         $the_query = new WP_Query( $args ); 
         if ( $the_query->have_posts() ) {
             while ( $the_query->have_posts() ) {
                 $the_query->the_post(); 
      ?>
      
       <div class="item">
          <div class="col-md-4">
             <h1 class="carousel-title"><?php the_title(); ?></h1>
             <?php the_content(); ?> 
          </div>
          <div class="col-md-8">
            <div id="hp-country-slide" class="hp-total-wrapper">
              <h2>Activities per country</h2>
              <hr>
              <div class="row">
                <div class="col-md-4">
                  <canvas id="hp-country-pie-chart" width="183" height="183"></canvas>
                  <div id="chartjs-tooltip-1"></div>
                </div>
                <div class="col-md-8">
                  <div class="hp-table-title"> Top 5 countries by activity</div>
                  <table class="table table-striped">
                    <tbody>
                      <tr>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td></td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
       </div>
      
      <?php 
         } // while
        } 
        wp_reset_postdata(); 




         $args = array( 'post_type' => 'bs_slider', 'posts_per_page' => 1, 'offset' => 2 );
         $the_query = new WP_Query( $args ); 
         if ( $the_query->have_posts() ) {
             while ( $the_query->have_posts() ) {
                 $the_query->the_post(); 
      ?>
      
       <div class="item">
          <div class="col-md-4">
             <h1 class="carousel-title"><?php the_title(); ?></h1>
             <?php the_content(); ?> 
          </div>
          <div class="col-md-8">
            <div id="hp-donor-slide" class="hp-total-wrapper">
              <h2>Budget per donor</h2>
              <hr>
              <div class="row">
                <div class="col-md-4">
                  <canvas id="hp-donor-pie-chart" width="183" height="183"></canvas>
                  <div id="chartjs-tooltip-2"></div>
                </div>
                <div class="col-md-8">
                  <div class="hp-table-title"> Top 5 donors by budget</div>
                  <table class="table table-striped">
                    <tbody>
                      <tr>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td></td>
                        <td></td>
                      </tr>
                      <tr>
                        <td></td>
                        <td></td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
       </div>
      
      <?php 
         } // while
        } 
        wp_reset_postdata(); ?>


      </div>
   </div>
</div>

<div class="container">
   <?php 
wp_reset_postdata();
   the_content(); ?>
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

   var stats = new OipaMainStats();
   stats.get_total_projects();

</script>

<script>

var load_scatter_plot = false;
if (Math.random() > 0.5){
  load_scatter_plot = true;
}
</script>

<script defer src="<?php echo get_stylesheet_directory_uri(); ?>/js/plugins.js"></script>
<script defer src="<?php echo get_stylesheet_directory_uri(); ?>/js/script.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/CustomTooltip.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/libs/d3.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/bubblechart.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/FrontPageCharts.js"></script>



<?php get_footer(); ?>