@extends('dashbord.layouts.master')
<style>
    .btn:not(.btn-outline):not(.btn-dashed):not(.border-hover):not(.border-active):not(.btn-flush):not(.btn-icon).btn-sm,
    .btn-group-sm>.btn:not(.btn-outline):not(.btn-dashed):not(.border-hover):not(.border-active):not(.btn-flush):not(.btn-icon) {
        padding: 10px 12px !important;
    }
</style>
@section('toolbar')
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        @php
            $title = trans('reports.reports');
            $breadcrumbs = [
                ['label' => trans('Toolbar.home'), 'link' => route('admin.dashboard')],
                ['label' => trans('Toolbar.reports'), 'link' => ''],
                ['label' => trans('reports.reports_table'), 'link' => ''],
            ];

            PageTitle($title, $breadcrumbs);
        @endphp
    </div>

@endsection
@section('content')

    <div id="kt_app_content_container" class="app-container container-xxxl">

        {{-- <form action="{{ route('admin.reports.reports') }}" method="get" enctype="multipart/form-data"> --}}
        {{-- @csrf --}}
        <div class="card-body">
            <div class="col-md-12 row">

                <div class="col-md-3">
                    <label for="client_id"class="form-label">{{ trans('reports.client_id') }}</label>
                    <div class="input-group flex-nowrap ">
                        <span class="input-group-text" id="basic-addon3">{!! form_icon('select1') !!}</i></span>
                        <div class="overflow-hidden flex-grow-1">
                            <select class="form-select rounded-start-0" name="client_id" id="client_id"
                                data-placeholder="{{ trans('reports.select') }}">
                                <option value="">{{ trans('reports.select') }}</option>
                                @foreach ($clients as $item)
                                    <option value="{{ $item->id }}"
                                        {{ old('client_id') == $item->id ? 'selected' : '' }}>{{ $item->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @error('client_id')
                        <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-3">
                    <label for="type" class="form-label">{{ trans('reports.type') }}</label>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="basic-addon4">{!! form_icon('select1') !!}</span>
                        <select class="form-select" name="type" id="type">
                            <option value="">
                                {{ trans('reports.select') }}
                            </option>
                            <option value="subscription" {{ old('type') == 'subscription' ? 'selected' : '' }}>
                                {{ trans('reports.subscription') }}
                            </option>
                            <option value="service" {{ old('type') == 'service' ? 'selected' : '' }}>
                                {{ trans('reports.service') }}
                            </option>
                        </select>
                    </div>
                    @error('type')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>


                <div class="col-md-3">
                    <label for="status" class="form-label">{{ trans('reports.status') }}</label>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text" id="basic-addon4">{!! form_icon('select1') !!}</span>
                        <select class="form-select" name="status" id="status">
                            <option value="">
                                {{ trans('reports.select') }}
                            </option>
                            <option value="paid" {{ old('status') == 'paid' ? 'selected' : '' }}>
                                {{ trans('reports.paid') }}
                            </option>
                            <option value="partial" {{ old('status') == 'partial' ? 'selected' : '' }}>
                                {{ trans('reports.partial') }}
                            </option>
                            <option value="unpaid" {{ old('status') == 'unpaid' ? 'selected' : '' }}>
                                {{ trans('reports.unpaid') }}
                            </option>
                        </select>
                    </div>
                    @error('status')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="col-md-3">
                    <label for="month" class="form-label">{{ trans('reports.month') }}</label>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text">{!! form_icon('date') !!}</span>
                        <input type="month" class="form-control" name="month" id="month"
                            value="{{ old('month') }}">
                    </div>
                    @error('month')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-3 mb-3" style="margin-top: 10px">
                    <label for="from_date" class="form-label">{{ trans('reports.from_date') }}</label>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text">{!! form_icon('date') !!}</span>
                        <input type="date" class="form-control" name="from_date" id="from_date"
                            value="{{ old('from_date') }}">
                    </div>
                    @error('from_date')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="col-md-3 mb-3" style="margin-top: 10px;">
                    <label for="to_date" class="form-label">{{ trans('reports.to_date') }}</label>
                    <div class="input-group flex-nowrap">
                        <span class="input-group-text">{!! form_icon('date') !!}</span>
                        <input type="date" class="form-control" name="to_date" id="to_date"
                            value="{{ old('to_date') }}">
                    </div>
                    @error('to_date')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                {{--
                    <div class="col-md-3" style="margin-top: 35px">
                        <button type="submit" class="btn btn-primary">{{ trans('reports.search') }}</button>
                    </div> --}}
            </div>
        </div>
        {{-- </form> --}}


        <div class="card shadow-sm" style="border-top: 3px solid #007bff;">
            @php
                $headers = [
                    'invoices.ID',
                    'invoices.invoice_number',
                    'invoices.client',
                    'invoices.amount',
                    'invoices.paid_amount',
                    'invoices.remaining_amount',
                    'invoices.due_date',
                    'invoices.paid_date',
                    'invoices.status',
                    'invoices.subscription',
                    // 'invoices.employee',
                    'invoices.month_year',
                    // 'invoices.action',
                ];

                generateTable($headers);
            @endphp
        </div>

    </div>

    <div class="modal fade" id="payInvoiceModal" tabindex="-1" aria-labelledby="payInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="payInvoiceModalLabel">{{ trans('invoices.enter_payment_amount') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="payInvoiceForm" method="POST" action="">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="invoice_amount" class="form-label">{{ trans('invoices.invoice_amount') }}</label>
                            <input type="number" class="form-control" id="invoice_amount" name="invoice_amount"
                                required min="1">
                        </div>
                        <div class="mb-3">
                            <label for="paid_amount"
                                class="form-label">{{ trans('invoices.invoice_paid_amount') }}</label>
                            <input type="number" class="form-control" id="paid_amount" name="paid_amount" required
                                min="1">
                        </div>
                        <div class="mb-3">
                            <label for="notes" class="form-label">{{ trans('invoices.notes') }}</label>
                            <textarea class="form-control" id="notes" name="notes" rows="2"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{ trans('invoices.cancel') }}</button>
                        <button type="submit" class="btn btn-primary">{{ trans('invoices.pay') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modaldetails">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title"><?= trans('invoices.invoice_details') ?></h3>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                        aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1">&times;</i>
                    </div>

                </div>

                <div id="result_info">

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
                "deferRender": true,
                "order": [],
                "ajax": {
                    url: "{{ route('admin.reports.index') }}",
                    type: "POST",
                    data: function(d) {
                        d._token = "{{ csrf_token() }}";
                        d.client_id = $('#client_id').val();
                        d.type = $('#type').val();
                        d.status = $('#status').val();
                        d.month = $('#month').val();
                        d.from_date = $('#from_date').val();
                        d.to_date = $('#to_date').val();
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error:", status, error);
                        console.log("Response Text:", xhr.responseText);
                    }
                },
                "columns": [{
                        data: 'id',
                        className: 'text-center no-export'
                    },
                    {
                        data: 'invoice_number',
                        className: 'text-center'
                    },
                    {
                        data: 'client',
                        className: 'text-center'
                    },
                    {
                        data: 'amount',
                        className: 'text-center'
                    },
                    {
                        data: 'paid_amount',
                        className: 'text-center'
                    },
                    {
                        data: 'remaining_amount',
                        className: 'text-center'
                    },
                    // {
                    //     data: 'enshaa_date',
                    //     className: 'text-center'
                    // },
                    {
                        data: 'due_date',
                        className: 'text-center'
                    },
                    {
                        data: 'paid_date',
                        className: 'text-center'
                    },
                    {
                        data: 'status',
                        className: 'text-center'
                    },
                    {
                        data: 'subscription',
                        className: 'text-center'
                    },
                    // {
                    //     data: 'employee',
                    //     className: 'text-center'
                    // },
                    {
                        data: 'month_year',
                        className: 'text-center'
                    },
                    // {
                    //     data: 'action',
                    //     name: 'action',
                    //     orderable: false,
                    //     className: 'text-center no-export'
                    // },
                ],
                "columnDefs": [{
                        "targets": [1, -1],
                        "orderable": false,
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
                            });
                        }
                    },

                    {
                        "targets": [5],
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
                        "text": '<i class="bi bi-file-earmark-excel"></i>إكسل',
                        "className": 'btn btn-dark'
                    },
                    {
                        "extend": 'copy',
                        "text": '<i class="bi bi-clipboard"></i>نسخ',
                        "className": 'btn btn-primary'
                    }
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
                    [10, 5, 25, 50, -1],
                    [10, 5, 25, 50, "الكل"]
                ],
            });

            $('#client_id, #type, #status, #month, #from_date, #to_date').on('change', function() {
                table.ajax.reload();
            });

            // $('#client_id, #type, #status, #month, #from_date, #to_date').on('change', function() {
            //     table.draw();
            // });

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
        function invoice_details(url) {
            $.get(url, function(data) {
                $('#result_info').html(data);
                $('#modaldetails').modal('show');
            });
        }

        function print_invoice(url) {
            var printWindow = window.open(url, '_blank');
            printWindow.focus();
        }
    </script>
@endsection
