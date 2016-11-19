@extends('layouts.app')

@section('title', 'Create shop item')

@section('content')
    <form action="{{ route('shop.store') }}" method="post" enctype="multipart/form-data">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label class="control-label" for="name">Item name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
            @if ($errors->has('name'))
                <p class="help-block">
                    <strong>{{ $errors->first('name') }}</strong>
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

        <div class="form-group{{ $errors->has('price') ? ' has-error' : '' }}">
            <label class="control-label" for="price">Price</label>
            <input type="number" class="form-control" id="price" name="price" value="{{ old('price') }}" min="1">
            @if ($errors->has('price'))
                <p class="help-block">
                    <strong>{{ $errors->first('price') }}</strong>
                </p>
            @endif
        </div>

        <div class="form-group{{ $errors->has('power') ? ' has-error' : '' }}">
            <label class="control-label" for="power">Power</label>
            <input type="number" class="form-control" id="power" name="power" value="{{ old('power') }}" min="1">
            @if ($errors->has('power'))
                <p class="help-block">
                    <strong>{{ $errors->first('power') }}</strong>
                </p>
            @endif
        </div>

        <div class="form-group{{ $errors->has('min_strength_points') ? ' has-error' : '' }}">
            <label class="control-label" for="min_strength_points">Minimum gym points</label>
            <input type="number" class="form-control" id="min_strength_points" name="min_strength_points"
                   value="{{ old('min_strength_points') }}" min="0">
            @if ($errors->has('min_strength_points'))
                <p class="help-block">
                    <strong>{{ $errors->first('min_strength_points') }}</strong>
                </p>
            @endif
            <p class="help-block">
                The minimum gym points the user requires to have to buy this item from the shop. Leave this field to 0
                if you wish to not have a minimum.
            </p>
        </div>

        <div class="form-group{{ $errors->has('max_amount') ? ' has-error' : '' }}">
            <label class="control-label" for="max_amount">Maximum amount</label>
            <input type="number" class="form-control" id="max_amount" name="max_amount"
                   value="{{ old('max_amount') }}" min="0">
            @if ($errors->has('max_amount'))
                <p class="help-block">
                    <strong>{{ $errors->first('max_amount') }}</strong>
                </p>
            @endif
            <p class="help-block">
                The maximum amount the user may have of this item from the shop. Leave this field to 0 if you wish to
                not have a maximum.
            </p>
        </div>

        <button type="submit" class="btn btn-success">Create</button>
        <a href="{{ route('shop.index') }}" class="btn btn-warning">Cancel</a>
@endsection
