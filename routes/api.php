<?php

use App\Http\Controllers\Api\PublicSiteController;
use Illuminate\Support\Facades\Route;

Route::prefix('public')->group(function () {
    Route::get('/site-shell', [PublicSiteController::class, 'shell']);
    Route::get('/homepage', [PublicSiteController::class, 'homepage']);
});
