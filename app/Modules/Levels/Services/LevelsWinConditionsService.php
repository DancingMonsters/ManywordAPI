<?php

namespace App\Modules\Levels\Services;

use App\Modules\Levels\Repositories\LevelsWinConditionsRepository;
use Illuminate\Http\Request;

class LevelsWinConditionsService
{
    public function setWinConditions(Request $request, ?int $levelID)
    {
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
            $values['level_id'] = $levelID;
            $levelWinConditionsRepository->addConditions($values);
        }
    }
}
