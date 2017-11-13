<?php

Route::get('/issue/{taskId}', 'MainController@getJiraTaskName');
Route::post('/add/comment', 'MainController@postComments');
Route::post('/save-config', 'MainController@saveJiraConfiguration');
Route::get('/{slug?}', 'MainController@index');
Route::resource('report', 'ReportController', ['only' => [
    'show'
]]);


