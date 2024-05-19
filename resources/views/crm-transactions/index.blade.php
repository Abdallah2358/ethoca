<style>
    th {
        position: sticky;
        top: 50px;
        background: white;
    }
</style>
<h1>CRM Transactions</h1>
<a href="{{ route('crm-transactions.index', ['paid' => true]) }}">Paid</a>
<a href="{{ route('crm-transactions.index', ['paid' => false]) }}">Not Paid</a>
<table style="text-align: center" border="1">
    <thead>
        <th>id</th>
        <th>transactionId</th>
        <th>parentTxnId</th>
        <th>merchant</th>
        <th>merchantDescriptor</th>
        <th>merchantId</th>
        <th>isChargedback</th>
        <th>alerts</th>
    </thead>
    <tbody>
        @foreach ($crmTransactions as $crmTransaction)
            <tr>
                <td>
                    <a href="{{ route('crm-transactions.show', $crmTransaction->id) }}">
                        {{ $crmTransaction->id }}
                    </a>
                </td>
                <td>{{ $crmTransaction->transactionId }}</td>
                <td>{{ $crmTransaction->parentTxnId }}</td>
                <td>{{ $crmTransaction->merchant }}</td>
                <td>{{ $crmTransaction->merchantDescriptor }}</td>
                <td>{{ $crmTransaction->merchantId }}</td>
                <td>{{ $crmTransaction->isChargedback }}</td>
                <td>{{ $crmTransaction->ethocaAlerts->count() }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
