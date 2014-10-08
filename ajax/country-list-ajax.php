<?php 

oipa_generate_country_results($country_activities);

if (isset($country_activities->objects)){
    foreach($country_activities->objects AS $idx=>$country) { ?>

    <div class="container">
      <div class="col-md-6 list-col">
         <h2><a href="<?php echo home_url(); ?>/country/<?php echo $country->id; ?>/" class="view-details"><?php echo $country->name; ?></a></h2>
      </div>
      <div class="col-md-3 list-col">
         <h2><?php echo "US$" . number_format($country->total_budget, 0, '', ','); ?></h2>
      </div>
      <div class="col-md-3 list-col">
         <h2><a href="<?php echo home_url(); ?>/country/<?php echo $country->id; ?>/" class="view-details"><?php echo $country->total_projects; ?> <?php echo 'project'; ?><?php if((int)$country->total_projects > 1){ echo "s"; } ?></a></h2>
      </div>
   </div>
   <div class="container">
      <hr>
   </div>
      
<?php }
} ?>

<input type="hidden" class="list-amount-input" value="<?php echo $country_activities->meta->total_count; ?>">




