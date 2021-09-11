<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\alphabets;

class AlphabetsController extends Controller
{
  public function add($language, Request $req) {
      $alphabet = new alphabets();
      $alphabet -> alphabet = $req -> input('alphabet');
      $alphabet -> save();
  }

  public function get($language) {
      $alphabet = alphabets::where('lang', $language) -> get() -> first();
      return response()->json(json_decode($alphabet -> alphabet));
  }
}