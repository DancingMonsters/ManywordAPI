<?php

namespace App\Modules\Levels\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Levels extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $table = "levels";
}
