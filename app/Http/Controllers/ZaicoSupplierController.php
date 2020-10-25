<?php

namespace App\Http\Controllers;
use Intervention\Image\Facades\Image;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use My_func;

class ZaicoSupplierController extends Controller
{
  public function supplier_input(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      $supplier = DB::table('supplier_table')->get();
      $param = [
        'users' => $user,
        'supplier_info' => $supplier,
      ];
      return view('supplier_table.index', $param);
    }else{
      return view('auth/login');
    }
  }

  public function supplier_register(Request $request){
    $validate_rule = [
      'supplier_name_new' => 'required',
    ];
    $this->validate($request, $validate_rule);
    //$request->session()->regenerateToken();

    $param = [
      'supplier_name' => $request -> supplier_name_new,
    ];
    try{
      DB::table('supplier_table')->insert($param);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }
    return My_func::parameterInput('/supplier_input', $request);
  }
}
