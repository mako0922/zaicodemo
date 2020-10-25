<?php

namespace App\Http\Controllers;
use Intervention\Image\Facades\Image;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use My_func;

class ZaicoManufacturerController extends Controller
{
  public function manufacturer_input(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      $manufacturer = DB::table('manufacturer_table')->get();
      $param = [
        'users' => $user,
        'manufacturer_info' => $manufacturer,
      ];
      return view('manufacturer_table.index', $param);
    }else{
      return view('auth/login');
    }
  }

  public function manufacturer_register(Request $request){
    $validate_rule = [
      'manufacturer_new' => 'required',
    ];
    $this->validate($request, $validate_rule);
    $param = [
      'manufacturer' => $request -> manufacturer_new,
    ];
    try{
      DB::table('manufacturer_table')->insert($param);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }
    return My_func::parameterInput('/manufacturer_input', $request);
  }
}
