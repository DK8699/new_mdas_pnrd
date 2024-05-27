<?php

namespace App\Uc;
use Illuminate\Database\Eloquent\Model;
use Auth;

class UcProjectYear extends Model
{
    protected $table = "uc_projects_years";
	
	public static function getYearsByProjectId($project_id,$user_id){
		
	$users=Auth::user();
        if($users->mdas_master_role_id==2)
        {
            $whereArray = [
                ['p_div.division_type','=',2],
            ];
        }
        else if($users->mdas_master_role_id==6)
        {
            $whereArray = [
                ['p_div.division_type','=',1], 
            ];
        }

        else{

        }
        return UcProjectYear::join('uc_project_divisions as p_div','p_div.project_id','=','uc_projects_years.project_id')
		   ->where([
			  [$whereArray],
		  ['uc_projects_years.project_id','=',$project_id],
		  ['p_div.zilla_extension_id','=',$user_id],
        ])->select('uc_projects_years.id', 'uc_projects_years.project_year')
            ->get();
    }
}
