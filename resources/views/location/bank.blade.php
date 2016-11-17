@extends('layouts.app')

@section('title', 'Bank')

@section('content')
    <form action="{{ route('bank.store') }}" method="post">
        {{ csrf_field() }}

        <p>
            Your cash: {{ money(user()->cash) }}
        </p>

        <p>
            Your bank: {{ money(user()->bank) }}
        </p>

        <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
            <label class="control-label" for="amount">Amount to withdraw or deposit.</label>
            <input type="number" class="form-control" id="amount" name="amount" value="{{ old('amount', 1) }}">
            @if ($errors->has('amount'))
                <p class="help-block">
                    <strong>{{ $errors->first('amount') }}</strong>
                </p>
            @endif
        </div>

        <button type="submit" class="btn btn-primary" name="option" value="withdraw">Withdraw</button>
        <button type="submit" class="btn btn-primary" name="option" value="deposit">Deposit</button>
    </form>
@endsection