<?php

namespace App\CourtCases;

use Illuminate\Database\Eloquent\Model;

class CourtCasesRecipient extends Model
{
    public static function getRecepientsMobileNos($district_id, $submitted_by_id, $targetBlocks){

        if(in_array($submitted_by_id, [3,4,5])) {
            $district_id = json_decode($district_id);
            $results= CourtCasesRecipient::where([
                ['court_cases_submitted_by_id', '=', $submitted_by_id],
                ['is_active', '=', 1],
            ])->whereIn('district_id', $district_id)->get();
        }
        elseif($submitted_by_id == 6) {
            $district_id = json_decode($district_id);
            $results= CourtCasesRecipient::where([
                ['court_cases_submitted_by_id', '=', $submitted_by_id],
                ['is_active', '=', 1],
            ])->whereIn('district_id', $district_id)->whereIn('block_id', $targetBlocks)->get();
        }
        else {
            $results= CourtCasesRecipient::where([
                ['court_cases_submitted_by_id', $submitted_by_id],
                ['is_active', '=', 1],
            ])->get();
        }

        $receipts=[];

        foreach ($results AS $li){
            array_push($receipts, $li->recipient_mobile);
        }

        return $receipts;
    }

    public static function isMobileAlreadyExists($mobile_no){

        $countMobile = CourtCasesRecipient::where('recipient_mobile','=',$mobile_no)
            ->count();

        if($countMobile > 0)
        {
            return false;
        }
        return true;
    }

    public static function geRecipientById($rid){
        return CourtCasesRecipient::leftJoin('districts as d','d.id','=','court_cases_recipients.district_id')
            ->join('court_cases_submitted_by as submit_by','submit_by.id','=','court_cases_recipients.court_cases_submitted_by_id')
            ->select('d.district_name', 'submit_by.submitted_by', 'court_cases_recipients.*')
            ->where('court_cases_recipients.id','=',$rid)
            ->first();
    }
}
