<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostingController;
use App\Http\Controllers\MyPageController;


// 未ログイン時
Route::get('/', function () {
    return view('mainPage');
});

Route::middleware('auth')->group(function () {
    //AuthController
    Route::get('/myPage', [AuthController::class, 'showMyPage']);

    //ProfileController
    Route::get('/myPage/profile', [ProfileController::class, 'showProfile']);
    Route::post('/setUpProfiles', [ProfileController::class, 'update']);

    //MyPageController
    Route::get('/myPage', [MyPageController::class, 'showMyPage']);

    //PostingController
    Route::get('/sell', [PostingController::class, 'showPostingPage']);
    Route::get('/postItems', [PostingController::class, 'PostItems']);
    Route::post('/postItems', [PostingController::class, 'PostItems'])->name('postItems');
});