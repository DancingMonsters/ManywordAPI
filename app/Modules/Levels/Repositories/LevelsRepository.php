<?php

namespace App\Modules\Levels\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LevelsRepository
{
    /**
     * Получение уровней
     * @param array $fields
     * @param int $language
     * @param int $historyID
     * @param int $published
     * @return Collection
     */
    public function get(array $fields, int $language, int $historyID, int $published): Collection
    {
        return DB::table('levels')
            ->select($fields)
            ->where('history_id', '=', $historyID)
            ->where('language', '=', $language)
            ->where('published', '=', $published)
            ->get();
    }

    /**
     * Получение уровня по id
     * @param int $levelID
     * @return Model|Builder|object|null
     */
    public function getById(int $levelID) {
        return DB::table('levels')
            ->select('levels.*')
            ->where('id', '=', $levelID)
            ->first();
    }
}
