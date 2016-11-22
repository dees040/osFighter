@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    <ul class="nav nav-tabs nav-tabs-content" role="tablist">
        <li role="presentation" class="active">
            <a href="#tab-text" aria-controls="configuration" role="tab" data-toggle="tab">
                Profile text
            </a>
        </li>
        {{-- <li role="presentation">
            <a href="#tab-ranks" aria-controls="ranks" role="tab" data-toggle="tab">
                Ranks
            </a>
        </li>--}}
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="tab-text">
            <form action="{{ route('settings.update') }}" method="post">
                {{ csrf_field() }}

                <div class="input-group{{ $errors->has('profile_text') ? ' has-error' : '' }}">
                    <textarea id="trumbowyg" name="profile_text" class="form-control"
                              placeholder="Your text as placeholder">{{ old('profile_text', user()->profile_text) }}</textarea>
                    @if($errors->has('profile_text'))
                        <p class="help-block">{{ $errors->first('profile_text') }}</p>
                    @endif
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
        <div role="tabpanel" class="tab-pane" id="tab-ranks">

        </div>
    </div>
@endsection
