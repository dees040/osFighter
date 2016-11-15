<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        @yield('title') - {{ config('app.name', 'osFighter') }}
        @if(game('app_slogan'))
            - {{ game('app_slogan') }}
        @endif
    </title>

    <!-- Styles -->
    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
<div id="app">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-3 col-lg-2">
                @include('layouts.menu.outgame')
            </div>
            <div class="col-xs-12 col-sm-8 col-lg-5 col-lg-offset-1">
                <div class="row">
                    <div class="col-md-12">
                        <div class="panel panel-default panel-main-content">
                            <div class="panel-heading">@yield('title')</div>

                            <div class="panel-body">
                                @include('layouts.blocks.messages')
                                @yield('content')
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="/js/app.js"></script>
</body>
</html>
