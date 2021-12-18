<?php

namespace App\Http\Controllers;

use Illuminate\Database\Query\Builder;
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
        if($request->filled('page_start'))
        {
            $pageStart = (int)$request->query('page_start');
            $size = (int)$request->query('size', 10);
            $search = [
                'particle' => $request->query('particle'),
                'length' => $request->query('length'),
                'word' => $request->query('word')
            ];
            $result = [
                'total_pages' => 0,
                'words' => []
            ];
            $totalPages = $this->getPagesCount($language, $size, $search);
            if ($totalPages !== 0) {
                $result['total_pages'] = $totalPages;
                $result['words'] = $this->getPage($language, $pageStart, $size, $search);
            }
            return response()->json($result);
        } else {
            if($words = $this->getDictionaryTable($language))
                return response()->json(["status" => true, "words" => $words -> get()]);
            else
                return response()->json(["status" => false]);
        }
    }

    private function search(Builder $query, array $search)
    {
        foreach ($search as $key => $item) {
            if ($key == 'length') {
                if (intval($item) != 0) {
                    $query->where($key, '=', intval($item));
                }
                continue;
            } elseif ($key == 'particle') {
                if ($item == 'all') {
                    continue;
                }
            } elseif ($key == 'word') {
                if ($item == 'all') {
                    continue;
                }
            }
            $query->where($key, 'LIKE', '%' . $item . '%');
        }
        return $query;
    }

    private function getPagesCount(string $language, int $size, array $search = null): int
    {
        $query = $this->getDictionaryTable($language);
        if ($search !== null) {
            $query = $this->search($query, $search);
        }
        $wordsCount = $query->get()->count();
        $pages = intdiv($wordsCount, $size);
        $pages += ($wordsCount % $size) == 0 ? 0 : 1;
        return $pages;
    }

    private function getPage(string $language, int $pageStart, int $size, array $search = null): array
    {
        $query = $this->getDictionaryTable($language)
            ->select('*');
        if ($search !== null) {
            $this->search($query, $search);
        }
        $query->where('id', '>', $pageStart)->take($size);
        return $query->get()->toArray();
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
