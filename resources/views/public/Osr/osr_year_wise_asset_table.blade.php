<div class="row mt40">
            <div class="col-md-12 col-sm-12 col-xs-12">
                <h3 style="background-color:#d4b3c7; padding:5px;">{{$data['head_txt']}}</h3><br/>
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable1">
                        <thead>
                        <tr class="bg-primary">
                            <td>SL</td>
                            <td>District</td>
                            <td>Total Master Asset</td>
                            <td>Asset Shortlisted under ZP</td>
                            <td>Asset Shortlisted under AP</td>
                            <td>Asset Shortlisted under GP</td>
                            <td>Total Asset Shortlisted</td>
							<td>Signed Report</td>
                        </tr>
                        </thead>
                        <tbody>
                        @php 
                            
                            $i=1; 
                            $tot_m_asset=0;
                            
                            $tot_zp_row=0;
                            $tot_ap_row=0;
                            $tot_gp_row=0;
                            
                            $tot_zp_col=0;
                            $tot_ap_col=0;
                            $tot_gp_col=0;
                            
                            $tot_s_row=0;
                            
                        @endphp
                            @foreach($data['zilas'] AS $li)
                            
                            
                            @php
                            
                                $tot_asset=0;
                                $zp_count=0;
                                $ap_count=0;
                                $gp_count=0;
                                
                            
                            @endphp
                            
                            @if(isset($data['asset_count'][$li->id]))
                                @php 
                                    $tot_asset=$data['asset_count'][$li->id];
                                @endphp
                            @endif
                            
                            @if(isset($data['shortlisted_asset'][$li->id]['ZP']))
                                @php 
                                    $zp_count= $data['shortlisted_asset'][$li->id]['ZP'];
                                @endphp
                            @endif
                            
                            @if(isset($data['shortlisted_asset'][$li->id]['AP']))
                                @php 
                                    $ap_count=$data['shortlisted_asset'][$li->id]['AP'];
                                @endphp
                            @endif
                            
                            @if(isset($data['shortlisted_asset'][$li->id]['GP']))
                                @php 
                                    $gp_count=$data['shortlisted_asset'][$li->id]['GP'];
                                @endphp
                            @endif
                            
                            @php
                                $tot_m_asset=$tot_m_asset+$tot_asset;
                                $tot_zp_row=$tot_zp_row+$zp_count;
                                $tot_ap_row=$tot_ap_row+$ap_count;
                                $tot_gp_row=$tot_gp_row+$gp_count;
                            
                                $tot_col=$zp_count+$ap_count+$gp_count;
                            
                                $tot_s_row=$tot_s_row+$tot_col;
								
								$result = DB::select('select * from osr_non_tax_signed_asset_reports where osr_fy_year_id=? AND zila_id=?', [$data['yr_id'],$li->id]);
								
                            @endphp
                            
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$li->zila_parishad_name}}</td>
                                <td>{{$tot_asset}}</td>
                                <td>{{$zp_count}}</td>
                                <td>{{$ap_count}}</td>
                                <td>{{$gp_count}}</td>
                                <td>{{$tot_col}}</td>
								@if(empty($result))
                                    <td>No file uploaded </td>
                                @else
                                <td> <a href="{{route('osr.non_tax.asset.shortlist.report.view', [encrypt($data['yr_id']),encrypt($li->id)])}}" 
                                                   target="_blank" class="btn btn-success btn-xs" style ="padding:1px 10px"id="attachment_view_link1">
                                                    <i class="fa fa-check"></i>
                                                    View
                                </a></td>
								@endif
                            </tr>
                            
                            @php $i++; @endphp
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="bg-danger">
                                <td>#</td>
                                <td>Total</td>
                                <td>{{$tot_m_asset}}</td>
                                <td>{{$tot_zp_row}}</td>
                                <td>{{$tot_ap_row}}</td>
                                <td>{{$tot_gp_row}}</td>
                                <td>{{$tot_s_row}}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

<script type="application/javascript">
         $(document).ready(function () {
            $('#dataTable1').DataTable({
                dom: 'Bfrtip',
                ordering: false,
                paging: false,
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