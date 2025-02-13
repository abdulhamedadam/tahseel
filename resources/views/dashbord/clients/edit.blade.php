@extends('dashbord.layouts.master')
@section('toolbar')
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        @php
            $title = trans('client.clients');
            $breadcrumbs = [
                ['label' => trans('Toolbar.home'), 'link' => route('admin.clients.index')],
                ['label' => trans('Toolbar.clients'), 'link' => ''],
                ['label' => trans('client.clients_table'), 'link' => ''],
            ];

            PageTitle($title, $breadcrumbs);
        @endphp


        <div class="d-flex align-items-center gap-2 gap-lg-3">

            {{ BackButton(route('admin.clients.index')) }}

        </div>
    </div>

@endsection
@section('content')

    <div id="kt_app_content_container" class="app-container container-xxxl">

        <div class="card shadow-sm" style="border-top: 3px solid #007bff;">
            @php
                generateCardHeader('clients.add_client', 'admin.clients.index', ' ');
            @endphp


            <form action="{{ route('admin.clients.update', $all_data->id) }}" method="post" enctype="multipart/form-data"
                id="store_form">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="col-md-12 row" style="margin-top: 10px">
                        <div class="col-md-3" style="margin-top: 10px">
                            <label for="emp_code" class="form-label">{{ trans('clients.client_code') }}</label>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" id="basic-addon3">{!! form_icon('text') !!}</span>
                                <input type="text" class="form-control" name="client_code" id="client_code"
                                    value="{{ $all_data->client_code }}" readonly>
                            </div>
                            @error('client_code')
                                <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-3" style="margin-top: 10px">
                            <label for="first_name" class="form-label">{{ trans('clients.name') }}</label>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" id="basic-addon3">{!! form_icon('text') !!}</span>
                                <input type="text" class="form-control" name="name" id="name"
                                    value="{{ old('name', $all_data->name) }}">
                            </div>
                            @error('name')
                                <span class="fv-plugins-message-container " role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-3" style="margin-top: 10px">
                            <label for="last_name" class="form-label">{{ trans('clients.phone') }}</label>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" id="basic-addon3">{!! form_icon('phone') !!}</span>
                                <input type="phone" class="form-control" name="phone" id="phone"
                                    value="{{ old('phone', $all_data->phone) }}">
                            </div>
                            @error('phone')
                                <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- <div class="col-md-3" style="margin-top: 10px">
                            <label for="email" class="form-label">{{ trans('clients.email') }}</label>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" id="basic-addon3">{!! form_icon('email') !!}</span>
                                <input type="text" class="form-control" name="email" id="email"
                                    value="{{ old('email', $all_data->email) }}">
                            </div>
                            @error('email')
                                <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                            @enderror
                        </div> --}}

                        <div class="col-md-3" style="margin-top: 10px">
                            <label for="user" class="form-label">{{ trans('clients.user') }}</label>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" id="basic-addon3">{!! form_icon('text') !!}</span>
                                <input type="text" class="form-control" name="user" id="user"
                                        value="{{ old('user', $all_data->user) }}">
                            </div>
                            @error('user')
                            <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                    {{-- </div>

                    <div class="col-md-12 row" style="margin-top: 10px"> --}}
                        <div class="col-md-3" style="margin-top: 10px">
                            <label for="national_id" class="form-label">{{ trans('clients.address1') }}</label>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" id="basic-addon3">{!! form_icon('address') !!}</span>
                                <input type="text" class="form-control" name="address1" id="address1"
                                    value="{{ old('address1', $all_data->address1) }}">
                            </div>
                            @error('address1')
                                <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        {{-- <div class="col-md-3" style="margin-top: 10px">
                            <label for="national_id" class="form-label">{{ trans('clients.address2') }}</label>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" id="basic-addon3">{!! form_icon('address') !!}</span>
                                <input type="text" class="form-control" name="address2" id="address2"
                                    value="{{ old('address2', $all_data->address2) }}">
                            </div>
                            @error('address2')
                                <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                            @enderror
                        </div> --}}
                        <div class="col-md-3" style="margin-top: 10px">
                            <label for="box_switch" class="form-label">{{ trans('clients.box_switch') }}</label>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" id="basic-addon3">{!! form_icon('text') !!}</span>
                                <input type="text" class="form-control" name="box_switch" id="box_switch"
                                        value="{{ old('box_switch', $all_data->box_switch) }}">
                            </div>
                            @error('box_switch')
                            <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-3" style="margin-top: 10px">
                            <label for="client_type" class="form-label">{{ trans('clients.client_type') }}</label>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" id="basic-addon4">{!! form_icon('select2') !!}</span>
                                <select class="form-select" name="client_type" id="client_type">
                                    <option value="satellite" {{ old('client_type', $all_data->client_type) == 'satellite' ? 'selected' : '' }}>
                                        {{ trans('clients.satellite') }}
                                    </option>
                                    <option value="internet" {{ old('client_type', $all_data->client_type) == 'internet' ? 'selected' : '' }}>
                                        {{ trans('clients.internet') }}
                                    </option>
                                </select>
                            </div>
                            @error('client_type')<span class="invalid-feedback d-block">{{ $message }}</span>@enderror
                        </div>

                        {{-- <div class="col-md-3" style="margin-top: 10px">
                            <label for="whatsapp_num" class="form-label">{{ trans('clients.commercial_register') }}</label>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" id="basic-addon3">{!! form_icon('number') !!}</span>
                                <input type="number" class="form-control" name="commercial_register"
                                    id="commercial_register"
                                    value="{{ old('commercial_register', $all_data->commercial_register) }}">
                            </div>
                            @error('commercial_register')
                                <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                            @enderror
                        </div> --}}

                        <div class="col-md-3" style="margin-top: 10px">
                            <label for="basic-url"class="form-label">{{ trans('clients.subscription') }}</label>
                            <div class="input-group flex-nowrap ">
                                <span class="input-group-text" id="basic-addon3">{!! form_icon('select1') !!}</span>
                                <div class="overflow-hidden flex-grow-1">
                                    <select class="form-select rounded-start-0" name="subscription_id"
                                        id="subscription_id" onchange="get_price(this.value)" data-placeholder="{{ trans('clients.select') }}">
                                        <option value="">{{ trans('clients.select') }}</option>
                                        @foreach ($subscriptions as $item)
                                            <option value="{{ $item->id }}"
                                                {{ old('subscription_id', $all_data->subscription_id) == $item->id ? 'selected' : '' }}>
                                                {{ $item->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            @error('subscription_id')
                                <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-3" style="margin-top: 10px">
                            <label for="price" class="form-label">{{ trans('clients.price') }}</label>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" id="basic-addon3">{!! form_icon('price') !!}</span>
                                <input type="text" class="form-control" id="price" name="price" readonly>
                            </div>
                        </div>
                    {{-- </div>

                    <div class="col-md-12 row" style="margin-top: 10px"> --}}
                        <div class="col-md-3" style="margin-top: 10px">
                            <label for="subscription_date"
                                class="form-label">{{ trans('clients.subscription_date') }}</label>
                            <div class="input-group flex-nowrap ">
                                <span class="input-group-text" id="basic-addon3">{!! form_icon('date') !!}</span>
                                <input type="date" class="form-control" name="subscription_date"
                                    id="subscription_date"
                                    value="{{ old('subscription_date', $all_data->subscription_date) }}">
                                @error('subscription_date')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3" style="margin-top: 10px">
                            <label for="start_date" class="form-label">{{ trans('clients.start_date') }}</label>
                            <div class="input-group flex-nowrap ">
                                <span class="input-group-text" id="basic-addon3">{!! form_icon('date') !!}</span>
                                <input type="date" class="form-control" name="start_date" id="start_date"
                                    value="{{ old('start_date', $all_data->start_date) }}">
                                @error('start_date')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="col-md-3" style="margin-top: 10px">
                            <label for="is_active" class="form-label">{{ trans('clients.status') }}</label>
                            <div class="input-group flex-nowrap ">
                                <span class="input-group-text" id="basic-addon3">{!! form_icon('select1') !!}</span>
                                <select class="form-select" name="is_active" id="is_active">
                                    <option value="1"
                                        {{ old('is_active', $all_data->is_active) == 1 ? 'selected' : '' }}>
                                        {{ trans('clients.active') }}</option>
                                    <option value="0"
                                        {{ old('is_active', $all_data->is_active) == 0 ? 'selected' : '' }}>
                                        {{ trans('clients.inactive') }}</option>
                                </select>
                            </div>
                            @error('is_active')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-md-3" style="margin-top: 10px">
                            <label for="phone" class="form-label">{{ trans('clients.image') }}</label>
                            <div class="input-group flex-nowrap">
                                <span class="input-group-text" id="basic-addon3">{!! form_icon('image') !!}</span>
                                <input type="file" class="form-control" name="image" id="image"
                                    value="{{ old('image') }}">
                            </div>
                            @error('image')
                                <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                            @enderror

                            @if ($all_data->image)
                                <div class="mt-2">
                                    <img src="{{ asset('images/' . $all_data->image) }}" alt="Current Photo"
                                        class="img-thumbnail" width="100">
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label for="area" class="form-label">{{ trans('clients.notes') }}</label>
                        <div class="input-group flex-nowrap">
                            <span class="input-group-text" id="basic-addon3">
                                {!! form_icon('text') !!}
                            </span>
                            <textarea class="form-control @error('notes') is-invalid @enderror" id="notes" name="notes"
                                aria-describedby="basic-addon3" rows="3">{{ old('notes', $all_data->notes) }}</textarea>
                        </div>
                        @error('notes')
                            <span class="invalid-feedback d-block" role="alert">{{ $message }}</span>
                        @enderror
                    </div>


                </div>
                <div class="card-footer d-flex justify-content-end">
                    <button type="submit" class="btn btn-success">
                        {{ trans('clients.save') }}
                    </button>
                </div>
            </form>
        </div>

    </div>












@stop
@section('js')
    <script>
        $(document).ready(function() {
            setTimeout(function() {
                $("#subscription_id").trigger("change");
            }, 300);
        });
    </script>
    <script>
        function get_price(id) {
            $.ajax({
                url: "{{ route('admin.get_price', ['id' => '__id__']) }}".replace('__id__', id),
                type: "get",
                dataType: "json",
                success: function(data) {
                    $('#price').val(data.price || '<?= old('price') ?>');
                },
                error: function(xhr, status, error) {
                    console.error("Error fetching price:", status, error);
                    $('#price').val('0');
                }
            });
        }
    </script>
    <script>
        function showSuccessMessage(message) {
            $('#success_message').text(message).removeClass('d-none').show();
            setTimeout(function() {
                $('#success_message').fadeOut().addClass('d-none');
            }, 8000);
        }
    </script>



    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>

    {!! JsValidator::formRequest('App\Http\Requests\Admin\clients\SaveRequests', '#store_form1') !!}



@endsection
