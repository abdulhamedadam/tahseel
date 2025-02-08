@extends('dashbord.layouts.master')
@section('toolbar')
    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <!--begin::Title-->
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                {{trans('event.create')}}</h1>
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
                    {{trans('Toolbar.site')}}
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">
                    <a href="{{ route('admin.hr.reqholiday.index') }}"
                       class="text-muted text-hover-primary">Hr</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">
                    Holiday
                </li>


            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--begin::Actions-->
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <!--begin::Filter menu-->
            <div class="d-flex">
                <a href="{{route('admin.hr.reqholiday.index')}}"
                   class="btn btn-icon btn-sm btn-primary flex-shrink-0 ms-4">

                    <!--begin::Svg Icon | path: /var/www/preview.keenthemes.com/keenthemes/keen/docs/core/html/src/media/icons/duotune/arrows/arr054.svg-->
                    <span class="svg-icon svg-icon-2">
                                   <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                       <path
                                           d="M17.6 4L9.6 12L17.6 20H13.6L6.3 12.7C5.9 12.3 5.9 11.7 6.3 11.3L13.6 4H17.6Z"
                                           fill="currentColor"/>
                                   </svg>
                                </span>
                    <!--end::Svg Icon-->
                </a>
            </div>
            <!--end::Filter menu-->
            <!--begin::Secondary button-->
            <!--end::Secondary button-->
            <!--begin::Primary button-->
            <!--end::Primary button-->
        </div>
        <!--end::Actions-->

    </div>
    <!--end::Toolbar container-->
@endsection

@section('content')

    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxxl">
        <div class="row">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <form id="StorForm" class="form d-flex flex-column flex-lg-row"
                  action="{{route('admin.hr.reqholiday.store')}}" method="post" enctype="multipart/form-data">
            @csrf
            <!--begin::Aside column-->

                <!--end::Aside column-->
                <!--begin::Main column-->
                <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10 col">
                    <!--begin::General options-->
                    <div class="card card-flush py-4">
                        <!--begin::Card header-->
                        <div class="card-header">
                            <div class="card-title">
                                <h2>{{trans('reqholiday.Fill_Data')}}</h2>
                            </div>
                        </div>
                        <!--end::Card header-->
                        <!--begin::Card body-->
                        <div class="card-body pt-0">
                            <!--begin::Input group-->
                            <!-- beside eachother -->
                            <div class="row">
                                <div class="mb-10 fv-row col">
                                    <!--begin::Label-->
                                    <label class="required form-label">{{trans('reqholiday.Employee ID')}}

                                    </label>
                                    <!--end::Label-->

                                    <select name="emp_id" id="emp_id"
                                            class="form-select @error('emp_id') is-invalid @enderror"
                                            data-control="select2"
                                            data-allow-clear="true"
                                            data-placeholder="{{trans('maindata.Select')}}">

                                    </select>

                                    @error('emp_id')
                                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                                    @enderror


                                </div>
                                <div class=" col">
                                    <label class="required form-label">{{trans('Hr_setting.Type')}}</label>

                                    <!--begin::Select2-->
                                    <select class="form-select mb-2 @error('type_holiday_fk') is-invalid @enderror"
                                            onchange="/*set_status()*/"
                                            data-control="select2"
                                            data-placeholder="Select an option"
                                            id="type_holiday_fk" name="type_holiday_fk">
                                        @foreach($type_holiday as $row)
                                            <option value="{{ $row->id}}">{{ $row->title}}</option>
                                        @endforeach
                                    </select>
                                    <!--end::Select2-->
                                    @error('type_holiday_fk')
                                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Main column-->

                            </div>  <!--- end of row -->
                            <!--end::Input group-->
                            <!--begin::Input group-->

                            <div class="row">

                                <!--end::Input group-->
                                <div class="mb-10 fv-row col">
                                    <!--begin::Label-->
                                    <label class="required form-label">{{trans('reqholiday.Number_Of_Days')}}

                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input type="text" name="num_days" readonly id="day_num"
                                           class="form-control mb-2  @error('num_days') is-invalid @enderror"
                                           placeholder="Number of Days"/>
                                    <!--end::Input-->
                                    @error('num_days')
                                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                                    @enderror


                                </div>
                                <!--begin::Col-->
                                <div class="mb-10 fv-row col">
                                    <!--begin::Label-->
                                    <label
                                        class="required fs-6 fw-semibold mb-2">{{trans('reqholiday.Start_Date')}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input
                                        class="form-control form-control-solid @error('date_start') is-invalid @enderror"
                                        value="{{old('date_start')}}" name="date_start" onchange="calculateDays()"
                                        placeholder="Pick date rage" id="date_start"/>
                                    <!--end::Input-->
                                    @error('date_start')
                                    <div
                                        class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Col--> <!--begin::Col-->


                                <div class="mb-10 fv-row col">
                                    <!--begin::Label-->
                                    <label
                                        class="required fs-6 fw-semibold mb-2">{{trans('reqholiday.End_Date')}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input
                                        class="form-control form-control-solid @error('date_end') is-invalid @enderror"
                                        value="{{old('date_end')}}" name="date_end" onchange="calculateDays()"
                                        placeholder="Pick date rage" id="date_end"/>
                                    <!--end::Input-->
                                    @error('date_end')
                                    <div
                                        class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                                    @enderror


                                </div>
                                <!--end::Col-->

                            </div>

                            <!--begin::Input group-->
                            <div class="row">
                                <div class="mb-10 fv-row col">
                                    <!--begin::Label-->
                                    <label class="form-label">{{trans('reqholiday.Reason')}}
                                    </label>

                                    <textarea class="form-control @error('reason') is-invalid @enderror"
                                              data-kt-autosize="true"
                                              id="reason" name="reason"></textarea>
                                    @error('reason')
                                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Input group-->


                            </div>

                        </div>

                        <!--end::Card header-->
                    </div>
                    <!--end::General options-->


                    <div class="d-flex justify-content-end col">
                        <!--begin::Button-->
                        <button type="reset" class="btn btn-light me-5">{{trans('forms.cancel_btn')}}</button>
                        <!--end::Button-->
                        <!--begin::Button-->
                        <button type="submit" id="" class="btn btn-primary">
                            <span class="indicator-label">{{trans('forms.save_btn')}}</span>
                            <span class="indicator-progress">Please wait...
													<span
                                                        class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                        <!--end::Button-->
                    </div>
                </div>
                <!--end::Main column-->
            </form>
        </div>
    </div>


@endsection
@section('js')

    <script src="{{asset('assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js')}}"></script>

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

    {!! JsValidator::formRequest('App\Http\Requests\Site\Event\EventStoreRequest', '#StorForm') !!}
    <script>
        var KTAppBlogSave = function () {


            // Init daterangepicker
            const initSelectEmplyee = () => {
                $('#emp_id').select2({
                    ajax: {
                        url: '{{ route('admin.hr.getEmployees') }}',
                        type: "post",
                        dataType: 'json',
                        delay: 250,
                        data: function (params) {
                            return {
                                search: params.term,// search term
                                page: params.page || 1
                            };
                        }, processResults: function (data, params) {
                            params.page = params.page || 1;
                            var mappedData = $.map(data.data, function (item) {
                                return {id: item.id, text: item.name, imageUrl: item.imageUrl};
                            });
                            return {
                                results: mappedData,
                                pagination: {
                                    more: (params.page * 10) < data.total
                                }

                            };
                        },
                        cache: true
                    },
                    placeholder: 'Select an option',
                    minimumInputLength: 0
                });

                $('#search-input').on('keyup', function () {
                    $('#select2-dropdown').empty().trigger('change');
                });
            };
            // Init daterangepicker
            const initDaterangepicker = () => {

                $("#date_start").daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    autoApply: true,
                    minDate: "{{date('m/d/Y')}}",
                    minYear: 2024,
                    maxYear: parseInt(moment().format("YYYY"), 12)
                }, function (start, end, label) {
                    var years = moment().diff(start, 'years');
                    console.log("You are " + years + " years old!");
                    //  calculateDays();
                });


                $("#date_end").daterangepicker({
                        singleDatePicker: true,
                        showDropdowns: true,
                        minYear: 2024,
                        autoApply: true,
                        maxYear: parseInt(moment().format("YYYY"), 12)
                    }
                    , function (start, end, label) {
                        var years = moment().diff(start, 'years');
                        console.log("You are " + years + " years old!");
                        // calculateDays();
                    }
                );

                /*     $('#date_start').on('apply.daterangepicker', function(ev, picker) {

               calculateDays();
             });
             $('#date_end').on('apply.daterangepicker', function(ev, picker) {

               calculateDays();
             });*/


            }

            // Public methods
            return {
                init: function () {
                    initDaterangepicker();
                    initSelectEmplyee();
                }
            };
        }();
        // On document ready
        KTUtil.onDOMContentLoaded(function () {
            KTAppBlogSave.init();
        });

        function calculateDays() {
            var date_start = new Date(document.getElementById('date_start').value);
            var date_end = new Date(document.getElementById('date_end').value);
            console.log('date_start', date_start, 'date_end', date_end);
            console.log('start', document.getElementById('date_start').value, 'end', document.getElementById('date_end').value);

            // Calculate the difference in milliseconds
            var differenceMs = date_end - date_start;

            // Convert milliseconds to days
            var numberOfDays = Math.ceil(differenceMs / (1000 * 60 * 60 * 24));

            // Display the result
            console.log("Number of days: ", numberOfDays);
            document.getElementById('day_num').value = numberOfDays;

        }
    </script>
@endsection

