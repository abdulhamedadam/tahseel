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

                        </div>
                        <!--end::Container-->
                    </div>
                    <!--end::Wrapper-->
                    <!--begin::Body-->
                    <div class="p-0">
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



