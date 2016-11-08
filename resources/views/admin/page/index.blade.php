@extends('layouts.app')

@section('title', 'Pages')

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
                @foreach($pages as $page)
                    <tr>
                        <td>
                            {{ $page->name }}
                        </td>
                        <td>
                            <a href="{{ route('menus.show', $page->menu) }}">
                                {{ $page->menu->name }}
                            </a>
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
        </div>
    </div>
@endsection
