@extends('layouts.app')
{{-- TODO : Add Copany coloum --}}
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">LLC Alerts Table</div>
            <div class="card-body">
                <table id="myTable" class="display resposive nowrap" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Merchants Count</th>
                            <th>Alerts Count</th>
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
            ajax: "{{ route('alerts.companies.data') }}",
            columns: [{
                    data: 'id',
                },
                {
                    data: 'name'
                },
                {
                    data: 'merchants_count'
                },
                {
                    data: 'alerts_count'
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        return `<a href="{{ route('alerts.companies.show', '') }}/${data}" hover="Show Merchant Alerts"><i class="bi bi-eye"></i></a>`;
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
