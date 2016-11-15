@extends('layouts.app')

@section('title', 'Users')

@section('content')
    <div class="row">
        <div class="col-md-12">
            The users.
        </div>
        <div class="col-md-12">
            {{ $users->links() }}
            <table class="table table-responsive table-clearance">
                <tr>
                    <th>
                        User
                    </th>
                    <th>
                        Total money
                    </th>
                    <th>
                        Power
                    </th>
                    <th>
                        Actions
                    </th>
                </tr>
                @foreach($users as $user)
                    <tr>
                        <td>
                            {{ $user->username }}
                        </td>
                        <td>
                            {{ money($user->info->cash + $user->info->bank) }}
                        </td>
                        <td>
                            {{ $user->info->power }}
                        </td>
                        <td>
                            <a href="{{ route('users.show', $user) }}">
                                <img src="{{ icon('eye--arrow') }}" alt="Show">
                            </a>
                            <a href="{{ route('users.edit', $user) }}">
                                <img src="{{ icon('pencil') }}" alt="Edit">
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>
            {{ $users->links() }}
            <a href="{{ route('users.create') }}" class="btn btn-success">Create User</a>
        </div>
    </div>
@endsection
