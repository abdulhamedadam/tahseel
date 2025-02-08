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
                            <!--begin::Image-->


                            <!--end::Image-->
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
                    <div class="p-0">
                        <table style=" border-collapse: collapse; width=50%">

                            <tbody>
                                <tr  class="fs-5 fw-semibold text-gray-400">
                                    <td>{{trans('permission.Employee_ID')}}:-</td>
                                   <td style="color:black">{{$permission->emp_id}}</td>
                                </tr>
                                <tr  class="fs-5 fw-semibold text-gray-400">
                                    <td>{{trans('permission.date_permission')}}:-</td>
                                    <td style="color:black">{{$permission->date_permission}}</td>
                                </tr>
                                <tr  class="fs-5 fw-semibold text-gray-400">
                                    <td>{{trans('permission.start_permission')}}:-</td>
                                    <td style="color:black">{{$permission->start_permission}}</td>
                                </tr>
                                <tr  class="fs-5 fw-semibold text-gray-400">
                                    <td>{{trans('permission.end_permission')}}:-</td>
                                    <td style="color:black">{{$permission->end_permission}}</td>
                                </tr>
                                <tr  class="fs-5 fw-semibold text-gray-400">
                                    <td>{{trans('permission.period')}}:-</td>
                                    <td style="color:black">{{$permission->period}} minutes</td>
                                </tr>
                                <tr  class="fs-5 fw-semibold text-gray-400">
                                    <td>{{trans('permission.Reaso')}}:-</td>
                                    <td style="color:black">{{$permission->reason}}</td>
                                </tr>
                            </tbody>
                        </table>

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



