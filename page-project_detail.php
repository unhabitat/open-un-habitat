<?php
/*
Template Name: Project detail template
*/

get_header(); 

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

require('incl/pager.php'); 

?>

<div class="container project-detail">
   <div class="col-md-9 col-md-push-3">
      <h1 class="project-title"><?php if(!empty($activity->titles)){ echo $activity->titles[0]->title; }  ?></h1>
      
      <div class="row">
         <div class="col-md-7">
            <p><?php if(!empty($activity->descriptions)){ echo $activity->descriptions[0]->description; }  ?></p>
         </div>
         <div class="col-md-5">
            <div id="project-detail-map"></div>
         </div>
      </div>

      <div style="clear:both;"></div>
      <h2>Financials</h2>
      <?php if(empty($activity->transactions)){
         echo "Not available";
      } else { ?>
      <div class="table-responsive">
         <table class="table table-striped table-un">
            <thead>
               <tr>
                  <th>Transaction type</th>
                  <th>Value</th>
                  <th>Date</th>
               </tr>
            </thead>
            <tbody>

               <?php foreach($activity->transactions as $transaction){
                  echo "<tr><td>";
                  $transaction_type = $transaction->transaction_type;
                  switch ($transaction_type) {
                     case "C":
                          echo "Commitment";
                          break;
                     case "D":
                          echo "Disbursement";
                          break;
                     case "E":
                          echo "Expenditure";
                          break;
                      case "IF":
                          echo "Incoming Funds";
                          break;
                  }
                  echo "</td><td>";
                  echo "US$ " . number_format($transaction->value, 0, '', ',');
                  echo "</td><td>";
                  echo $transaction->value_date;
                  echo "</td></tr>";
               } ?>
            </tbody>
         </table>
      </div>
      <?php } ?>


      <h2>Document</h2>
      <?php if (!empty($activity->documents)){ ?>
      <p> <?php foreach($activity->documents as $document){ 
         echo $document->title . "<br>";
         ?>
         <a target="_blank" href="<?php echo $document->url; ?>" class="btn btn-default btn-download-all-data">Download the document</a> 
      <?php } ?>
      </p>
      <?php } else {
         echo "No documents available.";
      } ?>

      <?php if (!empty($activity->results)){ ?>

      <h2>Results</h2>
      <p> <?php foreach($activity->results as $result){ 
         echo $result->description;
      } ?>
      </p>
      <?php } ?>


      <h2>Related projects</h2>
      
      <div class="row">
         <div id="project-list-wrapper" class="related-projects-wrapper">
            <?php include( TEMPLATEPATH .'/ajax/related-project-list-ajax.php' ); ?>
         </div>
      </div>


   </div>
   <div class="col-md-3 col-md-pull-9">
   
      <div class="widget social-media-widget"> 
         <?php 
         $current_url = $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"];
         $current_title = the_title('', '', false);
         ?>
         <div class="openunh-share-buttons">
            <a id="openunh-share-linkedin" href="http://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode("http://" . $current_url); ?>&title=<?php echo $current_title; ?>" target="_blank"></a>
            <a id="openunh-share-twitter" href="http://twitter.com/home?status=<?php echo urlencode($current_title . " | " . $current_url); ?>" target="_blank"></a>
            <a id="openunh-share-facebook" href="http://www.facebook.com/sharer/sharer.php?s=100&p[url]=<?php echo $current_url; ?>" target="_blank"></a>
            <a id="openunh-share-googleplus" href="https://plus.google.com/share?url=<?php echo $current_url; ?>" target="_blank"></a>
         </div>
      </div>
      <div class="widget">
         <h3>Information</h3>
         <div class="info-row info-row-first">
            <div class="info-col-1 info-budget">Budget</div>
            <div class="info-col-2 info-budget-price"><strong>US$ <span>

               <?php if(!empty($activity->total_budget)) {
                  echo number_format($activity->total_budget, 0, '', ',');
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
            <div class="info-col-1">Sectors codes</div>
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

      <?php /*

      <div class="widget">
         <h3>Location</h3>
            
         
         <div class="clearfix"></div>
      </div>

      */ ?>

      <div class="widget">
         <h3>Export project IATI data</h3>
         <div class="blue-col">
            <p>Select the format and download the data for this project</p>

            <a class="btn btn-info btn-download-data" data-format="json" data-id="<?php echo $id; ?>" href="#">JSON</a>
            <a class="btn btn-info btn-download-data" data-format="csv" data-id="<?php echo $id; ?>" href="#">CSV</a>
            <a class="btn btn-info btn-download-data" data-format="xml" data-id="<?php echo $id; ?>" href="#">XML</a>

         </div>
      </div>
      <div class="widget">
         <h3>Sector tags</h3>
         <?php 
         if (!empty($activity->sectors)) { 
            for($i = 0;$i < count($activity->sectors);$i++){
               ?>
               <div class="gray-col blue-corner">
                  <p>
                     <?php echo $activity->sectors[$i]->name; ?>
                  </p> 
               </div>
               <?php 
            }
         } ?>
      </div>
      <div class="widget">
         <h3>Embed code</h3>
         <div class="blue-col">
            <p>Embed code &lt;/&gt;</p>
            <div class="embed-code">  
            <textarea><script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/js/embed.js"></script><script>oipa_embed.options( url = "<?php echo site_url(); ?>/embed/<?php echo $id ?>/", width = 600, height = 730);</script></textarea>
         </div>

            <a href="#" style="float:right" class="btn btn-primary">COPY</a>
            <div class="clearfix"></div>
         </div>
      </div>

      <?php if(!empty($activity->contact_info)){ ?>
      <div class="widget contact">
         <h3>Main contact</h3>
            <?php 
            foreach($activity->contact_info as $contact){
            ?>
            <h4><?php echo $contact->person_name; ?></h4>
            <p><a href="mailto:"><?php echo $contact->email; ?></a></p>
            <?php
            }  
         ?>
      </div>
      <?php } ?>
   </div>
</div>

<?php include( TEMPLATEPATH .'/footer-scripts.php' ); ?>

<script>

   Oipa.pageType = "activities";

   $( document ).ready(function() {

      var map = new OipaMap();
      map.set_map("project-detail-map", null);
      map.selection = Oipa.mainSelection;
      map.selection.group_by = "country";
      Oipa.maps.push(map);
      
      <?php 

      $region_codes = array();

      function add_iati_locations_to_map($activity){
         $exact_locs = array();
         for($i = 0;$i < count($activity->locations);$i++){
            // show regions on map
            if (!empty($activity->locations[$i]->latitude) && !empty($activity->locations[$i]->longitude)){
               array_push($exact_locs, array(
                  "name" => $activity->locations[$i]->name,
                  "latitude" => $activity->locations[$i]->latitude,
                  "longitude" => $activity->locations[$i]->longitude
               ));
            }
         }
         if (!empty($exact_locs)){
            echo "map.show_project_detail_locations('exact_location', '" . json_encode($exact_locs) . "');";
         } else {


            $activity->locations = null;
            load_geo_to_map($activity);
         }
         
      }

      function add_countries_to_map($activity){
         $loc_countries = array();
         for($i = 0;$i < count($activity->countries);$i++){
            // show regions on map
            array_push($loc_countries, $activity->countries[$i]->code);
         }
         echo "map.show_project_detail_locations('countries', '" . implode(",", $loc_countries) . "');";
      }

      function add_regions_to_map($activity){
         $loc_regions = array();
         for($i = 0;$i < count($activity->regions);$i++){
            // show regions on map
            array_push($loc_regions, $activity->regions[$i]->code);
         }
         echo "map.show_project_detail_locations('regions', '" . implode(",", $loc_regions) . "');";
      }

      function load_geo_to_map($activity){
         if (!empty($activity->locations)) { 
            add_iati_locations_to_map($activity);

         } else if (!empty($activity->countries)) { 
            add_countries_to_map($activity);

         } else if (!empty($activity->regions)) {
            add_regions_to_map($activity);
         }
      }

      load_geo_to_map($activity);

      ?>

   });
</script>

   



<?php get_footer(); ?>