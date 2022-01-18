<?php

namespace App\Modules\Dictionary;

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
}
