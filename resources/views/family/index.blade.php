@extends('layouts.app')

@section('title', 'Families')

@section('content')
    <table class="table table-responsive table-clearance">
        <tr>
            <th>
                Name
            </th>
            <th>
                Power
            </th>
            <th>
                Members
            </th>
        </tr>
        @foreach($families as $family)
            <tr>
                <td>
                    {{ dynamic_route('families.show', $family, $family->name) }}
                </td>
                <td>
                    {{ $family->power }}
                </td>
                <td>
                    {{ count($family->members) }}
                </td>
            </tr>
        @endforeach
    </table>
    {{ $families->links() }}

    @if(! user()->isInFamily())
        <a href="{{ route('families.create') }}" class="btn btn-primary">Create a family</a>
    @endif
@endsection
