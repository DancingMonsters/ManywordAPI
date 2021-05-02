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

    public function create(Request $req, $language) {
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

    public function edit(Request $req, $language, $id) {
        $table = $this->getDictionaryTable($language);
        $word = $req -> input('word');
        $particle = $req -> input("particle");
        if($table) {
            $table -> where("id", $id) -> update(["word" => $word, "particle" => $particle]);
            return response() -> json(["status" => true]);
        } else {
            return response() -> json(["status" => false]);
        }
    }

    public function destroy($language, $id) {
        $table = $this->getDictionaryTable($language);
        if($table) {
            $table -> where("id", $id) -> delete();
            return response() -> json(["status" => true]);
        } else {
            return response() -> json(["status" => false]);
        }
    }
}
