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

    private function createUniqueId($count) {
        $uniqueId = "";
        for($i = 1; $i != $count+1; $i ++) {
            $r = rand(1,3);
            switch($r) {
                case 1:
                    $z = rand(65,90);
                    $uniqueId .= chr($z);
                    break;
                case 2:
                    $z = rand(97,122);
                    $uniqueId .= chr($z);
                    break;
                case 3:
                    $z = rand(0,9);
                    $uniqueId .= $z;
                    break;
            }
        }
        return $uniqueId;
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

    public function create(Request $req, $language) {
        if($table = $this->getLevelsTable($language)) {
            $table -> insert(['level' => $req -> input('level'), 'uniqueId' => $this->createUniqueId(4)]);
            return response() -> json(["status" => true]);
        } else return response() -> json(["status" => false]);
    }

    public function setUniques(Request $req) {
        $levels = $this->getLevelsTable("RU")->where("uniqueId", null)->get();
        foreach($levels as $level) {
            echo $level->id;
        }
    }
}
