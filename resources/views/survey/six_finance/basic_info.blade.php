@php
    $page_title="six_form_basic";
@endphp

@extends('layouts.app_user')

@section('custom_css')

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
        <form action="#" method="POST" id="basicForm">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <a href="{{route('survey.six_finance_form_dashboard')}}" class="btn btn-warning animated-button" style="margin-bottom: 10px;">
                        <i class="fa fa-arrow-left"></i>
                        Back
                    </a>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>1) Name of {{$applicable_name}}</label>
                        <input type="text" class="form-control" name="app_name" id="app_name" value="@if(isset($basicInfoFill->app_name)){{$basicInfoFill->app_name}}@endif"/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>2) Area of {{$applicable_name}} (in Square K.M)</label>
                        <input type="number" class="form-control" name="app_area" id="app_area" min="0" step=".01" value="@if(isset($basicInfoFill->app_area)){{$basicInfoFill->app_area}}@endif"/>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>3) No. of household in the {{$applicable_name}} (House hold)</label>
                        <input type="number" class="form-control" name="app_house_nos" id="app_house_nos" value="@if(isset($basicInfoFill->app_house_nos)){{$basicInfoFill->app_house_nos}}@endif" min="0"/>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <label>4) Population of the {{$applicable_name}} (as per 2011 census)</label>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Male</th>
                                <th>Female</th>
                                <th class="bg-danger">Total (Male+Female)</th>
                                <th>SC</th>
                                <th>ST</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="number" min="0" class="form-control a__total" name="pop_male" id="pop_male" value="@if(isset($basicInfoFill->pop_male)){{$basicInfoFill->pop_male}}@endif"/>
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control a__total" name="pop_female" id="pop_female" value="@if(isset($basicInfoFill->pop_female)){{$basicInfoFill->pop_female}}@endif"/>
                                </td>
                                <td class="bg-danger">
                                    <input type="number" min="0" class="form-control" name="pop_total" id="pop_total" readonly="readonly" value="@if(isset($basicInfoFill->pop_total)){{$basicInfoFill->pop_total}}@endif"/>
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="pop_sc" id="pop_sc" value="@if(isset($basicInfoFill->pop_sc)){{$basicInfoFill->pop_sc}}@endif"/>
                                </td>
                                <td>
                                    <input type="number" min="0" class="form-control" name="pop_st" id="pop_st" value="@if(isset($basicInfoFill->pop_st)){{$basicInfoFill->pop_st}}@endif"/>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>5) Date of last {{$electionName}} election held</label>
                        <input type="text" class="form-control" name="election_dt" id="election_dt" value="@if(isset($basicInfoFill->election_date)){{$basicInfoFill->election_date}}@endif"/>
                    </div>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>6) Whether the {{$applicable_name}} is housed in its own building ?, if not the amount of rent paid monthly</label>
                        <div class="radio">
                            <label><input type="radio" name="app_household_rented" value="1" @if(isset($basicInfoFill->app_household_rented)) @if($basicInfoFill->app_household_rented==1) checked="checked" @endif @endif>YES</label>
                        </div>
                        <div class="radio">
                            <label><input type="radio" name="app_household_rented" value="2" @if(isset($basicInfoFill->app_household_rented)) @if($basicInfoFill->app_household_rented==2) checked="checked" @endif @endif>NO</label>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12" id="household_div" style="@if(isset($basicInfoFill->app_household_rented)) @if($basicInfoFill->app_household_rented==1) display: none @endif @else display: none @endif">
                    <div class="form-group" style="margin-left:15px">
                        <label>a) Amount of rent paid monthly ( in <i class="fa fa-rupee"></i> )</label>
                        <input type="number" class="form-control" name="app_monthly_rent" id="app_monthly_rent" min="0" step=".01" value="@if(isset($basicInfoFill->app_monthly_rent)){{$basicInfoFill->app_monthly_rent}}@endif"/>
                    </div>
                </div>
                <!------------ NETWORK CONNECTIVITY --------------------------------------------->
                @if($applicable_id==2 || $applicable_id==3)
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <h4 style="border-bottom: 1px solid #ddd">Network Connectivity Details</h4>
                </div>
                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>1) Report of Network connectivity as on date</label>
                        <input type="text" class="form-control" name="network_dt" id="network_dt" value="@if(isset($basicInfoFill->network_dt)){{$basicInfoFill->network_dt}}@endif"/>
                    </div>
                </div>

                <div class="col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label>2) Contact Number of Nodal Person</label>
                        <input type="number" class="form-control" name="nodal_mobile_no" id="nodal_mobile_no" value="@if(isset($basicInfoFill->nodal_mobile_no)){{$basicInfoFill->nodal_mobile_no}}@endif"/>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>3) Status of National Optical Fibre Network (NOFN) in {{$applicable_name}}</label>
                        <div class="radio">
                            <label><input type="radio" name="nofn_status" value="1" @if(isset($basicInfoFill->nofn_status)) @if($basicInfoFill->nofn_status==1) checked="checked" @endif @endif>Equipment Provided</label>
                        </div>
                        <div class="radio">
                            <label><input type="radio" name="nofn_status" value="2" @if(isset($basicInfoFill->nofn_status)) @if($basicInfoFill->nofn_status==2) checked="checked" @endif @endif>Equipment Installed</label>
                        </div>
                        <div class="radio" id="nofn_status">
                            <label><input type="radio" name="nofn_status" value="3" @if(isset($basicInfoFill->nofn_status)) @if($basicInfoFill->nofn_status==3) checked="checked" @endif @endif>Connectivity is Operational</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>4) BSNL has provided internet connectivity in {{$applicable_name}}</label>
                        <div class="radio">
                            <label><input type="radio" name="bsnl_i_con" value="1" @if(isset($basicInfoFill->bsnl_i_con)) @if($basicInfoFill->bsnl_i_con==1) checked="checked" @endif @endif>YES</label>
                        </div>
                        <div class="radio" id="bsnl_i_con">
                            <label><input type="radio" name="bsnl_i_con" value="2" @if(isset($basicInfoFill->bsnl_i_con)) @if($basicInfoFill->bsnl_i_con==2) checked="checked" @endif @endif>NO</label>
                        </div>
                    </div>
                </div>

                <div id="bsnl_div" style="@if(isset($basicInfoFill->bsnl_i_con)) @if($basicInfoFill->bsnl_i_con==2) display: none @endif @else display: none @endif">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label>5) BSNL Connection is operational in {{$applicable_name}}</label>
                            <div class="radio">
                                <label><input type="radio" name="bsnl_c_op" value="1" @if(isset($basicInfoFill->bsnl_c_op)) @if($basicInfoFill->bsnl_c_op==1) checked="checked" @endif @endif>YES</label>
                            </div>
                            <div class="radio" id="bsnl_c_op">
                                <label><input type="radio" name="bsnl_c_op" value="2" @if(isset($basicInfoFill->bsnl_c_op)) @if($basicInfoFill->bsnl_c_op==2) checked="checked" @endif @endif>NO</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12" id="bsnl_c_op_div" style="@if(isset($basicInfoFill->bsnl_c_op)) @if($basicInfoFill->bsnl_c_op==2) display: none @endif @else display: none @endif">
                        <div class="form-group" style="margin-left:15px">
                            <label>a) What is the BSNL internet speed available in mbps?</label>
                            <input type="number" class="form-control" name="bsnl_i_speed" id="bsnl_i_speed" min="0" step=".01" value="@if(isset($basicInfoFill->bsnl_i_speed)){{$basicInfoFill->bsnl_i_speed}}@endif"/>
                        </div>
                        <div class="form-group" style="margin-left:15px">
                            <label>b) Office is using the connection fully in {{$applicable_name}}</label>
                            <div class="radio">
                                <label><input type="radio" name="bsnl_c_fully" value="1" @if(isset($basicInfoFill->bsnl_c_fully)) @if($basicInfoFill->bsnl_c_fully==1) checked="checked" @endif @endif>YES</label>
                            </div>
                            <div class="radio" id="bsnl_c_fully">
                                <label><input type="radio" name="bsnl_c_fully" value="2" @if(isset($basicInfoFill->bsnl_c_fully)) @if($basicInfoFill->bsnl_c_fully==2) checked="checked" @endif @endif>NO</label>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label>6) BSNL Landline phone provided with wifi modem in {{$applicable_name}}</label>
                            <div class="radio">
                                <label><input type="radio" name="bsnl_l_wifi_m" value="1" @if(isset($basicInfoFill->bsnl_l_wifi_m)) @if($basicInfoFill->bsnl_l_wifi_m==1) checked="checked" @endif @endif>YES</label>
                            </div>
                            <div class="radio" id="bsnl_l_wifi_m">
                                <label><input type="radio" name="bsnl_l_wifi_m" value="2" @if(isset($basicInfoFill->bsnl_l_wifi_m)) @if($basicInfoFill->bsnl_l_wifi_m==2) checked="checked" @endif @endif>NO</label>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <!------------ NETWORK CONNECTIVITY ENDED --------------------------------------------->
                <div class="col-md-12 col-sm-12 col-xs-12">
                    @if($alreadySubmitted)
                        {{--<button type="button" class="btn btn-danger animated-button" id="deleteRequest" data-df="BAS" style="margin-bottom: 40px">
                            <i class="fa fa-trash-o"></i>
                            Request to delete basic info
                        </button>--}}
                        <button type="submit" class="btn animated-button thar-two" style="margin-bottom: 40px">
                            <i class="fa fa-save"></i>
                            Resubmit
                        </button>
                    @else
                        <button type="submit" class="btn animated-button thar-two" style="margin-bottom: 40px">
                            <i class="fa fa-save"></i>
                            Submit
                        </button>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection

@section('custom_js')
<script type="application/javascript">
    $('#election_dt').Zebra_DatePicker();
    $('#network_dt').Zebra_DatePicker();

    $('.a__total').on('blur', function(e){
        e.preventDefault();
        var value1= $('#pop_male').val();
        var value2= $('#pop_female').val();

        if(value1 == ''){
            value1=0;
        }if(value2 == ''){
            value2=0;
        }

        $('#pop_total').val(parseInt(value1)+parseInt(value2));
    });

    $('input[type=radio][name=app_household_rented]').change(function() {
        if (this.value == 1) {
            $('#household_div').hide();
            $('#app_monthly_rent').val('');
        }
        else if (this.value == 2) {
            $('#household_div').show();
        }
    });

    $('input[type=radio][name=bsnl_i_con]').change(function() {
        if (this.value == 1) {
            $('#bsnl_div').show();
        }
        else if (this.value == 2) {
            $('#bsnl_div').hide();
            $('#bsnl_i_speed').val('');
            $('input[type=radio][name=bsnl_c_fully]').prop("checked", false);
            $('#bsnl_c_op_div').hide();
        }
    });

    $('input[type=radio][name=bsnl_c_op]').change(function() {
        if (this.value == 1) {
            $('#bsnl_c_op_div').show();
        }
        else if (this.value == 2) {
            $('#bsnl_i_speed').val('');
            $('input[type=radio][name=bsnl_c_fully]').prop("checked", false);
            $('#bsnl_c_op_div').hide();
        }
    });

    $('#basicForm').on('submit', function(e){
        e.preventDefault();
		
		swal({
            title: "Are you sure?",
            text: "You are sure you want to @if(!$alreadySubmitted){{"submit"}}@else{{"resubmit"}}@endif basic info.",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        })
            .then((willStore) => {
            if (willStore) {
				
				$('.form_errors').remove();
				$('.page-loader-wrapper').fadeIn();
				$.ajax({
					headers: {
						'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
					},
					type: "POST",
					url: '{{route('survey.six_finance_form_basic.save')}}',
					dataType: "json",
					data: new FormData(this),
					contentType: false,
					cache: false,
					processData: false,
					success: function (data) {
						if (data.msgType == true) {

							$('#basicForm')[0].reset();
							
							@if(!$alreadySubmitted)
								window.location.replace("{{route('survey.six_finance_form_dashboard')}}");
							@else
								swal("success", "Successfully re-submitted the basic_info!","success");
								setTimeout(function(){ location.reload(); }, 1200);
							@endif
							
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
		} else {
                swal("You have canceled your operation!");
            }
        });
    });

</script>
@endsection