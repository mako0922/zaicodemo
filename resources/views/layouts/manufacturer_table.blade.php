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

<section id="sec0">
  <div class="container">
    <h2>登録済み一覧</h2>
    @foreach ($manufacturer_info as $manufacturer)
    <form action="/table_item_delete" method="post">
      <div class="row mb-5">
        @csrf
        <div class="col-6"><h3>{{$manufacturer -> manufacturer}}:</h3></div>
        <div class="col-4"><h3><input type="submit" value="削除"></h3></div>
      </div>
      <input type="hidden" name="id" value="{{$manufacturer -> id}}">
      <input type="hidden" name="table_item" value="manufacturer_table">
    </form>
    @endforeach
  </div>
</section>
<!------------------------------------------------------------------------------------------------------------------>
<form id="manufacturer_input" action="/manufacturer_input/register" method="post">
@csrf
<section id="sec1">
  <div class="container">
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">メーカ名：</h2>
      </div>
      <div class="col-8">
        <h2><input type="text" name="manufacturer_new"></h2>
      </div>
    </div>

    <div class="row mt-5 mb-5">
      <div class="col mt-5 mb-5">
        <button form="manufacturer_input" type="submit"><img class="p-2 rounded mx-auto d-block border border-primary" src="img/new_input.png" alt="new_input"></a>
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
