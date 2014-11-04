<?php 
oipa_generate_donor_results($donor_activities);
if (isset($donor_activities->objects)){
foreach($donor_activities->objects AS $idx=>$donor) { ?>

  <div class="container">
   <div class="col-md-6 list-col">
      <h2><a href="<?php echo home_url(); ?>/donor/<?php echo $donor->id; ?>/" class="view-details"><?php echo $donor->name; ?></a></h2>
   </div>
   <div class="col-md-3 list-col">
      <h2><?php echo "US$" . number_format($donor->total_budget, 0, '', '.'); ?></h2>
   </div>
   <div class="col-md-3 list-col">
      <h2><a href="<?php echo home_url(); ?>/donor/<?php echo $donor->id; ?>/" class="view-details"><?php echo $donor->total_projects; ?> <?php echo 'project'; ?><?php if((int)$donor->total_projects > 1){ echo "s"; } ?></a></h2>
   </div>
</div>
<div class="container">
   <hr>
</div>



  <?php }
} ?>

<input type="hidden" class="list-amount-input" value="<?php echo $donor_activities->meta->total_count; ?>">
