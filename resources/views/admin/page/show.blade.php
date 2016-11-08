@extends('layouts.app')

@section('title', $page->name . ' Page')

@section('content')
    <p>
        Page name: {{ $page->name }}
    </p>
    <p>
        Belongs to <a href="{{ route('menus.show', $page->menu) }}">{{ $page->menu->name }}</a> menu.
    </p>
    <p>
        Url to page is <a href="{{ route($page->route_name) }}">{{ $page->url }}</a>.
    </p>
    <a href="{{ route('pages.edit', $page) }}" class="btn btn-primary">Edit page</a>
    <a href="{{ route('pages.index') }}" class="btn btn-default">Back to Pages</a>
@endsection
