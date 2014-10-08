
// LIST PAGES

jQuery(".filter-save").click(function(){
	Oipa.mainFilter.save();
	if(projectlist !== null){
		projectlist.offset = 0;
		projectlist.refresh();
	}
	if(otherlist !== null){
		otherlist.offset = 0;
		otherlist.refresh();
	}
});

jQuery(".filter-clear-all").click(function(e){
	e.preventDefault();
	Oipa.mainFilter.reset_filters();
});

jQuery("#sort-by-budget").change(function(e){
	e.preventDefault();
	var val = jQuery(this).val();
	if (val != "0"){

		if (typeof(projectlist) !== "undefined"){
			projectlist.order_by = "total_budget";
			projectlist.order_asc_desc = val;
			projectlist.offset = 0;
			projectlist.refresh();
		} else {
			otherlist.order_by = "total_budget";
			otherlist.order_asc_desc = val;
			otherlist.offset = 0;
			otherlist.refresh();
		}
	}
});

jQuery("#sort-by-start-actual").change(function(e){
	e.preventDefault();
	var val = jQuery(this).val();
	if (val != "0"){

		if (typeof(projectlist) !== "undefined"){
			projectlist.order_by = "start_planned";
			projectlist.order_asc_desc = val;
			projectlist.offset = 0;
			projectlist.refresh();
		} else {
			otherlist.order_by = "start_planned";
			otherlist.order_asc_desc = val;
			otherlist.offset = 0;
			otherlist.refresh();
		}
	}
});






// PROJECT DETAIL PAGE

jQuery(".btn-download-data").click(function(e){
	e.preventDefault();
	var format = jQuery(this).data("format");
	var project = new OipaProject();
	project.id = jQuery(this).data("id");
	project.export(format);
});












// Custom JavaScripts

/* Bind signin | register to the bs modal */
$( document ).ready(function() {
   $( ".nav-login a" ).attr( "data-toggle", "modal" );
   $( ".nav-login a" ).attr( "data-target", ".bs-login-modal-lg" );
});

/* Make the select orange when something is selected */
$( "select.selectpickr" ).change(function() {
   count = $(this).find(':selected').size();
   ref = $(this).data('filter-name');
   Oipa.mainFilter.save();
   if(count > 0) {
      $("#"+ref+"-filter-wrapper .btn.selectpicker").addClass( "select-orange" );
   } else {
      $("#"+ref+"-filter-wrapper .btn.selectpicker").removeClass( "select-orange" );
   }
});

/* Slide-out search box on the top */
$(document).ready(function(){
	var submitIcon = $('.searchbox-icon');
	var inputBox = $('.searchbox-input');
	var searchBox = $('.searchbox');
	var isOpen = false;
	submitIcon.click(function(){
		if(isOpen == false){
			searchBox.addClass('searchbox-open');
			inputBox.focus();
			isOpen = true;
		} else {
			searchBox.removeClass('searchbox-open');
			inputBox.focusout();
			isOpen = false;
		}
	});  
	 submitIcon.mouseup(function(){
		return false;
	});
	searchBox.mouseup(function(){
		return false;
	});
	$(document).mouseup(function(){
		if(isOpen == true){
			$('.searchbox-icon').css('display','block');
			submitIcon.click();
		}
	});





  	// Handler for .ready() called.

   function load_visualisation_wrapper(vis_div){
      $('.project-vis-wrapper').hide();
      if(vis_div == "#map"){
         $('.btn-map').hide();
         $('.btn-visualisation').show();
      } else {
         $('.btn-visualisation').hide();
         $('.btn-map').show();
      }
      $(vis_div).show();
   }

   $('.bar-chart').click(function(e) {
   	e.preventDefault();
      load_visualisation_wrapper(".bar-chart-wrapper");
   });
   
   $('.scatter-plot').click(function(e) {
   	  e.preventDefault();
      load_visualisation_wrapper(".scatter-plot-wrapper");
      toggle_view("year");
   });
   
   $('.circle-package').click(function(e) {
   	e.preventDefault();
    load_visualisation_wrapper(".circle-package-wrapper");
   });
   
   $('.btn-map').hide();
   
    $('.btn-visualisation').click(function(e) {
    	e.preventDefault();
      load_visualisation_wrapper(".scatter-plot-wrapper");
      toggle_view("year");
   });
   
    $('.btn-map').click(function(e) {
    	e.preventDefault();
    	load_visualisation_wrapper("#map");
   });






});

function buttonUp(){
	var inputVal = $('.searchbox-input').val();
	inputVal = $.trim(inputVal).length;
	if( inputVal !== 0){
		$('.searchbox-icon').css('display','none');
	} else {
		$('.searchbox-input').val('');
		$('.searchbox-icon').css('display','block');
	}
}