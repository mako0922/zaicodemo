<?php

namespace App\Http\Controllers;
use Intervention\Image\Facades\Image;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use My_func;

class ZaicoController extends Controller
{
  // ホーム画面 GET動作
  public function zaico_home(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();
      return view('zaico_home.index', $param = ['users' => $user]);
    }else{
      return view('auth/login');
    }
  }

  // 新規登録 常時在庫管理有無選択画面
  public function part_info_select(Request $request){
    // ログインチェック
    if (Auth::check()){
      $param = My_func::tableRead();
      return view('part_info_select.index', $param);
    }else{
      return view('auth/login');
    }
  }

  // 在庫管理品 削除画面GET動作
  public function part_delete(Request $request){

    if (Auth::check()){
      $param = My_func::tableRead();
      $param += $request->old();
      return view('part_delete.index', $param);
    }else{
      return view('auth/login');
    }

  }

  // 在庫管理品 削除実行
  public function part_delete_register(Request $request){
    $user = Auth::user();
    $validate_rule = [
      'part_name' => 'required',
      'stock' => 'required',
    ];
    $this->validate($request, $validate_rule);
    list($param, $param_log) = My_func::partParaIni('在庫管理データ削除処理' , $request);

    try{
      DB::table('part_info')->where('id', $request -> part_id)->delete();
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

  // 在庫管理品リスト画面表示動作
  public function zaico_list(Request $request){
    // ログインチェック
    if (Auth::check()){
      $user = Auth::user();

      try{
        $part_info = DB::table('part_info')->orderBy('id', 'desc')->paginate(15);
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }
      $param = My_func::tableRead();
      $param += [
        'part_info' => $part_info,
      ];
      return view('zaico_list.index', $param);
    }else{

      return view('auth/login');
    }
  }

  // 在庫管理品リスト画面 あいまい検索動作
  public function part_list_serch(Request $request){
    // ログインチェック
    if (Auth::check()){
      try{
        $param = My_func::tableRead();
        $param += My_func::serchLike('part_info', 'part_info', $request);
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }

      return view('zaico_list.index', $param);
    }else{

      return view('auth/login');
    }
  }

  // 在庫管理品リスト画面 パラメータ検索
  public function onchange_list(Request $request){
    // ログインチェック
    if (Auth::check()){
      try{
        $param = My_func::tableRead();
        $param += My_func::serchPara('part_info', 'part_info', $request);
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }

      return view('zaico_list.index', $param);
    }else{
      return view('auth/login');
    }
  }

  // 在庫管理品入庫処理画面表示動作
  public function zaico_input_arrival(Request $request){
    if (Auth::check()){
      $validate_rule = [
      ];
      $this->validate($request, $validate_rule);

      try{
        $info = DB::table('part_info')->where('id', $request -> id)->first();
        $zaico_info = DB::table('zaico_table')->groupBy('utilization')->get(['utilization']);
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }

      $param = My_func::tableRead();
      $param = [
        'info' => $info,
        'utilization_info' => $zaico_info,
        'url' => $request -> url,
        'rec_and_ship' => $request -> rec_and_ship,
      ];

      return redirect('/zaico_input')->withInput($param);
    }else{
      return view('auth/login');
    }
  }

  // 在庫管理品出庫処理画面表示動作
  public function zaico_input_utilize(Request $request){
    if (Auth::check()){
      $validate_rule = [
      ];
      $this->validate($request, $validate_rule);
      try{
        $info = DB::table('part_info')->where('id', $request -> id)->first();
        $zaico_info = DB::table('zaico_table')->groupBy('utilization')->get(['utilization']);
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }
      $param = My_func::tableRead();
      $param += [
        'info' => $info,
        'utilization_info' => $zaico_info,
        'url' => $request -> url,
        'rec_and_ship' => $request -> rec_and_ship,
      ];
      return redirect('/zaico_input')->withInput($param);
    }else{
      return view('auth/login');
    }
  }

  // 在庫管理品変更処理画面表示
  public function zaico_input_update(Request $request){
    if (Auth::check()){
      $validate_rule = [
      ];
      $this->validate($request, $validate_rule);
      try{
        $info = DB::table('part_info')->where('id', $request -> id)->first();
        $zaico_status = DB::table('zaico_table')->groupBy('status')->get(['status']);
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }

      $param = My_func::tableRead();
      $param += [
        'info' => $info,
        'url' => $request -> url,
      ];

      return redirect('/part_update')->withInput($param);
    }else{
      return view('auth/login');
    }
  }

  // 在庫管理品削除画面表示動作
  public function zaico_input_delete(Request $request){
    if (Auth::check()){
      $validate_rule = [
      ];
      $this->validate($request, $validate_rule);
      try{
        $info = DB::table('part_info')->where('id', $request -> id)->first();
        $zaico_status = DB::table('zaico_table')->groupBy('status')->get(['status']);
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }

      $param = My_func::tableRead();
      $param += [
        'info' => $info,
        'url' => $request -> url,
      ];

      return redirect('/part_delete')->withInput($param);
    }else{
      return view('auth/login');
    }
  }

  // 在庫管理ログ画面表示
  public function zaico_log(Request $request){
    // ログインチェック
    if (Auth::check()){
      try{
        $zaico_log = DB::table('zaico_table')->orderBy('id', 'desc')->paginate(15);
        $part_info = DB::table('part_info')->orderBy('id', 'desc')->get();
        $columns = Schema::getColumnListing('zaico_table');
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }
      $param = My_func::tableRead();
      $param += [
        'zaico_log' => $zaico_log,
        'part_info' => $part_info,
        'columns' => $columns,
      ];
      return view('zaico_log.index', $param);
    }else{
      return view('auth/login');
    }
  }

  // 在庫管理ログ 削除画面表示動作
  public function zaico_log_input_delete(Request $request){
    if (Auth::check()){
      $user = Auth::user();
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
      $param = [
        'info' => $info,
        'zaico_status' => $zaico_status,
        'url' => $request -> url,
      ];

      return redirect('/zaico_log_delete')->withInput($param);
    }else{
      return view('auth/login');
    }
  }

  // 在庫管理ログ 削除画面表示動作
  public function zaico_log_delete(Request $request){

    if (Auth::check()){
      $param = My_func::tableRead();
      $param += $request->old();
      return view('zaico_log_delete.index', $param);
    }else{
      return view('auth/login');
    }
  }

  // 在庫管理ログ 削除実行
  public function zaico_log_delete_register(Request $request){
    $validate_rule = [
    ];
    $this->validate($request, $validate_rule);
    try{
      DB::table('zaico_table')->where('id', $request -> id)->delete();
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }

    return redirect($request -> url);
  }

  // パラメータ削除実行
  public function table_item_delete(Request $request){

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
    }else if($request -> table_item == "users"){
      $re_hp = "/register";
    }

    $param = My_func::tableRead();

    try{
      DB::table( $request -> table_item)->where('id', $request -> id)->delete();
    } catch (\Exception $e) {
      return redirect('/zaico_home');
    }
    return redirect($re_hp)->withInput($param);
  }

  // 在庫ログ あいまい検索動作
  public function zaico_log_serch(Request $request){
    // ログインチェック
    if (Auth::check()){
      try{
        $part_info = DB::table('part_info')->orderBy('id', 'desc')->get();
        $param = My_func::tableRead();
        $param += My_func::serchLike('zaico_table', 'zaico_log', $request);
        $param += [
          'part_info' => $part_info,
        ];
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }

      return view('zaico_log.index', $param);
    }else{
      return view('auth/login');
    }
  }

  // 在庫ログ パラメータ検索
  public function onchange_log(Request $request){
    // ログインチェック
    if (Auth::check()){
      try{
        $part_info = DB::table('part_info')->orderBy('id', 'desc')->get();
        $param = My_func::tableRead();
        $param += My_func::serchPara('zaico_table', 'zaico_log', $request);
        $param += [
          'part_info' => $part_info,
        ];
      } catch (\Exception $e) {
        return redirect('/zaico_home');
      }
      return view('zaico_log.index', $param);
    }else{
      return view('auth/login');
    }
  }

  // 在庫管理リストCSV ダウンロード機能
  public function csv_download(Request $request){
    $user = Auth::user();
    // データタイトル行の定義
    $column_title = [
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
    ];

    // データベース 変数名（取得したいパラメータ）の定義
    $column_name = [
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
    ];

    $part_info = DB::table('part_info') -> orderBy('id', 'desc') -> get();
    date_default_timezone_set('Asia/Tokyo');
    $fileName = date("YmdHi") . 'zaico_list.csv';
    My_func::postCSV($part_info, $column_title, $column_name, $fileName);

    return redirect()->withInput();
  }

  // 在庫ログCSV ダウンロード機能
  public function csv_log_download(Request $request){
    $user = Auth::user();
    // データタイトル行の定義
    $column_title = [
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
    ];

    // データベース 変数名（取得したいパラメータ）の定義
    $column_name = [
      'revision_number',
      'part_number',
      'manufacturer',
      'class',
      'status',
      'supplier',
      'new_used',
      'storage_name',
      'purchase_date',
      'partnumber',
      'comment',
      'cost_price',
      'cost_price_tax',
      'selling_price',
      'selling_price_tax',
      'staff_name',
      'utilization',
      'datetime',
    ];

    $zaico_log = DB::table('zaico_table') -> orderBy('id', 'desc') -> get();
    date_default_timezone_set('Asia/Tokyo');
    $fileName = date("YmdHi") . 'log.csv';
    My_func::postCSV($zaico_log, $column_title, $column_name, $fileName);

    return redirect()->withInput();
  }

}
