<?php

Route::get('courtCases/dashboard', 'CourtCases\CourtCasesController@dashboard')->name('courtCases.dashboard');
// Route::get('courtCases/addCourtCase', 'CourtCases\CourtCasesController@add_court_case')->name('courtCases.addCourtCase');
// Route::post('courtCases/addCourtCaseDetails', 'CourtCases\CourtCasesController@add_court_case_details')->name('courtCases.addCourtCaseDetails');

Route::get('courtCases/listCourtCase', 'CourtCases\CourtCasesController@list_court_case')->name('courtCases.listCourtCase');
Route::post('courtCases/searchCourtCase', 'CourtCases\CourtCasesController@search_court_case')->name('courtCases.searchCourtCase');
Route::post('courtCases/searchCourtCasebyNo', 'CourtCases\CourtCasesController@search_court_case_byno')->name('courtCases.searchCourtCasebyNo');
// Route::get('courtCases/manageCourtCase/{id}', 'CourtCases\CourtCasesController@manage_court_case')->name('courtCases.manageCourtCase');
Route::get('courtCases/viewCourtCase/{id}', 'CourtCases\CourtCasesController@view_court_case')->name('courtCases.viewCourtCase');
Route::post('courtCases/updateCourtCasePrimary', 'CourtCases\CourtCasesController@update_court_case_primary')->name('courtCases.updateCourtCasePrimary');
Route::get('courtCases/viewBlocks', 'CourtCases\CourtCasesController@view_blocks')->name('courtCases.viewBlocks');

Route::get('courtCases/loadDistrictBlocks', 'CourtCases\CourtCasesController@load_district_blocks')->name('courtCases.loadDistrictBlocks');
//Route::post('courtCases/addCourtCaseParawise', 'CourtCases\CourtCasesController@add_court_case_parawise')->name('courtCases.addCourtCaseParawise');
Route::post('courtCases/addParawiseComments', 'CourtCases\CourtCasesController@add_parawise_comments')->name('courtCases.addParawiseComments');
Route::get('courtCases/refreshParawiseComments', 'CourtCases\CourtCasesController@refresh_parawise_comments')->name('courtCases.refreshParawiseComments');
Route::get('courtCases/loadParawiseComments', 'CourtCases\CourtCasesController@load_parawise_comments')->name('courtCases.loadParawiseComments');
Route::post('courtCases/updateParawiseComments', 'CourtCases\CourtCasesController@update_parawise_comments')->name('courtCases.updateParawiseComments');
Route::get('courtCases/viewCourtCaseParawiseComments/{id}', 'CourtCases\CourtCasesController@view_court_case_parawise_comments')->name('courtCases.viewCourtCaseParawiseComments');

//Route::post('courtCases/addCourtCaseInstruction', 'CourtCases\CourtCasesController@add_court_case_instruction')->name('courtCases.addCourtCaseInstruction');
// Route::post('courtCases/addInstruction', 'CourtCases\CourtCasesController@add_instruction')->name('courtCases.addInstruction');
// Route::get('courtCases/refreshInstruction', 'CourtCases\CourtCasesController@refresh_instruction')->name('courtCases.refreshInstruction');
// Route::get('courtCases/loadInstruction', 'CourtCases\CourtCasesController@load_instruction')->name('courtCases.loadInstruction');
// Route::post('courtCases/updateInstruction', 'CourtCases\CourtCasesController@update_instruction')->name('courtCases.updateInstruction');
Route::get('courtCases/viewCourtCaseInstruction/{id}', 'CourtCases\CourtCasesController@view_court_case_instruction')->name('courtCases.viewCourtCaseInstruction');

// Route::post('courtCases/addInterimOrder', 'CourtCases\CourtCasesController@add_interim_order')->name('courtCases.addInterimOrder');
// Route::get('courtCases/refreshInterimOrder', 'CourtCases\CourtCasesController@refresh_interim_order')->name('courtCases.refreshInterimOrder');
// Route::get('courtCases/loadInterimOrder', 'CourtCases\CourtCasesController@load_interim_order')->name('courtCases.loadInterimOrder');
// Route::post('courtCases/updateInterimOrder', 'CourtCases\CourtCasesController@update_interim_order')->name('courtCases.updateInterimOrder');

// Route::post('courtCases/addCourtCaseAffidavit', 'CourtCases\CourtCasesController@add_court_case_affidavit')->name('courtCases.addCourtCaseAffidavit');
Route::get('courtCases/viewCourtCaseAffidavit/{id}', 'CourtCases\CourtCasesController@view_court_case_affidavit')->name('courtCases.viewCourtCaseAffidavit');

// Route::post('courtCases/addCourtCaseAdditionalAffidavit', 'CourtCases\CourtCasesController@add_court_case_additional_affidavit')->name('courtCases.addCourtCaseAdditionalAffidavit');
// Route::post('courtCases/addCourtCaseFinalOrder', 'CourtCases\CourtCasesController@add_court_case_final_order')->name('courtCases.addCourtCaseFinalOrder');

// Route::post('courtCases/addSpeakingOrder', 'CourtCases\CourtCasesController@add_speaking_order')->name('courtCases.addSpeakingOrder');
// Route::get('courtCases/refreshSpeakingOrder', 'CourtCases\CourtCasesController@refresh_speaking_order')->name('courtCases.refreshSpeakingOrder');
// Route::get('courtCases/loadSpeakingOrder', 'CourtCases\CourtCasesController@load_speaking_order')->name('courtCases.loadSpeakingOrder');
// Route::post('courtCases/updateSpeakingOrder', 'CourtCases\CourtCasesController@update_speaking_order')->name('courtCases.updateSpeakingOrder');
Route::get('courtCases/viewCourtCaseSpeakingOrder/{id}', 'CourtCases\CourtCasesController@view_court_case_speaking_order')->name('courtCases.viewCourtCaseSpeakingOrder');

//ADD RECIPIENTS
Route::get('courtCases/addRecipients', 'CourtCases\CourtCasesRecipientsController@add_recipients')->name('courtCases.addRecipients');
Route::post('courtCases/addRecipients', 'CourtCases\CourtCasesRecipientsController@recipient_save')->name('courtCases.saveRecipients');//ADD RECIPIENTS
Route::get('courtCases/viewRecipients', 'CourtCases\CourtCasesRecipientsController@view_recipients')->name('courtCases.viewRecipients');

//VIEW RECIPIENTS
Route::post('courtCases/getRecipientsByid', 'CourtCases\CourtCasesRecipientsController@getRecipientsByid')->name('courtCases.getRecipientsByid');
Route::post('courtCases/editRecipient','CourtCases\CourtCasesRecipientsController@editRecipient')->name('courtCases.editRecipient');
Route::get('courtCases/loadDistrictBlocks1', 'CourtCases\CourtCasesRecipientsController@load_district_blocks')->name('courtCases.loadDistrictBlocks1');

//RECIPIENT STATUS
Route::get('courtCases/statusRecipient','CourtCases\CourtCasesRecipientsController@statusRecipient')->name('courtCases.statusRecipient');

//COURT CASE REPORTS
Route::get('courtCases/listStatusReport', 'CourtCases\CourtCasesReportsController@list_status_report')->name('courtCases.listStatusReport');
Route::post('courtCases/statussearchCourtCase', 'CourtCases\CourtCasesReportsController@search_court_case')->name('courtCases.statussearchCourtCase');
Route::post('courtCases/statussearchCourtCasebyNo', 'CourtCases\CourtCasesReportsController@search_court_case_byno')->name('courtCases.statussearchCourtCasebyNo');
Route::get('courtCases/statusexcelCourtCase', 'CourtCases\CourtCasesReportsController@status_excel_court_case')->name('courtCases.statusexcelCourtCase');
Route::get('courtCases/statusexcelCourtCasebyNo', 'CourtCases\CourtCasesReportsController@status_excel_court_case_byno')->name('courtCases.statusexcelCourtCasebyNo');