<h1>Name : {{ $action->name }}</h1>
<h1>Details Link : {{ $action->link }}</h1>
<h1>Status : {{ $action->status }}</h1>
<h1>Errors</h1>
<table border="1">
    <thead>
        <th>code</th>
        <th>Message</th>
    </thead>
    <tbody>
        @foreach ($action->errors as $error)
            <tr>
                <td>{{ $error->code }}</td>
                <td>{{ $error->message }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

