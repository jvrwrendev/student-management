<?php

use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\GradeController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\PortalController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportCardController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SubjectController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\TeacherDashboardController;
use App\Http\Controllers\TeacherStudentController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function (): void {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');

    Route::get('/', HomeController::class)->name('home');
    Route::redirect('home', '/');

    Route::get('/about', [PageController::class, 'about'])->name('about');
    Route::get('/contact', [PageController::class, 'contact'])->name('contact');
    Route::get('/posts/{post}', [PageController::class, 'show'])->name('posts.show');

    Route::middleware('role:admin')
        ->prefix('admin')
        ->group(function (): void {
            Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
            Route::resource('students', StudentController::class);
            Route::resource('courses', CourseController::class);
            Route::resource('subjects', SubjectController::class);
            Route::resource('teachers', TeacherController::class);
            Route::get('/students/{student}/spr', [StudentController::class, 'spr'])->name('students.spr');
            Route::get('/settings', [SettingController::class, 'index'])->name('settings.index');
            Route::put('/settings', [SettingController::class, 'update'])->name('settings.update');
        });

    Route::middleware('role:admin,teacher')->group(function (): void {
        Route::resource('schedules', ScheduleController::class);
        Route::get('report-cards/{student}', ReportCardController::class)->name('report-cards.show');
    });

    Route::middleware('role:teacher')->group(function (): void {
        Route::resource('grades', GradeController::class);
        Route::get('/teacher/students', [TeacherStudentController::class, 'index'])->name('teacher.students');
    });

    Route::middleware('role:teacher')
        ->get('/teacher/dashboard', [TeacherDashboardController::class, 'index'])
        ->name('teacher.dashboard');

    Route::middleware('role:student')->group(function (): void {
        Route::get('/portal', [PortalController::class, 'dashboard'])->name('portal.dashboard');
        Route::get('/portal/grades', [PortalController::class, 'grades'])->name('portal.grades');
        Route::get('/portal/schedule', [PortalController::class, 'schedule'])->name('portal.schedule');
    });
});
