@include('layouts.partials.head')

</head>
<body id="page-top">
<!------------------------------------------------------------------------------------------------------------------>
@include('layouts.partials.header')
<section id="sec1">
<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-8">
      <div class="card">
        <div class="card-header">ユーザー登録内容の変更</div>
        <div class="card-body">
          <form method="POST" action="{{ action('Admin\UserController@update') }}">
						@csrf
            <div class="form-group">
              <label for="name">
                名前
              </label>
              <div>
                <input type="text" name="name" class="form-control" value="{{ $user_update->name }}">
              </div>
            </div>
            <div class="form-group">
              <label for="email">
                email
              </label>
              <div>
                <input type="text" name="email" class="form-control" value="{{ $user_update->email }}">
              </div>
              <button type="submit" class="user-btn">変更</button>
              {{ csrf_field() }}
          </form>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="form-group row mb-0">
	<div class="col-md-8 offset-md-4">
		@if (Route::has('password.request'))
				<a class="btn btn-link" href="{{ route('password.request') }}">
				パスワードを再設定する
				</a>
		@endif
	</div>
</div>
<!------------------------------------------------------------------------------------------------------------------>
<!------------------------------------------------------------------------------------------------------------------>
</section>

@include('layouts.partials.footer')
