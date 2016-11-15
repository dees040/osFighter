@extends('layouts.app')

@section('title', 'Create User')

@section('content')
    <form action="{{ route('users.store') }}" method="post">
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
            <label class="control-label" for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" value="{{ old('username') }}">
            @if ($errors->has('username'))
                <p class="help-block">
                    <strong>{{ $errors->first('username') }}</strong>
                </p>
            @endif
        </div>

        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
            <label class="control-label" for="email">Email</label>
            <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}">
            @if ($errors->has('email'))
                <p class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </p>
            @endif
        </div>

        <div class="form-group{{ $errors->has('cash') ? ' has-error' : '' }}">
            <label class="control-label" for="cash">Cash</label>
            <div class="input-group">
                <span class="input-group-addon">{{ game()->currency_symbol }}</span>
                <input type="number" class="form-control" id="cash" name="cash" value="{{ old('cash', 0) }}" min="0">
            </div>
            @if ($errors->has('cash'))
                <p class="help-block">
                    <strong>{{ $errors->first('cash') }}</strong>
                </p>
            @endif
            <p class="help-block">
                The amount of cash the user will start with.
            </p>
        </div>

        <div class="form-group{{ $errors->has('bank') ? ' has-error' : '' }}">
            <label class="control-label" for="cash">Bank</label>
            <div class="input-group">
                <span class="input-group-addon">{{ game()->currency_symbol }}</span>
                <input type="number" class="form-control" id="bank" name="bank" value="{{ old('bank', 0) }}" min="0">
            </div>
            @if ($errors->has('bank'))
                <p class="help-block">
                    <strong>{{ $errors->first('bank') }}</strong>
                </p>
            @endif
            <p class="help-block">
                The balance of the user it's bank account.
            </p>
        </div>

        <div class="form-group{{ $errors->has('power') ? ' has-error' : '' }}">
            <label class="control-label" for="power">Power</label>
            <input type="number" class="form-control" id="power" name="power" value="{{ old('power', 0) }}" min="0">
            @if ($errors->has('power'))
                <p class="help-block">
                    <strong>{{ $errors->first('power') }}</strong>
                </p>
            @endif
            <p class="help-block">
                The amount of power the user will start with.
            </p>
        </div>

        <div class="form-group{{ $errors->has('rank_id') ? ' has-error' : '' }}">
            <label class="control-label" for="rank_id">Rank</label>
            <select class="form-control" name="rank_id" id="rank_id">
                <option value="0" selected disabled>Select a rank</option>
                @foreach($ranks as $item)
                    <option value="{{ $item->id }}"{{ $item->id == old('rank_id', $ranks->first()->id) ? ' selected' : '' }}>
                        {{ $item->name }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('rank_id'))
                <p class="help-block">
                    <strong>{{ $errors->first('rank_id') }}</strong>
                </p>
            @endif
        </div>

        <div class="form-group{{ $errors->has('group_id') ? ' has-error' : '' }}">
            <label class="control-label" for="group_id">Group</label>
            <select class="form-control" name="group_id" id="group_id">
                <option value="0" selected disabled>Select group</option>
                @foreach($groups as $item)
                    <option value="{{ $item->id }}"{{ $item->id == old('group_id', $groups->first()->id) ? ' selected' : '' }}>
                        {{ $item->name }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('group_id'))
                <p class="help-block">
                    <strong>{{ $errors->first('group_id') }}</strong>
                </p>
            @endif
            <p class="help-block">
                The access level of an user. The group with admin permissions is '{{ game()->getAdminGroup()->name }}'.
            </p>
        </div>

        <div class="form-group{{ $errors->has('notify') ? ' has-error' : '' }}">
            <div class="checkbox">
                <label>
                    <input type="hidden" name="notify" value="0">
                    <input type="checkbox" name="notify" id="notify" value="1"{{ old('notify', 1) ? ' checked' : '' }}>
                    Notify user?
                </label>
            </div>
            @if ($errors->has('notify'))
                <p class="help-block">
                    <strong>{{ $errors->first('notify') }}</strong>
                </p>
            @endif
            <p class="help-block">
                Do we need to notify the user with his new account (via email).
            </p>
        </div>

        <button type="submit" class="btn btn-success">Create</button>
        <a href="{{ route('users.index') }}" class="btn btn-warning">Cancel</a>
@endsection
