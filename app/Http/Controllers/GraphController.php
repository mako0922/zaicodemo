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
      $audjpy = DB::table('audjpy')->orderBy('datetime','desc')->get();
      //echo $audjpy;
      //$file = public_path() . '\data\templary.json';
      //$file = $audjpy;
      //$json = file_get_contents($file);
      //$data = json_decode($json, true);
      $data = json_decode($audjpy[0]->openorder, true);
      $data_median = median($data['buckets']);
      $data_reserve = array_reverse($data['buckets']);
      $updatetime = $audjpy[0]->datetime;
      $param = ['data' => $data_reserve, 'users' => $user, 'updatetime' => $updatetime, 'median' => $data_median];
      return view('audjpy.index',$param);
    }else{
      return view('auth/login');
    }

  }

}

function median($list){
  sort($list);
  if (count($list) % 2 == 0){
    return (($list[(count($list)/2)-1]+$list[((count($list)/2))])/2);
  }else{
    return ($list[floor(count($list)/2)]);
  }
}
