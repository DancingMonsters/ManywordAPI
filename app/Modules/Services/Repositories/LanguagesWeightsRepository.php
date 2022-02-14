<?php

namespace App\Modules\Services\Repositories;

use Illuminate\Support\Facades\DB;

class LanguagesWeightsRepository
{
    /**
     * Получение весов языка
     * @param int $language
     * @return mixed|null
     */
    public function get(int $language)
    {
        return DB::table('weights')
            ->where('language', '=', $language)
            ->value('weights');
    }
}
