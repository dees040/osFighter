@extends('layouts.app')

@section('title', $car->name . ' (CAR)')

@section('content')
    <p>
        Car name: {{ $car->name }}
    </p>
    <p>
        Price: {{ money($car->price) }}
    </p>
    <p>
        <img src="{{ $car->image() }}" alt="Car" width="300px">
    </p>

    <a href="{{ route('cars.edit', $car) }}" class="btn btn-primary">Edit car</a>

    <a href="{{ route('cars.destroy', $car) }}" class="btn btn-warning"
       onclick="event.preventDefault();
                                document.getElementById('destroy-form').submit();">
        Destroy car
    </a>
    <form id="destroy-form" action="{{ route('cars.destroy', $car) }}" method="POST" style="display: none;">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
    </form>

    <a href="{{ route('cars.index') }}" class="btn btn-default">Back to Cars</a>
@endsection
