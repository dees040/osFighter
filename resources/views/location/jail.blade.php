@extends('layouts.app')

@section('title', 'Jail')

@section('content')
    @if(count($users))
        <form action="{{ route('jail.free') }}" method="post">
            {{ csrf_field() }}

            <table class="table table-responsive table-bordered table-clearance">
                @foreach($users as $user)
                    <tr>
                        <td class="text-center">
                            <input type="radio" name="user" value="{{ $user->id }}">
                        </td>
                        <td class="text-center">
                            <img src="{{ icon('user') }}" alt="User">
                        </td>
                        <td class="">
                            {{ dynamic_route('users.show', $user, $user->username) }}
                        </td>
                        <td class="text-center">
                            <img src="{{ icon('medal-red') }}" alt="Rank">
                        </td>
                        <td class="">
                            {{ user()->rank()->name }}
                        </td>
                        <td class="text-center">
                            <img src="{{ icon('clock') }}" alt="Clock">
                        </td>
                        <td class="">
                            <span class="game-countdown">{{ sec_difference(user()->jail) }}</span>
                            seconds
                        </td>
                        <td class="text-center">
                            <img src="{{ icon('money') }}" alt="Price">
                        </td>
                        <td class="">
                            {{ game('currency_symbol') }}
                            <span class="game-countdown"
                                  data-extract="180">{{ sec_difference(user()->jail) * 180 }}</span>
                        </td>
                    </tr>
                @endforeach
            </table>

            <button type="submit" class="btn btn-primary">Buy free</button>
        </form>
    @else
        No users in the jail.
    @endif
@endsection
