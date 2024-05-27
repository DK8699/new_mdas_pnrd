<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;

class OsrNonTaxBidderAttachment extends Model
{
     public static function getAllActiveBidderDoc(){
        return OsrNonTaxBidderAttachment::where('is_active', 1)
            ->get();
    }
    
    
    public static function getAttachmentByBid($bid){
         return OsrNonTaxBidderAttachment::leftJoin('osr_non_tax_bidder_attachment_uploads as uploads','osr_non_tax_bidder_attachments.id','=','uploads.osr_non_tax_bidder_attachment_id') ->where('uploads.osr_non_tax_bidder_entry_id','=',$bid)->select('osr_non_tax_bidder_attachments.id as att_id','osr_non_tax_bidder_attachments.doc_name','uploads.*')->get();
     }
}
