<?php

use App\Modules\Dictionary\DictionaryController;
use App\Modules\Histories\HistoriesController;
use App\Modules\Levels\LevelsController;
use App\Modules\Services\ServicesController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AlphabetsController;
use App\Http\Controllers\WeightsController;

//Route::prefix('create')->group(function () {
//    Route::post('dictionary/{language}', [dictionaryController::class, 'create'])->name("wordAdd");
//    Route::post('levels/{language}', [LevelsController::class, 'create'])->name("levelAdd");
//});

//Route::prefix('edit')->group(function () {
//    Route::post('dictionary/{language}/{id}', [dictionaryController::class, 'edit'])->name("wordEdit");
//    Route::post('levels/{language}/{id}', [LevelsController::class, 'edit'])->name("levelEdit");
//});

//Route::prefix('destroy')->group(function () {
//    Route::get('dictionary/{language}/{id}', [dictionaryController::class, 'destroy'])->name("wordDestroy");
//    Route::get('levels/{language}/{id}', [LevelsController::class, 'destroy'])->name("levelDestroy");
//});

//Route::prefix('dictionary')->group(function () {
//    Route::get('{language}', [dictionaryController::class, 'index'])->name("words");
//    Route::get('{language}/pages/{size}', [dictionaryController::class, 'getPagesCount']);
//    Route::get('{language}/{id}', [dictionaryController::class, 'show'])->name("wordShow");
//});

Route::prefix('dictionary')->group(function () {
    Route::get('/', [DictionaryController::class, 'get']); //получение списка слов
    Route::patch('/', [DictionaryController::class, 'createWord']); //создание слова
    Route::get('/check_word', [DictionaryController::class, 'checkWord']); //проверка слова на существование
});

Route::prefix('histories')->group(function() {
    Route::get('/', [HistoriesController::class, 'get']); //получение списка историй
});

Route::prefix('levels')->group(function () {
    Route::get('/', [LevelsController::class, 'get']); //получение списка уровней
    Route::get('{id}', [LevelsController::class, 'getById'])->where('id', '[0-9]+'); //получение уровня
    Route::patch('{id?}', [LevelsController::class, 'setById'])->where('id', '[0-9]+'); //обновление уровня
    Route::delete('{id}', [LevelsController::class, 'deleteLevel'])->where('id', '[0-9]+'); //удаление уровня
});

Route::prefix('alphabet')->group(function () {
    Route::post('{language}/add', [AlphabetsController::class, 'add']); //добавление нового алфавита
    Route::get('{language}', [ServicesController::class, 'getAlphabet']); //получение алфавита
});

Route::prefix('weights')->group(function () {
    Route::post('{language}/add', [WeightsController::class, 'add']); //добавление весов букв для языка
    Route::get('{language}', [WeightsController::class, 'get']); //получение весов букв для языка
});

Route::prefix('services')->group(function () {
    Route::get('date', [ServicesController::class, 'getDate']);
    Route::get('languages', [ServicesController::class, 'getLanguages']);
    Route::get('levels/types', [ServicesController::class, 'getLevelsTypes']);
    Route::get('levels/conditions', [ServicesController::class, 'getLevelsWinConditionsTypes']);
    Route::get('languages/alphabet', [ServicesController::class, 'getAlphabet']);
    Route::get('languages/alphabet/weights', [ServicesController::class, 'getWeights']);
    Route::get('words/particles', [ServicesController::class, 'getParticles']);
});

Route::get('send', [LevelsController::class, 'send']);

// Route::get('generate', [levelsController::class, 'generate']);


