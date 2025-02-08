     <!-- Modal --->

     @foreach  ($permission as $x)

     <div class="modal fade" tabindex="-1" id="kt_modal_1{{$x->id}}">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">{{trans('contactus.details')}}</h3>

                    <!--begin::Close-->
                    <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                         aria-label="Close">
                        <i class="ki-duotone ki-cross fs-1">
                            <span class="path1"></span><span class="path2"></span></i>
                    </div>
                    <!--end::Close-->
                </div>

                <div class="modal-body" id="load_div">
   <!--begin::Body-->
   <div class="p-0">
    <!--begin::Title-->
    <h3 class="fs-2qx fw-bold text-dark">{{$x->emp_id}}</h3>
    <span
        class="fs-5 fw-semibold text-gray-400">{{$x->date_permission}}</span>
    <!--end::Title-->
    <div class="d-flex align-items-center justify-content-between pb-4">
        <!--begin::Date-->
        <div class="text-gray-500 fs-5">
            <!--begin::Date-->
            <span class="me-2 fw-bold">Data </span>
            <!--end::Date-->
            <!--begin::Author-->
            <span class="fw-semibold">{{$x->start_permission}}</span>
            <!--end::Author-->
            <span class="me-2 fw-bold">Data</span>
            <!--end::Date-->
            <!--begin::Author-->
            <span class="fw-semibold">{{$x->end_permission}}</span>
            <!--end::Author-->
            <span class="me-2 fw-bold">Data</span>
            <!--end::Date-->
        </div>
        <!--end::Date-->
        <!--begin::Action-->
        <span class="text-gray-500 me-2 fw-bold fs-5">  </span>
        <!--end::Action-->
    </div>
    <!--begin::Text-->
    <div class="fs-5 fw-semibold text-gray-600 mt-4">
        {!!$x->reason !!}

    </div>
    <!--end::Text-->
    <!--end::Body-->
</div>
<!--end::Post content-->

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endforeach