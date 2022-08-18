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
        DB::table('levels_letter_in_danger')
            ->where('id', '=', $id)
            ->update($values);
    }

    /**
     * @param array $values
     */
    public function addLetterInDanger(array $values)
    {
        DB::table('levels_letter_in_danger')
            ->insert($values);
    }

    public function deleteLetterInDangerByLevelId(int $levelId)
    {
        DB::table('levels_letter_in_danger')
            ->where('level_id', '=', $levelId)
            ->delete();
    }
}
