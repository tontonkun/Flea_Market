<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostingController;

Route::middleware('auth')->group(function () {
    //AuthController
    Route::get('/', [AuthController::class, 'showTopPage']);

    //ProfileController
    Route::post('/setUpProfiles', [ProfileController::class, 'update']);
    Route::get('/myPage', [ProfileController::class, 'showProfile']);

    //PostingController
    Route::get('/sell', [PostingController::class, 'showPostingPage']);
    Route::get('/postItems', [PostingController::class, 'PostItems']);
    Route::post('/postItems', [PostingController::class, 'PostItems'])->name('postItems');
});