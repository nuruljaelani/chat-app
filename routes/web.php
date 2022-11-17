<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\ConversationController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::controller(LoginController::class)->group(function(){
    Route::post('/auth', 'authenticate')->name('auth');
    Route::post('/logout', 'logout')->name('logout');
});

Route::get('login', function () {
    return view('login');
})->name('login');

Route::middleware('auth')->group(function() {
    Route::controller(ConversationController::class)->name('conversation.')->group(function() {
        Route::get('/', 'index')->name('index');
        Route::post('/conversation', 'create')->name('create');
    });

    Route::controller(ChatController::class)->name('chat.')->group(function(){
        Route::post('chat/store', 'store')->name('store');
        Route::get('chat', 'loadMessage')->name('load');
    });

    Route::controller(UserController::class)->name('user.')->group(function(){
        Route::get('/user/show', 'show')->name('show');
        Route::post('/user/update', 'update')->name('update');
    });
});