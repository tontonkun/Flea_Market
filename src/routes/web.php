<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AddressController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\UserRegistrationController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\MainPageController;
use App\Http\Controllers\MyPageController;
use App\Http\Controllers\PostingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\ChatController;

// Auth::routes(['verify' => true]); を追加して、認証とメール認証を有効化
Auth::routes(['verify' => true]);

// UserRegistrationController
Route::get('register', [UserRegistrationController::class, 'create'])->name('register.form');
Route::post('register', [UserRegistrationController::class, 'store'])->name('register.submit');

// LoginController
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');;
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// MainPageController
Route::get('/', [MainPageController::class, 'showMainPage']);

// ItemController
Route::get('/item/{id}', [ItemController::class, 'showDetail'])->name('item.showDetail');
Route::post('/item/{id}/favorite', [ItemController::class, 'addFavorite'])->name('item.addFavorite');
Route::post('/item/{id}/addComment', [ItemController::class, 'addComment'])->name('item.addComment');

// PurchaseController(カスタムミドルウェア分)
Route::get('/purchase/{item}', [PurchaseController::class, 'showPurchasePage'])->name('showPurchasePage')->middleware('custom.auth');

// 認証が必要なルート
Route::middleware(['auth', 'verified'])->group(function () {

    // ProfileController
    Route::get('/myPage/profile', [ProfileController::class, 'showProfile']);
    Route::post('/setUpProfiles', [ProfileController::class, 'update']);

    // MyPageController
    Route::get('/myPage', [MyPageController::class, 'showMyPage']);

    // PostingController
    Route::get('/sell', [PostingController::class, 'showPostingPage']);
    Route::post('/postItems', [PostingController::class, 'postItems'])->name('postItems'); // POSTのみ

    // PurchaseController
    Route::post('/purchase/update-payment', [PurchaseController::class, 'updatePaymentMethod'])->name('purchase.updatePayment');
    Route::post('/purchase/{item}/process', [PurchaseController::class, 'process'])->name('purchase.process');
    Route::get('/purchase/success/{item}', [PurchaseController::class, 'success'])->name('purchase.success');

    // AddressController
    Route::get('/purchase/address/{item_id}', [AddressController::class, 'showAdressChangePage']);
    Route::post('/changeAddress/{item_id}', [AddressController::class, 'changeAddress']);

    // ChatController
    Route::get('/chat/{itemId}', [ChatController::class, 'showChat'])->name('chat.show');
    Route::post('/chat/{itemId}/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    Route::put('chat/{message}/edit', [ChatController::class, 'editMessage'])->name('chat.edit');
    Route::delete('chat/{message}', [ChatController::class, 'deleteMessage'])->name('chat.delete');
    Route::post('/items/{itemId}/complete', [ChatController::class, 'completeTrade'])->name('chat.evaluation');
    Route::post('/chat/{item}/complete', [ChatController::class, 'evaluation'])->name('chat.evaluation');
});
