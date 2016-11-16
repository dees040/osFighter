<p>
    Update the locations. Leave a field empty to delete it.
</p>
<form action="{{ route('locations.store') }}" method="post">
    {{ csrf_field() }}

    <ul class="list-group" id="sortable-items">
        @foreach($locations as $key => $location)
            <li class="list-group-item">
                <input type="text" class="form-control" name="locations[]"
                       value="{{ old('ranks.' . $key, $location->name) }}">
            </li>
        @endforeach
        <li class="list-group-item">
            <input type="text" class="form-control" name="locations[]">
        </li>
    </ul>
    <button type="submit" class="btn btn-success">Update</button>
</form>