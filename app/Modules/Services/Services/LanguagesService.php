<?php

namespace App\Modules\Services\Services;

use App\Modules\Services\Models\Languages;
use Illuminate\Support\Collection;

class LanguagesService
{
    public function getLanguages(): Collection {
        return (new Languages())->all();
    }
}
