@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Dashboard') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{ __('You are logged in!') }}
                </div>
            </div>
        </div>
        <div class="col-md-8">
          <div class="row">
            <div class="col-md-4 m-0 p-0">
              <a class="navbar-brand" href="/audjpy"><img src="img/AUD_logo.png" alt="AUDJPY_LOGO"><h2 class="text-center">AUDJPY</h2></a>
            </div>
            <div class="col-md-4 m-0 p-0">
              <a class="navbar-brand" href="#"><img src="img/AUD_logo.png" alt=""><h2 class="text-center">dummy</h2></a>
            </div>
            <div class="col-md-4 m-0 p-0">
              <a class="navbar-brand" href="#"><img src="img/AUD_logo.png" alt=""><h2 class="text-center">dummy</h2></a>
            </div>
          </div>
        </div>
    </div>
</div>
@endsection
