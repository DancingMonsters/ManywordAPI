<?php

namespace App\Modules\Levels\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class LevelsWinConditionsRepository
{
    private string $table = 'levels_win_conditions';

    /**
     * Получить условия победы уровня по его id
     * @param int $levelID
     * @return Model|Builder|object|null
     */
    public function getByLevelId(int $levelID)
    {
        return DB::table($this->table)
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
        DB::table($this->table)
            ->where('id', '=', $winID)
            ->update($values);
    }

    /**
     * @param array $values
     */
    public function addConditions(array $values)
    {
        DB::table($this->table)
            ->insert($values);
    }
}
