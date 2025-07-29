<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\FaqController;

Route::get('/faq', [FaqController::class, 'index'])->name('faq.index');

Route::post('/lang', LanguageController::class);