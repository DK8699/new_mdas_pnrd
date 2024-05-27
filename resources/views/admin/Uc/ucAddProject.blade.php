@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_admin_uc')

@section('custom_css')
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="{{asset('mdas_assets/css/animate.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('mdas_assets/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('mdas_assets/css/style1.css') }}">

    <style>
        .card{
            border: 1px solid #6b133d33;
            background-color: rgba(254, 254, 254, 0.91);;
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
            left:0px;
            border:2px solid #747474;
            background-color: #dedede !important;
        }
        .form-control, button, input, select, textarea {
            height: 40px;
            border: 2px solid #747474;
            color: #444;
            font-size: 12pt;
            font-weight: bold;
            line-height: inherit;
            background-color: rgba(251, 251, 251, 0.95);
            box-shadow: inset 0 1px 1px rgba(255, 255, 255, 0);
            padding: 5px 10px;
        }
        .form-control[disabled], .form-control[readonly], fieldset[disabled] .form-control {
            background-color: rgba(251, 251, 251, 0.95);
        }
    </style>
@endsection
@section('content')
            <div class="container" style="margin-top:65px; margin-bottom:50px;">
                <form action="" method="POST" id="addProject" autocomplete="off">
                    <!-- Modal body -->
                            <div class="card"><br />
                                <div class="card-header">
                                    <h4><b>PROJECT / PROGRAMME DETAILS</b></h4>
                                </div>
                                <div class="card-body" style="padding:20px;">
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Project Name <strong>*</strong></label>
                                                <input type="text" class="form-control text-uppercase" name="project_name" id="project_name" placeholder=""/>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Start Date <strong>*</strong></label>
                                                <input type="text" class="form-control" name="start_date" id="start_date"/>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>End Date <strong>*</strong></label>
                                                <input type="text" class="form-control" name="end_date" id="end_date"/>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Sactioned Amount <strong>*</strong></label>
                                                <input type="text" class="form-control money" name="sactioned_amt" id="sactioned_amt"/>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Extension Centre(s)</label>
                                                <!-- <select class="selectpicker form-control select-margin" id="header_id" name="header_id" data-style="btn-info" > -->
                                                <select class="selectpicker form-control" multiple id="extension_id" name="extension_id" data-style="btn-info" >
                                                    @foreach($extension_centre as $extension)
                                                        <option value="{{$extension->id }}">{{ $extension->extension_center_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>District(s)</label>
                                                <select class="selectpicker form-control" data-live-search="true" id="zilla_id" name="zilla_id" multiple data-style="btn-info" >
                                                    @foreach($zilas as $zila)
                                                        <option value="{{$zila->id }}">{{ $zila->zila_parishad_name }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 col-sm-12 col-xs-12">
                                            <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0;">
                                                <div class="form-group">
                                                    <label>GOI Share<strong>*</strong></label>
                                                    <input type="text" class="form-control money" name="goi_share" id="goi_share"/>
                                                </div>
                                            </div>
                                            <div class="col-md-12 col-sm-12 col-xs-12" style="padding:0;">
                                                <div class="form-group">
                                                    <label>GOA Share<strong>*</strong></label>
                                                    <input type="text" class="form-control money" name="goa_share" id="goa_share"/>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-8 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>About Project <strong>*</strong></label>
                                                <textarea class="form-control" name="about_project" id="about_project" style="height:117px;"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12 col-xs-12">
                                            <button type="submit" class="btn btn-primary btn-save pull-right">
                                                <i class="fa fa-send"></i>
                                                    Submit
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                </form>
            </div>
@endsection

@section('custom_js')
<script type="text/javascript" src="{{ asset('mdas_assets/mdbootstrap/js/mdb.min.js') }}"></script>
<script src="{{ asset('mdas_assets/bootstrap-select/dist/js/bootstrap-select.min.js') }}"></script>
<script src="{{ asset('mdas_assets/bootstrap-select/dist/js/i18n/defaults-en_US.min.js') }}"></script>

<script type="application/javascript">

    $('.my-select').selectpicker();
        
    $('#start_date').Zebra_DatePicker({
			/**/
		});
    $('#end_date').Zebra_DatePicker({
			/**/
		});
    
    var indianRupeeFormatter = OSREC.CurrencyFormatter.getFormatter({
                currency: 'INR',
                symbol: '',
            });

        var indianRupeeFormatterText = OSREC.CurrencyFormatter.getFormatter({
                currency: 'INR',
                symbol: '₹',
            });

        $('.money').on('blur', function (e){
            e.preventDefault();
            var value= OSREC.CurrencyFormatter.parse($(this).val(), { locale: 'en_IN' });
            var formattedVal = indianRupeeFormatter(value);
            $(this).val(formattedVal);
        });

        OSREC.CurrencyFormatter.formatAll({
            selector: '.money_txt',
            currency: 'INR'
        });
    
    
    $("#addProject").validate({
        rules: {
            project_name: {
                required: true,
            },
            start_date: {
                required: true,
            },
            end_date: {
                required: true,
            },
            sactioned_amt: {
                required: true,
            },
            goi_share: {
                required: true,
            },
            goa_share: {
                required: true,
            },
            about_project: {
                required: true,
            },
        },
    });
    
    $('#addProject').on('submit', function(e){
            e.preventDefault();
            
            var formData = new FormData(this);
            formData.append('zilla_id', $("#zilla_id").val() );   
            formData.append('extension_id', $("#extension_id").val() );   
            
            if($('#addProject').valid()){

                $('.page-loader-wrapper').fadeIn();

                $('.form_errors').remove();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.uc.ucProject.save')}}',
                    dataType: "json",
                    data: formData,
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
                        $(".selectpicker").val('');
                        $(".selectpicker").selectpicker("refresh");
                        $('.page-loader-wrapper').fadeOut();
                    }
                });
            }
        });

</script>

@endsection