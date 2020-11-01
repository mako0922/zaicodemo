@include('layouts.partials.head')

</head>
<body id="page-top">
<!------------------------------------------------------------------------------------------------------------------>
@include('layouts.partials.header_contact')

<main>
<h1 class="text-center ml-5" style="color: black; font-size:3.0em;">@yield('title_exchange')</h1>

<form method="POST" action="{{ route('contact.send') }}">
    @csrf
    <section id="sec1">
      <div class="container">
        <div class="row mb-5">
          <h2 class="text-left">お客様メールアドレス：{{ $inputs['email'] }}</h2>
            <input
                name="email"
                value="{{ $inputs['email'] }}"
                type="hidden">
  			</div>
  		</div>

      <div class="container">
        <div class="row mb-5">
          <h2 class="text-left">申請者様 氏名：{{ $inputs['customer_name'] }}</h2>
            <input
                name="customer_name"
                value="{{ $inputs['customer_name'] }}"
                type="hidden">
  			</div>
  		</div>

      <div class="container">
        <div class="row mb-5">
          <h2 class="text-left">その他ご質問など：{!! nl2br(e($inputs['body'])) !!}</h2>
            <input
                name="body"
                value="{{ $inputs['body'] }}"
                type="hidden">
  			</div>
  		</div>

      <div class="container">
        <div class="row mb-5">
          <h2>
            <button type="submit" name="action" value="back">
                入力内容修正
            </button>
          </h2>
        </div>
      </div>

      <div class="container">
        <div class="row mb-5">
          <h2>
            <button type="submit" name="action" value="submit">
                送信する
            </button>
          </h2>
        </div>
      </div>

  </section>
</form>

</main>

@include('layouts.partials.footer')
