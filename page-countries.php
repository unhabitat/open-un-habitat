<?php
/*
Template Name: Countries template
*/
?>
<?php get_header(); ?>

<div class="container">
   <div class="map"> <div id="map" class="project-vis-wrapper"></div></div>
</div>
<div id="country-page-filters">
<?php include( TEMPLATEPATH .'/projects-filters.php' ); ?>
</div>
<div class="container"> <!-- TO DO > total country count -->
   <div class="col-md-7 filter-results">Results <span id="current-grouped-list-count"></span> of <span id="total-country-amount"></span> Countries &gt; <span class="filters-selected-text">No filter selected</span></div>
   <div class="col-md-5 sort-by" style="text-align:right;"> Sort by :
      <select id="sort-by-budget">
         <option value="0">Budget</option>
         <option value="asc">Ascending</option>
         <option value="desc">Descending</option>
      </select>
   </div>
</div>
<div class="container country-list-wrapper">
   <div class="list-search">
      <div class="col-lg-6">
         <form action="/" method="get">
         <div class="input-group">
            <input id="grouped-list-search" type="text" class="form-control" name="s">
            <span class="input-group-btn">
            <button class="btn btn-default" type="submit"><span class="glyphicon glyphicon-search"></span></button>
            </span> 
            
         </div>
         </form>
         <!-- /input-group --> 
      </div>
      <div class="clearfix"></div>
   </div>
</div>

<div id="grouped-list-wrapper">
   <?php include( TEMPLATEPATH .'/ajax/country-list-ajax.php' ); ?>
</div>
<div id="grouped-list-pagination"></div>
   <?php include( TEMPLATEPATH .'/footer-scripts.php' ); ?>
<script>

   Oipa.pageType = "activities";
   var selection = new OipaSelection(1, 1);
   Oipa.mainSelection = selection;

   var map = null;
   var otherlist = null;

   $( document ).ready(function() {

      map = new OipaMap();
      map.set_map("map", 'topright');
      map.selection = Oipa.mainSelection;
      map.selection.group_by = "country";
      Oipa.maps.push(map);

      var filter = new OipaFilters();
      Oipa.mainFilter = filter;
      filter.selection = Oipa.mainSelection;
      filter.init();

      otherlist = new OipaCountryList();
      otherlist.list_div = "#grouped-list-wrapper";
      otherlist.pagination_div = "#grouped-list-pagination";
      otherlist.selection = Oipa.mainSelection;
      otherlist.init();
      Oipa.lists.push(otherlist);

      map.refresh();

      var stats = new OipaMainStats();
      stats.get_total_countries();

   });


</script>
<?php get_footer(); ?>