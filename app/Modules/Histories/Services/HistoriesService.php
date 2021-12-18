<?php

namespace App\Modules\Histories\Services;

use App\Modules\Histories\Repositories\HistoriesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class HistoriesService
{
    /**
     * Получение историй
     * @param Request $request
     * @return Collection
     */
    public function get(Request $request): Collection
    {
        return (new HistoriesRepository())->get($request->query('language'));
    }
}
