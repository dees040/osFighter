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
                            @if(! $page->menuable)
                                <span class="text-warning"
                                      title="This means that the url may not be shown in the menus">Not assignable</span>
                            @elseif(is_null($page->menu))
                                Unassigned
                            @else
                                <a href="{{ route('menus.show', $page->menu) }}">
                                    {{ $page->menu->name }}
                                </a>
                            @endif
                        </td>
                        <td>
                            @if(! $page->menuable)
                                {{ $page->url }}
                            @else
                                <a href="{{ route($page->route_name) }}">{{ $page->url }}</a>
                            @endif
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
