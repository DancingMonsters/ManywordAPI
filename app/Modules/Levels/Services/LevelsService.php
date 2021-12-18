<?php

namespace App\Modules\Levels\Services;

use App\Modules\Levels\Repositories\LevelsBlocksRepository;
use App\Modules\Levels\Repositories\LevelsRepository;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LevelsService
{
    /**
     * Получение уровней
     * @param Request $request
     * @return Collection
     */
    public function get(Request $request): Collection
    {
        return (new LevelsRepository())->get(
            $request->has('only_id') ? ['id', 'description'] : ['*'],
            $request->query('language'),
            $request->query('history_id'),
            $request->query('published')
        );
    }

    /**
     * Получение уровня по id
     * @param int $levelID
     * @return Model|Builder|object|null
     */
    public function getById(int $levelID)
    {
        $level = (new LevelsRepository())->getById($levelID);
        $level->active_blocks = json_decode((new LevelsBlocksRepository())->getBlocksByLevelID($level->id)->blocks);
        return $level;
    }
}
