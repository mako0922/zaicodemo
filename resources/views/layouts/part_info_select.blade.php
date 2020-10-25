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
        <a class="" href="part_info"><img class="p-2 rounded mx-auto d-block border border-primary" src="img/new_part.png" alt="new_part"></a>
        <h2 class="text-center">1.新品<br>定数在庫管理あり部品</h2>
      </div>
      <div class="col mt-5 mb-5">
        <a class="" href="used_info"><img class="p-2 rounded mx-auto d-block border border-primary" src="img/used_logo.png" alt="used_logo"></a>
        <h2 class="text-center">2.新品/中古<br>現品のみ在庫</h2>
      </div>
    </div>
  </div>
</section>
</main>

@include('layouts.partials.footer')
