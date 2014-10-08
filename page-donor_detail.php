<?php
/*
Template Name: Donor detail template
*/
?>
<?php get_header(); ?>


<?php 
// Donor detail
if (isset($_REQUEST['donor_id'])){
   $country_id = $_REQUEST['donor_id'];
} else {
   $url_parts = explode("/", $_SERVER["REQUEST_URI"]);
   $partamount = count($url_parts);
   $donor_id = $url_parts[($partamount -2)];
   oipa_get_donor($donor_id, $donor_info);
   $donor_info = $donor_info->objects[0];
}
?>

<?php require('incl/pager.php'); ?>
<div class="container country-detail">
   <div class="col-md-12">
      <h1><?php if(isset($donor_info->name)){ echo $donor_info->name; } ?></h1>
      <p> Aenean eu leo quam. Pellentesque ornare sem lacinia quam venenatis vestibulum. Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Vestibulum id ligula porta felis euismod semper. Maecenas sed diam eget risus varius blandit sit amet non magna. </p>
   </div>
</div>
<div class="container country-detail">
   <div class="col-md-12">
      <h3>Information</h3>
   </div>
   <div class="col-md-3">
      <p>Total<br>
         budget<span class="budget">US$<span> <?php echo number_format($donor_info->total_budget, 0, '', '.'); ?></span></span></p>
      <hr>
      <p>Number of active<br>
         projects<span class="big-text" class="float-right"><?php if(isset($donor_info->total_projects)){ echo $donor_info->total_projects; } ?></span></p>
   </div>
</div>
<div class="container country-detail">
   <div class="col-md-12">
      <h3>Projects</h3>
   </div>
</div>
<div class="container">
   <div class="col-md-7 filter-results">Results <?php echo $donor_info->total_projects; ?> of <?php echo $donor_info->total_projects; ?> Activity &gt; <?php if(isset($donor_info->name)){ echo $donor_info->name; } ?></div>
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
   selection.donors.push({"id": "<?php echo $donor_id; ?>", "name": "<?php echo $donor_info->name; ?>"});
   Oipa.mainSelection = selection;

   var filter = new OipaFilters();
   Oipa.mainFilter = filter;
   filter.selection = Oipa.mainSelection;
   filter.init();

   projectlist = new OipaProjectList();
   projectlist.list_div = "#project-list-wrapper";
   projectlist.pagination_div = "#project-list-pagination";
   projectlist.selection = Oipa.mainSelection;

   Oipa.lists.push(projectlist);
   projectlist.init();


</script>
<?php get_footer(); ?>