<table style="text-align: center" border="1">
    <thead>
        <th>id</th>
        <th>ethoca_response_id</th>
        <th>crm_transaction_id</th>
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
        <th>transaction_timestamp</th>
        <th>merchant_descriptor</th>
        <th>member_id</th>
        <th>mcc</th>
        <th>amount</th>
        <th>currency</th>
        <th>transaction_type</th>
        <th>initiated_by</th>
        <th>is_3d_secure</th>
        <th>source</th>
        <th>auth_code</th>
        <th>merchant_member_name</th>
        <th>ethoca_transaction_id</th>
        <th>chargeback_reason_code</th>
        <th>chargeback_amount</th>
        <th>chargeback_currency</th>
        <th>created_at</th>
        <th>updated_at</th>
    </thead>
    <tbody>
        @foreach ($alerts as $alert)
            <tr>
                <td>
                    <a href="{{ route('alerts.show', $alert->id) }}">
                        {{ $alert->id }}
                    </a>
                </td>
                <td>{{ $alert->ethoca_response_id }}</td>
                <td>{{ $alert->crm_transaction_id }}</td>
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
                <td>{{ $alert->transaction_timestamp }}</td>
                <td>{{ $alert->merchant_descriptor }}</td>
                <td>{{ $alert->member_id }}</td>
                <td>{{ $alert->mcc }}</td>
                <td>{{ $alert->amount }}</td>
                <td>{{ $alert->currency }}</td>
                <td>{{ $alert->transaction_type }}</td>
                <td>{{ $alert->initiated_by }}</td>
                <td>{{ $alert->is_3d_secure }}</td>
                <td>{{ $alert->source }}</td>
                <td>{{ $alert->auth_code }}</td>
                <td>{{ $alert->merchant_member_name }}</td>
                <td>{{ $alert->ethoca_transaction_id }}</td>
                <td>{{ $alert->chargeback_reason_code }}</td>
                <td>{{ $alert->chargeback_amount }}</td>
                <td>{{ $alert->chargeback_currency }}</td>
                <td>{{ $alert->created_at }}</td>
                <td>{{ $alert->updated_at }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
