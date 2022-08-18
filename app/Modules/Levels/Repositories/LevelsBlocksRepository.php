<?php

namespace App\Modules\Levels\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class LevelsBlocksRepository
{

    private string $table = 'levels_blocks';
    /**
     * Получение блоков уровня по его id
     * @param int $levelID
     * @return Model|Builder|object|null
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

    public function deleteBlocksByLevelId(int $levelId)
    {
        DB::table('levels_blocks')
            ->where('level_id', '=', $levelId)
            ->delete();
    }
}
