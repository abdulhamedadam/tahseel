@extends('dashbord.layouts.master')
@section('toolbar')
    <!--begin::Toolbar container-->
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <!--begin::Page title-->
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <!--begin::Title-->
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">
                {{trans('about.create')}}</h1>
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
                    {{trans('Toolbar.hr')}}
                </li>
                <li class="breadcrumb-item">
                    <span class="bullet bg-gray-400 w-5px h-2px"></span>
                </li>
                <li class="breadcrumb-item text-muted">
                    {{trans('Toolbar.setting')}}
                </li>


            </ul>
            <!--end::Breadcrumb-->
        </div>
        <!--begin::Actions-->
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <!--begin::Filter menu-->
            <div class="d-flex">
                <button type="button" data-bs-toggle="modal" data-bs-target="#exampleModal"
                        class="btn btn-icon btn-sm btn-success flex-shrink-0 ms-4">
                    <!--begin::Svg Icon | path: icons/duotune/arrows/arr075.svg-->
                    <span class="svg-icon svg-icon-2">
													<svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                                         xmlns="http://www.w3.org/2000/svg">
														<rect opacity="0.5" x="11.364" y="20.364" width="16" height="2"
                                                              rx="1" transform="rotate(-90 11.364 20.364)"
                                                              fill="currentColor"/>
														<rect x="4.36396" y="11.364" width="16" height="2" rx="1"
                                                              fill="currentColor"/>
													</svg>
												</span>
                    <!--end::Svg Icon-->
                </button>
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
    <!-- Modal 1-->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{route('admin.hr.mainsetting.store')}}" method="POST"
                  class="form d-flex flex-column flex-lg-row my-form" enctype="multipart/form-data">
                {{csrf_field()}}

                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">{{trans('Hr_setting.Add')}}</h5>
                    </div>
                    <!--begin::Formmmmm-->

                    <div class="modal-body">
                        <div class="container-fluid">

                            {{-- {{dd($type)}} --}}
                            <div class="row">
                                <label class="required form-label">{{trans('Hr_setting.Type')}}</label>

                                <!--begin::Select2-->
                                <select class="form-select mb-2 @error('type_code') is-invalid @enderror"
                                        onchange="/*set_status()*/"
                                        data-control="select2" data-hide-search="false"
                                        data-dropdown-parent="#exampleModal" data-placeholder="Select an option" data-allow-clear="true"
                                        id="type_code" name="type_code">

                                    <option value=" ">{{trans('maindata.Select')}}</option>
                                    @foreach($type as $row)
                                        <option value="{{ $row->code}}">{{ $row->title}}</option>
                                    @endforeach
                                </select>
                                <!--end::Select2-->
                            </div>
                            <div class="row">
                                <label class="required form-label">{{trans('Hr_setting.Title')}}(<span
                                        class="text-gray-600">{{trans('forms.lable_en')}}</span>)</label>

                                <input type="text" name="title_en" class="form-control mb-2"
                                       placeholder="title" value="" required autocomplete/>
                            </div>
                            <div class="row">
                                <label class="required form-label">{{trans('Hr_setting.Title')}}(<span
                                        class="text-gray-600">{{trans('forms.lable_ar')}}</span>)</label>

                                <input type="text" name="title_ar" class="form-control mb-2"
                                       placeholder="العنـــوان" value="" required autocomplete/>
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
                                    $status_array = array('1' => 'Active', '0' => 'NotActive')
                                    ?>
                                    <option value=" ">{{trans('maindata.Select')}}</option>
                                    @foreach($status_array as $key=>$value)
                                        <option value="{{ $key }}">{{ $value }}
                                        </option>
                                    @endforeach


                                </select>
                                <!--end::Select2-->
                            </div>
                            <!--end::Main column-->

                            <!--end::Main column-->
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


    <div id="kt_app_content_container" class="app-container container-xxl">
        <!--begin::Category-->
        <div class="card card-flush">


            <div class="card-body pt-0">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
            @endif
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
                    @foreach  ($main as $x)
                        <tr>
                            <td>{{$i++}}</td>

                            <td>{{$x->title}}</td>

                            <td>
                                {{-- @if (isset($x->typedata)&&(!empty($x->typedata)))
                                {{$x->typedata->name}}
                                @endif --}}

                                {{optional($x->typedata)->title}}
                            </td>
                            {{-- {{$x->status_array}} --}}
                            <td>{{$x->status}}</td>

                            <!--begin::Action=-->
                            <td class="text-end">

                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{route('admin.hr.mainsetting.edit', $x->id)}}"

                                       data-bs-toggle="modal" data-bs-target="#exampleModal{{$x->id}}"
                                       class="btn btn-sm btn-light-warning  btn-icon-warning btn-text-warning"><i
                                            class="fas fa-pencil"></i></a>


                                    <a href="{{route('admin.hr.mainsetting.delete', $x->id)}}"
                                       class="btn btn-sm btn-light-danger   btn-text-danger btn-icon-danger"><i
                                            class="fas fa-trash"></i></a>

                                </div>
                            </td>
                            <!--end::Action=-->
                        </tr>

                    @endforeach

                    </tbody>

                </table>
                <!--end::Table-->

            @foreach  ($main as $x)
                <!-- Modal 1-->
                    <div class="modal fade" id="exampleModal{{$x->id}}" tabindex="-1"
                         aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <!--begin::Formmmmm-->
                            <form action="{{route('admin.hr.mainsetting.update',$x->id)}}" method="POST"
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
                                                <label class="required form-label">{{trans('Hr_setting.Type')}}</label>

                                                <!--begin::Select2-->
                                                <select
                                                    class="form-select mb-2 @error('type_code') is-invalid @enderror"
                                                    onchange="/*set_status()*/"
                                                    data-control="select2" data-hide-search="false"
                                                    data-placeholder="Select an option"
                                                    id="type_code" name="type_code">

                                                    <option value=" ">{{trans('maindata.Select')}}</option>
                                                    @foreach($type as $row)
                                                        <option value="{{ $row->code}}"
                                                        @if ($row->code==$x->type_code)
                                                            {{'selected'}}
                                                            @endif
                                                        >{{ $row->title}}</option>
                                                    @endforeach
                                                </select>
                                                <!--end::Select2-->
                                            </div>
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
                                                    <option value=" ">{{trans('maindata.Select')}}</option>
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


                                        <!--end::Button-->

                                            <!--end::Main column-->
                                        </div>
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

    </script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\hr\setting\mainsettinRequest', '.my-form'); !!}


@endsection
