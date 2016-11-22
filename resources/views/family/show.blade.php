@extends('layouts.app')

@section('title', $family->name)

@section('content')
    <table class="table table-bordered table-responsive table-clearance">
        <tr class="top">
            <td width="28%"><strong>Creator:</strong></td>
            <td width="4%"><img src="{{ icon('user-business') }}" title="Online"></td>
            <td width="68%">
                {!! dynamic_route('users.show', $family->creator, $family->creator->username) !!}
            </td>
        </tr>
        <tr>
            <td><strong>Power:</strong></td>
            <td align="center"><img src="{{ icon('lightning') }}"></td>
            <td>
                {{ $family->power }}
            </td>
        </tr>
        <tr>
            <td><strong>Money:</strong></td>
            <td align="center"><img src="{{ icon('money') }}"></td>
            <td>
                {{ money($family->power) }}
            </td>
        </tr>
    </table>
@endsection
