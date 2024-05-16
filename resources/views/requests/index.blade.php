<h1>Hello from indx </h1>
<table style="text-align: center" border="1">
    <thead>
        <th>id</th>
        <th>title</th>
        <th>alert_type</th>
        <th>search_start_date</th>
        <th>search_end_date</th>
        <th>created_at</th>
        <th>updated_at</th>
        <th>errors</th>
        </th>
    </thead>
    <tbody>
        @foreach ($requests as $request)
            <tr>
                <td>
                    <a href="{{ route('requests.show', $request->id) }}">
                        {{ $request->id }}
                    </a>
                </td>
                <td>{{ $request->title }}</td>
                <td>{{ $request->alert_type }}</td>
                <td>{{ $request->search_start_date }}</td>
                <td>{{ $request->search_end_date }}</td>
                <td>{{ $request->created_at }}</td>
                <td>{{ $request->updated_at }}</td>
                <td>{{ count($request->errors) }}</td>
            </tr>
        @endforeach

    </tbody>
</table>
