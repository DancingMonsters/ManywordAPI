<?php

namespace App\Modules\Services\Services;

use App\Modules\Services\Repositories\LanguagesWeightsRepository;
use Illuminate\Http\Request;

class LanguagesWeightsService
{
    /**
     * Получение весов языка
     * @param Request $request
     * @return mixed
     */
    public function get(Request $request)
    {
        $weights = (new LanguagesWeightsRepository())->get($request->query('language', 1));
        return json_decode($weights);
    }
}
