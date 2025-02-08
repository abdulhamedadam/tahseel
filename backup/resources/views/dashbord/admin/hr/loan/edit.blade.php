@extends('dashbord.layouts.master')
@section('toolbar')
    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <!--begin::Title-->
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
               Update</h1>
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
                    <a href="{{ route('admin.hr.hr_loan.index') }}"
                       class="text-muted text-hover-primary">Hr</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">
                  Loan
                </li>


            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--begin::Actions-->
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <!--begin::Filter menu-->
            <div class="d-flex">
                <a href="{{route('admin.hr.hr_loan.index')}}"
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
                  action="{{route('admin.hr.hr_loan.update',$hr_loan->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="_method" value="PATCH"/>

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
                                    <label class="required form-label">{{trans('loan.Employee_ID')}}

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

                                <div class="mb-10 fv-row col">
                                    <!--begin::Label-->
                                    <label class="required form-label">{{trans('loan.loan_type')}}

                                    </label>
                                    <!--end::Label-->

                                    <select name="loan_type" id="loan_type" 
                                            class="form-select @error('loan_type') is-invalid @enderror"
                                            data-control="select" onchange="set_type()"
                                            data-allow-clear="true"
                                            data-placeholder="{{trans('maindata.Select')}}">
                                            <?php
                                            $type_array = array('0' => 'advance payment', '1' => 'loan')
                                            ?>
                                            @foreach($type_array as $key=>$value)
                                                <option value="{{ $key }}">{{ $value }}</option>
                                            @endforeach
                                    </select>

                                    @error('e')
                                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                                    @enderror


                                </div>
                                <div class="mb-10 fv-row col">
                                    <!--begin::Label-->
                                    <label class="required form-label">{{trans('loan.value')}}
                  
                                    </label>
                                    <!--end::Label-->
                                    <!--begin::Input-->
                                  <input type="text" name="value" id="value"   value="{{$hr_loan->value}}"
                            
                               class="form-control mb-2  @error('value') is-invalid @enderror"
                                           placeholder="value"/>
                                    <!--end::Input-->
                                    @error('value')
                                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                                    @enderror
                  
                  
                                      </div>


                            </div>

<!-------------------------------------------------------------------------->
                                      
<div class="row" >                     <!--end::Input group-->
                <div class="mb-10 fv-row col">
                    <!--begin::Label-->
                    <label
                        class="required fs-6 fw-semibold mb-2">{{trans('loan.date_loan')}}</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input
                        class="form-control form-control-solid @error('date_loan') is-invalid @enderror"
                        value="{{old('date_loan')}}" name="date_loan" 
                        placeholder="Pick date rage" id="date_loan"/>
                    <!--end::Input-->
                    @error('date_loan')
                    <div
                        class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror


                </div>
                <div class="mb-10 fv-row col">
                    <!--begin::Label-->
                    <label
                        class="required fs-6 fw-semibold mb-2">{{trans('loan.date_deductions')}}</label>
                    <!--end::Label-->
                    <!--begin::Input-->
                    <input
                        class="form-control form-control-solid @error('date_deductions') is-invalid @enderror"
                        value="{{$hr_loan->date_deductions}}" name="date_deductions" 
                        placeholder="Pick date rage" id="date_deductions"/>
                    <!--end::Input-->
                    @error('date_deductions')
                    <div
                        class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror


                </div>
                <div class="mb-10 fv-row col">
                    <!--begin::Label-->
                    <label class="required form-label">{{trans('loan.installments_num')}}
  
                    </label>
                    <!--end::Label-->
                    <!--begin::Input-->
                  <input type="text" name="installments_num" id="installments_num" 
                  value="{{$hr_loan->installments_num}}"
            
               class="form-control mb-2  @error('value') is-invalid @enderror"
                           placeholder="installments_num"/>
                    <!--end::Input-->
                    @error('installments_num')
                    <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                    @enderror
  
  
                      </div>

</div>
<!------------------------------------------------------------------------------>
<div class="row">

                            <!--begin::Input group-->
                            <div class="row">
                                <div class="mb-10 fv-row col">
                                    <!--begin::Label-->
                                    <label class="form-label">{{trans('loan.Reason')}}
                                    </label>

                                    <textarea class="form-control @error('reason') is-invalid @enderror"
                                              data-kt-autosize="true"
                                              id="reason" name="reason">{{$hr_loan->reason}}</textarea>
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
    {!! JsValidator::formRequest('App\Http\Requests\Hr\operation\LoanRequest', '#StorForm') !!}


    <script>
        var KTAppBlogSave = function () {


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

                $("#date_loan").daterangepicker({
                    singleDatePicker: true,
                    showDropdowns: true,
                    autoApply: true,
                    minDate: "{{date('m/d/Y')}}",
                    minYear: 2024,
                    maxYear: parseInt(moment().format("YYYY"), 12)
                }),
                $("#date_deductions").daterangepicker({
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
                    set_type();
                }
            };
        
        
        }();
        


    

  
        function set_type() {
            var type = $('#kt_ecommerce_add_category_status');
            var type_val = $('#loan_type').val();
            if (type_val == 0) {
                type.removeClass('bg-success').addClass('bg-danger');
            } else if (status_val == 1) {
                status.removeClass('bg-danger').addClass('bg-success');
            }
        }
   
       


        
        // On document ready
        KTUtil.onDOMContentLoaded(function () {
            KTAppBlogSave.init();
        });

     

    </script>
@endsection

