function customTooltips(tooltip) {

    // Tooltip Element
    var tooltipEl = $('#chartjs-tooltip');

    // Hide if no tooltip
    if (!tooltip) {
        tooltipEl.css({
            opacity: 0
        });
        return;
    }

    // Set caret Position
    tooltipEl.removeClass('above below');
    tooltipEl.addClass(tooltip.yAlign);

    // Set Text
    var text = tooltip.text.split(":");

    var tooltip_html = '<div class="chartjs-tooltip-header">'+text[0]+'</div>';
    tooltip_html += '<div class="chartjs-tooltip-value">US$ '+comma_formatted(text[1])+'</div>';

    tooltipEl.html(tooltip_html);

    // Find Y Location on page
    var top;
    if (tooltip.yAlign == 'above') {
        top = tooltip.y - tooltip.caretHeight - tooltip.caretPadding;
    } else {
        top = tooltip.y + tooltip.caretHeight + tooltip.caretPadding;
    }

    // Display, position, and set styles for font
    tooltipEl.css({
        opacity: 1,
        left: tooltip.chart.canvas.offsetLeft + tooltip.x + 'px',
        top: tooltip.chart.canvas.offsetTop + top + 'px',
        fontFamily: tooltip.fontFamily,
        fontSize: tooltip.fontSize,
        fontStyle: tooltip.fontStyle,
    });
};




function SectorPieChart(){
    this.id = "hp-sector-pie-chart";
    this.chart = null;
    this.aggregation_data = null;
    this.sector_data = null;
    this.call_counter = 0;
}

SectorPieChart.prototype.init = function(data){
    var ctx = document.getElementById(this.id).getContext("2d");
    var options = {
        segmentShowStroke : true,
        segmentStrokeColor : "#fff",
        segmentStrokeWidth : 0,
        percentageInnerCutout : 0,
        animationSteps : 100,
        animationEasing : "easeOutBounce",
        animateRotate : true,
        animateScale : false,
        showTooltips: true,
        customTooltips: customTooltips,
        tooltipEvents: ["mousemove", "touchstart", "touchmove"],
        tooltipFillColor: "#fff",
        tooltipFontFamily: "'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
        tooltipFontSize: 14,
        tooltipFontStyle: "normal",
        tooltipFontColor: "#3789D4",
        tooltipTitleFontFamily: "'Open Sans', 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
        tooltipTitleFontSize: 14,
        tooltipTitleFontStyle: "bold",
        tooltipTitleFontColor: "#fff",
        tooltipYPadding: 6,
        tooltipXPadding: 6,
        tooltipCaretSize: 0,
        tooltipCornerRadius: 0,
        tooltipXOffset: 10,
        tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",
    }

    this.chart = new Chart(ctx).Doughnut(data,options);
}


SectorPieChart.prototype.search_sector = function(sector_id){
     
    for (var i = 0; i < this.sector_data.objects.length;i++){
        if (this.sector_data.objects[i].code == sector_id){
            return this.sector_data.objects[i].name;
        }
    }
}

SectorPieChart.prototype.format_data = function(){
    var data = []
    var randomColors = randomColor({
        hue: 'blue',
        count: this.aggregation_data.length
    });

    for (var i = 0; i < this.aggregation_data.length;i++){

        var sector_id = this.aggregation_data[i].group_field;
        var value = this.aggregation_data[i].aggregation_field;
        var sector_name = this.search_sector(sector_id);

        data.push({
            value: value,
            color: randomColors[i],
            highlight: "#FFC870",
            label: sector_name
        });
    }


    return data;
}

SectorPieChart.prototype.get_sector_data = function(){
    var that = this;

    sectors_url = search_url + "sectors/?format=json&limit=300";

    jQuery.ajax({
        type: 'GET',
        url: sectors_url,
        dataType: 'json',
        success: function(data){

            that.sector_data = data;

            that.call_counter++;

            if(that.call_counter == 2){
                that.load_chart();
            }
        }
    });
}

SectorPieChart.prototype.load_chart = function(){
    
    formatted_data = this.format_data();

    sorted_formatted_data = this.sort_formatted_data_by_value(formatted_data);

    // init the chart
    this.init(formatted_data);
    
    this.load_table(sorted_formatted_data);
}

SectorPieChart.prototype.sort_formatted_data_by_value = function(data){

    data.sort(function(b,a){
        var nameA=a.value, nameB=b.value;
        if (nameA < nameB) { //sort string ascending
            return -1;
        }
        if (nameA > nameB) {
            return 1;
        }
        return 0; //default return value (no sorting)
    });
    return data;
} 

SectorPieChart.prototype.load_table = function(data){
    
    var html = "";

    for(var i = 0;i < 5;i++){
        html += "<tr><td class='hp-table-nr'>"+(i+1)+". </td><td>"+data[i].label+"</td>";
        html += "<td class='hp-table-value'>US$ "+comma_formatted(data[i].value)+"</td></tr>";
    }

    $("#hp-sector-slide table tbody").html(html);
}

SectorPieChart.prototype.get_aggregation_data = function(){

    var that = this;
    var url = search_url + "activity-aggregate-any/?format=json&group_by=sector&aggregation_key=expenditure&reporting_organisation__in=41120";

    jQuery.ajax({
        type: 'GET',
        url: url,
        dataType: 'json',
        success: function(data){
            that.aggregation_data = data;
            that.call_counter++;

            if(that.call_counter == 2){
                that.load_chart();
            }
        }
    });
}

var spc = new SectorPieChart();
spc.get_aggregation_data();
spc.get_sector_data();

















function customTooltips1(tooltip) {

    // Tooltip Element
    var tooltipEl = $('#chartjs-tooltip-1');

    // Hide if no tooltip
    if (!tooltip) {
        tooltipEl.css({
            opacity: 0
        });
        return;
    }

    // Set caret Position
    tooltipEl.removeClass('above below');
    tooltipEl.addClass(tooltip.yAlign);

    // Set Text
    var text = tooltip.text.split(":");

    var tooltip_html = '<div class="chartjs-tooltip-header">'+text[0]+'</div>';
    tooltip_html += '<div class="chartjs-tooltip-value">'+text[1]+' activities</div>';

    tooltipEl.html(tooltip_html);

    // Find Y Location on page
    var top;
    if (tooltip.yAlign == 'above') {
        top = tooltip.y - tooltip.caretHeight - tooltip.caretPadding;
    } else {
        top = tooltip.y + tooltip.caretHeight + tooltip.caretPadding;
    }

    // Display, position, and set styles for font
    tooltipEl.css({
        opacity: 1,
        left: tooltip.chart.canvas.offsetLeft + tooltip.x + 'px',
        top: tooltip.chart.canvas.offsetTop + top + 'px',
        fontFamily: tooltip.fontFamily,
        fontSize: tooltip.fontSize,
        fontStyle: tooltip.fontStyle,
    });
};




function CountryPieChart(){
    this.id = "hp-country-pie-chart";
    this.chart = null;
    this.aggregation_data = null;
    this.sector_data = null;
}

CountryPieChart.prototype.init = function(data){
    var ctx = document.getElementById(this.id).getContext("2d");
    var options = {
        segmentShowStroke : true,
        segmentStrokeColor : "#fff",
        segmentStrokeWidth : 0,
        percentageInnerCutout : 0,
        animationSteps : 100,
        animationEasing : "easeOutBounce",
        animateRotate : true,
        animateScale : false,
        showTooltips: true,
        customTooltips: customTooltips1,
        tooltipEvents: ["mousemove", "touchstart", "touchmove"],
        tooltipFillColor: "#fff",
        tooltipFontFamily: "'Open Sans', 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
        tooltipFontSize: 14,
        tooltipFontStyle: "normal",
        tooltipFontColor: "#3789D4",
        tooltipTitleFontFamily: "'Open Sans', 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
        tooltipTitleFontSize: 14,
        tooltipTitleFontStyle: "bold",
        tooltipTitleFontColor: "#fff",
        tooltipYPadding: 6,
        tooltipXPadding: 6,
        tooltipCaretSize: 0,
        tooltipCornerRadius: 0,
        tooltipXOffset: 10,
        tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",
    }

    this.chart = new Chart(ctx).Doughnut(data,options);
}

CountryPieChart.prototype.load_listeners = function(){
    $("#" + this.id).click( 
        function(evt){
            var activePoints = cpc.chart.getSegmentsAtEvent(evt); 
            if(activePoints.length > 0){
                var clicked_name = activePoints[0].label;
                var clicked_id = null;

                for(var i = 0;i < cpc.aggregation_data.objects.length;i++){
                    if (cpc.aggregation_data.objects[i].name == clicked_name){
                        clicked_id = cpc.aggregation_data.objects[i].id;
                        break;
                    }
                }

                var url = site_url + "/country/" + clicked_id + "/";

                window.location = url;
            }
        }
    ); 
}


CountryPieChart.prototype.search_sector = function(sector_id){
     
    for (var i = 0; i < this.sector_data.objects.length;i++){
        if (this.sector_data.objects[i].code == sector_id){
            return this.sector_data.objects[i].name;
        }
    }
}

CountryPieChart.prototype.format_data = function(){

    var data = [];
    var randomColors = randomColor({
        hue: 'blue',
        count: this.aggregation_data.objects.length
    });

    for (var i = 0; i < this.aggregation_data.objects.length;i++){
        data.push({
            id: this.aggregation_data.objects[i].id,
            value: this.aggregation_data.objects[i].total_projects,
            color: randomColors[i],
            highlight: "#FFC870",
            label: this.aggregation_data.objects[i].name
        });
    }

    return data;
}


CountryPieChart.prototype.load_chart = function(){
    
    // format the data
    formatted_data = this.format_data();

    sorted_formatted_data = this.sort_formatted_data_by_value(formatted_data);

    // init the chart
    this.init(formatted_data);

    this.load_table(sorted_formatted_data);

    this.load_listeners();
}

CountryPieChart.prototype.sort_formatted_data_by_value = function(data){

    data.sort(function(b,a){
        var nameA=a.value, nameB=b.value;
        if (nameA < nameB) { //sort string ascending
            return -1;
        }
        if (nameA > nameB) {
            return 1;
        }
        return 0; //default return value (no sorting)
    });
    return data;
} 

CountryPieChart.prototype.load_table = function(data){

    var html = "";
    for(var i = 0;i < 5;i++){
        html += "<tr><td class='hp-table-nr'>"+(i+1)+". </td>";
        html += "<td><a href='"+site_url+"/country/"+data[i].id+"/'>"+data[i].label+"</a></td>";
        html += "<td class='hp-table-value'><a href='"+site_url+"/country/"+data[i].id+"/'>"+data[i].value+" activities</a></td></tr>";
    }

    $("#hp-country-slide table tbody").html(html);
}

CountryPieChart.prototype.get_aggregation_data = function(){

    var that = this;
    var url = search_url + "country-activities/?format=json&reporting_organisation__in=41120";

    jQuery.ajax({
        type: 'GET',
        url: url,
        dataType: 'json',
        success: function(data){
            that.aggregation_data = data;
            that.load_chart();
        }
    });
}

var cpc = new CountryPieChart();
cpc.get_aggregation_data();






function customTooltips2(tooltip) {

    // Tooltip Element
    var tooltipEl = $('#chartjs-tooltip-2');

    // Hide if no tooltip
    if (!tooltip) {
        tooltipEl.css({
            opacity: 0
        });
        return;
    }

    // Set caret Position
    tooltipEl.removeClass('above below');
    tooltipEl.addClass(tooltip.yAlign);

    // Set Text
    var text = tooltip.text.split(":");

    var tooltip_html = '<div class="chartjs-tooltip-header">'+text[0]+'</div>';
    tooltip_html += '<div class="chartjs-tooltip-value">US$ '+comma_formatted(text[1])+'</div>';

    tooltipEl.html(tooltip_html);

    // Find Y Location on page
    var top;
    if (tooltip.yAlign == 'above') {
        top = tooltip.y - tooltip.caretHeight - tooltip.caretPadding;
    } else {
        top = tooltip.y + tooltip.caretHeight + tooltip.caretPadding;
    }

    // Display, position, and set styles for font
    tooltipEl.css({
        opacity: 1,
        left: tooltip.chart.canvas.offsetLeft + tooltip.x + 'px',
        top: tooltip.chart.canvas.offsetTop + top + 'px',
        fontFamily: tooltip.fontFamily,
        fontSize: tooltip.fontSize,
        fontStyle: tooltip.fontStyle,
    });
};



function DonorPieChart(){
    this.id = "hp-donor-pie-chart";
    this.chart = null;
    this.aggregation_data = null;
    this.sector_data = null;
}

DonorPieChart.prototype.init = function(data){
    var ctx = document.getElementById(this.id).getContext("2d");
    var options = {
        segmentShowStroke : true,
        segmentStrokeColor : "#fff",
        segmentStrokeWidth : 0,
        percentageInnerCutout : 0,
        animationSteps : 100,
        animationEasing : "easeOutBounce",
        animateRotate : true,
        animateScale : false,
        showTooltips: true,
        customTooltips: customTooltips2,
        tooltipEvents: ["mousemove", "touchstart", "touchmove"],
        tooltipFillColor: "#fff",
        tooltipFontFamily: "'Open Sans', 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
        tooltipFontSize: 14,
        tooltipFontStyle: "normal",
        tooltipFontColor: "#3789D4",
        tooltipTitleFontFamily: "'Open Sans', 'Helvetica Neue', 'Helvetica', 'Arial', sans-serif",
        tooltipTitleFontSize: 14,
        tooltipTitleFontStyle: "bold",
        tooltipTitleFontColor: "#fff",
        tooltipYPadding: 6,
        tooltipXPadding: 6,
        tooltipCaretSize: 0,
        tooltipCornerRadius: 0,
        tooltipXOffset: 10,
        tooltipTemplate: "<%if (label){%><%=label%>: <%}%><%= value %>",
    }

    this.chart = new Chart(ctx).Doughnut(data,options);
}

DonorPieChart.prototype.load_listeners = function(){
    $("#" + this.id).click( 
        function(evt){
            var activePoints = dpc.chart.getSegmentsAtEvent(evt); 
            if(activePoints.length > 0){
                var clicked_name = activePoints[0].label;
                var clicked_id = null;

                for(var i = 0;i < dpc.aggregation_data.objects.length;i++){
                    if (dpc.aggregation_data.objects[i].name == clicked_name){
                        clicked_id = dpc.aggregation_data.objects[i].id;
                        break;
                    }
                }

                var url = site_url + "/donor/" + clicked_id + "/";

                window.location = url;
            }
        }
    ); 
}

DonorPieChart.prototype.search_sector = function(sector_id){
     
    for (var i = 0; i < this.sector_data.objects.length;i++){
        if (this.sector_data.objects[i].code == sector_id){
            return this.sector_data.objects[i].name;
        }
    }
}

DonorPieChart.prototype.format_data = function(){
    var data = [];
    var randomColors = randomColor({
        hue: 'blue',
        count: this.aggregation_data.objects.length
    });

    for (var i = 0; i < this.aggregation_data.objects.length;i++){

        data.push({
            id: this.aggregation_data.objects[i].id,
            value: this.aggregation_data.objects[i].total_budget,
            color: randomColors[i],
            highlight: "#FFC870",
            label: this.aggregation_data.objects[i].name
        });
    }

    return data;
}

DonorPieChart.prototype.load_chart = function(){
    
    // format the data
    formatted_data = this.format_data();

    sorted_formatted_data = this.sort_formatted_data_by_value(formatted_data);

    // init the chart
    this.init(formatted_data);

    this.load_table(sorted_formatted_data);

    this.load_listeners();
}

DonorPieChart.prototype.sort_formatted_data_by_value = function(data){

    data.sort(function(b,a){
        var nameA=a.value, nameB=b.value;
        if (nameA < nameB) { //sort string ascending
            return -1;
        }
        if (nameA > nameB) {
            return 1;
        }
        return 0; //default return value (no sorting)
    });
    return data;
}

DonorPieChart.prototype.load_table = function(data){
    
    var html = "";

    for(var i = 0;i < 5;i++){
        html += "<tr><td class='hp-table-nr'>"+(i+1)+". </td>";
        html += "<td><a href='"+site_url+"/donor/"+data[i].id+"/'>"+data[i].label+"</a></td>";
        html += "<td class='hp-table-value'><a href='"+site_url+"/donor/"+data[i].id+"/'>US$ "+comma_formatted(data[i].value)+"</a></td></tr>";
    }

    $("#hp-donor-slide table tbody").html(html);
}

DonorPieChart.prototype.get_aggregation_data = function(){

    var that = this;
    var url = search_url + "donor-activities/?format=json&reporting_organisation__in=41120";

    jQuery.ajax({
        type: 'GET',
        url: url,
        dataType: 'json',
        success: function(data){
            that.aggregation_data = data;
            that.load_chart();
        }
    });
}

var dpc = new DonorPieChart();
dpc.get_aggregation_data();


 