<?php

namespace App\Modules\Services;

use App\Modules\Services\Services\LanguagesAlphabetService;
use App\Modules\Services\Services\LanguagesWeightsService;
use App\Modules\Services\Services\LevelsTypesService;
use App\Modules\Services\Services\DateService;
use App\Modules\Services\Services\LanguagesService;
use App\Modules\Services\Services\LevelsWinConditionsTypesService;
use App\Modules\Services\Services\ParticlesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ServicesController
{
    /**
     * Получить текущую дату в unix формате
     * @param DateService $dateService
     * @return JsonResponse
     */
    public function getDate(DateService $dateService): JsonResponse
    {
        return response()->json($dateService->getCurrentDate());
    }

    /**
     * Получить языки
     * @param LanguagesService $languagesService
     * @return JsonResponse
     */
    public function getLanguages(LanguagesService $languagesService): JsonResponse
    {
        return response()->json($languagesService->getLanguages());
    }

    /**
     * Получить типы уровней
     * @param LevelsTypesService $levelsTypesService
     * @param Request $request
     * @return JsonResponse
     */
    public function getLevelsTypes(LevelsTypesService $levelsTypesService, Request $request): JsonResponse
    {
        return response()->json($levelsTypesService->getLevelsTypes($request));
    }

    /**
     * Получить типы условий победы
     * @param LevelsWinConditionsTypesService $levelsWinConditionsTypesService
     * @param Request $request
     * @return JsonResponse
     */
    public function getLevelsWinConditionsTypes(LevelsWinConditionsTypesService $levelsWinConditionsTypesService, Request $request): JsonResponse
    {
        return response()->json($levelsWinConditionsTypesService->getLevelsWinConditionsTypes($request));
    }

    /**
     * Получение алфавита
     * @param LanguagesAlphabetService $alphabetService
     * @param Request $request
     * @return JsonResponse
     */
    public function getAlphabet(LanguagesAlphabetService $alphabetService, Request $request): JsonResponse
    {
        return response()->json($alphabetService->get($request));
    }

    /**
     * Получение частей речи
     * @param ParticlesService $particlesService
     * @param Request $request
     * @return JsonResponse
     */
    public function getParticles(ParticlesService $particlesService, Request $request): JsonResponse
    {
        return response()->json($particlesService->get($request));
    }

    /**
     * Получение весов
     * @param LanguagesWeightsService $languagesService
     * @param Request $request
     * @return JsonResponse
     */
    public function getWeights(LanguagesWeightsService $languagesService, Request $request): JsonResponse
    {
        return response()->json($languagesService->get($request));
    }
}
