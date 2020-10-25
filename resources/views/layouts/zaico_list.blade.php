@include('layouts.partials.head')

</head>
<body id="page-top">
<!------------------------------------------------------------------------------------------------------------------>
@include('layouts.partials.header')

<main>
  <h1 class="text-center ml-5" style="color: black; font-size:3.0em;">@yield('title_exchange')</h1>
<!------------------------------------------------------------------------------------------------------------------>

<section id="sec1">
  <div class="container">
    @if($users->authority == 10)
    <div class="pb-1 border-bottom">
      <form action="/csv_download" method="post">
      @csrf
      <input type="submit" value="CSVダウンロード">
      </form>
    </div>
    @endif
    <div class="row text-center mx-auto my-auto">
      <div class="text-center mx-auto my-auto">
        {{ $part_info->appends(Request::only('keyword'))->appends(Request::only('log_select1'))
        ->appends(Request::only('log_select2'))->appends(Request::only('log_select3'))
        ->appends(Request::only('log_select4'))->appends(Request::only('log_select5'))
        ->appends(Request::only('log_select6'))->appends(Request::only('$log_select7'))->links() }}
      </div>
    </div>
    <div class="pb-1 border-bottom">
      <div>
          <form id="part_list_serch" action="/part_list_serch" method="get">
            <h3>あいまい検索</h3>
             @csrf
             <h3><input class="text-left" type="text" name="keyword" @if(!empty($keyword))value="{{$keyword}}"@endif><input form="part_list_serch" type="submit" value="検索"></h3>
             <br>
          </form>
      </div>
      <form id="submit_form" action="/onchange_list" method="get">
      <div class="row">
          <div class="col mt-1 mb-1">
            <h3 class="text-left">分類検索</h3>
              <select class="mt-1 mb-1 mx-auto" id="submit_select2" style="font-size: 20px; width:250px; margin-left:80px; padding-left:30px" name="log_select2" onChange="submit(this.form)">
                <option value=""></option>
                @foreach ($class_info as $class)
                <option value="{{$class->class}}" @if(!empty($log_select2) and $log_select2 === $class->class) selected @endif>{{$class->class}}</option>
                @endforeach
              </select>
          </div>
          <div class="col mt-1 mb-1">
            <h3 class="text-left">メーカ検索</h3>
              <select class="mt-1 mb-1 mx-auto" id="submit_select4" style="font-size: 20px; width:250px; margin-left:80px; padding-left:30px" name="log_select4" onChange="submit(this.form)">
                <option value=""></option>
                @foreach ($manufacturer_info as $manufacturer)
                <option value="{{$manufacturer->manufacturer}}" @if(!empty($log_select4) and $log_select4 === $manufacturer->manufacturer) selected @endif>{{$manufacturer->manufacturer}}</option>
                @endforeach
              </select>
          </div>
          <div class="col mt-1 mb-1">
            <h3 class="text-left">品名検索</h3>
              <select class="mt-1 mb-1 mx-auto" id="submit_select3" style="font-size: 20px; width:250px; margin-left:80px; padding-left:30px" name="log_select3" onChange="submit(this.form)">
                <option value=""></option>
                @foreach ($part_info as $part)
                <option value="{{$part->part_name}}" @if(!empty($log_select3) and $log_select3 === $part->part_name) selected @endif>{{$part->part_name}}</option>
                @endforeach
              </select>
          </div>
          <div class="col mt-1 mb-1">
            <h3 class="text-left">ステータス検索</h3>
              <select class="mt-1 mb-1 mx-auto" id="submit_select1" style="font-size: 20px; width:250px; margin-left:80px; padding-left:30px" name="log_select1" onChange="submit(this.form)">
                <option value=""></option>
                @foreach ($status_info as $status)
                <option value="{{$status->status_name}}" @if(!empty($log_select1) and $log_select1 === $status->status_name) selected @endif>{{$status->status_name}}</option>
                @endforeach
              </select>
          </div>
          <div class="col mt-1 mb-1">
            <h3 class="text-left">コンディション検索</h3>
              <select class="mt-1 mb-1 mx-auto" id="submit_select5" style="font-size: 20px; width:250px; margin-left:80px; padding-left:30px" name="log_select5" onChange="submit(this.form)">
                <option value=""></option>
                <option value="新品-常時在庫管理あり" @if(!empty($log_select5) and $log_select5 === "新品-常時在庫管理あり") selected @endif>新品-常時在庫管理あり</option>
                <option value="新品-常時在庫管理無し" @if(!empty($log_select5) and $log_select5 === "新品-常時在庫管理無し") selected @endif>新品-常時在庫管理無し</option>
                <option value="中古-常時在庫管理無し" @if(!empty($log_select5) and $log_select5 === "中古-常時在庫管理無し") selected @endif>中古-常時在庫管理無し</option>
              </select>
          </div>
          <div class="col mt-1 mb-1">
            <h3 class="text-left">保管場所検索</h3>
              <select class="mt-1 mb-1 mx-auto" id="submit_select6" style="font-size: 20px; width:250px; margin-left:80px; padding-left:30px" name="log_select6" onChange="submit(this.form)">
                <option value=""></option>
                @foreach ($storage_info as $storage)
                <option value="{{$storage->storage_name}}" @if(!empty($log_select6) and $log_select6 === $storage->storage_name) selected @endif>{{$storage->storage_name}}</option>
                @endforeach
              </select>
          </div>
          <div class="col mt-1 mb-1">
            <h3 class="text-left">仕入れ先検索</h3>
              <select class="mt-1 mb-1 mx-auto" id="submit_select7" style="font-size: 20px; width:250px; margin-left:80px; padding-left:30px" name="log_select7" onChange="submit(this.form)">
                <option value=""></option>
                @foreach ($supplier_info as $supplier)
                <option value="{{$supplier->supplier_name}}" @if(!empty($log_select7) and $log_select7 === $supplier->supplier_name) selected @endif>{{$supplier->supplier_name}}</option>
                @endforeach
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

    @foreach ($part_info as $info)
    <div class="row mt-1 mb-1">
      <div class="col-6 mt-1 mb-1">
        <img name="image_main_{{$info->id}}" class="p-2 rounded mx-auto d-block" width="100%" src="data:png;base64,{{$info->part_photo}}" alt="part_photo"><br><br>
        <div class="row">
        <img name="image_sub1_{{$info->id}}" class="p-2 rounded mx-auto d-block" width="20%" src="data:png;base64,{{$info->sub_part_photo_1}}" alt="part_photo1" onmouseover="onmouseover1_{{$info->id}}()" onmouseout="onmouseout_{{$info->id}}()">
        <img name="image_sub2_{{$info->id}}" class="p-2 rounded mx-auto d-block" width="20%" src="data:png;base64,{{$info->sub_part_photo_2}}" alt="part_photo2" onmouseover="onmouseover2_{{$info->id}}()" onmouseout="onmouseout_{{$info->id}}()">
        <img name="image_sub3_{{$info->id}}" class="p-2 rounded mx-auto d-block" width="20%" src="data:png;base64,{{$info->sub_part_photo_3}}" alt="part_photo3" onmouseover="onmouseover3_{{$info->id}}()" onmouseout="onmouseout_{{$info->id}}()">
        </div>
      </div>
      <div class="col-3 mt-1 mb-1 my-auto">
        <h5 class="text-left">管理番号：</h5>
        <h3 class="text-left">{{$info->revision_number}}</h3><br>
        <h5 class="text-left">品名：</h5>
        <h3 class="text-left">{{$info->part_name}}</h3><br>
        <h5 class="text-left">メーカ：</h5>
        <h3 class="text-left">{{$info->manufacturer}}</h3><br>
        <h5 class="text-left">分類：</h5>
        <h3 class="text-left">{{$info->class}}</h3><br>
        <h5 class="text-left">保管場所：</h5>
        <h3 class="text-left">{{$info->storage_name}}</h3><br>
        <h5 class="text-left">ステータス：{{$info->status}}</h5><br>
        <h5 class="text-left">仕入れ先：{{$info->supplier}}</h5><br>
      </div>
      <div class="col-3 mt-1 mb-1 my-auto">
        <h5 class="text-left">コンディション：<br>{{$info->new_used}}</h5><br>
        <h5 class="text-left">仕入れ日：{{$info->purchase_date}}</h5><br>
        @if($users->authority == 10)
        <h5 class="text-left">仕入れ価格：{{$info->cost_price}}円/税：{{$info->cost_price_tax}}</h5><br>
        @endif
        <h5 class="text-left">販売価格：{{$info->selling_price}}円/税：{{$info->selling_price_tax}}</h5><br>
        <h5 class="text-center p-1 border border-primary">在庫：{{$info->stock}}</h5><br>
        <h5 class="text-center">👇</h5><br>
        @if($info->new_used == "新品-常時在庫管理あり")
        <form id="zaico_arrival{{$info->id}}" action="/zaico_input/arrival" method="post">
          @csrf
          <input type="hidden" name="part_name" value="{{$info->part_name}}">
          <input type="hidden" name="id" value="{{$info->id}}">
          <input type="hidden" name="rec_and_ship" value="arrival">
          <input type="hidden" name="url" value="{{ str_replace(url('/'),"",request()->fullUrl()) }}">
          <button form="zaico_arrival{{$info->id}}" type="submit" style="width:100%;background-color:skyblue;" class="text-center border border-primary rounded p-1"><h3>入荷</h3></button><br><br>
        </form>
        @endif
        <form id="zaico_utilize{{$info->id}}" action="/zaico_input/utilize" method="post">
          @csrf
          <input type="hidden" name="part_name" value="{{$info->part_name}}">
          <input type="hidden" name="id" value="{{$info->id}}">
          <input type="hidden" name="rec_and_ship" value="utilize">
          <input type="hidden" name="url" value="{{ str_replace(url('/'),"",request()->fullUrl()) }}">
          <button form="zaico_utilize{{$info->id}}" type="submit" style="width:100%;background-color:orange;" class="text-center border border-warning rounded p-1"><h3>出荷</h3></button><br><br>
        </form>
        <form id="zaico_update{{$info->id}}" action="/zaico_input/update" method="post">
          @csrf
          <input type="hidden" name="part_name" value="{{$info->part_name}}">
          <input type="hidden" name="id" value="{{$info->id}}">
          <input type="hidden" name="status" value="update">
          <input type="hidden" name="url" value="{{ str_replace(url('/'),"",request()->fullUrl()) }}">
          <button form="zaico_update{{$info->id}}" type="submit" style="width:100%;background-color:green;" class="text-center border border-primary rounded p-1"><h3>変更</h3></button><br><br>
        </form>
        @if($users->authority == 10)
        <form id="zaico_delete{{$info->id}}" action="/zaico_input/delete" method="post">
          @csrf
          <input type="hidden" name="part_name" value="{{$info->part_name}}">
          <input type="hidden" name="id" value="{{$info->id}}">
          <input type="hidden" name="status" value="delete">
          <input type="hidden" name="url" value="{{ str_replace(url('/'),"",request()->fullUrl()) }}">
          <button form="zaico_delete{{$info->id}}" type="submit" style="width:100%;background-color:red;" class="text-center border border-warning rounded p-1"><h3>削除</h3></button><br>
        </form>
        @endif
      </div>
    </div>
    <div class="pb-1 border-bottom">
      <h5 class="text-left">コメント：</h5>
      <h3 class="text-left text_pc" style="height: 10vh; overflow: scroll; transform: translateZ(0);"><pre>{{$info->comment}}</pre></h3><br>
    </div>
    @endforeach
  </div>
</section>

<script type="text/javascript">
  @foreach ($part_info as $info)
    function onmouseover1_{{$info->id}}() {
      var photo_baffer = document.image_main_{{$info->id}}.src;
      document.image_main_{{$info->id}}.src = document.image_sub1_{{$info->id}}.src;
      document.image_sub1_{{$info->id}}.src = photo_baffer;
    }

    function onmouseover2_{{$info->id}}() {
      var photo_baffer = document.image_main_{{$info->id}}.src;
      document.image_main_{{$info->id}}.src = document.image_sub2_{{$info->id}}.src;
      document.image_sub2_{{$info->id}}.src = photo_baffer;
    }

    function onmouseover3_{{$info->id}}() {
      var photo_baffer = document.image_main_{{$info->id}}.src;
      document.image_main_{{$info->id}}.src = document.image_sub3_{{$info->id}}.src;
      document.image_sub3_{{$info->id}}.src = photo_baffer;
    }

    function onmouseout_{{$info->id}}() {
      document.image_main_{{$info->id}}.src = "data:png;base64,{{$info->part_photo}}";
      document.image_sub1_{{$info->id}}.src = "data:png;base64,{{$info->sub_part_photo_1}}";
      document.image_sub2_{{$info->id}}.src = "data:png;base64,{{$info->sub_part_photo_2}}";
      document.image_sub3_{{$info->id}}.src = "data:png;base64,{{$info->sub_part_photo_3}}";
    }
  @endforeach
</script>
<section id="sec2">
  <div class="container">
    <div class="row text-center mx-auto my-auto">
      <div class="text-center mx-auto my-auto">
        {{ $part_info->appends(Request::only('keyword'))->appends(Request::only('log_select1'))
        ->appends(Request::only('log_select2'))->appends(Request::only('log_select3'))
        ->appends(Request::only('log_select4'))->appends(Request::only('log_select5'))
        ->appends(Request::only('log_select6'))->appends(Request::only('$log_select7'))->links() }}
      </div>
    </div>
  </div>
</section>

<!------------------------------------------------------------------------------------------------------------------>
<!------------------------------------------------------------------------------------------------------------------>
</main>
<div id="page_top"><a href="#"></a></div>

@include('layouts.partials.footer')
