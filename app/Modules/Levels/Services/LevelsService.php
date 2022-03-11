<?php

namespace App\Modules\Levels\Services;

use App\Modules\Levels\Repositories\LevelsBlocksRepository;
use App\Modules\Levels\Repositories\LevelsLetterInDangerRepository;
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
        $letterInDangerRepository = new LevelsLetterInDangerRepository();
        $levels->map(function ($level) use ($winConditionsRepository, $blocksRepository, $wordsRepository, $letterInDangerRepository) {
            $level->win_conditions = $winConditionsRepository->getByLevelId($level->id);
            $level->active_blocks = json_decode($blocksRepository->getBlocksByLevelID($level->id)->blocks);
            $level->words = $wordsRepository->getByLevelId($level->id);
            $level->letter_in_danger = $letterInDangerRepository->getByLevelId($level->id);
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
        $blocks = (new LevelsBlocksRepository())->getBlocksByLevelID($levelID);
        $level->active_blocks = json_decode($blocks->blocks);
        $level->active_blocks_id = $blocks->id;
        $level->win_conditions = (new LevelsWinConditionsRepository())->getByLevelId($levelID);
        $level->words = (new LevelsWordsRepository())->getByLevelId($levelID);
        $level->letter_in_danger = (new LevelsLetterInDangerRepository())->getByLevelId($levelID);
        return $level;
    }

    /**
     * Обновление уровня по id
     * @param int $levelID
     * @param Request $request
     */
    public function setById(int $levelID, Request $request)
    {
        $update = $this->getUpdate($request);
        if ($request->filled('active_blocks_id')) {
            (new LevelsBlocksRepository())->updateBlocksByID(
                $request->post('active_blocks_id'),
                json_encode($request->filled('active_blocks'))
            );
        }
        if ($request->filled('win_conditions')) {
            $winUpdate = [];
            if ($request->filled('win_conditions.type')) {
                $winUpdate[] = ['type' => $request->post('win_conditions.type')];
            }
            if ($request->filled('win_conditions.value')) {
                $winUpdate[] = ['value' => $request->post('win_conditions.value')];
            }
            (new LevelsWinConditionsRepository())->updateById(
                $request->post('win_conditions.id'),
                $winUpdate
            );
        }
        if ($request->filled('words')) {
            $levelsWordsRepository = new LevelsWordsRepository();
            $currentWords = $levelsWordsRepository->getByLevelId($levelID)->pluck('id');
            $newWords = collect($request->post('words'));
            $add = $newWords->diff($currentWords);
            $delete = $currentWords->diff($newWords);
            $add->map(function ($item) use ($levelID, $levelsWordsRepository) {
                $levelsWordsRepository->addByLevelId($levelID, $item);
            });
            $delete->map(function ($item) use ($levelID, $levelsWordsRepository) {
                $levelsWordsRepository->deleteByLevelId($levelID, $item);
            });
        }
        if ($request->filled('letter_in_danger')) {
            $lidUpdate = [];
            if ($request->filled('letter_in_danger.count')) {
                $lidCount = $request->post('letter_in_danger.count');
                $lidUpdate[] = ['count' => $lidCount];
                if (intval($lidCount) === 0) {
                    $lidUpdate[] = ['weights' => 0];
                    $lidUpdate[] = ['time' => 0];
                }
            }
            if ($request->filled('letter_in_danger.weights')) {
                $lidUpdate[] = ['weights' => $request->post('letter_in_danger.weights')];
            }
            if ($request->filled('letter_in_danger.time')) {
                $lidUpdate[] = ['time' => $request->post('letter_in_danger.time')];
            }
            (new LevelsLetterInDangerRepository())->updateById(
                $request->post('letter_in_danger.id'),
                $lidUpdate
            );
        }
        (new LevelsRepository())->set($levelID, $update);
    }

    /**
     * Получение массива обновлений для уровня
     * @param Request $request
     * @return array
     */
    private function getUpdate(Request $request): array
    {
        $result = [];
        if ($request->filled('history_id')) {
            $result['history_id'] = $request->post('history_id');
        }
        if ($request->filled('published')) {
            $result['published'] = $request->post('published');
        }
        if ($request->filled('particles')) {
            $result['particles'] = $request->post('particles');
        }
        if ($request->filled('description')) {
            $result['description'] = $request->post('description');
        }
        if ($request->filled('type_id')) {
            $typeID = $request->post('type_id');
            if ($typeID == 2) {
                $result['time'] = 0;
            }
            $result['type_id'] = $typeID;
        }
        if ($request->filled('time')) {
            $result['time'] = $request->post('time');
        }
        return $result;
    }
}
