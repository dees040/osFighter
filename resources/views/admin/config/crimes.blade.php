<p>
    Manage your crimes. The crimes are sorted by chance.
</p>
<table class="table table-responsive table-clearance">
    <tr>
        <th>
            Title
        </th>
        <th>
            Chance
        </th>
        <th>
            Options
        </th>
    </tr>
    @foreach($crimes as $crime)
        <tr>
            <td>
                {{ $crime->title }}
            </td>
            <td>
                {{ $crime->chance }}
            </td>
            <td>
                <a href="{{ route('crimes.show', $crime) }}">
                    <img src="{{ icon('eye--arrow') }}" alt="Show">
                </a>
                <a href="{{ route('crimes.edit', $crime) }}">
                    <img src="{{ icon('pencil') }}" alt="Edit">
                </a>
            </td>
        </tr>
    @endforeach
</table>
<a href="{{ route('crimes.create') }}" class="btn btn-primary">Create new crime</a>