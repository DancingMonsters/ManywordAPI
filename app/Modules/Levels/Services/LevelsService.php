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
     * @param Request $request
     * @param int|null $levelID
     */
    public function setById(Request $request, int $levelID = null)
    {
        $update = $this->getUpdate($request);
        $newLevelID = (new LevelsRepository())->set($update, $levelID);

        if ($request->filled('changes.active_blocks_id')) {
            $levelBlocksRepository = new LevelsBlocksRepository();
            $levelBlocksRepository->updateBlocksById(
                ['blocks' => json_encode($request->input('changes.active_blocks'))],
                $request->input('changes.active_blocks_id')
            );
        } elseif ($levelID === null) {
            $levelBlocksRepository = new LevelsBlocksRepository();
            $levelBlocksRepository->addBlocks([
                'blocks' => json_encode($request->input('changes.active_blocks')),
                'level_id' => $newLevelID
            ]);
        }
        if ($request->filled('changes.win_conditions')) {
            $levelWinConditionsRepository = new LevelsWinConditionsRepository();
            if ($request->filled('changes.win_conditions.id')) {
                $winUpdate = [];
                if ($request->filled('changes.win_conditions.type')) {
                    $winUpdate["type"] = $request->input('changes.win_conditions.type');
                }
                if ($request->filled('changes.win_conditions.value')) {
                    $winUpdate["value"] = $request->input('changes.win_conditions.value');
                }
                $levelWinConditionsRepository->updateById(
                    $request->input('changes.win_conditions.id'),
                    $winUpdate
                );
            } else {
                $values = $request->input('changes.win_conditions');
                $values['level_id'] = $newLevelID;
                $levelWinConditionsRepository->addConditions($values);
            }
        }
        if ($request->filled('changes.words')) {
            $levelsWordsRepository = new LevelsWordsRepository();
            $currentWords = $levelsWordsRepository->getByLevelId($levelID)->pluck('id');
            $newWords = collect($request->input('changes.words'));
            $add = $newWords->diff($currentWords);
            $delete = $currentWords->diff($newWords);
            $add->map(function ($item) use ($levelID, $levelsWordsRepository) {
                $levelsWordsRepository->addByLevelId($levelID, $item);
            });
            $delete->map(function ($item) use ($levelID, $levelsWordsRepository) {
                $levelsWordsRepository->deleteByLevelId($levelID, $item);
            });
        }
        if ($request->filled('changes.letter_in_danger')) {
            $levelsLetterInDangerRepository = new LevelsLetterInDangerRepository();
            if ($request->filled('changes.letter_in_danger.id')) {
                $lidUpdate = [];
                if ($request->filled('changes.letter_in_danger.count')) {
                    $lidCount = $request->input('changes.letter_in_danger.count');
                    $lidUpdate[] = ['count' => $lidCount];
                    if (intval($lidCount) === 0) {
                        $lidUpdate[] = ['weights' => 0];
                        $lidUpdate[] = ['time' => 0];
                    }
                }
                if ($request->filled('changes.letter_in_danger.weights')) {
                    $lidUpdate[] = ['weights' => $request->input('changes.letter_in_danger.weights')];
                }
                if ($request->filled('changes.letter_in_danger.time')) {
                    $lidUpdate[] = ['time' => $request->input('changes.letter_in_danger.time')];
                }
                $levelsLetterInDangerRepository->updateById(
                    $request->input('changes.letter_in_danger.id'),
                    $lidUpdate
                );
            } else {
                $values = $request->input('changes.letter_in_danger');
                $values['level_id'] = $newLevelID;
                dd($values);
                $levelsLetterInDangerRepository->addLetterInDanger($values);
            }
        }
    }

    /**
     * Получение массива обновлений для уровня
     * @param Request $request
     * @return array
     */
    private function getUpdate(Request $request): array
    {
        $result = [];
        if ($request->filled('changes.history_id')) {
            $result['history_id'] = $request->input('changes.history_id');
        }
        if ($request->filled('changes.published')) {
            $result['published'] = $request->input('changes.published');
        }
        if ($request->filled('changes.particles')) {
            $result['particles'] = $request->input('changes.particles');
        }
        if ($request->filled('changes.description')) {
            $result['description'] = $request->input('changes.description');
        }
        if ($request->filled('changes.type_id')) {
            $typeID = $request->input('changes.type_id');
            if ($typeID == 2) {
                $result['time'] = 0;
            }
            $result['type_id'] = $typeID;
        }
        if ($request->filled('changes.time')) {
            $result['time'] = $request->input('changes.time');
        }
        return $result;
    }
}
