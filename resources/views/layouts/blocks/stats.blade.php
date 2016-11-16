<div class="navbar-text navbar-right">
    Health
    <div class="progress">
        <div class="progress-bar progress-bar-danger" role="progressbar" aria-valuenow="{{ user()->health }}"
             aria-valuemin="0" aria-valuemax="100" style="min-width: 2em; width: {{ user()->health }}%;">
            {{ user()->health }}%
        </div>
    </div>
</div>
Rank progress
<div class="progress">
    <div class="progress-bar" role="progressbar" aria-valuenow="{{ user()->rank_progress }}" aria-valuemin="0"
         aria-valuemax="100" style="min-width: 2em; width: {{ user()->rank_progress }}%;">
        {{ user()->rank_progress }}%
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        Rank
    </div>
    <div class="col-md-8">
        {{ user()->rank()->name }}
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        Location
    </div>
    <div class="col-md-8">
        <a href="{{ route('airport.index') }}">{{ user()->location()->name }}</a>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        Cash
    </div>
    <div class="col-md-8">
        {{ money(user()->cash) }}
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        Bank
    </div>
    <div class="col-md-8">
        {{ money(user()->bank) }}
    </div>
</div>
