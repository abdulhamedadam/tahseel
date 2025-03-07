@extends('dashbord.layouts.master')

@section('css')
    <style>
        .account-row.level-1 {
            background-color: #f8f9fa;
        }

        .account-row.level-2 {
            background-color: #e9ecef;
        }
    </style>
@endsection
@section('toolbar')
    <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
        @php
            $title = trans('accounts.accounts');
            $breadcrumbs = [
                ['label' => trans('Toolbar.home'), 'link' => ''],
                ['label' => trans('Toolbar.accounts'), 'link' => ''],
                ['label' => trans('accounts.accounts_table'), 'link' => ''],
            ];

            PageTitle($title, $breadcrumbs);
        @endphp

        <div class="d-flex align-items-center gap-2 gap-lg-3">
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalAccounts">
                {{ trans('accounts.add_account') }}
            </button>
        </div>
    </div>
@endsection

@section('content')
    <div id="kt_app_content_container" class="app-container container-xxxl">
        <div class="card shadow-sm" style="border-top: 3px solid #007bff;">
            <div class="card-body">
                <h2>{{ trans('accounts.accounts') }}</h2>

                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th>{{ trans('accounts.ID') }}</th>
                            <th>{{ trans('accounts.account_name') }}</th>
                            <th>{{ trans('accounts.parent') }}</th>
                            <th>{{ trans('accounts.level') }}</th>
                            <th>{{ trans('accounts.assigned_user') }}</th>
                            <th>{{ trans('accounts.created_by') }}</th>
                            <th>{{ trans('accounts.actions') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($accounts as $account)
                            @include('dashbord.accounts.partials.account_row', [
                                'account' => $account,
                            ])
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modalAccounts">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3 class="modal-title">{{ trans('accounts.add_account') }}</h3>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('admin.add_account') }}" enctype="multipart/form-data"
                    id="accountForm">
                    @csrf
                    <input type="hidden" name="row_id" id="row_id" value="">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">{{ trans('accounts.account_name') }}</label>
                            <input type="text" class="form-control" name="name" id="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="parent_id" class="form-label">{{ trans('accounts.parent') }}</label>
                            <select name="parent_id" id="parent_id" class="form-control">
                                <option value="">{{ trans('accounts.select_account') }}</option>
                                @foreach ($accounts as $account)
                                    <option value="{{ $account->id }}">{{ $account->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">{{ trans('accounts.save') }}</button>
                        <button type="button" class="btn btn-light"
                            data-bs-dismiss="modal">{{ trans('accounts.cancel') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        function confirmDelete(clientId) {
            Swal.fire({
                title: '{{ trans('employees.confirm_delete') }}',
                text: '{{ trans('clients.delete_warning') }}',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: '{{ trans('employees.yes_delete') }}',
                cancelButtonText: '{{ trans('employees.cancel') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('delete-form-' + clientId).submit();
                }
            });
        }

        function edit_account(id) {
            $.ajax({
                url: "{{ route('admin.edit_account', ['id' => '__id__']) }}".replace('__id__', id),
                type: "get",
                dataType: "json",
                success: function(data) {
                    var allData = data.all_data;
                    $('#row_id').val(allData.id);
                    $('#name').val(allData.name);
                    $('#parent_id').val(allData.parent_id);
                },
            });
        }
    </script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js') }}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\Admin\account\SaveRequest', '#accountForm') !!}
@endsection
