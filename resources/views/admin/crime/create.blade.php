@extends('layouts.app')

@section('title', 'Create Crime')

@section('content')
    <form action="{{ route('crimes.store') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
            <label class="control-label" for="title">Crime title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}">
            @if ($errors->has('title'))
                <p class="help-block">
                    <strong>{{ $errors->first('title') }}</strong>
                </p>
            @endif
        </div>

        <div class="form-group{{ $errors->has('image') ? ' has-error' : '' }}">
            <label class="control-label" for="image">Image</label>
            <input type="file" id="image" name="image" value="{{ old('image') }}">
            @if ($errors->has('image'))
                <p class="help-block">
                    <strong>{{ $errors->first('image') }}</strong>
                </p>
            @endif
        </div>

        <div class="form-group{{ $errors->has('chance') ? ' has-error' : '' }}">
            <label class="control-label" for="chance">Chance</label>
            <input type="number" class="form-control" id="chance" name="chance" value="{{ old('chance', 90) }}" min="1">
            @if ($errors->has('chance'))
                <p class="help-block">
                    <strong>{{ $errors->first('chance') }}</strong>
                </p>
            @endif
            <p class="help-block">
                The crime chance percentage of the user is been calculated like: chance = user_experience / crime_chance
            </p>
        </div>

        <div class="form-group{{ $errors->has('max_chance') ? ' has-error' : '' }}">
            <label class="control-label" for="max_chance">Max chance</label>
            <input type="number" class="form-control" id="max_chance" name="max_chance" value="{{ old('max_chance', 100) }}" min="1"
                   max="100">
            @if ($errors->has('max_chance'))
                <p class="help-block">
                    <strong>{{ $errors->first('max_chance') }}</strong>
                </p>
            @endif
            <p class="help-block">The maximum percentage a user can get for this crime.</p>
        </div>

        <div class="form-group{{ $errors->has('min_payout') ? ' has-error' : '' }}">
            <label class="control-label" for="min_payout">Min payout</label>
            <input type="number" class="form-control" id="min_payout" name="min_payout" value="{{ old('min_payout') }}"
                   min="1">
            @if ($errors->has('min_payout'))
                <p class="help-block">
                    <strong>{{ $errors->first('min_payout') }}</strong>
                </p>
            @endif
            <p class="help-block">
                The minimum payout a user can get for this crime.
            </p>
        </div>

        <div class="form-group{{ $errors->has('max_payout') ? ' has-error' : '' }}">
            <label class="control-label" for="max_payout">Max payout</label>
            <input type="number" class="form-control" id="max_payout" name="max_payout" value="{{ old('max_payout') }}"
                   min="1">
            @if ($errors->has('max_payout'))
                <p class="help-block">
                    <strong>{{ $errors->first('max_payout') }}</strong>
                </p>
            @endif
            <p class="help-block">
                The maximum payout a user can get for this crime. This can't be lower then the minimum
            </p>
        </div>

        <button type="submit" class="btn btn-success">Create</button>
        <a href="{{ route('crimes.index') }}" class="btn btn-warning">Cancel</a>
@endsection
