<?php

namespace App\Grievance;

use Illuminate\Database\Eloquent\Model;

class GrievanceRecipient extends Model
{
    public static function isDetailsAlreadyExists($m_no){
	    $countRecipientDetails = GrievanceRecipient::where([
		   ['mobile_no','=',$m_no], 
	    ])->count();

        if($countRecipientDetails > 0)
        {
            return false;
        }
        return true;
    }
	
	public static function geRecipientById($id){
		
        return GrievanceRecipient::leftJoin('districts as d','d.id','=','grievance_recipients.district_id')
		   ->leftJoin('blocks as b','b.id','=','grievance_recipients.block_id')
		   ->leftJoin('gram_panchyats as g','g.gram_panchyat_id','=','grievance_recipients.gp_id')
            ->join('grievance_submitted_by as submit_by','submit_by.id','=','grievance_recipients.submitted_to')
            ->select('d.district_name','b.block_name','g.gram_panchayat_name','submit_by.submitted_by', 'grievance_recipients.*')
            ->where('grievance_recipients.id','=',$id)
            ->first();
	}
}
