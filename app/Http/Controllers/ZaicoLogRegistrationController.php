<?php

namespace App\Http\Controllers;
use Intervention\Image\Facades\Image;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use My_func;

class ZaicoLogRegistrationController extends Controller
{
  // 在庫ログから登録機能画面表示
  public function zaico_log_input_registration(Request $request){
    if (Auth::check()){
      $validate_rule = [
      ];
      $this->validate($request, $validate_rule);
      try{
        $info = DB::table('zaico_table')->where('id', $request -> part_id)->first();
        $zaico_status = DB::table('zaico_table')->groupBy('status')->get(['status']);
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }
      $param = My_func::tableRead();
      $param += [
        'info' => $info,
        'zaico_status' => $zaico_status,
        'url' => $request -> url,
      ];
      return redirect('/zaico_log_registration')->withInput($param);
    }else{
      return view('auth/login');
    }
  }

  // 在庫ログから登録機能実行
  public function zaico_log_registration(Request $request){

    if (Auth::check()){
      $param = My_func::tableRead();
      $param += $request->old();

      return view('zaico_log_registration.index', $param);
    }else{
      return view('auth/login');
    }

  }

  public function zaico_log_registration_register(Request $request){
    $validate_rule = [
      'part_name' => 'required',
      'manufacturer' => 'required',
      'class_name' => 'required',
      'stock' => 'required',
      'storage' => 'required',
      'status' => 'required',
    ];
    $this->validate($request, $validate_rule);

    list($param, $param_log) = My_func::partParaIni('ログから登録処理' , $request);

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

    return redirect($request -> url);
  }
}
