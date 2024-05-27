<?php

namespace App\Grievance;
use DB;

use Illuminate\Database\Eloquent\Model;

class GrievanceMediaEntry extends Model
{
    public static function getReportByDate($date){
	    
	    return GrievanceMediaEntry::leftJoin('grievance_media_references as ref','grievance_media_entries.media_code','=','ref.media_code')
		    ->leftJoin('districts as d','d.id','=','grievance_media_entries.district_id')
		    ->leftJoin('blocks as b','b.id','=','grievance_media_entries.block_id')
		    ->leftJoin('gram_panchyats as g','g.gram_panchyat_id','=','grievance_media_entries.gp_id')
		    ->join('media as m','m.id','=','grievance_media_entries.media_id')
		    ->leftJoin('grievance_action_levels as l','l.id','=','ref.level')
		    ->where('grievance_media_entries.published_date','=',$date)
		    ->select('grievance_media_entries.*','m.name','ref.sent_status','ref.action_taken_by','ref.action_taken_status','d.district_name','b.block_name','g.gram_panchayat_name','l.level_name','ref.action_file_path','ref.report_file_path')
		    ->orderBy('m.id','Asc')
		    ->get();
    }
	
	public static function getDataByMediaId($id){
		return GrievanceMediaEntry::join('grievance_media_references as ref','grievance_media_entries.media_code','=','ref.media_code')
		    ->leftJoin('districts as d','d.id','=','grievance_media_entries.district_id')
		    ->leftJoin('blocks as b','b.id','=','grievance_media_entries.block_id')
		    ->leftJoin('gram_panchyats as g','g.gram_panchyat_id','=','grievance_media_entries.gp_id')
		    ->join('media as m','m.id','=','grievance_media_entries.media_id')
		    ->leftJoin('grievance_action_levels as l','l.id','=','ref.level')
		    ->where('grievance_media_entries.id','=',$id)
		    ->select('grievance_media_entries.*','m.name','l.level_name','l.id as level_id','d.district_name','b.block_name','g.gram_panchayat_name')
		    ->first();
	}
	
	public static function getMediaDataByDistrict(){
		
	$finalArray=[];

        $data= GrievanceMediaEntry::join('grievance_media_references as g_ref', 'g_ref.media_code','=','grievance_media_entries.media_code')
		   ->join('media as m','m.id','=','grievance_media_entries.media_id')
            ->where([
                ['grievance_media_entries.is_active','=',1]
            ])
            ->select(DB::raw('count(*) AS total'),'grievance_media_entries.district_id as d_id','grievance_media_entries.media_id as m_id')
            ->groupBy('grievance_media_entries.district_id')
            ->groupBy('grievance_media_entries.media_id')
            ->get();

        foreach($data AS $li){
            $finalArray[$li->d_id][$li->m_id]=$li->total;
        }
		
        return $finalArray;
		
	}
	
	public static function getRecievedData($id,$b_id,$gp_id,$level){
		
	  $finalArray=[];
		
		if($level == 2){
			$whereArray=[
				'g_ref.level'=>$level,
				'grievance_media_entries.district_id'=>$id,
				];
		}
		elseif($level == 3)
		{
			$whereArray=[
				'g_ref.level'=>$level,
				'grievance_media_entries.district_id'=>$id,
				'grievance_media_entries.block_id'=>$b_id,
			];
		}
		elseif($level == 4)
		{
			$whereArray=[
				'g_ref.level'=>$level,
				'grievance_media_entries.district_id'=>$id,
				'grievance_media_entries.block_id'=>$b_id,
				'grievance_media_entries.gp_id'=>$gp_id,
			];
		}
		else{
			
		}

        $data= GrievanceMediaEntry::join('grievance_media_references as g_ref', 'g_ref.media_code','=','grievance_media_entries.media_code')
		  ->join('grievance_action_levels as a_level','a_level.id','=','g_ref.level')
            ->where($whereArray)
            ->select(DB::raw('count(*) AS total'),'grievance_media_entries.district_id as d_id')
            ->groupBy('grievance_media_entries.district_id')
            ->get();

        foreach($data AS $li){
            $finalArray[$li->d_id]=$li->total;
        }
		
        return $finalArray;
		
	}
	
	public static function getDisposedData($id,$b_id,$gp_id,$level){
		
	  $finalArray=[];
		
		if($level == 2){
			$whereArray=[
				'g_ref.level'=>$level,
				'grievance_media_entries.district_id'=>$id,
				'g_ref.action_taken_status'=>1
				];
		}
		
		elseif($level == 3)
		{
			$whereArray=[
				'g_ref.level'=>$level,
				'grievance_media_entries.district_id'=>$id,
				'grievance_media_entries.block_id'=>$b_id,
				'g_ref.action_taken_status'=>1
			];
		}
		elseif($level == 4)
		{
			$whereArray=[
				'g_ref.level'=>$level,
				'grievance_media_entries.district_id'=>$id,
				'grievance_media_entries.block_id'=>$b_id,
				'grievance_media_entries.gp_id'=>$gp_id,
				'g_ref.action_taken_status'=>1
			];
		}
		else{
			
		}


        $data= GrievanceMediaEntry::join('grievance_media_references as g_ref', 'g_ref.media_code','=','grievance_media_entries.media_code')
		  ->join('grievance_action_levels as a_level','a_level.id','=','g_ref.level')
            ->where($whereArray)
            ->select(DB::raw('count(*) AS total'),'grievance_media_entries.district_id as d_id')
            ->groupBy('grievance_media_entries.district_id')
            ->get();

        foreach($data AS $li){
            $finalArray[$li->d_id]=$li->total;
        }
		
        return $finalArray;
		
	}
	
	public static function getMediaById($id,$b_id,$gp_id,$level){
		
		
		if($level == 2){
			$whereArray=[
				'grievance_media_entries.district_id'=>$id,
				];
		}
		if($level == 3)
		{
			$whereArray=[
				'ref.level'=>$level,
				'grievance_media_entries.district_id'=>$id,
				'grievance_media_entries.block_id'=>$b_id,
			];
		}
		elseif($level == 4)
		{
			$whereArray=[
				'ref.level'=>$level,
				'grievance_media_entries.district_id'=>$id,
				'grievance_media_entries.block_id'=>$b_id,
				'grievance_media_entries.gp_id'=>$gp_id,
			];
		}
		else{
			
		}
		
		 return GrievanceMediaEntry::leftJoin('grievance_media_references as ref','grievance_media_entries.media_code','=','ref.media_code')
		    ->join('media as m','m.id','=','grievance_media_entries.media_id')
		    ->join('grievance_action_levels as a_level','a_level.id','=','ref.level')
		    ->where($whereArray)
		    ->select('grievance_media_entries.*','m.name','ref.level','ref.action_taken_status','ref.date_of_entry','ref.action_file_path','ref.report_file_path','a_level.level_name')
		    ->orderBy('ref.date_of_entry','Desc')
		    ->get();
	}
	
	public static function getSchemeWiseMediaData(){
		
		$finalArray=[];
		
		$data = GrievanceMediaEntry::join('grievance_media_scheme_entries as m_scheme','m_scheme.media_code','=','grievance_media_entries.media_code')
			->select(DB::raw('count(*) AS total'),'m_scheme.scheme_id as s_id')
			->groupBy('m_scheme.scheme_id')
			->get();
		
		 foreach($data AS $li){
            $finalArray[$li->s_id]=$li->total;
        		}
		
        return $finalArray;
	}
	
	public static function getDistrictWiseMediaScheme($id){
	    
	    $finalArray=[];
		
		$data = GrievanceMediaEntry::join('grievance_media_references as g_ref','g_ref.media_code','=','grievance_media_entries.media_code')
			->join('grievance_media_scheme_entries as m_scheme','m_scheme.media_code','=','grievance_media_entries.media_code')
			->select(DB::raw('count(*) AS total'),'grievance_media_entries.district_id as d_id')
			->where('m_scheme.scheme_id','=',$id)
			->groupBy('grievance_media_entries.district_id')
			->get();
		
		 foreach($data AS $li){
            $finalArray[$li->d_id]=$li->total;
        		}
		
        return $finalArray;
    } 

	public static function getDistrictWiseMediaRecievedData(){
	    
	    $finalArray=[];
	    
	    $data= GrievanceMediaEntry::join('grievance_media_references as g_ref', 'g_ref.media_code','=','grievance_media_entries.media_code')
            ->select(DB::raw('count(*) AS total'),'grievance_media_entries.district_id as d_id')
            ->groupBy('grievance_media_entries.district_id')
            ->groupBy('grievance_media_entries.district_id')
            ->get();
	    

        foreach($data AS $li){
            $finalArray[$li->d_id]=$li->total;
        }
		
        return $finalArray;
	    
    }
	
	public static function getDistrictWiseMediaDisposedData(){
	    
	    $finalArray=[];
	    
	    $data= GrievanceMediaEntry::join('grievance_media_references as g_ref', 'g_ref.media_code','=','grievance_media_entries.media_code')
            ->select(DB::raw('count(*) AS total'),'grievance_media_entries.district_id as d_id')
		  ->where('g_ref.action_taken_status','=',1)
            ->groupBy('grievance_media_entries.district_id')
            ->get();
	    

        foreach($data AS $li){
            $finalArray[$li->d_id]=$li->total;
        }
		
        return $finalArray;
	    
    }

	
}
