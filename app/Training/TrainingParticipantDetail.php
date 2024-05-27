<?php

namespace App\Training;
use DB;

use Illuminate\Database\Eloquent\Model;

class TrainingParticipantDetail extends Model
{
    public static function getParticipantsCountByLocation(){
	    
	    $finalArray = [];
	    
	    $data =  TrainingParticipantDetail::join('training_location_details as loc_details','training_participant_details.training_location_id','loc_details.id')
		     ->select(DB::raw('count(*) AS total'),'training_participant_details.training_location_id as loc_id')
		    ->groupBy('training_participant_details.training_location_id')
		     ->get();
	    
	    foreach($data as $li)
	    {
		    $finalArray[$li->loc_id] = $li->total;
	    }
	    
	    return $finalArray;
    }
}
