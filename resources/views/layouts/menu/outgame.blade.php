<nav class="navbar navbar-default navbar-fixed-side">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#main-left-navbar" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="{{ route('home') }}">{{ config('app.name', 'osFighter') }}</a>
        </div>

        <div class="collapse navbar-collapse" id="main-left-navbar">
            <ul class="nav navbar-nav">
                <li class="">
                    <a href="{{ url('/') }}">Home</a>
                    <a href="{{ url('login') }}">Login</a>
                    <a href="{{ url('register') }}">Register</a>
                </li>
            </ul>
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>