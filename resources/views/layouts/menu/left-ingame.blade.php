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
            @foreach(game()->leftMenus() as $menu)
                <ul class="nav navbar-nav">
                    <li class="disabled">
                        <a href="#">
                            {{ $menu->name }}
                        </a>
                    </li>
                    @foreach($menu->routes as $route)
                        <li class="">
                            <a href="{{ route($route->name) }}">{{ $route->title }}</a>
                        </li>
                    @endforeach
                    @if($loop->first)
                        <li>
                            <a href="{{ url('/logout') }}"
                               onclick="event.preventDefault();
                                document.getElementById('logout-form').submit();">
                                Logout
                            </a>

                            <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                                {{ csrf_field() }}
                            </form>
                        </li>
                    @endif
                </ul>
            @endforeach
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>