<?php

use App\Http\Controllers\AttendanceSettingsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'show'])->name('login');
    Route::post('/login', [LoginController::class, 'login'])->name('login.post');
    Route::get('/register', [RegisterController::class, 'show'])->name('register');
    Route::post('/register', [RegisterController::class, 'register'])->name('register.post');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/leaderboard', [LeaderboardController::class, 'index'])->name('leaderboard');
});

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');
    Route::get('/dashboard/data', [AdminController::class, 'dashboardData'])->name('dashboard.data');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::post('/users/{user}/toggle-admin', [AdminController::class, 'toggleAdmin'])->name('toggle-admin');
    Route::post('/notifications/send', [AdminController::class, 'sendNotification'])->name('notifications.send');
    Route::get('/users/list', [AdminController::class, 'getUsersList'])->name('users.list');
    Route::get('/daily-attendance', [AdminController::class, 'getDailyAttendance'])->name('daily-attendance');
    Route::get('/leaderboard', [AdminController::class, 'leaderboard'])->name('leaderboard');
    Route::get('/devices', [AdminController::class, 'devices'])->name('devices');
    Route::post('/devices', [AdminController::class, 'storeDevice'])->name('devices.store');
    Route::post('/devices/{device}/register', [AdminController::class, 'registerDevice'])->name('devices.register');
    Route::delete('/devices/{device}', [AdminController::class, 'deleteDevice'])->name('devices.delete');
    Route::get('/attendance-logs', [AdminController::class, 'attendanceLogs'])->name('attendance.logs');
    Route::get('/attendance-settings', [AdminController::class, 'attendanceSettings'])->name('attendance.settings');
    Route::post('/attendance-settings', [AdminController::class, 'updateAttendanceSettings'])->name('attendance.settings.update');
    Route::post('/admin/attendance-settings/reset', [AttendanceSettingsController::class, 'reset'])->name('attendance.reset');
    Route::get('/register-user', [AdminController::class, 'showRegisterForm'])->name('users.create');
    Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
});

Route::get('/admin/dashboard/data', [AdminController::class, 'dashboardData'])
    ->name('admin.dashboard.data')
    ->middleware(['auth', 'admin']);
