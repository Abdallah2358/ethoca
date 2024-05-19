<h1>Ethoca Request #{{ $request->id }}</h1>
<h3>title : {{ $request->title }}</h3>
<h3>alert_type : {{ $request->alert_type }}</h3>
<h3>search_start_date : {{ $request->search_start_date }}</h3>
<h3>search_end_date : {{ $request->search_end_date }}</h3>
<h3>created_at : {{ $request->created_at }}</h3>
<h3>updated_at : {{ $request->updated_at }}</h3>
<h3>Alerts</h3>
<table border='1'>
    <thead>
        <th>Id</th>
        <th>ethoca_response_id</th>
        <th>crm_transaction_id</th>
        <th>is_handled</th>
        <th>is_updated</th>
        <th>is_ack</th>
        <th>ethoca_id</th>
        <th>type</th>
        <th>errors</th>
    </thead>
    <tbody>
        @foreach ($request->alerts as $alert)
            <tr>
                <td>{{ $alert->id }}</td>
                <td>{{ $alert->ethoca_response_id }}</td>
                <td>{{ $alert->crm_transaction_id }}</td>
                <td>{{ $alert->is_handled }}</td>
                <td>{{ $alert->is_updated }}</td>
                <td>{{ $alert->is_ack }}</td>
                <td>{{ $alert->ethoca_id }}</td>
                <td>{{ $alert->type }}</td>
                <td>{{ count($alert->errors) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<h3>Acknowledgements</h3>
<table border='1'>
    <thead>
        <th>Id</th>
        <th>ethoca_id</th>
        <th>errors</th>
    </thead>
    <tbody>
        @foreach ($request->ethocaAcknowledgements as $ethocaAcknowledgement)
            <tr>
                <td>{{ $ethocaAcknowledgement->id }}</td>
                <td>{{ $ethocaAcknowledgement->ethoca_id }}</td>
                <td>{{ count($ethocaAcknowledgement->errors) }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<h3>Updates</h3>
<table border='1'>
    <thead>
        <th>Id</th>
        <th>ethoca_id</th>
        <th>errors</th>
    </thead>
    <tbody>
        @foreach ($request->ethocaUpdates as $ethocaUpdate)
            <tr>
                <td>{{ $ethocaUpdate->id }}</td>
                <td>{{ $ethocaUpdate->ethoca_id }}</td>
                <td>{{ $ethocaUpdate->errors?->count() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<h3>errors</h3>
<table border="1">
    <thead>
        <th>code</th>
        <th>Origin</th>
        
        <th>description</th>
        <th>notes</th>
    </thead>
    <tbody>
        @foreach ($request->errors as $error)
            <tr>
                <td>{{ $error['code'] }}</td>
                <td>
                    @switch($error['model'])
                        @case('App\Models\EthocaResponse')
                            Response
                            @break
                        @case('App\Models\EthocaAcknowledgement')
                            Acknowledgement
                            @break
                        @case('App\Models\EthocaUpdate')
                            Update
                            @break
                        @default
                            Unknown
                    @endswitch
                    # {{ $error['model_id'] }}
                </td>
                <td>{{ $error['description'] }}</td>
                <td>{{ $error['notes'] }}</td>
            </tr>
        @endforeach
    </tbody>
</table>