@extends('layouts.app')

@section('title', 'Edit the Configuration')

@section('content')
    <ul class="nav nav-tabs nav-tabs-content" role="tablist">
        <li role="presentation" class="active"><a href="#tab-app" aria-controls="configuration" role="tab" data-toggle="tab">App</a></li>
        <li role="presentation"><a href="#tab-ranks" aria-controls="ranks" role="tab" data-toggle="tab">Ranks</a></li>
        <li role="presentation"><a href="#tab-cars" aria-controls="cars" role="tab" data-toggle="tab">Cars</a></li>
        <li role="presentation"><a href="#tab-crimes" aria-controls="crimes" role="tab" data-toggle="tab">Crimes</a></li>
    </ul>

    <div class="tab-content">
        <div role="tabpanel" class="tab-pane active" id="tab-app">
            @include('admin.config.configuration')
        </div>
        <div role="tabpanel" class="tab-pane" id="tab-ranks">
            @include('admin.config.ranks')
        </div>
        <div role="tabpanel" class="tab-pane" id="tab-cars">
            @include('admin.config.cars')
        </div>
        <div role="tabpanel" class="tab-pane" id="tab-crimes">
            @include('admin.config.crimes')
        </div>
    </div>
@endsection
