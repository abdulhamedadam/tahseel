@extends('dashbord.layouts.master')

@section('toolbar')
    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <!--begin::Title-->
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                {{trans('subscriptions_report.member_subscriptionsReport')}}</h1>
            <!--end::Title-->
            <!--begin::Breadcrumb-->
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">

                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('admin.dashboard') }}" class="text-muted text-hover-primary">
                        {{trans('Toolbar.home')}}</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">
                    {{trans('Toolbar.subscriptionsReport')}}
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">
                    {{trans('Toolbar.member_subscriptionsReport')}}
                </li>


            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--begin::Actions-->
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <!--begin::Filter menu-->
            <div class="d-flex">
                <a href="{{route('admin.subscriptions.member_subscriptions.create')}}"
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
                    <!--end::Svg Icon-->
                </a>
            </div>

        </div>

    </div>
    <!--end::Toolbar container-->
@endsection

@section('content')

    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxxl">
        <div class="card mb-5 mb-xl-10">
            <!--begin::Card header-->
            <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse" data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
                <!--begin::Card title-->
                <div class="card-title m-0">
                    <h3 class="fw-bold m-0">{{trans('subscriptions_report.member_subscriptionsReport')}}</h3>
                </div>
                <!--end::Card title-->
            </div>
            <!--begin::Card header-->

            <!--begin::Content-->
            <div id="kt_account_settings_profile_details" data-kt-user-table-filter="form" class="collapse show">
                <!--begin::Card body-->
                <div class="card-body border-top p-9" >
                    <div class="row">
                        <div class="col-6" >
                            <div class="mb-10 fv-row col">
                                <label
                                    class="required fs-6 fw-semibold mb-2">{{trans('subscriptions_report.member_name')}}
                                </label>
                                <select class="form-select mb-2 @error('member_id') is-invalid @enderror"
                                        onchange=""
                                        data-control="select2" data-hide-search="true"
                                        data-placeholder="Select an option"
                                        name="member_id" id="member_id">
                                    <option value=" ">{{trans('forms.Select')}}</option>

                                    @foreach($members as $key)
                                        <option value="{{$key->id}}" {{old('member_id',$key->id)}}> {{$key->member_name}}</option>
                                    @endforeach


                                </select>
                                <!--end::Select2-->
                                @error('member_id')
                                <div
                                    class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-6" >
                            <div class="mb-10 fv-row col">
                                <label
                                    class="required fs-6 fw-semibold mb-2">{{trans('subscriptions_report.category')}}
                                </label>
                                <select onchange="get_subscription(this.value,' ')"
                                        class="form-control form-control-solid type-select"
                                        data-control="select2" data-hide-search="true"
                                        name="type" id="type">
                                    <option value=" ">{{trans('forms.select')}}</option>
                                    @php $cat_arr=['main'=>trans('members.main_subscription'),'special'=>trans('members.special_subscription')] @endphp
                                    @foreach($cat_arr as $key=>$value)
                                        <option value="{{$key}}"> {{$value}}</option>
                                    @endforeach
                                </select>
                                <!--end::Select2-->
                                @error('subscription_id')
                                <div
                                    class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                    </div>

                    <div class="row">
                        <div class="col-6" >
                            <div class="mb-10 fv-row col">
                                <label
                                    class="required fs-6 fw-semibold mb-2">{{trans('subscriptions_report.subscription')}}
                                </label>
                                <select class="form-control form-control-solid subscription-select"
                                        data-control="select2" data-hide-search="true"
                                        name="subscription_id" id="subscription_id">
                                    <option value=" ">{{trans('forms.select')}}</option>
                                </select>
                                <!--end::Select2-->
                                @error('type')
                                <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <!--end::Card body-->

                <!--begin::Actions-->
                <div class="card-footer d-flex justify-content-end py-6 px-9">
                    <button type="reset" class="btn btn-light btn-active-light-primary me-2" data-kt-user-table-filter="reset">
                        {{trans('forms.reset')}}</button>
                    <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit" data-kt-user-table-filter="filter">
                        {{trans('forms.search')}}</button>
                </div>

            </div>
            <!--end::Content-->
        </div>

        <div class="card card-flush">

            <div class="card-body pt-0">

                <div class="table-responsive">
                    <table class="table align-middle table-row-dashed fs-6 gy-3"
                           id="data">
                        <thead>
                        <tr class="fw-semibold fs-6 text-gray-800">
                            <th>{{trans('subscriptions_report.process_num')}}</th>
                            <th>{{trans('subscriptions_report.member')}}</th>
                            <th>{{trans('subscriptions_report.member_email')}}</th>
                            <th>{{trans('subscriptions_report.member_phone')}}</th>
                            <th>{{trans('subscriptions_report.subscription_type')}}</th>
                            <th>{{trans('subscriptions_report.subscription_name')}}</th>
                            <th>{{trans('subscriptions_report.price')}}</th>
                            <th>{{trans('subscriptions_report.added_date')}}</th>
                            <th>{{trans('subscriptions_report.start_date')}}</th>


                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

    </div>


@endsection
@section('js')


    <script>
        var editors = {};

        // Class definition
        var KTDatatablesServerSide = function () {
            // Shared variables
            var table;
            var dt;
            var filterPayment;

            // Private functions
            var initDatatable = function () {
                dt = $('#data').DataTable({
                    searchDelay: 500,
                    processing: true,
                    serverSide: true,
                    dom: "<'row'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4'f><'col-sm-12 col-md-4'B>>" +
                        "<'row'<'col-sm-12'tr>>" +
                        "<'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
                    ajax: {
                        url: "{{route('admin.subscriptions.Reports.MembersSubscriptions')}}",
                        data: function (d) {
                            d.member_id = $('#member_id').val();
                            d.subscription_id = $('#subscription_id').val();
                            d.type = $('#type').val();
                        }
                    },
                    columns: [

                        { data: 'process_num', className: 'text-center no-export' },
                        { data: 'member_name', className: 'text-center' },
                        { data: 'member_email', className: 'text-center', orderable: false },
                        { data: 'member_phone', className: 'text-center' , orderable: false},
                        { data: 'type', className: 'text-center' },
                        { data: 'subscription', className: 'text-center' },
                        { data: 'price', className: 'text-center', orderable: false },
                        { data: 'added_date', className: 'text-center' },
                        { data: 'start_date', className: 'text-center' },
                        // { data: 'actions', className: 'text-center no-export' },

                       /* {data: 'id', name: 'id'},
                        {data: 'name', name: 'name'},
                        {data: 'phone', name: 'phone'},
                        {data: 'address', name: 'address'},
                        {data: 'city', name: 'city', orderable: false},
                        {data: 'district', name: 'district', orderable: false},
                        {data: 'add_by', name: 'add_by', orderable: false},
                        {data: 'created_at', name: 'created_at'},
                        {data: 'location', name: 'location', orderable: false},*/
                    ],
                    order: [[0, 'desc']],
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
            // Filter Datatable
            var handleFilterDatatable = () => {
                // Select filter options
                const filterForm = document.querySelector('[data-kt-user-table-filter="form"]');
                const filterButton = filterForm.querySelector('[data-kt-user-table-filter="filter"]');
                const selectOptions = filterForm.querySelectorAll('select');
                console.log('search');
                // Filter datatable on submit
                filterButton.addEventListener('click', function () {
                    console.log('search');
                    dt.draw();

                    /* var filterString = '';

                     // Get filter values
                     selectOptions.forEach((item, index) => {
                         if (item.value && item.value !== '') {
                             if (index !== 0) {
                                 filterString += ' ';
                             }

                             // Build filter value options
                             filterString += item.value;
                         }
                     });

                     // Filter datatable --- official docs reference: https://datatables.net/reference/api/search()
                     dt.search(filterString).draw();*/

                })
            }

            // Reset Filter
            var handleResetForm = () => {
                // Select reset button
                const resetButton = document.querySelector('[data-kt-user-table-filter="reset"]');

                // Reset datatable
                resetButton.addEventListener('click', function () {
                    // Select filter options
                    const filterForm = document.querySelector('[data-kt-user-table-filter="form"]');
                    const selectOptions = filterForm.querySelectorAll('select');

                    // Reset select2 values -- more info: https://select2.org/programmatic-control/add-select-clear-items
                    selectOptions.forEach(select => {
                        $(select).val('').trigger('change');
                    });

                    // Reset datatable --- official docs reference: https://datatables.net/reference/api/search()
                    dt.search('').draw();
                });
            }


            // Public methods
            return {
                init: function () {

                    initDatatable();
                    handleFilterDatatable();
                    handleResetForm();

                }
            }
        }();
        // On document ready
        KTUtil.onDOMContentLoaded(function () {

            KTDatatablesServerSide.init();

        });

        function get_subscription(type,subscription_id) {
            console.log('subscription_id'+subscription_id);
            $.ajax({
                url: '{{route('admin.get-subscription')}}',
                type: 'get',
                data: {
                    type: type,
                },
                success: function (response) {
                    $('#subscription_id').empty();
                    $('#subscription_id').append('<option value=" ">{{ trans('forms.select') }}</option>');
                    var currentLocale = '{{ app()->getLocale() }}';
                    response.forEach(function (subscription) {
                        var name = subscription.name[currentLocale]; // Access the translation for the current locale
                        $('#subscription_id').append('<option value="' + subscription.id + '">' + name + '</option>');
                        if(subscription_id !=' ')
                        {
                            $('#subscription_id2').append('<option value="' + subscription.id + '">' + name + '</option>');
                            $('#subscription_id').val(subscription_id);
                        }

                    });

                },
                error: function (xhr, status, error) {
                    // Handle any errors here
                    console.error(error);
                }
            });
        }

    </script>

@endsection
