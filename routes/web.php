<?php

use Illuminate\Support\Facades\Route;

/**
 * どんなパスでも Vue側でルーティングを行う
 */
Route::get('/{any?}', function () {
    return view('index');
})->where('any', '.*');
