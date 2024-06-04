@extends('layouts.app')
{{-- TODO : Add Copany coloum --}}
@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Errors Table</div>
            <div class="card-body">
                <table id="myTable" class="display resposive nowrap" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Model</th>
                            <th>Model Id</th>
                            <th>Ethoca Id</th>
                            <th>Code</th>
                            <th>Description</th>
                            <th>Notes</th>
                            <th>Created At</th>
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
            ajax: "{{ route('errors.data') }}",
            columns: [{data: 'id'},
                {data:'model'},
                {data:'model_id'},
                {data:'ethoca_id'},
                {data:'code'},
                {data:'description'},
                {data:'notes'},
                {data:'created_at'},

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
