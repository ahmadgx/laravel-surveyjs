<?php

Route::group(
    [
        'namespace'     =>  'AidynMakhataev\LaravelSurveyJs\app\Http\Controllers',
        'middleware'    =>  config('form-manager.route_middleware'),
        'prefix'        =>  config('form-manager.route_prefix'),
    ],
    function () {
        Route::get('/{surveySlug}', 'SurveyController@runSurvey')->name('survey-manager.run');
    }
);

Route::group(
    [
        'namespace'     =>  'AidynMakhataev\LaravelSurveyJs\app\Http\Controllers',
        'prefix'        =>  config('form-manager.admin_prefix').'/form/',
        'middleware'    =>  config('form-manager.admin_middleware'),
    ],
    function () {
        Route::view('{vue?}', 'survey-manager::admin')->where('vue', '[\/\w\.-]*')->name('survey-manager.admin');
    }
);
