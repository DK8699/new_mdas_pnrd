@php
    $page_title="six_form_staff";
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
        <form action="#" method="POST" id="staffInfoForm">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="col-md-12 col-sm-12 col-xs-12">
                    <a href="{{route('survey.six_finance_form_dashboard')}}" class="btn btn-warning animated-button" style="margin-bottom: 10px;">
                        <i class="fa fa-arrow-left"></i>
                        Back
                    </a>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>1. Details of Staff as on 31-03-2018</label>
                    </div>
                </div>

                @foreach($final_cats AS $li_cat)
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <div class="table-responsive">
                            <label> @if($li_cat['id']==1){{"a."}}@else{{"b."}}@endif {{$li_cat['category_name']}}</label>
                            <table class="table table-bordered">
                                <thead>
                                <tr class="bg-primary">
                                    <td>Designation</td>
                                    <td>No. of sanctioned post</td>
                                    <td>Scale of pay</td>
                                    <td>Consolidated pay monthly ( in <i class="fa fa-rupee"></i> )</td>
                                    <td>Vacant Post</td>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $tot_sp= 0;
                                    $tot_vp= 0;
                                @endphp
                                @foreach($li_cat['designations'] AS $li_d)
                                    <tr>
                                        <td>
                                            <label>{{$li_d->designation_name}}</label>
                                        </td>
                                        <td>
                                            <input type="number" name="san_post_c_{{$li_d->id}}" id="san_post_c_{{$li_d->id}}" class="form-control san_post_c_{{$li_cat['id']}}" value="@if(isset($staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["SC"])){{$staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["SC"]}}@elseif($alreadySubmitted){{"0"}}@endif"/>
                                        </td>
                                        <td>
                                            <input type="text" name="scale_pay_c_{{$li_d->id}}" id="scale_pay_c_{{$li_d->id}}" class="form-control" value="@if(isset($staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["SP"])){{$staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["SP"]}}@elseif($alreadySubmitted){{"NA"}}@endif"/>
                                        </td>
                                        <td>
                                            <input type="number" name="con_pay_c_{{$li_d->id}}" id="con_pay_c_{{$li_d->id}}" class="form-control" min="0" step=".01" value="@if(isset($staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["CP"])){{$staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["CP"]}}@elseif($alreadySubmitted){{"0"}}@endif"/>
                                        </td>
                                        <td>
                                            <input type="number" name="vac_post_c_{{$li_d->id}}" id="vac_post_c_{{$li_d->id}}" class="form-control vac_post_c_{{$li_cat['id']}}" value="@if(isset($staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["VP"])){{$staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["VP"]}}@elseif($alreadySubmitted){{"0"}}@endif"/>
                                        </td>
                                    </tr>

                                    @if(isset($staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["SC"]))
                                        @php $tot_sp= $tot_sp+(int)$staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["SC"]; @endphp
                                    @endif

                                    @if(isset($staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["VP"]))
                                        @php $tot_vp= $tot_vp+(int)$staffInfoDetailsFillFinal["C_".$li_cat['id']]["D_".$li_d->id]["VP"]; @endphp
                                    @endif

                                @endforeach
                                @if(!$alreadySubmitted)
                                    <tr>
                                        <td colspan="5">
                                            <button class="btn animated-button thar-two addDesign" data-cat_name="{{$li_cat['category_name']}}" data-cat_id="{{$li_cat['id']}}"><i class="fa fa-plus"></i> Add More Designation To {{$li_cat['category_name']}}</button>
                                        </td>
                                    </tr>
                                @endif
                                <tr class="bg-danger">
                                    <td>
                                        <label>Total</label>
                                    </td>
                                    <td>
                                        <input type="number" name="total_san_post_c_{{$li_cat['id']}}" id="total_san_post_c_{{$li_cat['id']}}"  class="form-control" readonly="readonly" value="@if($alreadySubmitted){{$tot_sp}}@endif"/>
                                    </td>
                                    <td>

                                    </td>
                                    <td>

                                    </td>
                                    <td>
                                        <input type="number" name="total_vac_post_c_{{$li_cat['id']}}" id="total_vac_post_c_{{$li_cat['id']}}"   class="form-control" readonly="readonly" value="@if($alreadySubmitted){{$tot_vp}}@endif"/>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                @endforeach

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label>2. Summary of Staff Salary</label>
                    </div>
                </div>

                <div class="col-md-12 col-sm-12 col-xs-12">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <tbody>
                            @foreach($final_cats AS $li_dm)
                                <tr class="bg-primary">
                                    <td colspan="6">
                                        @if($li_dm['id']==1){{"a."}}@else{{"b."}}@endif {{$li_dm['category_name']}}
                                        <span style="float:right;vertical-align:bottom">Yearly</span>
                                    </td>
                                </tr>
                                <tr class="bg-info">
                                    <th>Designation</th>
                                    @foreach($acts AS $li_act)
                                        <th>
                                            {{$li_act->financial_year}} ( in <i class="fa fa-rupee"></i> )
                                        </th>

                                        @php
                                            $tot_salary_A["C_".$li_dm['id']]["A_".$li_act->id]= 0;
                                        @endphp
                                    @endforeach
                                </tr>

                                @foreach($li_dm['designations'] AS $li_d)

                                    <tr>
                                        <td>
                                            <label>{{$li_d->designation_name}}</label>
                                        </td>
                                        @foreach($acts AS $li_act)

                                            <td>
                                                <input type="number" data-act='{{$li_act->id}}' name="salary_summary_a_{{$li_act->id}}_d_{{$li_d->id}}_c_{{$li_dm['id']}}" id="salary_summary_a_{{$li_act->id}}_d_{{$li_d->id}}_c_{{$li_dm['id']}}" class="form-control salary_summary_c_{{$li_dm['id']}}" min="0" step=".01"  value="@if(isset($staffInfoSalaryFillFinal["C_".$li_dm['id']]["D_".$li_d->id]["A_".$li_act->id])){{$staffInfoSalaryFillFinal["C_".$li_dm['id']]["D_".$li_d->id]["A_".$li_act->id]}}@elseif($alreadySubmitted){{"0"}}@endif"/>
                                            </td>
                                            @if(isset($staffInfoSalaryFillFinal["C_".$li_dm['id']]["D_".$li_d->id]["A_".$li_act->id]))
                                                @php
                                                    $tot_salary_A["C_".$li_dm['id']]["A_".$li_act->id]= $tot_salary_A["C_".$li_dm['id']]["A_".$li_act->id]+(float)$staffInfoSalaryFillFinal["C_".$li_dm['id']]["D_".$li_d->id]["A_".$li_act->id];
                                                @endphp
                                            @endif
                                        @endforeach
                                    </tr>
                                @endforeach
                                <tr class="bg-danger">
                                    <td>
                                        <label>Total</label>
                                    </td>
                                    @foreach($acts AS $li_act)
                                        <td>
                                            <input type="number" name="total_salary_summary_a_{{$li_act->id}}_c_{{$li_dm['id']}}" id="total_salary_summary_a_{{$li_act->id}}_c_{{$li_dm['id']}}" class="form-control" readonly="readonly" value="@if(isset($tot_salary_A["C_".$li_dm['id']]["A_".$li_act->id])){{$tot_salary_A["C_".$li_dm['id']]["A_".$li_act->id]}}@endif"/>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach

                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label>3. Arrear salary, if any, as on 31-03-2018 ( in <i class="fa fa-rupee"></i> )</label>
                        <input type="number" class="form-control" name="arrear_salary" id="arrear_salary" min="0" step=".01" value="@if(isset($staffInfoFill->arrear_salary)){{$staffInfoFill->arrear_salary}}@endif"/>
                    </div>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label>4. Number of Muster Roll and fixed pay employees as on 31-03-2018 </label>
                        <input type="number" class="form-control" name="no_muster_roll_fixed_pay_emp" id="no_muster_roll_fixed_pay_emp" value="@if(isset($staffInfoFill->number_of_muster_roll_fixed_pay_emp)){{$staffInfoFill->number_of_muster_roll_fixed_pay_emp}}@endif"/>
                    </div>
                </div>


                <div class="col-md-12 col-sm-12 col-xs-12">
                    @if($alreadySubmitted)
                        {{--<button type="button" class="btn btn-danger animated-button" id="deleteRequest" data-df="STA" style="margin-bottom: 40px">
                            <i class="fa fa-trash-o"></i>
                            Request to delete Staff Info
                        </button>--}}
                        <button type="submit" class="btn animated-button thar-two" style="margin-bottom: 40px">Resubmit</button>
                    @else
                        <button type="submit" class="btn animated-button thar-two" style="margin-bottom: 40px">Submit</button>
                    @endif
                </div>
            </div>
        </form>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="myModal" role="dialog">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn bg-red modal-close" data-dismiss="modal" aria-hidden="true">
                        <i class="fa fa-close"></i>
                    </button>
                    <h4 class="modal-title"><label>Add Designation</label></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <form action="#" method="POST" id="addDesignForm">
                            <div class="col-md-12 col-sm-12 col-sm-12">
                                <input type="hidden" name="cat_id" id="cat_id"/>
                                <div class="form-group">
                                    <label><span id="text_designation_name"></span> Designation Name</label>
                                    <input type="text" class="form-control" name="designation_name" id="designation_name" placeholder="Enter Designation Name"/>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-block animated-button thar-two">Submit</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom_js')
    <script type="application/javascript">

        $('.addDesign').on('click', function(e){
            e.preventDefault();
            $('#text_designation_name').text('');
            $('#myModal').modal('show');
            $('#text_designation_name').text($(this).data('cat_name'));
            $('#cat_id').val($(this).data('cat_id'));
        });

        $('#addDesignForm').on('submit', function(e){
            e.preventDefault();

            $('.form_errors').remove();
            /*$('.page-loader-wrapper').fadeIn();
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                type: "POST",
                url: '{{route('survey.six_finance_form_staff.add_design')}}',
                dataType: "json",
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {
                    if (data.msgType == true) {
                        swal("Success", data.msg, 'success');
                        $('#addDesignForm')[0].reset();
                        $('#myModal').modal('hide');
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
            });*/
            swal("Information", "Add Designation is currently disabled by admin. Please contact admin for more details", "info");

        });


        $('#staffInfoForm').on('submit', function(e){
            e.preventDefault();

            swal({
                title: "Are you sure?",
                text: "You are sure you want to @if(!$alreadySubmitted){{"submit"}}@else{{"resubmit"}}@endif staff info.",
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
                        url: '{{route('survey.six_finance_form_staff.save')}}',
                        dataType: "json",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        success: function (data) {
                            if (data.msgType == true) {
                                $('#staffInfoForm')[0].reset();

                                @if(!$alreadySubmitted)
                                    window.location.replace("{{route('survey.six_finance_form_dashboard')}}");
                                @else
                                    swal("success", "Successfully re-submitted the staff Info!","success");
                                    setTimeout(function(){ location.reload(); }, 1200);
                                @endif

                            }else{

                                if(data.msg=="VE"){
                                    swal("Vadidation Error", "Please fill up the form correctly. If no error is showing kindly refresh the page and try again.", 'info');
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


        @foreach($final_cats AS $li_cat)
        //Details of Staff ->No. of sanctioned post
        //Provincialised Staff
        $('.san_post_c_{{$li_cat['id']}}').on('blur', function(e){
            e.preventDefault();

            var sum =0;
                    @foreach($li_cat['designations'] AS $li_d)
            var i=$('#san_post_c_{{$li_d->id}}').val();
            if(i==''){
                i=0;
            }
            sum = sum + parseInt(i);
            @endforeach

$('#total_san_post_c_{{$li_cat['id']}}').val(sum);

        });
        //Non-provincialised Staff
        $('.san_post_c_{{$li_cat['id']}}').on('blur', function(e){
            e.preventDefault();

            var sum =0;
                    @foreach($li_cat['designations'] AS $li_d)
            var i=$('#san_post_c_{{$li_d->id}}').val();
            if(i==''){
                i=0;
            }
            sum = sum + parseInt(i);
            @endforeach

$('#total_san_post_c_{{$li_cat['id']}}').val(sum);

        });

        //Details of Staff -> Vacant Post
        //Provincialised Staff
        $('.vac_post_c_{{$li_cat['id']}}').on('blur', function(e){
            e.preventDefault();

            var sum =0;
                    @foreach($li_cat['designations'] AS $li_d)
            var i=$('#vac_post_c_{{$li_d->id}}').val();
            if(i==''){
                i=0;
            }
            sum = sum + parseInt(i);
            @endforeach

$('#total_vac_post_c_{{$li_cat['id']}}').val(sum);

        });
        //Non-provincialised Staff
        $('.vac_post_c_{{$li_cat['id']}}').on('blur', function(e){
            e.preventDefault();

            var sum =0;
                    @foreach($li_cat['designations'] AS $li_d)
            var i=$('#vac_post_c_{{$li_d->id}}').val();
            if(i==''){
                i=0;
            }
            sum = sum + parseInt(i);
            @endforeach

$('#total_vac_post_c_{{$li_cat['id']}}').val(sum);

        });

        //Summary of Staff Salary{{$li_cat['id']}}///
        $('.salary_summary_c_{{$li_cat['id']}}').on('blur', function(e){
            e.preventDefault();
            var act= $(this).data('act');

            @foreach($acts AS $li_act)

            if(act=={{$li_act->id}}){
                var sum=0;
                        @foreach($li_cat['designations'] AS $li_d)
                var i=$('#salary_summary_a_{{$li_act->id}}_d_{{$li_d->id}}_c_{{$li_cat['id']}}').val();

                if(i==''){
                    i=0;
                }
                sum = sum + parseFloat(i);
                @endforeach

$('#total_salary_summary_a_{{$li_act->id}}_c_{{$li_cat['id']}}').val(sum.toFixed(2));
            }
            @endforeach



        });
        @endforeach
    </script>
@endsection