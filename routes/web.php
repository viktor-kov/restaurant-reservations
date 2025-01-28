<?php

use App\Http\Controllers\Admin\Reservation\DeleteReservationController;
use App\Http\Controllers\Admin\Reservation\ListReservationController;
use App\Http\Controllers\Admin\Reservation\ShowReservationController;
use App\Http\Controllers\Customer\Reservation\ListReservationController as CustomerListReservationController;
use App\Http\Controllers\Customer\Reservation\DeleteReservationController as CustomerDeleteReservationController;
use App\Http\Controllers\Page\HomepageController;
use App\Http\Middleware\IsAdminMiddleware;
use Illuminate\Support\Facades\Route;

Route::get('/', HomepageController::class)->name('homepage');

Route::group(['middleware' => ['auth', IsAdminMiddleware::class], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::get('/reservations', ListReservationController::class)->name('reservations.list');
    Route::delete('/reservations/{reservation:uuid}', DeleteReservationController::class)->name('reservations.delete');
    Route::get('/reservations/{reservation:uuid}', ShowReservationController::class)->name('reservations.show');
});

Route::group(['middleware' => ['auth'], 'prefix' => 'customer', 'as' => 'customer.'], function () {
    Route::get('/reservations', CustomerListReservationController::class)->name('reservations.list');
    Route::delete('/reservations/{reservation:uuid}', CustomerDeleteReservationController::class)->name('reservations.delete');
});

require __DIR__.'/auth.php';
