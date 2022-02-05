<?php

namespace App\Modules\Dictionary\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class DictionaryRepository
{
    /**
     * Получить весь словарь
     * @param int $language
     * @return Collection
     */
    public function get(int $language): Collection
    {
        return DB::table('words')
            ->select('*')
            ->where('language', '=', $language)
            ->get();
    }

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

    /**
     * Получить id слова по его имени, части речи и языка
     * @param string $name
     * @param int $particle
     * @param int $language
     * @return mixed|null
     */
    public function getWordIdByName(string $name, int $particle, int $language)
    {
        return DB::table('words')
            ->where('word', '=', $name)
            ->where('particle', '=', $particle)
            ->where('language', '=', $language)
            ->value('id');
    }

    /**
     * Добавление слова в словарь
     * @param string $word
     * @param int $language
     * @param int $particle
     * @param int $length
     * @return int
     */
    public function add(string $word, int $language, int $particle, int $length): int
    {
        return DB::table('words')
            ->insertGetId(['word' => $word, 'language' => $language, 'particle' => $particle, 'length' => $length]);
    }
}
