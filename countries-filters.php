<?php
$filter_options = oipa_get_filters();
?>

<div class="container">
  <div class="projects-filter"> <span class="filters-text">Filters</span>

   	<div id="regions-filter-wrapper">
      <select class="selectpicker" data-filter-name="regions" multiple data-selected-text-format="count" title='Region' data-count-selected-text="{0} of {1} regions">
      	<?php foreach($filter_options->regions as $option_key => $option){
      		echo '<option value="'.$option_key.'">'.$option->name.'</option>';
		}?>      
	  </select>
	</div>
	<div id="countries-filter-wrapper">
      <select class="selectpicker" data-filter-name="countries" multiple data-selected-text-format="count" title='Country' data-count-selected-text="{0} of {1} countries">
         <?php foreach($filter_options->countries as $option_key => $option){
      		echo '<option value="'.$option_key.'">'.$option->name.'</option>';
		}?>      
      </select>
    </div>

    <span class="filter-right-buttons">
    	<!--  <button class="btn btn-default filter-btn filter-save">Save filters</button> -->
  		<button class="btn btn-default filter-btn filter-clear-all">Clear all</button>
  	</span> 
  </div>
</div>