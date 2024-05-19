<h1>id : {{ $crmTransaction->id }}</h1>
<h1>transactionId : {{ $crmTransaction->transactionId }} </h1>
<h1>parentTxnId : {{ $crmTransaction->transactionId }}</h1>
<h1>merchant : {{ $crmTransaction->transactionId }}</h1>
<h1>merchantDescriptor : {{ $crmTransaction->transactionId }}</h1>
<h1>merchantId : {{ $crmTransaction->transactionId }}</h1>
<h1>alerts</h1>
<table>
    <thead>
        <table style="text-align: center" border="1">
            <thead>
                <th>id</th>
                <th>ethoca_response_id</th>
                <th>is_handled</th>
                <th>is_updated</th>
                <th>is_ack</th>
                <th>ethoca_id</th>
                <th>type</th>
                <th>alert_timestamp</th>
                <th>age</th>
                <th>issuer</th>
                <th>card_number</th>
                <th>card_bin</th>
                <th>card_last4</th>
                <th>arn</th>
                <th>amount</th>
                <th>currency</th>
                <th>transaction_timestamp</th>
                <th>errors</th>
            </thead>
            <tbody>

                @foreach ($crmTransaction->ethocaAlerts as $alert)
                    <tr>

                        <td>{{ $alert->id }}</td>
                        <td>{{ $alert->ethoca_response_id }}</td>
                        <td>{{ $alert->is_handled }}</td>
                        <td>{{ $alert->is_updated }}</td>
                        <td>{{ $alert->is_ack }}</td>
                        <td>{{ $alert->ethoca_id }}</td>
                        <td>{{ $alert->type }}</td>
                        <td>{{ $alert->alert_timestamp }}</td>
                        <td>{{ $alert->age }}</td>
                        <td>{{ $alert->issuer }}</td>
                        <td>{{ $alert->card_number }}</td>
                        <td>{{ $alert->card_bin }}</td>
                        <td>{{ $alert->card_last4 }}</td>
                        <td>{{ $alert->arn }}</td>
                        <td>{{ $alert->amount }}</td>
                        <td>{{ $alert->currency }}</td>
                        <td>{{ $alert->transaction_timestamp }}</td>
                        <td>{{ $alert->errors->count() }}</td>
                    </tr>
                @endforeach
            </tbody>
    </thead>
</table>
