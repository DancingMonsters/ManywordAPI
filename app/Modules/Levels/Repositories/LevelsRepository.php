<?php

namespace App\Modules\Levels\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LevelsRepository
{
    private string $table = 'levels';

    /**
     * Получение уровней
     * @param array $fields
     * @param int|null $language
     * @param int|null $historyID
     * @param bool $published
     * @return Collection
     */
    public function get(array $fields, int $language = null, int $historyID = null, bool $published = null): Collection
    {
        return DB::table($this->table)
            ->select($fields)
            ->when($language !== null, function ($query) use ($language) {
                $query->where('language', '=', $language);
            })
            ->when($historyID !== null, function ($query) use ($historyID) {
                $query->where('history_id', '=', $historyID);
            })
            ->when($published !== null, function ($query) use ($published) {
                $query->where('published', '=', $published);
            })
            ->get();
    }

    /**
     * Получение уровня по id
     * @param int $levelID
     * @return Model|Builder|object|null
     */
    public function getById(int $levelID) {
        return DB::table($this->table)
            ->select('*')
            ->where('id', '=', $levelID)
            ->first();
    }

    /**
     * Редактирование уровня по id
     * @param int $levelID
     * @param array $values
     * @return int
     */
    public function set(int $levelID, array $values): int
    {
        return DB::table($this->table)
            ->where('id', '=', $levelID)
            ->update($values);
    }
}
