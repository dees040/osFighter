@extends('layouts.app')

@section('title', $route->title . ' Route')

@section('content')
    <p>
        Page name: {{ $route->name }}
    </p>
    @if(! $route->rules->menuable)
        <p class="text-warning">
            This page can't be assigned to a menu item.
        </p>
    @elseif(! is_null($route->menu))
        <p>
            Belongs to <a href="{{ route('menus.show', $route->menu) }}">{{ $route->menu->name }}</a> menu.
        </p>
    @endif
    <p>
        Url to page is {{ url($route->url) }}.
    </p>
    <p>
        Groups that have access to this page:
        {{ game()->getAllParentsFromGroup($route->rules->group)->implode('name', ', ') }}.
    </p>
    <a href="{{ route('routes.edit', $route) }}" class="btn btn-primary">Edit page</a>
    <a href="{{ route('routes.index') }}" class="btn btn-default">Back to Pages</a>
@endsection
