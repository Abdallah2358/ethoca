@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="card">
            <div class="card-header">Manage Users</div>
            <div class="card-body">
                <table id="myTable" class="display responsive nowrap" style="width: 100%">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>Name</th>
                            <th>Email</th>
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
    <script>
        $('#myTable').DataTable({
            ajax: "{{ route('users.data') }}",
            paging: true,
            responsive: true,
            // select: true,
            lengthChange: true,
            columns: [{
                    data: 'id'
                },

                {
                    data: 'name'
                },
                {
                    data: 'email'
                },
            ],

            columnDefs: [{
                searchPanes: {
                    show: true,
                    initCollapsed: true,

                },
                targets: [1, 2],
            }, ],
            lengthMenu: [10, 25, 50, {
                label: 'All',
                value: -1
            }],
            layout: {
                topStart: {

                    buttons: ['pageLength', 'searchPanes', 'pdf','excel','print'
                    ]
                }
            },
            // searchPanes: {
            //     order:['Email','Name']
            // }

        });
    </script>
@endpush
