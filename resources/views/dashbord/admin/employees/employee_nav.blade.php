<div class="col-md-12">
    <div class="card" style="margin-right: 20px;margin-left: 20px;margin-top: 5px" >
        <div class="card-body" style="padding: 10px">



            <div class="row">
                <!-- Left column for the remaining buttons -->
                <div class="col-md-11">
                <a href="{{ route('admin.employee_files', $all_data->id) }}" class="btn btn-success p-2">
                    <i class="bi bi-folder"></i> <?= trans('employees.employee_files') ?>
                </a>
                <a href="{{ route('admin.employee_masrofat', $all_data->id) }}" class="btn btn-danger p-2">
                    <i class="bi bi-cash-stack"></i> <?= trans('employees.employee_masrofat') ?>
                </a>
                <a href="{{ route('admin.employee_revenues', $all_data->id) }}" class="btn btn-primary p-2">
                    <i class="bi bi-cash-coin"></i> <?= trans('employees.employee_revenues') ?>
                </a><!-- Changed icon -->

                </div>

                <div class="col-md-1  text-end">
                    <a class="btn btn-warning" href="{{ route('admin.employee_data') }}">
                        <i class="bi bi-arrow-repeat fs-3"></i>{{trans('employees.back')}}
                    </a>
                </div>
            </div>

        </div>
    </div>
</div>
