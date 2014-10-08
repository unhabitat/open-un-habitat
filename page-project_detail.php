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
      <p><?php if(!empty($activity->descriptions)){ echo $activity->descriptions[0]->description; }  ?></p>
      <h2>Financials</h2>
      <?php if(empty($activity->transactions)){
         echo "Not available";
      } else { ?>
      <div class="table-responsive">
         <table class="table table-striped table-un">
            <thead>
               <tr>
                  <th>Transaction type</th>
                  <th>Provider Org</th>
                  <th>Receiver Org</th>
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
                  echo "</td><td></td><td></td><td>";
                  echo "US$ " . number_format($transaction->value, 0, '', '.');
                  echo "</td><td>";
                  echo $transaction->value_date;
                  echo "</td></tr>";
               } ?>
            </tbody>
         </table>
      </div>
      <?php } ?>
      <h2>Visualisation</h2>
      <img src="<?php echo get_stylesheet_directory_uri(); ?>/img/dummy-visualisation.jpg" alt="dummy" class="img-responsive"/>
      

      <?php /*
      <p> Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Vestibulum id ligula porta felis euismod semper. Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
      <h2>Donors</h2>
      <p> Duis mollis, est non commodo luctus, nisi erat porttitor ligula, eget lacinia odio sem nec elit. Integer posuere erat a ante venenatis dapibus posuere velit aliquet. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Vestibulum id ligula porta felis euismod semper. Lorem ipsum dolor sit amet, consectetur adipiscing elit. </p>
      */ ?>

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
   </div>
   <div class="col-md-3 col-md-pull-9">
      <?php /* niet meer van toepassing 
      <div class="widget">
         <button class="btn btn-default btn-block">save project</button>
         <p style="margin-top:10px;"><a href="#">Sign in</a> or <a href="#">register</a> to save projects to your dashboard.</p>
      </div>
      */ ?>
   
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
            <textarea><script type="text/javascript" src="<?php echo site_url(); ?>/wp-content/themes/rain/js/embed.js"></script><script>oipa_embed.options( url = "<?php echo site_url(); ?>/embed/<?php echo $id ?>/", width = 510, height = 1000);</script></textarea>
         </div>

            <a href="#" style="float:right" class="btn btn-primary">COPY</a>
            <div class="clearfix"></div>
         </div>
      </div>
      <div class="widget contact">
         <h3>Main contact</h3>
         <?php if(!empty($activity->contact_info)){
            foreach($activity->contact_info as $contact){
            ?>
            <h4><?php echo $contact->person_name; ?></h4>
            <p><a href="mailto:"><?php echo $contact->email; ?></a></p>
            <?php
            }  
         }  ?>
      </div>
   </div>
</div>
<div class="container">
   <div class="projects-title-bar">
      <h2>Similar projects</h2>
   </div>
</div>

<?php 
if(!empty($sector_codes)){
   $sector_codes = str_replace(" ", "", $sector_codes);
}

$country_codes = array();

if (!empty($activity->countries)) { 
   
   for($i = 0;$i < count($activity->countries);$i++){
      array_push($country_codes, $activity->countries[$i]->code);
   }
   $country_codes = implode(",", $country_codes);
} else {
   $country_codes = "";
}

$_REQUEST['countries__in'] = $country_codes;
$_REQUEST['sectors__in'] = $sector_codes;
?>

<div id="project-list-wrapper">
   <?php include( TEMPLATEPATH .'/ajax/project-list-ajax.php' ); ?>
</div>
<div id="project-list-pagination" style="text-align:center"></div>

<?php include( TEMPLATEPATH .'/footer-scripts.php' ); ?>



<?php 
// related requirements:
// - same county
// - same sector
?>

<script>

   Oipa.pageType = "activities";
   var selection = new OipaSelection(1, 1);
   Oipa.mainSelection = selection;

   var filter = null;
   var projectlist = null;

   <?php

   $_REQUEST['countries__in'] = $country_codes;
   $_REQUEST['sectors__in'] = $sector_codes;

   $country_codes = explode(",", $country_codes);
   foreach($country_codes as $c){
      echo "selection.countries.push({'id': '".$c."', 'name':'".$c."'});";
   }

   $sector_codes = explode(",", $sector_codes);
   foreach($sector_codes as $s){
      echo "selection.sectors.push({'id': '".$s."', 'name':'".$s."'});";
   }

   ?>

   $( document ).ready(function() {

      filter = new OipaFilters();
      Oipa.mainFilter = filter;
      filter.selection = Oipa.mainSelection;
      filter.init();

      projectlist = new OipaProjectList();
      projectlist.list_div = "#project-list-wrapper";
      projectlist.pagination_div = "#project-list-pagination";
      projectlist.selection = Oipa.mainSelection;
      projectlist.init();
      Oipa.lists.push(projectlist);
   });

</script>



<?php get_footer(); ?>