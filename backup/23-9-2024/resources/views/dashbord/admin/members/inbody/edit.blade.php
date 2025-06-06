@extends('dashbord.layouts.master')
@section('toolbar')
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{trans('sub.members')}}</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="{{ route('admin.dashboard') }}"
                                                          class="text-muted text-hover-primary">{{trans('Toolbar.home')}}</a>
                </li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">{{trans('Toolbar.members')}}</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">{{trans('sub.members_inbody')}}</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">{{trans('sub.edit_inbody')}}</li>
            </ul>
        </div>

        {{--add button --}}
        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <div class="d-flex">
                <a class="btn btn-icon btn-sm btn-primary flex-shrink-0 ms-4"
                   href="{{route('admin.Members-Inbody.index')}}">
                {{--                    <i class="bi bi-arrow-clockwise ">{{trans('sub.back')}}</i>--}}
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
        </div>
    </div>



@endsection
@section('content')



    <div id="kt_app_content" class="app-content flex-column-fluid">
        <div id="kt_app_content_container" class="t_container">
            <div class="card shadow-sm ">
                <div class="card-header">
                    <h3 class="card-title"></i> {{trans('sub.edit_inbody')}}</h3>

                </div>

                <form id="save_form" method="post" action="{{ route('admin.Members-Inbody.update',$one_data->id) }}"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PATCH')
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>

                        @endif

                        <div class="row" style="margin-top: 20px">
                            <div class="col-md-4">
                                <label class="required fs-6 fw-semibold mb-2">{{trans('members.member_name')}}</label>
                                <select class="form-control "
                                        name="member_id" id="member_id">
                                    <option>{{trans('forms.select')}}</option>

                                    @foreach($members as $key)
                                        <option value="{{$key->id}}" @if ($one_data->member_id ==old('member_id',$key->id)) selected @endif> {{$key->member_name}}</option>
                                    @endforeach

                                </select>
                            </div>
                            <div class="col-md-4">
                                <label class="required fs-6 fw-semibold mb-2">{{trans('members.date')}}</label>
                                <input type="date" class="form-control form-control-solid"
                                       value="{{$one_data->date}}" name="date"
                                       placeholder="Pick date rage" id="date"/>
                            </div>
                            <div class="col-md-4">
                                <label class="required fs-6 fw-semibold mb-2">{{trans('members.height')}}</label>
                                <input type="number" step="any" class="form-control form-control-solid"
                                       name="height" value="{{old('height',$one_data->muscle_mass_percentage)}}"
                                       id="height"/>
                            </div>
                        </div>

                        <div class="row" style="margin-top: 10px">
                            <div class="col-md-3">
                                <label class="required fs-6 fw-semibold mb-2">{{trans('members.weight')}}</label>
                                <input type="number" step="any" class="form-control form-control-solid"
                                       name="weight" value="{{old('weight',$one_data->weight)}}"
                                       id="weight"/>
                            </div>
                            <div class="col-md-3">
                                <label
                                    class="required fs-6 fw-semibold mb-2">{{trans('members.fat_percentage')}}</label>
                                <input type="number" step="any" class="form-control form-control-solid"
                                       name="fat_percentage" value="{{old('fat_percentage',$one_data->fat_percentage)}}"
                                       id="fat_percentage"/>
                            </div>

                            <div class="col-md-3">
                                <label
                                    class="required fs-6 fw-semibold mb-2">{{trans('members.muscle_mass_percentage')}}</label>
                                <input type="number" step="any" class="form-control form-control-solid"
                                       name="muscle_mass_percentage" value="{{old('muscle_mass_percentage',$one_data->muscle_mass_percentage)}}"
                                       id="muscle_mass_percentage"/>
                            </div>

                            <div class="col-md-3">

                                <label class="required form-label">{{trans('members.body_status')}}</label>

                                <input type="text"
                                       class="form-control mb-2" id="body_status"
                                       placeholder="{{trans('members.body_status')}}" name="body_status"
                                       value="{{old('body_status',$one_data->body_status)}}"/>

                            </div>

                        </div>




                        <div class="d-flex justify-content-end">
                            <button type="submit" id="" class="btn btn-primary">
                                <span class="indicator-label">{{trans('forms.save_btn')}}</span>
                                <span class="indicator-progress">Please wait...
							<span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                        </span>
                            </button>
                        </div>

                    </div>




                </form>

            </div>


        </div>
    </div>










@stop
@section('js')




    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\Admin\Members\SaveInbodyRequest', '#save_form') !!}








@endsection
