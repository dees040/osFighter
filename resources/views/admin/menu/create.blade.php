@extends('layouts.app')

@section('title', 'Create Menu')

@section('content')
    <form action="{{ route('menus.store') }}" method="post">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label class="control-label" for="name">Menu name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            @if ($errors->has('name'))
                <p class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </p>
            @endif
        </div>

        <div class="form-group{{ $errors->has('position') ? ' has-error' : '' }}">
            <label class="control-label" for="position">Menu position</label>
            <select class="form-control" name="position" id="position">
                <option value="1"{{ old('position') == 1 ? ' selected' : '' }}>Left menus</option>
                <option value="2"{{ old('position') == 2 ? ' selected' : '' }}>Right menus</option>
            </select>
            @if ($errors->has('position'))
                <p class="help-block">
                    <strong>{{ $errors->first('position') }}</strong>
                </p>
            @endif
        </div>

        <button type="submit" class="btn btn-success">Create</button>
    </form>
@endsection
