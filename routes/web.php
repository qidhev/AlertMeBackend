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

Route::group(['middleware' => ['auth']], function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard', [
            'lastNotification' => Notification::where('send', true)->orderBy('updated_at', 'DESC')->first()
        ]);
    })->name('dashboard');

    Route::resource('notification', NotificationController::class)->except(['edit']);
    Route::post('send-notification', [NotificationController::class, 'sendNotification']);
    Route::resource('cities', CityController::class)->only(['index', 'create', 'store']);

    Route::prefix('cities')->group(function() {
        Route::get('{city}/locations', [LocationController::class, 'index'])->name('locations.index');
        Route::get('{city}/locations/create', [LocationController::class, 'create'])->name('locations.create');
        Route::post('{city}/locations', [LocationController::class, 'store'])->name('locations.store');
        Route::get('{city}/locations/{location}/edit', [LocationController::class, 'edit'])->name('locations.edit');
        Route::put('{city}/locations/{location}', [LocationController::class, 'update'])->name('locations.update');
        Route::delete('{city}/locations/{location}', [LocationController::class, 'destroy'])->name('locations.destroy');
    });
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
