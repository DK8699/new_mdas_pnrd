@php
    $page_title="dashboard";
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

                    <form action="" id="user_manage" method="POST">
                        <div class="col-md-5 col-sm-4 col-xs-12">
                            <div class="form-group">
                              <label>Zilla Parishad</label>
                                    <select class="form-control" name="search_zila_id" id="search_zila_id" required>
                                        <option value="">--Select--</option>
                                        @foreach($zilas AS $zil)
                                                <option value="{{$zil->id}}">{{$zil->zila_parishad_name}}</option>
                                        @endforeach
                                    </select>
                            </div>
                        </div>
                        <div class="col-md-5 col-sm-4 col-xs-12">
                            <div class="form-group">
                                <label>Anchalik Parishad</label>
                                    <select class="form-control" name="search_ap_id" id="search_ap_id">
                                        <option value="">--Select--</option>
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

        <div class="row mt20">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable1">
                        <thead>
                            <tr class="bg-primary">
                                <td>SL</td>
                                <td>ZP Name</td>
                                <td>AP Name</td>
                                <td>GP Name</td>
                                <td>EMPLOYEE CODE</td>
                            </tr>
                        </thead>
                        <tbody>
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

        $(document).on('change', '#search_zila_id', function (e) {
            e.preventDefault();
            $('#search_ap_id').empty();

            var search_zila_id = $(this).val();
            
            if (search_zila_id) {
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.manageUser.selectAnchalAjax')}}',
                    dataType: "json",
                    data: {search_zila_id: search_zila_id},
                    success: function (data) {
                        if (data.msgType == true) {

                            $('#search_ap_id')
                                .append($("<option></option>")
                                    .attr("value", '')
                                    .text('--Select--'));

                            $.each(data.data, function (key, value) {
                                $('#search_ap_id')
                                    .append($("<option></option>")
                                        .attr("value", value['id'])
                                        .text(value['anchalik_parishad_name']));
                            });
                        } else {
                            swal(data.msg);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                    },
                    complete: function (data) {
                        $('.page-loader-wrapper').fadeOut();
                    }
                });
            }
         });
        
         /*$(document).on('submit', '#user_manage', function (e) {
            e.preventDefault();

            if (search_ap_id) {
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.manageUser.currentGPLevelEmpList')}}',
                    dataType: "json",
                    data: new FormData(),
                    success: function (data) {
                        if (data.msgType == true) {

                           
                        } else {
                            swal(data.msg);
                        }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                        callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                    },
                    complete: function (data) {
                        $('.page-loader-wrapper').fadeOut();
                    }
                });
            }
        });*/
        
        
        $('#user_manage').on('submit', function(e){

            e.preventDefault();

            if ($.fn.DataTable.isDataTable('#dataTable1') ) {
                $('#dataTable1').dataTable().fnClearTable();
                $('#dataTable1').dataTable().fnDestroy();
            }
             
            var zp_id= $('#search_zila_id').val();
            var ap_id= $('#search_ap_id').val();
            
            if((zp_id.length))
            {
                
                $('.page-loader-wrapper').fadeIn();
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    type: "POST",
                    url: '{{route('admin.manageUser.currentGPLevelEmpList')}}',
                    dataType: "json",
                    data: {search_zila_id : zp_id , search_ap_id : ap_id},
                    success: function (data) {
                        if (data.msgType == true) {
                             
                            var dataSet=data.data.results;
                          
                            $('#dataTable1').DataTable( {
                                dom: 'Bfrtip',
                                buttons: [
                                    'excel', 'copy', 'pdf'
                                ],
                                data: dataSet, 
                                columns: [
                                    { title: "SL" },
                                    { title: "ZP Name" },
                                    { title: "AP Name" },
                                    { title: "GP Name" },
                                    { title: "EMPLOYEE CODE" },
                                ]
                               
                            });
                            
                           
                            
                        }
                        else{
                                swal(data.msg);
                            }
                    },
                    error: function (jqXHR, textStatus, errorThrown) {
                       callAjaxErrorFunction(jqXHR, textStatus, errorThrown);
                    },
                    complete: function (data) {
                        $('.page-loader-wrapper').fadeOut();
                    }
                });
            }
            else
            {
                $('#dataTable1').DataTable();
            }
        });
        @if (session()->has('message'))
        swal("Information", "{{ session('message') }}", "info")
        @endif
    </script>
@endsection