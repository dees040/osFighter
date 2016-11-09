@extends('layouts.app')

@section('title', 'Edit ' . $page->name . ' Page')

@section('content')
    <form action="{{ route('pages.update', $page) }}" method="post">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label class="control-label" for="name">Page name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $page->name) }}">
            @if ($errors->has('name'))
                <p class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </p>
            @endif
        </div>

        <div class="form-group{{ $errors->has('menu') ? ' has-error' : '' }}">
            <label class="control-label" for="menu">Menu</label>
            <select class="form-control" name="menu" id="menu">
                <option value="0" selected disabled>Select a menu</option>
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
            <label class="control-label" for="url">Page url</label>
            <input type="text" class="form-control" id="url" name="url" value="{{ old('url', $page->url) }}">
            @if ($errors->has('url'))
                <p class="help-block">
                    <strong>{{ $errors->first('url') }}</strong>
                </p>
            @endif
        </div>

        <div class="form-group{{ $errors->has('group') ? ' has-error' : '' }}">
            <label class="control-label" for="group">Child group</label>
            <select class="form-control" name="group" id="group">
                <option value="0" selected disabled>Select a group</option>
                @foreach($groups as $item)
                    <option value="{{ $item->id }}"{{ $item->id == old('group', $page->group_id) ? ' selected' : '' }}>
                        {{ $item->name }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('group'))
                <p class="help-block">
                    <strong>{{ $errors->first('group') }}</strong>
                </p>
            @endif
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('pages.show', $page) }}" class="btn btn-warning">Cancel</a>

    </form>
@endsection
