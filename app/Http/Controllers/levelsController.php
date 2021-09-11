<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\RU_Levels;

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

    private function generateUniqueId($length) {
        $uniqueId = "";
        for($i = 0; $i < $length; $i ++) {
            $r = rand(1,3);
            switch($r) {
                case 1:
                    $l = chr(rand(65,90));
                    $uniqueId .= $l;
                    break;
                case 2:
                    $l = chr(rand(97,122));
                    $uniqueId .= $l;
                    break;
                case 3:
                    $l = rand(0, 9);
                    $uniqueId .= $l;
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
        $table = $this->getLevelsTable($language);
        if($table) {
            $levels = $table -> get();
            return response()->json(["status" => true, "levels" => $levels]);
        }
        else
            return response()->json(["status" => false]);
    }

    public function create(Request $req, $language) {
        $table = $this->getLevelsTable($language);
        $level = $req -> input('level');
        $uniqueId = $this->generateUniqueId(4);
        if($table) {
            while($table->where("uniqueId", $uniqueId) -> count() != 0) {
                $uniqueId = $this->generateUniqueId(4);
            }
            $newLevel = $table -> insert(['level' => $level, 'uniqueId' => $uniqueId]);
            return response()->json(["status" => true, 'level' => $newLevel]);
        }
        else
            return response()->json(["status" => false]);
    }

    public function edit(Request $req, $language, $id) {
        $table = $this->getLevelsTable($language);
        if($table) {
            $level = $table -> find($id);
            $level -> level = $req -> input('level');
            $level -> save();
        }
    }
    
    public function send() {
        mail('errannnnnnn@gmail.com', 'tema', 'message');
    }

    //--//--//--//--//--//--//--//--//--//--//--//
    //                                          //
    // public function generate() {             //
    //     $levels = RU_Levels::all();          //
    //     foreach($levels as $level) {         //
    //         $id = $level -> id;              //
    //         $ids[] = $id;                    //
    //         $uniqueId = $this -> generateUniqueId(4);
    //         while(RU_Levels::where("uniqueId", $uniqueId)->count() != 0) {
    //             $uniqueId = $this -> generateUniqueId(4);
    //         }                                //
    //         $level -> uniqueId = $uniqueId;  //
    //         $level -> published = 1;         //
    //         $level -> save();                //
    //     }                                    //
    // }                                        //
    //                                          //
    //--//--//--//--//--//--//--//--//--//--//--//
}
