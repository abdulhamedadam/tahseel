@extends('dashbord.layouts.master')

@section('css')
<style>
    .actions-column .btn-group {
        width: 100%;
    }

    .actions-column .btn {
        padding: 2px 6px !important;
        font-size: 12px !important;
    }
</style>
@endsection

@section('toolbar')
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        @php
            $title = trans('client.clients');
            $breadcrumbs = [
                ['label' => trans('Toolbar.home'), 'link' => route('admin.clients.create')],
                ['label' => trans('Toolbar.clients'), 'link' => ''],
                ['label' => trans('client.clients_table'), 'link' => ''],
            ];

            PageTitle($title, $breadcrumbs);
        @endphp


        <div class="d-flex align-items-center gap-2 gap-lg-3">

            @can('create_client')
                {{ AddButton(route('admin.clients.create')) }}
            @endcan
        </div>
    </div>

@endsection
@section('content')

    <div id="kt_app_content_container" class="app-container container-xxxl">

        <div class="card shadow-sm" style="border-top: 3px solid #007bff;">
            @php
                $headers = [
                    'clients.ID',
                    'clients.name',
                    'clients.phone',
                    //    'clients.email',
                    'clients.user',
                    'clients.box_switch',
                    'clients.client_type',
                    'clients.address1',
                    'clients.subscription',
                    'clients.price',
                    // 'clients.subscription_date',
                    'clients.notes',
                    'clients.start_date',
                    'clients.remaining_amount',
                    'clients.is_active',
                    'clients.action',
                ];

                generateTable($headers);
            @endphp
        </div>

    </div>


    <div class="modal fade" id="clientDetailsModal" tabindex="-1" aria-labelledby="clientDetailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="clientDetailsModalLabel">
                        <i class="bi bi-person-circle"></i> {{ trans('clients.client_details') }}
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="clientDetailsContent">
                    <div class="text-center py-5" id="modalLoader">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">{{ trans('clients.loading_details') }}</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> {{ trans('clients.close') }}
                    </button>
                    <a href="#" id="editClientBtn" class="btn btn-primary" target="_blank" style="display: none;">
                        <i class="bi bi-pencil-square"></i> {{ trans('clients.edit_clients') }}
                    </a>
                </div>
            </div>
        </div>
    </div>












@stop
@section('js')

    <script>
        $(document).ready(function() {
            //datatables
            table = $('#table1').DataTable({
                "language": {
                    url: "{{ asset('assets/Arabic.json') }}"
                },
                "processing": true,
                "serverSide": true,
                "searching": true,
                "order": [],
                "ajax": {
                    url: "{{ route('admin.clients.index') }}",
                },
                "columns": [{
                        data: 'id',
                        className: 'text-center no-export'
                    },
                    {
                        data: 'name',
                        className: 'text-center'
                    },
                    {
                        data: 'phone',
                        className: 'text-center'
                    },
                    // {data: 'email', className: 'text-center'},
                    {
                        data: 'user',
                        className: 'text-center'
                    },
                    {
                        data: 'box_switch',
                        className: 'text-center'
                    },
                    {
                        data: 'client_type',
                        className: 'text-center'
                    },
                    {
                        data: 'address1',
                        className: 'text-center',
                        width: "200px"
                    },
                    {
                        data: 'subscription',
                        className: 'text-center'
                    },
                    {
                        data: 'price',
                        className: 'text-center'
                    },
                    {
                        data: 'notes',
                        className: 'text-center',
                        width: "200px"
                    },
                    {
                        data: 'start_date',
                        className: 'text-center'
                    },
                    {
                        data: 'remaining_amount',
                        className: 'text-center'
                    },
                    {
                        data: 'is_active',
                        className: 'text-center'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        className: 'text-center no-export',
                        width: "30px"
                    },
                ],
                "columnDefs": [{
                        "targets": [-1], //last column
                        "orderable": false, //set not orderable
                        "width": "25px",
                        "className": "text-center no-export actions-column"
                    },
                    {
                        "targets": [1],
                        "createdCell": function(td, cellData, rowData, row, col) {
                            $(td).css({
                                'font-weight': '600',
                                'text-align': 'center',
                                'color': '#6610f2',

                                'vertical-align': 'middle',
                            });
                        }
                    },
                    {
                        "targets": [3, 4],
                        "createdCell": function(td, cellData, rowData, row, col) {
                            $(td).css({
                                'font-weight': '600',
                                'text-align': 'center',
                                'vertical-align': 'middle',
                            });
                        }
                    },
                    {
                        "targets": [2],
                        "createdCell": function(td, cellData, rowData, row, col) {
                            $(td).css({
                                'font-weight': '600',
                                'text-align': 'center',
                                'color': 'green',
                                'vertical-align': 'middle',
                                'direction': 'ltr'
                            });
                        }
                    },

                    {
                        "targets": [5, 11],
                        "createdCell": function(td, cellData, rowData, row, col) {
                            $(td).css({
                                'font-weight': '600',
                                'text-align': 'center',
                                'color': 'red',
                                'vertical-align': 'middle',
                            });
                        }
                    },



                ],
                "order": [],
                "dom": '<"row align-items-center"<"col-md-3"l><"col-md-6"f><"col-md-3"B>>rt<"row align-items-center"<"col-md-6"i><"col-md-6"p>>',
                "buttons": [{
                        "extend": 'excel',
                    },
                    {
                        "extend": 'copy',
                    },
                ],

                "language": {
                    "lengthMenu": "عرض _MENU_ سجلات",
                    "zeroRecords": "لا توجد سجلات",
                    "info": "عرض الصفحة _PAGE_ من _PAGES_",
                    "infoEmpty": "لا توجد سجلات",
                    "infoFiltered": "(مرشح من _MAX_ إجمالي السجلات)",
                    "search": "بحث:",
                    "paginate": {
                        "first": "الأول",
                        "last": "الأخير",
                        "next": "التالي",
                        "previous": "السابق"
                    }
                },
                "lengthMenu": [
                    [10, 25, 50, -1],
                    [10, 25, 50, "الكل"]
                ],
                "pageLength": 10,
            });

            $("input").change(function() {
                $(this).parent().parent().removeClass('has-error');
                $(this).next().empty();
            });
            $("textarea").change(function() {
                $(this).parent().parent().removeClass('has-error');
                $(this).next().empty();
            });
            $("select").change(function() {
                $(this).parent().parent().removeClass('has-error');
                $(this).next().empty();
            });
        });
    </script>

    <script>
        function confirmDelete(clientId) {
            Swal.fire({
                title: '{{ trans('employees.confirm_delete') }}',
                text: '{{ trans('clients.delete_warning') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ trans('employees.yes_delete') }}',
                cancelButtonText: '{{ trans('employees.cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + clientId).submit();
                }
            });
        }
    </script>

    <script>
        function showClientDetails(clientId) {
            $('#clientDetailsModal').modal('show');

            $('#modalLoader').show();
            $('#clientDetailsContent').html($('#modalLoader'));
            $('#editClientBtn').hide();

            $.ajax({
                url: "{{ route('admin.clients.details', '') }}/" + clientId,
                type: 'GET',
                success: function(response) {
                    $('#modalLoader').hide();
                    $('#clientDetailsContent').html(response);
                    $('#editClientBtn').attr('href', "{{ route('admin.clients.edit', '') }}/" + clientId).show();
                },
                error: function(xhr, status, error) {
                    $('#modalLoader').hide();
                    $('#clientDetailsContent').html(
                        '<div class="alert alert-danger text-center">' +
                        '<i class="bi bi-exclamation-triangle fs-1 text-danger"></i>' +
                        '<h4 class="mt-3">{{ trans("clients.error_loading_details") }}</h4>' +
                        '<p class="mb-0">{{ trans("clients.please_try_again") }}</p>' +
                        '</div>'
                    );
                }
            });
        }
    </script>
@endsection
