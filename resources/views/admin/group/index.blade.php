@extends('layouts.app')

@section('title', 'Groups')

@section('content')
    <div class="row">
        <div class="col-md-12">
            All the information about your groups.
        </div>
        <div class="col-md-12">
            <table class="table table-responsive table-clearance">
                <tr>
                    <th>
                        Group name
                    </th>
                    <th>
                        Child
                    </th>
                    <th>
                        Group members
                    </th>
                    <th>
                        Actions
                    </th>
                </tr>
                @foreach($groups as $group)
                    <tr>
                        <td>
                            {{ $group->name }}
                        </td>
                        <td>
                            @if(!is_null($group->child))
                                {{ $group->child->name }}
                            @endif
                        </td>
                        <td>
                            {{ count($group->users) }}
                        </td>
                        <td>
                            <a href="{{ route('groups.show', $group) }}">
                                <img src="{{ icon('eye--arrow') }}" alt="Show">
                            </a>
                            <a href="{{ route('groups.edit', $group) }}">
                                <img src="{{ icon('pencil') }}" alt="Edit">
                            </a>
                        </td>
                    </tr>
                @endforeach
            </table>
            <a href="{{ route('groups.create') }}" class="btn btn-success">Create Group</a>
        </div>
    </div>
@endsection
