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
                <label>New Schemes proposed for  next 5 years in {{$applicable_name}} (Showing details with estimated cost)</label>
            </div>
        </div>
        <div class="col-md-12 col-sm-12 col-xs-12">
            <form method="post" action="{{url('/save_scheme_cost')}}" class="form-horizontal" id="scheme-proposal-form">
                @csrf
                <table class="table table-responsive table-bordered" id="scheme-table">
                    <thead>
                    <tr class="bg-primary">
                        <th></th>
                        @foreach($financial_years AS $year)
                            <td>
                                {{$year['financial_year']}} ( in <i class="fa fa-rupee"></i> )
                            </td>
                        @endforeach
                    </tr>
                    </thead>
                    @php $k=1; @endphp
                    @foreach($entities AS $value)
                        {{--@if($value['id'] < 27)--}}
                        <tr>
                            <th>
                                <label>{{$k}}) {{$value['entity_name']}}</label>
                            </th>
                            @foreach($financial_years AS $year)
                                <td>
                                    <input type="number" name="estimated_cost{{$value['id']}}{{$year['id']}}" class="form-control" required='required' id="estimated_cost{{$value['id']}}{{$year['id']}}"
                                           @if(isset($dataFillFinal["E_".$value->id]["A_".$year->id])) value="{{$dataFillFinal["E_".$value->id]["A_".$year->id]}}" @elseif($alreadySubmitted) value="0" @endif min="0" step="0.01"/>
                                    <label class="text text-danger estimated_cost{{$value['id']}}{{$year['id']}}" style="display: none"></label>
                                </td>
                            @endforeach
                        </tr>
                        @php $k++; @endphp
                        {{--@endif--}}
                    @endforeach

                </table>
                @if($alreadySubmitted!=1)
                    <div class="form-group row">
                        <div class="col-md-6">
                            <button class="btn animated-button btn-warning"  type="button" data-toggle="modal" data-target="#modalSocial"><i class="fa fa-plus"></i> ADD MORE FOR OTHERS</button>
                        </div>
                    </div>
                @endif
                <div class="form-group row">
                    <div class="col-md-12">

                        @if($alreadySubmitted)
                            {{-- <button type="button" class="btn btn-danger animated-button" id="deleteRequest" data-df="NEX" style="margin-bottom: 40px">
                                <i class="fa fa-trash-o"></i>
                                Request to delete next 5 year info
                            </button> --}}

                            <button type="submit" class="btn animated-button thar-two" id="scheme-proposal-btn" style="margin-bottom: 40px">
                                <i class="fa fa-save"></i>
                                Resubmit
                            </button>
                        @else
                            <button type="submit" class="btn animated-button thar-two" id="scheme-proposal-btn" style="margin-bottom: 40px">
                                <i class="fa fa-save"></i>
                                Submit
                            </button>
                        @endif
                    </div>
                </div>
            </form>
        </div>
        <!--Modal: modalSocial-->
        <div class="modal fade" id="modalSocial" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog cascading-modal" role="document">

                <!--Content-->
                <div class="modal-content">

                    <!--Header-->
                    <div class="modal-header light-blue darken-3 white-text">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>

                    <!--Body-->
                    <div class="modal-body mb-0 text-center">
                        <form method="post" action="{{url('/add_new_others')}}" class="form-horizontal" id="other_option_form">
                            @csrf
                            <div class="form-group row">
                                <label class="control-label text text-primary">Other Option Name</label>
                                <div class="col-md-12">
                                    <input type="text" name="other_option_name" id="other_option_name" class="form-control other-option">
                                    <label class="other_option_name text text-danger"></label>
                                </div>
                            </div>
                            <div class="form-group row">
                                <div class="col-md-4">
                                    <input class="btn btn-primary btn-sm" type="submit" value="ADD OTHER OPTION">
                                </div>
                            </div>
                        </form>

                    </div>

                </div>
                <!--/.Content-->

            </div>
        </div>
        <!--Modal: modalSocial-->
    </div>
@endsection

@section('custom_js')
    <script>

        $(document).on('keypress', '.form-control', function () {
            $('.' + $(this).attr('id')).text("");
            $('.' + $(this).attr('id')).css("display", "none");
        });

        $('#scheme-proposal-form').on('submit', function (e) {
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: "You are sure you want to @if(!$alreadySubmitted){{"submit"}}@else{{"resubmit"}}@endif next 5 years info!",
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
                        url: "{{url('/save_scheme_cost')}}",
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
                                if (data.msg) {
                                    swal(data.msg, "", "warning");
                                } else {
                                    @if(!$alreadySubmitted)
                                        window.location.replace("{{route('survey.six_finance_form_dashboard')}}");
                                    @else
                                        swal("success", "Successfully re-submitted the next 5 years info!","success");
                                        setTimeout(function(){ location.reload(); }, 1200);
                                    @endif
                                }

                            },
                            500: function () {
                                swal("Something went wrong. Please try again.", "", "warning");
                            }
                        },
                        error: function (data) {

                        },
                        success: function (data) {
                           /* if (data.msg) {
                                swal(data.msg, "", "warning");
                            } else {
                                swal("Data has been successfully saved.!", "", "success");
                                window.location.replace("{{route('survey.six_finance_form_dashboard')}}");
                            }*/
                        }
                    });
                } else {
                    swal("You have canceled your operation!");
        }
        })



        });
        $('#other_option_form').on('submit', function (e) {
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: "You are about to save other record!",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            })
                .then((willStore) => {
                if (willStore) {
                    /*$.ajax({
                     headers: {
                     'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                     },
                     type: "POST",
                     url: "{{url('/add_new_others')}}",
                     dataType: "json",
                     data: new FormData(this),
                     contentType: false,
                     cache: false,
                     processData: false,
                     statusCode: {
                     422: function (data) {

                     var object = "";
                     $.each(data, function (key, val) {

                     if (key === "responseText") {
                     object = jQuery.parseJSON(val);
                     }

                     });
                     var error = "";
                     $.each(object.errors, function (key, val) {

                     $('.' + key).text(val);
                     });
                     swal("In valid data has been provided please check!", "", "error");
                     },
                     200: function (data) {
                     if (data.msg) {
                     swal(data.msg, "", "warning");
                     } else {
                     swal("Data has been successfully saved.!", "", "success");
                     window.location.replace("{{route('survey.six_finance_form_dashboard')}}");
                     }

                     },
                     500: function () {
                     swal("Something went wrong. Please try again.", "", "warning");
                     }
                     },
                     error: function (data) {

                     },
                     success: function () {
                     swal("Data has been successfully saved.!", "", "success");
                     }
                     });*/

                    swal("Information", "Currently the proposed entities addition is on hold. Consult with admin for more details.", "info");
                } else {
                    swal("You have canceled your operation!");
                }
        })



        });
        $(document).on('keypress', '.form-control', function () {
            $('.' + $(this).attr('name')).text("");
        });</script>
    @if(session('message'))
        <script>
            swal("{{session('message')}}", "error");</script>
    @endif
    @if(session('message'))
        <script>
            swal("{{session('error')}}", "error");
        </script>
    @endif
@endsection

