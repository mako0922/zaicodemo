@include('layouts.partials.head')

</head>
<body id="page-top">
<!------------------------------------------------------------------------------------------------------------------>
@include('layouts.partials.header_contact')

<main>
<h1 class="text-center ml-5" style="color: black; font-size:3.0em;">@yield('title_exchange')</h1>

<h2>{{ __('送信完了') }}</h2>
<h2>登録ありがとうございました。メールの返信をお待ちください。</h2>
<h2>本ページを閉じてください。</h2>
</main>

@include('layouts.partials.footer')
