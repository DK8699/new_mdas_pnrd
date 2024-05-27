<?php

namespace App\Http\Controllers\Admin\Grievance;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\CommonModels\Media;
use App\Grievance\GrievanceMediaEntry;
use App\Grievance\GrievanceMediaReference;
use App\Grievance\GrievanceEntry;
use Carbon\Carbon;
use PDF;
use Crypt;
use App;

class GrievanceDownloadController extends Controller
{
	//===================FOR MEDIA GRIEVANCE======================================
	
	public function download_permission(Request $request){
		   $returnData['msgType'] = false;
		   $returnData['data'] = [];
		   $returnData['msg'] = "Oops! Something went wrong!";
		
			$start_date =($request->input('start_date'));
			$end_date =($request->input('end_date'));

			if($request->input('cat_id') == 1) {

				if(!$request->input('start_date'))
				{ 
				$returnData['msg'] = "Please select Start Date";
				return response()->json($returnData);
				}

			}
			else{
				if($request->input('start_date') > $request->input('end_date'))
				{
					$returnData['msg'] = "Start Date cannot be after end date";
					return response()->json($returnData);
				}
			}

			$returnData['msgType'] = true;
			$returnData['msg'] = "Download in process";
			$returnData['data'] = ['start_date'=>$start_date,'end_date'=>$end_date];
			return response()->json($returnData);

	}
	
     public function download(Request $request){

        
     $data=[];
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //================================DOC NAME===============================================
        $docName = "GrievMediaReport";
        //========================================================================================
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	  $id = $request->input('date');
	  $id2 = $request->input('date2');
	  $media = Media::all();
	
		if(!$id2){
		$grievanceList = GrievanceMediaEntry::join('grievance_media_references as g_ref','g_ref.media_code','=','grievance_media_entries.media_code')
					->leftJoin('districts as d','d.id','=','grievance_media_entries.district_id')
					->leftJoin('blocks as b','b.id','=','grievance_media_entries.block_id')
					->leftJoin('media as med','med.id','=','grievance_media_entries.media_id')
					->leftJoin('gram_panchyats as g','g.gram_panchyat_id','=','grievance_media_entries.gp_id')
					->where([
						['g_ref.date_of_entry','=',$id],
					])
					->select('grievance_media_entries.*','d.district_name','b.block_name','g.gram_panchayat_name','med.name')
					->orderBy('grievance_media_entries.media_id','Asc')
					->get();
			
			
		}
		
		else{
			$grievanceList = GrievanceMediaEntry::join('grievance_media_references as g_ref','g_ref.media_code','=','grievance_media_entries.media_code')
					->leftJoin('districts as d','d.id','=','grievance_media_entries.district_id')
					->leftJoin('blocks as b','b.id','=','grievance_media_entries.block_id')
					->leftJoin('media as med','med.id','=','grievance_media_entries.media_id')
					->leftJoin('gram_panchyats as g','g.gram_panchyat_id','=','grievance_media_entries.gp_id')
					->where([
						['g_ref.date_of_entry','>=',$id],
						['g_ref.date_of_entry','<=',$id2],
					])
					->select('grievance_media_entries.*','d.district_name','b.block_name','g.gram_panchayat_name','med.name')
					->orderBy('grievance_media_entries.media_id','Asc')
					->get();
			
		}
        $data = [
		    "id"=>$id,
		   "grievanceList"=>$grievanceList,
		   "media"=>$media,
	   ];
		
		
        //echo($id);return;
        $pdf = PDF::loadView('admin.Grievance.template.media_grievance_download',$data);
	    $pdf->setPaper('A4','landscape');
        return $pdf->stream($docName.'.pdf');
        
		
    }
	
	//===================FOR INDIVIDUAL GRIEVANCE==================================
	
	public function acknowledgement_download(Request $request,$id){

        
     $data=[];
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //================================DOC NAME===============================================
        $docName = "Grievance_Reciept";
	 
        //========================================================================================
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
	  $g_id = Crypt::decrypt($id);
	 
		
		$grievanceData = GrievanceEntry::join('grievance_references as g_ref','g_ref.grievance_code','=','grievance_entries.grievance_code')
			->where('grievance_entries.id','=',$g_id)
			->select('grievance_entries.*')
			->first();
		
        $data = [
		   "grievanceData"=>$grievanceData,
	   ];
		
		//dd($grievanceList[3]); return;
        
        $pdf = PDF::loadView('admin.Grievance.template.individual_grievance_acknowledgement_download',$data);
	   $pdf->setPaper('A4','portrait');
        return $pdf->stream($docName.'.pdf');
        
        //$pdf->loadHTML($pdf);
            
        //return $pdf->download($docName.'.pdf');
    }
	
	

}
