@php
    $page_title="six_form";
@endphp

@extends('layouts.app_user')

@section('custom_css')
    <style>
        .m-bor{
            border:2px solid #ddd;
        }
        .fa-info{
            color:red;
        }
        .fa-check{
            color:green;
        }
        .okcom{
            border:2px solid green;
            box-shadow: 0px 0px 5px 2px #eadddd;
            border-radius: 0;
        }
        .notcom{
            border:2px solid red;
            box-shadow: 0px 0px 5px 2px #eadddd;
            border-radius: 0;
        }
        .mtb20{
            margin-top: 20px;
            margin-bottom: 20px;
        }
        .badge{
            border-radius: 0px;
            color: green;
			
            background-color: #fff;
            border:2px solid green;
        }
    </style>
@endsection

@section('content')

     <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="active">Sixth Assam State Finance</li>
        </ol>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12 m-bor">
        <div class="col-md-12 mt20 text-center">
            <h4>Sixth Assam State Finance Commission</h4>
            <h4>Questionnaire for {{$applicable_name}}</h4>
            <h4>(Applicable to {{$applicable_name}})</h4>
            <hr/>
        </div>

        @if(!empty($sixFinanceFinal))
            <div class="row text-center mt20">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="col-md-12 col-sm-12 col-xs-12 mtb20 text-left">
                        @if($sixFinanceFinal->district_name)
                            <span class="badge">District : {{$sixFinanceFinal->district_name}}</span>
                        @endif

                        @if($sixFinanceFinal->zila_parishad_name)
                            <span class="badge">Zila Parishad Name : {{$sixFinanceFinal->zila_parishad_name}}</span>
                        @endif
                        @if($sixFinanceFinal->anchalik_parishad_name)
                            <span class="badge">Anchalik Parishad Name : {{$sixFinanceFinal->anchalik_parishad_name}}</span>
                        @endif
                        @if($sixFinanceFinal->gram_panchayat_name)
                            <span class="badge">GP : {{$sixFinanceFinal->gram_panchayat_name}}</span>
                        @endif

                        @if($sixFinanceFinal->council_name)
                            <span class="badge">Council Name : {{$sixFinanceFinal->council_name}}</span>
                        @endif
                        @if($sixFinanceFinal->block_name)
                            <span class="badge">Block Name : {{$sixFinanceFinal->block_name}}</span>
                        @endif
                        @if($sixFinanceFinal->vcdc_vdc_mac_name)
                            <span class="badge">VCDC/VDC/MAC : {{$sixFinanceFinal->vcdc_vdc_mac_name}}</span>
                        @endif
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <p style="font-weight: 700;color:red;text-align: left;margin-bottom: 20px">Note: Red Border Forms are not complete. Final Submit Button is active only when all forms borders are submitted.</p>
                    </div>

                    <div class="col-md-3 col-sm-4 col-xs-6">
                        <a href="{{route('survey.six_finance_form_basic')}}" class="thumbnail text-uppercase @if($sixFinanceFinal->basic_info){{"okcom"}}@else{{"notcom"}}@endif">
                            <p>
                                @if($sixFinanceFinal->basic_info)
                                    <i class="fa fa-check fa-2x"></i>
                                @else
                                    <i class="fa fa-info fa-2x"></i>
                                @endif
                            </p>
                            <p>Basic Info</p>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-4 col-xs-6">
                        <a href="{{route('survey.six_finance_form_staff')}}" class="thumbnail text-uppercase @if($sixFinanceFinal->staff_info){{"okcom"}}@else{{"notcom"}}@endif">
                            <p>
                                @if($sixFinanceFinal->staff_info)
                                    <i class="fa fa-check fa-2x"></i>
                                @else
                                    <i class="fa fa-info fa-2x"></i>
                                @endif
                            </p>
                            <p>Staff Info</p>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-4 col-xs-6">
                        <a href="{{route('survey.six_finance_form_revenue')}}" class="thumbnail text-uppercase @if($sixFinanceFinal->revenue_info){{"okcom"}}@else{{"notcom"}}@endif">
                            <p>
                                @if($sixFinanceFinal->revenue_info)
                                    <i class="fa fa-check fa-2x"></i>
                                @else
                                    <i class="fa fa-info fa-2x"></i>
                                @endif
                            </p>
                            <p>Revenue Info</p>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-4 col-xs-6">
                        <a href="{{url('/expenditure')}}" class="thumbnail text-uppercase @if($sixFinanceFinal->expenditure_info){{"okcom"}}@else{{"notcom"}}@endif">
                            <p>
                                @if($sixFinanceFinal->expenditure_info)
                                    <i class="fa fa-check fa-2x"></i>
                                @else
                                    <i class="fa fa-info fa-2x"></i>
                                @endif
                            </p>
                            <p>Expenditure Info</p>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-4 col-xs-6">
                        <a href="{{route('balance')}}" class="thumbnail text-uppercase @if($sixFinanceFinal->balance_info){{"okcom"}}@else{{"notcom"}}@endif">
                            <p>
                                @if($sixFinanceFinal->balance_info)
                                    <i class="fa fa-check fa-2x"></i>
                                @else
                                    <i class="fa fa-info fa-2x"></i>
                                @endif
                            </p>
                            <p>Balance Info</p>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-4 col-xs-6">
                        <a href="{{route('survey.six_finance_form_other')}}" class="thumbnail text-uppercase @if($sixFinanceFinal->other_info){{"okcom"}}@else{{"notcom"}}@endif">
                            <p>
                                @if($sixFinanceFinal->other_info)
                                    <i class="fa fa-check fa-2x"></i>
                                @else
                                    <i class="fa fa-info fa-2x"></i>
                                @endif
                            </p>
                            <p>Other Info</p>
                        </a>
                    </div>
                    <div class="col-md-3 col-sm-4 col-xs-6">
                        <a href="{{route('new_scheme')}}" class="thumbnail text-uppercase @if($sixFinanceFinal->five_year_info){{"okcom"}}@else{{"notcom"}}@endif">
                            <p>
                                @if($sixFinanceFinal->five_year_info)
                                    <i class="fa fa-check fa-2x"></i>
                                @else
                                    <i class="fa fa-info fa-2x"></i>
                                @endif
                            </p>
                            <p>Next 5 Year Info</p>
                        </a>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <form action="#" method="POST" id="finalSubmit">
                        @if($sixFinanceFinal['final_submission_status'])
                            <p class="text-danger">
                                Form data successfully submitted.
                            </p>
                        @endif
                        <button type="submit" class="btn btn-lg animated-button thar-two" style="margin: 10px auto 80px auto;"
                                @if(!$sixFinanceFinal['basic_info'] || !$sixFinanceFinal['staff_info'] || !$sixFinanceFinal['revenue_info'] || !$sixFinanceFinal['expenditure_info'] || !$sixFinanceFinal['balance_info'] || !$sixFinanceFinal['other_info'] || !$sixFinanceFinal['five_year_info'])
                                disabled="disabled"
                                @elseif($sixFinanceFinal['final_submission_status'])
                                disabled="disabled"
                                @endif >
                            Final Submit
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('custom_js')
    <script type="application/javascript">
        $('#finalSubmit').on('submit', function(e){
            e.preventDefault();

            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "GET",
                url: '{{route('survey.six_finance_form_dashboard.save')}}',
                dataType: "json",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.msgType == true) {
                        swal("Success", data.msg, 'success');
						setTimeout(function(){ location.reload(); }, 1000);
                    }else{
                        swal("", data.msg, 'error');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                },
                complete: function (data) {
                    $('.page-loader-wrapper').fadeOut();
                }
            });
        });
    </script>
@endsection
