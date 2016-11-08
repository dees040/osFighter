@extends('layouts.app')

@section('title', $menu->name . ' Menu')

@section('content')
    <div class="row">
        <div class="col-md-12">
            All the information you can find about the {{ $menu->name }} menu.
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
                @foreach($menu->pages as $page)
                    <tr>
                        <td>
                            {{ $page->name }}
                        </td>
                        <td>
                            <a href="{{ route($page->route_name) }}">{{ $page->url }}</a>
                        </td>
                        <td>
                            <a href="{{ route('pages.show', $page) }}">
                                <img src="{{ icon('eye--arrow') }}" alt="Show">
                            </a>
                            <a href="{{ route('pages.edit', $page) }}">
                                <img src="{{ icon('pencil') }}" alt="Edit">
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>
            <a href="{{ route('menus.edit', $menu) }}" class="btn btn-primary">Edit menu</a>
            <a href="{{ route('menus.index') }}" class="btn btn-default">Back to Menus</a>
        </div>
    </div>
@endsection
