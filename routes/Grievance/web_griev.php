<?php

//grievance dashboard
Route::get('grievance/dashboard','Grievance\GrievanceController@index')->name('grievance.dashboard');

//get block by district
Route::post('grievance/getBlockByDistrict','Grievance\GrievanceController@getBlockByDistrict')->name('grievance.getBlockByDistrict');

//get gps by block
Route::post('grievance/getGPsByBlock','Grievance\GrievanceController@getGPsByBlock')->name('grievance.getGPsByBlock');

//-------------Daily Grievance------------------------------------

//grievance entry form
Route::get('grievance/NewGrievance','Grievance\GrievanceController@new_griev')->name('grievance.new_griev');

Route::post('grievance/save','Grievance\GrievanceController@griev_save')->name('grievance.save');

Route::get('grievance/grievance_show','Grievance\GrievanceController@grievance_show')->name('grievance.grievance_show');

//--------------------Media Grievance------------------ 

Route::get('grievance/Media/details','Grievance\GrievanceController@media_details')->name('grievance.media.details');

Route::post('grievance/Media/getMediaData', 'Grievance\GrievanceController@getMediaData')->name('grievance.media.getMediaData');

Route::post('grievance/Media/action', 'Grievance\GrievanceController@action')->name('grievance.media.action');


//--Report view links
Route::get('grievance/Media/Action/report/view/{m_id}','Grievance\GrievanceController@mediaActionReportView')->name('grievance.Media.Action.report.view');

Route::get('grievance/Media/Reply/view/{m_id}','Grievance\GrievanceController@mediaReplyReportView')->name('grievance.Media.Reply.view');
//--Report view links End

Route::get('grievance/Media/Action/media_status','Grievance\GrievanceController@media_status')->name('grievance.Media.Action.media_status');

Route::post('grievance/Media/Transfer/action', 'Grievance\GrievanceController@transfer_action')->name('grievance.media.transfer.action');

//--------------Media Grievance Ends------------------ 


//----------------------INDIVIDUAL GRIEVANCE-------------------------------------------------

Route::get('grievance/Individual/level/details','Grievance\GrievanceController@individual_level_wise_details')->name('grievance.individual.level.details');

Route::get('Grievance/Individual/griev_entry','Grievance\GrievanceController@individual_griev_entry')->name('grievance.individual_griev_entry');

Route::post('grievance/Individual/griev_save','Grievance\GrievanceController@individual_griev_save')->name('grievance.individual_griev_save');

Route::get('grievance/Individual/griev_confirm_page','Grievance\GrievanceController@griev_confirm_page')->name('grievance.individual_griev_confirm_page');

Route::get('grievance/Individual/individual_griev_list', 'Grievance\GrievanceController@individual_griev_list')->name('grievance.Individual.individual_griev_list');

Route::get('grievance/Individual/grievance_details/{id}','Grievance\GrievanceController@details')->name('grievance.details');

Route::get('grievance/Individual/document/view/{id}','Grievance\GrievanceController@individualDocumentView')->name('grievance.Individual.Document.view');

Route::post('grievance/Individual/getGrievData', 'Grievance\GrievanceController@getGrievData')->name('grievance.Individual.getGrievData');

Route::post('grievance/Individual/action', 'Grievance\GrievanceController@individual_griev_action')->name('grievance.Individual.action');

Route::post('grievance/Individual/getGrievData', 'Grievance\GrievanceController@getGrievData')->name('grievance.Individual.getGrievData');

Route::post('grievance/Individual/Transfer/action', 'Grievance\GrievanceController@griev_transfer_action')->name('grievance.Individual.transfer.action');

Route::get('grievance/Individual/Entry/Message','Grievance\GrievanceController@entry_msg')->name('grievance.Individual.entry_msg');

Route::get('grievance/Individual/Action/Message','Grievance\GrievanceController@action_msg')->name('grievance.Individual.action_msg');

//-------------PDF DOWNLOAD OF GRIEVANCE CONFIRMATION-----------------------------------------

Route::get('grievance/download/Acknowledgement/{id}','Grievance\GrievanceDownloadController@acknowledgement_download');

?>