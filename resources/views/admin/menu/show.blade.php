@extends('layouts.app')

@section('title', $menu->name . ' Menu')

@section('content')
    <div class="row">
        <div class="col-md-12">
            All the information you can find about the {{ $menu->name }} menu. To add a page to this menu you need to go
            to the <a href="{{ route('routes.index') }}">Routes</a> and select a route to update.
        </div>
        <div class="col-md-12">
            <table class="table table-responsive table-clearance">
                <tr>
                    <th>
                        Page name
                    </th>
                    <th>
                        Page URL
                    </th>
                    <th>
                        Actions
                    </th>
                </tr>
                @foreach($menu->routes as $route)
                    <tr>
                        <td>
                            {{ $route->title }}
                        </td>
                        <td>
                            <a href="{{ route($route->name) }}">{{ $route->url }}</a>
                        </td>
                        <td>
                            <a href="{{ route('routes.show', $route) }}">
                                <img src="{{ icon('eye--arrow') }}" alt="Show">
                            </a>
                            <a href="{{ route('routes.edit', $route) }}">
                                <img src="{{ icon('pencil') }}" alt="Edit">
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>
            <a href="{{ route('menus.edit', $menu) }}" class="btn btn-primary">Edit menu</a>
            <a href="{{ route('menus.destroy', $menu) }}" class="btn btn-warning"
               onclick="event.preventDefault();
                                document.getElementById('destroy-form').submit();">
                Destroy menu
            </a>
            <form id="destroy-form" action="{{ route('menus.destroy', $menu) }}" method="POST" style="display: none;">
                {{ method_field('DELETE') }}
                {{ csrf_field() }}
            </form>
            <a href="{{ route('menus.index') }}" class="btn btn-default">Back to Menus</a>
        </div>
    </div>
@endsection
