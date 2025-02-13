{{-- <div class="card shadow  bg-white rounded">
    <div class="card-header" style="background-color: #f8f9fa;">
        <h3 class="card-title"><i class="fas fa-text-width"></i> <?= trans('employees.employee_details') ?></h3>
    </div>
    <div class="card-body" style="padding: 20px !important;"> --}}
<style>
    body {
        background-color: #f8f9fa;
        font-family: 'Inter', sans-serif;
        margin: 0;
        padding: 0;
    }

    .profile-card {
        max-width: 400px;
        margin: 50px auto;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .profile-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 16px 32px rgba(0, 0, 0, 0.2);
    }

    .profile-header {
        background: linear-gradient(135deg, #667eea, #764ba2);
        padding: 30px;
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        color: #fff;
    }

    .profile-img {
        width: 100px;
        height: 100px;
        border-radius: 50%;
        object-fit: cover;
        margin-bottom: 15px;
        border: 4px solid rgba(255, 255, 255, 0.2);
    }

    .profile-name {
        font-size: 1.75rem;
        font-weight: 700;
        margin: 0;
    }

    .profile-title {
        font-size: 1rem;
        color: rgba(255, 255, 255, 0.9);
        margin: 5px 0;
    }

    .profile-location {
        font-size: 0.9rem;
        color: rgba(255, 255, 255, 0.8);
    }

    .profile-stats {
        display: flex;
        justify-content: space-around;
        padding: 20px;
        background-color: #f8f9fa;
        border-top: 1px solid #e0e0e0;
    }

    .stat-item {
        text-align: center;
    }

    .stat-number {
        font-size: 1.25rem;
        font-weight: 700;
        color: #333;
    }

    .stat-label {
        font-size: 0.85rem;
        color: #666;
    }

    .profile-details {
        padding: 20px;
    }

    .detail-item {
        margin-bottom: 15px;
    }

    .detail-label {
        font-size: 0.9rem;
        color: #666;
        margin-bottom: 5px;
    }

    .detail-value {
        font-size: 1rem;
        font-weight: 600;
        color: #333;
    }

    .social-links {
        text-align: center;
        padding: 20px;
        background-color: #f8f9fa;
        border-top: 1px solid #e0e0e0;
    }

    .social-icons {
        display: flex;
        justify-content: center;
        gap: 20px;
    }

    .social-icon {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        color: #555;
        text-decoration: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
        position: relative;
    }

    .social-icon:hover {
        background-color: #667eea;
        color: #fff;
        transform: translateY(-3px);
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.15);
    }

    .social-icon .tooltip {
        position: absolute;
        top: -30px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #333;
        color: #fff;
        font-size: 0.75rem;
        padding: 4px 8px;
        border-radius: 4px;
        opacity: 0;
        visibility: hidden;
        transition: opacity 0.3s ease, visibility 0.3s ease;
    }

    .social-icon:hover .tooltip {
        opacity: 1;
        visibility: visible;
    }



    .table {
        width: 100%;
        margin-bottom: 20px;
        border-collapse: collapse;
    }

    .table-bordered {
        border: 1px solid #e0e0e0;
    }

    .table-sm td,
    .table-sm th {
        padding: 12px;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.03);
    }

    .class_label {
        font-size: 0.9rem;
        color: #666;
    }

    .class_result {
        font-size: 1rem;
        font-weight: 600;
        color: #333;
    }
</style>

<div class="profile-card" style="margin-top: -20px">
    <div class="profile-header">
        <img src="{{ $all_data->profile_picture && file_exists(public_path('images/' . $all_data->profile_picture)) ? asset('images/' . $all_data->profile_picture) : asset('images/default-user-icon.png') }}"
            alt="{{ $all_data->name }}" class="profile-img">
        <h2 class="profile-name">{{ $all_data->first_name . ' ' . $all_data->last_name }}</h2>
        <p class="profile-location">
            {{ $all_data->address }}
        </p>
    </div>
    <table class="table table-bordered table-sm table-striped">
        <tbody>
            <tr>
                <td class="class_label" style="width: 25%"><?= trans('employees.name') ?></td>
                <td class="class_result"><?php echo $all_data->first_name . ' ' . $all_data->last_name; ?></td>
            </tr>
            <tr>
                <td class="class_label" style="width: 25%"><?= trans('employees.employee_code') ?></td>
                <td class="class_result"><?php echo $all_data->emp_code; ?></td>
            </tr>
            {{-- <tr>
                <td class="class_label" style="width: 25%"><?= trans('employees.email') ?></td>
                <td class="class_result"><?php echo $all_data->email; ?></td>
            </tr>
            <tr>
                <td class="class_label" style="width: 25%"><?= trans('employees.national_id') ?></td>
                <td class="class_result"><?php echo $all_data->national_id; ?></td>
            </tr>
            <tr>
                <td class="class_label"><?= trans('employees.gender') ?></td>
                <td class="class_result"><?php echo trans('employees.' . $all_data->gender); ?></td>
            </tr> --}}
            <tr>
                <td class="class_label"><?= trans('employees.position') ?></td>
                <td class="class_result"><?php echo $all_data->position; ?></td>
            </tr>
            <tr>
                <td class="class_label"><?= trans('employees.salary') ?></td>
                <td class="class_result"><?php echo $all_data->salary; ?></td>
            </tr>
            <tr>
                <td class="class_label"><?= trans('employees.details') ?></td>
                <td class="class_result"><a class="btn btn-primary" role="button" data-bs-toggle="modal"
                        data-bs-target="#modaldetails" onclick="employee_details({{ $all_data->id }})"><i
                            class="fa-solid fa-list"></i>{{ trans('employees.detail_employee') }}</a></td>
            </tr>

        </tbody>
    </table>
</div>
</div>


<div class="modal fade" tabindex="-1" id="modaldetails">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h3 class="modal-title"><?= trans('employees.employee_details') ?></h3>
                <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal"
                    aria-label="Close">
                    <i class="ki-duotone ki-cross fs-1">&times;</i>
                </div>

            </div>

            <div id="result_info">

            </div>

        </div>
    </div>
</div>
@section('js')
    <script>
        function employee_details(id) {
            $.ajax({
                url: "{{ route('admin.employee_details', ['id' => '__id__']) }}".replace('__id__', id),
                type: "get",
                dataType: "html",
                success: function(html) {

                    $('#result_info').html(html);
                },
            });
        }
    </script>
@endsection
