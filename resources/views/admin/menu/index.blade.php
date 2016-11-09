@extends('layouts.app')

@section('title', 'Menus')

@section('content')
    <div class="row">
        <div class="col-md-12">
            Change the menu's to your wishes.
        </div>
        <div class="col-md-12">
            <table class="table table-responsive table-clearance">
                <tr>
                    <th>
                        Name
                    </th>
                    <th>
                        Pages
                    </th>
                    <th>
                        Position
                    </th>
                    <th>
                        Options
                    </th>
                </tr>
                @foreach($menus as $menu)
                    <tr>
                        <td>
                            {{ $menu->name }}
                        </td>
                        <td>
                            {{ count($menu->pages) }}
                        </td>
                        <td>
                            @if($menu->position == 1)
                                Left menus
                            @else
                                Right menus
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('menus.show', $menu) }}">
                                <img src="{{ icon('eye--arrow') }}" alt="Show">
                            </a>
                            <a href="{{ route('menus.edit', $menu) }}">
                                <img src="{{ icon('pencil') }}" alt="Edit">
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>
            <a href="{{ route('menus.create') }}" class="btn btn-primary">Create new menu</a>
        </div>
    </div>
@endsection
