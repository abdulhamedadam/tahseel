<div class="" style="margin-top: 30px">
    @if(isset($paid_data) && !empty($paid_data))
        <table id="table" class="example table table-bordered responsive nowrap text-center" cellspacing="0"
               width="70%">
            <thead>
            <tr class="greentd" style="background-color: lightgrey" >
                <th>{{trans('invoices.hash') }}</th>
                <th>{{ trans('invoices.invoice_number') }}</th>
                <th>{{ trans('invoices.subscription') }}</th>
                <th>{{ trans('invoices.amount') }}</th>
                <th>{{ trans('invoices.enshaa_date') }}</th>
                <th>{{ trans('invoices.due_date') }}</th>
                <th>{{ trans('invoices.status') }}</th>
                <th>{{ trans('invoices.actions') }}</th>
            </tr>
            </thead>
            <tbody>
            @php
                $x = 1;
            @endphp
            @foreach ($paid_data as $invoice)
                <tr>
                    <td>{{ $x++ }}</td>
                    <td>{{ $invoice->invoice_number }}</td>
                    <td>{{ $invoice->subscription->name }}</td>
                    <td>{{ $invoice->amount }}</td>
                    <td class="fnt_center_black">{{ \Illuminate\Support\Carbon::parse($invoice->enshaa_date)->format('Y-m-d') }}</td>
                    <td class="fnt_center_black">{{ \Illuminate\Support\Carbon::parse($invoice->due_date)->format('Y-m-d') }}</td>
                    <td>
                        <span class="badge
                            @if($invoice->status == 'paid') bg-success text-white
                            @elseif($invoice->status == 'partial') bg-warning text-dark
                            @endif
                            px-4 py-3 rounded-pill fw-bold fs-5">
                            {{ trans('invoices.' . ($invoice->status ?? 'N/A')) }}
                        </span>
                    </td>
                    <td>
                        <div class="btn-group">
                            {{-- <a href="{{ route('admin.employee_delete_invoiceat', $invoice->id) }}" onclick="return confirm('Are You Sure To Delete?')" class="btn btn-sm btn-danger">
                                <i class="bi bi-trash"></i>
                            </a> --}}
                            <a href="javascript:void(0)" onclick="invoice_details('{{ route('admin.invoice_details', $invoice->id) }}')"
                                class="btn btn-sm btn-info text-white d-flex align-items-center gap-1"
                                title="{{ trans('invoices.view_details') }}">
                                <i class="bi bi-file-earmark-text fs-5"></i> {{ trans('invoices.view_details') }}
                            </a>
                        </div>
                    </td>
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





