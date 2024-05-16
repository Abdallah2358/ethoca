<h1>Ethoca Request #{{ $request->id }}</h1>
<h3>title : {{ $request->title }}</h3>
<h3>alert_type : {{ $request->alert_type }}</h3>
<h3>search_start_date : {{ $request->search_start_date }}</h3>
<h3>search_end_date : {{ $request->search_end_date }}</h3>
<h3>created_at : {{ $request->created_at }}</h3>
<h3>updated_at : {{ $request->updated_at }}</h3>
<h3>errors</h3>
<table border="1">
    <thead>
        <th>code</th>
        <th>description</th>
        <th>notes</th>
    </thead>
    <tbody>
        @foreach ($request->errors as $error)
            <tr>
                <td>{{ $error['code'] }}</td>
                <td>{{ $error['description'] }}</td>
                <td>{{ $error['notes'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
