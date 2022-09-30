<?php

namespace App\Modules\Services\Services;

use App\Modules\Services\Repositories\LanguagesAlphabetRepository;
use Illuminate\Http\Request;

class LanguagesAlphabetService
{
    /**
     * Получение алфавита языка
     * @param int $languageId
     * @return mixed
     */
    public function get(int $languageId)
    {
        $alphabet = (new LanguagesAlphabetRepository())->get($languageId);
        return json_decode($alphabet);
    }
}
