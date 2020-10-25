<?php

namespace App\Http\Controllers;
use Intervention\Image\Facades\Image;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use My_func;

class ZaicoStatusController extends Controller
{
  public function status_input(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      $status = DB::table('status_table')->get();
      $param = [
        'users' => $user,
        'status_info' => $status,
      ];
      return view('status_table.index', $param);
    }else{
      return view('auth/login');
    }
  }

  public function status_register(Request $request){
    $validate_rule = [
      'status_name_new' => 'required',
    ];
    $this->validate($request, $validate_rule);
    //$request->session()->regenerateToken();

    $param = [
      'status_name' => $request -> status_name_new,
    ];
    try{
      DB::table('status_table')->insert($param);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }
    return My_func::parameterInput('/status_input', $request);
  }
}
