<?php

namespace App\Modules\Levels;

use App\Modules\Levels\Services\LevelsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LevelsController
{
    /**
     * Получение уровней
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        return response()->json((new LevelsService())->get($request));
    }

    /**
     * Получение уровня по id
     * @param int $id
     * @return JsonResponse
     */
    public function getById(int $id): JsonResponse
    {
        return response()->json((new LevelsService())->getById($id));
    }

    /**
     * Обновление уровня по id
     * @param Request $request
     * @param int|null $id
     */
    public function setById(Request $request, int $id = null)
    {
        (new LevelsService())->setById($request, $id);
    }
}
