<?php

namespace App\Modules\Services\Services;

use Carbon\Carbon;

class DateService
{
    /**
     * Получить текущую дату в unix формате
     * @return int
     */
    public function getCurrentDate(): int
    {
        return Carbon::now('Europe/Moscow')->unix();
    }
}
