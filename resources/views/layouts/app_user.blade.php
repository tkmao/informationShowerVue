<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- API Token add 2019/02/14-->
    <meta name="api-token" content="{{ Auth::user()->api_token }}">

    <!-- Title -->
    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

    <!-- Script add 2019/02/14 -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <!-- Styles add 2019/02/14 -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <!-- Styles add 2019/03/29 -->
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
</head>
<body style="margin-top:70px;">
    <div id="app">
        {{-- <nav class="navbar navbar-expand-md navbar-dark bg-primary navbar-laravel navbar-fixed-top"> --}}
        <nav class="navbar navbar-expand-md navbar-dark bg-primary fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="{{ url('/top') }}">
                    {{ config('app.name', 'Laravel') }}
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <!-- Left Side Of Navbar -->
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.workschedule.show') }}">{{ __('勤務表') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.weeklyreport.create') }}">{{ __('週報登録') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.weeklyanalyze.show') }}">{{ __('週報集計') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.monthlyanalyze.show') }}">{{ __('勤務表集計') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.project.show') }}">{{ __('PJ管理') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.company.show') }}">{{ __('企業管理') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.category.show') }}">{{ __('PJ区分管理') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.projectstatus.show') }}">{{ __('PJステータス管理') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.holiday.show') }}">{{ __('休日管理') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.holidayvue.show') }}">{{ __('Vue休日管理') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.user.show') }}">{{ __('ユーザ管理') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('user.usertype.show') }}">{{ __('ユーザタイプ管理') }}</a>
                        </li>
                    </ul>

                    <!-- Right Side Of Navbar -->
                    <ul class="navbar-nav ml-auto">
                        <!-- Authentication Links -->
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('user.login') }}">{{ __('Login') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                    {{ Auth::user()->name }} <span class="caret"></span>
                                </a>

                                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                    <a class="dropdown-item" href="{{ route('user.logout') }}"
                                       onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                        {{ __('Logout') }}
                                    </a>

                                    <form id="logout-form" action="{{ route('user.logout') }}" method="POST" style="display: none;">
                                        @csrf
                                    </form>
                                </div>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    <!-- Script add 2019/02/14 -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
    <!-- Script add 2019/03/29 -->
    <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>
    <!-- Script add 2019/03/30 -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.3/Chart.min.js"></script>
    <script>
    // Script add 2019/02/14
    // API 実行するにあたって、全てのAjax通信処理に対して、任意のデフォルトオプションを指定している
    // 今回の場合は、csrf token と api token の２つ。ここは特に指定しなくてもAPI実行できるように設定する
    $(document).ready(function () {
        var csrf_token = $('meta[name="csrf-token"]').attr('content');
        var api_token = $('meta[name="api-token"]').attr('content');
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN' : csrf_token,
                'Authorization' : 'Bearer ' + api_token,
            }
        });
    });
    </script>
    <!-- Script add 2019/02/14 -->

    <script type="text/javascript">
        @yield('customJS')
    </script>
</body>
</html>
