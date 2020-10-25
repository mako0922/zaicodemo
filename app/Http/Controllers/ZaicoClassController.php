<?php

namespace App\Http\Controllers;
use Intervention\Image\Facades\Image;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use My_func;

class ZaicoClassController extends Controller
{
  public function class_input(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      $class_info = DB::table('class_table')->get();
      $param = [
        'users' => $user,
        'class_info' => $class_info,
      ];
      return view('class_table.index', $param);
    }else{
      return view('auth/login');
    }
  }

  public function class_register(Request $request){
    $validate_rule = [
      'class_name_new' => 'required',
    ];
    $this->validate($request, $validate_rule);
    //$request->session()->regenerateToken();

    $param = [
      'class' => $request -> class_name_new,
    ];
    try{
      DB::table('class_table')->insert($param);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }
    return My_func::parameterInput('/class_input', $request);
  }
}
