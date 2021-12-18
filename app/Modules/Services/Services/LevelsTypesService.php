<?php

namespace App\Modules\Services\Services;

use App\Modules\Services\Repositories\LevelsTypesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LevelsTypesService
{
    /**
     * Получить типы уровней
     * @param Request $request
     * @return Collection
     */
    public function getLevelsTypes(Request $request): Collection
    {
        return (new LevelsTypesRepository())->getTypes($request->query('language'));
    }
}
