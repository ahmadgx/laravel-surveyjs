<?php

Route::group(
    [
        'namespace'     =>  'AidynMakhataev\LaravelSurveyJs\app\Http\Controllers\API',
        'middleware'    =>  config('form-manager.api_middleware'),
        'prefix'        =>  config('form-manager.api_prefix'),
    ],
    function () {
        Route::resource('/form', 'SurveyAPIController', ['only' => [
            'index', 'store', 'update', 'destroy', 'show',
        ]]);
        Route::resource('/form/{form}/result', 'SurveyResultAPIController');

        Route::post('/form/upload-file', 'SurveyResultAPIController@uploadFile');
        Route::post('/form/back-end-validation', 'SurveyResultAPIController@backEndValidation');
        Route::get('/get-branches', 'APIController@getBranches');
    }
);
