<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\weights;

class WeightsController extends Controller
{
  public function add($language, Request $req) {
      $weights = new weights();
      $weights -> weights = $req -> input('weights');
      $weights -> save();
  }

  public function get($language) {
      $weights = weights::where('lang', $language) -> get() -> first();
      return response()->json(json_decode($weights -> weights));
  }
}