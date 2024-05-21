@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Manage Users</div>
            <div class="card-body">
                <table id="myTable" class="display resposive nowrap" style="width: 100%">
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
                        {{-- <th>errors</th> --}}
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.2/moment.min.js"></script>
    <script>
        const table = $('#myTable');
        table.DataTable({
            ajax: "{{ route('alerts.data') }}",
            // paging: true,
            responsive: false,
            // select: true,
            // scrollY: 200,
            scrollx: true,
            // scrollX: true,
            // deferRender: true,
            // scroller: true,
            // lengthChange: true,
            columns: [
                {
                    data: 'id'
                },
                {
                    data: 'ethoca_response_id'
                },
                {
                    data: 'crm_transaction_id'
                },
                {
                    data: 'is_handled'
                },
                {
                    data: 'is_updated'
                },
                {
                    data: 'is_ack'
                },
                {
                    data: 'ethoca_id'
                },
                {
                    data: 'type'
                },
                {
                    data: 'alert_timestamp'
                },
                {
                    data: 'age'
                },
                {
                    data: 'issuer'
                },
                {
                    data: 'card_number'
                },
                {
                    data: 'card_bin'
                },
                {
                    data: 'card_last4'
                },
                {
                    data: 'arn'
                },
                {
                    data: 'transaction_timestamp'
                },
                {
                    data: 'merchant_descriptor'
                },
                {
                    data: 'member_id'
                },
                {
                    data: 'mcc'
                },
                {
                    data: 'amount'
                },
                {
                    data: 'currency'
                },
                {
                    data: 'transaction_type'
                },
                {
                    data: 'initiated_by'
                },
                {
                    data: 'is_3d_secure'
                },
                {
                    data: 'source'
                },
                {
                    data: 'auth_code'
                },
                {
                    data: 'merchant_member_name'
                },
                {
                    data: 'ethoca_transaction_id'
                },
                {
                    data: 'chargeback_reason_code'
                },
                {
                    data: 'chargeback_amount'
                },
                {
                    data: 'chargeback_currency'
                },
                {
                    data: 'created_at'
                },
                {
                    data: 'updated_at'
                },
                // {data: 'errors'}
            ],

            columnDefs: [{
                searchPanes: {
                    show: true,
                    initCollapsed: true,

                },
                targets: [1, 2],
            }, ],
            // lengthMenu: [10, 25, 50, {
            //     label: 'All',
            //     value: -1
            // }],
            layout: {
                topStart: {

                    buttons: ['pageLength', {
                        extend: 'searchPanes',
                        text: 'Filter'
                    }, 'pdf', 'excel', 'print']
                },

            },
            // searchPanes: {
            //     order:['Email','Name']
            // }

        });
    </script>
@endpush
