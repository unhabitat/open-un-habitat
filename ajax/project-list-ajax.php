<?php 

if (isset($country_id)){
   $_REQUEST['countries__in'] = $country_id;
}

if (isset($donor_id)){
   $_REQUEST['donors__in'] = $donor_id;
}


oipa_generate_results($objects, $meta, null);

if(empty($objects)){
   ?>

   <div class="container projects">
   <div class="col-md-12">
      <hr>No projects found.
   </div>

   <?php
}


foreach($objects AS $idx=>$activity) {
?>

<div class="container projects">
   <div class="col-md-12">
      <hr>
   </div>
   <div class="col-md-6 project-col-2 col-md-push-3"> <span class="sector"><strong>Sector: </strong><?php if(!empty($activity->sectors)) { echo $activity->sectors[0]->name; } ?></span>
      <h2><a href="<?php echo home_url(); ?>/project/<?php echo $activity->id; ?>/"><?php if(!empty($activity->titles)) { echo $activity->titles[0]->title; } ?></a></h2>
   </div>
   <div class="col-md-3 project-col-1 col-md-pull-6">
      <ul>
         <li class="region">Region: 
            <?php 
            if(!empty($activity->regions)) { 
            $sep = ", ";
            for($i = 0;$i < count($activity->regions);$i++){
               if ($i == (count($activity->regions) - 1)){ $sep = ""; }
               echo "<a href='" . home_url() . "/countries/?regions__in=" . $activity->regions[$i]->code . "'>" . $activity->regions[$i]->name . "</a>" . $sep;
            }
         } ?>
         </li>
         <li class="state">
         <?php 
            if(!empty($activity->countries)) { 
            echo "Country: ";
            $sep = ", ";
            for($i = 0;$i < count($activity->countries);$i++){
               if ($i == (count($activity->countries) - 1)){ $sep = ""; }
               echo "<a href='" . home_url() . "/country/" . $activity->countries[$i]->code . "/'>" . $activity->countries[$i]->name . "</a>" . $sep;   
            } 
         } else {
            echo "&nbsp;";
         } ?>
         </li>
         <li class="donors">Donors: 
         <?php if(!empty($activity->participating_organisations)) { 
            $sep = ", ";
            $part_orgs = "";
            for($i = 0;$i < count($activity->participating_organisations);$i++){
            
               if ($activity->participating_organisations[$i]->role_id == "Funding"){
                  $part_orgs .= "<a href='" . home_url() . "/donor/" . $activity->participating_organisations[$i]->code . "/'>" . $activity->participating_organisations[$i]->name . "</a>" . $sep;
               }
            }
            echo substr($part_orgs, 0, -2);
         } ?></li>
         <li class="organisation">Organisation involved: <?php echo count($activity->participating_organisations); ?> </li>
      </ul>
   </div>
   <div class="col-md-3 project-col-3">
      <p><strong>Budget</strong><span class="budget">US$<span> <?php if(!empty($activity->total_budget)) { echo number_format($activity->total_budget, 0, '', ',');  } ?></span></p>
      <hr>
      <p>IATI Identifier<span class="float-right"><?php if(!empty($activity->iati_identifier)) { echo "<a href='" . home_url() . "/project/" . $activity->iati_identifier . "/'>" . $activity->iati_identifier . "</a>"; } ?></span></p>
      <hr>
      <p>Project starts<span class="float-right">
         <?php if(!empty($activity->start_planned)) { 
            $start_planned = strtotime($activity->start_planned); 
            echo date("d M. Y", $start_planned); 
         } ?>
      </span></p>
   </div>
</div>
<div class="container">
   <div class="col-md-3">&nbsp;</div>
   <div class="col-md-6"><span class="last-update">Latest Update <?php if(!empty($activity->last_updated_datetime)){ 
      $last_updated = strtotime($activity->last_updated_datetime);
      echo date("d F Y", $last_updated); 
   } ?></span></div>
   <div class="col-md-3">&nbsp;</div>
</div>

<?php 
} ?>


<input type="hidden" class="list-amount-input" value="<?php echo $meta->total_count; ?>">

