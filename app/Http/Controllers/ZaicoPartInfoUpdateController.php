<?php

namespace App\Http\Controllers;
use Intervention\Image\Facades\Image;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use My_func;

class ZaicoPartInfoUpdateController extends Controller
{
  // 在庫品変更処理画面表示
  public function part_update(Request $request){

    if (Auth::check()){
      $param = My_func::tableRead();
      $param += $request->old();
      return view('part_update.index', $param);
    }else{
      return view('auth/login');
    }
  }

  // 在庫品変更処理実行
  public function part_update_register(Request $request){
    $validate_rule = [
      'part_name' => 'required',
      'manufacturer' => 'required',
      'class_name' => 'required',
      'stock' => 'required',
      'storage' => 'required',
      'status' => 'required',
    ];
    $this->validate($request, $validate_rule);

    list($param, $param_log) = My_func::partParaIni('在庫管理修正処理' , $request);

    try{
      DB::table('part_info')->where('id', $request -> part_id)->update($param);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }

    try{
      DB::table('zaico_table')->insert($param_log);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }
    return redirect($request -> url);
  }
}
