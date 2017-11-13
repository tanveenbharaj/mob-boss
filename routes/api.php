<?php

use Illuminate\Http\Request;


Route::put('changemobname', 'MobController@changeName');
Route::resource('mob', 'MobController');

