<?php

namespace App\Modules\Levels\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LevelsWordsRepository
{
    private string $table = 'levels_words';

    /**
     * Получение слов уровня
     * @param int $levelId
     * @return Collection
     */
    public function getByLevelId(int $levelId): Collection
    {
        return DB::table($this->table)
            ->select('words.*')
            ->leftJoin('words', 'words.id', '=', $this->table . '.word_id')
            ->where($this->table . '.level_id', '=', $levelId)
            ->get();
    }

    /**
     * Добавление слова по id уровня
     * @param int $levelId
     * @param int $wordId
     */
    public function addByLevelId(int $levelId, int $wordId)
    {
        DB::table($this->table)
            ->insert(['level_id' => $levelId, 'word_id' => $wordId]);
    }

    /**
     * Удаление слова по id уровня и слова
     * @param int $levelId
     * @param int $wordId
     */
    public function deleteByLevelId(int $levelId, int $wordId)
    {
        DB::table($this->table)
            ->where('word_id', '=', $wordId)
            ->where('level_id', '=', $levelId)
            ->delete();
    }
}
