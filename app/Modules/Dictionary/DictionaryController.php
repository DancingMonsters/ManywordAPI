<?php

namespace App\Modules\Dictionary;

use App\Modules\Dictionary\FormServices\DictionaryFormService;
use App\Modules\Dictionary\Services\DictionaryService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DictionaryController
{
    private DictionaryService $dictionaryService;

    public function __construct()
    {
        $this->dictionaryService = new DictionaryService();
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        return response()->json($this->dictionaryService->get($request));
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
            return response()->json($this->dictionaryService->createWord($request));
        }
    }

    /**
     * Проверка слова на существование
     * @param Request $request
     * @return JsonResponse
     */
    public function checkWord(Request $request): JsonResponse
    {
        return response()->json($this->dictionaryService->checkWord($request));
    }
}
