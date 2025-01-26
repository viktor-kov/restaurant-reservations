<?php

use App\Http\Controllers\Admin\Reservation\DeleteReservationController;
use App\Http\Controllers\Admin\Reservation\ListReservationController;
use App\Http\Controllers\Admin\Reservation\ShowReservationController;
use App\Http\Controllers\Page\HomepageController;
use App\Http\Controllers\ProfileController;
use App\Http\Middleware\IsAdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', HomepageController::class)->name('homepage');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::group(['middleware' => ['auth', IsAdminMiddleware::class], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/reservations', ListReservationController::class)->name('reservations.list');
    Route::delete('/reservations/{reservation:uuid}', DeleteReservationController::class)->name('reservations.delete');
    Route::get('/reservations/{reservation:uuid}', ShowReservationController::class)->name('reservations.show');
});

require __DIR__.'/auth.php';
