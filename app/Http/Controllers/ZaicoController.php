<?php

namespace App\Http\Controllers;
use Intervention\Image\Facades\Image;

use Symfony\Component\HttpFoundation\StreamedResponse;
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
      $status_info = DB::table('status_table')->get();
      $part_info = $request->old();
      $info = DB::table('part_info')->where('id', $request -> id)->first();
      $part_info += ['users' => $user];
      $part_info += ['status_info' => $status_info];
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
    //$request->session()->regenerateToken();
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
      $purchase_date = "2014-09-10";
    }else{
      $purchase_date = $request -> purchase_date;
    }

    if(empty($request -> supplier)){
      $supplier = "";
    }else{
      $supplier = $request -> supplier;
    }

    $part_info_master = DB::table('part_info')->where('id', $request -> id)->first();
    $change_flag = change_flag($part_info_master, $request);

    $param = [
      'part_number' => $request -> partName,
      'manufacturer' => $manufacturer,
      'datetime' => date('Y-m-d H:i', strtotime('+9hour')),
      'staff_name' => $request -> staff_name,
      'utilization' => $utilization,
      'status' => $request -> status,
      'partnumber' => $request -> partNumber,
      'class' => $class_name,
      'supplier' => $supplier,
      'storage_name' => $storage,
      'comment' => $comment,
      'part_photo' => $part_photo64,
      'cost_price' => $request -> cost_price,
      'cost_price_tax' => $cost_price_tax,
      'selling_price' => $request -> selling_price,
      'selling_price_tax' => $selling_price_tax,
      'revision_number' => $revision_number,
      'purchase_date' => $purchase_date,
      'new_used' => $request -> new_used,
    ];

    try{
      $part_info = DB::table('part_info')->where('id', $request -> id)->first();
      if ($request -> rec_and_ship == '入荷'){
        $stock_update = $part_info -> stock + $request -> partNumber;
      }else if($request -> rec_and_ship == '出荷'){
        $stock_update = $part_info -> stock - $request -> partNumber;
      }else{
        $stock_update = $part_info -> stock;
      }
      $part_update = [
        'stock' => $stock_update,
        'status' => $request -> status,
        'comment' => $comment,
      ];
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }

    $param_log = [
      'part_number' => $request -> partName,
      'manufacturer' => $manufacturer,
      'datetime' => date('Y-m-d H:i', strtotime('+9hour')),
      'staff_name' => $request -> staff_name,
      'utilization' => $utilization,
      'status' => $request -> status,
      'partnumber' => $request -> partNumber,
      'class' => $class_name,
      'supplier' => $supplier,
      'storage_name' => $storage,
      'comment' => $comment,
      'part_photo' => $part_photo64,
      'cost_price' => $request -> cost_price,
      'cost_price_tax' => $cost_price_tax,
      'selling_price' => $request -> selling_price,
      'selling_price_tax' => $selling_price_tax,
      'revision_number' => $revision_number,
      'purchase_date' => $purchase_date,
      'new_used' => $request -> new_used,

      'status_change' => $change_flag['status_change'],
      'partnumber_change' => $change_flag['partnumber_change'],
      'storage_name_change' => $change_flag['storage_name_change'],
      'comment_change' => $change_flag['comment_change'],
    ];

    //try{
      DB::table('zaico_table')->insert($param_log);
      if($stock_update <= 0 and ($request -> new_used == "新品-常時在庫管理無し" or $request -> new_used == "中古-常時在庫管理無し")){
        DB::table('part_info')->where('id', $request -> id)->delete();
      }else{
        DB::table('part_info')->where('id', $request -> id)->update($part_update);
      }
    //} catch (\Exception $e) {
    //  return redirect('/zaico_home');
    //}
    return redirect($request -> url);
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
    if($request->hp_type === "part_info"){
      return redirect('/part_info')->withInput();
    }else if($request->hp_type === "used_info"){
      return redirect('/used_info')->withInput();
    }else if($request->hp_type === "part_update" or $request->hp_type === "zaico_log_registration"){
      $user = Auth::user();
      $zaico_log = DB::table('zaico_table')->orderBy('id', 'desc')->get();
      $class_info = DB::table('class_table')->get();
      $part_info = DB::table('part_info')->orderBy('id', 'desc')->get();
      $storage_info = DB::table('storage_table')->get();
      $status_info = DB::table('status_table')->get();
      $manufacturer_info = DB::table('manufacturer_table')->get();
      $supplier_info = DB::table('supplier_table')->get();
      if($request->hp_type === "part_update"){
        $info = DB::table('part_info')->where('id', $request -> part_id)->first();
      }else if($request->hp_type === "zaico_log_registration"){
        $info = DB::table('zaico_table')->where('id', $request -> part_id)->first();
      }

      $revision_number_old = $request -> revision_number;
      $part_name_old = $request -> part_name;
      $manufacturer_old = $request -> manufacturer;
      $class_name_old = $request -> class_name;
      $storage_old = $request -> storage;
      $status_old = $request -> status;
      $supplier_old = $request -> supplier;
      $purchase_date_old = $request -> purchase_date;
      $cost_price_old = $request -> cost_price;
      $cost_price_tax_old = $request -> cost_price_tax;
      $selling_price_old = $request -> selling_price;
      $selling_price_tax_old = $request -> selling_price_tax;
      $stock_old = $request -> stock;
      $comment_old = $request -> comment;
      $new_used_old = $request -> new_used;
      $id_old = $request -> id;
      $url_old = $request -> url;

      $param = [
        'users' => $user,
        'part_info' => $part_info,
        'class_info' => $class_info,
        'manufacturer_info' => $manufacturer_info,
        'status_info' => $status_info,
        'storage_info' => $storage_info,
        'supplier_info' => $supplier_info,
        'info' => $info,

        'part_name_old' => $part_name_old,
        'revision_number_old' => $revision_number_old,
        'manufacturer_old' => $manufacturer_old,
        'class_name_old' => $class_name_old,
        'storage_old' => $storage_old,
        'status_old' => $status_old,
        'supplier_old' => $supplier_old,
        'purchase_date_old' => $purchase_date_old,
        'cost_price_old' => $cost_price_old,
        'cost_price_tax_old' => $cost_price_tax_old,
        'selling_price_old' => $selling_price_old,
        'selling_price_tax_old' => $selling_price_tax_old,
        'stock_old' => $stock_old,
        'comment_old' => $comment_old,
        'new_used_old' => $new_used_old,
        'id_old' => $id_old,
        'url_old' => $url_old,

      ];
      if($request->hp_type === "part_update"){
        return redirect('/part_update')->withInput($param);
      }else if($request->hp_type === "zaico_log_registration"){
        return redirect('/zaico_log_registration')->withInput($param);
      }
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
      'manufacturer_new' => 'required',
    ];
    $this->validate($request, $validate_rule);
    //$request->session()->regenerateToken();

    $param = [
      'manufacturer' => $request -> manufacturer_new,
    ];
    try{
      DB::table('manufacturer_table')->insert($param);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }
    if($request->hp_type === "part_info"){
      return redirect('/part_info')->withInput();
    }else if($request->hp_type === "used_info"){
      return redirect('/used_info')->withInput();
    }else if($request->hp_type === "part_update" or $request->hp_type === "zaico_log_registration"){
      $user = Auth::user();
      $zaico_log = DB::table('zaico_table')->orderBy('id', 'desc')->get();
      $class_info = DB::table('class_table')->get();
      $part_info = DB::table('part_info')->orderBy('id', 'desc')->get();
      $storage_info = DB::table('storage_table')->get();
      $status_info = DB::table('status_table')->get();
      $manufacturer_info = DB::table('manufacturer_table')->get();
      $supplier_info = DB::table('supplier_table')->get();
      if($request->hp_type === "part_update"){
        $info = DB::table('part_info')->where('id', $request -> part_id)->first();
      }else if($request->hp_type === "zaico_log_registration"){
        $info = DB::table('zaico_table')->where('id', $request -> part_id)->first();
      }

      $revision_number_old = $request -> revision_number;
      $part_name_old = $request -> part_name;
      $manufacturer_old = $request -> manufacturer;
      $class_name_old = $request -> class_name;
      $storage_old = $request -> storage;
      $status_old = $request -> status;
      $supplier_old = $request -> supplier;
      $purchase_date_old = $request -> purchase_date;
      $cost_price_old = $request -> cost_price;
      $cost_price_tax_old = $request -> cost_price_tax;
      $selling_price_old = $request -> selling_price;
      $selling_price_tax_old = $request -> selling_price_tax;
      $stock_old = $request -> stock;
      $comment_old = $request -> comment;
      $new_used_old = $request -> new_used;
      $id_old = $request -> id;
      $url_old = $request -> url;

      $param = [
        'users' => $user,
        'part_info' => $part_info,
        'class_info' => $class_info,
        'manufacturer_info' => $manufacturer_info,
        'status_info' => $status_info,
        'storage_info' => $storage_info,
        'supplier_info' => $supplier_info,
        'info' => $info,

        'part_name_old' => $part_name_old,
        'revision_number_old' => $revision_number_old,
        'manufacturer_old' => $manufacturer_old,
        'class_name_old' => $class_name_old,
        'storage_old' => $storage_old,
        'status_old' => $status_old,
        'supplier_old' => $supplier_old,
        'purchase_date_old' => $purchase_date_old,
        'cost_price_old' => $cost_price_old,
        'cost_price_tax_old' => $cost_price_tax_old,
        'selling_price_old' => $selling_price_old,
        'selling_price_tax_old' => $selling_price_tax_old,
        'stock_old' => $stock_old,
        'comment_old' => $comment_old,
        'new_used_old' => $new_used_old,
        'id_old' => $id_old,
        'url_old' => $url_old,
      ];
      if($request->hp_type === "part_update"){
        return redirect('/part_update')->withInput($param);
      }else if($request->hp_type === "zaico_log_registration"){
        return redirect('/zaico_log_registration')->withInput($param);
      }
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
    if($request->hp_type === "part_info"){
      return redirect('/part_info')->withInput();
    }else if($request->hp_type === "used_info"){
      return redirect('/used_info')->withInput();
    }else if($request->hp_type === "zaico_input_arrival"){
      return redirect('/zaico_input')->withInput();
    }else if($request->hp_type === "part_update" or $request->hp_type === "zaico_log_registration"){
      $user = Auth::user();
      $zaico_log = DB::table('zaico_table')->orderBy('id', 'desc')->get();
      $class_info = DB::table('class_table')->get();
      $part_info = DB::table('part_info')->orderBy('id', 'desc')->get();
      $storage_info = DB::table('storage_table')->get();
      $status_info = DB::table('status_table')->get();
      $manufacturer_info = DB::table('manufacturer_table')->get();
      $supplier_info = DB::table('supplier_table')->get();
      if($request->hp_type === "part_update"){
        $info = DB::table('part_info')->where('id', $request -> part_id)->first();
      }else if($request->hp_type === "zaico_log_registration"){
        $info = DB::table('zaico_table')->where('id', $request -> part_id)->first();
      }

      $revision_number_old = $request -> revision_number;
      $part_name_old = $request -> part_name;
      $manufacturer_old = $request -> manufacturer;
      $class_name_old = $request -> class_name;
      $storage_old = $request -> storage;
      $status_old = $request -> status;
      $supplier_old = $request -> supplier;
      $purchase_date_old = $request -> purchase_date;
      $cost_price_old = $request -> cost_price;
      $cost_price_tax_old = $request -> cost_price_tax;
      $selling_price_old = $request -> selling_price;
      $selling_price_tax_old = $request -> selling_price_tax;
      $stock_old = $request -> stock;
      $comment_old = $request -> comment;
      $new_used_old = $request -> new_used;
      $id_old = $request -> id;
      $url_old = $request -> url;

      $param = [
        'users' => $user,
        'part_info' => $part_info,
        'class_info' => $class_info,
        'manufacturer_info' => $manufacturer_info,
        'status_info' => $status_info,
        'storage_info' => $storage_info,
        'supplier_info' => $supplier_info,
        'info' => $info,

        'part_name_old' => $part_name_old,
        'revision_number_old' => $revision_number_old,
        'manufacturer_old' => $manufacturer_old,
        'class_name_old' => $class_name_old,
        'storage_old' => $storage_old,
        'status_old' => $status_old,
        'supplier_old' => $supplier_old,
        'purchase_date_old' => $purchase_date_old,
        'cost_price_old' => $cost_price_old,
        'cost_price_tax_old' => $cost_price_tax_old,
        'selling_price_old' => $selling_price_old,
        'selling_price_tax_old' => $selling_price_tax_old,
        'stock_old' => $stock_old,
        'comment_old' => $comment_old,
        'new_used_old' => $new_used_old,
        'id_old' => $id_old,
        'url_old' => $url_old,

      ];
      if($request->hp_type === "part_update"){
        return redirect('/part_update')->withInput($param);
      }else if($request->hp_type === "zaico_log_registration"){
        return redirect('/zaico_log_registration')->withInput($param);
      }
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
    if($request->hp_type === "part_info"){
      return redirect('/part_info')->withInput();
    }else if($request->hp_type === "used_info"){
      return redirect('/used_info')->withInput();
    }else if($request->hp_type === "part_update" or $request->hp_type === "zaico_log_registration"){
      $user = Auth::user();
      $zaico_log = DB::table('zaico_table')->orderBy('id', 'desc')->get();
      $class_info = DB::table('class_table')->get();
      $part_info = DB::table('part_info')->orderBy('id', 'desc')->get();
      $storage_info = DB::table('storage_table')->get();
      $status_info = DB::table('status_table')->get();
      $manufacturer_info = DB::table('manufacturer_table')->get();
      $supplier_info = DB::table('supplier_table')->get();
      if($request->hp_type === "part_update"){
        $info = DB::table('part_info')->where('id', $request -> part_id)->first();
      }else if($request->hp_type === "zaico_log_registration"){
        $info = DB::table('zaico_table')->where('id', $request -> part_id)->first();
      }

      $revision_number_old = $request -> revision_number;
      $part_name_old = $request -> part_name;
      $manufacturer_old = $request -> manufacturer;
      $class_name_old = $request -> class_name;
      $storage_old = $request -> storage;
      $status_old = $request -> status;
      $supplier_old = $request -> supplier;
      $purchase_date_old = $request -> purchase_date;
      $cost_price_old = $request -> cost_price;
      $cost_price_tax_old = $request -> cost_price_tax;
      $selling_price_old = $request -> selling_price;
      $selling_price_tax_old = $request -> selling_price_tax;
      $stock_old = $request -> stock;
      $comment_old = $request -> comment;
      $new_used_old = $request -> new_used;
      $id_old = $request -> id;
      $url_old = $request -> url;

      $param = [
        'users' => $user,
        'part_info' => $part_info,
        'class_info' => $class_info,
        'manufacturer_info' => $manufacturer_info,
        'status_info' => $status_info,
        'storage_info' => $storage_info,
        'supplier_info' => $supplier_info,
        'info' => $info,

        'part_name_old' => $part_name_old,
        'revision_number_old' => $revision_number_old,
        'manufacturer_old' => $manufacturer_old,
        'class_name_old' => $class_name_old,
        'storage_old' => $storage_old,
        'status_old' => $status_old,
        'supplier_old' => $supplier_old,
        'purchase_date_old' => $purchase_date_old,
        'cost_price_old' => $cost_price_old,
        'cost_price_tax_old' => $cost_price_tax_old,
        'selling_price_old' => $selling_price_old,
        'selling_price_tax_old' => $selling_price_tax_old,
        'stock_old' => $stock_old,
        'comment_old' => $comment_old,
        'new_used_old' => $new_used_old,
        'id_old' => $id_old,
        'url_old' => $url_old,

      ];
      if($request->hp_type === "part_update"){
        return redirect('/part_update')->withInput($param);
      }else if($request->hp_type === "zaico_log_registration"){
        return redirect('/zaico_log_registration')->withInput($param);
      }
    }else{
      return redirect('/storage_input');
    }
  }

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
    if($request->hp_type === "part_info"){
      return redirect('/part_info')->withInput();
    }else if($request->hp_type === "used_info"){
      return redirect('/used_info')->withInput();
    }else if($request->hp_type === "part_update" or $request->hp_type === "zaico_log_registration"){
      $user = Auth::user();
      $zaico_log = DB::table('zaico_table')->orderBy('id', 'desc')->get();
      $class_info = DB::table('class_table')->get();
      $part_info = DB::table('part_info')->orderBy('id', 'desc')->get();
      $storage_info = DB::table('storage_table')->get();
      $status_info = DB::table('status_table')->get();
      $manufacturer_info = DB::table('manufacturer_table')->get();
      $supplier_info = DB::table('supplier_table')->get();
      if($request->hp_type === "part_update"){
        $info = DB::table('part_info')->where('id', $request -> part_id)->first();
      }else if($request->hp_type === "zaico_log_registration"){
        $info = DB::table('zaico_table')->where('id', $request -> part_id)->first();
      }

      $revision_number_old = $request -> revision_number;
      $part_name_old = $request -> part_name;
      $manufacturer_old = $request -> manufacturer;
      $class_name_old = $request -> class_name;
      $storage_old = $request -> storage;
      $status_old = $request -> status;
      $supplier_old = $request -> supplier;
      $purchase_date_old = $request -> purchase_date;
      $cost_price_old = $request -> cost_price;
      $cost_price_tax_old = $request -> cost_price_tax;
      $selling_price_old = $request -> selling_price;
      $selling_price_tax_old = $request -> selling_price_tax;
      $stock_old = $request -> stock;
      $comment_old = $request -> comment;
      $new_used_old = $request -> new_used;
      $id_old = $request -> id;
      $url_old = $request -> url;

      $param = [
        'users' => $user,
        'part_info' => $part_info,
        'class_info' => $class_info,
        'manufacturer_info' => $manufacturer_info,
        'status_info' => $status_info,
        'storage_info' => $storage_info,
        'supplier_info' => $supplier_info,
        'info' => $info,

        'part_name_old' => $part_name_old,
        'revision_number_old' => $revision_number_old,
        'manufacturer_old' => $manufacturer_old,
        'class_name_old' => $class_name_old,
        'storage_old' => $storage_old,
        'status_old' => $status_old,
        'supplier_old' => $supplier_old,
        'purchase_date_old' => $purchase_date_old,
        'cost_price_old' => $cost_price_old,
        'cost_price_tax_old' => $cost_price_tax_old,
        'selling_price_old' => $selling_price_old,
        'selling_price_tax_old' => $selling_price_tax_old,
        'stock_old' => $stock_old,
        'comment_old' => $comment_old,
        'new_used_old' => $new_used_old,
        'id_old' => $id_old,
        'url_old' => $url_old,

      ];
      if($request->hp_type === "part_update"){
        return redirect('/part_update')->withInput($param);
      }else if($request->hp_type === "zaico_log_registration"){
        return redirect('/zaico_log_registration')->withInput($param);
      }
    }else{
      return redirect('/supplier_input');
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
        $supplier_name = DB::table('supplier_table')->get();
        $revision_number_info = DB::table('part_info')->orderBy('revision_number', 'asc')->select('revision_number')->get();
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }

      if(!empty($revision_number_info)){
        $number_buf = 1;
        foreach ($revision_number_info as $revision_number_value) {
          $revision_number_auto = 'ZC'.sprintf('%06d', $number_buf);
          if($revision_number_auto == $revision_number_value -> revision_number){
            $number_buf += 1;
          }
        }
      }else{
        $number_buf = 1;
      }

      $revision_number_auto = 'ZC'.sprintf('%06d', $number_buf);

      $param = [
        'users' => $user,
        'class_info' => $class,
        'manufacturer_info' => $manufacturer,
        'storage_info' => $storage_name,
        'status_info' => $status_name,
        'supplier_info' => $supplier_name,
        'revision_number_auto' => $revision_number_auto,
      ];
      return view('part_info.index', $param);
    }else{
      return view('auth/login');
    }
  }

  public function part_info_register(Request $request){
    $user = Auth::user();
    //$request->session()->regenerateToken();

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

    if(empty($request -> supplier)){
      $supplier = "";
    }else{
      $supplier = $request -> supplier;
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
      $purchase_date = date('Y-m-d');
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
      'supplier' => $supplier,
      'cost_price' => $cost_price,
      'cost_price_tax' => $request -> cost_price_tax,
      'selling_price' => $selling_price,
      'selling_price_tax' => $request -> selling_price_tax,
      'revision_number' => $revision_number,
      'purchase_date' => $purchase_date,
      'new_used' => $request -> new_used,
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
      'supplier' => $supplier,
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
      'new_used' => $request -> new_used,
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
      $supplier_name = DB::table('supplier_table')->get();
      $revision_number_info = DB::table('part_info')->orderBy('revision_number', 'asc')->select('revision_number')->get();
      $part_info = $request->old();

      if(!empty($revision_number_info)){
        $number_buf = 1;
        foreach ($revision_number_info as $revision_number_value) {
          $revision_number_auto = 'ZC'.sprintf('%06d', $number_buf);
          if($revision_number_auto == $revision_number_value -> revision_number){
            $number_buf += 1;
          }
        }
      }else{
        $number_buf = 1;
      }

      $revision_number_auto = 'ZC'.sprintf('%06d', $number_buf);

      $part_info += ['users' => $user];
      $part_info += ['status_info' => $status_name];
      $part_info += ['supplier_info' => $supplier_name];
      $part_info += ['revision_number_auto' => $revision_number_auto];
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
    //$request->session()->regenerateToken();

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

    if(empty($request -> supplier)){
      $supplier = "";
    }else{
      $supplier = $request -> supplier;
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
      $purchase_date = date('Y-m-d');
    }else{
      $purchase_date = $request -> purchase_date;
    }

    $part_info_master = DB::table('part_info')->where('id', $request -> part_id)->first();
    $change_flag = change_flag($part_info_master, $request);

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
      'supplier' => $request -> supplier,
      'cost_price' => $cost_price,
      'cost_price_tax' => $request -> cost_price_tax,
      'selling_price' => $selling_price,
      'selling_price_tax' => $request -> selling_price_tax,
      'revision_number' => $revision_number,
      'purchase_date' => $purchase_date,
      'new_used' => $request -> new_used,
    ];
    try{
      DB::table('part_info')->where('id', $request -> part_id)->update($param);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }

    // $part_number_change = '0';
    // $manufacturer_change= '0';
    // $status_change= '0';
    // $supplier_change= '0';
    // $partnumber_change= '0';
    // $storage_name_change= '0';
    // $comment_change= '0';
    // $class_change= '0';
    // $cost_price_change= '0';
    // $cost_price_tax_change= '0';
    // $selling_price_change= '0';
    // $selling_price_tax_change= '0';
    // $revision_number_change= '0';
    // $purchase_date_change= '0';
    // $new_used_change= '0';
    //
    // if($part_info_master -> part_name != $request -> part_name){
    //   $part_number_change = '1';
    // }
    // if($part_info_master -> manufacturer != $manufacturer){
    //   $manufacturer_change = '1';
    // }
    // if($part_info_master -> status != $request -> status){
    //   $status_change = '1';
    // }
    // if($part_info_master -> supplier != $request -> supplier){
    //   $supplier_change = '1';
    // }
    // if($part_info_master -> stock != $request -> stock){
    //   $partnumber_change = '1';
    // }
    // if($part_info_master -> storage_name != $storage){
    //   $storage_name_change = '1';
    // }
    // if($part_info_master -> comment != $comment){
    //   $comment_change = '1';
    // }
    // if($part_info_master -> class != $request -> class_name){
    //   $class_change = '1';
    // }
    // if($part_info_master -> cost_price != $cost_price){
    //   $cost_price_change = '1';
    // }
    // if($part_info_master -> cost_price_tax != $request -> cost_price_tax){
    //   $cost_price_tax_change = '1';
    // }
    // if($part_info_master -> selling_price != $selling_price){
    //   $selling_price_change = '1';
    // }
    // if($part_info_master -> selling_price_tax != $request -> selling_price_tax){
    //   $selling_price_tax_change = '1';
    // }
    // if($part_info_master -> revision_number != $revision_number){
    //   $revision_number_change = '1';
    // }
    // if($part_info_master -> purchase_date != $purchase_date){
    //   $purchase_date_change = '1';
    // }
    // if($part_info_master -> new_used != $request -> new_used){
    //   $new_used_change = '1';
    // }

    $param_log = [
      'part_number' => $request -> part_name,
      'manufacturer' => $manufacturer,
      'datetime' => date('Y-m-d H:i', strtotime('+9hour')),
      'staff_name' => $user -> name,
      'utilization' => '在庫管理修正処理',
      'status' => $request -> status,
      'supplier' => $request -> supplier,
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
      'new_used' => $request -> new_used,

      'part_number_change' => $change_flag['part_number_change'],
      'manufacturer_change' => $change_flag['manufacturer_change'],
      'status_change' => $change_flag['status_change'],
      'supplier_change' => $change_flag['supplier_change'],
      'partnumber_change' => $change_flag['partnumber_change'],
      'storage_name_change' => $change_flag['storage_name_change'],
      'comment_change' => $change_flag['comment_change'],
      'class_change' => $change_flag['class_change'],
      'cost_price_change' => $change_flag['cost_price_change'],
      'cost_price_tax_change' => $change_flag['cost_price_tax_change'],
      'selling_price_change' => $change_flag['selling_price_change'],
      'selling_price_tax_change' => $change_flag['selling_price_tax_change'],
      'revision_number_change' => $change_flag['revision_number_change'],
      'purchase_date_change' => $change_flag['purchase_date_change'],
      'new_used_change' => $change_flag['new_used_change'],

    ];
    try{
      DB::table('zaico_table')->insert($param_log);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }

    return redirect($request -> url);
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
    //$request->session()->regenerateToken();

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

    if(empty($request -> storage)){
      $storage = "";
    }else{
      $storage = $request -> storage;
    }

    if(empty($request -> supplier)){
      $supplier = "";
    }else{
      $supplier = $request -> supplier;
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
      $purchase_date = date('Y-m-d');
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
      'supplier' => $request -> supplier,
      'cost_price' => $cost_price,
      'cost_price_tax' => $request -> cost_price_tax,
      'selling_price' => $selling_price,
      'selling_price_tax' => $request -> selling_price_tax,
      'revision_number' => $revision_number,
      'purchase_date' => $purchase_date,
      'new_used' => $request -> new_used,
    ];
    try{
      DB::table('part_info')->where('id', $request -> id)->delete();
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
      'supplier' => $request -> supplier,
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
      'new_used' => $request -> new_used,
    ];
    try{
      DB::table('zaico_table')->insert($param_log);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }

    return redirect($request -> url);
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
        $supplier_info = DB::table('supplier_table')->get();
        $revision_number_info = DB::table('part_info')->orderBy('revision_number', 'asc')->select('revision_number')->get();
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }

      if(!empty($revision_number_info)){
        $number_buf = 1;
        foreach ($revision_number_info as $revision_number_value) {
          $revision_number_auto = 'ZC'.sprintf('%06d', $number_buf);
          if($revision_number_auto == $revision_number_value -> revision_number){
            $number_buf += 1;
          }
        }
      }else{
        $number_buf = 1;
      }

      $revision_number_auto = 'ZC'.sprintf('%06d', $number_buf);

      $param = [
        'users' => $user,
        'class_info' => $class,
        'manufacturer_info' => $manufacturer,
        'storage_info' => $storage_name,
        'status_info' => $status_info,
        'supplier_info' => $supplier_info,
        'revision_number_auto' => $revision_number_auto,
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
    //$request->session()->regenerateToken();

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

    if(empty($request -> supplier)){
      $supplier = "";
    }else{
      $supplier = $request -> supplier;
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
      $purchase_date = date('Y-m-d');
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
      'supplier' => $supplier,
      'cost_price' => $cost_price,
      'cost_price_tax' => $request -> cost_price_tax,
      'selling_price' => $selling_price,
      'selling_price_tax' => $request -> selling_price_tax,
      'revision_number' => $revision_number,
      'purchase_date' => $purchase_date,
      'new_used' => $request -> new_used,
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
      'supplier' => $supplier,
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
      'new_used' => $request -> new_used,
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
      //$request->session()->regenerateToken();
      try{
        $zaico_log = DB::table('zaico_table')->orderBy('id', 'desc')->get();
        $class_table = DB::table('class_table')->get();
        $part_info = DB::table('part_info')->orderBy('id', 'desc')->paginate(15);
        $status_info = DB::table('status_table')->get();
        $supplier_info = DB::table('supplier_table')->get();
        $storage_info = DB::table('storage_table')->get();
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
        'supplier_info' => $supplier_info,
        'storage_info' => $storage_info,
        'log_select1' => $request -> log_select1,
        'log_select2' => $request -> log_select2,
        'log_select3' => $request -> log_select3,
        'log_select4' => $request -> log_select4,
        'log_select5' => $request -> log_select5,
        'log_select6' => $request -> log_select6,
        'log_select7' => $request -> log_select7,
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
      //$request->session()->regenerateToken();
      try{
        $columns = Schema::getColumnListing('part_info');
        $zaico_log = DB::table('zaico_table')->orderBy('id', 'desc')->get();
        $class_table = DB::table('class_table')->get();
        $part_info = DB::table('part_info');
        $manufacturer_info = DB::table('manufacturer_table')->get();
        $status_info = DB::table('status_table')->get();
        $supplier_info = DB::table('supplier_table')->get();
        $storage_info = DB::table('storage_table')->get();
        foreach ($columns as $column) {
          $part_info = $part_info -> orwhere($column, 'like', '%'. $request -> keyword . '%');
        }
        $part_info = $part_info -> orderBy('id', 'desc');
        $part_info = $part_info -> paginate(15);

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
        'supplier_info' => $supplier_info,
        'storage_info' => $storage_info,
        'log_select1' => $request -> log_select1,
        'log_select2' => $request -> log_select2,
        'log_select3' => $request -> log_select3,
        'log_select4' => $request -> log_select4,
        'log_select5' => $request -> log_select5,
        'log_select6' => $request -> log_select6,
        'log_select7' => $request -> log_select7,
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
      //$request->session()->regenerateToken();
      try{
        $info = DB::table('part_info')->where('id', $request -> id)->first();
        $staff = DB::table('users')->get();
        $zaico_info = DB::table('zaico_table')->groupBy('utilization')->get(['utilization']);
        $status_info = DB::table('status_table')->get();
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }

      $param = [
        'users' => $user,
        'staff' => $staff,
        'info' => $info,
        'utilization_info' => $zaico_info,
        'url' => $request -> url,
        'status_info' => $status_info,
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
      //$request->session()->regenerateToken();
      try{
        $info = DB::table('part_info')->where('id', $request -> id)->first();
        $staff = DB::table('users')->get();
        $zaico_info = DB::table('zaico_table')->groupBy('utilization')->get(['utilization']);
        $status_info = DB::table('status_table')->get();
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }
      $param = [
        'users' => $user,
        'staff' => $staff,
        'info' => $info,
        'utilization_info' => $zaico_info,
        'url' => $request -> url,
        'status_info' => $status_info,
      ];
      $param += ['rec_and_ship' => $request -> rec_and_ship];
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
      //$request->session()->regenerateToken();
      try{
        $info = DB::table('part_info')->where('id', $request -> id)->first();
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
        'url' => $request -> url,
      ];

      $param += ['status' => $request -> status];
      $param += ['supplier' => $request -> supplier];
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
      //$request->session()->regenerateToken();
      try{
        $info = DB::table('part_info')->where('id', $request -> id)->first();
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
        'url' => $request -> url,
      ];

      $param += ['status' => $request -> status];
      $param += ['supplier' => $request -> supplier];
      return redirect('/part_delete')->withInput($param);
    }else{
      return view('auth/login');
    }
  }

  public function zaico_log(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      //$request->session()->regenerateToken();
      try{
        $zaico_log = DB::table('zaico_table')->orderBy('id', 'desc')->paginate(15);
        $class_table = DB::table('class_table')->get();
        $part_info = DB::table('part_info')->orderBy('id', 'desc')->get();
        $status_info = DB::table('status_table')->get();
        $supplier_info = DB::table('supplier_table')->get();
        $storage_info = DB::table('storage_table')->get();
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
        'supplier_info' => $supplier_info,
        'storage_info' => $storage_info,
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
      //$request->session()->regenerateToken();
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
        'url' => $request -> url,
      ];

      $param += ['status' => $request -> status];
      $param += ['supplier' => $request -> supplier];
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
      //$request->session()->regenerateToken();
      return view('zaico_log_delete.index', $part_info);
    }else{
      return view('auth/login');
    }

  }

  public function zaico_log_delete_register(Request $request){
    $user = Auth::user();
    //$request->session()->regenerateToken();
    $validate_rule = [
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

    if(empty($request -> supplier)){
      $supplier = "";
    }else{
      $supplier = $request -> supplier;
    }

    if(empty($request -> revision_number)){
      $revision_number = "";
    }else{
      $revision_number = $request -> revision_number;
    }

    if(empty($request -> purchase_date)){
      $purchase_date = date('Y-m-d');
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
      'supplier' => $request -> supplier,
      'new_used' => $request -> new_used,
      'revision_number' => $revision_number,
      'purchase_date' => $purchase_date,
    ];
    try{
      DB::table('zaico_table')->where('id', $request -> id)->delete();
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }

    return redirect($request -> url);
  }

  public function zaico_log_input_registration(Request $request){
    //$this->validate($request, Attendance::$rules);
    if (Auth::check()){
      $user = Auth::user();
      //$request->session()->regenerateToken();
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
        $revision_number_info = DB::table('part_info')->orderBy('revision_number', 'asc')->select('revision_number')->get();
        $status_name = DB::table('status_table')->get();
        $supplier_name = DB::table('supplier_table')->get();

        if(!empty($revision_number_info)){
          $number_buf = 1;
          foreach ($revision_number_info as $revision_number_value) {
            $revision_number_auto = 'ZC'.sprintf('%06d', $number_buf);
            if($revision_number_auto == $revision_number_value -> revision_number){
              $number_buf += 1;
            }
          }
        }else{
          $number_buf = 1;
        }

        $revision_number_auto = 'ZC'.sprintf('%06d', $number_buf);

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
        'url' => $request -> url,
      ];

      $param += ['status' => $request -> status];
      $param += ['supplier' => $request -> supplier];
      $param += ['revision_number_auto' => $revision_number_auto];
      $param += ['status_info' => $status_name];
      $param += ['supplier_info' => $supplier_name];
      return redirect('/zaico_log_registration')->withInput($param);
    }else{
      return view('auth/login');
    }
  }

  public function zaico_log_registration(Request $request){

    if (Auth::check()){
      $user = Auth::user();
      $part_info = $request->old();
      $part_info += ['users' => $user];

      $revision_number_info = DB::table('part_info')->orderBy('revision_number', 'asc')->select('revision_number')->get();

      if(!empty($revision_number_info)){
        $number_buf = 1;
        foreach ($revision_number_info as $revision_number_value) {
          $revision_number_auto = 'ZC'.sprintf('%06d', $number_buf);
          if($revision_number_auto == $revision_number_value -> revision_number){
            $number_buf += 1;
          }
        }
      }else{
        $number_buf = 1;
      }

      $revision_number_auto = 'ZC'.sprintf('%06d', $number_buf);

      $part_info += ['revision_number_auto' => $revision_number_auto];
      //$request->session()->regenerateToken();
      return view('zaico_log_registration.index', $part_info);
    }else{
      return view('auth/login');
    }

  }

  public function zaico_log_registration_register(Request $request){
    $user = Auth::user();
    $validate_rule = [
      'part_name' => 'required',
      'stock' => 'required',
    ];
    $this->validate($request, $validate_rule);
    $resize_path = public_path('img\new.png');
    //$request->session()->regenerateToken();

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

    if(empty($request -> supplier)){
      $supplier = "";
    }else{
      $supplier = $request -> supplier;
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
      $purchase_date = date('Y-m-d');
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
      'supplier' => $request -> supplier,
      'cost_price' => $cost_price,
      'cost_price_tax' => $request -> cost_price_tax,
      'selling_price' => $selling_price,
      'selling_price_tax' => $request -> selling_price_tax,
      'revision_number' => $revision_number,
      'purchase_date' => $purchase_date,
      'new_used' => $request -> new_used,
    ];
    //try{
      DB::table('part_info')->insert($param);
    //} catch (\Exception $e) {
    //  return redirect('/zaico_home');
    //}

    $param_log = [
      'part_number' => $request -> part_name,
      'manufacturer' => $manufacturer,
      'datetime' => date('Y-m-d H:i', strtotime('+9hour')),
      'staff_name' => $user -> name,
      'utilization' => 'ログから登録処理',
      'status' => $request -> status,
      'supplier' => $request -> supplier,
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
      'new_used' => $request -> new_used,
    ];
    try{
      DB::table('zaico_table')->insert($param_log);
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }

    return redirect($request -> url);
  }

  public function table_item_delete(Request $request){
    $user = Auth::user();
    //$request->session()->regenerateToken();

    $status = DB::table('status_table')->get();
    $class = DB::table('class_table')->get();
    $manufacturer = DB::table('manufacturer_table')->get();
    $storage  = DB::table('storage_table')->get();
    $supplier  = DB::table('supplier_table')->get();

    if($request -> table_item == "class_table"){
      $re_hp = "/class_input";
    }else if($request -> table_item == "manufacturer_table"){
      $re_hp = "/manufacturer_input";
    }else if($request -> table_item == "status_table"){
      $re_hp = "/status_input";
    }else if($request -> table_item == "storage_table"){
      $re_hp = "/storage_input";
    }else if($request -> table_item == "supplier_table"){
      $re_hp = "/supplier_input";
    }

    $param = [
      'manufacturer_info' => $manufacturer,
      'class_info' => $class,
      'storage_info' => $storage,
      'status_info' => $status,
      'supplier_info' => $supplier,
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
      //$request->session()->regenerateToken();
      try{
        $columns = Schema::getColumnListing('zaico_table');
        $zaico_log = DB::table('zaico_table');
        $class_table = DB::table('class_table')->get();
        $part_info = DB::table('part_info')->orderBy('id', 'desc')->get();
        $status_info = DB::table('status_table')->get();
        $supplier_info = DB::table('supplier_table')->get();
        $storage_info = DB::table('storage_table')->get();
        $manufacturer_info = DB::table('manufacturer_table')->get();
        foreach ($columns as $column) {
          $zaico_log = $zaico_log -> orwhere($column, 'like', '%'. $request -> keyword . '%');
        }
        $zaico_log = $zaico_log -> orderBy('id', 'desc');
        $zaico_log = $zaico_log -> paginate(15);
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
        'supplier_info' => $supplier_info,
        'storage_info' => $storage_info,
        'keyword' => $request -> keyword,
        'log_select1' => $request -> log_select1,
        'log_select2' => $request -> log_select2,
        'log_select3' => $request -> log_select3,
        'log_select4' => $request -> log_select4,
        'log_select5' => $request -> log_select5,
        'log_select6' => $request -> log_select6,
        'log_select7' => $request -> log_select7,
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
      //$request->session()->regenerateToken();
      try{
        $zaico_log = DB::table('zaico_table');
        $class_table = DB::table('class_table')->get();
        $part_info = DB::table('part_info')->orderBy('id', 'desc')->get();
        $status_info = DB::table('status_table')->get();
        $supplier_info = DB::table('supplier_table')->get();
        $storage_info = DB::table('storage_table')->get();
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
        if ($request -> log_select5 != ''){
          $zaico_log = $zaico_log -> where('new_used', $request -> log_select5);
        }
        if ($request -> log_select6 != ''){
          $zaico_log = $zaico_log -> where('storage_name', $request -> log_select6);
        }
        if ($request -> log_select7 != ''){
          $zaico_log = $zaico_log -> where('supplier', $request -> log_select7);
        }
        $zaico_log = $zaico_log -> orderBy('id', 'desc');
        $zaico_log = $zaico_log -> paginate(15);
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }
      $param = [
        'users' => $user,
        'zaico_log' => $zaico_log,
        'class_table' => $class_table,
        'part_info' => $part_info,
        'status_info' => $status_info,
        'supplier_info' => $supplier_info,
        'storage_info' => $storage_info,
        'manufacturer_info' => $manufacturer_info,
        'log_select1' => $request -> log_select1,
        'log_select2' => $request -> log_select2,
        'log_select3' => $request -> log_select3,
        'log_select4' => $request -> log_select4,
        'log_select5' => $request -> log_select5,
        'log_select6' => $request -> log_select6,
        'log_select7' => $request -> log_select7,
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
        //$request->session()->regenerateToken();
        try{
          $part_info = DB::table('part_info') ;
          $class_table = DB::table('class_table')->get();
          $status_info = DB::table('status_table')->get();
          $supplier_info = DB::table('supplier_table')->get();
          $storage_info = DB::table('storage_table')->get();
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
          if ($request -> log_select5 != ''){
            $part_info = $part_info -> where('new_used', $request -> log_select5);
          }
          if ($request -> log_select6 != ''){
            $part_info = $part_info -> where('storage_name', $request -> log_select6);
          }
          if ($request -> log_select7 != ''){
            $part_info = $part_info -> where('supplier', $request -> log_select7);
          }
          $part_info = $part_info -> orderBy('id', 'desc');
          $part_info = $part_info -> paginate(15);
        } catch (\Exception $e) {
          return redirect('/zaico_home');
        }
        $param = [
          'users' => $user,
          'part_info' => $part_info,
          'class_table' => $class_table,
          'manufacturer_info' => $manufacturer_info,
          'status_info' => $status_info,
          'supplier_info' => $supplier_info,
          'storage_info' => $storage_info,
          'log_select1' => $request -> log_select1,
          'log_select2' => $request -> log_select2,
          'log_select3' => $request -> log_select3,
          'log_select4' => $request -> log_select4,
          'log_select5' => $request -> log_select5,
          'log_select6' => $request -> log_select6,
          'log_select7' => $request -> log_select7,
        ];
        return view('zaico_list.index', $param);
      }else{
        return view('auth/login');
      }
    }

    public function csv_download(Request $request){
      $user = Auth::user();
      //$request->session()->regenerateToken();

      //try{
        $part_info = DB::table('part_info') -> orderBy('id', 'desc') -> get();
        postCSV($part_info);
        //export("CSV_FILE",$part_info);
      //} catch (\Exception $e) {
      //  return redirect('/zaico_home');
      //}
      return redirect()->withInput();
    }

    public function csv_log_download(Request $request){
      $user = Auth::user();
      //$request->session()->regenerateToken();

      //try{
        $zaico_log = DB::table('zaico_table') -> orderBy('id', 'desc') -> get();
        logCSV($zaico_log);
        //export("CSV_FILE",$part_info);
      //} catch (\Exception $e) {
      //  return redirect('/zaico_home');
      //}
      return redirect()->withInput();
    }

}


function export($file_name, $data)
{
  $fp = fopen('test.csv', 'w');
  fwrite($fp, pack('C*',0xEF,0xBB,0xBF)); // BOM をつける

  $param = [
    'revision_number',
    'part_name',
    'manufacturer',
    'class',
    'status',
    'supplier',
    'new_used',
    'storage_name',
    'purchase_date',
    'stock',
    'comment',
    'cost_price',
    'cost_price_tax',
    'selling_price',
    'selling_price_tax',
    'part_photo',
    'sub_part_photo_1',
    'sub_part_photo_2',
    'sub_part_photo_3',
  ];

  // UTF-8からSJIS-winへ変換するフィルター
  //stream_filter_append($fp, 'convert.iconv.UTF-8/CP932//TRANSLIT', STREAM_FILTER_WRITE);

  fputcsv($fp, $param);

  foreach ($data as $row) {

  $csv_date = [
    $row -> revision_number,
    $row -> part_name,
    $row -> manufacturer,
    $row -> class,
    $row -> status,
    $row -> supplier,
    $row -> new_used,
    $row -> storage_name,
    $row -> purchase_date,
    $row -> stock,
    $row -> comment,
    $row -> cost_price,
    $row -> cost_price_tax,
    $row -> selling_price,
    $row -> selling_price_tax,
    $row -> part_photo,
    $row -> sub_part_photo_1,
    $row -> sub_part_photo_2,
    $row -> sub_part_photo_3,
  ];
  fputcsv($fp, $csv_date);
  }
  fclose($fp);
  header('Content-Type: application/octet-stream');
  header("Content-Disposition: attachment; filename={$file_name}");
  header('Content-Transfer-Encoding: binary');
  //return response(stream_get_contents($fp), 200)->header('Content-Type', 'text/csv')->header('Content-Disposition', 'attachment; filename="demo.csv"');
}

function postCSV($part_info)
{
    // データの作成
    /*
    $param = [
      'revision_number',
      'part_name',
      'manufacturer',
      'class',
      'status',
      'storage_name',
      'purchase_date',
      'stock',
      'comment',
      'cost_price',
      'cost_price_tax',
      'selling_price',
      'selling_price_tax',
      //'part_photo',
      //'sub_part_photo_1',
      //'sub_part_photo_2',
      //'sub_part_photo_3',
    ];
    */
    $param = [
      '管理番号',
      '品名',
      'メーカ',
      '分類',
      'ステータス',
      '仕入れ先',
      'コンディション',
      '保管場所',
      '仕入れ日',
      'ストック数量',
      'コメント',
      '仕入れ価格',
      '仕入れ価格/税区分',
      '販売価格',
      '販売価格/税区分',
      //'part_photo',
      //'sub_part_photo_1',
      //'sub_part_photo_2',
      //'sub_part_photo_3',
    ];
    // 書き込み用ファイルを開く
    $f = fopen('zaico_list.csv', 'w');
    if ($f) {
        // カラムの書き込み
        mb_convert_variables('SJIS', 'UTF-8', $param);
        fputcsv($f, $param);
        // データの書き込み
        foreach ($part_info as $row) {

          $csv_date = [
            $row -> revision_number,
            $row -> part_name,
            $row -> manufacturer,
            $row -> class,
            $row -> status,
            $row -> supplier,
            $row -> new_used,
            $row -> storage_name,
            $row -> purchase_date,
            $row -> stock,
            $row -> comment,
            $row -> cost_price,
            $row -> cost_price_tax,
            $row -> selling_price,
            $row -> selling_price_tax,
            //$row -> part_photo,
            //$row -> sub_part_photo_1,
            //$row -> sub_part_photo_2,
            //$row -> sub_part_photo_3,
          ];

          mb_convert_variables('SJIS', 'UTF-8', $csv_date);
          fputcsv($f, $csv_date);
        }
    }
    // ファイルを閉じる
    fclose($f);

    // HTTPヘッダ
    header("Content-Type: application/octet-stream");
    header('Content-Length: '.filesize('zaico_list.csv'));
    header('Content-Disposition: attachment; filename=zaico_list.csv');
    readfile('zaico_list.csv');

    exit;

    //return redirect()->withInput();
}

function logCSV($zaico_log)
{
    // データの作成
    /*
    $param = [
      'revision_number',
      'part_name',
      'manufacturer',
      'class',
      'status',
      'storage_name',
      'purchase_date',
      'stock',
      'comment',
      'cost_price',
      'cost_price_tax',
      'selling_price',
      'selling_price_tax',
      //'part_photo',
      //'sub_part_photo_1',
      //'sub_part_photo_2',
      //'sub_part_photo_3',
    ];
    */
    $param = [
      '管理番号',
      '品名',
      'メーカ',
      '分類',
      'ステータス',
      '仕入れ先',
      'コンディション',
      '保管場所',
      '仕入れ日',
      '数量（ストック量または取引数量）',
      'コメント',
      '仕入れ価格',
      '仕入れ価格/税区分',
      '販売価格',
      '販売価格/税区分',
      '担当',
      '用途',
      'ログ日時',
      //'part_photo',
      //'sub_part_photo_1',
      //'sub_part_photo_2',
      //'sub_part_photo_3',
    ];
    // 書き込み用ファイルを開く
    $f = fopen('log.csv', 'w');
    if ($f) {
        // カラムの書き込み
        mb_convert_variables('SJIS', 'UTF-8', $param);
        fputcsv($f, $param);
        // データの書き込み
        foreach ($zaico_log as $row) {

          $csv_date = [
            $row -> revision_number,
            $row -> part_number,
            $row -> manufacturer,
            $row -> class,
            $row -> status,
            $row -> supplier,
            $row -> new_used,
            $row -> storage_name,
            $row -> purchase_date,
            $row -> partnumber,
            $row -> comment,
            $row -> cost_price,
            $row -> cost_price_tax,
            $row -> selling_price,
            $row -> selling_price_tax,
            $row -> staff_name,
            $row -> utilization,
            $row -> datetime,
            //$row -> part_photo,
            //$row -> sub_part_photo_1,
            //$row -> sub_part_photo_2,
            //$row -> sub_part_photo_3,
          ];

          mb_convert_variables('SJIS', 'UTF-8', $csv_date);
          fputcsv($f, $csv_date);
        }
    }
    // ファイルを閉じる
    fclose($f);

    // HTTPヘッダ
    header("Content-Type: application/octet-stream");
    header('Content-Length: '.filesize('log.csv'));
    header('Content-Disposition: attachment; filename=log.csv');
    readfile('log.csv');

    exit;

    //return redirect()->withInput();
}

function change_flag($part_info_master, $request){

  $part_number_change = '0';
  $manufacturer_change= '0';
  $status_change= '0';
  $supplier_change= '0';
  $partnumber_change= '0';
  $storage_name_change= '0';
  $comment_change= '0';
  $class_change= '0';
  $cost_price_change= '0';
  $cost_price_tax_change= '0';
  $selling_price_change= '0';
  $selling_price_tax_change= '0';
  $revision_number_change= '0';
  $purchase_date_change= '0';
  $new_used_change= '0';

  $part_name = '';
  $manufacturer = '';
  $status = '';
  $supplier = '';
  $stock = '';
  $storage = '';
  $comment = '';
  $class_name = '';
  $cost_price = '';
  $cost_price_tax = '';
  $selling_price = '';
  $selling_price_tax = '';
  $revision_number = '';
  $purchase_date = '';
  $new_used = '';

  if(!empty($request -> part_name)){$part_name = $request -> part_name;}
  if(!empty($request -> manufacturer)){$manufacturer = $request -> manufacturer;}
  if(!empty($request -> status)){$status = $request -> status;}
  if(!empty($request -> supplier)){$supplier = $request -> supplier;}
  if(!empty($request -> stock)){$stock = $request -> stock;}
  if(!empty($request -> storage)){$storage = $request -> storage;}else if(!empty($request -> storage_name)){$storage = $request -> storage_name;}
  if(!empty($request -> comment)){$comment = $request -> comment;}
  if(!empty($request -> class_name)){$class_name = $request -> class_name;}
  if(!empty($request -> cost_price)){$cost_price = $request -> cost_price;}
  if(!empty($request -> cost_price_tax)){$cost_price_tax = $request -> cost_price_tax;}
  if(!empty($request -> selling_price)){$selling_price = $request -> selling_price;}
  if(!empty($request -> selling_price_tax)){$selling_price_tax = $request -> selling_price_tax;}
  if(!empty($request -> revision_number)){$revision_number = $request -> revision_number;}
  if(!empty($request -> purchase_date)){$purchase_date = $request -> purchase_date;}
  if(!empty($request -> new_used)){$new_used = $request -> new_used;}

  if($part_info_master -> part_name != $part_name){
    $part_number_change = '1';
  }
  if($part_info_master -> manufacturer != $manufacturer){
    $manufacturer_change = '1';
  }
  if($part_info_master -> status != $status){
    $status_change = '1';
  }
  if($part_info_master -> supplier != $supplier){
    $supplier_change = '1';
  }
  if($part_info_master -> stock != $stock){
    $partnumber_change = '1';
  }
  if($part_info_master -> storage_name != $storage){
    $storage_name_change = '1';
  }
  if($part_info_master -> comment != $comment){
    $comment_change = '1';
  }
  if($part_info_master -> class != $class_name){
    $class_change = '1';
  }
  if($part_info_master -> cost_price != $cost_price){
    $cost_price_change = '1';
  }
  if($part_info_master -> cost_price_tax != $cost_price_tax){
    $cost_price_tax_change = '1';
  }
  if($part_info_master -> selling_price != $selling_price){
    $selling_price_change = '1';
  }
  if($part_info_master -> selling_price_tax != $selling_price_tax){
    $selling_price_tax_change = '1';
  }
  if($part_info_master -> revision_number != $revision_number){
    $revision_number_change = '1';
  }
  if($part_info_master -> purchase_date != $purchase_date){
    $purchase_date_change = '1';
  }
  if($part_info_master -> new_used != $new_used){
    $new_used_change = '1';
  }

  if(!empty($request -> rec_and_ship)){if($request -> partNumber >= 1){$partnumber_change = 1;}else{$partnumber_change = 0;}}


  $change_flag = array(
    'part_number_change' => $part_number_change,
    'manufacturer_change' => $manufacturer_change,
    'status_change' => $status_change,
    'supplier_change' => $supplier_change,
    'partnumber_change' => $partnumber_change,
    'storage_name_change' => $storage_name_change,
    'comment_change' => $comment_change,
    'class_change' => $class_change,
    'cost_price_change' => $cost_price_change,
    'cost_price_tax_change' => $cost_price_tax_change,
    'selling_price_change' => $selling_price_change,
    'selling_price_tax_change' => $selling_price_tax_change,
    'revision_number_change' => $revision_number_change,
    'purchase_date_change' => $purchase_date_change,
    'new_used_change' => $new_used_change,
  );
return $change_flag;

}
