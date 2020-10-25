<?php

namespace App\Http\Controllers;
use Intervention\Image\Facades\Image;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use My_func;

class ZaicoInputController extends Controller
{
  // 在庫品入出庫画面表示
  public function zaico_input(Request $request){
    // ログインチェック
    if (Auth::check()){
      $info = DB::table('part_info')->where('id', $request -> id)->first();

      $param = My_func::tableRead();
      $param += $request->old();
      $param += [
        'info' => $info,
      ];

      return view('zaico_input.index', $param);
    }else{
      return view('auth/login');
    }
  }

  // 在庫品入出庫動作実行
  public function register(Request $request){
    $validate_rule = [
      'rec_and_ship' => 'required',
      'stock' => 'min:0|required',
      'part_photo' => 'image',
      'status' => 'required',
    ];

    $this->validate($request, $validate_rule);

    list($param, $param_log) = My_func::partParaIni($request -> utilization , $request);

    try{

    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }

    try{
      DB::table('zaico_table')->insert($param_log);
      if($param['stock'] <= 0 and ($request -> new_used == "新品-常時在庫管理無し" or $request -> new_used == "中古-常時在庫管理無し")){
        DB::table('part_info')->where('id', $request -> part_id)->delete();
      }else{
        DB::table('part_info')->where('id', $request -> part_id)->update($param);
      }
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }
    return redirect($request -> url);
  }
}
