<?php


Route::get('/pris/members', 'Pris\PrisMemberController@index')->name('pris.members');

Route::post('/pris/members/save', 'Pris\PrisMemberController@save')->name('pris.members.save');
Route::post('/pris/members/bank_save', 'Pris\PrisMemberController@store')->name('pris.members.bank_save');
Route::post('/pris/members/view', 'Pris\PrisMemberController@view')->name('pris.members.view');
Route::post('/pris/members/view/branch', 'Pris\PrisMemberController@sendBankBranch')->name('pris.members.sendBankBranch'); //get bank name/ifsc Code
Route::get('/pris/members/filledBankDetail', 'Pris\PrisMemberController@filledBankDetail')->name('pris.members.filledBankDetail');    //mishra wants it
Route::get('/pris/members/bankReport', 'Pris\PrisMemberController@bankReport')->name('pris.members.bankReport');    //piggy bank report DA/GA..
Route::get('/pris/members/bankSubDistrict/{id}', 'Pris\PrisMemberController@bankSubDistrict')->name('pris.members.bankSubDistrict'); //under ka hai
Route::get('/pris/members/bankSubDistrictGP/{id}', 'Pris\PrisMemberController@bankSubDistrictGP')->name('pris.members.bankSubDistrictGP'); //under ka GP wala hai

Route::post('/common/category/getGPsByAnchalikId', 'Pris\PrisMemberController@getGPsByAnchalikId')->name('common.category.getGPsByAnchalikId');


//----------- DISTRICT REPORT --------------------

Route::get('/pris/district/reportDist', 'Pris\PrisMemberReportController@reportDist')->name('pris.district.reportDist');
Route::post('/pris/district/reportDist', 'Pris\PrisMemberReportController@reportDist')->name('pris.district.reportDist');
Route::post('/pris/district/select_ajax','Pris\PrisMemberReportController@selectAjax')->name('select_ajax');
Route::get('/pris/district/reportProgress', 'Pris\PrisMemberReportController@reportProgress')->name('pris.district.reportProgress');

//----------- DELETION OF PRI MEMBER --------------------

Route::post('/pris/district/destroyPRI', 'Pris\PrisMemberReportController@destroyPRI')->name('pris.district.destroyPRI');
// Quick PRI Report Download
Route::get('/pris/district/quickReportDownload', 'Pris\quickReportDownloadController@quickReportDownloadParty')->name('pris.district.quickReportDownload');