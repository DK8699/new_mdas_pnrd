<?php

namespace App\Training;

use Illuminate\Database\Eloquent\Model;
use DB;

class TrainingLocationDetail extends Model
{
   public static function getInterestedParticipantsByCentre($current,$days_after_10){
		
		$finalArray=[];

        $data= TrainingLocationDetail::join('training_participant_details as p_details','p_details.training_location_id','=','training_location_details.id')
		   ->join('training_details as details','details.id','p_details.training_id')
		   ->where([
			   ['details.start_date','>=',$current,],
			   ['details.start_date','<=',$days_after_10]
		   ])
             ->select(DB::raw('count(*) AS total'),'training_location_details.training_centre_id as c_id')
             ->groupBy('training_location_details.training_centre_id')
             ->get();

        foreach($data AS $li){
		   $finalArray[$li->c_id]=[
                'interested_participants'=> $li->total
            ];
        }
		
        return $finalArray;
		
	}
	
	 public static function getInterestedParticipantsByProgramme($current,$days_after_10){
		
		$finalArray=[];

        $data= TrainingLocationDetail::join('training_participant_details as p_details','p_details.training_location_id','=','training_location_details.id')
		   ->join('training_details as details','details.id','p_details.training_id')
		   ->where([
			   ['details.start_date','>=',$current,],
			   ['details.start_date','<=',$days_after_10]
		   ])
             ->select(DB::raw('count(*) AS total'),'details.programme as programme_id')
             ->groupBy('details.programme')
             ->get();

        foreach($data AS $li){
		   $finalArray[$li->programme_id]=[
                'interested_participants'=> $li->total
            ];
        }
		
        return $finalArray;
		
	}
}
