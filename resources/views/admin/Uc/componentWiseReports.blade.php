@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_admin_uc')

@section('custom_css')
<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <!-- <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/al.css"> -->
    <!-- Bootstrap core CSS -->
    <!-- <link href="{{ asset('mdas_assets/mdbootstrap/css/bootstrap.mi.css') }}" rel="stylesheet"> -->
    <!-- Material Design Bootstrap -->
    <link href="{{ asset('mdas_assets/mdbootstrap/css/mdb.min.css') }}" rel="stylesheet">
    <!-- Your custom styles (optional) -->
    <!-- <link href="{{ asset('mdas_assets/mdbootstrap/css/style.css') }}" rel="stylesheet"> -->

    <link rel="stylesheet" href="{{ asset('mdas_assets/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('mdas_assets/css/style1.css') }}">
<style>
.btn {
    margin: .15rem;
    color: inherit;
    text-transform: uppercase;
    word-wrap: break-word;
    white-space: normal;
    cursor: pointer;
    border: 0;
    border-radius: .125rem;
    box-shadow: 0 2px 5px 0 rgba(0,0,0,.16), 0 2px 10px 0 rgba(0,0,0,.12);
    transition: color .15s ease-in-out,background-color .15s ease-in-out,border-color .15s ease-in-out,box-shadow .15s ease-in-out;
    padding: 5px 10px;
}
.dropdown-menu {
    position:relative;
    left:-2.5px;
}
#table-border th, #table-border td{
    border: 2px solid #83858f;
}
#table-border th{
    vertical-align:bottom;
    text-align:center;
}
.table-bordered>thead>tr>th {
    background-color: #384059;
    color: #ffffff;
}
</style>

@endsection
@section('content')
    <div class="container-fluid">
        <div class="row">
            <ol class="breadcrumb">
                <li><a href="{{route('admin.dashboard')}}">Home</a></li>
                {{--<li class="active"></li>--}}
            </ol>
        </div>
    </div>
    <div class="container">
        <div class="row mt10">
            <div class="col-md-12 col-sm-12 col-xs-12" style="border-bottom:3px solid #7c8487;margin:25px 0px 15px 0px;">
                <h2 style="text-transform: uppercase;font-weight:bold;">
                    Please Select Project and Year
                </h2>
            </div>
        </div>
        <form id="form-1" class="select-project" action="{{route('admin.courtCases.statussearchCourtCase')}}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>Programme or Project :</label>
                        <select id="project_id" class="selectpicker form-control" name="project_id" data-style="btn-info" multiple data-actions-box="true" required>
                            <?php
                                foreach($project as $values)
                                {
                            ?>
                            <option value="{{ Crypt::encrypt($values->id) }}">{{ $values->project_name }}</option>
                            <?php
                                }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>Year :</label>
                        <div id="year_select">
                            <select class="selectpicker form-control" data-style="btn-info" disabled>
                                <option value="">SELECT YEAR</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group mt20">
                        <a href="javascript:load_components()" type="submit" class="btn btn-primary blue-gradient" style="font-weight:bold;margin-top:4px;padding:8.5px;width:100%;"><i class="fa fa-search"></i>&nbsp;&nbsp;Load Components</a>
                    </div>
                </div>
            </div>
        </form>
        <br />
    </div>
    <div class="container">
        <div id="components-wise-data">
        </div>
    </div>
@endsection

@section('custom_js')
<script type="text/javascript" src="{{ asset('mdas_assets/mdbootstrap/js/mdb.min.js') }}"></script>
<script src="{{ asset('mdas_assets/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('mdas_assets/bootstrap-select/dist/js/i18n/defaults-en_US.min.js') }}"></script>
<script type="application/javascript">
        $(document).on('change', '#project_id', function() {
            var p_id = $('#project_id').val();

            if( p_id == "")
            {
                $("#year_select").html('<select class="selectpicker form-control" data-style="btn-info" disabled>\n\
                                            <option value="">SELECT YEAR</option>\n\
                                        </select>');
                $("#extension_district_select").html('<select class="selectpicker form-control" data-style="btn-info" disabled>\n\
                                                        <option value="">SELECT YEAR</option>\n\
                                                    </select>');                        
                $('.selectpicker').selectpicker();
                return false;
            }
            else {
                $("#extension_district_select").html('<select class="selectpicker form-control" data-style="btn-info" disabled>\n\
                                                        <option value="">SELECT YEAR</option>\n\
                                                    </select>'); 
            }
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                url: "{{ route('admin.Uc.getProjectYears') }}",
                data: 'p_id='+p_id,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    $("#year_select").html(data);
                    $('.selectpicker').selectpicker();
                    $('.page-loader-wrapper').fadeOut();
                }
            });
            return false;
        });
        function load_components(){
            if( $('#project_id').val() == "")
            {
                alert("Please Select Project Name...");
                return false;
            }
            if( $("#project_year").val() == "")
            {
                alert("Please Select Year...");
                return false;
            }
            py_id = $("#project_year").val();

            $('.page-loader-wrapper').fadeIn();

            $.ajax({
                url: "{{ route('admin.Uc.getComponentsHeadings') }}",
                method: "GET",
                data: "py_id="+py_id,
                success: function(html) {
                    $("#components-wise-data").html(html);
                    $('.selectpicker').selectpicker();
                    $('.page-loader-wrapper').fadeOut();
                },
                failure: function() {
                }
            });
        }
</script>
<script>
	// function load_components(){
	// 	if( $('#project_load').val() == "" || $('#project_load').val() == null)
	// 	{
	// 		alert("Please Select Project Name...");
	// 		return false;
	// 	}
	// 	if( $("#select_project_years").val() == "" || $('#select_project_years').val() == null )
	// 	{
	// 		alert("Please Select Project Year...");
	// 		return false;
	// 	}
	// 	p_id = $("#select_project_years").val();
	// 	var l = Ladda.create(document.getElementById("form_submit"));
	// 	l.start();
	// 	$.ajax({
	// 		url: "getComponentsHeadings",
	// 		method: "GET",
	// 		data: "p_id="+p_id,
	// 		success: function(html) {
	// 			$("#component_details").html(html);
	// 			l.stop();
	// 		},
	// 		failure: function() {
	// 		}
	// 	});
	// }
	// function form_reset()
	// {
	// 	$(".selectpicker").val('default');
	// 	$(".selectpicker").selectpicker("refresh");
	// 	$("#component_details").html("");
	// }
</script>
@endsection