@extends('layouts.app')

@section('title', 'Cars')

@section('content')
    <table class="table table-responsive table-clearance">
        <tr>
            <th>
                Name
            </th>
            <th>
                Price
            </th>
            <th>
                Options
            </th>
        </tr>
        @foreach($cars as $car)
            <tr>
                <td>
                    {{ $car->name }}
                </td>
                <td>
                    {{ money($car->price) }}
                </td>
                <td>
                    <a href="{{ route('cars.show', $car) }}">
                        <img src="{{ icon('eye--arrow') }}" alt="Show">
                    </a>
                    <a href="{{ route('cars.edit', $car) }}">
                        <img src="{{ icon('pencil') }}" alt="Edit">
                    </a>
                </td>
            </tr>
        @endforeach
    </table>
    <a href="{{ route('cars.create') }}" class="btn btn-primary">Create new car</a>
@endsection
