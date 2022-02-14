<?php

namespace App\Modules\Services\Services;

use App\Modules\Services\Repositories\ParticlesRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class ParticlesService
{
    /**
     * Получение частей речи
     * @param Request $request
     * @return Collection
     */
    public function get(Request $request): Collection
    {
        return (new ParticlesRepository())->get($request->query('language'));
    }
}
