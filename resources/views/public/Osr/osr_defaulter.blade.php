

@extends('layouts.app_website')

@section('custom_title')
    OSR non-tax
@endsection

@section('custom_css')

        <link href="//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css"/>
        <link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css"/>

@endsection


@section('content')
<div class="container mb-40">
                <div class="row" style="margin-top:40px">
                    <div class="col-md-12 col-sm-12 col-xs-12">
                        <h3 style="background-color:#d4b3c7; padding:5px;">Defaulter List</h3><br/>
                        <div class="table-responsive">
                            <table class="table table-bordered" id="dataTable1">
                                <thead>
                                <tr class="bg-primary">
                                    <td>SL</td>
                                    <td>District</td>
                                    <td>Total Asset</td>
                                    <td>Defaulter</td>

                                </tr>
                                </thead>
                                <tbody>
                                @php $i=1; @endphp
                                    @foreach($data['zilas'] as $list)
                                    <tr>
                                        <td>{{$i}}</td>
                                        <td>{{$list->zila_parishad_name}}</td>
                                        <td>
                                            @if(isset($data['asset_count'][$list->id]))
                                                {{$data['asset_count'][$list->id]}}
                                            @else
                                                {{'0'}}
                                            @endif
                                        </td>
                                        <td>
                                            @if(isset($data['defaulterData'][$list->id]))
                                                {{$data['defaulterData'][$list->id]}}
                                            @else
                                                {{'0'}}
                                            @endif
                                        </td>
                                    </tr>
                                @php $i++; @endphp
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
                ordering: false,
                buttons: [
                    {
                        extend:    'excelHtml5',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    }
                ]
            });
             
              $('#dataTable').DataTable({
                dom: 'Bfrtip',
                ordering: false,
                buttons: [
                    {
                        extend:    'excelHtml5',
                        text:      'Export to Excel <i class="fa fa-file-excel-o" style="font-size: 15px"></i>',
                        titleAttr: 'Excel',
                    }
                ]
            });
        });
    </script>
@endsection