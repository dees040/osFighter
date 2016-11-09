@extends('layouts.app')

@section('title', 'Crime')

@section('content')
    <form action="{{ route('crime.store') }}" method="post">
        {{ csrf_field() }}

        @if($errors->has('crime'))
            <div class="alert alert-danger" role="alert">
                {{ $errors->first('crime') }}
            </div>
        @endif

        <table class="table table-responsive table-clearance">
            @foreach($crimes as $crime)
                <tr>
                    <td rowspan="4" class="col-xs-1">
                        <input type="radio" name="crime" value="{{ $crime->id }}>">
                    </td>
                    <td rowspan="4" width="100" class="col-xs-2">
                        <img src="{{ $crime->image() }}" alt="" width="100%">
                    </td>
                    <td colspan="2" class="col-xs-9">
                        <strong>{{ $crime->title }}</strong>
                    </td>
                </tr>
                <tr>
                    <td>
                        <img src="{{ icon('chart') }}" alt="Chance">
                    </td>
                    <td>
                        <strong>{{ $crime->userChance() }}%</strong> chance of success.
                    </td>
                </tr>
                <tr>
                    <td>
                        <img src="{{ icon('money') }}" alt="Payout">
                    </td>
                    <td>
                        Payout is between
                        {{ money($crime->min_payout) }} and
                        {{ money($crime->max_payout) }}.
                    </td>
                </tr>
                <tr>
                    <td>
                        <img src="{{ icon('clock') }}" alt="Punishment ">
                    </td>
                    <td>
                        <strong>120 Seconds</strong> jail time if you get caught.
                    </td>
                </tr>
            @endforeach
        </table>

        <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
            {!! Recaptcha::render() !!}

            @if ($errors->has('g-recaptcha-response'))
                <p class="help-block">
                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                </p>
            @endif
        </div>
        <button type="submit" class="btn btn-primary">Do Crime</button>
    </form>
@endsection
