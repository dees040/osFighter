@extends('layouts.app')

@section('title', 'Gym')

@section('content')
    The place where you can train your strength. We you think you've been in the gym enough you can
    challenge other people and put some cash on the line.

    <div class="gym">
        <p>
            You have {{ user()->strength }} strength points.
        </p>

        @if(user()->mayView('training'))
            <form action="{{ route('gym.store') }}" method="post">
                {{ csrf_field() }}

                <button type="submit" class="btn btn-primary">Train</button>
            </form>
        @else
            <div class="alert alert-warning" role="alert">
                You need to wait <span class="game-countdown">{{ sec_difference(user()->training) }}</span> seconds.
            </div>
        @endif
    </div>
@endsection