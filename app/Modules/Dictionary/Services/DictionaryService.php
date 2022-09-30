<?php

namespace App\Modules\Dictionary\Services;

use App\Modules\Dictionary\Repositories\DictionaryRepository;
use Illuminate\Http\Request;

class DictionaryService
{
    private DictionaryRepository $dictionaryRepository;

    public function __construct()
    {
        $this->dictionaryRepository = new DictionaryRepository();
    }

    /**
     * Подготовить wheres для поиска
     * @param Request $request
     * @return array
     */
    private function prepareSearch(Request $request): array
    {
        $search = [];
        if ($request->filled('particles')) {
            $search[] = ['particle', '=', $request->get('particles')];
        }
        if ($request->filled('length')) {
            $search[] = ['length', '=', $request->get('length')];
        }
        if ($request->filled('word')) {
            $search[] = ['word', 'LIKE', '%' . $request->get('word') . '%'];
        }
        return $search;
    }

    /**
     * Получить слова
     * @param Request $request
     * @return array
     */
    public function get(Request $request): array
    {
        $result = [];
        $language = $request->get('language', 1);
        if ($request->filled('page_start')) {
            $size = $request->get('size', 10);
            $search = $this->prepareSearch($request);
            $result['words'] = $this->dictionaryRepository->getPage(
                $request->get('page_start', 0),
                $language,
                $size,
                $search
            );
            $result['total_pages'] = $this->dictionaryRepository->getPagesCount(
                $language,
                $size,
                $search
            );
        } else {
            $result['words'] = $this->dictionaryRepository->get($language);
        }
        return $result;
    }

    /**
     * Добавить слово
     * @param Request $request
     * @return int
     */
    public function createWord(Request $request): int
    {
        $word = $request->post('word');
        $language = $request->post('language');
        $particle = $request->post('particle');
        return $this->dictionaryRepository->add($word, $language, $particle, mb_strlen($word));
    }

    /**
     * Проверка слова на существование
     * @param Request $request
     * @return bool
     */
    public function checkWord(Request $request): bool
    {
        $word = $this->dictionaryRepository->checkWord($request->query('word'));
        return ($word !== null);
    }
}
