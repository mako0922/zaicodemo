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
      <form action="/csv_log_download" method="post">
      @csrf
      <input type="submit" value="CSVダウンロード">
      </form>
    </div>
    @endif
    <div class="row text-center mx-auto my-auto">
      <div class="text-center mx-auto my-auto">
        {{ $zaico_log->appends(Request::only('keyword'))->appends(Request::only('log_select1'))
        ->appends(Request::only('log_select2'))->appends(Request::only('log_select3'))
        ->appends(Request::only('log_select4'))->appends(Request::only('log_select5'))
        ->appends(Request::only('log_select6'))->appends(Request::only('log_select7'))->links() }}
      </div>
    </div>
    <div class="pb-1 border-bottom">
      <div>
          <form id="zaico_serch" action="/zaico_log_serch" method="get">
            <h3>あいまい検索</h3>
             @csrf
             <h3><input class="text-left" type="text" name="keyword" @if(!empty($keyword))value="{{$keyword}}"@endif><input form="zaico_serch" type="submit" value="検索"></h3>
             <br>
          </form>
      </div>
      <form id="submit_form" action="/onchange_log" method="get">
      <h3>絞り込み検索</h3>
      <div class="row">
          <div class="col mt-1 mb-1">
            <h3 class="text-left">分類検索</h3>
              @csrf
              <select class="mt-1 mb-1 mx-auto" id="submit_select2" style="font-size: 20px; width:250px; margin-left:80px; padding-left:30px" name="log_select2" onChange="submit(this.form)">
                <option value=""></option>
                @foreach ($class_info as $class)
                <option value="{{$class->class}}" @if(!empty($log_select2) and $log_select2 === $class->class) selected @endif>{{$class->class}}</option>
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
                @foreach ($status_info as $status)
                <option value="{{$status->status_name}}" @if(!empty($log_select1) and $log_select1 === $status->status_name) selected @endif>{{$status->status_name}}</option>
                @endforeach
              </select>
          </div>
          <div class="col mt-1 mb-1">
            <h3 class="text-left">コンディション検索</h3>
              @csrf
              <select class="mt-1 mb-1 mx-auto" id="submit_select5" style="font-size: 20px; width:250px; margin-left:80px; padding-left:30px" name="log_select5" onChange="submit(this.form)">
                <option value=""></option>
                <option value="新品-常時在庫管理あり" @if(!empty($log_select5) and $log_select5 === "新品-常時在庫管理あり") selected @endif>新品-常時在庫管理あり</option>
                <option value="新品-常時在庫管理無し" @if(!empty($log_select5) and $log_select5 === "新品-常時在庫管理無し") selected @endif>新品-常時在庫管理無し</option>
                <option value="中古-常時在庫管理無し" @if(!empty($log_select5) and $log_select5 === "中古-常時在庫管理無し") selected @endif>中古-常時在庫管理無し</option>
              </select>
          </div>
          <div class="col mt-1 mb-1">
            <h3 class="text-left">保管場所検索</h3>
              @csrf
              <select class="mt-1 mb-1 mx-auto" id="submit_select6" style="font-size: 20px; width:250px; margin-left:80px; padding-left:30px" name="log_select6" onChange="submit(this.form)">
                <option value=""></option>
                @foreach ($storage_info as $storage)
                <option value="{{$storage->storage_name}}" @if(!empty($log_select6) and $log_select6 === $storage->storage_name) selected @endif>{{$storage->storage_name}}</option>
                @endforeach
              </select>
          </div>
          <div class="col mt-1 mb-1">
            <h3 class="text-left">仕入れ先検索</h3>
              @csrf
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

    @foreach ($zaico_log as $info)
    <div class="row mt-1 mb-1">
      <div class="col-6 mt-1 mb-1">
        <h3 class="text-left">{{$info->datetime}}<span @if($info->status_change == 1) style="color:red;" @endif> ステータス：{{$info->status}}</span></h3>
        <h3 class="text-left" @if($info->new_used_change == 1) style="color:red;" @endif>コンディション：<br>{{$info->new_used}}</h3>
        <img class="p-2 rounded mx-auto d-block" width="100%" src="data:png;base64,{{$info->part_photo}}" alt="part_photo">
        @if($users->authority == 10)
        <form id="zaico_log_delete{{$info->id}}" action="/zaico_log/delete" method="post">
          @csrf
          <input type="hidden" name="part_id" value="{{$info->id}}">
          <input type="hidden" name="status" value="delete">
          <input type="hidden" name="url" value="{{ str_replace(url('/'),"",request()->fullUrl()) }}">
          <button form="zaico_log_delete{{$info->id}}" type="submit" style="width:50%;background-color:red;" class="text-center border border-warning rounded p-1"><h3>ログ削除</h3></button><br><br>
        </form>
        <form id="zaico_log_registration{{$info->id}}" action="/zaico_log/registration" method="post">
          @csrf
          <input type="hidden" name="part_id" value="{{$info->id}}">
          <input type="hidden" name="status" value="registration">
          <input type="hidden" name="url" value="{{ str_replace(url('/'),"",request()->fullUrl()) }}">
          <button form="zaico_log_registration{{$info->id}}" type="submit" style="width:50%;background-color:green;" class="text-center border border-warning rounded p-1"><h3>ログから登録</h3></button><br>
        </form>
        @endif
      </div>
      <div class="col-3 mt-1 mb-1 my-auto">
        <h5 class="text-left" @if($info->revision_number_change == 1) style="color:red;" @endif>管理番号：</h5>
        <h3 class="text-left" @if($info->revision_number_change == 1) style="color:red;" @endif>{{$info->revision_number}}</h3><br>
        <h5 class="text-left" @if($info->part_number_change == 1) style="color:red;" @endif>品名：</h5>
        <h3 class="text-left" @if($info->part_number_change == 1) style="color:red;" @endif>{{$info->part_number}}</h3><br>
        <h5 class="text-left" @if($info->manufacturer_change == 1) style="color:red;" @endif>メーカ：</h5>
        <h3 class="text-left" @if($info->manufacturer_change == 1) style="color:red;" @endif>{{$info->manufacturer}}</h3><br>
        <h5 class="text-left" @if($info->class_change == 1) style="color:red;" @endif>分類：</h5>
        <h3 class="text-left" @if($info->class_change == 1) style="color:red;" @endif>{{$info->class}}</h3><br>
        <h5 class="text-left" @if($info->storage_name_change == 1) style="color:red;" @endif>保管場所：</h5>
        <h3 class="text-left" @if($info->storage_name_change == 1) style="color:red;" @endif>{{$info->storage_name}}</h3><br>
      </div>
      <div class="col-3 mt-1 mb-1 my-auto">
        <h5 class="text-left" @if($info->supplier_change == 1) style="color:red;" @endif>仕入れ先：{{$info->supplier}}</h5><br>
        <h5 class="text-left" @if($info->purchase_date_change == 1) style="color:red;" @endif>仕入れ日：{{$info->purchase_date}}</h5><br>
        @if($users->authority == 10)
        <h5 class="text-left" @if($info->cost_price_change == 1) style="color:red;" @endif>仕入れ価格：{{$info->cost_price}}円/税：{{$info->cost_price_tax}}</h5><br>
        @endif
        <h5 class="text-left" @if($info->selling_price_change == 1) style="color:red;" @endif>販売価格：{{$info->selling_price}}円/税：{{$info->selling_price_tax}}</h5><br>
        <h5 class="text-left">担当：{{$info->staff_name}}</h5><br>
        <h5 class="text-left">用途：</h5>
        <h3 class="text-left">{{$info->utilization}}</h3><br>
        <h5 class="text-left" @if($info->partnumber_change == 1) style="color:red;" @endif>数量：</h5>
        <h3 class="text-left" @if($info->partnumber_change == 1) style="color:red;" @endif>{{$info->partnumber}}</h3><br>
      </div>
    </div>
    <div class="border-bottom">
      <h5 class="text-left" @if($info->comment_change == 1) style="color:red;" @endif>コメント：</h5>
      <h3 class="text-left" @if($info->comment_change == 1) style="color:red;" @endif><pre @if($info->comment_change == 1) style="color:red;" @endif>{{$info->comment}}</pre></h3><br>
    </div>
    @endforeach
  </div>
</section>

<section id="sec2">
  <div class="container">
    <div class="row text-center mx-auto my-auto">
      <div class="text-center mx-auto my-auto">
        {{ $zaico_log->appends(Request::only('keyword'))->appends(Request::only('log_select1'))
        ->appends(Request::only('log_select2'))->appends(Request::only('log_select3'))
        ->appends(Request::only('log_select4'))->appends(Request::only('log_select5'))
        ->appends(Request::only('log_select6'))->appends(Request::only('log_select7'))->links() }}
      </div>
    </div>
  </div>
</section>

<!------------------------------------------------------------------------------------------------------------------>
<!------------------------------------------------------------------------------------------------------------------>
</main>
<div id="page_top"><a href="#"></a></div>

@include('layouts.partials.footer')
