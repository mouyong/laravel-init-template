<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use ZhenMu\LaravelInitTemplate\Http\Controllers\Api\Common;
use ZhenMu\LaravelInitTemplate\Http\Controllers\Api\Mobile;

Route::prefix('admin')->middleware('auth')->group(function () {
    // 用户登录管理
    Route::post('login', [Mobile\AuthController::class, 'login'])->withoutMiddleware(['auth'])->name('login');
    Route::post('logout', [Mobile\AuthController::class, 'logout']);
    Route::post('refresh', [Mobile\AuthController::class, 'refresh']);
    Route::post('me', [Mobile\AuthController::class, 'me']);
});

Route::prefix('')->group(function () {
    Route::post('sms/send', [Mobile\SmsController::class, 'sendVerifySms']);
});

Route::middleware(['auth:jwt-api'])->group(function () {
    Route::get('qiniu/token', [Common\QiniuController::class, 'token']);
});