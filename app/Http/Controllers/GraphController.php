<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GraphController extends Controller
{
  public function audjpy(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      $audjpy = DB::table('audjpy')->get();
      //echo $audjpy;
      //$file = public_path() . '\data\templary.json';
      //$file = $audjpy;
      //$json = file_get_contents($file);
      //$data = json_decode($json, true);
      $data = json_decode($audjpy[0]->openorder, true);

      $param = ['data' => $data, 'users' => $user];
      return view('audjpy.index',$param);
    }else{
      return view('auth/login');
    }

  }

}
