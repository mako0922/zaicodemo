@include('layouts.partials.head')

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
@include('layouts.partials.header')

<main>
<h1 class="text-center ml-5" style="color: black; font-size:3.0em;">@yield('title_exchange')</h1>

<section id="sec0">
  <div class="container">
    <h2>登録済み一覧</h2>
    @foreach ($class_info as $class)
    <form action="/table_item_delete" method="post">
      <div class="row mb-5">
        @csrf
        <div class="col-6"><h3>{{$class -> class}}:</h3></div>
        <div class="col-4"><h3><input type="submit" value="削除"></h3></div>
      </div>
      <input type="hidden" name="id" value="{{$class -> id}}">
      <input type="hidden" name="table_item" value="class_table">
    </form>
    @endforeach
  </div>
</section>
<!------------------------------------------------------------------------------------------------------------------>
<form id="class_input" action="/class_input/register" method="post">
@csrf
<section id="sec1">
  <div class="container">
    <h2>新規登録</h2>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">分類：</h2>
      </div>
      <div class="col-8">
        <h2><input type="text" name="class_name_new"></h2>
      </div>
    </div>

    <div class="row mt-5 mb-5">
      <div class="col mt-5 mb-5">
        <button class="btn" form="class_input" type="submit"><img class="p-2 rounded mx-auto d-block border border-primary" src="img/new_input.png" alt="new_input"></a>
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

@include('layouts.partials.footer')
