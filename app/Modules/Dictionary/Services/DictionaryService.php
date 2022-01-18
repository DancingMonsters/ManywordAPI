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
        if ($request->filled('particle')) {
            $search[] = ['particle', '=', $request->get('particle')];
        }
        if ($request->filled('length')) {
            $search[] = ['length', '=', $request->get('length')];
        }
        if ($request->filled('word')) {
            $search[] = ['word', 'LIKE', '%' . $request->get('word') . '%'];
        }
        return $search;
    }

    public function get(Request $request): array
    {
        $repository = new DictionaryRepository();
        $result = [];
        $language = $request->get('language', 1);
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
        return $result;
    }
}
