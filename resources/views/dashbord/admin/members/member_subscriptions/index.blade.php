@extends('dashbord.layouts.master')
@section('toolbar')
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{trans('sub.main_subscriptions')}}</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="{{ route('admin.dashboard') }}"
                                                          class="text-muted text-hover-primary">{{trans('Toolbar.home')}}</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">{{trans('Toolbar.member_subscriptions')}}</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">{{trans('sub.member_subscriptions')}}</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">{{trans('sub.member_subscriptions_table')}}</li>
            </ul>
        </div>


        <div class="d-flex align-items-center gap-2 gap-lg-3 ">
            <div class="d-flex">
                <a href="{{route('admin.subscriptions.member-subscriptions.create')}}"
                   class="btn btn-icon btn-sm btn-success flex-shrink-0 ms-4">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                    <span class="svg-icon svg-icon-2">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
														<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2"
                                                              rx="1" transform="rotate(-90 11.364 20.364)"
                                                              fill="currentColor"/>
														<rect x="4.36396" y="11.364" width="16" height="2" rx="1"
                                                              fill="currentColor"/>
                                                    </svg>
					</span>

                </a>
            </div>


        </div>

    </div>

@endsection
@section('content')

    <div id="kt_app_content_container" class="t_container">
        <div class="card shadow-sm ">
            <div class="card-header">
                <h3 class="card-title"></i> {{trans('sub.special_subscriptions_table')}}</h3>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>

                @endif
            </div>


            <div class="card-body">

                <div class="table-responsive">
                    <table id="table" class="table table-bordered">
                        <thead>
                        <tr class="fw-bold fs-6 text-gray-800">
                            <th>{{trans('sub.m')}}</th>
                            <th>{{trans('sub.member')}}</th>
                            <th>{{trans('sub.process_num')}}</th>
                            <th>{{trans('sub.process_date')}}</th>
                            <th>{{trans('sub.sub_end_date')}}</th>
                            <th>{{trans('sub.sub_status')}}</th>
                            <th>{{trans('sub.actions')}}</th>

                        </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>


            </div>

        </div>


    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{trans('members.details')}}</h5>
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <i class="">&times;</i>
                    </div>
                </div>
                <div class="modal-body">
                    <div id="details">

                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary"
                            data-bs-dismiss="modal">{{trans('sub.Close')}}</button>

                </div>
            </div>
        </div>

    </div>

@stop
@section('js')
    <script>
        var table;
        var dt;
        var filterPayment;

        // Class definition
        var KTDatatablesServerSide = function () {
            // Shared variables

            // Private functions
            var initDatatable = function () {
                dt = $('#table').DataTable({
                    searchDelay: 500,
                    processing: true,
                    serverSide: true,
                    dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'f><'col-sm-12 col-md-4'B>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    ajax: "{{route('admin.subscriptions.member-subscriptions.index')}}",
                    columns: [
                        {data: 'id', className: 'text-center no-export'},
                        {data: 'member_name', className: 'text-center'},
                        {data: 'process_num', className: 'text-center'},
                        {data: 'process_date', className: 'text-center'},
                        {data: 'end_date', className: 'text-center'},
                        {data: 'sub_status', className: 'text-center'},
                        {data: 'actions', className: 'text-center no-export'},
                    ],
                    order: [[0, 'desc']],
                    columnDefs: [
                        {
                            "targets": [0, 1, 2, 3],
                            "createdCell": function (td, cellData, rowData, row, col) {
                                $(td).css({
                                    'font-weight': '600',
                                    'text-align': 'center',

                                });
                            }
                        },
                    ],
                    buttons: [
                        {
                            extend: 'excelHtml5',
                            text: '{{trans('forms.ExportToExcel')}}',
                            exportOptions: {
                                columns: ':visible:not(.no-export)'  // Exclude columns with class 'no-export'
                            }
                        }
                    ]
                });

                table = dt.$;

                // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
                dt.on('draw', function () {
                    KTMenu.createInstances();
                });
            }
            // Delete customer
            var handleDeleteRows = function () {
                const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
                KTUtil.on(document.body, '[data-kt-table-delete="delete_row"]', 'click', function (e) {
                    e.preventDefault();
                    // Select parent row
                    const parent = e.target.closest('tr');
                    var action = e.target.getAttribute('href'); // Use 'this' instead of 'e'
                    console.log('action', action)
                    // Get customer name
                    const customerName = parent.querySelectorAll('td')[1].innerText;

                    Swal.fire({
                        text: "{{trans('forms.delete_quetion')}}?",
                        icon: "warning",
                        showCancelButton: true,
                        buttonsStyling: false,
                        confirmButtonText: "{{trans('forms.delete_btn')}}",
                        cancelButtonText: "{{trans('forms.action_no')}}",
                        customClass: {
                            confirmButton: "btn fw-bold btn-danger",
                            cancelButton: "btn fw-bold btn-active-light-primary"
                        }
                    }).then(function (result) {
                        if (result.value) {
                            // Simulate delete request -- for demo purpose only
                            /*Swal.fire({
                                showConfirmButton: false,
                                imageUrl: 'https://media.tenor.com/C7KormPGIwQAAAAi/epic-loading.gif',
                                imageWidth: 200,
                                imageHeight: 200,
                                target: '#ConvertModalInfo',
                                imageAlt: '',
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            });*/
                            Swal.fire({
                                imageUrl: 'https://media.tenor.com/C7KormPGIwQAAAAi/epic-loading.gif',
                                imageWidth: 200,
                                imageHeight: 200,
                                buttonsStyling: false,
                                showConfirmButton: false,
                                timer: 2000,
                                allowOutsideClick: false,
                                allowEscapeKey: false
                            }).then(function () {


                                if (action) {
                                    fetch(action, {
                                        method: 'DELETE', // or 'GET', 'POST', etc. depending on your server setup
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'X-CSRF-TOKEN': csrfToken,

                                            // Add any additional headers if needed
                                        },
                                        // You can add body if needed, e.g., JSON.stringify({ key: 'value' })
                                    })
                                        .then(response => {
                                            if (!response.ok) {
                                                throw new Error('Network response was not ok');
                                            }
                                            return response.json(); // or response.text() or response.blob(), etc.
                                        })
                                        .then(data => {
                                            // Handle the response data if needed
                                            Swal.fire({
                                                text: "{{trans('forms.Delete')}}",
                                                icon: "success",
                                                buttonsStyling: false,
                                                confirmButtonText: "{{trans('forms.action_done')}}",

                                                customClass: {
                                                    confirmButton: "btn fw-bold btn-primary",
                                                }
                                            }).then(function () {
                                                // delete row data from server and re-draw datatable
                                                dt.draw();
                                            });
                                        })
                                        .catch(error => {
                                            console.error('There was a problem with the fetch operation:', error);
                                        });
                                }


                            });
                        } else if (result.dismiss === 'cancel') {
                            Swal.fire({
                                text: " {{trans('forms.Delete')}}",
                                icon: "error",
                                buttonsStyling: false,
                                confirmButtonText: "{{trans('forms.action_done')}}",
                                customClass: {
                                    confirmButton: "btn fw-bold btn-primary",
                                }
                            });
                        }
                    });
                });

                /* })
             });*/

            }

            // Public methods
            return {
                init: function () {
                    initDatatable();
                    handleDeleteRows();
                }
            }
        }();
        // On document ready
        KTUtil.onDOMContentLoaded(function () {
            KTDatatablesServerSide.init();
        });
    </script>

    <script>
        /*
                function print_subscription(subscription_num) {
                    var request = $.ajax({
                        // print_resrv -- print_contract
                        url: "{{route('admin.subscriptions.print_member_subscription')}}" ,
                type: "POST",
                data: {subscription_num: subscription_num},
            });
            request.done(function (msg) {
                var WinPrint = window.open('', '', 'width=800,height=700,toolbar=0,scrollbars=0,status=0');
                WinPrint.document.write(msg);
                WinPrint.focus();
                // WinPrint.print();

                WinPrint.onafterprint = function () {
                    WinPrint.close();
                    console.log("Printing completed...");
                    location.href = '{{route('admin.Members.index')}}';
                }

            });
            request.fail(function (jqXHR, textStatus) {
                console.log("Request failed: " + textStatus);
            });

        }
*/
        function print_subscription(subscription_num) {
            var request = $.ajax({
                url: "{{route('admin.subscriptions.print_member_subscription')}}",
                type: "POST",
                data: {subscription_num: subscription_num},
            });

            request.done(function (msg) {
                var WinPrint = window.open('', '', 'width=800,height=700,toolbar=0,scrollbars=0,status=0');

                // Write the HTML content to the new window
                WinPrint.document.write(msg);

                // Trigger print after the content is fully loaded
                WinPrint.onload = function () {
                    WinPrint.print();

                    WinPrint.onafterprint = function () {
                        WinPrint.close();
                        console.log("Printing completed...");
                        location.href = '{{route('admin.Members.index')}}';
                    };
                };

                WinPrint.focus();
            });

            request.fail(function (jqXHR, textStatus) {
                console.log("Request failed: " + textStatus);
            });
        }

    </script>
    <script>
        function show_details(process_num) {
            $.ajax({
                url: '{{route('admin.subscriptions.get_member_subscription_details2')}}',
                type: 'get',
                data: {
                    process_num: process_num,
                },
                success: function (data) {
                    $('#details').html(data);

                },
                error: function (xhr, status, error) {

                }
            });
        }

    </script>

    <script>
        function make_qed(process_num) {
            // Show loading alert
            Swal.fire({
                title: '{{trans('forms.Processing')}}',
                text: '{{trans('forms.Processing_data')}}',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading(); // Show loader
                }
            });

            $.ajax({
                url: '{{route('admin.subscriptions.make_qed')}}',
                type: 'POST',
                data: {
                    process_num: process_num,
                },
                success: function (data) {
                    // Close loader and show success alert
                    Swal.fire({
                        icon: 'success',
                        title: '',
                        text: '{{trans('forms.success')}}',
                    });
                    dt.draw();
                },
                error: function (xhr, status, error) {
                    // Close loader and show error alert
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: 'Something went wrong. Please try again.',
                    });
                }
            });
        }
    </script>

@endsection

