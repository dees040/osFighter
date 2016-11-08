@extends('layouts.app')

@section('title', $group->name . ' Group')

@section('content')
    <p>
        Group name: {{ $group->name }}
    </p>
    @if(! is_null($group->parent))
        <p>
            Parent: <a href="{{ route('groups.show', $group->parent) }}">{{ $group->parent->name }}</a>
        </p>
    @endif
    @if(! is_null($group->child))
        <p>
            Parent: <a href="{{ route('groups.show', $group->child) }}">{{ $group->child->name }}</a>
        </p>
    @endif
    <a href="{{ route('groups.edit', $group) }}" class="btn btn-primary">Edit group</a>

    <a href="{{ route('groups.destroy', $group) }}" class="btn btn-warning"
       onclick="event.preventDefault();
                                document.getElementById('destroy-form').submit();">
        Destroy group
    </a>
    <form id="destroy-form" action="{{ route('groups.destroy', $group) }}" method="POST" style="display: none;">
        {{ method_field('DELETE') }}
        {{ csrf_field() }}
    </form>

    <a href="{{ route('groups.index') }}" class="btn btn-default">Back to Groups</a>
@endsection
