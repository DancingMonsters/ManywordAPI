<?php

namespace App\Modules\Services\Services;

use App\Modules\Services\Repositories\LanguagesAlphabetRepository;
use Illuminate\Http\Request;

class LanguagesAlphabetService
{
    /**
     * Получение алфавита языка
     * @param Request $request
     * @return mixed
     */
    public function get(Request $request)
    {
        $alphabet = (new LanguagesAlphabetRepository())->get($request->query('language', 1));
        return json_decode($alphabet);
    }
}
