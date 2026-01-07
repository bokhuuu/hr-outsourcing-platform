<?php

use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\LeaveRequestController;
use App\Http\Controllers\Api\VacancyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::prefix('v1/public')->group(function () {
    Route::get('/vacancies', [VacancyController::class, 'index']);
    Route::get('/vacancies/{vacancy}', [VacancyController::class, 'show']);
});

Route::prefix('v1')->middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    Route::post('/attendances/check-in', [AttendanceController::class, 'checkIn']);
    Route::post('/attendances/check-out', [AttendanceController::class, 'checkOut']);
    Route::get('/attendances', [AttendanceController::class, 'index']);

    Route::post('/leave-requests', [LeaveRequestController::class, 'store']);
    Route::get('/leave-requests', [LeaveRequestController::class, 'index']);

    Route::middleware('role:hr,company_admin')->group(function () {
        Route::put('/leave-requests/{leaveRequest}/approve', [LeaveRequestController::class, 'approve']);
        Route::put('/leave-requests/{leaveRequest}/reject', [LeaveRequestController::class, 'reject']);
    });

    Route::middleware('role:hr')->group(function () {
        Route::post('/companies/{company}/vacancies', [VacancyController::class, 'store']);
    });
});
