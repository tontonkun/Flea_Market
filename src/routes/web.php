<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostingController;

Route::middleware('auth')->group(function () {
    Route::get('/', [AuthController::class, 'showTopPage']);
    Route::post('/setUpProfiles', [ProfileController::class, 'update']);
    Route::post('/myPage', [ProfileController::class, 'showProfile']);
    Route::get('/sell', [PostingController::class, 'startPosting']);
});