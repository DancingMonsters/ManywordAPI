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

    public function index(?Request $request, $language) {
        if($request->filled('page'))
        {
            $page = (int)$request->query('page', 1);
            $size = (int)$request->query('size', 20);
            $search = $request->query('search', null);
            return response()->json($this->getPage($language, $page, $size, $search));
        } else {
            if($words = $this->getDictionaryTable($language))
                return response()->json(["status" => true, "words" => $words -> get()]);
            else
                return response()->json(["status" => false]);
        }
    }

    public function getPagesCount(string $language, int $size, array $search = null) {
        $query = $this->getDictionaryTable($language);
        if ($search !== null)
        {
            foreach ($search as $key => $item)
            {
                if($key == 'word')
                {
                    $query = $query->where('word', 'LIKE', '%' . $item . '%');
                } else {
                    $query = $query->where('particle', '=', $key);
                }
            }
        }
        $wordsCount = $query->get()->count();
        $pages = intdiv($wordsCount, $size);
        $pages += ($wordsCount % $size) == 0 ? 0 : 1;
        return $pages;
    }

    private function getPage(string $language, int $page, int $size, array $search = null)
    {
        $query = $this->getDictionaryTable($language)
            ->select('*');
        if($search !== null)
        {
            dd($search);
            foreach ($search as $key => $item)
            {
                if($key == 'word')
                {
                    $query = $query->where('word', 'LIKE', '%' . $item . '%');
                } else {
                    $query = $query->where('particle', '=', $item);
                }
            }
        }
        return $query
            ->skip(($page-1) * $size)->take($size)
            ->get()->toArray();
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
