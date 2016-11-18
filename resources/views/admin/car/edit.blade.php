@extends('layouts.app')

@section('title', 'Edit car')

@section('content')
    <form action="{{ route('cars.update', $car) }}" method="post" enctype="multipart/form-data">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
            <label class="control-label" for="name">Car name</label>
            <input type="text" class="form-control" id="name" name="name" value="{{ old('name', $car->name) }}">
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
            <input type="number" class="form-control" id="price" name="price" value="{{ old('price', $car->price) }}"
                   min="1">
            @if ($errors->has('price'))
                <p class="help-block">
                    <strong>{{ $errors->first('price') }}</strong>
                </p>
            @endif
            <p class="help-block">
                The price of the car. When a user steal a car it might have some damage. The repair cost will be
                calculated by the value of the car (price).
            </p>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('cars.show', $car) }}" class="btn btn-warning">Cancel</a>
@endsection
