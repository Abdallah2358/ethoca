<style>
    th {
        position: sticky;
        top: 50px;
        background: white;
    }
</style>
<table style="text-align: center" border="1">
    <thead>
        <th>id</th>
        <th>Alert Id</th>
        <th>Name</th>
        <th>Details Link</th>
        <th>Status</th>
        <th>Errors</th>
    </thead>
    <tbody>
        @foreach ($actions as $action)
            <tr>
                <td><a href="{{ route('crm-actions.show', $action->id) }}"># {{ $action->id }}</a></td>
                <td><a href="{{ route('alerts.show', $action->ethocaAlert->id) }}"># {{ $action->ethocaAlert->id }}</a>
                </td>
                <td>{{ $action->name }}</td>
                <td>{{ $action->link }}</td>
                <td>{{ $action->status }}</td>
                <td>{{ $action->errors->count() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
