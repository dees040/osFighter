@extends('layouts.app')

@section('title', 'Shout Box')

@section('content')
    <div class="shout-box">
        <table class="table table-clearance">
            @foreach($messages as $message)
                <tr class="message">
                    <td>
                        {{ $message->user->username }}
                    </td>
                    <td class="col-xs-9">
                        {{ $message->body }}
                    </td>
                    <td>
                        <time class="timeago" datetime="{{ $message->created_at }}">
                            {{ $message->created_at->format('Y-m-d H:i') }}
                        </time>
                    </td>
                </tr>
            @endforeach
        </table>
        <div class="row">
            <div class="col-lg-12">
                <form action="{{ route('shoutbox.store') }}" method="post">
                    {{ csrf_field() }}
                    <div class="input-group{{ $errors->has('body') ? ' has-error' : '' }}">
                        <input type="text" class="form-control" name="body" placeholder="Message.."
                               value="{{ old('body') }}">
                        <span class="input-group-btn"><button class="btn btn-primary" type="submit">Save</button></span>
                    </div>
                    @if ($errors->has('body'))
                        <div class="input-group has-error">
                            <p class="help-block">
                                <strong>{{ $errors->first('body') }}</strong>
                            </p>
                        </div>
                    @endif
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
                </form>
            </div>
        </div>
    </div>
@endsection
