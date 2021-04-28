<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class levelsController extends Controller
{
    private function getLevelsTable($language) {
        $tableName = $language . "_Levels";
        if(\Schema::hasTable($tableName)) {
            return DB::table($tableName);
        } else {
            return false;
        }
    }
    
    public function show($language, $id) {
        if($level = $this->getLevelsTable($language) -> find($id))
            return response()->json(["status" => true,"level" => $level]);
        else
            return response()->json(["status" => false]);
    }

    public function index($language) {
        if($levels = $this->getLevelsTable($language))
            return response()->json(["status" => true, "levels" => $levels -> get()]);
        else
            return response()->json(["status" => false]);
    }
}
