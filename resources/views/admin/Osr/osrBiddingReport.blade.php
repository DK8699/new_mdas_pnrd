@php
    $page_title="priMenu";
@endphp

@extends('layouts.app_admin')

@section('custom_css')
    <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>

    <style>
      
        .panel
        {
            border: none;
            background: #98D3F6;
        }
        label{
            color: dodgerblue;
        }
        .mb40{
            margin-bottom: 40px;
        }
        .badge-red{
            background-color: orangered;
        }
        .badge-green{
            background-color: darkgreen;
        }
    </style>
@endsection




@section('content')

<div class="row">
        <ol class="breadcrumb">
            <li><a href="{{route('admin.dashboard')}}">Dashboard</a></li>
            <li class="active">OSR</li>
        </ol>
</div>

<div class="container mb40">  
    <div class="row">
         <div class="panel panel-primary">
              <div class="panel-body">
                    <form action="{{route('admin.Osr.osrBiddingReportIndex1')}}" method="POST">
                        {{csrf_field()}}
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Financial year</label>
                                    <select class="form-control" name="search_fyYr_id" id="search_fyYr_id" required>
                                        <option value="">--Select--</option>
                                        @foreach($osr_fy AS $os_list)
                                        <option value="{{$os_list->id}}" @if($filterArray['fy_filter']==$os_list->id)selected="selected"@endif>{{$os_list->fy_name}}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-4 col-sm-4 col-xs-12">
                            <div class="form-group">
                              <label>Zilla Parishad</label>
                                    <select class="form-control" name="search_zila_id" id="search_zila_id" required>
                                        <option value="">--Select--</option>
                                        @foreach($zilas AS $zil)
                                                <option value="{{$zil->id}}" @if($filterArray['zp_filter']==$zil->id)selected="selected"@endif>{{$zil->zila_parishad_name}}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-4 col-xs-12">
                            <div class="form-group">
                              <label>Branch Name</label>
                                    <select class="form-control" name="search_branch_id" id="search_branch_id" required>
                                        <option value="">--Select--</option>
                                        @foreach($branchData AS $branch)
                                                <option value="{{$branch->id}}" @if($filterArray['branch_filter']==$branch->id)selected="selected"@endif>{{$branch->branch_name}}</option>
                                        @endforeach
                                        
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-2 col-xs-12">
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary btn-block" style="margin-top: 22px">
                                    <i class="fa fa-search"></i>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
         </div>
    
      </div>
</div>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable1">
                    <thead>
                    <tr class="bg-primary">
                        <td>SL</td>
                        <td>Asset Code</td>
                        <td>Asset Name</td>
                        <td>Bidding Status</td>
                        <td>Bidding Date</td>
                        <td>Total Bidders</td>
                        <td>Withdrawn Bidders</td>
                        <td>Forfeited Bidders</td>
                        <td>Settled Amount</td>
                        <td>Forfeited Amount</td>
                        <td>Settled Date</td>
						<td>Accepted Bidder Name</td>
						<td>First Installment</td>  
                        <td>Second Installment</td>  
                        <td>Third Installment</td> 
                    </tr>
                    </thead>
                    <tbody>
                    @php $i=1; @endphp
                    @foreach($assetList AS $li)
                        @if($li->is_active==1)
                            <tr>
                                <td>{{$i}}</td>
                                <td>
                                    {{$li->asset_code}}
                                </td>
                                <td>
                                    {{$li->asset_name}}
                                </td>
                                <td>
                                    @if(isset($finalData[$li->id]['stage']) && $finalData[$li->id]['stage']==3)
                                        <span class="badge badge-green">Done</span>
                                    @else
                                        <span class="badge badge-red">Pending</span>
                                    @endif
                                </td>
                                <td>
                                    @if(isset($finalData[$li->id]))
                                        {{date('d-M-Y', strtotime($finalData[$li->id]['date_of_tender']))}}
                                    @endif
                                </td>
                                <td>
                                    @if(isset($finalData[$li->id]))
                                        {{$finalData[$li->id]['total_bidder']}}
                                    @endif
                                </td>
                                <td>
                                    @if(isset($finalData[$li->id]))
                                        {{$finalData[$li->id]['total_withdrawn_bidder']}}
                                    @endif
                                </td>
                                <td>
                                    @if(isset($finalData[$li->id]))
                                        {{$finalData[$li->id]['total_forfeited_bidder']}}
                                    @endif
                                </td>
                                <td>
                                    @if(isset($finalData[$li->id]))
                                        {{$finalData[$li->id]['bidding_amt']}}
                                    @endif
                                </td>
                                <td>
                                    @if(isset($finalData[$li->id]))
                                        {{$finalData[$li->id]['total_forfeited_amount']}}
                                    @endif
                                </td>
                                <td>
                                    @if(isset($finalData[$li->id]))
                                        {{date('d-M-Y', strtotime($finalData[$li->id]['awarded_date']))}}
                                    @endif
                                </td>
								<td>
                                        @if(isset($finalData[$li->id]))
                                            {{$finalData[$li->id]['b_f_name']}}
                                            {{$finalData[$li->id]['b_m_name']}}
                                            {{$finalData[$li->id]['b_l_name']}}
                                        @endif
                                    </td>
								<td>
                                        @if(isset($instalData[$li->id][1]['receipt_amt']))
                                            {{$instalData[$li->id][1]['receipt_amt']}}
										@else
                                        <span class="badge badge-red">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                         @if(isset($instalData[$li->id][2]['receipt_amt']))
                                            {{$instalData[$li->id][2]['receipt_amt']}}
										@else
                                        <span class="badge badge-red">Pending</span>
                                        @endif
                                    </td>
                                    <td>
                                         @if(isset($instalData[$li->id][3]['receipt_amt']))
                                            {{$instalData[$li->id][3]['receipt_amt']}}
										@else
                                        <span class="badge badge-red">Pending</span>
                                        @endif
                                    </td>
                            </tr>
                            @php $i++; @endphp
                        @endif
                    @endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>


@endsection

@section('custom_js')

<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.print.min.js"></script>
    <script type="application/javascript">
        $(document).ready(function () {
            $('#dataTable1').DataTable({
                dom: 'Bfrtip',
                buttons: [
                    'excel', 'copy', 'pdf'
                ]
            });
        });
        @if($filterArray)
        @php $filter_fy= $filterArray['fy_filter']; @endphp
        @php $filter_zp= $filterArray['zp_filter']; @endphp
        @php $filter_branch= $filterArray['branch_filter']; @endphp
        $('#filter_fy').val('{{$filter_fy}}');
        $('#filter_zp').val('{{$filter_zp}}');
        $('#filter_branch').val('{{$filter_branch}}');
        @endif
    </script>
@endsection