@extends('layouts.app')

@section('title', 'Red Light District')

@section('content')
    <p>
        Hoes: {{ user()->hoes }}.
    </p>
    <p>
        <a href="{{ route('rld.cash') }}">Get cash from hoes</a> ({{ money(user()->getCashAmountFromHoes()) }}).
    </p>
    @if (user()->mayView('pimped'))
        <form action="{{ route('rld.store') }}" method="post">
            {{ csrf_field() }}

            <button type="submit" class="btn btn-primary">Pimp hoes</button>
        </form>
    @else
        <div class="alert alert-warning" role="alert">
            You need to wait <span class="game-countdown">{{ sec_difference(user()->pimped) }}</span> seconds to pimp
            hoes.
        </div>
    @endif
@endsection