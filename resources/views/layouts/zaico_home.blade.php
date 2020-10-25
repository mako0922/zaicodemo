@include('layouts.partials.head')

</head>
<body id="page-top">
<!------------------------------------------------------------------------------------------------------------------>
@include('layouts.partials.header')

<main>
<!------------------------------------------------------------------------------------------------------------------>
<h1 class="text-center ml-5" style="color: black; font-size:3.0em;">@yield('title_exchange')</h1>
<section id="sec1">
  <div class="container">
    <div class="row mt-5 mb-5">
      <div class="col mt-5 mb-5">
        <a class="" href="zaico_list"><img class="p-2 rounded mx-auto d-block border border-primary" src="img/zaico_list.png" alt="zaico_list"></a>
        <h2 class="text-center">在庫管理</h2>
        <h2 class="text-center">入荷・出荷登録</h2>
      </div>
      <div class="col mt-5 mb-5">
        <a class="" href="zaico_log"><img class="p-2 rounded mx-auto d-block border border-primary" src="img/zaico_log.png" alt="zaico_log"></a>
        <h2 class="text-center">在庫管理ログ</h2>
      </div>
    </div>
    <div class="row mt-5 mb-5">
      <div class="col mt-5 mb-5">
        <a class="" href="part_info_select"><img class="p-2 rounded mx-auto d-block border border-primary" src="img/part_info_icon.png" alt="part_info_icon"></a>
        <h2 class="text-center">新規部品追加</h2>
      </div>
      @if($users->authority == 10)
      <div class="col mt-5 mb-5">
        <a class="" href="manufacturer_input"><img class="p-2 rounded mx-auto d-block border border-primary" src="img/manufacturer_icon.png" alt="manufacturer_icon"></a>
        <h2 class="text-center">メーカ名登録/削除</h2>
      </div>
    </div>
    <div class="row mt-5 mb-5">
      <div class="col mt-5 mb-5">
        <a class="" href="storage_input"><img class="p-2 rounded mx-auto d-block border border-primary" src="img/storage_logo.png" alt="storage_logo"></a>
        <h2 class="text-center">保管場所登録/削除</h2>
      </div>
      <div class="col mt-5 mb-5">
        <a class="" href="class_input"><img class="p-2 rounded mx-auto d-block border border-primary" src="img/class_icon.png" alt="class_icon"></a>
        <h2 class="text-center">分類登録/削除</h2>
      </div>
    </div>
    <div class="row mt-5 mb-5">
      <div class="col mt-5 mb-5">
        <a class="" href="status_input"><img class="p-2 rounded mx-auto d-block border border-primary" src="img/used_logo.png" alt="used_logo"></a>
        <h2 class="text-center">ステータス登録/削除</h2>
      </div>
      <div class="col mt-5 mb-5">
        <a class="" href="supplier_input"><img class="p-2 rounded mx-auto d-block border border-primary" src="img/supplier.png" alt="supplier"></a>
        <h2 class="text-center">仕入れ先登録/削除</h2>
      </div>
      @endif
    </div>
  </div>
</section>
</main>

@include('layouts.partials.footer')
