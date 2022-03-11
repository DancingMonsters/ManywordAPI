<?php

namespace App\Modules\Levels\Repositories;

use Illuminate\Support\Facades\DB;

class LevelsBlocksRepository
{
    /**
     * Получение блоков уровня по его id
     * @param int $levelID
     * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Query\Builder|object|null
     */
    public function getBlocksByLevelID(int $levelID) {
        return DB::table('levels_blocks')
            ->select('blocks', 'id')
            ->where('level_id', '=', $levelID)
            ->first();
    }

    /**
     * Обновление блоков уровня
     * @param int $blocksID
     * @param string $blocks
     */
    public function updateBlocksByID(int $blocksID, string $blocks)
    {
        DB::table('levels_blocks')
            ->where('id', '=', $blocksID)
            ->update(['blocks' => $blocks]);
    }
}
