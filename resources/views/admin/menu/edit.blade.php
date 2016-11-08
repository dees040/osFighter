@extends('layouts.app')

@section('title', 'Edit ' . $menu->name . ' Menu')

@section('content')
    <form action="{{ route('menus.update', $menu) }}" method="post">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label for="name">Menu name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $menu->name) }}">
            @if ($errors->has('name'))
                <p class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </p>
            @endif
        </div>

        <div class="form-group{{ $errors->has('position') ? ' has-error' : '' }}">
            <label for="position">Menu position</label>
            <select class="form-control" name="position" id="position">
                <option value="1"{{ $menu->position == 1 ? ' selected' : '' }}>Left menus</option>
                <option value="2"{{ $menu->position == 2 ? ' selected' : '' }}>Right menus</option>
            </select>
            @if ($errors->has('position'))
                <p class="help-block">
                    <strong>{{ $errors->first('position') }}</strong>
                </p>
            @endif
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('menus.show', $menu) }}" class="btn btn-warning">Cancel</a>
    </form>
@endsection
