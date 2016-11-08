@extends('layouts.app')

@section('title', 'Edit ' . $page->name . ' Page')

@section('content')
    <form action="{{ route('pages.update', $page) }}" method="post">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name">Page name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $page->name) }}">
            @if ($errors->has('name'))
                <p class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </p>
            @endif
        </div>

        <div class="form-group{{ $errors->has('menu') ? ' has-error' : '' }}">
            <label for="menu">Menu</label>
            <select class="form-control" name="menu" id="menu">
                @foreach($menus as $menu)
                    <option value="{{ $menu->id }}"{{ $menu->id == old('menu', $page->menu_id) ? ' selected' : '' }}>
                        {{ $menu->name }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('menu'))
                <p class="help-block">
                    <strong>{{ $errors->first('menu') }}</strong>
                </p>
            @endif
        </div>

        <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
            <label for="url">Page url</label>
            <input type="text" class="form-control" id="url" name="url" value="{{ old('url', $page->url) }}">
            @if ($errors->has('url'))
                <p class="help-block">
                    <strong>{{ $errors->first('url') }}</strong>
                </p>
            @endif
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('pages.show', $page) }}" class="btn btn-warning">Cancel</a>

    </form>
@endsection
