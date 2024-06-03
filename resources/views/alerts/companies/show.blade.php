@extends('layouts.app')
{{-- TODO : Add Copany coloum --}}
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Merchants Alerts Table</div>
            <div class="card-body">
                <table id="myTable" class="display resposive nowrap" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Discriptor</th>
                            {{-- <th>Parent LLC</th> --}}
                            <th>Alerts</th>
                            {{-- <th>Handled Alerts</th>
                            <th>UnHandled Alerts</th> --}}
                            <th>Total</th>
                            <th>Actions</th>
                        </tr>
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
            ajax: "{{ route('alerts.merchants.data') }}",
            columns: [{
                    data: 'id',
                },
                {
                    data: 'descriptor'
                },

              /*  {
                    data: 'company_name',
                    render: function(data, type, row) {
                        return data ?
                            `<a href="{{ route('companies.show', '') }}/${row.company_id}" class="link-dark text-decoration-none" >${data} <i class="bi bi-box-arrow-up-right" style="font-size: 0.73rem;"></i></a>` :
                            'N/A';
                    },
                    name: 'company_name'

                },*/
                {
                    data: 'alerts_data.alerts_count'
                },
                {
                    data: 'alerts_data.handled_alerts_total_amount'
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        return `<a href="{{ route('alerts.merchants.show', '') }}/${data}" hover="Show Merchant Alerts"><i class="bi bi-eye"></i></a>`;
                    }
                },
            ],
            columnDefs: [{
                    searchPanes: {
                        show: true,
                        initCollapsed: true,
                    },
                    target: 'company_name:name',
                },
                {
                    searchPanes: {
                        show: false,
                        initCollapsed: true,
                    },
                    targets: '_all'
                },
            ],
            lengthMenu: [10, 25, 50, {
                label: 'All',
                value: -1
            }],
            layout: {
                top: {

                    searchPanes: {
                        dtOpts: {
                            dom: 'Pfrtip',
                        }
                    },
                },
                bottom: {
                    buttons: ['pdf', 'excel', 'print'],
                },
            },
            // searchPanes: {
            //     order:['Email','Name']
            // }
        });
    </script>
@endpush
