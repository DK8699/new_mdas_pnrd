@php
    $page_title="Need Based Training";
@endphp

@extends('layouts.app_admin_training')

@section('custom_css')
	<link href="{{asset('mdas_assets/css/bootstrap-select.min.css')}}" rel="stylesheet" type="text/css"/>
	<link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
	<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
     <style>
		.mb40{
			margin-bottom: 40px;
		}
		#centreWiseDiv {
		  width: 100%;
		  height: 500px;
		}
		#programmeWiseDiv {
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
	</style>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.Training.dashboard')}}">Dashboard</a></li>
        </ol>
    </div>
</div>
<div class="container mb40">
	
	<div class="row mt40">
	
	   <!--Table to display the Media Entry date wise-->   
	<div class="col-md-6 col-sm-6 col-xs-12">
		 <div class="panel panel-primary" style="border-color:#9e1b56; min-height:600px">
			 <div class="panel-heading" style="background-image: none; background-color:#6e0d38; color:#fff">
				 Training Centre Wise Summary from <span style="color: #f9fd00; font-weight: 500;">{{\Carbon\Carbon::parse($current)->format('d M Y')}}</span> to <span style="color: #f9fd00; font-weight: 500;">{{\Carbon\Carbon::parse($days_after_10)->format('d M Y')}}</span>
			 </div>
			 <div class="panel-body">

					<!-- <h3 style="background-color:#d4b3c7; padding:5px;"></h3><br/>-->
					 <div class="table-responsive">
						<table class="table table-bordered table-striped no-wrap" id="dataTable">
						    <thead>
							    <tr style="background-color:#5f146f; color:#fff">
								   <td>SL</td>
								   <td>Training Centre</td>
								   <td>Trainings</td>
								    <td>Interested Participants</td>
								   <td>Participants</td>
								</tr>
						    </thead>
						    <tbody>
							    @php $i=1; @endphp
							    @foreach($data['training_centre'] as $li)
							    <tr>
								  <td>{{$i}}</td>
								  <td>{{$li->centre_name}}</td>
								  <td>
								    @if(isset($data['centreWiseTrainings'][$li->centre_id]['total']))
									  {{$data['centreWiseTrainings'][$li->centre_id]['total']}}
								    @else
									  {{0}}
								    @endif
								  </td>
								  <td>
								    @if(isset($data['centreWiseParticipants'][$li->centre_id]['interested_participants']))
									  {{$data['centreWiseParticipants'][$li->centre_id]['interested_participants']}}
								    @else
									  {{0}}
								    @endif
								  </td>
								  <td>
								    @if(isset($data['centreWiseTrainings'][$li->centre_id]['participants']))
									  {{$data['centreWiseTrainings'][$li->centre_id]['participants']}}
								    @else
									  {{0}}
								    @endif
								  </td>
							    </tr>
							    @php $i++; @endphp
							    @endforeach
						    </tbody>
						</table>
					 </div>
			   </div>
		    </div>
	  </div> 
	   <div class="col-md-6 col-sm-6 col-xs-12">
		  <div class="panel panel-primary" style="border-color:#9e1b56; min-height:600px;">
			 <div class="panel-heading" style="background-image: none; background-color:#6e0d38; color:#fff">
				 Programme Wise Summary from <span style="color: #f9fd00; font-weight: 500;">{{\Carbon\Carbon::parse($current)->format('d M Y')}}</span> to <span style="color: #f9fd00; font-weight: 500;">{{\Carbon\Carbon::parse($days_after_10)->format('d M Y')}}</span>
			 </div>
			 <div class="panel-body">

					<!-- <h3 style="background-color:#d4b3c7; padding:5px;"></h3><br/>-->
					 <div class="table-responsive">
						<table class="table table-bordered table-striped no-wrap" id="dataTable1">
						    <thead>
							    <tr style="background-color:#5f146f; color:#fff">
								   <td>SL</td>
								   <td>Programme</td>
								   <td>Trainings</td>
								   <td>Interested Participants</td>
								   <td>Participants</td>
								</tr>
						    </thead>
						    <tbody>
							    @php $i=1; @endphp
							    @foreach($data['programme'] as $li)
							    <tr>
								  <td>{{$i}}</td>
								  <td class="text-wrap">{{$li->programme_name}}</td>
								  <td>
								    @if(isset($data['programmeWiseTrainings'][$li->id]['total']))
									  {{$data['programmeWiseTrainings'][$li->id]['total']}}
								    @else
									  {{0}}
								    @endif
								  </td>
								   <td>
								    @if(isset($data['programmeWiseParticipants'][$li->id]['interested_participants']))
									  {{$data['programmeWiseParticipants'][$li->id]['interested_participants']}}
								    @else
									  {{0}}
								    @endif
								  </td>
								  <td>
								    @if(isset($data['programmeWiseTrainings'][$li->id]['participants']))
									  {{$data['programmeWiseTrainings'][$li->id]['participants']}}
								    @else
									  {{0}}
								    @endif
								  </td>
							    </tr>
							    @php $i++; @endphp
							    @endforeach
						    </tbody>
						</table>
					 </div>
			   </div>
		    </div>
	  </div> 
	</div>
		<div class="row mt40">
			<div class="col-md-12 col-sm-12 col-xs-12" style="margin-bottom:10px">
				 <ul class="nav nav-pills">
					<li class="active"><a data-toggle="tab" href="#centre_wise">Centre Wise</a></li>
					<li><a data-toggle="tab" href="#programme_wise">Programme Wise</a></li>
				 </ul>
			</div>
		</div>
		<div class="row">
			 <div class="tab-content">
                    <div id="centre_wise" class="tab-pane fade in active">
                         <div class="col-md-12">
						<div class="back" style="width:100%;">
							<h3 class="text-center">Centre wise overall training report</h3>
							<div id="centreWiseDiv"></div>
						</div>
			      	</div>
				 </div>
				 <div id="programme_wise" class="tab-pane fade in">
                         <div class="col-md-12">
						<div class="back" style="width:100%;">
							<h3 class="text-center">Programme wise overall training report</h3>
							<div id="programmeWiseDiv"></div>
						</div>
			      	</div>
				 </div>
			</div>
          </div>
		
	
	
	
</div>
	
@endsection
	

@section('custom_js')

    <script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
    <script src="{{ asset('mdas_assets/amcharts4/core.js') }}"></script>
    <script src="{{ asset('mdas_assets/amcharts4/charts.js') }}"></script>
    <script src="{{ asset('mdas_assets/amcharts4/themes/material.js') }}"></script>
    <script src="{{ asset('mdas_assets/amcharts4/themes/animated.js') }}"></script>
    <script src="{{ asset('mdas_assets/amcharts4/themes/dataviz.js') }}"></script>
    <script>   

	    $(document).ready(function () {
            $('#dataTable').DataTable({
                dom: 'Bfrtip',
                ordering: false,
                paging: true,
                buttons: [
                    {
                        extend:    'excelHtml5',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    }
                ]
            });
        });
	    $(document).ready(function () {
            $('#dataTable1').DataTable({
                dom: 'Bfrtip',
                ordering: false,
                paging: true,
                buttons: [
                    {
                        extend:    'excelHtml5',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    }
                ]
            });
        });
	    
	    
	    //Overall Training Centre Wise Training Report
	    
	    am4core.ready(function() {

		// Themes begin
		am4core.useTheme(am4themes_animated);
		// Themes end

		// Create chart instance
		var chart = am4core.create("centreWiseDiv", am4charts.XYChart3D);
		chart.paddingBottom = 30;
		chart.angle = 35;
		  
		chart.data = [
		@foreach($data['training_centre'] as $list)
                    {
                    "training_centre": "{{$list->centre_name}}",
                    "trainings": @if(isset($data['overallCentreWiseTrainings'][$list->centre_id]['total'])){{$data['overallCentreWiseTrainings'][$list->centre_id]['total']}}@else{{0}}@endif,
                    },
            @endforeach
		    ];
		// Create axes
		var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
		categoryAxis.dataFields.category = "training_centre";
		categoryAxis.title.text = "Training Centre";
		categoryAxis.title.fontWeight = "bold";
		categoryAxis.renderer.grid.template.location = 0;
		categoryAxis.renderer.minGridDistance = 20;
		categoryAxis.renderer.cellStartLocation = 0.1;
		categoryAxis.renderer.cellEndLocation = 0.9;
		categoryAxis.renderer.inside = true;
		categoryAxis.renderer.labels.template.verticalCenter = "middle";
		categoryAxis.renderer.labels.template.rotation = 280;
		categoryAxis.tooltip.disabled = true;
		categoryAxis.renderer.minHeight = 35;
		categoryAxis.renderer.grid.template.disabled = true;

		let labelTemplate = categoryAxis.renderer.labels.template;
		labelTemplate.rotation = -90;
		labelTemplate.horizontalCenter = "left";
		labelTemplate.verticalCenter = "middle";
		labelTemplate.dy = 10; // moves it a bit down;
		labelTemplate.inside = false; // this is done to avoid settings which are not suitable when label is rotated

		var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
		valueAxis.renderer.grid.template.disabled = true;

		// Create series
		var series = chart.series.push(new am4charts.ConeSeries());
		series.dataFields.valueY = "trainings";
		series.dataFields.categoryX = "training_centre";
		    
		    
		series.name = "Trainings";
		series.tooltipText = "{categoryX}: [bold]{valueY}[/]";
		series.columns.template.fillOpacity = .8;

		var columnTemplate = series.columns.template;
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

		}); 
	    
	    //Programme wise overall report graph
	    
	    am4core.ready(function() {

			// Themes begin
			am4core.useTheme(am4themes_animated);
			// Themes end

			// Create chart instance
			var chart = am4core.create("programmeWiseDiv", am4charts.XYChart3D);

			// Add data
			chart.data = [
				@foreach($data['programme'] as $list)
                    {
                    "programme": "{{$list->programme_name}}",
                    "trainings": @if(isset($data['overallProgrammeWiseTrainings'][$list->id]['total'])){{$data['overallProgrammeWiseTrainings'][$list->id]['total']}}@else{{0}}@endif,
                    },
            		@endforeach
			];

			// Create axes
			let categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
			categoryAxis.dataFields.category = "programme";
		     categoryAxis.title.text = "Programme";
			categoryAxis.title.fontWeight = "bold";
			categoryAxis.renderer.labels.template.rotation = 280;
			categoryAxis.renderer.labels.template.hideOversized = false;
			categoryAxis.renderer.minGridDistance = 20;
			categoryAxis.renderer.labels.template.horizontalCenter = "right";
			categoryAxis.renderer.labels.template.verticalCenter = "middle";
			categoryAxis.tooltip.label.rotation = 270;
			categoryAxis.tooltip.label.horizontalCenter = "right";
			categoryAxis.tooltip.label.verticalCenter = "middle";
		    

			let valueAxis = chart.yAxes.push(new am4charts.ValueAxis());

			// Create series
			var series = chart.series.push(new am4charts.ColumnSeries3D());
			series.dataFields.valueY = "trainings";
			series.dataFields.categoryX = "programme";
			series.name = "Trainings";
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

			chart.cursor = new am4charts.XYCursor();
			chart.cursor.lineX.strokeOpacity = 0;
			chart.cursor.lineY.strokeOpacity = 0;
		    
		    // Enable export
			chart.exporting.menu = new am4core.ExportMenu();
			chart.cursor = new am4charts.XYCursor();
			chart.cursor.lineX.strokeOpacity = 0;
			chart.cursor.lineY.strokeOpacity = 0;

			}); // end am4core.ready()
	    
	    
``</script>
	    
@endsection
