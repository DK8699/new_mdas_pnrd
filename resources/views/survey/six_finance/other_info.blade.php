@php
    $page_title="six_form_other";
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
        <form action="#" method="POST" id="otherInfoForm">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <a href="{{route('survey.six_finance_form_dashboard')}}" class="btn btn-warning animated-button" style="margin-bottom: 10px;">
                        <i class="fa fa-arrow-left"></i>
                        Back
                    </a>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <label>1. Length in Km of different types of roads maintained by {{$applicable_name}}</label>
                    <table class="table table-bordered">
                        <thead>
                        <tr class="bg-primary">
                            <th>Types of {{$applicable_name}} road</th>
                            @foreach($acts AS $li_act)
                                <th>
                                    {{$li_act->financial_year}}
                                </th>
                            @endforeach
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($cats AS $li_cat)
                            <tr>
                                <th>
                                    <label>{{$li_cat->road_cat_name}} ( in km )</label>
                                    {{-- <input type="hidden" name="road[]" class="form-control" value="{{$li_cat->id}}">--}}
                                </th>
                                @foreach($acts AS $li_act)
                                    <td>
                                        <input type="number" name="length_c_{{$li_cat->id}}_a_{{$li_act->id}}" id="length_c_{{$li_cat->id}}_a_{{$li_act->id}}" class="form-control" value="@if(isset($otherFinalSub["SIX_".$six_finance_final_id]["A_".$li_act->id]["C_".$li_cat->id])){{$otherFinalSub["SIX_".$six_finance_final_id]["A_".$li_act->id]["C_".$li_cat->id]}}@endif" min="0" step="0.01"/>
                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>2. Present status of Account & Audit of {{$applicable_name}}</label>
                        <textarea name="present_account_audit_status" id="present_account_audit_status" class="form-control">@if(isset($otherInfoVal->present_account_audit_status)){{$otherInfoVal->present_account_audit_status}}@endif</textarea>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>3. Whether {{$applicable_name}} have trained Accounts staff of its own?</label>
                        <div class="radio">
                            <label><input type="radio" name="trained_account_staff" value="1" @if(isset($otherInfoVal->trained_account_staff)) @if($otherInfoVal->trained_account_staff==1)checked="checked"@endif @endif>YES</label>
                        </div>
                        <div class="radio" id="trained_account_staff">
                            <label><input type="radio" name="trained_account_staff" value="2" @if(isset($otherInfoVal->trained_account_staff)) @if($otherInfoVal->trained_account_staff==2)checked="checked"@endif @endif>NO</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>4. Whether the following registers maintained in {{$applicable_name}}?</label>
                        {{--<div class="radio">
                            <label><input type="radio" name="different_register_maintained" value="1">YES</label>
                        </div>
                        <div class="radio" id="different_register_maintained">
                            <label><input type="radio" name="different_register_maintained" value="2">NO</label>
                        </div>--}}


                        <ol style="list-style: lower-alpha">

                            @foreach($register_cats AS $reg)

                                <li style="padding-top:7px;padding-bottom:7px" id="register_{{$reg->id}}">
                                    <label style="width:100%">{{$reg->register_cat_name}}</label>
                                    <label class="radio-inline"><input type="radio" name="register_{{$reg->id}}" value="1" @if(isset($otherFinalReg["R_".$reg->id])) @if($otherFinalReg["R_".$reg->id]==1)checked="checked"@endif @endif>YES</label>
                                    <label class="radio-inline"><input type="radio" name="register_{{$reg->id}}" value="2" @if(isset($otherFinalReg["R_".$reg->id])) @if($otherFinalReg["R_".$reg->id]==2)checked="checked"@endif @endif>NO</label>
                                </li>

                            @endforeach
                        </ol>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>5. Whether separate cash book is maintained for devolution of funds in {{$applicable_name}}?</label>
                        <div class="radio">
                            <label><input type="radio" name="seperate_cashbook_maintained" value="1" @if(isset($otherInfoVal->seperate_cashbook_maintained)) @if($otherInfoVal->seperate_cashbook_maintained==1)checked="checked"@endif @endif>YES</label>
                        </div>
                        <div class="radio" id="seperate_cashbook_maintained">
                            <label><input type="radio" name="seperate_cashbook_maintained" value="2" @if(isset($otherInfoVal->seperate_cashbook_maintained)) @if($otherInfoVal->seperate_cashbook_maintained==2)checked="checked"@endif @endif>NO</label>
                        </div>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    @if($alreadySubmitted)
                        {{--<button type="button" class="btn btn-danger animated-button" id="deleteRequest" data-df="OTH" style="margin-bottom: 40px">
                            <i class="fa fa-trash-o"></i>
                            Request to delete Other Info
                        </button>--}}
                        <button type="submit" class="btn animated-button thar-two" style="margin-bottom: 40px">Resubmit</button>
                    @else
                        <button type="submit" class="btn animated-button thar-two" style="margin-bottom: 40px">Submit</button>
                    @endif
                </div>
            </div>
        </form>
    </div>
@endsection

@section('custom_js')
    <script type="application/javascript">

        $('#otherInfoForm').on('submit', function(e){
            e.preventDefault();
            $('.form_errors').remove();
            swal({
                title: "Are you sure?",
                text: "You are sure you want to @if(!$alreadySubmitted){{"submit"}}@else{{"resubmit"}}@endif other info.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willStore) => {
                if (willStore) {

                        $('.page-loader-wrapper').fadeIn();
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            type: "POST",
                            url: '{{route('survey.six_finance_form_other.save')}}',
                            dataType: "json",
                            data: new FormData(this),
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function (data) {
                                if (data.msgType == true) {

                                    $('#otherInfoForm')[0].reset();
                                    @if(!$alreadySubmitted)
								        window.location.replace("{{route('survey.six_finance_form_dashboard')}}");
                                    @else
                                        swal("success", "Successfully re-submitted the other info!","success");
                                        setTimeout(function(){ location.reload(); }, 1200);
                                    @endif

                                }else{

                                    if(data.msg=="VE"){

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