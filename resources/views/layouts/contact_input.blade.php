@include('layouts.partials.head')

</head>
<body id="page-top">
<!------------------------------------------------------------------------------------------------------------------>
@include('layouts.partials.header_contact')

<main>
<h1 class="text-center ml-5" style="color: black; font-size:3.0em;">@yield('title_exchange')</h1>

<form method="POST" action="{{ route('contact.confirm') }}">
	@csrf
	<section id="sec1">
		<div class="container">
			<div class="row mb-5">
	      <div class="col-4">
	        <h2 class="text-left">お客様<br>メールアドレス：</h2>
				</div>
				<div class="col-8">
					<h2>
				    <input
				        name="email"
				        value="{{ old('email') }}"
				        type="text">
				    @if ($errors->has('email'))
				        <p class="error-message">{{ $errors->first('email') }}</p>
				    @endif
					</h2>
				</div>
			</div>
		</div>

		<div class="container">
			<div class="row mb-5">
				<div class="col-4">
					<h2 class="text-left">申請者様 氏名：</h2>
				</div>
				<div class="col-8">
					<h2>
				    <input
				        name="customer_name"
				        value="{{ old('customer_name') }}"
				        type="text">
				    @if ($errors->has('customer_name'))
				        <p class="error-message">{{ $errors->first('customer_name') }}</p>
				    @endif
					</h2>
				</div>
			</div>
		</div>

		<div class="container">
			<div class="row mb-5">
				<div class="col-4">
					<h2 class="text-left">その他ご質問など：</h2>
				</div>
				<div class="col-8">
					<h2>
				    <textarea name="body">{{ old('body') }}</textarea>
				    @if ($errors->has('body'))
				        <p class="error-message">{{ $errors->first('body') }}</p>
				    @endif
					</h2>
				</div>
			</div>
		</div>

		<div class="container">
			<div class="row mb-5">
				<div class="col-4 mx-auto my-auto">
			    <h2>
						<button type="submit">
			        入力内容確認
			    	</button>
				</h2>
				</div>
			</div>
		</div>

	</section>
</form>

</main>

@include('layouts.partials.footer')
