<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

Route::middleware('auth')->group(function () {
    Route::get('/', [AuthController::class, 'showTopPage']);
    Route::get('/mypage', [AuthController::class, 'showMyPage']);
});