<?php

use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/dashboard', [Controller::class, 'dashboard'])->name('dashboard');

    Route::post('department/print', [DepartmentController::class, 'print'])->name('department.print');
    Route::get('department/delete-modal/{id}', [DepartmentController::class, 'deleteModal'])->name('department.delete-modal');
    Route::post('department/export', [DepartmentController::class, 'export'])->name('department.export');
    Route::resource('department', DepartmentController::class);

    Route::post('user/print', [UserController::class, 'print'])->name('user.print');
    Route::get('user/delete-modal/{id}', [UserController::class, 'deleteModal'])->name('user.delete-modal');
    Route::post('user/export', [UserController::class, 'export'])->name('user.export');
    Route::resource('user', UserController::class);

    Route::post('announcement/print', [AnnouncementController::class, 'print'])->name('announcement.print');
    Route::get('announcement/delete-modal/{id}', [AnnouncementController::class, 'deleteModal'])->name('announcement.delete-modal');
    Route::post('announcement/export', [AnnouncementController::class, 'export'])->name('announcement.export');
    Route::resource('announcement', AnnouncementController::class);

    Route::get('log', [LogController::class, 'index'])->name('log.index');
    Route::post('log/print', [LogController::class, 'print'])->name('log.print');
    Route::post('log/export', [LogController::class, 'export'])->name('log.export');
});
