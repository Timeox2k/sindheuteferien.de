<?php

use App\Http\Controllers\DatenschutzController;
use App\Http\Controllers\HolidayDetailController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ImpressumController;
use App\Http\Controllers\RobotsController;
use App\Http\Controllers\SitemapController;
use App\Http\Controllers\StateController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name("home");
Route::get('/impressum', ImpressumController::class)->name('impressum');
Route::get('/datenschutz', DatenschutzController::class)->name('datenschutz');
Route::get('/sitemap.xml', SitemapController::class)->name('sitemap');
Route::get('/robots.txt', RobotsController::class)->name('robots');
Route::redirect('/github', 'https://github.com/Timeox2k/sindheuteferien.de')->name('github');

Route::get('/{bundesland}', StateController::class)->where('bundesland', '[a-z\-]+')->name('bundesland');
Route::get('/{bundesland}/{ferien}', HolidayDetailController::class)->where([
    'bundesland' => '[a-z\-]+',
    'ferien'     => '[a-z0-9\-]+',
])->name('holiday.detail');
