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

<script>
var set=0;
function double() {
if(set==0){ set=1; } else {
alert("只今処理中です。\nそのままお待ちください。");
return false; }}
</script>

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
  <a class="navbar-brand" href="zaico_home"><img src="img/home_back.png" width="50px" alt="home_back"></a>
  <h5 class="my-auto">メイン画面へ戻る</h5>
</div>
<main>
<h1 class="text-center ml-5" style="color: black; font-size:3.0em;">@yield('title_exchange')</h1>
<!------------------------------------------------------------------------------------------------------------------>
<form id="part_info_form" name="Form1" action="/part_info/register" method="post" enctype="multipart/form-data">
@csrf
<section id="sec1">
  <div class="container">
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">管理番号：</h2>
      </div>
      <div class="col-8">
        <h2>
          <input type="radio" name="revision_number" value="{{$revision_number_auto}}" id="radio-0" @if(empty(old('revision_number'))) checked @endif onClick="changeDisabled()"><label for="radio-0">自動採番:   {{$revision_number_auto}}</label>&nbsp;<br>
          <input type="radio" name="revision_number" value="その他" id="radio-other" @if(!empty(old('revision_number'))) checked @endif onClick="changeDisabled()"><label for="radio-other">手動採番</label>&nbsp;
          <p style="display:inline;"><input type="text" name="revision_number" value="{{old('revision_number')}}"></p>
        </h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">品名：</h2>
      </div>
      <div class="col-8">
        <h2><input type="text" name="part_name" value="{{old('part_name')}}"></h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">メーカ：</h2>
      </div>
      <div class="col-8">
        <h2>
          @if ($manufacturer_info != "")
          <select name="manufacturer">
          	<option value=""></option>
            @foreach ($manufacturer_info as $manufacturer)
          	<option value="{{$manufacturer->manufacturer}}" @if(!empty(old('manufacturer')) and old('manufacturer') == $manufacturer->manufacturer ) selected @endif>{{$manufacturer->manufacturer}}</option>
            @endforeach
          </select><br/>
          @endif
          新規登録<br><input type="text" name="manufacturer_new" id="input_click1">
          <input type="hidden" name="hp_type" value="part_info">
          <input type="submit" value="登録" id="click1" formaction="/manufacturer_input/register">
        </h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">分類：</h2>
      </div>
      <div class="col-8">
        <h2>
          @if ($class_info != "")
          <select name="class_name">
          	<option value=""></option>
            @foreach ($class_info as $class)
          	<option value="{{$class->class}}" @if(!empty(old('class_name')) and old('class_name') == $class->class ) selected @endif>{{$class->class}}</option>
            @endforeach
          </select><br/>
          @endif
          新規登録<br><input type="text" name="class_name_new" id="input_click2">
          <input type="hidden" name="hp_type" value="part_info">
          <input type="submit" value="登録" id="click2" formaction="/class_input/register" disabled>
        </h2>
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
            <option value="{{$storage->storage_name}}" @if(!empty(old('storage')) and old('storage') == $storage->storage_name ) selected @endif>{{$storage->storage_name}}</option>
            @endforeach
          </select><br/>
          @endif
          新規登録<br><input type="text" name="storage_name_new" id="input_click3">
          <input type="hidden" name="hp_type" value="part_info">
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
            <option value="{{$status->status_name}}" @if(!empty(old('status')) and old('status') == $status->status_name ) selected @endif>{{$status->status_name}}</option>
            @endforeach
          </select><br/>
          @endif
          新規登録<br><input type="text" name="status_name_new" id="input_click4">
          <input type="hidden" name="hp_type" value="part_info">
          <input type="submit" value="登録" id="click4" formaction="/status_input/register">
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
            <option value="{{$supplier->supplier_name}}" @if(!empty(old('supplier')) and old('supplier') == $status->supplier_name ) selected @endif>{{$supplier->supplier_name}}</option>
            @endforeach
          </select><br/>
          @endif
          新規登録<br><input type="text" name="supplier_name_new" id="input_click5">
          <input type="hidden" name="hp_type" value="part_info">
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
          <input type="hidden" name='new_used' value="新品-常時在庫管理あり">新品-常時在庫管理あり<br/>
        </h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">仕入れ日：</h2>
      </div>
      <div class="col-8">
        <h2><input type="date" name="purchase_date" value="{{old('purchase_date')}}"></h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">仕入れ価格：</h2>
      </div>
      <div class="col-8">
        <div class="col-8">
          <h2><input type="number" name="cost_price" step="0.01" min="0" value="{{old('cost_price')}}">円</h2>
        </div>
        <h2>税区分：
          <select name="cost_price_tax">
            <option value="無" @if(!empty(old('cost_price_tax')) and old('cost_price_tax') == "無") selected @endif>無</option>
            <option value="内" @if(!empty(old('cost_price_tax')) and old('cost_price_tax') == "内") selected @endif>内</option>
            <option value="外" @if(!empty(old('cost_price_tax')) and old('cost_price_tax') == "外") selected @endif>外</option>
          </select><br/>
        </h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">販売価格：</h2>
      </div>
      <div class="col-8">
        <div class="col-8">
          <h2><input type="number" name="selling_price" step="0.01" min="0" value="{{old('selling_price')}}">円</h2>
        </div>
        <h2>税区分：
          <select name="selling_price_tax" value="{{old('selling_price')}}">
            <option value="無" @if(!empty(old('selling_price_tax')) and old('selling_price_tax') == "無") selected @endif>無</option>
            <option value="内" @if(!empty(old('selling_price_tax')) and old('selling_price_tax') == "内") selected @endif>内</option>
            <option value="外" @if(!empty(old('selling_price_tax')) and old('selling_price_tax') == "外") selected @endif>外</option>
          </select><br/>
        </h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">初期数量：</h2>
      </div>
      <div class="col-8">
        <h2><input type="number" name="stock" min="0" value="{{old('stock')}}"></h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">コメント：</h2>
      </div>
      <div class="col-8">
        <h2><textarea name="comment" placeholder="フリーコメントを記入ください" style="width:373px;">{{old('comment')}}</textarea></h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <label for="chooser">
          メイン写真を選択してください
        <input  id="chooser" type="file" accept="image/*" name = 'part_photo'>      <!-- ファイル選択ダイアログ（カメラも使える） -->
        </label>
      </div>
      <div class="col-8">
        <canvas id='canvas' width='300' height='400'></canvas>  <!-- 絵を描くcanvas要素 -->
        <script>

        // canvas要素に描画するためのお決まりの2行
        var canvas  = document.getElementById("canvas");        // canvas 要素の取得
        var context = canvas.getContext("2d");                  // 描画用部品を取得

        // ファイルを読む（カメラを使う）準備
        var chooser = document.getElementById("chooser");       // ファイル選択用 input 要素の取得
        var reader  = new FileReader();                         // ファイルを読む FileReader オブジェクトを作成
        var image   = new Image();                              // 画像を入れておく Image オブジェクトを作成
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
        <label for="chooser1">
          サブ1写真を選択してください
        <input  id="chooser1" type="file" accept="image/*" name = 'part_photo1'>      <!-- ファイル選択ダイアログ（カメラも使える） -->
        </label>
      </div>
      <div class="col-2">
        <canvas id='canvas1' width='100' height='200'></canvas>  <!-- 絵を描くcanvas要素 -->
        <script>

        // canvas要素に描画するためのお決まりの2行
        var canvas1  = document.getElementById("canvas1");        // canvas 要素の取得
        var context1 = canvas1.getContext("2d");                  // 描画用部品を取得

        // ファイルを読む（カメラを使う）準備
        var chooser1 = document.getElementById("chooser1");       // ファイル選択用 input 要素の取得
        var reader1  = new FileReader();                         // ファイルを読む FileReader オブジェクトを作成
        var image1   = new Image();                              // 画像を入れておく Image オブジェクトを作成
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
        <label for="chooser2">
          サブ2写真を選択してください
        <input  id="chooser2" type="file" accept="image/*" name = 'part_photo2'>      <!-- ファイル選択ダイアログ（カメラも使える） -->
        </label>
      </div>
      <div class="col-2">
        <canvas id='canvas2' width='100' height='200'></canvas>  <!-- 絵を描くcanvas要素 -->
        <script>

        // canvas要素に描画するためのお決まりの2行
        var canvas2  = document.getElementById("canvas2");        // canvas 要素の取得
        var context2 = canvas2.getContext("2d");                  // 描画用部品を取得

        // ファイルを読む（カメラを使う）準備
        var chooser2 = document.getElementById("chooser2");       // ファイル選択用 input 要素の取得
        var reader2  = new FileReader();                         // ファイルを読む FileReader オブジェクトを作成
        var image2   = new Image();                              // 画像を入れておく Image オブジェクトを作成
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
        <label for="chooser3">
          サブ3写真を選択してください
        <input  id="chooser3" type="file" accept="image/*" name = 'part_photo3'>      <!-- ファイル選択ダイアログ（カメラも使える） -->
        </label>
      </div>
      <div class="col-2">
        <canvas id='canvas3' width='100' height='200'></canvas>  <!-- 絵を描くcanvas要素 -->
        <script>

        // canvas要素に描画するためのお決まりの2行
        var canvas3  = document.getElementById("canvas3");        // canvas 要素の取得
        var context3 = canvas3.getContext("2d");                  // 描画用部品を取得

        // ファイルを読む（カメラを使う）準備
        var chooser3 = document.getElementById("chooser3");       // ファイル選択用 input 要素の取得
        var reader3  = new FileReader();                         // ファイルを読む FileReader オブジェクトを作成
        var image3   = new Image();                              // 画像を入れておく Image オブジェクトを作成
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
        <button onSubmit="return double()" form="part_info_form" type="submit"><img class="p-2 rounded mx-auto d-block border border-primary" src="img/new_input.png" alt="new_input">
        <h2 class="text-center">登録</h2></button>
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
<!------------------------------------------------------------------------------------------------------------------>
<script type="text/javascript">
$(function() {
  $('button').on('click', function() {
     $(this).prop('disabled', true);
     $('form').submit();
  });
});

</script>

<script>
  function func_click1() {
    document.getElementById("click1").disabled = false;
  }

  function func2() {
    document.getElementById("button1").disabled = false;
    document.getElementById("button2").disabled = true;
    document.getElementById("myname").value = "";
    document.getElementById("output").innerHTML = "";
  }
</script>
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

</main>
<div id="page_top"><a href="#"></a></div>
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
<script src="https://code.jquery.com/jquery-1.12.4.min.js" type="text/javascript"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/script.js"></script>
<script src="js/script_2.js"></script>
</body>
</html>
