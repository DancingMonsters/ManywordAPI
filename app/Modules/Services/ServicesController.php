<?php

namespace App\Modules\Services;

use App\Modules\Services\Services\LevelsTypesService;
use App\Modules\Services\Services\DateService;
use App\Modules\Services\Services\LanguagesService;
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
}
