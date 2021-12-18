<?php

namespace App\Modules\Histories\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class HistoriesRepository
{
    public function get(string $language): Collection {
        return DB::table('histories')->select(
            'histories.id',
            'histories_names.name'
            )->leftJoin(
                'histories_names',
                'histories_names.history_id', '=', 'histories.id')
                ->where('histories_names.language','=', $language)
            ->get();
    }
}
