<?php

namespace App\Uc;

use Illuminate\Database\Eloquent\Model;
use DB;
class UcComponent extends Model
{
    public static function getComponents(){
        $finalArray=[];
       /* return UcComponent::select('uc_components.component_header_id','uc_components.component_name')
                           ->get()
                            ->groupBy('uc_components.component_header_id');*/

        $data= UcComponent::join('uc_components_headers as header','header.id','=','uc_components.component_header_id')->get();
        $data1 = collect($data)->groupBy('component_header_id');

        foreach($data1 as $d)
        {

            echo json_encode($d);
           /* $component_name = $d->component_name;

            $finalArray[$d->component_header_id]=['component_name'=>$component_name];*/
        }




    }

}
