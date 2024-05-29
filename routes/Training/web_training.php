<?php


//For Extension Centre only

Route::get('training/dashboard','Training\TrainingController@index')->name('training.dashboard');

Route::get('training/training_schedule_list','Training\TrainingController@training_schedule_list')->name('training.training_schedule_list');

Route::post('training/getTrainingData', 'Training\TrainingController@getTrainingData')->name('training.getTrainingData');

Route::post('training/setTrainingAction', 'Training\TrainingController@setTrainingAction')->name('training.setTrainingAction');


?>