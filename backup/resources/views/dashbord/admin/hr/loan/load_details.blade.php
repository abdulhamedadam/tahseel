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
                                    <td>{{trans('loan.Employee_ID')}}:-</td>
                                   <td style="color:black">{{$hr_loan->emp_id}}</td>
                                </tr>
                                <tr  class="fs-5 fw-semibold text-gray-400">
                                    <td>{{trans('loan.loan_type')}}:-</td>
                                    <td style="color:black">{{$hr_loan->loan_type}}</td>
                                </tr>
                                <tr  class="fs-5 fw-semibold text-gray-400">
                                    <td>{{trans('loan.date_loan')}}:-</td>
                                    <td style="color:black">{{$hr_loan->date_loan}}</td>
                                </tr>
                                <tr  class="fs-5 fw-semibold text-gray-400">
                                    <td>{{trans('loan.date_deductions')}}:-</td>
                                    <td style="color:black">{{$hr_loan->date_deductions}}</td>
                                </tr>
                                <tr  class="fs-5 fw-semibold text-gray-400">
                                    <td>{{trans('loan.value')}}:-</td>
                                    <td style="color:black">{{$hr_loan->value}}</td>
                                </tr>
                                <tr  class="fs-5 fw-semibold text-gray-400">
                                    <td>{{trans('loan.installments_num')}}:-</td>
                                    <td style="color:black">{{$hr_loan->installments_num}}</td>
                                </tr>
                               
                               
                                <tr  class="fs-5 fw-semibold text-gray-400">
                                    <td>{{trans('loan.Reason')}}:-</td>
                                    <td style="color:black">{{$hr_loan->reason}}</td>
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



