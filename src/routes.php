<?php

use Rinordreshaj\Localization\Controllers\LanguagesController;
use Rinordreshaj\Localization\Controllers\TranslationsController;

Route::get('localizations/languages', [LanguagesController::class, 'index']);
Route::post('localizations/languages', [LanguagesController::class, 'store']);
Route::put('localizations/languages/{language}', [LanguagesController::class, 'update']);
Route::delete('localizations/languages/{language}', [LanguagesController::class, 'destroy']);

Route::get('localizations/translations', [TranslationsController::class, 'index']);
Route::post('localizations/translations', [TranslationsController::class, 'store']);
Route::put('localizations/translations/{translation}', [TranslationsController::class, 'update']);
Route::delete('localizations/translations/{translation}', [TranslationsController::class, 'destroy']);
