@extends('layouts.app')

@section('title', 'Create Group')

@section('content')
    <form action="{{ route('groups.store') }}" method="post">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label class="control-label" for="name">Group name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            @if ($errors->has('name'))
                <p class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </p>
            @endif
        </div>

        <div class="form-group{{ $errors->has('child') ? ' has-error' : '' }}">
            <label class="control-label" for="child">Child group</label>
            <select class="form-control" name="child" id="child">
                <option value="0" selected disabled>None</option>
                @foreach($groups as $item)
                    <option value="{{ $item->id }}"{{ $item->id == old('child') ? ' selected' : '' }}>
                        {{ $item->name }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('child'))
                <p class="help-block">
                    <strong>{{ $errors->first('child') }}</strong>
                </p>
            @endif
            <p class="help-block">
                A child group is the sub group of a group. A group has the same permissions as it's child, but can have
                more permissions than it's child.
            </p>
        </div>

        <button type="submit" class="btn btn-success">Create</button>
        <a href="{{ route('groups.index') }}" class="btn btn-warning">Cancel</a>
@endsection