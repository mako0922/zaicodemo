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
<section id="sec1">
  <div class="container">
    <div class="pb-1 border-bottom">
      <div>
          <form id="zaico_serch" action="/zaico_log_serch" method="post">
            <h3>あいまい検索</h3>
             @csrf
             <h3><input class="text-left" type="text" name="keyword" @if(!empty($keyword))value="{{$keyword}}"@endif><input form="zaico_serch" type="submit" value="検索"></h3>
             <br>
          </form>
      </div>
      <form id="submit_form" action="/onchange_log" method="post">
      <h3>絞り込み検索</h3>
      <div class="row">
          <div class="col mt-1 mb-1">
            <h3 class="text-left">分類検索</h3>
              @csrf
              <select class="mt-1 mb-1 mx-auto" id="submit_select2" style="font-size: 20px; width:250px; margin-left:80px; padding-left:30px" name="log_select2" onChange="submit(this.form)">
                <option value=""></option>
                @foreach ($class_table as $class_info)
                <option value="{{$class_info->class}}" @if(!empty($log_select2) and $log_select2 === $class_info->class) selected @endif>{{$class_info->class}}</option>
                @endforeach
              </select>
          </div>
          <div class="col mt-1 mb-1">
            <h3 class="text-left">メーカ検索</h3>
              @csrf
              <select class="mt-1 mb-1 mx-auto" id="submit_select4" style="font-size: 20px; width:250px; margin-left:80px; padding-left:30px" name="log_select4" onChange="submit(this.form)">
                <option value=""></option>
                @foreach ($manufacturer_info as $manufacturer)
                <option value="{{$manufacturer->manufacturer}}" @if(!empty($log_select4) and $log_select4 === $manufacturer->manufacturer) selected @endif>{{$manufacturer->manufacturer}}</option>
                @endforeach
              </select>
          </div>
          <div class="col mt-1 mb-1">
            <h3 class="text-left">品名検索</h3>
              @csrf
              <select class="mt-1 mb-1 mx-auto" id="submit_select3" style="font-size: 20px; width:250px; margin-left:80px; padding-left:30px" name="log_select3" onChange="submit(this.form)">
                <option value=""></option>
                @foreach ($part_info as $part)
                <option value="{{$part->part_name}}" @if(!empty($log_select3) and $log_select3 === $part->part_name) selected @endif>{{$part->part_name}}</option>
                @endforeach
              </select>
          </div>
          <div class="col mt-1 mb-1">
            <h3 class="text-left">ステータス検索</h3>
              @csrf
              <select class="mt-1 mb-1 mx-auto" id="submit_select1" style="font-size: 20px; width:250px; margin-left:80px; padding-left:30px" name="log_select1" onChange="submit(this.form)">
                <option value=""></option>
                <option value="入荷" @if(!empty($log_select1) and $log_select1 === '入荷') selected @endif>入荷</option>
                <option value="出荷" @if(!empty($log_select1) and $log_select1 === '出荷') selected @endif>出荷</option>
                <option value="新品" @if(!empty($log_select1) and $log_select1 === '新品') selected @endif>新品</option>
                <option value="中古" @if(!empty($log_select1) and $log_select1 === '中古') selected @endif>中古</option>
              </select>
          </div>
          <script type="text/javascript" src="{{ asset('/js/jquery.select-submit-change.js') }}"></script>
          <script type="text/javascript">
            $(function() {
              $("#submit_select").SelectSubmitChange();
            });
          </script>
        </form>
      </div>
    </div>

    @foreach ($zaico_log as $info)
    <div class="row mt-1 mb-1 border-bottom">
      <div class="col-6 mt-1 mb-1">
        <h3 class="text-center">{{$info->datetime}} {{$info->status}}</h3>
        <img class="p-2 rounded mx-auto d-block" width="100%" src="data:png;base64,{{$info->part_photo}}" alt="part_photo">
        @if($users->authority == 10)
        <form id="zaico_log_delete{{$info->id}}" action="/zaico_log/delete" method="post">
          @csrf
          <input type="hidden" name="part_id" value="{{$info->id}}">
          <input type="hidden" name="status" value="delete">
          <button form="zaico_log_delete{{$info->id}}" type="submit" style="width:50%;background-color:red;" class="text-center border border-warning rounded p-1"><h3>削除</h3></button><br>
        </form>
        @endif
      </div>
      <div class="col-3 mt-1 mb-1 my-auto">
        <h5 class="text-left">品名：</h5>
        <h3 class="text-left">{{$info->part_number}}</h3><br>
        <h5 class="text-left">メーカ：</h5>
        <h3 class="text-left">{{$info->manufacturer}}</h3><br>
        <h5 class="text-left">分類：</h5>
        <h3 class="text-left">{{$info->class}}</h3><br>
        <h5 class="text-left">保管場所：</h5>
        <h3 class="text-left">{{$info->storage_name}}</h3><br>
      </div>
      <div class="col-3 mt-1 mb-1 my-auto">
        <h5 class="text-left">担当：</h5>
        <h3 class="text-left">{{$info->staff_name}}</h3><br>
        <h5 class="text-left">用途：</h5>
        <h3 class="text-left">{{$info->utilization}}</h3><br>
        <h5 class="text-left">数量：</h5>
        <h3 class="text-left">{{$info->partnumber}}</h3><br>
        <h5 class="text-left">コメント：</h5>
        <h3 class="text-left">{{$info->comment}}</h3><br>
      </div>
    </div>
    @endforeach
  </div>
</section>
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
