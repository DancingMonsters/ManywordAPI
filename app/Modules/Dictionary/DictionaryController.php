<?php

namespace App\Modules\Dictionary;

use App\Modules\Dictionary\FormServices\DictionaryFormService;
use App\Modules\Dictionary\Services\DictionaryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DictionaryController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        return response()->json((new DictionaryService())->get($request));
    }

    /**
     * Добавление слова
     * @param Request $request
     * @return JsonResponse
     */
    public function createWord(Request $request): JsonResponse
    {
        $formService = new DictionaryFormService($request);
        if (!$formService->validate()) {
            return response()->json(['error' => 1, 'messages' => $formService->messages]);
        } else {
            return response()->json((new DictionaryService())->createWord($request));
        }
    }
}
