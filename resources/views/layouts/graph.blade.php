<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>@yield('title')</title>
<!-- <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">-->
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/queries.css">
<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.4.1/css/all.css" integrity="sha384-5sAR7xN1Nv6T6+dT2mhtzEpVJvfS3NScPQTrOxhwjIuvcA67KV2R5Jz6kr4abQsz" crossorigin="anonymous">
<link rel="shortcut icon" href="{{ asset('/favicon/favicon_aud.ico') }}">
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.1.4/Chart.min.js"></script>
</head>
<body id="page-top">
<!------------------------------------------------------------------------------------------------------------------>
<header>
<nav class="navbar navbar-expand-md navbar-dark">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><img src="img/AUD_logo.png" alt="Graph Alert"></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span> </button>
             <ul class="navbar-nav">
                <li class="nav-item active">
                  <div class="collapse navbar-collapse justify-content-end" id="navbarSupportedContent">
                  <a href={{ route('logout') }} onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();">
                      Logout
                  </a>
                  <form id='logout-form' action={{ route('logout')}} method="POST" style="display: none;">
                      @csrf
                  </form>
                  </div>
                </li>
            </ul>
     </div>
</nav>
@if (Auth::check())
<p>USER: {{$users->name}}</p>
@else
<p>※ログインしていません。(<a href="/login">ログイン</a>)</p>
@endif
</header>
<main>
<!------------------------------------------------------------------------------------------------------------------>
<section id="sec1">
  <div class="container">
    <form id="submit_form" action="/onchange" method="post">
    @csrf
    <select id="submit_select" style="font-size: 25px; width:150px;" name="exchange" onChange="submit(this.form)">
      @foreach ($exchange as $currency)
      <option value="{{$currency->exchange_name}}" @if ($currency_ini == $currency->exchange_name) selected @endif >{{$currency->exchange_name}}</option>
      @endforeach
    </select>
    </form>

    <script type="text/javascript" src="{{ asset('/js/jquery.select-submit-change.js') }}"></script>
    <script type="text/javascript">
    $(function() {
      $("#submit_select").SelectSubmitChange();
    });
    </script>

    <h2 class="text-center">@yield('title_exchange')</h2>
    <div class="row">
      <h3 class="col text-center">Sell</h3>
      <h3 class="col text-center">Buy</h3>
    </div>
    <canvas id="myChart" width="100" height="100"></canvas>
    <script>
    var ctx = document.getElementById('myChart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'horizontalBar',
        data: {
            labels: [
              @foreach ($data as $date)
              {{$date['price']}},
              @endforeach
               ],
            datasets: [{
                label: 'Buy(longCountPercent)',
                data: [
                  @foreach ($data as $date)
                  {{$date['longCountPercent']}},
                  @endforeach
                ],
                backgroundColor: [
                  @foreach ($data as $date)
                  @if ($date['price'] < $median['price'])
                  'rgba(255, 99, 132, 0.2)',
                  @elseif ($date['price'] == $median['price'])
                  'rgba(54, 162, 235, 1)',
                  @else
                  'rgba(54, 162, 235, 0.2)',
                  @endif
                  @endforeach
                ],
                borderColor: [
                  @foreach ($data as $date)
                  @if ($date['price'] < $median['price'])
                  'rgba(255, 99, 132, 1)',
                  @else
                  'rgba(54, 162, 235, 1)',
                  @endif
                  @endforeach
                ],
                borderWidth: 1,
            },{
                label: 'Sell(shortCountPercent)',
                data: [
                  @foreach ($data as $date)
                  {{$date['shortCountPercent']*(-1)}},
                  @endforeach
                ],
                backgroundColor: [
                  @foreach ($data as $date)
                  @if ($date['price'] > $median['price'])
                  'rgba(255, 99, 132, 0.2)',
                  @elseif ($date['price'] == $median['price'])
                  'rgba(255, 99, 132, 1)',
                  @else
                  'rgba(54, 162, 235, 0.2)',
                  @endif
                  @endforeach
                ],
                borderColor: [
                  @foreach ($data as $date)
                  @if ($date['price'] >= $median['price'])
                  'rgba(255, 99, 132, 1)',
                  @else
                  'rgba(54, 162, 235, 1)',
                  @endif
                  @endforeach
                ],
                borderWidth: 1,
            }]
        },
        options: {
            scales: {
              xAxes:[{
                stacked: true,
                ticks: {            // 目盛り
                  min: -2,          // 最小値
                  max: 2,           // 最大値
                  stepSize: 0.5,    // 軸間隔
                  fontSize: 20      // フォントサイズ
                }
              }],
                yAxes: [{
                    stacked: true,
                    ticks: {            // 目盛り
                      callback: function(value) {return ((value % 0.25) == 0)? value : ''},
                      //min: -2,          // 最小値
                      //max: 2,           // 最大値
                      //stepSize: 2     // 軸間隔
                      fontSize: 20      // フォントサイズ
                    }
                }]
            },
            legend: {
              display: false
            }
        }
    });
    </script>
    <div class="row">
      <h3 class="col text-center">Sell</h3>
      <h3 class="col text-center">Buy</h3>
    </div>
    <h3 class="text-center">{{$updatetime}}</h3>
  </div><!-- .container -->
</section>
<!------------------------------------------------------------------------------------------------------------------>

<section id="sec2">
  <div class="container">
    <!-- TradingView Widget BEGIN -->
    <div class="tradingview-widget-container fx_graph">
      <div class="tradingview-widget-container__widget"></div>
      <div class="tradingview-widget-copyright">TradingView提供の<a href="https://jp.tradingview.com/markets/currencies/" rel="noopener" target="_blank"><span class="blue-text">FX</span></a></div>
      <script type="text/javascript" src="https://s3.tradingview.com/external-embedding/embed-widget-market-overview.js" async>
      {
        "colorTheme": "light",
        "dateRange": "1m",
        "showChart": true,
        "locale": "ja",
        "width": "100%",
        "height": "660",
        "largeChartUrl": "",
        "isTransparent": false,
        "plotLineColorGrowing": "rgba(33, 150, 243, 1)",
        "plotLineColorFalling": "rgba(33, 150, 243, 1)",
        "gridLineColor": "rgba(240, 243, 250, 1)",
        "scaleFontColor": "rgba(120, 123, 134, 1)",
        "belowLineFillColorGrowing": "rgba(33, 150, 243, 0.12)",
        "belowLineFillColorFalling": "rgba(33, 150, 243, 0.12)",
        "symbolActiveColor": "rgba(33, 150, 243, 0.12)",
        "tabs": [
          {
            "title": "FX",
            "symbols": [
              @foreach ($exchange as $currency)
              @if ($loop->last)
              {
                "s": "FX:{{strtoupper($currency->exchange_name)}}"
              }
              @else
              {
                "s": "FX:{{strtoupper($currency->exchange_name)}}"
              },
              @endif
              @endforeach
            ],
            "originalTitle": "Forex"
          }
        ]
      }
      </script>
    </div>
    <!-- TradingView Widget END -->
  </div><!-- .container -->
</section>
<!------------------------------------------------------------------------------------------------------------------>
</main>

<footer id="footer">
    <div class="container py-5">
        <div id="footer-contents" class="row mb-5">
            <div class="col-lg-6 col-xl-8">
                <address class="col-lg-10 offset-lg-1 mb-0">
                </address>
            </div>
            <div id="footer-news" class="col-lg-6 col-xl-4">
                <div class="col-lg-10 offset-lg-1">
                    <p class="footer-ttl"></p>
                </div>
            </div>
        </div><!-- .row -->
       <div id="footer-banner" class="container">
        </div><!-- /.container -->

    </div><!-- .container -->
    <div id="copyright">
        <p class="text-center mb-0 pt-3 pb-3">&copy;&ensp;mako</p>
    </div><!-- .container-fluid -->
</footer>
<!------------------------------------------------------------------------------------------------------------------>
<!-- javascript はここから -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="js/bootstrap.min.js"></script>
<script src="js/script.js"></script>
</body>
</html>
