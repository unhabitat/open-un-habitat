<?php
/*
Template Name: Projects template
*/
?>
<?php get_header(); ?>

<div class="container">
   <div class="map">
      <div class="map-menu">
         <p class="map-results">Results  <span class="project-list-amount"></span> of <span class="total-project-amount"></span> activities</p>

         <!-- <a href="#" class="map-btn bar-chart">Bar Chart</a>  -->
         <!-- <a href="#" class="map-btn scatter-plot">Scatter Plot</a>  -->
         <!-- <a href="#" class="map-btn circle-package">Circle Package</a>  -->
         <a href="#" class="btn-visualisation">View a visualization of the project data</a> 
         <a href="#" class="btn-map">View the project data on a map</a></div>

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


<?php include( TEMPLATEPATH .'/projects-filters.php' ); ?>


<div class="container">
   <div class="col-md-7 filter-results">Results <span class="project-list-amount"></span> of <span class="total-project-amount"></span> activities &gt; <span class="filters-selected-text">No filter selected</span></div>
   <div class="col-md-5 sort-by"> Sort by :
      <select id="sort-by-budget">
         <option value="0">Budget</option>
         <option value="asc">Ascending</option>
         <option value="desc">Descending</option>
      </select>
      <select id="sort-by-start-actual">
         <option value="0">Start Date</option>
         <option value="asc">Ascending</option>
         <option value="desc">Descending</option>
      </select>
   </div>
</div>

<div id="project-list-wrapper">
   <?php include( TEMPLATEPATH .'/ajax/project-list-ajax.php' ); ?>
</div>
<div id="project-list-pagination"></div>
   <?php include( TEMPLATEPATH .'/footer-scripts.php' ); ?>
<script>
   $(function() {
      Oipa.pageType = "activities";

      var selection = new OipaSelection(1, 1);
      Oipa.mainSelection = selection;
      <?php 

      if (isset($_GET['query'])){
         echo "selection.query = '" . $_GET['query'] . "';";
      }
      ?>

      var map = new OipaMap();
      map.set_map("map", 'topright');
      map.selection = Oipa.mainSelection;
      map.selection.group_by = "country";
      Oipa.maps.push(map);

      var filter = new OipaFilters();
      Oipa.mainFilter = filter;
      filter.selection = Oipa.mainSelection;
      filter.init();

      

      projectlist = new OipaProjectList();
      projectlist.list_div = "#project-list-wrapper";
      projectlist.pagination_div = "#project-list-pagination";
      projectlist.selection = Oipa.mainSelection;
      Oipa.lists.push(projectlist);

      map.refresh();
      projectlist.init();

      var stats = new OipaMainStats();
      var total_projects = stats.get_total_projects();

      load_scatter_plot = true;
   });


</script>
<script defer src="<?php echo get_stylesheet_directory_uri(); ?>/js/plugins.js"></script>
<script defer src="<?php echo get_stylesheet_directory_uri(); ?>/js/script.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/CustomTooltip.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/libs/d3.js"></script>
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/bubblechart.js"></script>

<?php /*
<script src="<?php echo get_stylesheet_directory_uri(); ?>/js/libs/coffee-script.js"></script>
<script type="text/coffeescript" src="<?php echo get_stylesheet_directory_uri(); ?>/coffee/vis.coffee"></script>
*/ ?>

<?php get_footer(); ?>