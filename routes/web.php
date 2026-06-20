<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\JobController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ResumeController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => redirect()->route('dashboard'));

Route::middleware(['auth', 'track.activity'])->group(function () {

    // Job creation is the landing page after login
    Route::get('/dashboard', [JobController::class, 'create'])->name('dashboard');

    // Admin
    Route::get('/admin', [AdminController::class, 'index'])->middleware('admin')->name('admin.dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Jobs
    Route::get('jobs', [JobController::class, 'index'])->name('jobs.index');
    Route::post('jobs', [JobController::class, 'store'])->name('jobs.store');
    Route::get('jobs/{job}', [JobController::class, 'show'])->name('jobs.show');
    Route::delete('jobs/{job}', [JobController::class, 'destroy'])->name('jobs.destroy');

    // Resumes (nested under jobs for create/store, standalone for show/destroy)
    Route::get('jobs/{job}/resumes/create', [ResumeController::class, 'create'])->name('resumes.create');
    Route::post('jobs/{job}/resumes', [ResumeController::class, 'store'])->name('resumes.store');
    Route::get('resumes/{resume}', [ResumeController::class, 'show'])->name('resumes.show');
    Route::delete('resumes/{resume}', [ResumeController::class, 'destroy'])->name('resumes.destroy');
});

require __DIR__.'/auth.php';
