@extends('layouts.app')

@section('title', 'Shop')

@section('content')
    @if($errors->count())
        <div class="alert alert-danger" role="alert">
            @foreach($errors->all() as $error)
                {{ $error }}<br/>
            @endforeach
        </div>
    @endif

    <table class="table table-bordered table-clearance">
        @foreach($items as $item)
            <tr>
                <td colspan="3">
                    <strong>{{ $item->name }}</strong>
                </td>
            </tr>
            <tr>
                <td rowspan="6" width="110px">
                    <img src="{{ $item->image() }}" alt="Item">
                </td>
            </tr>
            <tr>
                <td width="18px">
                    <img src="{{ icon('money') }}">
                </td>
                <td>
                    <strong>{{ money($item->price) }}</strong> for each <strong>{{ $item->name }}</strong>.
                </td>
            </tr>
            <tr>
                <td width="18px">
                    <img src="{{ icon('lightning') }}">
                </td>
                <td>
                    Gives <strong>{{ $item->power }}</strong> power for each <strong>{{ $item->name }}</strong>.
                </td>
            </tr>
            <tr>
                <td width="18px">
                    <img src="{{ icon('wand') }}">
                </td>
                <td>
                    <form action="{{ route('location.shop') }}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="item" value="{{ $item->id }}">

                        <div class="input-group">
                            <input type="number" name="amount" class="form-control" min="0"
                                   placeholder="Amount to buy"
                                   value="{{ old('item') == $item->id ? old('amount') : '' }}">
                            <span class="input-group-btn">
                                    <button class="btn btn-primary" type="submit">Buy</button>
                                </span>
                        </div>
                    </form>

                </td>
            </tr>
            <tr>
                <td width="18px">
                    <img src="{{ icon('chart') }}">
                </td>
                <td>
                    @if(is_null($userItem = user()->getShopItem($item)))
                        You don't have a <strong>{{ $item->name }}</strong> yet.
                    @else
                        You have {{ $userItem->pivot->amount }} out of the {{ $item->max_amount ?: '&#x221e;' }}
                        from this item.
                    @endif
                </td>
            </tr>
            <tr>
                <td width="18px"><img src="{{ icon('information') }}"></td>
                <td>
                    @if($item->min_strength_points)
                        You need to have a minimum of <strong>{{ $item->min_strength_points }} gym points</strong> to
                        buy
                        a <strong>{{ $item->name }}</strong>.
                    @else
                        This item doesn't have a minimum of strength points required.
                    @endif
                </td>
            </tr>
        @endforeach
    </table>
@endsection