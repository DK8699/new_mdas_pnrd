@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_admin_uc')

@section('custom_css')
<style>
    #districts_vs_cases_type {
        width: 100%;
        height: 655px;
    }
    #nature_of_case_vs_case_status {
        width: 100%;
        height: 855px;
    }
    #case_status {
        width: 100%;
        height: 585px;
    }
    .back {
        webkit-box-shadow: 1px 3px 3px 3px rgba(209,209,209,1);
        -moz-box-shadow: 1px 3px 3px 3px rgba(209,209,209,1);
        box-shadow: 1px 3px 15px 2px rgba(209,209,209,1);
        background-color:rgba(255, 255, 255, 0.90);
        padding:15px 25px;margin:25px 0px;
        min-height:555px;
    }
    h3 {
        font-weight:bold;
    }
    .container, .row {
        padding:0;
    }
    .col-lg-12, .col-lg-6 {
        margin:0;
    }

    button {
        font-size:15pt;
    }
    .dropdown-toggle {
        font-size:10pt;
    }
    .dropdown-menu {
        border-radius:1px;
    }
    li {
        line-height:1.5em;
    }
    .btn, label {
        font-size:12pt;
        color:#444;
    }
    .dropdown {
        background:rgba(0,0,0,0);
    }
    .btn{
        padding:0px 25px;
    }
    .btn-info.dropdown-toggle {
        left:-4px;
        border:2px solid #747474;
        background-color: #dedede !important;
    }
    table th, table td {
        font-size: 10pt;
    }
    table.dataTable thead th, table.dataTable thead td {
        border-bottom: 1px solid #8f8f8f;
    }
    .table-bordered>thead>tr>th, .table-bordered>tbody>tr>td {
        //background-color:white;
        font-size: 10pt;
        font-weight:bold;
        font-family:'Roboto';
        color:#5f5f5f;
        vertical-align: middle;
        padding:5px;
        border-top: 0px solid #ddd;
    }
    .table-bordered>thead>tr>th {
        background-color:#3a9fff;
        color:white;
    }
    table.table-hover tbody tr:hover {
        transition: .5s;
        background-color: rgba(200, 200, 200, 0.75);
    }
    .dataTables_wrapper .dataTables_filter input {
        margin: 0px 1px;
    }
    .form-control, button, input, select, textarea {
        height:35px;
        border:1px solid #747474;
        color:#444;
        font-size: 12pt;
        font-weight: bold;
        line-height: inherit;
        background-color:rgba(240, 240, 240, 0.85);
        box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0);
        //padding-top:4px;
    }
    .pt-3-half {
        padding-top: -1.4rem;
    }




    .navbar .dropdown-menu a {
        padding: 10px;
        font-size: 10pt;
        font-weight: 300;
        color: #000;
    }
    a {
        font-size: 10pt;
    }
    h1 {
        webkit-box-shadow: 1px 1px 5px 1px rgba(209,209,209,1);
        -moz-box-shadow: 1px 1px 5px 1px rgba(209,209,209,1);
        box-shadow: 1px 1px 5px 1px rgba(209,209,209,1);
        font-size:23pt;
        font-weight:bold;
        font-family:'Agency FB';
        color:#fff;
        padding:7.5px;
        margin:15px 0px 0px 0px;
    }
    h2 {
        font-size:18.5pt;
        font-weight:bold;
        margin-top:0;
        color:#367fed;
    }
    .sections {
        webkit-box-shadow: 1px 1px 5px 1px rgba(209,209,209,1);
        -moz-box-shadow: 1px 1px 5px 1px rgba(209,209,209,1);
        box-shadow: 1px 1px 5px 1px rgba(209,209,209,1);
        background-color:rgba(240, 240, 240, 0.85);
        padding:25px 25px;margin:55px 0px;
    }
    button {
        font-size:15pt;
    }
    .dropdown-toggle {
        font-size:10pt;
    }
    .dropdown-menu {
        border-radius:1px;
    }
    li {
        line-height:1.5em;
    }
    .btn, label {
        font-size:12pt;
        color:#444;
    }
    .dropdown {
        background:rgba(0,0,0,0);
    }
    .btn{
        padding:0px 25px;
    }
    .btn-info.dropdown-toggle {
        left:-4px;
        border:2px solid #747474;
        background-color: #dedede !important;
    }
    .Zebra_DatePicker_Icon {
        width: 45px;
    }
    span.Zebra_DatePicker_Icon {
        right:10px;
    }
    table th, table td {
        font-size: 10pt;
    }
    table.dataTable thead th, table.dataTable thead td {
        border-bottom: 1px solid #8f8f8f;
    }
    .table-bordered>thead>tr>th, .table-bordered>tbody>tr>td {
        //background-color:white;
        font-size: 10pt;
        font-weight:bold;
        font-family:'Roboto';
        color:#727272;
        vertical-align: middle;
        padding:5px;
    }
    .table-bordered>thead>tr>th {
        background-color:#3a9fff;
        color:white;
    }
    .dataTables_wrapper .dataTables_filter input {
        margin: 0px 1px;
    }
    .form-control, button, input, select, textarea {
        height:35px;
        border:1px solid #747474;
        color:#444;
        font-size: 12pt;
        font-weight: bold;
        line-height: inherit;
        background-color:rgba(240, 240, 240, 0.85);
        box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0);
        //padding-top:4px;
    }
    .pt-3-half {
        padding-top: -1.4rem;
    }

    #extension_uc {
        width: 100%;
        height: 555px;
    }
    #district_uc {
        width: 100%;
        height: 555px;
    }
    #extensions_projects_status {
        width: 100%;
        height: 555px;
    }
    #districts_projects_status {
        width: 100%;
        height: 555px;
    }
</style>
@endsection

@section('content')
<!-- HTML -->
    <br /><br />
    <div class="container">
        <div class="row">
            <div class="col-lg-12" style="text-align:center;">
                <div class="back" style="width:100%;">
                    <h3>Extension Centers UC-Financial Targets and Achievements</h3>
                    <div id="extension_uc"></div><br />
                </div>
            </div>
        </div>
    </div><br /><br />
    <div class="container">
        <div class="row">
            <div class="col-lg-12" style="text-align:center;">
                <div class="back" style="width:100%;">
                    <h3>Districts UC-Financial Targets and Achievements</h3>
                    <div id="district_uc"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6" style="text-align:center;">
                <div class="back" style="width:100%;">
                    <h3>Extension Centers Project Completion Status</h3>
                    <div id="extensions_projects_status"></div><br />
                </div>
            </div>
            <div class="col-lg-6" style="text-align:center;">
                <div class="back" style="width:100%;">
                    <h3>Districts Project Completion Status</h3>
                    <div id="districts_projects_status"></div><br />
                </div>
            </div>
        </div>
    </div><br /><br />
    <?php
                    $project_components = DB::select('select ob, goa_fund_received, other_receipts, uc_submitted from uc_project_entries a, uc_project_divisions b, uc_components_entities c, uc_components_details d
                    where a.id = ? and a.id = b.project_id and b.division_type = ? and b.id = c.division_id and c.id = d.components_entity_id', [6, 1]);
                    // dd($project_components);
                ?>
                    
@endsection

@section('custom_js')
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
<script type="text/javascript" src="{{ asset('mdas_assets/mdbootstrap/js/mdb.min.js') }}"></script>
<script src="{{ asset('mdas_assets/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('mdas_assets/bootstrap-select/dist/js/i18n/defaults-en_US.min.js') }}"></script>

<!-- Resources -->
<script src="{{ asset('mdas_assets/amcharts4/core.js') }}"></script>
<script src="{{ asset('mdas_assets/amcharts4/charts.js') }}"></script>
<script src="{{ asset('mdas_assets/amcharts4/themes/material.js') }}"></script>
<script src="{{ asset('mdas_assets/amcharts4/themes/animated.js') }}"></script>

<script src="{{ asset('mdas_assets/amcharts4/themes/dataviz.js') }}"></script>
<!-- Resources -->

<!-- Chart code -->
<script>
/************************ Extension Centers UC Financial************************/
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_dataviz);
am4core.useTheme(am4themes_animated);
// Themes end

 // Create chart instance
var chart = am4core.create("extension_uc", am4charts.XYChart);

chart.data = [
    <?php
                foreach($uc_extension_centers as $values)
                {
                    $uc_extension_centers = DB::select('select a.ob, a.goa_fund_received, a.other_receipts, a.uc_submitted, d.extension_center_name from uc_components_details a, uc_components_entities b, uc_project_divisions c, siprd_extension_centers d
                    where a.components_entity_id = b.id and b.division_id = c.id and c.division_type = ? and c.zilla_extension_id = d.id and d.id = ?', [1, $values->id]);
                    $target = 0; $achievement = 0;
                    if( !empty($uc_extension_centers) ) {
                        // $size = sizeof($uc_extension_centers);
                        foreach($uc_extension_centers as $comp) {
                            $target += $comp->ob + $comp->goa_fund_received + $comp->other_receipts;
                            $achievement += $comp->uc_submitted;
                        }
            ?>
                        {
                            "district": "{{ $values->extension_center_name }}",
                            "target": {{ $target }},
                            "achievement": {{ $achievement }}
                        },
            <?php
                    }
                    else
            ?>
                    {
                        "district": "{{ $values->extension_center_name }}",
                        "target": {{ $target }},
                        "achievement": {{ $achievement }}
                    },
            <?php
                }
            ?>
];

// Create axes
var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "district";
categoryAxis.numberFormatter.numberFormat = "#";
categoryAxis.renderer.inversed = true;
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.cellStartLocation = 0.1;
categoryAxis.renderer.cellEndLocation = 0.9;

var  valueAxis = chart.xAxes.push(new am4charts.ValueAxis()); 
valueAxis.renderer.opposite = true;

// Create series
function createSeries(field, name) {
  var series = chart.series.push(new am4charts.ColumnSeries());
  series.dataFields.valueX = field;
  series.dataFields.categoryY = "district";
  series.name = name;
  series.columns.template.tooltipText = "{name}: [bold]{valueX}[/]";
  series.columns.template.height = am4core.percent(100);
  series.sequencedInterpolation = true;

  var valueLabel = series.bullets.push(new am4charts.LabelBullet());
  valueLabel.label.text = "{valueX}";
  valueLabel.label.horizontalCenter = "left";
  valueLabel.label.dx = 10;
  valueLabel.label.hideOversized = false;
  valueLabel.label.truncate = false;

  var categoryLabel = series.bullets.push(new am4charts.LabelBullet());
  categoryLabel.label.text = "{name}";
  categoryLabel.label.horizontalCenter = "right";
  categoryLabel.label.dx = -10;
  categoryLabel.label.fill = am4core.color("#fff");
  categoryLabel.label.hideOversized = false;
  categoryLabel.label.truncate = false;
}

createSeries("target", "Target");
createSeries("achievement", "Achievement");

}); // end am4core.ready()
/************************ Extension Centers UC Financial************************/


/************************ Districts UC Financial************************/
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_dataviz);
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart = am4core.create("district_uc", am4charts.XYChart);

// Add percent sign to all numbers
// chart.numberFormatter.numberFormat = "#.#'%'";
chart.data = [
    <?php
                foreach($uc_zila_parishads as $values)
                {
                    $uc_extension_centers = DB::select('select a.ob, a.goa_fund_received, a.other_receipts, a.uc_submitted, d.zila_parishad_name from uc_components_details a, uc_components_entities b, uc_project_divisions c, zila_parishads d
                    where a.components_entity_id = b.id and b.division_id = c.id and c.division_type = ? and c.zilla_extension_id = d.id and d.id = ?', [2, $values->id]);
                    $target = 0; $achievement = 0;
                    if( !empty($uc_extension_centers) ) {
                        // $size = sizeof($uc_extension_centers);
                        foreach($uc_extension_centers as $comp) {
                            $target += $comp->ob + $comp->goa_fund_received + $comp->other_receipts;
                            $achievement += $comp->uc_submitted;
                        }
            ?>
                        {
                            "district": "{{ $values->zila_parishad_name }}",
                            "target": {{ $target }},
                            "achievement": {{ $achievement }}
                        },
            <?php
                    }
                    else
            ?>
                    {
                        "district": "{{ $values->zila_parishad_name }}",
                        "target": {{ $target }},
                        "achievement": {{ $achievement }}
                    },
            <?php
                }
            ?>
];

// Create axes
var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "district";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.minGridDistance = 30;
categoryAxis.renderer.labels.template.horizontalCenter = "right";
categoryAxis.renderer.labels.template.verticalCenter = "middle";
categoryAxis.renderer.labels.template.rotation = 300;
categoryAxis.title.text = "ZILA PARISHADS";
categoryAxis.title.fontWeight = 1000;

var valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
valueAxis.title.text = "UC Financial Figures";
valueAxis.title.fontWeight = 800;

// Create series
var series = chart.series.push(new am4charts.ColumnSeries());
series.dataFields.valueY = "target";
series.dataFields.categoryX = "district";
series.clustered = false;
series.tooltipText = "UC Target {categoryX}: [bold]{valueY}[/]";

var series2 = chart.series.push(new am4charts.ColumnSeries());
series2.dataFields.valueY = "achievement";
series2.dataFields.categoryX = "district";
series2.clustered = false;
series2.columns.template.width = am4core.percent(50);
series2.tooltipText = "UC Achievement {categoryX}: [bold]{valueY}[/]";

chart.cursor = new am4charts.XYCursor();
chart.cursor.lineX.disabled = true;
chart.cursor.lineY.disabled = true;

}); // end am4core.ready()
/************************Districts UC Financial************************/



/************************Extension Projects Status************************/
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_material);
am4core.useTheme(am4themes_animated);
// Themes end


// Create chart instance
var chart = am4core.create("extensions_projects_status", am4charts.RadarChart);

chart.data = [
            <?php
                foreach($Projects as $values)
                {
                    $project_components = DB::select('select ob, goa_fund_received, other_receipts, uc_submitted from uc_project_entries a, uc_project_divisions b, uc_components_entities c, uc_components_details d
                    where a.id = ? and a.id = b.project_id and b.division_type = ? and b.id = c.division_id and c.id = d.components_entity_id', [$values->id, 1]);


                    
                    $full = 100; $percentage = 0; $target = 0; $achievement = 0;
                    if( !empty($project_components) ) {
                        foreach($project_components as $comp) {
                            $target += $comp->ob + $comp->goa_fund_received + $comp->other_receipts;
                            $achievement += $comp->uc_submitted;
                        }
                        if( $achievement != 0 && $target != 0 ) {
                            $percentage = ($achievement/$target) * 100;
            ?>
                            {
                                "category": "{{ $values->project_name }}",
                                "value": {{ $percentage }},
                                "full": {{ $full }}
                            },
            <?php
                        }
                        else {
            ?>
                            {
                                "category": "{{ $values->project_name }}",
                                "target": {{ $percentage }},
                                "full": {{ $full }}
                            },
            <?php
                            continue;
                        }
                    }
                    else
            ?>
                    {
                        "category": "{{ $values->project_name }}",
                        "target": {{ $percentage }},
                        "full": {{ $full }}
                    },
            <?php
                }
            ?>
];

// Make chart not full circle
chart.startAngle = -90;
chart.endAngle = 180;
chart.innerRadius = am4core.percent(20);

// Set number format
chart.numberFormatter.numberFormat = "#.#'%'";

// Create axes
var categoryAxis = chart.yAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "category";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.grid.template.strokeOpacity = 0;
categoryAxis.renderer.labels.template.horizontalCenter = "right";
categoryAxis.renderer.labels.template.fontWeight = 500;
categoryAxis.renderer.labels.template.adapter.add("fill", function(fill, target) {
  return (target.dataItem.index >= 0) ? chart.colors.getIndex(target.dataItem.index) : fill;
});
categoryAxis.renderer.minGridDistance = 10;

var valueAxis = chart.xAxes.push(new am4charts.ValueAxis());
valueAxis.renderer.grid.template.strokeOpacity = 0;
valueAxis.min = 0;
valueAxis.max = 100;
valueAxis.strictMinMax = true;

// Create series
var series1 = chart.series.push(new am4charts.RadarColumnSeries());
series1.dataFields.valueX = "full";
series1.dataFields.categoryY = "category";
series1.clustered = false;
series1.columns.template.fill = new am4core.InterfaceColorSet().getFor("alternativeBackground");
series1.columns.template.fillOpacity = 0.08;
series1.columns.template.cornerRadiusTopLeft = 20;
series1.columns.template.strokeWidth = 0;
series1.columns.template.radarColumn.cornerRadius = 20;

var series2 = chart.series.push(new am4charts.RadarColumnSeries());
series2.dataFields.valueX = "value";
series2.dataFields.categoryY = "category";
series2.clustered = false;
series2.columns.template.strokeWidth = 0;
series2.columns.template.tooltipText = "{category}: [bold]{value}[/]";
series2.columns.template.radarColumn.cornerRadius = 20;

series2.columns.template.adapter.add("fill", function(fill, target) {
  return chart.colors.getIndex(target.dataItem.index);
});

// Add cursor
chart.cursor = new am4charts.RadarCursor();

}); // end am4core.ready()
/************************Extension Projects Status************************/


/************************Districts Projects Status************************/
am4core.ready(function() {

// Themes begin
am4core.useTheme(am4themes_dataviz);
am4core.useTheme(am4themes_animated);
// Themes end

// Create chart instance
var chart1 = am4core.create("districts_projects_status", am4charts.RadarChart);

// Add data
chart1.data = [
            <?php
                foreach($Projects as $values)
                {
                    $project_components = DB::select('select ob, goa_fund_received, other_receipts, uc_submitted from uc_project_entries a, uc_project_divisions b, uc_components_entities c, uc_components_details d
                    where a.id = ? and a.id = b.project_id and b.division_type = ? and b.id = c.division_id and c.id = d.components_entity_id', [$values->id, 2]);


                    
                    $full = 100; $percentage = 0; $target = 0; $achievement = 0;
                    if( !empty($project_components) ) {
                        foreach($project_components as $comp) {
                            $target += $comp->ob + $comp->goa_fund_received + $comp->other_receipts;
                            $achievement += $comp->uc_submitted;
                        }
                        if( $achievement != 0 && $target != 0 ) {
                            $percentage = ($achievement/$target) * 100;
            ?>
                            {
                                "category": "{{ $values->project_name }}",
                                "value": {{ $percentage }},
                                "full": {{ $full }}
                            },
            <?php
                        }
                        else {
            ?>
                            {
                                "category": "{{ $values->project_name }}",
                                "target": {{ $percentage }},
                                "full": {{ $full }}
                            },
            <?php
                            continue;
                        }
                    }
                    else
            ?>
                    {
                        "category": "{{ $values->project_name }}",
                        "target": {{ $percentage }},
                        "full": {{ $full }}
                    },
            <?php
                }
            ?>
];

// Make chart not full circle
chart1.startAngle = -90;
chart1.endAngle = 180;
chart1.innerRadius = am4core.percent(20);

// Set number format
chart1.numberFormatter.numberFormat = "#.#'%'";

// Create axes
var categoryAxis = chart1.yAxes.push(new am4charts.CategoryAxis());
categoryAxis.dataFields.category = "category";
categoryAxis.renderer.grid.template.location = 0;
categoryAxis.renderer.grid.template.strokeOpacity = 0;
categoryAxis.renderer.labels.template.horizontalCenter = "right";
categoryAxis.renderer.labels.template.fontWeight = 500;
categoryAxis.renderer.labels.template.adapter.add("fill", function(fill, target) {
  return (target.dataItem.index >= 0) ? chart1.colors.getIndex(target.dataItem.index) : fill;
});
categoryAxis.renderer.minGridDistance = 10;

var valueAxis = chart1.xAxes.push(new am4charts.ValueAxis());
valueAxis.renderer.grid.template.strokeOpacity = 0;
valueAxis.min = 0;
valueAxis.max = 100;
valueAxis.strictMinMax = true;

// Create series
var series1 = chart1.series.push(new am4charts.RadarColumnSeries());
series1.dataFields.valueX = "full";
series1.dataFields.categoryY = "category";
series1.clustered = false;
series1.columns.template.fill = new am4core.InterfaceColorSet().getFor("alternativeBackground");
series1.columns.template.fillOpacity = 0.08;
series1.columns.template.cornerRadiusTopLeft = 20;
series1.columns.template.strokeWidth = 0;
series1.columns.template.radarColumn.cornerRadius = 20;

var series2 = chart1.series.push(new am4charts.RadarColumnSeries());
series2.dataFields.valueX = "value";
series2.dataFields.categoryY = "category";
series2.clustered = false;
series2.columns.template.strokeWidth = 0;
series2.columns.template.tooltipText = "{category}: [bold]{value}[/]";
series2.columns.template.radarColumn.cornerRadius = 20;

series2.columns.template.adapter.add("fill", function(fill, target) {
  return chart1.colors.getIndex(target.dataItem.index);
});

// Add cursor
chart1.cursor = new am4charts.RadarCursor();

}); // end am4core.ready()
/************************Districts Projects Status************************/
</script>
@endsection