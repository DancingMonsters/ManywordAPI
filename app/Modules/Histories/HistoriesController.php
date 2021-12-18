<?php

namespace App\Modules\Histories;

use App\Modules\Histories\Services\HistoriesService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class HistoriesController
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get(Request $request): JsonResponse
    {
        return response()->json((new HistoriesService())->get($request));
    }
}
