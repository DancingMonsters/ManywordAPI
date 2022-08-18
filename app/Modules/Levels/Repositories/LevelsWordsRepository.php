<?php

namespace App\Modules\Levels\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class LevelsWordsRepository
{
    /**
     * Получение слов уровня
     * @param int $levelId
     * @return Collection
     */
    public function getByLevelId(int $levelId): Collection
    {
        return DB::table('levels_words')
            ->select('words.*')
            ->leftJoin('words', 'words.id', '=', 'levels_words' . '.word_id')
            ->where('levels_words' . '.level_id', '=', $levelId)
            ->get();
    }

    /**
     * Добавление слова по id уровня
     * @param int $levelId
     * @param int $wordId
     */
    public function addByLevelId(int $levelId, int $wordId)
    {
        DB::table('levels_words')
            ->insert(['level_id' => $levelId, 'word_id' => $wordId]);
    }

    /**
     * Удаление слова по id уровня и слова
     * @param int $levelId
     * @param int $wordId
     */
    public function deleteByLevelId(int $levelId, int $wordId)
    {
        DB::table('levels_words')
            ->where('word_id', '=', $wordId)
            ->where('level_id', '=', $levelId)
            ->delete();
    }

    public function deleteAllWordsByLevelId(int $levelId)
    {
        DB::table('levels_words')
            ->where('level_id', '=', $levelId)
            ->delete();
    }
}
