@extends('layouts.app')

@section('title', 'Routes')

@section('content')
    <div class="row">
        <div class="col-md-12">
            Change the pages to your wishes.
        </div>
        <div class="col-md-12">
            <table class="table table-responsive table-clearance">
                <tr>
                    <th>
                        Name
                    </th>
                    <th>
                        Menu
                    </th>
                    <th>
                        URL
                    </th>
                    <th>
                        Options
                    </th>
                </tr>
                @foreach($routes as $route)
                    <tr>
                        <td>
                            {{ $route->title }}
                        </td>
                        <td>
                            @if(! $route->rules->menuable)
                                <span class="text-warning"
                                      title="This means that the url may not be shown in the menus">Not assignable</span>
                            @elseif(is_null($route->menu))
                                Unassigned
                            @else
                                <a href="{{ route('menus.show', $route->menu) }}">
                                    {{ $route->menu->name }}
                                </a>
                            @endif
                        </td>
                        <td>
                            @if(! $route->rules->menuable)
                                {{ $route->url }}
                            @else
                                <a href="{{ route($route->name) }}">{{ $route->url }}</a>
                            @endif
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
        </div>
    </div>
@endsection
