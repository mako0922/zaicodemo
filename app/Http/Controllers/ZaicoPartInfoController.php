<?php

namespace App\Http\Controllers;
use Intervention\Image\Facades\Image;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use My_func;

class ZaicoPartInfoController extends Controller
{
  // 常時在庫在り新規登録画面表示
  public function part_info(Request $request){
    // ログインチェック
    if (Auth::check()){
      $param = My_func::tableRead();
      return view('part_info.index', $param);
    }else{
      return view('auth/login');
    }
  }

  // 常時在庫在り新規登録実行
  public function part_info_register(Request $request){

    $validate_rule = [
      'part_name' => 'required',
      'manufacturer' => 'required',
      'class_name' => 'required',
      'stock' => 'required',
      'storage' => 'required',
      'status' => 'required',
    ];
    $this->validate($request, $validate_rule);

    list($param, $param_log) = My_func::partParaIni('新規登録' , $request);

    try{
      DB::table('part_info')->insert($param);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }

    try{
      DB::table('zaico_table')->insert($param_log);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }
    return redirect('/zaico_home');
  }
}
