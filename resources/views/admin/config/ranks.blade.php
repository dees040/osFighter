<p>
    Update the ranks. Leave a field empty to delete it. You can also rearrange the items by dragging and dropping them
    via the level label.
</p>
<form action="{{ route('ranks.store') }}" method="post">
    {{ csrf_field() }}

    <ul class="list-group" id="sortable-items">
        @foreach($ranks as $key => $rank)
            <li class="list-group-item">
                <div class="input-group">
                    <div class="input-group-addon">Level {{ $rank->level }}</div>
                    <input type="text" class="form-control" name="ranks[]"
                           value="{{ old('ranks.' . $key, $rank->name) }}">
                </div>
            </li>
        @endforeach
        <li class="list-group-item">
            <input type="text" class="form-control" name="ranks[]">
        </li>
    </ul>
    <button type="submit" class="btn btn-success">Update</button>
</form>