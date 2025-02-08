<div class="" style="margin-top: 30px">
    @if(isset($revenues_data) && !empty($revenues_data))
        <table id="table" class="example table table-bordered responsive nowrap text-center" cellspacing="0"
               width="70%">
            <thead>
            <tr class="greentd" style="background-color: lightgrey" >
                <th>{{trans('employees.hash') }}</th>
                <th>{{ trans('employees.amount') }}</th>
                <th>{{ trans('employees.invoice') }}</th>
                <th>{{ trans('employees.client') }}</th>
                <th>{{ trans('employees.received_at') }}</th>
                <th>{{ trans('employees.notes') }}</th>
                {{-- <th>{{ trans('employees.actions') }}</th> --}}
            </tr>
            </thead>
            <tbody>
            @php
                $x = 1;
            @endphp
            @foreach ($revenues_data as $revenue)
                <tr>
                    <td>{{ $x++ }}</td>
                    <td>{{ $revenue->amount }}</td>
                    <td>
                        <a href="javascript:void(0)" onclick="invoice_details('{{ route('admin.invoice_details', $revenue->id) }}')"
                            class="text-primary fw-bold text-decoration-underline" title="{{ trans('invoices.view_details') }}">
                            INV-{{ $revenue->invoice ? $revenue->invoice->invoice_number : 'N/A' }}
                        </a>
                    </td>
                    <td>{{ $revenue->client ? $revenue->client->name : 'N/A' }}</td>
                    <td class="fnt_center_black">{{ \Illuminate\Support\Carbon::parse($revenue->received_at)->format('Y-m-d') }}</td>
                    <td class="fnt_center_blue">{{ $revenue->notes ?? 'N\A' }}</td>
                    {{-- <td>
                        <div class="btn-group">
                            <a href="{{ route('admin.employee_delete_revenue', $revenue->id) }}" onclick="return confirm('Are You Sure To Delete?')" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </a>
                        </div>

                    </td> --}}
                </tr>
            @endforeach
            </tbody>
        </table>
    @endif
</div>

@section('js')
<script>
    function invoice_details(url) {
        $.get(url, function(data) {
            $('#result_info').html(data);
            $('#modaldetails').modal('show');
        });
    }
</script>
@endsection



