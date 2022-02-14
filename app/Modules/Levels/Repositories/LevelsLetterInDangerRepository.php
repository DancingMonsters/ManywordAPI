<?php

namespace App\Modules\Levels\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class LevelsLetterInDangerRepository
{
    /**
     * Получение букв в опасности по id уровня
     * @param int $levelId
     * @return Model|Builder|object|null
     */
    public function getByLevelId(int $levelId)
    {
        return DB::table('levels_letter_in_danger')
            ->select('count', 'weights', 'time')
            ->where('level_id', '=', $levelId)
            ->first();
    }
}
