<h1>Ethoca alert #{{ $alert->id }}</h1>
<h3>ethoca_response_id : {{ $alert->ethoca_response_id }}</h3>
<h3>crm_transaction_id : {{ $alert->crm_transaction_id }}</h3>
<h3>is_handled : {{ $alert->is_handled }}</h3>
<h3>is_updated : {{ $alert->is_updated }}</h3>
<h3>is_ack : {{ $alert->is_ack }}</h3>
<h3>ethoca_id : {{ $alert->ethoca_id }}</h3>
<h3>type : {{ $alert->type }}</h3>
<h3>alert_timestamp : {{ $alert->alert_timestamp }}</h3>
<h3>age : {{ $alert->age }}</h3>
<h3>issuer : {{ $alert->issuer }}</h3>
<h3>card_number : {{ $alert->card_number }}</h3>
<h3>card_bin : {{ $alert->card_bin }}</h3>
<h3>card_last4 : {{ $alert->card_last4 }}</h3>
<h3>arn : {{ $alert->arn }}</h3>
<h3>transaction_timestamp : {{ $alert->transaction_timestamp }}</h3>
<h3>merchant_descriptor : {{ $alert->merchant_descriptor }}</h3>
<h3>member_id : {{ $alert->member_id }}</h3>
<h3>mcc : {{ $alert->mcc }}</h3>
<h3>amount : {{ $alert->amount }}</h3>
<h3>currency : {{ $alert->currency }}</h3>
<h3>transaction_type : {{ $alert->transaction_type }}</h3>
<h3>initiated_by : {{ $alert->initiated_by }}</h3>
<h3>is_3d_secure : {{ $alert->is_3d_secure }}</h3>
<h3>source : {{ $alert->source }}</h3>
<h3>auth_code : {{ $alert->auth_code }}</h3>
<h3>merchant_member_name : {{ $alert->merchant_member_name }}</h3>
<h3>ethoca_transaction_id : {{ $alert->ethoca_transaction_id }}</h3>
<h3>chargeback_reason_code : {{ $alert->chargeback_reason_code }}</h3>
<h3>chargeback_amount : {{ $alert->chargeback_amount }}</h3>
<h3>chargeback_currency : {{ $alert->chargeback_currency }}</h3>
<h3>created_at : {{ $alert->created_at }}</h3>
<h3>updated_at : {{ $alert->updated_at }}</h3>
<h3>Acknowledgements</h3>
<table border='1'>
    <thead>
        <th>Id</th>
        <th>ethoca_id</th>
        <th>errors</th>
    </thead>
    <tbody>
        @foreach ($alert->ethocaAcknowledgements as $ethocaAcknowledgement)
            <tr>
                <td>{{ $ethocaAcknowledgement->id }}</td>
                <td>{{ $ethocaAcknowledgement->ethoca_id }}</td>
                <td>{{ $ethocaAcknowledgement->errors->count() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<h3>CRM Actions</h3>
<table border="1">
    <thead>
        <th>Id</th>
        <th>ethoca_id</th>
        <th>errors</th>
    </thead>
    <tbody>
        @foreach ($alert->crmActions as $crmAction)
            <tr>
                <td>{{ $crmAction->id }}</td>
                <td>{{ $crmAction->name }}</td>
                <td>{{ $crmAction->link }}</td>
                <td>{{ $crmAction->status }}</td>
                <td>{{ $crmAction->errors->count() }}</td>
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
        @foreach ($alert->ethocaUpdates as $ethocaUpdate)
            <tr>
                <td>{{ $ethocaUpdate->id }}</td>
                <td>{{ $ethocaUpdate->ethoca_id }}</td>
                <td>{{ $ethocaUpdate->errors->count() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
<h3>Errors</h3>
<table border="1">
    <thead>
        <th>code</th>
        <th>Origin</th>
        
        <th>description</th>
        <th>notes</th>
    </thead>
    <tbody>
        @foreach ($alert->errors as $error)
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