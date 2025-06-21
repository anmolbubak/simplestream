<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FolderController;
use App\Http\Controllers\InstallerController;
use App\Http\Controllers\VideoController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

// Check if the application is installed
$installed = File::exists(storage_path('installed'));

// Installer Routes (only available if not installed)
if (!$installed) {
    Route::prefix('install')->group(function () {
        Route::get('/', [InstallerController::class, 'showWelcome'])->name('installer.welcome');
        Route::get('/requirements', [InstallerController::class, 'showRequirements'])->name('installer.requirements');
        Route::get('/database', [InstallerController::class, 'showDatabaseSetup'])->name('installer.database');
        Route::post('/database', [InstallerController::class, 'setupDatabase'])->name('installer.database.setup');
        Route::get('/admin', [InstallerController::class, 'showAdminSetup'])->name('installer.admin');
        Route::post('/admin', [InstallerController::class, 'setupAdmin'])->name('installer.admin.setup');
        Route::get('/complete', [InstallerController::class, 'showComplete'])->name('installer.complete');
    });
    
    // Redirect all other routes to installer
    Route::any('{any}', function () {
        return redirect()->route('installer.welcome');
    })->where('any', '.*');
} else {
    // Authentication Routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Protected Routes
    Route::middleware(['auth'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::resource('folders', FolderController::class);
        Route::resource('videos', VideoController::class);
    });

    // Redirect root to dashboard or login
    Route::redirect('/', '/login');
}
