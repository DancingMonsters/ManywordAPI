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
     * @param int|null $language
     * @param int|null $historyID
     * @param bool|null $published
     * @return Collection
     */
    public function get(array $fields, int $language = null, int $historyID = null, bool $published = null): Collection
    {
        return DB::table('levels')
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
        return DB::table('levels')
            ->select('*')
            ->where('id', '=', $levelID)
            ->first();
    }

    /**
     * Редактирование уровня по id
     * @param array $values
     * @param int $levelId
     * @return int
     */
    public function set(array $values, int $levelId): int
    {
        return DB::table('levels')
            ->where('id', '=', $levelId)
            ->update($values);
    }

    /**
     * @param array $values
     * @return int
     */
    public function add(array $values): int
    {
        return DB::table('levels')
            ->insertGetId($values);
    }

    public function delete(int $levelId)
    {
        DB::table('levels')
            ->delete($levelId);
    }

    public function getNextLevelById(int $id, int $language)
    {
        dd($id);
        return DB::table('levels')
            ->select('levels.*', 'particles.name as particles_name')
            ->leftJoin('particles', 'particles.id', '=', 'levels.particles')
            ->where('levels.id', '>', $id)
            ->where('levels.language', $language)
            ->where('levels.published', 1)
            ->first();
    }
}
