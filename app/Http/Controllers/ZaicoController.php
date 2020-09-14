<?php

namespace App\Http\Controllers;
use Intervention\Image\Facades\Image;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

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
      if(!empty($request -> part_photo_origin)){
        $part_photo64 = $request -> part_photo_origin;
      }else{
        $part_photo64 = "";
      }
    }

    if(empty($request -> comment)){
      $comment = "";
    }else{
      $comment = $request -> comment;
    }

    if(empty($request -> manufacturer)){
      $manufacturer = "";
    }else{
      $manufacturer = $request -> manufacturer;
    }

    if(empty($request -> class_name)){
      $class_name = "";
    }else{
      $class_name = $request -> class_name;
    }

    if(empty($request -> storage_name)){
      $storage = "";
    }else{
      $storage = $request -> storage_name;
    }

    if(empty($request -> utilization)){
      $utilization = "";
    }else{
      $utilization = $request -> utilization;
    }

    if(empty($request -> cost_price_tax)){
      $cost_price_tax = "";
    }else{
      $cost_price_tax = $request -> cost_price_tax;
    }

    if(empty($request -> selling_price_tax)){
      $selling_price_tax = "";
    }else{
      $selling_price_tax = $request -> selling_price_tax;
    }

    if(empty($request -> revision_number)){
      $revision_number = "";
    }else{
      $revision_number = $request -> revision_number;
    }

    if(empty($request -> purchase_date)){
      $purchase_date = "0000-00-00";
    }else{
      $purchase_date = $request -> purchase_date;
    }

    $param = [
      'part_number' => $request -> partName,
      'manufacturer' => $manufacturer,
      'datetime' => date('Y-m-d H:i', strtotime('+9hour')),
      'staff_name' => $request -> staff_name,
      'utilization' => $utilization,
      'status' => $request -> rec_and_ship,
      'partnumber' => $request -> partNumber,
      'class' => $class_name,
      'storage_name' => $storage,
      'comment' => $comment,
      'part_photo' => $part_photo64,
      'cost_price' => $request -> cost_price,
      'cost_price_tax' => $cost_price_tax,
      'selling_price' => $request -> selling_price,
      'selling_price_tax' => $selling_price_tax,
      'revision_number' => $revision_number,
      'purchase_date' => $purchase_date
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

    //try{
      DB::table('zaico_table')->insert($param);
      DB::table('part_info')->where('part_name', $request -> partName)->update($part_update);
    //} catch (\Exception $e) {
    //  return redirect('/zaico_home');
    //}
    return redirect('/zaico_home');
  }

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
    if($request->hp_type === "part_info"){
      return redirect('/part_info');
    }else if($request->hp_type === "used_info"){
      return redirect('/used_info');
    }else if($request->hp_type === "part_update"){
      $user = Auth::user();
      $zaico_log = DB::table('zaico_table')->get();
      $class_info = DB::table('class_table')->get();
      $part_info = DB::table('part_info')->get();
      $storage_info = DB::table('storage_table')->get();
      $status_info = DB::table('status_table')->get();
      $manufacturer_info = DB::table('manufacturer_table')->get();
      $info = DB::table('part_info')->where('id', $request -> part_id)->first();

      $param = [
        'users' => $user,
        'part_info' => $part_info,
        'class_info' => $class_info,
        'manufacturer_info' => $manufacturer_info,
        'status_info' => $status_info,
        'storage_info' => $storage_info,
        'info' => $info,
      ];
      return redirect('/part_update')->withInput($param);
    }else{
      return redirect('/class_input');
    }
  }

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
    if($request->hp_type === "part_info"){
      return redirect('/part_info');
    }else if($request->hp_type === "used_info"){
      return redirect('/used_info');
    }else if($request->hp_type === "part_update"){
      $user = Auth::user();
      $zaico_log = DB::table('zaico_table')->get();
      $class_info = DB::table('class_table')->get();
      $part_info = DB::table('part_info')->get();
      $storage_info = DB::table('storage_table')->get();
      $status_info = DB::table('status_table')->get();
      $manufacturer_info = DB::table('manufacturer_table')->get();
      $info = DB::table('part_info')->where('id', $request -> part_id)->first();

      $param = [
        'users' => $user,
        'part_info' => $part_info,
        'class_info' => $class_info,
        'manufacturer_info' => $manufacturer_info,
        'status_info' => $status_info,
        'storage_info' => $storage_info,
        'info' => $info,
      ];
      return redirect('/part_update')->withInput($param);
    }else{
      return redirect('/manufacturer_input');
    }
  }

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
      'status_name' => 'required',
    ];
    $this->validate($request, $validate_rule);

    $param = [
      'status_name' => $request -> status_name,
    ];
    try{
      DB::table('status_table')->insert($param);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }
    if($request->hp_type === "part_info"){
      return redirect('/part_info');
    }else if($request->hp_type === "used_info"){
      return redirect('/used_info');
    }else if($request->hp_type === "part_update"){
      $user = Auth::user();
      $zaico_log = DB::table('zaico_table')->get();
      $class_info = DB::table('class_table')->get();
      $part_info = DB::table('part_info')->get();
      $storage_info = DB::table('storage_table')->get();
      $status_info = DB::table('status_table')->get();
      $manufacturer_info = DB::table('manufacturer_table')->get();
      $info = DB::table('part_info')->where('id', $request -> part_id)->first();

      $param = [
        'users' => $user,
        'part_info' => $part_info,
        'class_info' => $class_info,
        'manufacturer_info' => $manufacturer_info,
        'status_info' => $status_info,
        'storage_info' => $storage_info,
        'info' => $info,
      ];
      return redirect('/part_update')->withInput($param);
    }else{
      return redirect('/status_input');
    }
  }

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
      'storage_name' => 'required',
    ];
    $this->validate($request, $validate_rule);

    $param = [
      'storage_name' => $request -> storage_name,
    ];
    try{
      DB::table('storage_table')->insert($param);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }
    if($request->hp_type === "part_info"){
      return redirect('/part_info');
    }else if($request->hp_type === "used_info"){
      return redirect('/used_info');
    }else if($request->hp_type === "part_update"){
      $user = Auth::user();
      $zaico_log = DB::table('zaico_table')->get();
      $class_info = DB::table('class_table')->get();
      $part_info = DB::table('part_info')->get();
      $storage_info = DB::table('storage_table')->get();
      $status_info = DB::table('status_table')->get();
      $manufacturer_info = DB::table('manufacturer_table')->get();
      $info = DB::table('part_info')->where('id', $request -> part_id)->first();

      $param = [
        'users' => $user,
        'part_info' => $part_info,
        'class_info' => $class_info,
        'manufacturer_info' => $manufacturer_info,
        'status_info' => $status_info,
        'storage_info' => $storage_info,
        'info' => $info,
      ];
      return redirect('/part_update')->withInput($param);
    }else{
      return redirect('/storage_input');
    }
  }

  public function part_info_select(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      try{
        $class = DB::table('class_table')->get();
        $manufacturer = DB::table('manufacturer_table')->get();
        $storage_name = DB::table('storage_table')->get();
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }
      $param = [
        'users' => $user,
        'class_info' => $class,
        'manufacturer_info' => $manufacturer,
        'storage_info' => $storage_name,
      ];
      return view('part_info_select.index', $param);
    }else{
      return view('auth/login');
    }
  }

  public function part_info(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      try{
        $class = DB::table('class_table')->get();
        $manufacturer = DB::table('manufacturer_table')->get();
        $storage_name = DB::table('storage_table')->get();
        $status_name = DB::table('status_table')->get();
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }
      $param = [
        'users' => $user,
        'class_info' => $class,
        'manufacturer_info' => $manufacturer,
        'storage_info' => $storage_name,
        'status_info' => $status_name,
      ];
      return view('part_info.index', $param);
    }else{
      return view('auth/login');
    }
  }

  public function part_info_register(Request $request){
    $user = Auth::user();
    $validate_rule = [
      'part_name' => 'required',
      'manufacturer' => 'required',
      'class_name' => 'required',
      'stock' => 'required',
      'storage' => 'required',
    ];
    $this->validate($request, $validate_rule);
    $resize_path = public_path('img\new.png');

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

    if(empty($request -> comment)){
      $comment = "";
    }else{
      $comment = $request -> comment;
    }

    if(empty($request -> status)){
      $status = "";
    }else{
      $status = $request -> status;
    }

    if(empty($request -> cost_price)){
      $cost_price = 0;
    }else{
      $cost_price = $request -> cost_price;
    }

    if(empty($request -> selling_price)){
      $selling_price = 0;
    }else{
      $selling_price = $request -> selling_price;
    }

    if(!empty($request -> part_photo1)){
      $image1 = Image::make(file_get_contents($request -> part_photo1));
      $image1->resize(100, null, function ($constraint) {
        $constraint->aspectRatio();
    });
      $image1->save($resize_path);
      $part_photo64_1 = base64_encode($image1);
    }else{
      $part_photo64_1 = "";
    }

    if(!empty($request -> part_photo2)){
      $image2 = Image::make(file_get_contents($request -> part_photo2));
      $image2->resize(100, null, function ($constraint) {
        $constraint->aspectRatio();
    });
      $image2->save($resize_path);
      $part_photo64_2 = base64_encode($image2);
    }else{
      $part_photo64_2 = "";
    }

    if(!empty($request -> part_photo3)){
      $image3 = Image::make(file_get_contents($request -> part_photo3));
      $image3->resize(100, null, function ($constraint) {
        $constraint->aspectRatio();
    });
      $image3->save($resize_path);
      $part_photo64_3 = base64_encode($image3);
    }else{
      $part_photo64_3 = "";
    }

    if(empty($request -> revision_number)){
      $revision_number = "";
    }else{
      $revision_number = $request -> revision_number;
    }

    if(empty($request -> purchase_date)){
      $purchase_date = "0000-00-00";
    }else{
      $purchase_date = $request -> purchase_date;
    }

    $param = [
      'part_name' => $request -> part_name,
      'manufacturer' => $request -> manufacturer,
      'part_photo' => $part_photo64,
      'sub_part_photo_1' => $part_photo64_1,
      'sub_part_photo_2' => $part_photo64_2,
      'sub_part_photo_3' => $part_photo64_3,
      'class' => $request -> class_name,
      'stock' => $request -> stock,
      'storage_name' => $request -> storage,
      'comment' => $comment,
      'status' => $status,
      'cost_price' => $cost_price,
      'cost_price_tax' => $request -> cost_price_tax,
      'selling_price' => $selling_price,
      'selling_price_tax' => $request -> selling_price_tax,
      'revision_number' => $revision_number,
      'purchase_date' => $purchase_date,
    ];
    try{
      DB::table('part_info')->insert($param);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }

    $param_log = [
      'part_number' => $request -> part_name,
      'manufacturer' => $request -> manufacturer,
      'datetime' => date('Y-m-d H:i', strtotime('+9hour')),
      'staff_name' => $user -> name,
      'utilization' => '新規登録',
      'status' => $status,
      'partnumber' => $request -> stock,
      'storage_name' => $request -> storage,
      'comment' => $comment,
      'class' => $request -> class_name,
      'part_photo' => $part_photo64,
      'cost_price' => $cost_price,
      'cost_price_tax' => $request -> cost_price_tax,
      'selling_price' => $selling_price,
      'selling_price_tax' => $request -> selling_price_tax,
      'revision_number' => $revision_number,
      'purchase_date' => $purchase_date,
    ];
    try{
      DB::table('zaico_table')->insert($param_log);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }

    return redirect('/zaico_home');
  }

  public function part_update(Request $request){

    if (Auth::check()){
      $user = Auth::user();
      $status_name = DB::table('status_table')->get();
      $part_info = $request->old();
      $part_info += ['users' => $user];
      $part_info += ['status_info' => $status_name];
      return view('part_update.index', $part_info);
    }else{
      return view('auth/login');
    }

  }

  public function part_update_register(Request $request){
    $user = Auth::user();
    $validate_rule = [
      'part_name' => 'required',
      'stock' => 'required',
    ];
    $this->validate($request, $validate_rule);
    $resize_path = public_path('img\new.png');

    if(!empty($request -> part_photo)){
      $image = Image::make(file_get_contents($request -> part_photo));
      $image->resize(200, null, function ($constraint) {
        $constraint->aspectRatio();
      });
      $image->save($resize_path);
      $part_photo64 = base64_encode($image);
    }else{
      if(!empty($request -> part_photo_origin)){
        $part_photo64 = $request -> part_photo_origin;
      }else{
        $part_photo64 = "";
      }
    }

    if(empty($request -> comment)){
      $comment = "";
    }else{
      $comment = $request -> comment;
    }

    if(empty($request -> manufacturer)){
      $manufacturer = "";
    }else{
      $manufacturer = $request -> manufacturer;
    }

    if(empty($request -> class_name)){
      $class_name = "";
    }else{
      $class_name = $request -> class_name;
    }

    if(empty($request -> storage)){
      $storage = "";
    }else{
      $storage = $request -> storage;
    }

    if(empty($request -> status)){
      $status = "";
    }else{
      $status = $request -> status;
    }

    if(empty($request -> cost_price)){
      $cost_price = 0;
    }else{
      $cost_price = $request -> cost_price;
    }

    if(empty($request -> selling_price)){
      $selling_price = 0;
    }else{
      $selling_price = $request -> selling_price;
    }

    if(!empty($request -> part_photo1)){
      $image1 = Image::make(file_get_contents($request -> part_photo1));
      $image1->resize(100, null, function ($constraint) {
        $constraint->aspectRatio();
    });
      $image1->save($resize_path);
      $part_photo64_1 = base64_encode($image1);
    }else{
      if(!empty($request -> part_photo1_origin)){
        $part_photo64_1 = $request -> part_photo1_origin;
      }else{
        $part_photo64_1 = "";
      }
    }

    if(!empty($request -> part_photo2)){
      $image2 = Image::make(file_get_contents($request -> part_photo2));
      $image2->resize(100, null, function ($constraint) {
        $constraint->aspectRatio();
    });
      $image2->save($resize_path);
      $part_photo64_2 = base64_encode($image2);
    }else{
      if(!empty($request -> part_photo2_origin)){
        $part_photo64_2 = $request -> part_photo2_origin;
      }else{
        $part_photo64_2 = "";
      }
    }

    if(!empty($request -> part_photo3)){
      $image3 = Image::make(file_get_contents($request -> part_photo3));
      $image3->resize(100, null, function ($constraint) {
        $constraint->aspectRatio();
    });
      $image3->save($resize_path);
      $part_photo64_3 = base64_encode($image3);
    }else{
      if(!empty($request -> part_photo3_origin)){
        $part_photo64_3 = $request -> part_photo3_origin;
      }else{
        $part_photo64_3 = "";
      }
    }

    if(empty($request -> revision_number)){
      $revision_number = "";
    }else{
      $revision_number = $request -> revision_number;
    }

    if(empty($request -> purchase_date)){
      $purchase_date = "0000-00-00";
    }else{
      $purchase_date = $request -> purchase_date;
    }

    $param = [
      'part_name' => $request -> part_name,
      'manufacturer' => $manufacturer,
      'part_photo' => $part_photo64,
      'sub_part_photo_1' => $part_photo64_1,
      'sub_part_photo_2' => $part_photo64_2,
      'sub_part_photo_3' => $part_photo64_3,
      'class' => $class_name,
      'stock' => $request -> stock,
      'storage_name' => $storage,
      'comment' => $comment,
      'status' => $request -> status,
      'cost_price' => $cost_price,
      'cost_price_tax' => $request -> cost_price_tax,
      'selling_price' => $selling_price,
      'selling_price_tax' => $request -> selling_price_tax,
      'revision_number' => $revision_number,
      'purchase_date' => $purchase_date,
    ];
    try{
      DB::table('part_info')->where('id', $request -> part_id)->update($param);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }

    $param_log = [
      'part_number' => $request -> part_name,
      'manufacturer' => $manufacturer,
      'datetime' => date('Y-m-d H:i', strtotime('+9hour')),
      'staff_name' => $user -> name,
      'utilization' => '在庫管理修正処理',
      'status' => $request -> status,
      'partnumber' => $request -> stock,
      'storage_name' => $storage,
      'comment' => $comment,
      'class' => $request -> class_name,
      'part_photo' => $part_photo64,
      'cost_price' => $cost_price,
      'cost_price_tax' => $request -> cost_price_tax,
      'selling_price' => $selling_price,
      'selling_price_tax' => $request -> selling_price_tax,
      'revision_number' => $revision_number,
      'purchase_date' => $purchase_date,
    ];
    try{
      DB::table('zaico_table')->insert($param_log);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }

    return redirect('/zaico_home');
  }

  public function part_delete(Request $request){

    if (Auth::check()){
      $user = Auth::user();
      $part_info = $request->old();
      $part_info += ['users' => $user];
      return view('part_delete.index', $part_info);
    }else{
      return view('auth/login');
    }

  }

  public function part_delete_register(Request $request){
    $user = Auth::user();
    $validate_rule = [
      'part_name' => 'required',
      'stock' => 'required',
    ];
    $this->validate($request, $validate_rule);
    $resize_path = public_path('img\new.png');

    if(!empty($request -> part_photo)){
      $part_photo64 = $request -> part_photo;
    }else{
      $part_photo64 = "";
    }

    if(empty($request -> comment)){
      $comment = "";
    }else{
      $comment = $request -> comment;
    }

    if(empty($request -> manufacturer)){
      $manufacturer = "";
    }else{
      $manufacturer = $request -> manufacturer;
    }

    if(empty($request -> class_name)){
      $class_name = "";
    }else{
      $class_name = $request -> class_name;
    }

    if(empty($request -> storage_name)){
      $storage = "";
    }else{
      $storage = $request -> storage;
    }

    if(empty($request -> cost_price)){
      $cost_price = 0;
    }else{
      $cost_price = $request -> cost_price;
    }

    if(empty($request -> selling_price)){
      $selling_price = 0;
    }else{
      $selling_price = $request -> selling_price;
    }

    if(empty($request -> revision_number)){
      $revision_number = "";
    }else{
      $revision_number = $request -> revision_number;
    }

    if(empty($request -> purchase_date)){
      $purchase_date = "0000-00-00";
    }else{
      $purchase_date = $request -> purchase_date;
    }

    $param = [
      'part_name' => $request -> part_name,
      'manufacturer' => $manufacturer,
      'part_photo' => $part_photo64,
      'class' => $class_name,
      'stock' => $request -> stock,
      'storage_name' => $storage,
      'comment' => $comment,
      'status' => $request -> status,
      'cost_price' => $cost_price,
      'cost_price_tax' => $request -> cost_price_tax,
      'selling_price' => $selling_price,
      'selling_price_tax' => $request -> selling_price_tax,
      'revision_number' => $revision_number,
      'purchase_date' => $purchase_date,
    ];
    try{
      DB::table('part_info')->where('part_name', $request -> part_name)->delete();
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }

    $param_log = [
      'part_number' => $request -> part_name,
      'manufacturer' => $manufacturer,
      'datetime' => date('Y-m-d H:i', strtotime('+9hour')),
      'staff_name' => $user -> name,
      'utilization' => '在庫管理データ削除処理',
      'status' => $request -> status,
      'partnumber' => $request -> stock,
      'storage_name' => $storage,
      'comment' => $comment,
      'class' => $request -> class_name,
      'part_photo' => $part_photo64,
      'cost_price' => $cost_price,
      'cost_price_tax' => $request -> cost_price_tax,
      'selling_price' => $selling_price,
      'selling_price_tax' => $request -> selling_price_tax,
      'revision_number' => $revision_number,
      'purchase_date' => $purchase_date,
    ];
    try{
      DB::table('zaico_table')->insert($param_log);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }

    return redirect('/zaico_home');
  }

  public function used_info(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      try{
        $class = DB::table('class_table')->get();
        $manufacturer = DB::table('manufacturer_table')->get();
        $storage_name = DB::table('storage_table')->get();
        $status_info = DB::table('status_table')->get();
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }
      $param = [
        'users' => $user,
        'class_info' => $class,
        'manufacturer_info' => $manufacturer,
        'storage_info' => $storage_name,
        'status_info' => $status_info,
      ];
      return view('used_info.index', $param);
    }else{
      return view('auth/login');
    }
  }

  public function used_info_register(Request $request){
    $user = Auth::user();
    $validate_rule = [
      'part_name' => 'required',
      'stock' => 'required',
    ];
    $this->validate($request, $validate_rule);
    $resize_path = public_path('img\new.png');

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

    if(empty($request -> comment)){
      $comment = "";
    }else{
      $comment = $request -> comment;
    }

    if(empty($request -> manufacturer)){
      $manufacturer = "";
    }else{
      $manufacturer = $request -> manufacturer;
    }

    if(empty($request -> class_name)){
      $class_name = "";
    }else{
      $class_name = $request -> class_name;
    }

    if(empty($request -> storage)){
      $storage = "";
    }else{
      $storage = $request -> storage;
    }

    if(empty($request -> status)){
      $status = "";
    }else{
      $status = $request -> status;
    }

    if(empty($request -> cost_price)){
      $cost_price = 0;
    }else{
      $cost_price = $request -> cost_price;
    }

    if(empty($request -> selling_price)){
      $selling_price = 0;
    }else{
      $selling_price = $request -> selling_price;
    }

    if(!empty($request -> part_photo1)){
      $image1 = Image::make(file_get_contents($request -> part_photo1));
      $image1->resize(100, null, function ($constraint) {
        $constraint->aspectRatio();
    });
      $image1->save($resize_path);
      $part_photo64_1 = base64_encode($image1);
    }else{
      $part_photo64_1 = "";
    }

    if(!empty($request -> part_photo2)){
      $image2 = Image::make(file_get_contents($request -> part_photo2));
      $image2->resize(100, null, function ($constraint) {
        $constraint->aspectRatio();
    });
      $image2->save($resize_path);
      $part_photo64_2 = base64_encode($image2);
    }else{
      $part_photo64_2 = "";
    }

    if(!empty($request -> part_photo3)){
      $image3 = Image::make(file_get_contents($request -> part_photo3));
      $image3->resize(100, null, function ($constraint) {
        $constraint->aspectRatio();
    });
      $image3->save($resize_path);
      $part_photo64_3 = base64_encode($image3);
    }else{
      $part_photo64_3 = "";
    }

    if(empty($request -> revision_number)){
      $revision_number = "";
    }else{
      $revision_number = $request -> revision_number;
    }

    if(empty($request -> purchase_date)){
      $purchase_date = "0000-00-00";
    }else{
      $purchase_date = $request -> purchase_date;
    }

    $param = [
      'part_name' => $request -> part_name,
      'manufacturer' => $manufacturer,
      'part_photo' => $part_photo64,
      'sub_part_photo_1' => $part_photo64_1,
      'sub_part_photo_2' => $part_photo64_2,
      'sub_part_photo_3' => $part_photo64_3,
      'class' => $class_name,
      'stock' => $request -> stock,
      'storage_name' => $storage,
      'comment' => $comment,
      'status' => $status,
      'cost_price' => $cost_price,
      'cost_price_tax' => $request -> cost_price_tax,
      'selling_price' => $selling_price,
      'selling_price_tax' => $request -> selling_price_tax,
      'revision_number' => $revision_number,
      'purchase_date' => $purchase_date,
    ];
    try{
      DB::table('part_info')->insert($param);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }

    $param_log = [
      'part_number' => $request -> part_name,
      'manufacturer' => $request -> manufacturer,
      'datetime' => date('Y-m-d H:i', strtotime('+9hour')),
      'staff_name' => $user -> name,
      'utilization' => '新規登録',
      'status' => $status,
      'partnumber' => $request -> stock,
      'storage_name' => $request -> storage,
      'comment' => $comment,
      'class' => $request -> class_name,
      'part_photo' => $part_photo64,
      'cost_price' => $cost_price,
      'cost_price_tax' => $request -> cost_price_tax,
      'selling_price' => $selling_price,
      'selling_price_tax' => $request -> selling_price_tax,
      'revision_number' => $revision_number,
      'purchase_date' => $purchase_date,
    ];
    try{
      DB::table('zaico_table')->insert($param_log);
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
        $status_info = DB::table('status_table')->get();
        $manufacturer_info = DB::table('manufacturer_table')->get();
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }
      $param = [
        'users' => $user,
        'class_table' => $class_table,
        'part_info' => $part_info,
        'manufacturer_info' => $manufacturer_info,
        'status_info' => $status_info,
        'log_select1' => $request -> log_select1,
        'log_select2' => $request -> log_select2,
        'log_select3' => $request -> log_select3,
        'log_select4' => $request -> log_select4,
      ];
      return view('zaico_list.index', $param);
    }else{

      return view('auth/login');
    }
  }

  public function part_list_serch(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      try{
        $columns = Schema::getColumnListing('part_info');
        $zaico_log = DB::table('zaico_table')->get();
        $class_table = DB::table('class_table')->get();
        $part_info = DB::table('part_info');
        $manufacturer_info = DB::table('manufacturer_table')->get();
        $status_info = DB::table('status_table')->get();
        foreach ($columns as $column) {
          $part_info = $part_info -> orwhere($column, 'like', '%'. $request -> keyword . '%');
        }
        $part_info = $part_info -> orderBy('id', 'desc');
        $part_info = $part_info -> get();

      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }
      $param = [
        'users' => $user,
        'class_table' => $class_table,
        'part_info' => $part_info,
        'keyword' => $request -> keyword,
        'manufacturer_info' => $manufacturer_info,
        'status_info' => $status_info,
        'log_select1' => $request -> log_select1,
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
        $zaico_info = DB::table('zaico_table')->groupBy('utilization')->get(['utilization']);
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }

      $param = [
        'users' => $user,
        'staff' => $staff,
        'info' => $info,
        'utilization_info' => $zaico_info,
      ];

      $param += ['status' => $request -> rec_and_ship];
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
        $zaico_info = DB::table('zaico_table')->groupBy('utilization')->get(['utilization']);
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }
      $param = [
        'users' => $user,
        'staff' => $staff,
        'info' => $info,
        'utilization_info' => $zaico_info,
      ];
      $param += ['status' => $request -> rec_and_ship];
      return redirect('/zaico_input')->withInput($param);
    }else{
      return view('auth/login');
    }
  }

  public function zaico_input_update(Request $request){
    //$this->validate($request, Attendance::$rules);
    if (Auth::check()){
      $user = Auth::user();
      $validate_rule = [
      ];
      $this->validate($request, $validate_rule);
      try{
        $info = DB::table('part_info')->where('part_name', $request -> part_name)->first();
        $zaico_status = DB::table('zaico_table')->groupBy('status')->get(['status']);
        $staff = DB::table('users')->get();
        $class = DB::table('class_table')->get();
        $manufacturer = DB::table('manufacturer_table')->get();
        $storage_name = DB::table('storage_table')->get();
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }

      $param = [
        'users' => $user,
        'info' => $info,
        'staff' => $staff,
        'class_info' => $class,
        'manufacturer_info' => $manufacturer,
        'storage_info' => $storage_name,
        'zaico_status' => $zaico_status,
      ];

      $param += ['status' => $request -> status];
      return redirect('/part_update')->withInput($param);
    }else{
      return view('auth/login');
    }
  }

  public function zaico_input_delete(Request $request){
    //$this->validate($request, Attendance::$rules);
    if (Auth::check()){
      $user = Auth::user();
      $validate_rule = [
      ];
      $this->validate($request, $validate_rule);
      try{
        $info = DB::table('part_info')->where('part_name', $request -> part_name)->first();
        $zaico_status = DB::table('zaico_table')->groupBy('status')->get(['status']);
        $staff = DB::table('users')->get();
        $class = DB::table('class_table')->get();
        $manufacturer = DB::table('manufacturer_table')->get();
        $storage_name = DB::table('storage_table')->get();
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }

      $param = [
        'users' => $user,
        'info' => $info,
        'staff' => $staff,
        'class_info' => $class,
        'manufacturer_info' => $manufacturer,
        'storage_info' => $storage_name,
        'zaico_status' => $zaico_status,
      ];

      $param += ['status' => $request -> status];
      return redirect('/part_delete')->withInput($param);
    }else{
      return view('auth/login');
    }
  }

  public function zaico_log(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      try{
        $zaico_log = DB::table('zaico_table')->orderBy('id', 'desc')->get();
        $class_table = DB::table('class_table')->get();
        $part_info = DB::table('part_info')->get();
        $status_info = DB::table('status_table')->get();
        $manufacturer_info = DB::table('manufacturer_table')->get();
        $columns = Schema::getColumnListing('zaico_table');
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }
      $param = [
        'users' => $user,
        'zaico_log' => $zaico_log,
        'class_table' => $class_table,
        'part_info' => $part_info,
        'manufacturer_info' => $manufacturer_info,
        'status_info' => $status_info,
        'columns' => $columns,
      ];
      return view('zaico_log.index', $param);
    }else{
      return view('auth/login');
    }
  }

  public function zaico_log_input_delete(Request $request){
    //$this->validate($request, Attendance::$rules);
    if (Auth::check()){
      $user = Auth::user();
      $validate_rule = [
      ];
      $this->validate($request, $validate_rule);
      try{
        $info = DB::table('zaico_table')->where('id', $request -> part_id)->first();
        $zaico_status = DB::table('zaico_table')->groupBy('status')->get(['status']);
        $staff = DB::table('users')->get();
        $class = DB::table('class_table')->get();
        $manufacturer = DB::table('manufacturer_table')->get();
        $storage_name = DB::table('storage_table')->get();
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }

      $param = [
        'users' => $user,
        'info' => $info,
        'staff' => $staff,
        'class_info' => $class,
        'manufacturer_info' => $manufacturer,
        'storage_info' => $storage_name,
        'zaico_status' => $zaico_status,
      ];

      $param += ['status' => $request -> status];
      return redirect('/zaico_log_delete')->withInput($param);
    }else{
      return view('auth/login');
    }
  }

  public function zaico_log_delete(Request $request){

    if (Auth::check()){
      $user = Auth::user();
      $part_info = $request->old();
      $part_info += ['users' => $user];
      return view('zaico_log_delete.index', $part_info);
    }else{
      return view('auth/login');
    }

  }

  public function zaico_log_delete_register(Request $request){
    $user = Auth::user();
    $validate_rule = [
      'part_name' => 'required',
      'stock' => 'required',
    ];
    $this->validate($request, $validate_rule);
    $resize_path = public_path('img\new.png');

    if(!empty($request -> part_photo)){
      $part_photo64 = $request -> part_photo;
    }else{
      $part_photo64 = "";
    }

    if(empty($request -> comment)){
      $comment = "";
    }else{
      $comment = $request -> comment;
    }

    if(empty($request -> manufacturer)){
      $manufacturer = "";
    }else{
      $manufacturer = $request -> manufacturer;
    }

    if(empty($request -> class_name)){
      $class_name = "";
    }else{
      $class_name = $request -> class_name;
    }

    if(empty($request -> storage_name)){
      $storage = "";
    }else{
      $storage = $request -> storage;
    }

    if(empty($request -> revision_number)){
      $revision_number = "";
    }else{
      $revision_number = $request -> revision_number;
    }

    if(empty($request -> purchase_date)){
      $purchase_date = "0000-00-00";
    }else{
      $purchase_date = $request -> purchase_date;
    }

    $param = [
      'part_name' => $request -> part_name,
      'manufacturer' => $manufacturer,
      'part_photo' => $part_photo64,
      'class' => $class_name,
      'stock' => $request -> stock,
      'storage_name' => $storage,
      'comment' => $comment,
      'status' => $request -> status,
      'revision_number' => $revision_number,
      'purchase_date' => $purchase_date,
    ];
    try{
      DB::table('zaico_table')->where('id', $request -> id)->delete();
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }

    return redirect('/zaico_home');
  }

  public function table_item_delete(Request $request){
    $user = Auth::user();

    $status = DB::table('status_table')->get();
    $class = DB::table('class_table')->get();
    $manufacturer = DB::table('manufacturer_table')->get();
    $storage  = DB::table('storage_table')->get();

    if($request -> table_item == "class_table"){
      $re_hp = "/class_input";
    }else if($request -> table_item == "manufacturer_table"){
      $re_hp = "/manufacturer_input";
    }else if($request -> table_item == "status_table"){
      $re_hp = "/status_input";
    }else if($request -> table_item == "storage_table"){
      $re_hp = "/storage_input";
    }

    $param = [
      'manufacturer_info' => $manufacturer,
      'class_info' => $class,
      'storage_info' => $storage,
      'status_info' => $status,
    ];
    try{
      DB::table( $request -> table_item)->where('id', $request -> id)->delete();
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }
    return redirect($re_hp)->withInput($param);
  }


  public function zaico_log_serch(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      try{
        $columns = Schema::getColumnListing('zaico_table');
        $zaico_log = DB::table('zaico_table');
        $class_table = DB::table('class_table')->get();
        $part_info = DB::table('part_info')->get();
        $status_info = DB::table('status_table')->get();
        $manufacturer_info = DB::table('manufacturer_table')->get();
        foreach ($columns as $column) {
          $zaico_log = $zaico_log -> orwhere($column, 'like', '%'. $request -> keyword . '%');
        }
        $zaico_log = $zaico_log -> orderBy('id', 'desc');
        $zaico_log = $zaico_log -> get();
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }
      $param = [
        'users' => $user,
        'zaico_log' => $zaico_log,
        'class_table' => $class_table,
        'part_info' => $part_info,
        'cloums' => $columns,
        'manufacturer_info' => $manufacturer_info,
        'status_info' => $status_info,
        'keyword' => $request -> keyword,
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

  public function onchange_log(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      try{
        $zaico_log = DB::table('zaico_table');
        $class_table = DB::table('class_table')->get();
        $part_info = DB::table('part_info')->get();
        $status_info = DB::table('status_table')->get();
        $manufacturer_info = DB::table('manufacturer_table')->get();
        if ($request -> log_select1 != ''){
          $zaico_log = $zaico_log -> where('status', $request -> log_select1);
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
        'status_info' => $status_info,
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
          $status_info = DB::table('status_table')->get();
          $manufacturer_info = DB::table('manufacturer_table')->get();
          if ($request -> log_select1 != ''){
            $part_info = $part_info -> where('status', $request -> log_select1);
          }
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
          'status_info' => $status_info,
          'log_select1' => $request -> log_select1,
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
