@extends('layouts.app')

@section('title', 'Edit ' . $group->name . ' Group')

@section('content')
    <form action="{{ route('groups.update', $group) }}" method="post">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label class="control-label" for="name">Group name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $group->name) }}">
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
                    @if ($item->id != $group->id)
                        <option value="{{ $item->id }}"{{ $item->id == old('child', $group->child_id) ? ' selected' : '' }}>
                            {{ $item->name }}
                        </option>
                    @endif
                @endforeach
            </select>
            @if ($errors->has('child'))
                <p class="help-block">
                    <strong>{{ $errors->first('child') }}</strong>
                </p>
            @endif
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('groups.show', $group) }}" class="btn btn-warning">Cancel</a>
    </form>
@endsection
