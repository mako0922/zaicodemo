<?php

namespace App\Http\Controllers;
use Intervention\Image\Facades\Image;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ZaicoController extends Controller
{
  public function zaico_home(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      return view('zaico_home.index', $param = ['users' => $user]);
    }else{
      return view('auth/login');
    }
  }

  public function zaico_input(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      $part_info = $request->old();
      $part_info += ['users' => $user];
      return view('zaico_input.index', $part_info);
    }else{
      return view('auth/login');
    }
  }

  public function register(Request $request){
    $validate_rule = [
      'rec_and_ship' => 'required',
      'partnumber' => 'min:0',
      'part_photo' => 'image',
    ];
    $this->validate($request, $validate_rule);
    $resize_path = public_path('img\logimage.png');
    if(!empty($request -> part_photo)){
      $image = Image::make(file_get_contents($request -> part_photo));
      $image->resize(200, null, function ($constraint) {
        $constraint->aspectRatio();
      });
      $image->save($resize_path);
      $part_photo64 = base64_encode($image);
    }else{
      $part_photo64 = "";
    }
    $param = [
      'part_number' => $request -> partName,
      'manufacturer' => $request -> manufacturer,
      'datetime' => date('Y-m-d H:i', strtotime('+9hour')),
      'staff_name' => $request -> staff_name,
      'utilization' => $request -> utilization,
      'rec_and_ship' => $request -> rec_and_ship,
      'partnumber' => $request -> partNumber,
      'class' => $request -> class_name,
      'part_photo' => $part_photo64,
    ];

    try{
      $part_info = DB::table('part_info')->where('part_name', $request -> partName)->first();
      if ($request -> rec_and_ship == '入荷'){
        $stock_update = $part_info -> stock + $request -> partNumber;
      }else if($request -> rec_and_ship == '出荷'){
        $stock_update = $part_info -> stock - $request -> partNumber;
      }else{
        $stock_update = $part_info -> stock;
      }
      $part_update = [
        'stock' => $stock_update,
      ];
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }

    try{
      DB::table('zaico_table')->insert($param);
      DB::table('part_info')->where('part_name', $request -> partName)->update($part_update);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }
    return redirect('/zaico_home');
  }

  public function class_input(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      return view('class_table.index', $param = ['users' => $user]);
    }else{
      return view('auth/login');
    }
  }

  public function class_register(Request $request){
    $validate_rule = [
      'class_name' => 'required',
    ];
    $this->validate($request, $validate_rule);

    $param = [
      'class' => $request -> class_name,
    ];
    try{
      DB::table('class_table')->insert($param);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }
    return redirect('/zaico_home');
  }

  public function manufacturer_input(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      return view('manufacturer_table.index', $param = ['users' => $user]);
    }else{
      return view('auth/login');
    }
  }

  public function manufacturer_register(Request $request){
    $validate_rule = [
      'manufacturer' => 'required',
    ];
    $this->validate($request, $validate_rule);

    $param = [
      'manufacturer' => $request -> manufacturer,
    ];
    try{
      DB::table('manufacturer_table')->insert($param);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }
    return redirect('/zaico_home');
  }

  public function part_info(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      try{
        $class = DB::table('class_table')->get();
        $manufacturer = DB::table('manufacturer_table')->get();
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }
      $param = [
        'users' => $user,
        'class_info' => $class,
        'manufacturer_info' => $manufacturer,
      ];
      return view('part_info.index', $param);
    }else{
      return view('auth/login');
    }
  }

  public function part_info_register(Request $request){
    $validate_rule = [
      'part_name' => 'required',
      'manufacturer' => 'required',
      'class_name' => 'required',
      'stock' => 'required',
    ];
    $this->validate($request, $validate_rule);
    $resize_path = public_path('img\new.png');
    $image = Image::make(file_get_contents($request -> part_photo));
    $image->resize(200, null, function ($constraint) {
      $constraint->aspectRatio();
    });
    $image->save($resize_path);
    $part_photo64 = base64_encode($image);

    $param = [
      'part_name' => $request -> part_name,
      'manufacturer' => $request -> manufacturer,
      'part_photo' => $part_photo64,
      'class' => $request -> class_name,
      'stock' => $request -> stock,
    ];
    try{
      DB::table('part_info')->insert($param);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }
    return redirect('/zaico_home');
  }

  public function zaico_list(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      try{
        $zaico_log = DB::table('zaico_table')->get();
        $class_table = DB::table('class_table')->get();
        $part_info = DB::table('part_info')->get();
        $manufacturer_info = DB::table('manufacturer_table')->get();
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }
      $param = [
        'users' => $user,
        'class_table' => $class_table,
        'part_info' => $part_info,
        'manufacturer_info' => $manufacturer_info,
        'log_select2' => $request -> log_select2,
        'log_select3' => $request -> log_select3,
        'log_select4' => $request -> log_select4,
      ];
      return view('zaico_list.index', $param);
    }else{

      return view('auth/login');
    }
  }

  public function zaico_input_arrival(Request $request){
    if (Auth::check()){
      $user = Auth::user();
      $validate_rule = [
      ];
      $this->validate($request, $validate_rule);
      try{
        $info = DB::table('part_info')->where('part_name', $request -> part_name)->first();
        $staff = DB::table('users')->get();
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }
      $param = [
        'users' => $user,
        'staff' => $staff,
        'info' => $info,
      ];
      $param += ['rec_and_ship' => $request -> rec_and_ship];
      return redirect('/zaico_input')->withInput($param);
    }else{
      return view('auth/login');
    }
  }

  public function zaico_input_utilize(Request $request){
    //$this->validate($request, Attendance::$rules);
    if (Auth::check()){
      $user = Auth::user();
      $validate_rule = [
      ];
      $this->validate($request, $validate_rule);
      try{
        $info = DB::table('part_info')->where('part_name', $request -> part_name)->first();
        $staff = DB::table('users')->get();
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }
      $param = [
        'users' => $user,
        'staff' => $staff,
        'info' => $info,
      ];
      $param += ['rec_and_ship' => $request -> rec_and_ship];
      return redirect('/zaico_input')->withInput($param);
    }else{
      return view('auth/login');
    }
  }

  public function zaico_log(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      try{
        $zaico_log = DB::table('zaico_table')->get();
        $class_table = DB::table('class_table')->get();
        $part_info = DB::table('part_info')->get();
        $manufacturer_info = DB::table('manufacturer_table')->get();
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }
      $param = [
        'users' => $user,
        'zaico_log' => $zaico_log,
        'class_table' => $class_table,
        'part_info' => $part_info,
        'manufacturer_info' => $manufacturer_info,
      ];
      return view('zaico_log.index', $param);
    }else{
      return view('auth/login');
    }
  }

  public function onchange_log(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      try{
        $zaico_log = DB::table('zaico_table');
        $class_table = DB::table('class_table')->get();
        $part_info = DB::table('part_info')->get();
        $manufacturer_info = DB::table('manufacturer_table')->get();
        if ($request -> log_select1 != ''){
          $zaico_log = $zaico_log -> where('rec_and_ship', $request -> log_select1);
        }
        if ($request -> log_select2 != ''){
          $zaico_log = $zaico_log -> where('class', $request -> log_select2);
        }
        if ($request -> log_select3 != ''){
          $zaico_log = $zaico_log -> where('part_number', $request -> log_select3);
        }
        if ($request -> log_select4 != ''){
          $zaico_log = $zaico_log -> where('manufacturer', $request -> log_select4);
        }
        $zaico_log = $zaico_log -> get();
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }
      $param = [
        'users' => $user,
        'zaico_log' => $zaico_log,
        'class_table' => $class_table,
        'part_info' => $part_info,
        'manufacturer_info' => $manufacturer_info,
        'log_select1' => $request -> log_select1,
        'log_select2' => $request -> log_select2,
        'log_select3' => $request -> log_select3,
        'log_select4' => $request -> log_select4,
      ];
      return view('zaico_log.index', $param);
    }else{
      return view('auth/login');
    }
  }

    public function onchange_list(Request $request){
      // ログインチェック
      if (Auth::check()){
        $user = Auth::user();
        try{
          $part_info = DB::table('part_info');
          $class_table = DB::table('class_table')->get();
          $manufacturer_info = DB::table('manufacturer_table')->get();
          if ($request -> log_select2 != ''){
            $part_info = $part_info -> where('class', $request -> log_select2);
          }
          if ($request -> log_select3 != ''){
            $part_info = $part_info -> where('part_name', $request -> log_select3);
          }
          if ($request -> log_select4 != ''){
            $part_info = $part_info -> where('manufacturer', $request -> log_select4);
          }
          $part_info = $part_info -> get();
        } catch (\Exception $e) {
          return redirect('/zaico_home');
        }
        $param = [
          'users' => $user,
          'part_info' => $part_info,
          'class_table' => $class_table,
          'manufacturer_info' => $manufacturer_info,
          'log_select2' => $request -> log_select2,
          'log_select3' => $request -> log_select3,
          'log_select4' => $request -> log_select4,
        ];
        return view('zaico_list.index', $param);
      }else{
        return view('auth/login');
      }
    }
}
