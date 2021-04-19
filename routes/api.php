<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use ZhenMu\LaravelInitTemplate\Http\Controllers\Api\Mobile\AuthController;

Route::prefix('admin')->middleware('auth')->group(function () {
    // 用户登录管理
    Route::post('login', [AuthController::class, 'login'])->withoutMiddleware(['auth']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('refresh', [AuthController::class, 'refresh']);
    Route::post('me', [AuthController::class, 'me']);
});