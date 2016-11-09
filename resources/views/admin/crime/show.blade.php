@extends('layouts.app')

@section('title', 'Crime')

@section('content')
    <p>
        Crime name: {{ $crime->title }}
    </p>
    <p>
        Chance: {{ $crime->chance }}
    </p>
    <p>
        Max change: %{{ $crime->max_chance }}
    </p>
    <p>
        Minimum payout: {{ $crime->min_payout }}
    </p><p>
        Maximum payout: {{ $crime->max_payout }}
    </p>

    <a href="{{ route('crimes.edit', $crime) }}" class="btn btn-primary">Edit Crime</a>

    <a href="{{ route('crimes.destroy', $crime) }}" class="btn btn-warning"
       onclick="event.preventDefault();
                                document.getElementById('destroy-form').submit();">
        Destroy Crime
    </a>
    <form id="destroy-form" action="{{ route('crimes.destroy', $crime) }}" method="POST" style="display: none;">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
    </form>

    <a href="{{ route('crimes.index') }}" class="btn btn-default">Back to Crimes</a>
@endsection
