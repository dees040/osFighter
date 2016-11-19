@extends('layouts.app')

@section('title', 'Airport')

@section('content')
    @if(user()->mayView('flying'))
        <p>
            You are currently on the airport in <strong>{{ user()->location()->name }}</strong>. A ticket to another
            location will cost you {{ money(300) }}. While traveling you're not permitted to do other actions then
            sitting in the plane for 2 minutes.
        </p>
        @if($errors->has('airport'))
            <div class="alert alert-danger" role="alert">
                {{ $errors->first('airport') }}
            </div>
        @endif
        <form action="{{ route('airport.store') }}" method="post">
            {{ csrf_field() }}
            <table class="table table-responsive">
                <tr>
                    <th></th>
                    <th>
                        Destination
                    </th>
                    <th>
                        Price
                    </th>
                    <th>
                        Population
                    </th>
                </tr>
                @foreach($locations as $location)
                    <tr>
                        <td>
                            @if ($location->id != user()->location()->id)
                                <input type="radio" name="airport" value="{{ $location->id }}">
                            @endif
                        </td>
                        <td>
                            {{ $location->name }}
                        </td>
                        <td>
                            {{ money(300) }}
                        </td>
                        <td>
                            {{ count($location->population) }}
                        </td>
                    </tr>
                @endforeach
            </table>

            <button type="submit" class="btn btn-primary">Fly</button>
        </form>
    @else
        <div class="alert alert-warning" role="alert">
            You are flying for another <span class="game-countdown">{{ sec_difference(user()->flying) }}</span> seconds.
        </div>
    @endif
@endsection