<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\levelsController;
use App\Http\Controllers\dictionaryController;
use App\Http\Controllers\AlphabetsController;
use App\Http\Controllers\WeightsController;

Route::prefix('create') -> group(function() {
    Route::post('dictionary/{language}', [dictionaryController::class, 'create']) -> name("wordAdd");
    Route::post('levels/{language}', [levelsController::class, 'create']) -> name("levelAdd");
});

Route::prefix('edit') -> group(function() {
    Route::post('dictionary/{language}/{id}', [dictionaryController::class, 'edit']) -> name ("wordEdit");
    Route::post('levels/{language}/{id}', [levelsController::class, 'edit']) -> name ("levelEdit");
});

Route::prefix('destroy') -> group(function() {
   Route::get('dictionary/{language}/{id}', [dictionaryController::class, 'destroy']) -> name ("wordDestroy");
   Route::get('levels/{language}/{id}', [levelsController::class, 'destroy']) -> name("levelDestroy");
});

Route::prefix('dictionary') -> group(function() {
    Route::get('{language}', [dictionaryController::class, 'index']) -> name ("words");
    Route::get('{language}/pages/{size}', [dictionaryController::class, 'getPagesCount']);
    Route::get('{language}/{id}', [dictionaryController::class, 'show']) -> name("wordShow");
});

Route::prefix('levels') -> group(function() {
    Route::get('{language}', [levelsController::class, 'index']);
    Route::get('{language}/{id}', [levelsController::class, 'show']);
});

Route::prefix('alphabet') -> group(function() {
    Route::post('{language}/add', [AlphabetsController::class, 'add']);
    Route::get('{language}', [AlphabetsController::class, 'get']);
});

Route::prefix('weights') -> group(function() {
    Route::post('{language}/add', [WeightsController::class, 'add']);
    Route::get('{language}', [WeightsController::class, 'get']);
});

Route::get('send', [levelsController::class, 'send']);

// Route::get('generate', [levelsController::class, 'generate']);


