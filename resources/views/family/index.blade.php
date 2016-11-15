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
        </tr>
        @foreach($families as $family)
            <tr>
                <td>
                    {{ $family->name }}
                </td>
                <td>
                    {{ $family->power }}
                </td>
            </tr>
        @endforeach
    </table>
    {{ $families->links() }}
@endsection
