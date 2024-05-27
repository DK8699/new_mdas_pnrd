@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_admin_uc')

@section('custom_css')
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>

    <link href="{{ asset('mdas_assets/mdbootstrap/css/mdb.min.css') }}" rel="stylesheet">
    <link href="{{asset('mdas_assets/css/animate.css')}}" rel="stylesheet" type="text/css"/>

    <link rel="stylesheet" href="{{ asset('mdas_assets/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('mdas_assets/css/style1.css') }}">
    <style>
        #table th, #table td{
			vertical-align:middle;
		}
		#table-bordered th{
    		text-align:center;
            height:55px;
		}
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
			border: 2px solid #83858f;
        }
		.table-bordered>thead>tr>th {
			background-color: #384059;
			color: #ffffff;
		}
        .dataTables_wrapper .dataTables_filter input {
            border: 2px solid #83858f;
        }
        .mtb40{
            margin-bottom: 40px;
            margin-top: 40px;
        }

        .card{
            border: 1px solid #6b133d33;
            background-color: rgba(248, 245, 245, 0.81);
            max-height: 1080px;
            min-height: 350px;
        }
        .card span {
            font-weight: bolder;
        }

        strong {
            color: red;
        }

        .mb40{
            margin-bottom: 40px;
        }

        .Zebra_DatePicker_Icon_Wrapper {
            width: 100% !important;
        }

        .form-control {
            height: 38px;
            padding: 2px 5px;
            font-size: 12px;
            border-width: 1px;
        }

        label {

            font-size: 15px;
        }

        input[type=number]::-webkit-inner-spin-button,
        input[type=number]::-webkit-outer-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        .btn, label {
            font-size:12pt;
            color:#444;
        }
        .btn{
            padding: 5px 10px;
            background-color: #f1f1f1;
        }
        .btn-info.dropdown-toggle {
            left:-4px;
            border:2px solid #747474;
            background-color: #dedede !important;
        }
        .form-control, button, input, select, textarea {
            height:40px;
            border:2px solid #747474;
            color:#444;
            font-size: 12pt;
            font-weight: bold;
            line-height: inherit;
            background-color:rgba(240, 240, 240, 0.85);
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0);
            padding: 5px 10px;
        }
    </style>
@endsection
@section('content')
    <div class="container" style="margin-top:55px;">
        <div class="row mt10">
            <div class="col-md-12 col-sm-12 col-xs-12" style="border-bottom:3px solid #7c8487;margin:25px 0px 15px 0px;">
                <h2 style="text-transform: uppercase;font-weight:bold;width:100%;">
                    Add Commponent Details
                </h2>
            </div>
        </div><br />
        <form action="" method="POST" id="addComponent" autocomplete="off">
            <div class="row">
                <div class="col-md-4 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>Header</label>
                        <select class="selectpicker form-control select-margin" id="header_id" name="header_id" data-style="btn-info" >
                            <option value="">SELECT HEADER</option>
                            @foreach($header as $list)
                                <option value="{{$list->id }}">{{ $list->header_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-5 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label> Component Name</label>
                        <input type="text" class="form-control" name="component_name" id="component_name" placeholder=""/>
                    </div>
                </div>
                <div class="col-md-3 col-sm-4 col-xs-12">
                    <div class="form-group mt20">
                        <button type="submit" class="btn btn-primary blue-gradient" style="font-weight:bold;margin-top:2px;width:100%;"><i class="fa fa-save"></i>&nbsp;&nbsp;Save Component</button>
                    </div>
                </div>
            </div>
        </form>

        {{-----------------------DATA TABLE-----------------------------------------}}
        <div class="row" style="margin-top:80px; margin-bottom: 55px">
            <div class="col-md-12 col-sm-12 col-xs-12" style="border-bottom:3px solid #7c8487;margin:25px 0px 45px 0px;">
                <h2 style="text-transform: uppercase;font-weight:bold;width:100%;">
                    List of All Components
                </h2>
            </div>
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable1" style="border:4px solid #515a74;background:rgba(255,255,255,0.885);">
                        <thead>
                            <tr>
                                <th width="35px">Sl. No.</th>
                                <th width="325px">Header Name</th>
                                <th>Component Name</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($header as $head)
                            @php
                                $components = DB::select('select * from uc_components where component_header_id=?', [$head->id]);
                            @endphp
                                    <tr>
                                        <td rowspan="<?php echo sizeof($components) + 1; ?>">{{$i}}</td>
                                        <td rowspan="<?php echo sizeof($components) + 1; ?>">{{$head->header_name}}</td>
                                    </tr>
                                        <?php
                                            $components = DB::select('select * from uc_components where component_header_id=?', [$head->id]);
                                            foreach($components as $list)
                                            {
                                        ?>
                                    <tr>
                                        <td>{{ $list->component_name }}</td>
                                    </tr>
                                        <?php
                                            }
                                        ?>
                            @php $i++; @endphp
                        @endforeach
                        </tbody>
                    </table>
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
    <script type="text/javascript" src="{{ asset('mdas_assets/mdbootstrap/js/mdb.min.js') }}"></script>
    <script src="{{ asset('mdas_assets/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
    <script src="{{ asset('mdas_assets/bootstrap-select/dist/js/i18n/defaults-en_US.min.js') }}"></script>

    <script type="application/javascript">

        // $(document).ready(function () {
        //     $('#dataTable1').DataTable({
        //         dom: 'Bfrtip',
        //         buttons: [
        //             'excel', 'copy', 'pdf'
        //         ]
        //     });
        // });

        $("#addComponent").validate({
            rules: {
                component_name: {
                    required: true,
                },
            },
        });

        $('#addComponent').on('submit', function(e){
            e.preventDefault();

            if($('#addComponent').valid()){

                $('.page-loader-wrapper').fadeIn();

                $('.form_errors').remove();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.uc.ucComponent.save')}}',
                    dataType: "json",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {

                            swal("Success", data.msg, "success")
                                .then((value) => {
                                location.reload();
                        });

                        }else{
                            if(data.msg=="VE"){
                                swal("Error", "Validation error.Please check the form correctly!", 'error');
                                $.each(data.errors, function( index, value ) {
                                    $('#'+index).after('<p class="text-danger form_errors">'+value+'</p>');
                                });
                            }else{
                                swal("Error", data.msg, 'error');

                            }
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                    },
                    complete: function (data) {
                        $('.page-loader-wrapper').fadeOut();
                    }
                });
            }
        });

    </script>

@endsection