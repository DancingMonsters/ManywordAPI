<?php

namespace App\Modules\Levels\Repositories;

use Illuminate\Support\Facades\DB;

class LevelsBlocksRepository
{
    public function getBlocksByLevelID(int $levelID) {
        return DB::table('levels_blocks')
            ->select('blocks')
            ->where('level_id', '=', $levelID)
            ->first();
    }
}
