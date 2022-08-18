<?php

namespace App\Modules\Dictionary\Services;

use App\Modules\Dictionary\Repositories\DictionaryRepository;
use Illuminate\Http\Request;

class DictionaryService
{
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
        $repository = new DictionaryRepository();
        $result = [];
        $language = $request->get('language', 1);
        if ($request->filled('page_start')) {
            $size = $request->get('size', 10);
            $search = $this->prepareSearch($request);
            $result['words'] = $repository->getPage(
                $request->get('page_start', 0),
                $language,
                $size,
                $search
            );
            $result['total_pages'] = $repository->getPagesCount(
                $language,
                $size,
                $search
            );
        } else {
            $result['words'] = $repository->get($language);
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
        return (new DictionaryRepository())->add($word, $language, $particle, mb_strlen($word));
    }
}
