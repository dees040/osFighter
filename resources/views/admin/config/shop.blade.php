<p>
    Manage your shop items. The items are sorted by power.
</p>
<table class="table table-responsive table-clearance">
    <tr>
        <th>
            Name
        </th>
        <th>
            Price
        </th>
        <th>
            Power
        </th>
        <th>
            Options
        </th>
    </tr>
    @foreach($items as $item)
        <tr>
            <td>
                {{ $item->name }}
            </td>
            <td>
                {{ money($item->price) }}
            </td>
            <td>
                {{ $item->power }}
            </td>
            <td>
                <a href="{{ route('shop.show', $item) }}">
                    <img src="{{ icon('eye--arrow') }}" alt="Show">
                </a>
                <a href="{{ route('shop.edit', $item) }}">
                    <img src="{{ icon('pencil') }}" alt="Edit">
                </a>
            </td>
        </tr>
    @endforeach
</table>
<a href="{{ route('shop.create') }}" class="btn btn-primary">Create new shop item</a>