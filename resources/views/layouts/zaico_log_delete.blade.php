@include('layouts.partials.head')

</head>
<body id="page-top">
<!------------------------------------------------------------------------------------------------------------------>
@include('layouts.partials.header')

<main>
<h1 class="text-center ml-5" style="color: black; font-size:3.0em;">@yield('title_exchange')</h1>
<!------------------------------------------------------------------------------------------------------------------>
<form id="zaico_log_delete_form" action="/zaico_log_delete/register" method="post" enctype="multipart/form-data">
<input type="hidden" name="id" @if(!empty($info -> id))value="{{$info -> id}}"@endif>
<input type="hidden" name="url" value="{{$url}}">
@csrf
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
        <h2><input type="hidden" name="part_name" @if(!empty($info -> part_number))value="{{$info -> part_number}}"@endif>{{$info -> part_number}}</h2>
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
        <h2><input type="hidden" name="stock" @if(!empty($info -> partnumber))value="{{$info -> partnumber}}"@endif><pre>{{$info -> partnumber}}</pre></h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">コメント：</h2>
      </div>
      <div class="col-8">
        <input type="hidden" name="comment" @if(!empty($info -> comment))value="{{$info -> comment}}"@endif>
        <h2 class="text-left text_pc" style="height: 10vh; overflow: scroll; transform: translateZ(0);">{{$info->comment}}</h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <input type="hidden" name="part_photo" @if(!empty($info -> part_photo))value="{{$info -> part_photo}}"@endif>
        <img class="p-2 rounded mx-auto d-block" width="100%" src="data:png;base64,{{$info->part_photo}}" alt="part_photo">
      </div>
    </div>
    <div class="row mt-5 mb-5">
      <div class="col mt-5 mb-5">
        <button form="zaico_log_delete_form" type="submit"><img class="p-2 rounded mx-auto d-block border border-primary" src="img/delete_logo.png" alt="delete_logo"></a>
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

@include('layouts.partials.footer')
