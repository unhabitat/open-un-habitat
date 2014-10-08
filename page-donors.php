<?php
/*
Template Name: Donors template
*/
?>
<?php get_header(); ?>

<?php include( TEMPLATEPATH .'/projects-filters.php' ); ?>

<div class="container">
   <div class="col-md-7 filter-results">Results <span id="project-list-amount"></span> of 264 Activity &gt; No filter selected</div>
   <div class="col-md-5 sort-by" style="text-align:right;"> Sort by :
      <select id="sort-by-budget">
         <option value="0">Budget</option>
         <option value="asc">Ascending</option>
         <option value="desc">Descending</option>
      </select>
   </div>
</div>
<div class="container">
   <div class="list-search">
      <div class="col-lg-6">
         <div class="input-group">
            <input type="text" class="form-control">
            <span class="input-group-btn">
            <button class="btn btn-default" type="button"><span class="glyphicon glyphicon-search"></span></button>
            </span> </div>
         <!-- /input-group --> 
      </div>
      <div class="clearfix"></div>
   </div>
</div>

<div id="grouped-list-wrapper">
   <?php include( TEMPLATEPATH .'/ajax/donor-list-ajax.php' ); ?>
</div>
<div id="grouped-list-pagination"></div>
   <?php include( TEMPLATEPATH .'/footer-scripts.php' ); ?>
<script>

   Oipa.pageType = "activities";
   var selection = new OipaSelection(1, 1);
   Oipa.mainSelection = selection;

   var filter = null;
   var otherlist = null;

   $( document ).ready(function() {
      filter = new OipaFilters();
      Oipa.mainFilter = filter;
      filter.selection = Oipa.mainSelection;
      filter.init();

      otherlist = new OipaDonorList();
      otherlist.list_div = "#grouped-list-wrapper";
      otherlist.pagination_div = "#grouped-list-pagination";
      otherlist.selection = Oipa.mainSelection;
      otherlist.init();
   });

</script>
<?php get_footer(); ?>