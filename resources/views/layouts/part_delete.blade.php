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
//送信ボタンを押した際に送信ボタンを無効化する（連打による多数送信回避）
$(function(){
	$('[type="submit"]').click(function(){
		$(this).prop('disabled',true);//ボタンを無効化する
		$(this).closest('form').submit();//フォームを送信する
	});
});
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
<form id="part_delete_form" action="/part_delete/register" method="post" enctype="multipart/form-data">
@csrf
<input type="hidden" name="url" value="{{$url}}">
<input type="hidden" name="id" value="{{$info -> id}}">
<section id="sec1">
  <div class="container">
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">管理番号：</h2>
      </div>
      <div class="col-8">
        <h2><input type="hidden" name="revision_number" @if(!empty($info -> revision_number))value="{{$info -> revision_number}}"@endif>{{$info -> revision_number}}</h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">品名：</h2>
      </div>
      <div class="col-8">
        <h2><input type="hidden" name="part_name" @if(!empty($info -> part_name))value="{{$info -> part_name}}"@endif>{{$info -> part_name}}</h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">メーカ：</h2>
      </div>
      <div class="col-8">
        <h2><input type="hidden" name="manufacturer" @if(!empty($info -> manufacturer))value="{{$info -> manufacturer}}"@endif>{{$info -> manufacturer}}</h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">分類：</h2>
      </div>
      <div class="col-8">
        <h2><input type="hidden" name="class_name" @if(!empty($info -> class))value="{{$info -> class}}"@endif>{{$info -> class}}</h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">保管場所：</h2>
      </div>
      <div class="col-8">
        <h2><input type="hidden" name="storage" @if(!empty($info -> storage_name))value="{{$info -> storage_name}}"@endif>{{$info -> storage_name}}</h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">ステータス：</h2>
      </div>
      <div class="col-8">
        <h2><input type="hidden" name="status" @if(!empty($info -> status))value="{{$info -> status}}"@endif>{{$info -> status}}</h2>
      </div>
    </div>
		<div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">仕入れ先：</h2>
      </div>
      <div class="col-8">
        <h2><input type="hidden" name="supplier" @if(!empty($info -> supplier))value="{{$info -> supplier}}"@endif>{{$info -> supplier}}</h2>
      </div>
    </div>
		<div class="row mb-5">
			<div class="col-4">
				<h2 class="text-center">コンディション：</h2>
			</div>
			<div class="col-8">
				<h2><input type="hidden" name="new_used" @if(!empty($info -> new_used))value="{{$info -> new_used}}"@endif>{{$info -> new_used}}</h2>
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
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">仕入れ価格：</h2>
      </div>
      <div class="col-8">
        <div class="col-8">
          <h2><input type="hidden" name="cost_price" @if(!empty($info -> cost_price))value="{{$info -> cost_price}}" @endif>{{$info -> cost_price}}円</h2>
        </div>
        <h2>税区分：<input type="hidden" name="cost_price_tax" @if(!empty($info -> cost_price_tax))value="{{$info -> cost_price_tax}}" @endif>{{$info -> cost_price_tax}}</h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">販売価格：</h2>
      </div>
      <div class="col-8">
        <div class="col-8">
          <h2><input type="hidden" name="selling_price" @if(!empty($info -> selling_price))value="{{$info -> selling_price}}" @endif>{{$info -> selling_price}}円</h2>
        </div>
        <h2>税区分：<input type="hidden" name="selling_price_tax" @if(!empty($info -> selling_price_tax))value="{{$info -> selling_price_tax}}" @endif>{{$info -> selling_price_tax}}</h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">初期数量：</h2>
      </div>
      <div class="col-8">
        <h2><input type="hidden" name="stock" @if(!empty($info -> stock))value="{{$info -> stock}}"@else value="0"@endif>{{$info -> stock}}</h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">コメント：</h2>
      </div>
      <div class="col-8">
        <h2 class="text-left text_pc" style="height: 10vh; overflow: scroll; transform: translateZ(0);"><pre>{{$info->comment}}</pre></h2>
				<input type="hidden" name="comment" value="{{$info->comment}}">
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <img class="p-2 rounded mx-auto d-block" width="100%" src="data:png;base64,{{$info->part_photo}}" alt="part_photo">
        <input type="hidden" name="part_photo" value="{{$info->part_photo}}" alt="part_photo">
      </div>
    </div>
    <div class="row mt-5 mb-5">
      <div class="col mt-5 mb-5">
        <button form="part_delete_form" type="submit"><img class="p-2 rounded mx-auto d-block border border-primary" src="img/delete_logo.png" alt="delete_logo"></a>
        <h2 class="text-center">削除</h2></button>
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
