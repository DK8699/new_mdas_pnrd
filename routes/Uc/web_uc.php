<?php
// Route::get('uc/dashboard', 'Uc\UcComponentsController@add_component_details')->name('uc.dashboard');
// Route::get('uc/addComponentDetails', 'Uc\UcComponentsController@add_edit_component_details')->name('uc.addComponentDetails');
// Route::get('uc/loadProjectStates', 'Uc\UcController@load_project_years')->name('uc.loadProjectStates');
// Route::get('uc/getEntityComponents', 'Uc\UcComponentsController@get_entity_components')->name('uc.getEntityComponents');

Route::get('Uc/dashboard', 'Uc\UcController@dashboard')->name('Uc.dashboard');
// Route::get('Uc/ucAddProject','Uc\UcController@ucAddProject')->name('Uc.ucAddProject');
// Route::post('Uc/ucProject/save','Uc\UcController@project_save')->name('uc.ucProject.save');
Route::get('Uc/ucViewProject','Uc\UcController@ucViewProject')->name('Uc.ucViewProject');
Route::get('Uc/loadProjectStates', 'Uc\UcController@load_project_years')->name('Uc.loadProjectStates');
// Route::get('Uc/loadProjectStates1', 'Uc\UcController@load_project_years1')->name('Uc.loadProjectStates1');
// Route::get('Uc/loadExtensionsDistricts', 'Uc\UcController@load_extensions_districts')->name('Uc.loadExtensionsDistricts');
// Route::get('Uc/loadExtensionsDistricts1', 'Uc\UcController@load_extensions_districts1')->name('Uc.loadExtensionsDistricts1');

// Route::get('Uc/addProjectYears', 'Uc\UcController@add_project_years')->name('Uc.addProjectYears');
// Route::post('Uc/saveProjectYears', 'Uc\UcController@save_project_years')->name('Uc.saveProjectYears');
Route::get('Uc/viewProjectYears', 'Uc\UcController@view_project_years')->name('Uc.viewProjectYears');

// //Add new Component
Route::get('Uc/addComponent', 'Uc\UcComponentsController@add_component')->name('Uc.addComponent');
// Route::post('Uc/ucComponent/save','Uc\UcComponentsController@component_save')->name('uc.ucComponent.save');

// Route::get('Uc/addEntities', 'Uc\UcController@add_entities')->name('Uc.addEntities');
// Route::post('Uc/saveEntities', 'Uc\UcController@save_entities')->name('Uc.saveEntities');
Route::get('Uc/viewEntities', 'Uc\UcController@view_entities')->name('Uc.viewEntities');

Route::get('Uc/addEditComponentDetails', 'Uc\UcComponentsController@add_edit_component_details')->name('Uc.addEditComponentDetails');
Route::get('Uc/loadEntities', 'Uc\UcComponentsController@load_entities')->name('Uc.loadEntities');
Route::get('Uc/getEntityComponents', 'Uc\UcComponentsController@get_entity_components')->name('Uc.getEntityComponents');
Route::post('Uc/saveEntityComponents', 'Uc\UcComponentsController@save_entity_components')->name('Uc.saveEntityComponents');

//uc upload
Route::post('Uc/gfr/save','Uc\UcComponentsController@gfr_save')->name('uc.gfr.save');

//UC view pdf
Route::get('Uc/gfr/view/{entity_id}','Uc\UcComponentsController@UcView')->name('uc.gfr.view');

Route::get('Uc/viewComponentDetails', 'Uc\UcComponentsController@view_component_details')->name('Uc.viewComponentDetails');
Route::get('Uc/viewEntityComponents/{id}', 'Uc\UcComponentsController@view_entity_components')->name('Uc.viewEntityComponents');
// Route::get('Uc/showEntityComponents', 'Uc\UcComponentsController@show_entity_components')->name('Uc.showEntityComponents');







Route::post('Uc/selectYearsAjax', 'Uc\UcComponentsReportsController@selectYearsAjax')->name('selectYearsAjax');
Route::get('Uc/componentWiseReports', 'Uc\UcComponentsReportsController@componentWiseReports')->name('Uc.componentWiseReports');
Route::post('Uc/componentWiseReports', 'Uc\UcComponentsReportsController@componentWiseReports')->name('Uc.componentWiseReports');









Route::get('Uc/getProjectYears', 'Uc\UcComponentsReportsController@get_project_years')->name('Uc.getProjectYears');
/*Route::get('Uc/getComponentsHeadings', 'Uc\UcComponentsReportsController@get_components_headings')->name('Uc.getComponentsHeadings');*/

/*Route::post('Uc/gistallEntitiesComponents', 'Uc\UcComponentsReportsController@gist_all_entities_components')->name('Uc.gistallEntitiesComponents');*/
/*Route::post('Uc/gistEntitiesComponents', 'Uc\UcComponentsReportsController@gist_entities_components')->name('Uc.gistEntitiesComponents');*/
