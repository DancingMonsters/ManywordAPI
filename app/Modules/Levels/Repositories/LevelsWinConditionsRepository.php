<?php

namespace App\Modules\Levels\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class LevelsWinConditionsRepository
{
    /**
     * Получить условия победы уровня по его id
     * @param int $levelID
     * @return Model|Builder|object|null
     */
    public function getByLevelId(int $levelID)
    {
        return DB::table('levels_win_conditions')
            ->select('type', 'value', 'id')
            ->where('level_id', '=', $levelID)
            ->first();
    }

    /**
     * Обновление условий победы по id
     * @param int $winID
     * @param array $values
     */
    public function updateById(int $winID, array $values)
    {
        DB::table('levels_win_conditions')
            ->where('id', '=', $winID)
            ->update($values);
    }

    /**
     * @param array $values
     */
    public function addConditions(array $values)
    {
        DB::table('levels_win_conditions')
            ->insert($values);
    }

    public function deleteConditionsByLevelId(int $levelId)
    {
        DB::table('levels_win_conditions')
            ->where('level_id', '=', $levelId)
            ->delete();
    }
}
