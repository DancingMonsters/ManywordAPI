<?php

namespace App\Modules\Levels\Services;

use App\Modules\Levels\Repositories\LevelsBlocksRepository;
use Illuminate\Http\Request;

class LevelsBlocksService
{
    public function addBlocks(Request $request, int $levelID)
    {
        $levelBlocksRepository = new LevelsBlocksRepository();
        $levelBlocksRepository->addBlocks([
            'blocks' => json_encode($request->input('changes.active_blocks')),
            'level_id' => $levelID
        ]);
    }

    public function updateBlocks(Request $request)
    {
        $levelBlocksRepository = new LevelsBlocksRepository();
        $levelBlocksRepository->updateBlocksById(
            ['blocks' => json_encode($request->input('changes.active_blocks'))],
            $request->input('changes.active_blocks_id')
        );
    }
}
