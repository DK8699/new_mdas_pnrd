<?php


Route::get('/admin/dashboard', 'Admin\AdminController@index')->name('admin.dashboard');

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//===============================######  SIX FINANCE  #####=============================================

Route::get('/admin/survey/six_finance/', 'Admin\six_finance\AdminSixFinanceController@index')->name('admin.survey.six_finance');

Route::get('/admin/survey/six_finance/delete_request_list', 'Admin\six_finance\AdminSixFinanceDeleteController@index')->name('admin.survey.six_finance.delete_request_list');

Route::post('/admin/survey/six_finance/delete_request_list/delete', 'Admin\six_finance\AdminSixFinanceDeleteController@deleteRequest')->name('admin.survey.six_finance.delete_request_list.delete');

Route::post('/admin/survey/six_finance/delete_request_list/cancel', 'Admin\six_finance\AdminSixFinanceDeleteController@cancelRequest')->name('admin.survey.six_finance.delete_request_list.cancel');
Route::get('/admin/survey/six_finance/request_categories', 'Admin\six_finance\AdminAcceptRejectController@index')->name('admin.survey.six_finance.accept_reject_request_list.accept_reject');
Route::get('/admin/survey/six_finance/employee_request/{category}', 'Admin\six_finance\AdminAcceptRejectController@expenditureIndex')->name('admin.survey.six_finance.accept_reject_request_list.employee_request');
Route::post('/admin/survey/six_finance/accept_request_list/accept', 'Admin\six_finance\AdminAcceptRejectController@acceptRequest')->name('admin.survey.six_finance.accept_request_list.accept');
Route::post('/admin/survey/six_finance/reject_request_list/reject', 'Admin\six_finance\AdminAcceptRejectController@rejectRequest')->name('admin.survey.six_finance.reject_request_list.reject');


Route::get('/admin/survey/six_finance/combined_list', 'Admin\six_finance\AdminSixFinanceCombinedListController@combined_list')->name('admin.survey.six_finance.combined_list');
Route::get('/admin/survey/six_finance/downloadCombined/{req_for}', 'six_finance\DownloadPDFController@downloadCombined');

Route::get('/admin/survey/six_finance/track_zp_ap_gp', 'Admin\six_finance\AdminSixFinanceCombinedListController@track_zp_ap_gp')->name('admin.survey.six_finance.track_zp_ap_gp');
Route::get('/admin/survey/six_finance/track_zp', 'Admin\six_finance\AdminSixFinanceCombinedListController@track_zp')->name('admin.survey.six_finance.track_zp');
Route::get('/admin/survey/six_finance/track_ap', 'Admin\six_finance\AdminSixFinanceCombinedListController@track_ap')->name('admin.survey.six_finance.track_ap');
Route::get('/admin/survey/six_finance/track_gp', 'Admin\six_finance\AdminSixFinanceCombinedListController@track_gp')->name('admin.survey.six_finance.track_gp');
//---------------------------------------Download Combined--------------------------------------------------------------------
Route::get('/admin/survey/six_finance/download_combined', 'Admin\six_finance\AdminSixfinanceDownloadController@download_combined')->name('admin.survey.six_finance.download_combined');



//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//===============================######  PRIs  #####=============================================

Route::get('/admin/pris/dashboard/', 'Admin\six_finance\AdminPrisDashboardController@index')->name('admin.pris.dashboard');
Route::get('/admin/Pris/priMenu/', 'Admin\Pris\AdminPrisMemberReportController@priMenu')->name('admin.Pris.priMenu');
Route::get('/admin/Pris/reportAdmin/', 'Admin\Pris\AdminPrisMemberReportController@reportAdmin')->name('admin.Pris.reportAdmin');

Route::get('/admin/Pris/priMenu/bankProgressReport', 'Admin\Pris\AdminBankReportController@bankProgressReport')->name('admin.Pris.bankProgressReport'); //bank report admin
Route::get('/admin/Pris/priMenu/bankSubDistrictAdmin/{id}', 'Admin\Pris\AdminBankReportController@bankSubDistrictAdmin'); //under ka hai admin
Route::get('/pris/members/bankSubDistrictGPAdmin/{id}', 'Admin\Pris\AdminBankReportController@bankSubDistrictGPAdmin')->name('admin.Pris.bankSubDistrictGPAdmin'); //under ka GP wala hai

Route::get('/admin/Pris/priDistrictWiseProgressReportZP/', 'Admin\Pris\AdminPrisMemberReportController@priDistrictWiseProgressReportZP')->name('admin.Pris.priDistrictWiseProgressReportZP');
Route::get('/admin/Pris/priDistrictWiseProgressReportZP/{zp_id}', 'Admin\Pris\AdminPrisMemberReportController@reportProgress');

Route::get('/admin/Pris/priDistrictWiseGenderZP/', 'Admin\Pris\AdminPrisMemberReportController@priDistrictWiseGenderZP')->name('admin.Pris.priDistrictWiseGenderZP');
Route::get('/admin/Pris/priDistrictWiseQualiReport/', 'Admin\Pris\AdminPrisMemberReportController@priDistrictWiseQualiReport')->name('admin.Pris.priDistrictWiseQualiReport');
Route::post('/admin/Pris/priDistrictWiseQualiReport/', 'Admin\Pris\AdminPrisMemberReportController@reportHQualByHQualList')->name('admin.Pris.reportHQualByHQualList');
Route::get('/admin/Pris/priDistrictWisePartyZP/', 'Admin\Pris\AdminPrisMemberReportController@priDistrictWisePartyZP')->name('admin.Pris.priDistrictWisePartyZP');
Route::post('/admin/Pris/reportAdmin/', 'Admin\Pris\AdminPrisMemberReportController@reportAdmin')->name('admin.Pris.reportAdmin');
Route::post('/admin/Pris/selectZilaAjax', 'Admin\Pris\AdminPrisMemberReportController@selectZilaAjax')->name('selectZilaAjax');
Route::post('/admin/Pris/selectAnchalAjax', 'Admin\Pris\AdminPrisMemberReportController@selectAnchalAjax')->name('selectAnchalAjax');
Route::post('/admin/Pris/selectGramAjax', 'Admin\Pris\AdminPrisMemberReportController@selectGramAjax')->name('selectGramAjax');
Route::post('/admin/Pris/viewPri/', 'Common\PriViewController@viewPri')->name('admin.Pris.viewPri');


//-------------------------------------------##### Quick Report Download #####------------------------------------------
Route::get('/admin/Pris/priDownloadMenu/', 'Admin\Pris\AdminQuickReportDownloadController@priDownloadMenu')->name('admin.Pris.priDownloadMenu');

Route::get('/admin/Pris/quickReportDownloadZP/', 'Admin\Pris\AdminQuickReportDownloadController@quickReportDownloadZP')->name('admin.Pris.quickReportDownloadZP');
Route::get('/admin/Pris/quickReportDownloadZPP/', 'Admin\Pris\AdminQuickReportDownloadController@quickReportDownloadZPP')->name('admin.Pris.quickReportDownloadZPP');
Route::get('/admin/Pris/servicePriZPNicDataList/', 'Admin\Pris\PriAdminServiceController@servicePriZPNicDataList')->name('admin.Pris.servicePriZPNicDataList');

Route::get('/admin/Pris/quickReportDownloadAP/', 'Admin\Pris\AdminQuickReportDownloadController@quickReportDownloadAP')->name('admin.Pris.quickReportDownloadAP');
Route::get('/admin/Pris/quickReportDownloadAPP/', 'Admin\Pris\AdminQuickReportDownloadController@quickReportDownloadAPP')->name('admin.Pris.quickReportDownloadAPP');
Route::get('/admin/Pris/servicePriAPNicDataList/', 'Admin\Pris\PriAdminServiceController@servicePriAPNicDataList')->name('admin.Pris.servicePriAPNicDataList');

Route::get('/admin/Pris/quickReportDownloadGP/', 'Admin\Pris\AdminQuickReportDownloadController@quickReportDownloadGP')->name('admin.Pris.quickReportDownloadGP');
Route::get('/admin/Pris/quickReportDownloadGPP/', 'Admin\Pris\AdminQuickReportDownloadController@quickReportDownloadGPP')->name('admin.Pris.quickReportDownloadGPP');
Route::get('/admin/Pris/servicePriGPNicDataList/', 'Admin\Pris\PriAdminServiceController@servicePriGPNicDataList')->name('admin.Pris.servicePriGPNicDataList');
//-------------------------------------------##### Female PRI Report Download #####------------------------------------------
Route::get('/admin/Pris/priFemaleMenu/', 'Admin\Pris\AdminGenderwisePriController@priFemaleMenu')->name('admin.Pris.priFemaleMenu');
Route::get('/admin/Pris/quickReportFemaleZP/', 'Admin\Pris\AdminGenderwisePriController@quickReportFemaleZP')->name('admin.Pris.quickReportFemaleZP');
Route::get('/admin/Pris/quickReportFemaleAP/', 'Admin\Pris\AdminGenderwisePriController@quickReportFemaleAP')->name('admin.Pris.quickReportFemaleAP');
Route::get('/admin/Pris/quickReportFemaleGP/', 'Admin\Pris\AdminGenderwisePriController@quickReportFemaleGP')->name('admin.Pris.quickReportFemaleGP');
//=======================================================**OSR**========================================================
Route::get('/admin/Asset/Osr/osrAssetsReport', 'Admin\Osr\AdminOsrController@osrAssetsReport')->name('admin.Asset.Osr.osrAssetsReport');
Route::post('/admin/Asset/Osr/osrAssetsReport', 'Admin\Osr\AdminOsrController@osrAssetsReport')->name('admin.Asset.Osr.osrAssetsReport');

Route::get('/admin/Asset/Osr/osrAssetsReport/notShortlistedReport/{fy_id}/{zp_id}', 'Admin\Osr\AdminOsrController@notShortlistedReport')->name('admin.Asset.Osr.osrAssetsReport.notShortlistedReport');

//===================================================**OSR BIDDING REPORT**==============================================
Route::get('/admin/Osr/osrBiddingReportIndex', 'Admin\Osr\AdminOsrController@osrBiddingReportIndex')->name('admin.Osr.osrBiddingReportIndex');
Route::post('/admin/Osr/osrBiddingReportIndex', 'Admin\Osr\AdminOsrController@osrBiddingReportIndex')->name('admin.Osr.osrBiddingReportIndex1');


//===================================================**USER MANAGEMENT**================================================
Route::get('/admin/UsersManagement/user_dashboard', 'Admin\UsersManagement\AdminUsersManagementController@user_dashboard')->name('admin.UsersManagement.user_dashboard');

Route::get('admin/UsersManagement/sa_user_management', 'Admin\UsersManagement\AdminUsersManagementController@StateUser')->name('admin.UsersManagement.sa_user_management');
Route::get('admin/UsersManagement/da_user_management', 'Admin\UsersManagement\AdminUsersManagementController@ZpUser')->name('admin.UsersManagement.da_user_management');
Route::get('admin/UsersManagement/aa_user_management', 'Admin\UsersManagement\AdminUsersManagementController@ApUser')->name('admin.UsersManagement.aa_user_management');
Route::get('admin/UsersManagement/ga_user_management', 'Admin\UsersManagement\AdminUsersManagementController@GpUser')->name('admin.UsersManagement.ga_user_management');
Route::get('admin/UsersManagement/cc_user_management', 'Admin\UsersManagement\AdminUsersManagementController@CourtCaseUser')->name('admin.UsersManagement.cc_user_management');
Route::get('admin/UsersManagement/ex_user_management', 'Admin\UsersManagement\AdminUsersManagementController@ExtensionCentreUser')->name('admin.UsersManagement.ex_user_management');

Route::get('admin/UsersManagement/user_management', 'Admin\UsersManagement\AdminUsersManagementController@User')->name('admin.UsersManagement.user_management');
Route::get('admin/UsersManagement/profile', 'Admin\UsersManagement\AdminUsersManagementController@profile')->name('admin.UsersManagement.profile');
Route::get('admin/UsersManagement/change_password', 'Admin\UsersManagement\AdminUsersManagementController@changePassword')->name('admin.UsersManagement.change_password');
Route::post('admin/UsersManagement/user_management', 'Admin\UsersManagement\AdminUsersManagementController@User')->name('admin.UsersManagement.user_management');
Route::post('admin/UsersManagement/select_ap', 'Admin\UsersManagement\AdminUsersManagementController@selectAp')->name('admin.userManagement.select_ap');
Route::post('admin/UsersManagement/select_ajax', 'Admin\UsersManagement\AdminUsersManagementController@selectAjax')->name('admin.userManagement.select_ajax');
Route::post('admin/UsersManagement/createMdasUser', 'Admin\UsersManagement\AdminUsersManagementController@createMdasUser')->name('admin.userManagement.createMdasUser');
Route::get('admin/UsersManagement/statusUser', 'Admin\UsersManagement\AdminUsersManagementController@statusUser')->name('admin.userManagement.statusUser');



//====================================================**CHANGE PASSWORD**======================================================================

Route::post('admin/UsersManagement/getMdasUserByid', 'Admin\UsersManagement\AdminUsersManagementController@getMdasUserByid')->name('admin.userManagement.getMdasUserByid');

Route::post('admin/UsersManagement/userPasswordUpdate', 'Admin\UsersManagement\AdminUsersManagementController@userPasswordUpdate')->name('admin.userManagement.userPasswordUpdate');

//++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
//===============================######  MANAGE USERS  #####============================================================

Route::get('/admin/manageUser', 'Admin\ManageUser\ManageUserController@index')->name('admin.manageUser');
Route::post('/admin/manageUser/selectAnchalAjax', 'Admin\ManageUser\ManageUserController@selectAnchalAjax')->name('admin.manageUser.selectAnchalAjax');
Route::post('/admin/manageUser/currentGPLevelEmpList', 'Admin\ManageUser\ManageUserController@currentGPLevelEmpList')->name('admin.manageUser.currentGPLevelEmpList');


//===================================================**OSR NEW**========================================================

Route::get('/admin/Osr/osr_dashboard/', 'Admin\Osr\AdminOsrController@osr_dashboard')->name('admin.Osr.osr_dashboard');
Route::get('/admin/Osr/LevelWiseReport/', 'Admin\Osr\AdminOsrController@LevelWiseReport')->name('admin.Osr.LevelWiseReport');


Route::get('/admin/Osr/ning/', 'Admin\Osr\OsrTestController@TestSubDistrictWiseRevenue')->name('admin.Osr.TestSubDistrictWiseRevenue'); // for latest year testing
Route::get('/admin/Osr/ning_asset/', 'Admin\Osr\OsrTestController@nonCompletedAssetZps')->name('admin.Osr.nonCompletedAssetZps');

Route::get('/admin/Osr/nontaxAssets', 'Admin\Osr\AdminOsrNonTaxAssetsController@non_tax_assets')->name('admin.Osr.nonTaxAssets');
Route::get('/admin/Osr/getnontaxassetsShortlisted', 'Admin\Osr\AdminOsrNonTaxAssetsController@get_non_tax_assets_shortlisted')->name('admin.Osr.getnontaxassetsShortlisted');
Route::get('/admin/Osr/shownontaxassetsShortlisted/{id}', 'Admin\Osr\AdminOsrNonTaxAssetsController@show_non_tax_assets_shortlisted')->name('admin.Osr.shownontaxassetsShortlisted');
Route::get('/admin/Osr/nontaxOtherAssets', 'Admin\Osr\AdminOsrNonTaxOtherAssetsController@non_tax_other_assets')->name('admin.Osr.nonTaxOtherAssets');


Route::get('/admin/Shortlist/Osr/Asset/asset_status/', 'Admin\Osr\AdminOsrController@asset_status')->name('admin.Osr.asset.asset_status');

Route::get('/admin/Shortlist/Osr/Asset/asset_status_show/', 'Admin\Osr\AdminOsrController@asset_status_show')->name('admin.Osr.asset.asset_status_show');

//DEFAULTER
Route::post('/admin/Osr/dashboard/listOfTotalZPDefaulterZilaWise', 'Admin\Osr\AdminOsrController@listOfTotalZPDefaulterZilaWise')->name('admin.Osr.dashboard.listOfTotalZPDefaulterZilaWise');

Route::post('/admin/Osr/dashboard/listOfZPDefaulterZilaWise', 'Admin\Osr\AdminOsrController@listOfZPDefaulterZilaWise')->name('admin.Osr.dashboard.listOfZPDefaulterZilaWise');
Route::post('/admin/Osr/dashboard/listOfTotalDefaulterYearWise', 'Admin\Osr\AdminOsrController@listOfTotalDefaulterYearWise')->name('admin.Osr.dashboard.listOfTotalDefaulterYearWise');
//-----------------------------------------------AP LEVEL --------------------------------------------------------------
//Settlement
Route::get('/admin/Osr/subDistrictWiseAssetSettlement/{id}/{fy}', 'Admin\Osr\AdminOsrApController@subDistrictWiseAssetSettlement')->name('admin.Osr.subDistrictWiseAssetSettlement');
//Defaulter
Route::get('/admin/Osr/subDistrictWiseDefaulterReport/{id}/{fy}', 'Admin\Osr\AdminOsrApController@subDistrictWiseDefaulterReport')->name('admin.Osr.subDistrictWiseDefaulterReport');
Route::post('/admin/Osr/dashboard/listOfAPDefaulterZilaWise', 'Admin\Osr\AdminOsrApController@listOfAPDefaulterZilaWise')->name('admin.Osr.dashboard.listOfAPDefaulterZilaWise');
//Revenue
Route::get('/admin/Osr/subDistrictWiseRevenue/{id}/{fy}', 'Admin\Osr\AdminOsrApController@subDistrictWiseRevenue')->name('admin.Osr.subDistrictWiseRevenue');

//Share
Route::get('/admin/Osr/subDistrictWiseShare/{id}/{fy}', 'Admin\Osr\AdminOsrApController@subDistrictWiseShare')->name('admin.Osr.subDistrictWiseShare');

// Test Ningthem
Route::get('/admin/Osr/TestSubDistrict/{id}/{fy}', 'Admin\Osr\OsrTestController@TestSubDistrictWiseRevenue')->name('admin.Osr.TestSubDistrictWiseRevenue');

//-----------------------------------------------GP LEVEL---------------------------------------------------------------
//Settlement
Route::get('/admin/Osr/subAPWiseAssetSettlement/{id}/{fy}/{ap}', 'Admin\Osr\AdminOsrGpController@subAPWiseAssetSettlement')->name('admin.Osr.subAPWiseAssetSettlement');
//Defaulter
Route::get('/admin/Osr/subAPWiseAssetDefaulter/{id}/{fy}/{ap}', 'Admin\Osr\AdminOsrGpController@subAPWiseAssetDefaulter')->name('admin.Osr.subAPWiseAssetDefaulter');
Route::post('/admin/Osr/dashboard/listOfGPDefaulterZilaWise', 'Admin\Osr\AdminOsrGpController@listOfGPDefaulterZilaWise')->name('admin.Osr.dashboard.listOfGPDefaulterZilaWise');
//Revenue
Route::get('/admin/Osr/subAPWiseAssetRevenue/{id}/{fy}/{ap}', 'Admin\Osr\AdminOsrGpController@subAPWiseAssetRevenue')->name('admin.Osr.subAPWiseAssetRevenue');
//Share
Route::get('/admin/Osr/subAPWiseAssetShare/{id}/{fy}/{ap}', 'Admin\Osr\AdminOsrGpController@subAPWiseAssetShare')->name('admin.Osr.subAPWiseAssetShare');

//--------------------------------------------------BRANCH LIST (ZP, AP, GP)--------------------------------------------
Route::get('/admin/Osr/assetInformation/{id}/{fy}/{branch}/{asset}/{ap?}/{gp?}', 'Admin\Osr\AdminOsrNonTaxAssetsController@assetInformation')->name('admin.Osr.assetInformation');

//Settlement
Route::get('/admin/Osr/osrAssetBranchList/{id}/{fy}/{ap?}/{gp?}', 'Admin\Osr\AdminOsrBranchController@osrAssetBranchList')->name('admin.Osr.osrAssetBranchList');
Route::get('/admin/Osr/osrAssetSingleBranchList/{id}/{fy}/{branch}/{ap?}/{gp?}', 'Admin\Osr\AdminOsrBranchController@osrAssetSingleBranchList')->name('admin.Osr.osrAssetSingleBranchList');

//Defaulter
Route::get('/admin/Osr/osrDefaulterAssetBranchList/{id}/{fy}/{ap?}/{gp?}', 'Admin\Osr\AdminOsrBranchController@osrDefaulterAssetBranchList')->name('admin.Osr.osrDefaulterAssetBranchList');
Route::get('/admin/Osr/osrDefaulterSingleBranchList/{id}/{fy}/{branch}/{ap?}/{gp?}', 'Admin\Osr\AdminOsrBranchController@osrDefaulterSingleBranchList')->name('admin.Osr.osrDefaulterSingleBranchList');
Route::post('/admin/Osr/dashboard/listOfDefaulterBranchWise', 'Admin\Osr\AdminOsrBranchController@listOfDefaulterBranchWise')->name('admin.Osr.dashboard.listOfDefaulterBranchWise');

//Collection
Route::get('/admin/Osr/osrRevenueAssetBranchList/{id}/{fy}/{ap?}/{gp?}', 'Admin\Osr\AdminOsrBranchController@osrRevenueAssetBranchList')->name('admin.Osr.osrRevenueAssetBranchList');
Route::get('/admin/Osr/osrRevenueSingleBranchList/{id}/{fy}/{branch}/{ap?}/{gp?}', 'Admin\Osr\AdminOsrBranchController@osrRevenueSingleBranchList')->name('admin.Osr.osrRevenueSingleBranchList');

//Share
Route::get('/admin/Osr/osrShareAssetBranchList/{id}/{fy}/{ap?}/{gp?}', 'Admin\Osr\AdminOsrBranchController@osrShareAssetBranchList')->name('admin.Osr.osrShareAssetBranchList');
Route::get('/admin/Osr/osrShareSingleBranchList/{id}/{fy}/{branch}/{ap?}/{gp?}', 'Admin\Osr\AdminOsrBranchController@osrShareSingleBranchList')->name('admin.Osr.osrShareSingleBranchList');



//--------------------------------------------Utilization Certificate------------------------------------------------------//
Route::get('admin/Uc/dashboard', 'Admin\Uc\UcController@dashboard')->name('admin.Uc.dashboard');
Route::get('admin/Uc/ucAddProject', 'Admin\Uc\UcController@ucAddProject')->name('admin.Uc.ucAddProject');
Route::post('admin/Uc/ucProject/save', 'Admin\Uc\UcController@project_save')->name('admin.uc.ucProject.save');
Route::get('admin/Uc/ucViewProject', 'Admin\Uc\UcController@ucViewProject')->name('admin.Uc.ucViewProject');
Route::get('admin/Uc/loadProjectStates', 'Admin\Uc\UcController@load_project_years')->name('admin.Uc.loadProjectStates');
Route::get('admin/Uc/loadProjectStates1', 'Admin\Uc\UcController@load_project_years1')->name('admin.Uc.loadProjectStates1');
Route::get('admin/Uc/loadExtensionsDistricts', 'Admin\Uc\UcController@load_extensions_districts')->name('admin.Uc.loadExtensionsDistricts');
Route::get('admin/Uc/loadExtensionsDistricts1', 'Admin\Uc\UcController@load_extensions_districts1')->name('admin.Uc.loadExtensionsDistricts1');

Route::get('admin/Uc/addProjectYears', 'Admin\Uc\UcController@add_project_years')->name('admin.Uc.addProjectYears');
Route::post('admin/Uc/saveProjectYears', 'Admin\Uc\UcController@save_project_years')->name('admin.Uc.saveProjectYears');
Route::get('admin/Uc/viewProjectYears', 'Admin\Uc\UcController@view_project_years')->name('admin.Uc.viewProjectYears');

//Add new Component
Route::get('admin/Uc/addComponent', 'Admin\Uc\UcComponentsController@add_component')->name('admin.Uc.addComponent');
Route::post('admin/Uc/ucComponent/save', 'Admin\Uc\UcComponentsController@component_save')->name('admin.uc.ucComponent.save');

Route::get('admin/Uc/addEntities', 'Admin\Uc\UcController@add_entities')->name('admin.Uc.addEntities');
Route::post('admin/Uc/saveEntities', 'Admin\Uc\UcController@save_entities')->name('admin.Uc.saveEntities');
Route::get('admin/Uc/viewEntities', 'Admin\Uc\UcController@view_entities')->name('admin.Uc.viewEntities');

Route::get('admin/Uc/addEditComponentDetails', 'Admin\Uc\UcComponentsController@add_edit_component_details')->name('admin.Uc.addEditComponentDetails');
Route::get('admin/Uc/loadEntities', 'Admin\Uc\UcComponentsController@load_entities')->name('admin.Uc.loadEntities');
Route::get('admin/Uc/getEntityComponents', 'Admin\Uc\UcComponentsController@get_entity_components')->name('admin.Uc.getEntityComponents');
Route::post('admin/Uc/saveEntityComponents', 'Admin\Uc\UcComponentsController@save_entity_components')->name('admin.Uc.saveEntityComponents');

//uc upload
Route::post('admin/Uc/gfr/save', 'Admin\Uc\UcComponentsController@gfr_save')->name('admin.uc.gfr.save');

//UC view pdf
Route::get('admin/Uc/gfr/view/{entity_id}', 'Admin\Uc\UcComponentsController@UcView')->name('admin.uc.gfr.view');

Route::get('admin/Uc/viewComponentDetails', 'Admin\Uc\UcComponentsController@view_component_details')->name('admin.Uc.viewComponentDetails');
Route::get('admin/Uc/viewEntityComponents/{id}', 'Admin\Uc\UcComponentsController@view_entity_components')->name('admin.Uc.viewEntityComponents');
Route::get('admin/Uc/showEntityComponents', 'Admin\Uc\UcComponentsController@show_entity_components')->name('admin.Uc.showEntityComponents');

Route::get('admin/Uc/componentWiseReports', 'Admin\Uc\UcComponentsReportsController@componentWiseReports')->name('admin.Uc.componentWiseReports');
Route::get('admin/Uc/getProjectYears', 'Admin\Uc\UcComponentsReportsController@get_project_years')->name('admin.Uc.getProjectYears');
Route::get('admin/Uc/getComponentsHeadings', 'Admin\Uc\UcComponentsReportsController@get_components_headings')->name('admin.Uc.getComponentsHeadings');

Route::post('admin/Uc/gistallEntitiesComponents', 'Admin\Uc\UcComponentsReportsController@gist_all_entities_components')->name('admin.Uc.gistallEntitiesComponents');
Route::post('admin/Uc/gistEntitiesComponents', 'Admin\Uc\UcComponentsReportsController@gist_entities_components')->name('admin.Uc.gistEntitiesComponents');

//--------------------New changes for council user creation-----------------------------------------------

//New changes for council
Route::get('admin/UsersManagement/daC_user_management', 'Admin\UsersManagement\AdminUsersManagementController@DistrictCouncilUser')->name('admin.UsersManagement.dca_user_management');
Route::get('admin/UsersManagement/blockC_user_management', 'Admin\UsersManagement\AdminUsersManagementController@BlockCouncilUser')->name('admin.UsersManagement.bca_user_management');
Route::get('admin/UsersManagement/gaC_user_management', 'Admin\UsersManagement\AdminUsersManagementController@GpCouncilUser')->name('admin.UsersManagement.gca_user_management');

//District council/Block/VCDC/VDC
Route::post('admin/UsersManagement/select_block_council', 'Admin\UsersManagement\AdminUsersManagementController@selectBlockCouncil')->name('admin.userManagement.select_block_council');
Route::post('admin/UsersManagement/selectVCDCajax', 'Admin\UsersManagement\AdminUsersManagementController@selectVCDCajax')->name('admin.userManagement.select_vcdc_ajax');
//District council/Block/VCDC/VDC Ends

//===================================== Grievance =============================================

Route::get('admin/Grievance/dashboard', 'Admin\Grievance\AdminGrievanceController@dashboard')->name('admin.Grievance.dashboard');

//ADD RECIPIENTS
Route::get('admin/Grievance/addRecipients', 'Admin\Grievance\GrievanceRecipientsController@add_recipients')->name('admin.Grievance.addRecipients');
Route::post('admin/Grievance/addRecipients', 'Admin\Grievance\GrievanceRecipientsController@recipient_save')->name('admin.Grievance.saveRecipients'); //ADD RECIPIENTS
Route::get('admin/Grievance/viewRecipients', 'Admin\Grievance\GrievanceRecipientsController@view_recipients')->name('admin.Grievance.viewRecipients');

//VIEW RECIPIENTS
Route::post('admin/Grievance/getRecipientsByid', 'Admin\Grievance\GrievanceRecipientsController@getRecipientsByid')->name('admin.Grievance.getRecipientsByid');
Route::post('admin/Grievance/editRecipient', 'Admin\Grievance\GrievanceRecipientsController@editRecipient')->name('admin.Grievance.editRecipient');
Route::get('admin/Grievance/loadDistrictBlocks1', 'Admin\Grievance\GrievanceRecipientsController@load_district_blocks')->name('admin.Grievance.loadDistrictBlocks1');


//RECIPIENT STATUS
Route::get('admin/Grievance/statusRecipient', 'Admin\Grievance\GrievanceRecipientsController@statusRecipient')->name('admin.Grievance.statusRecipient');

//===================================MEDIA GRIEVANCE==============================//


//get block by district
Route::post('admin/Grievance/getBlockByDistrict', 'Admin\Grievance\AdminGrievanceController@getBlockByDistrict')->name('admin.Grievance.getBlockByDistrict');

//get gps by block
Route::post('admin/Grievance/getGPsByBlock', 'Admin\Grievance\AdminGrievanceController@getGPsByBlock')->name('admin.Grievance.getGPsByBlock');

//Media Grievance Entry part
Route::get('admin/Grievance/Media/entry', 'Admin\Grievance\AdminGrievanceController@media_entry')->name('admin.Grievance.media.entry');

Route::post('admin/Grievance/Media/save', 'Admin\Grievance\AdminGrievanceController@media_entry_save')->name('admin.Grievance.media.save');

//download PDF
Route::post('admin/Grievance/Media/download/permission', 'Admin\Grievance\GrievanceDownloadController@download_permission')->name('admin.grievance.media.download.permission');

Route::get('admin/Grievance/Media/download', 'Admin\Grievance\GrievanceDownloadController@download')->name('admin.grievance.media.download');

//Media Grievance Action panel
Route::get('admin/Grievance/Media/action_panel', 'Admin\Grievance\AdminGrievanceController@action_panel')->name('admin.Grievance.media.action_panel');

Route::post('admin/Grievance/Media/action_panel', 'Admin\Grievance\AdminGrievanceController@action_panel')->name('admin.Grievance.media.action_panel');

Route::get('admin/Grievance/Media/action_list', 'Admin\Grievance\AdminGrievanceController@action_list')->name('admin.Grievance.media.action_list');

Route::post('admin/Grievance/Media/getMediaData', 'Admin\Grievance\AdminGrievanceController@getMediaData')->name('admin.Grievance.media.getMediaData');

Route::post('admin/Grievance/Media/action', 'Admin\Grievance\AdminGrievanceController@action')->name('admin.Grievance.media.action');

Route::get('admin/Grievance/reportStatus', 'Admin\Grievance\AdminGrievanceController@reportStatus')->name('admin.Grievance.reportStatus');

//--Report view links
Route::get('grievance/Media/Action/report/view/{m_id}', 'Grievance\GrievanceController@mediaActionReportView')->name('grievance.Media.Action.report.view');

Route::get('grievance/Media/Reply/view/{m_id}', 'Grievance\GrievanceController@mediaReplyReportView')->name('grievance.Media.Reply.view');
//--Report view links End


//-------MEDIA GRIEVANCE MESSAGE

Route::get('admin/msg/Media/Grievance', 'Admin\Grievance\GrievanceMessageController@sendMediaMessages')->name('admin.grievance.Media.sendMessage');

//-----INDIVIDUAL GRIEVANCE MESSAGE ON TAKING ACTION 
Route::get('admin/Grievance/Individual/Action/Message', 'Admin\Grievance\GrievanceMessageController@sendActionMessages')->name('admin.grievance.Individual.action.sendMessage');

//----------------------INDIVIDUAL GRIEVANCE-------------------------------------------------

Route::get('admin/Grievance/Individual/griev_entry', 'Admin\Grievance\AdminGrievanceController@individual_griev_entry')->name('admin.Grievance.individual_griev_entry');

Route::post('admin/Grievance/Individual/griev_save', 'Admin\Grievance\AdminGrievanceController@individual_griev_save')->name('admin.Grievance.individual_griev_save');

Route::get('admin/Grievance/Individual/griev_confirm_page', 'Admin\Grievance\AdminGrievanceController@griev_confirm_page')->name('admin.Grievance.individual_griev_confirm_page');

Route::get('admin/Grievance/Individual/individual_griev_list', 'Admin\Grievance\AdminGrievanceController@individual_griev_list')->name('admin.Grievance.Individual.individual_griev_list');

Route::get('admin/Grievance/Individual/individual_grievance_details/{id}', 'Admin\Grievance\AdminGrievanceController@details')->name('admin.grievance.Individual.details');

Route::get('admin/Grievance/Individual/document/view/{id}', 'Admin\Grievance\AdminGrievanceController@individualDocumentView')->name('admin.grievance.Individual.Document.view');

Route::post('admin/Grievance/Individual/getGrievData', 'Admin\Grievance\AdminGrievanceController@getGrievData')->name('admin.Grievance.Individual.getGrievData');

Route::post('admin/Grievance/Individual/action', 'Admin\Grievance\AdminGrievanceController@individual_griev_action')->name('admin.Grievance.Individual.action');

Route::get('admin/Grievance/Individual/Entry/Message', 'Admin\Grievance\AdminGrievanceController@entry_msg')->name('admin.Grievance.Individual.entry_msg');

Route::get('admin/Grievance/Individual/Action/Message', 'Admin\Grievance\AdminGrievanceController@action_msg')->name('admin.Grievance.Individual.action_msg');

//Grievance Dahsboard Report Links

Route::get('admin/Grievance/Schemes/report/{id}', 'Admin\Grievance\AdminGrievanceController@scheme_report')->name('admin.Grievance.Scheme.report');


//-------------PDF DOWNLOAD OF GRIEVANCE CONFIRMATION-----------------------------------------

Route::get('admin/Grievance/download/Acknowledgement/{id}', 'Admin\Grievance\GrievanceDownloadController@acknowledgement_download');

Route::get('admin/Grievance/{grievType?}', 'Admin\Grievance\AdminGrievanceController@type_report')->name('admin.Grievance.Type.report');

//==================INDIVIDUAL GRIEVANCE=======================================//

//==================================== Grievance Ends =========================================

//******************************************NEED BASED TRAINING************************************

Route::get('admin/Training/dashboard', 'Admin\Training\TrainingController@index')->name('admin.Training.dashboard');

Route::get('admin/Training/training_entry', 'Admin\Training\TrainingController@training_entry')->name('admin.Training.training_entry');

Route::post('admin/Training/training_save', 'Admin\Training\TrainingController@training_save')->name('admin.Training.save');

Route::get('admin/Training/training_schedule_list', 'Admin\Training\TrainingController@training_schedule_list')->name('admin.Training.training_schedule_list');

Route::post('admin/training/getTrainingData', 'Admin\Training\TrainingController@getTrainingData')->name('admin.training.getTrainingData');

Route::post('admin/training/setTrainingAction', 'Admin\Training\TrainingController@setTrainingAction')->name('admin.training.setTrainingAction');

Route::get('admin/training/getParticipantDetails/{loc_id}/{training_id}', 'Admin\Training\TrainingController@getParticipantDetails')->name('admin.training.getParticipantDetails');

//*************************************************************************************************//






?>