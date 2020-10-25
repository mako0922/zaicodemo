<?php

namespace App\Http\Controllers;
use Intervention\Image\Facades\Image;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use My_func;

class ZaicoStorageController extends Controller
{
  public function storage_input(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      $storage = DB::table('storage_table')->get();
      $param = [
        'users' => $user,
        'storage_info' => $storage,
      ];
      return view('storage_table.index', $param);
    }else{
      return view('auth/login');
    }
  }

  public function storage_register(Request $request){
    $validate_rule = [
      'storage_name_new' => 'required',
    ];
    $this->validate($request, $validate_rule);
    //$request->session()->regenerateToken();

    $param = [
      'storage_name' => $request -> storage_name_new,
    ];
    try{
      DB::table('storage_table')->insert($param);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }
    return My_func::parameterInput('/storage_input', $request);
  }
}
