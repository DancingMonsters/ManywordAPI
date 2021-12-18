<?php

namespace App\Modules\Services\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LevelsTypesRepository
{
    /**
     * Получение типов уровней
     * @param int $language
     * @return Collection
     */
    public function getTypes(int $language): Collection
    {
        return DB::table('levels_types')
            ->select('levels_types.*', 'levels_types_names.name as man_name')
            ->leftJoin('levels_types_names', 'levels_types_names.type_id', '=', 'levels_types.id')
            ->where('levels_types_names.language', '=', $language)
            ->get();
    }
}
