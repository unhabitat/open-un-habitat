<?php 

function oipa_get_filters(){
	$search_url = OIPA_URL . "activity-filter-options/?format=json&reporting_organisation__in=" . DEFAULT_ORGANISATION_ID;
	$content = file_get_contents($search_url);
	$filter_options = json_decode($content);
	return $filter_options;
}

function oipa_get_activity($identifier) {
	if(empty($identifier)) return null;
	$search_url = OIPA_URL . "activities/{$identifier}/?format=json";

	$content = @file_get_contents($search_url);
	if ($content === false) { return false; }
	$activity = json_decode($content);
	return $activity;
}

function oipa_generate_results(&$objects, &$meta, $perspective = null){
	
	$search_url = OIPA_URL . "activity-list/?format=json";
    $search_url = oipa_filter_request($search_url);

    if ($perspective){ 
    	if ($perspective == "country"){
    		$search_url .= "&countries__code__gte=0";
    	} else if ($perspective == "region"){
    		$search_url .= "&regions__code__gte=0";
    	} else if ($perspective == "global"){
    		$search_url .= "&countries=None&regions=None";
    	}
    }

    $search_url = str_replace('start_planned__in', 'start_year_planned__in', $search_url);

    if(!isset($_REQUEST['order_by'])) {
		$search_url .= "&order_by=start_planned";
		$search_url .= "&order_by_asc_desc=desc";
	}

	$content = file_get_contents($search_url);
	$result = json_decode($content);
	$meta = $result->meta;
	$objects = $result->objects;
}

function oipa_generate_regional_results(&$objects, &$meta, $region_id, $offsetpar = ""){

	$search_url = OIPA_URL . "activity-list/?format=json&limit=10&regions__in=" . $region_id;
	$search_url .= "&reporting_organisation__in=" . DEFAULT_ORGANISATION_ID;
	$content = file_get_contents($search_url);
	$result = json_decode($content);
	$meta = $result->meta;
	$objects = $result->objects;
}

function oipa_generate_country_results(&$country_activities){

	$search_url = OIPA_URL . "country-activities/?format=json";
    $search_url = oipa_filter_request($search_url);
	$content = file_get_contents($search_url);
	$country_activities = json_decode($content);
}

function oipa_generate_region_results(&$region_activities){

	$search_url = OIPA_URL . "region-activities/?format=json";
    $search_url = oipa_filter_request($search_url);
	$content = file_get_contents($search_url);
	$region_activities = json_decode($content);
}

function oipa_generate_sector_results(&$sector_activities){

	$search_url = OIPA_URL . "sector-activities/?format=json";
    $search_url = oipa_filter_request($search_url);
	$content = file_get_contents($search_url);
	$sector_activities = json_decode($content);
}

function oipa_generate_donor_results(&$donor_activities){

	$search_url = OIPA_URL . "donor-activities/?format=json";
    $search_url = oipa_filter_request($search_url);
	$content = file_get_contents($search_url);
	$donor_activities = json_decode($content);
}

function oipa_get_donor($donor_id, &$donor_info){
	$search_url = OIPA_URL . "donor-activities/?format=json&donors__in=" . $donor_id;
	$search_url .= "&reporting_organisation__in=" . DEFAULT_ORGANISATION_ID;
	$search_url = str_replace(" ", "%20", $search_url);
	$content = file_get_contents($search_url);
	$donor_info = json_decode($content);
}

function oipa_get_country($country_id, &$country_info, &$country){
	$search_url = OIPA_URL . "country-activities/?format=json&countries__in=" . $country_id;
	$search_url .= "&reporting_organisation__in=" . DEFAULT_ORGANISATION_ID;
	$content = file_get_contents($search_url);
	$country_info = json_decode($content);
	$country_url = OIPA_URL . "countries/" . $country_id . "/?format=json";
	$country_content = file_get_contents($country_url);
	$country = json_decode($country_content);
}

function oipa_get_region($region_id, &$region_info, &$region){
	$search_url = OIPA_URL . "region-activities/?format=json&regions__in=" . $region_id;
	$search_url .= "&reporting_organisation__in=" . DEFAULT_ORGANISATION_ID;
	$content = file_get_contents($search_url);
	$region_info = json_decode($content);
	$region_url = OIPA_URL . "regions/" . $region_id . "/?format=json";
	$region_content = file_get_contents($region_url);
	$region = json_decode($region_content);
}

function oipa_get_sector($sector_id, &$sector_info){
	$search_url = OIPA_URL . "sector-activities/?format=json&sectors__in=" . $sector_id;
	$search_url .= "&reporting_organisation__in=" . DEFAULT_ORGANISATION_ID;
	$content = file_get_contents($search_url);
	$sector_info = json_decode($content);
}

function oipa_generate_link_parameters($filter_to_unset=null){

	parse_str($_SERVER['QUERY_STRING'], $vars);
	if(isset($filter_to_unset)){
		if(isset($_GET[$filter_to_unset])){ unset($vars[$filter_to_unset]); }
	}
	if(isset($_GET['offset'])){ unset($vars['offset']); }
	if(isset($_GET['action'])){ unset($vars['action']); }
	$parameters = http_build_query($vars);
	$parameters = str_replace("%2C", ",", $parameters);
	if ($parameters){ $parameters = "&" . $parameters; }
	return $parameters;
}


function oipa_filter_request($search_url){

	if(isset($_REQUEST['id__in'])) {
		$search_url .= "&id__in=" . $_REQUEST['id__in'];
	}

    if(isset($_REQUEST['countries__in'])) {
		$search_url .= "&countries__in=" . $_REQUEST['countries__in'];
	}

	if(isset($_REQUEST['countries'])) {
		$search_url .= "&countries=" . $_REQUEST['countries'];
	}

	if(isset($_REQUEST['country_id'])) {
		$search_url .= "&countries__in=" . $_REQUEST['country_id'];
	}

	if(isset($_REQUEST['regions__in'])) {
		$search_url .= "&regions__in=" . $_REQUEST['regions__in'];
	}

	if(isset($_REQUEST['regions'])) {
		$search_url .= "&regions=" . $_REQUEST['regions'];
	}

	if(isset($_REQUEST['region_id'])) {
		$search_url .= "&regions__in=" . $_REQUEST['region_id'];
	}

	if(isset($_REQUEST['sectors__in'])) {
		$search_url .= "&sectors__in=" . $_REQUEST['sectors__in'];
	}

	if(isset($_REQUEST['sector_id'])) {
		$search_url .= "&sectors__in=" . $_REQUEST['sector_id'];
	}

	if(isset($_REQUEST['donors__in'])) {
		$search_url .= "&participating_organisations__organisation__code__in=" . $_REQUEST['donors__in'];
	}

	if(isset($_REQUEST['donor_id'])) {
		$search_url .= "&participating_organisations__organisation__code__in=" . $_REQUEST['donor_id'];
	}

	if(isset($_REQUEST['participating_organisations__organisation__code__in'])) {
		$search_url .= "&participating_organisations__organisation__code__in=" . $_REQUEST['participating_organisations__organisation__code__in'];
	}

	if(isset($_REQUEST['activity_scope'])) {
		$search_url .= "&activity_scope=" . $_REQUEST['activity_scope'];
	}

	if(isset($_REQUEST['start_actual__in'])) {
		$search_url .= "&start_actual__in=" . $_REQUEST['start_actual__in'];
	}

	if(isset($_REQUEST['start_planned__in'])) {
		$search_url .= "&start_planned__in=" . $_REQUEST['start_planned__in'];
	}

	if(isset($_REQUEST['indicators__in'])) {
		$search_url .= "&indicators__in=" . $_REQUEST['indicators__in'];
	}

	if(isset($_REQUEST['cities__in'])) {
		$search_url .= "&cities__in=" . $_REQUEST['cities__in'];
	}

	if(isset($_REQUEST['reporting_organisations__in'])) {
		$search_url .= "&reporting_organisations__in=" . $_REQUEST['reporting_organisations__in'];
	} else {
		if (DEFAULT_ORGANISATION_ID){
			$search_url .= "&reporting_organisation__in=" . DEFAULT_ORGANISATION_ID;
		}
	}

	if(isset($_REQUEST['search'])) {
		$search_url .= "&search=" . $_REQUEST['search'];
	}

	if(isset($_REQUEST['query'])) {
		$search_url .= "&query=" . $_REQUEST['query'];
	}

	if(isset($_REQUEST['country'])) {
		$search_url .= "&country=" . $_REQUEST['country'];
	}

	if(isset($_REQUEST['region'])) {
		$search_url .= "&region=" . $_REQUEST['region'];
	}

	if(isset($_REQUEST['donor'])) {
		$search_url .= "&donor=" . $_REQUEST['donor'];
	}

	if(isset($_REQUEST['order_by'])) {
		$search_url .= "&order_by=" . $_REQUEST['order_by'];
	}

	if(isset($_REQUEST['order_by_asc_desc'])) {
		$search_url .= "&order_by_asc_desc=" . $_REQUEST['order_by_asc_desc'];
	}

	if(isset($_REQUEST['offset'])) {
		$search_url .= "&offset=" . $_REQUEST['offset'];
	}

	if(isset($_REQUEST['limit'])) {
		$search_url .= "&limit=" . $_REQUEST['limit'];
	} else {
		$search_url .= "&limit=10";
	}

	if(isset($_REQUEST['regions__code__gte'])) {
		$search_url .= "&regions__code__gte=" . $_REQUEST['regions__code__gte'];
	}

	if(isset($_REQUEST['countries__code__gte'])) {
		$search_url .= "&countries__code__gte=" . $_REQUEST['countries__code__gte'];
	}

	if(isset($_REQUEST['order_asc_desc'])){
		$search_url .= "&order_asc_desc=" . $_REQUEST['order_asc_desc'];
	}

	if(isset($_REQUEST['total_budget__gt'])){
		$search_url .= "&total_budget__gt=" . $_REQUEST['total_budget__gt'];
	}

	if(isset($_REQUEST['total_budget__lt'])){
		$search_url .= "&total_budget__lt=" . $_REQUEST['total_budget__lt'];
	}

	if(isset($_REQUEST['budgets__in'])) {
		$budget_gte = 99999999999;
		$budget_lte = 0;
		$budgets = explode(',', trim($_REQUEST['budgets__in']));
		foreach ($budgets as &$budget) {
		    $lower_higher = explode('-', $budget);
		    if($lower_higher[0] < $budget_gte){
		    	$budget_gte = $lower_higher[0];
		    }
		    if (sizeof($lower_higher) > 1) {
		    	
		    	if($lower_higher[1] > $budget_lte){
		    		$budget_lte = $lower_higher[1];
		    	}
		    }
		}

		if ($budget_gte != 99999999999){
			$search_url .= "&total_budget__gte={$budget_gte}";
		}
		if ($budget_lte != 0){
			$search_url .= "&total_budget__lte={$budget_lte}";
		}
	}
	$search_url = trim($search_url);
	$search_url = str_replace(" ", "%20", $search_url);

    return $search_url;
}




function currencyCodeToSign($currency){
	switch ($currency) {
	    case "CAD":
	        return "CAD $ ";
	        break;
	    case "DDK":
	        return "DDK kr. ";
	        break;
	    case "EUR":
	        return "EUR ";
	        break;
	    case "GBP":
	        return "GBP £ ";
	        break;
	    case "INR":
	        return "INR Rs ";
	        break;
	    case "NOK":
	        return "NOK kr. ";
	        break;
	    case "NPR":
	        return "NPR Rs ";
	        break;
	    case "NZD":
	        return "NZD $ ";
	        break;
	    case "PKR":
	        return "PKR Rs ";
	        break;
	    case "USD":
	        return "US $ ";
	        break;
	    case "ZAR":
	        return "Rand ";
	        break;

    }
}
