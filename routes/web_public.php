<?php


Route::get('report/osr/osr_asset_settlement', 'Website\PublicController@osr_asset_settlement')->name('osr_asset_settlement');


Route::get('report/osr/osr_defaulter', 'Website\PublicController@osr_defaulter')->name('osr_defaulter');

Route::get('report/osr/osr_year_wise', 'Website\PublicController@osr_year_wise')->name('osr_year_wise');

Route::get('report/osr/osr_asset_list/{zp_id}', 'Website\PublicController@osr_asset_list')->name('osr_asset_list');

Route::get('report/osr/osr_yr_wise_asset_show','Website\PublicController@osr_yr_wise_asset_show')->name('osr_yr_wise_asset_show');

Route::get('osr/non_tax/asset/shortlist/report/view/{fy_id}/{z_id}','Website\PublicController@shortlistReportView')->name('osr.non_tax.asset.shortlist.report.view');


//NEED BASED TRAINING

Route::get('training/index','Website\TrainingController@index')->name('training.index');

//get block by district
Route::post('training/getBlockByDistrict','Website\TrainingController@getBlockByDistrict')->name('training.getBlockByDistrict');

//get gps by block
Route::post('training/getGPsByBlock','Website\TrainingController@getGPsByBlock')->name('training.getGPsByBlock');

Route::get('training/index/view_more/{type?}','Website\TrainingController@view_more')->name('training.index.view_more');

Route::get('training/participants/entry/{t_id}/{l_id}','Website\TrainingController@participants_entry')->name('training.participants.entry');

Route::post('training/participants/save','Website\TrainingController@participants_entry_save')->name('training.participants.save');

Route::get('training/participants/confirmation','Website\TrainingController@participant_confirmation')->name('training.participants.confirmation');

Route::get('recruitment','Website\PublicController@recruitment')->name('recruitment');

?>