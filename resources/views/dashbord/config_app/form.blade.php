@extends('dashbord.layouts.master')
@section('css')
    @notifyCss
@endsection
@section('content')
    <div id="kt_app_content" class="app-content flex-column-fluid" >
        <div class="row col-md-12">
            <div class="col-md-3">
                @include('dashbord.admin.settings.sidebar')
            </div>
            <div class="col-md-9">
                <div id="kt_app_content" class="app-content flex-column-fluid" >
                    <div id="kt_app_content_container" class="" style="padding-top: 20px" >
                        <div class="card shadow-sm" style="border-top: 3px solid #007bff;">
                            <div class="card-header">
                                <h3 class="card-title">{{ trans('settings.app_config') }}</h3>
                            </div>

                            <div class="card-body">
                                <form action="{{route('admin.save_app_config')}}" method="POST">
                                    @csrf
                                    @method('POST')

                                    <div class="row">
                                        <div class="col-md-6">
                                            <label for="phone_service" class="form-label">{{ trans('settings.phone_service') }}</label>
                                            <div class="input-group flex-nowrap">
                                                <span class="input-group-text" id="basic-addon3">{!! form_icon('phone') !!}</span>
                                                <input type="tel" class="form-control" name="phone_service" id="phone_service" value="{{ old('phone_service', $all_data['phone_service'] ?? '') }}" >
                                            </div>
                                            @error('phone_service')
                                                <span class="fv-plugins-message-container" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>

                                        <div class="col-md-6">
                                            <label for="currency" class="form-label">{{ trans('settings.currency') }}</label>
                                            <div class="input-group flex-nowrap">
                                                <span class="input-group-text" id="basic-addon3">{!! form_icon('price') !!}</span>
                                                <input type="text" class="form-control" name="currency" id="currency" value="{{ old('phone_service', $all_data['currency'] ?? '') }}" >
                                            </div>
                                            @error('currency')
                                                <span class="fv-plugins-message-container" role="alert">{{ $message }}</span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="card-footer d-flex justify-content-end">
                                        <button type="submit" class="btn btn-success">
                                            {{ trans('company.save') }}
                                        </button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection
@section('js')


    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>

@endsection
