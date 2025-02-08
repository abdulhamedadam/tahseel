<!DOCTYPE html>
@php
    $mainData=getMainData();
@endphp
<html lang="en">
<!--begin::Head-->
<head>
    <base href="../../"/>
    <title>{{(!empty($mainData->name)) ? $mainData->name : 'yourhome'}}</title>
    <meta charset="utf-8"/>
    <meta name="description"
          content="The most advanced Bootstrap Admin Theme on Bootstrap Market trusted by over 4,000 beginners and professionals. Multi-demo, Dark Mode, RTL support. Grab your copy now and get life-time updates for free."/>
    <meta name="keywords"
          content="keen, bootstrap, bootstrap 5, bootstrap 4, admin themes, web design, figma, web development, free templates, free admin themes, bootstrap theme, bootstrap template, bootstrap dashboard, bootstrap dak mode, bootstrap button, bootstrap datepicker, bootstrap timepicker, fullcalendar, datatables, flaticon"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta property="og:locale" content="en_US"/>
    <meta property="og:type" content="article"/>
    <meta property="og:title" content="Keen - Multi-demo Bootstrap 5 HTML Admin Dashboard Theme"/>
    <meta property="og:url" content="https://keenthemes.com/keen"/>
    <meta property="og:site_name" content="Keenthemes | Keen"/>
    @include('dashbord.layouts.head')
</head>
<body>
<!--begin::Theme mode setup on page load-->
<!--end::Theme mode setup on page load-->
<!--begin::Root-->
<div class="d-flex flex-column flex-root" id="kt_app_root">
    <!--begin::Authentication - Sign-in -->
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
        <!--begin::Aside-->
        <div class="d-flex flex-lg-row-fluid w-lg-50 bgi-size-cover bgi-position-center"
             style="background-image: url({{URL::asset('assets/media/misc/٢٣.png')}})">
            <!--begin::Content-->
            <div class="d-flex flex-column flex-center p-6 p-lg-10 w-100">
                <!--begin::Logo-->

                <!--end::Logo-->
                <!--begin::Image-->
                <img class="d-none d-lg-block mx-auto w-300px w-lg-75 w-xl-500px mb-10 mb-lg-20"
                     src="{{asset('assets/media/auth/palm-recognition.png')}}" alt=""/>
                <!--end::Image-->

            </div>
            <!--end::Content-->
        </div>
        <!--begin::Aside-->
        <!--begin::Body-->
        <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10">
            <!--begin::Form-->
            <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                <!--begin::Wrapper-->
                <div class="w-lg-500px p-10">
                    <!--begin::Form-->
                    <form method="POST" action="{{ route('admin.login') }}" class="form w-100">
                    @csrf
                    <!--begin::Heading-->
                        <div class="text-center mb-11">
                            <!--begin::Title-->
                            <h1 class="text-dark fw-bolder mb-3">Sign In</h1>
                            <!--end::Title-->

                        </div>
                        <!--begin::Heading-->

                        <!--begin::Input group=-->
                        <div class="fv-row mb-8">
                            <!--begin::Email-->
                            <input type="text" placeholder="Email" name="email" autocomplete="off"
                                   class="form-control bg-transparent"/>
                            <!--end::Email-->
                        </div>
                        <!--end::Input group=-->
                        <div class="fv-row mb-3">
                            <!--begin::Password-->
                            <input type="password" placeholder="Password" name="password" autocomplete="off"
                                   class="form-control bg-transparent"/>
                            <!--end::Password-->
                        </div>
                        <!--end::Input group=-->
                    @if (Route::has('admin.password.request'))

                        <!--begin::Wrapper-->
                            <div class="d-flex flex-stack flex-wrap gap-3 fs-base fw-semibold mb-8">
                                <div></div>
                                <!--begin::Link-->
                                <a href="{{ route('admin.password.request') }}" class="link-primary">Forgot Password
                                    ?</a>
                                <!--end::Link-->
                            </div>
                            <!--end::Wrapper-->
                    @endif
                    <!--begin::Submit button-->
                        <div class="d-grid mb-10">
                            <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                                <!--begin::Indicator label-->
                                <span class="indicator-label">Sign In</span>
                                <!--end::Indicator label-->
                                <!--begin::Indicator progress-->
                                <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                <!--end::Indicator progress-->
                            </button>
                        </div>
                        <!--end::Submit button-->
                        <!--begin::Sign up-->
                        @if (Route::has('admin.register'))

                            <div class="text-gray-500 text-center fw-semibold fs-6">Not a Member yet?
                                <a href="{{route('admin.register')}}"
                                   class="link-primary">Sign up</a>

                            <!--  <a href="{{url('signup')}}" class="link-primary">Sign Up -->
                            </div>
                    @endif
                    <!--end::Sign up-->
                    </form>
                    <!--end::Form-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--end::Form-->

        </div>
        <!--end::Body-->
    </div>
    <!--end::Authentication - Sign-in-->
</div>
<!--end::Root-->
@include('dashbord.layouts.footer-scripts')
<!--begin::Javascript-->
<script>var hostUrl = "{{URL::asset('assets')}}/";</script>
<!--begin::Global Javascript Bundle(mandatory for all pages)-->
<script src="{{URL::asset('assets/plugins/global/plugins.bundle.js')}}"></script>
<script src="{{URL::asset('assets/js/scripts.bundle.js')}}"></script>
<!--end::Global Javascript Bundle-->
<!--begin::Custom Javascript(used for this page only)-->
<script src="{{URL::asset('assets/js/custom/authentication/sign-in/general.js')}}"></script>
<!--end::Custom Javascript-->
<!--end::Javascript-->

</body>
</html>
