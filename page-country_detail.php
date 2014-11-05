<?php
/*
Template Name: Country detail template
*/
?>
<?php get_header(); ?>
<?php 

// Country detail
if (isset($_REQUEST['country_id'])){
   $country_id = $_REQUEST['country_id'];
} else {
   $url_parts = explode("/", $_SERVER["REQUEST_URI"]);
   $partamount = count($url_parts);
   $country_id = $url_parts[($partamount -2)];
   
}

oipa_get_country($country_id, $country_info, $country);
$country_info = $country_info->objects[0];

?>

<?php require('incl/pager.php'); ?>
<div class="container country-detail">
   <div class="col-md-12 country-title">
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/64x42/<?php echo $country_id; ?>.png" width="64" height="42" />
      <h1><?php if(isset($country_info->name)){ echo $country_info->name; } ?></h1>
      <!-- <p></p> -->
   </div>
</div>
<div class="container country-detail">
   <div class="col-md-12">
      <h3>Information</h3>
   </div>
   <div class="col-md-4">
      <p>Total<br>
         budget<span class="budget">US$<span> <?php echo number_format($country_info->total_budget, 0, '', '.'); ?></span></span></p>
      <hr>
      <p>Number of active<br>
         projects<span class="big-text" class="float-right"><?php if(isset($country_info->total_projects)){ echo $country_info->total_projects; } ?></span></p>
   </div>
</div>
<div class="container country-detail">
   <div class="col-md-12">
      <h3>Projects</h3>
   </div>
</div>
<div class="container">
   <div class="col-md-7 filter-results">Results <?php echo $country_info->total_projects; ?> of <?php echo $country_info->total_projects; ?> Activity &gt; <?php if(isset($country_info->name)){ echo $country_info->name; } ?></div>
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
<div id="project-list-pagination" style="text-align:center"></div>
   <?php include( TEMPLATEPATH .'/footer-scripts.php' ); ?>
<script>
   
   Oipa.pageType = "activities";

   var selection = new OipaSelection(1, 1);
   selection.countries.push({"id": "<?php echo $country_id; ?>", "name": "<?php echo $country_info->name; ?>"});
   Oipa.mainSelection = selection;

   var filter = new OipaFilters();
   Oipa.mainFilter = filter;
   filter.selection = Oipa.mainSelection;
   filter.init();

   projectlist = new OipaProjectList();
   projectlist.list_div = "#project-list-wrapper";
   projectlist.pagination_div = "#project-list-pagination";
   projectlist.selection = Oipa.mainSelection;
   projectlist.init();
   Oipa.lists.push(projectlist);

   map.refresh();
   


</script>
<?php get_footer(); ?>