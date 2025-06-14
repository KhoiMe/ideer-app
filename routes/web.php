<?php

use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

// controllers
use App\Http\Controllers\IdeasController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('ideas', [IdeasController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('ideas');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
});

require __DIR__ . '/auth.php';
