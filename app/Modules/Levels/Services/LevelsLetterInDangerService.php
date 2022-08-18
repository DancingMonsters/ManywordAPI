<?php

namespace App\Modules\Levels\Services;

use App\Modules\Levels\Repositories\LevelsLetterInDangerRepository;
use Illuminate\Http\Request;

class LevelsLetterInDangerService
{
    public function setLetterInDanger(Request $request, ?int $levelId)
    {
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
            $values['level_id'] = $levelId;
            $levelsLetterInDangerRepository->addLetterInDanger($values);
        }
    }
}
