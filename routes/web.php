<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Web\EmployeeController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::prefix('employee')->name('employee.')->group(function () {
        Route::get('/attendance', [EmployeeController::class, 'attendanceForm'])->name('attendance');
        Route::post('/attendance/check-in', [EmployeeController::class, 'checkIn'])->name('check-in');
        Route::post('/attendance/check-out', [EmployeeController::class, 'checkOut'])->name('check-out');

        Route::get('/leave-requests', [EmployeeController::class, 'leaveRequests'])->name('leave-requests');
        Route::get('/leave-requests/create', [EmployeeController::class, 'createLeaveRequest'])->name('leave-requests.create');
        Route::post('/leave-requests', [EmployeeController::class, 'storeLeaveRequest'])->name('leave-requests.store');

        Route::get('/absences', [EmployeeController::class, 'absences'])->name('absences');
    });
});

require __DIR__ . '/auth.php';
