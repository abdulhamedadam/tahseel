@extends('dashbord.layouts.master')

@section('content')
    <!-- Modal 1-->
    <div class="modal fade" id="exampleModal" tabindex="-1" 
    aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{route('admin.hr.reqholiday.store')}}" method="POST" id="kt_ecommerce_add_product_form"
                  class="form d-flex flex-column flex-lg-row my-form" enctype="multipart/form-data">
                {{csrf_field()}}
                <input type="hidden" name="id" value="0">

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{trans('Hr_setting.Add')}}</h5>
                    </div>
                    <!--begin::Formmmmm-->

                    <div class="modal-body">
                        <div class="container-fluid">


                            <div class="row">
                                <label class="required form-label">{{trans('Hr_setting.Employee ID')}}</label>

                                <input type="text" name="emp_id" class="form-control mb-2"
                                       placeholder="ID" value="" required autocomplete/>
                            </div>
                            <div class="row">
                                <label class="required form-label">{{trans('Hr_setting.Holiday Type')}}(<span
                                        class="text-gray-600">{{trans('forms.lable_ar')}}</span>)</label>

                                <input type="text" name="type_holiday_ar" class="form-control mb-2"
                                       placeholder="نوع الاجــازة" value="" required autocomplete/>
                            </div>
                            <div class="row">
                                <label class="required form-label">{{trans('Hr_setting.Holiday Type')}}(<span
                                        class="text-gray-600">{{trans('forms.lable_en')}}</span>)</label>

                                <input type="text" name="type_holiday_en" class="form-control mb-2"
                                       placeholder="holiday type" value="" required autocomplete/>
                            </div>


                            <div class="input-group" id="kt_td_picker_button" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                <input id="kt_td_picker_modal_input" type="text" class="form-control" data-td-target="#kt_td_picker_modal"/>
                                <span class="input-group-text" data-td-target="#kt_td_picker_modal" data-td-toggle="datetimepicker">
                                    <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                </span>
                            </div>


                     <!--           <button id="kt_td_picker_button" class="btn btn-flex flex-center btn-primary">
                                    Pick date & time
                                    <i class="ki-duotone ki-calendar-8 fs-2 ms-3 pe-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                                </button>
                            -->
                            <div class="row">
                                <div class="col-sm-6">
                                    <label for="kt_td_picker_linked_1_input" class="form-label">From</label>
                                    <div class="input-group log-event" id="kt_td_picker_linked_1" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                        <input id="kt_td_picker_linked_1_input" type="text" class="form-control" data-td-target="#kt_td_picker_linked_1"/>
                                        <span class="input-group-text" data-td-target="#kt_td_picker_linked_1" data-td-toggle="datetimepicker">
                                            <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                        </span>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label for="kt_td_picker_linked_2_input" class="form-label">To</label>
                                    <div class="input-group log-event" id="kt_td_picker_linked_2" data-td-target-input="nearest" data-td-target-toggle="nearest">
                                        <input id="kt_td_picker_linked_2_input" type="text" class="form-control" data-td-target="#kt_td_picker_linked_2"/>
                                        <span class="input-group-text" data-td-target="#kt_td_picker_linked_2" data-td-toggle="datetimepicker">
                                            <i class="ki-duotone ki-calendar fs-2"><span class="path1"></span><span class="path2"></span></i>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <label class="required form-label">{{trans('Hr_setting.Status')}}</label>

                               <!--begin::Select2-->
                               <select class="form-select mb-2 @error('status') is-invalid @enderror"
                               onchange="/*set_status()*/"
                               data-control="select2" data-hide-search="true"
                               data-placeholder="Select an option"
                               id="status" name="status">
                                 <?php
                                 $status_array = array('0' => 'NotActive', '1' => 'Active')
                                 ?>
                                 <option></option>
                                 @foreach($status_array as $key=>$value)
                                     <option value="{{ $key }}">{{ $value }}
                                    </option>
                                 @endforeach



                             </select>
                       <!--end::Select2-->
                            </div>
                            <!--end::Main column-->
                            {{-- {{dd($type)}} --}}

                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">
                            <span class="indicator-label">{{trans('settings.Save Changes')}}</span>
                            <span class="indicator-progress">Please wait...
            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                        </button>
                        <button type="button" class="btn btn-secondary"
                                data-bs-dismiss="modal">{{trans('settings.Close')}}</button>

                    </div>


                </div>
            </form>
        </div>

    </div>

    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
            <!--begin::Toolbar container-->
            <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
                <!--begin::Page title-->
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <!--begin::Title-->
                    <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                {{trans('Hr_setting.Settings')}} </h1>
                    <!--end::Title-->
                    <!--begin::Breadcrumb-->
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <!--begin::Item-->
                        <li class="breadcrumb-item text-muted">
                            <a href="{{route('admin.dashboard')}}" class="text-muted text-hover-primary">{{trans('Hr_setting.Home')}}</a>
                        </li>

                        <li class="breadcrumb-item">
                            <span class="bullet bg-gray-400 w-5px h-2px"></span>
                        </li>

                    </ul>

                </div>
                <div class="d-flex align-items-center gap-2 gap-lg-3">

                </div>
                <!--end::Actions-->
            </div>
            <!--end::Toolbar container-->
        </div>

        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-xxl">
                <!--begin::Category-->
                <div class="card card-flush">
                    <!--begin::Card header-->
                    <div class="card-header align-items-center py-3 gap-2 gap-md-1">
                        <!--begin::Card title-->
                        <div class="card-title">

                        </div>
                        <!--end::Card title-->

                        @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                        <div class="card-toolbar">
                            <!--begin::Add customer-->
                        {{--                            <a href="../../demo1/dist/apps/ecommerce/catalog/add-category.html" class="btn btn-primary">Add Category</a>--}}

                        <!------ button model  ---------->
                            <button type="button"

                                    class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModal">
                              {{trans('Hr_setting.Add')}}
                            </button>
                            <!--end::Add customer-->
                        </div>

                    </div>

                    <div class="card-body pt-0">
                        <!--begin::Table-->
                        <table id="kt_datatable_zero_configuration"
                               class="table align-middle table-row-dashed fs-6 gy-3">
                            <!--begin::Table head-->
                            <thead>

                            <tr class="text-start text-gray-400 fw-bold fs-7 text-uppercase gs-0">
                                <th class="min-w-250px">#</th>

                                <th class="min-w-200px">{{trans('Hr_setting.Title')}}</th>
                                <th class="min-w-200px">{{trans('Hr_setting.Type')}}</th>
                                <th class="min-w-200px">{{trans('Hr_setting.Status')}}</th>

                                <th class="text-end min-w-70px">{{trans('Hr_setting.Action')}}</th>
                            </tr>
                            <!--end::Table row-->
                            </thead>
                            <!--end::Table head-->
                            <!--begin::Table body-->
                            <tbody class="fw-semibold text-gray-600">
                            <!--begin::Table row-->
                            @php
                                $i=1;
                            @endphp
                            @foreach  ($hr_holiday as $x)
                                <tr>
                                    <td>{{$i++}}</td>

                                    <td>{{$x->title}}</td>

                                    <td>
                                        {{-- @if (isset($x->typedata)&&(!empty($x->typedata)))
                                        {{$x->typedata->name}}
                                        @endif

                                        {{optional($x->typedata)->name}} --}}
                                        </td>
                                    {{-- {{$x->status_array}} --}}
                                    <td>{{$x->status}}</td>

                                    <!--begin::Action=-->
                                    <td class="text-end">

                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <a href="{{route('admin.hr.reqholiday.edit', $x->id)}}"

                                                                   data-bs-toggle="modal" data-bs-target="#exampleModal{{$x->id}}"
                                                                   class="btn btn-sm btn-light-warning  btn-icon-warning btn-text-warning"><i class="fas fa-pencil"></i></a>
                                                                    </a>

                                                                     <a href="{{route('admin.hr.reqholiday.delete', $x->id)}}"
                                                                    class="btn btn-sm btn-light-danger   btn-text-danger btn-icon-danger"><i class="fas fa-trash"></i></a>

                                                                     </div>
                                    </td>
                                    <!--end::Action=-->
                                </tr>

                            @endforeach

                            </tbody>

                        </table>
                        <!--end::Table-->

                    @foreach  ($hr_holiday as $x)
                        <!--Update Modal 1-->
                            <div class="modal fade" id="exampleModal{{$x->id}}" tabindex="-1"
                                 aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                  <!--begin::Formmmmm-->
                                     <form action="{{route('admin.hr.reqholiday.update',$x->id)}}" method="POST"
                                          id="kt_ecommerce_add_product_form "
                                          class="form d-flex flex-column flex-lg-row my-form"
                                          enctype="multipart/form-data">
                                        @method('PUT')
                                        {{csrf_field()}}
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">
                                                  {{trans('Hr_setting.Update')}}</h5>
                                            </div>


                                            <div class="modal-body">
                                                <div class="container-fluid">

                                                    <input type="hidden" name="id" value="{{$x->id}}">
                                                    @php
                                                        $title=$x->getTranslations('title'); //return local lang
                                                    @endphp
                                                    <div class="row">
                                                        <label
                                                            class="required form-label">{{trans('Hr_setting.Title')}}
                                                            (<span
                                                                class="text-gray-600">{{trans('forms.lable_en')}}</span>)</label>

                                                        <input type="text" name="title_en" class="form-control mb-2"
                                                               placeholder="Title" value="{{$title['en']}}"
                                                               required autocomplete/>
                                                    </div>
                                                    <div class="row">
                                                        <label
                                                            class="required form-label">{{trans('Hr_setting.Title')}}
                                                            (<span
                                                                class="text-gray-600">{{trans('forms.lable_ar')}}</span>)</label>

                                                        <input type="text" name="title_ar" class="form-control mb-2"
                                                               placeholder="العنــوان" value="{{$title['ar']}}"
                                                               required autocomplete/>
                                                    </div>
                                                    <div class="row">
                                                        <label class="required form-label">{{trans('Hr_setting.Status')}}(<span
                                                            class="text-gray-600">{{trans('forms.lable_en')}}</span>)</label>

                                                       <!--begin::Select2-->
                                                       <select class="form-select mb-2 @error('status') is-invalid @enderror"
                                                       onchange="/*set_status()*/"
                                                       data-control="select2" data-hide-search="true"
                                                       data-placeholder="Select an option"
                                                       id="status" name="status">
                                                         <?php
                                                         $status_array = array('0' => 'NotActive', '1' => 'Active')
                                                         ?>
                                                         <option></option>
                                                         @foreach($status_array as $key=>$value)
                                                             <option value="{{ $key }}"
                                                             @if ($key==$x->status)
                                                             {{'selected'}}

                                                             @endif
                                                             >{{ $value }}
                                                            </option>
                                                         @endforeach



                                                     </select>
                                               <!--end::Select2-->
                                                    </div>
                                                    <!--end::Main column-->
                                                    {{-- {{dd($type)}} --}}
                                                    <div class="row">
                                                        <label class="required form-label">{{trans('Hr_setting.Type')}}</label>

                                                       <!--begin::Select2-->
                                                       <select class="form-select mb-2 @error('status') is-invalid @enderror"
                                                       onchange="/*set_status()*/"
                                                       data-control="select2" data-hide-search="true"
                                                       data-placeholder="Select an option"
                                                       id="type" name="type">

                                                         <option></option>
                                                   {{--    @foreach($type as $row)
                                                             <option value="{{ $row->id}}"
                                                                @if ($row->id==$x->type)
                                                                {{'selected'}}

                                                                @endif
                                                                >{{ $row->name}}</option>
                                                         @endforeach  --}}
                                                     </select>
                                               <!--end::Select2-->
                                                    </div>




                                                    <!--end::Button-->

                                                    <!--end::Main column-->
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">
                                                    <span
                                                        class="indicator-label">{{trans('settings.Save Changes')}}</span>
                                                        <span class="indicator-progress">Please wait...
                                    <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                                    </button>
                                                    <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">
                                                        {{trans('settings.Close')}}</button>

                                                </div>

                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endforeach

                    </div>
                    <!--end::Card body-->
                </div>
                <!--end::Category-->
            </div>
            <!--end::Content container-->
        </div>
    </div>

@stop
@section('js')
    <script>
        /*
                $("#kt_datatable_zero_configuration").DataTable();
        */

        $("#kt_datatable_zero_configuration").DataTable({
            "language": {
                "lengthMenu": "Show _MENU_",
            },
            "dom":
                "<'row'" +
                "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
                "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
                ">" +

                "<'table-responsive'tr>" +

                "<'row'" +
                "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
                "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
                ">"
        });




          // Init daterangepicker
          const initDaterangepicker = () => {

         $("#date_at").daterangepicker({
        singleDatePicker: true,
        showDropdowns: true,
        minYear: 2000,
        maxYear: parseInt(moment().format("YYYY"), 12)
             }
       );
    }
         // Init Dropzone



/********************** Date Picker ****************/
         new tempusDominus.TempusDominus(document.getElementById("kt_td_picker_button"), {

});


/**********************To _ From _ Date*******************/
const linkedPicker1Element = document.getElementById("kt_td_picker_linked_1");
const linked1 = new tempusDominus.TempusDominus(linkedPicker1Element);
const linked2 = new tempusDominus.TempusDominus(document.getElementById("kt_td_picker_linked_2"), {
    useCurrent: false,
});

//using event listeners
linkedPicker1Element.addEventListener(tempusDominus.Namespace.events.change, (e) => {
    linked2.updateOptions({
        restrictions: {
        minDate: e.detail.date,
        },
    });
});

//using subscribe method
const subscription = linked2.subscribe(tempusDominus.Namespace.events.change, (e) => {
    linked1.updateOptions({
        restrictions: {
        maxDate: e.date,
        },
    });
});

/***********************************/








    </script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
  {{--  {!! JsValidator::formRequest('App\Http\Requests\EditeCityRequest', '.my-form'); !!}  --}}

{{--
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\EditeCarBrandRequest', '.my-form'); !!} --}}
@endsection
