<?php

namespace App\Modules\Services\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;

class LanguagesAlphabetRepository
{
    /**
     * Получение алфавита языка
     * @param int $language
     * @return Model|Builder|object|null
     */
    public function get(int $language)
    {
        return DB::table('alphabets')
            ->select('alphabet')
            ->where('language', '=', $language)
            ->value('alphabet');
    }
}
