@extends('layouts.app')

@section('title', $item->name . ' (SHOP ITEM)')

@section('content')
    <p>
        Item name: {{ $item->name }}
    </p>
    <p>
        Price: {{ money($item->price) }}
    </p>
    <p>
        Power: {{ $item->power }}
    </p>
    <p>
        Minimum gym points: {{ $item->min_strength_points }}
    </p>
    <p>
        Maximum amount: {{ $item->max_amount }}
    </p>
    <p>
        <img src="{{ $item->image() }}" alt="Car" width="300px">
    </p>

    <a href="{{ route('shop.edit', $item) }}" class="btn btn-primary">Edit shop item</a>

    <a href="{{ route('shop.destroy', $item) }}" class="btn btn-warning"
       onclick="event.preventDefault();
                                document.getElementById('destroy-form').submit();">
        Destroy shop item
    </a>
    <form id="destroy-form" action="{{ route('shop.destroy', $item) }}" method="POST" style="display: none;">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
    </form>

    <a href="{{ route('shop.index') }}" class="btn btn-default">Back to shop items</a>
@endsection
