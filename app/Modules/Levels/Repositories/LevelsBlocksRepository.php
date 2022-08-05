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
     * @param array $values
     * @param int|null $blocksId
     */
    public function updateBlocksById(array $values, int $blocksId)
    {
        DB::table('levels_blocks')
            ->where('id', '=', $blocksId)
            ->update($values);
    }

    /**
     * @param array $values
     */
    public function addBlocks(array $values)
    {
        DB::table('levels_blocks')
            ->insert($values);
    }
}
