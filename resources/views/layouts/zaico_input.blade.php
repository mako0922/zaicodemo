@include('layouts.partials.head')

</head>
<body id="page-top">
<!------------------------------------------------------------------------------------------------------------------>
@include('layouts.partials.header')

<main>
<h1 class="text-center ml-5" style="color: black; font-size:3.0em;">@yield('title_exchange')</h1>
<!------------------------------------------------------------------------------------------------------------------>
<form id="zaico_input" action="/zaico_input/register" method="post" enctype="multipart/form-data">
@csrf
<input type="hidden" name="url" value="{{$url}}">
<input type="hidden" name="part_id" @if(!empty(old('part_id'))) value="{{old('part_id')}}" @else value="{{$info -> id}}" @endif>
<section id="sec1">
  <div class="container">
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">管理番号：</h2>
      </div>
      <div class="col-8">
        <h2>@if(!empty(old('revision_number'))) {{old('revision_number')}} @else {{$info->revision_number}}@endif</h2>
        <input type="hidden" name="revision_number" @if(!empty(old('revision_number'))) value="{{old('revision_number')}}" @elseif(!empty($info))value="{{$info->revision_number}}"@endif>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">品名：</h2>
      </div>
      <div class="col-8">
        <h2>@if(!empty(old('part_name'))) {{old('part_name')}} @else {{$info->part_name}}@endif</h2>
        <input type="hidden" name="part_name" @if(!empty(old('part_name'))) value="{{old('part_name')}}" @elseif(!empty($info))value="{{$info->part_name}}"@endif>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">メーカ：</h2>
      </div>
      <div class="col-8">
        <h2>@if(!empty(old('manufacturer'))) {{old('manufacturer')}} @else {{$info->manufacturer}}@endif</h2>
        <input type="hidden" name="manufacturer" @if(!empty(old('manufacturer'))) value="{{old('manufacturer')}}" @elseif(!empty($info))value="{{$info->manufacturer}}"@endif>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">分類：</h2>
      </div>
      <div class="col-8">
        <h2>@if(!empty(old('class_name'))) {{old('class_name')}} @else {{$info->class}}@endif</h2>
        <input type="hidden" name="class_name" @if(!empty(old('class_name'))) value="{{old('class_name')}}" @elseif(!empty($info))value="{{$info->class}}"@endif>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">保管場所：</h2>
      </div>
      <div class="col-8">
        <h2>@if(!empty(old('storage'))) {{old('storage')}} @else {{$info->storage_name}}@endif</h2>
        <input type="hidden" name="storage" @if(!empty(old('storage'))) value="{{old('storage')}}" @elseif(!empty($info))value="{{$info->storage_name}}"@endif>
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
            <option value="{{$status->status_name}}" @if(!empty(old('status')) and old('status') == $status->status_name ) selected @elseif(!empty($info -> status) and $status->status_name === $info -> status) selected @endif>{{$status->status_name}}</option>
            @endforeach
          </select><br/>
          @endif
          @if($users->authority == 10)
          新規登録<br><input type="text" name="status_name_new" id="input_click4">
          <input type="hidden" name="hp_type" value="zaico_input_arrival">
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
        <h2><input type="hidden" name="supplier" @if(!empty(old('supplier'))) value="{{old('supplier')}}" @elseif(!empty($info -> supplier))value="{{$info -> supplier}}"@endif>@if(!empty(old('supplier'))) {{old('supplier')}} @else {{$info -> supplier}}@endif</h2>
      </div>
    </div>
		<div class="row mb-5">
			<div class="col-4">
				<h2 class="text-center">コンディション：</h2>
			</div>
			<div class="col-8">
				<h2><input type="hidden" name="new_used" @if(!empty(old('new_used'))) value="{{old('new_used')}}" @elseif(!empty($info -> new_used))value="{{$info -> new_used}}"@endif>@if(!empty(old('new_used'))) {{old('new_used')}} @else {{$info -> new_used}}@endif</h2>
			</div>
		</div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">コメント：</h2>
      </div>
      <div class="col-8">
        <h2><textarea name="comment" placeholder="フリーコメントを記入ください" style="width:100%; height:500px;">@if(!empty(old('comment'))) {{old('comment')}} @elseif(!empty($info -> comment)){{$info -> comment}}@endif</textarea></h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">仕入れ日：</h2>
      </div>
      <div class="col-8">
        <h2><input type="hidden" name="purchase_date" @if(!empty(old('purchase_date'))) value="{{old('purchase_date')}}" @elseif(!empty($info -> purchase_date))value="{{$info -> purchase_date}}"@endif>@if(!empty(old('purchase_date'))) {{old('purchase_date')}} @else {{$info -> purchase_date}}@endif</h2>
      </div>
    </div>
    @if($users->authority == 10)
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">仕入れ価格：</h2>
      </div>
      <div class="col-8">
        <h2>@if(!empty(old('cost_price'))) {{old('cost_price')}} @else {{$info->cost_price}}@endif円/税区分：@if(!empty(old('cost_price_tax'))) {{old('cost_price_tax')}} @else {{$info->cost_price_tax}}@endif</h2>
      </div>
    </div>
    @endif
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">販売価格：</h2>
      </div>
      <div class="col-8">
        <h2>@if(!empty(old('selling_price'))) {{old('selling_price')}} @else {{$info->selling_price}}@endif円/税区分：@if(!empty(old('selling_price_tax'))) {{old('selling_price_tax')}} @else {{$info->selling_price_tax}}@endif</h2>
        <input type="hidden" name="selling_price" @if(!empty(old('selling_price'))) value="{{old('selling_price')}}" @elseif(!empty($info))value="{{$info->selling_price}}"@endif>
        <input type="hidden" name="selling_price_tax" @if(!empty(old('selling_price_tax'))) value="{{old('selling_price_tax')}}" @elseif(!empty($info->selling_price_tax))value="{{$info->selling_price_tax}}"@endif>
        <input type="hidden" name="cost_price" @if(!empty(old('cost_price'))) value="{{old('cost_price')}}" @elseif(!empty($info))value="{{$info->cost_price}}"@endif>
        <input type="hidden" name="cost_price_tax" @if(!empty(old('cost_price_tax'))) value="{{old('cost_price_tax')}}" @elseif(!empty($info->cost_price_tax))value="{{$info->cost_price_tax}}"@endif>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">担当名：</h2>
      </div>
      <div class="col-8">
        <h2>@if(!empty($users)){{$users->name}}@endif</h2>
        <input type="hidden" name="staff_name" @if(!empty($users))value="{{$users->name}}"@endif>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">用途：</h2>
      </div>
      <div class="col-8">
        <h2>
          <input type="hidden" name="utilization" @if((!empty(old('rec_and_ship')) and old('rec_and_ship') === '入荷') or (!empty($rec_and_ship) and $rec_and_ship === 'arrival')) value="入荷処理" @elseif((!empty(old('rec_and_ship')) and old('rec_and_ship') === '出荷') or (!empty($rec_and_ship) and $rec_and_ship === 'utilize')) value="出荷処理" @endif>
          @if((!empty(old('rec_and_ship')) and old('rec_and_ship') === '入荷') or (!empty($rec_and_ship) and $rec_and_ship === 'arrival')) 入荷処理 @elseif((!empty(old('rec_and_ship')) and old('rec_and_ship') === '出荷') or (!empty($rec_and_ship) and $rec_and_ship === 'utilize')) 出荷処理 @endif <br>
        </h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">入出荷：</h2>
      </div>
      <div class="col-8">
        <h2>
          <input type="hidden" name="rec_and_ship" @if((!empty(old('rec_and_ship')) and old('rec_and_ship') === '入荷') or (!empty($rec_and_ship) and $rec_and_ship === 'arrival')) value="入荷" @elseif((!empty(old('rec_and_ship')) and old('rec_and_ship') === '出荷') or (!empty($rec_and_ship) and $rec_and_ship === 'utilize')) value="出荷" @endif>
          @if((!empty(old('rec_and_ship')) and old('rec_and_ship') === '入荷') or (!empty($rec_and_ship) and $rec_and_ship === 'arrival')) 入荷 @elseif((!empty(old('rec_and_ship')) and old('rec_and_ship') === '出荷') or (!empty($rec_and_ship) and $rec_and_ship === 'utilize')) 出荷 @endif <br>
        </h2>
      </div>
    </div>
    <div class="row mb-5">
      <div class="col-4">
        <h2 class="text-center">数量：</h2>
      </div>
      <div class="col-8">
        <h2><input type="number" name="stock" min="0" @if(!empty(old('stock')))value="{{old('stock')}}" @else value="1" @endif></h2>
      </div>
    </div>

    <div class="row mb-5">
      <div class="col-4">
        @if(!empty($info -> part_photo) or !empty(old('part_photo_origin')))
        <input type="hidden" name="part_photo_origin" @if(!empty(old('part_photo_origin')))value="{{old('part_photo_origin')}}" @else value="{{$info -> part_photo}}"@endif>
        @endif
        <label for="chooser">
          写真を選択してください
        <input  id="chooser" type="file" accept="image/*" name = 'part_photo'><!-- ファイル選択ダイアログ（カメラも使える） -->
        </label>
      </div>
      <div class="col-8">
        <canvas id='canvas' width='300' height='400'></canvas>  <!-- 絵を描くcanvas要素 -->
        <script>

        @if(!empty($info -> part_photo) or !empty(old('part_photo_origin')))
        var data  = "data:image/png;base64,@if(!empty(old('part_photo_origin'))){{old('part_photo_origin')}} @else {{$info -> part_photo}}@endif";
        @endif


        // canvas要素に描画するためのお決まりの2行
        var canvas  = document.getElementById("canvas");        // canvas 要素の取得
        var context = canvas.getContext("2d");                  // 描画用部品を取得

        // ファイルを読む（カメラを使う）準備
        var chooser = document.getElementById("chooser");       // ファイル選択用 input 要素の取得
        var reader  = new FileReader();                         // ファイルを読む FileReader オブジェクトを作成
        var image   = new Image();

        @if(!empty($info -> part_photo) or !empty(old('part_photo_origin')))
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

@include('layouts.partials.footer')
