<?php

namespace App\Lib;
use Intervention\Image\Facades\Image;

use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;

class My_func
{
  // 各共通パラメータ取得の関数
  public static function tableRead(){
    try{
      $user = Auth::user();
      $staff = DB::table('users')->get();
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
      'staff' => $staff,
      'class_info' => $class,
      'manufacturer_info' => $manufacturer,
      'storage_info' => $storage_name,
      'status_info' => $status_name,
      'supplier_info' => $supplier_name,
      'revision_number_auto' => $revision_number_auto,
    ];

    return $param;
  }

  // 各共通パラメータPOST後の共通初期設定
  public static function partParaIni($utilization , $request)
  {
    $user = Auth::user();
    $resize_path = public_path('img\new.png');

    if(!empty($request -> part_photo)){
      if($utilization == '在庫管理データ削除処理'){
        $part_photo64 = $request -> part_photo;
      }else{
        $image = Image::make(file_get_contents($request -> part_photo));
        $image->resize(200, null, function ($constraint) {
          $constraint->aspectRatio();
        });
        $image->save($resize_path);
        $part_photo64 = base64_encode($image);
      }
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
      $image1->resize(200, null, function ($constraint) {
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
      $image2->resize(200, null, function ($constraint) {
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
      $image3->resize(200, null, function ($constraint) {
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

    if (($utilization == '入荷処理') or ($utilization == '出荷処理')){
      $part_info = DB::table('part_info')->where('id', $request -> part_id)->first();
      if ($request -> rec_and_ship == '入荷'){
        $stock_update = $part_info -> stock + $request -> stock;
      }else if($request -> rec_and_ship == '出荷'){
        $stock_update = $part_info -> stock - $request -> stock;
      }else{
        $stock_update = $part_info -> stock;
      }
      $param = [
        'stock' => $stock_update,
        'status' => $request -> status,
        'comment' => $comment,
      ];
    }else{
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
    }

    $param_log = [
      'part_number' => $request -> part_name,
      'manufacturer' => $request -> manufacturer,
      'datetime' => date('Y-m-d H:i'),
      'staff_name' => $user -> name,
      'utilization' => $utilization,
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

    if (($utilization == '在庫管理修正処理') or ($utilization == '入荷処理') or ($utilization == '出荷処理')){
      $part_info_master = DB::table('part_info')->where('id', $request -> part_id)->first();
      $change_flag = My_func::change_flag($part_info_master, $request);
      $param_log += [
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
    }
    return [$param , $param_log];
  }

  // 各パラメータ新規登録時の動作関数
  public static function parameterInput($table_name, $request)
  {
    if($request->hp_type === "part_info"){
      return redirect('/part_info')->withInput();
    }else if($request->hp_type === "used_info"){
      return redirect('/used_info')->withInput();
    }else if($request->hp_type === "zaico_input_arrival"){
      return redirect('/zaico_input')->withInput();
    }else if($request->hp_type === "part_update" or $request->hp_type === "zaico_log_registration"){
      $zaico_log = DB::table('zaico_table')->orderBy('id', 'desc')->get();
      $part_info = DB::table('part_info')->orderBy('id', 'desc')->get();
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

      $param = My_func::tableRead();
      $param += [
        'part_info' => $part_info,
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
      return redirect($table_name);
    }
  }

  // 各パラメータ更新時の変化点チェック関数
  public static function change_flag($part_info_master, $request){

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

    if(!empty($request -> rec_and_ship)){if($request -> stock >= 1){$partnumber_change = 1;}else{$partnumber_change = 0;}}

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

  // 各パラメータ検索動作関数
  public static function serchPara($serchTable, $serchParaName, $request){
    $serchPara_log = DB::table($serchTable);
    if ($request -> log_select1 != ''){
      $serchPara_log = $serchPara_log -> where('status', $request -> log_select1);
    }
    if ($request -> log_select2 != ''){
      $serchPara_log = $serchPara_log -> where('class', $request -> log_select2);
    }
    if ($request -> log_select3 != ''){
      $serchPara_log = $serchPara_log -> where('part_number', $request -> log_select3);
    }
    if ($request -> log_select4 != ''){
      $serchPara_log = $serchPara_log -> where('manufacturer', $request -> log_select4);
    }
    if ($request -> log_select5 != ''){
      $serchPara_log = $serchPara_log -> where('new_used', $request -> log_select5);
    }
    if ($request -> log_select6 != ''){
      $serchPara_log = $serchPara_log -> where('storage_name', $request -> log_select6);
    }
    if ($request -> log_select7 != ''){
      $serchPara_log = $serchPara_log -> where('supplier', $request -> log_select7);
    }
    $serchPara_log = $serchPara_log -> orderBy('id', 'desc');
    $serchPara_log = $serchPara_log -> paginate(15);

    $retparam = [
      $serchParaName => $serchPara_log,
      'log_select1' => $request -> log_select1,
      'log_select2' => $request -> log_select2,
      'log_select3' => $request -> log_select3,
      'log_select4' => $request -> log_select4,
      'log_select5' => $request -> log_select5,
      'log_select6' => $request -> log_select6,
      'log_select7' => $request -> log_select7,
    ];

    return $retparam;
  }

  // あいまい検索動作関数
  public static function serchLike($serchTable, $serchParaName, $request){
    $columns = Schema::getColumnListing($serchTable);
    $serchPara_log = DB::table($serchTable);
    foreach ($columns as $column) {
      if (strpos($column, 'part_photo') === false){
        $serchPara_log = $serchPara_log -> orwhere($column, 'like', '%'. $request -> keyword . '%');
      }
    }
    $serchPara_log = $serchPara_log -> orderBy('id', 'desc');
    $serchPara_log = $serchPara_log -> paginate(15);

    $retparam = [
      $serchParaName => $serchPara_log,
      'cloums' => $columns,
      'keyword' => $request -> keyword,
      'log_select1' => $request -> log_select1,
      'log_select2' => $request -> log_select2,
      'log_select3' => $request -> log_select3,
      'log_select4' => $request -> log_select4,
      'log_select5' => $request -> log_select5,
      'log_select6' => $request -> log_select6,
      'log_select7' => $request -> log_select7,
    ];

    return $retparam;
  }

  // CSVダウンロード関数
  public static function postCSV($part_info, $column_title, $column_name , $fileName)
  {
    // 書き込み用ファイルを開く
    $f = fopen($fileName, 'w');
    if ($f) {

        // カラムの書き込み
        mb_convert_variables('SJIS', 'UTF-8', $column_title);
        fputcsv($f, $column_title);

        // データの書き込み
        foreach ($part_info as $row) {
          $csv_date = [];
          foreach ($column_name as $columnName) {
            $csv_date[] = $row ->  $columnName;
          }
          mb_convert_variables('SJIS', 'UTF-8', $csv_date);
          fputcsv($f, $csv_date);
        }
    }
    // ファイルを閉じる
    fclose($f);

    // HTTPヘッダ
    header("Content-Type: application/octet-stream");
    header('Content-Length: '.filesize($fileName));
    header('Content-Disposition: attachment; filename='.$fileName);
    readfile($fileName);
    unlink($fileName);
    exit;

  }
}
