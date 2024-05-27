@php
    $page_title="six_form";
@endphp

@extends('layouts.app_user')

@section('custom_css')
    <style>

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
                <label>1. Revenue Expenditure in {{$applicable_name}} (Showing details with estimated cost)</label>
            </div>
        </div>
        <form method="post" action="{{url('/save_expenditure')}}" class="form-horizontal" id="expenditure-form">
            <div class="col-md-12 col-sm-12 col-xs-12">
                @csrf
                @foreach($category AS $main_cat)
                    @php $i = 'a'; @endphp
                    @if($main_cat['id'] != 2 && $main_cat['id'] != 3)
                        @if($main_cat['list_order'] == 1)
                            <div class="row">
                                <div class="col-md-12 col-sm-12 col-xs-12">
                                    <label>A. Revenue Account</label>
                                </div>
                            </div>
                        @endif
                        <div class="table-responsive">
                            <table class="table table-bordered" id="scheme-table">
                                <thead>
                                <tr class="bg-primary">
                                    <th></th>
                                    @foreach($financial_years AS $year)
                                        <td>
                                            {{$year['financial_year']}} ( in <i class="fa fa-rupee"></i> )
                                            <input type="hidden" name="acts[]" class="form-control" value="{{$year['id']}}" required/>
                                        </td>
                                        @php
                                            $ad_e_tot[$main_cat['list_order']][$year->id]=0;
                                        @endphp
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>

                                @if($main_cat['list_order'] == 1)
                                    <tr class="bg-info">
                                        <td colspan="6">A. {{$main_cat['list_order']}}) {{$main_cat['category_name']}}</td>
                                    </tr>
                                @else
                                    <tr class="bg-info">
                                        <td colspan="6">A. {{$main_cat['list_order']}}) {{$main_cat['category_name']}}</td>
                                    </tr>
                                @endif


                                @foreach($expenditure AS $value)
                                    @if($main_cat['id'] == $value['category'])
                                        <tr>
                                            <td>
                                                {{$i++}}) {{$value['expenditure_name']}}
                                                <input type="hidden" name="category_expenditure_id[]" value="{{$value['category_expenditure_id']}}" class="form-control"/>
                                            </td>
                                            @foreach($financial_years AS $year)
                                                <td>
                                                    <input type="text" name="expenditure{{$value['expenditure']}}{{$value['category']}}{{$year['id']}}" class="form-control text{{$main_cat['list_order']}}{{$year['id']}}" @if(isset($dataFillFinal["E_".$value['category_expenditure_id']]["A_".$year['id']]))value="{{$dataFillFinal["E_".$value['category_expenditure_id']]["A_".$year['id']]}}"@elseif($alreadySubmitted)value="0"@endif autocomplete="off" required/>
                                                    <label class="text text-danger expenditure{{$value['expenditure']}}{{$value['category']}}{{$year['id']}}" style="display: none"></label>
                                                </td>
                                                @if(isset($dataFillFinal["E_".$value['category_expenditure_id']]["A_".$year['id']]))
                                                    @php
                                                        $ad_e_tot[$main_cat['list_order']][$year->id]=$ad_e_tot[$main_cat['list_order']][$year->id]+(float)$dataFillFinal["E_".$value['category_expenditure_id']]["A_".$year['id']];
                                                    @endphp
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endif
                                @endforeach

                                {{------------------------------SUB TOTAL 1 A1------------------------------------------------}}
                                @if($main_cat['list_order'] == 1)
                                    @if($alreadySubmitted !=1)
                                        <tr>
                                            <td colspan="7">
                                                <button class="btn animated-button btn-warning"  type="button" data-toggle="modal" data-target="#modalOther">
                                                    <i class="fa fa-plus"></i>
                                                    ADD MORE FOR OTHERS
                                                </button>
                                            </td>
                                        </tr>
                                    @endif
                                    <tr class="bg-danger">
                                        <th style="width:30em;">
                                            Sub-Total (1. A{{$main_cat['list_order']}})
                                        </th>
                                        @foreach($financial_years AS $year)
                                            <td>
                                                <input type="text" name="a1" class="form-control text_total{{$main_cat['list_order']}}{{$year['id']}}" @if(isset($ad_e_tot[$main_cat['list_order']][$year->id]))value="{{$ad_e_tot[$main_cat['list_order']][$year->id]}}"@endif readonly="readonly">
                                            </td>
                                        @endforeach
                                    </tr>
                                @endif
                                {{------------------------------SUB TOTAL 1 A2------------------------------------------------}}
                                @if($main_cat['list_order'] == 2)
                                    <tr class="bg-danger">
                                        <th style="width:27em;">
                                            Sub-Total (1.A{{$main_cat['list_order']}})
                                        </th>
                                        @foreach($financial_years AS $year)
                                            <th>
                                                <input type="text" name="a1" class="form-control text_total{{$main_cat['list_order']}}{{$year['id']}}" required readonly="readonly" @if(isset($ad_e_tot[$main_cat['list_order']][$year->id]))value="{{$ad_e_tot[$main_cat['list_order']][$year->id]}}"@endif>
                                            </th>
                                        @endforeach
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                    @endif

                    {{--------------------------------------------------------------------------------------------------------}}
                    {{--------------------------------------- Expenditure Against CSS ----------------------------------------}}
                    {{--------------------------------------------------------------------------------------------------------}}

                    @if($main_cat['id'] == 2)
                        <div class="table-responsive">
                            <table class="table table-bordered" id="scheme-table">
                                <thead>
                                <tr class="bg-primary">
                                    <th></th>
                                    @foreach($financial_years AS $year)
                                        <td>
                                            {{$year['financial_year']}} ( in <i class="fa fa-rupee"></i> )
                                            <input type="hidden" name="acts[]" class="form-control" required value="{{$year['id']}}">
                                        </td>
                                        @php $ad_css_tot[$main_cat['list_order']][$year->id]=0; @endphp
                                    @endforeach
                                </tr>
                                </thead>
                                <tr>
                                    <th style="width:35em;">A.{{$main_cat['list_order']}}&nbsp;{{$main_cat['category_name']}}
                                        <input type="hidden" name="category_expenditure_id[]" value="34" class="form-control"></th>
                                    @foreach($financial_years AS $year)
                                        <th>
                                            <input type="text" name="expenditure2{{$year['id']}}" class="form-control text{{$main_cat['list_order']}}{{$year['id']}}" required @if(isset($dataFillFinal["E_34"]["A_".$year['id']]))value="{{$dataFillFinal["E_34"]["A_".$year['id']]}}"@elseif($alreadySubmitted)value="0"@endif autocomplete="off"/>
                                            <label class="text text-danger expenditure2{{$year['id']}}" style="display: none"></label>
                                        </th>

                                        @if(isset($dataFillFinal["E_34"]["A_".$year['id']]))
                                            @php
                                                $ad_css_tot[$main_cat['list_order']][$year->id]=$ad_css_tot[$main_cat['list_order']][$year->id]+(float)$dataFillFinal["E_34"]["A_".$year['id']];
                                            @endphp
                                        @endif
                                    @endforeach
                                </tr>
                                @if($main_cat['list_order'] == 3)
                                    <tr class="bg-danger">
                                        <th>
                                            <label>2. Total Revenue Expenditure (A.1+A.2+14.A3)</label>
                                        </th>
                                        @foreach($financial_years AS $year)
                                            <th>
                                                <input type="text" name="a1" class="form-control total_revenue_{{$year['id']}}" readonly="readonly"

                                                       @if(isset($ad_e_tot[1][$year->id]) && isset($ad_e_tot[2][$year->id]) && isset($ad_css_tot[$main_cat['list_order']][$year->id]))
                                                       value="{{(float)$ad_e_tot[1][$year->id]+(float)$ad_e_tot[2][$year->id]+(float)$ad_css_tot[$main_cat['list_order']][$year->id]}}"
                                                        @endif
                                                />
                                            </th>
                                        @endforeach
                                    </tr>
                                @endif
                            </table>
                        </div>
                    @endif

                    {{-----------------------------------CAPITAL EXPENDITURE---------------------------------------------------}}
                    @if($main_cat['id'] == 3)
                        <div class="row">
                            <div class="col-md-12 col-sm-12 col-xs-12">
                                <label>3. {{$main_cat['category_name']}} of {{$applicable_name}}</label>
                            </div>
                        </div>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="scheme-table">
                                <thead>
                                <tr class="bg-primary">
                                    <th></th>
                                    @foreach($financial_years AS $year)
                                        <td>
                                            {{$year['financial_year']}} ( in <i class="fa fa-rupee"></i> )
                                            <input type="hidden" name="acts[]" class="form-control" required value="{{$year['id']}}">
                                        </td>
                                        @php $cap_e_tot[$year->id]= 0;@endphp
                                    @endforeach
                                </tr>
                                </thead>
                                <tbody>

                                @foreach($expenditure AS $value)
                                    @if($main_cat['id'] == $value['category'])
                                        <tr>
                                            <td>{{$i++}}) {{$value['expenditure_name']}}<input type="hidden" name="category_expenditure_id[]" value="{{$value['category_expenditure_id']}}" class="form-control"></td>
                                            @foreach($financial_years AS $year)
                                                <td>
                                                    <input type="text" name="expenditure{{$value['expenditure']}}{{$value['category']}}{{$year['id']}}" class="form-control capital{{$year['id']}}" required @if(isset($dataFillFinal["E_".$value['category_expenditure_id']]["A_".$year['id']]))value="{{$dataFillFinal["E_".$value['category_expenditure_id']]["A_".$year['id']]}}"@elseif($alreadySubmitted)value="0"@endif autocomplete="off" />
                                                    <label class="text text-danger expenditure{{$value['expenditure']}}{{$value['category']}}{{$year['id']}}" style="display: none"></label>
                                                </td>

                                                @if(isset($dataFillFinal["E_".$value['category_expenditure_id']]["A_".$year['id']]))
                                                    @php
                                                        $cap_e_tot[$year->id]=$cap_e_tot[$year->id]+(float)$dataFillFinal["E_".$value['category_expenditure_id']]["A_".$year['id']];
                                                    @endphp
                                                @endif
                                            @endforeach
                                        </tr>
                                    @endif
                                @endforeach
                                @if($alreadySubmitted !=1)
                                    <tr>
                                        <td colspan="7">
                                            <button class="btn animated-button btn-warning"  type="button" data-toggle="modal" data-target="#modalOther">
                                                <i class="fa fa-plus"></i>
                                                ADD MORE FOR OTHERS
                                            </button>
                                        </td>
                                    </tr>
                                @endif
                                @if($main_cat['list_order'] == 4)
                                    <tr class="bg-danger">
                                        <th>
                                            <label>Total Capital Expenditure (Sub-Total 3)</label>
                                        </th>
                                        @foreach($financial_years AS $year)
                                            <th>
                                                <input type="text" name="a1" class="form-control total_capital{{$year['id']}}" readonly="readonly" autocomplete="off" @if(isset($cap_e_tot[$year->id]))value="{{$cap_e_tot[$year->id]}}"@endif />
                                            </th>
                                        @endforeach
                                    </tr>
                                @endif
                                </tbody>
                            </table>
                        </div>
                        @if($main_cat['list_order'] == 4)
                            <div class="table-responsive">
                                <table class="table table-responsive table-bordered" id="scheme-table">
                                    <thead>
                                    <tr class="bg-primary">
                                        <th></th>
                                        @foreach($financial_years AS $year)
                                            <td>
                                                {{$year['financial_year']}} ( in <i class="fa fa-rupee"></i> )
                                                <input type="hidden" name="acts[]" class="form-control" required value="{{$year['id']}}">
                                            </td>
                                        @endforeach
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr class="bg-danger">
                                        <th>
                                            <label>Grand Total Expenditure (Total Revenue Expenditure + Total Capital Expenditure)</label>
                                        </th>
                                        @foreach($financial_years AS $year)
                                            <th>
                                                <input type="text" name="a1" class="form-control grand{{$year['id']}}" readonly="readonly"

                                                       @if(isset($ad_e_tot[1][$year->id]) && isset($ad_e_tot[2][$year->id]) && isset($ad_css_tot[3][$year->id]) && isset($cap_e_tot[$year->id]))
                                                       value="{{(float)$ad_e_tot[1][$year->id]+(float)$ad_e_tot[2][$year->id]+(float)$ad_css_tot[3][$year->id]+(float)$cap_e_tot[$year->id]}}"
                                                        @endif
                                                />
                                            </th>
                                        @endforeach
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endif
                @endforeach

                <div class="form-group row">
                    <div class="col-md-12">
                        @if($alreadySubmitted)
                            {{--<button type="button" class="btn btn-danger animated-button" id="deleteRequest" data-df="EXP" style="margin-bottom: 40px">
                                <i class="fa fa-trash-o"></i>
                                Request to delete Expenditure Info
                            </button>--}}

                            <p style="font-weight: 700;color:red">Note: If you resubmit the expenditure info you have to fill the balance info again.</p>

                            <button type="submit" class="btn animated-button thar-two" id="expenditure-btn" style="margin-bottom: 40px">
                                <i class="fa fa-save"></i>
                                Resubmit
                            </button>
                        @else
                            <button type="submit" class="btn animated-button thar-two" id="expenditure-btn" style="margin-bottom: 40px">
                                <i class="fa fa-save"></i>
                                Submit
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        </form>
    </div>

    <!--Modal: modalSocial-->
    <div class="modal fade" id="modalOther" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog cascading-modal" role="document">

            <!--Content-->
            <div class="modal-content">

                <!--Header-->
                <div class="modal-header light-blue darken-3 white-text">
                    <h4 class="title"><i class="fa fa-edit"></i>Add Other Specification!</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                </div>

                <!--Body-->
                <div class="modal-body mb-0 text-center">

                    <form method="post" action="" class="form-horizontal" id="other_category_form">
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="text text-primary">Category</label>
                            </div>
                            <div class="col-md-6">
                                <select class="form-control" name="category" id="category">
                                    <option value="">---SELECT CATEGORY---</option>
                                    @foreach($category AS $value)
                                        @if($value['id'] > 2)
                                            <option value="{{$value['id']}}">{{$value['category_name']}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <label class="text text-danger category"></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-4">
                                <label class="text text-primary">Other Specification</label>
                            </div>
                            <div class="col-md-6">
                                <input type="text" name="other_specify" class="form-control" id="other_specify">
                                <label class="text text-danger other_specify"></label>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-3">
                                <input type="submit" class="btn btn-primary" value="SAVE">
                            </div>
                        </div>
                    </form>


                </div>

            </div>
            <!--/.Content-->

        </div>
    </div>
    <!--Modal: modalSocial-->
@endsection

@section('custom_js')
    <script>
        for (var i = 1; i <= 5; i++) {
            sum_total(i, '.capital', '.total_capital', "");
        }
        for (var j = 1; j <= 3; j++) {
            for (var i = 1; i <= 5; i++) {
                sum_total(i, '.text' + j, '.text_total' + j, j);
            }
        }
        function sum_total(i, initial_class, total_class, j) {

            if (j != 3) {
                $(initial_class + "" + i).blur(function () {
                    var sum = 0;
                    $(initial_class + "" + i).each(function () {
                        sum += Number($(this).val());
                    });
                    $(total_class + "" + i).val(sum);
                    if (total_class === '.total_capital') {
                        $('.grand' + i).val(sum + Number($('.total_revenue_' + i).val()));
                    }
                });
            } else {
                $(initial_class + "" + i).blur(function () {
                    $('.total_revenue_' + i).val(Number($(this).val()) + Number($('.text_total2' + i).val()) + Number($('.text_total1' + i).val()));
                });
            }
        }

    </script>
    <script>

        var label = [];
        $(document).on('keypress', '.form-control', function () {
            $('#warning' + $(this).attr('id')).text("");
        });


        $('#expenditure-form').on('submit', function (e) {
            e.preventDefault();
            swal({
                title: "Are you sure?",
                text: "You are sure you want to @if(!$alreadySubmitted){{"submit"}}@else{{"resubmit"}}@endif expenditure info!",
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
                        url: "{{url('/save_expenditure')}}",
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
                                        swal("success", "Successfully re-submitted the Expenditure Info!","success");
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
                        success: function () {
                            //swal("Data has been successfully saved.!", "", "success");
                        }
                    });
                } else {
                    swal("You have canceled your operation!");
                }
            });

        });

        $('#other_category_form').on('submit', function (e) {
            e.preventDefault();

            swal({
                title: "Are you sure?",
                text: "You are about to save other specification record!",
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
                     url: "{{url('/save_other_specification')}}",
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

                     $('.' + key).text(val);

                     });
                     swal("In valid data has been provided please check!", "", "error");
                     },
                     200: function () {
                     swal("Data has been successfully saved.!", "", "success");

                     },
                     500: function () {
                     swal("Something went wrong. Please try again.", "", "warning");
                     }
                     },
                     error: function (data) {

                     },
                     success: function () {
                     swal("Other specification is successfully saved.!", "", "success");
                     }
                     });*/
                    swal("Information", "Add Category request is currently disabled by admin. Please contact admin for more details", "info");
                } else {
                    swal("You have canceled your operation!");
        }
        })



        });
        $(document).on('keypress', '.form-control', function () {
            $('.' + $(this).attr('name')).text("");
        });
    </script>
    @if(session('message'))
        <script>
            swal("{{session('message')}}", "error");
        </script>
    @endif
    @if(session('message'))
        <script>
            swal("{{session('error')}}", "error");
        </script>

    @endif
@endsection

