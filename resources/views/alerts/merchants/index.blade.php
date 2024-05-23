@extends('layouts.app')
{{-- TODO : Add Copany coloum --}}
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Alerts Table</div>
            <div class="card-body">
                <table id="myTable" class="display resposive nowrap" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Discriptor</th>
                            <th>MCC</th>
                            {{-- <th>Alerts Count</th> --}}

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
            // paging: true,
            // responsive: false,
            // select: true,
            // scrollY: 200,
            // scrollx: true,
            // scrollX: true,
            // deferRender: true,
            // scroller: true,
            // lengthChange: true,
            columns: [{
                    data: 'id',
                },
                {
                    data: 'descriptor'
                },
                {
                    data: 'mcc'
                },
                {
                    data: 'id',
                    render: function(data, type, row) {
                        return `<a href="{{ route('alerts.merchants.show', '') }}/${data}" hover="Show Merchant Alerts"><i class="bi bi-eye"></i></a>`;
                    }
                },
            ],
            // columnDefs: [{
            //     searchPanes: {
            //         show: true,
            //         initCollapsed: true,

            //     },
            //     targets: '_all'
            //     // targets: [1, 2],
            // }, ],
            // lengthMenu: [10, 25, 50, {
            //     label: 'All',
            //     value: -1
            // }],
            // layout: {
            //     topStart: {
            //         buttons: ['pageLength', {
            //             extend: 'searchPanes',
            //             text: 'Filter'

            //         }, 'pdf', 'excel', 'print']
            //     },

            // },
            // searchPanes: {
            //     order:['Email','Name']
            // }

        });
    </script>
@endpush
