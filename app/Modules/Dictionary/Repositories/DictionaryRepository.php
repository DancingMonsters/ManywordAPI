<?php

namespace App\Modules\Dictionary\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DictionaryRepository
{
    /**
     * Получение страницы слов
     * @param int $pageStart
     * @param int $language
     * @param int $size
     * @param array $search
     * @return Collection
     */
    public function getPage(int $pageStart, int $language, int $size, array $search): Collection
    {
        return DB::table('words')
            ->select('*')
            ->where('id', '>', $pageStart)
            ->where('language', '=', $language)
            ->where($search)
            ->take($size)
            ->get();
    }

    /**
     * Получение количества страниц
     * @param int $language
     * @param int $size
     * @param array $search
     * @return int
     */
    public function getPagesCount(int $language, int $size, array $search): int
    {
        $all = DB::table('words')
            ->where('language', '=', $language)
            ->where($search)
            ->count();
        $pages = intdiv($all, $size);
        $pages += ($all % $size) == 0 ? 0 : 1;
        return $pages;
    }
}
