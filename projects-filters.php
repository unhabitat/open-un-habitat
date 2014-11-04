<?php
$filter_options = oipa_get_filters();
?>

<div class="container">
  <div class="projects-filter"> <span class="filters-text">Filters</span>

   	<div id="regions-filter-wrapper">
      <select data-size="10" name="regions[]" class="selectpickr" data-filter-name="regions" multiple data-selected-text-format="count" title='Region' data-count-selected-text="{0} of {1} regions" multiple>
      	<?php foreach($filter_options->regions as $option_key => $option){
      		echo '<option value="'.$option_key.'">'.$option->name.'</option>';
		}?>      
	  </select>
	</div>
	<div id="countries-filter-wrapper">
      <select data-size="10" name="countries[]" class="selectpickr" data-filter-name="countries" multiple data-selected-text-format="count" title='Country' data-count-selected-text="{0} of {1} countries" multiple>
         <?php foreach($filter_options->countries as $option_key => $option){
      		echo '<option value="'.$option_key.'">'.$option->name.'</option>';
		}?>      
      </select>
    </div>
    <div id="sectors-filter-wrapper">
      <select data-size="10" name="sectors[]" class="selectpickr" data-filter-name="sectors" multiple data-selected-text-format="count" title='Sector' data-count-selected-text="{0} of {1} sectors" multiple>
         <?php foreach($filter_options->sectors as $option_key => $option){
      		echo '<option value="'.$option_key.'">'.$option->name.'</option>';
		}?>   
      </select>
    </div>
    <div id="budgets-filter-wrapper">
      <select data-size="10" name="budgets[]" class="selectpickr" data-filter-name="budgets" multiple data-selected-text-format="count" title='Budget' data-count-selected-text="{0} of {1} budgets" multiple>
         <option value="0-20000">0 - 20.000</option>
         <option value="20000-100000">20.000 - 100.000</option>
         <option value="100000-1000000">100.000 - 1.000.000</option>
         <option value="1000000-5000000">1.000.000 - 5.000.000</option>
         <option value="5000000">5.000.000 ></option>
      </select>
    </div>

    <span class="filter-right-buttons">
    	<!--  <button class="btn btn-default filter-btn filter-save">Save filters</button> -->
  		<button class="btn btn-default filter-btn filter-clear-all">Clear all</button>
  	</span> 
  </div>
</div>