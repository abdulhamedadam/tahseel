<!DOCTYPE html>
{{-- @php
    $mainData=getMainData();
@endphp --}}
<html lang="en">
<!--begin::Head-->
<head>
    <base href="../../"/>
    {{-- <title>{{(!empty($mainData->name)) ? $mainData->name : 'yourhome'}}</title> --}}
    <title>Login</title>
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

        <!--begin::Aside-->
        <!--begin::Body-->
        <div class="d-flex flex-column flex-lg-row-fluid w-lg-50 p-10">
            <div class="d-flex flex-center flex-column flex-lg-row-fluid">
                <div class="w-lg-500px">
                    <div class="card shadow-sm">
                        <div class="card-body p-10">
                            <form method="POST" action="{{ route('admin.login') }}" class="form w-100">
                                @csrf
                                <div class="text-center mb-4">
                                    <h1 class="text-dark fw-bolder mb-3">Sign In</h1>
                                </div>

                                <div class="mb-3">
                                    <input type="text" placeholder="Email" name="email" autocomplete="off"
                                           class="form-control bg-transparent"/>
                                </div>

                                <div class="mb-3">
                                    <input type="password" placeholder="Password" name="password" autocomplete="off"
                                           class="form-control bg-transparent"/>
                                </div>

                                @if (Route::has('admin.password.request'))
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div></div>
                                        <a href="{{ route('admin.password.request') }}" class="link-primary">Forgot Password?</a>
                                    </div>
                                @endif

                                <div class="d-grid mb-3">
                                    <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">
                                        <span class="indicator-label">Sign In</span>
                                        <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                </span>
                                    </button>
                                </div>

                                @if (Route::has('admin.register'))
                                    <div class="text-center text-gray-500 fw-semibold fs-6">
                                        Not a Member yet?
                                        <a href="{{route('admin.register')}}" class="link-primary">Sign up</a>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
