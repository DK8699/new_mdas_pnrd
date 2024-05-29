<?php



//Route::get('/dashboard', 'six_finance\BasicQuestionController@dashboard')->name('dashboard');

Route::get('/survey/six_finance_form/{applicable_id}', 'six_finance\BasicQuestionController@index');
Route::get('/survey/six_finance_form_dashboard', 'six_finance\BasicQuestionController@six_finance_dashboard')->name('survey.six_finance_form_dashboard');

Route::get('/survey/six_finance_form_dashboard/save', 'six_finance\BasicQuestionController@finalSubmit')->name('survey.six_finance_form_dashboard.save');

// Basic Info

Route::get('/survey/six_finance_form_basic', 'six_finance\BasicQuestionController@basic')->name('survey.six_finance_form_basic');

Route::post('/survey/six_finance_form_basic/save', 'six_finance\BasicQuestionController@basic_save')->name('survey.six_finance_form_basic.save');

Route::post('/survey/six_finance_form/getGPsByAnchalikId', 'six_finance\BasicQuestionController@getGPsByAnchalikId')->name('survey.getGPsByAnchalikId');
Route::post('/survey/six_finance_form/getGPsByBlockId', 'six_finance\BasicQuestionController@getGPsByBlockId')->name('survey.getGPsByBlockId');

Route::post('/survey/saveAndCheckSixFinance', 'six_finance\BasicQuestionController@saveAndCheckSixFinance')->name('survey.saveAndCheckSixFinance');


//Other Info
Route::get('/survey/six_finance_form_other', 'six_finance\OtherInfoController@index')->name('survey.six_finance_form_other');
Route::post('/survey/six_finance_form_other/save', 'six_finance\OtherInfoController@save')->name('survey.six_finance_form_other.save');

//Staff Info
Route::get('/survey/six_finance_form_staff', 'six_finance\StaffInfoController@index')->name('survey.six_finance_form_staff');
Route::post('/survey/six_finance_form_staff/add_design', 'six_finance\StaffInfoController@add_design')->name('survey.six_finance_form_staff.add_design');
Route::post('/survey/six_finance_form_staff/save', 'six_finance\StaffInfoController@save')->name('survey.six_finance_form_staff.save');

//Revenue Info
Route::get('/survey/six_finance_form_revenue', 'six_finance\RevenueInfoController@index')->name('survey.six_finance_form_revenue');
Route::post('/survey/six_finance_form_revenue/add_tax_own_revenue', 'six_finance\RevenueInfoController@add_tax_own_revenue')->name('survey.six_finance_form_revenue.add_tax_own_revenue');
Route::post('/survey/six_finance_form_revenue/add_share', 'six_finance\RevenueInfoController@add_share')->name('survey.six_finance_form_revenue.add_share');
Route::post('/survey/six_finance_form_revenue/addTransferResource', 'six_finance\RevenueInfoController@addTransferResource')->name('survey.six_finance_form_revenue.addTransferResource');
Route::post('/survey/six_finance_form_revenue/save', 'six_finance\RevenueInfoController@save')->name('survey.six_finance_form_revenue.save');
/*************************************************** Shyam Link *********************************************************/
/************************************************** New Scheme Proposals **********************************************/

Route::get('/new_scheme','NewScheme\NewSchemeController@index')->name('new_scheme');
Route::post('/save_scheme_cost','NewScheme\NewSchemeController@save_proposal_entities');
Route::post('/add_new_others','NewScheme\NewSchemeController@add_other_options');
/************************************************** END ***************************************************************/

/*************************************************** Balance Links*********************************************************/
Route::get('/balance','Balance\FinancialBalanceController@index')->name('balance');
Route::post('/save_balance','Balance\FinancialBalanceController@save_financial_balance');
/*************************************************** END ********************************************************************/

/***************************************** Expenditure Links ***************************************************/
Route::get('/match_anchalik_parishads','Balance\FinancialBalanceController@match_anchalik_parishads');
Route::get('/expenditure','Expenditure\ExpenditureController@index');
Route::post('/save_expenditure','Expenditure\ExpenditureController@save_expenditure');
Route::post('/save_other_specification','Expenditure\ExpenditureController@save_other_specification');
/******************************************* END *************************************************************/


//DELETE REQUEST
Route::post('/survey/six_finance/delete_request', 'six_finance\DeleteInfoController@index')->name('survey.six_finance.delete_request');


//REPORT

Route::get('/survey/six_finance/report', 'six_finance\ReportController@index')->name('survey.six_finance.report');

Route::get('/survey/six_finance/report_district_wise', 'six_finance\ReportController@report_district_wise')->name('survey.six_finance.report_district_wise');

Route::post('survey/six_finance/download_distCombined', 'six_finance\DownloadPDFController@download_distCombined')->name('survey.six_finance.download_distCombined');

Route::get('/survey/six_finance/report/view_submitted_list', 'six_finance\ReportController@view_submitted_list')->name('survey.six_finance.report.view_submitted_list');
Route::get('/survey/six_finance/report/download_zp_ap_gp/{six_finance_final_id}/{emp_code}', 'six_finance\DownloadPDFController@download_zp_ap_gp');

Route::get('/survey/six_finance/report/getAP', 'six_finance\ReportController@getAP')->name('survey.six_finance.report.getAP');

?>