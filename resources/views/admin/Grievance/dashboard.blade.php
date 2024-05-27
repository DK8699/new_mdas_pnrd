@php
    $page_title="Grievance Dashboard";
@endphp

@extends('layouts.app_admin_griev')

@section('custom_css')

  <link rel="stylesheet" href="{{asset('mdas_assets/OwlCarousel/dist/assets/owl.carousel.min.css')}}">
	<link rel="stylesheet" href="{{asset('mdas_assets/OwlCarousel/dist/assets/owl.theme.default.min.css')}}">

    <style>
	   #chartdiv {
		  width: 100%;
		  height: 500px;
		}
	    
	    #individualGriev {
		  width: 100%;
		  height: 500px;
		}
	    #schemeWise {
		  width: 100%;
		  height: 500px;
		}
	    .back {
        webkit-box-shadow: 1px 3px 3px 3px rgba(209,209,209,1);
        -moz-box-shadow: 1px 3px 3px 3px rgba(209,209,209,1);
        box-shadow: 1px 3px 15px 2px rgba(209,209,209,1);
        background-color:rgba(255, 255, 255, 0.90);
        padding:15px 25px;
	   margin:25px 0px;
        min-height:555px;
    		}
	    .mbt-40{
		    margin-top: 40px;
		    margin-bottom: 80px;
	    }
	     h3 {
        font-weight:bold;
    		}
	    .card{
		  background-color:rgba(255, 255, 255, 0.90); 
		  box-shadow: 1px 3px 15px 2px rgba(209,209,209,1);  
	    }
	    .item{
	    height: 20rem;
	    background: #cddad6;
	    padding: 1rem;
	    width: 20rem;
	    box-shadow: 1px 3px 15px 2px rgba(209,209,209,1);	    
   
	    }
	    
	    
	   
	    
    </style>
@endsection

@section('content')

<div class="container mbt-40">
	 <div class="row">
            <div class="col-lg-12" style="text-align:center;">
                <div class="back" style="width:100%;">
                    <h3>Media Grievance</h3>
					<div id="chartdiv"></div>
					
					<div class="row">
						<div class="col-md-3 col-sm-3 col-xs-12">
							<div class="card">
							  <div class="card-body">
								  <div class="row">
									  <div class="col-md-6 col-sm-6 col-xs-6">
											<h4 class="card-title">Total Grievance</h4>
										  <hr style="width:100%;text-align:left; color: #333;border-width: 2px;margin-left:0">
											<h3 class="card-subtitle mb-2">{{$data['totMediaGriev']}} </h3>
									  </div>
									  
									  <div class="col-md-6 col-sm-6 col-xs-6">
										  <i class="fa fa-list fa-4x" style="color:#822829;" aria-hidden="true"></i>
									  </div>
								  </div>
							  </div>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12">
							<div class="card">
							  <div class="card-body">
								<div class="row">
									  <div class="col-md-6 col-sm-6 col-xs-6">
											<h4 class="card-title">Resolved Grievance</h4>
										  <hr style="width:100%;text-align:left; color: #333;border-width: 2px;margin-left:0">
											<h3 class="card-subtitle mb-2">{{$data['resolvedMediaGriev']}} </h3>
									  </div>
									  
									  <div class="col-md-6 col-sm-6 col-xs-6">
										  <i class="fa fa-check-square-o fa-4x" style="color:#822829;" aria-hidden="true"></i>
									  </div>
									   
								</div>
							 </div>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12">
							<div class="card">
							  <div class="card-body">
							   <div class="row">
									  <div class="col-md-6 col-sm-6 col-xs-6">
											<h4 class="card-title">Pending Grievance</h4>
										  <hr style="width:100%;text-align:left; color: #333;border-width: 2px;margin-left:0">
											<h3 class="card-subtitle mb-2">{{$data['pendingMediaGriev']}} </h3>
									  </div>
									  
									  <div class="col-md-6 col-sm-6 col-xs-6">
										  <i class="fa fa-file-archive-o fa-4x"  style="color:#822829;" aria-hidden="true"></i>
									  </div>
									   
								  </div>
							  </div>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12">
							<div class="card">
							  <div class="card-body">
								<div class="row">
									  <div class="col-md-6 col-sm-6 col-xs-6">
											<h4 class="card-title">Resolved Percentage</h4>
										  <hr style="width:100%;text-align:left; color: #333;border-width: 2px;margin-left:0">
											<h3 class="card-subtitle mb-2">{{round($data['resolvedPercent'],2)}} </h3>
									  </div>
									  
									  <div class="col-md-6 col-sm-6 col-xs-6">
										 <i class="fa fa-percent fa-4x"  style="color:#822829;" aria-hidden="true"></i>
									  </div>
									   
								  </div>
							  </div>
							</div>
						</div>
					</div>
					
					 <br>
				 <div class="row">
				 		<div class="col-md-12 col-sm-12 col-xs-12">
							<span class="pull-right"><a target="_blank" href="{{route('admin.Grievance.Type.report',('MEDIA'))}}">More details <i class="fa fa-arrow-right" aria-hidden="true"></i></a></span>
					 	</div>
				 </div>
					
				</div>
		 </div>
	</div>
	
	<div class="row">
            <div class="col-lg-12" style="text-align:center;">
                <div class="back" style="width:100%;">
                    <h3>Individual Grievance</h3>
					<div id="individualGriev"></div>
					
					<div class="row">
						<div class="col-md-3 col-sm-3 col-xs-12">
							<div class="card">
							  <div class="card-body">
								  <div class="row">
									  <div class="col-md-6 col-sm-6 col-xs-6">
											<h4 class="card-title">Total Grievance</h4>
										  <hr style="width:100%;text-align:left; color: #333;border-width: 2px;margin-left:0">
											<h3 class="card-subtitle mb-2">{{$data['totIndividualGriev']}} </h3>
									  </div>
									  
									  <div class="col-md-6 col-sm-6 col-xs-6">
										  <i class="fa fa-list fa-4x" style="color:#822829;" aria-hidden="true"></i>
									  </div>
									   
								  </div>
							  </div>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12">
							<div class="card">
							  <div class="card-body">
								<div class="row">
									  <div class="col-md-6 col-sm-6 col-xs-6">
											<h4 class="card-title">Resolved Grievance</h4>
										  <hr style="width:100%;text-align:left; color: #333;border-width: 2px;margin-left:0">
											<h3 class="card-subtitle mb-2">{{$data['resolvedIndividualGriev']}} </h3>
									  </div>
									  
									  <div class="col-md-6 col-sm-6 col-xs-6">
										  <i class="fa fa-check-square-o fa-4x" style="color:#822829;" aria-hidden="true"></i>
									  </div>
									   
								</div>
							 </div>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12">
							<div class="card">
								  <div class="card-body">
								   <div class="row">
										  <div class="col-md-6 col-sm-6 col-xs-6">
												<h4 class="card-title">Pending Grievance</h4>
											  <hr style="width:100%;text-align:left; color: #333;border-width: 2px;margin-left:0">
												<h3 class="card-subtitle mb-2">{{$data['pendingIndividualGriev']}} </h3>
										  </div>

										  <div class="col-md-6 col-sm-6 col-xs-6">
											  <i class="fa fa-file-archive-o fa-4x"  style="color:#822829;" aria-hidden="true"></i>
										  </div>

									  </div>
								  </div>
							</div>
						</div>
						<div class="col-md-3 col-sm-3 col-xs-12">
							<div class="card">
							  <div class="card-body">
								<div class="row">
									  <div class="col-md-6 col-sm-6 col-xs-6">
											<h4 class="card-title">Resolved Percentage</h4>
										  <hr style="width:100%;text-align:left; color: #333;border-width: 2px;margin-left:0">
											<h3 class="card-subtitle mb-2">{{round($data['resolvedIndivdualPercent'],2)}} </h3>
									  </div>
									  
									  <div class="col-md-6 col-sm-6 col-xs-6">
										 <i class="fa fa-percent fa-4x"  style="color:#822829;" aria-hidden="true"></i>
									  </div>
									   
								  </div>
							  </div>
							</div>
						</div>
					</div>
					
					<br>
					 <div class="row">
							<div class="col-md-12 col-sm-12 col-xs-12">
								<span class="pull-right"><a target="_blank" href="{{route('admin.Grievance.Type.report',('INDIVIDUAL'))}}">More details <i class="fa fa-arrow-right" aria-hidden="true"></i></a></span>
							</div>
					 </div>					
									
				</div>
			</div>
	</div>
	
	<div class="row">
            <div class="col-lg-12" style="text-align:center;">
                <div class="back" style="width:100%;">
                    <h3>Scheme Wise Analaysis</h3>
					<div id="schemeWise"></div>
				 		<div class="owl-carousel owl-theme">
							@foreach($scheme as $li)
							<a target="_blank" href="{{route('admin.Grievance.Scheme.report', Crypt::encrypt($li->id))}}">
							<div class="col-md-4 col-sm-4 col-xs-12 item">
								<h4>{{$li->scheme_name}}</h4>
								 <hr style="width:100%;text-align:left; color: #333;border-width: 2px;margin-left:0">
								<div class="row">
								<div class="col-md-12 col-sm-12 col-xs-12">
									<span style="font-weight: 700; color: #3d4661;">MEDIA GRIEVANCE: @if(isset($data['schemeWiseMedia'][$li->id])){{$data['schemeWiseMedia'][$li->id]}}@else{{0}}@endif
										 </span>
								</div><br><br><br>
								<div class="col-md-12 col-sm-12 col-xs-12">
									<span style="font-weight: 700; color: #902c2d;">INDIVIDUAL GRIEVANCE: @if(isset($data['schemeWiseIndividual'][$li->id])){{$data['schemeWiseIndividual'][$li->id]}}@else{{0}}@endif</span>
								</div>
								</div>
							</div>
							</a>
							 @endforeach
					</div>
			  </div>
		  </div>
		
	</div>
		
</div> 



@endsection

@section('custom_js')
	<!-- Resources -->
	<script src="{{ asset('mdas_assets/amcharts4/core.js') }}"></script>
	<script src="{{ asset('mdas_assets/amcharts4/charts.js') }}"></script>
	<script src="{{ asset('mdas_assets/amcharts4/themes/material.js') }}"></script>
	<script src="{{ asset('mdas_assets/amcharts4/themes/animated.js') }}"></script>

	<script src="{{ asset('mdas_assets/amcharts4/themes/dataviz.js') }}"></script>

	<script src="{{asset('mdas_assets/OwlCarousel/dist/owl.carousel.min.js')}}"></script>

<script>
	$('.owl-carousel').owlCarousel({
    loop:true,
    margin:10,
    nav:true,
    autoplay:true,
    autoplayTimeout:3000,
    autoplayHoverPause:true,
    responsive:{
        0:{
            items:1
        },
        600:{
            items:3
        },
        1000:{
            items:5
        }
    }
})

	
//MEDIA GRIEVANCE GRAPH	 
	
	am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_dataviz);
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("chartdiv", am4charts.XYChart3D);

// Add data
	chart.data = [
		{
                    "districts": "STATE",
                       "Print Media": @if(isset($districtWiseData[0][1])){{$districtWiseData[0][1]}}@else{{0}}@endif,
                        "Electronic Media": @if(isset($districtWiseData[0][2])){{$districtWiseData[0][2]}}@else{{0}}@endif,
                        "Social Media": @if(isset($districtWiseData[0][3])){{$districtWiseData[0][3]}}@else{{0}}@endif
               
               },
           @foreach($district as $list)
                    {
                    "districts": "{{ $list->district_name }}",
                        "Print Media": @if(isset($districtWiseData[$list->id][1])){{$districtWiseData[$list->id][1]}}@else{{0}}@endif,
                        "Electronic Media": @if(isset($districtWiseData[$list->id][2])){{$districtWiseData[$list->id][2]}}@else{{0}}@endif,
                        "Social Media": @if(isset($districtWiseData[$list->id][3])){{$districtWiseData[$list->id][3]}}@else{{0}}@endif
               
                    },
            @endforeach
];

// Create axes
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "districts";
categoryAxis.title.text = "Districts";
categoryAxis.title.fontWeight = "bold";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 20;
 categoryAxis.renderer.cellStartLocation = 0.1;
 categoryAxis.renderer.cellEndLocation = 0.9;
 categoryAxis.renderer.labels.template.horizontalCenter = "right";
 categoryAxis.renderer.labels.template.verticalCenter = "middle";
 categoryAxis.renderer.labels.template.rotation = 280;
 categoryAxis.tooltip.disabled = true;
 categoryAxis.renderer.minHeight = 35;	

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.title.text = "Number";
valueAxis.title.fontWeight = "bold";
valueAxis.renderer.labels.template.adapter.add("text", function(text) {
  return text + "";
});

// Create series
var series = chart.series.push(new am4charts.ColumnSeries3D());
series.dataFields.valueY = "Print Media";
series.dataFields.categoryX = "districts";
series.name = "Print Media";
series.clustered = false;
series.columns.template.tooltipText = "Print Media: [bold]{valueY}[/]";
series.columns.template.fillOpacity = 0.9;

var series2 = chart.series.push(new am4charts.ColumnSeries3D());
series2.dataFields.valueY = "Electronic Media";
series2.dataFields.categoryX = "districts";
series2.name = "Electronic Media";
series2.clustered = false;
series2.columns.template.tooltipText = "Electronic Media: [bold]{valueY}[/]";
series.columns.template.fillOpacity = 0.9;
	
var series3 = chart.series.push(new am4charts.ColumnSeries3D());
series3.dataFields.valueY = "Social Media";
series3.dataFields.categoryX = "districts";
series3.name = "Social Media";
series3.clustered = false;
series3.fill = am4core.color("green");
series3.columns.template.tooltipText = "Social Media: [bold]{valueY}[/]";
	
	// Create series
            function createSeries(field, name, stacked) {
            var series = chart.series.push(new am4charts.ColumnSeries());
            series.dataFields.valueY = field;
            series.dataFields.categoryX = "districts";
            series.name = name;
            series.columns.template.tooltipText = "{name}: [bold]{valueY}[/]";
            series.stacked = true;
            series.columns.template.width = am4core.percent(95);
            }
            // Add legend
            chart.legend = new am4charts.Legend();

            // Enable export
            chart.exporting.menu = new am4core.ExportMenu();

}); // end am4core.ready()

	    
//INDIVIDUAL GRIEVANCE GRAPH
	    
	am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("individualGriev", am4charts.XYChart3D);

// Add data
chart.data = [
	
	  {
                    "districts": "STATE",
                    "number": @if(isset($districtWiseIndividualGrievData[0])){{$districtWiseIndividualGrievData[0]}}@else{{0}}@endif,
               
       },
	@foreach($district as $list)
                    {
                    "districts": "{{ $list->district_name }}",
                    "number": @if(isset($districtWiseIndividualGrievData[$list->id])){{$districtWiseIndividualGrievData[$list->id]}}@else{{0}}@endif,
               
                    },
            @endforeach
	];

let categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "districts";
categoryAxis.title.text = "Districts";
categoryAxis.title.fontWeight = "bold";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 20;
categoryAxis.renderer.cellStartLocation = 0.1;
categoryAxis.renderer.cellEndLocation = 0.9;
categoryAxis.renderer.labels.template.horizontalCenter = "right";
categoryAxis.renderer.labels.template.verticalCenter = "middle";
categoryAxis.renderer.labels.template.rotation = 280;
categoryAxis.tooltip.disabled = true;
categoryAxis.renderer.minHeight = 35;	
		
let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.title.text = "Number";
valueAxis.title.fontWeight = "bold";

// Create series
var series = chart.series.push(new am4charts.ColumnSeries3D());
series.dataFields.valueY = "number";
series.dataFields.categoryX = "districts";
series.name = "Number";
series.tooltipText = "{categoryX}: [bold]{valueY}[/]";
series.columns.template.fillOpacity = .8;

var columnTemplate = series.columns.template;
columnTemplate.strokeWidth = 2;
columnTemplate.strokeOpacity = 1;
columnTemplate.stroke = am4core.color("#FFFFFF");

columnTemplate.adapter.add("fill", function(fill, target) {
  return chart.colors.getIndex(target.dataItem.index);
})

columnTemplate.adapter.add("stroke", function(stroke, target) {
  return chart.colors.getIndex(target.dataItem.index);
})
// Enable export
  chart.exporting.menu = new am4core.ExportMenu();
chart.cursor = new am4charts.XYCursor();
chart.cursor.lineX.strokeOpacity = 0;
chart.cursor.lineY.strokeOpacity = 0;

}); // end am4core.ready()
	    
		
	am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_dataviz);
		am4core.useTheme(am4themes_animated);
		// Themes end

		// Create chart instance
		var chart = am4core.create("schemeWise", am4charts.XYChart3D);

		// Add data
			chart.data = [
				 @foreach($scheme as $list)
						{
						"schemes": "{{ $list->scheme_name }}",
						    "Media Grievance":@if(isset($data['schemeWiseMedia'][$list->id])){{$data['schemeWiseMedia'][$list->id]}}@else{{0}}@endif,
						    "Individual Grievance":@if(isset($data['schemeWiseIndividual'][$list->id])){{$data['schemeWiseIndividual'][$list->id]}}@else{{0}}@endif
						},
				  @endforeach
		];

		// Create axes
		var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
		categoryAxis.dataFields.category = "schemes";
		categoryAxis.title.text = "Schemes";
		categoryAxis.title.fontWeight = "bold";
		categoryAxis.renderer.grid.template.location = 0;
		categoryAxis.renderer.minGridDistance = 20;
		 categoryAxis.renderer.cellStartLocation = 0.1;
		 categoryAxis.renderer.cellEndLocation = 0.9;
		 categoryAxis.renderer.labels.template.horizontalCenter = "right";
		 categoryAxis.renderer.labels.template.verticalCenter = "middle";
		 categoryAxis.renderer.labels.template.rotation = 280;
		 categoryAxis.tooltip.disabled = true;
		 categoryAxis.renderer.minHeight = 35;	

		var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
		valueAxis.title.text = "Number";
		valueAxis.title.fontWeight = "bold";
		valueAxis.renderer.labels.template.adapter.add("text", function(text) {
		  return text + "";
		});

		// Create series
		var series = chart.series.push(new am4charts.ColumnSeries3D());
		series.dataFields.valueY = "Media Grievance";
		series.dataFields.categoryX = "schemes";
		series.name = "Media Grievance";
		series.clustered = false;
		series.columns.template.tooltipText = "Media Grievance: [bold]{valueY}[/]";
		series.columns.template.fillOpacity = 0.9;

		var series2 = chart.series.push(new am4charts.ColumnSeries3D());
		series2.dataFields.valueY = "Individual Grievance";
		series2.dataFields.categoryX = "schemes";
		series2.name = "Individual Grievance";
		series2.clustered = false;
		series2.columns.template.tooltipText = "Individual Grievance: [bold]{valueY}[/]";
		series.columns.template.fillOpacity = 0.9;

			// Create series
				  function createSeries(field, name, stacked) {
				  var series = chart.series.push(new am4charts.ColumnSeries());
				  series.dataFields.valueY = field;
				  series.dataFields.categoryX = "schemes";
				  series.name = name;
				  series.columns.template.tooltipText = "{name}: [bold]{valueY}[/]";
				  series.stacked = true;
				  series.columns.template.width = am4core.percent(95);
				  }
				  // Add legend
				  chart.legend = new am4charts.Legend();

				  // Enable export
				  chart.exporting.menu = new am4core.ExportMenu();

		}); // end am4core.ready()
		
		
		
</script>
@endsection