<?php

use Illuminate\Support\Facades\Route;

Route::get('{code}', 'FansManageController@index')->name('index');
