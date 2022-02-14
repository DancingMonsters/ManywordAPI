<?php

namespace App\Modules\Services\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ParticlesRepository
{
    public function get(int $language): Collection
    {
        return DB::table('particles')
            ->select('particles.id', 'particles_names.name', 'particles_names.plural_name')
            ->leftJoin('particles_names', 'particles_names.particle_id', '=', 'particles.id')
            ->where('language', '=', $language)
            ->get();
    }
}
