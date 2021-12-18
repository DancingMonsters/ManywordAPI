<?php

namespace App\Modules\Services\Services;

use App\Modules\Services\Repositories\LevelsWinConditionsTypesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LevelsWinConditionsTypesService
{
    /**
     * Получение типов условий победы
     * @param Request $request
     * @return Collection
     */
    public function getLevelsWinConditionsTypes(Request $request): Collection
    {
        return (new LevelsWinConditionsTypesRepository())->getLevelsWinConditionsTypes($request->query('language'));
    }
}
