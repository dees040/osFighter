@extends('layouts.app')

@section('title', 'Gym')

@section('content')
    <ul class="nav nav-tabs nav-tabs-content" role="tablist">
        <li role="presentation" class="active"><a href="#tab-training" aria-controls="configuration" role="tab"
                                                  data-toggle="tab">Training</a></li>
        <li role="presentation"><a href="#tab-game" aria-controls="locations" role="tab" data-toggle="tab">Game</a>
        </li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="tab-training">
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
        </div>
        <div role="tabpanel" class="tab-pane" id="tab-game">
            <p>
                Do a game of arm wrestling against an opponent.
            </p>

        </div>
    </div>
@endsection