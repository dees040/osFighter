@extends('layouts.app')

@section('title', 'Edit ' . $route->title. ' Route')

@section('content')
    <form action="{{ route('routes.update', $route) }}" method="post">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <div class="form-group{{ $errors->has('title') ? ' has-error' : '' }}">
            <label class="control-label" for="title">Page title</label>
            <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $route->title) }}">
            @if ($errors->has('title'))
                <p class="help-block">
                    <strong>{{ $errors->first('title') }}</strong>
                </p>
            @endif
        </div>

        @if ($route->menuable)
            <div class="form-group{{ $errors->has('menu') ? ' has-error' : '' }}">
                <label class="control-label" for="menu">Menu</label>
                <select class="form-control" name="menu" id="menu">
                    <option value="0" selected disabled>Select a menu</option>
                    @foreach($menus as $menu)
                        <option value="{{ $menu->id }}"{{ $menu->id == old('menu', $route->menu_id) ? ' selected' : '' }}>
                            {{ $menu->name }}
                        </option>
                    @endforeach
                </select>
                @if ($errors->has('menu'))
                    <p class="help-block">
                        <strong>{{ $errors->first('menu') }}</strong>
                    </p>
                @endif
            </div>
        @endif

        <div class="form-group{{ $errors->has('url') ? ' has-error' : '' }}">
            <label class="control-label" for="url">Page url</label>
            <input type="text" class="form-control" id="url" name="url" value="{{ old('url', $route->url) }}">
            @if($errors->has('url'))
                <p class="help-block">
                    <strong>{{ $errors->first('url') }}</strong>
                </p>
            @endif
            @if($route->rules->hasBindings())
                <p class="help-block">
                    <strong>
                        This route needs a parameter binding. This means we need to know some parameters. The following
                        parameters are required: {{ $route->rules->bindings()->implode(', ') }}. The parameters need to
                        be wrapped in braces: {}.
                    </strong>
                </p>
            @endif
        </div>

        <div class="form-group{{ $errors->has('group') ? ' has-error' : '' }}">
            <label class="control-label" for="group">Group</label>
            <select class="form-control" name="group" id="group">
                <option value="0" selected disabled>Select a group</option>
                @foreach($groups as $item)
                    <option value="{{ $item->id }}"{{ $item->id == old('group', $route->rules->group_id) ? ' selected' : '' }}>
                        {{ $item->name }}
                    </option>
                @endforeach
            </select>
            @if ($errors->has('group'))
                <p class="help-block">
                    <strong>{{ $errors->first('group') }}</strong>
                </p>
            @endif
            <p class="help-block">
                The given group and all it's parents will have access to this page.
            </p>
        </div>

        <div class="form-group{{ $errors->has('jail') ? ' has-error' : '' }}">
            <input type="hidden" name="captcha" value="0">
            <div class="checkbox">
                <label class="control-label" for="flying">
                    <input type="hidden" name="jail" value="0">
                    <input type="checkbox" name="jail" id="jail"
                           value="1"{{ $route->rules->jail_viewable ? ' checked' : '' }}>
                    View able when in jail
                </label>
            </div>
            @if ($errors->has('jail'))
                <p class="help-block">
                    <strong>{{ $errors->first('jail') }}</strong>
                </p>
            @endif
            <p class="help-block">
                May the user view this page when in jail?
            </p>
        </div>

        <div class="form-group{{ $errors->has('flying') ? ' has-error' : '' }}">
            <input type="hidden" name="flying" value="0">
            <div class="checkbox">
                <label class="control-label" for="flying">
                    <input type="hidden" name="flying" value="0">
                    <input type="checkbox" name="flying" id="flying"
                           value="1"{{ $route->rules->fly_viewable ? ' checked' : '' }}>
                    View able when flying.
                </label>
            </div>
            @if ($errors->has('flying'))
                <p class="help-block">
                    <strong>{{ $errors->first('flying') }}</strong>
                </p>
            @endif
            <p class="help-block">
                May the user view this page while flying?
            </p>
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('routes.show', $route) }}" class="btn btn-warning">Cancel</a>

    </form>
@endsection
