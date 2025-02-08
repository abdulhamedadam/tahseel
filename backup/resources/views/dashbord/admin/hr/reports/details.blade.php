@extends('dashbord.layouts.master')
@section('toolbar')
    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <!--begin::Title-->
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
               Show Data</h1>
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
                    <a href="{{ route('admin.hr.hr_reports.index') }}"
                       class="text-muted text-hover-primary">Hr</a>
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">
               Performance_Reports
                </li>


            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--begin::Actions-->
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <!--begin::Filter menu-->
            <div class="d-flex">
                <a href="{{route('admin.hr.hr_reports.index')}}"
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
            <!--begin::Post card-->
            <div class="card">
                <!--begin::Body-->
                <div class="card-body p-lg-10 pb-lg-0">
                    <!--begin::Layout-->
                    <div class="d-flex flex-column flex-xl-row">
                        <!--begin::Content-->
                        <div class="flex-lg-row-fluid me-xl-15">
                            <!--begin::Post content-->
                            <div class="mb-17">
                                <!--begin::Wrapper-->
                                <div class="mb-8">
                                    <!--begin::Container-->
                                    <div class="overlay mt-0">
                                     
                                        <!--begin::Links-->
                                    {{--  <div class="overlay-layer card-rounded bg-dark bg-opacity-25">
                                          <a href="../../demo1/dist/pages/about.html" class="btn btn-primary">About Us</a>
                                          <a href="../../demo1/dist/pages/careers/apply.html" class="btn btn-light-primary ms-3">Join Us</a>
                                      </div>--}}
                                    <!--end::Links-->
                                    </div>
                                    <!--end::Container-->
                                </div>


                                <!--end::Wrapper-->
                               
                                <!--begin::Body-->
                            <div class="fs-5 fw-semibold text-gray-600 mt-4" >
                                 
                                <table style=" border-collapse: collapse; width=50%">
                                  
                                    <tbody>
                                        <tr  class="fs-5 fw-semibold text-gray-400">
                                            <td>{{trans('reports.Employee_ID')}}:-</td>
                                           <td style="color:black">{{$hr_reports->emp_id}}</td>
                                        </tr>
                                     
                                        <tr  class="fs-5 fw-semibold text-gray-400">
                                            <td>{{trans('reports.date_report')}}:-</td>
                                            <td style="color:black">{{$hr_reports->date_report}}</td>
                                        </tr>
                                      
                                        <tr  class="fs-5 fw-semibold text-gray-400">
                                            <td>{{trans('reports.details')}}:-</td>
                                            <td style="color:black">{{$hr_reports->details}}</td>
                                        </tr>
                                    </tbody>       
                                   
                                </table>
                         
                                    
                                    <!--end::Text-->
                                    <!--end::Body-->
                                </div>
                                <!--end::Post content-->

                            </div>
                            <!--end::Content-->
                        </div>
                        <!--end::Layout-->

                    </div>

                    <!--end::Body-->
                </div>
                <!--end::Post card-->
            </div>
            <!--end::Content container-->
        </div>
        <!--end::Content-->
 @endsection

@section('js')

    <script src="{{asset('assets/plugins/custom/ckeditor/ckeditor-classic.bundle.js')}}"></script>

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>

    @endsection