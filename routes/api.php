<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DepositController;
use App\Http\Controllers\RtController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WithdrawalController;
use App\Http\Controllers\WithdrawController;
use App\Http\Controllers\NotificationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function() {
    // Endpoint untuk mendapatkan data semua pengguna
    Route::get('users', [UserController::class, 'index']);

    Route::get('/users/get-users', [UserController::class, 'getUsers']);

    // Endpoint untuk mendapatkan data pengguna berdasarkan ID
    Route::get('users/{id}', [UserController::class, 'show']);

    // Endpoint untuk membuat pengguna baru
    Route::post('users', [UserController::class, 'store']);

    // Endpoint untuk memperbarui data pengguna
    Route::put('users/{id}', [UserController::class, 'update']);

    // Endpoint untuk menghapus pengguna
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    

    // Endpoint untuk mendapatkan semua kategori sampah
    Route::get('categories', [CategoryController::class, 'index']);

    // Endpoint untuk mendapatkan kategori sampah berdasarkan ID
    Route::get('categories/{id}', [CategoryController::class, 'show']);

    // Endpoint untuk membuat kategori sampah baru
    Route::post('categories', [CategoryController::class, 'store']);

    // Endpoint untuk memperbarui kategori sampah
    Route::put('categories/{id}', [CategoryController::class, 'update']);

    // Endpoint untuk menghapus kategori sampah
    Route::delete('categories/{id}', [CategoryController::class, 'destroy']);

     Route::get('deposits', [DepositController::class, 'index']);

     Route::post('deposits/estimate-price', [DepositController::class, 'estimatePrice']);
    // Endpoint untuk mendapatkan setoran berdasarkan ID
    Route::get('deposits/{id}', [DepositController::class, 'show']);


    // Endpoint untuk membuat setoran baru
    Route::post('deposits', [DepositController::class, 'store']);

    // Endpoint untuk memperbarui setoran
    Route::put('deposits/{id}', [DepositController::class, 'update']);

    // Endpoint untuk menghapus setoran
    Route::delete('deposits/{id}', [DepositController::class, 'destroy']);

    Route::get('withdraw', [WithdrawController::class, 'index']);
    Route::get('withdraw/admin/summary', [WithdrawController::class, 'adminSummary']);
    Route::post('withdraw', [WithdrawController::class, 'store']);
    Route::patch('withdraw/{id}/approve', [WithdrawController::class, 'approve']);
    Route::patch('withdraw/{id}/reject', [WithdrawController::class, 'reject']);
    Route::get('withdraw/{id}', [WithdrawController::class, 'show']);

     Route::get('rts', [RtController::class, 'index']);

    // Endpoint untuk mendapatkan RT berdasarkan ID
    Route::get('rts/{id}', [RtController::class, 'show']);

    // Endpoint untuk membuat RT baru
    Route::post('rts', [RtController::class, 'store']);

    // Endpoint untuk memperbarui RT
    Route::put('rts/{id}', [RtController::class, 'update']);

    // Endpoint untuk menghapus RT
    Route::delete('rts/{id}', [RtController::class, 'destroy']);

    Route::get('dashboard/summary', [DashboardController::class, 'summary']);
    Route::get('dashboard/user-summary', [DashboardController::class, 'userSummary']);
    Route::get('report', [ReportController::class, 'index']);
    Route::get('report/user', [ReportController::class, 'userReport']);

    Route::get('schedules', [ScheduleController::class, 'index']);
    Route::get('schedules/{id}', [ScheduleController::class, 'show']);
    Route::post('schedules', [ScheduleController::class, 'store']);
    Route::put('schedules/{id}', [ScheduleController::class, 'update']);
    Route::delete('schedules/{id}', [ScheduleController::class, 'destroy']);
    Route::post('notifications/test', [NotificationController::class, 'send']);
});

Route::post('login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->post('logout', [AuthController::class, 'logout']);
