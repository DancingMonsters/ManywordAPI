<?php

namespace App\Modules\Levels\Services;

use App\Modules\Levels\Repositories\LevelsWordsRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class LevelsWordsService
{
    public function setWords(Request $request, ?int $levelId, bool $newLevel = false)
    {
        $levelsWordsRepository = new LevelsWordsRepository();
        $newWords = collect($request->input('changes.words'));
        if (!$newLevel) {
            $currentWords = $levelsWordsRepository->getByLevelId($levelId)->pluck('id');
            $delete = $currentWords->diff($newWords);
            $delete->map(function ($item) use ($levelId, $levelsWordsRepository) {
                $levelsWordsRepository->deleteByLevelId($levelId, $item);
            });
        } else {
            $currentWords = new Collection();
        }
        $add = $newWords->diff($currentWords);
        if ($newLevel) {
            $add = $add->pluck('id');
        }
        $add->map(function ($item) use ($levelId, $levelsWordsRepository) {
            $levelsWordsRepository->addByLevelId($levelId, $item);
        });
    }
}
