<?php
/*
Template Name: Embed template
*/

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="description" content="">
<meta name="author" content="">
<link rel="icon" type="image/x-icon" href="<?php echo get_stylesheet_directory_uri(); ?>>/favicon.ico" />
<link rel="icon" type="image/png" href="<?php echo get_stylesheet_directory_uri(); ?>/favicon.png" />
<title><?php wp_title( ' | ', true, 'right' ); ?><?php bloginfo('name'); ?></title>

<!-- Google fonts -->
<link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,400italic,300italic,300,600italic,700,700italic' rel='stylesheet' type='text/css'>

<!-- Bootstrap -->
<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/bootstrap.css" rel="stylesheet">
<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/bootstrap-select.min.css" rel="stylesheet">

<!-- Leaflet -->
<link rel="stylesheet" href="http://cdn.leafletjs.com/leaflet-0.7.3/leaflet.css" />

<!-- Custom styles -->
<link href="<?php echo get_stylesheet_directory_uri(); ?>/css/style.css?v=<?php echo time(); ?>" rel="stylesheet">

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->




<?php include( TEMPLATEPATH . '/constants.php' ); ?>

<?php include( TEMPLATEPATH . '/oipa-functions.php' ); ?>


<script type="text/javascript">
var _gaq = _gaq || [];
_gaq.push(['_setAccount', '<?php echo GOOGLE_ANALYTICS_CODE; ?>']);
_gaq.push(['_trackPageview']);
(function() {
var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
})();
</script>


<script>
   var search_url = '<?php echo OIPA_URL; ?>';
   var home_url = "<?php echo bloginfo("url"); ?>";
   var site_url = home_url;
   var template_directory = "<?php echo bloginfo("template_url"); ?>";
   var site_title = "<?php echo wp_title(''); ?>";
   var standard_basemap = "zimmerman2014.hmpkg505";
   var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
   var ajax_path = "/wp-admin/admin-ajax.php?action=refresh_elements";
   var theme_path = "<?php echo get_stylesheet_directory_uri(); ?>";
</script>

<?php // include( TEMPLATEPATH . '/oipa-ajax-functions.php' ); ?>

<link rel="stylesheet" type="text/css" href="<?php echo get_stylesheet_uri(); ?>" />
<?php wp_head(); ?>

</head>
<body id="embed-page">


	<?php 

if (isset($_GET['id'])){
  $id = $_GET['id'];
} else if(isset($_GET['iati_id'])){
  $id = $_GET['iati_id'];
} else {
   $url_parts = explode("/", $_SERVER["REQUEST_URI"]);
   $partamount = count($url_parts);
   $id = $url_parts[($partamount -2)];
}

$activity = oipa_get_activity($id);


?>

<div style="display: block; width: 600px; padding: 10px 10px 0 10px;">
  <h1 class="project-title"><a href="<?php echo home_url() . '/project/' . $id . '/'; ?>"><?php if(!empty($activity->titles)){ echo $activity->titles[0]->title; }  ?></a></h1>
  <p class="project-description"><?php if(!empty($activity->descriptions)){ 
  	echo substr($activity->descriptions[0]->description, 0, 180) . "... <a target='_blank' href='" . home_url() . '/project/' . $id . "/'> Read more</a>";
  }	?></p>

<hr>
<div class="row">
	<div class="col-md-5" style="max-height: 420px; overflow: hidden;">
		<div class="widget">
	         <h3>Information</h3>
	         <div class="info-row info-row-first">
	            <div class="info-col-1 info-budget">Budget</div>
	            <div class="info-col-2 info-budget-price"><strong>US$ <span>

	               <?php if(!empty($activity->total_budget)) {
	                  echo number_format($activity->total_budget, 0, '', '.');
	               } else {
	                  echo "-";
	               } ?>


	            </span></strong></div>
	            <div class="clearfix"></div>
	         </div>
	         <div class="info-row">
	            <div class="info-col-1">IATI Indentifier</div>
	            <div class="info-col-2"><strong><?php echo $activity->iati_identifier; ?></strong></div>
	            <div class="clearfix"></div>
	         </div>
	         <div class="info-row">
	            <div class="info-col-1">Sectors</div>
	            <div class="info-col-2"><strong>
	               <?php 
	               $sector_codes = "";
	               if (!empty($activity->sectors)) { 
	                  $sep = ", ";
	                  for($i = 0;$i < count($activity->sectors);$i++){
	                     if ($i == (count($activity->sectors) - 1)){ $sep = ""; }
	                     $sector_codes .= $activity->sectors[$i]->code . $sep;
	                  }
	                  echo $sector_codes;
	               } ?>
	            </strong></div>
	            <div class="clearfix"></div>
	         </div>
	         <div class="info-row">
	            <div class="info-col-1">Start date planned</div>
	            <div class="info-col-2"><strong><?php if (!empty($activity->start_planned)) { echo $activity->start_planned; } ?></strong></div>
	            <div class="clearfix"></div>
	         </div>
	         <div class="info-row">
	            <div class="info-col-1">End date planned</div>
	            <div class="info-col-2"><strong><?php if (!empty($activity->end_planned)) { echo $activity->end_planned; } ?></strong></div>
	            <div class="clearfix"></div>
	         </div>
	         <div class="info-row">
	            <div class="info-col-1">Activity status</div>
	            <div class="info-col-2"><strong><?php if (!empty($activity->activity_status)) { echo $activity->activity_status->name; } ?></strong></div>
	            <div class="clearfix"></div>
	         </div>

	         <?php 
	         if (!empty($activity->regions)) { ?>
	         <div class="info-row">
	            <div class="info-col-1">Regions</div>
	            <div class="info-col-2"><strong>
	               <?php 
	                  $regions = "";
	                  $sep = ", ";

	                  for($i = 0;$i < count($activity->regions);$i++){
	                     if ($i == (count($activity->regions) - 1)){ $sep = ""; }
	                     $regions .= $activity->regions[$i]->name . $sep;
	                  }

	                  echo $regions;
	              ?>
	            </strong></div>
	            <div class="clearfix"></div>
	         </div>
	         <?php  } ?>

	         <?php 
	         if (!empty($activity->countries)) { ?>
	         <div class="info-row">
	            <div class="info-col-1">Countries</div>
	            <div class="info-col-2"><strong>
	               <?php 
	                  $countries = "";
	                  $sep = ", ";

	                  for($i = 0;$i < count($activity->countries);$i++){
	                     if ($i == (count($activity->countries) - 1)){ $sep = ""; }
	                     $countries .= $activity->countries[$i]->name . $sep;
	                  }

	                  echo $countries;
	              ?>
	            </strong></div>
	            <div class="clearfix"></div>
	         </div>
	         <?php  } ?>

	         <div class="info-row info-row-last">
	            <div class="info-col-1">Participating organisations</div>
	            <div class="info-col-2"><strong>
	               <?php 
	               if (!empty($activity->participating_organisations)) { 
	                  $part_orgs = "";
	                  $sep = ", ";


	                  for($i = 0;$i < count($activity->participating_organisations);$i++){
	                     if ($i == (count($activity->participating_organisations) - 1)){ $sep = ""; }
	                     $part_orgs .= $activity->participating_organisations[$i]->name . $sep;
	                  }
	                  echo $part_orgs;
	               } ?>
	            </strong></div>
	            <div class="clearfix"></div>
	         </div>

	      </div>
	</div>
	<div class="col-md-7">

		<div id="project-detail-map"></div>

	</div>
</div>
<div class="row">
<div class="col-md-12" style="background: #1AA4D6; margin-top: 16px; text-align: center; color: #fff; height: 136px;">

	<a href="<?php echo home_url() . '/project/' . $id . '/'; ?>" style="font-weight: bold; background-color: #1594C1; padding: 10px 8px; color: #fff; margin: 10px;display: inline-block;">
		More information about this project
	</a>
	<br>
	<img style="margin-top: 16px;" src="<?php echo get_stylesheet_directory_uri(); ?>/img/un-habitat-logo-footer.png" width="200" height="36" />
</div>
</div>






</div>



<?php include( TEMPLATEPATH .'/footer-scripts.php' ); ?>




<script>

   Oipa.pageType = "activities";
   var selection = new OipaSelection(1, 1);
   Oipa.mainSelection = selection;


   $( document ).ready(function() {

      var map = new OipaMap();
      map.set_map("project-detail-map", null);
      map.selection = Oipa.mainSelection;
      map.selection.group_by = "country";
      Oipa.maps.push(map);


      <?php 


      $region_codes = array();



      if (!empty($activity->locations)) { 


      } else if (!empty($activity->countries)) { 


      } else if (!empty($activity->regions)) {

         $loc_regions = array();
         for($i = 0;$i < count($activity->regions);$i++){
            // show regions on map
            array_push($loc_regions, $activity->regions[$i]->code);
         }
         echo "map.show_project_detail_locations('regions', '" . implode(",", $loc_regions) . "');";
      }
      ?>




   });

</script>











<?php wp_footer(); ?>
</body></html>