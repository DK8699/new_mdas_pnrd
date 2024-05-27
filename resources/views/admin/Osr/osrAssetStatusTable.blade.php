 <div class="row mt20 text-center" style="background-color: #fff;box-shadow: 0px 0px 13px 6px #aca8a8;border:1px solid #fff">
            <h3 style="color:blue;text-align: center">Year wise non-tax assets (Haat, Ghat, Fishery, Animal Pound) of {{$data['zp_name']->zila_parishad_name}}</h3>
            <hr/>
            <table class="table table-bordered">
                <thead class="bg-primary">
                    <tr>
                        <td>FY</td>
                        <td>Category</td>
                        <td>Managed by ZP</td>
                        <td>Managed by AP</td>
                        <td>Managed by GP</td>
                        <td>Pending to assign</td>
                        <td>Asset Not Selected</td>
                    </tr>
                </thead>
                <tbody>
                @foreach($data['fyList'] AS $years)
                    <tr style="border-top:2px solid green;">
                        <td rowspan="5" class="rotate"><p>{{$years->fy_name}}</p></td>
                    </tr>
                    @foreach($data['cats'] AS $cats)

                        @php
                            $zpValue=0;
                            $apValue=0;
                            $gpValue=0;
                            $totValue=0;
                            $notAssignValue=0;
                            $totValue=0;
                            $pendingValue=0;

                            if(count($data['zp_asset'])){
                                foreach($data['zp_asset'] AS $li){
                                    if($li->fy_id==$years->id && $li->cat_id==$cats->id){
                                        $zpValue=$li->count;
                                    }
                                }
                            }

                            if(count($data['ap_asset'])){
                                foreach($data['ap_asset'] AS $li){
                                    if($li->fy_id==$years->id && $li->cat_id==$cats->id){
                                        $apValue=$li->count;
                                    }
                                }
                            }

                            if(count($data['gp_asset'])){
                                foreach($data['gp_asset'] AS $li){
                                    if($li->fy_id==$years->id && $li->cat_id==$cats->id){
                                        $gpValue=$li->count;
                                    }
                                }
                            }

                            if(count($data['notselected'])){
                                foreach($data['notselected'] AS $li){
                                    if($li->fy_id==$years->id && $li->cat_id==$cats->id){
                                        $notAssignValue=$li->count;
                                    }
                                }
                            }

                            if(count($data['totData'])){
                                foreach($data['totData'][$years->id] AS $li){
                                    if($li->cat_id==$cats->id){
                                         $totValue=$li->count;
                                    }
                                }
                            }

                            $pendingValue=$totValue-($zpValue+$apValue+$gpValue+$notAssignValue);

                        @endphp

                        <tr @if($cats->id==1)style="border-top:2px solid #333"@endif>
                            <td>{{$cats->branch_name}}</td>
                            <td>
                                {{$zpValue}}
                            </td>
                            <td>
                                {{$apValue}}
                            </td>
                            <td>
                                {{$gpValue}}
                            </td>
                            <td>
                                <span style="font-size:16px;color:red;">{{$pendingValue}}</span>{{-- / {{$totValue}}--}}
                            </td>
                            <td>
                                {{$notAssignValue}}
                            </td>
                        </tr>
                    @endforeach
                @endforeach
                </tbody>
                
            </table>
        </div>