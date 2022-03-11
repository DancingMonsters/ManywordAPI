<?php

namespace App\Modules\Levels\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class LevelsLetterInDangerRepository
{
    private string $table = "levels_letter_in_danger";

    /**
     * Получение букв в опасности по id уровня
     * @param int $levelId
     * @return Model|Builder|object|null
     */
    public function getByLevelId(int $levelId)
    {
        return DB::table($this->table)
            ->select('count', 'weights', 'time', 'id')
            ->where('level_id', '=', $levelId)
            ->first();
    }

    /**
     * Обновление букв в опасности по id
     * @param int $id
     * @param array $values
     */
    public function updateById(int $id, array $values)
    {
        DB::table($this->table)
            ->where('id', '=', $id)
            ->update($values);
    }
}
