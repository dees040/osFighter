<nav class="navbar navbar-default navbar-fixed-side">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse"
                    data-target="#main-right-navbar" aria-expanded="false">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <span class="navbar-brand visible-xs">Menu</span>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="main-right-navbar">
            @foreach(game()->rightMenus() as $menu)
                <ul class="nav navbar-nav">
                    <li class="disabled">
                        <a href="#">
                            {{ $menu->name }}
                        </a>
                    </li>
                    @foreach($menu->pages as $page)
                        <li class="">
                            <a href="{{ route($page->route_name) }}">{{ $page->name }}</a>
                        </li>
                    @endforeach
                </ul>
            @endforeach
            @if(Auth::check() && game()->isInAdminGroup())
                <ul class="nav navbar-nav">
                    <li class="disabled"><a href="#">Admin</a></li>
                    <li><a href="{{ route('users.index') }}">Users</a></li>
                    <li><a href="{{ route('menus.index') }}">Menus</a></li>
                    <li><a href="{{ route('pages.index') }}">Pages</a></li>
                    <li><a href="{{ route('groups.index') }}">Groups</a></li>
                    <li><a href="{{ route('config.index') }}">Configuration</a></li>
                </ul>
            @endif
        </div><!-- /.navbar-collapse -->
    </div><!-- /.container-fluid -->
</nav>