<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\UserRegistrationController;
use App\Http\Controllers\Auth\UserLoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostingController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\AddressController;

// Auth::routes(['verify' => true]); を追加して、認証とメール認証を有効化
// Auth::routes(['verify' => true]);

// UserRegistrationController
Route::get('register', [UserRegistrationController::class, 'create'])->name('register.form');
Route::post('register', [UserRegistrationController::class, 'store'])->name('register.submit');

// UserLoginController
Route::get('login', [UserLoginController::class, 'showLoginForm'])->name('login.form');
Route::post('login', [UserLoginController::class, 'login'])->name('login.submit');
Route::post('logout', [UserLoginController::class, 'logout'])->name('logout.submit');

// MainPageController
Route::get('/', [MainPageController::class, 'showMainPage']);

// ItemController
Route::get('/item/{id}', [ItemController::class, 'showDetail'])->name('item.showDetail');
Route::post('/item/{id}/favorite', [ItemController::class, 'addFavorite'])->name('item.addFavorite');
Route::post('/item/{id}/addComment', [ItemController::class, 'addComment'])->name('item.addComment');

// 認証が必要なルート
Route::middleware(['auth'])->group(function () {

    // ProfileController
    Route::get('/myPage/profile', [ProfileController::class, 'showProfile']);
    Route::post('/setUpProfiles', [ProfileController::class, 'update']);

    // MyPageController
    Route::get('/myPage', [MyPageController::class, 'showMyPage']);

    // PostingController
    Route::get('/sell', [PostingController::class, 'showPostingPage']);
    Route::get('/postItems', [PostingController::class, 'postItems']);
    Route::post('/postItems', [PostingController::class, 'postItems'])->name('postItems');

    // PurchaseController
    Route::get('/purchase', [PurchaseController::class, 'showPurchasePage']);
    Route::post('/purchase/{item}/process', [PurchaseController::class, 'process'])->name('purchase.process');

    // AddressController
    Route::get('/purchase/address/{item_id}', [AddressController::class, 'showAdressChangePage']);
    Route::get('/changeAddress/{item_id}', [AddressController::class, 'changeAddress']);
});

// メール認証が必要な場合に表示されるルート
// Route::middleware('guest')->group(function () {
//     Route::get('/email/verify', function () {
//         return view('auth.verify');
//     })->name('verification.notice');
// });
