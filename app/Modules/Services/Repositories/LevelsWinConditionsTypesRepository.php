<?php

namespace App\Modules\Services\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LevelsWinConditionsTypesRepository
{
    /**
     * Получение типов условий победы
     * @param int $language
     * @return Collection
     */
    public function getLevelsWinConditionsTypes(int $language): Collection
    {
        return DB::table('levels_win_condition_types', 'types')
            ->select('types.id', 'types_names.name')
            ->leftJoin('levels_win_condition_types_names as types_names', 'types_names.type_id', '=', 'types.id')
            ->where('types_names.language', '=', $language)
            ->get();
    }
}
