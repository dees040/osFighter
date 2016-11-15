@extends('layouts.app')

@section('title', $user->username)

@section('content')
    <p>
        Username: {{ $user->username }}
    </p>
    <p>
        Cash: {{ money($user->info->cash) }}
    </p>
    <p>
        Bank: {{ money($user->info->bank) }}
    </p>
    <p>
        Power: {{ $user->info->power }}
    </p>
    <p>
        Group: {{ $user->group->name }}
    </p>
    <p>
        Rank: {{ $user->info->rank->name }}
    </p>
    <a href="{{ route('users.edit', $user) }}" class="btn btn-primary">Edit user</a>

    <a href="{{ route('users.destroy', $user) }}" class="btn btn-warning"
       onclick="event.preventDefault();
                                document.getElementById('destroy-form').submit();">
        Destroy user
    </a>
    <form id="destroy-form" action="{{ route('users.destroy', $user) }}" method="POST" style="display: none;">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
    </form>

    <a href="{{ route('users.index') }}" class="btn btn-default">Back to Users</a>
@endsection
