<?php

namespace App\Http\Controllers\Admin\manageUser;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\CommonModels\District;
use App\survey\six_finance\ZilaParishad;
use App\survey\six_finance\AnchalikParishad;
use App\survey\six_finance\SixFinanceGpSelectionList;
class ManageUserController extends Controller
{
      public function __construct()
    {
        $this->middleware(['auth', 'admin_mdas']);
    }
    public function index()
    {
        $zilas = ZilaParishad::all();
        return view('admin.ManageUser.manage_user',compact('zilas'));
    }
    
    public function selectAnchalAjax(Request $request)
    {
        $returnData['msgType'] = false;
        $retrunData['data'] = [];
        $returnData['msg'] = "Failed to Request Process.";
        $gpList = [];
        try {
            $resultAP = AnchalikParishad::getAPsByZilaId($request->input('search_zila_id'));
            if (!count($resultAP) > 0) {
                $returnData['msg'] = "No Data Found";
                return response()->json($returnData);
              }  
            
        } catch (\Exception $e) {
            $returnData['msg'] = "Server Exception." . $e->getMessage();
            return response()->json($returnData);
        }
        $returnData['msgType'] = true;
        $returnData['data'] = $resultAP;
        $returnData['msg'] = "Success";
        return response()->json($returnData);

    }
    
  /* public function currentGPLevelEmpList(Request $request){
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Failed To Process Request.";
        
        
        $results =[];
        $i=1;

        try {
            $zp_id=$request->input('search_zila_id');
            $ap_id=$request->input('search_ap_id');
           
            
            foreach($resultGPC AS $gpc) {
                    
                     array_push($results,
                     array(
                        $i,
                        $gpc->zila_parishad_name,
                        $gpc->anchalik_parishad_name,
                        $gpc->gram_panchayat_name,
                        $gpc->emp_code
                    )
                );
                    
                    $i++;
                }
        } catch (\Exception $e) {
            $returnData['msg'] = "Server Exception." . $e->getMessage();
            return response()->json($returnData);
        }

        $returnData['msgType'] = true;
        $returnData['data'] = ["results"=> $results];
        $returnData['msg'] = "Success.";
        return response()->json($returnData);
    }
    */
    
   public function currentGPLevelEmpList(Request $request)
    {
        $returnData['msgType'] = false;
        $returnData['data'] = [];
        $returnData['msg'] = "Failed To Process Request.";
        
        $results =[];
        $i=1;

        try {
            $zp_id=$request->input('search_zila_id');
            $ap_id=$request->input('search_ap_id');
            $resultGPC = SixFinanceGpSelectionList::getMemberDetailsByZpAp($zp_id,$ap_id);
            
            foreach($resultGPC AS $gpc) {
                    
                     array_push($results,
                     array(
                        $i,
                        $gpc->zila_parishad_name,
                        $gpc->anchalik_parishad_name,
                        $gpc->gram_panchayat_name,
                        $gpc->emp_code
                    )
                );
                    
                    $i++;
                }
        } catch (\Exception $e) {
            $returnData['msg'] = "Server Exception." . $e->getMessage();
            return response()->json($returnData);
        }

        $returnData['msgType'] = true;
        $returnData['data'] = ["results"=> $results];
        $returnData['msg'] = "Success.";
        return response()->json($returnData);
   }
   

}
