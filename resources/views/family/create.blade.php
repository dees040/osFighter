@extends('layouts.app')

@section('title', 'Create family')

@section('content')
    <form action="{{ route('families.store') }}" method="post">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label class="control-label" for="name">Name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            @if ($errors->has('name'))
                <p class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
                </p>
            @endif
        </div>

        <button type="submit" class="btn btn-success">Create</button>
        <a href="{{ route('families.index') }}" class="btn btn-warning">Cancel</a>
@endsection
