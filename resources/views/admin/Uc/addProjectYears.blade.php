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
            <div class="container" style="margin-top:65px;">
                <form action="" method="POST" id="addProjectYear" autocomplete="off">
                    <!-- Modal body -->
                            <div class="card" style="width:755px;margin:0 auto;"><br />
                                <div class="card-header">
                                    <h4><b>ADD PROJECT / PROGRAMME YEAR</b></h4>
                                </div><br />
                                <div class="card-body" style="padding:20px;">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Project(s) / Programme(s)</label>
                                                <select class="selectpicker form-control" id="project_id" name="project_id" data-style="btn-info">
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
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Year <strong>*</strong></label>
                                                <input type="text" class="form-control" name="year" id="year" placeholder="Enter Year Name" maxlength="25" />
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row"><br />
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>Start Date <strong>*</strong></label>
                                                <input type="text" class="form-control" name="start_date" id="start_date"/>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-12 col-xs-12">
                                            <div class="form-group">
                                                <label>End Date <strong>*</strong></label>
                                                <input type="text" class="form-control" name="end_date" id="end_date"/>
                                            </div>
                                        </div>
                                    </div><br />
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

    
    $("#addProjectYear").validate({
        rules: {
            project_id: {
                required: true,
            },
            year: {
                required: true,
            },
            start_date: {
                required: true,
            },
            end_date: {
                required: true,
            }
        }
    });
    
    $('#addProjectYear').on('submit', function(e){
            e.preventDefault();

            // var formData = new FormData(this);
            // formData.append('zilla_id', $("#zilla_id").val() );   
            // formData.append('extension_id', $("#extension_id").val() );   

            if($('#addProjectYear').valid()){
                $('.page-loader-wrapper').fadeIn();
                $('.form_errors').remove();

                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.Uc.saveProjectYears')}}',
                    dataType: "json",
                    data: new FormData(this),
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function (data) {
                        if (data.msgType == true) {
                            swal("Success", data.msg, "success").then((value) => {
                                location.reload();
                            });
                        }
                        else{
                            if(data.msg=="VE"){
                                swal("Error", "Validation error.Please check the form correctly!", 'error');
                                $.each(data.errors, function( index, value ) {
                                    $('#'+index).after('<p class="text-danger form_errors">'+value+'</p>');
                                });
                            } else {
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
        $(document).on('change', '#project_year', function() {
            var py_id = $('#project_year').val();
            if( py_id == "")
            {
                $("#year_select").html('<select class="selectpicker form-control" data-style="btn-info" disabled>\n\
                                            <option value="">SELECT YEAR</option>\n\
                                        </select>');
                $('.selectpicker').selectpicker();
                return false;
            }
            $('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: 'GET',
                url: "{{ route('admin.Uc.loadExtensionsDistricts') }}",
                data: 'py_id='+py_id,
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    $("#extension_district_select").html(data);
                    $('.selectpicker').selectpicker();
                    $('.page-loader-wrapper').fadeOut();
                }
            });
            return false;
        });
</script>

@endsection