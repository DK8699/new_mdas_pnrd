<?php

namespace App\Uc;

use Illuminate\Database\Eloquent\Model;
use DB;

class UcProjectDivision extends Model
{
    public static function getProjectById($id)
    {
        return UcProjectDivision::leftJoin('zila_parishads as z','uc_project_divisions.zilla_extension_id','=','z.id')
                                ->leftJoin('siprd_extension_centers as ex','uc_project_divisions.zilla_extension_id','=','ex.id')
                                ->where('uc_project_divisions.project_id','=',$id)
                                ->select('uc_project_divisions.*','z.zila_parishad_name','ex.extension_center_name')
                                ->get();
        
    }
}
