@extends('layouts.app')

@section('title', 'Garage')

@section('content')
    <ul class="nav nav-tabs nav-tabs-content" role="tablist">
        <li role="presentation" class="active"><a href="#tab-garage" aria-controls="ranks" role="tab" data-toggle="tab">Garage</a>
        </li>
        <li role="presentation"><a href="#tab-cars" aria-controls="configuration" role="tab" data-toggle="tab">Steal
                cars</a></li>
    </ul>
    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="tab-garage">
            <form action="{{ route('garage.store') }}" method="post">
                {{ csrf_field() }}
                <table class="table table-responsive table-clearance">
                    @foreach($cars as $car)
                        <tr>
                            <td rowspan="4" class="col-xs-1 text-center">
                                <input type="radio" name="car" value="{{ $car->pivot->id }}>">
                            </td>
                            <td rowspan="4" width="100" class="col-xs-4">
                                <img src="{{ $car->image() }}" alt="" width="100%">
                            </td>
                            <td colspan="2" class="col-xs-9">
                                <strong>{{ $car->name }}</strong>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="{{ icon('money') }}" alt="Chance">
                            </td>
                            <td>
                                {{ money($car->price / 100 * (100 - $car->pivot->damage)) }} in value
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="{{ icon('heart') }}" alt="Payout">
                            </td>
                            <td>
                                {{ $car->pivot->damage }}% damage
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <img src="{{ icon('toolbox') }}" alt="Punishment ">
                            </td>
                            <td>
                                {{ money($car->price / 100 * $car->pivot->damage) }} repair costs
                            </td>
                        </tr>
                    @endforeach
                </table>
                <div>{{ $cars->links() }}</div>
                <button type="submit" class="btn btn-primary" name="action" value="sell">Sell</button>
                <button type="submit" class="btn btn-primary" name="action" value="repair">Repair</button>
            </form>
        </div>
        <div role="tabpanel" class="tab-pane" id="tab-cars">
            @if(user()->mayView('car'))
                <form action="{{ route('car.store') }}" method="post">
                    {{ csrf_field() }}

                    @if (game()->isUsingCaptcha())
                        <div class="form-group{{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                            {!! Recaptcha::render() !!}

                            @if ($errors->has('g-recaptcha-response'))
                                <p class="help-block">
                                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                </p>
                            @endif
                        </div>
                    @endif

                    <button type="submit" class="btn btn-primary">Steal a car</button>
                </form>
            @else
                <div class="alert alert-warning alert-dismissible" role="alert">
                    <strong>Warning!</strong> You're still cooling down from the police for
                    <span class="game-countdown">{{ sec_difference(user()->car) }}</span> seconds.
                </div>
            @endif
        </div>
    </div>
@endsection