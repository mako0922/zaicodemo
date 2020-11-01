@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">ログイン{{-- __('Dashboard') --}}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @else
                    ログイン完了
                    @endif

                    {{-- __('You are logged in!') --}}
                </div>
            </div>
        </div>
        <div class="col-md-8">
          <div class="row">
            <div class="col-md-4 m-0 p-0 mx-auto">
              <a class="navbar-brand mx-auto" href="/zaico_home"><img src="img/zaico_icon.png" width="100px" alt="zaico_icon"><h3 class="text-center">メイン画面へ</h3></a>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection
