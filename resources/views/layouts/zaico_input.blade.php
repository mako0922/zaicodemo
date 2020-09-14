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
<form id="zaico_input" action="/zaico_input/register" method="post" enctype="multipart/form-data">
@csrf
<input type="hidden" name="id" value="{{$info -> id}}">
<section id="sec1">
  <div class="container">
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">管理番号：</h2>
      </div>
      <div class="col-8">
        <h2>{{$info->revision_number}}</h2>
        <input type="hidden" name="revision_number" @if(!empty($info))value="{{$info->revision_number}}"@endif>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">品番：</h2>
      </div>
      <div class="col-8">
        <h2>{{$info->part_name}}</h2>
        <input type="hidden" name="partName" @if(!empty($info))value="{{$info->part_name}}"@endif>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">メーカ：</h2>
      </div>
      <div class="col-8">
        <h2>{{$info->manufacturer}}</h2>
        <input type="hidden" name="manufacturer" @if(!empty($info))value="{{$info->manufacturer}}"@endif>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">分類：</h2>
      </div>
      <div class="col-8">
        <h2>{{$info->class}}</h2>
        <input type="hidden" name="class_name" @if(!empty($info))value="{{$info->class}}"@endif>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">保管場所：</h2>
      </div>
      <div class="col-8">
        <h2>{{$info->storage_name}}</h2>
        <input type="hidden" name="storage_name" @if(!empty($info))value="{{$info->storage_name}}"@endif>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">コメント：</h2>
      </div>
      <div class="col-8">
        <h2 class="text-left text_pc" style="height: 10vh; overflow: scroll; transform: translateZ(0);">{{$info->comment}}</h2>
        <input type="hidden" name="comment" @if(!empty($info))value="{{$info->comment}}"@endif>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">仕入れ日：</h2>
      </div>
      <div class="col-8">
        <h2><input type="hidden" name="purchase_date" @if(!empty($info -> purchase_date))value="{{$info -> purchase_date}}"@endif>{{$info -> purchase_date}}</h2>
      </div>
    </div>
    @if($users->authority == 10)
    <div class="row mb-5">
      <div class="col-6">
        <h2 class="text-center">仕入れ価格：{{$info->cost_price}}円/税区分：{{$info->cost_price_tax}}</h2>
      </div>
    </div>
    @endif
    <div class="row mb-5">
      <div class="col-6">
        <h2 class="text-center">販売価格：{{$info->selling_price}}円/税区分：{{$info->selling_price_tax}}</h2>
        <input type="hidden" name="selling_price" @if(!empty($info))value="{{$info->selling_price}}"@endif>
        <input type="hidden" name="selling_price_tax" @if(!empty($info->selling_price_tax))value="{{$info->selling_price_tax}}"@endif>
        <input type="hidden" name="cost_price" @if(!empty($info))value="{{$info->cost_price}}"@endif>
        <input type="hidden" name="cost_price_tax" @if(!empty($info->cost_price_tax))value="{{$info->cost_price_tax}}"@endif>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">担当名：</h2>
      </div>
      <div class="col-8">
        <h2><select name="staff_name">
          <option class="text-primary" value=""></option>
          @foreach ($staff as $staff_name)
          <option class="text-primary" value="{{$staff_name->name}}" @if( $staff_name->name === $users -> name) selected @endif>{{$staff_name->name}}</option>
          @endforeach
        </select></h2>
        <input type="hidden" name="staff_name" @if(!empty($users))value="{{$users->name}}"@endif>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">用途：</h2>
      </div>
      <div class="col-8">
        <h2><input type="text" name="utilization" list="work" placeholder="テキスト入力/選択" autocomplete="off">
          <datalist id="work">
            <option class="text-primary" value="">
            @if ($utilization_info != "")
            @foreach ($utilization_info as $utilization)
            <option class="text-primary" value="{{$utilization->utilization}}">
            @endforeach
            @endif
            <option class="text-primary" value="交換作業">
            <option class="text-primary" value="修理作業">
            <option class="text-primary" value="売却">
            <option class="text-primary" value="購入">
            <option class="text-primary" value="譲渡">
          </datalist>
        </h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">入出荷：</h2>
      </div>
      <div class="col-8">
        <h2><select name="rec_and_ship">
          <option class="text-primary" value=""></option>
          <option class="text-primary" value="入荷" @if(!empty($status) and $status === 'arrival') selected @endif>入荷</option>
          <option class="text-warning" value="出荷" @if(!empty($status) and $status === 'utilize') selected @endif>出荷</option></h2>
        </select></h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">数量：</h2>
      </div>
      <div class="col-8">
        <h2><input type="number" name="partNumber" min="0" value="1"></h2>
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

    <div class="row mt-5 mb-5">
      <div class="col mt-5 mb-5">
        <button form="zaico_input" type="submit"><img class="p-2 rounded mx-auto d-block border border-primary" src="img/new_input.png" alt="new_input"></a>
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
