@extends('dashbord.layouts.master')
@section('toolbar')
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
            <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">{{trans('sub.members')}}</h1>
            <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                <li class="breadcrumb-item text-muted"><a href="{{ route('admin.Members.index') }}" class="text-muted text-hover-primary">{{trans('Toolbar.home')}}</a></li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">{{trans('Toolbar.members')}}</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">{{trans('members.members')}}</li>
                <li class="breadcrumb-item"><span class="bullet bg-gray-400 w-5px h-2px"></span></li>
                <li class="breadcrumb-item text-muted">{{trans('members.expire_main_subscription')}}</li>
            </ul>
        </div>

    </div>

@endsection
@section('content')

    <div id="kt_app_content_container" class="app-container container-xxxl">


        <div class="card card-flush">
            <div class="card-header">
                <h3 class="card-title"></i> {{trans('members.expire_main_subscription')}}</h3>

            </div>

            <div class="card-body pt-0">


                <table class="table align-middle table-row-dashed fs-6 gy-3"
                       id="table">
                    <thead>
                    <tr class="fw-semibold fs-6 text-gray-800">
                        <th>{{trans('event.ID')}}</th>
                        <th>{{trans('members.member_name')}}</th>
                        <th>{{trans('members.subscription')}}</th>
                        <th>{{trans('members.duration')}}</th>
                        <th>{{trans('members.remain_session')}}</th>
                        <th>{{trans('members.end_date')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @php $x=1; @endphp
                    @foreach($all_data as $data)
                        <tr>
                            <td>{{ $x++ }}</td>
                            <td>{{ $data->member_subscription->member ? $data->member_subscription->member->member_name : 'N/A'}}</td>
                            <td>{{ $data->special_subscriptions->name }}</td>
                            <td>{{ $data->special_subscriptions->duration }} {{ trans('members.session') }}</td>
                            <td>{{ $data->special_subscriptions->duration }} {{ trans('members.session') }}</td>
                            <td>{{ \Carbon\Carbon::parse($data->end_date)->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach

                    @foreach($filtered_data as $data)
                        <tr>
                            <td>{{ $x++ }}</td>
                            <td>{{ $data->member ? $data->member->member_name : 'N/A'}}</td>
                            <td>{{ $data->special_subscriptions->name }}</td>
                            <td>{{ $data->special_subscriptions->duration }} {{ trans('members.session') }}</td>
                            <td>{{ ($data->special_subscriptions->duration - get_session_attendance($data->member_subscription->member_id,$data->id)) }} {{ trans('members.session') }}</td>
                            <td>{{ \Carbon\Carbon::parse($data->end_date)->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>

            </div>
        </div>

    </div>












@stop


