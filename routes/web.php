<?php

use App\Http\Controllers\DatenschutzController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImpressumController;
use App\Http\Controllers\StateController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name("home");
Route::get('/impressum', ImpressumController::class)->name('impressum');
Route::get('/datenschutz', DatenschutzController::class)->name('datenschutz');
Route::redirect('/github', 'https://github.com/Timeox2k/sindheuteferien.de')->name('github');

// Achtung, diese Route muss als letzte definiert werden, da sie alle anderen Routen abfängt
Route::get('/{route}', StateController::class)->where('route', '[a-z\-]+')->name('bundesland');
