<?php

use App\Http\Controllers\CityController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProfileController;
use App\Models\Notification;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    } else {
        return redirect()->route('login');
    }
});

Route::group(['middleware' => ['auth', 'verified']], function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard', [
            'lastNotification' => Notification::where('send', true)->latest()->first()
        ]);
    })->name('dashboard');

    Route::resource('notification', NotificationController::class)->except(['edit']);
    Route::post('send-notification', [NotificationController::class, 'sendNotification']);

    Route::prefix('cities')->group(function() {
        Route::get('', [CityController::class, 'index'])->name('city');
        Route::get('{city}/locations', [LocationController::class, 'index'])->name('locations');
        Route::get('{city}/locations/create', [LocationController::class, 'create']);
        Route::post('{city}/locations', [LocationController::class, 'store']);
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
