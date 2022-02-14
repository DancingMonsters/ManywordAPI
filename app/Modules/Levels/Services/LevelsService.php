<?php

namespace App\Modules\Levels\Services;

use App\Modules\Levels\Repositories\LevelsBlocksRepository;
use App\Modules\Levels\Repositories\LevelsRepository;
use App\Modules\Levels\Repositories\LevelsWinConditionsRepository;
use App\Modules\Levels\Repositories\LevelsWordsRepository;
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
        $levels = (new LevelsRepository())->get(
            $request->has('only_id') ? ['id', 'description', 'published'] : ['*'],
            $request->query('language'),
            $request->query('history_id'),
            $request->query('published')
        );
        $winConditionsRepository = new LevelsWinConditionsRepository();
        $blocksRepository = new LevelsBlocksRepository();
        $wordsRepository = new LevelsWordsRepository();
        $levels->map(function ($level) use ($winConditionsRepository, $blocksRepository, $wordsRepository) {
            $level->win_conditions = $winConditionsRepository->getByLevelId($level->id);
            $level->active_blocks = json_decode($blocksRepository->getBlocksByLevelID($level->id)->blocks);
            $level->words = $wordsRepository->getByLevelId($level->id);
        });
        return $levels;
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
        $level->win_conditions = (new LevelsWinConditionsRepository())->getByLevelId($level->id);
        $level->words = (new LevelsWordsRepository())->getByLevelId($level->id);
        return $level;
    }
}
