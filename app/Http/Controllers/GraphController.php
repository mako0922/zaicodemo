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
      $param = graphExchange('audjpy');
      return view('audjpy.index',$param);
    }else{
      return view('auth/login');
    }
  }

  public function usdjpy(Request $request){
    // ログインチェック
    if (Auth::check()){
      $param = graphExchange('usdjpy');
      return view('usdjpy.index',$param);
    }else{
      return view('auth/login');
    }
  }

  public function cadjpy(Request $request){
    // ログインチェック
    if (Auth::check()){
      $param = graphExchange('cadjpy');
      return view('cadjpy.index',$param);
    }else{
      return view('auth/login');
    }
  }

  public function nzdjpy(Request $request){
    // ログインチェック
    if (Auth::check()){
      $param = graphExchange('nzdjpy');
      return view('nzdjpy.index',$param);
    }else{
      return view('auth/login');
    }
  }

  public function chfjpy(Request $request){
    // ログインチェック
    if (Auth::check()){
      $param = graphExchange('chfjpy');
      return view('chfjpy.index',$param);
    }else{
      return view('auth/login');
    }
  }

  public function audnzd(Request $request){
    // ログインチェック
    if (Auth::check()){
      $param = graphExchange('audnzd');
      return view('audnzd.index',$param);
    }else{
      return view('auth/login');
    }
  }

  public function onchange(Request $request){
    // ログインチェック
    if (Auth::check()){
      $param = graphExchange($request->exchange);
      return redirect('/' . $request->exchange);
      //return view('audjpy.index',$param);
    }else{
      return view('auth/login');
    }
  }
}

//中央値取得メソッド
function median($list){
  sort($list);
  if (count($list) % 2 == 0){
    return ($list[floor(count($list)/2)-1]);
  }else{
    return ($list[floor(count($list)/2)]);
  }
}

//表示データ取得メソッド
function graphExchange($graphexchange){
  $user = Auth::user();
  $exchange = DB::table('exchange')->orderBy('id','asc')->get();
  $exchange_form = DB::table($graphexchange)->orderBy('datetime','desc')->get();
  $data = json_decode($exchange_form[0]->openorder, true);
  $data_median = median($data['buckets']);
  $data_reserve = array_reverse($data['buckets']);
  $updatetime = $exchange_form[0]->datetime;
  $currency_ini = $graphexchange;

  $minkabu_ini = hp_source('https://fx.minkabu.jp/indicators/');
  $minkabu = mb_strstr($minkabu_ini, '<section>');
  $minkabu = mb_strstr($minkabu, '</section>',true);

  $minkabu = str_replace( '/indicators' , 'https://fx.minkabu.jp/indicators' , $minkabu);
  $minkabu = str_replace( '/assets' , 'https://fx.minkabu.jp/assets' , $minkabu);
  $minkabu = str_replace( '<section>' , '' , $minkabu);
  //$minkabu = '<link rel="stylesheet" media="all" href="https://fx.minkabu.jp/assets/application-1af5af89e296014a0411659dc3977394208b47f9f5adad41762e4a0cabeb3589.css" data-turbolinks-track="reload" />' . $minkabu;

  $param = ['data' => $data_reserve, 'users' => $user, 'updatetime' => $updatetime, 'median' => $data_median, 'exchange' => $exchange, 'currency_ini' => $currency_ini, 'minkabu' => $minkabu];
  return $param;
}

//取得したいサイトのURL
function hp_source($URL){
  $html_source = file_get_contents($URL);
  return $html_source;
}
