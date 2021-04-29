<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\levelsController;
use App\Http\Controllers\dictionaryController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::prefix('levels') -> group(function() {
    Route::get('{language}', [levelsController::class, 'index']);
    Route::get('{language}/{id}', [levelsController::class, 'show']);
});

Route::prefix('dictionary') -> group(function() {
    Route::get('{language}', [dictionaryController::class, 'index']) -> name ("words");
    Route::get('{language}/{id}', [dictionaryController::class, 'show']) -> name("wordShow");
    Route::post('create', [dictionaryController::class, 'create']) -> name("wordAdd");
});


