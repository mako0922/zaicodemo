<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>@yield('title')</title>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/queries.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
<link rel="shortcut icon" href="{{ asset('/favicon/favicon_zaico.ico') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>

<script type="text/javascript">
<!--
function changeDisabled() {
    if ( document.Form1["revision_number"][1].checked ) {
        document.Form1["revision_number"][2].disabled = false;
    } else {
        document.Form1["revision_number"][2].disabled = true;
    }
}
window.onload = changeDisabled;
// -->
</script>

</head>
<body id="page-top">
<!------------------------------------------------------------------------------------------------------------------>
<header>
<nav class="navbar navbar-expand-md navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="zaico_home"><img src="img/zaico_icon.png" alt="zaico_icon"></a>
        <h5>メイン画面へ戻る</h5>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span> </button>
             <ul class="navbar-nav">
                <li class="nav-item active">
                  <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                  <a href={{ route('logout') }} onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();">
                      Logout
                  </a>
                  <form id='logout-form' action={{ route('logout')}} method="POST" style="display: none;">
                      @csrf
                  </form>
                  </div>
                </li>
            </ul>
     </div>
</nav>
@if (Auth::check())
<p>USER: {{$users->name}}</p>
@else
<p>※ログインしていません。(<a href="/login">ログイン</a>)</p>
@endif
</header>

<div>
  <ul>
    @foreach ($errors->all() as $error)
    <li>{{ $error }}</li>
    @endforeach
  </ul>
</div>

<div class="d-flex">
  <a class="navbar-brand" href="zaico_home"><img src="img/home_back.png" width="100px" alt="home_back">
  <h5 class="my-auto navbar-brand">メイン画面へ戻る</h5></a>
</div>
<main>
<h1 class="text-center ml-5" style="color: black; font-size:3.0em;">@yield('title_exchange')</h1>
<!------------------------------------------------------------------------------------------------------------------>
<form id="part_update_form" name="Form1" action="/part_update/register" method="post" enctype="multipart/form-data">
@csrf
<input type="hidden" name="part_id" @if(!empty($id_old)) value="{{$id_old}}" @elseif(!empty($info -> id))value="{{$info -> id}}"@endif>
<input type="hidden" name="url" @if(!empty($url_old)) value="{{$url_old}}" @elseif(!empty($url))value="{{$url}}"@endif>
<section id="sec1">
  <div class="container">
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">管理番号：</h2>
      </div>
      <div class="col-8">
        @if($users->authority == 10)
        <h2>
          <input type="radio" name="revision_number" value="{{$revision_number_auto}}" id="radio-0" @if(empty($revision_number_old) and empty($info -> revision_number)) checked @endif onClick="changeDisabled()"><label for="radio-0">自動採番:   {{$revision_number_auto}}</label>&nbsp;<br>
          <input type="radio" name="revision_number" value="その他" id="radio-other" @if(!empty($revision_number_old) or !empty($info -> revision_number)) checked @endif onClick="changeDisabled()"><label for="radio-other">手動採番</label>&nbsp;
          <p style="display:inline;"><input type="text" name="revision_number" @if(!empty($revision_number_old)) value="{{$revision_number_old}}" @elseif(!empty($info -> revision_number))value="{{$info -> revision_number}}"@endif></p>
        </h2>
        @else
        <input type="hidden" name="revision_number" @if(!empty($revision_number_old)) value="{{$revision_number_old}}" @elseif(!empty($info -> revision_number))value="{{$info -> revision_number}}"@endif>
        <h2>@if(!empty($revision_number_old)){{$revision_number_old}}@elseif(!empty($info -> revision_number)){{$info -> revision_number}}@endif</h2>
        @endif
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">品名：</h2>
      </div>
      <div class="col-8">
        @if($users->authority == 10)
        <h2><input type="text" name="part_name" @if(!empty($part_name_old)) value="{{$part_name_old}}" @elseif(!empty($info -> part_name))value="{{$info -> part_name}}"@endif></h2>
        @else
        <input type="hidden" name="part_name" @if(!empty($part_name_old)) value="{{$part_name_old}}" @elseif(!empty($info -> part_name))value="{{$info -> part_name}}"@endif>
        <h2>@if(!empty($part_name_old)){{$part_name_old}}@elseif(!empty($info -> part_name)){{$info -> part_name}}@endif</h2>
        @endif
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">メーカ：</h2>
      </div>
      <div class="col-8">
        @if($users->authority == 10)
        <h2>
          @if ($manufacturer_info != "")
          <select name="manufacturer">
          	<option value=""></option>
            @foreach ($manufacturer_info as $manufacturer)
          	<option value="{{$manufacturer->manufacturer}}" @if(!empty($manufacturer_old) and $manufacturer_old == $manufacturer->manufacturer ) selected @elseif(empty($manufacturer_old) and !empty($info -> manufacturer) and $info -> manufacturer === $manufacturer->manufacturer ) selected @endif>{{$manufacturer->manufacturer}}</option>
            @endforeach
          </select><br/>
          @endif
          新規登録<br><input type="text" name="manufacturer_new" id="input_click1">
          <input type="hidden" name="hp_type" value="part_update">
          <input type="submit" value="登録" id="click1" formaction="/manufacturer_input/register">
        </h2>
        @else
        <input type="hidden" name="manufacturer" @if(!empty($manufacturer_old)) value="{{$manufacturer_old}}" @elseif(!empty($info -> manufacturer))value="{{$info -> manufacturer}}"@endif>
        <h2>@if(!empty($manufacturer_old)){{$manufacturer_old}}@elseif(!empty($info -> manufacturer)){{$info -> manufacturer}}@endif</h2>
        @endif
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">分類：</h2>
      </div>
      <div class="col-8">
        @if($users->authority == 10)
        <h2>
          @if ($class_info != "")
          <select name="class_name">
          	<option value=""></option>
            @foreach ($class_info as $class)
          	<option value="{{$class->class}}" @if(!empty($class_name_old) and $class_name_old == $class->class ) selected @elseif(empty($class_name_old) and !empty($info -> class) and $info -> class === $class->class ) selected @endif>{{$class->class}}</option>
            @endforeach
          </select><br/>
          @endif
          新規登録<br><input type="text" name="class_name_new" id="input_click2">
          <input type="hidden" name="hp_type" value="part_update">
          <input type="submit" value="登録" id="click2" formaction="/class_input/register">
        </h2>
        @else
        <input type="hidden" name="class_name" @if(!empty($class_name_old)) value="{{$class_name_old}}" @elseif(!empty($info -> class))value="{{$info -> class}}"@endif>
        <h2>@if(!empty($class_name_old)){{$class_name_old}}@elseif(!empty($info -> class)){{$info -> class}}@endif</h2>
        @endif
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">保管場所：</h2>
      </div>
      <div class="col-8">
        <h2>
          @if ($storage_info != "")
          <select name="storage">
            <option value=""></option>
            @foreach ($storage_info as $storage)
            <option value="{{$storage->storage_name}}" @if(!empty($storage_old) and $storage_old == $storage->storage_name ) selected @elseif(empty($storage_old) and !empty($info -> storage_name) and $info -> storage_name === $storage->storage_name ) selected @endif>{{$storage->storage_name}}</option>
            @endforeach
          </select><br/>
          @endif
          新規登録<br><input type="text" name="storage_name_new" id="input_click3">
          <input type="hidden" name="hp_type" value="part_update">
          <input type="submit" value="登録" id="click3" formaction="/storage_input/register">
        </h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">ステータス：</h2>
      </div>
      <div class="col-8">
        <h2>
          @if ($status_info != "")
          <select name="status">
            <option value=""></option>
            @foreach ($status_info as $status)
            <option value="{{$status->status_name}}" @if(!empty($status_old) and $status_old == $status->status_name ) selected @elseif(empty($status_old) and !empty($info -> status) and $status->status_name === $info -> status) selected @endif>{{$status->status_name}}</option>
            @endforeach
          </select><br/>
          @endif
          @if($users->authority == 10)
          新規登録<br><input type="text" name="status_name_new" id="input_click4">
          <input type="hidden" name="hp_type" value="part_update">
          <input type="submit" value="登録" id="click4" formaction="/status_input/register">
          @endif
        </h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">仕入れ先：</h2>
      </div>
      <div class="col-8">
        <h2>
          @if ($supplier_info != "")
          <select name="supplier">
            <option value=""></option>
            @foreach ($supplier_info as $supplier)
            <option value="{{$supplier->supplier_name}}" @if(!empty($supplier_old) and $supplier_old == $supplier->supplier_name ) selected @elseif(empty($supplier_old) and !empty($info -> supplier) and $supplier->supplier_name === $info -> supplier) selected @endif>{{$supplier->supplier_name}}</option>
            @endforeach
          </select><br/>
          @endif
          新規登録<br><input type="text" name="supplier_name_new" id="input_click5">
          <input type="hidden" name="hp_type" value="part_update">
          <input type="submit" value="登録" id="click5" formaction="/supplier_input/register">
        </h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">コンディション：</h2>
      </div>
      <div class="col-8">
        <h2>
          @if((!empty($new_used_old) and $new_used_old == "新品-常時在庫管理あり") or (empty($new_used_old) and !empty($info -> new_used) and $info -> new_used == "新品-常時在庫管理あり"))
          <input type="hidden" name="new_used" value="新品-常時在庫管理あり">新品-常時在庫管理あり<br>
          @else
          <select name="new_used">
            <option value="新品-常時在庫管理無し" @if(!empty($new_used_old) and $new_used_old == "新品-常時在庫管理無し" ) selected @elseif(empty($new_used_old) and !empty($info -> new_used) and $info -> new_used == "新品-常時在庫管理無し") selected @endif>新品-常時在庫管理無し</option>
            <option value="中古-常時在庫管理無し" @if(!empty($new_used_old) and $new_used_old == "中古-常時在庫管理無し" ) selected @elseif(empty($new_used_old) and !empty($info -> new_used) and $info -> new_used == "中古-常時在庫管理無し") selected @endif>中古-常時在庫管理無し</option>
          </select><br/>
          @endif
        </h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">仕入れ日：</h2>
      </div>
      <div class="col-8">
        @if($users->authority == 10)
        <h2><input type="date" name="purchase_date" @if(!empty($purchase_date_old)) value="{{$purchase_date_old}}" @elseif(!empty($info -> purchase_date))value="{{$info -> purchase_date}}" @endif></h2>
        @else
        <input type="hidden" name="purchase_date" @if(!empty($purchase_date_old)) value="{{$purchase_date_old}}" @elseif(!empty($info -> purchase_date))value="{{$info -> purchase_date}}"@endif>
        <h2>@if(!empty($purchase_date_old)){{$purchase_date_old}}@elseif(!empty($info -> purchase_date)){{$info -> purchase_date}}@endif</h2>
        @endif
      </div>
    </div>
    @if($users->authority == 10)
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">仕入れ価格：</h2>
      </div>
      <div class="col-8">
        <div class="col-8">
          <h2><input type="number" name="cost_price" step="0.01" min="0" @if(!empty($cost_price_old)) value="{{$cost_price_old}}" @elseif(!empty($info -> cost_price))value="{{$info -> cost_price}}" @endif>円</h2>
        </div>
        <h2>税区分：
          <select name="cost_price_tax">
            <option value="無" @if(!empty($cost_price_tax_old) and $cost_price_tax_old == "無" ) selected @elseif(empty($cost_price_tax_old) and !empty($info -> cost_price_tax) and "無" === $info -> cost_price_tax) selected @endif>無</option>
            <option value="内" @if(!empty($cost_price_tax_old) and $cost_price_tax_old == "内" ) selected @elseif(empty($cost_price_tax_old) and !empty($info -> cost_price_tax) and "内" === $info -> cost_price_tax) selected @endif>内</option>
            <option value="外" @if(!empty($cost_price_tax_old) and $cost_price_tax_old == "外" ) selected @elseif(empty($cost_price_tax_old) and !empty($info -> cost_price_tax) and "外" === $info -> cost_price_tax) selected @endif>外</option>
          </select><br/>
        </h2>
      </div>
    </div>
    @else
    <input type="hidden" name="cost_price" @if(!empty($cost_price_old)) value="{{$cost_price_old}}" @elseif(!empty($info -> cost_price))value="{{$info -> cost_price}}"@endif>
    <input type="hidden" name="cost_price_tax" @if(!empty($cost_price_tax_old)) value="{{$cost_price_tax_old}}" @elseif(!empty($info -> cost_price_tax))value="{{$info -> cost_price_tax}}"@endif>
    @endif
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">販売価格：</h2>
      </div>
      <div class="col-8">
        <div class="col-8">
          @if($users->authority == 10)
          <h2><input type="number" name="selling_price" step="0.01" min="0" @if(!empty($selling_price_old)) value="{{$selling_price_old}}" @elseif(!empty($info -> selling_price))value="{{$info -> selling_price}}" @endif>円</h2>
          @else
          <input type="hidden" name="selling_price" @if(!empty($selling_price_old)) value="{{$selling_price_old}}" @elseif(!empty($info -> selling_price))value="{{$info -> selling_price}}"@endif>
          <h2>@if(!empty($selling_price_old)){{$selling_price_old}}@elseif(!empty($info -> selling_price)){{$info -> selling_price}}@endif円</h2>
          @endif
        </div>
        <h2>税区分：
          @if($users->authority == 10)
          <select name="selling_price_tax">
            <option value="無" @if(!empty($selling_price_tax_old) and $selling_price_tax_old == "無" ) selected @elseif(empty($selling_price_tax_old) and !empty($info -> selling_price_tax) and "無" === $info -> selling_price_tax) selected @endif>無</option>
            <option value="内" @if(!empty($selling_price_tax_old) and $selling_price_tax_old == "内" ) selected @elseif(empty($selling_price_tax_old) and !empty($info -> selling_price_tax) and "内" === $info -> selling_price_tax) selected @endif>内</option>
            <option value="外" @if(!empty($selling_price_tax_old) and $selling_price_tax_old == "外" ) selected @elseif(empty($selling_price_tax_old) and !empty($info -> selling_price_tax) and "外" === $info -> selling_price_tax) selected @endif>外</option>
          </select><br/>
          @else
          <input type="hidden" name="selling_price_tax" @if(!empty($selling_price_tax_old)) value="{{$selling_price_tax_old}}" @elseif(!empty($info -> selling_price_tax))value="{{$info -> selling_price_tax}}"@endif>
          @if(!empty($selling_price_tax_old)){{$selling_price_tax_old}}@elseif(!empty($info -> selling_price_tax)){{$info -> selling_price_tax}}@endif
          @endif
        </h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">数量：</h2>
      </div>
      <div class="col-8">
        @if($users->authority == 10)
        <h2><input type="number" name="stock" min="0" @if(!empty($stock_old)) value="{{$stock_old}}" @elseif(!empty($info -> stock))value="{{$info -> stock}}"@else value="0"@endif></h2>
        @else
        <input type="hidden" name="stock" @if(!empty($stock_old)) value="{{$stock_old}}" @elseif(!empty($info -> stock))value="{{$info -> stock}}"@else value="0" @endif>
        <h2>@if(!empty($stock_old)){{$stock_old}}@elseif(!empty($info -> stock)){{$info -> stock}} @else 0 @endif</h2>
        @endif
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">コメント：</h2>
      </div>
      <div class="col-8">
        <h2><textarea name="comment" placeholder="フリーコメントを記入ください" style="width:100%; height:500px;">@if(!empty($comment_old)) {{$comment_old}} @elseif(!empty($info -> comment)){{$info -> comment}}@endif</textarea></h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        @if(!empty($info -> part_photo))
        <input type="hidden" name="part_photo_origin" value="{{$info -> part_photo}}">
        @endif
        <label for="chooser">
          写真を選択してください
        <input  id="chooser" type="file" accept="image/*" name = 'part_photo'><!-- ファイル選択ダイアログ（カメラも使える） -->
        </label>
      </div>
      <div class="col-8">
        <canvas id='canvas' width='300' height='400'></canvas>  <!-- 絵を描くcanvas要素 -->
        <script>

        @if(!empty($info -> part_photo))
        var data  = "data:image/png;base64,{{$info -> part_photo}}";
        @endif


        // canvas要素に描画するためのお決まりの2行
        var canvas  = document.getElementById("canvas");        // canvas 要素の取得
        var context = canvas.getContext("2d");                  // 描画用部品を取得

        // ファイルを読む（カメラを使う）準備
        var chooser = document.getElementById("chooser");       // ファイル選択用 input 要素の取得
        var reader  = new FileReader();                         // ファイルを読む FileReader オブジェクトを作成
        var image   = new Image();

        @if(!empty($info -> part_photo))
        image.src  = data;
        @endif                            // 画像を入れておく Image オブジェクトを作成
        // ファイルを読み込む処理
        chooser.addEventListener("change", () => {              // ファイル選択ダイアログの値が変わったら
            var file = chooser.files[0];                        // ファイル名取得
            reader.readAsDataURL(file);                         // FileReader でファイルを読み込む
        });
        reader.addEventListener("load", () => {                 // FileReader がファイルの読み込みを完了したら
            image.src = reader.result;                          // Image オブジェクトに読み込み結果を入れる
        });
        image.addEventListener("load", () => {                  // Image オブジェクトに画像が入ったら
            context.drawImage(image, 0, 0, 300, 400);           // 画像を canvas に描く（Image, Left, Top, Width, Height）
        });
        </script>
      </div>
    </div>

    <div class="row mb-5">
      <div class="col-2">
        @if(!empty($info -> sub_part_photo_1))
        <input type="hidden" name="part_photo1_origin" value="{{$info -> sub_part_photo_1}}">
        @endif
        <label for="chooser1">
          サブ1写真を選択してください
        <input  id="chooser1" type="file" accept="image/*" name = 'part_photo1'>      <!-- ファイル選択ダイアログ（カメラも使える） -->
        </label>
      </div>
      <div class="col-2">
        <canvas id='canvas1' width='100' height='200'></canvas>  <!-- 絵を描くcanvas要素 -->
        <script>

        @if(!empty($info -> sub_part_photo_1))
        var data1  = "data:image/png;base64,{{$info -> sub_part_photo_1}}";
        @endif

        // canvas要素に描画するためのお決まりの2行
        var canvas1  = document.getElementById("canvas1");        // canvas 要素の取得
        var context1 = canvas1.getContext("2d");                  // 描画用部品を取得

        // ファイルを読む（カメラを使う）準備
        var chooser1 = document.getElementById("chooser1");       // ファイル選択用 input 要素の取得
        var reader1  = new FileReader();                         // ファイルを読む FileReader オブジェクトを作成
        var image1   = new Image();                              // 画像を入れておく Image オブジェクトを作成

        @if(!empty($info -> sub_part_photo_1))
        image1.src  = data1;
        @endif

        // ファイルを読み込む処理
        chooser1.addEventListener("change", () => {              // ファイル選択ダイアログの値が変わったら
            var file1 = chooser1.files[0];                        // ファイル名取得
            reader1.readAsDataURL(file1);                         // FileReader でファイルを読み込む
        });
        reader1.addEventListener("load", () => {                 // FileReader がファイルの読み込みを完了したら
            image1.src = reader1.result;                          // Image オブジェクトに読み込み結果を入れる
        });
        image1.addEventListener("load", () => {                  // Image オブジェクトに画像が入ったら
            context1.drawImage(image1, 0, 0, 100, 200);           // 画像を canvas に描く（Image, Left, Top, Width, Height）
        });
        </script>
      </div>

      <div class="col-2">
        @if(!empty($info -> sub_part_photo_2))
        <input type="hidden" name="part_photo2_origin" value="{{$info -> sub_part_photo_2}}">
        @endif
        <label for="chooser2">
          サブ2写真を選択してください
        <input  id="chooser2" type="file" accept="image/*" name = 'part_photo2'>      <!-- ファイル選択ダイアログ（カメラも使える） -->
        </label>
      </div>
      <div class="col-2">
        <canvas id='canvas2' width='100' height='200'></canvas>  <!-- 絵を描くcanvas要素 -->
        <script>

        @if(!empty($info -> sub_part_photo_2))
        var data2  = "data:image/png;base64,{{$info -> sub_part_photo_2}}";
        @endif

        // canvas要素に描画するためのお決まりの2行
        var canvas2  = document.getElementById("canvas2");        // canvas 要素の取得
        var context2 = canvas2.getContext("2d");                  // 描画用部品を取得

        // ファイルを読む（カメラを使う）準備
        var chooser2 = document.getElementById("chooser2");       // ファイル選択用 input 要素の取得
        var reader2  = new FileReader();                         // ファイルを読む FileReader オブジェクトを作成
        var image2   = new Image();                              // 画像を入れておく Image オブジェクトを作成

        @if(!empty($info -> sub_part_photo_2))
        image2.src  = data2;
        @endif

        // ファイルを読み込む処理
        chooser2.addEventListener("change", () => {              // ファイル選択ダイアログの値が変わったら
            var file2 = chooser2.files[0];                        // ファイル名取得
            reader2.readAsDataURL(file2);                         // FileReader でファイルを読み込む
        });
        reader2.addEventListener("load", () => {                 // FileReader がファイルの読み込みを完了したら
            image2.src = reader2.result;                          // Image オブジェクトに読み込み結果を入れる
        });
        image2.addEventListener("load", () => {                  // Image オブジェクトに画像が入ったら
            context2.drawImage(image2, 0, 0, 100, 200);           // 画像を canvas に描く（Image, Left, Top, Width, Height）
        });
        </script>
      </div>

      <div class="col-2">
        @if(!empty($info -> sub_part_photo_3))
        <input type="hidden" name="part_photo3_origin" value="{{$info -> sub_part_photo_3}}">
        @endif
        <label for="chooser3">
          サブ3写真を選択してください
        <input  id="chooser3" type="file" accept="image/*" name = 'part_photo3'>      <!-- ファイル選択ダイアログ（カメラも使える） -->
        </label>
      </div>
      <div class="col-2">
        <canvas id='canvas3' width='100' height='200'></canvas>  <!-- 絵を描くcanvas要素 -->
        <script>

        @if(!empty($info -> sub_part_photo_3))
        var data3  = "data:image/png;base64,{{$info -> sub_part_photo_3}}";
        @endif

        // canvas要素に描画するためのお決まりの2行
        var canvas3  = document.getElementById("canvas3");        // canvas 要素の取得
        var context3 = canvas3.getContext("2d");                  // 描画用部品を取得

        // ファイルを読む（カメラを使う）準備
        var chooser3 = document.getElementById("chooser3");       // ファイル選択用 input 要素の取得
        var reader3  = new FileReader();                         // ファイルを読む FileReader オブジェクトを作成
        var image3   = new Image();                              // 画像を入れておく Image オブジェクトを作成

        @if(!empty($info -> sub_part_photo_3))
        image3.src  = data3;
        @endif

        // ファイルを読み込む処理
        chooser3.addEventListener("change", () => {              // ファイル選択ダイアログの値が変わったら
            var file3 = chooser3.files[0];                        // ファイル名取得
            reader3.readAsDataURL(file3);                         // FileReader でファイルを読み込む
        });
        reader3.addEventListener("load", () => {                 // FileReader がファイルの読み込みを完了したら
            image3.src = reader3.result;                          // Image オブジェクトに読み込み結果を入れる
        });
        image3.addEventListener("load", () => {                  // Image オブジェクトに画像が入ったら
            context3.drawImage(image3, 0, 0, 100, 200);           // 画像を canvas に描く（Image, Left, Top, Width, Height）
        });
        </script>
      </div>

    </div>


    <div class="row mt-5 mb-5">
      <div class="col mt-5 mb-5">
        <button form="part_update_form" type="submit"><img class="p-2 rounded mx-auto d-block border border-primary" src="img/update_logo.png" alt="update_logo"></a>
        <h2 class="text-center">更新</h2></button>
      </div>
      <div class="col mt-5 mb-5">
        <a class="" href="zaico_home"><img class="p-2 rounded mx-auto d-block border border-primary" src="img/home_back.png" alt="home_back"></a>
        <h2 class="text-center">ホームに戻る</h2>
      </div>
    </div>
  </div>
</section>
</form>
<!------------------------------------------------------------------------------------------------------------------>

<script>
window.addEventListener('DOMContentLoaded',function(){
document.getElementById('click1').disabled = true;
document.getElementById('input_click1').addEventListener('keyup',function(){
if (this.value.length < 2) {
document.getElementById('click1').disabled = true;
} else {
document.getElementById('click1').disabled = false;
}
},false);
document.getElementById('input_click1').addEventListener('change',function(){
if (this.value.length < 2) {
document.getElementById('click1').disabled = true;
}
},false);
},false);
</script>

<script>
window.addEventListener('DOMContentLoaded',function(){
document.getElementById('click2').disabled = true;
document.getElementById('input_click2').addEventListener('keyup',function(){
if (this.value.length < 2) {
document.getElementById('click2').disabled = true;
} else {
document.getElementById('click2').disabled = false;
}
},false);
document.getElementById('input_click2').addEventListener('change',function(){
if (this.value.length < 2) {
document.getElementById('click2').disabled = true;
}
},false);
},false);
</script>

<script>
window.addEventListener('DOMContentLoaded',function(){
document.getElementById('click3').disabled = true;
document.getElementById('input_click3').addEventListener('keyup',function(){
if (this.value.length < 2) {
document.getElementById('click3').disabled = true;
} else {
document.getElementById('click3').disabled = false;
}
},false);
document.getElementById('input_click3').addEventListener('change',function(){
if (this.value.length < 2) {
document.getElementById('click3').disabled = true;
}
},false);
},false);
</script>

<script>
window.addEventListener('DOMContentLoaded',function(){
document.getElementById('click4').disabled = true;
document.getElementById('input_click4').addEventListener('keyup',function(){
if (this.value.length < 2) {
document.getElementById('click4').disabled = true;
} else {
document.getElementById('click4').disabled = false;
}
},false);
document.getElementById('input_click4').addEventListener('change',function(){
if (this.value.length < 2) {
document.getElementById('click4').disabled = true;
}
},false);
},false);
</script>

<script>
window.addEventListener('DOMContentLoaded',function(){
document.getElementById('click5').disabled = true;
document.getElementById('input_click5').addEventListener('keyup',function(){
if (this.value.length < 2) {
document.getElementById('click5').disabled = true;
} else {
document.getElementById('click5').disabled = false;
}
},false);
document.getElementById('input_click5').addEventListener('change',function(){
if (this.value.length < 2) {
document.getElementById('click5').disabled = true;
}
},false);
},false);
</script>

<!------------------------------------------------------------------------------------------------------------------>
</main>

<footer id="footer">
    <div class="container py-5">
        <div id="footer-contents" class="row mb-5">
            <div class="col-lg-6 col-xl-8">
                <address class="col-lg-10 offset-lg-1 mb-0">
                </address>
            </div>
            <div id="footer-news" class="col-lg-6 col-xl-4">
                <div class="col-lg-10 offset-lg-1">
                    <p class="footer-ttl"></p>
                </div>
            </div>
        </div><!-- .row -->
       <div id="footer-banner" class="container">
        </div><!-- /.container -->

    </div><!-- .container -->
    <div id="copyright">
        <p class="text-center mb-0 pt-3 pb-3">&copy;&ensp;mako</p>
    </div><!-- .container-fluid -->
</footer>
<!------------------------------------------------------------------------------------------------------------------>
<!-- javascript はここから -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>
