<?php

namespace App\Modules\Levels\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class LevelsWinConditionsRepository
{
    /**
     * Получить условия победы уровня
     * @param int $levelID
     * @return Model|Builder|object|null
     */
    public function getByLevelId(int $levelID)
    {
        return DB::table('levels_win_conditions')
            ->select('type', 'value')
            ->where('level_id', '=', $levelID)
            ->first();
    }
}
