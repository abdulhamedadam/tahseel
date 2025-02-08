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
                    <a href="{{ route('admin.hr.hr_permission.index') }}"
                       class="text-muted text-hover-primary">Hr</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">
                    Permission
                </li>


            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--begin::Actions-->
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <!--begin::Filter menu-->
            <div class="d-flex">
                <a href="{{route('admin.hr.hr_permission.index')}}"
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
                  action="{{route('admin.hr.hr_permission.store')}}" method="post" enctype="multipart/form-data">
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
                                <h2>{{trans('permission.Fill_Data')}}</h2>
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
                                    <label class="required form-label">{{trans('permission.Employee_ID')}}

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
                                       <!--end::Input group-->
                <div class="mb-10 fv-row col">
                    <!--begin::Label-->
                    <label
                        class="required fs-6 fw-semibold mb-2">{{trans('permission.date_permission')}}</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input
                        class="form-control form-control-solid @error('date_permission') is-invalid @enderror"
                        value="{{old('date_permission')}}" name="date_permission"
                        placeholder="Pick date rage" id="date_permission"/>
                    <!--end::Input-->
                    @error('date_permission')
                    <div
                        class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror


                </div>
                <!--end::Col-->


                            </div>  <!--- end of row -->
                            <!--end::Input group-->
                            <!--begin::Input group-->

                            <div class="row">


                                <div class="mb-10 fv-row col">
                                    <!--begin::Label-->
                                    <label
                                        class="required fs-6 fw-semibold mb-2">{{trans('permission.start_permission')}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input  type="time"
                                     class="form-control form-control-solid @error('start_permission') is-invalid @enderror"
                                        value="{{old('start_permission')}}" name="start_permission" onchange="getTimeDifferenceInMinutes()"
                                        placeholder="" id="start_permission"/>
                                    <!--end::Input-->
                                    @error('start_permission')
                                    <div
                                        class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <!--end::Col--> <!--begin::Col-->


                                <div class="mb-10 fv-row col">
                                    <!--begin::Label-->
                                    <label
                                        class="required fs-6 fw-semibold mb-2">{{trans('permission.end_permission')}}</label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                    <input  type="time"
                                        class="form-control form-control-solid @error('end_permision') is-invalid @enderror"
                                        value="{{old('end_permision')}}" name="end_permission" onchange="getTimeDifferenceInMinutes()"
                                        placeholder="" id="end_permission"/>
                                    <!--end::Input-->
                                    @error('end_permission')
                                    <div
                                        class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                                    @enderror


                                </div>
                                <!--end::Col-->
                <div class="mb-10 fv-row col">
                  <!--begin::Label-->
                  <label class="required form-label">{{trans('permission.period')}}

                  </label>
                  <!--end::Label-->
                  <!--begin::Input-->
                <input type="text" name="period" id="period" readonly

             class="form-control mb-2  @error('period') is-invalid @enderror"
                         placeholder="period"/>
                  <!--end::Input-->
                  @error('period')
                  <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                  @enderror


                    </div>
<!--begin::Col-->

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

    {!! JsValidator::formRequest('App\Http\Requests\Hr\operation\PermissionRequest', '#StorForm') !!}
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


            };



            // Init daterangepicker
            const initDaterangepicker = () => {

                $("#date_permission").daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    autoApply: true,
                    minDate: "{{date('m/d/Y')}}",
                    minYear: 2024,
                    maxYear: parseInt(moment().format("YYYY"), 12)
                });

            }

            // Public methods
            return {
                init: function () {
                    initDaterangepicker();
                    initSelectEmplyee();
                }
            };


        }();



        function getTimeDifferenceInMinutes() {
            var time1 = $('#start_permission').val();
            var time2 = $('#end_permission').val();
            if(time1&&time2){
                console.log('satrt:',time1,'end:',time2);
    // Parse input times into Date objects
    var startTime = new Date('1970-01-01T' + time1 + 'Z');
    var endTime = new Date('1970-01-01T' + time2 + 'Z');
    console.log('startTime:',startTime,'endTime:',endTime);

    // Calculate difference in milliseconds
    var timeDiff = endTime - startTime;

    // Convert milliseconds to minutes
    var diffMinutes = Math.round((timeDiff / 1000) / 60);
if (diffMinutes <=0) {
    Swal.fire({
  title: "من فضلــك ادخل فتره ذمنيه صحيحه",
  icon: "warning",
  iconHtml: "!",
  confirmButtonText: "تم",
  showCloseButton: true
});
$('#period').val(0);
$('#start_permission').val(' ');
            $('#end_permission').val(' ');
return false;
}

            }else{
                var diffMinutes =0;
            }
    console.log('diffMinutes:',diffMinutes);
    $('#period').val(diffMinutes);
    // return diffMinutes;
}



        function Calculatetime() {
            var start = parseInt(document.getElementById('start_permission').value);
            var end = parseInt(document.getElementById('end_permission').value);
            var starthour=parseInt(start[0]);
            var startmin=parseInt(start[1]);
            var endhour=parseInt(end[0]);
            var endmin=parseInt(end[1]);
            var totalstartmin=starthour *60 +startmin;
            var totalendmin=endhour *60 +endmin;
            var diff=totalendmin - totalstartmin;
           // var finish=Math.ceil(diff /(1000 * 60));

          //  console.log('start_permission', start, 'end_permission', end);

            console.log('start', document.getElementById('start_permission').value, 'end', document.getElementById('end_permission').value);

            // Calculate the difference in milliseconds
            var differenceMs = end - start;
            // Convert milliseconds to days
            var res = Math.ceil(differenceMs / (1000 * 60));

            // Display the result
          //  console.log("start",start);
            console.log("totalstartmin",totalstartmin,"totalendmin",totalendmin);
            console.log("result=: ", res);
            console.log('diff=',diff);
          //  console.log('finish',finish);
            document.getElementById('period').value = res;

        }




        // On document ready
        KTUtil.onDOMContentLoaded(function () {
            KTAppBlogSave.init();
        });



    </script>
@endsection

