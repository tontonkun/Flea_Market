<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostingController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\ProductController;


//MainPageController
Route::get('/', [MainPageController::class, 'showMainPage']);


//ProductController
Route::get('/product/{id}', [ProductController::class, 'showDetail'])->name('product.showDetail');
Route::post('/product/{id}/favorite', [ProductController::class, 'addFavorite'])->name('product.addFavorite');
Route::post('/product/{id}/addComment', [ProductController::class, 'addComment'])->name('product.addComment');


Route::middleware('auth')->group(function () {

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