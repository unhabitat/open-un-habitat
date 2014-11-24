
function OipaSelection(main, has_default_reporter){
	this.cities = [];
	this.countries = [];
	this.regions = [];
	this.sectors = [];
	this.budgets = [];
	this.indicators = [];
	this.reporting_organisations = [];
	this.start_actual_years = [];
	this.start_planned_years = [];
	this.donors = [];
	this.query = "";
	this.country = ""; // for country search
	this.region = ""; // for region search
	this.donor = "";
	this.group_by = "";
	this.url = null;

	if (main === 1){
		this.url = new OipaUrl();
	}
	if (has_default_reporter === 1){
		if (Oipa.default_organisation_id){
			this.reporting_organisations.push({"id": Oipa.default_organisation_id, "name": Oipa.default_organisation_name});
		}
	}
	
}

var Oipa = {
	default_organisation_id: null,
	default_organisation_name: null,
	pageType: null,
	mainSelection: new OipaSelection(1),
	mainFilter: null,
	maps : [],
	refresh_maps : function(){
		for (var i = 0; i < this.maps.length; i++){
			this.maps[i].refresh();
		}
	},
	visualisations : [],
	refresh_visualisations : function(){
		for (var i = 0; i < this.visualisations.length; i++){
			this.visualisations[i].refresh();
		}
	},
	lists: [],
	refresh_lists : function(){
		for (var i = 0; i < this.lists.length; i++){
			this.lists[i].refresh();
		}
	}
};

function OipaIndicatorSelection(main){
	this.cities = [];
	this.countries = [];
	this.regions = [];
	this.indicators = [];
	this.url = null;

	if (main){
		this.url = new OipaUrl();
	}
}



function OipaList(){

	this.offset = 0;
	this.limit = 10;
	this.amount = 0;
	this.order_by = null;
	this.order_asc_desc = null;
	this.selection = null;
	this.api_resource = "activity-list";

	this.list_div = "#oipa-list";
	this.pagination_div = "#oipa-list-pagination";
	this.activity_count_div = ".project-list-amount";

	this.init = function(){
		var thislist = this;

		// init pagination
		jQuery(this.pagination_div).bootpag({
		   total: 5,
		   page: 1,
		   maxVisible: 6
		}).on('page', function(event, num){
			thislist.go_to_page(num);
		});

		this.update_pagination();
		this.load_listeners();
		this.extra_init();
	}

	this.extra_init = function(){
		// override
	}

	this.refresh = function(data){
		
		if (!data){
			// get URL
			var url = this.get_url();

			// get data
			this.get_data(url);

		} else {
			// set amount of results
			this.update_list(data);
			this.update_pagination(data);
			
		}
	}

	this.reset_pars = function(){
		this.selection.query = "";
		this.offset = 0;
		this.limit = 10;
		this.amount = 0;
		this.order_by = null;
		this.order_asc_desc = null;
		this.refresh();
	}

	this.get_url = function(){
		// overriden in children, unused if called correctly
		return site_url + ajax_path + '&format=json&limit=10&call=activity-list';
	};

	this.get_data = function(url){

		var curlist = this;
		jQuery.ajax({
			type: 'GET',
			url: url,
			dataType: 'html',
			success: function(data){
				curlist.refresh(data);
			}
		});

	};

	this.update_list = function(data){
		// generate list html and add to this.list_div
		jQuery(this.list_div).html(data);
	};

	this.load_listeners = function(){
		// override
	}

	this.update_pagination = function(data){

		var total = jQuery(this.list_div + " .list-amount-input").val();
		this.amount = total;
		jQuery(this.activity_count_div).text(total);

		var total_pages = Math.ceil(this.amount / this.limit);
		var current_page = Math.ceil(this.offset / this.limit) + 1;
		jQuery(this.pagination_div).bootpag({total: total_pages});
	};

	this.go_to_page = function(page_id){
		this.offset = (page_id * this.limit) - this.limit;
		this.refresh();
	};

	this.export = function(format){

		var url = this.get_url();
		url = url.replace("format=json", "format=" + format);
		url_splitted = url.split("?");
		url = search_url + this.api_resource + "/?" + url_splitted[1];

		jQuery("#ExportListHiddenWrapper").remove();

		iframe = document.createElement('a');
        iframe.id = "ExportListHiddenWrapper";
        iframe.style.display = 'none';
        document.body.appendChild(iframe);

        var export_func_url = base_url + "/" + theme_path + "/export.php?path=" + encodeURIComponent(url);

        jQuery("#ExportListHiddenWrapper").attr("href", export_func_url);
        jQuery("#ExportListHiddenWrapper").attr("target", "_blank");
        jQuery("#ExportListHiddenWrapper").bind('click', function() {
			window.location.href = this.href;
			return false;
		});
        jQuery("#ExportListHiddenWrapper").click();
		jQuery("#download-dialog").toggle();
	}

}

function OipaMainStats(){
	this.reporting_organisation = null;

	this.get_total_projects = function(reporting_organisation){

		var url = site_url + ajax_path + '&call=total-projects';
		var stats = this;
		jQuery.ajax({
			type: 'GET',
			url: url,
			contentType: "application/json",
			dataType: 'json',
			success: function(data){
				jQuery("#project-list-amount, #total-project-amount").text(data[0].aggregation_field);
				jQuery(".total-project-amount").text(data[0].aggregation_field);
			}
		});
	};

	this.get_total_donors = function(reporting_organisation){

		var url = site_url + ajax_path + '&call=total-donors';
		var stats = this;
		jQuery.ajax({
			type: 'GET',
			url: url,
			contentType: "application/json",
			dataType: 'json',
			success: function(data){
				jQuery("#total-donor-amount").text(data.meta.total_count);
			}
		});
	};

	this.get_total_countries = function(reporting_organisation){

		var url = site_url + ajax_path + '&call=total-countries';
		var stats = this;
		jQuery.ajax({
			type: 'GET',
			url: url,
			contentType: "application/json",
			dataType: 'json',
			success: function(data){
				jQuery("#total-country-amount").text(data.meta.total_count);
			}
		});
	};

	this.get_total_budget = function(reporting_organisation){

		var url = site_url + ajax_path + '&call=homepage-total-budget';
		var stats = this;
		
		jQuery.ajax({
			type: 'GET',
			url: url,
			contentType: "application/json",
			dataType: 'json',
			success: function(data){
				jQuery("#homepage-total-budget").text("US$" + comma_formatted(data[reporting_organisation]));
			}
		});
	};

	this.load_major_programmes = function(){

	    var url = site_url + ajax_path + '&call=homepage-major-programmes';

	    jQuery.ajax({
			type: 'GET',
			url: url,
			dataType: 'json',
			success: function(data){
				jQuery.each(data.objects, function( index, value ) {
			    	jQuery("#major-programmes-tbody tr[data-sectorid='"+value.id+"'] .major-programme-amount").html(value.total_projects + " " + Drupal.t("projects"));
			    	jQuery("#major-programmes-tbody tr[data-sectorid='"+value.id+"'] .major-programme-budget").html("US$" + comma_formatted(value.total_budget));
			    });
			}
		});
	};

}

function OipaProjectList(){
	this.only_regional = false;
	this.only_country = false;
	this.api_resource = "activity-list";

	this.get_url = function(){
		var parameters = get_activity_based_parameters_from_selection(this.selection);
		
		if(Oipa.pageType == "activities"){
			project_path = "projects";
		} else {
			project_path = "projects-on-detail";
		}

		var extra_par = "";
		var desc = "";
		if (this.only_country == true){ extra_par = "&countries__code__gte=0"; }
		else if (this.only_regional == true){ extra_par = "&regions__code__gte=0"; }
		else if(this.only_global == true){ extra_par = "&activity_scope=1"; }
		else if(this.only_other == true){ extra_par = "&regions=None&countries=None&activity_scope=None"; }
		// if(Oipa.pageType != "activities" && typeof(referrer_id) !== "unknown" && typeof(referrer_par_name) !== "unknown"){ extra_par += "&" + referrer_par_name + "=" + referrer_id; }


		if(this.order_asc_desc == "desc"){ desc = "-"; }
		if(this.order_by){ extra_par += "&order_by=" + desc + this.order_by; }
		var url = site_url + ajax_path + "&format=json&limit=" + this.limit + "&offset=" + this.offset + parameters + extra_par + "&call=" + project_path;
		url = replaceAll(url, " ", "%20");
		url = replaceAll(url, "start_planned__in", "start_year_planned__in");

		return url;
	};

	
}
OipaProjectList.prototype = new OipaList();

function OipaCountryList(){
	this.api_resource = "country-activities";

	this.get_url = function(){
		var parameters = get_activity_based_parameters_from_selection(this.selection);
		var extra_par = "";
		if(this.order_by){ extra_par += "&order_by=" + this.order_by; }
		if(this.order_asc_desc){ extra_par += "&order_asc_desc=" + this.order_asc_desc; }
		return site_url + ajax_path + "&call=countries&format=json&limit=" + this.limit + "&offset=" + this.offset + parameters + extra_par;
	};

	this.update_pagination = function(data){

		var total = jQuery(this.list_div + " .list-amount-input").val();
		this.amount = total;

		var total_pages = Math.ceil(this.amount / this.limit);
		var current_page = Math.ceil(this.offset / this.limit) + 1;
		jQuery(this.pagination_div).bootpag({total: total_pages});

		jQuery("#current-grouped-list-count").html(jQuery("#grouped-list-wrapper .list-amount-input").val());
	};

	this.load_listeners = function(){

		jQuery("#grouped-list-search").keyup(function() {
			if (jQuery(this).val().length == 0){
				otherlist.selection.country = "";
				otherlist.refresh();
				Oipa.refresh_maps();
			} 
		});

		jQuery(".country-list-wrapper form").submit(function(e){
			e.preventDefault();
			otherlist.selection.country = jQuery("#grouped-list-search").val();
			otherlist.refresh();
			Oipa.refresh_maps();
		});
		
	};
}
OipaCountryList.prototype = new OipaList();

function OipaRegionList(){
	this.api_resource = "region-activities";

	this.get_url = function(){
		var parameters = get_activity_based_parameters_from_selection(this.selection);
		var extra_par = "";
		if(this.order_by){ extra_par += "&order_by=" + this.order_by; }
		if(this.order_asc_desc){ extra_par += "&order_asc_desc=" + this.order_asc_desc; }
		return site_url + ajax_path + "&call=regions&format=json&limit=" + this.limit + "&offset=" + this.offset + parameters + extra_par;
	};
}
OipaRegionList.prototype = new OipaList();

function OipaSectorList(){
	this.api_resource = "sector-activities";

	this.get_url = function(){
		var parameters = get_activity_based_parameters_from_selection(this.selection);
		var extra_par = "";
		if(this.order_by){ extra_par += "&order_by=" + this.order_by; }
		if(this.order_asc_desc){ extra_par += "&order_asc_desc=" + this.order_asc_desc; }
		return site_url + ajax_path + "&call=sectors&format=json&limit=" + this.limit + "&offset=" + this.offset + parameters + extra_par;
	};
}
OipaSectorList.prototype = new OipaList();

function OipaDonorList(){
	this.api_resource = "donor-activities";

	this.get_url = function(){
		var parameters = get_activity_based_parameters_from_selection(this.selection);
		var extra_par = "";
		if(this.order_by){ extra_par += "&order_by=" + this.order_by; }
		if(this.order_asc_desc){ extra_par += "&order_asc_desc=" + this.order_asc_desc; }
		if(this.query) {extra_par += "&query=" + this.query; }
		return site_url + ajax_path + "&call=donors&format=json&limit=" + this.limit + "&offset=" + this.offset + parameters + extra_par;
	};

	this.update_pagination = function(data){

		var total = jQuery(this.list_div + " .list-amount-input").val();
		this.amount = total;

		var total_pages = Math.ceil(this.amount / this.limit);
		var current_page = Math.ceil(this.offset / this.limit) + 1;
		jQuery(this.pagination_div).bootpag({total: total_pages});

		jQuery("#current-grouped-list-count").html(jQuery("#grouped-list-wrapper .list-amount-input").val());
	};

	this.load_listeners = function(){

		jQuery("#grouped-list-search").keyup(function() {
			if (jQuery(this).val().length == 0){
				otherlist.selection.donor = "";
				otherlist.refresh();
			} 
		});

		jQuery(".donor-list-wrapper form").submit(function(e){
			e.preventDefault();
			otherlist.selection.donor = jQuery("#grouped-list-search").val();
			otherlist.refresh();
		});
		
	};
}
OipaDonorList.prototype = new OipaList();


function OipaMap(){
	this.map = null;
	this.selection = null;
	this.slider = null;
	this.basemap = "zimmerman2014.hmpkg505";
	this.tl = null;
	this.compare_left_right = null;
	this.circles = {};
	this.markers = [];
	this.vistype = "geojson";
	this.selected_year = null;

	if (typeof standard_basemap !== 'undefined') {
		this.basemap = standard_basemap;
	}

	this.set_map = function(div_id, zoomposition){

		var mapoptions = {
			attributionControl: false,
			scrollWheelZoom: false,
			zoom: 3,
			minZoom: 2,
			maxZoom:12,
			continuousWorld: 'false'
		}

		if(zoomposition || zoomposition == null){
			mapoptions.zoomControl = false;
		}

		jQuery("#"+div_id).css("min-height", "200px");
		this.map = L.map(div_id, mapoptions).setView([3.505, -15.00], 2);

		if (zoomposition){
			new L.Control.Zoom({ position: zoomposition }).addTo(this.map);
		}

		this.tl = L.tileLayer('https://{s}.tiles.mapbox.com/v3/'+this.basemap+'/{z}/{x}/{y}.png', {
			maxZoom: 12
		}).addTo(this.map);
	};

	this.get_markers_bounds = function(){

		var minlat = 0;
		var maxlat = 0;
		var minlng = 0;
		var maxlng = 0;
		var first = true;

		jQuery.each(this.markers, function( index, value ) {

		  curlat = value._latlng.lat;
		  curlng = value._latlng.lng;

		  if (first){
				minlat = curlat;
				maxlat = curlat;
				minlng = curlng;
				maxlng = curlng;
			}

			if (curlat < minlat){
				minlat = curlat;
			}
			if (curlat > maxlat){
				maxlat = curlat;
			}
			if (curlng < minlng){
				minlng = curlng;
			}
			if (curlng > maxlng){
				maxlng = curlng;
			}

			first = false;
		});

		return [[minlat, minlng],[maxlat, maxlng]];
	}

	this.refresh = function(data){


		if (!data){
	
			// get url
			var url = this.get_url();

			// get data
			this.get_data(url);

			
		} else {
			// show data
			this.show_data_on_map(data);

		}

	};

	this.get_url = function(){

		var parameters = get_activity_based_parameters_from_selection(this.selection);
		var api_call = "activities";

		if (this.selection.group_by == "activity"){
			api_call = "activities";
		} else if(this.selection.group_by == "country"){
			api_call = "country-activities";
		} else if(this.selection.group_by == "region"){
			api_call = "region-activities";
		} else if(this.selection.group_by == "global"){
			api_call = "global-activities";
		}

		if (this.vistype == "geojson"){
			api_call = "country-geojson";
		}

		parameters += "&call=" + api_call;

		return site_url + ajax_path + '&format=json' + parameters;
	};

	this.get_data = function(url){

		if (url === null){
			this.refresh(1);
		}

		var thismap = this;

		jQuery.ajax({
			type: 'GET',
			url: url,
			contentType: "application/json",
			dataType: 'json',
			success: function(data){
				thismap.refresh(data);
			}
		});
	};
	
	
	this.delete_markers = function(){
		for (var i = 0; i < this.markers.length; i++) {
			this.map.removeLayer(this.markers[i]);
		}
	};

	this.show_data_as_markers = function(data){
		// For 0 -> 9, create markers in a circle
		for (var i = 0; i < data.objects.length; i++) {
			if (data.objects[i].id === null){ continue; }
			// Use a little math to position markers.
			// Replace this with your own code.
			if (data.objects[i].latitude !== null || data.objects[i].longitude !== null){
				curmarker = L.marker([
					data.objects[i].latitude,
					data.objects[i].longitude
				], {
					icon: L.divIcon({
						// Specify a class name we can refer to in CSS.
						className: 'country-marker-icon',
						// Define what HTML goes in each marker.
						html: data.objects[i].total_projects,
						// Set a markers width and height.
						iconSize: [36, 44],
						iconAnchor: [18, 34],
					})
				}).bindPopup('<div class="country-marker-popup-header"><a href="'+site_url+'/country/'+data.objects[i].id+'/">'+data.objects[i].name+'</a></div><table><tr><td>START DATE:</td><td>ALL</td></tr><tr><td>PROJECTS:</td><td><a href="'+site_url+'/country/'+data.objects[i].id+'/">' + data.objects[i].total_projects + '</a></td></tr><tr><td>BUDGET:</td><td>US$' + comma_formatted(data.objects[i].total_budget) + '</td></tr></table>', { minWidth: 300, maxWidth: 300, offset: L.point(173, 69), closeButton: false, className: "country-popup"})
				.addTo(this.map);

				this.markers.push(curmarker);
			}
		}
	}

	this.show_data_as_geojson = function(project_geojson){

		var thismap = this;

		// Map polygon styling
		function getColor(d) {
		    return d > 10 ? '#0f567c' :
		           d > 8  ? '#045A8D' :
		           d > 6  ? '#176792' :
		           d > 4   ? '#2476A2' :
		           d > 2   ? '#2B8CBE' :
		           d > 0    ? '#65a8cf' :
		                      'transparent';
		}

		function getWeight(d) {
		    return d > 0  ? 1 :
		                      0;
		}

		function style(feature) {
		    return {
		        fillColor: getColor(feature.properties.project_amount),
		        weight: getWeight(feature.properties.project_amount),
		        opacity: 1,
		        color: '#FFF',
		        dashArray: '',
		        fillOpacity: 0.7
		    };
		}

		function highlightFeature(e) {
		    var layer = e.target;
		    
		    if(typeof layer.feature.properties.project_amount != "undefined"){

		        layer.setStyle({
		            weight: 2,
		            fillOpacity: 0.9
		        });

		        if (!L.Browser.ie && !L.Browser.opera) {
		            layer.bringToFront();
		        }
		    }
		}

		function showPopup(e){
		    var layer = e.target;

		    var mostNorth = layer.getBounds().getNorthWest().lat;
		    var mostSouth = layer.getBounds().getSouthWest().lat;
		    var center = layer.getBounds().getCenter();
		    var heightToDraw = ((mostNorth - mostSouth) / 4) + center.lat;
		    var pointToDraw = new L.LatLng(heightToDraw, center.lng);
		    var url_parameters = "todo";
		    url_parameters = url_parameters.replace("?", "&");

		    var popup_html = "<div class='leaflet-popup-wrapper'>"
    		popup_html += "<div class='leaflet-popup-title'><a href='"+home_url+"/country/"+layer.feature.id+"/'>" + layer.feature.properties.name + "</a></div>"
    		popup_html += "<div class='leaflet-popup-budget-wrapper'><div class='leaflet-popup-budget-header'>Total projects</div><div class='leaflet-popup-budget-value'> " + layer.feature.properties.project_amount + "</div>"
    		popup_html += "</div></div>"

    		// .setContent('<div id="map-tip-header">' + layer.feature.properties.name + '</div><div id="map-tip-text">Total projects: '+ layer.feature.properties.project_amount + '</div><div id="map-tip-link"><a href="'+home_url+'/country/'+layer.feature.id+'/">View country</a></div>')

		    var popup = L.popup({'closeButton': false, 'classname': ''})
		    .setLatLng(pointToDraw)
		    .setContent(popup_html)
		    .openOn(thismap.map);
		}

		function resetHighlight(e) {
		    thismap.geojson.resetStyle(e.target);
		}

		if (this.geojson != null){
	      this.geojson.clearLayers();
	    }

	    this.geojson = L.geoJson(project_geojson, {style: style,onEachFeature: function(feature,layer) {

	      layer.on({
	          mouseover: highlightFeature,
	          mouseout: resetHighlight,
	          click: showPopup
	      });
	    }});

	    this.geojson.addTo(this.map); 

	}

	this.show_data_on_map = function(data){

		this.delete_markers();

		if(this.vistype == "markers"){
			this.show_data_as_markers(data);
		} else if (this.vistype == "geojson"){
			this.show_data_as_geojson(data);
		}
		
	};

	this.show_project_detail_locations = function(administrative_level, terms){

		if(administrative_level == "locations"){
			// show exact location markers if exact location
			// show polygon (if available) if its an adm1 region

		} else if (administrative_level == "countries"){
			// show country polygons
			var self = this;

			terms = terms.split(",");
			var response_count = 0;
			for (var i = 0;i < terms.length; i++){

				var url = site_url + ajax_path + '&format=json&call=country&country=' + terms[i];
				
				jQuery.ajax({
					type: 'GET',
					url: url,
					contentType: "application/json",
					dataType: 'json',
					success: function(data){

						// show polygon on map
						if(data.polygon){
							var pol = JSON.parse(data.polygon);
							var polygon = L.geoJson(pol, {
								style: {
									"color": "#2581D4",
									// "fillColor": "#ff7800",
								    "weight": 1,
								    "opacity": 0.85
								} 
							}).addTo(self.map);
							response_count++;

							if ((response_count + 1) == terms.length){
								var center = polygon.getBounds().getCenter();
								self.map.setView(center, 1);
								var bounds = polygon.getBounds();
								if (terms.length == 1){
									setTimeout(
									  function(){
									    self.map.fitBounds(bounds);
									  }, 800);
								}
							}
						}
					}
				});
			}

			
		} else if (administrative_level == "regions"){
			var self = this;

			terms = terms.split(",");
			var response_count = 0;
			for (var i = 0;i < terms.length; i++){

				var url = site_url + ajax_path + '&format=json&call=region&region=' + terms[i];
			
				jQuery.ajax({
					type: 'GET',
					url: url,
					contentType: "application/json",
					dataType: 'json',
					success: function(data){
						if(data.center_longlat){
							var center_longlat = geo_point_to_latlng(data.center_longlat);
							var marker = L.marker(center_longlat).addTo(self.map);
						}
					}
				});
			}
		}
	}

	this.set_bounds_and_center = function(){
		var bounds = this.get_markers_bounds();
		
		if(bounds){
			this.map.fitBounds(bounds);

			var center_lat = (bounds[0][0] + bounds[1][0]) / 2;
			var center_lng = (bounds[0][1] + bounds[1][1]) / 2;
			this.map.setView([center_lat, center_lng], 2);
		}
	}

	this.load_map_listeners = function(){
		// no default listeners, this function should be overriden.
	};

	this.update_indicator_timeline = function(){
		
		jQuery('.slider-year').removeClass('slider-active');
		
		for (var i=1950;i<2051;i++){
			var curyear = "y" + i;
			// TO DO
			jQuery.each(indicator_data, function(key, value){
				if (value.years){
					if (curyear in value.years){
						jQuery("#year-" + i).addClass("slider-active");
						return false;
					}
				}   
			});
		}
	};

	this.change_basemap = function(basemap_id){
		this.tl._url = "https://{s}.tiles.mapbox.com/v3/"+basemap_id+"/{z}/{x}/{y}.png";
		this.tl.redraw();
	};

	this.set_city = function(city_id) {
		var city = new OipaCity();
		city.id = city_id;
		var thismap = this;
		url = search_url + "cities/?format=json&id=" + city_id;


		jQuery.ajax({
			type: 'GET',
			url: url,
			contentType: "application/json",
			dataType: 'json',
			success: function(data){
				city.set_compare_data(data, thismap.compare_left_right);
			}
		});
		

		return city;
	};

	this.zoom_on_dom = function(curelem){
		var latitude = curelem.getAttribute("latitude");
		var longitude = curelem.getAttribute("longitude");
		var country_id = curelem.getAttribute("name");
		var country_name = curelem.getAttribute("country_name");

		this.map.setView([latitude, longitude], 6);
		Oipa.mainSelection.countries.push({"id": country_id, "name": country_name});
		Oipa.refresh_maps();
		Oipa.refresh_lists();
	};

	this.popup_click_project_amount = function(curelem){

		if (this.selection.group_by == "country"){

			var country_id = curelem.getAttribute("data-country-id");
			var country_name = curelem.getAttribute("data-country-name");
			Oipa.mainSelection.countries = [];
			Oipa.mainSelection.countries.push({"id": country_id, "name": country_name});
		}

		if (this.selection.group_by == "region"){

			var region_id = curelem.getAttribute("data-region-id");
			var region_name = curelem.getAttribute("data-region-name");
			Oipa.mainSelection.regions = [];
			Oipa.mainSelection.regions.push({"id": region_id, "name": region_name});
		}

		if (projectlist == null){
			// We are on the homepage, redirect to project page with parameters
			var url = new OipaUrl();
			var urlpars = url.build_parameters();

			var fullurl = site_url + "/projects/" + urlpars + "&scrollto=projectlist";
			
			window.location.href = fullurl;
		}

		Oipa.refresh_maps();
		Oipa.refresh_lists();
		jQuery("#show-all-projects-button").click();

		jQuery('html,body').animate({
	        scrollTop: jQuery("#show-all-projects-button").offset().top - 50
	    }, 500);
	};
}



function OipaCity(){
	this.id = null;
	this.name = null;
	this.latlng = null;

	this.set_compare_data = function(data, map_left_right){

		this.name = data.objects[0].name;
		this.latlng = geo_point_to_latlng(data.objects[0].location);

		if(map_left_right == "left"){
			OipaCompare.item1 = this;
		} else if (map_left_right == "right"){
			OipaCompare.item2 = this;
		}

		OipaCompare.refresh_state++;
		if (OipaCompare.refresh_state > 1){
			
			OipaCompare.refresh_state = 0;
			// refresh map
			OipaCompare.refresh_comparison();
		}
		
	}
}


function OipaFilters(){

	this.data = null;
	this.selection = null;
	this.firstLoad = true;
	this.perspective = null;
	this.filter_wrapper_div = ".projects-filter";

	this.init = function(){

		// check url parameters -> selection
		this.get_selection_from_url();

		// get url
		// var url = this.get_url();

		// get data, this will trigger process filters etc.
		// this.get_data(url);
	};

	this.save = function(dont_update_selection){
		
		if(!dont_update_selection){
			// update OipaSelection object
			this.update_selection_object();
		}

		// reload maps
		Oipa.refresh_maps();

		// reload lists
		Oipa.refresh_lists();

		// reload visualisations
		Oipa.refresh_visualisations();
	};

	this.get_selection_from_url = function(){
		var url_pars = window.location.search.substring(1);
		var selection = [];
		var query_found = false;
		var selection_found = false;
		var scrolltolist = false;
		var clickregions = false;

		if(url_pars !== ''){
			var vars = url_pars.split("&");
			for (var i=0;i<vars.length;i++) {
				var pair = vars[i].split("=");
				if (pair[0] == "query"){
					query_found = true;
				}
				if (pair[0] == "scrollto"){
					scrolltolist = true;
				}
			}

			for (var i=0;i<vars.length;i++) {
				var pair = vars[i].split("=");
				var vals = pair[1].split(",");
				for(var y=0;y<vals.length;y++){
					if (pair[0] == "regions__in" || pair[0] == "budgets__in" || pair[0] == "countries__in" || pair[0] == "sectors__in" || pair[0] == "query"){
						pair[0] = pair[0].replace("__in", "");
						jQuery('select[data-filter-name="'+pair[0]+'"]').selectpicker('val', vals[y]);
						jQuery('#'+pair[0]+'-filter-wrapper .btn.selectpicker').addClass("select-orange");
						selection_found = true;
					}
				}
			}
			
		}

		if (selection_found === true){
			this.save();
		}

		if (scrolltolist === true){

			
			jQuery('html,body').animate({
		        scrollTop: jQuery("#show-all-projects-button").offset().top - 50
		    }, 500, function(){
		    	if (clickregions == true){
		    		jQuery("#map-regional-tab").click();
		    	}
		    	
		    	jQuery("#show-all-projects-button").click();

		    	jQuery('html,body').animate({
		        	scrollTop: jQuery("#show-all-projects-button").offset().top - 50
		    	}, 500);
		    });
		}
	};

	this.update_selection_object = function(){

		// set selection as filter and load results
		this.selection.sectors = this.get_checked_by_filter("sectors");
		this.selection.countries = this.get_checked_by_filter("countries");
		this.selection.budgets = this.get_checked_by_filter("budgets");
		this.selection.regions = this.get_checked_by_filter("regions"); 
		this.selection.indicators = this.get_checked_by_filter("indicators");
		this.selection.cities = this.get_checked_by_filter("cities");
		this.selection.start_planned_years = this.get_checked_by_filter("start_planned_years");
		this.selection.donors = this.get_checked_by_filter("donors");
		
		if (!Oipa.default_organisation_id){
			this.selection.reporting_organisations = this.get_checked_by_filter("reporting_organisations");
		}
		this.fill_filter_selection_string();
	};

	this.fill_filter_selection_string = function(){

		var html = "";
		var is_selection = false;
		// region, country, sector, budget, search

		// for each of the above
		function add_to_selection_string(html, arr){
			for (var i = 0;i < arr.length;i++){
				
				if (html.length > 0){ // add comma
					html += ", " + arr[i].name;
				} else {
					html = arr[i].name;
				}
			}
			return html;
		}

		html = add_to_selection_string(html, this.selection.regions);
		html = add_to_selection_string(html, this.selection.countries);
		html = add_to_selection_string(html, this.selection.sectors);
		html = add_to_selection_string(html, this.selection.budgets);

		if (this.selection.query){
			html += "'" + this.selection.query + "'";
		}

		if (html.length > 0){
			html = "Filters selected: " + html;
		} else {
			html = "No filter selected";
		}

		$(".filters-selected-text").html(html);

	}

	this.get_selection_object = function(){
		// set selection as filter and load results
		var current_selection = new OipaSelection();

		current_selection.sectors = this.get_checked_by_filter("sectors");
		current_selection.countries = this.get_checked_by_filter("countries");
		current_selection.budgets = this.get_checked_by_filter("budgets");
		current_selection.regions = this.get_checked_by_filter("regions");
		current_selection.indicators = this.get_checked_by_filter("indicators");
		current_selection.cities = this.get_checked_by_filter("cities");
		current_selection.start_planned_years = this.get_checked_by_filter("start_planned_years");
		current_selection.donors = this.get_checked_by_filter("donors");
		
		if (!Oipa.default_organisation_id){
			current_selection.reporting_organisations = this.get_checked_by_filter("reporting_organisations");
		}
		return current_selection;
	};

	this.get_checked_by_filter = function(filtername){

		var arr = [];
		jQuery('select[data-filter-name="'+filtername+'"] option:selected').each(function(index, value){
			arr.push({"id":value.value, "name":value.text});
		});
		return arr;
	};

	this.get_url = function(selection, parameters_set){
		// override
	};

	this.get_data = function(url){
		// filters
		var filters = this;
		
		jQuery.ajax({
			type: 'GET',
			url: url,
			contentType: "application/json",
			dataType: 'json',
			success: function(data){
				filters.process_filter_options(data);
				filters.data = data;
				if (filters.firstLoad){
					filters.get_selection_from_url();
					filters.firstLoad = false;
				}
			}
		});
		
	};

	this.process_filter_options = function(data){

		var columns = 4;
		var filter = this;

		// projects page etc.

		// load filter html and implement it in the page
		jQuery.each(data, function( key, value ) {
			if (!jQuery.isEmptyObject(value)){
				if (jQuery.inArray(key, ["sectors"])){ columns = 2; }
				Oipa.mainFilter.create_filter_attributes(value, columns, key);
			}
		});

		var budgetfilters = this.get_budget_filter_options();

		// reload checked boxes
		this.initialize_filters();
	};

	this.get_budget_filter_options = function(){

		var budgetfilters = [];
		budgetfilters.push(["0-20000", { "name": "0 - 20,000" }]);
		budgetfilters.push(["20000-100000", { "name": "20,000 - 100,000" }]);
		budgetfilters.push(["100000-1000000", { "name": "100,000 - 1,000,000" }]);
		budgetfilters.push(["1000000-5000000", { "name": "1,000,000 - 5,000,000" }]);
		budgetfilters.push(["5000000", { "name": "> 5,000,000" }]);

		return budgetfilters;
	}

	this.initialize_filters = function(selection){

		if (!selection){
			var selection = this.selection;
		}

		jQuery('#map-filter-overlay input:checked').prop('checked', false);
		if (typeof selection.sectors !== "undefined") { this.init_filters_loop(selection.sectors) };
		if (typeof selection.countries !== "undefined") { this.init_filters_loop(selection.countries) };
		if (typeof selection.budgets !== "undefined") { this.init_filters_loop(selection.budgets) };
		if (typeof selection.regions !== "undefined") { this.init_filters_loop(selection.regions) };
		if (typeof selection.indicators !== "undefined") { this.init_filters_loop(selection.indicators) };
		if (typeof selection.cities !== "undefined") { this.init_filters_loop(selection.cities) };
		if (typeof selection.reporting_organisations !== "undefined") { this.init_filters_loop(selection.reporting_organisations) };
	
		//fill_selection_box();
	};

	this.init_filters_loop = function(arr){
		for(var i = 0; i < arr.length;i++){
			jQuery(':checkbox[value=' + arr[i].id + ']').prop('checked', true);
		}
	};

	this.create_filter_attributes = function(objects, columns, attribute_type){

		if (attribute_type === "indicators"){
			this.create_indicator_filter_attributes(objects, columns);
			return true;
		}

		var html = '';
		var per_col = 6;

		var sortable = [];
		for (var key in objects){
			sortable.push([key, objects[key]]);
		}
		sortable.sort(function(a, b){
			var nameA=a[1].toString().toLowerCase(), nameB=b[1].toString().toLowerCase();
			if (nameA < nameB) { //sort string ascending
				return -1; 
			}
			if (nameA > nameB) {
				return 1;
			}
			return 0; //default return value (no sorting)
		});

		var page_counter = 1;
		html += '<div class="row filter-page filter-page-1">';
	
		for (var i = 0;i < sortable.length;i++){

			if (i%per_col == 0){
				if (columns == 2){
					html += '<div class="col-md-6 col-sm-6 col-xs-12">';
				} else {
					html += '<div class="col-md-3 col-sm-3 col-xs-6">';
				}
			}

			var sortablename = sortable[i][1];
			if (columns == 4 && sortablename.length > 32){
				sortablename = sortablename.substr(0,28) + "...";
			} else if (columns == 3 && sortablename.length > 40){
				sortablename = sortablename.substr(0,36) + "...";
			}

			html += '<div class="checkbox">';
			html += '<label><input type="checkbox" value="'+ sortable[i][0] +'" id="'+sortable[i][1].toString().replace(/ /g,'').replace(',', '').replace('&', '').replace('%', 'perc')+'" name="'+sortable[i][1]+'" />'+sortablename+'</label></div>';
	
			if (i%per_col == (per_col - 1)){
				html += '</div>';
			}
			if ((i + 1) > ((page_counter * (per_col * columns))) - 1) { 
		
				html += '</div>';
				page_counter = page_counter + 1;
				html += '<div class="row filter-page filter-page-' + page_counter + '">';
			}
		}

		/// if paginated, close the pagination.
		if (page_counter > 1){
			html += '</div>';
		}

		// get pagination attributes and add both pagination + filter options to div
		jQuery("#"+attribute_type+"-pagination").html(this.paginate(1, page_counter));
		jQuery("#"+attribute_type+"-filters").html(html);
		this.load_paginate_listeners(attribute_type, page_counter);
		this.update_selection_after_filter_load();
	};

	this.update_selection_after_filter_load = function(){
		// TO DO
		// we know the selection
		// we know the filters
		// set checked if selection option still exists
		// remove from filters if not
	};

	this.paginate = function(cur_page, total_pages){

		// range of num links to show
		var range = 2;
		var paging_block = "";

		if (cur_page == 1){ paging_block += '<a href="#" class="pagination-btn-previous btn-prev"></a>'; } 
		else { paging_block += '<a href="#" class="pagination-btn-previous btn-prev">&lt; previous</a>'; }
		paging_block += "<ul>";

		if (cur_page > (1 + range)){ paging_block += "<li><a href='#'>1</a></li>"; }
		if (cur_page > (2 + range)){ paging_block += "<li>...</li>"; }

		// loop to show links to range of pages around current page
		for (var x = (cur_page - range); x < ((cur_page + range) + 1); x++) { 
		   // if it's a valid page number...
		   if ((x > 0) && (x <= total_pages)) {
			  if (x == cur_page) { paging_block += "<li class='active'><a>"+x+"</a></li>"; } 
			  else { paging_block += "<li><a href='#'>"+x+"</a></li>"; } // end else
		   } // end if 
		} // end for

		if(cur_page < (total_pages - (1 + range))){ paging_block += "<li>...</li>"; }
		if(cur_page < (total_pages - range)){ paging_block += "<li><a href='#' class='page'><span>"+total_pages+"</span></a></li>"; }	   
		paging_block += "</ul>";

		// if not on last page, show forward and last page links		
		if (cur_page != total_pages) { paging_block += '<a href="#" class="pagination-btn-next btn-next">next &gt;</a>'; } 
		else { paging_block += '<a href="#" class="pagination-btn-next btn-next"></a>'; } // end if
		/****** end build pagination links ******/
		
		return paging_block;
	};

	this.load_paginate_listeners = function(attribute_type, total_pages){

		// load pagination filters
		jQuery("#"+attribute_type+"-pagination ul a").click(function(e){
			e.preventDefault();
			var page_number = jQuery(this).text();
			jQuery("#"+attribute_type+"-pagination").html(Oipa.mainFilter.paginate(page_number, total_pages));
			Oipa.mainFilter.load_paginate_page(attribute_type, page_number);
			Oipa.mainFilter.load_paginate_listeners(attribute_type, total_pages);
		});

		jQuery("#"+attribute_type+"-pagination .pagination-btn-next").click(function(e){
			e.preventDefault();
			var page_number = jQuery("#"+attribute_type+"-pagination .active a").text();
			page_number = parseInt(page_number) + 1;
			jQuery("#"+attribute_type+"-pagination").html(Oipa.mainFilter.paginate(page_number, total_pages));
			Oipa.mainFilter.load_paginate_page(attribute_type, page_number);
			Oipa.mainFilter.load_paginate_listeners(attribute_type, total_pages);
		});

		jQuery("#"+attribute_type+"-pagination .pagination-btn-previous").click(function(e){
			e.preventDefault();
			var page_number = jQuery("#"+attribute_type+"-pagination .active a").text();
			page_number = parseInt(page_number) - 1;
			jQuery("#"+attribute_type+"-pagination").html(Oipa.mainFilter.paginate(page_number, total_pages));
			Oipa.mainFilter.load_paginate_page(attribute_type, page_number);
			Oipa.mainFilter.load_paginate_listeners(attribute_type, total_pages);
		});
		
	};

	this.load_paginate_page = function(attribute_type, page_number){
		// hide all pages
		jQuery("#"+attribute_type+"-filters .filter-page").hide();
		jQuery("#"+attribute_type+"-filters .filter-page-"+page_number).show();
	};

	this.reload_specific_filter = function(filter_name, data){

		if (!data){
			filters = this;

			// get selection
			selection = this.get_selection_object();

			// get data
			if (filter_name === "left-cities") { var url = this.get_url(null, "&indicators__in=" + get_parameters_from_selection(selection.indicators) + "&countries__in=" + get_parameters_from_selection(selection.left.countries) ); }
			if (filter_name === "right-cities") { var url = this.get_url(null, "&indicators__in=" + get_parameters_from_selection(selection.indicators) + "&countries__in=" + get_parameters_from_selection(selection.right.countries) ); }
			if (filter_name === "indicators") { var url = this.get_url(null, "&regions__in=" + get_parameters_from_selection(selection.regions) + "&countries__in=" + get_parameters_from_selection(selection.countries) + "&cities__in=" + get_parameters_from_selection(selection.cities) ); }
			if (filter_name === "regions") { var url = this.get_url(null, "&indicators__in=" + get_parameters_from_selection(selection.indicators) ); }
			if (filter_name === "countries") { var url = this.get_url(null, "&indicators__in=" + get_parameters_from_selection(selection.indicators) + "&regions__in=" + get_parameters_from_selection(selection.regions) ); }
			if (filter_name === "cities") { var url = this.get_url(null, "&indicators__in=" + get_parameters_from_selection(selection.indicators) + "&regions__in=" + get_parameters_from_selection(selection.regions) + "&countries__in=" + get_parameters_from_selection(selection.countries) ); }


			jQuery.ajax({
				type: 'GET',
				url: url,
				contentType: "application/json",
				dataType: 'json',
				success: function(data){
					filters.reload_specific_filter(filter_name, data);
				}
			});
			

		} else {
			// reload filters
			columns = 4;
			if (filter_name === "left-cities") { this.create_filter_attributes(data.cities, columns, 'left-cities'); }
			if (filter_name === "right-cities") { this.create_filter_attributes(data.cities, columns, 'right-cities'); }
			if (filter_name === "indicators" && Oipa.pageType == "compare") { 
				this.create_filter_attributes(data.countries, columns, 'left-countries');
				this.create_filter_attributes(data.countries, columns, 'right-countries');
				this.create_filter_attributes(data.cities, columns, 'left-cities');
				this.create_filter_attributes(data.cities, columns, 'left-cities');
			}
			if (filter_name === "regions") { this.create_filter_attributes(data.regions, 2, 'regions'); }
			if (filter_name === "countries") { this.create_filter_attributes(data.countries, columns, 'countries'); }
			if (filter_name === "cities") { this.create_filter_attributes(data.cities, columns, 'cities'); }
			if (filter_name === "indicators" && Oipa.pageType == "indicators") { this.create_filter_attributes(data.indicators, 2, 'indicators'); }

			this.initialize_filters(selection);
		}
	};


	this.reset_filters = function(){
		// $('.selectpicker').selectpicker('deselectAll');
		jQuery('.selectpickr option:selected').prop('selected', false);
		jQuery('.selectpickr').selectpicker('refresh');
		jQuery('.selectpicker').removeClass('select-orange');
		Oipa.mainFilter.selection.search = "";
		Oipa.mainFilter.selection.query = "";
		Oipa.mainFilter.selection.country = "";
		Oipa.mainFilter.selection.region = "";
		Oipa.mainFilter.save();
	}

}

function OipaProjectFilters(){

	this.get_url = function(selection, parameters_set){
		// get url from filter selection object
		var parameters = get_activity_based_parameters_from_selection(this.selection);
		var extra_par = "";
		if (this.perspective !== null){
			extra_par = "&perspective=" + this.perspective;
		}
		parameters += "&call=activity-filter-options";
		var cururl = site_url + ajax_path + "&format=json" + extra_par + parameters;	
	

		return cururl;
	};

	// create filter options of one particular filter type, objects = the options, columns = amount of columns per filter page
	function create_project_filter_attributes(objects, columns){
		var html = '';
		var per_col = 20;


		var sortable = [];
		for (var key in objects){
			if (objects[key].name === null){
				objects[key].name = "Unknown";
			}
			sortable.push([key, objects[key]]);
		}
	
		sortable.sort(function(a, b){
			var nameA=a[1].name.toString().toLowerCase(), nameB=b[1].name.toString().toLowerCase();
			if (nameA < nameB) { //sort string ascending
				return -1; 
			}
			if (nameA > nameB) {
				return 1;
			}
			return 0; //default return value (no sorting)
		});

		var page_counter = 1;
		html += '<div class="filter-page filter-page-1">';
		
		for (var i = 0;i < sortable.length;i++){

			if (i%per_col == 0){
				html += '<div class="span' + (12 / columns) + '">';
			}
			var sortablename = sortable[i][1].name;
			if (columns === 4 && sortablename.length > 32){
				sortablename = sortablename.substr(0,28) + "...";
			} else if (columns === 3 && sortablename.length > 46){
				sortablename = sortablename.substr(0,42) + "...";
			}
			var sortableamount = sortable[i][1].total.toString();

			html += '<div class="squaredThree"><div>';
			html += '<input type="checkbox" value="'+ sortable[i][0] +'" id="'+sortable[i][0]+sortable[i][1].name.toString().replace(/ /g,'').replace(',', '').replace('&', '').replace('%', 'perc')+'" name="'+sortable[i][1].name+'" />';
			html += '<label class="map-filter-cb-value" for="'+sortable[i][0]+sortable[i][1].name.toString().replace(/ /g,'').replace(',', '').replace('&', '').replace('%', 'perc')+'"></label>';
			html += '</div><div class="squaredThree-fname"><span>'+sortablename+' (' + sortableamount + ')</span></div></div>';
			if (i%per_col === (per_col - 1)){
				html += '</div>';
			}
			if ((i + 1) > ((page_counter * (per_col * columns))) - 1) {
			
			
				html += '</div>';
				page_counter = page_counter + 1;
				html += '<div class="filter-page filter-page-' + page_counter + '">';
			}

		}

		html += '<div class="filter-total-pages" name="' + page_counter + '"></div>';

		/// if paginated, close the pagination.
		if (page_counter > 1){
			html += '</div>';
		}
		
		return html;
	}
}
OipaProjectFilters.prototype = new OipaFilters();


function OipaUrl(){

	this.get_selection_from_url = function(){



		var query = window.location.search.substring(1);
			if(query !== ''){
				var vars = query.split("&");
				for (var i=0;i<vars.length;i++) {
					var pair = vars[i].split("=");
				var vals = pair[1].split(",");
				current_selection[pair[0]] = [];

				for(var y=0;y<vals.length;y++){
					if (pair[0] !== "query"){
						this[pair[0]].push({"id":vals[y], "name":"unknown"});
					} else{
						this[pair[0]].push({"id":vals[y], "name":vals[y]});
					}
				}
			}
		}
	};

	this.set_current_url = function(){
		var link = document.URL.toString().split("?")[0] + build_parameters();
		if (history.pushState) {
			history.pushState(null, null, link);
		}
	};

	this.build_parameters = function (){

		// build current url based on selection made
		var url = '';
		if (typeof Oipa.mainSelection.budgets !== "undefined") { url += this.build_current_url_add_par("budgets", Oipa.mainSelection.budgets); }
		if (typeof Oipa.mainSelection.regions !== "undefined") { url += this.build_current_url_add_par("regions", Oipa.mainSelection.regions); }
		if (typeof Oipa.mainSelection.countries !== "undefined") { url += this.build_current_url_add_par("countries", Oipa.mainSelection.countries); }
		if (typeof Oipa.mainSelection.start_planned_years !== "undefined") { url += this.build_current_url_add_par("start_planned_years", Oipa.mainSelection.start_planned_years); }
		if (typeof Oipa.mainSelection.donors !== "undefined") { url += this.build_current_url_add_par("donors", Oipa.mainSelection.donors); }

		if (url === ''){return '';}
		url = "?" + url.substring(1);

		return url;
	};

	this.build_current_url_add_par = function(name, arr, dlmtr){

		dlmtr = ",";

		if(arr.length === 0){return '';}
		var par = '&' + name + '=';
		for(var i = 0; i < arr.length;i++){

			par += arr[i].id.toString() + dlmtr;
		}
		par = par.substr(0, par.length - 1);
		return par;
	};
}

function OipaExport(vis, filetype){
	this.visualisation = vis;
	this.filetype = filetype;
	
	function render(){

	}

}

function OipaEmbed(vis){
	this.visualisation = vis;

}

var OipaSelectionBox = {
	fill_selection_box: function(){

		var html = '';
		var indicatorhtml = '';
		if ((typeof current_selection.sectors !== "undefined") && (current_selection.sectors.length > 0)) { html += fill_selection_box_single_filter("SECTORS", current_selection.sectors); }
		if ((typeof current_selection.countries !== "undefined") && (current_selection.countries.length > 0)) { html += fill_selection_box_single_filter("COUNTRIES", current_selection.countries); }
		if ((typeof current_selection.budgets !== "undefined") && (current_selection.budgets.length > 0)) { html += fill_selection_box_single_filter("BUDGETS", current_selection.budgets); }
		if ((typeof current_selection.regions !== "undefined") && (current_selection.regions.length > 0)) { html += fill_selection_box_single_filter("REGIONS", current_selection.regions); }
		if ((typeof current_selection.cities !== "undefined") && (current_selection.cities.length > 0)) { html += fill_selection_box_single_filter("CITIES", current_selection.cities); }
		if ((typeof current_selection.indicators !== "undefined") && (current_selection.indicators.length > 0)) { indicatorhtml = fill_selection_box_single_filter("INDICATORS", current_selection.indicators); }
		if ((typeof current_selection.reporting_organisations !== "undefined") && (current_selection.reporting_organisations.length > 0)) { indicatorhtml = fill_selection_box_single_filter("REPORTING_ORGANISATIONS", current_selection.reporting_organisations); }
		if ((typeof current_selection.query !== "undefined") && (current_selection.query.length > 0)) { html += fill_selection_box_single_filter("QUERY", current_selection.query); }
		jQuery("#selection-box").html(html);
		jQuery("#selection-box-indicators").html(indicatorhtml);
		this.init_remove_filters_from_selection_box();
	},
	fill_selection_box_single_filter: function(header, arr){

		var html = '<div class="select-box" id="selected-' + header.toLowerCase() + '">';
		html += '<div class="select-box-header">';
		if (header === "INDICATORS" && selected_type === "cpi"){ header = "DIMENSIONS"; }
		if (header === "QUERY"){header = "SEARCH"; }
		if (header === "REPORTING_ORGANISATIONS"){header = "REPORTING ORGANISATIONS"; }
		html += header;
		html += '</div>';

		for(var i = 0; i < arr.length; i++){
			html += '<div class="select-box-selected">';
			html += '<div id="selected-' + arr[i].id.toString().replace(/ /g,'').replace(',', '').replace('&', '').replace('%', 'perc') + '" class="selected-remove-button"></div>';

			if (arr[i].name.toString() == 'unknown'){
				arr[i].name = jQuery(':checkbox[value=' + arr[i].id + ']').attr("name");
			}

			html += '<div>' + arr[i].name + '</div>';
			if (header == "INDICATORS" || header == "DIMENSIONS"){
				html += '<div class="selected-indicator-color-filler"></div><div class="selected-indicator' + (i + 1).toString() + '-color"></div>';
			}
			html += '</div>';
		}

		html += '</div>';
		return html;
	},
	init_remove_filters_from_selection_box: function (){

		jQuery(".selected-remove-button").click(function(){
			var id = jQuery(this).attr('id');
			id = id.replace("selected-", "");
			var filtername = jQuery(this).parent().parent().attr('id');
			filtername = filtername.replace("selected-", "");
			var arr = current_selection[filtername];
			for (var i = 0; i < arr.length;i++){
				if(arr[i].id === id){
					// arr.splice(i, 1);
					jQuery('input[name="' + arr[i].name + '"]').attr('checked', false);
					break;
				}
			}
			this.save_selection();
		});
	}
};



function OipaProject(){
	this.id = "";

	this.get_url = function(){

		var url =  search_url + "activities/"+ this.id +"/?format=json";
		return url;
	}

	this.export = function(format){

		var url = this.get_url();
		url = url.replace("format=json", "format=" + format);
		jQuery("#ExportListHiddenWrapper").remove();

		iframe = document.createElement('a');
        iframe.id = "ExportListHiddenWrapper";
        iframe.style.display = 'none';
        document.body.appendChild(iframe);

        var export_func_url = template_directory + "/export.php?path=" + encodeURIComponent(url);

        jQuery("#ExportListHiddenWrapper").attr("href", export_func_url);
        jQuery("#ExportListHiddenWrapper").attr("target", "_blank");
        jQuery("#ExportListHiddenWrapper").bind('click', function() {
			window.location.href = this.href;
			return false;
		});
        jQuery("#ExportListHiddenWrapper").click();
		jQuery("#download-dialog").toggle();
	}

};

function geo_point_to_latlng(point_string){
	point_string = point_string.replace("POINT (", "");
	point_string = point_string.substring(0, point_string.length - 1);
	lnglat = point_string.split(" ");
	latlng = [lnglat[1], lnglat[0]];
	return latlng;
}

function get_parameters_from_selection(arr){

	dlmtr = ",";
	var str = '';

	if(arr.length > 0){
		for(var i = 0; i < arr.length; i++){
			str += arr[i].id + dlmtr;
		}
		str = str.substring(0, str.length-1);
	}

	return str;
}

function make_parameter_string_from_budget_selection(arr){

	var gte = '';
	var lte = '';
	var str = '';

	if(arr.length > 0){
	  gte = '99999999999';
	  lte = '0';
	  for(var i = 0; i < arr.length; i++){
		curid = arr[i].id;
		lower_higher = curid.split('-');

		if(lower_higher[0] < gte){
		  gte = lower_higher[0];
		}

		if(lower_higher.length > 1){
		  if(lower_higher[1] > lte){
			lte = lower_higher[1];
		  }
		}
	  }
	}
  
	if (gte != '' && gte != '99999999999'){
		str += '&total_budget__gt=' + gte;
	}
	if (lte != '' && lte != '0'){
		str += '&total_budget__lt=' + lte;
	}

	return str;
}

function get_indicator_parameters_from_selection(arr){
	dlmtr = ",";
	var str = '';
	var selection_type_str = "&selection_type__in=";

	if(arr.length > 0){
		for(var i = 0; i < arr.length; i++){
			str += arr[i].id + dlmtr;
			if (arr[i].selection_type){
				selection_type_str += arr[i].selection_type + dlmtr;
			}
		}
		str = str.substring(0, str.length-1);
	}

	return str + selection_type_str;
}

function make_parameter_string_from_selection(arr, parameter_name){ 

	var parameters = get_parameters_from_selection(arr);
	if (parameters !== ''){
		return "&" + parameter_name + "=" + parameters;
	} else {
		return '';
	}
}

function make_parameter_string_from_query_selection(str, parameter_name){
	if (str != ""){
		var str = "&"+parameter_name+"=" + str;
	} else {
		var str = "";
	}
	return str;
}

function get_activity_based_parameters_from_selection(selection){
	var str_region = make_parameter_string_from_selection(selection.regions, "regions__in");
	var str_country = make_parameter_string_from_selection(selection.countries, "countries__in");
	var str_sector = make_parameter_string_from_selection(selection.sectors, "sectors__in");
	var str_budget = make_parameter_string_from_budget_selection(selection.budgets);
	var str_start_year = make_parameter_string_from_selection(selection.start_planned_years, "start_planned__in");
	var str_donor = make_parameter_string_from_selection(selection.donors, "participating_organisations__organisation__code__in");
	var str_reporting_organisation = make_parameter_string_from_selection(selection.reporting_organisations, "reporting_organisation__in"); 
	var str_search = make_parameter_string_from_query_selection(selection.query, "query");
	var str_country_search = make_parameter_string_from_query_selection(selection.country, "country");
	var str_region_search = make_parameter_string_from_query_selection(selection.region, "region");
	var str_donor_search = make_parameter_string_from_query_selection(selection.donor, "donor");

	return str_region + str_country + str_sector + str_budget + str_start_year + str_donor + str_reporting_organisation + str_search + str_country_search + str_region_search + str_donor_search;
}

function comma_formatted(amount) {


	sep = ",";


	if (amount){
		return amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, sep);
	} else {
		return "-";
	}
}

function replaceAll(o,t,r,c){
	var cs = "";
	if(c===1){
		cs = "g";
	} else {
		cs = "gi";
	}
	var mp=new RegExp(t,cs);
	ns=o.replace(mp,r);
	return ns;
}
