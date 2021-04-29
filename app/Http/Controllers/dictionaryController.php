<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class dictionaryController extends Controller
{
    private function getDictionaryTable($language) {
        $tableName = $language . "_Words";
        if(\Schema::hasTable($tableName)) {
            return DB::table($tableName);
        } else {
            return false;
        }
    }

    public function show($language, $id) {
        if($word = $this->getDictionaryTable($language) -> find($id))
            return response()->json(["status" => true,"word" => $word]);
        else
            return response()->json(["status" => false]);
    }

    public function index($language) {
        if($words = $this->getDictionaryTable($language))
            return response()->json(["status" => true, "words" => $words -> get()]);
        else
            return response()->json(["status" => false]);
    }

    public function create(Request $req) {
        $language = $req -> input('language');
        $word = $req -> input('word');
        $particle = $req -> input('particle');
        $table = $this->getDictionaryTable($language);
        if($table) {
            $table -> insert(['word' => $word, 'particle' => $particle]);
            return response()->json(["status" => true]);
        } else {
            return response()->json(["status" => false]);
        }
    }

    // public function edit(Request $req, $id) {
    //     $language = $req -> input('language');
    //     $table = $this->getDictionaryTable($language);
    //     if($table) {
    //         $editableWord = $table -> find($id);
    //         $editableWord -> word = $req -> input('word');
    //         $editableWord -> particle = $req -> input('particle');
    //         $editableWord -> save();
    //     } else {
    //         return response() -> json(["status" => false]);
    //     }
    // }
}
