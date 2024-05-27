@php
    $page_title="dashboard";
@endphp

@extends('layouts.app_admin_uc')

@section('custom_css')
    <link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>

    <link href="{{ asset('mdas_assets/mdbootstrap/css/mdb.min.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('mdas_assets/bootstrap-select/dist/css/bootstrap-select.min.css') }}">
    <link rel="stylesheet" href="{{ asset('mdas_assets/css/style1.css') }}">
    <style>
        .table th, .table td{
			vertical-align:middle;
		}
		.table-bordered th{
            height:45px;
		}
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
			border-bottom: 2px solid #83858f;
			border-left: 1.5px solid #83858f;
			border-right: 1.5px solid #83858f;
        }
		.table-bordered>thead>tr>th {
			background-color: #384059;
			color: #ffffff;
		}
        .dataTables_wrapper .dataTables_filter input {
            border: 2px solid #83858f;
        }
        .mtb40{
            margin-bottom: 40px;
            margin-top: 40px;
        }
    </style>

@endsection
@section('content')
    <div class="container mtb40">
        {{-----------------------DATA TABLE-----------------------------------------}}
        <div class="row">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <div class="table-responsive">
                    <br /><br /><br />
                    <center>
                        <h2 style="text-transform: uppercase;font-weight:bold;">
                            List of all Entities Created
                        </h2>
                    </center>
                    <br />
                    <table class="table table-bordered" id="dataTable1" style="border:4px solid #515a74;background:rgba(255,255,255,0.885);">
                        <thead>
                            <tr>
                                <th width="25px">SL</th>
                                <th>Project / Programme Name</th>
                                <th>Project Year Name</th>
                                <th>Extension centre / District</th>
                                <th>Entity Name</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php
                            use App\CommonModels\ZilaParishad;
                            use App\CommonModels\SiprdExtensionCenter;
                        ?>
                        @php $i=1; @endphp
                        @foreach($projectEntities as $pro)
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$pro->project_name}}</td>
                                <td>{{$pro->project_year}}</td>
                                <td>
                                <?php
                                    if($pro->division_type == 1 ) {
                                        $ec = SiprdExtensionCenter::where('siprd_extension_centers.id', '=', $pro->zilla_extension_id)
                                                                    ->select('siprd_extension_centers.extension_center_name')
                                                                    ->get();
                                        echo "Extension Center : ".$ec[0]->extension_center_name;
                                    }
                                    else if($pro->division_type == 2 ) {
                                        $zilla = ZilaParishad::where('zila_parishads.id', '=', $pro->zilla_extension_id)
                                                                ->select('zila_parishads.zila_parishad_name')
                                                                ->get();
                                        echo "District: ".$zilla[0]->zila_parishad_name;
                                    }
                                ?>
                                </td>
                                <td>{{ $pro->short_entity_name }}</td>
                            </tr>
                            @php $i++; @endphp
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{------------------DATA TABLE ENDED-----------------------------------------}}
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
    </script>

@endsection