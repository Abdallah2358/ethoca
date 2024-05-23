@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Alerts Table</div>
            <div class="card-body">
                <table id="myTable" class="display resposive nowrap" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Company</th>
                            <th>Merchant Count</th>
                            {{-- <th>Alerts Count</th> --}}
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
            ajax: "{{ route('companies.alerts.data') }}",
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
                    data: 'id'
                },
                {
                    data: 'name'
                },
                {
                    data: 'merchants_count'
                },
                // {
                //     data: 'id'
                // },
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
