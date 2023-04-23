<?php

namespace App\Modules\Levels\Services;

use App\Modules\Dictionary\Repositories\DictionaryRepository;
use App\Modules\Levels\Models\Levels;
use App\Modules\Levels\Repositories\LevelsBlocksRepository;
use App\Modules\Levels\Repositories\LevelsLetterInDangerRepository;
use App\Modules\Levels\Repositories\LevelsRepository;
use App\Modules\Levels\Repositories\LevelsWinConditionsRepository;
use App\Modules\Levels\Repositories\LevelsWordsRepository;
use App\Modules\Services\Repositories\LanguagesWeightsRepository;
use App\Modules\Services\Services\LanguagesWeightsService;
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
    public function setById(Request $request, ?int $levelID = null)
    {
        $update = $this->getUpdate($request);
        $newLevelID = null;
        if (!empty($update)) {
            $levelsRepository = new LevelsRepository();
            if ($levelID !== null) {
                $levelsRepository->set($update, $levelID);
            } else {
                do {
                    $uniqueId = $this->generateUniqueId(4);
                } while (Levels::where('unique_id', '=', $uniqueId)->count() > 0);
                $update['unique_id'] = $uniqueId;
                $newLevelID = $levelsRepository->add($update);
            }
        }

        $levelsBlocksService = new LevelsBlocksService();

        if ($request->filled('changes.active_blocks_id')) {
            $levelsBlocksService->updateBlocks($request);
        } elseif ($levelID === null && $newLevelID !== null) {
            $levelsBlocksService->addBlocks($request, $newLevelID);
        }

        if ($request->filled('changes.win_conditions')) {
            (new LevelsWinConditionsService())->setWinConditions($request, $newLevelID);
        }

        if ($request->filled('changes.words')) {
            (new LevelsWordsService())->setWords($request, $levelID ?? $newLevelID, $levelID === null);
        }

        if ($request->filled('changes.letter_in_danger')) {
            (new LevelsLetterInDangerService())->setLetterInDanger($request, $levelID ?? $newLevelID);
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
        if ($request->filled('changes.language')) {
            $result['language'] = $request->input('changes.language');
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

    /**
     * @param int $length
     * @return string
     */
    private function generateUniqueId(int $length): string
    {
        $uniqueId = "";
        for($i = 0; $i < $length; $i ++) {
            $r = rand(1,3);
            switch($r) {
                case 1:
                    $l = chr(rand(65,90));
                    $uniqueId .= $l;
                    break;
                case 2:
                    $l = chr(rand(97,122));
                    $uniqueId .= $l;
                    break;
                case 3:
                    $l = rand(0, 9);
                    $uniqueId .= $l;
                    break;
            }
        }
        return $uniqueId;
    }

    public function deleteLevel(int $id)
    {
        (new LevelsWordsRepository())->deleteAllWordsByLevelId($id);
        (new LevelsWinConditionsRepository())->deleteConditionsByLevelId($id);
        (new LevelsLetterInDangerRepository())->deleteLetterInDangerByLevelId($id);
        (new LevelsBlocksRepository())->deleteBlocksByLevelId($id);
        (new LevelsRepository())->delete($id);
    }

    public function nextLevelById(int $id, Request $request)
    {
        $language = $request->input('language', 1);
        $level = (new LevelsRepository())->getNextLevelById($id, $language);
        $blocks = (new LevelsBlocksRepository())->getBlocksByLevelID($level->id);
        $level->active_blocks = json_decode($blocks->blocks);
        $level->active_blocks_id = $blocks->id;
        $level->win_conditions = (new LevelsWinConditionsRepository())->getByLevelId($level->id);
        $level->words = (new LevelsWordsRepository())->getByLevelId($level->id);
        $level->letter_in_danger = (new LevelsLetterInDangerRepository())->getByLevelId($level->id);
        return $level;
    }

    public function checkWord(int $id, Request $request): array
    {
        $word = $request->post('word');
        $wordsRepository = new DictionaryRepository();
        $level = $this->getById($id);
        $wordExists = $wordsRepository->getWordIdByName($word, $level->particles, $level->language);

        $returnData = [
            "status" => 0,
            "in_level" => 0,
            "score" => 0
        ];
        if ($wordExists !== null) {
            $returnData["status"] = 1;

            $score = 0;
            $weights = json_decode((new LanguagesWeightsRepository())->get($level->language));
            $weightsDecompressed = [];
            foreach ($weights as $key => $weight) {
                foreach ($weight as $value) {
                    $weightsDecompressed[$value] = $key;
                }
            }

            foreach (mb_str_split($word) as $char) {
                $score += $weightsDecompressed[$char];
            }

            $score *= mb_strlen($word);
            $score *= 10;

            if ($level->words->where('word', $word)->first() !== null) {
                $returnData["in_level"] = 1;
            }

            $returnData["score"] = $score;
        }

        return $returnData;
    }
}
