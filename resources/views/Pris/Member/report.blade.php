@php
    $page_title="PRIs_Menbers";
@endphp

@extends('layouts.app_user')

@section('custom_css')
    <style>
        .mt10{
            margin-top: 10px;
        }
		.mt20{
            margin-top: 20px;
        }
        .mt30{
            margin-top: 30px;
        }
		strong{
			color:red;
		}
        #myModalAddPri .form-control{
            height:25px;
            padding:2px 5px;
            font-size: 12px;
        }
        label{
            font-size: 11px;
        }
        .Zebra_DatePicker_Icon_Wrapper{
            width:100% !important;
        }
        .table{
            margin-bottom: 0px;
            border:0px;
        }
        body{
            background-color: #eee;
        }

        #myModalAddPri .modal-body{
            padding-bottom:0px;
            background-color: rgba(125, 210, 235, 0.93);
        }
        .well{
            margin-bottom: 0px;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('dashboard')}}">Dashboard</a></li>
            <li class="active">PRIs</li>
        </ol>
    </div>

    <div class="container">
	
		<div class="row mt10">
            <form action="{{route('pris.district.searchReportAP')}}" method="POST">
                {{csrf_field()}}
                <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-3" style="border:1px solid #ddd;background-color:#ddd">
                   
                    <h3 class=text-center text-uppercase>PRIs Member Report AP Wise</h3>
                        <div class="form-group">
                            <label>Select Zilla Parishad</label>
                            <select class="form-control" name="zilla_code" id="zilla_code">
                               <option value="@if(isset($zillas->id)){{$zillas->id}}@endif">
                                    @if(isset($zillas->id)){{$zillas->zila_parishad_name}}@endif
                                </option>
                            </select>
                        </div>
                       <div class="form-group">
                            <button type="submit" class="btn btn-lg animated-button thar-two" style="margin: 0px auto 0px auto;">Search</button>
                        </div>
                </div>
            </form>

        </div>
	
        <div class="row mt10">
            <form action="{{route('pris.district.searchReportGP')}}" method="POST">
                {{csrf_field()}}
                <div class="col-md-6 col-sm-12 col-xs-12 col-md-offset-3" style="border:1px solid #ddd;background-color:#ddd">
                   
                    <h3 class=text-center text-uppercase>PRIs Member Report GP Wise</h3>
                        <div class="form-group">
                            <label>Select Zilla Parishad</label>
                            <select class="form-control" name="zilla_code" id="zilla_code">
                               <option value="@if(isset($zillas->id)){{$zillas->id}}@endif">
                                    @if(isset($zillas->id)){{$zillas->zila_parishad_name}}@endif
                                </option>
                            </select>
                        </div>
                    
                        <div class="form-group">
                            <label>Select Anchalik Panchayat</label>
                            <select class="form-control" name="anchalik_code" id="anchalik_code" required>
                                <option value="">--Select--</option>
                                @foreach($anchaliks AS $li_a)
                                    <option value="{{$li_a->id}}">{{$li_a->anchalik_parishad_name}}</option>
                                @endforeach
                            </select>
                        </div>
                       <div class="form-group">
                            <button type="submit" class="btn btn-lg animated-button thar-two" style="margin: 0px auto 0px auto;">Search</button>
                        </div>
                </div>
            </form>

        </div>
    </div>



    <!-- Modal ADD PRIs -->



    <!-- Modal ADD PRIs Ended -->

@endsection

@section('custom_js')

@endsection