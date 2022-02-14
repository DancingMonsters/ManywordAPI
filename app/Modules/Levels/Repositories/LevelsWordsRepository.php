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
            ->leftJoin('words', 'words.id', '=', 'levels_words.word_id')
            ->where('levels_words.level_id', '=', $levelId)
            ->get();
    }
}
