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
    public function setById(Request $request, ?int $id = null)
    {
        (new LevelsService())->setById($request, $id);
    }

    public function deleteLevel(int $id)
    {
        (new LevelsService())->deleteLevel($id);
    }

    /**
     * Получение следующего уровня
     * @param int $id
     * @param Request $request
     * @return JsonResponse
     */
    public function next(int $id, Request $request): JsonResponse
    {
        return response()->json((new LevelsService())->nextLevelById($id, $request));
    }

    public function checkWord(int $id, Request $request): JsonResponse
    {
        return response()->json((new LevelsService())->checkWord($id, $request));
    }
}
