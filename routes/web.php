<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home.index');

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';

// 后台
Route::prefix('admin')->middleware(['auth:sanctum', 'verified', 'CheckAccess'])->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');

    // 权限管理
    Route::group([], function () {
        // 权限
        Route::get('/permission', [PermissionController::class, 'index'])->name('permission.index');

        // 角色
        Route::prefix('role')->name('role.')->group(function () {
            Route::resource('', RoleController::class)->except(['create', 'edit', 'show'])->parameter('', 'role');
        });

        // 用户
        Route::prefix('user')->name('user.')->group(function () {
            Route::resource('', UserController::class)->except(['create', 'edit', 'show'])->parameter('', 'user');
        });
    });

    // 通知中心
    Route::prefix('notification')->name('notification.')->group(function () {
        // 通知中心
        Route::get('/', [NotificationController::class, 'index'])->name('index');
        // 标记为已读
        Route::put('/{notification}', [NotificationController::class, 'markAsRead'])->name('read');
        // 清空通知
        Route::put('/', [NotificationController::class, 'empty'])->name('empty');
        // 获取近十条未读通知
        Route::get('/top-ten', [NotificationController::class, 'getTopTenNotifications'])->name('top.ten');
    });
});
