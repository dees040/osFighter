@extends('layouts.app')

@section('title', 'Edit ' . $user->username)

@section('content')
    <form action="{{ route('users.update', $user) }}" method="post">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('username') ? ' has-error' : '' }}">
            <label class="control-label" for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username"
                   value="{{ old('username', $user->username) }}">
            @if ($errors->has('username'))
                <p class="help-block">
                    <strong>{{ $errors->first('username') }}</strong>
                </p>
            @endif
        </div>

        <div class="form-group{{ $errors->has('cash') ? ' has-error' : '' }}">
            <label class="control-label" for="cash">Cash</label>
            <div class="input-group">
                <span class="input-group-addon">{{ game()->currency_symbol }}</span>
                <input type="number" class="form-control" id="cash" name="cash"
                       value="{{ old('cash', $user->info->cash) }}" min="0">
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
                <input type="number" class="form-control" id="bank" name="bank"
                       value="{{ old('bank', $user->info->bank) }}" min="0">
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
            <input type="number" class="form-control" id="power" name="power"
                   value="{{ old('power', $user->info->power) }}" min="0">
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
                    <option value="{{ $item->id }}"{{ $item->id == old('rank_id', $user->info->rank_id) ? ' selected' : '' }}>
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
                    <option value="{{ $item->id }}"{{ $item->id == old('group_id', $user->group->id) ? ' selected' : '' }}>
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

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('users.show', $user) }}" class="btn btn-warning">Cancel</a>
@endsection
