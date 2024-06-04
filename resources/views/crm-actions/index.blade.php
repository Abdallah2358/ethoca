@extends('layouts.app')
{{-- TODO : Add Copany coloum --}}
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Crm Actions Table</div>
            <div class="card-body">
                <table id="myTable" class="display resposive nowrap" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Alert Id</th>
                            <th>Name</th>
                            <th>Details Link</th>
                            <th>Status</th>
                            <th>Created At</th>

                            {{-- <th>Errors</th> --}}
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
            ajax: "{{ route('crm-actions.data') }}",
            columns: [{
                    data: 'id'
                },
                {
                    data: 'ethoca_alert_id',
                    render: function(data, type, row) {
                        return `<a href="/alerts/${data}"># ${data}</a>`;
                    }
                },
                {
                    data: 'name'
                },
                {
                    data: 'link'
                },
                {
                    data: 'status'
                },
                {data:'created_at'},

                // {
                //     data: 'errors_count'
                // }
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
{{--
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
</table> --}}
