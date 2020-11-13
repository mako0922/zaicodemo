@include('layouts.partials.head')

</head>
<body id="page-top">
<!------------------------------------------------------------------------------------------------------------------>
@include('layouts.partials.header_register')
{{--
@extends('layouts.app')

@section('content')
--}}
<section id="sec1">
  <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-8">
              <div class="card">
                  <div class="card-header">{{ __('Register') }}</div>

                  <div class="card-body">
                      <form method="POST" action="{{ route('register') }}">
                          @csrf

                          <div class="form-group row">
                              <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                              <div class="col-md-6">
                                  <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                  @error('name')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                              </div>
                          </div>

                          <div class="form-group row">
                              <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                              <div class="col-md-6">
                                  <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                  @error('email')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                              </div>
                          </div>

                          <div class="form-group row">
                              <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                              <div class="col-md-6">
                                  <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                  @error('password')
                                      <span class="invalid-feedback" role="alert">
                                          <strong>{{ $message }}</strong>
                                      </span>
                                  @enderror
                              </div>
                          </div>

                          <div class="form-group row">
                              <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{ __('Confirm Password') }}</label>

                              <div class="col-md-6">
                                  <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                              </div>
                          </div>

                          <div class="form-group row">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-right">権限</label>
                            <div class="col-md-6">
                              <h2>
                                <select name="authority_name">
                                  <option value="nomal">一般</option>
                                  <option value="administrator">管理者</option>
                                </select>
                              </h2>
                            </div>
                          </div>

                          <div class="form-group row mb-0">
                              <div class="col-md-6 offset-md-4">
                                  <button type="submit" class="btn btn-primary">
                                      {{ __('Register') }}
                                  </button>
                              </div>
                          </div>
                      </form>
                  </div>
              </div>
              @if (!empty($staff_list))
              <div class="card">
                <div class="card-header mb-2">登録済み一覧</div>
                @foreach ($staff_list as $staff)
                @if($staff -> id != 27)
                <form action="/table_item_delete" method="post">
                  <div class="form-group row ">
                    @csrf
                    <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{$staff -> name}}:</label>
                    <div class="col-md-6"><h3><input type="submit" value="削除"></h3></div>
                  </div>
                  <input type="hidden" name="id" value="{{$staff -> id}}">
                  <input type="hidden" name="table_item" value="users">
                </form>
                @endif
                @endforeach
              </div>
              @endif
          </div>
      </div>
  </div>
</section>
{{--
@endsection
--}}
@include('layouts.partials.footer')
