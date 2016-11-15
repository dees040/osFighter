<form action="{{ route('config.update') }}" method="post">
    {{ method_field('PUT') }}
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('app_name') ? ' has-error' : '' }}">
        <label class="control-label" for="app_name">App Name</label>
        <input type="text" class="form-control" id="app_name" name="app_name"
               value="{{ old('app_name', config('app.name')) }}">
        @if ($errors->has('app_name'))
            <p class="help-block">
                <strong>{{ $errors->first('app_name') }}</strong>
            </p>
        @endif
        <p class="help-block">
            The name of your application.
        </p>
    </div>

    <div class="form-group{{ $errors->has('app_slogan') ? ' has-error' : '' }}">
        <label class="control-label" for="app_slogan">App Slogan</label>
        <input type="text" class="form-control" id="app_slogan" name="app_slogan"
               value="{{ old('app_slogan', game('app_slogan')) }}">
        @if ($errors->has('app_slogan'))
            <p class="help-block">
                <strong>{{ $errors->first('app_slogan') }}</strong>
            </p>
        @endif
        <p class="help-block">
            The slogan of your application. <i>Leave empty if you don't want to use as slogan.</i>
        </p>
    </div>

    <div class="form-group{{ $errors->has('currency_symbol') ? ' has-error' : '' }}">
        <label class="control-label" for="currency_symbol">Currency symbol</label>
        <select class="form-control" name="currency_symbol" id="currency_symbol">
            <option value="&euro;"{{ game('currency_symbol') == '€' ? ' selected' : '' }}>&euro; (Euro)</option>
            <option value="&dollar;"{{ game('currency_symbol') == '$' ? ' selected' : '' }}>&dollar; (Dollar)</option>
            <option value="&pound;"{{ game('currency_symbol') == '£' ? ' selected' : '' }}>&pound; (Pound)</option>
            <option value="&yen;"{{ game('currency_symbol') == '¥' ? ' selected' : '' }}>&yen; (Yen/Yuan)</option>
            <option value="&#x20b9;"{{ game('currency_symbol') == '₹' ? ' selected' : '' }}>&#x20b9; (Rupee)</option>
        </select>
        @if ($errors->has('currency_symbol'))
            <p class="help-block">
                <strong>{{ $errors->first('currency_symbol') }}</strong>
            </p>
        @endif
        <p class="help-block">
            The currency symbol you want to use for your application.
        </p>
    </div>

    <div class="form-group{{ $errors->has('timezone') ? ' has-error' : '' }}">
        <label class="control-label" for="timezone">Timezone</label>
        {!! Timezone::selectForm(config('app.timezone'), 'App Timezone', ['class' => 'form-control', 'name' => 'timezone', 'id' => 'timezone']) !!}
        @if ($errors->has('timezone'))
            <p class="help-block">
                <strong>{{ $errors->first('timezone') }}</strong>
            </p>
        @endif
        <p class="help-block">
            The currency symbol you want to use for your application.
        </p>
    </div>

    <div class="form-group{{ $errors->has('user_start_group') ? ' has-error' : '' }}">
        <label class="control-label" for="user_start_group">New User Start Group</label>
        <select class="form-control" name="user_start_group" id="user_start_group">
            @foreach($groups as $group)
                <option value="{{ $group->id }}"{{ $group->id == old('user_start_group', game('user_start_group')) ? ' selected' : '' }}>
                    {{ $group->name }}
                </option>
            @endforeach
        </select>
        @if ($errors->has('user_start_group'))
            <p class="help-block">
                <strong>{{ $errors->first('user_start_group') }}</strong>
            </p>
        @endif
        <p class="help-block">
            If a user registers a new account. This is the group he or she will be assigned to. You can create new
            groups <a href="{{ route('groups.create') }}">here</a>.
        </p>
    </div>

    <div class="form-group{{ $errors->has('admin_group') ? ' has-error' : '' }}">
        <label class="control-label" for="admin_group">Admin Group</label>
        <select class="form-control" name="admin_group" id="admin_group">
            @foreach($groups as $group)
                <option value="{{ $group->id }}"{{ $group->id == old('admin_group', game('admin_group')) ? ' selected' : '' }}>
                    {{ $group->name }}
                </option>
            @endforeach
        </select>
        @if ($errors->has('admin_group'))
            <p class="help-block">
                <strong>{{ $errors->first('admin_group') }}</strong>
            </p>
        @endif
        <p class="help-block">
            Define the group which has Admin access. This group can for example manage the menus, pages, groups and
            this configuration. You can create new groups <a href="{{ route('groups.create') }}">here</a>.
        </p>
    </div>

    <div class="form-group{{ $errors->has('captcha') ? ' has-error' : '' }}">
        <input type="hidden" name="captcha" value="0">
        <div class="checkbox">
            <label class="control-label" for="captcha">
                <input type="checkbox" name="captcha" id="captcha" value="1"{{ game('captcha') ? ' checked' : '' }}>
                Use captcha
            </label>
        </div>
        @if ($errors->has('captcha'))
            <p class="help-block">
                <strong>{{ $errors->first('captcha') }}</strong>
            </p>
        @endif
        <p class="help-block">
            Captcha is needed for not cheating in your application. If captcha is turned off, user's can create a bot
            which plays the game for them.
        </p>
    </div>

    <button type="submit" class="btn btn-success">Update</button>

</form>