@php
    $page_title="dashboard";
@endphp

@extends('admin.CourtCases.layouts.frame')

@section('custom_css')
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
<link href="{{ asset('mdas_assets/mdbootstrap/css/mdb.min.css') }}" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <!-- <link href="{{ asset('mdas_assets/mdbootstrap/css/style.css') }}" rel="stylesheet"> -->

<link rel="stylesheet" href="{{ asset('mdas_assets/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
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
    /* } */
</style>
@endsection

@section('content')
    <br /><br />
    <div class="container">
        <div class="row">
            <div class="col-lg-12" style="text-align:center;">
                <div class="back" style="width:100%;">
                    <h3>District-Wise Court Case Summary</h3>
                    <div id="districts_vs_cases_type"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-6" style="text-align:center;">
                <div class="back" style="width:100%;">
                    <h3>Parawise Comments Due</h3><h4>{{ $rev_case_start }} to {{ $rev_case_end }}</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="dataTable1">
                            <thead>
                                <tr class="bg-primary">
                                    <th width="25px">SL</th>
                                    <th width="85px">Due Date</th>
                                    <th width="85px">To be submitted by</th>
                                    <th width="105px">Case Number</th>
                                    <th width="105px">Name of Petitioner v/s Respondant</th>
                                    <th style="width:80px;">Task</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php $i=1; @endphp
                            @foreach($court_cases_parawise_comments as $li)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $li->due_date_of_parawise_comments }}</td>
                                    <td>{{ $li->submitted_by }}</td>
                                    <td>{{ $li->case_number }}</td>
                                    <td>{{ $li->name_of_petitioner }}</td>
                                    <td>
                                        <a href="{{ route('admin.courtCases.viewCourtCase', Crypt::encrypt($li->c_id)) }}" target="_blank" title="View" class="btn btn-outline-primary waves-effect btn-lg" style="font-size:12pt;padding:2px 5px;float:left;"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('admin.courtCases.manageCourtCase', Crypt::encrypt($li->c_id)) }}" target="_blank" title="Edit" class="btn btn-outline-default waves-effect btn-lg" style="font-size:12pt;padding:2px 5px;float:left;left:5px;"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                            @php $i++; @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" style="text-align:center;">
                <div class="back" style="width:100%;">
                    <h3>Interim Order Due</h3><h4>{{ $rev_case_start }} to {{ $rev_case_end }}</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="dataTable1">
                            <thead>
                                <tr class="bg-primary">
                                    <th width="25px">SL</th>
                                    <th width="85px">Due Date</th>
                                    <th width="85px">To be submitted by</th>
                                    <th width="105px">Case Number</th>
                                    <th width="105px">Name of Petitioner v/s Respondant</th>
                                    <th style="width:80px;">Task</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php $i=1; @endphp
                            @foreach($court_cases_interim_order as $li)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $li->due_date_of_interim_order }}</td>
                                    <td>{{ $li->submitted_by }}</td>
                                    <td>{{ $li->case_number }}</td>
                                    <td>{{ $li->name_of_petitioner }}</td>
                                    <td>
                                        <a href="{{ route('admin.courtCases.viewCourtCase', Crypt::encrypt($li->c_id)) }}" target="_blank" title="View" class="btn btn-outline-primary waves-effect btn-lg" style="font-size:12pt;padding:2px 5px;float:left;"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('admin.courtCases.manageCourtCase', Crypt::encrypt($li->c_id)) }}" target="_blank" title="Edit" class="btn btn-outline-default waves-effect btn-lg" style="font-size:12pt;padding:2px 5px;float:left;left:5px;"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                            @php $i++; @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" style="text-align:center;">
                <div class="back" style="width:100%;">
                    <h3>Instructions Due</h3><h4>{{ $rev_case_start }} to {{ $rev_case_end }}</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="dataTable1">
                            <thead>
                                <tr class="bg-primary">
                                    <th width="25px">SL</th>
                                    <th width="85px">Due Date</th>
                                    <th width="85px">To be submitted by</th>
                                    <th width="105px">Case Number</th>
                                    <th width="105px">Name of Petitioner v/s Respondant</th>
                                    <th style="width:80px;">Task</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php $i=1; @endphp
                            @foreach($court_cases_instruction as $li)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $li->due_date_of_instruction }}</td>
                                    <td>{{ $li->submitted_by }}</td>
                                    <td>{{ $li->case_number }}</td>
                                    <td>{{ $li->name_of_petitioner }}</td>
                                    <td>
                                        <a href="{{ route('admin.courtCases.viewCourtCase', Crypt::encrypt($li->c_id)) }}" target="_blank" title="View" class="btn btn-outline-primary waves-effect btn-lg" style="font-size:12pt;padding:2px 5px;float:left;"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('admin.courtCases.manageCourtCase', Crypt::encrypt($li->c_id)) }}" target="_blank" title="Edit" class="btn btn-outline-default waves-effect btn-lg" style="font-size:12pt;padding:2px 5px;float:left;left:5px;"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                            @php $i++; @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" style="text-align:center;">
                <div class="back" style="width:100%;">
                    <h3>Final Order Due</h3><h4>{{ $rev_case_start }} to {{ $rev_case_end }}</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="dataTable1">
                            <thead>
                                <tr class="bg-primary">
                                    <th width="25px">SL</th>
                                    <th width="85px">Due Date</th>
                                    <th width="85px">Date of receipt of Final Order</th>
                                    <th width="105px">Case Number</th>
                                    <th width="105px">Name of Petitioner v/s Respondant</th>
                                    <th style="width:80px;">Task</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php $i=1; @endphp
                            @foreach($court_cases_final_order as $li)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $li->due_date_of_final_order }}</td>
                                    <td>{{ $li->date_of_receipt_of_final_order }}</td>
                                    <td>{{ $li->case_number }}</td>
                                    <td>{{ $li->name_of_petitioner }}</td>
                                    <td>
                                        <a href="{{ route('admin.courtCases.viewCourtCase', Crypt::encrypt($li->c_id)) }}" target="_blank" title="View" class="btn btn-outline-primary waves-effect btn-lg" style="font-size:12pt;padding:2px 5px;float:left;"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('admin.courtCases.manageCourtCase', Crypt::encrypt($li->c_id)) }}" target="_blank" title="Edit" class="btn btn-outline-default waves-effect btn-lg" style="font-size:12pt;padding:2px 5px;float:left;left:5px;"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                            @php $i++; @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" style="text-align:center;">
                <div class="back" style="width:100%;">
                    <h3>Speaking Order Due</h3><h4>{{ $rev_case_start }} to {{ $rev_case_end }}</h4>
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover" id="dataTable1">
                            <thead>
                                <tr class="bg-primary">
                                    <th width="25px">SL</th>
                                    <th width="85px">Due Date</th>
                                    <th width="85px">Date of Speaking Order</th>
                                    <th width="105px">Case Number</th>
                                    <th width="105px">Name of Petitioner v/s Respondant</th>
                                    <th style="width:80px;">Task</th>
                                </tr>
                            </thead>
                            <tbody>
                            @php $i=1; @endphp
                            @foreach($court_cases_speaking_order as $li)
                                <tr>
                                    <td>{{ $i }}</td>
                                    <td>{{ $li->due_date_of_speaking_order }}</td>
                                    <td>{{ $li->date_of_speaking_order }}</td>
                                    <td>{{ $li->case_number }}</td>
                                    <td>{{ $li->name_of_petitioner }}</td>
                                    <td>
                                        <a href="{{ route('admin.courtCases.viewCourtCase', Crypt::encrypt($li->c_id)) }}" target="_blank" title="View" class="btn btn-outline-primary waves-effect btn-lg" style="font-size:12pt;padding:2px 5px;float:left;"><i class="fa fa-eye"></i></a>
                                        <a href="{{ route('admin.courtCases.manageCourtCase', Crypt::encrypt($li->c_id)) }}" target="_blank" title="Edit" class="btn btn-outline-default waves-effect btn-lg" style="font-size:12pt;padding:2px 5px;float:left;left:5px;"><i class="fa fa-edit"></i></a>
                                    </td>
                                </tr>
                            @php $i++; @endphp
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-lg-6" style="text-align:center;">
                <div class="back" style="width:100%;">
                    <h3>Court Cases Status Summary</h3>
                    <div id="case_status"></div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12" style="text-align:center;">
                <div class="back" style="width:100%;">
                <h3>Court Case Nature and Status Summary</h3>
                <div id="nature_of_case_vs_case_status"></div>
            </div>
        </div>
    </div><br /><br />
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
<script type="text/javascript" src="{{ asset('mdas_assets/mdbootstrap/js/mdb.min.js') }}"></script>
<script src="{{ asset('mdas_assets/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('mdas_assets/bootstrap-select/dist/js/i18n/defaults-en_US.min.js') }}"></script>

<!-- Resources -->
<script src="{{ asset('mdas_assets/amcharts4/core.js') }}"></script>
<script src="{{ asset('mdas_assets/amcharts4/charts.js') }}"></script>
<script src="{{ asset('mdas_assets/amcharts4/themes/material.js') }}"></script>
<script src="{{ asset('mdas_assets/amcharts4/themes/animated.js') }}"></script>

<script src="{{ asset('mdas_assets/amcharts4/themes/dataviz.js') }}"></script>
<script>
    $('.table').DataTable({
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                messageTop: 'List'
            },
        ]
    });
</script>
<!-- <script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/material.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script>

<script src="https://www.amcharts.com/lib/4/core.js"></script>
<script src="https://www.amcharts.com/lib/4/charts.js"></script>
<script src="https://www.amcharts.com/lib/4/themes/animated.js"></script> -->
<script>
    /********************************District Vs Cases Types*******************************/
        am4core.ready(function() {

            // Themes begin
            am4core.useTheme(am4themes_material);
            am4core.useTheme(am4themes_animated);
            // Themes end

            // Create chart instance
            var chart = am4core.create("districts_vs_cases_type", am4charts.XYChart);

            // Add data
            chart.data = [
            <?php
                foreach($districts as $values)
                {
            ?>
                    {
                        "districts": "{{ $values->district_name }}",
                <?php
                        foreach($court_cases_type as $val)
                        {
                            $court_cases = DB::select('select district_id from court_cases where case_type_id = ? order by id asc', [$val->id]);

                            $size_of_case = sizeof($court_cases);
                      
                            $total = 0;

                            for($i=0;$i<$size_of_case;$i++)
                            {
                                $district_ids = json_decode($court_cases[$i]->district_id);
                                $size_of_districts = sizeof($district_ids);
                                for($j=0;$j<$size_of_districts;$j++)
                                {
                                    if( $values->id == $district_ids[$j] ) {
                                        $total++;
                                    }
                                }
                            }
                ?>
                        "{{ $val->court_case_type }}": {{ $total }},
                <?php
                        }
                ?>
                    },
            <?php
                }
            ?>
            ];

            // Create axes
            var categoryAxis = chart.xAxes.push(new am4charts.CategoryAxis());
            categoryAxis.dataFields.category = "districts";
            categoryAxis.title.text = "Districts";
            categoryAxis.renderer.grid.template.location = 0;
            categoryAxis.renderer.minGridDistance = 20;
            categoryAxis.renderer.cellStartLocation = 0.1;
            categoryAxis.renderer.cellEndLocation = 0.9;
            categoryAxis.renderer.labels.template.horizontalCenter = "right";
            categoryAxis.renderer.labels.template.verticalCenter = "middle";
            categoryAxis.renderer.labels.template.rotation = 280;
            categoryAxis.tooltip.disabled = true;
            categoryAxis.renderer.minHeight = 35;
            // Make it stacked

            var  valueAxis = chart.yAxes.push(new am4charts.ValueAxis());
            valueAxis.min = 0;
            valueAxis.title.text = "Court Cases";

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

            <?php
                foreach($court_cases_type as $val)
                {
            ?>
                    createSeries("{{ $val->court_case_type }}", "{{ $val->court_case_type }}");
            <?php
                }
            ?>
            // Add legend
            chart.legend = new am4charts.Legend();

            // Enable export
            chart.exporting.menu = new am4core.ExportMenu();

        }); // end am4core.ready()
    /********************************District Vs Cases Types*******************************/


    /********************************Cases Status*******************************/
        am4core.ready(function() {

            // Themes begin
            /* am4core.useTheme(am4themes_animated); */
            // Themes end

            // Create chart instance
            var chart1 = am4core.create("case_status", am4charts.PieChart);

            // Add and configure Series
            var pieSeries = chart1.series.push(new am4charts.PieSeries());
            pieSeries.dataFields.value = "cases";
            pieSeries.dataFields.category = "case_status";

            // Let's cut a hole in our Pie chart the size of 30% the radius
            chart1.innerRadius = am4core.percent(30);

            // Put a thick white border around each Slice
            pieSeries.slices.template.stroke = am4core.color("#fff");
            pieSeries.slices.template.strokeWidth = 2;
            pieSeries.slices.template.strokeOpacity = 1;
            pieSeries.slices.template
            // change the cursor on hover to make it apparent the object can be interacted with
            .cursorOverStyle = [
                {
                "property": "cursor",
                "value": "pointer"
                }
            ];

            pieSeries.alignLabels = false;
            pieSeries.labels.template.bent = true;
            pieSeries.labels.template.radius = 3;
            pieSeries.labels.template.padding(0,0,0,0);

            pieSeries.ticks.template.disabled = true;

            // Create a base filter effect (as if it's not there) for the hover to return to
            var shadow = pieSeries.slices.template.filters.push(new am4core.DropShadowFilter);
            shadow.opacity = 0;

            // Create hover state
            var hoverState = pieSeries.slices.template.states.getKey("hover"); // normally we have to create the hover state, in this case it already exists

            // Slightly shift the shadow and make it more prominent on hover
            var hoverShadow = hoverState.filters.push(new am4core.DropShadowFilter);
            hoverShadow.opacity = 0.7;
            hoverShadow.blur = 5;

            // Add a legend
            chart1.legend = new am4charts.Legend();

            chart1.data = [
                <?php
                        $size = sizeof($court_cases_status) - 1;
                        for($i=0;$i<sizeof($court_cases_status);$i++)
                        {
                            $court_cases_total = DB::select('select count(*) as total from court_cases where case_status_id = ? order by id asc', [$court_cases_status[$i]->id]);
                            $court_case_status = DB::select('select court_case_status from court_cases_status where id = ? order by id asc', [$court_cases_status[$i]->id]);
                ?>
                            {
                                "case_status": "{{ $court_case_status[0]->court_case_status }}",
                                "cases": "{{ $court_cases_total[0]->total }}",

                        <?php
                    if( $size == $i )
                        echo "}";
                    else
                        echo "},";
                        }
                    ?>
            ];

        }); // end am4core.ready()
    /********************************Cases Status*******************************/


    /********************************Nature of Cases Vs Cases Status*******************************/
    am4core.ready(function() {

    // Themes begin
    am4core.useTheme(am4themes_dataviz);
    am4core.useTheme(am4themes_animated);
    // Themes end

    // Create chart instance
    var chart2 = am4core.create("nature_of_case_vs_case_status", am4charts.XYChart);

    // Add data
    chart2.data = [
        <?php
            $size = sizeof($court_cases_nature) - 1;
            for($i=0;$i<sizeof($court_cases_nature);$i++)
            {
        ?>
                {
                    "nature": "{{ $court_cases_nature[$i]->court_case_nature }}",
            <?php
                    foreach($court_cases_status as $val)
                    {
                        $court_cases = DB::select('select count(*) as total from court_cases where nature_of_case = ? AND case_status_id = ? order by id asc', [$court_cases_nature[$i]->id, $val->id]);
            ?>
                        "{{ $val->court_case_status }}" : {{ $court_cases[0]->total}},
            <?php
                    }
        if( $size == $i )
            echo "}";
        else
            echo "},";
            }
        ?>
    ];

    chart2.legend = new am4charts.Legend();
    chart2.legend.position = "right";

    // Create axes
    var categoryAxis2 = chart2.yAxes.push(new am4charts.CategoryAxis());
    categoryAxis2.dataFields.category = "nature";
    categoryAxis2.title.text = "Nature of Case";
    categoryAxis2.renderer.grid.template.opacity = 0;

    var valueAxis2 = chart2.xAxes.push(new am4charts.ValueAxis());
    valueAxis2.title.text = "Court Cases Status";
    valueAxis2.min = 0;
    valueAxis2.renderer.grid.template.opacity = 0;
    valueAxis2.renderer.ticks.template.strokeOpacity = 0.5;
    valueAxis2.renderer.ticks.template.stroke = am4core.color("#495C43");
    valueAxis2.renderer.ticks.template.length = 10;
    valueAxis2.renderer.line.strokeOpacity = 0.5;
    valueAxis2.renderer.baseGrid.disabled = true;
    valueAxis2.renderer.minGridDistance = 40;

    createSeries2("Ongoing Case", "Ongoing Case");
    createSeries2("Closed Case", "Closed Case");
    createSeries2("Disposed Case", "Disposed Case");
    // Create series
    function createSeries2(field, name) {
        var series2 = chart2.series.push(new am4charts.ColumnSeries());
        series2.dataFields.valueX = field;
        series2.dataFields.categoryY = "nature";
        series2.stacked = true;
        series2.name = name;

        var labelBullet2 = series2.bullets.push(new am4charts.LabelBullet());
        labelBullet2.locationX = 0.5;
        labelBullet2.label.text = "{valueX}";
        labelBullet2.label.fill = am4core.color("#fff");
    }


    }); // end am4core.ready()
/********************************Nature of Cases Vs Cases Status*******************************/

</script>
@endsection
