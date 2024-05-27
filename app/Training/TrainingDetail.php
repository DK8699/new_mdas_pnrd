<?php

namespace App\Training;

use Illuminate\Database\Eloquent\Model;
use DB;

class TrainingDetail extends Model
{
    	public static function getTrainingListAll(){
	    
	    return TrainingDetail::join('training_location_details as loc_details','training_details.id','loc_details.training_id')
		     ->leftJoin('training_centres as centres','centres.centre_id','loc_details.training_centre_id')
		     ->leftJoin('training_programmes as programme','programme.id','training_details.programme')
		     ->select(
		    		    'training_details.id as details_id',
				    'training_details.*',
		    		    'loc_details.id as loc_id',
				    'loc_details.*','centres.centre_name',
				    'programme.programme_name'
	    			)
		     ->orderBy('training_details.start_date','Desc')
		     ->get();
		     
    }
	
	
	public static function getTrainingListCentreWise($id){
	    
	    return TrainingDetail::join('training_location_details as loc_details','training_details.id','loc_details.training_id')
		     ->leftJoin('training_centres as centres','centres.centre_id','loc_details.training_centre_id')
		     ->leftJoin('training_programmes as programme','programme.id','training_details.programme')
		     ->where('loc_details.training_centre_id','=',$id)
		     ->select('training_details.id as details_id','training_details.*','loc_details.*','centres.centre_name','programme.programme_name')
		     ->get();
    }
	
	public static function getTrainingDataById($id){
		
		return TrainingDetail::leftJoin('training_programmes as programme','programme.id','training_details.programme')
			->where('training_details.id',$id)
			->select('programme.programme_name','training_details.*')
			->first();
		
	}
	
	public static function getTrainingsByCentre($current,$days_after_10){
		
	$finalArray=[];

        $data= TrainingDetail::join('training_location_details as loc_details','loc_details.training_id','=','training_details.id')
		   ->join('training_centres as centre','centre.centre_id','loc_details.training_centre_id')
		   ->where([
			   ['training_details.start_date','>=',$current,],
			   ['training_details.start_date','<=',$days_after_10]
		   ])
             ->select(DB::raw('count(*) AS total, sum(loc_details.participants_no) AS tot_participants'),'loc_details.training_centre_id as c_id')
             ->groupBy('loc_details.training_centre_id')
             ->get();

        foreach($data AS $li){
		   $finalArray[$li->c_id]=[
                'total'=> $li->total,'participants'=>$li->tot_participants
            ];
        }
		
        return $finalArray;
		
	}
	
	
	
	public static function getTrainingsByProgramme($current,$days_after_10){
		
	$finalArray=[];

        $data= TrainingDetail::join('training_location_details as loc_details','loc_details.training_id','=','training_details.id')
		   ->join('training_programmes as programme','programme.id','training_details.programme')
		   ->where([
			   ['training_details.start_date','>=',$current,],
			   ['training_details.start_date','<=',$days_after_10]
		   ])
             ->select(DB::raw('count(*) AS total, sum(loc_details.participants_no) AS tot_participants'),'training_details.programme as p_id')
             ->groupBy('training_details.programme')
             ->get();

        foreach($data AS $li){
		   $finalArray[$li->p_id]=[
                'total'=> $li->total,'participants'=>$li->tot_participants
            ];
        }
		
        return $finalArray;
		
	}
	
	public static function getOverallTrainingsByCentre(){
		
	$finalArray=[];

        $data= TrainingDetail::join('training_location_details as loc_details','loc_details.training_id','=','training_details.id')
		   ->join('training_centres as centre','centre.centre_id','loc_details.training_centre_id')
             ->select(DB::raw('count(*) AS total, sum(loc_details.participants_no) AS tot_participants'),'loc_details.training_centre_id as c_id')
             ->groupBy('loc_details.training_centre_id')
             ->get();

        foreach($data AS $li){
		   $finalArray[$li->c_id]=[
                'total'=> $li->total, 'participants'=>$li->tot_participants
            ];
        }
		
        return $finalArray;
		
	}
	
	public static function getOverallTrainingsByProgramme(){
		
	$finalArray=[];

        $data= TrainingDetail::join('training_location_details as loc_details','loc_details.training_id','=','training_details.id')
		   ->join('training_programmes as programme','programme.id','training_details.programme')
             ->select(DB::raw('count(*) AS total, sum(loc_details.participants_no) AS tot_participants'),'training_details.programme as p_id')
             ->groupBy('training_details.programme')
             ->get();

        foreach($data AS $li){
		   $finalArray[$li->p_id]=[
                'total'=> $li->total, 'participants'=>$li->tot_participants
            ];
        }
		
        return $finalArray;
		
	}
	
	//for public dashboard
	
	public static function getUpcomingTrainings($current){
	    
	    return TrainingDetail::select('training_details.*')
		     ->where([
				   ['training_details.start_date','>=',$current],
				   ])
		     ->orderBy('training_details.start_date','Asc')
		    ->LIMIT (5)
		     ->get();
    }
	
	public static function getConductedTrainings($current){
	    
	    return TrainingDetail::join('training_location_details as loc_details','training_details.id','loc_details.training_id')
		     ->select('training_details.*')
		     ->where([
				   ['training_details.start_date','<',$current],
					['loc_details.is_training_conducted','=',1]
				   ])
		    ->orderBy('training_details.start_date','Desc')
		    ->LIMIT (5)
		     ->get();
    }
	
	public static function getTrainingDetailsByIds($t_id,$l_id){
		
		return TrainingDetail::join('training_location_details as loc_details','training_details.id','loc_details.training_id')
		     ->leftJoin('training_centres as centres','centres.centre_id','loc_details.training_centre_id')
		     ->leftJoin('training_programmes as programme','programme.id','training_details.programme')
			->where([
				['training_details.id',$t_id],
				['loc_details.id',$l_id],
			])
		     ->select('training_details.*','loc_details.*','centres.centre_name','programme.programme_name')
		     ->first();
	}
	
	// For Extension Centres
	
	public static function programmeWiseTrainings($id){
		
	$finalArray=[];

        $data= TrainingDetail::join('training_location_details as loc_details','loc_details.training_id','=','training_details.id')
		   ->join('training_programmes as programme','programme.id','training_details.programme')
             ->select(DB::raw('count(*) AS total, sum(loc_details.participants_no) AS tot_participants'),'training_details.programme as p_id')
		   ->where('loc_details.training_centre_id','=',$id)
             ->groupBy('training_details.programme')
             ->get();

        foreach($data AS $li){
		   $finalArray[$li->p_id]=[
                'total'=> $li->total, 'participants'=>$li->tot_participants
            ];
        }
		
        return $finalArray;
		
	}
	
	public static function trainingListByCentre($id,$current,$days_after_10){
		

        return TrainingDetail::join('training_location_details as loc_details','loc_details.training_id','=','training_details.id')
		   ->join('training_programmes as programme','programme.id','training_details.programme')
             ->select('training_details.*','programme.programme_name')
		   ->where([
			['loc_details.training_centre_id','=',$id], 
			['training_details.start_date','>=',$current], 
			['training_details.start_date','<=',$days_after_10], 
		   ])->get();

        
		
	}
	
	
	
}
