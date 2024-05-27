<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;

class OsrNonTaxBidderAttachmentUpload extends Model
{
     public static function getUploadedBidderAttachments($asset_code){
        $docs= OsrNonTaxBidderAttachmentUpload::where('asset_code', $asset_code)
            ->get();
        if(count($docs)>0){
            foreach ($docs AS $li){
                $data[$li->osr_non_tax_bidding_attachment_id]=$li->attachment_path;
            }
            return $data;
        }
        return NULL;
    }
     public static function getAttachmentByBid($bid,$doc_id){
         return OsrNonTaxBidderAttachmentUpload::where('osr_non_tax_bidder_entry_id','=',$bid)
             ->where('osr_non_tax_bidder_attachment_id','=',$doc_id)->first();
     }
   
    public static function alreadyExist($b_id, $doc_no){
        $res= OsrNonTaxBidderAttachmentUpload::where([
            ['osr_non_tax_bidder_entry_id', '=', $b_id],
            ['osr_non_tax_bidder_attachment_id', '=', $doc_no],
        ])->count();

        if($res > 0){
            return true;
        }
        return false;
    }
    
    public static function attachmentUploadCount($b_id){
        return OsrNonTaxBidderAttachmentUpload::where('osr_non_tax_bidder_entry_id','=',$b_id)
            ->get();
    }
    

}
