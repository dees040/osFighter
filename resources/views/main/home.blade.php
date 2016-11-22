@extends('layouts.app')

@section('title', 'Me')

@section('content')
    <table class="table table-responsive table-clearance table-bordered">
        <tr>
            <td class="col-md-5">
                Username
            </td>
            <td>
                <img src="{{ icon('user') }}" alt="User">
            </td>
            <td class="col-md-7">
                {{ dynamic_route('users.show', currentUser(), user()->username) }}
            </td>
        </tr>
        <tr>
            <td class="col-md-5">
                Health
            </td>
            <td>
                <img src="{{ icon(user()->health == 0 ? 'heart-empty' : 'heart') }}" alt="Health">
            </td>
            <td class="col-md-7">
                {{ user()->health }}%
            </td>
        </tr>
        <tr>
            <td class="col-md-5">
                Power
            </td>
            <td>
                <img src="{{ icon('lightning') }}" alt="Power">
            </td>
            <td class="col-md-7">
                {{ user()->power }}
            </td>
        </tr>
        <tr>
            <td class="col-md-5">
                Cash
            </td>
            <td>
                <img src="{{ icon('money') }}" alt="Cash">
            </td>
            <td class="col-md-7">
                {{ money(user()->cash) }}
            </td>
        </tr>
        <tr>
            <td class="col-md-5">
                Bank
            </td>
            <td>
                <img src="{{ icon('bank') }}" alt="Bank">
            </td>
            <td class="col-md-7">
                {{ money(user()->bank) }}
            </td>
        </tr>
        <tr>
            <td class="col-md-5">
                Credits
            </td>
            <td>
                <img src="{{ icon('crown') }}" alt="Credits">
            </td>
            <td class="col-md-7">

            </td>
        </tr>
        <tr>
            <td class="col-md-5">
                Rank
            </td>
            <td>
                <img src="{{ icon('medal') }}" alt="Credits">
            </td>
            <td class="col-md-7">
                {{ user()->rank()->name }}
            </td>
        </tr>
        <tr>
            <td class="col-md-5">
                Rank progress
            </td>
            <td>
                <img src="{{ icon('crown') }}" alt="Credits">
            </td>
            <td class="col-md-7">
                {{ user()->rank_progress }}%
            </td>
        </tr>
        <tr>
            <td class="col-md-5">
                Location
            </td>
            <td>
                <img src="{{ icon('globe') }}" alt="Location">
            </td>
            <td class="col-md-7">
                @if(user()->location_id == 0)
                    None
                @else
                    {{ user()->location()->name }}
                @endif
            </td>
        </tr>
        <tr>
            <td class="col-md-5">
                Family
            </td>
            <td>
                <img src="{{ icon('users') }}" alt="User">
            </td>
            <td class="col-md-7">
                @if(user()->family_id == 0)
                    None
                @else
                    {!! dynamic_route('families.show', user()->family(), user()->family()->name) !!}
                @endif
            </td>
        </tr>
    </table>
@endsection
