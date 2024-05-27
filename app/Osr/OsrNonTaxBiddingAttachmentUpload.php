<?php

namespace App\Osr;

use Illuminate\Database\Eloquent\Model;

class OsrNonTaxBiddingAttachmentUpload extends Model
{
    public static function getUploadedAttachments($general_id){
        $docs= OsrNonTaxBiddingAttachmentUpload::where('osr_non_tax_bidding_general_detail_id', $general_id)
            ->get();
        if(count($docs)>0){
            foreach ($docs AS $li){
                $data[$li->osr_non_tax_bidding_attachment_id]=$li->attachment_path;
            }
            return $data;
        }
        return NULL;
    }

    public static function alreadyExist($general_id, $doc_no){
        $res= OsrNonTaxBiddingAttachmentUpload::where([
            ['osr_non_tax_bidding_general_detail_id', '=', $general_id],
            ['osr_non_tax_bidding_attachment_id', '=', $doc_no],
        ])->count();

        if($res > 0){
            return true;
        }
        return false;
    }


    public static function checkDocsUploaded($general_id){
        $uploaded_docs_count= OsrNonTaxBiddingAttachmentUpload::where([
            ['osr_non_tax_bidding_general_detail_id', '=', $general_id],
        ])->count();

        $allActiveDocs= OsrNonTaxBiddingAttachment::getAllActiveDoc();

        if($uploaded_docs_count == count($allActiveDocs)){
            return true;
        }

        return false;
    }

    public static function getOnlyUploadedAttachments($general_id){
        return  OsrNonTaxBiddingAttachmentUpload::join('osr_non_tax_bidding_attachments AS a', 'a.id', '=', 'osr_non_tax_bidding_attachment_uploads.osr_non_tax_bidding_attachment_id')
            ->where('osr_non_tax_bidding_general_detail_id', $general_id)
            ->select('a.doc_name', 'osr_non_tax_bidding_attachment_uploads.attachment_path')
            ->orderBy('a.id')
            ->get();
    }
}
