<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\TelegramActivationController;
use App\Http\Controllers\TelegramWebhookController;
use Illuminate\Support\Facades\Route;

Route::get('/', [AuthController::class, 'showLogin'])->name('login');
Route::post('/', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::post('/telegram/webhook', TelegramWebhookController::class);

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/telegram/activate', [TelegramActivationController::class, 'activate'])->name('telegram.activate');

    // Siswa Routes
    Route::post('/permissions', [PermissionController::class, 'store'])->name('permissions.store');
    
    // Lecturer Routes for Siswa
    Route::get('/lecturers', [App\Http\Controllers\LecturerController::class, 'index'])->name('lecturers.index');
    Route::post('/lecturers/{lecturer}/contact', [App\Http\Controllers\LecturerController::class, 'contact'])->name('lecturers.contact');
    Route::get('/lecturers/{lecturer}/contact/status', [App\Http\Controllers\LecturerController::class, 'checkContactStatus'])->name('lecturers.contact.status');

    // Admin Routes
    Route::middleware(['can:admin-only'])->group(function () {
        Route::get('/admin/permissions', [PermissionController::class, 'index'])->name('admin.permissions.index');
        Route::get('/admin/permissions/export', [PermissionController::class, 'export'])->name('admin.permissions.export');
        Route::patch('/admin/permissions/{permission}', [PermissionController::class, 'updateStatus'])->name('admin.permissions.update');
        Route::delete('/admin/permissions/{permission}', [PermissionController::class, 'destroy'])->name('admin.permissions.destroy');

        // Lecturer Management
        Route::get('/admin/lecturers', [App\Http\Controllers\Admin\LecturerController::class, 'index'])->name('admin.lecturers.index');
        Route::post('/admin/lecturers', [App\Http\Controllers\Admin\LecturerController::class, 'store'])->name('admin.lecturers.store');
        Route::patch('/admin/lecturers/{lecturer}', [App\Http\Controllers\Admin\LecturerController::class, 'update'])->name('admin.lecturers.update');
        Route::delete('/admin/lecturers/{lecturer}', [App\Http\Controllers\Admin\LecturerController::class, 'destroy'])->name('admin.lecturers.destroy');

        // Student Management
        Route::get('/admin/students', [App\Http\Controllers\Admin\StudentController::class, 'index'])->name('admin.students.index');
        Route::post('/admin/students', [App\Http\Controllers\Admin\StudentController::class, 'store'])->name('admin.students.store');
        Route::patch('/admin/students/{student}', [App\Http\Controllers\Admin\StudentController::class, 'update'])->name('admin.students.update');
        Route::delete('/admin/students/{student}', [App\Http\Controllers\Admin\StudentController::class, 'destroy'])->name('admin.students.destroy');
        Route::post('/admin/students/import', [App\Http\Controllers\Admin\StudentController::class, 'import'])->name('admin.students.import');

        // Subject Management
        Route::get('/admin/subjects', [App\Http\Controllers\Admin\SubjectController::class, 'index'])->name('admin.subjects.index');
        Route::post('/admin/subjects', [App\Http\Controllers\Admin\SubjectController::class, 'store'])->name('admin.subjects.store');
        Route::patch('/admin/subjects/{subject}', [App\Http\Controllers\Admin\SubjectController::class, 'update'])->name('admin.subjects.update');
        Route::delete('/admin/subjects/{subject}', [App\Http\Controllers\Admin\SubjectController::class, 'destroy'])->name('admin.subjects.destroy');

        // User Management
        Route::get('/admin/users', [App\Http\Controllers\Admin\UserController::class, 'index'])->name('admin.users.index');
        Route::patch('/admin/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'update'])->name('admin.users.update');
        Route::delete('/admin/users/{user}', [App\Http\Controllers\Admin\UserController::class, 'destroy'])->name('admin.users.destroy');
        
        // Report Routes
        Route::get('/admin/reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])->name('admin.reports.index');
        Route::get('/admin/reports/export', [App\Http\Controllers\Admin\ReportController::class, 'export'])->name('admin.reports.export');
        
        // Tracking Real-Time
        Route::get('/admin/tracking', [App\Http\Controllers\Admin\TrackingController::class, 'index'])->name('admin.tracking.index');
        Route::post('/admin/tracking/{user}/toggle', [App\Http\Controllers\Admin\TrackingController::class, 'toggleAccess'])->name('admin.tracking.toggle');

        // Settings
        Route::get('/admin/settings', [App\Http\Controllers\Admin\SettingController::class, 'index'])->name('admin.settings.index');
        Route::post('/admin/settings', [App\Http\Controllers\Admin\SettingController::class, 'update'])->name('admin.settings.update');
    });

    // API-like routes for frontend polling
    Route::post('/session/check', [App\Http\Controllers\Auth\SessionCheckController::class, 'check'])->name('session.check');
    Route::post('/onboarding/complete', [App\Http\Controllers\Student\OnboardingController::class, 'complete'])->name('onboarding.complete');
});
