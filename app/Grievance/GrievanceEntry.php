<?php

namespace App\Grievance;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use App\CommonModels\Block;
use DB;


class GrievanceEntry extends Model
{
	
    public static function getGrievDataById($id){
	    
	    return GrievanceEntry::join('grievance_references as g_ref','g_ref.grievance_code','=','grievance_entries.grievance_code')->where('grievance_entries.id','=',$id)
		    ->leftJoin('grievance_action_levels as l','l.id','=','g_ref.action_level')
		    ->select('grievance_entries.*','g_ref.document','g_ref.reply_status','l.level_name','l.id as l_id')
		    ->first();
    }
	
    public static function getGrievCodeById($id){
	    
	    return GrievanceEntry::where('id','=',$id)->select('grievance_code')->first();
    }
	
    public static function getGrievanceList(){
	    
	    return GrievanceEntry::join('grievance_references as g_ref','grievance_entries.grievance_code','=','g_ref.grievance_code')
		    ->leftJoin('grievance_action_levels as l','l.id','=','g_ref.action_level')
		     ->select('l.level_name','grievance_entries.id as id','grievance_entries.entry_date','grievance_entries.grievance_code','grievance_entries.name','grievance_entries.grievance_details','g_ref.entry_level','g_ref.action_level','g_ref.reply_status','g_ref.sent_status','g_ref.transferred_status')
		    ->get();
    }
    
    public static function getGrievanceListByLevel(){
	    
	    $users=Auth::user();
		
		if(Auth::user()->mdas_master_role_id==2 || Auth::user()->mdas_master_role_id==7)
		{
			$d_id = $users->district_code;
			$level = 2;
			
			$whereArray = [
				['grievance_entries.district_id','=',$d_id],
				['g_ref.action_level','=',$level],
			];
		}
		elseif(Auth::user()->mdas_master_role_id==3)
		{
			$d_id = $users->district_code;
			$ap_id = $users->ap_id;
			$b_id = Block::getBlockIdByAnchalikId($ap_id);
			$level = 3;
			$whereArray = [
				['grievance_entries.district_id','=',$d_id],
				['grievance_entries.block_id','=',$b_id->id],
				['g_ref.action_level','=',$level],
			];
			
		}
		elseif(Auth::user()->mdas_master_role_id==8)
		{
			$d_id = $users->district_code;
			$b_id = $users->block_id;
			$level = 3;
			
			$whereArray = [
				['grievance_entries.district_id','=',$d_id],
				['grievance_entries.block_id','=',$b_id],
				['g_ref.action_level','=',$level],
			];
		}
		elseif(Auth::user()->mdas_master_role_id==4)
		{
			$d_id = $users->district_code;
			$ap_id = $users->ap_id;
			$b_id = Block::getBlockIdByAnchalikId($ap_id);
			$gp_id = $users->gp_id;
			$level = 4;
			
			$whereArray = [
				['grievance_entries.district_id','=',$d_id],
				['grievance_entries.block_id','=',$b_id->id],
				['grievance_entries.gp_id','=',$gp_id],
				['g_ref.action_level','=',$level],
			];
		}
	    elseif(Auth::user()->mdas_master_role_id==9){
		    $d_id = $users->district_code;
		    $b_id = $users->block_id;
		    $gp_id = $users->gp_id;
		    $level = 4;
		    
		    $whereArray = [
				['grievance_entries.district_id','=',$d_id],
				['grievance_entries.block_id','=',$b_id],
				['grievance_entries.gp_id','=',$gp_id],
				['g_ref.action_level','=',$level],
			];
	    }
	    else
		{
		}
	    return GrievanceEntry::join('grievance_references as g_ref','grievance_entries.grievance_code','=','g_ref.grievance_code')
		    ->leftJoin('grievance_action_levels as l','l.id','=','g_ref.action_level')
		    ->where($whereArray)
		    ->select('l.level_name','grievance_entries.id as id','grievance_entries.entry_date','grievance_entries.grievance_code','grievance_entries.name','grievance_entries.grievance_details','g_ref.entry_level','g_ref.action_level','g_ref.reply_status')
		    ->get();
    }
	
    public static function getRecievedData($id,$b_id,$gp_id,$level){
		
	  $finalArray=[];
	    
	   if($level == 2)
		{
			$whereArray=[
				'g_ref.action_level'=>$level,
				'grievance_entries.district_id'=>$id,
				];
		}
		else if($level == 3)
		{
			$whereArray=[
				'g_ref.action_level'=>$level,
				'grievance_entries.district_id'=>$id,
				'grievance_entries.block_id'=>$b_id,
			];
		}
		else if($level == 4)
		{
			$whereArray=[
				'g_ref.action_level'=>$level,
				'grievance_entries.district_id'=>$id,
				'grievance_entries.block_id'=>$b_id,
				'grievance_entries.gp_id'=>$gp_id,
			];
		}
	    else
	    {
		    
	    }
	    
        $data= GrievanceEntry::join('grievance_references as g_ref', 'g_ref.grievance_code','=','grievance_entries.grievance_code')
		  ->leftJoin('grievance_action_levels as a_level','a_level.id','=','g_ref.entry_level')
            ->select(DB::raw('count(*) AS total'),'grievance_entries.district_id as d_id')
		  ->where($whereArray)
            ->groupBy('grievance_entries.district_id')
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
				'g_ref.action_level'=>$level,
				'grievance_entries.district_id'=>$id,
				'g_ref.reply_status'=>1
				];
	    }
		
		else if($level == 3)
		{
			$whereArray=[
				'g_ref.action_level'=>$level,
				'grievance_entries.district_id'=>$id,
				'grievance_entries.block_id'=>$b_id,
				'g_ref.reply_status'=>1
			];
		}
		elseif($level == 4)
		{
			$whereArray=[
				'g_ref.action_level'=>$level,
				'grievance_entries.district_id'=>$id,
				'grievance_entries.block_id'=>$b_id,
				'grievance_entries.gp_id'=>$gp_id,
				'g_ref.reply_status'=>1
			];
		}
		else{
			
		}


        $data= GrievanceEntry::join('grievance_references as g_ref', 'g_ref.grievance_code','=','grievance_entries.grievance_code')
		  ->join('grievance_action_levels as a_level','a_level.id','=','g_ref.entry_level')
            ->where($whereArray)
            ->select(DB::raw('count(*) AS total'),'grievance_entries.district_id as d_id')
            ->groupBy('grievance_entries.district_id')
            ->get();

        foreach($data AS $li){
            $finalArray[$li->d_id]=$li->total;
        }
		
        return $finalArray;
		
	}
	
	public static function getIndividualDataByDistrict(){
		
	$finalArray=[];

        $data= GrievanceEntry::join('grievance_references as g_ref', 'g_ref.grievance_code','=','grievance_entries.grievance_code')
            ->select(DB::raw('count(*) AS total'),'grievance_entries.district_id as d_id')
            ->groupBy('grievance_entries.district_id')
            ->get();

        foreach($data AS $li){
            $finalArray[$li->d_id]=$li->total;
        }
		
        return $finalArray;
		
	}
	
	public static function getSchemeWiseIndividualData(){
		
		$finalArray=[];
		
		$data = GrievanceEntry::join('grievance_scheme_entries as i_scheme','i_scheme.grievance_code','=','grievance_entries.grievance_code')
			->select(DB::raw('count(*) AS total'),'i_scheme.scheme_id as s_id')
			->groupBy('i_scheme.scheme_id')
			->get();
		
		 foreach($data AS $li){
            $finalArray[$li->s_id]=$li->total;
        		}
		
        return $finalArray;
	}
	
	public static function getIndividualData(){
		
		$users=Auth::user();
		
		if(Auth::user()->mdas_master_role_id==2 || Auth::user()->mdas_master_role_id==7)
		{
			$d_id = $users->district_code;
			
			$whereArray = [
				['grievance_entries.district_id','=',$d_id],
				];
		}
		elseif(Auth::user()->mdas_master_role_id==3)
		{
			$d_id = $users->district_code;
			$ap_id = $users->ap_id;
			$b_id = Block::getBlockIdByAnchalikId($ap_id);
			$whereArray = [
				['grievance_entries.district_id','=',$d_id],
				['grievance_entries.block_id','=',$b_id->id],
			];
			
		}
		elseif(Auth::user()->mdas_master_role_id==8)
		{
			$d_id = $users->district_code;
			$b_id = $users->block_id;
			
			$whereArray = [
				['grievance_entries.district_id','=',$d_id],
				['grievance_entries.block_id','=',$b_id],
			];
		}
		elseif(Auth::user()->mdas_master_role_id==4)
		{
			$d_id = $users->district_code;
			$ap_id = $users->ap_id;
			$b_id = Block::getBlockIdByAnchalikId($ap_id);
			$gp_id = $users->gp_id;
			
			$whereArray = [
				['grievance_entries.district_id','=',$d_id],
				['grievance_entries.block_id','=',$b_id->id],
				['grievance_entries.gp_id','=',$gp_id],
			];
		}
	    elseif(Auth::user()->mdas_master_role_id==9){
		    $d_id = $users->district_code;
		    $b_id = $users->block_id;
		    $gp_id = $users->gp_id;
		    
		    $whereArray = [
				['grievance_entries.district_id','=',$d_id],
				['grievance_entries.block_id','=',$b_id],
				['grievance_entries.gp_id','=',$gp_id],
			];
	    }
	    else
		{
		}
				
		return GrievanceEntry::join('grievance_references as g_ref','grievance_entries.grievance_code','=','g_ref.grievance_code')
		    ->leftJoin('districts as d','d.id','=','grievance_entries.district_id')
		    ->leftJoin('blocks as b','b.id','=','grievance_entries.block_id')
		    ->leftJoin('gram_panchyats as g','g.gram_panchyat_id','=','grievance_entries.gp_id')
		    ->leftJoin('grievance_action_levels as l','l.id','=','g_ref.entry_level')
		    ->where($whereArray)
		    ->select('l.level_name','grievance_entries.id as id','grievance_entries.entry_date','grievance_entries.grievance_code','grievance_entries.name','grievance_entries.grievance_details','grievance_entries.district_id','d.district_name','b.block_name','g.gram_panchayat_name','g_ref.entry_level','g_ref.action_level','g_ref.reply_status')
		    ->get();
		
		
	}
	
	public static function getDistrictWiseIndividualScheme($id){
	    
	    $finalArray=[];
		
		$data = GrievanceEntry::join('grievance_references as g_ref','g_ref.grievance_code','=','grievance_entries.grievance_code')
			->join('grievance_scheme_entries as i_scheme','i_scheme.grievance_code','=','grievance_entries.grievance_code')
			->select(DB::raw('count(*) AS total'),'grievance_entries.district_id as d_id')
			->where('i_scheme.scheme_id','=',$id)
			->groupBy('grievance_entries.district_id')
			->get();
		
		 foreach($data AS $li){
            $finalArray[$li->d_id]=$li->total;
        		}
		
        return $finalArray;
    } 
	
	public static function getDistrictWiseIndividualRecievedData(){
	    
	    $finalArray=[];
	    
	    $data= GrievanceEntry::join('grievance_references as g_ref', 'g_ref.grievance_code','=','grievance_entries.grievance_code')
            ->select(DB::raw('count(*) AS total'),'grievance_entries.district_id as d_id')
            ->groupBy('grievance_entries.district_id')
            ->get();
	    

        foreach($data AS $li){
            $finalArray[$li->d_id]=$li->total;
        }
		
        return $finalArray;
	    
    }
	
	public static function getDistrictWiseIndividualDisposedData(){
	    
	    $finalArray=[];
	    
	    $data= GrievanceEntry::join('grievance_references as g_ref', 'g_ref.grievance_code','=','grievance_entries.grievance_code')
            ->select(DB::raw('count(*) AS total'),'grievance_entries.district_id as d_id')
		  ->where('g_ref.reply_status','=',1)
            ->groupBy('grievance_entries.district_id')
            ->get();
	    

        foreach($data AS $li){
            $finalArray[$li->d_id]=$li->total;
        }
		
        return $finalArray;
	    
    }

}
