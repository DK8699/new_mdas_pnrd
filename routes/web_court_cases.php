<?php

Route::get('admin/courtCases/dashboard', 'Admin\CourtCases\CourtCasesController@dashboard')->name('admin.courtCases.dashboard');
Route::get('admin/courtCases/addCourtCase', 'Admin\CourtCases\CourtCasesController@add_court_case')->name('admin.courtCases.addCourtCase');
Route::post('admin/courtCases/addCourtCaseDetails', 'Admin\CourtCases\CourtCasesController@add_court_case_details')->name('admin.courtCases.addCourtCaseDetails');

Route::get('admin/courtCases/listCourtCase', 'Admin\CourtCases\CourtCasesController@list_court_case')->name('admin.courtCases.listCourtCase');
Route::post('admin/courtCases/searchCourtCase', 'Admin\CourtCases\CourtCasesController@search_court_case')->name('admin.courtCases.searchCourtCase');
Route::post('admin/courtCases/searchCourtCasebyNo', 'Admin\CourtCases\CourtCasesController@search_court_case_byno')->name('admin.courtCases.searchCourtCasebyNo');
Route::get('admin/courtCases/manageCourtCase/{id}', 'Admin\CourtCases\CourtCasesController@manage_court_case')->name('admin.courtCases.manageCourtCase');
Route::get('admin/courtCases/viewCourtCase/{id}', 'Admin\CourtCases\CourtCasesController@view_court_case')->name('admin.courtCases.viewCourtCase');
Route::post('admin/courtCases/updateCourtCasePrimary', 'Admin\CourtCases\CourtCasesController@update_court_case_primary')->name('admin.courtCases.updateCourtCasePrimary');
Route::get('admin/courtCases/viewBlocks', 'Admin\CourtCases\CourtCasesController@view_blocks')->name('admin.courtCases.viewBlocks');

Route::get('admin/courtCases/loadDistrictBlocks', 'Admin\CourtCases\CourtCasesController@load_district_blocks')->name('admin.courtCases.loadDistrictBlocks');
//Route::post('admin/courtCases/addCourtCaseParawise', 'Admin\CourtCases\CourtCasesController@add_court_case_parawise')->name('admin.courtCases.addCourtCaseParawise');
Route::post('admin/courtCases/addParawiseComments', 'Admin\CourtCases\CourtCasesController@add_parawise_comments')->name('admin.courtCases.addParawiseComments');
Route::get('admin/courtCases/refreshParawiseComments', 'Admin\CourtCases\CourtCasesController@refresh_parawise_comments')->name('admin.courtCases.refreshParawiseComments');
Route::get('admin/courtCases/loadParawiseComments', 'Admin\CourtCases\CourtCasesController@load_parawise_comments')->name('admin.courtCases.loadParawiseComments');
Route::post('admin/courtCases/updateParawiseComments', 'Admin\CourtCases\CourtCasesController@update_parawise_comments')->name('admin.courtCases.updateParawiseComments');
Route::get('admin/courtCases/viewCourtCaseParawiseComments/{id}', 'Admin\CourtCases\CourtCasesController@view_court_case_parawise_comments')->name('admin.courtCases.viewCourtCaseParawiseComments');

//Route::post('admin/courtCases/addCourtCaseInstruction', 'Admin\CourtCases\CourtCasesController@add_court_case_instruction')->name('admin.courtCases.addCourtCaseInstruction');
Route::post('admin/courtCases/addInstruction', 'Admin\CourtCases\CourtCasesController@add_instruction')->name('admin.courtCases.addInstruction');
Route::get('admin/courtCases/refreshInstruction', 'Admin\CourtCases\CourtCasesController@refresh_instruction')->name('admin.courtCases.refreshInstruction');
Route::get('admin/courtCases/loadInstruction', 'Admin\CourtCases\CourtCasesController@load_instruction')->name('admin.courtCases.loadInstruction');
Route::post('admin/courtCases/updateInstruction', 'Admin\CourtCases\CourtCasesController@update_instruction')->name('admin.courtCases.updateInstruction');
Route::get('admin/courtCases/viewCourtCaseInstruction/{id}', 'Admin\CourtCases\CourtCasesController@view_court_case_instruction')->name('admin.courtCases.viewCourtCaseInstruction');

Route::post('admin/courtCases/addInterimOrder', 'Admin\CourtCases\CourtCasesController@add_interim_order')->name('admin.courtCases.addInterimOrder');
Route::get('admin/courtCases/refreshInterimOrder', 'Admin\CourtCases\CourtCasesController@refresh_interim_order')->name('admin.courtCases.refreshInterimOrder');
Route::get('admin/courtCases/loadInterimOrder', 'Admin\CourtCases\CourtCasesController@load_interim_order')->name('admin.courtCases.loadInterimOrder');
Route::post('admin/courtCases/updateInterimOrder', 'Admin\CourtCases\CourtCasesController@update_interim_order')->name('admin.courtCases.updateInterimOrder');

Route::post('admin/courtCases/addCourtCaseAffidavit', 'Admin\CourtCases\CourtCasesController@add_court_case_affidavit')->name('admin.courtCases.addCourtCaseAffidavit');
Route::get('admin/courtCases/viewCourtCaseAffidavit/{id}', 'Admin\CourtCases\CourtCasesController@view_court_case_affidavit')->name('admin.courtCases.viewCourtCaseAffidavit');

Route::post('admin/courtCases/addCourtCaseAdditionalAffidavit', 'Admin\CourtCases\CourtCasesController@add_court_case_additional_affidavit')->name('admin.courtCases.addCourtCaseAdditionalAffidavit');
Route::post('admin/courtCases/addCourtCaseFinalOrder', 'Admin\CourtCases\CourtCasesController@add_court_case_final_order')->name('admin.courtCases.addCourtCaseFinalOrder');

Route::post('admin/courtCases/addSpeakingOrder', 'Admin\CourtCases\CourtCasesController@add_speaking_order')->name('admin.courtCases.addSpeakingOrder');
Route::get('admin/courtCases/refreshSpeakingOrder', 'Admin\CourtCases\CourtCasesController@refresh_speaking_order')->name('admin.courtCases.refreshSpeakingOrder');
Route::get('admin/courtCases/loadSpeakingOrder', 'Admin\CourtCases\CourtCasesController@load_speaking_order')->name('admin.courtCases.loadSpeakingOrder');
Route::post('admin/courtCases/updateSpeakingOrder', 'Admin\CourtCases\CourtCasesController@update_speaking_order')->name('admin.courtCases.updateSpeakingOrder');
Route::get('admin/courtCases/viewCourtCaseSpeakingOrder/{id}', 'Admin\CourtCases\CourtCasesController@view_court_case_speaking_order')->name('admin.courtCases.viewCourtCaseSpeakingOrder');

//ADD RECIPIENTS
Route::get('admin/courtCases/addRecipients', 'Admin\CourtCases\CourtCasesRecipientsController@add_recipients')->name('admin.courtCases.addRecipients');
Route::post('admin/courtCases/addRecipients', 'Admin\CourtCases\CourtCasesRecipientsController@recipient_save')->name('admin.courtCases.saveRecipients');//ADD RECIPIENTS
Route::get('admin/courtCases/viewRecipients', 'Admin\CourtCases\CourtCasesRecipientsController@view_recipients')->name('admin.courtCases.viewRecipients');

//VIEW RECIPIENTS
Route::post('admin/courtCases/getRecipientsByid', 'Admin\CourtCases\CourtCasesRecipientsController@getRecipientsByid')->name('admin.courtCases.getRecipientsByid');
Route::post('admin/courtCases/editRecipient','Admin\CourtCases\CourtCasesRecipientsController@editRecipient')->name('admin.courtCases.editRecipient');
Route::get('admin/courtCases/loadDistrictBlocks1', 'Admin\CourtCases\CourtCasesRecipientsController@load_district_blocks')->name('admin.courtCases.loadDistrictBlocks1');

//RECIPIENT STATUS
Route::get('admin/courtCases/statusRecipient','Admin\CourtCases\CourtCasesRecipientsController@statusRecipient')->name('admin.courtCases.statusRecipient');


//COURT CASE REPORTS
Route::get('admin/courtCases/listStatusReport', 'Admin\CourtCases\CourtCasesReportsController@list_status_report')->name('admin.courtCases.listStatusReport');
Route::post('admin/courtCases/statussearchCourtCase', 'Admin\CourtCases\CourtCasesReportsController@search_court_case')->name('admin.courtCases.statussearchCourtCase');
Route::post('admin/courtCases/statussearchCourtCasebyNo', 'Admin\CourtCases\CourtCasesReportsController@search_court_case_byno')->name('admin.courtCases.statussearchCourtCasebyNo');
Route::get('admin/courtCases/statusexcelCourtCase', 'Admin\CourtCases\CourtCasesReportsController@status_excel_court_case')->name('admin.courtCases.statusexcelCourtCase');
Route::get('admin/courtCases/statusexcelCourtCasebyNo', 'Admin\CourtCases\CourtCasesReportsController@status_excel_court_case_byno')->name('admin.courtCases.statusexcelCourtCasebyNo');
// Route::get('admin/courtCases/excelStatusReport', 'Admin\CourtCases\CourtCasesReportsController@excel_status_report')->name('admin.courtCases.excelStatusReport');
// Route::post('admin/courtCases/addRecipients', 'Admin\CourtCases\CourtCasesRecipientsController@recipient_save')->name('admin.courtCases.saveRecipients');//ADD RECIPIENTS
// Route::get('admin/courtCases/viewRecipients', 'Admin\CourtCases\CourtCasesRecipientsController@view_recipients')->name('admin.courtCases.viewRecipients');

//______________________MESSAGE CASES___________________________________________________________________________________
Route::get('msg/courtCase', 'Admin\CourtCases\CourtCasesMessageController@sendCourtCaseMessages')->name('msg.courtCase');