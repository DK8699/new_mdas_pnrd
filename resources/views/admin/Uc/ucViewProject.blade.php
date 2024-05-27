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
        #table th, #table td{
			vertical-align:middle;
		}
		#table-bordered th{
    		text-align:center;
            height:55px;
		}
        .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
			border: 2px solid #83858f;
        }
		.table-bordered>thead>tr>th {
			background-color: #384059;
			color: #ffffff;
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
                    <br /><br />
                    <center>
                        <h2 style="text-transform: uppercase;font-weight:bold;">
                            List of all Projects
                        </h2>
                    </center>
                    <br />
                    <table class="table table-bordered" id="dataTable1" style="border:4px solid #515a74;background:rgba(255,255,255,0.885);">
                        <thead>
                            <tr style="background:#e8fdff;">
                                <th width="25px">SL</th>
                                <th width="225px">Project/Programme Name</th>
                                <th width="155px">Project Duration</th>
                                <th width="85px">Sactioned Amount</th>
                                <th width="85px">GOI Share</th>
                                <th width="85px">GOA Share</th>
                                <th width="225px">Extension Center(s)</th>
                                <th width="225px">Zilla Parishad(s)</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php $i=1; @endphp
                        @foreach($project as $pro)
                            <tr>
                                <td>{{ $i }}</td>
                                <td>{{ $pro->project_name }}</td>
                                <td>{{ $pro->start_date }} to {{ $pro->end_date }}</td>
                                <td>{{ $pro->sactioned_amt }}</td>
                                <td>{{ $pro->goi_share }}</td>
                                <td>{{ $pro->goa_share}}</td>
                                <td>
                                    <?php
                                        $first = 1;
                                        foreach($p[$pro->id] as $li) {
                                            if($li->division_type == 1) {
                                                if( $first == 1 )
                                                    echo $li->extension_center_name;
                                                else
                                                    echo ", ".$li->extension_center_name;
                                                $first = 0;
                                            }
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                        $first = 1;
                                        foreach( $p[$pro->id] as $li ) {
                                            if( $li->division_type == 2 ) {
                                                if( $first == 1 )
                                                    echo $li->zila_parishad_name;
                                                else
                                                    echo ", ".$li->zila_parishad_name;
                                                $first = 0;
                                            }
                                        }
                                    ?>
                                    <!-- @foreach($p[$pro->id] as $li)
                                        @if($li->division_type == 2)
                                            {{$li->zila_parishad_name}},
                                        @endif
                                    @endforeach -->
                                </td>
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
        // $(document).ready(function () {
        //     $('#dataTable1').DataTable({
        //         dom: 'Bfrtip',
        //         buttons: [
        //             'excel', 'copy', 'pdf'
        //         ]
        //     });
        // });
    </script>

@endsection