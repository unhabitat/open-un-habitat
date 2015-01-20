<?php 

$related_activities = array();

if (!empty($activity->related_activities)) { 
   
   for($i = 0;$i < count($activity->related_activities);$i++){
      array_push($related_activities, $activity->related_activities[$i]->ref);
   }
   $related_activities = implode(",", $related_activities);
} else {
   $related_activities = "";
}

$_REQUEST['id__in'] = $related_activities;

oipa_generate_results($objects, $meta, null);

if(empty($objects)){
   ?>

   <div class="col-md-12 no-related-projects">
      No related projects found.
   </div>

   <?php
}


foreach($objects AS $idx=>$related_activity) {
?>

<div class="projects">
   <div class="col-md-6 project-col-2 col-md-push-3"> <span class="sector"><strong>Sector: </strong><?php if(!empty($related_activity->sectors)) { echo $related_activity->sectors[0]->name; } ?></span>
      <h2><a href="<?php echo home_url(); ?>/project/<?php echo $related_activity->id; ?>/"><?php if(!empty($related_activity->titles)) { echo $related_activity->titles[0]->title; } ?></a></h2>
   </div>
   <div class="col-md-3 project-col-1 col-md-pull-6">
      <ul>
         <li class="region">Region: 
            <?php 
            if(!empty($related_activity->regions)) { 
            $sep = ", ";
            for($i = 0;$i < count($related_activity->regions);$i++){
               if ($i == (count($related_activity->regions) - 1)){ $sep = ""; }
               echo "<a href='" . home_url() . "/countries/?regions__in=" . $related_activity->regions[$i]->code . "'>" . $related_activity->regions[$i]->name . "</a>" . $sep;
            }
         } ?>
         </li>
         <li class="state">
         <?php 
            if(!empty($related_activity->countries)) { 
            echo "Country: ";
            $sep = ", ";
            for($i = 0;$i < count($related_activity->countries);$i++){
               if ($i == (count($related_activity->countries) - 1)){ $sep = ""; }
               echo "<a href='" . home_url() . "/country/" . $related_activity->countries[$i]->code . "/'>" . $related_activity->countries[$i]->name . "</a>" . $sep;   
            } 
         } else {
            echo "&nbsp;";
         } ?>
         </li>
         <li class="donors">Donors: 
         <?php if(!empty($related_activity->participating_organisations)) { 
            $sep = ", ";
            $part_orgs = "";
            for($i = 0;$i < count($related_activity->participating_organisations);$i++){
            
               if ($related_activity->participating_organisations[$i]->role_id == "Funding"){
                  $part_orgs .= "<a href='" . home_url() . "/donor/" . $related_activity->participating_organisations[$i]->code . "/'>" . $related_activity->participating_organisations[$i]->name . "</a>" . $sep;
               }
            }
            echo substr($part_orgs, 0, -2);
         } ?></li>
         <li class="organisation">Organisation involved: <?php echo count($related_activity->participating_organisations); ?> </li>
      </ul>
   </div>
   <div class="col-md-3 project-col-3">
      <p><strong>Budget</strong><span class="budget">US$<span> <?php if(!empty($related_activity->total_budget)) { echo number_format($related_activity->total_budget, 0, '', ',');  } ?></span></p>
      <hr>
      <p>IATI Identifier<span class="float-right"><?php if(!empty($related_activity->iati_identifier)) { echo "<a href='" . home_url() . "/project/" . $related_activity->iati_identifier . "/'>" . $related_activity->iati_identifier . "</a>"; } ?></span></p>
      <hr>
      <p>Project starts<span class="float-right">
         <?php if(!empty($related_activity->start_planned)) { 
            $start_planned = strtotime($related_activity->start_planned); 
            echo date("d M. Y", $start_planned); 
         } ?>
      </span></p>
   </div>
</div>
<div class="clear-both">
   <div class="col-md-3">&nbsp;</div>
   <div class="col-md-6"><span class="last-update">Latest Update <?php if(!empty($related_activity->last_updated_datetime)){ 
      $last_updated = strtotime($related_activity->last_updated_datetime);
      echo date("d F Y", $last_updated); 
   } ?></span></div>
   <div class="col-md-3">&nbsp;</div>
</div>
<div class="clear-both">
   <div class="col-md-12">
      <hr>
   </div>
</div>

<?php 
} ?>

