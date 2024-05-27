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
        .fa-ok{
            color:green;
        }
        .notcom{
            border:2px solid red;
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
        <div class="col-md-12 col-sm-12 col-xs-12">
            <a href="{{route('survey.six_finance_form_dashboard')}}" class="btn btn-warning animated-button" style="margin-bottom: 10px;">
                <i class="fa fa-arrow-left"></i>
                Back
            </a>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="form-group">
                <label>Closing balance in {{$applicable_name}}</label>
            </div>
        </div>

        @if($ready)
            <div class="col-md-12 col-sm-12 col-xs-12">
                <form method="post" action="{{url('/save_balance')}}" class="form-horizontal" id="balance-form">
                    @csrf
                    <table class="table table-responsive table-bordered" id="scheme-table">
                        <thead>
                        <tr class="bg-primary">
                            <td>FY</td>
                            @for($i = 0; $i < count($balance_type); $i++)
                                <td>
                                    {{$balance_type[$i]}}
                                </td>
                            @endfor
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($acts AS $li_act)
                            <tr>
                                <th style="width:150px">
                                    <label>{{$li_act->financial_year}} <br/>( in <i class="fa fa-rupee"></i> )</label>
                                    <input type="hidden" name="acts[]" class="form-control" value="{{$li_act->id}}"/>
                                </th>
                                @for($i = 0; $i < count($balance_type); $i++)
                                    <td @if($i==3)class='bg-danger'@endif>
                                        @if($i == 0)
                                            <input autocomplete="off" type="number" name="balance{{$i}}{{$li_act->id}}" data-act="{{$li_act->id}}" class="form-control o__" required='required' id="balance{{$i}}{{$li_act->id}}" step="0.01" @if(isset($balanceInfoFillFinal["O_A_".$li_act->id]))value="{{$balanceInfoFillFinal["O_A_".$li_act->id]}}"@endif/>
                                            <label class="text text-danger balance{{$i}}{{$li_act->id}}" style="display: none"></label>
                                        @else
                                            <input autocomplete="off" type="number" name="balance{{$i}}{{$li_act->id}}" id="balance{{$i}}{{$li_act->id}}"
                                                   @if($i==1)
                                                   @if(isset($grand_tot_revenue["A_".$li_act->id]))
                                                   value="{{$grand_tot_revenue["A_".$li_act->id]}}"
                                                   @endif
                                                   class="form-control o__inflow_{{$li_act->id}}"
                                                   @elseif($i==2)
                                                   @if(isset($grand_tot_expenditure["A_".$li_act->id]))
                                                   value="{{$grand_tot_expenditure["A_".$li_act->id]}}"
                                                   @endif
                                                   class="form-control o__outflow_{{$li_act->id}}"
                                                   @else
                                                   class="form-control o__total_{{$li_act->id}}"
                                                   @if($alreadySubmitted)
                                                   value="{{$balanceInfoFillFinal["C_A_".$li_act->id]}}"
                                                   @endif
                                                   @endif
                                                   readonly="readonly" step=".01" />
                                        @endif
                                    </td>
                                @endfor
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                    <div class="form-group row">
                        <div class="col-md-12">
                            @if($alreadySubmitted)
                               {{-- <button type="button" class="btn btn-danger animated-button" id="deleteRequest" data-df="BAL" style="margin-bottom: 40px">
                                    <i class="fa fa-trash-o"></i>
                                    Request to delete Balance Info
                                </button>--}}

                                <button type="submit" class="btn animated-button thar-two" id="balance-btn" style="margin-bottom: 40px">
                                    <i class="fa fa-save"></i>
                                    Resubmit
                                </button>
                            @else
                                <button type="submit" class="btn animated-button thar-two" id="balance-btn" style="margin-bottom: 40px">
                                    <i class="fa fa-save"></i>
                                    Submit
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        @else
            <div class="col-md-12 col-sm-12 col-xs-12">
                <label>Please Submitted Revenue Info & Expenditure Info First </label>
            </div>
        @endif
    </div>
@endsection

@section('custom_js')
    <script>
        $(document).on('keypress', '.form-control', function () {
            $('.' + $(this).attr('id')).text("");
            $('.' + $(this).attr('id')).css("display", "none");
        });

        $('.o__').on('blur', function(e){
            e.preventDefault();
            var act=$(this).data('act');
            var val= $(this).val();
            if(act){

                //var val= val.toFixed(2);
                var inflow= $('.o__inflow_'+act).val();
                var outflow= $('.o__outflow_'+act).val();

                if(inflow==''){
                    inflow=0;
                }if(outflow==''){
                    outflow=0;
                }if(val==''){
                    val=0;

                    $('.o__total_'+act).val('');
                }else{
                    $('.o__total_'+act).val((parseFloat(val)+parseFloat(inflow)-parseFloat(outflow)).toFixed(2));
                }
            }
        });

        $('#balance-form').on('submit', function (e) {
            e.preventDefault();

            swal({
                title: "Are you sure?",
                text: "You are sure you want to @if(!$alreadySubmitted){{"submit"}}@else{{"resubmit"}}@endif balance info!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willStore) => {
                if (willStore) {
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        },
                        type: "POST",
                        url: "{{url('/save_balance')}}",
                        dataType: "json",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        statusCode: {
                            422: function (data) {
                                // Error...
                                var object = "";
                                $.each(data, function (key, val) {

                                    if (key === "responseText") {
                                        object = jQuery.parseJSON(val);
                                    }

                                });
                                var error = "";
                                $.each(object.errors, function (key, val) {
                                    $('.' + key).removeAttr("style");
                                    $('.' + key).text(val);
                                });
                                swal("In valid data has been provided please check!", "", "error");
                            },
                            200: function (data) {
                                if(data.msgType==true){
                                    @if(!$alreadySubmitted)
                                        window.location.replace("{{route('survey.six_finance_form_dashboard')}}");
                                    @else
										
                                        swal("success", "Successfully re-submitted the Balance Info!","success");
                                        setTimeout(function(){ location.reload(); }, 1200);
                                    @endif
                                }else{
                                    swal(data.msg, "", "error");
                                }

                            },
                            500: function () {
                                swal("Something went wrong. Please try again.", "", "warning");
                            }
                        },
                        error: function (data) {

                        },
                        success: function () {
                            //swal("Data has been successfully saved.!", "", "success");
                        }
                    });
                } else {
                    swal("You have canceled your operation!");
        }
        })



        });
    </script>
    @if(session('message'))
        <script>
            swal("{{session('message')}}", "", "success");
        </script>
    @endif
    @if(session('error'))
        <script>
            swal("{{session('error')}}", "", "error");
        </script>
    @endif
@endsection

