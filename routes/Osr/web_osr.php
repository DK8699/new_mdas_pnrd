<?php

Route::post('osr/non_tax/dw_asset/save','Osr\OsrNonTaxAssetController@asset_save')->name('osr.non_tax.dw_asset.save');

Route::post('osr/non_tax/dw_asset/getById','Osr\OsrNonTaxAssetController@getAssetEntriesById')->name('osr.non_tax.dw_asset.getById');

Route::post('osr/non_tax/dw_asset/edit','Osr\OsrNonTaxAssetController@saveEditById')->name('osr.non_tax.dw_asset.edit');

Route::post('osr/non_tax/dw_asset/geo_tag_details','Osr\OsrNonTaxAssetController@geo_tagging_details')->name('osr.non_tax.dw_asset.geo_tag_details');

Route::post('osr/non_tax/dw_asset/geo_tag_approve','Osr\OsrNonTaxAssetController@geo_tagging_approval')->name('osr.non_tax.dw_asset.geo_tag_approve');

Route::get('osr/non_tax/year_wise_assets','Osr\OsrYearWiseAssetsController@index')->name('osr.non_tax.year_wise_assets');
Route::get('osr/non_tax/year_wise_asset_shortlist/{fy_id}/{cat_id}','Osr\OsrYearWiseAssetsController@year_wise_asset_shortlist')->name('osr.non_tax.year_wise_asset_shortlist');
Route::post('osr/non_tax/year_wise_asset_shortlist/save','Osr\OsrYearWiseAssetsController@year_wise_asset_shortlist_save')->name('osr.non_tax.year_wise_asset_shortlist.save');


//------------------------ BIDDING -------------------------------------------------------------------------------------

Route::get('osr/non_tax/asset/bidding/fy/{asset_code}/{fy_id}','Osr\OsrNonTaxAssetBiddingController@asset_bidding');

Route::post('osr/non_tax/asset/bidding/save_general_detail','Osr\OsrNonTaxAssetBiddingController@save_general_detail')->name('osr.non_tax.asset.bidding.save_general_detail');
Route::post('osr/non_tax/asset/bidding/getGeneralDetails','Osr\OsrNonTaxAssetBiddingController@getGeneralDetails')->name('osr.non_tax.asset.bidding.getGeneralDetails');
Route::post('osr/non_tax/asset/bidding/status_update','Osr\OsrNonTaxAssetBiddingController@bidder_status_update')->name('osr.non_tax.asset.bidding.status_update');

Route::post('osr/non_tax/asset/bidding/attachment_upload','Osr\OsrNonTaxAssetBiddingController@bidder_attachment_upload')->name('osr.non_tax.asset.bidding.attachment_upload');
Route::post('osr/non_tax/asset/bidding/final_submit','Osr\OsrNonTaxAssetBiddingController@bidding_final_submit')->name('osr.non_tax.asset.bidding.final_submit');

Route::post('osr/non_tax/asset/bidding/report_upload','Osr\OsrNonTaxAssetBiddingController@report_upload')->name('osr.non_tax.asset.bidding.report_upload');
//------------------------ BIDDER --------------------------------------------------------------------------------------

Route::post('osr/non_tax/asset/bidder/save','Osr\OsrNonTaxBidderController@bidder_save')->name('osr.non_tax.asset.bidder.save');
Route::post('osr/non_tax/asset/bidder/getById','Osr\OsrNonTaxBidderController@bidderGetById')->name('osr.non_tax.asset.bidder.getById');
Route::post('osr/non_tax/asset/bidder/edit','Osr\OsrNonTaxBidderController@bidder_edit')->name('osr.non_tax.asset.bidder.edit');

Route::post('osr/non_tax/bidder/Attachment/getById','Osr\OsrNonTaxBidderController@bidderAttachmentGetById')->name('osr.non_tax.bidderAttachment.getById');
Route::post('osr/non_tax/asset/bidder/attachment_upload','Osr\OsrNonTaxBidderController@bidder_attachment_upload')->name('osr.non_tax.bidder.attachment_upload');


//------------------------- DOWNLOAD PDF--------------------------------------------------------------------------------

Route::get('osr/non_tax/asset/download/comparative/{asset_code}/{fy_id}','Osr\OsrNonTaxBiddingDownloadController@comparativeBiddingReport');
Route::get('osr/non_tax/asset/download/detailreport/{asset_code}/{fy_id}','Osr\OsrNonTaxBiddingDownloadController@detailBiddingReport');


//------------------------- TRACK PAYMENT ------------------------------------------------------------------------------

Route::get('osr/non_tax/asset/track/fy/{asset_code}/{fy_id}','Osr\OsrNonTaxAssetPaymentController@index');

Route::post('osr/non_tax/asset/payment/forfeited_earnest_money_save','Osr\OsrNonTaxAssetPaymentController@forfeited_earnest_money_save')->name('osr.non_tax.asset.payment.forfeited_earnest_money_save');

//--collection
Route::post('osr/non_tax/asset/payment/collection', 'Osr\OsrNonTaxAssetPaymentController@collection')->name('osr.non_tax.asset.payment.collection');

//--sharing
Route::post('osr/non_tax/asset/payment/instalment', 'Osr\OsrNonTaxAssetPaymentController@instalment')->name('osr.non_tax.asset.payment.instalment');

Route::post('osr/non_tax/asset/payment/mark_as_defaulter','Osr\OsrNonTaxAssetPaymentController@mark_as_defaulter')->name('osr.non_tax.asset.payment.mark_as_defaulter');
Route::post('osr/non_tax/asset/payment/update_bakijai','Osr\OsrNonTaxAssetPaymentController@update_bakijai')->name('osr.non_tax.asset.payment.update_bakijai');

Route::post('osr/non_tax/dw_asset/gapPeriod','Osr\OsrNonTaxAssetPaymentController@gapPeriod')->name('osr.non_tax.asset.payment.gapPeriod');

//--------------PAYMENT VIEW(SHARE DISTRIBUTION) AP,GP wise-------------------------------------------------------------
Route::get('osr/non_tax/payment/view/{ins_id}/{asset_code}/{fy_id}','Osr\OsrNonTaxAssetPaymentController@payment_view');

//--------------GAP Payment View----------------------------------------------------------------------------------------
Route::get('osr/non_tax/payment/gap_view/{ins_id}/{asset_code}/{fy_id}','Osr\OsrNonTaxAssetPaymentController@gap_payment_view');


//Route::post('osr/non_tax/dw_asset/gapPeriodEdit','Osr\OsrNonTaxAssetPaymentController@gapPeriodEdit')->name('osr.non_tax.dw_asset.gapPeriodEdit');
//Route::post('osr/non_tax/dw_asset/formSelling','Osr\OsrNonTaxAssetPaymentController@formSelling')->name('osr.non_tax.dw_asset.formSelling');
//Route::post('osr/non_tax/dw_asset/formSellingEdit','Osr\OsrNonTaxAssetPaymentController@formSellingEdit')->name('osr.non_tax.dw_asset.formSellingEdit');
//Route::post('osr/non_tax/dw_asset/bakiJari','Osr\OsrNonTaxAssetPaymentController@bakiJari')->name('osr.non_tax.dw_asset.bakiJari');
//Route::post('osr/non_tax/dw_asset/bakiJariEdit','Osr\OsrNonTaxAssetPaymentController@bakiJariEdit')->name('osr.non_tax.dw_asset.bakiJariEdit');

//------------------OSR ASSET SHORTLISTING REPORT UPLOAD----------------------------------------------------------------
//Asset download upload

Route::get('osr/non_tax/asset_download_upload','Osr\OsrController@asset_download_upload')->name('osr.non_tax.asset_download_upload');

Route::get('osr/non_tax/asset/download/assetReport/{fy_id}/{z_id}','Osr\OsrNonTaxAssetDownloadController@assetReport')->name('osr.non_tax.asset.download.assetReport');

//view pdf
Route::get('osr/non_tax/asset/shortlist/report/view/{fy_id}/{z_id}','Osr\OsrController@shortlistReportView')->name('osr.non_tax.asset.shortlist.report.view');

Route::post('osr/non_tax/asset/Attachment/getById','Osr\OsrYearWiseAssetsController@assetAttachmentGetById')->name('osr.non_tax.asset.Attachment.getById');
Route::post('osr/non_tax/asset/Attachment/attachment_upload','Osr\OsrYearWiseAssetsController@asset_attachment_upload')->name('osr.non_tax.asset.attachment_upload');


//------------------------- OTHER RESOURCES ----------------------------------------------------------------------------

//Route::get('osr/non_tax/dw_asset/other_assets', 'Osr\OsrNonTaxOtherAssetController@index')->name('osr.non_tax.dw_asset.other_assets');
//Route::get('osr/non_tax/dw_other_asset_list/{cat_id}','Osr\OsrNonTaxOtherAssetController@nt_dw_other_asset_list');
Route::post('osr/non_tax/dw_other_asset/save','Osr\OsrNonTaxOtherAssetController@asset_save')->name('osr.non_tax.dw_other_asset.save');
Route::get('osr/non_tax/dw_other_asset/track/fy/{asset_code}/{osr_fy_id}','Osr\OsrNonTaxOtherAssetPaymentController@index');
Route::post('osr/non_tax/dw_other_asset/track/save_amount', 'Osr\OsrNonTaxOtherAssetPaymentController@save_amount')->name('osr.non_tax.dw_other_asset.track.save_amount');
Route::post('osr/non_tax/dw_other_asset/track/save_agreement', 'Osr\OsrNonTaxOtherAssetPaymentController@save_agreement')->name('osr.non_tax.dw_other_asset.track.save_agreement');
Route::post('osr/non_tax/dw_other_asset/track/save_agreement_instalment', 'Osr\OsrNonTaxOtherAssetPaymentController@save_agreement_instalment')->name('osr.non_tax.dw_other_asset.track.save_agreement_instalment');


/*------------------------- NEW OSR ----------------------------------------------------------------------------------*/

Route::get('osr/osr_panel/{fy_id}','Osr\OsrController@panel');
Route::get('osr/non_tax/asset_entry_panel','Osr\OsrController@asset_entry_panel')->name('osr.non_tax.asset_entry_panel');
Route::get('osr/non_tax/dw_asset_list/{branch_id}','Osr\OsrController@nt_dw_asset_list');
Route::get('osr/non_tax/dw_asset_show_list/{branch_id}/{level}','Osr\OsrController@nt_dw_asset_show_list');
Route::get('osr/non_tax/asset_show_list','Osr\OsrController@asset_show_list')->name('osr.non_tax.asset_show_list');
Route::post('osr/non_tax/asset_show_list','Osr\OsrController@asset_show_list')->name('osr.non_tax.asset_show_list');

Route::get('osr/non_tax/asset_shortlist_bidding','Osr\OsrController@asset_shortlist_bidding')->name('osr.non_tax.asset_shortlist_bidding');
Route::post('osr/non_tax/asset_shortlist_bidding','Osr\OsrController@asset_shortlist_bidding')->name('osr.non_tax.asset_shortlist_bidding');
Route::post('osr/non_tax/asset_shortlist_entry','Osr\OsrController@asset_shortlist_entry')->name('osr.non_tax.asset_shortlist_entry');


Route::get('osr/non_tax/asset_shortlist_bidding_update_payment','Osr\OsrController@asset_shortlist_bidding_update_payment')->name('osr.non_tax.asset_shortlist_bidding_update_payment');
Route::post('osr/non_tax/asset_shortlist_bidding_update_payment','Osr\OsrController@asset_shortlist_bidding_update_payment')->name('osr.non_tax.asset_shortlist_bidding_update_payment');


//------BIDDING------------------------------------------
Route::post('osr/non_tax/bidder/Attachment/getById','Osr\OsrNonTaxBidderController@bidderAttachmentGetById')->name('osr.non_tax.bidderAttachment.getById');


//------------------------OTHER SOURCES ASSET --------------------------------------------------------------------------
Route::get('osr/non_tax/dw_asset/other_assets', 'Osr\OsrNonTaxOtherAssetController@index')->name('osr.non_tax.dw_asset.other_assets');
Route::get('osr/non_tax/dw_other_asset_list/{cat_id}','Osr\OsrNonTaxOtherAssetController@nt_dw_other_asset_list');

Route::get('osr/non_tax/other_asset_show_list','Osr\OsrNonTaxOtherAssetController@other_asset_show_list')->name('osr.non_tax.other_asset_show_list');
Route::post('osr/non_tax/other_asset_show_list','Osr\OsrNonTaxOtherAssetController@other_asset_show_list')->name('osr.non_tax.other_asset_show_list');

Route::post('osr/non_tax/dw_other_asset/getById','Osr\OsrNonTaxOtherAssetController@getOtherAssetEntriesById')->name('osr.non_tax.dw_other_asset.getById');
Route::post('osr/non_tax/dw_other_asset/edit','Osr\OsrNonTaxOtherAssetController@saveEditById')->name('osr.non_tax.dw_other_asset.edit');




//--------------------------------------- ZP DASHBOARD -----------------------------------------------------------------
//---------------------------------------(Settlement)-------------------------------------------------------------------
Route::get('osr/non_tax/asset/zp/zp_asset_settlement_percent/{fy_id}','Osr\zp_panel\OsrNonTaxAssetZpController@zp_asset_settlement_percent');
Route::get('osr/non_tax/asset/zp/ap_list_asset_settlement_percent/{fy_id}','Osr\zp_panel\OsrNonTaxAssetZpController@ap_list_asset_settlement_percent');
Route::get('osr/non_tax/asset/zp/gp_list_asset_settlement_percent/{fy_id}','Osr\zp_panel\OsrNonTaxAssetZpController@gp_list_asset_settlement_percent');

Route::get('osr/non_tax/asset/zp/zp_asset_settlement_percent_branch/{fy_id}/{branch_id}','Osr\zp_panel\OsrNonTaxAssetZpController@zp_asset_settlement_percent_branch');

Route::get('osr/non_tax/asset/zp/ap_asset_settlement_percent/{fy_id}/{ap_id}','Osr\zp_panel\OsrNonTaxAssetZpController@ap_asset_settlement_percent');
Route::get('osr/non_tax/asset/zp/gp_asset_settlement_percent/{fy_id}/{ap_id}/{gp_id}','Osr\zp_panel\OsrNonTaxAssetZpController@gp_asset_settlement_percent');

Route::get('osr/non_tax/asset/zp/ap_asset_settlement_percent_branch/{fy_id}/{ap_id}/{branch_id}','Osr\zp_panel\OsrNonTaxAssetZpController@ap_asset_settlement_percent_branch');
Route::get('osr/non_tax/asset/zp/gp_asset_settlement_percent_branch/{fy_id}/{ap_id}/{gp_id}/{branch_id}','Osr\zp_panel\OsrNonTaxAssetZpController@gp_asset_settlement_percent_branch');

//-------------------------------------(Defaulter)----------------------------------------------------------------

Route::get('osr/non_tax/asset/zp/zp_asset_defaulter/{fy_id}','Osr\zp_panel\OsrNonTaxAssetZpController@zp_asset_defaulter');
Route::get('osr/non_tax/asset/zp/ap_list_asset_defaulter/{fy_id}','Osr\zp_panel\OsrNonTaxAssetZpController@ap_list_asset_defaulter');
Route::get('osr/non_tax/asset/zp/gp_list_asset_defaulter/{fy_id}','Osr\zp_panel\OsrNonTaxAssetZpController@gp_list_asset_defaulter');

Route::get('osr/non_tax/asset/zp/zp_asset_defaulter_branch/{fy_id}/{branch_id}','Osr\zp_panel\OsrNonTaxAssetZpController@zp_asset_defaulter_branch');
Route::get('osr/non_tax/asset/zp/ap_asset_defaulter/{fy_id}/{ap_id}','Osr\zp_panel\OsrNonTaxAssetZpController@ap_asset_defaulter');
Route::get('osr/non_tax/asset/zp/gp_asset_defaulter/{fy_id}/{ap_id}/{gp_id}','Osr\zp_panel\OsrNonTaxAssetZpController@gp_asset_defaulter');


Route::get('osr/non_tax/asset/zp/ap_asset_defaulter_branch/{fy_id}/{ap_id}/{branch_id}','Osr\zp_panel\OsrNonTaxAssetZpController@ap_asset_defaulter_branch');
Route::get('osr/non_tax/asset/zp/gp_asset_defaulter_branch/{fy_id}/{ap_id}/{gp_id}/{branch_id}','Osr\zp_panel\OsrNonTaxAssetZpController@gp_asset_defaulter_branch');


//*************************************District Level PORAG-15-10-19**********************************************************
Route::post('/district/Osr/dashboard/listOfZPDefaulterZilaWise','Osr\zp_panel\OsrNonTaxAssetZpController@listOfZPDefaulterZilaWise')->name('district.Osr.dashboard.listOfZPDefaulterZilaWise');
Route::post('/district/Osr/dashboard/listOfAPDefaulterZilaWise','Osr\zp_panel\OsrNonTaxAssetZpController@listOfAPDefaulterZilaWise')->name('district.Osr.dashboard.listOfAPDefaulterZilaWise');
Route::post('/district/Osr/dashboard/listOfGPDefaulterZilaWise','Osr\zp_panel\OsrNonTaxAssetZpController@listOfGPDefaulterZilaWise')->name('district.Osr.dashboard.listOfGPDefaulterZilaWise');
//*************************************AP Level**********************************************************
Route::post('/district/Osr/dashboard/listOfAPDefaulterAPWise','Osr\zp_panel\OsrNonTaxAssetZpController@listOfAPDefaulterAPWise')->name('district.Osr.dashboard.listOfAPDefaulterAPWise');
Route::post('/district/Osr/dashboard/listOfGPDefaulterAPWise','Osr\zp_panel\OsrNonTaxAssetZpController@listOfGPDefaulterAPWise')->name('district.Osr.dashboard.listOfGPDefaulterAPWise');
//*************************************GP Level**********************************************************
Route::post('/district/Osr/dashboard/listOfGPDefaulterGPWise','Osr\zp_panel\OsrNonTaxAssetZpController@listOfGPDefaulterGPWise')->name('district.Osr.dashboard.listOfGPDefaulterGPWise');


//--------------------------------------(Revenue Collection)---------------------------------------------------------------

Route::get('osr/non_tax/asset/zp/ap_list_asset_collection/{fy_id}','Osr\zp_panel\OsrNonTaxAssetZpController@ap_list_asset_collection');
Route::get('osr/non_tax/asset/zp/gp_list_asset_collection/{fy_id}','Osr\zp_panel\OsrNonTaxAssetZpController@gp_list_asset_collection');

Route::get('osr/non_tax/asset/zp/zp_asset_collection_branch/{fy_id}/{branch_id}','Osr\zp_panel\OsrNonTaxAssetZpController@zp_asset_collection_branch');
//Route::get('osr/non_tax/asset/zp/ap_asset_collection/{fy_id}/{ap_id}','Osr\zp_panel\OsrNonTaxAssetZpController@ap_asset_collection');
//Route::get('osr/non_tax/asset/zp/gp_asset_collection/{fy_id}/{ap_id}/{gp_id}','Osr\zp_panel\OsrNonTaxAssetZpController@gp_asset_collection');

Route::get('osr/non_tax/asset/zp/ap_asset_collection_branch/{fy_id}/{ap_id}/{branch_id}','Osr\zp_panel\OsrNonTaxAssetZpController@ap_asset_collection_branch');
Route::get('osr/non_tax/asset/zp/gp_asset_collection_branch/{fy_id}/{ap_id}/{gp_id}/{branch_id}','Osr\zp_panel\OsrNonTaxAssetZpController@gp_asset_collection_branch');

//-------------------------------------(Share Distribution)--------------------------------------------------------------------

//Route::get('osr/non_tax/asset/zp/zp_asset_share/{fy_id}','Osr\zp_panel\OsrNonTaxAssetZpController@zp_asset_share');
Route::get('osr/non_tax/asset/zp/ap_list_asset_share/{fy_id}','Osr\zp_panel\OsrNonTaxAssetZpController@ap_list_asset_share');
Route::get('osr/non_tax/asset/zp/gp_list_asset_share/{fy_id}','Osr\zp_panel\OsrNonTaxAssetZpController@gp_list_asset_share');

Route::get('osr/non_tax/asset/zp/zp_asset_share_branch/{fy_id}/{branch_id}','Osr\zp_panel\OsrNonTaxAssetZpController@zp_asset_share_branch');
//Route::get('osr/non_tax/asset/zp/ap_asset_share/{fy_id}/{ap_id}','Osr\zp_panel\OsrNonTaxAssetZpController@ap_asset_share');
//Route::get('osr/non_tax/asset/zp/gp_asset_share/{fy_id}/{ap_id}/{gp_id}','Osr\zp_panel\OsrNonTaxAssetZpController@gp_asset_share');

Route::get('osr/non_tax/asset/zp/ap_asset_share_branch/{fy_id}/{ap_id}/{branch_id}','Osr\zp_panel\OsrNonTaxAssetZpController@ap_asset_share_branch');
Route::get('osr/non_tax/asset/zp/gp_asset_share_branch/{fy_id}/{ap_id}/{gp_id}/{branch_id}','Osr\zp_panel\OsrNonTaxAssetZpController@gp_asset_share_branch');

//---------------------------------------other asset(Revenue Collection)---------------------------------------------------

Route::get('osr/non_tax/other_asset/zp/ap_list_other_asset_collection/{fy_id}','Osr\zp_panel\OsrNonTaxOtherAssetZpController@ap_list_other_asset_collection');
Route::get('osr/non_tax/other_asset/zp/gp_list_other_asset_collection/{fy_id}','Osr\zp_panel\OsrNonTaxOtherAssetZpController@gp_list_other_asset_collection');


Route::get('osr/non_tax/other_asset/zp/ap_other_asset_collection/{fy_id}/{ap_id}','Osr\zp_panel\OsrNonTaxOtherAssetZpController@ap_other_asset_collection');
Route::get('osr/non_tax/other_asset/zp/gp_other_asset_collection/{fy_id}/{ap_id}/{gp_id}','Osr\zp_panel\OsrNonTaxOtherAssetZpController@gp_other_asset_collection');

//---------------------------------------other asset(share Distribution)-------------------------------------------------------------

Route::get('osr/non_tax/other_asset/zp/zp_other_asset_share/{fy_id}','Osr\zp_panel\OsrNonTaxOtherAssetZpController@zp_other_asset_share');
Route::get('osr/non_tax/other_asset/zp/ap_list_other_asset_share/{fy_id}','Osr\zp_panel\OsrNonTaxOtherAssetZpController@ap_list_other_asset_share');
Route::get('osr/non_tax/other_asset/zp/gp_list_other_asset_share/{fy_id}','Osr\zp_panel\OsrNonTaxOtherAssetZpController@gp_list_other_asset_share');

Route::get('osr/non_tax/other_asset/zp/ap_other_asset_share/{fy_id}/{ap_id}','Osr\zp_panel\OsrNonTaxOtherAssetZpController@ap_other_asset_share');
Route::get('osr/non_tax/other_asset/zp/gp_other_asset_share/{fy_id}/{ap_id}/{gp_id}','Osr\zp_panel\OsrNonTaxOtherAssetZpController@gp_other_asset_share');


//--------------------------------------- AP DASHBOARD -----------------------------------------------------------------
//======================================================================================================================


//---------------------------------------(Settlement)-------------------------------------------------------------------
Route::get('osr/non_tax/asset/ap/ap_asset_settlement_percent/{fy_id}','Osr\ap_panel\OsrNonTaxAssetApController@ap_asset_settlement_percent');
Route::get('osr/non_tax/asset/ap/gp_list_asset_settlement_percent/{fy_id}','Osr\ap_panel\OsrNonTaxAssetApController@gp_list_asset_settlement_percent');

Route::get('osr/non_tax/asset/ap/ap_asset_settlement_percent_branch/{fy_id}/{branch_id}','Osr\ap_panel\OsrNonTaxAssetApController@ap_asset_settlement_percent_branch');
Route::get('osr/non_tax/asset/ap/gp_asset_settlement_percent/{fy_id}/{gp_id}','Osr\ap_panel\OsrNonTaxAssetApController@gp_asset_settlement_percent');

Route::get('osr/non_tax/asset/ap/gp_asset_settlement_percent_branch/{fy_id}/{gp_id}/{branch_id}','Osr\ap_panel\OsrNonTaxAssetApController@gp_asset_settlement_percent_branch');

//-------------------------------------(Defaulter)----------------------------------------------------------------------
Route::get('osr/non_tax/asset/ap/ap_asset_defaulter/{fy_id}','Osr\ap_panel\OsrNonTaxAssetApController@ap_asset_defaulter');
Route::get('osr/non_tax/asset/ap/gp_list_asset_defaulter/{fy_id}','Osr\ap_panel\OsrNonTaxAssetApController@gp_list_asset_defaulter');

Route::get('osr/non_tax/asset/ap/ap_asset_defaulter_branch/{fy_id}/{branch_id}','Osr\ap_panel\OsrNonTaxAssetApController@ap_asset_defaulter_branch');
Route::get('osr/non_tax/asset/ap/gp_asset_defaulter/{fy_id}/{gp_id}','Osr\ap_panel\OsrNonTaxAssetApController@gp_asset_defaulter');

Route::get('osr/non_tax/asset/ap/gp_asset_defaulter_branch/{fy_id}/{gp_id}/{branch_id}','Osr\ap_panel\OsrNonTaxAssetApController@gp_asset_defaulter_branch');

//--------------------------------------(Collection)--------------------------------------------------------------------
//Route::get('osr/non_tax/asset/ap/ap_asset_collection/{fy_id}','Osr\ap_panel\OsrNonTaxAssetApController@ap_asset_collection');
Route::get('osr/non_tax/asset/ap/gp_list_asset_collection/{fy_id}','Osr\ap_panel\OsrNonTaxAssetApController@gp_list_asset_collection');

Route::get('osr/non_tax/asset/ap/ap_asset_collection_branch/{fy_id}/{branch_id}','Osr\ap_panel\OsrNonTaxAssetApController@ap_asset_collection_branch');
//Route::get('osr/non_tax/asset/ap/gp_asset_collection/{fy_id}/{gp_id}','Osr\ap_panel\OsrNonTaxAssetApController@gp_asset_collection');

Route::get('osr/non_tax/asset/ap/gp_asset_collection_branch/{fy_id}/{gp_id}/{branch_id}','Osr\ap_panel\OsrNonTaxAssetApController@gp_asset_collection_branch');

//-------------------------------------(Share)--------------------------------------------------------------------------
//Route::get('osr/non_tax/asset/ap/ap_asset_share/{fy_id}','Osr\ap_panel\OsrNonTaxAssetApController@ap_asset_share');
Route::get('osr/non_tax/asset/ap/gp_list_asset_share/{fy_id}','Osr\ap_panel\OsrNonTaxAssetApController@gp_list_asset_share');

Route::get('osr/non_tax/asset/ap/ap_asset_share_branch/{fy_id}/{branch_id}','Osr\ap_panel\OsrNonTaxAssetApController@ap_asset_share_branch');
//Route::get('osr/non_tax/asset/ap/gp_asset_share/{fy_id}/{gp_id}','Osr\ap_panel\OsrNonTaxAssetApController@gp_asset_share');

Route::get('osr/non_tax/asset/ap/gp_asset_share_branch/{fy_id}/{gp_id}/{branch_id}','Osr\ap_panel\OsrNonTaxAssetApController@gp_asset_share_branch');


//---------------------------------------other asset(collection)--------------------------------------------------------
Route::get('osr/non_tax/other_asset/ap/ap_other_asset_collection/{fy_id}','Osr\ap_panel\OsrNonTaxOtherAssetApController@ap_other_asset_collection');

Route::get('osr/non_tax/other_asset/ap/gp_list_other_asset_collection/{fy_id}','Osr\ap_panel\OsrNonTaxOtherAssetApController@gp_list_other_asset_collection');
Route::get('osr/non_tax/other_asset/ap/gp_other_asset_collection/{fy_id}/{gp_id}','Osr\ap_panel\OsrNonTaxOtherAssetApController@gp_other_asset_collection');


//---------------------------------------other asset(share)-------------------------------------------------------------
Route::get('osr/non_tax/other_asset/ap/ap_other_asset_share/{fy_id}','Osr\ap_panel\OsrNonTaxOtherAssetApController@ap_other_asset_share');

Route::get('osr/non_tax/other_asset/ap/gp_list_other_asset_share/{fy_id}','Osr\ap_panel\OsrNonTaxOtherAssetApController@gp_list_other_asset_share');
Route::get('osr/non_tax/other_asset/ap/gp_other_asset_share/{fy_id}/{gp_id}','Osr\ap_panel\OsrNonTaxOtherAssetApController@gp_other_asset_share');





//======================================================================================================================
//--------------------------------------- GP DASHBOARD -----------------------------------------------------------------
//======================================================================================================================


//------------------------------------------Settlement-----------------------------------------------------------------------------

Route::get('osr/non_tax/asset/gp/gp_asset_settlement_percent/{fy_id}','Osr\gp_panel\OsrNonTaxAssetGpController@gp_asset_settlement_percent');

Route::get('osr/non_tax/asset/gp/gp_asset_settlement_percent_branch/{fy_id}/{branch_id}','Osr\gp_panel\OsrNonTaxAssetGpController@gp_asset_settlement_percent_branch');


//------------------------------------------Defaulter------------------------------------------------------------------------------
Route::get('osr/non_tax/asset/gp/gp_asset_defaulter/{fy_id}','Osr\gp_panel\OsrNonTaxAssetGpController@gp_asset_defaulter');

Route::get('osr/non_tax/asset/gp/gp_asset_defaulter_branch/{fy_id}/{branch_id}','Osr\gp_panel\OsrNonTaxAssetGpController@gp_asset_defaulter_branch');

//------------------------------------------Collection-----------------------------------------------------------------------------
Route::get('osr/non_tax/asset/gp/gp_asset_collection/{fy_id}','Osr\gp_panel\OsrNonTaxAssetGpController@gp_asset_collection');

Route::get('osr/non_tax/asset/gp/gp_asset_collection_branch/{fy_id}/{branch_id}','Osr\gp_panel\OsrNonTaxAssetGpController@gp_asset_collection_branch');

//------------------------------------------Share----------------------------------------------------------------------------------
Route::get('osr/non_tax/asset/gp/gp_asset_share/{fy_id}','Osr\gp_panel\OsrNonTaxAssetGpController@gp_asset_share');

Route::get('osr/non_tax/asset/gp/gp_asset_share_branch/{fy_id}/{branch_id}','Osr\gp_panel\OsrNonTaxAssetGpController@gp_asset_share_branch');


//---------------------------------------other asset(collection)--------------------------------------------------------
Route::get('osr/non_tax/other_asset/gp/gp_other_asset_collection/{fy_id}','Osr\gp_panel\OsrNonTaxOtherAssetGpController@gp_other_asset_collection');


//---------------------------------------other asset(share)-------------------------------------------------------------
Route::get('osr/non_tax/other_asset/gp/gp_other_asset_share/{fy_id}','Osr\gp_panel\OsrNonTaxOtherAssetGpController@gp_other_asset_share');

Route::get('Osr/non_tax/dwAssetInformation','Osr\OsrNonTaxAssetController@dwAssetInformation')->name('Osr.non_tax.dwAssetInformation');


//------------------------------------------COMMON DASHBOARD OF ZP, AP & GP --------------------------------------------
//ASSET
Route::get('osr/non_tax/asset/common/branch_list_settlement_defaulter/{fy_id}/{page_for}/{level}/{zp_id}/{ap_id?}/{gp_id?}','Osr\zp_panel\OsrNonTaxAssetZpController@branch_list_settlement_defaulter')->name('osr.non_tax.asset.common.branch_list_settlement_defaulter');

Route::get('osr/non_tax/asset/common/single_branch_settlement_defaulter/{fy_id}/{page_for}/{level}/{branch_id}/{zp_id}/{ap_id?}/{gp_id?}','Osr\zp_panel\OsrNonTaxAssetZpController@single_branch_settlement_defaulter')->name('osr.non_tax.asset.common.single_branch_settlement_defaulter');

Route::get('osr/non_tax/asset/common/branch_list_revenue_share/{fy_id}/{page_for}/{level}/{zp_id}/{ap_id?}/{gp_id?}','Osr\zp_panel\OsrNonTaxAssetZpController@branch_list_revenue_share')->name('osr.non_tax.asset.common.branch_list_revenue_share');

Route::get('osr/non_tax/asset/common/single_branch_revenue_share/{fy_id}/{page_for}/{level}/{branch_id}/{zp_id}/{ap_id?}/{gp_id?}','Osr\zp_panel\OsrNonTaxAssetZpController@single_branch_revenue_share')->name('osr.non_tax.asset.common.single_branch_revenue_share');

//ASSET VIEW
Route::get('osr/non_tax/asset/common/asset_information/{fy}/{level}/{branch}/{asset_id}/{zp_id}/{ap?}/{gp?}','Osr\zp_panel\OsrNonTaxAssetZpController@assetInformation')->name('osr.non_tax.asset.common.asset_information');

//OTHER ASSET

Route::get('osr/non_tax/other_asset/common/cat_list_revenue_share/{fy_id}/{page_for}/{level}/{zp_id}/{ap_id?}/{gp_id?}','Osr\zp_panel\OsrNonTaxOtherAssetZpController@cat_list_revenue_share')->name('osr.non_tax.other_asset.common.cat_list_revenue_share');


//=============================NEW CONFIRMATION FOR 2020-2021=================================================================

Route::get('osr/non_tax/asset_confirmation','Osr\OsrYearWiseAssetsController@asset_confirmation')->name('osr.non_tax.asset_confirmation');

Route::post('osr/non_tax/asset_confirmation/save','Osr\OsrYearWiseAssetsController@asset_confirmation_save')->name('osr.non_tax.asset_confirmation.save');
?>